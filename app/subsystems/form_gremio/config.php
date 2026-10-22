<?php
// Configuração do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'u750204740_formcpgremio');
define('DB_USER', 'u750204740_formcpgremio');
define('DB_PASS', 'paoComOvo123!@##'); // Substitua pela senha correta

// Função para conectar ao banco de dados
function getConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
}
?> 