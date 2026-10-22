<?php

require_once __DIR__ . '/../config/Database.php';

class Curso {
    private $conn;
    private $table = 'cursos';
    
    public $id;
    public $titulo;
    public $descricao;
    public $imagem_capa;
    public $nivel_requerido;
    public $ativo;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function criar() {
        $query = "INSERT INTO " . $this->table . " 
                  (titulo, descricao, imagem_capa, nivel_requerido, ativo) 
                  VALUES (:titulo, :descricao, :imagem_capa, :nivel_requerido, :ativo)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':imagem_capa', $this->imagem_capa);
        $stmt->bindParam(':nivel_requerido', $this->nivel_requerido);
        $stmt->bindParam(':ativo', $this->ativo);
        
        return $stmt->execute();
    }
    
    public function listarTodos($nivel_usuario = null, $usuario_id = null) {
        // Mostra todos os cursos ativos e, se fornecido, indica se o usuário já comprou cada curso
        if ($usuario_id) {
            $query = "SELECT c.*,
                      CASE WHEN cc.id IS NOT NULL THEN 1 ELSE 0 END as comprado
                      FROM " . $this->table . " c
                      LEFT JOIN compras_cursos cc ON c.id = cc.curso_id AND cc.usuario_id = :usuario_id
                      WHERE c.ativo = 1
                      ORDER BY c.data_criacao DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
        } else {
            $query = "SELECT *, 0 as comprado FROM " . $this->table . " WHERE ativo = 1 ORDER BY data_criacao DESC";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function verificarAcessoNivel($nivel_usuario, $nivel_requerido) {
        $niveis = ['iniciante' => 1, 'intermediario' => 2, 'avancado' => 3, 'premium' => 4];
        $nivel_usuario_num = $niveis[$nivel_usuario] ?? 1;
        $nivel_requerido_num = $niveis[$nivel_requerido] ?? 1;
        
        return $nivel_usuario_num >= $nivel_requerido_num;
    }
    
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function buscarModulos($curso_id) {
        $query = "SELECT * FROM modulos WHERE curso_id = :curso_id ORDER BY ordem ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function verificarAcesso($usuario_id, $curso_id) {
        $query = "SELECT id FROM compras_cursos 
                  WHERE usuario_id = :usuario_id AND curso_id = :curso_id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    public function registrarAcessoCurso($usuario_id, $curso_id) {
        try {
            $query = "INSERT INTO compras_cursos (usuario_id, curso_id) 
                      VALUES (:usuario_id, :curso_id)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':curso_id', $curso_id);
            
            return $stmt->execute();
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function buscarCursosComprados($usuario_id) {
        $query = "SELECT c.*, cc.data_compra 
                  FROM " . $this->table . " c
                  INNER JOIN compras_cursos cc ON c.id = cc.curso_id
                  WHERE cc.usuario_id = :usuario_id
                  ORDER BY cc.data_compra DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    public function calcularProgresso($usuario_id, $curso_id) {
        // Conta total de vídeos
        $query = "SELECT COUNT(DISTINCT v.id) as total_videos
                  FROM videos v
                  INNER JOIN modulos m ON v.modulo_id = m.id
                  WHERE m.curso_id = :curso_id AND v.ativo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->execute();
        $total_videos = $stmt->fetch();
        
        // Conta vídeos assistidos
        $query = "SELECT COUNT(DISTINCT va.video_id) as videos_assistidos
                  FROM videos_assistidos va
                  INNER JOIN videos v ON va.video_id = v.id
                  INNER JOIN modulos m ON v.modulo_id = m.id
                  WHERE m.curso_id = :curso_id AND va.usuario_id = :usuario_id
                  AND va.progresso >= 90";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $videos_assistidos = $stmt->fetch();
        
        // Conta total de atividades
        $query = "SELECT COUNT(DISTINCT a.id) as total_atividades
                  FROM atividades a
                  INNER JOIN modulos m ON a.modulo_id = m.id
                  WHERE m.curso_id = :curso_id AND a.ativo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->execute();
        $total_atividades = $stmt->fetch();
        
        // Conta atividades concluídas (com resposta salva)
        // Uma atividade é considerada concluída se tem resposta salva na tabela respostas_usuarios
        $query = "SELECT COUNT(DISTINCT ru.atividade_id) as atividades_concluidas
                  FROM respostas_usuarios ru
                  INNER JOIN atividades a ON ru.atividade_id = a.id
                  INNER JOIN modulos m ON a.modulo_id = m.id
                  WHERE m.curso_id = :curso_id 
                  AND ru.usuario_id = :usuario_id
                  AND a.ativo = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':curso_id', $curso_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $atividades_concluidas = $stmt->fetch();
        
        // Calcula progresso total
        $total_itens = ($total_videos['total_videos'] ?? 0) + ($total_atividades['total_atividades'] ?? 0);
        $itens_concluidos = ($videos_assistidos['videos_assistidos'] ?? 0) + ($atividades_concluidas['atividades_concluidas'] ?? 0);
        
        if ($total_itens > 0) {
            return round(($itens_concluidos / $total_itens) * 100, 2);
        }
        
        return 0;
    }
    
    public function verificarCursoConcluido($usuario_id, $curso_id) {
        $progresso = $this->calcularProgresso($usuario_id, $curso_id);
        return $progresso >= 100;
    }
    
    public function verificarCursoAnteriorConcluido($usuario_id, $curso_id_atual) {
        // Busca o curso atual para obter seu nível
        $curso_atual = $this->buscarPorId($curso_id_atual);
        if (!$curso_atual) {
            return true; // Se não encontrar, permite por segurança
        }
        
        // Ordena cursos por nível (iniciante primeiro) e depois por ID
        // Níveis: iniciante=1, intermediario=2, avancado=3
        $niveis = ['iniciante' => 1, 'intermediario' => 2, 'avancado' => 3];
        
        // Busca todos os cursos ordenados por nível e ID
        $query = "SELECT id, nivel_requerido FROM " . $this->table . " 
                  WHERE ativo = 1 
                  ORDER BY 
                    CASE nivel_requerido 
                      WHEN 'iniciante' THEN 1 
                      WHEN 'intermediario' THEN 2 
                      WHEN 'avancado' THEN 3 
                      ELSE 4 
                    END ASC, 
                    id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $todos_cursos = $stmt->fetchAll();
        
        // Encontra a posição do curso atual na ordem
        $posicao_atual = -1;
        foreach ($todos_cursos as $index => $curso) {
            if ($curso['id'] == $curso_id_atual) {
                $posicao_atual = $index;
                break;
            }
        }
        
        // Se é o primeiro curso na ordem (índice 0), sempre permite
        if ($posicao_atual === 0) {
            return true;
        }
        
        // Se não encontrou o curso ou está em posição inválida, permite por segurança
        if ($posicao_atual < 0) {
            return true;
        }
        
        // Busca o curso anterior na ordem
        $curso_anterior_id = $todos_cursos[$posicao_atual - 1]['id'];
        
        // Verifica se tem acesso ao curso anterior
        $tem_acesso = $this->verificarAcesso($usuario_id, $curso_anterior_id);
        
        if (!$tem_acesso) {
            // Não comprou o curso anterior - precisa comprar primeiro
            return false;
        }
        
        // Verifica se concluiu 100% do curso anterior
        $concluido = $this->verificarCursoConcluido($usuario_id, $curso_anterior_id);
        
        return $concluido;
    }
    
    public function buscarCursoAnterior($curso_id_atual) {
        // Ordena cursos por nível (iniciante primeiro) e depois por ID
        $query = "SELECT id, titulo, nivel_requerido FROM " . $this->table . " 
                  WHERE ativo = 1 
                  ORDER BY 
                    CASE nivel_requerido 
                      WHEN 'iniciante' THEN 1 
                      WHEN 'intermediario' THEN 2 
                      WHEN 'avancado' THEN 3 
                      ELSE 4 
                    END ASC, 
                    id ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $todos_cursos = $stmt->fetchAll();
        
        // Encontra a posição do curso atual
        $posicao_atual = -1;
        foreach ($todos_cursos as $index => $curso) {
            if ($curso['id'] == $curso_id_atual) {
                $posicao_atual = $index;
                break;
            }
        }
        
        // Se é o primeiro curso, não há curso anterior
        if ($posicao_atual <= 0) {
            return null;
        }
        
        // Retorna o curso anterior
        return $todos_cursos[$posicao_atual - 1];
    }
    
    // Sistema de recompensas removido - cursos agora são gratuitos baseado apenas no nível
}
