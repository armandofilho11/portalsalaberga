<?php
require_once('../models/main.model.php');
if (
    isset($_POST['nome']) && !empty($_POST['nome']) &&
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['data']) && !empty($_POST['data']) &&
    isset($_POST['turno']) && !empty($_POST['turno']) &&
    isset($_POST['senha']) && !empty($_POST['senha'])
) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data = $_POST['data'];
    $turno = $_POST['turno'];
    $senha = $_POST['senha'];

    $main_model = new main_model();
    $result = $main_model->adicionar_avaliador($nome, $email, $turno, $data, $senha);

    if ($result === 1) {
        header('Location: ../views/registro_user.php?success=Usuario+cadastrado+com+sucesso!');
        exit();
    } elseif ($result === 3) {
        header('Location: ../views/registro_user.php?error=Email+ja+cadastrado.+Tente+outro.');
        exit();
    } else {
        header('Location: ../views/registro_user.php?error=Erro+ao+cadastrar+usuario.+Tente+novamente.');
        exit();
    }
} else {
    header('location:../views/registro_user.php?empty');
    exit();
}
