<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEEPS - Inscrição Confirmada</title>
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
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Matrix-like background effect */
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

        /* Floating code particles */
        .code-particle {
            position: absolute;
            color: rgba(0, 122, 51, 0.3);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            animation: float-code 8s linear infinite;
            pointer-events: none;
        }

        @keyframes float-code {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Terminal-like container */
        .terminal-container {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 122, 51, 0.3);
            border-radius: 12px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(0, 122, 51, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            overflow: hidden;
            position: relative;
        }

        /* Terminal header */
        .terminal-header {
            background: rgba(0, 122, 51, 0.1);
            border-bottom: 1px solid rgba(0, 122, 51, 0.2);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .terminal-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27ca3f; }

        .terminal-title {
            color: rgba(255, 255, 255, 0.7);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            margin-left: 12px;
        }

        /* Success icon with glow effect */
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #007A33, #10B981);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
            animation: pulse-glow 2s infinite;
            box-shadow: 0 0 30px rgba(0, 122, 51, 0.5);
        }

        .success-icon::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            background: linear-gradient(45deg, #007A33, #10B981, #007A33);
            border-radius: 50%;
            z-index: -1;
            animation: rotate-border 3s linear infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 30px rgba(0, 122, 51, 0.5);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 50px rgba(0, 122, 51, 0.8);
            }
        }

        @keyframes rotate-border {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Typewriter effect */
        .typewriter {
            font-family: 'JetBrains Mono', monospace;
            overflow: hidden;
            border-right: 2px solid #10B981;
            white-space: nowrap;
            animation: typing 2s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #10B981; }
        }

        /* Code block styling */
        .code-block {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 122, 51, 0.2);
            border-radius: 8px;
            padding: 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            color: #10B981;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .code-block::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 122, 51, 0.1), transparent);
            animation: scan-line 3s infinite;
        }

        @keyframes scan-line {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Loading dots with matrix effect */
        .loading-dots {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: matrix-dot 1.5s infinite;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }

        .dot:nth-child(1) { animation-delay: 0s; }
        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes matrix-dot {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.5);
                opacity: 1;
                box-shadow: 0 0 20px rgba(16, 185, 129, 0.8);
            }
        }

        /* Progress bar */
        .progress-container {
            background: rgba(0, 0, 0, 0.3);
            height: 4px;
            border-radius: 2px;
            overflow: hidden;
            margin: 20px 0;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #007A33, #10B981);
            border-radius: 2px;
            animation: progress-fill 3s ease-out forwards;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
        }

        @keyframes progress-fill {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        /* Glitch effect for title */
        .glitch {
            position: relative;
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .glitch::before,
        .glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .glitch::before {
            animation: glitch-1 0.5s infinite;
            color: #ff0040;
            z-index: -1;
        }

        .glitch::after {
            animation: glitch-2 0.5s infinite;
            color: #00ff40;
            z-index: -2;
        }

        @keyframes glitch-1 {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-2px, 2px); }
            40% { transform: translate(-2px, -2px); }
            60% { transform: translate(2px, 2px); }
            80% { transform: translate(2px, -2px); }
        }

        @keyframes glitch-2 {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(2px, 2px); }
            40% { transform: translate(2px, -2px); }
            60% { transform: translate(-2px, 2px); }
            80% { transform: translate(-2px, -2px); }
        }

        /* Responsive design */
        @media (max-width: 640px) {
            .terminal-container {
                margin: 16px;
            }
            
            .glitch {
                font-size: 1.8rem;
            }
            
            .success-icon {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Floating code particles -->
    <div id="code-particles"></div>

    <div class="terminal-container max-w-2xl w-full">
        <!-- Terminal Header -->
        <div class="terminal-header">
            <div class="terminal-dot dot-red"></div>
            <div class="terminal-dot dot-yellow"></div>
            <div class="terminal-dot dot-green"></div>
            <div class="terminal-title">seeps-dev-portal.sh</div>
            <div class="ml-auto text-xs text-gray-400">
                <i class="fas fa-wifi mr-1"></i>
                Connected
            </div>
        </div>

        <!-- Terminal Content -->
        <div class="p-8 text-center">
            <!-- Success Icon -->
            <div class="success-icon mb-6">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>

            <!-- Title with Glitch Effect -->
            <h1 class="glitch mb-6" data-text="SUCCESS">
                SUCCESS
            </h1>

            <!-- Code Block -->
            

            <!-- Typewriter Message -->
            <div class="mb-6">
                <p class="text-gray-300 text-lg mb-2">
                    <span class="text-green-400 font-semibold">$ ./confirm_registration.sh</span>
                </p>
                <p class="typewriter text-green-400 text-lg font-mono inline-block">
                    Desenvolvedor registrado com sucesso!
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>

            <!-- Loading Section -->
            <div class="flex items-center justify-center gap-4 text-gray-300">
                <i class="fas fa-terminal text-green-400"></i>
                <span class="font-mono">Redirecionando para portal de autenticação</span>
                <div class="loading-dots">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>

            <!-- System Info -->
            <div class="mt-8 text-xs text-gray-500 font-mono">
                <div class="flex justify-between items-center">
                    <span>SEEPS v2.0.1</span>
                    <span id="countdown">Redirecionando em 3s</span>
                    <span>PID: 1337</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Create floating code particles
        function createCodeParticles() {
            const particles = document.getElementById('code-particles');
            const codeSnippets = [
                'function()', '{ }', 'const', 'let', 'var', '=>', '&&', '||', 
                'if()', 'else', 'return', 'true', 'false', 'null', 'undefined',
                '===', '!==', '++', '--', 'async', 'await', 'Promise', 'Array',
                '.map()', '.filter()', '.reduce()', 'console.log()', 'JSON.parse()',
                'setTimeout()', 'addEventListener()', 'querySelector()', 'fetch()'
            ];

            function createParticle() {
                const particle = document.createElement('div');
                particle.className = 'code-particle';
                particle.textContent = codeSnippets[Math.floor(Math.random() * codeSnippets.length)];
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDuration = (Math.random() * 3 + 5) + 's';
                particle.style.opacity = Math.random() * 0.5 + 0.1;
                particles.appendChild(particle);

                // Remove particle after animation
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }, 8000);
            }

            // Create particles periodically
            setInterval(createParticle, 500);
        }

        // Countdown timer
        function startCountdown() {
            let count = 3;
            const countdownElement = document.getElementById('countdown');
            
            const timer = setInterval(() => {
                count--;
                countdownElement.textContent = `Redirecionando em ${count}s`;
                
                if (count <= 0) {
                    clearInterval(timer);
                    countdownElement.textContent = 'Redirecionando...';
                    // Simulate redirect (replace with actual URL)
                    setTimeout(() => {
                         window.location.href = '../../index.php';
                        console.log('Redirecionando para login...');
                    }, 500);
                }
            }, 1000);
        }

        // Add glitch effect on hover
        document.querySelector('.glitch').addEventListener('mouseenter', function() {
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = '';
            }, 100);
        });

        // Initialize effects
        document.addEventListener('DOMContentLoaded', function() {
            createCodeParticles();
            startCountdown();
            
            // Add typing sound effect (optional)
            setTimeout(() => {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
            }, 1000);
        });

        // Matrix-like effect for background
        function createMatrixEffect() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.style.position = 'fixed';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.width = '100%';
            canvas.style.height = '100%';
            canvas.style.pointerEvents = 'none';
            canvas.style.zIndex = '-1';
            canvas.style.opacity = '0.1';
            document.body.appendChild(canvas);

            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            const matrix = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789@#$%^&*()*&^%+-/~{[|`]}";
            const matrixArray = matrix.split("");
            const fontSize = 10;
            const columns = canvas.width / fontSize;
            const drops = [];

            for (let x = 0; x < columns; x++) {
                drops[x] = 1;
            }

            function draw() {
                ctx.fillStyle = 'rgba(15, 23, 42, 0.04)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = '#007A33';
                ctx.font = fontSize + 'px monospace';

                for (let i = 0; i < drops.length; i++) {
                    const text = matrixArray[Math.floor(Math.random() * matrixArray.length)];
                    ctx.fillText(text, i * fontSize, drops[i] * fontSize);

                    if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) {
                        drops[i] = 0;
                    }
                    drops[i]++;
                }
            }

            setInterval(draw, 35);
        }

        // Start matrix effect
        setTimeout(createMatrixEffect, 2000);
    </script>
</body>
</html>