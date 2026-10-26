<?php

require_once __DIR__ . '/../auth.php';
requireLogin(['master', 'aee', 'apoio', 'responsavel']);
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $tipoUsuario = $_SESSION['tipo'] ?? 'responsavel';
    $usuarioId = (int) ($_SESSION['usuario_id'] ?? 0);
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    if ($method === 'GET') {
        $alunoId = (int) ($_GET['aluno_id'] ?? 0);
        $sql = 'SELECT f.aluno_id, f.data, f.presente, u.nome AS usuario_nome
                FROM frequencia_apoio f
                INNER JOIN alunos a ON a.id = f.aluno_id
                INNER JOIN usuarios u ON u.id = f.usuario_id';
        $condicoes = [];
        $parametros = [];
        if ($tipoUsuario === 'apoio') {
            $condicoes[] = 'a.id_apoio = ?';
            $parametros[] = $usuarioId;
        }
        if ($alunoId > 0) {
            $condicoes[] = 'f.aluno_id = ?';
            $parametros[] = $alunoId;
        }
        if ($condicoes) {
            $sql .= ' WHERE ' . implode(' AND ', $condicoes);
        }
        $sql .= ' ORDER BY f.data DESC, f.aluno_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($parametros);
        echo json_encode(['success' => true, 'frequencias' => $stmt->fetchAll(PDO::FETCH_ASSOC)], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($method !== 'POST' || $tipoUsuario !== 'apoio') {
        http_response_code($method === 'POST' ? 403 : 405);
        throw new RuntimeException($method === 'POST' ? 'Somente o apoio pode registrar presença.' : 'Método não permitido.');
    }

    $dados = json_decode(file_get_contents('php://input'), true) ?: [];
    $alunoId = (int) ($dados['aluno_id'] ?? 0);
    $data = trim((string) ($dados['data'] ?? ''));
    $presenteRecebido = $dados['presente'] ?? null;
    $dataValida = DateTime::createFromFormat('!Y-m-d', $data);
    if (
        $alunoId <= 0
        || !$dataValida
        || $dataValida->format('Y-m-d') !== $data
        || $data !== date('Y-m-d')
        || !in_array($presenteRecebido, [0, 1, '0', '1'], true)
    ) {
        http_response_code(422);
        throw new InvalidArgumentException('Informe um aluno, a presença e a data de hoje.');
    }

    $stmtAluno = $pdo->prepare('SELECT id FROM alunos WHERE id = ? AND id_apoio = ? LIMIT 1');
    $stmtAluno->execute([$alunoId, $usuarioId]);
    if (!$stmtAluno->fetchColumn()) {
        http_response_code(403);
        throw new RuntimeException('Você não possui vínculo com este aluno.');
    }

    $stmt = $pdo->prepare(
        'INSERT INTO frequencia_apoio (aluno_id, usuario_id, data, presente)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE usuario_id = VALUES(usuario_id), presente = VALUES(presente), atualizado_em = CURRENT_TIMESTAMP'
    );
    $stmt->execute([$alunoId, $usuarioId, $data, (int) $presenteRecebido]);
    echo json_encode(['success' => true, 'message' => 'Presença registrada com sucesso.']);
} catch (InvalidArgumentException | RuntimeException $e) {
    if (http_response_code() < 400) {
        http_response_code(422);
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('Erro ao registrar frequência: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Não foi possível registrar a presença.'], JSON_UNESCAPED_UNICODE);
}
