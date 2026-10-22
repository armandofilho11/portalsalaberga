<?php
class Database {
    /*
    private $host = "localhost";
    private $db_name = "u750204740_selecao_gremio";
    private $username = "u750204740_selecao_gremio";
    private $password = "paoComOvo123!";
    public $conn;  
*/
    private $host = "localhost";
    private $db_name = "selecao_gremio";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?> 