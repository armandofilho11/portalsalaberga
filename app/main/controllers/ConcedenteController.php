<?php

require_once '../models/Concedente.php';

class ConcedenteController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new Concedente($db);
    }

    public function store()
    {
        $dados = $_POST;

        $this->model->salvar($dados);

        header("Location: index.php?action=pdf&id=".$db->lastInsertId());
    }

    public function pdf($id)
    {
        $dados = $this->model->buscarPorId($id);

        require '../views/concedentes/pdf.php';
    }
}