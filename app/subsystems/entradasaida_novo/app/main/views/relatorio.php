<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../index.php");
    exit();
}

require_once __DIR__ . '/partials/system_layout.php';

$extraHead = '
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="https://salaberga.com/salaberga/portalsalaberga/app/main/assets/img/Design%20sem%20nome.svg" type="image/xicon">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ceara: { green: "#008C45", light: "#3CB371", olive: "#8CA03E", orange: "#FFA500" },
                    }
                }
            }
        };
    </script>
    <style>
        body { background: #f8fafc; min-height: 100vh; }
        /* Alterado o degradê do título para tons de verde institucional */
        .gradient-text-green { background: linear-gradient(45deg, #008C45, #3CB371); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .report-card { background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); transition: all 0.3s ease; cursor: pointer; }
        .report-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        .report-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; transition: all 0.3s ease; }
        .report-card:hover .report-icon { transform: scale(1.1); }
    </style>
';

renderHead('Relatórios - Salaberga', $extraHead);
?>

<div class="lg:pl-72 flex flex-col min-h-screen">
    <?php 
        // Renderiza a barra lateral nativa do sistema
        renderSidebar('relatorios');
    ?>
    
    <div class="flex-1 flex flex-col pt-12 px-4 sm:px-6 lg:px-8">
        <main class="flex-1 container mx-auto px-4 py-8 mt-4">
            <div class="max-w-2xl mx-auto">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2">
                        <span class="gradient-text-green">Relatórios do Sistema</span>
                    </h1>
                    <p class="text-gray-600">Selecione o tipo de relatório que deseja gerar</p>
                </div>

                <div class="space-y-4">
                    <a href="relatorios/relatorio_de_entrada.php" class="report-card block w-full p-6 text-left">
                        <div class="flex items-center gap-4">
                            <div class="report-icon bg-blue-50 text-blue-600">
                                <i class="fas fa-sign-in-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Relatório de Entradas</h3>
                                <p class="text-sm text-gray-500">Visualize o histórico de entradas dos alunos</p>
                            </div>
                        </div>
                    </a>

                    <a href="relatorios/relatorio_de_saida.php" class="report-card block w-full p-6 text-left">
                        <div class="flex items-center gap-4">
                            <div class="report-icon bg-red-50 text-red-600">
                                <i class="fas fa-sign-out-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Relatório de Saídas</h3>
                                <p class="text-sm text-gray-500">Visualize o histórico de saídas dos alunos</p>
                            </div>
                        </div>
                    </a>

                    <a href="relatorios/relatorio_diario_estagio.php" class="report-card block w-full p-6 text-left">
                        <div class="flex items-center gap-4">
                            <div class="report-icon bg-purple-50 text-purple-600">
                                <i class="fas fa-briefcase text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Relatório de Saídas-Estágio</h3>
                                <p class="text-sm text-gray-500">Visualize o histórico de saídas para estágio</p>
                            </div>
                        </div>
                    </a>

                    <a href="relatorios/relatorio_pre_estagio.php" class="report-card block w-full p-6 text-left">
                        <div class="flex items-center gap-4">
                            <div class="report-icon bg-teal-50 text-teal-600">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Relatório de Pré-Estágio</h3>
                                <p class="text-sm text-gray-500">Visualize os 4 momentos pós-almoço registrados por aluno</p>
                            </div>
                        </div>
                    </a>

                    <a href="relatorios/escolher_dia.php" class="report-card block w-full p-6 text-left">
                        <div class="flex items-center gap-4">
                            <div class="report-icon bg-yellow-50 text-yellow-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Relatório de Saídas-Estágio no dia específico</h3>
                                <p class="text-sm text-gray-500">Visualize informações gerais sobre todos os alunos por dia</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="./QRCode/decisao.php" class="report-card block w-full p-6 text-left">
                        <div class="flex items-center gap-4">
                            <div class="report-icon bg-green-50 text-green-600">
                                <i class="fas fa-qrcode text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Relatório Geral de QRCodes</h3>
                                <p class="text-sm text-gray-500">Gere QRCodes para todos os alunos</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </main>

        <?php 
        // O rodapé
        renderFooter(); 
        ?>
    </div>
</div>