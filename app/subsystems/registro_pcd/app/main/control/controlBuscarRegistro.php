<?php
require_once '../config/verificar_sessao.php';
require_once '../model/model.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID não fornecido']);
    exit;
}

try {
    $model = new Model();
    $registro = $model->buscarRegistroPorId($_GET['id']);
    
    if ($registro) {
        echo json_encode($registro);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Registro não encontrado']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar registro: ' . $e->getMessage()]);
} 