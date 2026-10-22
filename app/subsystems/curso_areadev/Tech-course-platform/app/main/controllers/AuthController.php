<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            
            if (empty($email) || empty($senha)) {
                $_SESSION['erro'] = 'Preencha todos os campos!';
                header('Location: ' . BASE_PATH . '/login');
                exit;
            }
            
            $usuario = new Usuario();
            
            if ($usuario->login($email, $senha)) {
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_nome'] = $usuario->nome;
                $_SESSION['usuario_email'] = $usuario->email;
                $_SESSION['usuario_nivel'] = $usuario->nivel_conta;
                
                header('Location: ' . BASE_PATH . '/dashboard');
                exit;
            } else {
                $_SESSION['erro'] = 'Email ou senha incorretos!';
                header('Location: ' . BASE_PATH . '/login');
                exit;
            }
        }
        
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $confirmar_senha = $_POST['confirmar_senha'] ?? '';
            
            if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
                $_SESSION['erro'] = 'Preencha todos os campos!';
                header('Location: ' . BASE_PATH . '/registro');
                exit;
            }
            
            if ($senha !== $confirmar_senha) {
                $_SESSION['erro'] = 'As senhas não coincidem!';
                header('Location: ' . BASE_PATH . '/registro');
                exit;
            }
            
            if (strlen($senha) < 6) {
                $_SESSION['erro'] = 'A senha deve ter no mínimo 6 caracteres!';
                header('Location: ' . BASE_PATH . '/registro');
                exit;
            }
            
            $usuario = new Usuario();
            
            if ($usuario->emailExiste($email)) {
                $_SESSION['erro'] = 'Este email já está cadastrado!';
                header('Location: ' . BASE_PATH . '/registro');
                exit;
            }
            
            $usuario->nome = $nome;
            $usuario->email = $email;
            $usuario->senha = $senha;
            $usuario->nivel_conta = 'iniciante';
            
            if ($usuario->criar()) {
                $_SESSION['sucesso'] = 'Cadastro realizado com sucesso! Faça login para continuar.';
                header('Location: ' . BASE_PATH . '/login');
                exit;
            } else {
                $_SESSION['erro'] = 'Erro ao criar conta. Tente novamente!';
                header('Location: ' . BASE_PATH . '/registro');
                exit;
            }
        }
        
        require_once __DIR__ . '/../views/auth/registro.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_PATH . '/login');
        exit;
    }
}
