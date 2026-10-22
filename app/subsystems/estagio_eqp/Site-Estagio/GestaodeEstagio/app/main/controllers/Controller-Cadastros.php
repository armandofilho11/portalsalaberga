<?php 
session_start();
require("../models/model-function.php");

// Cadastro de Aluno
if (isset($_POST["btn"]) && isset($_POST["nome"]) && isset($_POST["matricula"])) {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $contato = $_POST['contato'];
    $curso = $_POST['curso'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $senha = $_POST['senha'];

    $x = new Cadastro();
    $x->Cadastrar_alunos($nome, $matricula, $contato, $curso, $email, $endereco, $senha);
    header("Location: ../views/perfildoaluno.php");
}

// Cadastro de Empresa
if (isset($_POST["btn"]) && isset($_POST["nome"]) && isset($_POST["numero_vagas"])) {
    $nome = $_POST['nome'];
    $contato = $_POST['contato'];
    $endereco = $_POST['endereco'];
    $perfis = isset($_POST['perfis']) ? json_encode($_POST['perfis']) : json_encode([]);
    $vagas = $_POST['numero_vagas'];

    $x = new Cadastro();
    $x->Cadastrar_empresa($nome, $contato, $endereco, $perfis, $vagas);
    header("Location: ../views/dadosempresa.php");
}

// Cadastro de Professor
if (isset($_POST["btn"]) && isset($_POST["email"]) && isset($_POST["senha"])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $x = new Cadastro();
    $resultado = $x->cadastrar_professor($email, $senha);
    if($resultado) {
        header('location:../views/Login_professor.php');
    } else {
        header('location:../views/Cadastro_professor.php');
    }
}
?> 