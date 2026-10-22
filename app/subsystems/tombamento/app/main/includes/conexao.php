<?php
$host = "localhost";
$dbname = "u750204740_tombamento";
$user = "u750204740_tombamento";
$pass = "areaDev123!@#";


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>