<?php
require_once('../models/select_model.php');
require_once('../models/sessions.php');

$session = new sessions;

$session->autenticar_session();

$select_model = new select_model();

// Obter parâmetros de filtro
$search = isset($_GET['search']) ? $_GET['search'] : '';
$area = isset($_GET['area']) ? $_GET['area'] : '';
$empresa = isset($_GET['empresa']) ? $_GET['empresa'] : '';

// Buscar vagas com os filtros
$vagas = $select_model->vagas($search, $area, $empresa);

// Retornar resultado em JSON
header('Content-Type: application/json');
echo json_encode($vagas); 