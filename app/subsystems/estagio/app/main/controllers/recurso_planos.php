<?php
require_once __DIR__ . '/../auth.php';
requireLogin(['master', 'aee', 'apoio']);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Autorizacao.php';
header('Content-Type: application/json; charset=utf-8');

$autorizacao = new Autorizacao();
$usuario = currentUser();
$tipo = strtolower((string) $usuario['tipo']);
$usuarioId = (int) $usuario['id'];
$entrada = json_decode(file_get_contents('php://input'), true) ?: [];
$action = (string) ($_GET['action'] ?? $entrada['action'] ?? '');
$modulo = (string) ($_GET['modulo'] ?? $entrada['modulo'] ?? 'pei');
$tabela = $modulo === 'paee' ? 'paee' : ($modulo === 'pei' ? 'pei' : '');

function respostaPlano(array $dados, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}
function inteiro($valor): int { return filter_var($valor, FILTER_VALIDATE_INT) !== false ? (int) $valor : 0; }

try {
    if ($action === 'cadastrar_disciplina' || $action === 'associar_disciplina') {
        if (!in_array($tipo, ['master', 'aee'], true)) respostaPlano(['success' => false, 'message' => 'Sem permissão para gerenciar disciplinas.'], 403);
        $alunoId = inteiro($entrada['aluno_id'] ?? $_GET['aluno_id'] ?? 0);
        if (!$autorizacao->usuarioTemAcessoAluno($usuarioId, $alunoId)) respostaPlano(['success' => false, 'message' => 'Acesso negado ao aluno.'], 403);
        if ($action === 'cadastrar_disciplina') {
            $nome = trim((string) ($entrada['nome'] ?? ''));
            if ($nome === '' || mb_strlen($nome) > 150) respostaPlano(['success' => false, 'message' => 'Informe um nome de disciplina válido.'], 422);
            $stmt = $pdo->prepare('INSERT INTO disciplinas (nome) VALUES (:nome) ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id), ativa = 1');
            $stmt->execute([':nome' => $nome]);
            $disciplinaId = (int) $pdo->lastInsertId();
        } else {
            $disciplinaId = inteiro($entrada['disciplina_id'] ?? 0);
        }
        if (!$disciplinaId) respostaPlano(['success' => false, 'message' => 'Disciplina inválida.'], 422);
        $stmt = $pdo->prepare('INSERT INTO alunos_disciplinas (aluno_id, disciplina_id) VALUES (:aluno_id, :disciplina_id) ON DUPLICATE KEY UPDATE ativa = 1');
        $stmt->execute([':aluno_id' => $alunoId, ':disciplina_id' => $disciplinaId]);
        respostaPlano(['success' => true, 'disciplina_id' => $disciplinaId, 'message' => 'Disciplina associada com sucesso.']);
    }

    if ($action === 'catalogo') {
        if (!in_array($tipo, ['master', 'aee'], true)) respostaPlano(['success' => false, 'message' => 'Sem permissão.'], 403);
        $stmt = $pdo->query('SELECT id, nome FROM disciplinas WHERE ativa = 1 ORDER BY nome');
        respostaPlano(['success' => true, 'disciplinas' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($action === 'alunos') {
        if (!in_array($tipo, ['master', 'aee'], true)) {
            $alunoId = $autorizacao->alunoDoApoio($usuarioId);
            respostaPlano(['success' => true, 'alunos' => $alunoId ? [['id' => $alunoId]] : []]);
        }
        $stmt = $pdo->query('SELECT id, nome, matricula, turma, foto FROM alunos ORDER BY nome');
        respostaPlano(['success' => true, 'alunos' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($action === 'disciplinas') {
        $alunoId = inteiro($_GET['aluno_id'] ?? $entrada['aluno_id'] ?? 0);
        $moduloDisciplinas = (string) ($_GET['modulo'] ?? $entrada['modulo'] ?? '');
        $incluirSemRegistro = filter_var($_GET['incluir_sem_registro'] ?? $entrada['incluir_sem_registro'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if ($moduloDisciplinas !== '' && !in_array($moduloDisciplinas, ['pei', 'paee'], true)) {
            respostaPlano(['success' => false, 'message' => 'Módulo inválido.'], 422);
        }
        if ($tipo === 'apoio') {
            $alunoVinculado = $autorizacao->alunoDoApoio($usuarioId);
            if (!$alunoVinculado) respostaPlano(['success' => true, 'aluno' => null, 'modulo' => $moduloDisciplinas, 'disciplinas' => []]);
            $alunoId = $alunoVinculado;
        }
        if (!$autorizacao->usuarioTemAcessoAluno($usuarioId, $alunoId)) respostaPlano(['success' => false, 'message' => 'Acesso negado ao aluno.'], 403);
        $alunoStmt = $pdo->prepare('SELECT id, nome, matricula, turma, foto FROM alunos WHERE id = :id LIMIT 1');
        $alunoStmt->execute([':id' => $alunoId]);
        $aluno = $alunoStmt->fetch(PDO::FETCH_ASSOC);
        if (!$aluno) respostaPlano(['success' => false, 'message' => 'Aluno não encontrado.'], 404);
        $filtroModulo = '';
        if ($moduloDisciplinas !== '' && !$incluirSemRegistro) {
            $filtroModulo = $moduloDisciplinas === 'paee'
                ? " AND EXISTS (SELECT 1 FROM paee plano WHERE plano.aluno_id = ad.aluno_id AND (plano.disciplina_id = ad.disciplina_id OR (plano.disciplina_id IS NULL AND TRIM(plano.nome_documento) = d.nome)))"
                : " AND EXISTS (SELECT 1 FROM pei plano WHERE plano.aluno_id = ad.aluno_id AND plano.disciplina_id = ad.disciplina_id)";
        }
        $camposStatus = $moduloDisciplinas === 'pei'
            ? 'EXISTS(SELECT 1 FROM pei p WHERE p.aluno_id = ad.aluno_id AND p.disciplina_id = ad.disciplina_id) AS tem_pei'
            : ($moduloDisciplinas === 'paee'
                ? 'EXISTS(SELECT 1 FROM paee p WHERE p.aluno_id = ad.aluno_id AND (p.disciplina_id = ad.disciplina_id OR (p.disciplina_id IS NULL AND TRIM(p.nome_documento) = d.nome))) AS tem_paee'
                : 'EXISTS(SELECT 1 FROM pei p WHERE p.aluno_id = ad.aluno_id AND p.disciplina_id = ad.disciplina_id) AS tem_pei,
            EXISTS(SELECT 1 FROM paee p WHERE p.aluno_id = ad.aluno_id AND p.disciplina_id = ad.disciplina_id) AS tem_paee');
        $stmt = $pdo->prepare("SELECT d.id, d.nome, d.ativa, {$camposStatus}
            FROM alunos_disciplinas ad INNER JOIN disciplinas d ON d.id = ad.disciplina_id
            WHERE ad.aluno_id = :aluno_id AND ad.ativa = 1{$filtroModulo} ORDER BY d.nome");
        $stmt->execute([':aluno_id' => $alunoId]);
        respostaPlano(['success' => true, 'aluno' => $aluno, 'modulo' => $moduloDisciplinas, 'disciplinas' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($action === 'historico' || $action === 'abrir') {
        if ($tabela === '') respostaPlano(['success' => false, 'message' => 'Módulo inválido.'], 422);
        $id = inteiro($_GET['id'] ?? $entrada['id'] ?? 0);
        $alunoId = inteiro($_GET['aluno_id'] ?? $entrada['aluno_id'] ?? 0);
        $disciplinaId = inteiro($_GET['disciplina_id'] ?? $entrada['disciplina_id'] ?? 0);
        if ($action === 'abrir' && $id > 0) {
            $camposDocumento = $tabela === 'pei'
                ? 'p.id, p.aluno_id, p.disciplina_id, p.data_elaboracao, p.responsavel_preenchimento, p.objetivos_pedagogicos, p.habilidades_desenvolver, p.estrategias_ensino, p.recursos_acessibilidade, p.adaptacoes_curriculares, p.criterios_avaliacao, p.observacoes'
                : 'p.id, p.aluno_id, COALESCE(p.disciplina_id, d.id) AS disciplina_id, p.data_atendimento, p.profissional_responsavel, p.necessidades_identificadas, p.objetivos_atendimento, p.recursos_utilizados, p.estrategias_aplicadas, p.tecnologias_assistivas, p.resultados_observados, p.encaminhamentos, p.observacoes_gerais';
            $stmt = $pdo->prepare("SELECT {$camposDocumento}, COALESCE(d.nome, p.nome_documento) AS disciplina_nome, a.nome AS aluno_nome FROM {$tabela} p LEFT JOIN disciplinas d ON d.id = p.disciplina_id OR (p.disciplina_id IS NULL AND TRIM(p.nome_documento) = d.nome) INNER JOIN alunos a ON a.id = p.aluno_id WHERE p.id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$registro || !$autorizacao->podeConsultarPlano($tipo, $usuarioId, (int) $registro['aluno_id'])) respostaPlano(['success' => false, 'message' => 'Acesso negado ao documento.'], 403);
            respostaPlano(['success' => true, 'modulo' => $modulo, 'registro' => $registro]);
        }
        if ($tipo === 'apoio') $alunoId = $autorizacao->alunoDoApoio($usuarioId) ?? 0;
        if (!$autorizacao->podeConsultarPlano($tipo, $usuarioId, $alunoId) || !$autorizacao->disciplinaAssociadaAoAluno($alunoId, $disciplinaId)) respostaPlano(['success' => false, 'message' => 'Acesso negado ao contexto.'], 403);
        $camposHistorico = $tabela === 'pei'
            ? 'p.id, p.aluno_id, p.disciplina_id, p.data_elaboracao, p.responsavel_preenchimento, p.objetivos_pedagogicos, p.habilidades_desenvolver, p.estrategias_ensino, p.recursos_acessibilidade, p.adaptacoes_curriculares, p.criterios_avaliacao, p.observacoes'
            : 'p.id, p.aluno_id, COALESCE(p.disciplina_id, d.id) AS disciplina_id, p.data_atendimento, p.profissional_responsavel, p.necessidades_identificadas, p.objetivos_atendimento, p.recursos_utilizados, p.estrategias_aplicadas, p.tecnologias_assistivas, p.resultados_observados, p.encaminhamentos, p.observacoes_gerais';
        $dataCampo = $tabela === 'pei' ? 'data_elaboracao' : 'data_atendimento';
        if ($tabela === 'paee') {
            $stmt = $pdo->prepare("SELECT {$camposHistorico}, d.nome AS disciplina_nome FROM paee p INNER JOIN disciplinas d ON d.id = :disciplina_id WHERE p.aluno_id = :aluno_id AND (p.disciplina_id = d.id OR (p.disciplina_id IS NULL AND TRIM(p.nome_documento) = d.nome)) ORDER BY p.{$dataCampo} DESC, p.id DESC");
            $stmt->execute([':aluno_id' => $alunoId, ':disciplina_id' => $disciplinaId]);
        } else {
            $stmt = $pdo->prepare("SELECT {$camposHistorico}, d.nome AS disciplina_nome FROM pei p LEFT JOIN disciplinas d ON d.id = p.disciplina_id WHERE p.aluno_id = :aluno_id AND p.disciplina_id = :disciplina_id ORDER BY p.{$dataCampo} DESC, p.id DESC");
            $stmt->execute([':aluno_id' => $alunoId, ':disciplina_id' => $disciplinaId]);
        }
        respostaPlano(['success' => true, 'modulo' => $modulo, 'registros' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    if ($action === 'salvar') {
        if ($tabela === '') respostaPlano(['success' => false, 'message' => 'Módulo inválido.'], 422);
        $id = inteiro($entrada['id'] ?? 0);
        $alunoId = inteiro($entrada['aluno_id'] ?? 0);
        $disciplinaId = inteiro($entrada['disciplina_id'] ?? 0);
        if (!$autorizacao->podeEditarPlano($tipo, $usuarioId, $alunoId) || !$autorizacao->disciplinaAssociadaAoAluno($alunoId, $disciplinaId)) respostaPlano(['success' => false, 'message' => 'Sem permissão ou contexto inválido.'], 403);
        $campos = $tabela === 'pei'
            ? ['data_elaboracao','responsavel_preenchimento','objetivos_pedagogicos','habilidades_desenvolver','estrategias_ensino','recursos_acessibilidade','adaptacoes_curriculares','criterios_avaliacao','observacoes']
            : ['data_atendimento','profissional_responsavel','necessidades_identificadas','objetivos_atendimento','recursos_utilizados','estrategias_aplicadas','tecnologias_assistivas','resultados_observados','encaminhamentos','observacoes_gerais'];
        $valores = [];
        foreach ($campos as $campo) $valores[$campo] = array_key_exists($campo, $entrada) ? $entrada[$campo] : null;
        if ($id > 0) {
            $existente = $pdo->prepare("SELECT aluno_id, disciplina_id, nome_documento FROM {$tabela} WHERE id = :id LIMIT 1");
            $existente->execute([':id' => $id]);
            $registro = $existente->fetch(PDO::FETCH_ASSOC);
            $disciplinaCompativel = $registro && (int) ($registro['disciplina_id'] ?? 0) === $disciplinaId;
            if ($tabela === 'paee' && $registro && empty($registro['disciplina_id'])) {
                $nomeDisciplina = $pdo->prepare('SELECT 1 FROM disciplinas WHERE id = :id AND TRIM(nome) = TRIM(:nome) LIMIT 1');
                $nomeDisciplina->execute([':id' => $disciplinaId, ':nome' => (string) $registro['nome_documento']]);
                $disciplinaCompativel = (bool) $nomeDisciplina->fetchColumn();
            }
            if (!$registro || (int) $registro['aluno_id'] !== $alunoId || !$disciplinaCompativel) respostaPlano(['success' => false, 'message' => 'Documento incompatível com o contexto.'], 403);
            $sets = implode(', ', array_map(fn($campo) => "$campo = :$campo", $campos));
            $valores['id'] = $id; $valores['aluno_id'] = $alunoId; $valores['disciplina_id'] = $disciplinaId;
            $whereDisciplina = ' AND disciplina_id = :disciplina_id';
            if ($tabela === 'paee') {
                $sets .= ', disciplina_id = :disciplina_id_novo';
                $valores['disciplina_id_novo'] = $disciplinaId;
                $whereDisciplina = ' AND (disciplina_id = :disciplina_id OR disciplina_id IS NULL)';
            }
            $stmt = $pdo->prepare("UPDATE {$tabela} SET $sets WHERE id = :id AND aluno_id = :aluno_id{$whereDisciplina}");
            $stmt->execute($valores);
        } else {
            $valores['aluno_id'] = $alunoId; $valores['disciplina_id'] = $disciplinaId;
            $colunasBase = ['aluno_id','disciplina_id'];
            $colunas = implode(', ', array_merge($colunasBase, $campos));
            $marcadores = ':' . implode(', :', array_merge($colunasBase, $campos));
            $stmt = $pdo->prepare("INSERT INTO {$tabela} ($colunas) VALUES ($marcadores)");
            $stmt->execute($valores); $id = (int) $pdo->lastInsertId();
        }
        respostaPlano(['success' => true, 'id' => $id, 'message' => 'Documento salvo com sucesso.']);
    }

    respostaPlano(['success' => false, 'message' => 'Operação inválida.'], 422);
} catch (Throwable $e) {
    error_log('recurso_planos: ' . $e->getMessage());
    respostaPlano(['success' => false, 'message' => 'Falha interna ao processar a solicitação.'], 500);
}
