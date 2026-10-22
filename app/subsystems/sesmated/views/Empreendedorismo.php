<?php 
require_once('../../../main/models/sessions.php');
$session = new sessions;
$session->autenticar_session();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefa 12 - Empreendedorismo</title>
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
            font-size: 14px;
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
            font-size: 14px;
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
            font-size: 13px;
            padding: 0.5rem 1rem;
        }
        
        .btn-primary:hover {
            box-shadow: 0 8px 30px rgba(0, 179, 72, 0.4);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: linear-gradient(145deg, #2a2a2a 0%, #1a1a1a 100%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
            font-size: 13px;
            padding: 0.5rem 1rem;
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
        
        .modal-fullscreen {
            background: radial-gradient(ellipse at top, #1a1a1a 0%, #0a0a0a 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            overflow-y: auto;
        }
        
        .modal-fullscreen-header {
            background: linear-gradient(135deg, var(--header-bg) 0%, rgba(0, 0, 0, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .modal-fullscreen-content {
            min-height: calc(100vh - 80px);
            padding: 1.5rem;
        }
        
        .step-indicator {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.8) 0%, rgba(20, 20, 20, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .step-active {
            background: linear-gradient(135deg, var(--header-color) 0%, #00a040 100%);
            color: white;
        }
        
        .step-completed {
            background: linear-gradient(135deg, var(--accent-color) 0%, #e6a429 100%);
            color: #1a1a1a;
        }
        
        .selection-card {
            background: linear-gradient(145deg, rgba(30, 30, 30, 0.95) 0%, rgba(25, 25, 25, 0.95) 100%);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }
        
        .selection-card:hover {
            border-color: var(--accent-color);
            box-shadow: 0 8px 30px rgba(255, 183, 51, 0.2);
            transform: translateY(-2px);
        }
        
        .selection-card.selected {
            border-color: var(--header-color);
            box-shadow: 0 8px 30px rgba(0, 179, 72, 0.3);
        }
        
        .stats-card {
            background: linear-gradient(145deg, rgba(40, 40, 40, 0.8) 0%, rgba(30, 30, 30, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
        
        .slide-in-right {
            animation: slideInRight 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        
        @keyframes slideInRight {
            from { 
                opacity: 0; 
                transform: translateX(100px); 
            }
            to { 
                opacity: 1; 
                transform: translateX(0); 
            }
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
            border: 1px solid rgba(255,255,255,0.12);
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 2.5rem;
        }
        
        select.input-field option {
            color: #222 !important;
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
        .user-chip {
            background: linear-gradient(145deg, #232d25 0%, #181f1a 100%);
            border: 1px solid #1f3a26;
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: clamp(0.7rem, 2vw, 0.8rem);
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
            
            .modal-fullscreen-content {
                padding: 1rem;
            }
        }
        .main-title {
            font-size: clamp(1.3rem, 5vw, 2rem);
            line-height: 1.2;
        }
        
        h2 { font-size: 1.4rem; }
        h3 { font-size: 1.2rem; }
        h4 { font-size: 1.1rem; }
        
        .nav-button {
            background: linear-gradient(145deg, #3a3a3a 0%, #2a2a2a 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            font-size: 12px;
            padding: 0.4rem 0.8rem;
        }
        
        .nav-button:hover {
            background: linear-gradient(145deg, #4a4a4a 0%, #3a3a3a 100%);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Estilos para os formulários dos cursos */
        .form-curso {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .form-curso.active {
            display: block;
        }
        
        .form-section {
            background: linear-gradient(145deg, rgba(40, 40, 40, 0.8) 0%, rgba(30, 30, 30, 0.9) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .form-section h3 {
            color: var(--header-color);
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="header-bg">
        <div class="container-responsive py-3">
            <div class="header-content">
                <!-- Título e Logo Centralizados -->
                <div class="header-title-section">
                    <div class="header-title-row">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-green-500 via-emerald-600 to-green-700 flex items-center justify-center pulse-glow">
                            <i class="fas fa-store text-white text-sm"></i>
                        </div>
                        <h1 class="main-title font-black bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent">
                            TAREFA 12
                        </h1>
                    </div>
                    <p class="text-gray-400 text-xs font-medium tracking-wider uppercase">Empreendedorismo</p>
                </div>
                <!-- Botão para abrir o modal de lucro do dia -->
                <button onclick="abrirModalLucroDia()" class="btn-primary ml-4 px-4 py-2 rounded-2xl font-semibold text-white flex items-center gap-2">
                    <i class="fas fa-coins"></i>
                    Lucro do Dia
                </button>
                <!-- Chip do Usuário - Posicionado à direita no desktop -->
                <div class="user-chip user-chip-desktop">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <i class="fas fa-user text-green-300 text-xs"></i>
                    </div>
                    <span class="text-gray-100"><?=$_SESSION['Nome']?></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-6">
        <!-- Cadastro Section -->
        <section class="mb-10">
            <div class="card-bg rounded-3xl p-6 fade-in">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-plus-circle text-xl" style="color: var(--header-color);"></i>
                    <h2 class="text-xl font-bold">Cadastrar Produto</h2>
                </div>
                
                <form action="../controllers/controller_empreendedorismo.php" method="post" id="produtoForm" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label for="cursoProduto" class="block text-xs font-bold mb-1 text-gray-300 uppercase tracking-wide">
                                <i class="fas fa-graduation-cap mr-2"></i>Curso do Produto
                            </label>
                            <select id="cursoProduto" name="cursoProduto" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none" required>
                                <option value="">Selecione o curso</option>
                                <option value="1">Enfermagem</option>
                                <option value="2">Informática</option>
                                <option value="3">Meio ambiente</option>
                                <option value="4">Administração</option>
                                <option value="5">Edificações</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="nomeProduto" class="block text-xs font-bold mb-1 text-gray-300 uppercase tracking-wide">
                                <i class="fas fa-box mr-2"></i>Nome do Produto
                            </label>
                            <input type="text" id="nomeProduto" name="nomeProduto" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none" required>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="precoProduto" class="block text-xs font-bold mb-1 text-gray-300 uppercase tracking-wide">
                                <i class="fas fa-dollar-sign mr-2"></i>Valor unitário (R$)
                            </label>
                            <input type="number" id="precoProduto" name="precoProduto" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none" step="0.01" min="0" required>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="quantidadeProduto" class="block text-xs font-bold mb-1 text-gray-300 uppercase tracking-wide">
                                <i class="fas fa-sort-numeric-up mr-2"></i>Quantidade
                            </label>
                            <input type="number" id="quantidadeProduto" name="quantidadeProduto" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none" min="0" required>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 justify-end mt-4">
                        <button type="submit" class="btn-primary px-6 py-2.5 rounded-2xl font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Cadastrar Produto
                        </button>
                        <button type="button" onclick="abrirModalLucroDia()" class="px-6 py-2.5 rounded-2xl font-semibold text-white flex items-center gap-2 bg-gradient-to-br from-orange-500 to-yellow-400 hover:from-orange-600 hover:to-yellow-500 transition-all">
                            <i class="fas fa-coins"></i>
                            Lucro do Dia
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Gerenciar Produtos Modal -->
        <div id="produtosModal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
            <div class="modal-bg rounded-3xl p-6 w-full max-w-5xl mx-4 slide-up max-h-[90vh] overflow-y-auto">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white text-lg"></i>
                    </div>
                    <h2 class="text-xl font-bold">Gerenciar Produtos</h2>
                </div>
                
                <div class="mb-5">
                    <h3 class="text-lg font-semibold mb-2" id="barracaAtual"></h3>
                </div>
                
                <!-- Cadastro de Produtos -->
                <div class="card-bg rounded-2xl p-5 mb-6">
                    <h4 class="text-base font-semibold mb-3">Produtos da Barraca</h4>
                    <div id="produtosList" class="space-y-2 max-h-32 overflow-y-auto">
                        <!-- Produtos serão inseridos aqui -->
                    </div>
                </div>
                
                <!-- Registrar Venda -->
                <div class="card-bg rounded-2xl p-5 mb-6">
                    <h4 class="text-base font-semibold mb-3">Registrar Venda</h4>
                    <form id="vendaForm" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium mb-2">Produto</label>
                            <select id="produtoVenda" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none" required>
                                <option value="">Selecione o produto</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-2">Quantidade Vendida</label>
                            <input type="number" id="quantidadeVenda" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none" min="1" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-2">Valor Total</label>
                            <input type="number" id="valorVenda" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none" step="0.01" readonly>
                        </div>
                        <div class="md:col-span-3">
                            <button type="submit" class="btn-primary px-5 py-2.5 rounded-2xl font-semibold text-white flex items-center gap-2">
                                <i class="fas fa-cash-register"></i>
                                Registrar Venda
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Resumo de Vendas -->
                <div class="card-bg rounded-2xl p-5 mb-6">
                    <h4 class="text-base font-semibold mb-3">Resumo de Vendas</h4>
                    <div id="resumoVendas">
                        <!-- Resumo será inserido aqui -->
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button onclick="fecharProdutos()" class="btn-secondary px-5 py-2.5 rounded-2xl font-semibold text-gray-300 flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Fullscreen Lucro do Dia -->
    <div id="modalLucroDia" class="modal-fullscreen hidden">
        <div class="modal-fullscreen-header">
            <div class="container-responsive py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center">
                            <i class="fas fa-coins text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-white">Lucro do Dia</h1>
                            <p class="text-gray-400 text-xs">Selecione um curso e digite o valor final do lucro</p>
                        </div>
                    </div>
                    <button onclick="fecharModalLucroDia()" class="w-10 h-10 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white hover:from-red-600 hover:to-red-700 transition-all duration-200 hover:scale-105">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-fullscreen-content flex flex-col items-center justify-center">
            <div class="card-bg rounded-3xl p-8 max-w-md w-full">
                <form id="formLucroDia" action="../controllers/controller_empreendedorismo.php" method="post" autocomplete="off">
                    <div class="mb-6">
                        <input type="hidden" name="id_avaliador" value="<?=$_SESSION['user_id']?>">
                        <label for="selectCursoLucro" class="block text-xs font-bold mb-2 text-gray-300 uppercase tracking-wide">
                            <i class="fas fa-graduation-cap mr-2"></i>Selecione o Curso
                        </label>
                        <select id="selectCursoLucro" name="curso" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none">
                            <option value="">Selecione o curso</option>
                            <option value="1">Enfermagem</option>
                            <option value="2">Informática</option>
                            <option value="3">Meio ambiente</option>
                            <option value="4">Administração</option>
                            <option value="5">Edificações</option>
                        </select>
                    </div>
                    <div id="inputLucroDiaContainer" class="mt-4 hidden">
                        <label for="valorDeclarado" class="block text-xs font-bold mb-2 text-gray-300 uppercase tracking-wide">
                            Valor Declarado
                        </label>
                        <input type="number" id="valorDeclarado" name="valor_declarado" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none text-center text-xl font-bold mb-4" step="0.01" min="0" placeholder="Digite o valor declarado" required>

                        <label for="valorEspecie" class="block text-xs font-bold mb-2 text-gray-300 uppercase tracking-wide">
                            Valor em Espécie
                        </label>
                        <input type="number" id="valorEspecie" name="em_especie" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none text-center text-xl font-bold mb-4" step="0.01" min="0" placeholder="Digite o valor em espécie" required>

                        <label for="valorConta" class="block text-xs font-bold mb-2 text-gray-300 uppercase tracking-wide">
                            Valor em Conta
                        </label>
                        <input type="number" id="valorConta" name="em_conta" class="input-field w-full rounded-2xl px-3 py-2.5 text-white focus:outline-none text-center text-xl font-bold mb-4" step="0.01" min="0" placeholder="Digite o valor em conta" required>

                        <button type="submit" class="btn-primary w-full rounded-2xl font-semibold text-white flex items-center justify-center gap-2 bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 transition-all">
                            <i class="fas fa-save"></i> Salvar Lucro do Dia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let barracas = JSON.parse(localStorage.getItem('barracas') || '[]');
        let produtos = JSON.parse(localStorage.getItem('produtos_barracas') || '[]');
        let vendas = JSON.parse(localStorage.getItem('vendas_barracas') || '[]');
        let barracaAtual = null;
        let produtoAtualizarId = null;
        let etapaAtual = 1;
        let cursoSelecionado = null;

        const cursoColors = {
            'Informática': 'from-blue-500 to-cyan-600',
            'Enfermagem': 'from-red-500 to-pink-600',
            'Administração': 'from-purple-500 to-indigo-600',
            'Meio ambiente': 'from-green-500 to-emerald-600',
            'Edificações': 'from-orange-500 to-yellow-600'
        };

        const cursoIcons = {
            'Informática': 'fas fa-laptop-code',
            'Enfermagem': 'fas fa-user-nurse',
            'Administração': 'fas fa-briefcase',
            'Meio ambiente': 'fas fa-leaf',
            'Edificações': 'fas fa-building'
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            renderBarracas();
            
            // Form submissions
            document.getElementById('produtoForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const curso = document.getElementById('cursoProduto').value;
                // Cria barraca automaticamente se não existir para o curso
                let barraca = barracas.find(b => b.curso === curso);
                if (!barraca) {
                    barraca = {
                        id: Date.now(),
                        nome: 'Barraca de ' + curso,
                        curso: curso,
                        responsaveis: '',
                        timestamp: new Date().toISOString()
                    };
                    barracas.push(barraca);
                    localStorage.setItem('barracas', JSON.stringify(barracas));
                    renderBarracas();
                }
                const produto = {
                    id: Date.now(),
                    barracaId: barraca.id,
                    nome: document.getElementById('nomeProduto').value,
                    preco: parseFloat(document.getElementById('precoProduto').value),
                    quantidadeInicial: parseInt(document.getElementById('quantidadeProduto').value),
                    quantidadeVendida: 0,
                    timestamp: new Date().toISOString()
                };
                produtos.push(produto);
                localStorage.setItem('produtos_barracas', JSON.stringify(produtos));
                this.reset();
                renderProdutos();
                atualizarSelectProdutos();
                showNotification('Produto cadastrado com sucesso!', 'success');
                abrirModalAtualizarProduto(produto.id);
            });

            document.getElementById('vendaForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!barracaAtual) return;
                
                const produtoId = parseInt(document.getElementById('produtoVenda').value);
                const quantidade = parseInt(document.getElementById('quantidadeVenda').value);
                const produto = produtos.find(p => p.id === produtoId);
                
                if (!produto) {
                    showNotification('Produto não encontrado! Só é possível vender produtos previamente cadastrados.', 'error');
                    return;
                }
                
                const estoque = produto.quantidadeInicial - produto.quantidadeVendida;
                if (quantidade > estoque) {
                    showNotification('Quantidade insuficiente em estoque!', 'error');
                    return;
                }
                
                const venda = {
                    id: Date.now(),
                    barracaId: barracaAtual.id,
                    produtoId: produtoId,
                    nomeProduto: produto.nome,
                    quantidade: quantidade,
                    precoUnitario: produto.preco,
                    valorTotal: produto.preco * quantidade,
                    timestamp: new Date().toISOString()
                };
                
                vendas.push(venda);
                localStorage.setItem('vendas_barracas', JSON.stringify(vendas));
                
                // Atualizar quantidade vendida do produto
                produto.quantidadeVendida += quantidade;
                localStorage.setItem('produtos_barracas', JSON.stringify(produtos));
                
                this.reset();
                renderProdutos();
                atualizarSelectProdutos();
                atualizarResumoVendas();
                renderBarracas();
                
                showNotification('Venda registrada com sucesso!', 'success');
            });

            // Atualizar valor da venda automaticamente
            document.getElementById('produtoVenda').addEventListener('change', calcularValorVenda);
            document.getElementById('quantidadeVenda').addEventListener('input', calcularValorVenda);

            // Botão editar produto
            document.getElementById('btnEditarProduto').onclick = function() {
                abrirModalEdicaoFullscreen();
            };
        });

        function renderBarracas() {
            const container = document.getElementById('barracasList');
            container.innerHTML = '';
            
            if (barracas.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-16">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-3xl bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center">
                            <i class="fas fa-store text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-300 mb-3">Nenhuma barraca cadastrada</h3>
                        <p class="text-gray-400 mb-6 text-sm">Cadastre um produto para criar a barraca do curso</p>
                    </div>
                `;
                return;
            }
            
            barracas.forEach(barraca => {
                const vendasBarraca = vendas.filter(v => v.barracaId === barraca.id);
                const totalArrecadado = vendasBarraca.reduce((sum, v) => sum + v.valorTotal, 0);
                
                const card = document.createElement('div');
                card.className = 'card-bg rounded-3xl p-5 card-hover transition-all duration-300 fade-in';
                
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-5">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br ${cursoColors[barraca.curso] || 'from-gray-600 to-gray-700'} flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-store text-white text-lg"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-bold text-white mb-1">${barraca.nome}</h3>
                                <p class="text-xs text-gray-400 font-medium">${barraca.curso}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-400 text-xs">Responsáveis:</span>
                            <p class="text-gray-300 text-xs mt-1">${barraca.responsaveis || 'Não informado'}</p>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300 font-semibold text-sm">Total Arrecadado</span>
                            <span class="text-lg font-bold" style="color: var(--accent-color);">R$ ${totalArrecadado.toFixed(2)}</span>
                        </div>
                        
                        <div class="text-center pt-3">
                            <button onclick="gerenciarProdutos(${barraca.id})" 
                                    class="btn-primary w-full py-2.5 rounded-2xl font-semibold text-white flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart"></i>
                                Gerenciar Produtos
                            </button>
                        </div>
                    </div>
                `;
                
                container.appendChild(card);
            });
        }

        function gerenciarProdutos(id) {
            barracaAtual = barracas.find(b => b.id === id);
            document.getElementById('barracaAtual').textContent = 
                `${barracaAtual.nome} (${barracaAtual.curso})`;
            
            document.getElementById('produtosModal').classList.remove('hidden');
            document.getElementById('produtosModal').classList.add('flex');
            
            renderProdutos();
            atualizarSelectProdutos();
            atualizarResumoVendas();
        }

        function renderProdutos() {
            if (!barracaAtual) return;
            
            const produtosBarraca = produtos.filter(p => p.barracaId === barracaAtual.id);
            const container = document.getElementById('produtosList');
            
            container.innerHTML = '';
            
            if (produtosBarraca.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center py-3 text-sm">Nenhum produto cadastrado ainda</p>';
                return;
            }
            
            produtosBarraca.forEach(produto => {
                const estoque = produto.quantidadeInicial - produto.quantidadeVendida;
                const totalVendido = produto.quantidadeVendida * produto.preco;
                
                const item = document.createElement('div');
                item.className = 'flex justify-between items-center p-2.5 bg-gray-800/50 rounded-xl';
                item.innerHTML = `
                    <div>
                        <span class="font-medium text-white text-sm">${produto.nome}</span>
                        <span class="text-xs text-gray-400 ml-2">R$ ${produto.preco.toFixed(2)}</span>
                    </div>
                    <div class="flex items-center gap-2 text-right text-xs">
                        <div>
                            <div class="text-gray-300">Estoque: ${estoque}</div>
                            <div class="text-gray-400">Vendido: ${produto.quantidadeVendida}</div>
                            <div class="text-green-400 font-bold">R$ ${totalVendido.toFixed(2)}</div>
                        </div>
                        <button class="ml-2 text-yellow-400 hover:text-yellow-300" title="Editar Produto" onclick="abrirModalAtualizarProduto(${produto.id})">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                    </div>
                `;
                container.appendChild(item);
            });
        }

        function atualizarSelectProdutos() {
            if (!barracaAtual) return;
            
            const produtosBarraca = produtos.filter(p => p.barracaId === barracaAtual.id);
            const select = document.getElementById('produtoVenda');
            
            select.innerHTML = '<option value="">Selecione o produto</option>';
            
            produtosBarraca.forEach(produto => {
                const estoque = produto.quantidadeInicial - produto.quantidadeVendida;
                if (estoque > 0) {
                    const option = document.createElement('option');
                    option.value = produto.id;
                    option.textContent = `${produto.nome} - R$ ${produto.preco.toFixed(2)} (Estoque: ${estoque})`;
                    select.appendChild(option);
                }
            });
        }

        function calcularValorVenda() {
            const produtoId = document.getElementById('produtoVenda').value;
            const quantidade = parseInt(document.getElementById('quantidadeVenda').value) || 0;
            
            if (produtoId && quantidade > 0) {
                const produto = produtos.find(p => p.id == produtoId);
                if (produto) {
                    const valor = produto.preco * quantidade;
                    document.getElementById('valorVenda').value = valor.toFixed(2);
                }
            } else {
                document.getElementById('valorVenda').value = '';
            }
        }

        function atualizarResumoVendas() {
            if (!barracaAtual) return;
            
            const vendasBarraca = vendas.filter(v => v.barracaId === barracaAtual.id);
            const totalArrecadado = vendasBarraca.reduce((sum, v) => sum + v.valorTotal, 0);
            const totalItensVendidos = vendasBarraca.reduce((sum, v) => sum + v.quantidade, 0);
            
            const container = document.getElementById('resumoVendas');
            container.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="text-center p-3 bg-gray-800/50 rounded-xl">
                        <h5 class="text-sm font-semibold text-green-400">Total Arrecadado</h5>
                        <p class="text-lg font-bold text-white">R$ ${totalArrecadado.toFixed(2)}</p>
                    </div>
                    <div class="text-center p-3 bg-gray-800/50 rounded-xl">
                        <h5 class="text-sm font-semibold text-blue-400">Itens Vendidos</h5>
                        <p class="text-lg font-bold text-white">${totalItensVendidos}</p>
                    </div>
                    <div class="text-center p-3 bg-gray-800/50 rounded-xl">
                        <h5 class="text-sm font-semibold text-purple-400">Vendas Realizadas</h5>
                        <p class="text-lg font-bold text-white">${vendasBarraca.length}</p>
                    </div>
                </div>
            `;
        }

        function fecharProdutos() {
            document.getElementById('produtosModal').classList.add('hidden');
            document.getElementById('produtosModal').classList.remove('flex');
            barracaAtual = null;
        }

        // Modal Lucro do Dia
        function abrirModalLucroDia() {
            document.getElementById('modalLucroDia').classList.remove('hidden');
            document.getElementById('selectCursoLucro').value = '';
            document.getElementById('inputLucroDiaContainer').classList.add('hidden');
            document.getElementById('inputLucroDia').value = '';
        }

        function fecharModalLucroDia() {
            document.getElementById('modalLucroDia').classList.add('hidden');
            document.getElementById('selectCursoLucro').value = '';
            document.getElementById('inputLucroDiaContainer').classList.add('hidden');
            document.getElementById('inputLucroDia').value = '';
        }

        document.getElementById('selectCursoLucro').addEventListener('change', function() {
            const curso = this.value;
            const inputContainer = document.getElementById('inputLucroDiaContainer');
            if (!curso) {
                inputContainer.classList.add('hidden');
                document.getElementById('inputLucroDia').value = '';
            } else {
                inputContainer.classList.remove('hidden');
            }
        });

        function gerarRelatorioVendas() {
            let relatorio = 'RELATÓRIO DE VENDAS - EMPREENDEDORISMO\n';
            relatorio += '='.repeat(50) + '\n\n';
            
            barracas.forEach(barraca => {
                const vendasBarraca = vendas.filter(v => v.barracaId === barraca.id);
                const produtosBarraca = produtos.filter(p => p.barracaId === barraca.id);
                const totalArrecadado = vendasBarraca.reduce((sum, v) => sum + v.valorTotal, 0);
                
                relatorio += `BARRACA: ${barraca.nome}\n`;
                relatorio += `Curso: ${barraca.curso}\n`;
                relatorio += `Responsáveis: ${barraca.responsaveis}\n`;
                relatorio += `Total Arrecadado: R$ ${totalArrecadado.toFixed(2)}\n\n`;
                
                relatorio += 'PRODUTOS CADASTRADOS:\n';
                produtosBarraca.forEach(produto => {
                    const totalVendido = produto.quantidadeVendida * produto.preco;
                    relatorio += `  ${produto.nome} - R$ ${produto.preco.toFixed(2)}\n`;
                    relatorio += `    Quantidade inicial: ${produto.quantidadeInicial}\n`;
                    relatorio += `    Quantidade vendida: ${produto.quantidadeVendida}\n`;
                    relatorio += `    Total arrecadado: R$ ${totalVendido.toFixed(2)}\n\n`;
                });
                
                relatorio += 'VENDAS REALIZADAS:\n';
                vendasBarraca.forEach(venda => {
                    relatorio += `  ${new Date(venda.timestamp).toLocaleString('pt-BR')}\n`;
                    relatorio += `  ${venda.nomeProduto} - Qtd: ${venda.quantidade} - Valor: R$ ${venda.valorTotal.toFixed(2)}\n\n`;
                });
                
                relatorio += '-'.repeat(40) + '\n\n';
            });
            
            downloadFile(relatorio, 'relatorio_vendas_empreendedorismo.txt');
        }

        function downloadFile(content, filename) {
            const blob = new Blob([content], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
            URL.revokeObjectURL(url);
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-3 rounded-2xl text-white font-semibold ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            } slide-up`;
            notification.style.fontSize = '13px';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Event listeners para fechar modal
        document.getElementById('produtosModal').addEventListener('click', function(e) {
            if (e.target === this) fecharProdutos();
        });

        document.getElementById('modalLucroDia').addEventListener('click', function(e) {
            if (e.target === this) fecharModalLucroDia();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('produtosModal').classList.contains('hidden')) {
                    fecharProdutos();
                }
                if (!document.getElementById('modalLucroDia').classList.contains('hidden')) {
                    fecharModalLucroDia();
                }
            }
        });
    </script>
</body>
</html>
