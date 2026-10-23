<?php
require_once __DIR__ . '/../../app/main/models/usuario_model.php';

$modelUsuario = new usuario_model();
$usuarios = $modelUsuario->listar_usuarios();

echo json_encode($usuarios, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
