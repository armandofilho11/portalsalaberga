<?php
require_once('../models/select_model.php');
require_once('../models/sessions.php');
$select_model = new select_model();
$session = new sessions;
$session->autenticar_session();

if (isset($_POST['layout'])) {
    $session->quebra_session();
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a1a1a">
    <meta name="description" content="Vagas - Sistema de Estágio">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <title>Vagas - Sistema de Estágio</title>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#007A33',
                            '50': '#00FF6B',
                            '100': '#00EB61',
                            '200': '#00C250',
                            '300': '#00993F',
                            '400': '#00802F',
                            '500': '#007A33',
                            '600': '#00661F',
                            '700': '#00521A',
                            '800': '#003D15',
                            '900': '#002910'
                        },
                        secondary: {
                            DEFAULT: '#FFA500',
                            '50': '#FFE9C0',
                            '100': '#FFE1AB',
                            '200': '#FFD183',
                            '300': '#FFC15A',
                            '400': '#FFB232',
                            '500': '#FFA500',
                            '600': '#C78000',
                            '700': '#8F5C00',
                            '800': '#573800',
                            '900': '#1F1400'
                        },
                        dark: {
                            DEFAULT: '#1a1a1a',
                            '50': '#2d2d2d',
                            '100': '#272727',
                            '200': '#232323',
                            '300': '#1f1f1f',
                            '400': '#1a1a1a',
                            '500': '#171717',
                            '600': '#141414',
                            '700': '#111111',
                            '800': '#0e0e0e',
                            '900': '#0a0a0a'
                        }
                    },
                    animation: {
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.36)',
                        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px rgba(0, 0, 0, 0.2)'
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #1a1a1a;
            color: #ffffff;
            min-height: 100vh;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(0, 122, 51, 0.05) 0%, rgba(0, 122, 51, 0) 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 165, 0, 0.05) 0%, rgba(255, 165, 0, 0) 20%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cpath fill='%23007A33' fill-opacity='0.03' d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z'%3E%3C/path%3E%3C/svg%3E");
            transition: all 0.3s ease;
        }

        /* Sidebar styling */
        .sidebar {
            background-color: rgba(45, 45, 45, 0.95);
            background-image: linear-gradient(to bottom, #2d2d2d, #222222);
            border-right: 1px solid rgba(0, 122, 51, 0.2);
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            color: #ffffff;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 122, 51, 0.2);
            color: #00C250;
            transform: translateX(5px);
        }

        .sidebar-link.active {
            background-color: rgba(0, 122, 51, 0.3);
            color: #00FF6B;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 122, 51, 0.15);
        }

        /* Vaga card styling */
        .vaga-card {
            background: linear-gradient(135deg, rgba(49, 49, 49, 0.95) 0%, rgba(37, 37, 37, 0.95) 100%);
            border-radius: 16px;
            padding: 1.75rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            min-height: 350px;
            display: flex;
            flex-direction: column;
        }

        .vaga-card.single-link {
            min-height: 320px;
        }

        .vaga-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, #00FF6B, #007A33);
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .vaga-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 122, 51, 0.3);
        }

        .vaga-card:hover::before {
            opacity: 1;
            box-shadow: 0 0 15px rgba(0, 255, 107, 0.4);
        }

        .vaga-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .vaga-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(90deg, #ffffff, #e0e0e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .vaga-card:hover .vaga-card-title {
            background: linear-gradient(90deg, #ffffff, #00FF6B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .vaga-card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .vaga-card-action {
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .vaga-card-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-grow: 1; /* Ensure info section takes available space */
        }

        .vaga-card-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #d1d5db;
            padding: 0.5rem;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.03);
            transition: all 0.3s ease;
        }

        .vaga-card-info-item i {
            color: #007A33;
            transition: all 0.3s ease;
        }

        .vaga-card:hover .vaga-card-info-item i {
            color: #00FF6B;
        }

        /* Area chips styling */
        .area-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .area-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .area-desenvolvimento {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(29, 78, 216, 0.2) 100%);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .area-desenvolvimento:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(29, 78, 216, 0.3) 100%);
        }

        .area-design {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2) 0%, rgba(126, 34, 206, 0.2) 100%);
            color: #c4b5fd; /* Cor original para Design */
            border: 1px solid rgba(168, 85, 247, 0.3);
        }

        .area-design:hover {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.3) 0%, rgba(126, 34, 206, 0.3) 100%);
        }

        .area-midia {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .area-midia:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.3) 0%, rgba(5, 150, 105, 0.3) 100%);
        }

        .area-redes {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .area-redes:hover {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.3) 0%, rgba(217, 119, 6, 0.3) 100%);
        }

        .area-tutoria {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%); /* Usando estilo de midia para tutoria */
            color: #6ee7b7; /* Usando estilo de midia para tutoria */
            border: none; /* Removendo a borda roxa */
        }

        .area-tutoria:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.3) 0%, rgba(5, 150, 105, 0.3) 100%); /* Usando estilo de midia para tutoria */
        }

        /* Ver detalhes link styling */
        .ver-detalhes-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(0, 122, 51, 0.1);
            border-radius: 6px;
            color: #00C250;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .ver-detalhes-link:hover {
            background: rgba(0, 122, 51, 0.2);
            transform: translateX(3px);
        }

        .ver-detalhes-link i {
            transition: transform 0.3s ease;
        }

        .ver-detalhes-link:hover i {
            transform: translateX(3px);
        }

        /* Custom input styling */
        .custom-input {
            background-color: rgba(35, 35, 35, 0.8) !important;
            border: 2px solid rgba(61, 61, 61, 0.8) !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            padding: 0.75rem 2.5rem 0.75rem 1rem !important;
            width: 100% !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(5px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            min-width: 180px !important;
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
        }

        select.custom-input {
            min-width: 180px !important;
            max-width: 100% !important;
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
        }

        .relative select.custom-input {
            padding-right: 2.5rem !important;
        }

        .relative {
            min-width: 180px;
        }

        @media (max-width: 640px) {

            .custom-input,
            select.custom-input {
                min-width: 100% !important;
                font-size: 1rem !important;
            }

            .relative {
                min-width: 100%;
            }
        }

        .custom-input:focus {
            border-color: #00C250 !important;
            box-shadow: 0 0 0 2px rgba(0, 194, 80, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            outline: none !important;
            background-color: rgba(40, 40, 40, 0.9) !important;
        }

        /* Search input styling */
        .search-input-container {
            position: relative;
            transition: all 0.3s ease;
        }

        .search-input-container:focus-within {
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .search-input-container:focus-within .search-icon {
            color: #00C250;
        }

        /* Custom button styling */
        .custom-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .custom-btn-primary {
            background: linear-gradient(135deg, #007A33 0%, #009940 100%);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 122, 51, 0.3);
        }

        .custom-btn-primary:hover {
            background: linear-gradient(135deg, #00993F 0%, #00B64B 100%);
            transform: translateY(-3px);
            box-shadow: 0 7px 15px rgba(0, 122, 51, 0.4);
        }

        .custom-btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .custom-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .custom-btn:hover::before {
            transform: translateX(100%);
        }

        .btn-icon {
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .custom-btn:hover .btn-icon {
            transform: translateX(3px);
            opacity: 1;
        }

        /* Action bar styling */
        .action-bar {
            background-color: rgba(45, 45, 45, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Modal styling */
        .candidatura-modal {
            background: linear-gradient(135deg, rgba(49, 49, 49, 0.95) 0%, rgba(37, 37, 37, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Checkbox styling */
        .custom-checkbox {
            appearance: none;
            width: 1.2rem;
            height: 1.2rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            background-color: transparent;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .custom-checkbox:checked {
            border-color: #007A33;
            background-color: #007A33;
            box-shadow: 0 0 0 2px rgba(0, 122, 51, 0.2);
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            width: 0.3rem;
            height: 0.6rem;
            border: solid white;
            border-width: 0 2px 2px 0;
            animation: scaleIn 0.15s ease-in-out;
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

        /* Animations */
        @keyframes scaleIn {
            0% {
                transform: translate(-50%, -50%) scale(0);
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.4s ease-out forwards;
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .vaga-card {
                padding: 1rem;
            }

            .custom-input {
                min-width: 100% !important;
            }

            .mobile-stack {
                flex-direction: column;
            }
        }
    </style>
</head>

<body class="select-none">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="sidebar w-64 hidden md:block">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center gap-2 mb-6">
                    <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                    <div>
                        <h1 class="text-lg font-bold text-primary-400">Sistema <span class="text-secondary">STGM</span></h1>
                        <div class="h-0.5 bg-primary-500/20 rounded-full mt-1"></div>
                    </div>
                </div>
                <nav class="flex-1">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link active">
                        <i class="fas fa-briefcase w-5 mr-3"></i>
                        Vagas
                    </a>
                    <a href="selecionados.php" class="sidebar-link">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link">
                        <i class="fa fa-user-circle w-5 mr-3"></i>
                        Resultados 
                    </a>
                    
                </nav>
                <div class="mt-auto pt-4 border-t border-gray-700">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        Configurações
                    </a>
                    <form action="" method="post">
                        <button type="submit" name="layout" class="sidebar-link text-red-400 hover:text-red-300">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <div class="md:hidden fixed top-4 left-4 z-50">
            <button id="sidebarToggle" class="bg-dark-50 p-2 rounded-lg shadow-md hover:bg-dark-100 transition-all">
                <i class="fas fa-bars text-primary-400"></i>
            </button>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden sidebar">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                        <h1 class="text-lg font-bold text-primary-400">Sistema <span class="text-secondary">STGM</span></h1>
                    </div>
                    <button id="closeSidebar" class="p-2 text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="flex-1">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link active">
                        <i class="fas fa-briefcase w-5 mr-3"></i>
                        Vagas
                    </a>
                    <a href="selecionados.php" class="sidebar-link">
                        <i class="fas fa-check-circle w-5 mr-3"></i>
                        Selecionados
                    </a>
                    <a href="gerenciar_alunos.php" class="sidebar-link">
                        <i class="fas fa-user-graduate w-5 mr-3"></i>
                        Gerenciar Alunos
                    </a>
                    <a href="resultado_selecionados.php" class="sidebar-link">
                        <i class="fa fa-user-circle w-5 mr-3"></i>
                        Resultados 
                    </a>
                    
                </nav>
                <div class="mt-auto pt-4 border-t border-gray-700">
                    <a href="#" class="sidebar-link">
                        <i class="fas fa-cog w-5 mr-3"></i>
                        Configurações
                    </a>
                    <form action="" method="post">
                        <button type="submit" name="layout" class="sidebar-link text-red-400 hover:text-red-300">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Conteúdo principal -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-dark-400">
            <!-- Header -->
            <header class="bg-dark-50 shadow-md sticky top-0 z-30 border-b border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center justify-between w-full">
                        <h1 class="text-xl font-bold text-white text-center sm:text-left w-full">Vagas Disponíveis</h1>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">
                                <i class="fas fa-user-circle mr-1"></i> Admin
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                <!-- Breadcrumbs -->
                <div class="text-sm text-gray-400 mb-6 flex items-center">
                    <a href="./dashboard.php" class="hover:text-primary-400 transition-colors">Dashboard</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="text-white">Vagas</span>
                </div>

                <!-- Actions Bar -->
                <div class="mb-8 action-bar p-4 sm:p-5 fade-in">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full">
                            <button id="addVagaBtn" class="custom-btn custom-btn-primary w-full sm:w-auto">
                                <i class="fas fa-plus btn-icon"></i>
                                <span>Nova Vaga</span>
                            </button>
                            <button id="relatorioVagasBtn" class="custom-btn custom-btn-secondary w-full sm:w-auto">
                                <i class="fas fa-file-pdf btn-icon"></i>
                                <span>Relatório de seleção</span>
                            </button>
                            <button id="gerarRelatorioBtn" class="custom-btn custom-btn-secondary w-full sm:w-auto">
                                <i class="fas fa-file-pdf btn-icon"></i>
                                <span>Resumo de vagas </span>
                            </button>
                            <script>
                                console.log('Tentando encontrar o botão gerarRelatorioBtn para adicionar listener...');
                                const resumoBtn = document.getElementById('gerarRelatorioBtn');
                                if (resumoBtn) {
                                    console.log('Botão gerarRelatorioBtn encontrado. Adicionando listener.');
                                    resumoBtn.addEventListener('click', () => {
                                        console.log('Botão Gerar Resumo clicado (via script isolado). Abrindo relatório...');
                                        window.open('./relatorio/relatorio_resumo_vagas.php', '_blank');
                                    });
                                } else {
                                    console.error('Erro: Botão gerarRelatorioBtn NÃO encontrado no script isolado.');
                                }
                            </script>
                        </div>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full sm:w-auto">
                            <div class="relative">
                                
                                <select id="filterArea" class="custom-input pl-4 pr-10 py-2.5 appearance-none w-full">
                                    <option value="">Todas as áreas</option>
                                    <?php
                                    // Mapeamento de ID para valor do option
                                    $perfil_options = [
                                        '1' => 'desenvolvimento',
                                        '2' => 'design/mídia',
                                        '4' => 'tutoria',
                                        '3' => 'suporte/redes'
                                    ];
                                    $perfil_selecionado_id = isset($_GET['perfil']) ? $_GET['perfil'] : '';
                                    $perfil_selecionado_value = '';
                                    if (!empty($perfil_selecionado_id) && isset($perfil_options[$perfil_selecionado_id])) {
                                        $perfil_selecionado_value = $perfil_options[$perfil_selecionado_id];
                                    }

                                    // Opções de área com seus valores
                                    $areas = [
                                        'desenvolvimento' => 'Desenvolvimento',
                                        'design/mídia' => 'Desing/Mídia',
                                        'tutoria' => 'Tutoria',
                                        'suporte/redes' => 'Suporte/Redes'
                                    ];

                                    foreach ($areas as $value => $text) {
                                        $selected = ($value === $perfil_selecionado_value) ? 'selected' : '';
                                        echo "<option value='{$value}' {$selected}>" . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                    ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                            <div class="relative">
                                <select id="filterEmpresa" class="custom-input pl-4 pr-10 py-2.5 appearance-none w-full">
                                    <option value="">Todas as empresas</option>
                                    <?php
                                    $empresas = $select_model->concedentes();
                                    $empresa_selecionada = isset($_GET['empresa']) ? $_GET['empresa'] : '';
                                    foreach ($empresas as $empresa) {
                                        $selected = ($empresa['id'] == $empresa_selecionada) ? 'selected' : '';
                                        echo "<option value='{$empresa['id']}' {$selected}>" . htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8') . "</option>";
                                    }
                                    ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>  
                        </div>
                    </div>
                </div>

               
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" id="vagasGrid">
                    <?php
                    $empresa_filtro = isset($_GET['empresa']) ? $_GET['empresa'] : '';
                    // Adicionar lógica para ler o parâmetro GET 'perfil' aqui
                    $perfil_filtro = isset($_GET['perfil']) ? $_GET['perfil'] : '';

                    // Modificar a chamada a $select_model->vagas para incluir o filtro de perfil
                    $dados = $select_model->vagas('', $perfil_filtro, $empresa_filtro); // Ajustado para usar $perfil_filtro também
                    if (empty($dados)): ?>
                        <div class="col-span-3 text-center py-16 text-gray-400 fade-in">
                            <i class="fas fa-briefcase text-5xl mb-4 text-gray-600 opacity-30"></i>
                            <p class="text-xl">Nenhuma vaga cadastrada no momento.</p>
                            <button id="firstVagaBtn" class="mt-6 custom-btn custom-btn-primary">
                                <i class="fas fa-plus btn-icon"></i>
                                <span>Cadastrar Primeira Vaga</span>
                            </button>
                        </div>
                    <?php else: ?>
                        <?php
                        $delay = 0;
                        foreach ($dados as $dado):
                            $delay += 100;
                            $nomePerfil = isset($dado['nome_perfil']) ? htmlspecialchars($dado['nome_perfil'], ENT_QUOTES, 'UTF-8') : 'Área não informada';
                            $area = strtolower($nomePerfil);
                            if ($area === 'design/social mídia' || $area === 'design/mídia' || $area === 'design/mídias' || $area === 'design') { // Considerar todas as variações
                                $area = 'design';
                            } elseif ($nomePerfil === 'Suporte/Redes') {
                                $area = 'redes';
                            } elseif ($nomePerfil === 'Tutoria') { // Adicionar condição explícita para Tutoria
                                $area = 'tutoria';
                            } elseif (empty($area) || $area === 'área não informada') {
                                $area = 'desenvolvimento'; // Cor padrão se a área não for informada
                            }
                            $vagaId = isset($dado['id']) ? htmlspecialchars($dado['id'], ENT_QUOTES, 'UTF-8') : '';
                            $empresaName = isset($dado['nome_empresa']) ? htmlspecialchars($dado['nome_empresa'], ENT_QUOTES, 'UTF-8') : 'Não informado';
                            $quant_vaga = isset($dado['quant_vaga']) ? htmlspecialchars($dado['quant_vaga'], ENT_QUOTES, 'UTF-8') : '0';
                            $quant_cand = isset($dado['quant_cand']) ? htmlspecialchars($dado['quant_cand'], ENT_QUOTES, 'UTF-8') : '0';
                            $nomePerfil = isset($dado['nome_perfil']) ? htmlspecialchars($dado['nome_perfil'], ENT_QUOTES, 'UTF-8') : 'Área não informada';
                            $empresaId = isset($dado['id_empresa']) ? htmlspecialchars($dado['id_empresa'], ENT_QUOTES, 'UTF-8') : '';
                            $data = isset($dado['data']) ? htmlspecialchars($dado['data'], ENT_QUOTES, 'UTF-8') : '';
                            $hora = isset($dado['hora']) ? htmlspecialchars($dado['hora'], ENT_QUOTES, 'UTF-8') : '';
                            $tipoVaga = isset($dado['tipo_vaga']) ? htmlspecialchars($dado['tipo_vaga'], ENT_QUOTES, 'UTF-8') : '';
                            $hasAlunos = !empty($select_model->alunos_selecionados_estagio($dado['id']));
                            $empresa = $select_model->concedente_por_id($empresaId);
                            $empresaContato = $empresa['contato']; // Obter o contato da tabela concedentes
                            $empresaContatoLink = $empresaContato ? 'https://wa.me/55' . preg_replace('/\D/', '', $empresaContato) : '#';
                            ?>
                            <div class="vaga-card slide-up<?php echo !$hasAlunos ? ' single-link' : ''; ?>"
                                style="animation-delay: <?php echo $delay; ?>ms;"
                                data-vaga-id="<?php echo $vagaId; ?>"
                                data-area="<?php echo htmlspecialchars($area, ENT_QUOTES, 'UTF-8'); ?>"
                                data-empresa-id="<?php echo $empresaId; ?>"
                                data-empresa-nome="<?php echo $empresaName; ?>"
                                data-quantidade="<?php echo $quant_vaga; ?>"
                                data-data="<?php echo $data; ?>"
                                data-hora="<?php echo $hora; ?>"
                                data-tipo-vaga="<?php echo $tipoVaga; ?>">
                                <div class="vaga-card-header">
                                    <h3 class="vaga-card-title">
                                        <?php echo $empresaName ?>
                                    </h3>
                                    <div class="vaga-card-actions">
                                        <button class="vaga-card-action text-gray-400 hover:text-primary-400 edit-btn" data-modal-id="editarVagaModal-<?php echo $vagaId; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="vaga-card-action text-red-500 hover:text-red-400 delete-btn" data-modal-id="excluirVagaModal-<?php echo $vagaId; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="area-chip area-<?php echo htmlspecialchars($area, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php if ($area === 'desenvolvimento'): ?>
                                            <i class="fas fa-code mr-1 text-xs"></i>
                                        <?php elseif ($area === 'tutoria'): ?>
                                            <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                        <?php elseif ($area === 'design'): ?>
                                            <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                        <?php elseif ($area === 'redes'): ?>
                                            <i class="fas fa-network-wired mr-1 text-xs"></i>
                                        <?php else: ?>
                                            <i class="fas fa-question mr-1 text-xs"></i>
                                        <?php endif; ?>
                                        <?php
                                        // Ajustar exibição do nome do perfil para Design/Mídia
                                        if ($nomePerfil === 'Design/Social mídia') {
                                            echo 'Design/Mídia';
                                        } else {
                                            echo $nomePerfil;
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="vaga-card-info">
                                    <div class="vaga-card-info-item">
                                        <i class="fas fa-building w-5"></i>
                                        <span><?php echo $empresaName; ?></span>
                                    </div>
                                    <div class="vaga-card-info-item">
                                        <i class="fas fa-users w-5"></i>
                                        <span><?php echo $quant_vaga; ?> vaga(s) disponível(is)</span>
                                    </div>
                                    <div class="vaga-card-info-item">
                                        <i class="fas fa-users w-5"></i>
                                        <span><?php echo $quant_cand; ?> candidatos</span>
                                    </div>
                                    <div class="vaga-card-info-item">
                                        <i class="fas fa-calendar w-5"></i>
                                        <span><?= $dado['data'] ?></span>
                                        <span class="mx-2">|</span>
                                        <span><?= $dado['hora'] ?></span>
                                    </div>
                                    <div class="vaga-card-info-item">
                                        <i class="fas fa-briefcase w-5"></i>
                                        <span><?= $dado['tipo_vaga'] ?></span>
                                    </div>
                                    <div class="vaga-card-info-item">
                                        <i class="fas fa-user-graduate w-5"></i>
                                        <div class="flex flex-col gap-1">
                                            <?php
                                            $id_vaga = $dado['id'];
                                            $alunos = $select_model->alunos_selecionados_estagio($id_vaga);
                                            $hasAlunos = !empty($alunos);
                                            if (empty($alunos)): ?>
                                                <span class="text-gray-500">Nenhum aluno selecionado</span>
                                            <?php else: ?>
                                                <?php foreach ($alunos as $aluno): ?>
                                                    <span class="text-sm text-gray-300">
                                                        <?= htmlspecialchars($aluno['nome']) ?>
                                                        <?php $alunoContato = $aluno['contato']; // Supondo que o contato do aluno está disponível ?>
                                                        <?php $alunoContatoLink = $alunoContato ? 'https://wa.me/55' . preg_replace('/\D/', '', $alunoContato) : '#'; ?>
                                                        <a href="<?php echo $alunoContatoLink; ?>" target="_blank">
                                                            <i class="fab fa-whatsapp text-green-500 ml-2"></i>
                                                        </a>
                                                        <?php if (!$alunoContato): ?>
                                                            <span class="text-sm text-gray-500">(contato não informado)</span>
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-auto <?php echo $hasAlunos ? 'space-y-4' : ''; ?>">
                                    <div>
                                        <a href="./alunos_vaga.php?nome_perfil=<?= urlencode($dado['nome_perfil']) ?>&id_vaga=<?= $dado['id'] ?>&nome_empresa=<?= urlencode($dado['nome_empresa']) ?>&nome_baga=<?= $dado['id'] ?>" class="ver-detalhes-link">
                                            <span>Selecionar aluno</span>
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                    <?php if ($hasAlunos): ?>
                                    <div>
                                        <a href="#" class="ver-detalhes-link" onclick="abrirModal(event, <?= $vagaId ?>)">
                                            <span>Carta de Encaminhamento</span>
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Modal Carta de Encaminhamento -->
                            <div id="modalCarta-<?= $vagaId ?>" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                                <div class="candidatura-modal rounded-lg p-8 max-w-md w-full mx-4">
                                    <div class="text-center mb-6">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500/10 text-primary-400 mb-4">
                                            <i class="fas fa-file-alt text-2xl"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-white slide-up">Gerar Carta de Encaminhamento</h3>
                                        <p class="text-gray-400 mt-2">Preencha os dados para gerar a carta de encaminhamento.</p>
                                    </div>
                                    <form id="formCarta-<?= $vagaId ?>" onsubmit="gerarCarta(event, <?= $vagaId ?>)" class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Nome do Responsável</label>
                                            <input type="text" id="responsavel-<?= $vagaId ?>" name="responsavel" class="custom-input mt-1 w-full" required>
                                        </div>
                                        <div class="mt-8 flex justify-end space-x-4">
                                            <button type="button" class="custom-btn custom-btn-secondary" onclick="fecharModal(<?= $vagaId ?>)">
                                                <i class="fas fa-times btn-icon"></i>
                                                <span>Cancelar</span>
                                            </button>
                                            <button type="submit" class="custom-btn custom-btn-primary">
                                                <i class="fas fa-file-download btn-icon"></i>
                                                <span>Gerar Carta</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal de Editar Vaga -->
                            <div id="editarVagaModal-<?php echo $vagaId; ?>" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                                <div class="candidatura-modal rounded-lg p-8 max-w-md w-full mx-4">
                                    <h2 class="text-2xl font-bold mb-6 text-white slide-up">Editar Vaga</h2>
                                    <form action="../controllers/controller_editar_excluir.php" method="post" class="space-y-6">
                                        <input type="hidden" name="id_editar_vaga" value="<?php echo $vagaId; ?>">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Nome da Empresa</label>
                                            <select name="empresa_editar_vaga" class="custom-input mt-1 w-full" required>
                                                <option value="" disabled>Selecione uma empresa</option>
                                                <?php
                                                $empresas = $select_model->concedentes();
                                                foreach ($empresas as $empresa) {
                                                    $selected = $empresa['id'] == $empresaId ? 'selected' : '';
                                                    echo "<option value='{$empresa['id']}' $selected>" . htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8') . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Áreas de Atuação</label>
                                            <div class="mt-2 grid lg:grid-cols-2 gap-y-3 gap-x-6 items-center">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" class="custom-checkbox" name="perfil_editar_vaga" value="1" <?php echo $nomePerfil === 'Desenvolvimento' ? 'checked' : ''; ?> style="vertical-align: middle;">
                                                    <span class="ml-3 text-gray-300" style="vertical-align: middle;">Desenvolvimento</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" class="custom-checkbox" name="perfil_editar_vaga" value="2" <?php echo ($nomePerfil === 'Design/Social mídia' || $nomePerfil === 'Design/Mídia' || $nomePerfil === 'Design') ? 'checked' : ''; ?> style="vertical-align: middle;">
                                                    <span class="ml-3 text-gray-300 lg:whitespace-nowrap" style="vertical-align: middle;">Design/Mídia</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" class="custom-checkbox" name="perfil_editar_vaga" value="3" <?php echo $nomePerfil === 'Suporte/Redes' ? 'checked' : ''; ?> style="vertical-align: middle;">
                                                    <span class="ml-3 text-gray-300" style="vertical-align: middle;">Suporte/Redes</span>
                                                </label>
                                                <label class="inline-flex items-center lg:-mt-px">
                                                    <input type="radio" class="custom-checkbox" name="perfil_editar_vaga" value="4" <?php echo $nomePerfil === 'Tutoria' ? 'checked' : ''; ?> style="vertical-align: middle;">
                                                    <span class="ml-3 text-gray-300" style="vertical-align: middle; margin-top: -1px;">Tutoria</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Vagas Disponíveis</label>
                                            <input type="number" name="quantidade_editar_vaga" min="1" value="<?php echo $quant_vaga; ?>" class="custom-input mt-1 w-full" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Quantidade de Candidatos</label>
                                            <input type="number" name="quant_candidatos_editar_vaga" min="1" value="<?php echo isset($quant_cand) ? $quant_cand : ''; ?>" class="custom-input mt-1 w-full" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Vaga</label>
                                            <select name="tipo_editar_vaga" class="custom-input mt-1 w-full" required>
                                                <option value="" disabled>Selecione o tipo de vaga</option>
                                                <option value="Hibrido" <?php echo $tipoVaga === 'Hibrido' ? 'selected' : ''; ?>>Híbrido</option>
                                                <option value="Presencial" <?php echo $tipoVaga === 'Presencial' ? 'selected' : ''; ?>>Presencial</option>
                                                <option value="HomeOffice" <?php echo $tipoVaga === 'HomeOffice' ? 'selected' : ''; ?>>Home Office</option>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300 mb-2">Data</label>
                                                <input type="date" name="data_editar_vaga" value="<?php echo $data; ?>" class="custom-input mt-1 w-full">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300 mb-2">Hora</label>
                                                <input type="time" name="hora_editar_vaga" value="<?php echo $hora; ?>" class="custom-input mt-1 w-full">
                                            </div>
                                        </div>
                                        <div class="mt-8 flex justify-end space-x-4">
                                            <button type="button" class="custom-btn custom-btn-secondary close-btn" data-modal-id="editarVagaModal-<?php echo $vagaId; ?>">
                                                <i class="fas fa-times btn-icon"></i>
                                                <span>Cancelar</span>
                                            </button>
                                            <button type="submit" class="custom-btn custom-btn-primary">
                                                <i class="fas fa-save btn-icon"></i>
                                                <span>Salvar</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal de Excluir Vaga -->
                            <div id="excluirVagaModal-<?php echo $vagaId; ?>" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                                <div class="candidatura-modal rounded-lg p-6 max-w-md w-full mx-4">
                                    <div class="text-center mb-6">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/10 text-red-500 mb-4">
                                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-white slide-up">Confirmar Exclusão</h3>
                                        <p class="text-gray-400 mt-2">Tem certeza que deseja excluir esta vaga? Esta ação não pode ser desfeita.</p>
                                    </div>
                                    <form action="../controllers/controller_editar_excluir.php" method="post">
                                        <input type="hidden" name="id_excluir_vaga" value="<?php echo $vagaId; ?>">
                                        <div class="flex justify-center space-x-4">
                                            <button type="button" class="custom-btn custom-btn-secondary close-btn" data-modal-id="excluirVagaModal-<?php echo $vagaId; ?>">
                                                <i class="fas fa-times btn-icon"></i>
                                                <span>Cancelar</span>
                                            </button>
                                            <button type="submit" class="custom-btn bg-red-500 hover:bg-red-600 text-white">
                                                <i class="fas fa-trash-alt btn-icon"></i>
                                                <span>Excluir Vaga</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>

        <!-- Modal de Cadastro de Nova Vaga -->
        <div id="novaVagaModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
            <div class="candidatura-modal rounded-lg p-8 max-w-md w-full mx-4">
                <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-white slide-up">Nova Vaga</h2>
                <form action="../controllers/controller.php" method="post" id="vagaForm" class="space-y-6">
                    <input type="hidden" id="vagaId" name="vaga_id" value="">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nome da Empresa</label>
                        <select id="vagaEmpresa" name="empresa" class="custom-input mt-1 w-full" required>
                            <option value="" selected disabled>Selecione uma empresa</option>
                            <?php
                            $empresas = $select_model->concedentes();
                            foreach ($empresas as $empresa) {
                            ?>
                                <option value="<?= $empresa['id'] ?>"><?= $empresa['nome'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Áreas de Atuação</label>
                        <div class="mt-2 grid lg:grid-cols-2 gap-y-3 gap-x-6 items-center">
                            <label class="inline-flex items-center">
                                <input type="radio" class="custom-checkbox" name="areas" value="1" style="vertical-align: middle;">
                                <span class="ml-3 text-gray-300" style="vertical-align: middle;">Desenvolvimento</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="custom-checkbox" name="areas" value="2" style="vertical-align: middle;">
                                <span class="ml-3 text-gray-300 lg:whitespace-nowrap" style="vertical-align: middle;">Design/Mídia</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="custom-checkbox" name="areas" value="3" style="vertical-align: middle;">
                                <span class="ml-3 text-gray-300" style="vertical-align: middle;">Suporte/Redes</span>
                            </label>
                            <label class="inline-flex items-center lg:-mt-1">
                                <input type="radio" class="custom-checkbox" name="areas" value="4" style="vertical-align: middle;">
                                <span class="ml-3 text-gray-300" style="vertical-align: middle; margin-top: -1px;">Tutoria</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Quantidade de Vagas</label>
                        <input type="number" id="vagaVagasDisponiveis" name="quant_vagas" min="1" class="custom-input mt-1 w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Quantidade de Candidatos</label>
                        <input type="number" id="vagaVagasDisponiveis" name="quant_candidatos" min="1" class="custom-input mt-1 w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Vaga</label>
                        <select name="tipo_vaga" class="custom-input mt-1 w-full" required>
                            <option value="" selected disabled>Selecione o tipo de vaga</option>
                            <option value="Hibrido">Híbrido</option>
                            <option value="Presencial">Presencial</option>
                            <option value="HomeOffice">Home Office</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Data</label>
                            <input type="date" name="data" class="custom-input mt-1 w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Hora</label>
                            <input type="time" name="hora" class="custom-input mt-1 w-full">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="button" class="custom-btn custom-btn-secondary close-btn" data-modal-id="novaVagaModal">
                            <i class="fas fa-times btn-icon"></i>
                            <span>Cancelar</span>
                        </button>
                        <button type="submit" class="custom-btn custom-btn-primary">
                            <i class="fas fa-save btn-icon"></i>
                            <span>Salvar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de Relatório de Vagas -->
        <div id="relatorioVagasModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
            <div class="candidatura-modal rounded-lg p-8 max-w-md w-full mx-4">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500/10 text-primary-400 mb-4">
                        <i class="fas fa-file-pdf text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">Relatório de seleção</h3>
                    <p class="text-gray-400 mt-2">Selecione as opções para gerar o relatório.</p>
                </div>
                <form id="formRelatorio" action="./relatorio/gerar_relatorio_vagas.php" method="get" target="_blank" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Empresa</label>
                        <select name="empresa" class="custom-input mt-1 w-full">
                            <option value="">Todas as empresas</option>
                            <?php
                            $empresas = $select_model->concedentes();
                            $empresa_atual = isset($_GET['empresa']) ? $_GET['empresa'] : '';
                            foreach ($empresas as $empresa) {
                                $selected = ($empresa['id'] == $empresa_atual) ? 'selected' : '';
                                echo "<option value='{$empresa['id']}' {$selected}>" . htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Perfil</label>
                        <select name="perfil" class="custom-input mt-1 w-full">
                            <option value="">Todos os perfis</option>
                            <?php
                            $perfis = [
                                '1' => 'Desenvolvimento',
                                '2' => 'Design/Mídia',
                                '4' => 'Tutoria',
                                '3' => 'Suporte/Redes'
                            ];
                            $perfil_atual = isset($_GET['perfil']) ? $_GET['perfil'] : '';
                            foreach ($perfis as $id => $nome) {
                                $selected = ($id == $perfil_atual) ? 'selected' : '';
                                echo "<option value='{$id}' {$selected}>" . htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mt-8 flex justify-end space-x-4">
                        <button type="button" class="custom-btn custom-btn-secondary" onclick="fecharModalRelatorio()">
                            <i class="fas fa-times btn-icon"></i>
                            <span>Cancelar</span>
                        </button>
                        <button type="submit" class="custom-btn custom-btn-primary">
                            <i class="fas fa-file-download btn-icon"></i>
                            <span>Gerar Relatório</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function abrirModal(event, idVaga) {
            event.preventDefault();
            const modal = document.getElementById('modalCarta-' + idVaga);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Animar a entrada do modal
            const modalContent = modal.querySelector('.candidatura-modal');
            gsap.fromTo(modalContent, {
                opacity: 0,
                scale: 0.9
            }, {
                opacity: 1,
                scale: 1,
                duration: 0.3,
                ease: 'power2.out'
            });
        }

        function fecharModal(idVaga) {
            const modal = document.getElementById('modalCarta-' + idVaga);
            const modalContent = modal.querySelector('.candidatura-modal');
            
            // Animar a saída do modal
            gsap.to(modalContent, {
                opacity: 0,
                scale: 0.9,
                duration: 0.3,
                ease: 'power2.in',
                onComplete: () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }

        function gerarCarta(event, idVaga) {
            event.preventDefault();
            const responsavel = document.getElementById('responsavel-' + idVaga).value;
            
            // Adicionar animação ao botão
            const submitBtn = event.submitter;
            gsap.to(submitBtn, {
                scale: 0.95,
                duration: 0.1,
                yoyo: true,
                repeat: 1
            });
            
            // Redirecionar para a página de geração da carta
            window.location.href = `relatorio/gerar_carta.php?id_vaga=${idVaga}&responsavel=${encodeURIComponent(responsavel)}`;
        }
        document.addEventListener('DOMContentLoaded', () => {
            const novaVagaModal = document.getElementById('novaVagaModal');
            const addVagaBtn = document.getElementById('addVagaBtn');
            const firstVagaBtn = document.getElementById('firstVagaBtn');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const searchInput = document.getElementById('searchVaga');
            const filterArea = document.getElementById('filterArea');
            const filterEmpresa = document.getElementById('filterEmpresa');
            const vagasGrid = document.getElementById('vagasGrid');
            const relatorioVagasBtn = document.getElementById('relatorioVagasBtn');
            const gerarRelatorioBtn = document.getElementById('gerarRelatorioBtn');
            let currentModalId = null;

            // GSAP Animations
            gsap.from('.action-bar', {
                opacity: 0,
                y: 20,
                duration: 0.5,
                ease: 'power2.out'
            });
            gsap.from('.vaga-card', {
                opacity: 0,
                y: 50,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power3.out'
            });

            // Manipulação do formulário
            document.getElementById('vagaForm').addEventListener('submit', (e) => {
                const submitBtn = e.submitter;
                if (submitBtn) {
                    gsap.to(submitBtn, {
                        scale: 0.95,
                        duration: 0.1,
                        yoyo: true,
                        repeat: 1
                    });
                }
            });

            // Sidebar mobile toggle
            sidebarToggle.addEventListener('click', () => {
                mobileSidebar.classList.remove('-translate-x-full');
                document.body.style.overflow = 'hidden';
            });

            closeSidebar.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
                document.body.style.overflow = 'auto';
            });

            mobileSidebar.addEventListener('click', (e) => {
                if (e.target === mobileSidebar) {
                    mobileSidebar.classList.add('-translate-x-full');
                    document.body.style.overflow = 'auto';
                }
            });

            // Modal handling
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }

            // Função para preencher o modal de edição de vaga
            function preencherModalEditarVaga(vagaId) {
                const modal = document.getElementById(`editarVagaModal-${vagaId}`);
                if (!modal) return;

                // Encontrar o card da vaga correspondente para obter os dados
                const vagaCard = document.querySelector(`.vaga-card[data-vaga-id="${vagaId}"]`);
                if (!vagaCard) return;

                // Obter dados dos atributos data-*
                const empresaId = vagaCard.dataset.empresaId;
                const area = vagaCard.dataset.area; // Nome da área
                const quantidade = vagaCard.dataset.quantidade; // Quantidade de vagas
                const quantCandidatos = vagaCard.dataset.quantCandidatos; // Quantidade de candidatos
                const tipoVaga = vagaCard.dataset.tipoVaga; // Tipo de vaga
                const data = vagaCard.dataset.data; // Data
                const hora = vagaCard.dataset.hora; // Hora

                // Preencher os campos do formulário
                modal.querySelector('[name="id_editar_vaga"]').value = vagaId;
                modal.querySelector('[name="empresa_editar_vaga"]').value = empresaId;
                modal.querySelector('[name="quantidade_editar_vaga"]').value = quantidade;
                modal.querySelector('[name="quant_candidatos_editar_vaga"]').value = quantCandidatos;
                modal.querySelector('[name="tipo_editar_vaga"]').value = tipoVaga;
                modal.querySelector('[name="data_editar_vaga"]').value = data;
                modal.querySelector('[name="hora_editar_vaga"]').value = hora;

                // Marcar o radio button do perfil correto
                const perfilRadioButtons = modal.querySelectorAll('[name="perfil_editar_vaga"]');
                perfilRadioButtons.forEach(radio => {
                    // Comparar o valor do radio button (ID do perfil) com o nome da área da vaga
                    // Pode ser necessário mapear o nome da área para o ID aqui, ou ajustar o data-area no card
                    // Por enquanto, vamos tentar uma comparação baseada no texto exibido nos radio buttons (não ideal, mas para testar)
                    const labelText = radio.nextElementSibling.textContent.trim().toLowerCase();
                    const areaNormalized = area.toLowerCase();
                    
                    // Lógica para mapear o nome da área para o valor do radio button (ID)
                    let perfilId = null;
                    if (areaNormalized === 'desenvolvimento') perfilId = '1';
                    else if (areaNormalized === 'design') perfilId = '2'; // Assumindo que 'design/mídia' e variações normalizam para 'design'
                    else if (areaNormalized === 'suporte/redes') perfilId = '3';
                    else if (areaNormalized === 'tutoria') perfilId = '4';

                    if (radio.value === perfilId) {
                         radio.checked = true;
                    } else {
                        radio.checked = false;
                    }

                });

                // Abrir o modal
                openModal(`editarVagaModal-${vagaId}`);
            }

            // Delegar eventos para botões de Editar e Excluir
            vagasGrid.addEventListener('click', (e) => {
                const editBtn = e.target.closest('.edit-btn');
                const deleteBtn = e.target.closest('.delete-btn');

                if (editBtn) {
                    const vagaId = editBtn.closest('.vaga-card').dataset.vagaId;
                    console.log('Botão Editar clicado, vagaId:', vagaId);
                    preencherModalEditarVaga(vagaId); // Chamar a nova função
                }

                if (deleteBtn) {
                    const modalId = deleteBtn.getAttribute('data-modal-id');
                    console.log('Botão Excluir clicado, modalId:', modalId);
                    openModal(modalId);
                }
            });

            // Adicionar um event listener global para todos os botões de fechar modal
            document.addEventListener('click', function(e) {
                const closeBtn = e.target.closest('.close-btn');
                if (closeBtn) {
                    const modalId = closeBtn.getAttribute('data-modal-id');
                    if (modalId) {
                        console.log('Botão Fechar global clicado, modalId:', modalId); // Debug
                        closeModal(modalId);
                    }
                }
            });

            // Nova Vaga Modal
            addVagaBtn.addEventListener('click', () => {
                console.log('Botão Nova Vaga clicado');
                openModal('novaVagaModal');
            });

            if (firstVagaBtn) {
                firstVagaBtn.addEventListener('click', () => {
                    console.log('Botão Cadastrar Primeira Vaga clicado');
                    openModal('novaVagaModal');
                });
            }

            // Fechar modais ao clicar fora
            document.querySelectorAll('.fixed.inset-0').forEach(modalContainer => { // Seletor mais específico
                modalContainer.addEventListener('click', (e) => {
                    if (e.target === modalContainer) {
                        closeModal(modalContainer.id);
                    }
                });
            });

            // Filtragem de vagas - Lógica de navegação para GET
            function aplicarFiltros() {
                const areaFiltro = filterArea.value;
                const empresaFiltro = filterEmpresa.value;

                const urlParams = new URLSearchParams();
                if (areaFiltro) {
                    // Converter nome da área para ID do perfil para o GET request
                    let perfilId;
                    switch (areaFiltro.toLowerCase()) {
                        case 'desenvolvimento':
                            perfilId = '1';
                            break;
                        case 'design/mídia':
                        case 'desing/mídia': // Incluir variação 'Desing/Mídia'
                            perfilId = '2';
                            break;
                        case 'tutoria':
                            perfilId = '4';
                            break;
                        case 'suporte/redes':
                            perfilId = '3';
                            break;
                        default:
                            perfilId = '';
                    }
                    if (perfilId) {
                         urlParams.set('perfil', perfilId);
                    }
                }
                if (empresaFiltro) {
                    urlParams.set('empresa', empresaFiltro);
                }

                // Construir a nova URL e navegar
                window.location.href = `${window.location.pathname}?${urlParams.toString()}`;
            }

            // Modificar listeners para disparar navegação
            filterArea.addEventListener('change', aplicarFiltros);
            filterEmpresa.addEventListener('change', aplicarFiltros);

            // A busca por texto ainda funcionará no cliente após o carregamento inicial (com os filtros aplicados pelo GET)
            // searchInput.addEventListener('input', aplicarFiltros); // Remover este listener pois queremos GET request nos selects

            // Não chamar aplicarFiltros() aqui para evitar loop infinito de recarga
            // A filtragem inicial será feita pelo PHP lendo os parâmetros GET na primeira carga

            // Função para exibir toast
            function showToast(message, type) {
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 fade-in ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                } text-white`;
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-3"></i>
                        <span>${message}</span>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    gsap.to(toast, {
                        opacity: 0,
                        y: 20,
                        duration: 0.3,
                        onComplete: () => {
                            document.body.removeChild(toast);
                        }
                    });
                }, type === 'success' ? 3000 : 4000);
            }

            // Relatório de Vagas Modal
            relatorioVagasBtn.addEventListener('click', () => {
                console.log('Botão Relatório de Vagas clicado');
                openModal('relatorioVagasModal');
                
                // Atualiza os filtros do relatório com os filtros atuais
                const empresaAtual = document.getElementById('filterEmpresa').value;
                const perfilAtual = document.getElementById('filterArea').value;
                
                const formRelatorio = document.getElementById('formRelatorio');
                const empresaRelatorio = formRelatorio.querySelector('[name="empresa"]');
                const perfilRelatorio = formRelatorio.querySelector('[name="perfil"]');
                
                // Atualiza os valores dos selects
                if (empresaAtual) {
                    empresaRelatorio.value = empresaAtual;
                } else {
                    empresaRelatorio.value = '';
                }
                
                if (perfilAtual) {
                    perfilRelatorio.value = perfilAtual;
                } else {
                    perfilRelatorio.value = '';
                }
            });

            // Adiciona um listener para o submit do formulário de relatório
            document.getElementById('formRelatorio').addEventListener('submit', function(e) {
                e.preventDefault();
                const url = new URL(this.action);
                const empresaRelatorio = this.querySelector('[name="empresa"]').value;
                const perfilRelatorio = this.querySelector('[name="perfil"]').value;
                
                url.searchParams.set('empresa', empresaRelatorio);
                url.searchParams.set('perfil', perfilRelatorio);
                window.open(url.toString(), '_blank');
                closeModal('relatorioVagasModal');
            });

            // Botão de gerar relatório
            if (gerarRelatorioBtn) {
                gerarRelatorioBtn.addEventListener('click', () => {
                    console.log('Botão Gerar Resumo clicado. Abrindo relatório...');
                    window.open('../controllers/relatorio_resumo_vagas.php', '_blank');
                });
            } else {
                console.error('Erro no script principal: Elemento com ID gerarRelatorioBtn não encontrado.');
            }
        });

        // Funções para o modal de relatório
        function abrirModalRelatorio() {
            const modal = document.getElementById('relatorioVagasModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function fecharModalRelatorio() {
            const modal = document.getElementById('relatorioVagasModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Adicionar evento ao botão de relatório
        document.getElementById('relatorioVagasBtn').addEventListener('click', abrirModalRelatorio);

        // Fechar modal ao clicar fora
        document.getElementById('relatorioVagasModal').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalRelatorio();
            }
        });

        // Submit do formulário
        document.getElementById('formRelatorio').addEventListener('submit', function(e) {
            e.preventDefault();
            const url = new URL(this.action);
            const empresa = this.querySelector('[name="empresa"]').value;
            const perfil = this.querySelector('[name="perfil"]').value;
            
            url.searchParams.set('empresa', empresa);
            url.searchParams.set('perfil', perfil);
            window.open(url.toString(), '_blank');
            fecharModalRelatorio();
        });

        document.addEventListener('DOMContentLoaded', () => {
            const novaVagaModal = document.getElementById('novaVagaModal');
            const cancelarBtn = document.getElementById('cancelarBtn');

            // Função para fechar o modal
            function fecharModal() {
                novaVagaModal.classList.add('hidden');
                novaVagaModal.classList.remove('flex');
            }

            // Fechar modal ao clicar no botão cancelar
            cancelarBtn.addEventListener('click', fecharModal);

            // Fechar modal ao clicar fora dele
            window.addEventListener('click', (event) => {
                if (event.target === novaVagaModal) {
                    fecharModal();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const novaVagaModal = document.getElementById('novaVagaModal');
            const addVagaBtn = document.getElementById('addVagaBtn');

            // Função para abrir o modal
            function abrirModal() {
                novaVagaModal.classList.remove('hidden');
                novaVagaModal.classList.add('flex');
            }

            // Função para fechar o modal
            function fecharModal() {
                novaVagaModal.classList.add('hidden');
                novaVagaModal.classList.remove('flex');
            }

            // Abrir modal ao clicar no botão "Nova Vaga"
            addVagaBtn.addEventListener('click', abrirModal);

            // Fechar modal ao clicar fora dele
            novaVagaModal.addEventListener('click', (event) => {
                if (event.target === novaVagaModal) {
                    fecharModal();
                }
            });
        });
    </script>
</body>

</html>
