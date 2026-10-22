<?php

$dsn = 'mysql:host=localhost;dbname=u750204740_SS';
$username = "u750204740_SS";
$password = "paoComOvo123!@##";

try {
    $conexao = new PDO($dsn, $username, $password);
    
} catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}

?>
