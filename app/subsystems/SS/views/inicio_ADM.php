<?php
require_once('../controllers/controller_sessao/autenticar_sessao.php');
require_once('../controllers/controller_sessao/verificar_sessao.php');
verificarSessao(600);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SS - Salaberga Seleciona | ADM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../assets/theme/js/modal.js"></script>
    <script src="../assets/theme/js/sidebar.js"></script>
    <link rel="shortcut icon" href="../../SS/assets/images/logo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        primary: '#4CAF50',
                        secondary: '#FFB74D',
                        'red-theme': '#ef4444',
                        'blue-theme': '#3b82f6',
                        'green-theme': '#10b981',
                        'gray-theme': '#6b7280'
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'slide-in-right': 'slideInRight 0.3s ease-out',
                        'slide-out-right': 'slideOutRight 0.3s ease-in',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'fade-out': 'fadeOut 0.3s ease-in',
                    },
                    keyframes: {
                        slideInRight: {
                            '0%': { transform: 'translateX(100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        slideOutRight: {
                            '0%': { transform: 'translateX(0)', opacity: '1' },
                            '100%': { transform: 'translateX(100%)', opacity: '0' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        fadeOut: {
                            '0%': { opacity: '1' },
                            '100%': { opacity: '0' },
                        },
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --ceara-green: #008C45;
            --ceara-orange: #FFA500;
            --shadow-elegant: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #1f2937;
        }

        .mobile-warning {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 9999;
            overflow: hidden;
        }

        .mobile-warning-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .mobile-bg-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float-mobile 6s ease-in-out infinite;
        }

        .mobile-bg-1 {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .mobile-bg-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .mobile-bg-3 {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float-mobile {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
        }

        .mobile-content {
            text-align: center;
            color: white;
            max-width: 400px;
            width: 100%;
            z-index: 10;
            position: relative;
        }

        .mobile-logo {
            margin-bottom: 30px;
            animation: bounce-in 1s ease-out;
        }

        .mobile-logo-img {
            height: 60px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .mobile-404 {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            animation: slide-in-up 1s ease-out 0.2s both;
        }

        .mobile-404-number {
            font-size: 4rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .mobile-404-icon {
            margin: 0 20px;
            font-size: 3rem;
            color: #FFA500;
            animation: pulse-icon 2s infinite;
        }

        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .mobile-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            animation: slide-in-up 1s ease-out 0.4s both;
        }

        .mobile-subtitle {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: slide-in-up 1s ease-out 0.6s both;
        }

        .mobile-description {
            margin-bottom: 40px;
            animation: slide-in-up 1s ease-out 0.8s both;
        }

        .mobile-description p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .mobile-features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
        }

        .mobile-feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .mobile-feature i {
            font-size: 2rem;
            color: #FFA500;
            margin-bottom: 5px;
        }

        .mobile-feature span {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .mobile-cta {
            margin-bottom: 40px;
            animation: slide-in-up 1s ease-out 1s both;
        }

        .mobile-cta-text {
            font-size: 0.9rem;
            margin-bottom: 20px;
            opacity: 0.8;
            line-height: 1.5;
        }

        .mobile-cta-button {
            background: linear-gradient(135deg, #FFA500, #FF8C00);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3);
            display: inline-flex;
            align-items: center;
        }

        .mobile-cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 165, 0, 0.4);
        }

        .mobile-cta-button:active {
            transform: translateY(0);
        }

        .mobile-footer {
            animation: slide-in-up 1s ease-out 1.2s both;
        }

        .mobile-footer p {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes slide-in-up {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 480px) {
            .mobile-404-number {
                font-size: 3rem;
            }
            
            .mobile-404-icon {
                font-size: 2.5rem;
                margin: 0 15px;
            }
            
            .mobile-title {
                font-size: 1.5rem;
            }
            
            .mobile-subtitle {
                font-size: 1rem;
            }
            
            .mobile-features {
                gap: 20px;
            }
            
            .mobile-feature i {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .mobile-warning {
                display: flex;
            }
            .main-content {
                display: none;
            }
        }

        .header-main {
            background: white;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .course-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-elegant);
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, currentColor, transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .course-card:hover::before {
            opacity: 1;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
        }

        .icon-container {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .course-btn {
            border-radius: 10px;
            font-weight: 500;
            font-size: 13px;
            padding: 10px 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.025em;
        }

        .course-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .enfermagem { color: #ef4444; }
        .informatica { color: #3b82f6; }
        .administracao { color: #10b981; }
        .edificacoes { color: #6b7280; } 
        .sidebar {
            background: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            right: 0;
            width: 320px;
            height: 100vh;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.03), rgba(16, 185, 129, 0.03)); /* Reduzido de 0.1 para 0.03 */
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }

        .floating-element:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 80px;
            height: 80px;
            top: 25%;
            right: 15%;
            animation-delay: 3s;
        }

        .floating-element:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 25%;
            left: 20%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-15px) rotate(120deg); }
            66% { transform: translateY(8px) rotate(240deg); }
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 350px;
        }

        .toast {
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            max-width: 100%;
        }

        .toast-success {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            color: #065f46;
        }

        .toast-error {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }

        .toast-warning {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }

        .toast-info {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }
    </style>
</head>

<body class="main-content">

    <div class="toast-container" id="toastContainer"></div>

    <div class="mobile-warning">
        <div class="mobile-warning-content">
            <div class="mobile-bg-element mobile-bg-1"></div>
            <div class="mobile-bg-element mobile-bg-2"></div>
            <div class="mobile-bg-element mobile-bg-3"></div>
            
            <div class="mobile-content">
                <div class="mobile-logo">
                    <img src="../assets/images/LOGO_new.png" alt="Logo EEEP" class="mobile-logo-img">
                </div>
                
                <div class="mobile-404">
                    <span class="mobile-404-number">4</span>
                    <div class="mobile-404-icon">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <span class="mobile-404-number">4</span>
                </div>
                
                <h1 class="mobile-title">Acesso Restrito</h1>
                <h2 class="mobile-subtitle">Sistema Disponível Apenas no Computador</h2>
                
                <div class="mobile-description">
                    <p>Este é um sistema administrativo de seleção escolar que requer uma tela maior para funcionar adequadamente.</p>
                    <div class="mobile-features">
                        <div class="mobile-feature">
                            <i class="fas fa-laptop"></i>
                            <span>Computador</span>
                        </div>
                        <div class="mobile-feature">
                            <i class="fas fa-tablet-alt"></i>
                            <span>Tablet</span>
                        </div>
                    </div>
                </div>
                
                <div class="mobile-cta">
                    <p class="mobile-cta-text">Para acessar o sistema, utilize um dispositivo com tela de pelo menos 768px de largura.</p>
                    <button class="mobile-cta-button" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Tentar Novamente
                    </button>
                </div>
                
                <div class="mobile-footer">
                    <p>&copy; 2024 SEEPS - Sistema Salaberga</p>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['erro_usuario'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Usuario já cadastrado!', 'error');
            });
        </script>
    <?php } ?>

    <?php if (isset($_GET['certo_usuario'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Usuario cadastrado com sucesso!', 'success');
            });
        </script>
    <?php } ?>

    <?php if (isset($_GET['candidato_excluido_sucesso'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Candidato excluido com sucesso!', 'success');
            });
        </script>
    <?php } ?>

    <?php if (isset($_GET['candidato_erro'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('ERRO ao deletar!', 'error');
            });
        </script>
    <?php } ?>

    <?php if (isset($_GET['candidato_nao_existe'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Candidato não cadastrado!', 'warning');
            });
        </script>
    <?php } ?>

    <?php if (isset($_GET['usuario_excluido_sucesso'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Usuário excluido com sucesso!', 'success');
            });
        </script>
    <?php } ?>

    <?php if (isset($_GET['usuario_erro'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('ERRO ao deletar!', 'error');
            });
        </script>
    <?php } ?>

    <?php if (isset($_GET['usuario_nao_existe'])) { ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('Usuário não cadastrado!', 'warning');
            });
        </script>
    <?php } ?>

    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <header class="header-main">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <img
                        src="../assets/images/LOGO_new.png"
                        alt="Logo EEEP"
                        class="h-10 w-auto">
                </div>

                <button
                    id="menuButton"
                    class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-ceara-green text-white hover:bg-green-600 transition-all duration-200 shadow-sm"
                    onclick="openSidebar()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <span class="font-medium">Menu</span>
                </button>
            </div>
        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800">Menu Administrativo</h3>
                <button
                    onclick="closeSidebar()"
                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="space-y-3">
                <a href="../controllers/autentica.php?sair" 
                   class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sair do Sistema
                </a>

                <button onclick="openInsertUserModal();" 
                        class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Inserir Usuário
                </button>

                <button onclick="showatualizModal(); toggleOverlay()" 
                        class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-orange-200 text-orange-600 hover:bg-orange-50 hover:border-orange-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Atualizar Sistema
                </button>

                <button onclick="showReportsModal(); toggleOverlay()" 
                        class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-green-200 text-green-600 hover:bg-green-50 hover:border-green-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Relatórios
                </button>

                <button onclick="showResultsModal(); toggleOverlay()" 
                        class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-purple-200 text-purple-600 hover:bg-purple-50 hover:border-purple-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Resultados
                </button>

                <button onclick="showDeleteConfirmationModal(); toggleOverlay()" 
                        class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Limpar Banco de Dados
                </button>

                <button onclick="showExcluirCandidatoModal(); toggleOverlay()" 
                        class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Excluir Candidato
                </button>

                <button onclick="window.location.href='../views/Excluir_usuario.php'" 
                        class="flex items-center w-full px-4 py-3 text-sm rounded-lg border border-pink-200 text-pink-600 hover:bg-pink-50 hover:border-pink-300 transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6z" />
                    </svg>
                    Excluir Usuário
                </button>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 py-12 relative z-10" style="margin-top: 90px">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                Sistema de Seleção
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto font-light">
                Escolha o curso e modalidade de ensino para iniciar o processo seletivo
            </p>
            <div class="mt-6 w-16 h-0.5 bg-gradient-to-r from-ceara-green to-ceara-orange mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
            <div class="course-card enfermagem p-8">
                <div class="flex flex-col items-center">
                    <div class="icon-container bg-red-theme text-white">
                        <i class="fas fa-heartbeat text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Enfermagem</h3>
                    <p class="text-sm text-gray-500 mb-6 text-center">Cuidado e dedicação à saúde</p>
                    <div class="w-full space-y-3">
                        <button onclick="enfermagemPub()" class="course-btn w-full bg-red-theme text-white hover:bg-red-600">
                            <i class="fas fa-school mr-2 text-xs"></i>
                            ESCOLA PÚBLICA
                        </button>
                        <button onclick="enfermagemPriv()" class="course-btn w-full bg-red-theme text-white hover:bg-red-600">
                            <i class="fas fa-building mr-2 text-xs"></i>
                            ESCOLA PRIVADA
                        </button>
                    </div>
                </div>
            </div>

            <div class="course-card informatica p-8">
                <div class="flex flex-col items-center">
                    <div class="icon-container bg-blue-theme text-white">
                        <i class="fas fa-laptop-code text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Informática</h3>
                    <p class="text-sm text-gray-500 mb-6 text-center">Tecnologia e inovação</p>
                    <div class="w-full space-y-3">
                        <button onclick="informaticaPub()" class="course-btn w-full bg-blue-theme text-white hover:bg-blue-600">
                            <i class="fas fa-school mr-2 text-xs"></i>
                            ESCOLA PÚBLICA
                        </button>
                        <button onclick="informaticaPriv()" class="course-btn w-full bg-blue-theme text-white hover:bg-blue-600">
                            <i class="fas fa-building mr-2 text-xs"></i>
                            ESCOLA PRIVADA
                        </button>
                    </div>
                </div>
            </div>

            <div class="course-card administracao p-8">
                <div class="flex flex-col items-center">
                    <div class="icon-container bg-green-theme text-white">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Administração</h3>
                    <p class="text-sm text-gray-500 mb-6 text-center">Gestão e liderança</p>
                    <div class="w-full space-y-3">
                        <button onclick="administracaoPub()" class="course-btn w-full bg-green-theme text-white hover:bg-green-600">
                            <i class="fas fa-school mr-2 text-xs"></i>
                            ESCOLA PÚBLICA
                        </button>
                        <button onclick="administracaoPriv()" class="course-btn w-full bg-green-theme text-white hover:bg-green-600">
                            <i class="fas fa-building mr-2 text-xs"></i>
                            ESCOLA PRIVADA
                        </button>
                    </div>
                </div>
            </div>

            <div class="course-card edificacoes p-8">
                <div class="flex flex-col items-center">
                    <div class="icon-container bg-gray-theme text-white">
                        <i class="fas fa-hard-hat text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Edificações</h3>
                    <p class="text-sm text-gray-500 mb-6 text-center">Construção e arquitetura</p>
                    <div class="w-full space-y-3">
                        <button onclick="edificacoesPub()" class="course-btn w-full bg-gray-theme text-white hover:bg-gray-600">
                            <i class="fas fa-school mr-2 text-xs"></i>
                            ESCOLA PÚBLICA
                        </button>
                        <button onclick="edificacoesPriv()" class="course-btn w-full bg-gray-theme text-white hover:bg-gray-600">
                            <i class="fas fa-building mr-2 text-xs"></i>
                            ESCOLA PRIVADA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <footer class="bg-gradient-to-br from-ceara-green via-green-600 to-green-800 text-white w-full mt-auto relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-y-1"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-ceara-orange/10 rounded-full blur-3xl"></div>
        <div class="absolute top-0 left-0 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-ceara-orange rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-ceara-orange text-xl font-bold">SEEPS</h4>
                        <p class="text-xs text-gray-200">Sistema Educacional</p>
                    </div>
                </div>
                <p class="text-sm text-gray-100 leading-relaxed">
                    Sistema de Ensino e Educação Profissional Salaberga - Transformando vidas através da educação de qualidade.
                </p>
                
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/groups/salaberga/" target="_blank" 
                       class="group w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="fab fa-facebook text-lg group-hover:text-white"></i>
                    </a>
                    <a href="https://www.instagram.com/eeepsalabergampe/" target="_blank"
                       class="group w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-500 hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="fab fa-instagram text-lg group-hover:text-white"></i>
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone text-ceara-orange"></i>
                    </div>
                    <h4 class="text-ceara-orange text-lg font-semibold">CONTATO</h4>
                </div>
                
                <div class="space-y-4">
                    <a href="mailto:eeepsantaritama@gmail.com" 
                       class="group flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                        <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-ceara-orange group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-envelope text-ceara-orange group-hover:text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-300">Email</p>
                            <p class="text-sm font-medium group-hover:text-ceara-orange transition-colors">eeepsantaritama@gmail.com</p>
                        </div>
                    </a>
                    
                    <a href="tel:+558531012100" 
                       class="group flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                        <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-ceara-orange group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-phone-alt text-ceara-orange group-hover:text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-300">Telefone</p>
                            <p class="text-sm font-medium group-hover:text-ceara-orange transition-colors">(85) 3101-2100</p>
                        </div>
                    </a>

                    <div class="flex items-center p-3 bg-white/5 rounded-lg">
                        <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-map-marker-alt text-ceara-orange text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-300">Localização</p>
                            <p class="text-sm font-medium">Maranguape, CE</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-link text-ceara-orange"></i>
                    </div>
                    <h4 class="text-ceara-orange text-lg font-semibold">LINKS RÁPIDOS</h4>
                </div>
                
                <div class="space-y-2">
                    <a href="#" class="block text-sm hover:text-ceara-orange transition-colors py-1 hover:translate-x-2 transform duration-200">
                        <i class="fas fa-chevron-right text-xs mr-2 text-ceara-orange"></i>
                        Sobre o Sistema
                    </a>
                    <a href="#" class="block text-sm hover:text-ceara-orange transition-colors py-1 hover:translate-x-2 transform duration-200">
                        <i class="fas fa-chevron-right text-xs mr-2 text-ceara-orange"></i>
                        Como se Inscrever
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-code text-ceara-orange"></i>
                    </div>
                    <h4 class="text-ceara-orange text-lg font-semibold">DESENVOLVEDORES</h4>
                </div>
                
                <div class="space-y-3">
                    <div class="group">
                        <p class="text-xs text-gray-300 mb-2">Equipe de Desenvolvimento</p>
                        <div class="space-y-2">
                            <a href="https://www.instagram.com/otavio.ce/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-instagram text-ceara-orange mr-2 text-xs"></i>
                                Otavio Menezes
                            </a>
                            <a href="https://www.linkedin.com/in/matheus-felix-74489329a/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-linkedin text-ceara-orange mr-2 text-xs"></i>
                                Matheus Felix
                            </a>
                            <a href="https://www.linkedin.com/in/lavosier-nascimento-4b124a2b8/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-linkedin text-ceara-orange mr-2 text-xs"></i>
                                Lavosier Nascimento
                            </a>
                            <a href="https://www.linkedin.com/in/roger-cavalcante/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-linkedin text-ceara-orange mr-2 text-xs"></i>
                                Roger Cavalcante
                            </a>
                            <a href="https://www.linkedin.com/in/pedro-uch%C3%B4a-de-abreu-67723429a/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-linkedin text-ceara-orange mr-2 text-xs"></i>
                                Pedro Uchôa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/20 pt-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <p class="text-sm text-gray-200">
                        &copy; 2024 SEEPS - Todos os direitos reservados
                    </p>
                    <div class="hidden md:flex items-center space-x-4 text-xs text-gray-300">
                        <a href="#" class="hover:text-ceara-orange transition-colors">Política de Privacidade</a>
                        <span>•</span>
                        <a href="#" class="hover:text-ceara-orange transition-colors">Termos de Uso</a>
                        <span>•</span>
                        <a href="#" class="hover:text-ceara-orange transition-colors">Cookies</a>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <button onclick="scrollToTop()" 
                            class="w-10 h-10 bg-ceara-orange hover:bg-orange-600 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-lg">
                        <i class="fas fa-chevron-up text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</footer>


    <script>
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            toast.className = 'toast animate-slide-in-right flex items-center';
            
            switch(type) {
                case 'success':
                    toast.classList.add('toast-success');
                    icon = '<i class="fas fa-check-circle mr-3 text-green-500"></i>';
                    break;
                case 'error':
                    toast.classList.add('toast-error');
                    icon = '<i class="fas fa-exclamation-circle mr-3 text-red-500"></i>';
                    break;
                case 'warning':
                    toast.classList.add('toast-warning');
                    icon = '<i class="fas fa-exclamation-triangle mr-3 text-amber-500"></i>';
                    break;
                default:
                    toast.classList.add('toast-info');
                    icon = '<i class="fas fa-info-circle mr-3 text-blue-500"></i>';
            }
            
            toast.innerHTML = `
                <div class="flex items-center">
                    ${icon}
                    <span>${message}</span>
                </div>
                <button class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none" onclick="closeToast(this.parentElement)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                closeToast(toast);
            }, 5000);
        }
        
        function closeToast(toast) {
            toast.classList.remove('animate-slide-in-right');
            toast.classList.add('animate-slide-out-right');
            
            setTimeout(() => {
                toast.remove();
            }, 300);
        }

        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.add('open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        document.querySelectorAll('.course-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Carregando...';
                this.disabled = true;
                
                showToast('Processando sua seleção...', 'info');
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                    
                    showToast('Seleção realizada com sucesso!', 'success');
                }, 1500);
            });
        });

        function testToasts() {
            showToast('Esta é uma mensagem de informação', 'info');
            setTimeout(() => {
                showToast('Esta é uma mensagem de sucesso', 'success');
            }, 1000);
            setTimeout(() => {
                showToast('Esta é uma mensagem de aviso', 'warning');
            }, 2000);
            setTimeout(() => {
                showToast('Esta é uma mensagem de erro', 'error');
            }, 3000);
        }
        
        //document.addEventListener('DOMContentLoaded', testToasts);
    </script>

</body>
</html>