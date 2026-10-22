<?php
require_once('../config/connect.php');

class select_model extends connect
{

    function __construct()
    {
        parent::__construct();
    }

    //selects especificos
    public function select_turma()
    {
        $stmt_turma = $this->connect->query("SELECT t.turma_id, t.nome_turma, c.nome_curso FROM turmas t 
                    INNER JOIN cursos c ON c.curso_id = t.curso_id");
        $result = $stmt_turma->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    public function select_curso(){
        $stmt_cursos = $this->connect->query("SELECT * FROM cursos");
        $result = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    //rifas
    public function controle_turma()
    {
        $stmt_turma = $this->connect->query(
            "SELECT t.turma_id, t.nome_turma, c.nome_curso, rifa.valor_arrecadado, rifa.quantidades_rifas, a.nome
            FROM turmas t
            INNER JOIN cursos c ON c.curso_id = t.curso_id
            INNER JOIN tarefa_01_rifas rifa ON rifa.turma_id = t.turma_id
            INNER JOIN avaliadores a ON a.id = rifa.id_usuario
            "
        );
        $result = $stmt_turma->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    public function select_resumo_turma() {}
}
