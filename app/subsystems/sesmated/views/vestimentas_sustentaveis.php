<?php
require_once('../models/select.model.php');
$select = new select_model();
// Mensagem de status
$status = '';
if (isset($_GET['confirmado'])) {
    $status = 'sucesso';
} elseif (isset($_GET['erro'])) {
    $status = 'erro';
} elseif (isset($_GET['ja_confirmado'])) {
    $status = 'ja_confirmado';
} elseif (isset($_GET['empty'])) {
    $status = 'empty';
}

$total_php = null;
$curso_post = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pontuacao_total']) && isset($_POST['curso'])) {
    $total_php = intval($_POST['pontuacao_total']);
    $curso_post = $_POST['curso'];
}
require_once('../../../main/models/sessions.php');
$session = new sessions;
$session->autenticar_session();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefa 08 - Vestimentas Sustentáveis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --background-color: #0a0a0a;
            --text-color: #ffffff;
            --header-color: #00b348;
            --icon-bg: #2d2d2d;
            --icon-shadow: rgba(0, 0, 0, 0.3);
            --accent-color: #ffb733;
            --grid-color: #333333;
            --card-bg: rgba(30, 30, 30, 0.95);
            --header-bg: rgba(15, 15, 15, 0.98);
            --search-bar-bg: #1a1a1a;
            --card-border-hover: var(--accent-color);
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }
        
        body {
            background: radial-gradient(ellipse at top, #1a1a1a 0%, #0a0a0a 100%);
            color: var(--text-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
        }
        
        .header-bg {
            background: linear-gradient(135deg, var(--header-bg) 0%, rgba(0, 0, 0, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        
        .card-bg {
            background: linear-gradient(145deg, var(--card-bg) 0%, rgba(25, 25, 25, 0.95) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        .input-field {
            background: linear-gradient(145deg, var(--search-bar-bg) 0%, #151515 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(255, 183, 51, 0.1);
            background: linear-gradient(145deg, #202020 0%, #1a1a1a 100%);
        }
        
        .card-hover:hover {
            border-color: rgba(255, 183, 51, 0.3);
            box-shadow: 0 12px 40px rgba(255, 183, 51, 0.15);
            transform: translateY(-4px);
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
            font-size: clamp(0.75rem, 2vw, 0.875rem);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            min-height: 2.5rem;
            display: flex;
            align-items: center;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(145deg, #353535 0%, #252525 100%);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }
        
        .modal-bg {
            background: linear-gradient(145deg, rgba(25, 25, 25, 0.98) 0%, rgba(15, 15, 15, 0.98) 100%);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-up {
            animation: slideUp 0.4s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite alternate;
        }
        
        @keyframes pulseGlow {
            from { box-shadow: 0 4px 20px rgba(0, 179, 72, 0.3); }
            to { box-shadow: 0 8px 40px rgba(0, 179, 72, 0.5); }
        }

        select.input-field {
            background: linear-gradient(145deg, var(--search-bar-bg) 0%, #151515 100%) !important;
            color: var(--text-color) !important;
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

        /* ===== HEADER ORIGINAL - INÍCIO ===== */
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
        .user-chip-desktop {
            position: absolute;
            top: 0;
            right: 0;
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
        }
        /* ===== HEADER ORIGINAL - FIM ===== */

        /* Títulos responsivos */
        .main-title {
            font-size: clamp(1.5rem, 6vw, 2.5rem);
            line-height: 1.2;
        }

        /* ====== INÍCIO: CSS dos inputs melhorado ====== */
        .score-input-wrapper {
            position: relative;
            width: 100%;
            margin-top: 1rem;
        }

        .score-input {
            background: linear-gradient(145deg, var(--search-bar-bg) 0%, #151515 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-color);
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            font-size: clamp(0.875rem, 2.5vw, 1rem);
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            text-align: center;
        }

        .score-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(255, 183, 51, 0.1);
            background: linear-gradient(145deg, #202020 0%, #1a1a1a 100%);
            outline: none;
        }

        .score-input:hover {
            border-color: rgba(255, 183, 51, 0.3);
            background: linear-gradient(145deg, #202020 0%, #1a1a1a 100%);
        }

        .score-input::placeholder {
            color: #6b7280;
            font-weight: 500;
        }

        .score-input::-webkit-outer-spin-button,
        .score-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .score-input[type=number] {
            -moz-appearance: textfield;
        }

        .score-input.valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
            background: rgba(16, 185, 129, 0.05);
        }

        .score-input.invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
            background: rgba(239, 68, 68, 0.05);
        }

        .course-panel {
            display: none !important;
            background: linear-gradient(145deg, rgba(40, 40, 40, 0.6) 0%, rgba(30, 30, 30, 0.6) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
            padding: clamp(1rem, 3vw, 1.5rem);
            margin-top: 1.5rem;
            transition: all 0.3s ease;
        }

        .course-panel.active {
            display: block !important;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .course-title {
            font-size: clamp(1rem, 4vw, 1.25rem);
            font-weight: 700;
            color: var(--header-color);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .year-section {
            margin-bottom: 1.5rem;
        }

        .year-title {
            font-size: clamp(0.875rem, 3.5vw, 1rem);
            font-weight: 600;
            color: white;
            margin-bottom: 0.75rem;
            text-align: center;
        }

        .input-row {
            display: grid;
            gap: clamp(0.5rem, 2vw, 1rem);
            width: 100%;
        }

        .total-display {
            background: linear-gradient(135deg, rgba(255, 183, 51, 0.15) 0%, rgba(255, 183, 51, 0.05) 100%);
            border: 1px solid rgba(255, 183, 51, 0.3);
            border-radius: 0.75rem;
            padding: 1rem;
            margin: 1.5rem 0;
            font-size: clamp(0.875rem, 3vw, 1rem);
            font-weight: 700;
            color: var(--accent-color);
            text-align: center;
            width: 100%;
        }

        .btn-responsive {
            padding: clamp(0.75rem, 3vw, 1rem) clamp(1.5rem, 6vw, 2rem);
            font-size: clamp(0.875rem, 3vw, 1rem);
            border-radius: 1rem;
            width: 100%;
            max-width: 20rem;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 700;
            color: white;
        }

        @media (max-width: 640px) {
            .input-row {
                grid-template-columns: 1fr;
            }
            .card-bg {
                padding: clamp(1rem, 4vw, 1.5rem);
                margin: 0.5rem;
            }
        }
        @media (min-width: 641px) and (max-width: 1024px) {
            .input-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1025px) {
            .input-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Modal styles */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
        }
        
        .modal.hidden {
            display: none;
        }
        
        .modal-content {
            background: var(--card-bg);
            border-radius: 1.5rem;
            padding: 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }
        
        .modal-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .modal-icon.success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        }
        
        .modal-icon.error {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        }
        
        .modal-icon.warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        }
        
        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .modal-title.success {
            color: var(--success-color);
        }
        
        .modal-title.error {
            color: var(--danger-color);
        }
        
        .modal-title.warning {
            color: var(--warning-color);
        }
        
        .modal-description {
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .nova-votacao-btn {
            background: linear-gradient(90deg, #ffe29f 0%, #00b348 100%);
            color: #181818;
            font-weight: 700;
            font-size: 1.08rem;
            border: none;
            border-radius: 1.1rem;
            padding: 0.65rem 1.7rem;
            box-shadow: 0 3px 14px rgba(0,179,72,0.13), 0 1px 4px rgba(255,183,51,0.10);
            transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
            cursor: pointer;
            outline: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .nova-votacao-btn:hover, .nova-votacao-btn:focus {
            background: linear-gradient(90deg, #00b348 0%, #ffe29f 100%);
            color: #fff;
            transform: scale(1.04) translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,179,72,0.16), 0 1.5px 6px rgba(255,183,51,0.12);
        }
        .nova-votacao-btn .icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15em;
            animation: rotateIcon 1.2s linear infinite;
        }
        @keyframes rotateIcon {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }
        /* ====== FIM: CSS dos inputs melhorado ====== */
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-bg">
        <div class="container-responsive py-4">
            <div class="header-content">
                <div class="header-title-section">
                    <div class="header-title-row">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 via-emerald-600 to-green-700 flex items-center justify-center pulse-glow">
                            <i class="fas fa-tshirt text-white text-lg"></i>
                        </div>
                        <h1 class="main-title font-black bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                            TAREFA 8
                        </h1>
                    </div>
                    <p class="text-gray-400 text-xs font-medium tracking-wider uppercase">Avaliação das Vestimentas Sustentáveis</p>
                </div>
                <div class="flex items-center gap-2 user-chip-desktop">
                    <a href="abertura.php" class="btn-secondary px-4 py-2 rounded-2xl font-semibold text-gray-300 flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                    <div class="user-chip">
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
    <main class="container-responsive py-8">
        <div class="flex flex-col items-center justify-center min-h-[70vh]">
            <div class="card-bg rounded-3xl w-full max-w-6xl text-center fade-in" style="padding: clamp(1.5rem, 4vw, 2rem);">
                
                <div class="flex flex-col items-center gap-6 mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <i class="fas fa-tshirt text-white text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="main-title font-black mb-4 bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                            Avaliação das Vestimentas Sustentáveis
                        </h2>
                        <p class="text-lg text-gray-300 font-medium">
                            Avalie cada critério com notas de 0 a 100 pontos
                        </p>
                    </div>
                </div>

                <!-- Seletor de Curso -->
                <div class="mb-8">
                    <label class="block text-sm font-bold mb-4 text-gray-300 uppercase tracking-wide">
                        <i class="fas fa-graduation-cap mr-2"></i>Curso
                    </label>
                    <div class="select-wrapper">
                        <select id="cursoSelect" required class="input-field w-full rounded-2xl px-4 py-3 text-white focus:outline-none">
                            <option value="" selected disabled>Selecione o curso</option>
                            <option value="Enfermagem">Enfermagem</option>
                            <option value="Informática">Informática</option>
                            <option value="Edificações">Edificações</option>
                            <option value="Administração">Administração</option>
                            <option value="Meio ambiente">Meio ambiente</option>
                        </select>
                    </div>
                </div>

                <!-- Painéis dos Cursos -->
                <div id="paineis-cursos">
                    <!-- ENFERMAGEM -->
                    <form id="painel-Enfermagem" class="course-panel" method="post" action="../controllers/controller_vestimentas_sustentaveis.php">
                        <h3 class="course-title">Enfermagem</h3>
                        <input type="hidden" name="curso" value="1">
                        <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                        
                        <div class="year-section">
                            <div class="year-title">1º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="year-section">
                            <div class="year-title">2º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="year-section">
                            <div class="year-title">3º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="total-display">
                            Total: <span class="total-pontos">0</span> / 500 pontos
                        </div>
                        
                        <input type="hidden" name="pontuacao_total" class="pontuacao_total" value="0">
                        <button type="submit" class="btn-primary btn-responsive">
                            <i class="fas fa-paper-plane"></i>
                            Confirmar Avaliação
                        </button>
                    </form>

                    <!-- INFORMÁTICA -->
                    <form id="painel-Informática" class="course-panel" method="post" action="../controllers/controller_vestimentas_sustentaveis.php">
                        <h3 class="course-title">Informática</h3>
                        <input type="hidden" name="curso" value="2">
                        <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                        
                        <div class="year-section">
                            <div class="year-title">1º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="year-section">
                            <div class="year-title">2º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="year-section">
                            <div class="year-title">3º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="total-display">
                            Total: <span class="total-pontos">0</span> / 500 pontos
                        </div>
                        
                        <input type="hidden" name="pontuacao_total" class="pontuacao_total" value="0">
                        <button type="submit" class="btn-primary btn-responsive">
                            <i class="fas fa-paper-plane"></i>
                            Confirmar Avaliação
                        </button>
                    </form>

                    <!-- EDIFICAÇÕES -->
                    <form id="painel-Edificações" class="course-panel" method="post" action="../controllers/controller_vestimentas_sustentaveis.php">
                        <h3 class="course-title">Edificações</h3>
                        <input type="hidden" name="curso" value="5">
                        <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                        
                        <div class="year-section">
                            <div class="year-title">1º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="year-section">
                            <div class="year-title">2º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="year-section">
                            <div class="year-title">3º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="total-display">
                            Total: <span class="total-pontos">0</span> / 500 pontos
                        </div>
                        
                        <input type="hidden" name="pontuacao_total" class="pontuacao_total" value="0">
                        <button type="submit" class="btn-primary btn-responsive">
                            <i class="fas fa-paper-plane"></i>
                            Confirmar Avaliação
                        </button>
                    </form>

                    <!-- ADMINISTRAÇÃO -->
                    <form id="painel-Administração" class="course-panel" method="post" action="../controllers/controller_vestimentas_sustentaveis.php">
                        <h3 class="course-title">Administração</h3>
                        <input type="hidden" name="curso" value="4">
                        <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                        
                        <div class="year-section">
                            <div class="year-title">1º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="1.5" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="1.5" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="year-section">
                            <div class="year-title">3º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="2" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="total-display">
                            Total: <span class="total-pontos">0</span> / 500 pontos
                        </div>
                        
                        <input type="hidden" name="pontuacao_total" class="pontuacao_total" value="0">
                        <button type="submit" class="btn-primary btn-responsive">
                            <i class="fas fa-paper-plane"></i>
                            Confirmar Avaliação
                        </button>
                    </form>

                    <!-- MEIO AMBIENTE -->
                    <form id="painel-Meio ambiente" class="course-panel" method="post" action="../controllers/controller_vestimentas_sustentaveis.php">
                        <h3 class="course-title">Meio Ambiente</h3>
                        <input type="hidden" name="curso" value="3">
                        <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                        
                        <div class="year-section">
                            <div class="year-title">2º Ano</div>
                            <div class="input-row">
                                <input type="number" class="score-input" data-weight="2.5" min="0" max="100" placeholder="Modelo 1 (máx: 100)" required>
                                <input type="number" class="score-input" data-weight="2.5" min="0" max="100" placeholder="Modelo 2 (máx: 100)" required>
                            </div>
                        </div>
                        
                        <div class="total-display">
                            Total: <span class="total-pontos">0</span> / 500 pontos
                        </div>
                        
                        <input type="hidden" name="pontuacao_total" class="pontuacao_total" value="0">
                        <button type="submit" class="btn-primary btn-responsive">
                            <i class="fas fa-paper-plane"></i>
                            Confirmar Avaliação
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Modais -->
    <!-- Modal de Sucesso -->
    <div id="modalSucesso" class="modal hidden">
        <div class="modal-content">
            <div class="modal-icon success">
                <i class="fas fa-check-circle text-white text-2xl"></i>
            </div>
            <h3 class="modal-title success">Avaliação Registrada!</h3>
            <p class="modal-description">As notas das vestimentas foram computadas com sucesso.</p>
            <div style="background: linear-gradient(135deg, var(--accent-yellow) 0%, var(--primary-green) 100%); border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem;">
                <p style="color: #000; font-weight: 700; font-size: 1.125rem;">
                    Total: <span id="pontosFinal">0</span> / 500 pontos
                </p>
            </div>
            <button onclick="fecharModal('modalSucesso')" class="btn-primary nova-votacao-btn">
                <i class="fas fa-arrow-rotate-left"></i>
                Nova Avaliação
            </button>
        </div>
    </div>

    <!-- Modal de Erro -->
    <div id="modalErro" class="modal hidden">
        <div class="modal-content">
            <div class="modal-icon error">
                <i class="fas fa-times-circle text-white text-2xl"></i>
            </div>
            <h3 class="modal-title error">Erro ao Registrar!</h3>
            <p class="modal-description">Ocorreu um erro ao registrar a avaliação. Tente novamente.</p>
            <button onclick="fecharModal('modalErro')" class="btn-primary nova-votacao-btn">
                <i class="fas fa-arrow-rotate-left"></i>
                Tentar Novamente
            </button>
        </div>
    </div>

    <!-- Modal de Já Confirmado -->
    <div id="modalJaConfirmado" class="modal hidden">
        <div class="modal-content">
            <div class="modal-icon warning">
                <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
            </div>
            <h3 class="modal-title warning">Já Confirmado!</h3>
            <p class="modal-description">A avaliação destas vestimentas já foi registrada anteriormente.</p>
            <button onclick="fecharModal('modalJaConfirmado')" class="btn-primary nova-votacao-btn">
                <i class="fas fa-arrow-rotate-left"></i>
                Nova Avaliação
            </button>
        </div>
    </div>

    <!-- Modal de Campos Obrigatórios -->
    <div id="modalEmpty" class="modal hidden">
        <div class="modal-content">
            <div class="modal-icon error">
                <i class="fas fa-exclamation-circle text-white text-2xl"></i>
            </div>
            <h3 class="modal-title error">Campos Obrigatórios!</h3>
            <p class="modal-description">Preencha todos os campos obrigatórios para registrar a avaliação.</p>
            <button onclick="fecharModal('modalEmpty')" class="btn-primary nova-votacao-btn">
                <i class="fas fa-arrow-rotate-left"></i>
                Corrigir
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cursoSelect = document.getElementById('cursoSelect');
            const paineis = document.querySelectorAll('.course-panel');
            
            // Garantir que todos os painéis estejam ocultos no início
            paineis.forEach(painel => {
                painel.style.display = 'none';
                painel.classList.remove('active');
            });

            // Função para mostrar/ocultar painéis
            function mostrarPainel(curso) {
                // Primeiro ocultar todos
                paineis.forEach(painel => {
                    painel.style.display = 'none';
                    painel.classList.remove('active');
                });
                
                // Depois mostrar o selecionado
                if (curso) {
                    const painelAtivo = document.getElementById('painel-' + curso);
                    if (painelAtivo) {
                        painelAtivo.style.display = 'block';
                        painelAtivo.classList.add('active');
                        configurarInputs(painelAtivo);
                    }
                }
            }
            
            // Configurar inputs com máscara e validação
            function configurarInputs(painel) {
                const inputs = painel.querySelectorAll('input[type="number"]');
                const totalSpan = painel.querySelector('.total-pontos');
                const totalInput = painel.querySelector('.pontuacao_total');
                
                inputs.forEach(input => {
                    // Aplicar máscara e validação em tempo real
                    input.addEventListener('input', function() {
                        let valor = parseInt(this.value) || 0;
                        const max = parseInt(this.max);
                        const min = parseInt(this.min);
                        
                        // Aplicar limites
                        if (valor > max) {
                            this.value = max;
                            valor = max;
                        }
                        if (valor < min) {
                            this.value = min;
                            valor = min;
                        }
                        
                        // Validação visual
                        this.classList.remove('valid', 'invalid');
                        if (valor >= min && valor <= max && this.value !== '') {
                            this.classList.add('valid');
                        } else if (this.value !== '') {
                            this.classList.add('invalid');
                        }
                        
                        // Calcular total
                        calcularTotal();
                    });
                    
                    // Prevenir entrada de valores inválidos
                    input.addEventListener('keydown', function(e) {
                        // Permitir: backspace, delete, tab, escape, enter
                        if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                            // Permitir: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                            (e.keyCode === 65 && e.ctrlKey === true) ||
                            (e.keyCode === 67 && e.ctrlKey === true) ||
                            (e.keyCode === 86 && e.ctrlKey === true) ||
                            (e.keyCode === 88 && e.ctrlKey === true) ||
                            // Permitir: home, end, left, right
                            (e.keyCode >= 35 && e.keyCode <= 39)) {
                            return;
                        }
                        // Garantir que é um número
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });
                    
                    // Validar ao sair do campo
                    input.addEventListener('blur', function() {
                        if (this.value === '') {
                            this.value = this.min || 0;
                        }
                        calcularTotal();
                    });
                });
                
                function calcularTotal() {
                    let total = 0;
                    inputs.forEach(input => {
                        const valor = parseInt(input.value) || 0;
                        const peso = parseFloat(input.getAttribute('data-weight')) || 1;
                        total += valor * peso;
                    });
                    
                    if (totalSpan) totalSpan.textContent = Math.round(total);
                    if (totalInput) totalInput.value = Math.round(total);
                }
            }
            
            // Event listener para mudança de curso
            cursoSelect.addEventListener('change', function() {
                mostrarPainel(this.value);
            });
            
            // Função para fechar modais
            window.fecharModal = function(modalId) {
                document.getElementById(modalId).classList.add('hidden');
                // Reset form
                cursoSelect.value = '';
                paineis.forEach(painel => {
                    painel.style.display = 'none';
                    painel.classList.remove('active');
                    const inputs = painel.querySelectorAll('input[type="number"]');
                    inputs.forEach(input => {
                        input.value = '';
                        input.classList.remove('valid', 'invalid');
                    });
                    const totalSpan = painel.querySelector('.total-pontos');
                    if (totalSpan) totalSpan.textContent = '0';
                });
            };
            
            // Verificar status da URL e mostrar modal apropriado
            const status = '<?php echo $status; ?>';
            if (status === 'sucesso') {
                document.getElementById('modalSucesso').classList.remove('hidden');
                const total = <?php echo $total_php ?: 0; ?>;
                document.getElementById('pontosFinal').textContent = total;
            } else if (status === 'erro') {
                document.getElementById('modalErro').classList.remove('hidden');
            } else if (status === 'ja_confirmado') {
                document.getElementById('modalJaConfirmado').classList.remove('hidden');
            } else if (status === 'empty') {
                document.getElementById('modalEmpty').classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
