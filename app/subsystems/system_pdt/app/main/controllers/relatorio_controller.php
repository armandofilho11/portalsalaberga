<?php
require_once('../config/security.php');
require_once('../config/connect.php');

iniciarSessaoSegura();
exigirAutenticacao(true);
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'resumo_geral':
        resumoGeral();
        break;
    case 'calendario_eventos':
        calendarioEventos();
        break;
    case 'listar_turmas_relatorio':
        listarTurmasRelatorio();
        break;
    case 'listar_ocorrencias_relatorio':
        listarOcorrenciasRelatorio();
        break;
    case 'exportar_relatorio':
        exportarRelatorio();
        break;
    default:
        echo json_encode(['sucesso' => false, 'erro' => 'Ação não reconhecida']);
        break;
}

function obterIntervaloPeriodo(string $periodo): array
{
    $periodo = strtolower(trim($periodo));
    $hoje = new DateTime('today');
    $inicio = clone $hoje;
    $fim = clone $hoje;

    switch ($periodo) {
        case 'trimestre':
            $inicio->modify('first day of -2 month');
            $fim->modify('last day of this month');
            break;
        case 'semestre':
            $inicio->modify('first day of -5 month');
            $fim->modify('last day of this month');
            break;
        case 'ano':
            $inicio = new DateTime('first day of january this year');
            $fim = new DateTime('last day of december this year');
            break;
        case 'mes':
        default:
            $inicio->modify('first day of this month');
            $fim->modify('last day of this month');
            break;
    }

    return [
        'inicio' => $inicio->format('Y-m-d 00:00:00'),
        'fim' => $fim->format('Y-m-d 23:59:59'),
        'periodo' => $periodo,
    ];
}

function aplicarFiltroProfessor(string $tipoUsuario, int $professorId, array &$params): string
{
    if ($tipoUsuario === 'professor') {
        $params[':id_professor'] = $professorId;
        return 'AND t.id_professor = :id_professor';
    }
    return '';
}

