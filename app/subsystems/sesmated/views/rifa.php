<?php
require_once('../models/select.model.php');
$select = new select_model();
require_once('../../../main/models/sessions.php');
$session = new sessions;
$session->autenticar_session();

?>

</html>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefa 1 - Venda de Rifas</title>
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

        .input-field:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(255, 183, 51, 0.1);
            background: linear-gradient(145deg, #202020 0%, #1a1a1a 100%);
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--header-color) 0%, var(--accent-color) 100%);
            box-shadow: 0 2px 10px rgba(0, 179, 72, 0.4);
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
        }

        .btn-secondary:hover {
            background: linear-gradient(145deg, #353535 0%, #252525 100%);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .status-complete {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .status-pending {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .icon-button {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .icon-button:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .modal-bg {
            background: linear-gradient(145deg, rgba(25, 25, 25, 0.98) 0%, rgba(15, 15, 15, 0.98) 100%);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .section-divider {
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
            height: 1px;
            margin: 2rem 0;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite alternate;
        }

        @keyframes pulseGlow {
            from {
                box-shadow: 0 4px 20px rgba(0, 179, 72, 0.3);
            }

            to {
                box-shadow: 0 8px 40px rgba(0, 179, 72, 0.5);
            }
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
            color: #181818 !important;
        }

        select.input-field option:checked {
            background-color: #ffb733 !important;
            color: #181818 !important;
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
            font-size: 0.875rem;
            font-weight: 600;
            color: #e5e7eb;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.08);
        }

        /* Card customizado para turma */
        .card-turma {
            border-left: 4px solid var(--header-color);
            transition: box-shadow 0.3s, border-color 0.3s;
        }

        .card-turma:hover {
            border-color: var(--accent-color);
            box-shadow: 0 8px 32px rgba(255, 183, 51, 0.15);
        }

        /* Custom select */
        .custom-select-wrapper {
            font-family: inherit;
            user-select: none;
        }

        .custom-select-selected {
            background: #232323;
            color: #fff;
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid #ffb733;
            cursor: pointer;
        }

        .custom-select-items {
            position: absolute;
            background: #232323;
            border: 2px solid #ffb733;
            border-top: none;
            width: 100%;
            z-index: 99;
            border-radius: 0 0 12px 12px;
            max-height: 250px;
            overflow-y: auto;
        }

        .custom-select-items div {
            padding: 12px 16px;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .custom-select-items div:hover,
        .custom-select-items .same-as-selected {
            background: #ffb733;
            color: #181818;
        }

        .custom-select-hide {
            display: none;
        }
    </style>
</head>

<body class="min-h-screen">

    <!-- Header -->
    <header class="header-bg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-2 sm:py-3 relative">
            <div class="flex flex-col items-start sm:items-center justify-start sm:justify-center gap-2">
                <div class="flex items-start sm:items-center justify-start sm:justify-center gap-2">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-green-500 via-emerald-600 to-green-700 flex items-center justify-center pulse-glow">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-black bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent text-left sm:text-center">
                        TAREFA 1
                    </h1>
                </div>
                <p class="text-gray-400 text-xs font-medium tracking-wider uppercase text-left sm:text-center">VENDA DE RIFAS</p>
            </div>
            <div class="user-chip absolute top-4 right-4">
                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                    <i class="fas fa-user text-green-300 text-xs"></i>
                </div>
                <span class="text-gray-100"><?= $_SESSION['Nome'] ?? 'Nome não encontrado' ?></span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-8">
        <!-- Controls Section -->
        <section class="mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                <!-- Search and Add Controls -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-1 max-w-md">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input
                                type="text"
                                id="searchInput"
                                placeholder="Buscar turma..."
                                class="input-field rounded-2xl pl-12 pr-4 py-3 text-white placeholder-gray-400 focus:outline-none w-full">
                        </div>
                        <button
                            onclick="openAddModal()"
                            class="btn-primary px-6 py-3 rounded-2xl font-semibold text-white flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="fas fa-plus-circle"></i>
                            <span class="hidden sm:inline">Novo Registro</span>
                            <span class="sm:hidden">Adicionar</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <!-- Turmas Grid -->
        <section class="mb-16">
            <div class="flex items-center gap-3 mb-8">
                <i class="fas fa-users text-2xl" style="color: var(--header-color);"></i>
                <h2 class="text-2xl font-bold">Controle por Turma</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6" id="representativesGrid">
                <?php
                $dados = $select->controle_turma();
                foreach ($dados as $dado) {
                ?>
                    <div class="card-bg card-turma rounded-2xl p-6 flex flex-col gap-3 card-hover transition-all duration-300">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fas fa-users text-lg" style="color: var(--header-color);"></i>
                            <span class="font-bold text-lg"><?= htmlspecialchars($dado['nome_turma']) ?></span>
                        </div>
                        <div class="text-sm text-gray-400 mb-1">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            <?= htmlspecialchars($dado['nome_curso']) ?>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-400">Rifas vendidas:</span>
                            <span class="font-bold text-lg style=" color: var(--accent-color);">
                                <?= htmlspecialchars($dado['quantidades_rifas']) ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Valor arrecadado:</span>
                            <span class="font-bold text-lg text-green-400">R$ <?= number_format($dado['valor_arrecadado'], 2, ',', '.') ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Nome do avaliador:</span>
                            <span class="font-bold text-lg text-green-400"> <?= $dado['nome']?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>

        <div class="section-divider"></div>

        <!-- Resumos Section -->
        <section class="text-center space-y-6">
            <div class="flex items-center justify-center gap-3 mb-8">
                <i class="fas fa-chart-line text-2xl" style="color: var(--accent-color);"></i>
                <h2 class="text-2xl font-bold">Relatórios Detalhados</h2>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="./relatorios/rifas/resumo_turma.php" class="btn-secondary px-6 py-3 rounded-2xl font-semibold text-gray-300 flex items-center justify-center gap-2">
                    <i class="fas fa-chart-bar"></i>
                    Resumo por Turma
                </a>
                <a href="./relatorios/rifas/resumo_curso.php" class="btn-secondary px-6 py-3 rounded-2xl font-semibold text-gray-300 flex items-center justify-center gap-2">
                    <i class="fas fa-graduation-cap"></i>
                    Resumo por Curso
                </a>
                <a href="./relatorios/rifas/resumo_total.php" class="btn-secondary px-6 py-3 rounded-2xl font-semibold text-gray-300 flex items-center justify-center gap-2">
                    <i class="fas fa-coins"></i>
                    Total Arrecadado
                </a>
            </div>
        </section>
    </main>

    <!-- Add/Edit Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="modal-bg rounded-3xl p-8 w-full max-w-md mx-4 slide-up max-h-[90vh] overflow-y-auto">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>
                <h2 class="text-2xl font-bold" id="modalTitle">Adicionar Turma</h2>
            </div>

            <form action="../controllers/controller_rifas.php" method="post" class="space-y-6">
                <input type="hidden" name="id_usuario" value="<?= $_SESSION['user_id']?>">
                <div>
                    <label class="block text-sm font-bold mb-4 text-gray-300 uppercase tracking-wide">
                        <i class="fas fa-users mr-2"></i>Turma
                    </label>
                    <select name="turma" id="turma" class="input-field w-full rounded-2xl px-4 py-3 text-white focus:outline-none" required>
                        <option value="" selected disabled>Selecione a turma</option>
                        <?php
                        $dados = $select->select_turma();
                        foreach ($dados as $dado) {
                        ?>
                            <option value="<?= htmlspecialchars($dado['turma_id']) ?>">
                                <?= htmlspecialchars($dado['nome_turma'] . ' ' . $dado['nome_curso']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-4 text-gray-300 uppercase tracking-wide">
                        <i class="fas fa-ticket-alt mr-2"></i>Rifas Vendidas
                    </label>
                    <input
                        type="number"
                        name="rifas"
                        id="rifasInput"
                        min="0"
                        required
                        class="input-field w-full rounded-2xl px-4 py-3 text-white focus:outline-none"
                        placeholder="Quantidade de rifas">
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button
                        type="submit"
                        class="btn-primary flex-1 py-3 rounded-2xl font-semibold text-white flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Salvar
                    </button>
                    <button
                        type="button"
                        onclick="closeModal()"
                        class="btn-secondary flex-1 py-3 rounded-2xl font-semibold text-gray-300 flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Exibir modal de adicionar registro
        function openAddModal() {
            var modal = document.getElementById('modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        // Fechar modal de adicionar registro
        function closeModal() {
            var modal = document.getElementById('modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        // Fechar modal ao clicar fora dele
        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        // Fechar modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('modal').classList.contains('hidden')) closeModal();
            }
        });

        // Funções para abrir/fechar modais de relatório
        function openModalResumoTurma() {
            var modal = document.getElementById('modalResumoTurma');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModalResumoTurma() {
            var modal = document.getElementById('modalResumoTurma');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openModalResumoCurso() {
            var modal = document.getElementById('modalResumoCurso');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModalResumoCurso() {
            var modal = document.getElementById('modalResumoCurso');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openModalTotalArrecadado() {
            var modal = document.getElementById('modalTotalArrecadado');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModalTotalArrecadado() {
            var modal = document.getElementById('modalTotalArrecadado');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Custom select JS
        document.querySelectorAll('.custom-select-wrapper').forEach(function(wrapper) {
            var selected = wrapper.querySelector('.custom-select-selected');
            var items = wrapper.querySelector('.custom-select-items');
            selected.addEventListener('click', function(e) {
                e.stopPropagation();
                closeAllSelect(this);
                items.classList.toggle('custom-select-hide');
                selected.classList.toggle('custom-select-arrow-active');
            });
            items.querySelectorAll('div').forEach(function(optionDiv) {
                optionDiv.addEventListener('click', function(e) {
                    selected.textContent = this.textContent;
                    items.querySelectorAll('div').forEach(function(d) {
                        d.classList.remove('same-as-selected');
                    });
                    this.classList.add('same-as-selected');
                    items.classList.add('custom-select-hide');
                });
            });
        });

        function closeAllSelect(elmnt) {
            document.querySelectorAll('.custom-select-items').forEach(function(items) {
                if (items.previousElementSibling !== elmnt) {
                    items.classList.add('custom-select-hide');
                }
            });
        }
        document.addEventListener('click', closeAllSelect);
    </script>
</body>

</html>