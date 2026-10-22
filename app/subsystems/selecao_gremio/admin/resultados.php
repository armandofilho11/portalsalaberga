<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    $_SESSION['mensagem'] = 'Você precisa estar logado para acessar esta página';
    $_SESSION['tipo_mensagem'] = 'error';
    
    // Garantir que o redirecionamento seja absoluto
    $redirect_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $redirect_url .= dirname($_SERVER['PHP_SELF']) . "/index.php";
    
    header("Location: " . $redirect_url);
    exit();
}

require_once '../model/VotoModel.php';

$votoModel = new VotoModel();
$resultados = $votoModel->obterResultados();

// Formatar a data atual
$dataAtual = date('d/m/Y');
$horaAtual = date('H:i');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Votação - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#e6f2ec', 100: '#cce5d9', 200: '#99cbb3', 300: '#66b18d',
                            400: '#339766', 500: '#007d40', 600: '#006a36', 700: '#005A24',
                            800: '#004d1f', 900: '#00401a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body { 
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        }
        
        .dashboard-card {
            transition: all 0.3s ease;
            transform: translateY(0);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover .stat-icon {
            transform: scale(1.1);
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            background-color: #f3f4f6;
        }
        
        .progress-value {
            height: 100%;
            border-radius: 4px;
            transition: width 1.5s ease-in-out;
        }
        
        .chart-container {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .chart-container:hover {
            transform: scale(1.02);
        }
        
        .result-card {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .result-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
        }
        
        .result-approved::before {
            background: linear-gradient(90deg, #10B981, #059669);
        }
        
        .result-rejected::before {
            background: linear-gradient(90deg, #EF4444, #DC2626);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in;
        }
        
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.5s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .pulse-ring {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.1; transform: scale(1.2); }
        }
        
        .header-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .logout-btn {
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .logout-btn:hover i {
            transform: translateX(3px);
        }
        
        .logout-btn i {
            transition: transform 0.2s ease;
        }
        
        /* Estilos para o botão de exportar */
        .export-btn {
            position: relative;
            overflow: hidden;
        }
        
        .export-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .export-btn:hover::after {
            left: 100%;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gradient-to-r from-primary-800 to-primary-600 text-white shadow-lg header-pattern">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center">
                            <div class="bg-white p-2 rounded-full mr-3 shadow-md">
                                <i class="fas fa-chart-pie text-primary-700 text-xl"></i>
                            </div>
                            <h1 class="text-3xl font-bold">Resultados da Votação</h1>
                        </div>
                        <p class="text-primary-100 mt-2">Painel Administrativo - Grêmio Estudantil</p>
                    </div>
                    <div class="flex items-center">
                        <span class="mr-4 text-primary-100">
                            <i class="fas fa-user mr-2"></i>
                            <?php echo htmlspecialchars($_SESSION['admin_usuario'] ?? 'Admin'); ?>
                        </span>
                        <a href="logout.php" class="logout-btn flex items-center bg-primary-800 bg-opacity-30 hover:bg-opacity-40 px-4 py-2 rounded-lg transition-all">
                            <span>Sair</span>
                            <i class="fas fa-sign-out-alt ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-fade-in">
                <!-- Total de Votos -->
                <div class="dashboard-card bg-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm uppercase tracking-wider font-medium">Total de Votos</p>
                            <h3 class="text-4xl font-bold text-gray-800 mt-2"><?php echo $resultados['total_votos']; ?></h3>
                            <p class="text-sm text-primary-600 mt-1">
                                <i class="fas fa-users mr-1"></i> Participantes
                            </p>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-0 bg-primary-400 rounded-full opacity-10 pulse-ring"></div>
                            <div class="relative bg-gradient-to-br from-primary-50 to-primary-100 p-4 rounded-full shadow-md">
                                <i class="fas fa-vote-yea text-3xl text-primary-600 stat-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Votos Sim -->
                <div class="dashboard-card bg-white rounded-xl p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-gray-500 text-sm uppercase tracking-wider font-medium">Votos Sim</p>
                            <h3 class="text-4xl font-bold text-green-600 mt-2"><?php echo $resultados['votos_sim']; ?></h3>
                            <p class="text-sm text-green-600 mt-1">
                                <i class="fas fa-percentage mr-1"></i> <?php echo $resultados['percentual_sim']; ?>% do total
                            </p>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-0 bg-green-400 rounded-full opacity-10 pulse-ring"></div>
                            <div class="relative bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-full shadow-md">
                                <i class="fas fa-thumbs-up text-3xl text-green-600 stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-value bg-green-500" style="width: <?php echo $resultados['percentual_sim']; ?>%"></div>
                    </div>
                </div>

                <!-- Votos Não -->
                <div class="dashboard-card bg-white rounded-xl p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-gray-500 text-sm uppercase tracking-wider font-medium">Votos Não</p>
                            <h3 class="text-4xl font-bold text-red-600 mt-2"><?php echo $resultados['votos_nao']; ?></h3>
                            <p class="text-sm text-red-600 mt-1">
                                <i class="fas fa-percentage mr-1"></i> <?php echo $resultados['percentual_nao']; ?>% do total
                            </p>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-0 bg-red-400 rounded-full opacity-10 pulse-ring"></div>
                            <div class="relative bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-full shadow-md">
                                <i class="fas fa-thumbs-down text-3xl text-red-600 stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-value bg-red-500" style="width: <?php echo $resultados['percentual_nao']; ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Pie Chart -->
                <div class="dashboard-card bg-white rounded-xl p-6 animate-slide-up" style="animation-delay: 0.2s;">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-chart-pie text-primary-600 mr-2"></i>
                        Distribuição dos Votos
                    </h2>
                    <div class="chart-container h-64">
                        <canvas id="votacaoChart"></canvas>
                    </div>
                </div>
                
                <!-- Bar Chart -->
                <div class="dashboard-card bg-white rounded-xl p-6 animate-slide-up" style="animation-delay: 0.4s;">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-chart-bar text-primary-600 mr-2"></i>
                        Comparativo de Votos
                    </h2>
                    <div class="chart-container h-64">
                        <canvas id="comparativoChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Resultado Final -->
            <div class="animate-scale-in" style="animation-delay: 0.6s;">
                <?php if ($resultados['votos_sim'] > $resultados['votos_nao']): ?>
                    <div class="result-card result-approved bg-white rounded-xl shadow-md p-8">
                        <div class="text-center">
                            <div class="relative inline-block mb-6">
                                <div class="absolute inset-0 bg-green-400 rounded-full opacity-10 pulse-ring"></div>
                                <div class="relative inline-flex items-center justify-center p-5 bg-gradient-to-br from-green-50 to-green-100 rounded-full shadow-md">
                                    <i class="fas fa-check-circle text-5xl text-green-600"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-bold text-green-600 mb-3">Proposta Aprovada!</h3>
                            <p class="text-gray-600 max-w-2xl mx-auto">
                                A criação do novo grêmio estudantil foi <span class="font-semibold">aprovada</span> pela maioria dos votantes com 
                                <span class="text-green-600 font-semibold"><?php echo $resultados['percentual_sim']; ?>%</span> dos votos.
                            </p>
                            
                            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                    <h4 class="font-medium text-green-700 mb-1">Próximos Passos</h4>
                                    <p class="text-green-600 text-sm">Organizar a eleição para a diretoria do grêmio</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <h4 class="font-medium text-blue-700 mb-1">Prazo Estimado</h4>
                                    <p class="text-blue-600 text-sm">Implementação em até 30 dias</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="result-card result-rejected bg-white rounded-xl shadow-md p-8">
                        <div class="text-center">
                            <div class="relative inline-block mb-6">
                                <div class="absolute inset-0 bg-red-400 rounded-full opacity-10 pulse-ring"></div>
                                <div class="relative inline-flex items-center justify-center p-5 bg-gradient-to-br from-red-50 to-red-100 rounded-full shadow-md">
                                    <i class="fas fa-times-circle text-5xl text-red-600"></i>
                                </div>
                            </div>
                            <h3 class="text-3xl font-bold text-red-600 mb-3">Proposta Não Aprovada</h3>
                            <p class="text-gray-600 max-w-2xl mx-auto">
                                A criação do novo grêmio estudantil <span class="font-semibold">não foi aprovada</span> pela maioria dos votantes com 
                                <span class="text-red-600 font-semibold"><?php echo $resultados['percentual_nao']; ?>%</span> dos votos contrários.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Export Options -->
            <div class="mt-8 flex flex-wrap justify-center gap-4 animate-fade-in" style="animation-delay: 0.8s;">
                <button id="exportPdfBtn" class="export-btn flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors shadow-md">
                    <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                </button>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6 mt-8">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; <?php echo date('Y'); ?> Escola Estadual - Sistema de Votação do Grêmio Estudantil</p>
                <p class="text-gray-400 text-sm mt-2">Desenvolvido para a comunidade escolar</p>
            </div>
        </footer>
    </div>

    <script>
        // Importar jsPDF
        const { jsPDF } = window.jspdf;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Configuração do gráfico de pizza
            const ctxPie = document.getElementById('votacaoChart').getContext('2d');
            const pieChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: ['Sim', 'Não'],
                    datasets: [{
                        data: [<?php echo $resultados['votos_sim']; ?>, <?php echo $resultados['votos_nao']; ?>],
                        backgroundColor: ['#10B981', '#EF4444'],
                        borderWidth: 0,
                        borderRadius: 5,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: 'Poppins',
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            bodyFont: {
                                family: 'Poppins',
                                size: 14
                            },
                            titleFont: {
                                family: 'Poppins',
                                size: 16,
                                weight: 'bold'
                            },
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} votos (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            
            // Configuração do gráfico de barras
            const ctxBar = document.getElementById('comparativoChart').getContext('2d');
            const barChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: ['Votos'],
                    datasets: [
                        {
                            label: 'Sim',
                            data: [<?php echo $resultados['votos_sim']; ?>],
                            backgroundColor: '#10B981',
                            borderRadius: 6,
                            barThickness: 50
                        },
                        {
                            label: 'Não',
                            data: [<?php echo $resultados['votos_nao']; ?>],
                            backgroundColor: '#EF4444',
                            borderRadius: 6,
                            barThickness: 50
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: 'Poppins',
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            bodyFont: {
                                family: 'Poppins',
                                size: 14
                            },
                            titleFont: {
                                family: 'Poppins',
                                size: 16,
                                weight: 'bold'
                            }
                        }
                    },
                    animation: {
                        delay: function(context) {
                            return context.dataIndex * 300;
                        },
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
            
            // Função para exportar PDF
            document.getElementById('exportPdfBtn').addEventListener('click', function() {
                // Alterar o botão para mostrar o estado de carregamento
                const exportBtn = this;
                const originalContent = exportBtn.innerHTML;
                exportBtn.innerHTML = '<div class="loading-spinner"></div>Gerando PDF...';
                exportBtn.disabled = true;
                
                try {
                    // Criar o PDF
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    
                    // Adicionar título
                    pdf.setFontSize(18);
                    pdf.setTextColor(0, 125, 64); // Verde primário
                    pdf.text('Relatório de Votação - Grêmio Estudantil', pageWidth / 2, 20, { align: 'center' });
                    
                    // Adicionar subtítulo
                    pdf.setFontSize(12);
                    pdf.setTextColor(100, 100, 100); // Cinza
                    pdf.text('Escola Estadual', pageWidth / 2, 28, { align: 'center' });
                    
                    // Adicionar data
                    pdf.setFontSize(10);
                    pdf.text(`Gerado em: <?php echo $dataAtual; ?> às <?php echo $horaAtual; ?>`, pageWidth / 2, 35, { align: 'center' });
                    
                    // Linha separadora
                    pdf.setDrawColor(0, 125, 64); // Verde primário
                    pdf.line(20, 40, pageWidth - 20, 40);
                    
                    // Resumo da votação
                    pdf.setFontSize(14);
                    pdf.setTextColor(60, 60, 60); // Cinza escuro
                    pdf.text('Resumo da Votação', 20, 50);
                    
                    // Total de votos
                    pdf.setFontSize(12);
                    pdf.setTextColor(80, 80, 80);
                    pdf.text('Total de Votos:', 30, 60);
                    pdf.setFontSize(16);
                    pdf.setTextColor(40, 40, 40);
                    pdf.text('<?php echo $resultados['total_votos']; ?>', 90, 60);
                    
                    // Votos Sim
                    pdf.setFontSize(12);
                    pdf.setTextColor(80, 80, 80);
                    pdf.text('Votos Sim:', 30, 70);
                    pdf.setFontSize(16);
                    pdf.setTextColor(16, 185, 129); // Verde
                    pdf.text('<?php echo $resultados['votos_sim']; ?> (<?php echo $resultados['percentual_sim']; ?>%)', 90, 70);
                    
                    // Votos Não
                    pdf.setFontSize(12);
                    pdf.setTextColor(80, 80, 80);
                    pdf.text('Votos Não:', 30, 80);
                    pdf.setFontSize(16);
                    pdf.setTextColor(239, 68, 68); // Vermelho
                    pdf.text('<?php echo $resultados['votos_nao']; ?> (<?php echo $resultados['percentual_nao']; ?>%)', 90, 80);
                    
                    // Resultado
                    pdf.setFontSize(14);
                    pdf.setTextColor(60, 60, 60);
                    pdf.text('Resultado Final', 20, 100);
                    
                    <?php if ($resultados['votos_sim'] > $resultados['votos_nao']): ?>
                    // Resultado aprovado
                    pdf.setFillColor(230, 255, 243); // Verde claro
                    pdf.roundedRect(20, 110, pageWidth - 40, 30, 3, 3, 'F');
                    pdf.setFontSize(16);
                    pdf.setTextColor(16, 185, 129); // Verde
                    pdf.text('Proposta Aprovada', pageWidth / 2, 120, { align: 'center' });
                    pdf.setFontSize(12);
                    pdf.setTextColor(80, 80, 80);
                    pdf.text('A criação do novo grêmio estudantil foi aprovada pela maioria', pageWidth / 2, 130, { align: 'center' });
                    pdf.text('dos votantes com <?php echo $resultados['percentual_sim']; ?>% dos votos.', pageWidth / 2, 137, { align: 'center' });
                    <?php else: ?>
                    // Resultado reprovado
                    pdf.setFillColor(255, 235, 235); // Vermelho claro
                    pdf.roundedRect(20, 110, pageWidth - 40, 30, 3, 3, 'F');
                    pdf.setFontSize(16);
                    pdf.setTextColor(239, 68, 68); // Vermelho
                    pdf.text('Proposta Não Aprovada', pageWidth / 2, 120, { align: 'center' });
                    pdf.setFontSize(12);
                    pdf.setTextColor(80, 80, 80);
                    pdf.text('A criação do novo grêmio estudantil não foi aprovada pela maioria', pageWidth / 2, 130, { align: 'center' });
                    pdf.text('dos votantes com <?php echo $resultados['percentual_nao']; ?>% dos votos contrários.', pageWidth / 2, 137, { align: 'center' });
                    <?php endif; ?>
                    
                    // Rodapé
                    pdf.setFontSize(10);
                    pdf.setTextColor(150, 150, 150);
                    pdf.text('© <?php echo date('Y'); ?> Escola Estadual - Sistema de Votação do Grêmio Estudantil', pageWidth / 2, pageHeight - 10, { align: 'center' });
                    
                    // Salvar o PDF
                    pdf.save('Resultados_Votacao_Gremio_<?php echo date('d-m-Y'); ?>.pdf');
                    
                    // Restaurar o botão
                    exportBtn.innerHTML = originalContent;
                    exportBtn.disabled = false;
                    
                    // Mostrar mensagem de sucesso
                    alert('PDF gerado com sucesso!');
                } catch (error) {
                    console.error('Erro ao gerar PDF:', error);
                    
                    // Restaurar o botão em caso de erro
                    exportBtn.innerHTML = originalContent;
                    exportBtn.disabled = false;
                    
                    // Mostrar mensagem de erro
                    alert('Erro ao gerar o PDF. Por favor, tente novamente.');
                }
            });
        });
    </script>
</body>
</html>