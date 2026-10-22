<?php
require_once('../models/select_model.php');
require_once('../models/sessions.php');

// Inicializar sessão
$session = new sessions;
$session->autenticar_session();

// Inicializar modelo
$select_model = new select_model();

// Obter parâmetros da busca
$search = isset($_GET['search']) ? $_GET['search'] : '';
$perfil = isset($_GET['perfil']) ? $_GET['perfil'] : '';

// Buscar alunos
$alunos = $select_model->alunos_aptos_curso($perfil, $search);

// Retornar resultados em JSON
header('Content-Type: application/json');
echo json_encode($alunos); 