function resumoGeral()
{
    $db = new connect();
    $conn = $db->getConnection();
    $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
    $professorId = (int) ($_SESSION['id_usuario'] ?? 0);
    $periodo = $_GET['periodo'] ?? 'mes';
    $intervalo = obterIntervaloPeriodo($periodo);
    $filtroProfessor = '';
    $params = [
        ':inicio' => $intervalo['inicio'],
        ':fim' => $intervalo['fim'],
    ];

    $filtroProfessor = aplicarFiltroProfessor($tipoUsuario, $professorId, $params);

    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM turmas t WHERE 1=1 $filtroProfessor");
        $stmt->execute($params);
        $totalTurmas = (int) ($stmt->fetchColumn() ?? 0);

        $stmt = $conn->prepare("SELECT COUNT(DISTINCT a.id_aluno) as total FROM alunos a JOIN turmas t ON a.id_turma = t.id_turma WHERE 1=1 $filtroProfessor");
        $stmt->execute($params);
        $totalAlunos = (int) ($stmt->fetchColumn() ?? 0);

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM ocorrencias o JOIN turmas t ON o.id_turma = t.id_turma WHERE o.data_ocorrencia BETWEEN :inicio AND :fim $filtroProfessor");
        $stmt->execute($params);
        $totalOcorrenciasPeriodo = (int) ($stmt->fetchColumn() ?? 0);

        $stmt = $conn->prepare("SELECT ROUND(CASE WHEN COUNT(*) = 0 THEN 0 ELSE 100 * SUM(f.status = 'presente') / COUNT(*) END, 1) AS media FROM frequencias f JOIN turmas t ON f.id_turma = t.id_turma WHERE f.data_frequencia BETWEEN :inicio AND :fim $filtroProfessor");
        $stmt->execute($params);
        $mediaFrequenciaPeriodo = floatval($stmt->fetchColumn() ?? 0);

        $stmt = $conn->prepare("SELECT o.id_ocorrencia, o.tipo_ocorrencia, o.descricao, DATE(o.data_ocorrencia) AS data_ocorrencia, a.nome AS nome_aluno, a.matricula, c.nome_curso, t.serie_turma, t.letra, t.sala_turma, u.nome AS professor_nome FROM ocorrencias o JOIN alunos a ON o.id_aluno = a.id_aluno JOIN turmas t ON o.id_turma = t.id_turma JOIN cursos c ON t.id_curso = c.id_curso JOIN usuarios u ON o.id_professor = u.id WHERE o.data_ocorrencia BETWEEN :inicio AND :fim $filtroProfessor ORDER BY o.data_ocorrencia DESC LIMIT 5");
        $stmt->execute($params);
        $ocorrenciasRecentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT CONCAT(c.nome_curso, ' ', t.serie_turma, 'º ', t.letra) AS turma, COUNT(*) AS total FROM ocorrencias o JOIN turmas t ON o.id_turma = t.id_turma JOIN cursos c ON t.id_curso = c.id_curso WHERE o.data_ocorrencia BETWEEN :inicio AND :fim $filtroProfessor GROUP BY t.id_turma ORDER BY total DESC LIMIT 5");
        $stmt->execute($params);
        $topTurmasOcorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT CONCAT(c.nome_curso, ' ', t.serie_turma, 'º ', t.letra) AS turma, ROUND(CASE WHEN COUNT(f.id) = 0 THEN 0 ELSE 100 * SUM(f.status = 'presente') / COUNT(f.id) END, 1) AS total FROM frequencias f JOIN turmas t ON f.id_turma = t.id_turma JOIN cursos c ON t.id_curso = c.id_curso WHERE f.data_frequencia BETWEEN :inicio AND :fim $filtroProfessor GROUP BY t.id_turma ORDER BY total DESC LIMIT 5");
        $stmt->execute($params);
        $topTurmasFrequencia = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT c.nome_curso, COUNT(*) as total FROM turmas t JOIN cursos c ON t.id_curso = c.id_curso WHERE 1=1 $filtroProfessor GROUP BY c.id_curso ORDER BY total DESC");
        $stmt->execute($params);
        $turmasPorCurso = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT tipo_ocorrencia, COUNT(*) as total FROM ocorrencias o JOIN turmas t ON o.id_turma = t.id_turma WHERE o.data_ocorrencia BETWEEN :inicio AND :fim $filtroProfessor GROUP BY tipo_ocorrencia ORDER BY total DESC");
        $stmt->execute($params);
        $ocorrenciasPorTipo = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'sucesso' => true,
            'periodo' => $intervalo['periodo'],
            'inicio' => $intervalo['inicio'],
            'fim' => $intervalo['fim'],
            'total_turmas' => $totalTurmas,
            'total_alunos' => $totalAlunos,
            'total_ocorrencias_periodo' => $totalOcorrenciasPeriodo,
            'media_frequencia_periodo' => $mediaFrequenciaPeriodo,
            'ocorrencias_recentes' => $ocorrenciasRecentes,
            'top_turmas_ocorrencias' => $topTurmasOcorrencias,
            'top_turmas_frequencia' => $topTurmasFrequencia,
            'turmas_por_curso' => $turmasPorCurso,
            'ocorrencias_por_tipo' => $ocorrenciasPorTipo,
        ]);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
}

function listarTurmasRelatorio()
{
    $db = new connect();
    $conn = $db->getConnection();
    $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
    $professorId = (int) ($_SESSION['id_usuario'] ?? 0);
    $periodo = $_GET['periodo'] ?? 'mes';
    $intervalo = obterIntervaloPeriodo($periodo);
    $params = [
        ':inicio' => $intervalo['inicio'],
        ':fim' => $intervalo['fim'],
    ];
    $filtroProfessor = aplicarFiltroProfessor($tipoUsuario, $professorId, $params);

    try {
        $stmt = $conn->prepare("SELECT t.id_turma, CONCAT(c.nome_curso, ' ', t.serie_turma, 'º ', t.letra) AS turma, u.nome AS professor_nome, t.sala_turma AS sala, t.ano_letivo AS ano, COUNT(DISTINCT a.id_aluno) AS total_alunos, COUNT(DISTINCT o.id_ocorrencia) AS ocorrencias_periodo, ROUND(CASE WHEN COUNT(f.id) = 0 THEN 0 ELSE 100 * SUM(f.status = 'presente') / COUNT(f.id) END, 1) AS frequencia_media_periodo FROM turmas t JOIN cursos c ON t.id_curso = c.id_curso JOIN usuarios u ON t.id_professor = u.id LEFT JOIN alunos a ON a.id_turma = t.id_turma LEFT JOIN ocorrencias o ON o.id_turma = t.id_turma AND o.data_ocorrencia BETWEEN :inicio AND :fim LEFT JOIN frequencias f ON f.id_turma = t.id_turma AND f.data_frequencia BETWEEN :inicio AND :fim WHERE 1=1 $filtroProfessor GROUP BY t.id_turma ORDER BY total_alunos DESC");
        $stmt->execute($params);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['sucesso' => true, 'dados' => $dados]);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
}

