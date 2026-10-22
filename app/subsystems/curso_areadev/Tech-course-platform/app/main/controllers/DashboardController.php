<?php

require_once __DIR__ . '/../models/Curso.php';

class DashboardController {
    
    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }
    
    public function index() {
        $cursoModel = new Curso();
        $cursos_comprados = $cursoModel->buscarCursosComprados($_SESSION['usuario_id']);
        
        require_once __DIR__ . '/../models/Atividade.php';
        $atividadeModel = new Atividade();
        $atividades = $atividadeModel->buscarAtividadesDoUsuario($_SESSION['usuario_id']);
        
        $stats = [
            'total' => count($cursos_comprados)
        ];
        
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}
