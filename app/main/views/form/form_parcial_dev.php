<?php
// Inclua arquivos de configuração ou funções PHP necessários aqui
// require_once '../../config/sua_configuracao.php';
// require_once '../../models/seu_modelo.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação de Entregas - Sistema Compacto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                        'accent-green': '#00A651',
                        'dark-green': '#006B35',
                        'light-orange': '#FFD54F',
                        'gradient-start': '#008C45',
                        'gradient-end': '#00A651'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-padding-top: 80px; /* Ajusta o scroll para não esconder conteúdo sob a barra fixa */
        }

        body {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
            min-height: 100vh;
            padding-top: 90px; /* Espaço para a barra fixa */
        }

        /* Barra de Progresso Fixa - Melhorada */
        .progress-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(30, 41, 59, 0.98); /* Background mais escuro e menos transparente */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-bottom: 2px solid #008C45; /* Borda verde para destacar */
            padding: 0.75rem 1rem;
            z-index: 9999; /* Z-index muito alto para garantir que fique acima de tudo */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); /* Sombra mais pronunciada */
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        /* Efeito de destaque quando a página é rolada */
        .progress-fixed.scrolled {
            box-shadow: 0 4px 20px rgba(0, 140, 69, 0.4);
        }

        .progress-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .progress-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #e5e7eb;
            font-size: 0.875rem;
            font-weight: 500;
            min-width: 120px;
        }

        .progress-bar-fixed {
            background: #4b5563; /* Fundo mais escuro para contraste */
            height: 0.5rem;
            border-radius: 0.25rem;
            flex: 1;
            min-width: 200px;
            overflow: hidden;
            position: relative;
            border: 1px solid #6b7280; /* Borda para melhor visibilidade */
        }

        .progress-fill-fixed {
            background: linear-gradient(90deg, #008C45 0%, #00A651 100%);
            height: 100%;
            border-radius: 0.25rem;
            transition: width 0.3s ease;
            width: 0%;
            position: relative;
            box-shadow: 0 0 10px rgba(0, 166, 81, 0.5); /* Brilho na barra de progresso */
        }

        .progress-fill-fixed::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.2) 50%, transparent 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .stats-fixed {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: #d1d5db;
            flex-wrap: wrap;
        }

        .stat-item-fixed {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            background: rgba(75, 85, 99, 0.7); /* Fundo mais escuro */
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            border: 1px solid #6b7280;
        }

        .stat-number-fixed {
            font-weight: 600;
            color: #00A651;
            font-size: 0.875rem;
        }

        .progress-percentage {
            color: #00A651;
            font-weight: 600;
            font-size: 0.875rem;
            min-width: 40px;
            text-align: center;
            background: rgba(0, 140, 69, 0.1);
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            border: 1px solid rgba(0, 140, 69, 0.3);
        }

        .main-container {
            background: #374151;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid #4b5563;
            margin-top: 3rem; /* Espaço adicional após a barra fixa */
        }

        .header-compact {
            background: linear-gradient(135deg, #008C45 0%, #00A651 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 1rem 1rem 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .team-selector-compact {
            background: #4b5563;
            border: 1px solid #6b7280;
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1rem 1.5rem;
        }

        .custom-select-compact {
            background: #374151;
            border: 1px solid #6b7280;
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            width: 100%;
            color: #e5e7eb;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23008C45' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem;
        }

        .custom-select-compact:focus {
            outline: none;
            border-color: #008C45;
            box-shadow: 0 0 0 2px rgba(0, 140, 69, 0.2);
        }

        .custom-select-compact option {
            background: #374151;
            color: #e5e7eb;
        }

        .deliveries-container {
            padding: 0 1.5rem 1.5rem 1.5rem;
        }

        .sprint-card {
            background: #4b5563;
            border: 1px solid #6b7280;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sprint-header-compact {
            background: linear-gradient(135deg, #008C45 0%, #00A651 100%);
            color: white;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .delivery-grid {
            display: grid;
            gap: 0.5rem;
            padding: 1rem;
        }

        .delivery-row {
            display: grid;
            grid-template-columns: 1fr auto auto;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: #374151;
            border-radius: 0.5rem;
            border: 1px solid #4b5563;
            transition: all 0.2s ease;
        }

        .delivery-row:hover {
            background: #475569;
            border-color: #008C45;
            transform: translateX(2px);
        }

        .delivery-info-compact {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .delivery-title-compact {
            font-weight: 500;
            color: #f3f4f6;
            font-size: 0.875rem;
            line-height: 1.2;
        }

        .delivery-date-compact {
            color: #d1d5db;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .delivery-date-compact.urgent {
            color: #fca5a5;
        }

        .delivery-date-compact.warning {
            color: #fcd34d;
        }

        .delivery-date-compact.normal {
            color: #86efac;
        }

        .checkbox-compact {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #e5e7eb;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .checkbox-compact:hover {
            background: rgba(0, 140, 69, 0.1);
        }

        .checkbox-compact input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            border: 1.5px solid #6b7280;
            border-radius: 0.25rem;
            background: #374151;
            cursor: pointer;
            appearance: none;
            position: relative;
            transition: all 0.2s ease;
        }

        .checkbox-compact input[type="checkbox"]:checked {
            background: linear-gradient(135deg, #008C45 0%, #00A651 100%);
            border-color: #008C45;
        }

        .checkbox-compact input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 0.625rem;
        }

        .checkbox-compact input[type="checkbox"]:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 140, 69, 0.3);
        }

        .submit-section-compact {
            background: #4b5563;
            padding: 1rem 1.5rem;
            border-top: 1px solid #6b7280;
            border-radius: 0 0 1rem 1rem;
        }

        .submit-btn-compact {
            background: linear-gradient(135deg, #008C45 0%, #00A651 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 140, 69, 0.3);
        }

        .submit-btn-compact:hover {
            background: linear-gradient(135deg, #006B35 0%, #008C45 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 140, 69, 0.4);
        }

        /* Status visual dos itens */
        .delivery-row.completed {
            background: rgba(16, 185, 129, 0.1);
            border-color: #10b981;
        }

        .delivery-row.partial {
            background: rgba(245, 158, 11, 0.1);
            border-color: #f59e0b;
        }

        .delivery-row.pending {
            background: #374151;
        }

        /* Labels e textos */
        .team-selector-compact label {
            color: #e5e7eb;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            body {
                padding-top: 130px; /* Mais espaço para mobile */
            }

            .progress-content {
                flex-direction: column;
                gap: 0.5rem;
                align-items: stretch;
            }

            .progress-info {
                justify-content: center;
                min-width: auto;
            }

            .stats-fixed {
                justify-content: center;
                gap: 0.5rem;
            }

            .main-container {
                margin: 0.5rem;
            }

            .header-compact {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .delivery-row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
                text-align: center;
            }

            .checkbox-group-mobile {
                display: flex;
                justify-content: center;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding-top: 150px; /* Ainda mais espaço para telas muito pequenas */
            }

            .checkbox-group-mobile {
                flex-direction: column;
                gap: 0.5rem;
            }

            .checkbox-compact {
                justify-content: center;
            }

            .stat-item-fixed {
                font-size: 0.625rem;
                padding: 0.125rem 0.375rem;
            }
        }

        /* Animações */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }

        .stagger {
            animation-delay: calc(var(--index) * 0.05s);
        }

        /* Scrollbar customizada */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #4b5563;
        }

        ::-webkit-scrollbar-thumb {
            background: #6b7280;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #008C45;
        }

        /* Indicadores visuais para diferentes tipos de entrega */
        .delivery-row[data-type="documentation"] {
            border-left: 3px solid #3b82f6;
        }

        .delivery-row[data-type="development"] {
            border-left: 3px solid #10b981;
        }

        .delivery-row[data-type="design"] {
            border-left: 3px solid #f59e0b;
        }

        .delivery-row[data-type="database"] {
            border-left: 3px solid #8b5cf6;
        }

        /* Melhorias de acessibilidade */
        .checkbox-compact:focus-within {
            outline: 2px solid #008C45;
            outline-offset: 2px;
        }

        /* Transições suaves */
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        /* Efeito de pulsação na barra quando há mudanças */
        .progress-bar-fixed.updating {
            animation: pulse-bar 0.5s ease-in-out;
        }

        @keyframes pulse-bar {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Indicador de equipe selecionada na barra */
        .team-indicator {
            background: rgba(0, 140, 69, 0.2);
            color: #00A651;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid #008C45;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Botão para voltar ao topo */
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #008C45 0%, #00A651 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 999;
        }

        .back-to-top.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 140, 69, 0.4);
        }
    </style>
