<?php

session_start();

// Define o caminho base dinamicamente, compatível com local e hospedagem
// Ex: '/Tech-course-platform/app/main' (local) ou '/app/main' (hospedagem)
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_PATH', $basePath === '/' ? '' : $basePath);
define('APP_NAME', 'Tech Course Platform');

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/CursoController.php';
require_once __DIR__ . '/controllers/VideoController.php';
require_once __DIR__ . '/controllers/AtividadeController.php';
require_once __DIR__ . '/controllers/ApiController.php';

$request_uri = $_SERVER['REQUEST_URI'];

// Remove o caminho base da URI
$path = str_replace(BASE_PATH, '', $request_uri);
// Remove query string
$path = strtok($path, '?');
// Remove barras do início e fim
$path = trim($path, '/');

$segments = explode('/', $path);
$route = $segments[0] ?? '';
$param = $segments[1] ?? null;

switch ($route) {
    case '':
    case 'login':
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_PATH . '/dashboard');
            exit;
        }
        $controller = new AuthController();
        $controller->login();
        break;
        
    case 'registro':
        if (isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_PATH . '/dashboard');
            exit;
        }
        $controller = new AuthController();
        $controller->registro();
        break;
        
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case 'cursos':
        $controller = new CursoController();
        $controller->index();
        break;
        
    case 'curso':
        if ($param) {
            $controller = new CursoController();
            $controller->detalhes($param);
        } else {
            header('Location: ' . BASE_PATH . '/cursos');
        }
        break;
        
    case 'comprar-curso':
        if ($param) {
            $controller = new CursoController();
            $controller->comprar($param);
        } else {
            header('Location: ' . BASE_PATH . '/cursos');
        }
        break;
        
    case 'meus-cursos':
        $controller = new CursoController();
        $controller->meusCursos();
        break;
        
    case 'video':
        if ($param) {
            $controller = new VideoController();
            $controller->assistir($param);
        } else {
            header('Location: ' . BASE_PATH . '/cursos');
        }
        break;
        
    case 'video-info':
        if ($param) {
            $controller = new VideoController();
            $controller->visualizar($param);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID do vídeo não fornecido']);
        }
        break;
        
    case 'marcar-video-assistido':
        $controller = new VideoController();
        $controller->marcarAssistido();
        break;
        
    case 'atividades':
        $controller = new AtividadeController();
        $controller->listar();
        break;

    case 'atividade':
        if ($param) {
            $controller = new AtividadeController();
            $controller->visualizar($param);
        } else {
            header('Location: ' . BASE_PATH . '/cursos');
        }
        break;
        
    case 'responder-atividade':
        $controller = new AtividadeController();
        $controller->responder();
        break;
        
    case 'api':
        if ($param === 'progresso' && isset($segments[2])) {
            $controller = new ApiController();
            $controller->progresso($segments[2]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Endpoint não encontrado']);
        }
        break;
        
    default:
        header('HTTP/1.0 404 Not Found');
        echo '<!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Página não encontrada</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }
                .error-container {
                    text-align: center;
                }
                h1 {
                    font-size: 72px;
                    margin: 0;
                }
                p {
                    font-size: 24px;
                }
                a {
                    color: white;
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>404</h1>
                <p>Página não encontrada</p>
                <a href="' . BASE_PATH . '/dashboard">Voltar ao início</a>
            </div>
        </body>
        </html>';
        break;
}