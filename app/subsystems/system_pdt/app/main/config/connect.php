<?php
class connect 
{
    protected $conn;

    function __construct() 
    {
        $this->connect_database();
    }

    function connect_database()
    {
        try {
            $host = 'localhost';
            $database = 'sistema_pdt';
            $user = 'root';
            $password = '';

            $this->conn = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $user, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // $host = 'localhost';
            // $database = 'u750204740_demandas';
            // $user = 'u750204740_demandas';
            // $password = 'Demandas!@#$2026';

            // $this->conn = new PDO('mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8', $user, $password);
            // $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erro! O sistema não possui conexão com banco de dados.');
        }
    }

    function getConnection() 
    {
        return $this->conn;
    }
}

$db = new connect();
$db->getConnection();


?>