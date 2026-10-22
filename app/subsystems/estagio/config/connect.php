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
    protected $connect;

    function __construct()
    {
        $this->connect_database();
    }

    function connect_database()
    {
        // Tenta conectar ao banco de dados local
        try {
            $this->connect = new PDO('mysql:host=' . LOCAL_HOST . ';dbname=' . LOCAL_DATABASE, LOCAL_USER, LOCAL_PASSWORD);
            return $this->connect;
        } catch (PDOException $e) {
            // Se a conexão local falhar, tenta conectar ao banco de dados remoto
            try {
                $this->connect = new PDO('mysql:host=' . REMOTE_HOST . ';dbname=' . REMOTE_DATABASE, REMOTE_USER, REMOTE_PASSWORD);
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