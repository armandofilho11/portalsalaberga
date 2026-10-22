<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão direta PDO (sem require externo)
function getPDOConnection() {
    try {
        $HOST = 'localhost';
        $DATABASE = 'entradasaida';
        $USER = 'root';
        $PASSWORD = '';
        return new PDO('mysql:host=' . $HOST . ';dbname=' . $DATABASE, $USER, $PASSWORD);
    } catch (PDOException $e) {
        $HOST = 'localhost';
        $DATABASE = 'u750204740_entradasaida';
        $USER = 'u750204740_entradasaida';
        $PASSWORD = 'paoComOvo123!@##';
        try {
            return new PDO('mysql:host=' . $HOST . ';dbname=' . $DATABASE, $USER, $PASSWORD);
        } catch (PDOException $e) {
            return null;
        }
    }
}

$pdo = getPDOConnection();
if (!$pdo) {
    if (isset($_GET['action'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Erro de conexão com o banco de dados']);
        exit;
    } else {
        die('Erro de conexão com o banco de dados');
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'get_courses') {
    header('Content-Type: application/json');
    try {
        $stmt = $pdo->prepare("SELECT id_curso, curso FROM curso WHERE curso <> ''");
        $stmt->execute();
        $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($cursos);
    } catch (Exception $e) {
        echo json_encode([]);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_students') {
    header('Content-Type: application/json');
    $curso_id = intval($_GET['curso_id'] ?? 0);
    try {
        $stmt = $pdo->prepare("SELECT id_aluno, nome, matricula FROM aluno WHERE id_curso = ?");
        $stmt->execute([$curso_id]);
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($alunos);
    } catch (Exception $e) {
        echo json_encode([]);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_events') {
    header('Content-Type: application/json');
    try {
        $stmt = $pdo->prepare("SELECT id_evento AS id, nome, tipo, data_evento, horario_inicio, horario_fim, local FROM evento");
        $stmt->execute();
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $eventos_formatados = array_map(function($evento) {
            return [
                'id' => $evento['id'],
                'nome' => $evento['nome'],
                'tipo' => $evento['tipo'],
                'horario' => date('d/m/Y', strtotime($evento['data_evento'])) . ', ' . substr($evento['horario_inicio'], 0, 5) . ' às ' . substr($evento['horario_fim'], 0, 5),
                'local' => $evento['local']
            ];
        }, $eventos);
        echo json_encode($eventos_formatados);
    } catch (Exception $e) {
        echo json_encode([]);
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'save_attendance') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['presencas']) || !isset($data['evento_id'])) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
        exit;
    }
    $evento_id = intval($data['evento_id']);
    $presencas = $data['presencas'];
    try {
        foreach ($presencas as $id_aluno => $presente) {
            $stmt = $pdo->prepare("INSERT INTO frequencia_sesmated (id_aluno, presente, id_evento) VALUES (?, ?, ?)");
            $stmt->execute([intval($id_aluno), $presente ? 1 : 0, $evento_id]);
        }
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar frequência']);
    }
    exit;
}

header('Content-Type: text/html; charset=UTF-8');
require_once('../../../../main/models/sessions.php');
$session = new sessions;
$session->autenticar_session();
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequência - Palestras e Workshops</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --background-color: #0a0a0a;
            --text-color: #ffffff;
            --header-color: #00b348;
            --accent-color: #ffb733;
            --card-bg: rgba(30, 30, 30, 0.95);
            --header-bg: rgba(15, 15, 15, 0.98);
            --search-bar-bg: #1a1a1a;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: radial-gradient(ellipse at top, #1a1a1a 0%, #0a0a0a 100%);
            color: var(--text-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .header-bg {
            background: linear-gradient(135deg, var(--header-bg) 0%, rgba(0, 0, 0, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            z-index: 40;
        }
        
        .card-bg {
            background: linear-gradient(145deg, var(--card-bg) 0%, rgba(25, 25, 25, 0.95) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* CARDS DE EVENTOS - RESPONSIVIDADE APRIMORADA */
        .event-card {
            background: linear-gradient(145deg, rgba(40, 40, 40, 0.9) 0%, rgba(30, 30, 30, 0.9) 100%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            border-radius: clamp(0.75rem, 2vw, 1rem);
            padding: clamp(1rem, 3vw, 1.5rem);
            transition: box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            min-height: clamp(100px, 15vw, 140px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .event-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 179, 72, 0.05) 0%, rgba(255, 183, 51, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }
        
        .event-card:hover {
            border-color: rgba(0, 179, 72, 0.4);
            transform: scale(1.03);
            box-shadow: 0 12px 35px rgba(0, 179, 72, 0.18);
        }
        
        .event-card:hover::before {
            opacity: 1;
        }
        
        .event-card:active {
            transform: translateY(-1px) scale(1.01);
            transition: transform 0.1s ease;
        }
        
        .event-card-header {
            display: flex;
            align-items: flex-start;
            gap: clamp(0.75rem, 2vw, 1rem);
            margin-bottom: clamp(0.75rem, 2vw, 1rem);
            flex: 1;
        }
        
        .event-icon {
            width: clamp(2.5rem, 8vw, 3rem);
            height: clamp(2.5rem, 8vw, 3rem);
            border-radius: clamp(0.5rem, 1.5vw, 0.75rem);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        
        .event-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: transform 0.6s ease;
        }
        
        .event-card:hover .event-icon::before {
            transform: rotate(45deg) translate(100%, 100%);
        }
        
        .event-icon i {
            font-size: clamp(1rem, 3vw, 1.25rem);
            color: white;
            z-index: 1;
            position: relative;
        }
        
        .event-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: clamp(0.25rem, 1vw, 0.5rem);
        }
        
        .event-title {
            font-size: clamp(0.875rem, 3vw, 1.125rem);
            font-weight: 700;
            color: white;
            line-height: 1.3;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .event-time {
            font-size: clamp(0.75rem, 2.5vw, 0.875rem);
            color: #9ca3af;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .event-location {
            font-size: clamp(0.7rem, 2vw, 0.8rem);
            color: #6b7280;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }
        
        .event-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: clamp(0.5rem, 1.5vw, 0.75rem);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .event-type-badge {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: clamp(0.375rem, 1vw, 0.5rem);
            padding: clamp(0.25rem, 1vw, 0.375rem) clamp(0.5rem, 2vw, 0.75rem);
            font-size: clamp(0.65rem, 2vw, 0.75rem);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .event-arrow {
            color: var(--accent-color);
            font-size: clamp(0.875rem, 2.5vw, 1rem);
            transition: transform 0.3s ease;
        }
        
        .event-card:hover .event-arrow {
            transform: translateX(4px);
        }
        
        /* GRID RESPONSIVO PARA EVENTOS */
        .events-grid {
            display: grid;
            gap: clamp(1rem, 3vw, 1.5rem);
            width: 100%;
        }
        
        /* Mobile Portrait (até 480px) */
        @media (max-width: 480px) {
            .events-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .event-card {
                min-height: 120px;
                padding: 1rem;
            }
            
            .event-card-header {
                flex-direction: row;
                align-items: center;
            }
            
            .event-content {
                flex: 1;
            }
            
            .event-title {
                font-size: 0.9rem;
                -webkit-line-clamp: 2;
            }
            
            .event-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
        
        /* Mobile Landscape (481px - 640px) */
        @media (min-width: 481px) and (max-width: 640px) {
            .events-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            
            .event-card {
                min-height: 130px;
            }
            
            .event-footer {
                flex-direction: row;
            }
        }
        
        /* Tablet Portrait (641px - 768px) */
        @media (min-width: 641px) and (max-width: 768px) {
            .events-grid.palestras {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .events-grid.workshops {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .event-card {
                min-height: 140px;
            }
        }
        
        /* Tablet Landscape (769px - 1024px) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .events-grid.palestras {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .events-grid.workshops {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .event-card {
                min-height: 150px;
            }
        }
        
        /* Desktop (1025px - 1440px) */
        @media (min-width: 1025px) and (max-width: 1440px) {
            .events-grid.palestras {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .events-grid.workshops {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .event-card {
                min-height: 160px;
            }
        }
        
        /* Large Desktop (1441px+) */
        @media (min-width: 1441px) {
            .events-grid.palestras {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .events-grid.workshops {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .event-card {
                min-height: 170px;
            }
        }
        
        /* CORES DOS EVENTOS */
        .event-palestra .event-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .event-workshop .event-icon {
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
        }
        
        .event-palestra .event-type-badge {
            color: #60a5fa;
            border-color: rgba(96, 165, 250, 0.3);
        }
        
        .event-workshop .event-type-badge {
            color: #c084fc;
            border-color: rgba(192, 132, 252, 0.3);
        }
        
        /* OUTROS ESTILOS MANTIDOS */
        .input-field {
            background: linear-gradient(145deg, var(--search-bar-bg) 0%, #151515 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(255, 183, 51, 0.1);
            background: linear-gradient(145deg, #202020 0%, #1a1a1a 100%);
            outline: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--header-color) 0%, #00a040 100%);
            box-shadow: 0 4px 20px rgba(0, 179, 72, 0.3);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            box-shadow: 0 8px 30px rgba(0, 179, 72, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: linear-gradient(145deg, #2a2a2a 0%, #1a1a1a 100%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(145deg, #353535 0%, #252525 100%);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        }
        
        .btn-success:hover {
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }
        
        .select-wrapper {
            position: relative;
        }
        
        .select-wrapper::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-color);
            pointer-events: none;
            font-size: 0.875rem;
        }
        
        select.input-field {
            background: linear-gradient(145deg, #1a1a1a 0%, #151515 100%) !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 3rem;
            cursor: pointer;
        }
        
        select.input-field:focus {
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 3px rgba(255, 183, 51, 0.1) !important;
            background: linear-gradient(145deg, #202020 0%, #1a1a1a 100%) !important;
        }
        
        select.input-field option {
            background-color: #232323 !important;
            color: #fff !important;
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite alternate;
        }
        
        @keyframes pulseGlow {
            from { box-shadow: 0 4px 20px rgba(0, 179, 72, 0.3); }
            to { box-shadow: 0 8px 40px rgba(0, 179, 72, 0.5); }
        }
        
        .student-list {
            /* max-height: 70vh; */
            /* overflow-y: auto; */
            padding-right: 0.5rem;
        }
        
        .student-item {
            background: linear-gradient(145deg, rgba(40, 40, 40, 0.6) 0%, rgba(30, 30, 30, 0.6) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .student-item:hover {
            border-color: rgba(255, 183, 51, 0.3);
            transform: translateY(-1px);
        }
        
        .container-fluid {
            width: 100%;
            padding-left: clamp(0.75rem, 3vw, 2rem);
            padding-right: clamp(0.75rem, 3vw, 2rem);
            margin-left: auto;
            margin-right: auto;
        }
        
        .main-title {
            font-size: clamp(1.25rem, 5vw, 2.5rem);
            line-height: 1.1;
            text-align: center;
        }
        
        .section-title {
            font-size: clamp(0.875rem, 3.5vw, 1.25rem);
            line-height: 1.3;
        }
        
        .btn-fluid {
            width: 100%;
            padding: clamp(0.75rem, 2.5vw, 1rem) clamp(1rem, 3vw, 1.5rem);
            font-size: clamp(0.75rem, 2.5vw, 0.875rem);
            border-radius: clamp(0.5rem, 1.5vw, 1rem);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-align: center;
            min-height: 44px;
        }
        
        .container-responsive {
            width: 100%;
            max-width: none;
            padding: 0 clamp(1rem, 4vw, 2rem);
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100%;
        }
        
        .header-title-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .header-title-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }
        
        .user-chip-desktop {
            position: absolute;
            top: 0;
            right: 0;
        }
        
        .user-chip {
            background: linear-gradient(145deg, #232d25 0%, #181f1a 100%);
            border: 1px solid #1f3a26;
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: clamp(0.75rem, 2vw, 0.875rem);
            font-weight: 600;
            color: #e5e7eb;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.08);
        }
        
        @media (max-width: 640px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            .user-chip-desktop {
                position: relative;
                top: auto;
                right: auto;
            }
            .header-title-section {
                align-items: center;
            }
            .btn-save-frequencia-mobile-hide { display: none !important; }
        }
        
        @media print {
            .header-bg,
            .btn-primary,
            .btn-secondary,
            .btn-success {
                background: #333 !important;
                color: white !important;
                box-shadow: none !important;
            }
        }
        
        @media (min-width: 641px) {
            #floatingConfirmBtn { display: none !important; }
        }
        
        .custom-checkbox input[type="checkbox"] {
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #6ee7b7;
            border-radius: 0.5rem;
            background: #181f1a;
            transition: border-color 0.2s, background 0.2s;
            position: relative;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            cursor: pointer;
            margin-right: 0.75rem;
            display: inline-block;
            vertical-align: middle;
        }
        
        .custom-checkbox input[type="checkbox"]:checked {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #10b981;
        }
        
        .custom-checkbox input[type="checkbox"]:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #fff;
            font-size: 1rem;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            transition: color 0.2s;
        }
        
        .custom-checkbox label {
            font-weight: 600;
            color: #10b981;
            cursor: pointer;
            user-select: none;
            font-size: 1rem;
            margin-left: 0.25rem;
            transition: color 0.2s;
        }
        
        .custom-checkbox input[type="checkbox"]:not(:checked) + label {
            color: #fff;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="header-bg">
        <div class="container-responsive py-4">
            <div class="header-content">
                <div class="header-title-section">
                    <div class="header-title-row">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 via-emerald-600 to-green-700 flex items-center justify-center pulse-glow">
                            <i class="fas fa-clipboard-check text-white text-lg"></i>
                        </div>
                        <h1 class="main-title font-black bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                            FREQUÊNCIA DE ALUNOS
                        </h1>
                    </div>
                    <p class="text-gray-400 text-xs font-medium tracking-wider uppercase">Palestras & Workshops</p>
                </div>
                <div class="flex items-center gap-2 user-chip-desktop">
                <div class="user-chip user-chip-desktop">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <i class="fas fa-user text-green-300 text-xs"></i>
                    </div>
                    <span class="text-gray-100"><?=$_SESSION['Nome']?></span>
                </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container-fluid py-4 sm:py-8">
        <!-- Course Selection -->
        <div class="card-bg rounded-2xl sm:rounded-3xl p-4 sm:p-6 mb-6 sm:mb-8 fade-in">
            <div class="flex items-center gap-3 mb-4">
                <i class="fas fa-graduation-cap text-green-400 text-lg sm:text-xl"></i>
                <h2 class="section-title font-bold text-white">Selecionar Curso</h2>
            </div>
            <div class="select-wrapper">
                <select id="cursoSelect" name="curso" required class="input-field w-full rounded-2xl px-4 py-3 text-white focus:outline-none">
                    <option value="" selected disabled>Selecione o curso</option>
                </select>
            </div>
        </div>

        <!-- Event Selection -->
        <div class="card-bg rounded-2xl sm:rounded-3xl p-4 sm:p-6 mb-6 sm:mb-8 fade-in" id="eventSection" style="display: none;">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-calendar-alt text-blue-400 text-lg sm:text-xl"></i>
                <h2 class="section-title font-bold text-white">Selecionar Evento</h2>
            </div>
            
            <!-- Palestras -->
            <div class="mb-8" id="palestrasSection">
                <h3 class="text-base sm:text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-microphone text-blue-400"></i>
                    <span>Palestras no Auditório</span>
                    <span class="hidden sm:inline text-gray-400 text-sm font-normal">- 24/06/2025</span>
                </h3>
                <div class="events-grid palestras" id="palestrasGrid">
                    <div class="loading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            
            <!-- Workshops -->
            <div id="workshopsSection">
                <h3 class="text-base sm:text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-tools text-purple-400"></i>
                    <span>Workshops</span>
                    <span class="hidden sm:inline text-gray-400 text-sm font-normal">- 25/06/2025</span>
                </h3>
                <div class="events-grid workshops" id="workshopsGrid">
                    <div class="loading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Section -->
        <div class="card-bg rounded-2xl sm:rounded-3xl p-4 sm:p-6 fade-in" id="attendanceSection" style="display: none;">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6 gap-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users text-green-400 text-lg sm:text-xl"></i>
                    <div>
                        <h2 class="section-title font-bold text-white" id="eventTitle">Evento Selecionado</h2>
                        <p class="text-gray-400 text-sm" id="eventDetails">Detalhes do evento</p>
                    </div>
                </div>
                <button onclick="backToEvents()" class="btn-secondary btn-fluid lg:w-auto lg:px-6 text-white font-semibold">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
                    <h3 class="text-base sm:text-lg font-bold text-white">Lista de Alunos</h3>
                    <div class="w-full sm:w-72">
                        <input type="text" id="studentFilter" class="input-field w-full rounded-2xl px-4 py-3 text-white focus:outline-none" placeholder="Filtrar por nome do aluno..." oninput="filterStudentList()">
                    </div>
                </div>
                
                <div class="student-list" id="studentList">
                    <div class="loading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-center">
                <button onclick="saveAttendance()" class="btn-primary btn-fluid sm:w-auto sm:px-8 py-3 rounded-xl text-white font-bold text-base sm:text-lg btn-save-frequencia-mobile-hide">
                    <i class="fas fa-save"></i>
                    <span>Salvar Frequência</span>
                </button>
            </div>
        </div>

        <!-- Success Message -->
        <div id="successMessage" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4" style="display: none;">
            <div class="card-bg rounded-2xl sm:rounded-3xl p-6 sm:p-8 w-full max-w-sm sm:max-w-md text-center fade-in">
                <div class="flex flex-col items-center gap-4 mb-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center pulse-glow">
                        <i class="fas fa-check-circle text-white text-2xl sm:text-4xl"></i>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-green-400 mb-2">Frequência Registrada!</h2>
                    <p class="text-base sm:text-lg text-gray-200">A presença dos alunos foi salva com sucesso.</p>
                </div>
                <button onclick="closeSuccess()" class="btn-primary btn-fluid font-semibold text-white">
                    <i class="fas fa-check"></i>
                    <span>OK</span>
                </button>
            </div>
        </div>
    </main>

    <!-- Botão flutuante de confirmação (mobile) -->
    <button id="floatingConfirmBtn" onclick="saveAttendance()" type="button"
        class="fixed z-50 bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center"
        style="width:60px;height:60px;display:none;"
        aria-label="Confirmar presença">
        <i class="fas fa-check text-2xl"></i>
    </button>

    <script>
        let selectedCourse = '';
        let selectedEvent = '';
        let selectedEventType = '';
        let allStudents = [];
        let students = [];
        let attendance = {};
        let courses = [];
        let events = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
            loadEvents();
        });

        // Load courses from PHP
        function loadCourses() {
            fetch('index.php?action=get_courses')
                .then(response => response.text())
                .then(text => {
                    if (!text) throw new Error('Resposta vazia do servidor');
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('JSON inválido');
                    }
                })
                .then(data => {
                    courses = data;
                    populateCourseSelect();
                })
                .catch(error => {
                    console.error('Erro ao carregar cursos:', error);
                    showError('Erro ao carregar cursos');
                });
        }

        // Load events from PHP
        function loadEvents() {
            fetch('index.php?action=get_events')
                .then(response => response.text())
                .then(text => {
                    if (!text) throw new Error('Resposta vazia do servidor');
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('JSON inválido');
                    }
                })
                .then(data => {
                    events = data;
                    populateEvents();
                })
                .catch(error => {
                    console.error('Erro ao carregar eventos:', error);
                    showError('Erro ao carregar eventos');
                });
        }

        // Populate course select
        function populateCourseSelect() {
            const courseSelect = document.getElementById('cursoSelect');
            courseSelect.innerHTML = '<option value="" selected disabled>Selecione o curso</option>';
            
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id_curso;
                option.textContent = course.curso.charAt(0).toUpperCase() + course.curso.slice(1);
                courseSelect.appendChild(option);
            });
        }

        // Populate events
        function populateEvents() {
            const palestrasGrid = document.getElementById('palestrasGrid');
            const workshopsGrid = document.getElementById('workshopsGrid');
            
            palestrasGrid.innerHTML = '';
            workshopsGrid.innerHTML = '';
            
            events.forEach(event => {
                const eventCard = createEventCard(event);
                
                if (event.tipo === 'palestra') {
                    palestrasGrid.appendChild(eventCard);
                } else if (event.tipo === 'workshop') {
                    workshopsGrid.appendChild(eventCard);
                }
            });
        }

        // Create event card - VERSÃO MELHORADA
        function createEventCard(event) {
            const card = document.createElement('div');
            card.className = `event-card event-${event.tipo}`;
            card.onclick = () => selectEvent(event.id, event.nome, event.horario, event.tipo);
            
            card.innerHTML = `
                <div class="event-card-header">
                    <div class="event-icon ${getEventColor(event.tipo)}">
                        <i class="fas ${getEventIcon(event.tipo)}"></i>
                    </div>
                    <div class="event-content">
                        <h4 class="event-title">${event.nome}</h4>
                        <div class="event-time">
                            <i class="fas fa-clock" style="font-size: 0.75em; color: #60a5fa;"></i>
                            <span>${event.horario}</span>
                    </div>
                        ${event.local ? `
                            <div class="event-location">
                                <i class="fas fa-map-marker-alt" style="font-size: 0.7em; color: #f87171;"></i>
                                <span>${event.local}</span>
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="event-footer">
                    <span class="event-type-badge">${event.tipo}</span>
                </div>
            `;
            
            return card;
        }

        // Get event color based on type
        function getEventColor(tipo) {
            const colors = {
                'palestra': 'from-blue-500 to-cyan-600',
                'workshop': 'from-purple-500 to-violet-600'
            };
            return colors[tipo] || 'from-gray-500 to-gray-600';
        }

        // Get event icon based on type
        function getEventIcon(tipo) {
            const icons = {
                'palestra': 'fa-microphone',
                'workshop': 'fa-tools'
            };
            return icons[tipo] || 'fa-calendar';
        }

        // Course selection handler
        const cursoSelect = document.getElementById('cursoSelect');
        cursoSelect.addEventListener('change', function() {
            selectedCourse = this.value;
            if (selectedCourse) {
                document.getElementById('eventSection').style.display = 'block';
                loadStudentsForCourse(selectedCourse);
            } else {
                document.getElementById('eventSection').style.display = 'none';
                document.getElementById('attendanceSection').style.display = 'none';
            }
        });

        // Load students for selected course
        function loadStudentsForCourse(cursoId) {
            fetch('index.php?action=get_students&curso_id=' + cursoId)
                .then(response => response.text())
                .then(text => {
                    if (!text) throw new Error('Resposta vazia do servidor');
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('JSON inválido');
                    }
                })
                .then(data => {
                    allStudents = data;
                    students = data;
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    showError('Erro ao carregar alunos');
                });
        }

        // Select event
        function selectEvent(eventId, eventName, eventTime, eventType) {
            selectedEvent = eventId;
            selectedEventType = eventType;
            document.getElementById('eventTitle').textContent = eventName;
            document.getElementById('eventDetails').textContent = eventTime;
            document.getElementById('eventSection').style.display = 'none';
            document.getElementById('attendanceSection').style.display = 'block';
            // Desabilitar select de curso
            cursoSelect.disabled = true;
            loadStudentsForEvent();
        }

        // Load students for selected event
        function loadStudentsForEvent() {
            fetch('index.php?action=get_students&curso_id=' + selectedCourse)
                .then(response => response.text())
                .then(text => {
                    if (!text) throw new Error('Resposta vazia do servidor');
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('JSON inválido');
                    }
                })
                .then(data => {
                    allStudents = data;
                    students = data;
                    populateStudentList(students);
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos do evento:', error);
                    showError('Erro ao carregar alunos do evento');
                });
        }

        // Populate student list
        function populateStudentList(studentData) {
            students = studentData; // Atualiza a lista global
            const studentList = document.getElementById('studentList');
            studentList.innerHTML = '';
            
            if (!studentData || studentData.length === 0) {
                studentList.innerHTML = '<p class="text-gray-400 text-center py-8">Nenhum aluno encontrado</p>';
                return;
            }
            
            studentData.forEach((student, index) => {
                const studentItem = document.createElement('div');
                studentItem.className = 'student-item';
                studentItem.innerHTML = `
                    <div class="student-item-content flex items-center justify-between gap-4">
                        <div class="student-info flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold text-sm">${getInitials(student.nome)}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-semibold text-white truncate">${student.nome}</h4>
                                <p class="text-sm text-gray-400">Matrícula: ${student.matricula}</p>
                            </div>
                        </div>
                        <div class="attendance-control custom-checkbox">
                            <input type="checkbox" id="chk-${student.id_aluno}" onchange="toggleAttendanceCheckbox('${student.id_aluno}')" />
                            <label for="chk-${student.id_aluno}">Presente</label>
                        </div>
                    </div>
                `;
                studentList.appendChild(studentItem);
                attendance[student.id_aluno] = false;
            });
            updateFloatingConfirmBtn();
        }

        // Get initials from name
        function getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        }

        // Mark all present
        function markAllPresent() {
            Object.keys(attendance).forEach(studentId => {
                attendance[studentId] = true;
                const checkbox = document.getElementById(`chk-${studentId}`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }

        // Mark all absent
        function markAllAbsent() {
            Object.keys(attendance).forEach(studentId => {
                attendance[studentId] = false;
                const checkbox = document.getElementById(`chk-${studentId}`);
                if (checkbox) {
                    checkbox.checked = false;
                }
            });
        }

        // Back to events
        function backToEvents() {
            document.getElementById('attendanceSection').style.display = 'none';
            document.getElementById('eventSection').style.display = 'block';
            // Habilitar select de curso
            cursoSelect.disabled = false;
            document.getElementById('floatingConfirmBtn').style.display = 'none';
        }

        // Save attendance
        function saveAttendance() {
            const presencasMarcadas = {};
            Object.keys(attendance).forEach(id => {
                if (attendance[id]) {
                    presencasMarcadas[id] = true;
                }
            });

            const attendanceData = {
                evento_id: selectedEvent,
                presencas: presencasMarcadas
            };
            
            fetch('index.php?action=save_attendance', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(attendanceData)
            })
            .then(response => response.text())
            .then(text => {
                if (!text) throw new Error('Resposta vazia do servidor');
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('JSON inválido');
                }
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').style.display = 'flex';
                } else {
                    showError('Erro ao salvar frequência');
                }
            })
            .catch(error => {
                console.error('Erro ao salvar frequência:', error);
                showError('Erro ao salvar frequência');
            });
        }

        // Close success message
        function closeSuccess() {
            document.getElementById('successMessage').style.display = 'none';
            backToEvents();
            attendance = {};
        }

        // Show error message
        function showError(message) {
            alert(message);
        }

        // Toggle attendance checkbox
        function toggleAttendanceCheckbox(studentId) {
            attendance[studentId] = document.getElementById(`chk-${studentId}`).checked;
            updateFloatingConfirmBtn();
        }

        function filterStudentList() {
            const filter = document.getElementById('studentFilter').value.toLowerCase();
            if (!filter) {
                students = allStudents;
                populateStudentList(students);
                return;
            }
            students = allStudents.filter(student => student.nome.toLowerCase().includes(filter));
            populateStudentList(students);
        }

        function updateFloatingConfirmBtn() {
            // Verifica se algum aluno está marcado
            const algumMarcado = Object.values(attendance).some(v => v);
            const btn = document.getElementById('floatingConfirmBtn');
            // Só mostra no mobile
            if (window.innerWidth <= 640 && algumMarcado) {
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
            }
        }
    </script>
</body>
</html>