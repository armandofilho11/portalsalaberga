<?php

require_once ('..app/main/model/relatorio_model.php');




$servidor = "localhost"; // Endereço do servidor de banco de dados
$usuario = "root";       // Nome de usuário para acessar o banco de dados
$senha = "";             // Senha para acessar o banco de dados
$banco = "meu_banco";    // Nome do banco de dados

// Criando a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
} else {
    echo "Conexão bem-sucedida!";
}

// Fechar a conexão
$conn->close();
?>