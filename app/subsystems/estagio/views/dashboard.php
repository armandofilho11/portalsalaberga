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
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#4A90E2">
    <meta name="description" content="Dashboard - Sistema de Gerenciamento de Estágio">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Dashboard - Sistema de Estágio</title>
</head>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#007A33',
                    secondary: '#FFA500',
                    success: '#10B981',
                    danger: '#EF4444',
                    warning: '#F59E0B',
                    info: '#3B82F6'
                }
            }
        }
    }
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --background-color: #1a1a1a;
        --text-color: #ffffff;
        --header-color: #00b348;
        --accent-color: #ffb733;
        --card-bg: rgba(45, 45, 45, 0.9);
        --sidebar-bg: #2d2d2d;
        --sidebar-active: rgba(0, 179, 72, 0.2);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background-color: var(--background-color);
        min-height: 100vh;
        background-image: radial-gradient(circle at 10% 20%, rgba(52, 152, 219, 0.05) 0%, rgba(52, 152, 219, 0) 20%), radial-gradient(circle at 90% 80%, rgba(46, 204, 113, 0.05) 0%, rgba(46, 204, 113, 0) 20%);
        color: var(--text-color);
        transition: background-color 0.3s ease;
    }

    .sidebar {
        background-color: rgba(45, 45, 45, 0.95);
        background-image: linear-gradient(to bottom, #2d2d2d, #222222);
        border-right: 1px solid rgba(0, 122, 51, 0.2);
        transition: all 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.2);
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
        color: #ffffff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    .sidebar-link i {
        min-width: 20px;
        margin-right: 12px;
        display: inline-block;
        text-align: center;
    }

    .dashboard-card {
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }


    .hamburger {
        width: 44px;
        height: 44px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        z-index: 60;
        background: rgba(0, 122, 51, 0.3); /* Aumentei a opacidade do fundo */
        border-radius: 10px;
        border: 1px solid rgba(0, 122, 51, 0.5); /* Adicionei borda sutil */
        padding: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra para destaque */
    }
    
    .hamburger:hover {
        background: rgba(0, 122, 51, 0.4); /* Fundo mais sólido no hover */
        transform: scale(1.05);
    }
    
    .hamburger:active {
        background: rgba(0, 122, 51, 0.5); /* Ainda mais sólido quando pressionado */
        transform: scale(0.98);
    }
    
    .hamburger-bar {
        width: 24px;
        height: 2.5px; /* Linhas um pouco mais grossas */
        background: #00FF6B; /* Cor mais vibrante */
        margin: 4px 0; /* Espaçamento maior entre linhas */
        border-radius: 2px;
        transition: all 0.3s cubic-bezier(0.68, -0.6, 0.32, 1.6);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); /* Sombra sutil nas linhas */
    }
    
    /* Overlay mais escuro */
    .sidebar-overlay {
        background: rgba(0, 0, 0, 0.7); /* Aumentei a opacidade do overlay */
    }

    .sidebar-overlay.active {
        opacity: 1;
        pointer-events: all;
    }