</head>
<body class="p-2 lg:p-4">
    <!-- Barra de Progresso Fixa -->
    <div class="progress-fixed" id="progress-fixed">
        <div class="progress-content">
            <div class="progress-info">
                <i class="fas fa-chart-line text-ceara-green"></i>
                <span>Progresso:</span>
            </div>
            
            <div class="progress-bar-fixed" id="progress-bar-container">
                <div id="progress-fill-fixed" class="progress-fill-fixed"></div>
            </div>
            
            <div class="progress-percentage" id="progress-percentage">0%</div>
            
            <div class="stats-fixed">
                <div class="stat-item-fixed">
                    <span class="stat-number-fixed" id="total-delivered-fixed">0</span>
                    <span>entregues</span>
                </div>
                <div class="stat-item-fixed">
                    <span class="stat-number-fixed" id="on-time-fixed">0</span>
                    <span>no prazo</span>
                </div>
                <div class="stat-item-fixed">
                    <span class="stat-number-fixed" id="completion-rate-fixed">0%</span>
                    <span>concluído</span>
                </div>
            </div>
            
            <div class="team-indicator" id="team-indicator" style="display: none;">
                <i class="fas fa-users"></i>
                <span id="selected-team">Nenhuma equipe</span>
            </div>
        </div>
    </div>

    <div class="main-container max-w-5xl mx-auto animate-slide-in">
        <!-- Header Compacto -->
        <div class="header-compact">
            <div class="flex items-center gap-3">
                <i class="fas fa-clipboard-check text-xl"></i>
                <div>
                    <h1 class="text-lg font-bold">Avaliação de Entregas</h1>
                    <p class="text-sm opacity-90">Sistema de Acompanhamento</p>
                </div>
            </div>
            <div class="text-right text-sm opacity-90">
                <div>Projeto Integrador</div>
                <div class="font-semibold">2024</div>
            </div>
        </div>

        <!-- Seleção de Equipe Compacta -->
        
        <form id="evaluation-form" action="../../controllers/controller_processar_avaliacao.php" method="POST">
            <div class="team-selector-compact">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-users text-ceara-green"></i>
                    <label for="equipe" class="font-medium text-sm">Equipe:</label>
                </div>
                <select id="equipe" name="equipe" required class="custom-select-compact">
                    <option value="">-- Selecione --</option>
                    <option value="Entrada e saída de alunos">Equipe 1 – Entrada e saída de alunos</option>
                    <option value="Gestão da alimentação escolar">Equipe 2 – Gestão da alimentação escolar</option>
                    <option value="Controle de estoque de materiais">Equipe 3 – Controle de estoque de materiais</option>
                    <option value="Gestão de estágio">Equipe 4 – Gestão de estágio</option>
                    <option value="Chamados de suporte">Equipe 5 – Chamados de suporte</option>
                    <option value="Gerência de espaços e equipamentos">Equipe 6 – Gerência de espaços e equipamentos</option>
                    <option value="Banco de questões">Equipe 7 – Banco de questões</option>
                    <option value="Biblioteca">Equipe 8 – Biblioteca</option>
                    <option value="Registros PCD">Equipe 9 – Registros PCD</option>
                    <option value="Tombamento">Equipe 10 – Tombamento</option>
                    <option value="Financeiro">Equipe 11 – Financeiro</option>
                    <option value="Sistema PDT">Equipe 12 – Sistema PDT</option>
                </select>
            </div>
            <div class="deliveries-container">
                <!-- Sprint 1 -->
                <div class="sprint-card" animate-slide-in stagger" style="--index: 1">
                    <div class="sprint-header-compact">
                        <i class="fas fa-rocket"></i>
                        <span>Sprint 1 - Documentação Inicial</span>
                    </div>
                    <div class="delivery-grid">
                        <div class="delivery-row" data-type="documentation">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Documento Descritivo</div>
                                <div class="delivery-date-compact normal">25/02</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint1_doc_entregue" name="sprint1_doc_entregue" type="checkbox" data-notes-target="#sprint1_doc_notes_container">
                                <label for="sprint1_doc_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint1_doc_prazo" name="sprint1_doc_prazo" type="checkbox">
                                <label for="sprint1_doc_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="documentation">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Requisitos Funcionais e Não Funcionais</div>
                                <div class="delivery-date-compact normal">25/02</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint1_reqs_entregue" name="sprint1_reqs_entregue" type="checkbox" data-notes-target="#sprint1_reqs_notes_container">
                                <label for="sprint1_reqs_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint1_reqs_prazo" name="sprint1_reqs_prazo" type="checkbox">
                                <label for="sprint1_reqs_prazo">No Prazo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sprint 2 -->
                <div class="sprint-card" animate-slide-in stagger" style="--index: 2">
                    <div class="sprint-header-compact">
                        <i class="fas fa-sitemap"></i>
                        <span>Sprint 2 - Diagramas UML</span>
                    </div>
                    <div class="delivery-grid">
                        <div class="delivery-row" data-type="documentation">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Diagrama de Caso de Uso</div>
                                <div class="delivery-date-compact normal">28/02</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint2_caso_uso_entregue" name="sprint2_caso_uso_entregue" type="checkbox" data-notes-target="#sprint2_caso_uso_notes_container">
                                <label for="sprint2_caso_uso_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint2_caso_uso_prazo" name="sprint2_caso_uso_prazo" type="checkbox">
                                <label for="sprint2_caso_uso_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="documentation">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Diagrama de Atividades</div>
                                <div class="delivery-date-compact normal">28/02</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint2_atividades_entregue" name="sprint2_atividades_entregue" type="checkbox" data-notes-target="#sprint2_atividades_notes_container">
                                <label for="sprint2_atividades_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint2_atividades_prazo" name="sprint2_atividades_prazo" type="checkbox">
                                <label for="sprint2_atividades_prazo">No Prazo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sprint 3 -->
                <div class="sprint-card" animate-slide-in stagger" style="--index: 3">
                    <div class="sprint-header-compact">
                        <i class="fas fa-database"></i>
                        <span>Sprint 3 - Modelagem BD</span>
                    </div>
                    <div class="delivery-grid">
                        <div class="delivery-row" data-type="database">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Modelagem Conceitual</div>
                                <div class="delivery-date-compact warning">20/03</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint3_conceitual_entregue" name="sprint3_conceitual_entregue" type="checkbox" data-notes-target="#sprint3_conceitual_notes_container">
                                <label for="sprint3_conceitual_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint3_conceitual_prazo" name="sprint3_conceitual_prazo" type="checkbox">
                                <label for="sprint3_conceitual_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="database">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Modelagem Lógica</div>
                                <div class="delivery-date-compact warning">20/03</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint3_logica_entregue" name="sprint3_logica_entregue" type="checkbox" data-notes-target="#sprint3_logica_notes_container">
                                <label for="sprint3_logica_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint3_logica_prazo" name="sprint3_logica_prazo" type="checkbox">
                                <label for="sprint3_logica_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="database">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Modelagem Física</div>
                                <div class="delivery-date-compact warning">20/03</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint3_fisica_entregue" name="sprint3_fisica_entregue" type="checkbox" data-notes-target="#sprint3_fisica_notes_container">
                                <label for="sprint3_fisica_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint3_fisica_prazo" name="sprint3_fisica_prazo" type="checkbox">
                                <label for="sprint3_fisica_prazo">No Prazo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sprint 4 -->
                <div class="sprint-card" animate-slide-in stagger" style="--index: 4">
                    <div class="sprint-header-compact">
                        <i class="fas fa-paint-brush"></i>
                        <span>Sprint 4 - Prototipagem</span>
                    </div>
                    <div class="delivery-grid">
                        <div class="delivery-row" data-type="design">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Protótipo de Telas + Validação</div>
                                <div class="delivery-date-compact warning">30/03</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint4_prototipo_entregue" name="sprint4_prototipo_entregue" type="checkbox" data-notes-target="#sprint4_prototipo_notes_container">
                                <label for="sprint4_prototipo_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint4_prototipo_prazo" name="sprint4_prototipo_prazo" type="checkbox">
                                <label for="sprint4_prototipo_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="design">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Storyboard</div>
                                <div class="delivery-date-compact warning">30/03</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint4_storyboard_entregue" name="sprint4_storyboard_entregue" type="checkbox" data-notes-target="#sprint4_storyboard_notes_container">
                                <label for="sprint4_storyboard_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint4_storyboard_prazo" name="sprint4_storyboard_prazo" type="checkbox">
                                <label for="sprint4_storyboard_prazo">No Prazo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sprint 5 -->
                <div class="sprint-card" animate-slide-in stagger" style="--index: 5">
                    <div class="sprint-header-compact">
                        <i class="fas fa-code"></i>
                        <span>Sprint 5 - Desenvolvimento</span>
                    </div>
                    <div class="delivery-grid">
                        <div class="delivery-row" data-type="documentation">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Documentação Completa</div>
                                <div class="delivery-date-compact urgent">25/04</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_doc_final_entregue" name="sprint5_doc_final_entregue" type="checkbox" data-notes-target="#sprint5_doc_final_notes_container">
                                <label for="sprint5_doc_final_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_doc_final_prazo" name="sprint5_doc_final_prazo" type="checkbox">
                                <label for="sprint5_doc_final_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="development">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Interface + 1 Funcionalidade</div>
                                <div class="delivery-date-compact urgent">30/04</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_interface_entregue" name="sprint5_interface_entregue" type="checkbox" data-notes-target="#sprint5_interface_notes_container">
                                <label for="sprint5_interface_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_interface_prazo" name="sprint5_interface_prazo" type="checkbox">
                                <label for="sprint5_interface_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="development">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Relatório FPDF</div>
                                <div class="delivery-date-compact urgent">07/05</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_relatorio_entregue" name="sprint5_relatorio_entregue" type="checkbox" data-notes-target="#sprint5_relatorio_notes_container">
                                <label for="sprint5_relatorio_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_relatorio_prazo" name="sprint5_relatorio_prazo" type="checkbox">
                                <label for="sprint5_relatorio_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="development">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Entrega Personalizada 1</div>
                                <div class="delivery-date-compact urgent">16/05</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_personalizada1_entregue" name="sprint5_personalizada1_entregue" type="checkbox" data-notes-target="#sprint5_personalizada1_notes_container">
                                <label for="sprint5_personalizada1_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_personalizada1_prazo" name="sprint5_personalizada1_prazo" type="checkbox">
                                <label for="sprint5_personalizada1_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="development">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Entrega Personalizada 2</div>
                                <div class="delivery-date-compact urgent">23/05</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_personalizada2_entregue" name="sprint5_personalizada2_entregue" type="checkbox" data-notes-target="#sprint5_personalizada2_notes_container">
                                <label for="sprint5_personalizada2_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_personalizada2_prazo" name="sprint5_personalizada2_prazo" type="checkbox">
                                <label for="sprint5_personalizada2_prazo">No Prazo</label>
                            </div>
                        </div>
                        <div class="delivery-row" data-type="development">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Entrega Final do Projeto</div>
                                <div class="delivery-date-compact urgent">30/05</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_final_entregue" name="sprint5_final_entregue" type="checkbox" data-notes-target="#sprint5_final_notes_container">
                                <label for="sprint5_final_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="sprint5_final_prazo" name="sprint5_final_prazo" type="checkbox">
                                <label for="sprint5_final_prazo">No Prazo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ajustes -->
                <div class="sprint-card" animate-slide-in stagger" style="--index: 6">
                    <div class="sprint-header-compact">
                        <i class="fas fa-tools"></i>
                        <span>Ajustes Finais</span>
                    </div>
                    <div class="delivery-grid">
                        <div class="delivery-row" data-type="development">
                            <div class="delivery-info-compact">
                                <div class="delivery-title-compact">Validação ou ajustes extras solicitados pela AREADEV</div>
                                <div class="delivery-date-compact urgent">06/06</div>
                            </div>
                            <div class="checkbox-compact">
                                <input id="ajustes_areadev_entregue" name="ajustes_areadev_entregue" type="checkbox" data-notes-target="#ajustes_areadev_notes_container">
                                <label for="ajustes_areadev_entregue">Entregue</label>
                            </div>
                            <div class="checkbox-compact">
                                <input id="ajustes_areadev_prazo" name="ajustes_areadev_prazo" type="checkbox">
                                <label for="ajustes_areadev_prazo">No Prazo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avaliação Final -->
                <div class="sprint-card" animate-slide-in stagger" style="--index: 8">
                    <div class="sprint-header-compact">
                        <i class="fas fa-medal"></i>
                        <span>Avaliação Final do Sistema</span>
                    </div>
                    <div class="p-4 text-gray-200 text-sm">
                        <p class="mb-4">Selecione o nível de avaliação geral para o sistema:</p>
                        <div class="flex flex-col space-y-3">
                            <div class="flex items-center">
                                <input type="radio" id="avaliacao_a" name="avaliacao_final" value="A" class="mr-2 text-ceara-green focus:ring-ceara-green" required>
                                <label for="avaliacao_a"><strong>Nível A:</strong> Sistema pronto para uso com ajustes mínimos ou nenhum ajuste.</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="avaliacao_b" name="avaliacao_final" value="B" class="mr-2 text-ceara-green focus:ring-ceara-green" required>
                                <label for="avaliacao_b"><strong>Nível B:</strong> Sistema utilizável com ajustes significativos a realizar.</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="avaliacao_c" name="avaliacao_final" value="C" class="mr-2 text-ceara-green focus:ring-ceara-green" required>
                                <label for="avaliacao_c"><strong>Nível C:</strong> Sistema incompleto com inúmeros problemas.</label>
                            </div>
                        </div>
                        <!-- Observações finais do sistema -->
                        <div class="mt-4">
                            <label for="observacoes_finais" class="block text-sm font-medium text-gray-400 mb-1">
                                Observações Finais do Sistema:
                            </label>
                            <textarea id="observacoes_finais" name="observacoes_finais" rows="3"
                                class="w-full px-3 py-2 text-sm text-gray-200 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-ceara-green focus:border-ceara-green resize-y"
                                placeholder="Digite aqui as observações finais sobre o sistema..."></textarea>
                        </div>
                         <p class="mt-4 text-yellow-400"><strong>Importante:</strong> O envio da avaliação final concluirá o processo.</p>
                    </div>
                </div>
            </div>

            <!-- Botão Submit Compacto -->
            <div class="submit-section-compact">
                <button type="submit" id="submit-form" class="submit-btn-compact">
                    <i class="fas fa-paper-plane"></i>
                    <span>Enviar Avaliação</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Botão Voltar ao Topo -->
    <div class="back-to-top" id="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script>
        // Variáveis de controle
        let totalCheckboxes = 0;
        let checkedBoxes = 0;

        // Função para mostrar/esconder o campo de notas correspondente
        function toggleNotesField(checkbox) {
            const notesFieldContainerSelector = checkbox.getAttribute('data-notes-target');
            const notesFieldContainer = document.querySelector(notesFieldContainerSelector);
            if (notesFieldContainer) {
                if (checkbox.checked) {
                    notesFieldContainer.classList.remove('hidden');
                } else {
                    notesFieldContainer.classList.add('hidden');
                    notesFieldContainer.querySelector('textarea').value = '';
                }
            }
        }

        // Auto-save with notes data
        function saveFormData() {
            const form = document.getElementById('evaluation-form');
            if (!form) return;
            const formData = {};
            // Collect checkbox states, textarea values, select value, and radio button values
            document.querySelectorAll('#evaluation-form input[type="checkbox"], #evaluation-form textarea, #evaluation-form select, #evaluation-form input[type="radio"]').forEach(element => {
                if (element.type === 'checkbox') {
                    formData[element.name] = element.checked;
                } else if (element.type === 'radio') {
                    formData[element.name] = element.value;
                } else {
                    formData[element.name] = element.value;
                }
            });
            localStorage.setItem('evaluationFormData', JSON.stringify(formData));
        }

        // Load data from local storage and apply to form
        function loadFormData() {
            const savedData = localStorage.getItem('evaluationFormData');
            if (savedData) {
                const formData = JSON.parse(savedData);
                Object.keys(formData).forEach(key => {
                    const element = document.querySelector(`[name="${key}"]`);
                    if (element) {
                        if (element.type === 'checkbox') {
                            element.checked = formData[key];
                        } else if (element.type === 'radio') {
                            element.checked = formData[key] === element.value;
                        } else {
                            element.value = formData[key];
                        }
                    }
                });
            }
             // Ensure notes fields visibility is correct after loading, even if no saved data
             document.querySelectorAll('input[name$="_entregue"]').forEach(toggleNotesField);
             updateProgress(); // Update progress bar based on loaded state
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            initializeForm();
            addEventListeners();
            handleScrollEvents();
            loadProgress(); // Load progress after setting up event listeners
        });

        function initializeForm() {
            totalCheckboxes = document.querySelectorAll('input[name$="_entregue"]').length; // Count only delivery checkboxes
            
            // Add change listeners to all checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateProgress();
                    updateRowStatus(this);
                    // Chama a função para mostrar/esconder notas apenas para checkboxes de entrega
                    if (this.name.endsWith('_entregue')) {
                        toggleNotesField(this);
                    }
                     saveFormData(); // Save state on any checkbox change
                });
            });
        }

        function updateProgress() {
            const deliveredCheckboxes = document.querySelectorAll('input[name$="_entregue"]:checked');
            const onTimeCheckboxes = document.querySelectorAll('input[name$="_prazo"]:checked');
            const totalDeliveries = document.querySelectorAll('input[name$="_entregue"]').length;

            let deliveredCount = deliveredCheckboxes.length;
            let onTimeCount = onTimeCheckboxes.length;

            const progressPercentage = totalDeliveries === 0 ? 0 : (deliveredCount / totalDeliveries) * 100;
            
            // Atualiza barra fixa
            const progressBar = document.getElementById('progress-fill-fixed');
            const progressContainer = document.getElementById('progress-bar-container');
            
            progressBar.style.width = progressPercentage + '%';
            document.getElementById('progress-percentage').textContent = Math.round(progressPercentage) + '%';
            
            // Efeito de pulsação quando há mudanças
            progressContainer.classList.add('updating');
            setTimeout(() => {
                progressContainer.classList.remove('updating');
            }, 500);
            
            updateStats();
        }

        function updateStats() {
            const deliveredCount = document.querySelectorAll('input[name$="_entregue"]:checked').length;
            const onTimeCount = document.querySelectorAll('input[name$="_prazo"]:checked').length;
            const totalDeliveries = document.querySelectorAll('input[name$="_entregue"]').length;
            const completionRate = totalDeliveries > 0 ? Math.round((deliveredCount / totalDeliveries) * 100) : 0;
            
            // Atualiza estatísticas na barra fixa
            document.getElementById('total-delivered-fixed').textContent = deliveredCount;
            document.getElementById('on-time-fixed').textContent = onTimeCount;
            document.getElementById('completion-rate-fixed').textContent = completionRate + '%';
        }

        function updateRowStatus(checkbox) {
            const row = checkbox.closest('.delivery-row');
            const entregueCheckbox = row.querySelector('input[name$="_entregue"]');
            const prazoCheckbox = row.querySelector('input[name$="_prazo"]');
            
            row.classList.remove('completed', 'partial', 'pending');
            
            if (entregueCheckbox.checked && prazoCheckbox.checked) {
                row.classList.add('completed');
            } else if (entregueCheckbox.checked || prazoCheckbox.checked) {
                row.classList.add('partial');
            } else {
                 row.classList.add('pending');
            }
        }

        function handleScrollEvents() {
            const progressFixed = document.getElementById('progress-fixed');
            const backToTop = document.getElementById('back-to-top');
            
            // Detecta rolagem da página
            window.addEventListener('scroll', function() {
                // Adiciona classe quando a página é rolada
                if (window.scrollY > 10) {
                    progressFixed.classList.add('scrolled');
                    backToTop.classList.add('visible');
                } else {
                    progressFixed.classList.remove('scrolled');
                    backToTop.classList.remove('visible');
                }
            });
            
            // Botão voltar ao topo
            backToTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        function addEventListeners() {

            
            // Listener para seleção de equipe
            document.getElementById('equipe').addEventListener('change', function() {
                updateTeamIndicator(); // Update indicator on change
                saveFormData(); // Save state on team change
            });

            // Validação do formulário
            document.getElementById('evaluation-form').addEventListener('submit', function(e) {
                const equipe = document.getElementById('equipe').value;
                // Adicionar validação JavaScript explícita para o campo equipe
                if (!equipe) {
                    e.preventDefault(); // Previne o envio do formulário
                    alert('Por favor, selecione uma equipe.');
                    document.getElementById('equipe').focus();
                    return false; // Interrompe a execução
                }
                // --- Fim da adição ---

                // A validação PHP no controlador também está presente como fallback

                const submitBtn = document.querySelector('.submit-btn-compact');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Enviando...</span>';
                submitBtn.disabled = true;
                // ... restante do código do listener de submit
            });

            // Responsividade para mobile - Ensure notes field layout is correct
            function handleMobileLayout() {
                const isMobile = window.innerWidth <= 768;
                document.querySelectorAll('.delivery-row').forEach(row => {
                    const notesContainer = row.querySelector('.notes-field-container');
                    if(notesContainer) {
                        if(isMobile) {
                            notesContainer.classList.add('col-span-full');
                        } else {
                            notesContainer.classList.remove('col-span-full');
                        }
                    }
                     // Re-apply checkbox grouping logic if necessary, but focus on notes field layout here
                     // The original mobile layout logic for checkboxes seems separate from notes field visibility
                });
            }

            window.addEventListener('resize', handleMobileLayout);
            handleMobileLayout(); // Initial call

            // Smooth scroll para elementos quando clicados
            document.querySelectorAll('.sprint-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('.checkbox-compact') && e.target.tagName !== 'TEXTAREA' && !e.target.closest('select')) {
                         this.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
             // Prevent smooth scroll when clicking on notes field label
             document.querySelectorAll('.notes-field-container label').forEach(label => {
                 label.addEventListener('click', function(e) {
                     e.stopPropagation();
                 });
             });

             // Add input listeners to all textareas to save notes as they are typed
             document.querySelectorAll('textarea').forEach(textarea => {
                textarea.addEventListener('input', saveFormData);
             });

        }

         function updateTeamIndicator() {
            const equipeSelect = document.getElementById('equipe');
            const selectedTeam = equipeSelect.value;
            const teamIndicator = document.getElementById('team-indicator');
            const selectedTeamSpan = document.getElementById('selected-team');

            if (selectedTeam && selectedTeam !== '') {
                selectedTeamSpan.textContent = selectedTeam;
                teamIndicator.style.display = 'flex'; // Show the indicator
            } else {
                teamIndicator.style.display = 'none'; // Hide if no team is selected
            }
        }

    </script>
</body>
</html>