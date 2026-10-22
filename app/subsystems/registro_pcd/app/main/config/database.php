<?php
class Database
{
    protected $db;

    public function __construct()
    {
        $this->connect_database();
    }
    function connect_database()
    {
        try {
            $dsn = "mysql:host=localhost;dbname=registro_pcd;charset=utf8mb4";
            $username = "root";
            $password = "";
            $this->db = new PDO($dsn, $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $dsn = "mysql:host=localhost;dbname=u750204740_registro_pcd;charset=utf8mb4";
            $username = "u750204740_registro_pcd";
            $password = "paoComOvo123!@##";
            $this->db = new PDO($dsn, $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }
}
