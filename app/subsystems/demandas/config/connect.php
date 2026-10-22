<?php
class connect
{
    protected $connect_demandas;
    protected $connect_salaberga;

    function __construct()
    {
        $this->connect_database();
    }
    function connect_database()
    {
        try {
            $host = 'localhost';
            $database = 'demandas';
            $user = 'root';
            $password = '';
            $this->connect_demandas = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
            $this->connect_salaberga = 'salaberga';

        } catch (PDOException $e) {
            $host = 'localhost';
            $database = 'u750204740_demandas';
            $user = 'u750204740_demandas';
            $password = 'paoComOvo123!@##';
            $this->connect = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
            $this->connect_salaberga = 'salaberga';
        } catch (PDOException $e) {

            die('Erro! O sistema não possui conexão com o banco de dados.');
        }
    }
}
