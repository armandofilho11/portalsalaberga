<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../partials/system_layout.php';

// Estilos específicos para o formulário de geração de QRCodes
$extraHead = '
    <link rel="icon" href="https://salaberga.com/salaberga/portalsalaberga/app/main/assets/img/Design%20sem%20nome.svg" type="image/x-icon">
    <style>
        .gradient-text {
            background: linear-gradient(45deg, #008C45, #3CB371);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .input-focus-ring:focus {
            box-shadow: 0 0 0 3px rgba(0, 140, 69, 0.1);
        }
        .btn-primary {
            background: linear-gradient(90deg, #008C45, #3CB371);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #006d35, #2e8b57);
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -2px rgba(0, 140, 69, 0.2);
        }
        .loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #008C45;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .icon-container {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(0, 140, 69, 0.1);
            color: #008C45;
        }
        .pulse-effect {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .slide-in {
            animation: slideIn 0.5s ease-out;
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
    </style>
';

// Inicializa o head padrão do sistema
renderHead('Portal Salaberga - Seleção de Turma', $extraHead);
?>

<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col">

    <?php renderSidebar('qrcode'); ?>

    <div class="lg:pl-72 flex-1 flex flex-col justify-between">
        
        <main class="flex-1 flex items-center justify-center px-4 pt-12 pb-12">
            <div class="max-w-md w-full slide-in">
                
                <div class="text-center mb-8">
                    <div class="icon-container mx-auto mb-4">
                        <i class="fas fa-qrcode text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">
                        <span class="gradient-text">Seleção de Turma</span>
                    </h1>
                    <p class="text-slate-600 text-sm">Selecione a turma para gerar o QR Code</p>
                </div>

                <div class="form-card p-8 border-t-4 border-[#008C45]">
                    <form id="turmaForm" action="qrcode.php" method="post" class="space-y-6">
                        
                        <div class="space-y-2">
                            <label for="turmajs" class="block text-sm font-medium text-slate-700">
                                <i class="fas fa-users mr-2 text-[#008C45]"></i>
                                Turma
                            </label>
                            <select 
                                name="turma" 
                                id="turmajs" 
                                class="w-full p-4 border-2 border-slate-200 rounded-lg focus:border-[#008C45] input-focus-ring outline-none transition-all duration-300 bg-white text-sm text-slate-800"
                                required
                            >
                                <option value="">Selecione uma turma</option>
                                <option value="9">3° ano A</option>
                                <option value="10">3° ano B</option>
                                <option value="11">3° ano C</option>
                                <option value="12">3° ano D</option>
                            </select>
                            
                            <div id="turma-error" class="hidden text-rose-500 text-xs flex items-center mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span>Por favor, selecione uma turma</span>
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="w-full btn-primary text-white font-medium py-4 px-6 rounded-lg focus:outline-none focus:ring-4 focus:ring-emerald-100 disabled:opacity-50 disabled:cursor-not-allowed text-sm cursor-pointer"
                        >
                            <span id="submitText" class="flex items-center justify-center gap-2">
                                <i class="fas fa-qrcode"></i>
                                Gerar QR Code
                            </span>
                            <span id="submitLoading" class="hidden flex items-center justify-center gap-2">
                                <div class="loading-spinner"></div>
                                Gerando...
                            </span>
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <a href="<?= viewUrl('relatorio.php') ?>" class="inline-flex items-center gap-2 text-[#008C45] hover:text-[#3CB371] font-medium transition-colors duration-300 text-sm">
                            <i class="fas fa-arrow-left text-sm"></i>
                            Voltar ao Menu
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <?php renderFooter(); ?>
    </div>

    <script>
        class TurmaSelector {
            constructor() {
                this.form = document.getElementById('turmaForm');
                this.select = document.getElementById('turmajs');
                this.submitBtn = document.getElementById('submitBtn');
                this.init();
            }

            init() {
                this.select.addEventListener('change', () => this.validateSelection());
                this.form.addEventListener('submit', (e) => this.handleSubmit(e));

                this.select.addEventListener('change', () => {
                    if (this.select.value) {
                        this.select.classList.add('border-emerald-500');
                        this.select.classList.remove('border-slate-200');
                    } else {
                        this.select.classList.remove('border-emerald-500');
                        this.select.classList.add('border-slate-200');
                    }
                });
            }

            validateSelection() {
                const errorElement = document.getElementById('turma-error');
                if (!this.select.value) {
                    errorElement.classList.remove('hidden');
                    return false;
                } else {
                    errorElement.classList.add('hidden');
                    return true;
                }
            }

            handleSubmit(e) {
                if (!this.validateSelection()) {
                    e.preventDefault();
                    
                    this.select.classList.add('border-rose-500');
                    this.select.style.animation = 'shake 0.5s ease-in-out';
                    
                    setTimeout(() => {
                        this.select.style.animation = '';
                        this.select.classList.remove('border-rose-500');
                    }, 500);
                    
                    return;
                }
                this.showLoadingState();
            }

            showLoadingState() {
                const submitText = document.getElementById('submitText');
                const submitLoading = document.getElementById('submitLoading');
                
                submitText.classList.add('hidden');
                submitLoading.classList.remove('hidden');
                this.submitBtn.disabled = true;

                document.querySelector('.form-card').classList.add('pulse-effect');
            }
        }

        // Adiciona a animação de "shake" dinamicamente
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
        `;
        document.head.appendChild(style);

        document.addEventListener('DOMContentLoaded', () => {
            new TurmaSelector();
        });
    </script>
</body>
</html>