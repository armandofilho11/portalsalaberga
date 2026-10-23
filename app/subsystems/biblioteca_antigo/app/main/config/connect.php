<?php
class connect
{
    protected $connect;

    function __construct()
    {
        $this->connect_database();
    }
    function connect_database()
    {
        try {
            $host= 'localhost';
            $database= 'salaberga_biblioteca';
            $user= 'root';
            $password= '';
            
            $this->connect = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
        } catch (PDOException $e) {
            $host= 'localhost';
            $database= 'u750204740_sistBiblioteca';
            $user= 'u750204740_sistBiblioteca';
            $password= 'paoComOvo123!@##';
            $this->connect = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
        }catch (PDOException $e) {

            die('Erro! O sistema não possui conexão com o banco de dados.');
        }
    }
}
