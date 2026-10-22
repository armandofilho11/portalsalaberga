<?php
// Verificar sessão e tipo de usuário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirecionar se não autenticado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Não autenticado']);
    exit;
}

require_once(__DIR__ . '/../models/demanda_model.php');
require_once(__DIR__ . '/../models/autorizacao.php');

$demandaModel = new demanda_model();
$action = isset($_GET['action']) ? $_GET['action'] : '';

$tiposAdministradores = ['administrador', 'master'];
if (!in_array($_SESSION['tipo'] ?? '', $tiposAdministradores, true) && !in_array($action, ['demandas_por_situacao', 'demandas_por_categoria'], true)) {
    http_response_code(403);
    echo json_encode(['erro' => 'Acesso negado']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

try {
    switch ($action) {
        case 'demandas_por_situacao':
            echo json_encode(obterDemandasPorSituacao());
            break;
        
        case 'demandas_por_categoria':
            echo json_encode(obterDemandasPorCategoria());
            break;
        
        /*
        // Rotas antigas sem consumidores internos. Mantidas comentadas para
        // evitar remover uma possível integração externa sem aviso.
        case 'metricas_gerais':
            echo json_encode(obterMetricasGerais());
            break;
        
        case 'gerar_relatorio_personalizado':
            $dados = json_decode(file_get_contents('php://input'), true);
            echo json_encode(gerarRelatorioPersonalizado($dados));
            break;
        
        case 'gerar_relatorio_situacao':
            $dados = json_decode(file_get_contents('php://input'), true);
            echo json_encode(gerarRelatorioSituacao($dados));
            break;
        */
        
        default:
            http_response_code(400);
            echo json_encode(['erro' => 'Action não especificada']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro no servidor: ' . $e->getMessage()]);
}

function obterDemandasPorSituacao() {
    require_once(__DIR__ . '/../config/connect.php');
    $conn = (new connect())->getConnection();
    [$filtroProjetos, $paramsProjetos] = obterFiltroProjetosRelatorio();
    
    // Buscar dados baseado no status
    $stmt = $conn->prepare("
        SELECT 
            CASE 
                WHEN status = 'finalizada' THEN 'finalizada'
                WHEN status = 'em_andamento' THEN 'andamento'
                ELSE 'aberta'
            END as situacao,
            COUNT(*) as total
        FROM demandas{$filtroProjetos}
        GROUP BY status
        ORDER BY total DESC
    ");
    $stmt->execute($paramsProjetos);
    
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mapeamento de situação para labels e cores
    $mapa_situacao = [
        'finalizada' => ['label' => 'Finalizadas', 'cor' => '#9ca3af'],
        'andamento' => ['label' => 'Em andamento', 'cor' => '#FFA500'],
        'aberta' => ['label' => 'Em aberto', 'cor' => '#007A33']
    ];
    
    $total_geral = 0;
    $dados_formatados = [];
    
    foreach ($resultado as $row) {
        $total_geral += $row['total'];
        $situacao = strtolower($row['situacao']);
        $label = $mapa_situacao[$situacao]['label'] ?? ucfirst($situacao);
        $cor = $mapa_situacao[$situacao]['cor'] ?? '#999999';
        
        $dados_formatados[] = [
            'situacao' => $situacao,
            'label' => $label,
            'total' => (int)$row['total'],
            'cor' => $cor
        ];
    }
    
    // Garantir que todos os status apareçam na ordem desejada
    $status_esperados = ['aberta', 'andamento', 'finalizada'];
    $status_presentes = array_map(function($d) { return $d['situacao']; }, $dados_formatados);
    
    foreach ($status_esperados as $status) {
        if (!in_array($status, $status_presentes)) {
            $dados_formatados[] = [
                'situacao' => $status,
                'label' => $mapa_situacao[$status]['label'] ?? ucfirst($status),
                'total' => 0,
                'cor' => $mapa_situacao[$status]['cor'] ?? '#999999'
            ];
        }
    }

    usort($dados_formatados, function ($a, $b) use ($status_esperados) {
        $ordemA = array_search($a['situacao'], $status_esperados, true);
        $ordemB = array_search($b['situacao'], $status_esperados, true);

        if ($ordemA === false) $ordemA = PHP_INT_MAX;
        if ($ordemB === false) $ordemB = PHP_INT_MAX;

        return $ordemA <=> $ordemB;
    });
    
    return [
        'sucesso' => true,
        'dados' => $dados_formatados,
        'total' => $total_geral
    ];
}

function formatarNomeCategoria($categoria) {
    // Converter underscores em espaços e capitalizar cada palavra
    $formatada = str_replace('_', ' ', $categoria);
    $formatada = ucwords(strtolower($formatada));
    return $formatada;
}

function obterFiltroProjetosRelatorio(): array {
    $idProjetoSelecionado = isset($_GET['id_projeto']) && $_GET['id_projeto'] !== ''
        ? (int) $_GET['id_projeto']
        : null;

    if ($idProjetoSelecionado !== null) {
        $autorizacao = new autorizacao();
        $projetos = $autorizacao->listarProjetosDoUsuario((int) $_SESSION['id_usuario']);
        $idsPermitidos = array_map('intval', array_column($projetos, 'id'));
        if (!in_array($idProjetoSelecionado, $idsPermitidos, true)) {
            return [' WHERE 1 = 0', []];
        }
        return [' WHERE id_projeto = :relatorio_projeto_selecionado', [':relatorio_projeto_selecionado' => $idProjetoSelecionado]];
    }

    if (($_SESSION['tipo'] ?? '') === 'master') {
        return ['', []];
    }

    $autorizacao = new autorizacao();
    $projetos = $autorizacao->listarProjetosDoUsuario((int) $_SESSION['id_usuario']);
    $ids = array_values(array_filter(array_map(
        static fn (array $projeto): int => (int) ($projeto['id'] ?? 0),
        array_filter($projetos, static fn (array $projeto): bool => (int) ($projeto['ativo'] ?? 0) === 1)
    )));

    if (!$ids) {
        return [' WHERE 1 = 0', []];
    }

    $placeholders = [];
    $params = [];
    foreach ($ids as $indice => $id) {
        $placeholder = ':relatorio_projeto_' . $indice;
        $placeholders[] = $placeholder;
        $params[$placeholder] = $id;
    }

    return [' WHERE id_projeto IN (' . implode(',', $placeholders) . ')', $params];
}

function obterDemandasPorCategoria() {
    require_once(__DIR__ . '/../config/connect.php');
    $conn = (new connect())->getConnection();
    [$filtroProjetos, $paramsProjetos] = obterFiltroProjetosRelatorio();
    
    $stmt = $conn->prepare("
        SELECT 
            categoria,
            COUNT(*) as total
        FROM demandas{$filtroProjetos}
        GROUP BY categoria
        ORDER BY total DESC
        LIMIT 5
    ");
    $stmt->execute($paramsProjetos);
    
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $total_geral = 0;
    $dados = [];
    
    foreach ($resultado as $row) {
        $total_geral += $row['total'];
    }
    
    foreach ($resultado as $row) {
        $percentual = $total_geral > 0 ? ($row['total'] / $total_geral) * 100 : 0;
        
        $dados[] = [
            'categoria' => formatarNomeCategoria($row['categoria']),
            'categoria_original' => $row['categoria'],
            'total' => (int)$row['total'],
            'percentual' => round($percentual, 1)
        ];
    }
    
    return [
        'sucesso' => true,
        'dados' => $dados,
        'total' => $total_geral
    ];
}

function obterMetricasGerais() {
    require_once(__DIR__ . '/../config/connect.php');
    $conn = (new connect())->getConnection();
    
    // Total geral
    $stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM demandas");
    $stmt_total->execute();
    $total = $stmt_total->fetch()['total'];
    
    // Concluídas (status = 'finalizada')
    $stmt_concluidas = $conn->prepare("SELECT COUNT(*) as total FROM demandas WHERE status = 'finalizada'");
    $stmt_concluidas->execute();
    $concluidas = $stmt_concluidas->fetch()['total'];
    
    // Em andamento (status = 'em_andamento')
    $stmt_andamento = $conn->prepare("SELECT COUNT(*) as total FROM demandas WHERE status = 'em_andamento'");
    $stmt_andamento->execute();
    $andamento = $stmt_andamento->fetch()['total'];
    
    // Pendentes (status = 'aberta')
    $stmt_pendentes = $conn->prepare("SELECT COUNT(*) as total FROM demandas WHERE status = 'aberta'");
    $stmt_pendentes->execute();
    $pendentes = $stmt_pendentes->fetch()['total'];
    
    $pct_concluidas = $total > 0 ? ($concluidas / $total) * 100 : 0;
    $pct_andamento = $total > 0 ? ($andamento / $total) * 100 : 0;
    $pct_pendentes = $total > 0 ? ($pendentes / $total) * 100 : 0;
    
    return [
        'sucesso' => true,
        'total' => (int)$total,
        'concluidas' => (int)$concluidas,
        'andamento' => (int)$andamento,
        'pendentes' => (int)$pendentes,
        'pct_concluidas' => round($pct_concluidas, 1),
        'pct_andamento' => round($pct_andamento, 1),
        'pct_pendentes' => round($pct_pendentes, 1)
    ];
}

function gerarRelatorioSituacao($dados) {
    require_once(__DIR__ . '/../config/connect.php');
    $conn = (new connect())->getConnection();
    
    $situacao = isset($dados['situacao']) ? $dados['situacao'] : '';
    
    if (empty($situacao)) {
        return [
            'sucesso' => false,
            'erro' => 'Situação não especificada'
        ];
    }
    
    // Mapear situação para status no banco
    $status_map = [
        'aberta' => 'aberta',
        'em_andamento' => 'em_andamento',
        'finalizada' => 'finalizada'
    ];
    
    $status = isset($status_map[$situacao]) ? $status_map[$situacao] : $situacao;
    
    // Buscar demandas por situação
    $stmt = $conn->prepare("
        SELECT * FROM demandas
        WHERE status = :status
        ORDER BY data_criacao DESC
    ");
    
    $stmt->execute([':status' => $status]);
    $demandas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'sucesso' => true,
        'total' => count($demandas),
        'dados' => $demandas,
        'situacao' => $situacao,
        'arquivo_url' => null // Implementar geração de arquivo PDF/Excel se necessário
    ];
}

function gerarRelatorioPersonalizado($filtros) {
    require_once(__DIR__ . '/../config/connect.php');
    $conn = (new connect())->getConnection();
    
    $where_conditions = [];
    $params = [];
    
    // Filtro: Situação/Status
    if (!empty($filtros['situacao']) && is_array($filtros['situacao'])) {
        $placeholders = [];
        foreach ($filtros['situacao'] as $index => $sit) {
            $placeholder = ':situacao_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $sit;
        }
        $where_conditions[] = 'd.status IN (' . implode(',', $placeholders) . ')';
    }
    
    // Filtro: Prioridade
    if (!empty($filtros['prioridade']) && is_array($filtros['prioridade'])) {
        $placeholders = [];
        foreach ($filtros['prioridade'] as $index => $prio) {
            $placeholder = ':prioridade_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = $prio;
        }
        $where_conditions[] = 'd.prioridade IN (' . implode(',', $placeholders) . ')';
    }
    
    // Filtro: Categoria
    if (!empty($filtros['categoria'])) {
        $where_conditions[] = 'd.categoria = :categoria';
        $params[':categoria'] = $filtros['categoria'];
    }
    
    // Filtro: Projeto
    if (!empty($filtros['id_projeto']) && is_array($filtros['id_projeto'])) {
        $placeholders = [];
        foreach ($filtros['id_projeto'] as $index => $proj) {
            $placeholder = ':id_projeto_' . $index;
            $placeholders[] = $placeholder;
            $params[$placeholder] = (int) $proj;
        }
        $where_conditions[] = 'd.id_projeto IN (' . implode(',', $placeholders) . ')';
    } elseif (!empty($filtros['id_projeto']) && !is_array($filtros['id_projeto'])) {
        $where_conditions[] = 'd.id_projeto = :id_projeto';
        $params[':id_projeto'] = (int) $filtros['id_projeto'];
    }
    
    // Filtro: Data de início
    if (!empty($filtros['data_inicio'])) {
        $where_conditions[] = 'DATE(d.data_criacao) >= :data_inicio';
        $params[':data_inicio'] = $filtros['data_inicio'];
    }
    
    // Filtro: Data de fim
    if (!empty($filtros['data_fim'])) {
        $where_conditions[] = 'DATE(d.data_criacao) <= :data_fim';
        $params[':data_fim'] = $filtros['data_fim'];
    }
    
    // Construir query
    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
    
    $sql = "
        SELECT d.*, u.nome AS orientador_nome
        FROM demandas d
        LEFT JOIN usuarios u ON u.id = d.orientador
        $where_clause
        ORDER BY d.data_criacao DESC, d.prioridade DESC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $demandas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'sucesso' => true,
        'total' => count($demandas),
        'dados' => $demandas,
        'filtros_aplicados' => $filtros,
        'arquivo_url' => null // Implementar geração de arquivo PDF/Excel se necessário
    ];
}
?>

