<?php

require_once __DIR__ . '/../models/Atividade.php';
require_once __DIR__ . '/../models/Usuario.php';

class AtividadeController {
    
    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }

    public function listar() {
        $atividadeModel = new Atividade();
        $atividades = $atividadeModel->buscarAtividadesDoUsuario($_SESSION['usuario_id']);

        require_once __DIR__ . '/../views/atividades/listar.php';
    }
    
    public function visualizar($id) {
        header('Content-Type: application/json');

        $atividadeModel = new Atividade();
        $atividade = $atividadeModel->buscarPorId($id);

        if (!$atividade) {
            echo json_encode(['success' => false, 'message' => 'Atividade não encontrada!']);
            exit;
        }

        // Verificar acesso ao curso
        require_once __DIR__ . '/../models/Curso.php';
        $cursoModel = new Curso();

        $query = "SELECT m.curso_id FROM modulos m
                  INNER JOIN atividades a ON m.id = a.modulo_id
                  WHERE a.id = :atividade_id LIMIT 1";
        $stmt = $atividadeModel->getConnection()->prepare($query);
        $stmt->bindParam(':atividade_id', $id);
        $stmt->execute();
        $modulo = $stmt->fetch();

        $tem_acesso = false;
        if ($modulo) {
            $tem_acesso = $cursoModel->verificarAcesso($_SESSION['usuario_id'], $modulo['curso_id']);
        }

        if (!$tem_acesso) {
            echo json_encode(['success' => false, 'message' => 'Você não tem acesso a esta atividade!']);
            exit;
        }

        // Buscar perguntas e opções (já vêm do JSON)
        $perguntas = $atividadeModel->buscarQuestoes($id);
        if (!$perguntas) {
            $perguntas = [];
        }

        // As opções já estão dentro de cada pergunta no JSON
        // Não precisa buscar separadamente

        // Buscar respostas já dadas pelo usuário
        $respostas_usuario = $atividadeModel->verificarRespostasUsuario($_SESSION['usuario_id'], $id);
        if (!$respostas_usuario) {
            $respostas_usuario = [];
        }

        // Calcular nota atual
        $nota = $atividadeModel->calcularNota($_SESSION['usuario_id'], $id);
        if ($nota === null) {
            $nota = 0;
        }

        // Sistema de recompensas removido - atividades não dão mais coins ou XP
        
        // Verificar tentativas disponíveis
        $tentativas_info = $atividadeModel->verificarTentativas($_SESSION['usuario_id'], $id);
        
        // Verifica se já fez a atividade
        $ja_fez = !empty($respostas_usuario);

        echo json_encode([
            'success' => true,
            'atividade' => $atividade,
            'perguntas' => $perguntas,
            'respostas_usuario' => $respostas_usuario,
            'nota' => $nota,
            'tentativas' => $tentativas_info,
            'ja_fez' => $ja_fez
        ]);
        exit;
    }
    
    public function responder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Método inválido']);
            exit;
        }
        
        header('Content-Type: application/json');
        
        // Recebe dados via JSON ou POST
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            $data = $_POST;
        }
        
        $atividade_id = $data['atividade_id'] ?? 0;
        $respostas = $data['respostas'] ?? [];
        
        if (!$atividade_id) {
            echo json_encode(['success' => false, 'message' => 'ID da atividade não fornecido']);
            exit;
        }
        
        if (empty($respostas)) {
            echo json_encode(['success' => false, 'message' => 'Nenhuma resposta foi enviada']);
            exit;
        }
        
        $atividadeModel = new Atividade();
        
        // Verifica se pode tentar
        $tentativas_info = $atividadeModel->verificarTentativas($_SESSION['usuario_id'], $atividade_id);
        
        if (!$tentativas_info['pode_tentar']) {
            $horas_restantes = isset($tentativas_info['horas_restantes']) ? $tentativas_info['horas_restantes'] : 6;
            echo json_encode([
                'success' => false, 
                'message' => 'Você já utilizou todas as 3 tentativas. Tente novamente em ' . $horas_restantes . ' hora(s).',
                'bloqueado' => true,
                'tentativas' => $tentativas_info
            ]);
            exit;
        }
        
        // Salvar todas as respostas de uma vez
        if ($atividadeModel->salvarResposta($_SESSION['usuario_id'], $atividade_id, $respostas)) {
            $nota = $atividadeModel->calcularNota($_SESSION['usuario_id'], $atividade_id);
            
            // Atualiza informações de tentativas após salvar
            $tentativas_info = $atividadeModel->verificarTentativas($_SESSION['usuario_id'], $atividade_id);
            
            // Verificar se todas as perguntas foram respondidas
            $perguntas = $atividadeModel->buscarQuestoes($atividade_id);
            $respostas_usuario = $atividadeModel->verificarRespostasUsuario($_SESSION['usuario_id'], $atividade_id);
            $total_perguntas = count($perguntas);
            $total_respostas = count($respostas_usuario);
            // Verifica se todas as perguntas foram respondidas (todas as respostas foram enviadas)
            // Como acabamos de salvar, todas as perguntas devem ter sido respondidas
            $todas_respondidas = $total_respostas >= $total_perguntas && $total_perguntas > 0;
            
            // Sistema de recompensas removido - atividades não concedem mais coins ou XP
            
            // Verifica se o curso foi concluído após esta atividade
            require_once __DIR__ . '/../models/Curso.php';
            $cursoModel = new Curso();
            
            // Busca o curso_id da atividade
            $query = "SELECT m.curso_id FROM modulos m
                      INNER JOIN atividades a ON m.id = a.modulo_id
                      WHERE a.id = :atividade_id LIMIT 1";
            $stmt = $atividadeModel->getConnection()->prepare($query);
            $stmt->bindParam(':atividade_id', $atividade_id);
            $stmt->execute();
            $modulo = $stmt->fetch();
            
            $curso_concluido = false;
            if ($modulo) {
                $progresso_curso = $cursoModel->calcularProgresso($_SESSION['usuario_id'], $modulo['curso_id']);
                $curso_concluido = ($progresso_curso >= 100);
                
                // Se o curso foi concluído, promover nível do usuário conforme o nível do curso
                if ($curso_concluido) {
                    $curso_info = $cursoModel->buscarPorId($modulo['curso_id']);
                    if ($curso_info && isset($curso_info['nivel_requerido'])) {
                        $nivel_atual = $_SESSION['usuario_nivel'] ?? 'iniciante';
                        $nivel_curso = $curso_info['nivel_requerido'];
                        // Promove apenas se o nível atual do usuário corresponde ao nível do curso concluído
                        $mapa_promocao = [
                            'iniciante' => 'intermediario',
                            'intermediario' => 'avancado',
                            'avancado' => 'premium',
                            'premium' => 'premium'
                        ];
                        
                        if ($nivel_atual === $nivel_curso) {
                            $novo_nivel = $mapa_promocao[$nivel_atual] ?? $nivel_atual;
                            if ($novo_nivel !== $nivel_atual) {
                                $usuarioModel = new Usuario();
                                if ($usuarioModel->atualizarNivel($_SESSION['usuario_id'], $novo_nivel)) {
                                    $_SESSION['usuario_nivel'] = $novo_nivel;
                                }
                            }
                        }
                    }
                }
            }
            
            echo json_encode([
                'success' => true,
                'nota' => $nota,
                'todas_respondidas' => $todas_respondidas,
                'curso_concluido' => $curso_concluido,
                'tentativas' => $tentativas_info
            ]);
            exit;
        }
        
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar resposta']);
        exit;
    }
}
