<?php 
require("../models/model-function.php");

// Verifica se a requisição é POST e se tem os campos necessários
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['email']) && !empty($_POST['email']) && 
    isset($_POST['senha']) && !empty($_POST['senha']) &&
    isset($_POST['tipo']) && in_array($_POST['tipo'], ['professor', 'aluno'])) {

    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);
    $tipo = $_POST['tipo'];

    $usuarios = new Usuarios();
    $user_data = false;

    // Tenta fazer login baseado no tipo
    if ($tipo === 'professor') {
        $user_data = $usuarios->Login_professor($email, $senha);
        $redirect_sucesso = "../views/paginainicial.php";
        $redirect_erro = "../views/Login_professor.php";
    } else {
        $user_data = $usuarios->Login_aluno($email, $senha);
        $redirect_sucesso = "../views/detalhes_aluno.php?id=" . $user_data['id'];
        $redirect_erro = "../views/Login_aluno.php";
    }

    // Redireciona baseado no resultado do login
    if ($user_data) {
        header("Location: " . $redirect_sucesso);
    } else {
        header("Location: " . $redirect_erro);
    }
    exit();

} else {
    // Se não for uma requisição POST válida, redireciona para a página de login apropriada
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'professor';
    $redirect = $tipo === 'professor' ? "../views/Login_professor.php" : "../views/Login_aluno.php";
    header("Location: " . $redirect);
    exit();
}
?> 