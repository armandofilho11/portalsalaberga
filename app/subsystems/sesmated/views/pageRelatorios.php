<?php

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --background-color: #0a0a0a;
            --text-color: #ffffff;
            --header-color: #00b348;
            --accent-color: #ffb733;
            --card-bg: rgba(30, 30, 30, 0.95);
            --header-bg: rgba(15, 15, 15, 0.98);
            --search-bar-bg: #1a1a1a;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .report-card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .report-card:hover {
            transform: translateY(-2px);
            border-color: var(--header-color);
            box-shadow: 0 8px 32px rgba(0, 179, 72, 0.2);
        }

        .report-button {
            background: linear-gradient(135deg, var(--header-color), #00a040);
            color: white;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .report-button:hover {
            background: linear-gradient(135deg, #00a040, var(--header-color));
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(0, 179, 72, 0.4);
        }

        .report-button:active {
            transform: translateY(0);
        }

        .header-title {
            background: linear-gradient(135deg, var(--header-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .icon-wrapper {
            background: linear-gradient(135deg, var(--accent-color), #ff9500);
            border-radius: 12px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .page-header {
            background: var(--header-bg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <div class="page-header sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">📊</span>
                    </div>
                    <h1 class="text-xl font-bold header-title">SISTEMA DE RELATÓRIOS</h1>
                </div>
                <div class="text-sm text-gray-400">
                    Dashboard de Relatórios
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white mb-2">Relatórios Disponíveis</h2>
            <p class="text-gray-400">Selecione um relatório para gerar e visualizar os dados</p>
        </div>

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Relatório de Avaliadores -->
            <div class="report-card rounded-xl p-6">
                <div class="icon-wrapper">
                    <span class="text-white text-xl">👥</span>
                </div>
                <h1 class="text-lg font-semibold text-white mb-3">Relatório de Avaliadores</h1>
                <p class="text-gray-400 text-sm mb-4">Visualize informações detalhadas sobre os avaliadores do sistema</p>
                <form action="relatorios/avaliadores/avaliadores.php" method="get" target="_blank">
                    <button type="submit" class="report-button w-full py-3 px-4 rounded-lg font-medium">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            <!-- Relatório de Frequência de Eventos -->
            <div class="report-card rounded-xl p-6">
                <div class="icon-wrapper">
                    <span class="text-white text-xl">📅</span>
                </div>
                <h1 class="text-lg font-semibold text-white mb-3">Relatório de Frequência de Eventos</h1>
                <p class="text-gray-400 text-sm mb-4">Acompanhe a frequência e participação nos eventos realizados</p>
                <form action="relatorios/rifas/frequenciaEventos.php" method="get" target="_blank">
                    <button type="submit" class="report-button w-full py-3 px-4 rounded-lg font-medium">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            <!-- Relatório de Rifas - Resumo por Curso -->
            <div class="report-card rounded-xl p-6">
                <div class="icon-wrapper">
                    <span class="text-white text-xl">🎓</span>
                </div>
                <h1 class="text-lg font-semibold text-white mb-3">Relatório de Rifas - Resumo por Curso</h1>
                <p class="text-gray-400 text-sm mb-4">Análise detalhada das rifas organizadas por curso</p>
                <form action="relatorios/rifas/resumo_curso.php" method="get" target="_blank">
                    <button type="submit" class="report-button w-full py-3 px-4 rounded-lg font-medium">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            <!-- Relatório de Rifas - Resumo Total -->
            <div class="report-card rounded-xl p-6">
                <div class="icon-wrapper">
                    <span class="text-white text-xl">📊</span>
                </div>
                <h1 class="text-lg font-semibold text-white mb-3">Relatório de Rifas - Resumo Total</h1>
                <p class="text-gray-400 text-sm mb-4">Visão geral completa de todas as rifas do sistema</p>
                <form action="relatorios/rifas/resumo_total.php" method="get" target="_blank">
                    <button type="submit" class="report-button w-full py-3 px-4 rounded-lg font-medium">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            <!-- Relatório de Rifas - Resumo por Turma -->
            <div class="report-card rounded-xl p-6">
                <div class="icon-wrapper">
                    <span class="text-white text-xl">🏫</span>
                </div>
                <h1 class="text-lg font-semibold text-white mb-3">Relatório de Rifas - Resumo por Turma</h1>
                <p class="text-gray-400 text-sm mb-4">Dados específicos das rifas organizadas por turma</p>
                <form action="relatorios/rifas/resumo_turma.php" method="get" target="_blank">
                    <button type="submit" class="report-button w-full py-3 px-4 rounded-lg font-medium">
                        Gerar Relatório
                    </button>
                </form>
            </div>

            <!-- Relatório de exemplo -->
            <div class="report-card rounded-xl p-6">
                <div class="icon-wrapper">
                    <span class="text-white text-xl">🏫</span>
                </div>
                <h1 class="text-lg font-semibold text-white mb-3">Relatório de exemplo</h1>
                <p class="text-gray-400 text-sm mb-4">Coloque aqui uma descrição do relatório</p>
                <form action="relatorios/exemplo/exemplo.php" method="get" target="_blank">
                    <button type="submit" class="report-button w-full py-3 px-4 rounded-lg font-medium">
                        Gerar Relatório
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <div class="mt-16 border-t border-gray-800 py-8">
        <div class="container mx-auto px-6 text-center text-gray-400">
            <p>&copy; 2024 Sistema de Relatórios. Todos os direitos reservados.</p>
        </div>
    </div>

    <script>
        // Add subtle animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.report-card');
            
            cards.forEach((card, index) => {
                // Stagger animation on load
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add click feedback to buttons
            const buttons = document.querySelectorAll('.report-button');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });
    </script>
</body>
</html>