<?php
function getDatabaseConnection() {
    try {
        $host = 'localhost';
        $dbname = 'u750204740_equipamentos';
        $username = 'u750204740_equipamentos';
        $password = 'paoComOvo123!@##';
        
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];

        $pdo = new PDO($dsn, $username, $password, $options);
        
        // Testar a conexão
        $pdo->query("SELECT 1");
        
        return $pdo;
    } catch (PDOException $e) {
        error_log("Erro na conexão com o banco de dados: " . $e->getMessage());
        throw new Exception("Não foi possível conectar ao banco de dados. Verifique se o MySQL está rodando e se o banco 'sis_aee' existe.");
    }
}
?>