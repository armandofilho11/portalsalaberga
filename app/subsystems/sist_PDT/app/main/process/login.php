<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'] ?? '';

    if (empty($matricula)) {
        $_SESSION['error'] = "Matrícula é obrigatória.";
        header('Location: ../index.php');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT matricula_prof, nome_professor FROM PDTs WHERE matricula_prof = ?");
        $stmt->execute([$matricula]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            $_SESSION['usuario'] = [
                'nome' => $usuario['nome_professor'],
                'matricula' => $usuario['matricula_prof'],
                'tipo' => 'pdt'
            ];
            error_log('Login PDT bem sucedido. Dados da sessão: ' . print_r($_SESSION['usuario'], true));
            header('Location: ../view/dashboard.php');
            exit;
        }

        $_SESSION['error'] = "Matrícula não encontrada. Apenas PDTs podem acessar o sistema.";
        header('Location: ../index.php');
        exit;
    } catch (PDOException $e) {
        error_log('Erro no login: ' . $e->getMessage());
        $_SESSION['error'] = "Erro ao tentar fazer login. Por favor, tente novamente.";
        header('Location: ../index.php');
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
?> 