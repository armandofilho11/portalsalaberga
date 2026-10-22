<?php

require_once __DIR__ . '/../models/Curso.php';

class ApiController {
    
    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }
    
    public function progresso($curso_id) {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
            return;
        }
        
        try {
            $cursoModel = new Curso();
            $tem_acesso = $cursoModel->verificarAcesso($_SESSION['usuario_id'], $curso_id);
            
            if (!$tem_acesso) {
                echo json_encode(['success' => false, 'message' => 'Acesso negado ao curso']);
                return;
            }
            
            $progresso = $cursoModel->calcularProgresso($_SESSION['usuario_id'], $curso_id);
            
            echo json_encode([
                'success' => true,
                'progresso' => $progresso
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao calcular progresso: ' . $e->getMessage()]);
        }
    }
}