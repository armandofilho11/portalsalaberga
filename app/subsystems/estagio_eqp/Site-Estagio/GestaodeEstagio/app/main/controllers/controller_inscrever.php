<?php
session_start();
require_once '../models/model-function.php';

// Removendo a verificação de sessão inicial, o id_aluno virá do formulário via POST
// if (!isset($_SESSION['idAluno'])) {
//     header("Location: ../views/Login_aluno.php");
//     exit;
// }

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

try {
    $id_formulario = isset($_POST['id_formulario']) ? intval($_POST['id_formulario']) : 0;
    $id_aluno = isset($_POST['id_aluno']) ? intval($_POST['id_aluno']) : 0;
    $perfis = isset($_POST['perfis']) ? json_decode($_POST['perfis'], true) : [];

    if (!$id_formulario || !$id_aluno || empty($perfis)) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
        exit;
    }

    $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Iniciar transação
    $pdo->beginTransaction();

    // Buscar dados do processo original para criar o novo registro
    $stmt = $pdo->prepare('SELECT hora, local, id_concedente FROM selecao WHERE id = :id_formulario');
    $stmt->execute(['id_formulario' => $id_formulario]);
    $processo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$processo) {
        throw new Exception('Processo não encontrado');
    }

    // Verificar se o aluno já está inscrito neste processo
    $stmt = $pdo->prepare('SELECT id FROM selecao WHERE id_concedente = :id_concedente AND id_aluno = :id_aluno');
    $stmt->execute([
        'id_concedente' => $processo['id_concedente'],
        'id_aluno' => $id_aluno
    ]);
    
    if ($stmt->rowCount() > 0) {
        throw new Exception('Aluno já inscrito neste processo');
    }

    // Criar um novo registro de inscrição
    $stmt = $pdo->prepare('
        INSERT INTO selecao (hora, local, id_concedente, id_aluno, perfis_selecionados, status)
        VALUES (:hora, :local, :id_concedente, :id_aluno, :perfis, "pendente")
    ');

    $stmt->execute([
        'hora' => $processo['hora'],
        'local' => $processo['local'],
        'id_concedente' => $processo['id_concedente'],
        'id_aluno' => $id_aluno,
        'perfis' => json_encode($perfis)
    ]);

    // Confirmar transação
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso']);

} catch (Exception $e) {
    // Em caso de erro, desfazer a transação
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?> 

