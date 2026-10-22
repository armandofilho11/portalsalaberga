<?php

file_put_contents(__DIR__ . '/../logs/get_nome_perfil_log.txt', 'Nome Perfil Recebido (alunos_vaga.php): ' . print_r($_GET['nome_perfil'], true) . "\n---\n", FILE_APPEND);

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
    <meta name="description" content="Seleção de Alunos - Sistema de Estágio">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <title>Seleção de Alunos - Sistema de Estágio</title>

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
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cpath fill='%23007A33' fill-opacity='0.03' d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z'%3E%3C/path%3E%3C/svg%3E");
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

        /* Vaga info card styling */
        .vaga-info-card {
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
        }

        .vaga-info-card::before {
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

        .vaga-info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 122, 51, 0.3);
        }

        .vaga-info-card:hover::before {
            opacity: 1;
            box-shadow: 0 0 15px rgba(0, 255, 107, 0.4);
        }

        .vaga-info-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            background: linear-gradient(90deg, #ffffff, #e0e0e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .vaga-info-card:hover .vaga-info-title {
            background: linear-gradient(90deg, #ffffff, #00FF6B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        /* Table styling */
        .table-container {
            background: linear-gradient(135deg, rgba(49, 49, 49, 0.95) 0%, rgba(37, 37, 37, 0.95) 100%);
            border-radius: 16px;
            overflow-x: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead th {
            background: rgba(35, 35, 35, 0.9);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: #b0b0b0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .custom-table tbody tr {
            transition: all 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background-color: rgba(0, 122, 51, 0.1);
            transform: translateX(5px);
        }

        .custom-table tbody td {
            padding: 1rem;
            font-size: 0.875rem;
            color: #e0e0e0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
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

        /* Status chips */
        .chip {
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

        .chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .chip-green {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .chip-green:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.3) 0%, rgba(5, 150, 105, 0.3) 100%);
        }

        .chip-yellow {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .chip-yellow:hover {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.3) 0%, rgba(217, 119, 6, 0.3) 100%);
        }

        .chip-red {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(220, 38, 38, 0.2) 100%);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .chip-red:hover {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.3) 0%, rgba(220, 38, 38, 0.3) 100%);
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
            color: #c4b5fd;
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
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .area-tutoria:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.3) 0%, rgba(5, 150, 105, 0.3) 100%);
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

        .custom-input:focus {
            border-color: #007A33 !important;
            box-shadow: 0 0 0 2px rgba(0, 122, 51, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            outline: none !important;
            background-color: rgba(40, 40, 40, 0.9) !important;
        }

        .custom-input::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
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
            .custom-input, select.custom-input {
                min-width: 100% !important;
                font-size: 1rem !important;
            }
            .relative {
                min-width: 100%;
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
                    <h1 class="text-xl font-bold text-white text-center sm:text-left w-full">Selecionar Alunos para Vaga</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400">
                            <i class="fas fa-user-circle mr-1"></i> Admin
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7x2 mx-auto px-2 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                <!-- Breadcrumbs -->
                <div class="text-sm text-gray-400 mb-6 flex items-center">
                    <a href="dashboard.php" class="hover:text-primary-400 transition-colors">Dashboard</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <a href="vagas.php" class="hover:text-primary-400 transition-colors">Vagas</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="text-white">Selecionar Alunos</span>
                </div>

                <!-- Info da Vaga -->
                <div class="vaga-info-card mb-8 slide-up">
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-building text-primary-400"></i>
                            <span class="text-gray-400">Empresa:</span>
                            <span id="vagaEmpresa" class="text-white"><?php echo $_GET['nome_empresa']?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tag text-primary-400"></i>
                            <span class="text-gray-400">Área:</span>
                            <?php
                            $nomePerfil = isset($_GET['nome_perfil']) ? htmlspecialchars($_GET['nome_perfil'], ENT_QUOTES, 'UTF-8') : 'Área não informada';
                            $area = strtolower($nomePerfil);
                            if ($area === 'design/social mídia' || $area === 'design/mídia' || $area === 'design/mídias' || $area === 'design') {
                                $area_class = 'design';
                            } elseif ($nomePerfil === 'Suporte/Redes') {
                                $area_class = 'redes';
                            } elseif ($nomePerfil === 'Tutoria') {
                                $area_class = 'tutoria';
                            } else {
                                $area_class = 'desenvolvimento'; // Cor padrão se a área não for informada
                            }
                            ?>
                            <span class="area-chip area-<?php echo $area_class; ?>">
                                <?php if ($area_class === 'desenvolvimento'): ?>
                                    <i class="fas fa-code mr-1 text-xs"></i>
                                <?php elseif ($area_class === 'tutoria'): ?>
                                    <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                <?php elseif ($area_class === 'design'): ?>
                                    <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                <?php elseif ($area_class === 'redes'): ?>
                                    <i class="fas fa-network-wired mr-1 text-xs"></i>
                                <?php else: ?>
                                    <i class="fas fa-question mr-1 text-xs"></i>
                                <?php endif; ?>
                                <?php echo $nomePerfil; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions Bar -->
                <div class="mb-8 action-bar p-4 sm:p-5 fade-in">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full">
                            <div class="search-input-container relative w-full sm:w-64">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="searchAluno" placeholder="Buscar aluno..." class="custom-input pl-10 pr-4 py-2.5 w-full">
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full sm:w-auto">
                            <div class="relative">
                                <select id="filterArea" class="custom-input pl-4 pr-10 py-2.5 appearance-none w-full">
                                    <option value="">Todas as áreas</option>
                                    <option value="desenvolvimento">Desenvolvimento</option>
                                    <option value="design/mídia">Design/Mídia</option>
                                    <option value="suporte/redes">Suporte/Redes</option>
                                    <option value="tutoria">Tutoria</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                           
                        </div>
                    </div>
                </div>

                <!-- Tabela de Alunos -->
                <form action="../controllers/controller.php" method="post">
                    <div class="table-container slide-up md:block hidden">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th width="40px" class="text-center">#</th>
                                    <th width="50px" class="text-center"></th>
                                    <th>Scores</th>
                                    <th>Nome</th>
                                    <th width="80px" class="text-center">Média</th>
                                    <th>Projetos</th>
                                    <th width="100px">Área 1</th>
                                    <th width="100px">Área 2</th>
                                    <th width="100px" class="text-center">Ocorrências</th>
                                    <th width="100px">Custeio</th>
                                    <th width="150px">Entregas individuais</th>
                                    <th width="100px">Entregas do grupo</th>
                                </tr>
                            </thead>
                            <tbody id="alunosTableBody">
                                <?php
                                if (isset($_GET['nome_perfil'])) {
                                    $nome_perfil = $_GET['nome_perfil'];
                                    $id_vaga = $_GET['id_vaga'];
                                    $dados = $select_model->alunos($nome_perfil);
                                    foreach ($dados as $index => $dado) {
                                ?>
                                <input type="hidden" name="id_vaga" value="<?=$id_vaga?>">
                                        <tr class="slide-up" style="animation-delay: <?= $index * 0.1 ?>s;">
                                            <td class="text-center"><?= $index + 1 ?></td>
                                            <td class="text-center">
                                                <input type="checkbox" class="custom-checkbox" id="aluno_<?= $dado['id'] ?>" name="alunos[]" value="<?= $dado['id'] ?>">
                                            </td>
                                            <td><?= htmlspecialchars($dado['score']) ?></td>
                                            <td><?= htmlspecialchars($dado['nome']) ?></td>
                                            <td class="text-center"><?= number_format($dado['medias'], 1) ?></td>
                                            <td><?= htmlspecialchars($dado['projetos'] ?? '-') ?></td>
                                            <td>
                                                <?php
                                                $perfil1 = isset($dado['perfil_opc1']) ? htmlspecialchars($dado['perfil_opc1'], ENT_QUOTES, 'UTF-8') : 'Não informado';
                                                $area1_class = strtolower($perfil1);
                                                if ($area1_class === 'design/social mídia' || $area1_class === 'design/mídia' || $area1_class === 'design/mídias' || $area1_class === 'design') {
                                                    $area1_class = 'design';
                                                } elseif ($perfil1 === 'Suporte/Redes') {
                                                    $area1_class = 'redes';
                                                } elseif ($perfil1 === 'Tutoria') {
                                                    $area1_class = 'tutoria';
                                                } else {
                                                    $area1_class = 'desenvolvimento'; // Cor padrão
                                                }
                                                ?>
                                                <?php if ($perfil1 !== 'Não informado'): ?>
                                                    <span class="area-chip area-<?php echo $area1_class; ?>">
                                                        <?php if ($area1_class === 'desenvolvimento'): ?>
                                                            <i class="fas fa-code mr-1 text-xs"></i>
                                                        <?php elseif ($area1_class === 'tutoria'): ?>
                                                            <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                                        <?php elseif ($area1_class === 'design'): ?>
                                                            <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                                        <?php elseif ($area1_class === 'redes'): ?>
                                                            <i class="fas fa-network-wired mr-1 text-xs"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-question mr-1 text-xs"></i>
                                                        <?php endif; ?>
                                                        <?php echo $perfil1; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">Não informado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $perfil2 = isset($dado['perfil_opc2']) ? htmlspecialchars($dado['perfil_opc2'], ENT_QUOTES, 'UTF-8') : 'Não informado';
                                                $area2_class = strtolower($perfil2);
                                                if ($area2_class === 'design/social mídia' || $area2_class === 'design/mídia' || $area2_class === 'design/mídias' || $area2_class === 'design') {
                                                    $area2_class = 'design';
                                                } elseif ($perfil2 === 'Suporte/Redes') {
                                                    $area2_class = 'redes';
                                                } elseif ($perfil2 === 'Tutoria') {
                                                    $area2_class = 'tutoria';
                                                } else {
                                                    $area2_class = 'desenvolvimento'; // Cor padrão
                                                }
                                                ?>
                                                <?php if ($perfil2 !== 'Não informado' && !empty($perfil2)): ?>
                                                    <span class="area-chip area-<?php echo $area2_class; ?>">
                                                        <?php if ($area2_class === 'desenvolvimento'): ?>
                                                            <i class="fas fa-code mr-1 text-xs"></i>
                                                        <?php elseif ($area2_class === 'tutoria'): ?>
                                                            <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                                        <?php elseif ($area2_class === 'design'): ?>
                                                            <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                                        <?php elseif ($area2_class === 'redes'): ?>
                                                            <i class="fas fa-network-wired mr-1 text-xs"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-question mr-1 text-xs"></i>
                                                        <?php endif; ?>
                                                        <?php echo $perfil2; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $ocorrencias = $dado['ocorrencia'];
                                                $chipClass = $ocorrencias == 0 ? 'chip-green' : ($ocorrencias == 1 ? 'chip-yellow' : 'chip-red');
                                                echo "<span class='chip $chipClass'>$ocorrencias</span>";
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($dado['custeio'] = $dado['custeio'] == 0 ? "Não" : "Sim") ?></td>
                                            <td>
                                                <?php
                                                $entregasIndividuais = isset($dado['entregas_individuais']) ? (int)$dado['entregas_individuais'] : 0;
                                                $chipClassEntregas = $entregasIndividuais > 0 ? 'chip-green' : 'chip-yellow';
                                                ?>
                                                <span class="chip <?php echo $chipClassEntregas; ?>">
                                                    <?php echo $entregasIndividuais; ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($dado['entregas_grupo']) ?></td>
                                        </tr>
                                    <?php }
                                } else {
                                    $dados = $select_model->alunos_aptos();
                                    foreach ($dados as $index => $dado) {
                                    ?>

                                        <tr class="slide-up" style="animation-delay: <?= $index * 0.1 ?>s;">
                                            <td class="text-center"><?= $index + 1 ?></td>
                                            <td class="text-center">
                                                
                                                <input type="checkbox" class="custom-checkbox" id="aluno_<?= $dado['id'] ?>" name="alunos[]" value="<?= $dado['id'] ?>">
                                            </td>
                                            <td><?= htmlspecialchars( $dado['score']) ?></td>
                                            <td><?= htmlspecialchars($dado['nome']) ?></td>
                                            <td class="text-center"><?= number_format($dado['medias'], 1) ?></td>
                                            <td><?= htmlspecialchars($dado['projetos'] ?? '-') ?></td>
                                            <td>
                                            <?php
                                                $perfil1 = isset($dado['perfil_opc1']) ? htmlspecialchars($dado['perfil_opc1'], ENT_QUOTES, 'UTF-8') : 'Não informado';
                                                $area1_class = strtolower($perfil1);
                                                if ($area1_class === 'design/social mídia' || $area1_class === 'design/mídia' || $area1_class === 'design/mídias' || $area1_class === 'design') {
                                                    $area1_class = 'design';
                                                } elseif ($perfil1 === 'Suporte/Redes') {
                                                    $area1_class = 'redes';
                                                } elseif ($perfil1 === 'Tutoria') {
                                                    $area1_class = 'tutoria';
                                                } else {
                                                    $area1_class = 'desenvolvimento'; // Cor padrão
                                                }
                                                ?>
                                                <?php if ($perfil1 !== 'Não informado'): ?>
                                                    <span class="area-chip area-<?php echo $area1_class; ?>">
                                                        <?php if ($area1_class === 'desenvolvimento'): ?>
                                                            <i class="fas fa-code mr-1 text-xs"></i>
                                                        <?php elseif ($area1_class === 'tutoria'): ?>
                                                            <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                                        <?php elseif ($area1_class === 'design'): ?>
                                                            <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                                        <?php elseif ($area1_class === 'redes'): ?>
                                                            <i class="fas fa-network-wired mr-1 text-xs"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-question mr-1 text-xs"></i>
                                                        <?php endif; ?>
                                                        <?php echo $perfil1; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">Não informado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                            <?php
                                                $perfil2 = isset($dado['perfil_opc2']) ? htmlspecialchars($dado['perfil_opc2'], ENT_QUOTES, 'UTF-8') : 'Não informado';
                                                $area2_class = strtolower($perfil2);
                                                if ($area2_class === 'design/social mídia' || $area2_class === 'design/mídia' || $area2_class === 'design/mídias' || $area2_class === 'design') {
                                                    $area2_class = 'design';
                                                } elseif ($perfil2 === 'Suporte/Redes') {
                                                    $area2_class = 'redes';
                                                } elseif ($perfil2 === 'Tutoria') {
                                                    $area2_class = 'tutoria';
                                                } else {
                                                    $area2_class = 'desenvolvimento'; // Cor padrão
                                                }
                                                ?>
                                                <?php if ($perfil2 !== 'Não informado' && !empty($perfil2)): ?>
                                                    <span class="area-chip area-<?php echo $area2_class; ?>">
                                                        <?php if ($area2_class === 'desenvolvimento'): ?>
                                                            <i class="fas fa-code mr-1 text-xs"></i>
                                                        <?php elseif ($area2_class === 'tutoria'): ?>
                                                            <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                                        <?php elseif ($area2_class === 'design'): ?>
                                                            <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                                        <?php elseif ($area2_class === 'redes'): ?>
                                                            <i class="fas fa-network-wired mr-1 text-xs"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-question mr-1 text-xs"></i>
                                                        <?php endif; ?>
                                                        <?php echo $perfil2; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $ocorrencias = $dado['ocorrencia'];
                                                $chipClass = $ocorrencias == 0 ? 'chip-green' : ($ocorrencias == 1 ? 'chip-yellow' : 'chip-red');
                                                echo "<span class='chip $chipClass'>$ocorrencias</span>";
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($dado['custeio'] = $dado['custeio'] == 0 ? "Não" : "Sim") ?></td>
                                            <td>
                                                <?php
                                                $entregasIndividuais = isset($dado['entregas']) ? (int)$dado['entregas'] : 0;
                                                $chipClassEntregas = $entregasIndividuais > 0 ? 'chip-green' : 'chip-yellow';
                                                ?>
                                                <span class="chip <?php echo $chipClassEntregas; ?>">
                                                    <?php echo $entregasIndividuais; ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($dado['entregas_grupo']) ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards de Alunos (Mobile) -->
                    <div class="md:hidden grid grid-cols-1 gap-4 slide-up">
                        <?php
                        // Re-fetching or using the same data depending on the initial fetch logic
                        // Assuming $dados is available from the initial fetch
                        if (!empty($dados)) {
                            foreach ($dados as $index => $dado) {
                                // Determine area classes for chips
                                $perfil1 = isset($dado['perfil_opc1']) ? htmlspecialchars($dado['perfil_opc1'], ENT_QUOTES, 'UTF-8') : 'Não informado';
                                $area1_class = strtolower($perfil1);
                                if ($area1_class === 'design/social mídia' || $area1_class === 'design/mídia' || $area1_class === 'design/mídias' || $area1_class === 'design') {
                                    $area1_class = 'design';
                                } elseif ($perfil1 === 'Suporte/Redes') {
                                    $area1_class = 'redes';
                                } elseif ($perfil1 === 'Tutoria') {
                                    $area1_class = 'tutoria';
                                } else {
                                    $area1_class = 'desenvolvimento'; // Cor padrão
                                }

                                $perfil2 = isset($dado['perfil_opc2']) ? htmlspecialchars($dado['perfil_opc2'], ENT_QUOTES, 'UTF-8') : 'Não informado';
                                $area2_class = strtolower($perfil2);
                                if ($area2_class === 'design/social mídia' || $area2_class === 'design/mídia' || $area2_class === 'design/mídias' || $area2_class === 'design') {
                                    $area2_class = 'design';
                                } elseif ($perfil2 === 'Suporte/Redes') {
                                    $area2_class = 'redes';
                                } elseif ($perfil2 === 'Tutoria') {
                                    $area2_class = 'tutoria';
                                } else {
                                    $area2_class = 'desenvolvimento'; // Cor padrão
                                }

                                $ocorrencias = $dado['ocorrencia'];
                                $chipClassOcorrencias = $ocorrencias == 0 ? 'chip-green' : ($ocorrencias == 1 ? 'chip-yellow' : 'chip-red');

                                // Assuming $dado['entregas_individuais'] or $dado['entregas'] exists based on the table code
                                $entregasIndividuais = isset($dado['entregas_individuais']) ? (int)$dado['entregas_individuais'] : (isset($dado['entregas']) ? (int)$dado['entregas'] : 0);
                                $chipClassEntregas = $entregasIndividuais > 0 ? 'chip-green' : 'chip-yellow';
                        ?>
                            <div class="card-aluno mb-4 p-4 bg-dark-50 rounded-lg shadow-md border border-gray-700 slide-up" style="animation-delay: <?= $index * 0.1 ?>s;">
                                <div class="flex items-center justify-between mb-3 border-b border-gray-700 pb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-primary-400">Aluno #<?= $index + 1 ?></span>
                                        <!-- Checkbox -->
                                        <input type="checkbox" class="custom-checkbox" id="aluno_<?= $dado['id'] ?>_mobile" name="alunos[]" value="<?= $dado['id'] ?>">
                                    </div>
                                    <span class="text-xs text-gray-400"><i class="fas fa-star text-yellow-400 mr-1"></i> Score: <?= htmlspecialchars($dado['score']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <p class="text-lg font-bold text-white"><?= htmlspecialchars($dado['nome']) ?></p>
                                    <p class="text-sm text-gray-400">Média: <span class="font-semibold text-white"><?= number_format($dado['medias'], 1) ?></span></p>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                    <div>
                                        <p class="text-gray-400 mb-1">Projetos:</p>
                                        <p class="text-white font-semibold"><?= htmlspecialchars($dado['projetos'] ?? '-') ?></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 mb-1">Custeio:</p>
                                        <p class="text-white font-semibold"><?= htmlspecialchars($dado['custeio'] = $dado['custeio'] == 0 ? "Não" : "Sim") ?></p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                    <div>
                                        <p class="text-gray-400 mb-1">Entregas Individuais:</p>
                                        <span class="chip <?php echo $chipClassEntregas; ?>"><?php echo $entregasIndividuais; ?></span>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 mb-1">Entregas Grupo:</p>
                                        <span class="chip chip-green"><?= htmlspecialchars($dado['entregas_grupo']) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-1">Áreas de Interesse:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <?php if ($perfil1 !== 'Não informado'): ?>
                                            <span class="area-chip area-<?php echo $area1_class; ?>">
                                                <?php if ($area1_class === 'desenvolvimento'): ?>
                                                    <i class="fas fa-code mr-1 text-xs"></i>
                                                <?php elseif ($area1_class === 'tutoria'): ?>
                                                    <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                                <?php elseif ($area1_class === 'design'): ?>
                                                    <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                                <?php elseif ($area1_class === 'redes'): ?>
                                                    <i class="fas fa-network-wired mr-1 text-xs"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-question mr-1 text-xs"></i>
                                                <?php endif; ?>
                                                <?php echo $perfil1; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-500 text-xs">Não informado</span>
                                        <?php endif; ?>
                                        <?php if ($perfil2 !== 'Não informado' && !empty($perfil2)): ?>
                                            <span class="area-chip area-<?php echo $area2_class; ?>">
                                                <?php if ($area2_class === 'desenvolvimento'): ?>
                                                    <i class="fas fa-code mr-1 text-xs"></i>
                                                <?php elseif ($area2_class === 'tutoria'): ?>
                                                    <i class="fas fa-chalkboard-teacher mr-1 text-xs"></i>
                                                <?php elseif ($area2_class === 'design'): ?>
                                                    <i class="fas fa-paint-brush mr-1 text-xs"></i>
                                                <?php elseif ($area2_class === 'redes'): ?>
                                                    <i class="fas fa-network-wired mr-1 text-xs"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-question mr-1 text-xs"></i>
                                                <?php endif; ?>
                                                <?php echo $perfil2; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <p class="text-gray-400 mb-1">Ocorrências:</p>
                                    <span class="chip <?php echo $chipClassOcorrencias; ?>"><?php echo $ocorrencias; ?></span>
                                </div>
                            </div>
                        <?php
                            }
                        } else {
                            // Message for no students found
                        ?>
                            <p class="text-center text-gray-400 py-4">Nenhum aluno encontrado.</p>
                        <?php
                        }
                        ?>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="mt-6 flex justify-end space-x-4 fade-in">
                        <a href="vagas.php" class="custom-btn custom-btn-secondary">
                            <i class="fas fa-times btn-icon"></i>
                            <span>Cancelar</span>
                        </a>
                        <button type="submit" class="custom-btn custom-btn-primary" id="confirmarSelecaoBtn">
                            <i class="fas fa-check btn-icon"></i>
                            <span>Confirmar Seleção</span>
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchAluno = document.getElementById('searchAluno');
            const filterArea = document.getElementById('filterArea');
            const confirmarSelecaoBtn = document.getElementById('confirmarSelecaoBtn');
            console.log('confirmarSelecaoBtn element:', confirmarSelecaoBtn);
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const mobileSidebar = document.getElementById('mobileSidebar');

            // GSAP Animations
            gsap.from('.vaga-info-card', {
                opacity: 0,
                y: 50,
                duration: 0.6,
                ease: 'power3.out'
            });
            gsap.from('.table-container', {
                opacity: 0,
                y: 50,
                duration: 0.6,
                delay: 0.2,
                ease: 'power3.out'
            });
            gsap.from('.action-bar', {
                opacity: 0,
                y: 20,
                duration: 0.5,
                ease: 'power2.out'
            });

            // Sidebar mobile toggle
            sidebarToggle.addEventListener('click', () => {
                gsap.to(mobileSidebar, {
                    x: 0,
                    duration: 0.3,
                    ease: 'power2.out'
                });
            });

            closeSidebar.addEventListener('click', () => {
                gsap.to(mobileSidebar, {
                    x: '-100%',
                    duration: 0.3,
                    ease: 'power2.in'
                });
            });

            // Aplicar filtros na tabela
            function aplicarFiltros() {
                const searchTerm = searchAluno.value.toLowerCase();
                const areaFiltro = filterArea.value;
                const rows = document.querySelectorAll('#alunosTableBody tr');

                let hasVisible = false;
                rows.forEach((row, index) => {
                    const nome = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    const area1 = row.querySelector('td:nth-child(7)').textContent.toLowerCase();
                    const area2 = row.querySelector('td:nth-child(8)').textContent.toLowerCase();
                    const matchSearch = nome.includes(searchTerm);
                    const matchArea = !areaFiltro || area1 === areaFiltro || area2 === areaFiltro;

                    if (matchSearch && matchArea) {
                        row.style.display = '';
                        hasVisible = true;
                        gsap.fromTo(row, {
                            opacity: 0,
                            y: 20
                        }, {
                            opacity: 1,
                            y: 0,
                            duration: 0.3,
                            delay: index * 0.05
                        });
                    } else {
                        gsap.to(row, {
                            opacity: 0,
                            y: 20,
                            duration: 0.3,
                            onComplete: () => {
                                row.style.display = 'none';
                            }
                        });
                    }
                });

                const tbody = document.getElementById('alunosTableBody');
                if (!hasVisible && !tbody.querySelector('p')) {
                    const noResults = document.createElement('p');
                    noResults.className = 'text-center text-gray-400 col-span-full py-4';
                    noResults.textContent = 'Nenhum aluno encontrado.';
                    tbody.appendChild(noResults);
                } else if (hasVisible && tbody.querySelector('p')) {
                    tbody.querySelector('p').remove();
                }
            }

            // Selecionar/Deselecionar todos os alunos
            let todosSelecionados = false;

            function toggleSelectAll() {
                todosSelecionados = !todosSelecionados;
                const checkboxes = document.querySelectorAll('#alunosTableBody input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = todosSelecionados;
                });
            }

            // Event listeners
            searchAluno.addEventListener('input', aplicarFiltros);
            filterArea.addEventListener('change', aplicarFiltros);

            // Inicializar página
            aplicarFiltros();
        });
    </script>
</body>

</html>