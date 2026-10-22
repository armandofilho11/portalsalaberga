<?php
function getConnection() {
    $host = 'localhost';
    $dbname = 'u750204740_gestaoestagio';
    $username = 'u750204740_gestaoestagio';
    $password = 'paoComOvo123!@##';

    try {
        $conn = new mysqli($host, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8");
        return $conn;
    } catch (Exception $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}
?> 