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
    <title>Tarefa 02 - Grito de Guerra</title>
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
        
        .stats-card {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.98) 0%, rgba(20, 20, 20, 0.98) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
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
        
        .status-complete {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        
        .status-failed {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
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
        
        /* Estilo customizado para o select */
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
        
        select.input-field:hover {
            border-color: rgba(255, 183, 51, 0.3) !important;
            background: linear-gradient(145deg, #202020 0%, #1a1a1a 100%) !important;
        }
        
        select.input-field option {
            background-color: #232323 !important;
            color: #fff !important;
        }
        
        select.input-field option:hover,
        select.input-field option:focus {
            background-color: #444 !important;
            color: #ffb733 !important;
        }
        
        select.input-field option:checked {
            background-color: #ffb733 !important;
            color: #181818 !important;
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
        
        .points-glow {
            animation: pointsGlow 3s ease-in-out infinite;
        }
        
        @keyframes pointsGlow {
            0%, 100% { 
                box-shadow: 0 4px 20px rgba(255, 183, 51, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 8px 40px rgba(255, 183, 51, 0.6);
                transform: scale(1.05);
            }
        }
        
        .zero-points-glow {
            animation: zeroPointsGlow 2s ease-in-out infinite;
        }
        
        @keyframes zeroPointsGlow {
            0%, 100% { 
                box-shadow: 0 4px 20px rgba(239, 68, 68, 0.3);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
                transform: scale(1.02);
            }
        }
        
        /* Radio button customization - PADDING REDUZIDO */
        .radio-option {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.8) 0%, rgba(20, 20, 20, 0.8) 100%);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 1rem; /* Reduzido de 1.5rem para 1rem */
            transition: all 0.3s ease;
            cursor: pointer;
            backdrop-filter: blur(10px);
        }
        
        .radio-option:hover {
            border-color: rgba(255, 183, 51, 0.3);
            box-shadow: 0 8px 25px rgba(255, 183, 51, 0.1);
            transform: translateY(-2px);
        }
        
        .radio-option.selected {
            border-color: var(--accent-color);
            box-shadow: 0 8px 30px rgba(255, 183, 51, 0.2);
        }
        
        .radio-option input[type="radio"] {
            width: 1.5rem;
            height: 1.5rem;
            accent-color: var(--accent-color);
        }
        
        /* Melhorias de Responsividade */
        @media (max-width: 640px) {
            .header-bg { padding: 1rem 0; }
            .card-bg { padding: 1.5rem; }
            .radio-option { padding: 0.75rem; } /* Ainda menor em mobile */
            .user-chip { font-size: 0.75rem; padding: 0.375rem 0.75rem; }
        }
        
        @media (max-width: 480px) {
            .status-complete, .status-failed {
                font-size: 0.875rem;
                padding: 10px 20px;
            }
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
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #00a040, #ff9500);
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
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100%;
            flex-direction: row;
        }
        .header-title-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
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
        @media (max-width: 640px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                align-items: center;
            }
            .user-chip-desktop {
                position: relative;
                top: auto;
                right: auto;
                margin-top: 1rem;
            }
            .header-title-section {
                align-items: center;
                text-align: center;
            }
        }
        .main-title {
            font-size: clamp(1.5rem, 6vw, 2.5rem);
            line-height: 1.2;
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
                            <i class="fas fa-bullhorn text-white text-lg"></i>
                        </div>
                        <h1 class="main-title font-black bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                            TAREFA 2
                        </h1>
                    </div>
                    <p class="text-gray-400 text-xs font-medium tracking-wider uppercase">GRITO DE GUERRA </p>
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
                        <span class="text-gray-100"><?php echo $_SESSION['Nome']?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-8">
        <div class="flex flex-col items-center justify-center min-h-[70vh]">
            <div class="card-bg rounded-3xl p-8 sm:p-12 max-w-2xl w-full text-center fade-in">
                
                <!-- Formulário Principal -->
                <form id="gritoForm" action="../controllers/controller_grito.php" method="post" class="space-y-8">
                    <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                <div class="flex flex-col items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-yellow-600 flex items-center justify-center">
                            <i class="fas fa-bullhorn text-white text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl sm:text-4xl font-black mb-4 bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                                Grito de Guerra do Curso
                            </h2>
                            <p class="text-lg text-gray-300 font-medium">
                                Selecione o curso e confirme se o grito de guerra foi realizado:
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

                    <!-- Opções de Radio -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-12">
                        <label class="radio-option" onclick="selectOption(this, 'sim')">
                            <div class="flex flex-row items-center gap-3 justify-center">
                                <div class="w-7 h-7 rounded-md bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                    <i class="fas fa-check text-white text-base"></i>
                                </div>
                                <span class="text-lg font-bold text-green-400">Sim, foi realizado!</span>
                            </div>
                            <input type="radio" name="grito" value="sim" class="sr-only" required>
                        </label>

                        <label class="radio-option" onclick="selectOption(this, 'nao')">
                            <div class="flex flex-row items-center gap-3 justify-center">
                                <div class="w-7 h-7 rounded-md bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                    <i class="fas fa-times text-white text-base"></i>
                                </div>
                                <span class="text-lg font-bold text-red-400">Não foi realizado</span>
                            </div>
                            <input type="radio" name="grito" value="nao" class="sr-only">
                        </label>
                    </div>

                    <!-- Botão de Envio -->
                    <div class="pt-8">
                        <button type="submit" class="btn-primary px-8 py-4 rounded-2xl font-bold text-white flex items-center justify-center gap-3 text-lg mx-auto">
                            <i class="fas fa-paper-plane"></i>
                            Confirmar Status
                        </button>
                    </div>
                </form>

                <!-- Painel de Sucesso -->
                <div id="painelGrito" class="mt-12 hidden slide-up">
                    <div class="stats-card rounded-3xl p-8 mb-8">
                        <div class="flex flex-col items-center gap-6">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center pulse-glow">
                                <i class="fas fa-trophy text-white text-4xl"></i>
                            </div>
                            <div class="text-center">
                                <h3 class="text-3xl sm:text-4xl font-black mb-4 bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                                    Tarefa Cumprida!
                                </h3>
                                <p class="text-lg text-gray-300 mb-4">
                                    O grito de guerra do curso <span id="cursoSelecionado" class="font-bold" style="color: var(--accent-color);"></span> foi realizado com sucesso.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Painel de Não Realizado -->
                <div id="painelNaoGrito" class="mt-12 hidden slide-up">
                    <div class="stats-card rounded-3xl p-8 mb-8">
                        <div class="flex flex-col items-center gap-6">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                                <i class="fas fa-times-circle text-white text-4xl"></i>
                            </div>
                            <div class="text-center">
                                <h3 class="text-3xl sm:text-4xl font-black mb-4 text-red-400">
                                    Tarefa Não Cumprida
                                </h3>
                                <p class="text-lg text-gray-300 mb-6">
                                    O grito de guerra do curso <span id="cursoSelecionadoPendente" class="font-bold" style="color: var(--accent-color);"></span> não atendeu aos critérios estabelecidos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

          
            </div>
        </div>
    </main>

    <!-- Tela de Sucesso -->
    <div id="sucessoGrito" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4">
        <div class="card-bg rounded-3xl p-8 w-full max-w-md text-center fade-in">
            <div class="flex flex-col items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center pulse-glow">
                    <i class="fas fa-check-circle text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-green-400 mb-2">Voto Registrado!</h2>
                <p class="text-lg text-gray-200">O status do grito de guerra foi computado com sucesso.</p>
            </div>
            <button onclick="fecharSucesso()" class="nova-votacao-btn flex items-center justify-center gap-3 mx-auto mt-6">
                <span class="icon-wrapper"><i class="fas fa-arrow-rotate-left"></i></span>
                <span class="font-extrabold text-lg tracking-wide">Nova Votação</span>
            </button>
        </div>
    </div>

    <!-- Tela de Já Confirmado -->
    <div id="jaConfirmadoGrito" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 backdrop-blur-sm p-4">
        <div class="card-bg rounded-3xl p-8 w-full max-w-md text-center fade-in">
            <div class="flex flex-col items-center gap-4 mb-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center pulse-glow">
                    <i class="fas fa-exclamation-triangle text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-yellow-400 mb-2">Já Confirmado!</h2>
                <p class="text-lg text-gray-200">O grito de guerra deste curso já foi registrado anteriormente.</p>
            </div>
            <button onclick="fecharJaConfirmado()" class="nova-votacao-btn flex items-center justify-center gap-3 mx-auto mt-6">
                <span class="icon-wrapper"><i class="fas fa-arrow-rotate-left"></i></span>
                <span class="font-extrabold text-lg tracking-wide">Nova Votação</span>
            </button>
        </div>
    </div>

    <script>
        const form = document.getElementById('gritoForm');
        const painelGrito = document.getElementById('painelGrito');
        const painelNaoGrito = document.getElementById('painelNaoGrito');
        const voltarButton = document.getElementById('voltarButton');

        // Mapeamento de cursos para exibição
        const cursosNomes = {
            'enfermagem': 'Enfermagem',
            'informatica': 'Informática',
            'meio-ambiente': 'Meio Ambiente',
            'administracao': 'Administração',
            'edificacoes': 'Edificações'
        };

        function selectOption(element, value) {
            // Remove seleção anterior
            document.querySelectorAll('.radio-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Adiciona seleção atual
            element.classList.add('selected');
            
            // Marca o radio button
            element.querySelector('input[type="radio"]').checked = true;
        }

        function resetForm() {
            // Mostra o formulário novamente
            form.style.display = 'block';
            
            // Esconde os painéis
            painelGrito.classList.add('hidden');
            painelNaoGrito.classList.add('hidden');
            voltarButton.classList.add('hidden');
            
            // Reset do formulário
            form.reset();
            document.querySelectorAll('.radio-option').forEach(option => {
                option.classList.remove('selected');
            });
        }

        // Função global para fechar sucesso
        window.fecharSucesso = function() {
            document.getElementById('sucessoGrito').classList.add('hidden');
            resetForm();
        };

        // Função global para fechar já confirmado
        window.fecharJaConfirmado = function() {
            document.getElementById('jaConfirmadoGrito').classList.add('hidden');
            resetForm();
        };

        // Exibir mensagem de acordo com o status da URL
        document.addEventListener('DOMContentLoaded', function() {
            var status = '<?php echo $status; ?>';
            if (status === 'sucesso') {
                document.getElementById('sucessoGrito').classList.remove('hidden');
                form.style.display = 'none';
                voltarButton.classList.add('hidden');
                setTimeout(() => { createConfetti(); }, 500);
            } else if (status === 'erro') {
                showNotification('Ocorreu um erro ao registrar o voto. Tente novamente.', 'error');
            } else if (status === 'ja_confirmado') {
                document.getElementById('jaConfirmadoGrito').classList.remove('hidden');
                form.style.display = 'none';
                voltarButton.classList.add('hidden');
            } else if (status === 'empty') {
                showNotification('Preencha todos os campos obrigatórios.', 'error');
            }
        });
    </script>
</body>
</html>