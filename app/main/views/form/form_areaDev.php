<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção Área Dev</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#007A33',
                        secondary: '#FFA500',
                        success: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                        info: '#3B82F6'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding: 1rem;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(0, 122, 51, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 165, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-radius: 1rem;
            padding: 2rem;
            width: 100%;
            max-width: 42rem;
            margin: 0 auto;
        }

        .field-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .field-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(0, 122, 51, 0.3);
            transform: translateY(-2px);
        }

        .custom-input {
            background: rgba(15, 23, 42, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            color: #ffffff;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .custom-input:focus {
            border-color: #007A33;
            box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.1);
            outline: none;
            background: rgba(15, 23, 42, 0.9);
        }

        .custom-input:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .custom-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .option-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            cursor: pointer;
            padding: 1.5rem;
            text-align: center;
        }

        .option-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(0, 122, 51, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 122, 51, 0.15);
        }

        .option-card.selected {
            background: rgba(0, 122, 51, 0.2);
            border-color: #007A33;
            box-shadow: 0 10px 25px rgba(0, 122, 51, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #007A33 0%, #00a843 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px -5px rgba(0, 122, 51, 0.3);
            width: 100%;
            max-width: 300px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            margin: 0 auto;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00a843 0%, #00c250 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(0, 122, 51, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #FFA500 0%, #ff8c00 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px -5px rgba(255, 165, 0, 0.3);
            border: none;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 300px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            margin: 0 auto;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #ff8c00 0%, #ff7700 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(255, 165, 0, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-secondary:active {
            transform: translateY(0);
        }

        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-animation {
            animation-delay: calc(var(--index) * 0.1s);
        }

        .progress-bar {
            background: linear-gradient(90deg, #007A33 0%, #00a843 50%, #00c250 100%);
            height: 4px;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #3d3d3d;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #007A33;
        }

        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }
        
        .fade-out {
            animation: fadeOut 0.6s ease-out forwards;
        }

        /* Ajustes responsivos para o grid de opções */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        /* Media queries para diferentes tamanhos de tela */
        @media (max-width: 768px) {
            .glass-card {
                padding: 1.5rem;
                margin: 1rem;
            }

            .options-grid {
                grid-template-columns: 1fr;
            }

            .field-card {
                padding: 1rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            p {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .glass-card {
                padding: 1rem;
                margin: 0.5rem;
            }

            .btn-primary, .btn-secondary {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .option-card {
                padding: 1rem;
            }

            .custom-input {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }

        /* Ajustes para telas muito pequenas */
        @media (max-width: 320px) {
            .glass-card {
                padding: 0.75rem;
            }

            .field-card {
                padding: 0.75rem;
            }

            .option-card {
                padding: 0.75rem;
            }
        }

        /* Ajustes para telas muito grandes */
        @media (min-width: 1440px) {
            .glass-card {
                max-width: 48rem;
            }

            .options-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Ajustes para orientação paisagem em dispositivos móveis */
        @media (max-height: 600px) and (orientation: landscape) {
            .glass-card {
                margin: 0.5rem auto;
            }

            .field-card {
                padding: 0.75rem;
            }

            .options-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Ajustes para melhor legibilidade em telas de alta resolução */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            body {
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
        }

        /* Ajustes para modo escuro do sistema */
        @media (prefers-color-scheme: dark) {
            .glass-card {
                background: rgba(15, 23, 42, 0.7);
            }
        }

        /* Ajustes para reduzir movimento em dispositivos que preferem menos movimento */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Background Effects -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
        <div class="absolute top-3/4 left-3/4 w-48 h-48 bg-info/5 rounded-full blur-3xl"></div>
    </div>

    <div class="glass-card rounded-2xl p-8 max-w-2xl w-full mx-4 fade-in">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/20 rounded-full mb-4 floating-icon">
                <i class="fas fa-code text-2xl text-primary"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Seleção Área Dev</h1>
            <p class="text-xl text-gray-300 font-medium">Escolha sua área de especialização</p>
            
            <!-- Botão do Edital -->
            <div class="mt-4 mb-6">
                <a href="#" id="edital-btn" class="btn-secondary px-6 py-3 rounded-xl font-semibold text-lg gap-3" target="_blank">
                    <i class="fas fa-file-alt"></i>
                    <span>Ver Edital Completo</span>
                    <i class="fas fa-external-link-alt text-sm"></i>
                </a>
            </div>

            <div class="bg-slate-800/50 rounded-lg p-3">
                <div class="flex items-center justify-between text-sm text-gray-400 mb-2">
                    <span>Progresso do Formulário</span>
                    <span id="progress-text">0/2 preenchidos</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2">
                    <div class="progress-bar w-0" id="progress-bar"></div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form id="dev-form" class="space-y-6" action="../../controllers/controller_areaDev.php" method="POST">
            <!-- Nome Field -->
            <div class="field-card rounded-xl p-6 slide-up" style="--index: 0">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary/70 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Nome Completo</h3>
                        <p class="text-sm text-gray-400">Informe seu nome completo</p>
                    </div>
                </div>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                    class="custom-input w-full rounded-lg px-4 py-3 text-white"
                        placeholder="Digite seu nome completo"
                        required
                    >
                </div>
                
            <!-- Função Field -->
            <div class="field-card rounded-xl p-6 slide-up" style="--index: 1">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-secondary to-secondary/70 rounded-full flex items-center justify-center">
                        <i class="fas fa-briefcase text-white"></i>
                        </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Área de Especialização</h3>
                        <p class="text-sm text-gray-400">Selecione sua área de interesse</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4 options-grid">
                    <div class="option-card rounded-lg p-4 text-center" data-value="Back-end">
                        <input type="radio" id="backend" name="funcao" value="Back-end" class="hidden" required>
                        <div class="text-3xl mb-2">⚙️</div>
                        <div class="text-white font-medium">Back-end</div>
                        <div class="text-xs text-gray-400 mt-1">Servidor & APIs</div>
                    </div>
                    
                    <div class="option-card rounded-lg p-4 text-center" data-value="Front-end">
                        <input type="radio" id="frontend" name="funcao" value="Front-end" class="hidden">
                        <div class="text-3xl mb-2">🎨</div>
                        <div class="text-white font-medium">Front-end</div>
                        <div class="text-xs text-gray-400 mt-1">Interface & UX</div>
                    </div>
                    
                    <div class="option-card rounded-lg p-4 text-center" data-value="Design">
                        <input type="radio" id="design" name="funcao" value="Design" class="hidden">
                        <div class="text-3xl mb-2">✨</div>
                        <div class="text-white font-medium">Design</div>
                        <div class="text-xs text-gray-400 mt-1">UI/UX Design</div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center pt-6">
                <button type="submit" class="btn-primary px-8 py-4 rounded-xl text-white font-semibold text-lg flex items-center space-x-3 disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn" disabled>
                    <i class="fas fa-paper-plane"></i>
                    <span id="btn-text">Enviar Inscrição</span>
                    <span id="btn-loading" class="hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                        Processando...
                    </span>
                </button>
            </div>
            </form>
            
        <!-- Success Message -->
        <div id="success-message" class="hidden mt-6 p-4 bg-success/20 border border-success/30 rounded-lg text-success text-center">
            <i class="fas fa-check-circle mr-2" id="success-icon"></i>
            <div class="font-semibold text-lg mb-2" id="success-title">Mensagem</div>
            <div id="success-content"></div>
        </div>
    </div>

    <script>
        let filledFields = 0;
        const totalFields = 2;

        function updateProgress() {
            const percentage = (filledFields / totalFields) * 100;
            document.getElementById('progress-bar').style.width = `${percentage}%`;
            document.getElementById('progress-text').textContent = `${filledFields}/${totalFields} preenchidos`;
            
            const submitBtn = document.getElementById('submit-btn');
            if (filledFields === totalFields) {
                submitBtn.disabled = false;
                submitBtn.classList.add('pulse');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.remove('pulse');
            }
        }

        // Nome field validation
            const nomeInput = document.getElementById('nome');
        let nomeValid = false;

            nomeInput.addEventListener('input', function() {
            const isValid = this.value.trim().length >= 2;
            
            if (isValid && !nomeValid) {
                filledFields++;
                nomeValid = true;
            } else if (!isValid && nomeValid) {
                filledFields--;
                nomeValid = false;
            }
            
            // Visual feedback
                if (this.value.trim().length > 0 && this.value.trim().length < 2) {
                    this.style.borderColor = '#ef4444';
                } else {
                this.style.borderColor = 'rgba(255, 255, 255, 0.1)';
            }
            
            updateProgress();
        });

        // Function selection
        const optionCards = document.querySelectorAll('.option-card');
        let funcaoValid = false;

        optionCards.forEach(card => {
            card.addEventListener('click', function() {
                const value = this.dataset.value;
                const radioInput = document.querySelector(`input[value="${value}"]`);
                
                // Remove selection from all cards
                optionCards.forEach(c => c.classList.remove('selected'));
                
                // Add selection to clicked card
                this.classList.add('selected');
                radioInput.checked = true;
                
                // Update progress
                if (!funcaoValid) {
                    filledFields++;
                    funcaoValid = true;
                    updateProgress();
                }
                
                // Animation effect
                this.style.transform = 'translateY(-2px) scale(1.05)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-2px)';
                }, 200);
            });
        });

        // Edital button configuration
        document.getElementById('edital-btn').addEventListener('click', function(e) {
            // Substitua a URL abaixo pela URL real do seu edital
            const editalUrl = '../../assets/img/pdf/Edital-area-dev.pdf'; // ALTERE AQUI
            
            // Se você quiser que abra em uma nova aba
            window.open(editalUrl, '_blank');
            
            // Ou se quiser que abra na mesma aba, descomente a linha abaixo e comente a de cima
            // window.location.href = editalUrl;
            
            e.preventDefault();
        });

        // Form submission - mantém toda a funcionalidade original
        document.getElementById('dev-form').addEventListener('submit', function(e) {
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const submitBtn = document.getElementById('submit-btn');
            
            // Show loading state
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.remove('pulse');
        });

        // Verifica o status na URL ao carregar a página (após redirecionamento do controller)
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const messageArea = document.getElementById('success-message');
            const messageIcon = document.getElementById('success-icon');
            const messageTitle = document.getElementById('success-title');
            const messageContent = document.getElementById('success-content');

            if (status) {
                messageArea.classList.remove('hidden');
                // Limpa classes de cor anteriores
                messageArea.classList.remove('bg-success/20', 'border-success/30', 'text-success', 'bg-danger/20', 'border-danger/30', 'text-danger');

                if (status === 'sucesso') {
                    messageIcon.textContent = '🎉';
                    messageTitle.textContent = 'Inscrição realizada com sucesso!';
                    messageContent.textContent = '';
                    messageArea.classList.add('bg-success/20', 'border-success/30', 'text-success', 'fade-in');
                } else if (status === 'erro') {
                    messageIcon.textContent = '❌';
                    messageTitle.textContent = 'Erro ao realizar a inscrição.';
                    messageContent.textContent = 'Por favor, tente novamente.';
                    messageArea.classList.add('bg-danger/20', 'border-danger/30', 'text-danger', 'fade-in');
                } else if (status === 'erro_dados') {
                    messageIcon.textContent = '⚠️';
                    messageTitle.textContent = 'Dados incompletos!';
                    messageContent.textContent = 'Por favor, preencha todos os campos.';
                    messageArea.classList.add('bg-danger/20', 'border-danger/30', 'text-danger', 'fade-in');
                }

                // Ocultar a mensagem após alguns segundos
                setTimeout(() => {
                   messageArea.classList.remove('fade-in');
                   messageArea.classList.add('fade-out');
                   setTimeout(() => {
                       messageArea.style.display = 'none';
                       messageArea.classList.add('hidden');
                       messageArea.classList.remove('fade-out');
                   }, 600);
                }, 5000);

                // Limpar o parâmetro da URL após mostrar a mensagem
                history.replaceState({}, document.title, window.location.pathname);
            }
            
            // Initialize progress on page load
            updateProgress();
        });
    </script>
</body>
</html>