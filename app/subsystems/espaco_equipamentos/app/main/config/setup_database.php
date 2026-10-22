<?php
// Configuração de logs
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/setup_errors.log');

try {
    // Conectar ao MySQL sem selecionar banco de dados
    $pdo = new PDO(
        "mysql:host=localhost",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Ler o arquivo SQL
    $sql = file_get_contents(__DIR__ . '/database.sql');

    // Executar o script SQL
    $pdo->exec($sql);

    echo "Banco de dados configurado com sucesso!\n";
} catch (PDOException $e) {
    error_log("Erro ao configurar banco de dados: " . $e->getMessage());
    echo "Erro ao configurar banco de dados: " . $e->getMessage() . "\n";
    exit(1);
} 