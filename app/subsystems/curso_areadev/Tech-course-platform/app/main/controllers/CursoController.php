<?php

require_once __DIR__ . '/../models/Curso.php';
require_once __DIR__ . '/../models/Usuario.php';

class CursoController {
    
    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }
    
    public function index() {
        $cursoModel = new Curso();
        $cursos = $cursoModel->listarTodos($_SESSION['usuario_nivel'], $_SESSION['usuario_id']);

        require_once __DIR__ . '/../views/cursos/index.php';
    }
    
    public function detalhes($id) {
        $cursoModel = new Curso();
        $curso = $cursoModel->buscarPorId($id);
        
        if (!$curso) {
            $_SESSION['erro'] = 'Curso não encontrado!';
            header('Location: ' . BASE_PATH . '/cursos');
            exit;
        }
        
        $modulos = $cursoModel->buscarModulos($id);
        $tem_acesso = $cursoModel->verificarAcesso($_SESSION['usuario_id'], $id);
        $progresso = 0;
        
        if ($tem_acesso) {
            $progresso = $cursoModel->calcularProgresso($_SESSION['usuario_id'], $id);
            
            // Verifica se concluiu 100% para mostrar mensagem de parabéns
            if ($progresso >= 100 && !isset($_SESSION['parabens_conclusao_' . $id])) {
                $_SESSION['sucesso'] = 'Parabéns! Você concluiu 100% do curso!';
                $_SESSION['parabens_conclusao_' . $id] = true;
                
                // Promove nível do usuário conforme o nível do curso concluído
                if (isset($curso['nivel_requerido'])) {
                    $nivel_atual = $_SESSION['usuario_nivel'] ?? 'iniciante';
                    $nivel_curso = $curso['nivel_requerido'];
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
        
        require_once __DIR__ . '/../views/cursos/detalhes.php';
    }
    
    public function comprar($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/curso/' . $id);
            exit;
        }
        
        $cursoModel = new Curso();
        $curso = $cursoModel->buscarPorId($id);
        
        if (!$curso) {
            $_SESSION['erro'] = 'Curso não encontrado!';
            header('Location: ' . BASE_PATH . '/cursos');
            exit;
        }
        
        // Verifica se o usuário tem nível suficiente para o curso
        if (!$cursoModel->verificarAcessoNivel($_SESSION['usuario_nivel'], $curso['nivel_requerido'])) {
            $_SESSION['erro'] = 'Você não tem nível suficiente para este curso! Você precisa ser nível ' . ucfirst($curso['nivel_requerido']) . ' ou superior.';
            header('Location: ' . BASE_PATH . '/curso/' . $id);
            exit;
        }
        
        if ($cursoModel->verificarAcesso($_SESSION['usuario_id'], $id)) {
            $_SESSION['erro'] = 'Você já possui este curso!';
            header('Location: ' . BASE_PATH . '/curso/' . $id);
            exit;
        }
        
        // Verifica se concluiu o curso anterior (se houver)
        if (!$cursoModel->verificarCursoAnteriorConcluido($_SESSION['usuario_id'], $id)) {
            // Busca informações do curso anterior
            $curso_anterior = $cursoModel->buscarCursoAnterior($id);
            
            if ($curso_anterior) {
                $tem_acesso_anterior = $cursoModel->verificarAcesso($_SESSION['usuario_id'], $curso_anterior['id']);
                if ($tem_acesso_anterior) {
                    // Verifica o progresso do curso anterior
                    $progresso_anterior = $cursoModel->calcularProgresso($_SESSION['usuario_id'], $curso_anterior['id']);
                    $_SESSION['erro'] = 'Você precisa concluir 100% do curso "' . htmlspecialchars($curso_anterior['titulo']) . '" antes de acessar este curso! Seu progresso atual: ' . round($progresso_anterior, 1) . '%.';
                } else {
                    $_SESSION['erro'] = 'Você precisa concluir o curso "' . htmlspecialchars($curso_anterior['titulo']) . '" antes de acessar este curso!';
                }
            } else {
                // Não há curso anterior (é o primeiro curso), mas a verificação falhou
                // Isso não deveria acontecer, mas permite o acesso por segurança
                // Não bloqueia o acesso se não há curso anterior
            }
            
            // Se há um curso anterior e a verificação falhou, bloqueia o acesso
            if ($curso_anterior) {
                header('Location: ' . BASE_PATH . '/curso/' . $id);
                exit;
            }
        }
        
        // Registra o acesso ao curso (sem custo de coins)
        if ($cursoModel->registrarAcessoCurso($_SESSION['usuario_id'], $id)) {
            $_SESSION['sucesso'] = 'Acesso ao curso liberado com sucesso!';
            header('Location: ' . BASE_PATH . '/curso/' . $id);
            exit;
        }
        
        $_SESSION['erro'] = 'Erro ao liberar acesso ao curso. Tente novamente!';
        header('Location: ' . BASE_PATH . '/curso/' . $id);
        exit;
    }
    
    public function meusCursos() {
        $cursoModel = new Curso();
        $cursos = $cursoModel->buscarCursosComprados($_SESSION['usuario_id']);
        
        require_once __DIR__ . '/../views/cursos/meus-cursos.php';
    }
}
