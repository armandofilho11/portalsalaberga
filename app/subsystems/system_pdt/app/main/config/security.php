<?php

require_once(__DIR__ . '/connect.php');

function iniciarSessaoSegura(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

function gerarCsrfToken(): string
{
    iniciarSessaoSegura();
    return $_SESSION['csrf_token'];
}

function validarCsrfToken($token = null): bool
{
    iniciarSessaoSegura();

    $token = $token ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? ''));
    return is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function exigirAutenticacao(bool $jsonResponse = true): void
{
    iniciarSessaoSegura();

    if (!isset($_SESSION['id_usuario'])) {
        if ($jsonResponse) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(401);
            echo json_encode(['sucesso' => false, 'erro' => 'Usuário não autenticado']);
        } else {
            header('Location: ../index.php');
        }
        exit;
    }
}

function normalizarTipoUsuario($tipo): string
{
    $tipoNormalizado = strtolower(trim((string) $tipo));
    return $tipoNormalizado === 'pdt' ? 'professor' : $tipoNormalizado;
}

function solicitarPermissaoTurma(PDO $conn, int $idTurma, int $idUsuario, bool $allowGestor = true): bool
{
    $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');

    if ($allowGestor && $tipoUsuario === 'gestor') {
        return true;
    }

    if (!$idTurma || !$idUsuario) {
        return false;
    }

    $stmt = $conn->prepare(
        'SELECT id_turma FROM turmas WHERE id_turma = :id_turma AND id_professor = :id_professor LIMIT 1'
    );
    $stmt->execute([
        ':id_turma' => $idTurma,
        ':id_professor' => $idUsuario,
    ]);

    return (bool) $stmt->fetchColumn();
}

function validarPermissaoTurmaParaAcao($turmaId, $usuarioTipo = null, $usuarioId = null): bool
{
    $tipo = normalizarTipoUsuario($usuarioTipo ?? ($_SESSION['tipo'] ?? ''));
    if ($tipo === 'gestor') {
        return true;
    }

    $turmaId = (int) $turmaId;
    $usuarioId = (int) ($usuarioId ?? ($_SESSION['id_usuario'] ?? 0));
    if (!$turmaId || !$usuarioId) {
        return false;
    }

    $conn = (new connect())->getConnection();
    return podeGerenciarTurma($conn, $turmaId, $usuarioId);
}

function podeGerenciarTurma(PDO $conn, int $idTurma, int $idUsuario): bool
{
    return solicitarPermissaoTurma($conn, $idTurma, $idUsuario, true);
}

function podeVisualizarTurma(PDO $conn, int $idTurma, int $idUsuario): bool
{
    if (!$idTurma || !$idUsuario) {
        return false;
    }

    $tipoUsuario = normalizarTipoUsuario($_SESSION['tipo'] ?? '');
    if ($tipoUsuario === 'gestor') {
        return true;
    }

    // Todos os professores podem visualizar todas as turmas
    $stmt = $conn->prepare('SELECT id_turma FROM turmas WHERE id_turma = :id_turma LIMIT 1');
    $stmt->execute([':id_turma' => $idTurma]);
    return (bool) $stmt->fetchColumn();
}
