<?php

    session_start();

    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../index.php");
        exit;
    }

    require_once("../models/turmas_model.php");
    require_once("../models/usuario_model.php");

    $modelTurma = new TurmasModel();
    $modelUsuario = new usuario_model();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'gestor') {
        $turmas = $modelTurma->listarTurmas();
    } else {
        $turmas = $modelTurma->listarTurmas($_SESSION['id_usuario']);
    }

    $usuarios = $modelUsuario->listar_usuarios();
    $professores = [];
    
    foreach($usuarios as $u){
        if(isset($u['tipo'])){
            $tipo = strtolower($u['tipo']);
            if ($tipo === 'professor') {
                $professores[] = $u;
            }
        }
    }

?>
<script>window.csrfToken = <?= json_encode($_SESSION['csrf_token'] ?? '') ?>;</script>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Professor Diretor</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <style>
        :root{
            --c1: #005A24;
            --c2: #007A33;
            --c3: #4CAF50;
            --c4: #8BC34A;
            --gold: #FFD700;
            --amber: #FF8C00;
            --peach: #FFB74D;
            --bg: #020E06;
            --surface: #061409;
            --surface2: #091C0D;
            --border: rgba(76,175,80,0.14);
            --text: #F4FFF4;
            --text-soft: rgba(255,255,255,0.72);
            --text-muted: rgba(255,255,255,0.45);
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Inter', sans-serif;
        }

        body{
            background:
                radial-gradient(circle at top left, rgba(76,175,80,0.18), transparent 28%),
                radial-gradient(circle at bottom right, rgba(255,215,0,0.12), transparent 24%),
                var(--bg);
            overflow-x:hidden;
            color: var(--text);
        }

        ::selection{
            background: rgba(76,175,80,0.32);
            color: #fff;
        }

        .topbar-panel{
            background: rgba(2,14,6,0.86);
            backdrop-filter: blur(16px);
            border-color: var(--border);
        }

        .main-shell{
            background: transparent;
        }

        .card-dashboard,
        .card-aluno,
        .bg-white.border.border-gray-200,
        .bg-white,
        .modal-card{
            background: var(--surface2) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
            box-shadow: 0 10px 35px rgba(0,0,0,0.16);
        }

        .card-dashboard,
        .card-aluno{
            transition: box-shadow .25s ease, background-color .18s ease, transform .18s ease;
            border-radius: 22px;
            padding: 24px;
        }

        .card-dashboard:hover,
        .card-aluno:hover{
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(0,0,0,0.24);
        }

        button.bg-green-700,
        button.bg-green-700:hover,
        button.bg-green-800,
        button.bg-green-800:hover,
        .bg-green-700,
        .bg-green-800{
            background: linear-gradient(135deg, var(--c2), var(--c3)) !important;
            color: #fff !important;
            border: none !important;
        }

        button.bg-red-600,
        button.bg-red-700,
        .bg-red-600,
        .bg-red-700{
            background: linear-gradient(135deg, #b71c1c, #e53935) !important;
            color: #fff !important;
            border: none !important;
        }

        button.bg-orange-500,
        button.bg-orange-600,
        .bg-orange-500,
        .bg-orange-600{
            background: linear-gradient(135deg, var(--amber), #FFA726) !important;
            color: #fff !important;
            border: none !important;
        }

        .text-gray-500,
        .text-gray-400,
        .text-gray-600{
            color: var(--text-soft) !important;
        }

        .text-gray-800,
        .text-gray-700{
            color: var(--text) !important;
        }

        input,
        select,
        textarea{
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: rgba(255,255,255,0.04) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }

        select{
            color: var(--text) !important;
            background: rgba(255,255,255,0.04) !important;
        }

        select option{
            color: var(--text) !important;
            background: rgba(2,14,6,0.98) !important;
        }

        select::-ms-expand {
            filter: invert(1);
        }

        .bg-gray-50,
        .bg-gray-100,
        .bg-gray-200,
        .bg-gray-300{
            background: rgba(255,255,255,0.04) !important;
        }

        .border-gray-200{
            border-color: var(--border) !important;
        }

        input::placeholder,
        textarea::placeholder{
            color: rgba(255,255,255,0.35) !important;
        }

        input:focus,
        select:focus,
        textarea:focus{
            border-color: var(--c3) !important;
            box-shadow: 0 0 0 3px rgba(76,175,80,0.14) !important;
        }

        .modal-card{
            animation: fade .22s ease-out both;
        }

        .btn-editar-aluno{
            background: rgba(255,215,0,0.14) !important;
            border-color: rgba(255,215,0,0.24) !important;
            color: var(--gold) !important;
        }

        .btn-editar-aluno:hover{
            background: rgba(255,215,0,0.2) !important;
        }

        .btn-salvar-editar{
            background: linear-gradient(135deg, var(--amber), #ffb74d) !important;
            color: white !important;
            border: none !important;
        }

        .btn-salvar-editar:hover{
            filter: brightness(1.06);
        }

        @keyframes fade {
            from{
                opacity: 0;
                transform: translateY(10px) scale(.98);
            }

            to{
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
    <link rel="stylesheet" href="../assets/css/system-pdt.css?v=16">
    <script src="../assets/js/system-pdt-ui.js" defer></script>
</head>
<body class="pdt-interface">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="pdt-sidebar sidebar-panel border-r fixed h-screen px-6 py-8 flex flex-col justify-between">

        <div>
            <div class="pdt-brand flex items-center gap-3">
                <span class="pdt-brand-mark"><img src="../assets/images/s_simbol.png" alt="" class="pdt-brand-logo"></span>
                <div>
                    <h1 class="font-bold text-[18px]">Sistema <span>Salaberga</span></h1>
                    <p>Professor Diretor de Turma</p>
                </div>
            </div>

            <nav>
                <button class="menu-item active flex items-center gap-3 px-4 py-3 rounded-xl transition effect-bar effect-bar-select">
                    <i class="bi bi-house-door"></i>
                    <span>Início</span>
                </button>
                <button class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl transition effect-bar">
                    <i class="bi bi-journal-bookmark"></i>
                    <span>Turmas</span>
                </button>
                <button class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl transition effect-bar">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Ocorrências</span>
                </button>
                <!-- <button class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-600">
                    <i class="bi bi-bar-chart"></i>
                    <span>Relatórios</span>
                </button> -->
                <!-- <button class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-600">
                    <i class="bi bi-calendar-event"></i>
                    <span>Calendário</span>
                </button> -->
                <!-- <button class="menu-item flex items-center gap-3 px-4 py-3 rounded-xl transition text-gray-600">
                    <i class="bi bi-gear"></i>
                    <span>Configurações</span>
                </button> -->
            </nav>
        </div>

        <form action="../controllers/usuario_controller.php?action=logout" method="POST" class="m-0 p-0">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <button type="submit" class="menu-item option-nav-sair w-full flex items-center gap-3 px-4 py-3 rounded-xl transition">
                <i class="bi bi-box-arrow-right icon-nav-sair"></i>
                <span>Sair</span>
            </button>
        </form>

    </aside>

    <!-- CONTEÚDO -->
    <div class="pdt-main-offset flex-1 lg:ml-[280px]">

        <!-- HEADER -->
        <header class="pdt-topbar h-[85px] topbar-panel border-b flex items-center justify-between px-8">
            <div class="flex items-center gap-4">
                <button id="openMenuBtn" class="lg:hidden text-2xl text-gray-700">
                    <i class="bi bi-list"></i>
                </button>
                <h2 class="font-bold text-gray-800">Sistema PDT</h2>
            </div>

            <div class="flex items-center gap-6 relative">
                <button id="btnNotificacoes" class="relative" type="button" aria-label="Notificações">
                    <i class="bi bi-bell"></i>
                </button>
                <div id="notificacoesPanel" class="hidden absolute right-0 top-full mt-3 w-80 rounded-3xl border border-slate-700 bg-slate-950 p-4 shadow-2xl text-slate-200 z-50">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold">Notificações</span>
                        <button id="fecharNotificacoes" class="text-slate-400 hover:text-white text-sm">Fechar</button>
                    </div>
                    <div class="space-y-3 text-sm text-slate-300">
                        <p>Nenhuma notificação no momento.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-green-700 text-white flex items-center justify-center font-semibold">
                        <?= $_SESSION['nome'][0] ?>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= $_SESSION['nome'] ?></p>
                        <p class="text-xs text-gray-500"><?= ucfirst($_SESSION['tipo']) ?></p>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="pdt-content p-8 main-shell">

            <!-- SEÇÃO INÍCIO -->
            <div class="secs">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        Bem-vindo(a), <?= $_SESSION['nome'] ?>
                    </h1>
                    <p class="text-gray-500">
                        Aqui está um resumo completo da sua turma.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                    <div class="card-dashboard">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-2">Turmas</p>
                                <h2 id="contadorAlunos" class="text-4xl font-bold text-gray-800">0</h2>
                                <span class="text-gray-400 text-sm">Turmas criadas</span>
                            </div>
                            <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-2xl">
                                <i class="bi bi-journal-bookmark-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEÇÃO TURMAS -->
            <div class="secs hidden">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            Gestão de Turmas
                        </h1>
                        <p class="text-gray-500 mt-1">
                            Crie e gerencie as turmas do sistema.
                        </p>
                    </div>
                    <button
                        id="btnAbrirModalTurma"
                        class="bg-green-700 hover:bg-green-800 text-white h-12 px-6 rounded-2xl flex items-center gap-2 font-medium transition"
                    >
                        <i class="bi bi-plus-lg"></i>
                        Criar turma
                    </button>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
                    <div class="relative w-full lg:max-w-md">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input
                            type="text"
                            id="buscarTurma"
                            placeholder="Buscar turma..."
                            class="w-full border border-gray-200 rounded-xl h-12 pl-12 pr-4 outline-none"
                        >
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="font-semibold text-lg text-gray-700">
                        Total de turmas:
                        <span id="contadorTurmas" class="text-green-700">0</span>
                    </h2>
                </div>

                <div id="listaTurmas" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"></div>

                <!-- MODAL CRIAR/EDITAR TURMA -->
                <div id="overlayModalTurma" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[300] p-4 backdrop-blur-[2px]">
                    <div id="modalTurma" class="bg-white w-full max-w-2xl rounded-[32px] p-8 relative shadow-2xl animate-[fade_.2s_ease]">
                        <button id="fecharModalTurma" class="absolute top-5 right-5 w-11 h-11 rounded-full hover:bg-gray-100 transition text-gray-600">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <div class="mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-2xl mb-5">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                                Criar turma
                            </h2>
                            <p class="text-gray-500">
                                Cadastre uma nova turma no sistema.
                            </p>
                        </div>

                        <form id="formCadastrarTurma" class="space-y-4">
                            <input type="hidden" id="idTurmaEdicao" value="">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-200 mb-2">
                                        Turma
                                    </label>
                                    <select
                                        name="id_curso"
                                        id="cursoTurma"
                                        class="w-full h-12 border border-slate-700 rounded-xl px-4 outline-none focus:border-emerald-500 bg-slate-900 text-white transition"
                                    >
                                        <option value="" class="text-white bg-slate-900">Selecione</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-200 mb-2">
                                        Série
                                    </label>
                                    <select
                                        name="serie_turma"
                                        id="serieTurma"
                                        class="w-full h-12 border border-slate-700 rounded-xl px-4 outline-none focus:border-emerald-500 bg-slate-900 text-white transition"
                                    >
                                        <option value="" class="text-white bg-slate-900">Selecione</option>
                                        <option value="1" class="text-white bg-slate-900">1º Ano</option>
                                        <option value="2" class="text-white bg-slate-900">2º Ano</option>
                                        <option value="3" class="text-white bg-slate-900">3º Ano</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-200 mb-2">
                                        Sala
                                    </label>
                                    <input
                                        type="text"
                                        name="sala_turma"
                                        id="salaTurma"
                                        placeholder="Ex: 04"
                                        class="w-full h-12 border border-slate-700 rounded-xl px-4 outline-none focus:border-emerald-500 bg-slate-900 text-white transition"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-200 mb-2">
                                        Ano letivo
                                    </label>
                                    <div class="w-full h-12 border border-slate-700 rounded-xl px-4 flex items-center bg-[#020e05] text-white font-semibold">
                                        2026
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-200 mb-2">
                                    Professor responsável
                                </label>
                                <select
                                    name="id_professor"
                                    id="selectProfessor"
                                    class="w-full h-12 border border-slate-700 rounded-xl px-4 outline-none focus:border-emerald-500 bg-slate-900 text-white transition"
                                >
                                    <option value="" class="text-white bg-slate-900">Selecione um professor</option>
                                    <?php foreach($professores as $prof): ?>
                                        <option value="<?= $prof['id'] ?>" class="text-white bg-slate-900"><?= htmlspecialchars($prof['nome']) ?> (<?= htmlspecialchars($prof['matricula']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-200 mb-2">
                                    Observações
                                </label>
                                <textarea
                                    name="descricao_turma"
                                    id="descricaoTurma"
                                    rows="4"
                                    placeholder="Informações adicionais sobre a turma..."
                                    class="w-full border border-slate-700 rounded-2xl p-4 outline-none resize-none focus:border-emerald-500 bg-slate-900 text-white transition"
                                ></textarea>
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-4">
                                <button
                                    type="button"
                                    id="cancelarModalTurma"
                                    class="h-12 px-6 rounded-xl border border-gray-200 hover:bg-gray-100 transition font-medium"
                                >
                                    Cancelar
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-5 py-2.5 bg-green-700 hover:bg-green-800 transition text-white font-medium rounded-xl text-sm shadow-sm"
                                >
                                    Salvar Turma
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MODAL CURSO C -->
                <div id="overlayModalSubcursoC" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[400] p-4 backdrop-blur-[2px]">
                    <div class="bg-white w-full max-w-md rounded-[32px] p-8 relative shadow-2xl animate-[fade_.2s_ease]">
                        <button id="fecharModalSubcursoC" class="absolute top-5 right-5 w-11 h-11 rounded-full hover:bg-gray-100 transition text-gray-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Selecione o tipo de curso</h2>
                            <p class="text-gray-500">Curso C possui duas modalidades</p>
                        </div>
                        <div class="flex gap-4">
                            <button
                                type="button"
                                id="btnAdministracao"
                                class="flex-1 py-3 px-4 bg-green-100 hover:bg-green-200 text-green-700 font-semibold rounded-xl transition"
                            >
                                Administração
                            </button>
                            <button
                                type="button"
                                id="btnMeioAmbiente"
                                class="flex-1 py-3 px-4 bg-green-100 hover:bg-green-200 text-green-700 font-semibold rounded-xl transition"
                            >
                                Meio Ambiente
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MODAL DESVINCULAÇÃO -->
                <div id="overlayModalDesvinculaTurma" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[400] p-4 backdrop-blur-[2px]">
                    <div class="bg-white w-full max-w-md rounded-[32px] p-8 relative shadow-2xl animate-[fade_.2s_ease]">
                        <button id="fecharModalDesvincula" class="absolute top-5 right-5 w-11 h-11 rounded-full hover:bg-gray-100 transition text-gray-600">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-2xl mx-auto mb-5">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Desvincular turma</h2>
                            <p class="text-gray-500 text-sm">Esta ação não pode ser desfeita</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 mb-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Turma</span>
                                <span id="nomeTurmaDesvincula" class="font-semibold text-gray-800">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Professor</span>
                                <span id="professorDesvincula" class="font-semibold text-gray-800">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-sm">Série</span>
                                <span id="serieDesvincula" class="font-semibold text-gray-800">-</span>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                id="cancelarModalDesvincula"
                                class="flex-1 py-3 px-4 border border-gray-200 hover:bg-gray-100 text-gray-700 font-semibold rounded-xl transition"
                            >
                                Cancelar
                            </button>
                            <button
                                type="button"
                                id="confirmarDesvincula"
                                class="flex-1 py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition"
                            >
                                Desvincular
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SEÇÕES VAZIAS -->
            <div class="secs hidden"></div>
            <div class="secs hidden"></div>
            <div class="secs hidden"></div>
            <div class="secs hidden"></div>

        </main>

    </div>

</div>

<script>
const originalFetch = window.fetch.bind(window);
window.fetch = function(url, options = {}) {
    const method = (options?.method || 'GET').toUpperCase();
    const headers = new Headers(options?.headers || {});
    if (window.csrfToken && !headers.has('X-CSRF-Token')) {
        headers.set('X-CSRF-Token', window.csrfToken);
    }
    if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method) && options?.body && typeof options.body === 'string' && !options.body.includes('csrf_token')) {
        try {
            const parsed = JSON.parse(options.body);
            if (parsed && typeof parsed === 'object' && !('csrf_token' in parsed)) {
                parsed.csrf_token = window.csrfToken;
                options = { ...options, body: JSON.stringify(parsed) };
            }
        } catch (e) {}
    }
    return originalFetch(url, { ...options, headers });
};

async function carregarNotificacoes() {
    const lista = document.getElementById('listaNotificacoes');
    const contador = document.getElementById('contadorNotificacoes');
    if (!lista) return;
    try {
        const res = await fetch('../controllers/notificacao_controller.php?action=listar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ csrf_token: window.csrfToken })
        });
        const data = await res.json();
        if (!data?.sucesso) return;
        const itens = data.dados || [];
        if (contador) {
            const total = data.nao_lidas || 0;
            contador.textContent = total > 0 ? total : '';
            contador.classList.toggle('hidden', total <= 0);
        }
        lista.innerHTML = itens.length ? itens.map((item) => `
            <div class="rounded-2xl border ${item.lida ? 'border-slate-800 bg-slate-900/70' : 'border-emerald-500/30 bg-emerald-500/10'} p-3">
                <p class="font-semibold text-white">${item.titulo}</p>
                <p class="mt-1 text-slate-300">${item.mensagem}</p>
                <p class="mt-2 text-[11px] text-slate-500">${item.criado_em || ''}</p>
            </div>
        `).join('') : '<div class="text-slate-400">Nenhuma notificação</div>';
    } catch (e) {
        console.error('Erro ao carregar notificações:', e);
    }
}

document.addEventListener("DOMContentLoaded", function() {

    // ================================
    // MENU E NAVEGAÇÃO
    // ================================
    const btnOptionsPdt = document.querySelectorAll(".menu-item");
    const secsPdt = document.querySelectorAll(".secs");
    
    btnOptionsPdt.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            btnOptionsPdt.forEach(item => item.classList.remove("active"));
            btn.classList.add("active");
            secsPdt.forEach(sec => sec.classList.add("hidden"));
            if (secsPdt[index]) secsPdt[index].classList.remove("hidden");
        });
    });

    secsPdt.forEach((sec, index) => {
        if(index !== 0) sec.classList.add("hidden");
    });

    // ================================
    // VARIÁVEIS GLOBAIS
    // ================================
    window.cursos = [];
    window.turmas = [];
    let turmaParaDesvinculnarId = null;
    let cursosCAtivos = [];
    
    // Referências para facilitar
    let cursos = window.cursos;
    let turmas = window.turmas;

    // ================================
    // ELEMENTOS DO DOM
    // ================================
    const btnAbrirModalTurma = document.getElementById("btnAbrirModalTurma");
    const overlayModalTurma = document.getElementById("overlayModalTurma");
    const fecharModalTurma = document.getElementById("fecharModalTurma");
    const cancelarModalTurma = document.getElementById("cancelarModalTurma");
    const btnNotificacoes = document.getElementById("btnNotificacoes");
    const notificacoesPanel = document.getElementById("notificacoesPanel");
    const fecharNotificacoes = document.getElementById("fecharNotificacoes");
    const formCadastrarTurma = document.getElementById("formCadastrarTurma");
    const cursoTurmaSelect = document.getElementById("cursoTurma");
    const salaTurmaInput = document.getElementById("salaTurma");
    const idTurmaEdicao = document.getElementById("idTurmaEdicao");
    const overlayModalSubcursoC = document.getElementById("overlayModalSubcursoC");
    const fecharModalSubcursoC = document.getElementById("fecharModalSubcursoC");
    const btnAdministracao = document.getElementById("btnAdministracao");
    const btnMeioAmbiente = document.getElementById("btnMeioAmbiente");
    const overlayModalDesvincula = document.getElementById("overlayModalDesvinculaTurma");
    const fecharModalDesvincula = document.getElementById("fecharModalDesvincula");
    const cancelarModalDesvincula = document.getElementById("cancelarModalDesvincula");
    const confirmarDesvincula = document.getElementById("confirmarDesvincula");
    const buscarTurma = document.getElementById("buscarTurma");

    // ================================
    // MODAL TURMA - ABRIR/FECHAR
    // ================================
    function abrirModalTurma(turmaId = null) {
        if (!overlayModalTurma) return;
        overlayModalTurma.classList.remove("hidden");
        overlayModalTurma.classList.add("flex");
        
        if (turmaId) {
            const turma = turmas.find(t => t.id_turma === turmaId);
            if (turma) {
                document.getElementById("serieTurma").value = turma.serie_turma || "";
                document.getElementById("salaTurma").value = turma.sala_turma || "";
                document.getElementById("selectProfessor").value = turma.id_professor || "";
                document.getElementById("descricaoTurma").value = turma.descricao_turma || "";
                cursoTurmaSelect.value = turma.id_curso || "";
                idTurmaEdicao.value = turmaId;
            }
        } else {
            if (formCadastrarTurma) formCadastrarTurma.reset();
            idTurmaEdicao.value = "";
        }
    }
    
    function fecharModalTurmaFunc() {
        if (!overlayModalTurma) return;
        overlayModalTurma.classList.add("hidden");
        overlayModalTurma.classList.remove("flex");
        if (formCadastrarTurma) formCadastrarTurma.reset();
        idTurmaEdicao.value = "";
    }
    
    if (btnAbrirModalTurma) {
        btnAbrirModalTurma.addEventListener("click", (e) => {
            e.preventDefault();
            abrirModalTurma();
        });
    }
    
    if (fecharModalTurma) fecharModalTurma.addEventListener("click", fecharModalTurmaFunc);
    if (cancelarModalTurma) cancelarModalTurma.addEventListener("click", fecharModalTurmaFunc);
    
    if (overlayModalTurma) {
        overlayModalTurma.addEventListener("click", (e) => {
            if (e.target === overlayModalTurma) fecharModalTurmaFunc();
        });
    }

    if (btnNotificacoes && notificacoesPanel) {
        btnNotificacoes.addEventListener("click", (e) => {
            e.stopPropagation();
            notificacoesPanel.classList.toggle("hidden");
            if (!notificacoesPanel.classList.contains('hidden')) {
                carregarNotificacoes();
            }
        });
    }

    if (fecharNotificacoes) {
        fecharNotificacoes.addEventListener("click", () => {
            if (notificacoesPanel) notificacoesPanel.classList.add("hidden");
        });
    }

    document.addEventListener("click", (e) => {
        if (notificacoesPanel && !notificacoesPanel.contains(e.target) && !(btnNotificacoes && btnNotificacoes.contains(e.target))) {
            notificacoesPanel.classList.add("hidden");
        }
    });

    // Validação sala - apenas números
    if (salaTurmaInput) {
        salaTurmaInput.addEventListener("input", (e) => {
            e.target.value = e.target.value.replace(/[^\d]/g, "");
        });
    }

    // ================================
    // CARREGAR CURSOS
    // ================================
    async function carregarCursos() {
        try {
            const response = await fetch("../controllers/turma_controller.php?action=listar_cursos", {
                method: "GET"
            });
            const data = await response.json();
            
            if (!data.sucesso) {
                console.error("Erro ao carregar cursos:", data.erro);
                return;
            }
            
            window.cursos = data.dados;
            cursos = window.cursos;
            popularSelectCursos();
        } catch (error) {
            console.error("Erro ao carregar cursos:", error);
        }
    }

    // ================================
    // POPULAR SELECT DE CURSOS
    // ================================
    function popularSelectCursos() {
        if (!cursoTurmaSelect) return;
        
        cursoTurmaSelect.innerHTML = '<option value="">Selecione</option>';
        
        const letrasAdicionadas = new Set();
        cursos.forEach(curso => {
            if (curso.letra === 'C') {
                // Para Curso C, mostrar com o nome específico (Administração ou Meio Ambiente)
                const opt = document.createElement('option');
                opt.value = curso.id_curso;
                opt.text = `${curso.letra} - ${curso.nome_curso}`;
                cursoTurmaSelect.appendChild(opt);
            } else {
                // Para outros cursos, mostrar normalmente
                const opt = document.createElement('option');
                opt.value = curso.id_curso;
                opt.text = `${curso.letra} - ${curso.nome_curso}`;
                cursoTurmaSelect.appendChild(opt);
            }
        });
    }

    // ================================
    // MODAL SUBCURSO C
    // ================================
    function abrirModalSubcursoC() {
        if (overlayModalSubcursoC) {
            overlayModalSubcursoC.classList.remove("hidden");
            overlayModalSubcursoC.classList.add("flex");
        }
    }

    function fecharModalSubcursoCFunc() {
        if (overlayModalSubcursoC) {
            overlayModalSubcursoC.classList.add("hidden");
            overlayModalSubcursoC.classList.remove("flex");
        }
    }

    if (fecharModalSubcursoC) {
        fecharModalSubcursoC.addEventListener("click", fecharModalSubcursoCFunc);
    }

    if (overlayModalSubcursoC) {
        overlayModalSubcursoC.addEventListener("click", (e) => {
            if (e.target === overlayModalSubcursoC) fecharModalSubcursoCFunc();
        });
    }

    // Seleção de subcurso C
    if (cursoTurmaSelect) {
        cursoTurmaSelect.addEventListener("change", (e) => {
            // Não abrir modal, apenas permitir seleção direta
            // Se for futuramente necessário abrir modal, descomentar:
            // if (e.target.value === 'C') {
            //     abrirModalSubcursoC();
            // }
        });
    }

    if (btnAdministracao) {
        btnAdministracao.addEventListener("click", () => {
            console.log('Botão Administração clicado');
            console.log('Cursos disponíveis:', cursos);
            
            // Buscar curso C de Administração
            const adminCurso = cursos.find(c => {
                const nome = c.nome_curso.toLowerCase();
                const match = c.letra === 'C' && (nome.includes('administração') || nome.includes('administracao') || nome.includes('admin'));
                console.log(`Verificando: ${c.nome_curso} (${c.letra}) - Match: ${match}, Nome lower: ${nome}`);
                return match;
            });
            
            console.log('Curso encontrado:', adminCurso);
            
            if (adminCurso) {
                console.log('Atribuindo valor:', adminCurso.id_curso);
                cursoTurmaSelect.value = adminCurso.id_curso;
                // Disparar change para atualizar o campo
                cursoTurmaSelect.dispatchEvent(new Event('change'));
                console.log('Valor do select após atribuição:', cursoTurmaSelect.value);
                fecharModalSubcursoCFunc();
            } else {
                console.warn('Curso Administração não encontrado', cursos);
            }
        });
    }

    if (btnMeioAmbiente) {
        btnMeioAmbiente.addEventListener("click", () => {
            console.log('Botão Meio Ambiente clicado');
            console.log('Cursos disponíveis:', cursos);
            
            // Buscar curso C de Meio Ambiente
            const meioAmbienteCurso = cursos.find(c => {
                const nome = c.nome_curso.toLowerCase();
                const match = c.letra === 'C' && (nome.includes('meio ambiente') || nome.includes('meio') || nome.includes('ambiente'));
                console.log(`Verificando: ${c.nome_curso} (${c.letra}) - Match: ${match}, Nome lower: ${nome}`);
                return match;
            });
            
            console.log('Curso encontrado:', meioAmbienteCurso);
            
            if (meioAmbienteCurso) {
                console.log('Atribuindo valor:', meioAmbienteCurso.id_curso);
                cursoTurmaSelect.value = meioAmbienteCurso.id_curso;
                // Disparar change para atualizar o campo
                cursoTurmaSelect.dispatchEvent(new Event('change'));
                console.log('Valor do select após atribuição:', cursoTurmaSelect.value);
                fecharModalSubcursoCFunc();
            } else {
                console.warn('Curso Meio Ambiente não encontrado', cursos);
            }
        });
    }

    // ================================
    // FORM TURMA SUBMIT
    // ================================
    if (formCadastrarTurma) {
        formCadastrarTurma.addEventListener("submit", async (e) => {
            e.preventDefault();

            const idCurso = cursoTurmaSelect.value;
            const serie = document.getElementById("serieTurma").value;
            const sala = document.getElementById("salaTurma").value;
            const idProfessor = document.getElementById("selectProfessor").value;
            const descricao = document.getElementById("descricaoTurma").value;
            const turmaIdEdicao = idTurmaEdicao.value;

            if (!idCurso || !serie || !sala || !idProfessor) {
                alert("Preencha todos os campos obrigatórios!");
                return;
            }

            if (!/^\d+$/.test(sala)) {
                alert("O campo sala deve conter apenas números!");
                return;
            }

            try {
                const action = turmaIdEdicao ? "atualizar_turma" : "cadastrar_turma";
                const payload = {
                    id_curso: parseInt(idCurso),
                    serie_turma: parseInt(serie),
                    sala_turma: sala,
                    ano_letivo: 2026,
                    descricao_turma: descricao,
                    id_professor: parseInt(idProfessor)
                };

                if (turmaIdEdicao) {
                    payload.id_turma = parseInt(turmaIdEdicao);
                }

                const response = await fetch(`../controllers/turma_controller.php?action=${action}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.sucesso) {
                    alert(data.mensagem || "Turma salva com sucesso!");
                    fecharModalTurmaFunc();
                    await carregarTurmas();
                } else {
                    alert("Erro: " + (data.erro || "Algo deu errado"));
                }
            } catch (error) {
                console.error("Erro ao salvar turma:", error);
                alert("Erro ao salvar turma!");
            }
        });
    }

    // ================================
    // CARREGAR TURMAS
    // ================================
    async function carregarTurmas() {
        try {
            const response = await fetch("../controllers/turma_controller.php?action=listar_turmas", {
                method: "POST"
            });

            const data = await response.json();

            if (!data.sucesso) {
                console.error(data.erro);
                return;
            }

            window.turmas = data.dados;
            turmas = window.turmas;
            renderizarTurmas();
        } catch (error) {
            console.error("Erro ao carregar turmas:", error);
        }
    }

    // ================================
    // RENDERIZAR TURMAS
    // ================================
    function renderizarTurmas() {
        const listaTurmas = document.getElementById("listaTurmas");
        const contadorTurmas = document.getElementById("contadorTurmas");
        const contadorTurmasDashboard = document.getElementById("contadorAlunos");

        if (!listaTurmas) return;
        
        listaTurmas.innerHTML = "";

        if (turmas.length === 0) {
            listaTurmas.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-500">Nenhuma turma cadastrada ainda.</p></div>';
        } else {
            turmas.forEach(turma => {
                const card = document.createElement("div");
                card.className = "bg-white border border-gray-200 rounded-xl p-6 hover:shadow-xl transition duration-300 h-full w-full flex flex-col justify-between";
                card.innerHTML = `
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="w-16 h-16 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-2xl mb-4">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">
                                ${turma.serie_turma}º ${turma.letra}
                            </h2>
                            <p class="text-gray-500 text-sm">${turma.nome_curso}</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-7">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Sala</span>
                            <span class="font-semibold text-gray-700">${turma.sala_turma}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Ano letivo</span>
                            <span class="font-semibold text-gray-700">${turma.ano_letivo}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Professor</span>
                            <span class="font-semibold text-gray-700 text-right">${turma.professor_nome}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button class="flex-1 h-11 rounded-xl border border-gray-200 hover:bg-gray-100 transition font-medium btn-editar-turma" data-turma-id="${turma.id_turma}">
                            Editar
                        </button>
                        <button class="flex-1 h-11 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 transition font-medium btn-desvincula-turma" data-turma-id="${turma.id_turma}">
                            Desvincular
                        </button>
                    </div>
                `;
                listaTurmas.appendChild(card);
            });

            document.querySelectorAll(".btn-editar-turma").forEach(btn => {
                btn.addEventListener("click", (e) => {
                    const idTurma = parseInt(e.target.dataset.turmaId);
                    abrirModalTurma(idTurma);
                });
            });

            document.querySelectorAll(".btn-desvincula-turma").forEach(btn => {
                btn.addEventListener("click", (e) => {
                    const idTurma = parseInt(e.target.dataset.turmaId);
                    const turma = turmas.find(t => t.id_turma === idTurma);
                    if (turma) abrirModalDesvinculacao(turma);
                });
            });
        }

        if (contadorTurmas) contadorTurmas.textContent = turmas.length;
        if (contadorTurmasDashboard) contadorTurmasDashboard.textContent = turmas.length;
    }

    // ================================
    // MODAL DESVINCULAÇÃO
    // ================================
    function abrirModalDesvinculacao(turma) {
        document.getElementById("nomeTurmaDesvincula").textContent = `${turma.letra} - ${turma.nome_curso}`;
        document.getElementById("professorDesvincula").textContent = turma.professor_nome;
        document.getElementById("serieDesvincula").textContent = `${turma.serie_turma}º Ano`;
        turmaParaDesvinculnarId = turma.id_turma;
        
        if (overlayModalDesvincula) {
            overlayModalDesvincula.classList.remove("hidden");
            overlayModalDesvincula.classList.add("flex");
        }
    }

    function fecharModalDesvinculacaoFunc() {
        if (overlayModalDesvincula) {
            overlayModalDesvincula.classList.add("hidden");
            overlayModalDesvincula.classList.remove("flex");
        }
        turmaParaDesvinculnarId = null;
    }

    if (fecharModalDesvincula) fecharModalDesvincula.addEventListener("click", fecharModalDesvinculacaoFunc);
    if (cancelarModalDesvincula) cancelarModalDesvincula.addEventListener("click", fecharModalDesvinculacaoFunc);

    if (overlayModalDesvincula) {
        overlayModalDesvincula.addEventListener("click", (e) => {
            if (e.target === overlayModalDesvincula) fecharModalDesvinculacaoFunc();
        });
    }

    // Confirmar desvinculação
    if (confirmarDesvincula) {
        confirmarDesvincula.addEventListener("click", async () => {
            if (!turmaParaDesvinculnarId) return;

            try {
                const response = await fetch("../controllers/turma_controller.php?action=deletar_turma", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id_turma: turmaParaDesvinculnarId
                    })
                });

                const data = await response.json();

                if (data.sucesso) {
                    alert(data.mensagem || "Turma desvinculada com sucesso!");
                    fecharModalDesvinculacaoFunc();
                    await carregarTurmas();
                } else {
                    alert("Erro: " + (data.erro || "Algo deu errado"));
                }
            } catch (error) {
                console.error("Erro ao desvincular turma:", error);
                alert("Erro ao desvincular turma!");
            }
        });
    }

    // ================================
    // BUSCAR TURMA
    // ================================
    if (buscarTurma) {
        buscarTurma.addEventListener("input", () => {
            const valor = buscarTurma.value.toLowerCase();
            const cards = document.querySelectorAll("#listaTurmas > div");
            cards.forEach(card => {
                const texto = card.innerText.toLowerCase();
                if (texto.includes(valor)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }

    // ================================
    // INICIALIZAR
    // ================================
    carregarCursos();
    carregarTurmas();
});
</script>

</body>
</html>