function listarOcorrenciasRelatorio()
{
    $db = new connect();
    $conn = $db->getConnection();
    $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
    $professorId = (int) ($_SESSION['id_usuario'] ?? 0);
    $periodo = $_GET['periodo'] ?? 'mes';
    $intervalo = obterIntervaloPeriodo($periodo);
    $params = [
        ':inicio' => $intervalo['inicio'],
        ':fim' => $intervalo['fim'],
    ];
    $filtroProfessor = aplicarFiltroProfessor($tipoUsuario, $professorId, $params);

    try {
        $stmt = $conn->prepare("SELECT o.id_ocorrencia, DATE(o.data_ocorrencia) AS data_ocorrencia, o.tipo_ocorrencia, o.descricao, a.nome AS nome_aluno, a.matricula, CONCAT(c.nome_curso, ' ', t.serie_turma, 'º ', t.letra) AS turma, t.sala_turma AS sala, u.nome AS professor_nome FROM ocorrencias o JOIN alunos a ON o.id_aluno = a.id_aluno JOIN turmas t ON o.id_turma = t.id_turma JOIN cursos c ON t.id_curso = c.id_curso JOIN usuarios u ON o.id_professor = u.id WHERE o.data_ocorrencia BETWEEN :inicio AND :fim $filtroProfessor ORDER BY o.data_ocorrencia DESC LIMIT 200");
        $stmt->execute($params);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['sucesso' => true, 'dados' => $dados]);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
}

function exportarRelatorio()
{
    $db = new connect();
    $conn = $db->getConnection();
    $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
    $professorId = (int) ($_SESSION['id_usuario'] ?? 0);
    $report = $_GET['report'] ?? 'relatorio';
    $type = strtolower($_GET['type'] ?? 'csv');
    $periodo = $_GET['periodo'] ?? 'mes';
    $intervalo = obterIntervaloPeriodo($periodo);
    $params = [
        ':inicio' => $intervalo['inicio'],
        ':fim' => $intervalo['fim'],
    ];
    $filtroProfessor = aplicarFiltroProfessor($tipoUsuario, $professorId, $params);

    $extension = $type === 'xls' ? 'xls' : 'csv';
    $filename = sprintf('relatorio_%s_%s.%s', $report, $intervalo['periodo'], $extension);

    if ($type === 'xls') {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    } else {
        header('Content-Type: text/csv; charset=utf-8');
    }
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: public');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    if ($output === false) {
        echo 'Erro ao gerar arquivo';
        return;
    }
    fwrite($output, "\xEF\xBB\xBF");

    if ($report === 'turmas') {
        $stmt = $conn->prepare("SELECT CONCAT(c.nome_curso, ' ', t.serie_turma, 'º ', t.letra) AS turma, u.nome AS professor_nome, t.sala_turma AS sala, t.ano_letivo AS ano, COUNT(DISTINCT a.id_aluno) AS total_alunos, COUNT(DISTINCT o.id_ocorrencia) AS ocorrencias_periodo, ROUND(CASE WHEN COUNT(f.id) = 0 THEN 0 ELSE 100 * SUM(f.status = 'presente') / COUNT(f.id) END, 1) AS frequencia_media_periodo FROM turmas t JOIN cursos c ON t.id_curso = c.id_curso JOIN usuarios u ON t.id_professor = u.id LEFT JOIN alunos a ON a.id_turma = t.id_turma LEFT JOIN ocorrencias o ON o.id_turma = t.id_turma AND o.data_ocorrencia BETWEEN :inicio AND :fim LEFT JOIN frequencias f ON f.id_turma = t.id_turma AND f.data_frequencia BETWEEN :inicio AND :fim WHERE 1=1 $filtroProfessor GROUP BY t.id_turma ORDER BY total_alunos DESC");
        $stmt->execute($params);
        fputcsv($output, ['Turma', 'Professor', 'Sala', 'Ano', 'Alunos', 'Ocorrências', 'Frequência %']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $row['turma'],
                $row['professor_nome'],
                $row['sala'],
                $row['ano'],
                $row['total_alunos'],
                $row['ocorrencias_periodo'],
                $row['frequencia_media_periodo'],
            ]);
        }
    } elseif ($report === 'ocorrencias') {
        $stmt = $conn->prepare("SELECT DATE(o.data_ocorrencia) AS data_ocorrencia, o.tipo_ocorrencia, o.descricao, a.nome AS nome_aluno, a.matricula, CONCAT(c.nome_curso, ' ', t.serie_turma, 'º ', t.letra) AS turma, t.sala_turma AS sala, u.nome AS professor_nome FROM ocorrencias o JOIN alunos a ON o.id_aluno = a.id_aluno JOIN turmas t ON o.id_turma = t.id_turma JOIN cursos c ON t.id_curso = c.id_curso JOIN usuarios u ON o.id_professor = u.id WHERE o.data_ocorrencia BETWEEN :inicio AND :fim $filtroProfessor ORDER BY o.data_ocorrencia DESC");
        $stmt->execute($params);
        fputcsv($output, ['Data', 'Tipo', 'Descrição', 'Aluno', 'Matrícula', 'Turma', 'Sala', 'Professor']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $row['data_ocorrencia'],
                $row['tipo_ocorrencia'],
                $row['descricao'],
                $row['nome_aluno'],
                $row['matricula'],
                $row['turma'],
                $row['sala'],
                $row['professor_nome'],
            ]);
        }
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM turmas t WHERE 1=1 $filtroProfessor");
        $stmt->execute($params);
        $totalTurmas = (int) ($stmt->fetchColumn() ?? 0);
        $stmt = $conn->prepare("SELECT COUNT(DISTINCT a.id_aluno) as total FROM alunos a JOIN turmas t ON a.id_turma = t.id_turma WHERE 1=1 $filtroProfessor");
        $stmt->execute($params);
        $totalAlunos = (int) ($stmt->fetchColumn() ?? 0);
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM ocorrencias o JOIN turmas t ON o.id_turma = t.id_turma WHERE o.data_ocorrencia BETWEEN :inicio AND :fim $filtroProfessor");
        $stmt->execute($params);
        $totalOcorrencias = (int) ($stmt->fetchColumn() ?? 0);
        $stmt = $conn->prepare("SELECT ROUND(CASE WHEN COUNT(*) = 0 THEN 0 ELSE 100 * SUM(f.status = 'presente') / COUNT(*) END, 1) AS media FROM frequencias f JOIN turmas t ON f.id_turma = t.id_turma WHERE f.data_frequencia BETWEEN :inicio AND :fim $filtroProfessor");
        $stmt->execute($params);
        $mediaFrequencia = floatval($stmt->fetchColumn() ?? 0);
        fputcsv($output, ['Período', 'Total de turmas', 'Total de alunos', 'Ocorrências', 'Média de frequência']);
        fputcsv($output, [$intervalo['periodo'], $totalTurmas, $totalAlunos, $totalOcorrencias, $mediaFrequencia]);
    }

    fclose($output);
    exit;
}

