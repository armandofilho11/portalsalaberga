<?php
require_once __DIR__ . '/../auth.php';
requireLogin(['master', 'aee']);
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Autorizacao.php';

try {
    $dados = json_decode(file_get_contents('php://input'), true);
    if (!$dados) {
        echo json_encode(['success' => false, 'message' => 'Nenhum dado recebido']);
        exit;
    }
    $usuario = currentUser();
    $autorizacao = new Autorizacao();
    if (!$autorizacao->podeEditarPlano((string) $usuario['tipo'], (int) $usuario['id'], (int) ($dados['aluno_id'] ?? 0))) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Acesso negado ao aluno.']);
        exit;
    }

  $sql = "INSERT INTO paee (
    aluno_id,
    nome_documento,
    data_atendimento,
    profissional_responsavel,
    necessidades_identificadas,
    objetivos_atendimento,
    recursos_utilizados,
    estrategias_aplicadas,
    tecnologias_assistivas,
    resultados_observados,
    encaminhamentos,
    observacoes_gerais
) VALUES (
    :aluno_id,
    :nome_documento,
    :data_atendimento,
    :profissional_responsavel,
    :necessidades_identificadas,
    :objetivos_atendimento,
    :recursos_utilizados,
    :estrategias_aplicadas,
    :tecnologias_assistivas,
    :resultados_observados,
    :encaminhamentos,
    :observacoes_gerais
)";
    $stmt = $pdo->prepare($sql);
    $resultado = $stmt->execute([
    ':aluno_id' => $dados['aluno_id'] ?? 0,
    ':nome_documento' => trim((string) ($dados['nome'] ?? '')),
    ':data_atendimento' => $dados['dataAtendimento'] ?? null,
    ':profissional_responsavel' => $dados['responsavel'] ?? null,
    ':necessidades_identificadas' => $dados['necessidades'] ?? null,
    ':objetivos_atendimento' => $dados['objetivos'] ?? null,
    ':recursos_utilizados' => $dados['recursos'] ?? null,
    ':estrategias_aplicadas' => $dados['estrategias'] ?? null,
    ':tecnologias_assistivas' => $dados['tecnologias'] ?? null,
    ':resultados_observados' => $dados['resultados'] ?? null,
    ':encaminhamentos' => $dados['encaminhamentos'] ?? null,
    ':observacoes_gerais' => $dados['observacoes'] ?? null
]);

    echo json_encode(['success' => $resultado, 'message' => $resultado ? 'PAEE salvo com sucesso' : 'Erro ao salvar PAEE']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
