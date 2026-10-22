<?php

require_once __DIR__ . '/../config/Database.php';

class Video {
    private $conn;
    private $table = 'videos';
    
    public $id;
    public $modulo_id;
    public $titulo;
    public $descricao;
    public $drive_file_id;
    public $drive_embed_link;
    public $ordem;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function buscarPorModulo($modulo_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE modulo_id = :modulo_id AND ativo = 1 
                  ORDER BY ordem ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':modulo_id', $modulo_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function marcarComoAssistido($usuario_id, $video_id, $progresso = 100) {
        $query = "INSERT INTO videos_assistidos (usuario_id, video_id, progresso) 
                  VALUES (:usuario_id, :video_id, :progresso)
                  ON DUPLICATE KEY UPDATE progresso = :progresso, data_assistido = NOW()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':video_id', $video_id);
        $stmt->bindParam(':progresso', $progresso);
        
        return $stmt->execute();
    }
    
    public function verificarSeAssistido($usuario_id, $video_id) {
        $query = "SELECT progresso FROM videos_assistidos 
                  WHERE usuario_id = :usuario_id AND video_id = :video_id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':video_id', $video_id);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $result['progresso'] : 0;
    }
    
    // Sistema de recompensas removido - vídeos não dão mais coins ou XP
}
