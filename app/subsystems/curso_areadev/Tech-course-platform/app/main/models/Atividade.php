<?php

require_once __DIR__ . '/../config/Database.php';

class Atividade {
    private $conn;
    private $table = 'atividades';
    
    public $id;
    public $modulo_id;
    public $titulo;
    public $descricao;
    public $tipo;
    public $ordem;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function buscarPorModulo($modulo_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE modulo_id = :modulo_id AND ativo = 1 
                  ORDER BY ordem ASC";
        
        // Debug da query
        error_log("DEBUG Atividade::buscarPorModulo - Query: " . $query . " - modulo_id: " . $modulo_id);
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':modulo_id', $modulo_id);
        $stmt->execute();
        
        $resultados = $stmt->fetchAll();
        error_log("DEBUG Atividade::buscarPorModulo - Resultados encontrados: " . count($resultados));
        
        return $resultados;
    }
    
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function buscarQuestoes($atividade_id) {
        // Busca a atividade e extrai as perguntas do JSON
        $atividade = $this->buscarPorId($atividade_id);
        if (!$atividade || !$atividade['conteudo']) {
            return [];
        }
        
        $conteudo = json_decode($atividade['conteudo'], true);
        if (!$conteudo || !isset($conteudo['perguntas'])) {
            return [];
        }
        
        // Processa as perguntas e adiciona IDs para referência
        $perguntas = [];
        foreach ($conteudo['perguntas'] as $index => $pergunta) {
            $pergunta['id'] = $index + 1; // ID baseado no índice
            $perguntas[] = $pergunta;
        }
        
        return $perguntas;
    }
    
    public function buscarAlternativas($pergunta_id) {
        // Não usado diretamente, as opções estão dentro das perguntas
        return [];
    }
    
    public function verificarResposta($atividade_id, $pergunta_index, $opcao_index) {
        // Busca a atividade e verifica se a opção está correta
        $atividade = $this->buscarPorId($atividade_id);
        if (!$atividade || !$atividade['conteudo']) {
            return 0;
        }
        
        $conteudo = json_decode($atividade['conteudo'], true);
        if (!$conteudo || !isset($conteudo['perguntas'][$pergunta_index])) {
            return 0;
        }
        
        $pergunta = $conteudo['perguntas'][$pergunta_index];
        if (isset($pergunta['opcoes'][$opcao_index])) {
            return $pergunta['opcoes'][$opcao_index]['correta'] ? 1 : 0;
        }
        
        return 0;
    }
    
   public function verificarTentativas($usuario_id, $atividade_id) {
    $query = "SELECT pontuacao, respostas FROM respostas_usuarios 
              WHERE usuario_id = :usuario_id AND atividade_id = :atividade_id 
              LIMIT 1";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':atividade_id', $atividade_id);
    $stmt->execute();
    $resultado = $stmt->fetch();
    
    if (!$resultado) {
        return [
            'pode_tentar' => true,
            'ja_fez' => false,
            'pode_refazer' => false
        ];
    }
    
    $nota = $resultado['pontuacao'] ?? 0;
    $pode_refazer = $nota < 100;
    
    return [
        'pode_tentar' => $pode_refazer,
        'ja_fez' => true,
        'pode_refazer' => $pode_refazer,
        'nota_atual' => $nota
    ];
}
    
    // Sistema de recompensas removido - atividades não dão mais coins ou XP
    
    public function salvarResposta($usuario_id, $atividade_id, $respostas_array) {
        // Verifica se pode tentar
        $verificacao = $this->verificarTentativas($usuario_id, $atividade_id);
        if (!$verificacao['pode_tentar']) {
            return false;
        }
        // $respostas_array deve ser um array com as respostas
        // Formato: [{'pergunta_id': 0, 'opcao_id': 0}, ...]
        
        // Busca a atividade para validar as respostas
        $atividade = $this->buscarPorId($atividade_id);
        if (!$atividade || !$atividade['conteudo']) {
            return false;
        }
        
        $conteudo = json_decode($atividade['conteudo'], true);
        if (!$conteudo || !isset($conteudo['perguntas'])) {
            return false;
        }
        
        // Calcula pontuação total
        $total_perguntas = count($conteudo['perguntas']);
        $acertos = 0;
        $respostas_json = [];
        
        foreach ($respostas_array as $resposta) {
            $pergunta_index = isset($resposta['pergunta_id']) ? (int)$resposta['pergunta_id'] : (isset($resposta['pergunta_index']) ? (int)$resposta['pergunta_index'] : -1);
            $opcao_index = isset($resposta['opcao_id']) ? (int)$resposta['opcao_id'] : (isset($resposta['opcao_index']) ? (int)$resposta['opcao_index'] : -1);
            
            if ($pergunta_index >= 0 && $opcao_index >= 0 && isset($conteudo['perguntas'][$pergunta_index])) {
                $pergunta = $conteudo['perguntas'][$pergunta_index];
                if (isset($pergunta['opcoes'][$opcao_index])) {
                    $correta = $pergunta['opcoes'][$opcao_index]['correta'] ?? false;
                    if ($correta) {
                        $acertos++;
                    }
                    
                    $respostas_json[] = [
                        'pergunta_index' => $pergunta_index,
                        'opcao_index' => $opcao_index,
                        'correta' => $correta
                    ];
                }
            }
        }
        
        $pontuacao = $total_perguntas > 0 ? ($acertos / $total_perguntas) * 100 : 0;
        
        // Verifica se já existe resposta para esta atividade
        $query = "SELECT id, respostas, tentativas FROM respostas_usuarios 
                  WHERE usuario_id = :usuario_id AND atividade_id = :atividade_id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':atividade_id', $atividade_id);
        $stmt->execute();
        $existe = $stmt->fetch();
        
        $respostas_json_string = json_encode($respostas_json);
        $nova_tentativa = ($existe && isset($existe['tentativas'])) ? (int)$existe['tentativas'] + 1 : 1;
        
        if ($existe) {
            // Atualiza resposta existente e incrementa tentativas
            $query = "UPDATE respostas_usuarios 
                      SET respostas = :respostas, 
                          pontuacao = :pontuacao,
                          tentativas = :tentativas,
                          ultima_tentativa = NOW(),
                          data_resposta = NOW()
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':respostas', $respostas_json_string);
            $stmt->bindParam(':pontuacao', $pontuacao);
            $stmt->bindParam(':tentativas', $nova_tentativa);
            $stmt->bindParam(':id', $existe['id']);
            
            return $stmt->execute();
        } else {
            // Insere nova resposta
            $query = "INSERT INTO respostas_usuarios 
                      (usuario_id, atividade_id, respostas, pontuacao, tentativas, ultima_tentativa) 
                      VALUES (:usuario_id, :atividade_id, :respostas, :pontuacao, 1, NOW())";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':atividade_id', $atividade_id);
            $stmt->bindParam(':respostas', $respostas_json_string);
            $stmt->bindParam(':pontuacao', $pontuacao);
            
            return $stmt->execute();
        }
    }
    
    public function calcularNota($usuario_id, $atividade_id) {
        // Busca a resposta do usuário
        $query = "SELECT respostas, pontuacao FROM respostas_usuarios 
                  WHERE usuario_id = :usuario_id AND atividade_id = :atividade_id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':atividade_id', $atividade_id);
        $stmt->execute();
        $resultado = $stmt->fetch();
        
        if ($resultado && isset($resultado['pontuacao'])) {
            return (float)$resultado['pontuacao'];
        }
        
        return 0;
    }
    
    public function verificarRespostasUsuario($usuario_id, $atividade_id) {
        // Busca a resposta do usuário no formato JSON
        $query = "SELECT respostas, pontuacao FROM respostas_usuarios 
                  WHERE usuario_id = :usuario_id AND atividade_id = :atividade_id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':atividade_id', $atividade_id);
        $stmt->execute();
        $resultado = $stmt->fetch();
        
        if ($resultado && $resultado['respostas']) {
            $respostas_json = json_decode($resultado['respostas'], true);
            if ($respostas_json) {
                // Converte para formato indexado por pergunta_index
                $respostas_formatadas = [];
                foreach ($respostas_json as $resposta) {
                    $pergunta_index = isset($resposta['pergunta_index']) ? $resposta['pergunta_index'] : (isset($resposta['pergunta_id']) ? $resposta['pergunta_id'] : null);
                    if ($pergunta_index !== null) {
                        $respostas_formatadas[$pergunta_index] = $resposta;
                    }
                }
                return $respostas_formatadas;
            }
        }
        
        return [];
    }
    
    // Métodos de recompensa removidos - atividades não dão mais coins ou XP
    
    public function buscarAtividadesDoUsuario($usuario_id) {
        $query = "SELECT DISTINCT a.*, m.curso_id, c.titulo as curso_titulo
                  FROM atividades a
                  INNER JOIN modulos m ON a.modulo_id = m.id
                  INNER JOIN cursos c ON m.curso_id = c.id
                  INNER JOIN compras_cursos cc ON c.id = cc.curso_id
                  WHERE cc.usuario_id = :usuario_id 
                  AND a.ativo = 1
                  ORDER BY a.ordem ASC
                  LIMIT 10";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        
        $result = $stmt->fetchAll();
        return $result ? $result : [];
    }
}
