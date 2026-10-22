<?php 

class connect
{
    protected $conexao;

    function __construct()
    {
        $this->connect_database();
    }
    function connect_database()
    {
        try {
            $host= 'localhost';
            $database= 'questoes';
            $user= 'root';
            $password= '';
            
            $this->conexao = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
        } catch (PDOException $e) {
            $host= 'localhost';
            $database= 'u750204740_questoes';
            $user= 'u750204740_questoes';
            $password= 'paoComOvo123!@##';
            $this->conexao = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
        }catch (PDOException $e) {

            die('Erro! O sistema não possui conexão com o banco de dados.');
        }
    }
}

?>