<?php
require_once('../models/select_model.php');
$select_model = new select_model;
require_once('../models/sessions.php');

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
    <meta name="description" content="Gerenciamento de Empresas - Sistema de Estágio">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <title>Gerenciar Empresas - Sistema de Estágio</title>

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
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cpath fill='%23007A33' fill-opacity='0.03' d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z'%3E%3C/path%3E%3C/svg%3E");
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

        .bg-dark-50 {
            background-color: #1a1a1a;
        }

        .admin-indicator {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(22, 163, 74, 0.1) 100%);
            border: 0px solid rgba(34, 197, 94, 0.2);
        }

        .status-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }


        /* Company card styling */
        .empresa-card {
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

        .empresa-card::before {
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

        .empresa-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 122, 51, 0.3);
        }

        .empresa-card:hover::before {
            opacity: 1;
            box-shadow: 0 0 15px rgba(0, 255, 107, 0.4);
        }

        .empresa-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .empresa-card-title {
            font-size: 1.375rem;
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

        .empresa-card:hover .empresa-card-title {
            background: linear-gradient(90deg, #ffffff, #00FF6B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .empresa-card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .empresa-card-action {
            padding: 0.625rem;
            border-radius: 10px;
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .empresa-card-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .empresa-card-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #d1d5db;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .empresa-card-info-item i {
            color: #007A33;
            transition: all 0.3s ease;
        }

        .empresa-card:hover .empresa-card-info-item i {
            color: #00FF6B;
        }

        /* Area chips styling */
        .area-chip {
            padding: 0.35rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
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

        .area-tutoria {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .area-tutoria:hover {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.3) 0%, rgba(126, 34, 206, 0.3) 100%);
        }

        .area-mídia\/design {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .area-mídia\/design:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.3) 0%, rgba(5, 150, 105, 0.3) 100%);
        }

        .area-suporte {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .area-suporte:hover {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.3) 0%, rgba(217, 119, 6, 0.3) 100%);
        }

        .area-design {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2) 0%, rgba(126, 34, 206, 0.2) 100%);
            color: #c4b5fd;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }

        .area-design:hover {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.3) 0%, rgba(126, 34, 206, 0.3) 100%);
        }

        .area-redes {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(217, 119, 6, 0.2) 100%);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .area-redes:hover {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.3) 0%, rgba(217, 119, 6, 0.3) 100%);
        }

        /* Ver vagas link styling */
        .ver-vagas-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: linear-gradient(to right, rgba(0, 122, 51, 0.1), rgba(0, 122, 51, 0.2));
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 122, 51, 0.2);
        }

        .ver-vagas-link:hover {
            background: linear-gradient(to right, rgba(0, 122, 51, 0.2), rgba(0, 122, 51, 0.3));
            transform: translateX(5px);
        }

        .ver-vagas-link::before {
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

        .ver-vagas-link:hover::before {
            transform: translateX(100%);
        }

        .ver-vagas-link i {
            transition: transform 0.3s ease;
        }

        .ver-vagas-link:hover i {
            transform: translateX(5px);
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
            border-color: #007A33 !important;
            box-shadow: 0 0 0 2px rgba(0, 122, 51, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            outline: none !important;
            background-color: rgba(40, 40, 40, 0.9) !important;
        }

        .custom-input::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
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
            .empresa-card {
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
                    <a href="gerenciar_empresas.php" class="sidebar-link active">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link">
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
                    <a href="gerenciar_empresas.php" class="sidebar-link active">
                        <i class="fas fa-building w-5 mr-3"></i>
                        Gerenciar Empresas
                    </a>
                    <a href="vagas.php" class="sidebar-link">
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
                    <h1 class="text-xl font-bold text-white text-center sm:text-left w-full sm:w-auto">Gerenciamento de Empresas</h1>

                    <!-- Admin Badge - Versão Visual Aprimorada -->
                    <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-end">
                        <div class="admin-indicator rounded-lg px-3 py-2 flex w-10 items-center gap-2">
                            <div class="relative">
                                <i class="fas fa-user-shield text-green-400 text-sm"></i>
                            </div>
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
                    <span class="text-white">Gerenciar Empresas</span>
                </div>

                <!-- Actions Bar -->
                <div class="mb-8 action-bar p-4 sm:p-5 fade-in">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full">
                            <button id="addEmpresaBtn" class="custom-btn custom-btn-primary w-full sm:w-auto">
                                <i class="fas fa-plus btn-icon"></i>
                                <span>Nova Empresa</span>
                            </button>
                            <button id="relatorioEmpresasBtn" class="custom-btn custom-btn-secondary w-full sm:w-auto">
                                <i class="fas fa-file-alt btn-icon"></i>
                                <span>Gerar Relatório</span>
                            </button>
                            <div class="search-input-container relative w-full sm:w-64">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="searchEmpresa" placeholder="Buscar empresa..." class="custom-input pl-10 pr-4 py-2.5 w-full">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid de Empresas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" id="empresasGrid">
                    <?php
                    $dados = $select_model->concedentes();
                    if (empty($dados)): ?>
                        <div class="col-span-3 text-center py-16 text-gray-400 fade-in">
                            <i class="fas fa-building text-5xl mb-4 text-gray-600 opacity-30"></i>
                            <p class="text-xl">Nenhuma empresa cadastrada no momento.</p>
                            <button id="firstEmpresaBtn" class="mt-6 custom-btn custom-btn-primary">
                                <i class="fas fa-plus btn-icon"></i>
                                <span>Cadastrar Primeira Empresa</span>
                            </button>
                        </div>
                    <?php else: ?>
                        <?php
                        $delay = 0;
                        foreach ($dados as $dado):
                            $delay += 100;
                        ?>
                            <div class="empresa-card slide-up" style="animation-delay: <?= $delay ?>ms;" data-empresa-id="<?= htmlspecialchars($dado['id']) ?>">
                                <div class="empresa-card-header">
                                    <h3 class="empresa-card-title"><?= htmlspecialchars($dado['nome']) ?></h3>
                                    <div class="empresa-card-actions">
                                        <button class="empresa-card-action text-gray-400 hover:text-primary-400 edit-btn" data-modal-id="editarEmpresaModal-<?= $dado['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="empresa-card-action text-red-500 hover:text-red-400 delete-btn" data-modal-id="excluirEmpresaModal-<?= $dado['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="empresa-card-info">
                                    <div class="empresa-card-info-item">
                                        <i class="fas fa-map-marker-alt w-5 text-center"></i>
                                        <span><?= htmlspecialchars($dado['endereco']) ?: 'Não informado' ?></span>
                                    </div>
                                    <?php if (!empty($dado['nome_contato'])): ?>
                                        <div class="empresa-card-info-item">
                                            <i class="fas fa-user w-5 text-center"></i>
                                            <span><?= htmlspecialchars($dado['nome_contato']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="empresa-card-info-item">
                                        <i class="fab fa-whatsapp w-5 text-center text-green-400"></i>
                                        <?php if (!empty($dado['contato'])): ?>
                                            <?php $telefoneLimpo = preg_replace('/\D/', '', $dado['contato']); ?>
                                            <a href="https://wa.me/55<?= $telefoneLimpo ?>" target="_blank" class="hover:underline text-green-400">
                                                <?= htmlspecialchars($dado['contato']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span>Não informado</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mt-5 pt-4 border-t border-gray-700/50">
                                    <a href="./vagas.php?empresa_id=<?= htmlspecialchars($dado['id']) ?>" class="ver-vagas-link text-primary-400 font-medium">
                                        <span>Ver vagas</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="mt-5 pt-4 ">
                                    <a href="javascript:void(0)" onclick="copiarLink()" class="ver-vagas-link text-primary-400 font-medium">
                                        <span>Formulário Concedentes</span>
                                        <i class="fas fa-copy"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Modal de Editar Empresa -->
                            <div id="editarEmpresaModal-<?= $dado['id'] ?>" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                                <div class="candidatura-modal rounded-lg p-8 max-w-md w-full mx-4">
                                    <h2 class="text-2xl font-bold mb-6 text-white slide-up">Editar Empresa</h2>
                                    <form action="../controllers/controller_editar_excluir.php" method="post" class="space-y-4">
                                        <input type="hidden" name="id_editar_empresa" value="<?= htmlspecialchars($dado['id']) ?>">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300">Nome da Empresa</label>
                                            <input type="text" name="nome_editar_empresa" value="<?= htmlspecialchars($dado['nome']) ?>" class="custom-input mt-1" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300">Nome do Contato</label>
                                            <input type="text" name="nome_contato_editar_empresa" value="<?= htmlspecialchars($dado['nome_contato']) ?: '' ?>" class="custom-input mt-1" placeholder="Nome da pessoa responsável">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300">Endereço Completo</label>
                                            <input type="text" name="endereco_editar_empresa" value="<?= htmlspecialchars($dado['endereco']) ?: '' ?>" class="custom-input mt-1" placeholder="Rua, número, cidade - UF">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300">Telefone de Contato</label>
                                            <input type="tel" name="contato_editar_empresa" value="<?= htmlspecialchars($dado['contato']) ?: '' ?>" placeholder="(XX) XXXXX-XXXX" class="custom-input mt-1 telefone-input">
                                        </div>
                                        <div class="mt-6 flex justify-end space-x-4">
                                            <button type="button" class="custom-btn custom-btn-secondary close-btn" data-modal-id="editarEmpresaModal-<?= $dado['id'] ?>">
                                                <i class="fas fa-times btn-icon"></i>
                                                <span>Cancelar</span>
                                            </button>
                                            <button type="submit" class="custom-btn custom-btn-primary">
                                                <i class="fas fa-save btn-icon"></i>
                                                <span>Salvar Empresa</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal de Excluir Empresa -->
                            <div id="excluirEmpresaModal-<?= $dado['id'] ?>" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
                                <div class="candidatura-modal rounded-lg p-6 max-w-md w-full mx-4">
                                    <div class="text-center mb-6">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/10 text-red-500 mb-4">
                                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-white slide-up">Confirmar Exclusão</h3>
                                        <p class="text-gray-400 mt-2">Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita.</p>
                                    </div>
                                    <form action="../controllers/controller_editar_excluir.php" method="post">
                                        <input type="hidden" name="id_excluir_empresa" value="<?= htmlspecialchars($dado['id']) ?>">
                                        <div class="flex justify-center space-x-4">
                                            <button type="button" class="custom-btn custom-btn-secondary close-btn" data-modal-id="excluirEmpresaModal-<?= $dado['id'] ?>">
                                                <i class="fas fa-times btn-icon"></i>
                                                <span>Cancelar</span>
                                            </button>
                                            <button type="submit" class="custom-btn bg-red-500 hover:bg-red-600 text-white">
                                                <i class="fas fa-trash-alt btn-icon"></i>
                                                <span>Excluir Empresa</span>
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

        <!-- Modal de Cadastro de Nova Empresa -->
        <div id="novaEmpresaModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
            <div class="candidatura-modal rounded-lg p-8 max-w-md w-full mx-4">
                <h2 class="text-2xl font-bold mb-6 text-white slide-up">Nova Empresa</h2>
                <form action="../controllers/controller.php" id="novaEmpresaForm" method="post" class="space-y-4">
                    <input type="hidden" name="empresa_id" value="">
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Nome da Empresa</label>
                        <input type="text" name="nome" class="custom-input mt-1" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Nome do Contato</label>
                        <input type="text" name="nome_contato" class="custom-input mt-1" placeholder="Nome da pessoa responsável">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Endereço Completo</label>
                        <input type="text" name="endereco" class="custom-input mt-1" placeholder="Rua, número, cidade - UF">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Telefone de Contato</label>
                        <input type="tel" name="telefone" placeholder="(XX) XXXXX-XXXX" class="custom-input mt-1 telefone-input">
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" class="custom-btn custom-btn-secondary close-btn" data-modal-id="novaEmpresaModal">
                            <i class="fas fa-times btn-icon"></i>
                            <span>Cancelar</span>
                        </button>
                        <button type="submit" class="custom-btn custom-btn-primary">
                            <i class="fas fa-save btn-icon"></i>
                            <span>Salvar Empresa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de Relatório com Filtros -->
        <div id="relatorioEmpresasModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
            <div class="candidatura-modal rounded-lg p-8 max-w-md w-full mx-4">

                <form id="formRelatorioEmpresas" action="relatorio/relatorio_empresas.php" method="get" target="_blank" class="space-y-4">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-500/10 text-primary-400 mb-4">
                            <i class="fas fa-file-pdf text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white slide-up">Gerar Relatório de Empresas</h3>
                        <p class="text-gray-400 mt-2">Selecione as opções para gerar o relatório.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300">Empresa</label>
                        <select name="empresa_id" class="custom-input mt-1">
                            <option value="">Todas as Empresas</option>
                            <?php
                            $dados = $select_model->concedentes();
                            foreach ($dados as $dado): ?>
                                <option value="<?= htmlspecialchars($dado['id']) ?>"><?= htmlspecialchars($dado['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" class="custom-btn custom-btn-secondary close-btn" data-modal-id="relatorioEmpresasModal">
                            <i class="fas fa-times btn-icon"></i>
                            <span>Cancelar</span>
                        </button>
                        <button type="submit" class="custom-btn custom-btn-primary">
                            <i class="fas fa-file-alt btn-icon"></i>
                            <span>Gerar Relatório</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const novaEmpresaModal = document.getElementById('novaEmpresaModal');
            const novaEmpresaForm = document.getElementById('novaEmpresaForm');
            const addEmpresaBtn = document.getElementById('addEmpresaBtn');
            const firstEmpresaBtn = document.getElementById('firstEmpresaBtn');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const closeSidebar = document.getElementById('closeSidebar');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const searchInput = document.getElementById('searchEmpresa');
            const empresasGrid = document.getElementById('empresasGrid');
            const relatorioEmpresasBtn = document.getElementById('relatorioEmpresasBtn');
            const relatorioEmpresasModal = document.getElementById('relatorioEmpresasModal');
            const formRelatorioEmpresas = document.getElementById('formRelatorioEmpresas');
            let currentModalId = null;

            // GSAP Animations
            gsap.from('.action-bar', {
                opacity: 0,
                y: 20,
                duration: 0.5,
                ease: 'power2.out'
            });
            gsap.from('.empresa-card', {
                opacity: 0,
                y: 50,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power3.out'
            });

            // Máscara de telefone para todos os inputs de telefone
            document.querySelectorAll('.telefone-input').forEach(input => {
                input.addEventListener('input', (e) => {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 11) {
                        value = value.slice(0, 11);
                    }
                    if (value.length <= 10) {
                        value = value.replace(/(\d{2})(\d{0,4})(\d{0,4})/, '($1) $2-$3');
                    } else {
                        value = value.replace(/(\d{2})(\d{0,5})(\d{0,4})/, '($1) $2-$3');
                    }
                    e.target.value = value.trim();
                });
            });

            // Limpar caracteres não numéricos antes de enviar qualquer formulário
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    const telefoneInput = form.querySelector('input[name="telefone"]');
                    if (telefoneInput) {
                        telefoneInput.value = telefoneInput.value.replace(/\D/g, '');
                    }
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
                console.log('Tentando abrir modal:', modalId); // Debug
                if (currentModalId) {
                    closeModal(currentModalId);
                }

                const modal = document.getElementById(modalId);
                if (!modal) {
                    console.error('Modal não encontrada para o ID:', modalId);
                    return;
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                const modalContent = modal.querySelector('.candidatura-modal');
                if (modalContent) {
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

                currentModalId = modalId;
            }

            function closeModal(modalId) {
                console.log('Fechando modal:', modalId); // Debug
                const modal = document.getElementById(modalId);
                if (!modal) {
                    console.error('Modal não encontrada para o ID:', modalId);
                    return;
                }

                const modalContent = modal.querySelector('.candidatura-modal');
                if (modalContent) {
                    gsap.to(modalContent, {
                        opacity: 0,
                        scale: 0.9,
                        duration: 0.3,
                        ease: 'power2.in',
                        onComplete: () => {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                            currentModalId = null;
                        }
                    });
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    currentModalId = null;
                }
            }

            // Delegar eventos para botões de Editar e Excluir
            empresasGrid.addEventListener('click', (e) => {
                const editBtn = e.target.closest('.edit-btn');
                const deleteBtn = e.target.closest('.delete-btn');

                if (editBtn) {
                    const modalId = editBtn.getAttribute('data-modal-id');
                    console.log('Botão Editar clicado, modalId:', modalId); // Debug
                    openModal(modalId);
                }

                if (deleteBtn) {
                    const modalId = deleteBtn.getAttribute('data-modal-id');
                    console.log('Botão Excluir clicado, modalId:', modalId); // Debug
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

            // Nova Empresa Modal
            addEmpresaBtn.addEventListener('click', () => {
                console.log('Botão Nova Empresa clicado'); // Debug
                openModal('novaEmpresaModal');
            });

            if (firstEmpresaBtn) {
                firstEmpresaBtn.addEventListener('click', () => {
                    console.log('Botão Cadastrar Primeira Empresa clicado'); // Debug
                    openModal('novaEmpresaModal');
                });
            }

            // Fechar modais ao clicar fora
            document.querySelectorAll('.fixed.inset-0').forEach(modalContainer => {
                modalContainer.addEventListener('click', (e) => {
                    if (e.target === modalContainer && currentModalId === modalContainer.id) {
                        closeModal(modalContainer.id);
                    }
                });
            });

            // Botão para abrir o modal de relatório
            relatorioEmpresasBtn.addEventListener('click', () => {
                openModal('relatorioEmpresasModal');
            });

            // Filtragem de empresas
            function aplicarFiltros() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const empresaCards = document.querySelectorAll('.empresa-card');
                let visibleCount = 0;

                empresaCards.forEach((card, index) => {
                    const nome = card.querySelector('.empresa-card-title').textContent.toLowerCase();
                    const endereco = card.querySelector('.empresa-card-info-item:first-child span').textContent.toLowerCase();
                    const contato = card.querySelector('.empresa-card-info-item:last-child span, .empresa-card-info-item:last-child a').textContent.toLowerCase();

                    const matchSearch = searchTerm === '' ||
                        nome.includes(searchTerm) ||
                        endereco.includes(searchTerm) ||
                        contato.includes(searchTerm);

                    if (matchSearch) {
                        card.style.display = '';
                        visibleCount++;
                        gsap.fromTo(card, {
                            opacity: 0,
                            y: 20
                        }, {
                            opacity: 1,
                            y: 0,
                            duration: 0.3,
                            delay: index * 0.05
                        });
                    } else {
                        gsap.to(card, {
                            opacity: 0,
                            y: 20,
                            duration: 0.3,
                            onComplete: () => {
                                card.style.display = 'none';
                            }
                        });
                    }
                });

                const noResultsMessage = document.getElementById('noResultsMessage');
                if (visibleCount === 0 && !noResultsMessage) {
                    const message = document.createElement('div');
                    message.id = 'noResultsMessage';
                    message.className = 'col-span-3 text-center py-8 text-gray-400 fade-in';
                    message.innerHTML = `
                        <i class="fas fa-search text-4xl mb-4 text-gray-600 opacity-30"></i>
                        <p class="text-lg">Nenhuma empresa encontrada com os filtros atuais.</p>
                        <button id="clearFiltersBtn" class="mt-4 custom-btn custom-btn-secondary">
                            <i class="fas fa-times-circle btn-icon"></i>
                            <span>Limpar Filtros</span>
                        </button>
                    `;
                    document.getElementById('empresasGrid').appendChild(message);
                    document.getElementById('clearFiltersBtn').addEventListener('click', () => {
                        searchInput.value = '';
                        aplicarFiltros();
                    });
                } else if (visibleCount > 0 && noResultsMessage) {
                    noResultsMessage.remove();
                }
            }

            // Adicionar evento de input com debounce para melhor performance
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    aplicarFiltros();
                }, 300);
            });

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

            // Função para copiar o link
            window.copiarLink = function() {
                const link = 'https://salaberga.com.br/portalsalaberga/app/subsystems/estagio/views/form_concedentes.php';
                navigator.clipboard.writeText(link).then(() => {
                    showToast('Link copiado com sucesso!', 'success');
                }).catch(() => {
                    showToast('Erro ao copiar o link', 'error');
                });
            };
        });
    </script>
</body>

</html>