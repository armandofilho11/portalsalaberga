<?php
// Configurações do Banco de Dados Local
define('DB_HOST_LOCAL', 'localhost');
define('DB_USER_LOCAL', 'root');
define('DB_PASS_LOCAL', '');
define('DB_NAME_LOCAL', 'alimentacao');

// Configurações do Banco de Dados da Hospedagem
define('DB_HOST_HOSPEDAGEM', 'localhost'); 
define('DB_USER_HOSPEDAGEM', 'u750204740_alimentacao'); 
define('DB_PASS_HOSPEDAGEM', 'paoComOvo123!@##'); // Substitua pela senha da hospedagem
define('DB_NAME_HOSPEDAGEM', 'u750204740_alimentacao'); // Substitua pelo nome do banco da hospedagem

// Função para estabelecer conexão com o banco de dados
function getConnection() {
    try {
        // Tenta primeiro a conexão local
        $conn = new mysqli(DB_HOST_LOCAL, DB_USER_LOCAL, DB_PASS_LOCAL, DB_NAME_LOCAL);
        
        if ($conn->connect_error) {
            throw new Exception("Erro na conexão local: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8");
        return $conn;
    } catch (Exception $e) {
        error_log("Falha na conexão local, tentando conexão com hospedagem: " . $e->getMessage());
        
        try {
            // Se falhar, tenta a conexão com a hospedagem
            $conn = new mysqli(DB_HOST_HOSPEDAGEM, DB_USER_HOSPEDAGEM, DB_PASS_HOSPEDAGEM, DB_NAME_HOSPEDAGEM);
            
            if ($conn->connect_error) {
                throw new Exception("Erro na conexão com hospedagem: " . $conn->connect_error);
            }
            
            $conn->set_charset("utf8");
            return $conn;
        } catch (Exception $e) {
            error_log("Erro de conexão com o banco de dados (hospedagem): " . $e->getMessage());
            throw new Exception("Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.");
        }
    }
}

// Função para obter a conexão PDO
function getPDOConnection() {
    try {
        // Tenta primeiro a conexão local
        $dsn = "mysql:host=" . DB_HOST_LOCAL . ";dbname=" . DB_NAME_LOCAL . ";charset=utf8";
        $pdo = new PDO($dsn, DB_USER_LOCAL, DB_PASS_LOCAL);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Falha na conexão PDO local, tentando conexão com hospedagem: " . $e->getMessage());
        
        try {
            // Se falhar, tenta a conexão com a hospedagem
            $dsn = "mysql:host=" . DB_HOST_HOSPEDAGEM . ";dbname=" . DB_NAME_HOSPEDAGEM . ";charset=utf8";
            $pdo = new PDO($dsn, DB_USER_HOSPEDAGEM, DB_PASS_HOSPEDAGEM);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            error_log("Erro de conexão PDO com o banco de dados (hospedagem): " . $e->getMessage());
            throw new Exception("Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.");
        }
    }
}
?> 