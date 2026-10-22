<?php

require_once __DIR__ . '/../models/Video.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Curso.php';

class VideoController {
    
    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }
    
    public function assistir($id) {
        $videoModel = new Video();
        $cursoModel = new Curso();
        
        $video = $videoModel->buscarComModuloECurso($id);
        
        if (!$video) {
            $_SESSION['erro'] = 'Vídeo não encontrado!';
            header('Location: ' . BASE_PATH . '/cursos');
            exit;
        }
        
        $tem_acesso = $cursoModel->verificarAcesso($_SESSION['usuario_id'], $video['curso_id']);
        
        if (!$tem_acesso) {
            $_SESSION['erro'] = 'Você não tem acesso a este curso!';
            header('Location: ' . BASE_PATH . '/curso/' . $video['curso_id']);
            exit;
        }
        
        $progresso = $videoModel->verificarSeAssistido($_SESSION['usuario_id'], $id);
        
        $videos_modulo = $videoModel->buscarVideosDoModulo($video['modulo_id']);
        $proximo_video = $videoModel->buscarProximoVideo($video['modulo_id'], $video['ordem']);
        $video_anterior = $videoModel->buscarVideoAnterior($video['modulo_id'], $video['ordem']);
        
        $curso = $cursoModel->buscarPorId($video['curso_id']);
        
        require_once __DIR__ . '/../views/videos/assistir.php';
    }
    
    public function visualizar($id) {
        header('Content-Type: application/json');
        
        $videoModel = new Video();
        $cursoModel = new Curso();
        
        $video = $videoModel->buscarComModuloECurso($id);
        
        if (!$video) {
            echo json_encode(['success' => false, 'message' => 'Vídeo não encontrado!']);
            exit;
        }
        
        $tem_acesso = $cursoModel->verificarAcesso($_SESSION['usuario_id'], $video['curso_id']);
        
        if (!$tem_acesso) {
            echo json_encode(['success' => false, 'message' => 'Você não tem acesso a este curso!']);
            exit;
        }
        
        $progresso = $videoModel->verificarSeAssistido($_SESSION['usuario_id'], $id);
        
        $videos_modulo = $videoModel->buscarVideosDoModulo($video['modulo_id']);
        $proximo_video = $videoModel->buscarProximoVideo($video['modulo_id'], $video['ordem']);
        $video_anterior = $videoModel->buscarVideoAnterior($video['modulo_id'], $video['ordem']);
        
        echo json_encode([
            'success' => true,
            'video' => $video,
            'progresso' => $progresso,
            'proximo_video' => $proximo_video,
            'video_anterior' => $video_anterior,
            'videos_modulo' => $videos_modulo
        ]);
        exit;
    }
    
    public function marcarAssistido() {
    header('Content-Type: application/json');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método inválido']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    $video_id = $data['video_id'] ?? $_POST['video_id'] ?? 0;
    $progresso = $data['progresso'] ?? $_POST['progresso'] ?? 100;
    
    $videoModel = new Video();
    
    if ($videoModel->marcarComoAssistido($_SESSION['usuario_id'], $video_id, $progresso)) {
        $response = [
            'success' => true, 
            'message' => 'Vídeo marcado como assistido!'
        ];
        
        echo json_encode($response);
        exit;
    }
    
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar progresso']);
    exit;
}
}