</style>

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
                    <a href="dashboard.php" class="sidebar-link active">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
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
            <button id="hamburgerMenu" class="hamburger" aria-label="Menu">
                <div class="hamburger-bar bar1"></div>
                <div class="hamburger-bar bar2"></div>
                <div class="hamburger-bar bar3"></div>
            </button>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobileSidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden sidebar">
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center gap-2 mb-6">
                    <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                    <h1 class="text-lg font-bold text-primary-400">Sistema <span class="text-secondary">STGM</span></h1>
                </div>
                <nav class="flex-1">
                    <a href="dashboard.php" class="sidebar-link active">
                        <i class="fas fa-home w-5 mr-3"></i>
                        Dashboard
                    </a>
                    <a href="gerenciar_empresas.php" class="sidebar-link">
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

        <!-- Overlay para o menu mobile -->
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Dashboard</h1>
                    <p class="text-gray-600 dark:text-gray-400">Bem-vindo ao Sistema de Gerenciamento de Estágio</p>
                </div>

                <div class="mt-4 md:mt-0 flex items-center">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center font-semibold">
                            A
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">Admin</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Administrador</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:grid-cols-3 gap-6">
                
                <div class="dashboard-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Empresas</h3>
                            <?php
                            $dados = $select_model->total_empresa();
                            foreach ($dados as $dado) {

                            ?>
                                <p class="text-3xl font-bold mt-2 text-info"><?= $dado ?></p>
                            <?php } ?>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-info/10 flex items-center justify-center text-info">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Vagas Captadas</h3>
                            <?php
                            $dados = $select_model->total_vagas();
                            foreach ($dados as $dado) {
                            ?>
                                <p class="text-3xl font-bold mt-2 text-success"><?= $dado ?? 0 ?></p>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-success/10 flex items-center justify-center text-success">
                            <i class="fas fa-briefcase text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Déficit Vagas</h3>
                            <?php
                            $dados2 = $select_model->total_alunos();
                            $dados = $select_model->total_vagas();
                            foreach ($dados as $dado) {
                            ?>
                                <p class="text-3xl font-bold mt-2 text-danger"><?= $dados2-$dado ?? 0 ?></p>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-danger/10 flex items-center justify-center text-danger">
                            <i class="fas fa-briefcase text-xl"></i>
                        </div>
                    </div>
                </div>


                <div class="dashboard-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Alunos</h3>
                            <p class="text-3xl font-bold mt-2 text-info">
                            <?php 
                            echo $dados = $select_model->total_alunos();
                            ?>
                            </p>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-info/10 flex items-center justify-center text-info">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Selecionados</h3>
                            <?php
                            $dados = $select_model->estagios_ativas();

                            foreach ($dados as $dado) {
                            ?>
                                <p class="text-3xl font-bold mt-2 text-success"><?=$dado ?? 0?></p>
                            <?php } ?>
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-success/10 flex items-center justify-center text-success">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Não Selecionados</h3>
                            <?php 
                            $total_alunos = $select_model->total_alunos();
                            ?>

                            <?php
                            $dados = $select_model->estagios_ativas();
                            foreach ($dados as $dado) {
                            ?>
                            
                            <p class="text-3xl font-bold mt-2 text-danger">
                            <?=$total_alunos-$dado ?? 0?>
                            </p>
                            <?php } ?>
                            
                        </div>
                        <div class="h-12 w-12 rounded-lg bg-danger/10 flex items-center justify-center text-danger">
                            <i class="fas fa-times-circle text-xl"></i>
                        </div>
                    </div>
                </div>
                

                
                
            </div>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Ações Rápidas -->
                <div class="dashboard-card lg:col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Ações Rápidas</h3>
                    <div class="space-y-3">
                        <a href="gerenciar_empresas.php" class="flex items-center p-3 rounded-lg hover:bg-info/10 transition-colors">
                            <div class="h-10 w-10 rounded-lg bg-info/10 flex items-center justify-center text-info mr-3">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">Cadastrar Empresa</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Adicionar nova empresa parceira</p>
                            </div>
                        </a>

                        <a href="vagas.php" class="flex items-center p-3 rounded-lg hover:bg-secondary/10 transition-colors">
                            <div class="h-10 w-10 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary mr-3">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div>
                                <h4 class="font-medium">Cadastrar Vaga</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Adicionar nova oportunidade</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Estatísticas por Área -->
                <div class="dashboard-card lg:col-span-3">
                    <h3 class="text-lg font-semibold mb-4">Perfil das Vagas</h3>
                    <div class="grid grid-cols-4 gap-4">
                        
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <h4 class="font-medium text-blue-600 dark:text-blue-400">Desenvolvimento</h4>
                            <div class="mt-2 flex justify-between items-end">
                                <?php
                                $dados = $select_model->total_vagas_dev();
                                foreach ($dados as $dado) {
                                ?>
                                    <p class="text-lg text-gray-500 dark:text-gray-400"><?= $dado ?? 0 ?></p>
                                <?php } ?>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Vagas</span>
                            </div>
                        </div>

                    
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <h4 class="font-medium text-green-600 dark:text-green-400">Design / Mídias</h4>
                            <div class="mt-2 flex justify-between items-end">
                                <?php
                                $dados = $select_model->total_vagas_des();
                                if ($dados == 0) {
                                } else {
                                    foreach ($dados as $dado) {
                                ?>
                                        <p class="text-lg text-gray-500 dark:text-gray-400"><?= $dado ?? 0 ?></p>
                                <?php }
                                } ?>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Vagas</span>
                            </div>
                        </div>

                        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <h4 class="font-medium text-orange-600 dark:text-orange-400">Suporte / Redes</h4>
                            <div class="mt-2 flex justify-between items-end">
                                <?php
                                $dados = $select_model->total_vagas_sup();
                                foreach ($dados as $dado) {
                                ?>
                                    <p class="text-lg text-gray-500 dark:text-gray-400"><?= $dado ?? 0 ?></p>
                                <?php } ?>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Vagas</span>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-gray-50 dark:bg-gray-900/20 rounded-lg">
                            <h4 class="font-medium text-gray-600 dark:text-gray-400">Tutoria</h4>
                            <div class="mt-2 flex justify-between items-end">
                                <?php
                                $dados = $select_model->total_vagas_tut();
                                foreach ($dados as $dado) {
                                ?>
                                    <p class="text-lg text-gray-500 dark:text-gray-400"><?= $dado ?? 0 ?></p>
                                <?php } ?>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Vagas</span>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerMenu = document.getElementById('hamburgerMenu');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            hamburgerMenu.addEventListener('click', () => {
                hamburgerMenu.classList.toggle('active');
                mobileSidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('active');
                document.body.style.overflow = hamburgerMenu.classList.contains('active') ? 'hidden' : 'auto';
            });

            // Fecha o menu ao clicar no overlay ou fora
            sidebarOverlay.addEventListener('click', () => {
                hamburgerMenu.classList.remove('active');
                mobileSidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        });
    </script>
</body>
</html>