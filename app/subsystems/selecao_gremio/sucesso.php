<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voto Registrado - Grêmio Estudantil</title>
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
            animation: fadeIn 0.8s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-scale-in {
            animation: scaleIn 0.5s ease-out forwards;
            transform-origin: center;
            opacity: 0;
        }
        
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .animate-bounce-in {
            animation: bounceIn 1s ease-out forwards;
            animation-delay: 0.3s;
            opacity: 0;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0); opacity: 0; }
            60% { transform: scale(1.1); opacity: 1; }
            80% { transform: scale(0.95); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .progress-bar {
            transition: width 4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
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
        
        .success-circle {
            box-shadow: 0 0 0 15px rgba(16, 185, 129, 0.1);
        }
        
        .pulse-ring {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.1; transform: scale(1.2); }
        }
        
        .countdown-text {
            animation: countPulse 1s infinite;
        }
        
        @keyframes countPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
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
        <main class="flex-grow container mx-auto px-4 py-12">
            <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-xl p-8 text-center animate-fade-in relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-green-50 rounded-full -mr-20 -mt-20 z-0"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-green-50 rounded-full -ml-20 -mb-20 z-0"></div>
                
                <div class="relative z-10">
                    <!-- Success Icon with Rings -->
                    <div class="relative inline-block mb-6">
                        <div class="absolute inset-0 bg-green-400 rounded-full opacity-10 pulse-ring"></div>
                        <div class="relative inline-flex items-center justify-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-full success-circle animate-scale-in">
                            <i class="fas fa-check-circle text-5xl text-green-500"></i>
                        </div>
                    </div>
                    
                    <h2 class="text-3xl font-bold text-gray-800 mb-4 animate-bounce-in">Voto Registrado com Sucesso!</h2>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto animate-bounce-in" style="animation-delay: 0.5s;">
                        Obrigado por participar da votação do grêmio estudantil. Sua opinião é muito importante para nossa escola.
                    </p>

                    <div class="mb-8 animate-bounce-in" style="animation-delay: 0.7s;">
                        <div class="flex justify-center items-center mb-3">
                            <p class="text-sm text-gray-500 mr-2">Redirecionando em</p>
                            <span id="countdown" class="text-lg font-bold text-primary-600 countdown-text">4</span>
                            <p class="text-sm text-gray-500 ml-2">segundos</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="progress-bar bg-gradient-to-r from-green-400 to-primary-600 h-3 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>

                    <a href="index.php" class="inline-flex items-center px-5 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition duration-300 shadow-md animate-bounce-in" style="animation-delay: 0.9s;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar agora
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Criar efeito de confete
        function createConfetti() {
            const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];
            const confettiCount = 150;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = `${Math.random() * 100}vw`;
                confetti.style.top = `-20px`;
                confetti.style.width = `${5 + Math.random() * 5}px`;
                confetti.style.height = `${5 + Math.random() * 5}px`;
                confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                confetti.style.position = 'fixed';
                confetti.style.zIndex = '1000';
                document.body.appendChild(confetti);
                
                // Animação
                setTimeout(() => {
                    confetti.style.opacity = '1';
                    confetti.style.transform = `translate(${-50 + Math.random() * 100}px, ${100 + Math.random() * window.innerHeight}px) rotate(${Math.random() * 360}deg)`;
                    confetti.style.transition = `all ${3 + Math.random() * 2}s ease`;
                    
                    setTimeout(() => {
                        confetti.style.opacity = '0';
                        setTimeout(() => {
                            if (document.body.contains(confetti)) {
                                document.body.removeChild(confetti);
                            }
                        }, 1000);
                    }, 2000);
                }, Math.random() * 500);
            }
        }
        
        // Iniciar a barra de progresso e contagem regressiva
        document.addEventListener('DOMContentLoaded', function() {
            // Lançar confete
            createConfetti();
            
            const progressBar = document.querySelector('.progress-bar');
            const countdownEl = document.getElementById('countdown');
            let countdown = 4;
            
            // Iniciar a barra de progresso
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 100);
            
            // Atualizar a contagem regressiva
            const countdownInterval = setInterval(function() {
                countdown--;
                countdownEl.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                }
            }, 1000);
            
            // Redirecionar após 4 segundos
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 4000);
        });
    </script>
</body>
</html>