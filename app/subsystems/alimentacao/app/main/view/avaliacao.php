<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação das Refeições - EEEP STGM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="shortcut icon" href="img/Design-logo.svg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1f5632',
                        'secondary': '#f46815',
                        'neutral': '#f5f5f5',
                        'accent': '#333333',
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary-color: #1f5632;
            --secondary-color: #f46815;
            --bg-color: #f5f5f5;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --dark-green: #0f2a1d;
            --medium-dark-green: #1a3c2e;
            font-size: 16px;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s, color 0.3s;
            font-size: 1rem;
        }
        body.dark {
            --bg-color: #1a1a1a;
            --accent: #d1d5db;
            background: var(--bg-color);
            color: var(--accent);
        }
        .navbar-gradient {
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
        }
        .header {
            color: white;
            width: 100%;
            box-shadow: 0 2px 4px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .main-content {
            margin-top: 1rem;
            padding: 2rem 1rem;
            width: 100%;
            max-width: 600px;
            flex: 1;
            margin-left: auto;
            margin-right: auto;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 8px var(--shadow-color);
            margin-bottom: 1rem;
        }
        body.dark .card {
            background-color: #2d2d2d;
            color: #d1d5db;
        }
        .message {
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
        }
        .message.success {
            background: #E8F5E9;
            color: #66BB6A;
        }
        .message.error {
            background: #FFEBEE;
            color: #EF5350;
        }
        .section-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 1rem;
        }
        .options-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .option {
            padding: 0.75rem 1.25rem;
            background:rgba(224, 224, 224, 0);
            border: 2px solid #66BB6A;
            border-radius: 12px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s ease;
        }
        .option:hover:not(.disabled) {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
        }
        .option.active, .option.selected {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
            font-weight: 600;
        }
        .option.disabled {
            background: #F0F0F0;
            border-color: #B0BEC5;
            color: #B0BEC5;
            cursor: not-allowed;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        .action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-submit {
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            color: white;
        }
        .btn-submit:hover {
            background: linear-gradient(45deg, #1a3c2e, #2a6d43);
        }
        .btn-cancel {
            background: #EF5350;
            color: white;
        }
        .btn-cancel:hover {
            background: #FF8A80;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            text-align: center;
        }
        body.dark .modal-content {
            background-color: #2d2d2d;
            color: #d1d5db;
        }
        .modal-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        body.dark .modal-icon {
            color: #FF6B6B; /* Darker red for better contrast in dark mode */
        }
        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }
        body.dark .modal-title {
            color: #d1d5db;
        }
        .modal-message {
            font-size: 1rem;
            color: var(--accent);
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        body.dark .modal-message {
            color: #d1d5db;
        }
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .close-modal {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            font-size: 1.2rem;
            color: var(--accent);
            cursor: pointer;
            transition: color 0.3s ease;
        }
        body.dark .close-modal {
            color: #d1d5db;
        }
        .close-modal:hover {
            color: #EF5350;
        }
        .modal-textarea {
            width: 100%;
            min-height: 100px;
            padding: 0.75rem;
            border: 1px solid #B0BEC5;
            border-radius: 8px;
            resize: vertical;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }
        body.dark .modal-textarea {
            background-color: #333;
            color: #d1d5db;
            border-color: #555;
        }
        .accessibility-controls {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            .card {
                padding: 1.5rem;
            }
            .options-flex {
                flex-direction: column;
                gap: 0.5rem;
            }
            .option {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
            .action-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .modal-content {
                width: 95%;
                padding: 1rem;
            }
            .modal-icon {
                font-size: 1.5rem;
            }
            .modal-title {
                font-size: 1.2rem;
            }
            .modal-message {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body class="font-sans bg-neutral" x-data="{ mobileMenuOpen: false }">
    <header class="navbar-gradient text-white sticky top-0 z-50 shadow-md">
        <div class="container mx-auto px-4">
            <nav class="flex items-center justify-between flex-wrap py-4">
                <div class="accessibility-controls flex items-center space-x-2">
                    <span class="text-sm"><b>Acessibilidade</b></span>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Diminuir tamanho do texto" onclick="changeTextSize(-2)"><i class="fa-solid fa-a"></i><b>-</b></button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Tamanho padrão do texto" onclick="changeTextSize(0)"><i class="fa-solid fa-a"></i></button>
                    <button class="text-sm hover:text-secondary transition duration-300 px-1" aria-label="Aumentar tamanho do texto" onclick="changeTextSize(2)"><i class="fa-solid fa-a"></i><b>+</b></button>
                    <button class="hover:text-secondary transition duration-300 px-1" aria-label="Alternar modo escuro" @click="toggleDarkMode()">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                </div>
                <div class="flex items-center">
                    <div class="hidden lg:flex space-x-4">
                        <a href="sistemaAluno.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-house mr-1"></i> Início
                        </a>
                        <a href="cardapio.php" class="text-white hover:text-secondary transition duration-300">
                            <i class="fa-solid fa-utensils mr-1"></i> Cardápio
                        </a>
                        <a href="#" class="text-white hover:text-secondary transition duration-300" onclick="openSugestaoModal()">
                            <i class="fa-solid fa-comment-dots mr-1"></i> Críticas ou Sugestões
                        </a>
                    </div>
                    <div class="block lg:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="flex items-center px-3 py-2 border rounded text-secondary border-secondary hover:text-white hover:border-white transition duration-300">
                            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <title>Menu</title>
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>
            <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="lg:hidden">
                <div class="py-2 space-y-2">
                    <a href="sistemaAluno.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-house mr-1"></i> Início
                    </a>
                    <a href="cardapio.php" class="block text-white hover:text-secondary transition duration-300">
                        <i class="fa-solid fa-utensils mr-1"></i> Cardápio
                    </a>
                    <a href="#" class="block text-white hover:text-secondary transition duration-300" onclick="openSugestaoModal()">
                        <i class="fa-solid fa-comment-dots mr-1"></i> Críticas ou Sugestões
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="card">
            <?php
            $error_message = isset($_GET['error']) ? urldecode($_GET['error']) : '';
            $success_message = isset($_GET['success']) ? urldecode($_GET['success']) : '';

            if (!empty($error_message)): ?>
                <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>

            <form id="avaliacaoForm" action="../control/controlOpiniao.php" method="POST">
                <div class="section-label">Selecione a Refeição</div>
                <div class="options-flex">
                    <div class="option" data-meal="lanche-manha" onclick="selectMeal(this, 'lanche-manha')">Lanche da manhã</div>
                    <div class="option" data-meal="almoco" onclick="selectMeal(this, 'almoco')">Almoço</div>
                    <div class="option" data-meal="lanche-tarde" onclick="selectMeal(this, 'lanche-tarde')">Lanche da tarde</div>
                    <input type="hidden" name="refeicao" id="refeicao">
                    <input type="hidden" name="data" value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="section-label">Nível de Satisfação</div>
                <div class="options-flex">
                    <label class="option" onclick="selectOption(this, 'horrivel')">
                        <input type="radio" name="satisfacao" value="horrivel" class="hidden">
                        Horrível
                    </label>
                    <label class="option" onclick="selectOption(this, 'ruim')">
                        <input type="radio" name="satisfacao" value="ruim" class="hidden">
                        Ruim
                    </label>
                    <label class="option" onclick="selectOption(this, 'regular')">
                        <input type="radio" name="satisfacao" value="regular" class="hidden">
                        Regular
                    </label>
                    <label class="option" onclick="selectOption(this, 'bom')">
                        <input type="radio" name="satisfacao" value="bom" class="hidden">
                        Bom
                    </label>
                    <label class="option" onclick="selectOption(this, 'otimo')">
                        <input type="radio" name="satisfacao" value="otimo" class="hidden">
                        Ótimo
                    </label>
                </div>

                <div class="action-buttons">
                    <button type="button" class="action-btn btn-submit" onclick="openConfirmModal()">Enviar</button>
                    <button type="button" class="action-btn btn-cancel" onclick="window.location.href='sistemaAluno.php'">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('confirmModal')">×</span>
            <h2 class="modal-title">Confirmar Avaliação</h2>
            <div class="modal-info">
                <p><span>Refeição:</span> <span id="modalRefeicao"></span></p>
                <p><span>Satisfação:</span> <span id="modalSatisfacao"></span></p>
            </div>
            <div id="modalError" class="message error" style="display: none;"></div>
            <div class="modal-buttons">
                <button class="action-btn btn-submit" onclick="submitForm()">Confirmar</button>
                <button class="action-btn btn-cancel" onclick="closeModal('confirmModal')">Cancelar</button>
            </div>
        </div>
    </div>

    <div id="warningModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('warningModal')">×</span>
            <i class="fa-solid fa-exclamation-triangle modal-icon" style="color: #EF5350;"></i>
            <h2 class="modal-title">Atenção</h2>
            <p class="modal-message">Por favor, selecione uma refeição e uma satisfação antes de enviar.</p>
            <div class="modal-buttons">
                <button class="action-btn btn-submit" onclick="closeModal('warningModal')">OK</button>
            </div>
        </div>
    </div>

    <div id="sugestaoModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal('sugestaoModal')">×</span>
            <h2 class="modal-title">Críticas ou Sugestões</h2>
            <form id="sugestaoForm" action="../control/controlOpiniao.php" method="POST">
                <textarea name="sugestao" id="sugestaoTextarea" class="modal-textarea" placeholder="Digite sua crítica ou sugestão aqui..." required></textarea>
                <div class="modal-buttons">
                    <button type="submit" class="action-btn btn-submit">Enviar</button>
                    <button type="button" class="action-btn btn-cancel" onclick="closeModal('sugestaoModal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-black text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold">EEEP STGM</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            EEEP Salaberga Torquato Gomes de Matos - Educação profissional de qualidade.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold">Endereço</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Avenida Marta Maria Carvalho Nojoza, Outra Banda<br>
                            Maranguape - CE<br>
                            CEP: 61942-355
                        </p>
                    </div>
                </div>
                <div class="md:col-span-4 md:col-start-5 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Contatos</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.instagram.com/eeepsalabergamtg/" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-brands fa-instagram mr-2"></i>Instagram</a></li>
                        <li><a href="tel:+558533414000" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-phone mr-2"></i>(85) 3341-4000</a></li>
                        <li><a href="mailto:eeepsalab@gmail.com?subject=Contato%20EEEP%20STGM" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&to=eeepsalab@gmail.com', '_blank'); return true;" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-envelope mr-2"></i>Email</a></li>
                    </ul>
                </div>
                <div class="md:col-span-4 md:col-start-10 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Desenvolvedores</h3>
                    <p class="text-sm text-gray-400">Christian Santos</p>
                    <p class="text-sm text-gray-400">José Arimatéia</p>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-sm text-gray-400">© 2025. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        // Initialize VLibras
        document.addEventListener('DOMContentLoaded', () => {
            try {
                new window.VLibras.Widget('https://vlibras.gov.br/app');
            } catch (e) {
                console.error('Erro ao inicializar VLibras:', e);
            }

            // Fetch evaluated meals
            fetchEvaluatedMeals();
        });

        // Text size adjustment
        function changeTextSize(step) {
            const root = document.documentElement;
            let currentSize = parseFloat(getComputedStyle(root).fontSize);
            if (step === 0) {
                root.style.fontSize = '16px';
            } else {
                currentSize = Math.max(12, Math.min(24, currentSize + step));
                root.style.fontSize = currentSize + 'px';
            }
            localStorage.setItem('fontSize', root.style.fontSize);
        }

        // Dark mode toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        }

        // Apply saved settings on load
        document.addEventListener('DOMContentLoaded', () => {
            const savedFontSize = localStorage.getItem('fontSize');
            if (savedFontSize) {
                document.documentElement.style.fontSize = savedFontSize;
            }
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark');
            }
        });

        let selectedRefeicao = '';
        let selectedSatisfacao = '';

        async function fetchEvaluatedMeals() {
            try {
                const response = await fetch('../control/controlOpiniao.php?action=getEvaluations');
                if (!response.ok) {
                    throw new Error('Erro ao buscar avaliações: ' + response.statusText);
                }
                const data = await response.json();
                if (data.success) {
                    const evaluatedMeals = data.evaluatedMeals;
                    document.querySelectorAll('.options-flex .option[data-meal]').forEach(option => {
                        const meal = option.dataset.meal;
                        if (evaluatedMeals.includes(meal)) {
                            option.classList.add('disabled');
                            option.removeAttribute('onclick');
                            option.title = 'Você já avaliou esta refeição hoje.';
                        }
                    });
                    // Select first non-disabled meal
                    const firstAvailable = document.querySelector('.options-flex .option:not(.disabled)');
                    if (firstAvailable) {
                        selectMeal(firstAvailable, firstAvailable.dataset.meal);
                    }
                } else {
                    console.error('Erro ao carregar avaliações:', data.message);
                }
            } catch (error) {
                console.error('Erro ao buscar avaliações:', error);
            }
        }

        function selectMeal(element, value) {
            if (element.classList.contains('disabled')) {
                alert('Você já avaliou esta refeição hoje.');
                return;
            }
            document.querySelectorAll('.options-flex .option').forEach(btn => btn.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('refeicao').value = value;
            selectedRefeicao = element.textContent;
        }

        function selectOption(label, value) {
            document.querySelectorAll('.options-flex .option').forEach(option => {
                option.classList.remove('selected');
            });
            label.classList.add('selected');
            label.querySelector('input').checked = true;
            selectedSatisfacao = label.textContent.trim();
        }

        function openConfirmModal() {
            const refeicao = document.getElementById('refeicao').value;
            const satisfacao = document.querySelector('input[name="satisfacao"]:checked');
            if (!refeicao || !satisfacao) {
                const modal = document.getElementById('warningModal');
                modal.style.display = 'flex';
                return;
            }
            const modal = document.getElementById('confirmModal');
            document.getElementById('modalRefeicao').textContent = selectedRefeicao || 'Não selecionado';
            document.getElementById('modalSatisfacao').textContent = selectedSatisfacao || 'Não selecionado';
            document.getElementById('modalError').style.display = 'none';
            modal.style.display = 'flex';
        }

        function openSugestaoModal() {
            const modal = document.getElementById('sugestaoModal');
            modal.style.display = 'flex';
            document.getElementById('sugestaoTextarea').focus();
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'none';
        }

        async function submitForm() {
            const form = document.getElementById('avaliacaoForm');
            const formData = new FormData(form);
            console.log('Form Data:', Object.fromEntries(formData));

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.statusText);
                }

                const text = await response.text();
                console.log('Resposta do servidor:', text);

                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        showMessage('success', data.message);
                        closeModal('confirmModal');
                        form.reset();
                        selectedRefeicao = '';
                        selectedSatisfacao = '';
                        document.querySelectorAll('.option').forEach(btn => btn.classList.remove('active', 'selected'));
                        await fetchEvaluatedMeals();
                    } else {
                        document.getElementById('modalError').textContent = data.message || 'Erro ao enviar a avaliação.';
                        document.getElementById('modalError').style.display = 'block';
                    }
                } catch (e) {
                    window.location.href = '../view/avaliacao.php';
                }
            } catch (error) {
                console.error('Erro ao enviar formulário:', error);
                document.getElementById('modalError').textContent = 'Erro ao enviar a avaliação: ' + error.message;
                document.getElementById('modalError').style.display = 'block';
            }
        }

        function showMessage(type, message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.textContent = message;
            const card = document.querySelector('.card');
            card.insertBefore(messageDiv, card.firstChild);
            setTimeout(() => messageDiv.remove(), 5000);
        }

        document.addEventListener('click', function(event) {
            const confirmModal = document.getElementById('confirmModal');
            const sugestaoModal = document.getElementById('sugestaoModal');
            const warningModal = document.getElementById('warningModal');
            if (event.target === confirmModal) {
                closeModal('confirmModal');
            }
            if (event.target === sugestaoModal) {
                closeModal('sugestaoModal');
            }
            if (event.target === warningModal) {
                closeModal('warningModal');
            }
        });
    </script>
</body>
</html>