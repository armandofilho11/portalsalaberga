<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Cadastro de Concedente</title>
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
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2) 0%, rgba(126, 34, 206, 0.2) 100%);
            color: #c4b5fd;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }

        .area-tutoria:hover {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.3) 0%, rgba(126, 34, 206, 0.3) 100%);
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
<div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        

        <!-- Mobile Sidebar Toggle -->
        <div class="md:hidden fixed top-4 left-4 z-50">
            <button id="sidebarToggle" class="bg-dark-50 p-2 rounded-lg shadow-md hover:bg-dark-100 transition-all">
                <i class="fas fa-bars text-primary-400"></i>
            </button>
        </div>

        <!-- Mobile Sidebar -->
        

        <!-- Conteúdo principal -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-dark-400">
            <!-- Header -->
            

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-6 sm:py-8 w-full">
                <!-- Breadcrumbs -->
                <div class="text-sm text-gray-400 mb-6 flex items-center">
                    <a href="./dashboard.php" class="hover:text-primary-400 transition-colors">Dashboard</a>
                    <span class="mx-2 text-gray-600">/</span>
                    <span class="text-white">Vagas</span>
                </div>
            
            <div class="candidatura-modal rounded-lg p-8 max-w-4x2 w-full mx-4 bg-dark-100 shadow-lg">
                <h2 class="text-2xl font-bold mb-6 text-white text-left">Dados da Concedente</h2>
                <form action="../controllers/controller_concedente/controller_concedente.php" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <select name="tipo_instituicao" class="custom-input w-full" required>
                                <option value="">Tipo de Instituição</option>
                                <option value="publica">Pública</option>
                                <option value="privada">Privada</option>
                            </select>
                        </div>
                        <div>
                            <select name="rede" class="custom-input w-full">
                                <option value="">Rede</option>
                                <option value="federal">Federal</option>
                                <option value="estadual">Estadual</option>
                                <option value="municipal">Municipal</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" name="nome_fantasia" class="custom-input w-full" required placeholder="Nome Fantasia">
                        </div>
                        <div>
                            <input type="text" name="razao_social" class="custom-input w-full" required placeholder="Razão Social">
                        </div>
                        <div>
                            <input type="text" name="cnpj" class="custom-input w-full" required placeholder="CNPJ">
                        </div>
                        <div>
                            <input type="text" name="telefone_institucional" class="custom-input w-full" placeholder="Telefone Institucional">
                        </div>
                        <div>
                        <input type="email" name="email_institucional" class="custom-input w-full" placeholder="E-mail Institucional">
                        </div>
                        <div>
                        <input type="text" name="especificacao_atividade" class="custom-input w-full" placeholder="Especificação da Atividade: Descreva a atividade principal da concedente">
                        </div>
                    </div>
                    
                    
                    <h2 class="text-2xl font-bold mb-6 text-white">Dados do Representante Legal</h2>
                    
                
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                    <input type="text" name="nome_representante" class="custom-input w-full" placeholder="Nome do Representante">
                    </div>
                        <div>
                            <input type="email" name="email_representante" class="custom-input w-full" placeholder="E-mail do Representante Legal">
                        </div>
                        <div>
                            <input type="text" name="cpf_representante" class="custom-input w-full" placeholder="CPF do Representante Legal">
                        </div>
                        <div>
                            <input type="text" name="rg_representante" class="custom-input w-full" placeholder="RG do Representante Legal">
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold mb-4 text-white text-left">Dados do Supervisor </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" name="nome_supervisor" class="custom-input w-full" placeholder="Nome do Supervisor do Estágio">
                        </div>
                        <div>
                            <input type="email" name="email_supervisor" class="custom-input w-full" placeholder="E-mail do Supervisor do Estágio">
                        </div>
                        <div>
                            <input type="text" name="celular_supervisor" class="custom-input w-full" placeholder="Celular do Supervisor">
                        </div>
                        <div>
                            <input type="text" name="whats_do_supervisor" class="custom-input w-full" placeholder="Whatssap do Supervisor">
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold mb-6 text-white text-left">Endereço</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" name="endereco" class="custom-input w-full" placeholder="Endereço">
                        </div>
                        <div>
                            <input type="text" name="numero" class="custom-input w-full" placeholder="Número">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" name="complemento" class="custom-input w-full" placeholder="Complemento">
                        </div>
                        <div>
                            <input type="text" name="cep" class="custom-input w-full" placeholder="CEP">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <input type="text" name="bairro" class="custom-input w-full" placeholder="Bairro">
                        </div>
                        <div>
                            <input type="text" name="municipio" class="custom-input w-full" placeholder="Município">
                        </div>
                    </div>
                    <div>
                        <textarea name="observacoes" class="custom-input w-full" rows="2" placeholder="Observações sobre a concedente"></textarea>
                    </div>
                    <div class="flex justify-end gap-4 mt-8">
                       
                        <button type="submit" class="custom-btn custom-btn-primary">
                            <i class="fas fa-save btn-icon"></i>
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
</div>
</main>
</div>
</body>

</html>