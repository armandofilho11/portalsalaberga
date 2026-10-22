<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copa Grêmio 2025 - Programação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Design%20sem%20nome-MOpK2hbpuoqfoF8sir0Ue6SvciAArc.svg" type="image/svg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary-500: #007d40;
            --primary-600: #006a36;
            --primary-700: #005A24;
            --primary-50: #f0fdf4;
            --primary-100: #dcfce7;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            line-height: 1.6;
            color: #1e293b;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 50%, #059669 100%);
        }
        
        .day-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .day-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .day-header {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 100%);
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }
        
        .day-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        }
        
        .games-container {
            padding: 2rem;
        }
        
        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .match-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            border: 2px solid #f1f5f9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .match-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--sport-color, var(--primary-500));
            transition: width 0.3s ease;
        }
        
        .match-card:hover {
            border-color: var(--sport-color, var(--primary-500));
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .match-card:hover::before {
            width: 6px;
        }
        
        .match-card.futsal { --sport-color: #ef4444; }
        .match-card.teqvolei { --sport-color: #3b82f6; }
        .match-card.queimada { --sport-color: #f59e0b; }
        .match-card.x2futsal { --sport-color: #8b5cf6; }
        .match-card.volei { --sport-color: #10b981; }
        .match-card.abertura { --sport-color: #6b7280; }
        
        .sport-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .sport-badge.futsal { 
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); 
            color: #dc2626; 
            border: 1px solid #fca5a5;
        }
        .sport-badge.teqvolei { 
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); 
            color: #2563eb; 
            border: 1px solid #93c5fd;
        }
        .sport-badge.queimada { 
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); 
            color: #d97706; 
            border: 1px solid #fcd34d;
        }
        .sport-badge.x2futsal { 
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); 
            color: #7c3aed; 
            border: 1px solid #c4b5fd;
        }
        .sport-badge.volei { 
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); 
            color: #059669; 
            border: 1px solid #6ee7b7;
        }
        .sport-badge.abertura { 
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); 
            color: #475569; 
            border: 1px solid #cbd5e1;
        }
        
        .time-badge {
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
            color: var(--primary-700);
            font-weight: 700;
            font-size: 0.85rem;
            padding: 8px 16px;
            border-radius: 10px;
            border: 2px solid var(--primary-200);
            display: inline-block;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 125, 64, 0.1);
            margin-bottom: 1rem;
        }
        
        .vs-divider {
            color: var(--primary-600);
            font-weight: 800;
            font-size: 0.875rem;
            background: var(--primary-50);
            padding: 8px 12px;
            border-radius: 8px;
            display: inline-block;
            margin: 0 12px;
            border: 1px solid var(--primary-100);
        }
        
        .team-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.85rem;
            line-height: 1.3;
            padding: 10px 12px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            flex: 1;
            text-align: center;
            word-wrap: break-word;
            hyphens: auto;
            min-width: 0;
        }
        
        .final-badge {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: 600;
            padding: 16px 24px;
            border-radius: 16px;
            font-size: 0.85rem;
            text-align: center;
            border: 2px solid #fcd34d;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
        }
        
        .day-navigation {
            position: sticky;
            top: 20px;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .nav-buttons-container {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-button {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background: #f8fafc;
            color: #64748b;
            cursor: pointer;
            white-space: nowrap;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-button:hover {
            background: var(--primary-50);
            color: var(--primary-700);
            border-color: var(--primary-200);
        }

        .nav-button.active {
            background: var(--primary-500);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 125, 64, 0.3);
        }

        @media (max-width: 768px) {
            .day-navigation {
                position: static;
                padding: 1.2rem;
                margin-bottom: 1.5rem;
            }
            
            .nav-buttons-container {
                gap: 0.75rem;
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-template-rows: auto auto auto;
            }
            
            .nav-button {
                padding: 14px 16px;
                font-size: 0.85rem;
                border-radius: 10px;
                min-width: auto;
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }
            
            .nav-button[data-day="all"] {
                grid-column: 1 / -1;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 1024px) {
            .nav-button {
                padding: 10px 18px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .team-name {
                font-size: 0.75rem;
                padding: 8px 10px;
                line-height: 1.2;
            }
            
            .vs-divider {
                padding: 6px 8px;
                font-size: 0.75rem;
                margin: 0 4px;
            }
            
            .time-badge {
                font-size: 0.75rem;
                padding: 6px 12px;
                border-radius: 8px;
            }
        }

        @media (max-width: 360px) {
            .nav-button {
                padding: 14px 10px;
                font-size: 0.85rem;
                min-height: 50px;
            }
            
            .nav-button[data-day="all"] {
                padding: 16px 16px;
                font-size: 0.95rem;
                min-height: 54px;
            }
            
            .day-navigation {
                padding: 1rem;
            }
        }
        
        .match-teams {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1rem;
            gap: 6px;
            flex-wrap: nowrap;
        }
        
        .ceremony-content {
            text-align: center;
            padding: 1rem 0;
        }
        
        .ceremony-title {
            font-weight: 600;
            color: #374151;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .ceremony-subtitle {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .games-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .day-header {
                padding: 1.5rem;
            }
            
            .games-container {
                padding: 1.5rem;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .team-name {
                font-size: 0.85rem;
                padding: 10px 12px;
            }
            
            .sport-badge {
                font-size: 0.7rem;
                padding: 6px 12px;
            }
            
            .day-navigation {
                position: static;
                overflow-x: auto;
                padding: 1rem;
            }
            
            .nav-button {
                white-space: nowrap;
                min-width: 120px;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Main Content -->
    <main class="w-full px-4 py-8 max-w-7xl mx-auto">
        <!-- Hero Section -->
        <div class="hero-gradient text-white rounded-3xl p-8 md:p-12 shadow-2xl mb-8">
            <div class="text-center relative">
                <div class="absolute inset-0  to-transparent rounded-3xl"></div>
                <div class="relative z-10">
                    <h1 class="hero-title text-2xl md:text-5xl font-bold mb-4 tracking-tight">
                        <i class="fas fa-trophy text-yellow-300 mr-2 drop-shadow-lg"></i>
                        Copa Grêmio 2025
                    </h1>
                   
                    <p class="text-green-200 text-sm">Desenvolvido por <a href="https://www.instagram.com/mth_fl/" target="_blank" class="text-yellow-300 hover:underline">Matheus Felix</a></p>
                </div>
            </div>
        </div>

        <!-- Day Navigation -->
        <div class="day-navigation">
            <div class="nav-buttons-container">
                <button class="nav-button active" data-day="all">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    <span>Todos os Dias</span>
                </button>
                <button class="nav-button" data-day="monday">
                    <i class="fas fa-calendar-day mr-1"></i>
                    <span>Segunda (11/08)</span>
                </button>
                <button class="nav-button" data-day="tuesday">
                    <i class="fas fa-calendar-day mr-1"></i>
                    <span>Terça (12/08)</span>
                </button>
                <button class="nav-button" data-day="wednesday">
                    <i class="fas fa-calendar-day mr-1"></i>
                    <span>Quarta (13/08)</span>
                </button>
                <button class="nav-button" data-day="thursday">
                    <i class="fas fa-calendar-day mr-1"></i>
                    <span>Quinta (14/08)</span>
                </button>
                <button class="nav-button" data-day="friday">
                    <i class="fas fa-calendar-day mr-1"></i>
                    <span>Sexta (15/08)</span>
                </button>
            </div>
        </div>

        <!-- Schedule Container -->
        <div id="schedule-container">
            <!-- Segunda-feira -->
            <div class="day-card" data-day="monday">
                <div class="day-header text-white relative">
                    <h3 class="font-bold text-2xl text-center relative z-10">Segunda-feira</h3>
                    <p class="text-center text-green-100 text-lg relative z-10 mt-1">11 de Agosto</p>
                </div>
                <div class="games-container">
                    <div class="games-grid">
                        <div class="match-card abertura">
                            <div class="time-badge">8h00</div>
                            <span class="sport-badge abertura">
                                <i class="fas fa-flag"></i>Abertura
                            </span>
                            <div class="ceremony-content">
                                <p class="ceremony-title">Cerimônia de Abertura</p>
                                <p class="ceremony-subtitle">Copa Grêmio 2025</p>
                            </div>
                        </div>
                        
                        <div class="match-card abertura">
                            <div class="time-badge">8h30</div>
                            <span class="sport-badge abertura">
                                <i class="fas fa-flag"></i>Abertura
                            </span>
                            <div class="ceremony-content">
                                <p class="ceremony-title">Cerimônia de Abertura</p>
                                <p class="ceremony-subtitle">Copa Grêmio 2025</p>
                            </div>
                        </div>
                        
                        <div class="match-card abertura">
                            <div class="time-badge">9h00</div>
                            <span class="sport-badge abertura">
                                <i class="fas fa-flag"></i>Abertura
                            </span>
                            <div class="ceremony-content">
                                <p class="ceremony-title">Cerimônia de Abertura</p>
                                <p class="ceremony-subtitle">Copa Grêmio 2025</p>
                            </div>
                        </div>
                        
                        <div class="match-card futsal">
                            <div class="time-badge">9h30</div>
                            <span class="sport-badge futsal">
                                <i class="fas fa-futbol"></i>Futsal
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Brasa</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">UZ 17</div>
                            </div>
                        </div>
                        
                        <div class="match-card futsal">
                            <div class="time-badge">10h00</div>
                            <span class="sport-badge futsal">
                                <i class="fas fa-futbol"></i>Futsal
                            </span>
                            <div class="match-teams">
                                <div class="team-name">ADM</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Inimigos do Gol</div>
                            </div>
                        </div>
                        
                        <div class="match-card futsal">
                            <div class="time-badge">10h30</div>
                            <span class="sport-badge futsal">
                                <i class="fas fa-futbol"></i>Futsal
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Independente</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">perd (1)</div>
                            </div>
                        </div>
                        
                        <div class="match-card futsal">
                            <div class="time-badge">11h00</div>
                            <span class="sport-badge futsal">
                                <i class="fas fa-futbol"></i>Futsal
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Independente</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">perd (2)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terça-feira -->
            <div class="day-card" data-day="tuesday">
                <div class="day-header text-white relative">
                    <h3 class="font-bold text-2xl text-center relative z-10">Terça-feira</h3>
                    <p class="text-center text-green-100 text-lg relative z-10 mt-1">12 de Agosto</p>
                </div>
                <div class="games-container">
                    <div class="games-grid">
                        <div class="match-card teqvolei">
                            <div class="time-badge">8h00</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Trainetes</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Esquerdas</div>
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">8h30</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Vascelona</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Joaoyerick</div>
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">9h00</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Niell e jv</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Federados</div>
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">9h30</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Venc (1)</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Esquerdas</div>
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">10h00</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Venc (2)</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Os Irmãos</div>
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">10h30</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Venc (3)</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Pladu G P</div>
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">11h00</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Batutinhas</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Ataca Fofo</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quarta-feira -->
            <div class="day-card" data-day="wednesday">
                <div class="day-header text-white relative">
                    <h3 class="font-bold text-2xl text-center relative z-10">Quarta-feira</h3>
                    <p class="text-center text-green-100 text-lg relative z-10 mt-1">13 de Agosto</p>
                </div>
                <div class="games-container">
                    <div class="games-grid">
                        <div class="match-card queimada">
                            <div class="time-badge">8h30</div>
                            <span class="sport-badge queimada">
                                <i class="fas fa-fire"></i>Queimada
                            </span>
                            <div class="match-teams">
                                <div class="team-name">UZ 17</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Tropa da Sarrada</div>
                            </div>
                        </div>
                        
                        <div class="match-card queimada">
                            <div class="time-badge">9h00</div>
                            <span class="sport-badge queimada">
                                <i class="fas fa-fire"></i>Queimada
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Enfermáticos</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Perd (1)</div>
                            </div>
                        </div>
                        
                        <div class="match-card queimada">
                            <div class="time-badge">9h30</div>
                            <span class="sport-badge queimada">
                                <i class="fas fa-fire"></i>Queimada
                            </span>
                            <div class="match-teams">
                                <div class="team-name">SJA</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Dia.bets</div>
                            </div>
                        </div>
                        
                        <div class="match-card x2futsal">
                            <div class="time-badge">10h00</div>
                            <span class="sport-badge x2futsal">
                                <i class="fas fa-running"></i>X2
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Bonde do GE</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Só Para Brincar</div>
                            </div>
                        </div>
                        
                        <div class="match-card x2futsal">
                            <div class="time-badge">10h30</div>
                            <span class="sport-badge x2futsal">
                                <i class="fas fa-running"></i>X2
                            </span>
                            <div class="match-teams">
                                <div class="team-name">JP e Davi</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Balança + Não Cai</div>
                            </div>
                        </div>
                        
                        <div class="match-card x2futsal">
                            <div class="time-badge">11h00</div>
                            <span class="sport-badge x2futsal">
                                <i class="fas fa-running"></i>X2
                            </span>
                            <div class="match-teams">
                                <div class="team-name">OS</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Gordos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quinta-feira -->
            <div class="day-card" data-day="thursday">
                <div class="day-header text-white relative">
                    <h3 class="font-bold text-2xl text-center relative z-10">Quinta-feira</h3>
                    <p class="text-center text-green-100 text-lg relative z-10 mt-1">14 de Agosto</p>
                </div>
                <div class="games-container">
                    <div class="games-grid">
                        <div class="match-card teqvolei">
                            <div class="time-badge">8h00</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>Semi TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Venc (4)</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Venc (5)</div>
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">8h30</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>Semi TeqVôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Venc (6)</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Venc (7)</div>
                            </div>
                        </div>
                        
                        <div class="match-card x2futsal">
                            <div class="time-badge">9h30</div>
                            <span class="sport-badge x2futsal">
                                <i class="fas fa-running"></i>Semi X2
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Venc (3)</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Venc (4)</div>
                            </div>
                        </div>
                        
                        <div class="match-card volei">
                            <div class="time-badge">10h00</div>
                            <span class="sport-badge volei">
                                <i class="fas fa-volleyball-ball"></i>Vôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">Enfermagem 3A</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">Edificações</div>
                            </div>
                        </div>
                        
                        <div class="match-card volei">
                            <div class="time-badge">10h30</div>
                            <span class="sport-badge volei">
                                <i class="fas fa-volleyball-ball"></i>Vôlei
                            </span>
                            <div class="match-teams">
                                <div class="team-name">YES ADM</div>
                                <div class="vs-divider">VS</div>
                                <div class="team-name">UZ 17</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sexta-feira -->
            <div class="day-card" data-day="friday">
                <div class="day-header text-white relative">
                    <h3 class="font-bold text-2xl text-center relative z-10">Sexta-feira</h3>
                    <p class="text-center text-green-100 text-lg relative z-10 mt-1">15 de Agosto</p>
                </div>
                <div class="games-container">
                    <div class="games-grid">

                    <div class="match-card volei">
                            <div class="time-badge">8h00</div>
                            <span class="sport-badge volei">
                                <i class="fas fa-fire"></i>Vôlei
                            </span>
                            <div class="final-badge">
                                 FINAL VÔLEI 
                            </div>
                        </div>

                        <div class="match-card queimada">
                            <div class="time-badge">8h30</div>
                            <span class="sport-badge queimada">
                                <i class="fas fa-fire"></i>Queimada 
                            </span>
                            <div class="final-badge">
                                 FINAL CARIMBO 
                            </div>
                        </div>
                        
                        <div class="match-card futsal">
                            <div class="time-badge">9h00</div>
                            <span class="sport-badge futsal">
                                <i class="fas fa-futbol"></i>Futsal 
                            </span>
                            <div class="final-badge">
                                 FINAL FUTSAL 
                            </div>
                        </div>
                        
                        <div class="match-card teqvolei">
                            <div class="time-badge">9h30</div>
                            <span class="sport-badge teqvolei">
                                <i class="fas fa-volleyball-ball"></i>TeqVôlei 
                            </span>
                            <div class="final-badge">
                                 FINAL TEQVÔLEI 
                            </div>
                        </div>
                        
                        <div class="match-card x2futsal">
                            <div class="time-badge">10h00</div>
                            <span class="sport-badge x2futsal">
                                <i class="fas fa-running"></i>X2 
                            </span>
                            <div class="final-badge">
                                 FINAL X2  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Day Navigation
        const navButtons = document.querySelectorAll('.nav-button');
        const dayCards = document.querySelectorAll('.day-card');
        
        navButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetDay = button.dataset.day;
                
                // Update active button
                navButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Show/hide day cards
                dayCards.forEach(card => {
                    if (targetDay === 'all' || card.dataset.day === targetDay) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Adicionar números aos jogos por modalidade
        const modalities = ['futsal', 'teqvolei', 'queimada', 'x2futsal', 'volei'];
        
        modalities.forEach(modality => {
            const matches = document.querySelectorAll(`.match-card.${modality}`);
            matches.forEach((match, index) => {
                const badge = match.querySelector('.sport-badge');
                if (badge && !match.classList.contains('abertura')) {
                    badge.innerHTML = `<i class="fas fa-${modality === 'futsal' ? 'futbol' : modality === 'queimada' ? 'fire' : modality === 'x2futsal' ? 'running' : 'volleyball-ball'}"></i> ${badge.textContent} #${index + 1}`;
                }
            });
        });
    </script>

</body>
</html>