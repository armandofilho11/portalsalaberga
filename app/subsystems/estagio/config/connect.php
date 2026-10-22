<?php


define('LOCAL_HOST', 'localhost');
define('LOCAL_DATABASE', 'u750204740_estagio2k25');
define('LOCAL_USER', 'root');
define('LOCAL_PASSWORD', '');



define('REMOTE_HOST', 'localhost');
define('REMOTE_DATABASE', 'u750204740_estagio2k25');
define('REMOTE_USER', 'u750204740_estagio2k25');
define('REMOTE_PASSWORD', 'paoComOvo123!@##');



class connect
{
    protected PDO $connect;

    function __construct()
    {
        $this->connect_database();
    }

    function connect_database()
    {
        // Tenta conectar ao banco de dados local
        try {
            $this->connect = new PDO('mysql:host=' . LOCAL_HOST . ';dbname=' . LOCAL_DATABASE . ';charset=utf8mb4', LOCAL_USER, LOCAL_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $this->connect;
        } catch (PDOException $e) {
            // Se a conexão local falhar, tenta conectar ao banco de dados remoto
            try {
                $this->connect = new PDO('mysql:host=' . REMOTE_HOST . ';dbname=' . REMOTE_DATABASE . ';charset=utf8mb4', REMOTE_USER, REMOTE_PASSWORD, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
                return $this->connect;
            } catch (PDOException $e) {
                die('Erro! O sistema não possui conexão com nenhum banco de dados.');
            }
        }
    }

    function getConnection() {
        return $this->connect;
    }
}
?>