function calendarioEventos()
{
    $db = new connect();
    $conn = $db->getConnection();
    $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
    $professorId = (int) ($_SESSION['id_usuario'] ?? 0);
    $mes = intval($_GET['mes'] ?? date('n'));
    $ano = intval($_GET['ano'] ?? date('Y'));
    $filtroProfessor = '';
    $params = [':ano' => $ano, ':mes' => $mes];

    if ($tipoUsuario === 'professor') {
        $filtroProfessor = 'AND t.id_professor = :id_professor';
        $params[':id_professor'] = $professorId;
    }

    try {
        $stmt = $conn->prepare("SELECT DATE(o.data_ocorrencia) AS evento_data, o.tipo_ocorrencia AS titulo, o.descricao, 'Ocorrência' AS tipo FROM ocorrencias o JOIN turmas t ON o.id_turma = t.id_turma WHERE YEAR(o.data_ocorrencia) = :ano AND MONTH(o.data_ocorrencia) = :mes $filtroProfessor ORDER BY o.data_ocorrencia ASC");
        $stmt->execute($params);
        $ocorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT f.data_frequencia AS evento_data, CONCAT('Frequência: ', SUM(f.status = 'presente'), ' presentes', ' / ', COUNT(*), ' registros') AS titulo, '' AS descricao, 'Frequência' AS tipo FROM frequencias f JOIN turmas t ON f.id_turma = t.id_turma WHERE YEAR(f.data_frequencia) = :ano AND MONTH(f.data_frequencia) = :mes $filtroProfessor GROUP BY f.data_frequencia ORDER BY f.data_frequencia ASC");
        $stmt->execute($params);
        $frequencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dados = array_merge($ocorrencias, $frequencias);
        usort($dados, function ($a, $b) {
            return strcmp($a['evento_data'], $b['evento_data']);
        });

        echo json_encode(['sucesso' => true, 'dados' => $dados]);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
}
