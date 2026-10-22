<?php
session_start();
require_once 'model/VotoModel.php';

$votoModel = new VotoModel();
$mensagem = '';
$tipoMensagem = '';

// Processar voto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voto'])) {
    $voto = $_POST['voto'];
    $alunoId = isset($_SESSION['aluno_id']) ? $_SESSION['aluno_id'] : null;
    if ($votoModel->registrarVoto($alunoId, $voto)) {
        header("Location: sucesso.php");
        exit();
    } else {
        $mensagem = 'Erro ao registrar voto. Tente novamente.';
        $tipoMensagem = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votação - Grêmio Estudantil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#e6f2ec', 100: '#cce5d9', 200: '#99cbb3', 300: '#66b18d',
                            400: '#339766', 500: '#007d40', 600: '#006a36', 700: '#005A24',
                            800: '#004d1f', 900: '#00401a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body { 
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-pulse-slow {
            animation: pulseSlow 3s infinite;
        }
        
        @keyframes pulseSlow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .vote-card {
            transition: all 0.3s ease;
            transform: translateY(0);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .vote-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .vote-icon {
            transition: all 0.3s ease;
        }
        
        .vote-card:hover .vote-icon {
            transform: scale(1.2);
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #f00;
            opacity: 0;
        }
        
        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: -1;
        }
        
        .wave-bg svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }
        
        .wave-bg .shape-fill {
            fill: rgba(0, 90, 36, 0.1);
        }
    </style>
</head>
<body class="min-h-screen relative">
    <div class="wave-bg">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>

    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gradient-to-r from-primary-800 to-primary-600 text-white shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="bg-white p-3 rounded-full mr-4 shadow-md">
                            <i class="fas fa-users text-primary-700 text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">Votação do Grêmio</h1>
                            <p class="text-primary-100 mt-1">Escola Estadual</p>
                        </div>
                    </div>
                    <a href="admin/index.php" class="bg-white text-primary-700 hover:bg-primary-50 px-4 py-2 rounded-lg transition duration-200 shadow-md flex items-center">
                        <i class="fas fa-user-shield mr-2"></i>Admin
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <?php if ($mensagem): ?>
                <div id="message-alert" class="mb-6 p-4 rounded-lg animate-fade-in <?php echo $tipoMensagem === 'success' ? 'bg-green-100 text-green-700 border-l-4 border-green-500' : 'bg-red-100 text-red-700 border-l-4 border-red-500'; ?>">
                    <div class="flex items-center">
                        <i class="<?php echo $tipoMensagem === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500'; ?> text-xl mr-3"></i>
                        <p><?php echo htmlspecialchars($mensagem); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Votação Form -->
            <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-xl p-8 animate-fade-in relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-primary-50 rounded-full -mr-20 -mt-20 z-0"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-primary-50 rounded-full -ml-20 -mb-20 z-0"></div>
                
                <div class="relative z-10">
                    <div class="text-center mb-10">
                        <div class="inline-block p-3 bg-primary-50 rounded-full mb-4 animate-pulse-slow">
                            <i class="fas fa-vote-yea text-4xl text-primary-600"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800">Votação do Grêmio Estudantil</h2>
                        <p class="text-gray-600 mt-3 max-w-xl mx-auto">Você é a favor da criação do novo grêmio estudantil para representar os interesses dos alunos?</p>
                    </div>

                    <form method="POST" class="space-y-6 relative z-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <button type="submit" name="voto" value="sim" id="vote-yes"
                                    class="vote-card p-8 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 border-2 border-green-200 rounded-xl transition duration-300 group">
                                <div class="flex flex-col items-center">
                                    <div class="bg-green-100 p-4 rounded-full mb-5 shadow-md">
                                        <i class="fas fa-thumbs-up text-4xl text-green-600 vote-icon"></i>
                                    </div>
                                    <span class="text-2xl font-semibold text-green-700">Sim</span>
                                    <p class="text-green-600 mt-3 text-center">Apoio a criação do grêmio estudantil</p>
                                </div>
                            </button>

                            <button type="submit" name="voto" value="nao" id="vote-no"
                                    class="vote-card p-8 bg-gradient-to-br from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 border-2 border-red-200 rounded-xl transition duration-300 group">
                                <div class="flex flex-col items-center">
                                    <div class="bg-red-100 p-4 rounded-full mb-5 shadow-md">
                                        <i class="fas fa-thumbs-down text-4xl text-red-600 vote-icon"></i>
                                    </div>
                                    <span class="text-2xl font-semibold text-red-700">Não</span>
                                    <p class="text-red-600 mt-3 text-center">Não apoio a criação do grêmio estudantil</p>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Fechar alerta de mensagem após 5 segundos
        const messageAlert = document.getElementById('message-alert');
        if (messageAlert) {
            setTimeout(() => {
                messageAlert.style.opacity = '0';
                messageAlert.style.transform = 'translateY(-10px)';
                messageAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    messageAlert.style.display = 'none';
                }, 500);
            }, 5000);
        }
        
        // Efeito de confete ao passar o mouse sobre os botões de voto
        function createConfetti(button, color) {
            const buttonRect = button.getBoundingClientRect();
            const confettiCount = 20;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.backgroundColor = color;
                confetti.style.left = `${buttonRect.left + Math.random() * buttonRect.width}px`;
                confetti.style.top = `${buttonRect.top + Math.random() * buttonRect.height}px`;
                confetti.style.width = `${5 + Math.random() * 5}px`;
                confetti.style.height = `${5 + Math.random() * 5}px`;
                confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                confetti.style.position = 'fixed';
                confetti.style.zIndex = '1000';
                document.body.appendChild(confetti);
                
                // Animação
                setTimeout(() => {
                    confetti.style.opacity = '1';
                    confetti.style.transform = `translate(${-50 + Math.random() * 100}px, ${-Math.random() * 100}px) rotate(${Math.random() * 360}deg)`;
                    confetti.style.transition = 'all 1s ease';
                    
                    setTimeout(() => {
                        confetti.style.opacity = '0';
                        setTimeout(() => {
                            document.body.removeChild(confetti);
                        }, 1000);
                    }, 800);
                }, Math.random() * 300);
            }
        }
        
        const voteYesButton = document.getElementById('vote-yes');
        const voteNoButton = document.getElementById('vote-no');
        
        if (voteYesButton) {
            voteYesButton.addEventListener('mouseenter', () => {
                createConfetti(voteYesButton, '#10b981'); // Verde
            });
        }
        
        if (voteNoButton) {
            voteNoButton.addEventListener('mouseenter', () => {
                createConfetti(voteNoButton, '#ef4444'); // Vermelho
            });
        }
        
        // Adicionar efeito de clique nos botões
        const voteButtons = document.querySelectorAll('.vote-card');
        voteButtons.forEach(button => {
            button.addEventListener('mousedown', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            button.addEventListener('mouseup', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>