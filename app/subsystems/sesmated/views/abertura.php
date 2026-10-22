<?php
require_once('../../../main/models/sessions.php');
$session = new sessions;
$session->autenticar_session();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura sesmated</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --background-color: #0a0a0a;
            --text-color: #ffffff;
            --header-color: #00b348;
            --accent-color: #ffb733;
            --card-bg: rgba(30, 30, 30, 0.95);
            --header-bg: rgba(15, 15, 15, 0.98);
            --success-color: #10b981;
            --danger-color: #ef4444;
        }
        body {
            background: radial-gradient(ellipse at top, #1a1a1a 0%, #0a0a0a 100%);
            color: var(--text-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
        }
        .header-bg {
            background: linear-gradient(135deg, var(--header-bg) 0%, rgba(0, 0, 0, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        .card-bg {
            background: linear-gradient(145deg, var(--card-bg) 0%, rgba(25, 25, 25, 0.95) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-bg:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(0, 179, 72, 0.15);
            border-color: var(--accent-color);
        }
        .pulse-glow {
            animation: pulseGlow 2s ease-in-out infinite alternate;
        }
        @keyframes pulseGlow {
            from { box-shadow: 0 4px 20px rgba(0, 179, 72, 0.3); }
            to { box-shadow: 0 8px 40px rgba(0, 179, 72, 0.5); }
        }
        .main-title {
            font-size: clamp(1.5rem, 6vw, 2.5rem);
            line-height: 1.2;
        }
        .user-chip {
            background: linear-gradient(145deg, #232d25 0%, #181f1a 100%);
            border: 1px solid #1f3a26;
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: clamp(0.75rem, 2vw, 0.875rem);
            font-weight: 600;
            color: #e5e7eb;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.08);
        }
        .user-chip-desktop {
            position: absolute;
            top: 0;
            right: 0;
        }
        /* Ajuste de responsividade do header */
        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 100%;
            flex-direction: row;
        }
        .header-title-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }
        @media (max-width: 640px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                align-items: center;
            }
            .user-chip-desktop {
                position: relative;
                top: auto;
                right: auto;
                margin-top: 1rem;
            }
            .header-title-section {
                align-items: center;
                text-align: center;
            }
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 2rem;
            width: 100%;
            margin-top: 2rem;
        }
        .dashboard-card-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            font-size: 2rem;
        }
        .btn-acessar {
            background: linear-gradient(135deg, var(--header-color) 0%, #00a040 100%);
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 1rem;
            padding: 0.7rem 1.7rem;
            box-shadow: 0 3px 14px rgba(0,179,72,0.13), 0 1px 4px rgba(255,183,51,0.10);
            transition: transform 0.18s, box-shadow 0.18s, background 0.18s;
            cursor: pointer;
            outline: none;
        }
        .btn-acessar:hover, .btn-acessar:focus {
            background: linear-gradient(90deg, #00b348 0%, #ffe29f 100%);
            color: #181818;
            transform: scale(1.04) translateY(-1px);
            box-shadow: 0 6px 18px rgba(0,179,72,0.16), 0 1.5px 6px rgba(255,183,51,0.12);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="header-bg z-50">
        <div class="container-responsive py-4 px-4 sm:px-8 relative">
            <div class="header-content">
                <div class="header-title-section">
                    <div class="flex items-start sm:items-center justify-start sm:justify-center gap-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 via-emerald-600 to-green-700 flex items-center justify-center pulse-glow">
                            <i class="fas fa-seedling text-white text-lg"></i>
                        </div>
                        <h1 class="main-title font-black bg-gradient-to-r from-green-400 via-emerald-500 to-green-600 bg-clip-text text-transparent text-left sm:text-center">
                            Abertura Sesmated
                        </h1>
                    </div>
                    <p class="text-gray-400 text-xs font-medium tracking-wider uppercase text-left sm:text-center">Painel de Tarefas do Evento</p>
                </div>
                <div class="user-chip user-chip-desktop">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <i class="fas fa-user text-green-300 text-xs"></i>
                    </div>
                    <span class="text-gray-100"><?=$_SESSION['Nome']?></span>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-8 py-12">
        <div class="dashboard-grid">
            <!-- Card Grito de Guerra -->
            <div class="card-bg rounded-3xl p-8 text-center">
                <div class="dashboard-card-icon bg-gradient-to-br from-orange-500 to-yellow-600">
                    <i class="fas fa-bullhorn text-white"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Grito de Guerra</h2>
                <p class="text-gray-300 mb-6">Confirmação do grito de guerra do curso.</p>
                <a href="grito.php" class="btn-acessar">Acessar</a>
            </div>
            <!-- Card Mascote -->
            <div class="card-bg rounded-3xl p-8 text-center">
                <div class="dashboard-card-icon bg-gradient-to-br from-orange-400 to-green-500">
                    <i class="fas fa-paw text-white"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Mascote</h2>
                <p class="text-gray-300 mb-6">Avaliação do mascote do curso.</p>
                <a href="mascote.php" class="btn-acessar">Acessar</a>
            </div>
            <!-- Card Logomarca -->
            <div class="card-bg rounded-3xl p-8 text-center">
                <div class="dashboard-card-icon bg-gradient-to-br from-blue-500 to-cyan-600">
                    <i class="fas fa-palette text-white"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Logomarca</h2>
                <p class="text-gray-300 mb-6">Avaliação da logomarca do evento.</p>
                <a href="logo.php" class="btn-acessar">Acessar</a>
            </div>
            <!-- Card Cordel -->
            <div class="card-bg rounded-3xl p-8 text-center">
                <div class="dashboard-card-icon bg-gradient-to-br from-purple-500 to-violet-600">
                    <i class="fas fa-scroll text-white"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Cordel</h2>
                <p class="text-gray-300 mb-6">Avaliação do cordel apresentado pelo curso.</p>
                <a href="cordel.php" class="btn-acessar">Acessar</a>
            </div>
            <!-- Card Paródia -->
            <div class="card-bg rounded-3xl p-8 text-center">
                <div class="dashboard-card-icon bg-gradient-to-br from-pink-500 to-red-500">
                    <i class="fas fa-music text-white"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Paródia</h2>
                <p class="text-gray-300 mb-6">Avaliação da paródia apresentada pelo curso.</p>
                <a href="parodia.php" class="btn-acessar">Acessar</a>
            </div>
            <!-- Card Vestimentas Sustentáveis -->
           <!-- <div class="card-bg rounded-3xl p-8 text-center">
                <div class="dashboard-card-icon bg-gradient-to-br from-green-500 to-emerald-600">
                    <i class="fas fa-tshirt text-white"></i>
                </div>
                <h2 class="text-xl font-bold mb-2">Vestimentas </h2>
                <p class="text-gray-300 mb-6">Cadastro e avaliação das vestimentas sustentáveis.</p>
                <a href="vestimentas_sustentaveis.php" class="btn-acessar">Acessar</a>
            </div>-->
        </div>
    </main>
</body>
</html>
