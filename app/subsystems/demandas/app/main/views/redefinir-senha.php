<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  $email = $_SESSION['reset_email'] ?? '';
  $reset_verified = $_SESSION['reset_verified'] ?? false;
  if (!$email || !$reset_verified) {
    header("Location: recuperar-senha.php");
    exit;
  }
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="content-language" content="pt-BR">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="theme-color" content="#7FB069">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>Redefinir Senha | EEEP Salaberga Torquato Gomes de Matos</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Redefinição de senha - EEEP Salaberga Torquato Gomes de Matos">
    <meta name="author" content="EEEP Salaberga Torquato Gomes de Matos">
    <meta name="keywords" content="redefinir senha, recuperação conta, EEEP Salaberga, sistema educacional, Maranguape">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/s_simbol.png">

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&family=Comfortaa:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ghibli-mist': '#F7F3E9',
                        'ghibli-cloud': '#E8F4F8',
                        'ghibli-sky': '#A8DADC',
                        'ghibli-forest': '#2D5016',
                        'ghibli-sage': '#7FB069',
                        'ghibli-coral': '#F4A261',
                        'ghibli-cream': '#F7F3E9',
                        'ghibli-sunset': '#FF6B6B'
                    },
                    backgroundImage: {
                        'hero-ghibli': 'url("https://hebbkx1anhila5yf.public.blob.vercel-storage.com/img_index3-9IdYq3BVf9Sebk6O6b0ZJtyMde0HUW.png")'
                    },
                    fontFamily: {
                        'ghibli': ['Comfortaa', 'sans-serif'],
                        'ghibli-text': ['Nunito', 'sans-serif']
                    },
                    boxShadow: {
                        'ghibli-soft': '0 4px 20px rgba(127, 176, 105, 0.15)',
                        'ghibli-cloud': '0 8px 32px rgba(248, 248, 248, 0.4)'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'sway': 'sway 8s ease-in-out infinite',
                        'drift': 'drift 15s linear infinite',
                        'sparkle': 'sparkle 2s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'slide-in-left': 'slideInLeft 0.8s ease-out forwards',
                        'slide-in-right': 'slideInRight 0.8s ease-out forwards',
                        'pulse-glow': 'pulse-glow 2s infinite',
                        'bounce-gentle': 'bounce-gentle 3s ease-in-out infinite'
                    }
                }
            }
        }
    </script>

    <style>
        /* Fonte principal */
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #F7F3E9 0%, #E8F4F8 100%);
        }

        /* Animações Studio Ghibli */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes sway {
            0%, 100% {
                transform: translateX(0px) rotate(0deg);
            }
            50% {
                transform: translateX(10px) rotate(2deg);
            }
        }

        @keyframes drift {
            0% {
                transform: translateY(-100px) translateX(0px) rotate(0deg);
            }
            100% {
                transform: translateY(calc(100vh + 100px)) translateX(50px) rotate(360deg);
            }
        }

        @keyframes sparkle {
            0%, 100% {
                opacity: 0.3;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(127, 176, 105, 0.4);
            }
            50% {
                box-shadow: 0 0 30px rgba(127, 176, 105, 0.6);
            }
        }

        @keyframes bounce-gentle {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Elementos flutuantes */
        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .leaf {
            position: absolute;
            width: 20px;
            height: 20px;
            background: #7FB069;
            border-radius: 0 100% 0 100%;
            animation: drift 15s linear infinite;
            opacity: 0.6;
        }

        .leaf:nth-child(2n) {
            background: #A4C3A2;
            animation-duration: 20s;
            animation-delay: -5s;
        }

        .leaf:nth-child(3n) {
            background: #F4A261;
            animation-duration: 18s;
            animation-delay: -10s;
        }

        .ghibli-cloud {
            position: absolute;
            background: rgba(248, 248, 248, 0.9);
            border-radius: 50px;
            animation: cloud-drift 30s linear infinite;
            opacity: 0.8;
            box-shadow: 0 4px 20px rgba(248, 248, 248, 0.3);
        }

        @keyframes cloud-drift {
            0% {
                transform: translateX(-200px);
            }
            100% {
                transform: translateX(calc(100vw + 200px));
            }
        }

        .ghibli-cloud::before,
        .ghibli-cloud::after {
            content: '';
            position: absolute;
            background: rgba(248, 248, 248, 0.9);
            border-radius: 50px;
        }

        .ghibli-cloud.small {
            width: 60px;
            height: 30px;
        }

        .ghibli-cloud.small::before {
            width: 40px;
            height: 40px;
            top: -15px;
            left: 10px;
        }

        .ghibli-cloud.small::after {
            width: 30px;
            height: 30px;
            top: -10px;
            right: 10px;
        }

        /* Enhanced input styles */
        .input-group {
            position: relative;
        }

        .input-group input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(247, 243, 233, 0.8) 0%, rgba(255, 255, 255, 0.9) 100%);
            border: 2px solid rgba(127, 176, 105, 0.3);
            backdrop-filter: blur(5px);
        }

        .input-group input:focus {
            transform: translateY(-2px);
            outline: none;
            border-color: #F4A261;
            box-shadow: 0 8px 25px rgba(244, 162, 97, 0.3);
            background: rgba(255, 255, 255, 0.95);
        }

        .input-group label {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(45, 80, 22, 0.6);
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            padding: 0 5px;
            background-color: transparent;
            z-index: 1;
            font-family: 'Nunito', sans-serif;
        }

        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            top: -12px;
            left: 10px;
            font-size: 0.85rem;
            color: #E76F51;
            transform: translateY(0);
            background: linear-gradient(135deg, #F7F3E9 0%, #E8F4F8 100%);
            padding: 0 8px;
            font-weight: 600;
            border-radius: 4px;
        }

        /* Enhanced button styles */
        .btn-enhanced {
            background: linear-gradient(135deg, #7FB069 0%, #A4C3A2 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            font-family: 'Comfortaa', cursive;
            box-shadow: 0 8px 20px rgba(127, 176, 105, 0.3);
        }

        .btn-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-enhanced:hover::before {
            left: 100%;
        }

        .btn-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(127, 176, 105, 0.4);
        }

        /* Error message styling */
        .error-message {
            background: linear-gradient(135deg, #E76F51 0%, #F4A261 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin: 16px 0;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(231, 111, 81, 0.3);
            animation: fadeInUp 0.5s ease-out;
            font-family: 'Nunito', sans-serif;
        }

        /* Loading state */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Efeitos especiais */
        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #F1C40F;
            border-radius: 50%;
            animation: sparkle 2s ease-in-out infinite;
        }

        /* Elementos com brilho mágico */
        .magical-glow {
            box-shadow: 0 0 20px rgba(127, 176, 105, 0.4), 0 0 40px rgba(244, 162, 97, 0.2);
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out forwards;
        }

        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        /* Focus styles */
        *:focus {
            outline: 2px solid #F4A261;
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #7FB069 0%, #F4A261 100%);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #2D5016 0%, #E76F51 100%);
        }

        /* Enhanced responsive styles */
        @media (max-width: 640px) {
            .main-container {
                width: 95%;
                margin: 1rem auto;
            }

            .input-group label {
                font-size: 0.9rem;
            }

            .btn-enhanced {
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width: 475px) {
            .main-container {
                width: 100%;
                margin: 0;
                border-radius: 0;
                min-height: 100vh;
            }

            .form-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body class="font-ghibli-text bg-gradient-to-br from-ghibli-mist via-ghibli-cloud to-ghibli-sky min-h-screen flex items-center justify-center p-0 sm:p-4">
    
    <!-- Elementos flutuantes Studio Ghibli -->
    <div class="floating-elements">
        <div class="leaf" style="top: -10%; left: 10%; animation-delay: 0s;"></div>
        <div class="leaf" style="top: -10%; left: 80%; animation-delay: -3s;"></div>
        <div class="leaf" style="top: -10%; left: 20%; animation-delay: -6s;"></div>
        <div class="leaf" style="top: -10%; left: 70%; animation-delay: -9s;"></div>
        <div class="leaf" style="top: -10%; left: 90%; animation-delay: -12s;"></div>
        <div class="leaf" style="top: -10%; left: 40%; animation-delay: -15s;"></div>
        <div class="leaf" style="top: -10%; left: 60%; animation-delay: -18s;"></div>
        <div class="leaf" style="top: -10%; left: 30%; animation-delay: -21s;"></div>

        <!-- Nuvens flutuantes -->
        <div class="ghibli-cloud small" style="top: 20%; animation-delay: 0s;"></div>
        <div class="ghibli-cloud small" style="top: 60%; animation-delay: -10s;"></div>
        <div class="ghibli-cloud small" style="top: 40%; animation-delay: -20s;"></div>
    </div>

    <div class="main-container w-full max-w-6xl bg-ghibli-cream rounded-none sm:rounded-3xl shadow-ghibli overflow-hidden animate-fade-in-up relative z-10">
        <div class="flex flex-col lg:flex-row min-h-[100vh] sm:min-h-[600px]">
            
            <!-- Enhanced Image Container com traços Ghibli -->
            <div class="hidden md:block lg:flex-1 bg-ghibli-forest relative overflow-hidden animate-slide-in-left">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-ghibli-forest/80 via-transparent to-ghibli-coral/80"></div>
                
                <!-- Elementos decorativos Ghibli -->
                <div class="absolute top-10 left-10 w-20 h-20 border-2 border-white/30 rounded-full animate-float"></div>
                <div class="absolute bottom-20 right-10 w-16 h-16 border-2 border-white/30 rounded-full animate-sway"></div>
                <div class="absolute top-1/3 right-20 w-12 h-12 bg-white/20 rounded-full animate-bounce-gentle"></div>
                
                <!-- Sparkles mágicos -->
                <div class="sparkle" style="top: 15%; left: 20%; animation-delay: 0s;"></div>
                <div class="sparkle" style="top: 70%; left: 80%; animation-delay: 1s;"></div>
                <div class="sparkle" style="top: 40%; left: 15%; animation-delay: 2s;"></div>
                <div class="sparkle" style="top: 80%; left: 30%; animation-delay: 3s;"></div>
                
                <div class="relative z-10 h-full flex flex-col justify-center items-center p-8 lg:p-12 text-center text-white">
                    <div class="mb-8">
                        <img src="../assets/s_simbol.png" alt="Logo EEEP Salaberga" class="w-24 h-24 mx-auto mb-4 opacity-90">
                    </div>
                    
                    <h1 class="text-3xl lg:text-5xl font-bold mb-6 leading-tight font-ghibli">
                        Nova Senha Segura
                    </h1>
                    
                    <p class="text-lg lg:text-xl mb-8 max-w-md leading-relaxed opacity-90 font-ghibli-text">
                        Crie uma nova senha forte para proteger sua conta e manter seus dados seguros.
                    </p>
                    
                    <div class="flex space-x-4 text-sm opacity-80">
                        <div class="flex items-center">
                            <i class="fas fa-lock mr-2"></i>
                            <span>Segura</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Protegida</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Verificada</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced Form Container com traços Ghibli -->
            <div class="w-full lg:flex-1 p-4 xs:p-6 sm:p-8 lg:p-12 flex flex-col justify-center animate-slide-in-right relative">
                
                <!-- Sparkles no formulário -->
                <div class="sparkle" style="top: 10%; right: 10%; animation-delay: 0s;"></div>
                <div class="sparkle" style="bottom: 20%; left: 15%; animation-delay: 2s;"></div>
                
                <!-- Logo Container com efeitos Ghibli -->
                <div class="text-center mb-6 sm:mb-8">
                    <div class="inline-block p-3 sm:p-4 rounded-2xl">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-ghibli-forest mb-2 font-ghibli">
                        Criar Nova Senha
                    </h2>
                    <p class="text-ghibli-forest/70 text-base sm:text-lg font-ghibli-text">
                        Proteja sua conta
                    </p>
                </div>

                <!-- Email Display -->
                <div class="mb-6 p-4 bg-ghibli-sage/20 border border-ghibli-sage/30 rounded-lg">
                    <p class="text-xs text-ghibli-forest/70">Recuperando conta:</p>
                    <p class="text-sm font-semibold text-ghibli-forest break-all"><?php echo htmlspecialchars($email); ?></p>
                    <p class="text-xs text-ghibli-sage mt-1"><i class="fas fa-check-circle mr-1"></i>Código verificado com sucesso</p>
                </div>

                <!-- Reset Password Form -->
                <form action="../controllers/usuario_controller.php?action=redefinir_senha" method="POST" class="space-y-4 sm:space-y-6" id="formRedefinirSenha">
                    
                    <!-- Hidden email field -->
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                    <!-- New Password Input -->
                    <div class="input-group">
                        <input 
                          type="password" 
                          name="nova_senha" 
                          id="nova_senha"
                          placeholder="" 
                          minlength="6"
                          required
                          class="w-full p-4 border-2 border-ghibli-sage/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-ghibli-coral focus:border-ghibli-coral transition pr-12 bg-white/80 backdrop-blur-sm">
                        <label>Nova Senha</label>
                        <button
                          type="button"
                          onclick="togglePasswordVisibility('nova_senha', 'icon1')"
                          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-ghibli-forest/60 hover:text-ghibli-forest transition">
                          <i id="icon1" class="fas fa-eye"></i>
                        </button>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="input-group">
                        <input 
                          type="password"
                          name="confirmar_senha"
                          id="confirmar_senha"
                          placeholder="" 
                          minlength="6"
                          required
                          class="w-full p-4 border-2 border-ghibli-sage/30 rounded-xl focus:outline-none focus:ring-2 focus:ring-ghibli-coral focus:border-ghibli-coral transition pr-12 bg-white/80 backdrop-blur-sm">
                        <label>Confirmar Senha</label>
                        <button
                          type="button"
                          onclick="togglePasswordVisibility('confirmar_senha', 'icon2')"
                          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-ghibli-forest/60 hover:text-ghibli-forest transition">
                          <i id="icon2" class="fas fa-eye"></i>
                        </button>
                    </div>

                    <!-- Password Requirements -->
                    <div class="p-4 bg-ghibli-coral/10 border border-ghibli-coral/20 rounded-lg">
                        <p class="text-xs font-semibold text-ghibli-forest mb-2">Requisitos da senha:</p>
                        <ul class="text-xs text-ghibli-forest/80 space-y-1">
                            <li class="flex items-center"><i class="fas fa-check mr-2 text-ghibli-sage"></i> Mínimo 6 caracteres</li>
                            <li class="flex items-center"><i class="fas fa-check mr-2 text-ghibli-sage"></i> Senhas devem coincidir</li>
                            <li class="flex items-center"><i class="fas fa-check mr-2 text-ghibli-sage"></i> Use uma combinação segura</li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 sm:mt-8">
                        <button 
                          type="submit" 
                          class="w-full btn-enhanced text-white py-4 px-6 rounded-xl font-semibold text-lg pulse-glow">
                            <i class="fas fa-save mr-2"></i>Redefinir Senha
                        </button>
                    </div>
                </form>

                <!-- Additional Links -->
                <div class="mt-6 sm:mt-8 text-center space-y-3 sm:space-y-4">
                    <div class="pt-3 sm:pt-4 border-t border-ghibli-sage/20">
                        <p class="text-ghibli-forest/70 text-sm">
                            <a href="../index.php" class="text-ghibli-coral hover:text-ghibli-forest hover:underline font-semibold transition">Voltar ao Login</a>
                        </p>
                    </div>
                </div>

                <!-- Message Display -->
                <?php if (isset($_GET['erro'])): ?>
                  <div class="error-message">
                    <p class="text-sm font-medium">
                      <?php 
                        $erros = [
                          'senhas_nao_coincide' => '❌ As senhas não coincidem. Tente novamente.',
                          'senha_fraca' => '❌ Senha muito curta. Use mínimo 6 caracteres.',
                          'campos_vazios' => '⚠️ Por favor, preenchã todos os campos.',
                          'email_invalido' => '❌ Email inválido.',
                          'nao_verificado' => '⚠️ Você deve verificar o código primeiro.',
                          'erro_servidor' => '🔴 Erro ao atualizar senha. Tente novamente.',
                          'sessao_expirada' => '⏰ Sessão expirada. Comece novamente.'
                        ];
                        echo $erros[$_GET['erro']] ?? '❌ Erro desconhecido.';
                      ?>
                    </p>
                  </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Validação do formulário
        document.getElementById('formRedefinirSenha').addEventListener('submit', function(e) {
            const novaSenha = document.getElementById('nova_senha').value;
            const confirmarSenha = document.getElementById('confirmar_senha').value;

            // Verificar tamanho mínimo
            if (novaSenha.length < 6) {
                e.preventDefault();
                alert('A senha deve ter no mínimo 6 caracteres');
                return;
            }

            // Verificar se as senhas coincidem
            if (novaSenha !== confirmarSenha) {
                e.preventDefault();
                alert('As senhas não coincidem. Por favor, tente novamente.');
                return;
            }

            // Loading state
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.classList.add('loading');
            submitButton.innerHTML = '<span><i class="fas fa-spinner fa-spin mr-2"></i>Redefinindo...</span>';

            // Validação adicional de força
            if (novaSenha === novaSenha.toLowerCase() || novaSenha === novaSenha.toUpperCase()) {
                const confirmar = confirm('⚠️ Aviso: Sua senha contém apenas letras de um caso (maiúscula ou minúscula). Para máxima segurança, considere usar maiúsculas, minúsculas e números. Deseja continuar?');
                if (!confirmar) {
                    e.preventDefault();
                    submitButton.classList.remove('loading');
                    submitButton.innerHTML = '<i class="fas fa-save mr-2"></i>Redefinir Senha';
                }
            }
        });

        // Enhanced input animations
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('magical-glow');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('magical-glow');
                });
            });

            // Add ripple effect to button
            const submitButtons = document.querySelectorAll('.btn-enhanced');
            submitButtons.forEach(submitButton => {
                submitButton.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.left = (e.offsetX - 10) + 'px';
                    ripple.style.top = (e.offsetY - 10) + 'px';
                    ripple.style.width = '20px';
                    ripple.style.height = '20px';
                    this.appendChild(ripple);
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });

            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Criar sparkles dinamicamente
            function createSparkle() {
                const sparkle = document.createElement('div');
                sparkle.className = 'sparkle';
                sparkle.style.top = Math.random() * 100 + '%';
                sparkle.style.left = Math.random() * 100 + '%';
                sparkle.style.animationDelay = Math.random() * 2 + 's';

                document.querySelector('.animate-slide-in-right').appendChild(sparkle);

                setTimeout(() => {
                    sparkle.remove();
                }, 2000);
            }

            // Criar sparkles periodicamente
            setInterval(createSparkle, 4000);
        });

        // Enhanced accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });

        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });

        // Handle orientation changes on mobile
        window.addEventListener('orientationchange', function() {
            // Small delay to allow the browser to update dimensions
            setTimeout(() => {
                const vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }, 100);
        });

        // Set initial viewport height variable
        (function() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        })();
    </script>
</body>

</html>
