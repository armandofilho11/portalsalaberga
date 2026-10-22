<?php
session_start();
require_once '../model/Opiniao.class.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: ../view/login.php?error=" . urlencode("Por favor, faça login para acessar o portal"));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = (int)($_SESSION['usuario']['id'] ?? 0);
    $opiniao = new Opiniao();

    // Log para depuração
    error_log("POST Data: " . print_r($_POST, true));
    error_log("ID do usuário: $id_usuario");

    // Avaliação de refeição
    if (isset($_POST['refeicao']) && isset($_POST['satisfacao'])) {
        $refeicao = trim($_POST['refeicao'] ?? '');
        $satisfacao = trim($_POST['satisfacao'] ?? '');
        $data = trim($_POST['data'] ?? date('Y-m-d'));

        if ($refeicao && $satisfacao && $id_usuario > 0) {
            $result = $opiniao->cadastrarOpiniao($refeicao, $satisfacao, $id_usuario, $data);
            header("Location: ../view/avaliacao.php?" . ($result['success'] ? "success" : "error") . "=" . urlencode($result['message']));
        } else {
            error_log("Dados incompletos: refeicao=$refeicao, satisfacao=$satisfacao, id_usuario=$id_usuario, data=$data");
            header("Location: ../view/avaliacao.php?error=" . urlencode("Dados incompletos. Por favor, preencha todos os campos."));
        }
    }

    // Envio de sugestão
    if (isset($_POST['sugestao'])) {
        $texto = trim($_POST['sugestao'] ?? '');

        if ($texto && $id_usuario > 0) {
            $result = $opiniao->cadastrarSugestao($texto, $id_usuario);
            header("Location: ../view/avaliacao.php?" . ($result['success'] ? "success" : "error") . "=" . urlencode($result['message']));
        } else {
            error_log("Texto da sugestão vazio ou ID de usuário inválido: texto=$texto, id_usuario=$id_usuario");
            header("Location: ../view/avaliacao.php?error=" . urlencode("Texto da sugestão não pode estar vazio."));
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getEvaluations') {
    // New endpoint to fetch user's evaluations for the current day
    $id_usuario = (int)($_SESSION['usuario']['id'] ?? 0);
    $data = date('Y-m-d');
    $opiniao = new Opiniao();

    try {
        $stmt = $opiniao->pdo->prepare("SELECT refeicao FROM opiniao WHERE id_usuario = :id_usuario AND data = :data");
        $stmt->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindValue(":data", $data);
        $stmt->execute();
        $evaluatedMeals = $stmt->fetchAll(PDO::FETCH_COLUMN);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'evaluatedMeals' => $evaluatedMeals]);
    } catch (PDOException $e) {
        error_log("Erro ao buscar avaliações: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erro ao buscar avaliações']);
    }
} else {
    header("Location: ../view/avaliacao.php?error=" . urlencode("Método inválido."));
}
exit();
?>