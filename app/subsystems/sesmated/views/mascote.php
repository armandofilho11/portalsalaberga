<?php
require_once('../models/select.model.php');
$select = new select_model();
require_once('../../../main/models/sessions.php');
$session = new sessions;
$session->autenticar_session();

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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefa 03 - Mascote do Curso</title>
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
        
        .stats-card {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.8) 0%, rgba(20, 20, 20, 0.8) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
        
        /* Estilo para inputs de nota - Responsivo */
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
        
        /* Remove spinner arrows */
        .score-input::-webkit-outer-spin-button,
        .score-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .score-input[type=number] {
            -moz-appearance: textfield;
        }
        
        /* Score validation colors */
        .score-input.valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
        }
        
        .score-input.invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
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
        
        /* Select customizado */
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
        
        /* Animações */
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
        
        /* Critérios - Layout Flexível */
        .criteria-item {
            background: linear-gradient(145deg, rgba(40, 40, 40, 0.6) 0%, rgba(30, 30, 30, 0.6) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
            padding: clamp(1rem, 3vw, 1.5rem);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: auto;
        }
        
        .criteria-item:hover {
            border-color: rgba(255, 183, 51, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 183, 51, 0.1);
        }
        
        .criteria-icon {
            width: clamp(2.5rem, 8vw, 3rem);
            height: clamp(2.5rem, 8vw, 3rem);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .criteria-icon i {
            font-size: clamp(1rem, 4vw, 1.25rem);
            color: white;
        }
        
        .criteria-title {
            font-size: clamp(0.875rem, 3.5vw, 1rem);
            font-weight: 700;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .criteria-subtitle {
            font-size: clamp(0.75rem, 2.5vw, 0.875rem);
            color: #9ca3af;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        
        /* Score display */
        .score-display {
            background: linear-gradient(135deg, rgba(255, 183, 51, 0.15) 0%, rgba(255, 183, 51, 0.05) 100%);
            border: 1px solid rgba(255, 183, 51, 0.3);
            border-radius: 0.5rem;
            padding: 0.75rem 0.5rem;
            margin-top: 0.75rem;
            font-size: clamp(0.75rem, 2.5vw, 0.875rem);
            font-weight: 700;
            color: var(--accent-color);
            text-align: center;
            width: 100%;
        }
        
        /* Grid responsivo para critérios */
        .criteria-grid {
            display: grid;
            gap: clamp(1rem, 3vw, 1.5rem);
            width: 100%;
        }
        
        /* Header responsivo - CORRIGIDO */
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
        
        /* Responsividade aprimorada */
        @media (max-width: 640px) {
            .criteria-grid {
                grid-template-columns: 1fr;
            }
            
            .card-bg {
                padding: clamp(1rem, 4vw, 1.5rem);
                margin: 0.5rem;
            }
            
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
        
        @media (min-width: 641px) and (max-width: 1024px) {
            .criteria-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1025px) {
            .criteria-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        /* Container responsivo */
        .container-responsive {
            width: 100%;
            max-width: none;
            padding: 0 clamp(1rem, 4vw, 2rem);
        }
        
        /* Títulos responsivos */
        .main-title {
            font-size: clamp(1.5rem, 6vw, 2.5rem);
            line-height: 1.2;
        }
        
        .section-title {
            font-size: clamp(1rem, 4vw, 1.25rem);
        }
        
        /* Botões responsivos */
        .btn-responsive {
            padding: clamp(0.75rem, 3vw, 1rem) clamp(1.5rem, 6vw, 2rem);
            font-size: clamp(0.875rem, 3vw, 1rem);
            border-radius: 1rem;
            width: 100%;
            max-width: 20rem;
        }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--header-color), var(--accent-color));
            border-radius: 4px;
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
                            <i class="fas fa-paw text-white text-lg"></i>
                        </div>
                        <h1 class="main-title font-black bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                            TAREFA 3
                        </h1>
                    </div>
                    <p class="text-gray-400 text-xs font-medium tracking-wider uppercase">Avaliação do Mascote</p>
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
            <div class="card-bg rounded-3xl w-full max-w-5xl text-center fade-in">
                
                <!-- Formulário Principal -->
                <form  action="../controllers/controller_mascote.php" method="post" class="space-y-8">
                <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                <div class="flex flex-col items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-yellow-600 flex items-center justify-center">
                            <i class="fas fa-paw text-white text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="main-title font-black mb-4 bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                                Avaliação do Mascote
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
                            <select id="cursoInput" name="curso" required class="input-field w-full rounded-2xl px-4 py-3 text-white focus:outline-none">
                                <option value="" selected disabled>Selecione o curso</option>
                                <?php
                                $dados = $select->select_curso();
                                foreach ($dados as $dado) {
                                ?>
                                    <option value="<?= $dado['curso_id'] ?>"><?= $dado['nome_curso'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <!-- Critérios de Avaliação -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 justify-center mb-6">
                            <i class="fas fa-star text-yellow-400 text-xl"></i>
                            <span class="section-title font-bold text-gray-200">Critérios de Avaliação</span>
                        </div>
                        
                        <div class="criteria-grid">
                            <!-- Animação -->
                            <div class="criteria-item">
                                <div class="criteria-icon bg-gradient-to-br from-yellow-500 to-orange-600">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <h4 class="criteria-title">Animação</h4>
                                <p class="criteria-subtitle">Energia e carisma</p>
                                <div class="score-input-wrapper">
                                    <input 
                                        type="number" 
                                        id="notaAnimacao" 
                                        name="nota_animacao" 
                                        min="0" 
                                        max="100" 
                                        placeholder="0-100"
                                        class="score-input"
                                        value=""
                                    >
                                </div>
                                <div id="displayAnimacao" class="score-display hidden">
                                    <span id="valorAnimacao">0</span> pontos
                                </div>
                            </div>

                            <!-- Vestimenta -->
                            <div class="criteria-item">
                                <div class="criteria-icon bg-gradient-to-br from-green-500 to-emerald-600">
                                    <i class="fas fa-tshirt"></i>
                                </div>
                                <h4 class="criteria-title">Vestimenta</h4>
                                <p class="criteria-subtitle">Criatividade visual</p>
                                <div class="score-input-wrapper">
                                    <input 
                                        type="number" 
                                        id="notaVestimenta" 
                                        name="nota_vestimenta" 
                                        min="0" 
                                        max="100" 
                                        placeholder="0-100"
                                        class="score-input"
                                        value=""
                                    >
                                </div>
                                <div id="displayVestimenta" class="score-display hidden">
                                    <span id="valorVestimenta">0</span> pontos
                                </div>
                            </div>

                            <!-- Identidade -->
                            <div class="criteria-item">
                                <div class="criteria-icon bg-gradient-to-br from-blue-500 to-purple-600">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                                <h4 class="criteria-title">Identidade</h4>
                                <p class="criteria-subtitle">Conexão com curso</p>
                                <div class="score-input-wrapper">
                                    <input 
                                        type="number" 
                                        id="notaIdentidade" 
                                        name="nota_identidade" 
                                        min="0" 
                                        max="100" 
                                        placeholder="0-100"
                                        class="score-input"
                                        value=""
                                    >
                                </div>
                                <div id="displayIdentidade" class="score-display hidden">
                                    <span id="valorIdentidade">0</span> pontos
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Painel de Pontuação Total - Simplificado -->
                    <div id="pontosPainel" class="mt-8 hidden slide-up">
                        <div class="stats-card rounded-xl p-4 text-center max-w-sm mx-auto">
                            <div class="flex items-center justify-center gap-2 mb-3">
                                <i class="fas fa-calculator text-yellow-400"></i>
                                <h3 class="text-lg font-bold text-white">Pontuação Total</h3>
                            </div>
                            <p class="text-sm text-gray-300 mb-3">
                                <span id="cursoSelecionado" class="font-semibold text-yellow-400"></span>
                            </p>
                            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg p-3">
                                <p class="text-2xl font-black text-black">
                                    <span id="pontosTotais">0</span> / 300
                                </p>
                                <p class="text-xs font-medium text-black opacity-75">
                                    Média: <span id="mediaPontos">0.0</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botão de Envio -->
                    <div class="pt-8 flex justify-center">
                        <button type="submit" class="btn-primary btn-responsive font-bold text-white flex items-center justify-center gap-3 hover:scale-105 transition-transform">
                            <i class="fas fa-paper-plane"></i>
                            Confirmar Avaliação
                        </button>
                    </div>
                </form>

                <!-- Botão para Voltar -->
                <div id="voltarButton" class="mt-8 hidden">
                    <button onclick="resetForm()" class="btn-secondary btn-responsive font-semibold text-gray-300 flex items-center justify-center gap-2 mx-auto hover:scale-105 transition-transform">
                        <i class="fas fa-arrow-left"></i>
                        Nova Avaliação
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Tela de Sucesso -->
    <div id="sucessoMascote" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4">
        <div class="card-bg rounded-3xl p-8 w-full max-w-md text-center fade-in">
            <div class="flex flex-col items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center pulse-glow">
                    <i class="fas fa-check-circle text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-green-400 mb-2">Avaliação Registrada!</h2>
                <p class="text-lg text-gray-200">As notas do mascote foram computadas com sucesso.</p>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-3 mt-2">
                    <p class="text-xl font-black text-black">
                        Total: <span id="pontosFinal">0</span> / 300 pontos
                    </p>
                </div>
            </div>
            <button onclick="fecharSucesso()" class="nova-votacao-btn flex items-center justify-center gap-3 mx-auto mt-6">
                <span class="icon-wrapper"><i class="fas fa-arrow-rotate-left"></i></span>
                <span class="font-extrabold text-lg tracking-wide">Nova Avaliação</span>
            </button>
        </div>
    </div>
    <!-- Tela de Erro -->
    <div id="erroMascote" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4">
        <div class="card-bg rounded-3xl p-8 w-full max-w-md text-center fade-in">
            <div class="flex flex-col items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                    <i class="fas fa-times-circle text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-red-400 mb-2">Erro ao Registrar!</h2>
                <p class="text-lg text-gray-200">Ocorreu um erro ao registrar a avaliação. Tente novamente.</p>
            </div>
            <button onclick="fecharErro()" class="nova-votacao-btn flex items-center justify-center gap-3 mx-auto mt-6">
                <span class="icon-wrapper"><i class="fas fa-arrow-rotate-left"></i></span>
                <span class="font-extrabold text-lg tracking-wide">Nova Avaliação</span>
            </button>
        </div>
    </div>
    <!-- Tela de Já Confirmado -->
    <div id="jaConfirmadoMascote" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4">
        <div class="card-bg rounded-3xl p-8 w-full max-w-md text-center fade-in">
            <div class="flex flex-col items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center pulse-glow">
                    <i class="fas fa-exclamation-triangle text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-yellow-400 mb-2">Já Confirmado!</h2>
                <p class="text-lg text-gray-200">A avaliação deste mascote já foi registrada anteriormente.</p>
            </div>
            <button onclick="fecharJaConfirmado()" class="nova-votacao-btn flex items-center justify-center gap-3 mx-auto mt-6">
                <span class="icon-wrapper"><i class="fas fa-arrow-rotate-left"></i></span>
                <span class="font-extrabold text-lg tracking-wide">Nova Avaliação</span>
            </button>
        </div>
    </div>
    <!-- Tela de Campos Obrigatórios -->
    <div id="emptyMascote" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4">
        <div class="card-bg rounded-3xl p-8 w-full max-w-md text-center fade-in">
            <div class="flex flex-col items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-red-400 mb-2">Campos Obrigatórios!</h2>
                <p class="text-lg text-gray-200">Preencha todos os campos obrigatórios para registrar a avaliação.</p>
            </div>
            <button onclick="fecharEmpty()" class="nova-votacao-btn flex items-center justify-center gap-3 mx-auto mt-6">
                <span class="icon-wrapper"><i class="fas fa-arrow-rotate-left"></i></span>
                <span class="font-extrabold text-lg tracking-wide">Nova Avaliação</span>
            </button>
        </div>
    </div>

    <script>
        // Aguardar o DOM carregar completamente
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('mascoteForm');
            const pontosPainel = document.getElementById('pontosPainel');
            const voltarButton = document.getElementById('voltarButton');
            
            // Elementos dos inputs
            const notaAnimacao = document.getElementById('notaAnimacao');
            const notaVestimenta = document.getElementById('notaVestimenta');
            const notaIdentidade = document.getElementById('notaIdentidade');
            const cursoInput = document.getElementById('cursoInput');
            
            // Elementos de display
            const displayAnimacao = document.getElementById('displayAnimacao');
            const displayVestimenta = document.getElementById('displayVestimenta');
            const displayIdentidade = document.getElementById('displayIdentidade');
            const valorAnimacao = document.getElementById('valorAnimacao');
            const valorVestimenta = document.getElementById('valorVestimenta');
            const valorIdentidade = document.getElementById('valorIdentidade');
            const pontosTotais = document.getElementById('pontosTotais');
            const mediaPontos = document.getElementById('mediaPontos');
            const cursoSelecionado = document.getElementById('cursoSelecionado');

            // Garantir que os inputs começem vazios
            notaAnimacao.value = '';
            notaVestimenta.value = '';
            notaIdentidade.value = '';

            // Função para validar nota
            function validarNota(input, valor) {
                if (valor < 0 || valor > 100 || isNaN(valor)) {
                    input.classList.add('invalid');
                    input.classList.remove('valid');
                    return false;
                } else {
                    input.classList.add('valid');
                    input.classList.remove('invalid');
                    return true;
                }
            }

            // Função para atualizar display de nota individual
            function atualizarDisplayNota(input, display, valorSpan) {
                const valor = parseInt(input.value);
                
                if (input.value !== '' && !isNaN(valor) && validarNota(input, valor)) {
                    valorSpan.textContent = valor;
                    display.classList.remove('hidden');
                } else {
                    display.classList.add('hidden');
                }
                
                atualizarPontuacaoTotal();
            }

            // Função para calcular e atualizar pontuação total
            function atualizarPontuacaoTotal() {
                const animacao = notaAnimacao.value !== '' ? parseInt(notaAnimacao.value) || 0 : 0;
                const vestimenta = notaVestimenta.value !== '' ? parseInt(notaVestimenta.value) || 0 : 0;
                const identidade = notaIdentidade.value !== '' ? parseInt(notaIdentidade.value) || 0 : 0;
                
                const total = animacao + vestimenta + identidade;
                const media = total > 0 ? total / 3 : 0;
                
                pontosTotais.textContent = total;
                mediaPontos.textContent = media.toFixed(1);
                
                // Mostrar painel se houver pelo menos uma nota e curso selecionado
                if ((animacao > 0 || vestimenta > 0 || identidade > 0) && cursoInput.value) {
                    pontosPainel.classList.remove('hidden');
                    const cursoTexto = cursoInput.options[cursoInput.selectedIndex].text;
                    cursoSelecionado.textContent = cursoTexto;
                } else {
                    pontosPainel.classList.add('hidden');
                }
            }

            // Event listeners para os inputs de nota
            notaAnimacao.addEventListener('input', function() {
                atualizarDisplayNota(this, displayAnimacao, valorAnimacao);
            });

            notaVestimenta.addEventListener('input', function() {
                atualizarDisplayNota(this, displayVestimenta, valorVestimenta);
            });

            notaIdentidade.addEventListener('input', function() {
                atualizarDisplayNota(this, displayIdentidade, valorIdentidade);
            });

            // Event listener para o curso
            cursoInput.addEventListener('change', function() {
                atualizarPontuacaoTotal();
            });

            // Validação em tempo real para limitar valores
            [notaAnimacao, notaVestimenta, notaIdentidade].forEach(input => {
                input.addEventListener('keypress', function(e) {
                    // Permitir apenas números
                    if (!/[0-9]/.test(String.fromCharCode(e.which))) {
                        e.preventDefault();
                    }
                });

                input.addEventListener('input', function(e) {
                    if (e.target.value !== '') {
                        let valor = parseInt(e.target.value);
                        if (valor > 100) {
                            e.target.value = 100;
                        } else if (valor < 0) {
                            e.target.value = 0;
                        }
                    }
                });
            });

            // Submissão do formulário
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const cursoValue = cursoInput.value;
                const animacao = notaAnimacao.value !== '' ? parseInt(notaAnimacao.value) || 0 : 0;
                const vestimenta = notaVestimenta.value !== '' ? parseInt(notaVestimenta.value) || 0 : 0;
                const identidade = notaIdentidade.value !== '' ? parseInt(notaIdentidade.value) || 0 : 0;
                
                if (!cursoValue) {
                    alert('Por favor, selecione o curso.');
                    cursoInput.focus();
                    return;
                }
                
                if (animacao === 0 && vestimenta === 0 && identidade === 0) {
                    alert('Por favor, preencha pelo menos uma nota.');
                    notaAnimacao.focus();
                    return;
                }
                
                // Validar se todas as notas preenchidas estão no range correto
                const inputs = [notaAnimacao, notaVestimenta, notaIdentidade];
                for (let input of inputs) {
                    if (input.value !== '' && !validarNota(input, parseInt(input.value))) {
                        alert('Por favor, digite notas válidas entre 0 e 100.');
                        input.focus();
                        return;
                    }
                }
                
                // Atualizar pontuação final
                const total = animacao + vestimenta + identidade;
                document.getElementById('pontosFinal').textContent = total;
                
                // Mostrar tela de sucesso (modal)
                form.style.display = 'none';
                voltarButton.classList.add('hidden');
                document.getElementById('sucessoMascote').classList.remove('hidden');
            });

            // Função global para reset
            window.resetForm = function() {
                // Mostrar formulário novamente
                form.style.display = 'block';
                voltarButton.classList.add('hidden');
                
                // Reset do formulário
                form.reset();
                pontosPainel.classList.add('hidden');
                
                // Limpar valores dos inputs explicitamente
                notaAnimacao.value = '';
                notaVestimenta.value = '';
                notaIdentidade.value = '';
                
                // Esconder displays de nota
                displayAnimacao.classList.add('hidden');
                displayVestimenta.classList.add('hidden');
                displayIdentidade.classList.add('hidden');
                
                // Remover classes de validação
                [notaAnimacao, notaVestimenta, notaIdentidade].forEach(input => {
                    input.classList.remove('valid', 'invalid');
                });
            };

            // Função global para fechar sucesso
            window.fecharSucesso = function() {
                document.getElementById('sucessoMascote').classList.add('hidden');
                resetForm();
            };

            // Exibir modal conforme status da URL
            var status = '<?php echo $status; ?>';
            if (status === 'sucesso') {
                document.getElementById('sucessoMascote').classList.remove('hidden');
                form.style.display = 'none';
                voltarButton.classList.add('hidden');
            } else if (status === 'erro') {
                document.getElementById('erroMascote').classList.remove('hidden');
                form.style.display = 'none';
                voltarButton.classList.add('hidden');
            } else if (status === 'ja_confirmado') {
                document.getElementById('jaConfirmadoMascote').classList.remove('hidden');
                form.style.display = 'none';
                voltarButton.classList.add('hidden');
            } else if (status === 'empty') {
                document.getElementById('emptyMascote').classList.remove('hidden');
                form.style.display = 'none';
                voltarButton.classList.add('hidden');
            }
        });

        // Funções para fechar modais e resetar formulário
        function fecharErro() {
            document.getElementById('erroMascote').classList.add('hidden');
            resetForm();
        }
        function fecharJaConfirmado() {
            document.getElementById('jaConfirmadoMascote').classList.add('hidden');
            resetForm();
        }
        function fecharEmpty() {
            document.getElementById('emptyMascote').classList.add('hidden');
            resetForm();
        }
    </script>
</body>
</html>