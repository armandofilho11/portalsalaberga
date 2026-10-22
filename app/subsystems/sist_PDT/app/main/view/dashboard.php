<!DOCTYPE html>
<?php
session_start();
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Plataforma de Gestão Escolar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <!-- Mobile Menu Toggle -->
    <div class="fixed top-4 left-4 md:hidden">
        <button id="menuToggle" class="p-2 rounded-md bg-[#007A33] shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </div>

    <div class="flex h-screen">
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="fixed inset-0 bg-[#007A33] bg-opacity-75 z-50 hidden">
            <div class="absolute top-0 right-0 p-4">
                <button id="closeMenu" class="p-2 rounded-md bg-[#FFA500] shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            </div>
            <div class="flex flex-col p-4 space-y-4">
                <a href="dashboard.php" class="flex items-center p-2 text-white bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="9" y1="21" x2="9" y2="9"></line>
                    </svg>
                    Dashboard
                </a>
                <a href="avisos.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Avisos
                </a>
                <a href="ocorrencias.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <path d="M12 8v8"></path>
                        <path d="M12 12h.01"></path>
                    </svg>
                    Ocorrências
                </a>
                <a href="mapeamento.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Mapeamento
                </a>
                <a href="lideranca.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Liderança
                </a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-col w-64 bg-[#007A33] text-white">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">Menu</h2>
            </div>
            <div class="flex flex-col p-4 space-y-2">
                <a href="dashboard.php" class="flex items-center p-2 text-white bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="9" y1="21" x2="9" y2="9"></line>
                    </svg>
                    Dashboard
                </a>
                <a href="avisos.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Avisos
                </a>
                <a href="ocorrencias.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <path d="M12 8v8"></path>
                        <path d="M12 12h.01"></path>
                    </svg>
                    Ocorrências
                </a>
                <a href="mapeamento.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Mapeamento
                </a>
                <a href="lideranca.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Liderança
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Dashboard</h1>
                    <div class="flex items-center space-x-4">
                        <?php
                        // Debug da sessão
                        error_log('Conteúdo da sessão: ' . print_r($_SESSION, true));
                        
                        // Verifica se existe usuário na sessão e exibe o nome
                        if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['nome'])) {
                            $nomeUsuario = $_SESSION['usuario']['nome'];
                            $inicial = strtoupper(substr($nomeUsuario, 0, 1));
                        } else {
                            $nomeUsuario = 'Usuário';
                            $inicial = 'U';
                        }
                        ?>
                        <span class="text-gray-700"><?php echo htmlspecialchars($nomeUsuario); ?></span>
                        <div class="w-10 h-10 bg-[#FFA500] text-white rounded-full flex items-center justify-center">
                            <?php echo $inicial; ?>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Avisos da Turma -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Avisos da Turma</h2>
                            <button id="novoAviso" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Novo Aviso
                        </button>
                    </div>
                        <div id="listaAvisos" class="space-y-4">
                            <!-- Avisos serão carregados aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Ocorrências Recentes -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Ocorrências Recentes</h2>
                            <button id="novaOcorrencia" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Nova Ocorrência
                        </button>
                    </div>
                        <div id="listaOcorrencias" class="space-y-4">
                            <!-- Ocorrências serão carregadas aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Mapeamento da Sala -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Mapeamento da Sala</h2>
                            <div class="flex space-x-2">
                                <button id="editarMapeamento" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Editar Mapeamento
                            </button>
                            </div>
                        </div>
                        <div id="listaMapeamento" class="space-y-4">
                            <!-- O último mapeamento será carregado aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Liderança Atual -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Lider Atual</h2>
                            <button id="novaLideranca" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Novo Lider
                            </button>
                        </div>
                        <div id="listaLideranca" class="space-y-4">
                            <!-- Liderança será carregada aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Vice-Liderança Atual -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Vice-Lider Atual</h2>
                            <button id="novaViceLideranca" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Novo Vice-Lider
                            </button>
                        </div>
                        <div id="listaViceLideranca" class="space-y-4">
                            <!-- Vice-Liderança será carregada aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Secretaria Atual -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Secretario Atual</h2>
                            <button id="novaSecretaria" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Novo Secretario
                            </button>
                        </div>
                        <div id="listaSecretaria" class="space-y-4">
                            <!-- Secretaria será carregada aqui dinamicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Novo Aviso -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="avisoModal">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Novo Aviso</h2>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="avisoForm" class="space-y-4" action="../control/avisosControl.php" method="POST">
                <div>
                    <label for="buscaAluno" class="block text-sm font-medium text-gray-700 mb-1">Buscar Aluno</label>
                    <div class="relative">
                        <input type="text" id="buscaAluno" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o nome ou matrícula do aluno...">
                        <div id="resultadosBusca" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto"></div>
                    </div>
                </div>
                <div>
                    <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Aluno Selecionado</label>
                    <select id="matricula" name="matricula_aluno" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o aluno</option>
                    </select>
                </div>
                <div>
                    <label for="data" class="block text-sm font-medium text-gray-700 mb-1">Data do Aviso</label>
                    <input type="date" id="data" name="data_aviso" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="aviso" class="block text-sm font-medium text-gray-700 mb-1">Aviso</label>
                    <textarea id="aviso" name="aviso" required rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o aviso..."></textarea>
                </div>
                
                <input type="submit" name="registrar" value="Registrar Aviso" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#FFA500] transition-colors duration-200">
            </form>
        </div>
    </div>

    <!-- Modal para Nova Ocorrência -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="ocorrenciaModal">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Nova Ocorrência</h2>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="ocorrenciaForm" class="space-y-4" action="../control/ocorrenciasControl.php" method="POST">
                <div>
                    <label for="buscaAluno" class="block text-sm font-medium text-gray-700 mb-1">Buscar Aluno</label>
                    <div class="relative">
                        <input type="text" id="buscaAluno" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o nome ou matrícula do aluno...">
                        <div id="resultadosBusca" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto"></div>
                    </div>
                </div>
                <div>
                    <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Aluno Selecionado</label>
                    <select id="matricula" name="matricula" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o aluno</option>
                    </select>
                </div>
                <div>
                    <label for="data" class="block text-sm font-medium text-gray-700 mb-1">Data da Ocorrência</label>
                    <input type="date" id="data" name="data" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="ocorrencia" class="block text-sm font-medium text-gray-700 mb-1">Ocorrência</label>
                    <textarea id="ocorrencia" name="ocorrencia" required rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Descreva a ocorrência..."></textarea>
                </div>
                
                <input type="submit" name="registrar" value="Registrar Ocorrência" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#FFA500] transition-colors duration-200">
            </form>
        </div>
    </div>

    <!-- Modal para Editar Mapeamento -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="mapeamentoModal">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Editar Mapeamento</h2>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="mapeamentoForm" class="space-y-4">
                <div>
                    <label for="buscaAluno" class="block text-sm font-medium text-gray-700 mb-1">Buscar Aluno</label>
                    <div class="relative">
                        <input type="text" id="buscaAluno" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o nome ou matrícula do aluno...">
                        <div id="resultadosBusca" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto"></div>
                    </div>
                </div>
                <div>
                    <label for="mapeamentoMatricula" class="block text-sm font-medium text-gray-700 mb-1">Aluno Selecionado</label>
                    <select id="mapeamentoMatricula" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o aluno</option>
                    </select>
                </div>
                <div>
                    <label for="mapeamentoCarteira" class="block text-sm font-medium text-gray-700 mb-1">Número da Carteira</label>
                    <input type="number" id="mapeamentoCarteira" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label for="mapeamentoData" class="block text-sm font-medium text-gray-700 mb-1">Data do Mapeamento</label>
                    <input type="date" id="mapeamentoData" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <button type="submit" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#FFA500] transition-colors duration-200">
                    Salvar Alterações
                </button>
            </form>
        </div>
    </div>

    <!-- Modal para Nova Liderança -->
    <div id="liderancaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Nova Liderança</h2>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="liderancaForm" class="space-y-4">
                <div>
                    <label for="buscaAlunoLider" class="block text-sm font-medium text-gray-700 mb-1">Buscar Aluno</label>
                    <div class="relative">
                        <input type="text" id="buscaAlunoLider" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o nome ou matrícula do aluno...">
                        <div id="resultadosBuscaLider" class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto" style="top: calc(100% + 0.25rem);"></div>
                    </div>
                </div>
                <div>
                    <label for="matriculaLider" class="block text-sm font-medium text-gray-700 mb-1">Aluno Selecionado</label>
                    <select id="matriculaLider" name="matricula_aluno" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o aluno</option>
                    </select>
                </div>
                <div>
                    <label for="bimestreLider" class="block text-sm font-medium text-gray-700 mb-1">Bimestre</label>
                    <select id="bimestreLider" name="bimestre" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o bimestre</option>
                        <option value="1°">1° Bimestre</option>
                        <option value="2°">2° Bimestre</option>
                        <option value="3°">3° Bimestre</option>
                        <option value="4°">4° Bimestre</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#FFA500] transition-colors duration-200">
                    Salvar Liderança
                </button>
            </form>
        </div>
    </div>

    <!-- Modal para Nova Vice-Liderança -->
    <div id="viceLiderancaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Nova Vice-Liderança</h2>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="viceLiderancaForm" class="space-y-4">
                <div>
                    <label for="buscaAlunoVice" class="block text-sm font-medium text-gray-700 mb-1">Buscar Aluno</label>
                    <div class="relative">
                        <input type="text" id="buscaAlunoVice" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o nome ou matrícula do aluno...">
                        <div id="resultadosBuscaVice" class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto" style="top: calc(100% + 0.25rem);"></div>
                    </div>
                </div>
                <div>
                    <label for="matriculaVice" class="block text-sm font-medium text-gray-700 mb-1">Aluno Selecionado</label>
                    <select id="matriculaVice" name="matricula_aluno" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o aluno</option>
                    </select>
                </div>
                <div>
                    <label for="bimestreVice" class="block text-sm font-medium text-gray-700 mb-1">Bimestre</label>
                    <select id="bimestreVice" name="bimestre" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o bimestre</option>
                        <option value="1°">1° Bimestre</option>
                        <option value="2°">2° Bimestre</option>
                        <option value="3°">3° Bimestre</option>
                        <option value="4°">4° Bimestre</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#FFA500] transition-colors duration-200">
                    Salvar Vice-Liderança
                </button>
            </form>
        </div>
    </div>

    <!-- Modal para Nova Secretaria -->
    <div id="secretariaModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Nova Secretaria</h2>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="secretariaForm" class="space-y-4">
                <div>
                    <label for="buscaAlunoSecretaria" class="block text-sm font-medium text-gray-700 mb-1">Buscar Aluno</label>
                    <div class="relative">
                        <input type="text" id="buscaAlunoSecretaria" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o nome ou matrícula do aluno...">
                        <div id="resultadosBuscaSecretaria" class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto" style="top: calc(100% + 0.25rem);"></div>
                    </div>
                </div>
                <div>
                    <label for="matriculaSecretaria" class="block text-sm font-medium text-gray-700 mb-1">Aluno Selecionado</label>
                    <select id="matriculaSecretaria" name="matricula_aluno" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o aluno</option>
                    </select>
                </div>
                <div>
                    <label for="bimestreSecretaria" class="block text-sm font-medium text-gray-700 mb-1">Bimestre</label>
                    <select id="bimestreSecretaria" name="bimestre" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o bimestre</option>
                        <option value="1°">1° Bimestre</option>
                        <option value="2°">2° Bimestre</option>
                        <option value="3°">3° Bimestre</option>
                        <option value="4°">4° Bimestre</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#FFA500] text-white py-2 px-4 rounded-md hover:bg-[#FFA500] transition-colors duration-200">
                    Salvar Secretaria
                </button>
            </form>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
        // Modal functionality for Avisos
        const avisoModal = document.getElementById('avisoModal');
        const novoAvisoBtn = document.getElementById('novoAviso');
        const avisoCloseBtn = avisoModal.querySelector('.close-modal');
        const avisoForm = document.getElementById('avisoForm');
        const avisoBuscaInput = avisoModal.querySelector('#buscaAluno');
        const avisoResultadosDiv = avisoModal.querySelector('#resultadosBusca');
        const avisoMatriculaSelect = avisoModal.querySelector('#matricula');

        // Modal functionality for Ocorrências
        const ocorrenciaModal = document.getElementById('ocorrenciaModal');
        const novaOcorrenciaBtn = document.getElementById('novaOcorrencia');
        const ocorrenciaCloseBtn = ocorrenciaModal.querySelector('.close-modal');
        const ocorrenciaForm = document.getElementById('ocorrenciaForm');
        const ocorrenciaBuscaInput = ocorrenciaModal.querySelector('#buscaAluno');
        const ocorrenciaResultadosDiv = ocorrenciaModal.querySelector('#resultadosBusca');
        const ocorrenciaMatriculaSelect = ocorrenciaModal.querySelector('#matricula');

        // Modal functionality for Mapeamento
        const mapeamentoModal = document.getElementById('mapeamentoModal');
        const editarMapeamentoBtn = document.getElementById('editarMapeamento');
        const mapeamentoCloseBtn = mapeamentoModal.querySelector('.close-modal');
        const mapeamentoForm = document.getElementById('mapeamentoForm');
        const mapeamentoBuscaInput = mapeamentoModal.querySelector('#buscaAluno');
        const mapeamentoResultadosDiv = mapeamentoModal.querySelector('#resultadosBusca');
        const mapeamentoMatriculaSelect = mapeamentoModal.querySelector('#mapeamentoMatricula');

        // Modal functionality for Liderança
        const liderancaModal = document.getElementById('liderancaModal');
        const novaLiderancaBtn = document.getElementById('novaLideranca');
        const liderancaCloseBtn = liderancaModal.querySelector('.close-modal');
        const liderancaForm = document.getElementById('liderancaForm');
        const liderancaBuscaInput = liderancaModal.querySelector('#buscaAlunoLider');
        const liderancaResultadosDiv = liderancaModal.querySelector('#resultadosBuscaLider');
        const liderancaMatriculaSelect = liderancaModal.querySelector('#matriculaLider');

        // Modal functionality for Vice-Liderança
        const viceLiderancaModal = document.getElementById('viceLiderancaModal');
        const novaViceLiderancaBtn = document.getElementById('novaViceLideranca');
        const viceLiderancaCloseBtn = viceLiderancaModal.querySelector('.close-modal');
        const viceLiderancaForm = document.getElementById('viceLiderancaForm');
        const viceLiderancaBuscaInput = viceLiderancaModal.querySelector('#buscaAlunoVice');
        const viceLiderancaResultadosDiv = viceLiderancaModal.querySelector('#resultadosBuscaVice');
        const viceLiderancaMatriculaSelect = viceLiderancaModal.querySelector('#matriculaVice');

        // Modal functionality for Secretaria
        const secretariaModal = document.getElementById('secretariaModal');
        const novaSecretariaBtn = document.getElementById('novaSecretaria');
        const secretariaCloseBtn = secretariaModal.querySelector('.close-modal');
        const secretariaForm = document.getElementById('secretariaForm');
        const secretariaBuscaInput = secretariaModal.querySelector('#buscaAlunoSecretaria');
        const secretariaResultadosDiv = secretariaModal.querySelector('#resultadosBuscaSecretaria');
        const secretariaMatriculaSelect = secretariaModal.querySelector('#matriculaSecretaria');

        // Aviso Modal Functions
        novoAvisoBtn.onclick = function() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            
            document.getElementById('avisoForm').querySelector('input[type="date"]').value = `${yyyy}-${mm}-${dd}`;
            carregarAlunosAviso();
            avisoModal.classList.remove('hidden');
            avisoModal.classList.add('flex');
        }

        // Ocorrência Modal Functions
        novaOcorrenciaBtn.onclick = function() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            
            document.getElementById('ocorrenciaForm').querySelector('input[type="date"]').value = `${yyyy}-${mm}-${dd}`;
            carregarAlunosOcorrencia();
            ocorrenciaModal.classList.remove('hidden');
            ocorrenciaModal.classList.add('flex');
        }

        // Função para carregar alunos no modal de avisos
        function carregarAlunosAviso() {
            console.log('Iniciando carregamento de alunos para aviso...');
            fetch('../control/avisosControl.php?action=listar_alunos')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    avisoMatriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    if (Array.isArray(data)) {
                        data.forEach(aluno => {
                            const option = document.createElement('option');
                            option.value = aluno.matricula;
                            option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                            avisoMatriculaSelect.appendChild(option);
                        });
                    } else {
                        console.error('Dados recebidos não são um array:', data);
                        alert('Erro ao carregar lista de alunos. Formato de dados inválido.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos. Por favor, tente novamente.');
                });
        }

        // Função para carregar alunos no modal de ocorrências
        function carregarAlunosOcorrencia() {
            console.log('Iniciando carregamento de alunos para ocorrência...');
            fetch('../control/ocorrenciasControl.php?action=listar_alunos')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(response => {
                    if (!response.success) {
                        throw new Error('Erro ao carregar alunos: ' + (response.error || 'Erro desconhecido'));
                    }
                    const data = response.data;
                    ocorrenciaMatriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    if (Array.isArray(data)) {
                        data.forEach(aluno => {
                            const option = document.createElement('option');
                            option.value = aluno.matricula;
                            option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                            ocorrenciaMatriculaSelect.appendChild(option);
                        });
                    } else {
                        console.error('Dados recebidos não são um array:', data);
                        alert('Erro ao carregar lista de alunos. Formato de dados inválido.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos. Por favor, tente novamente.');
                });
        }

        // Função para buscar alunos no modal de avisos
        function buscarAlunosAviso(termo) {
            if (termo.length < 3) {
                avisoResultadosDiv.classList.add('hidden');
                return;
            }

            fetch(`../control/avisosControl.php?action=buscar_alunos&termo=${encodeURIComponent(termo)}`)
                .then(response => response.json())
                .then(data => {
                    avisoResultadosDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(aluno => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            div.textContent = `${aluno.matricula} - ${aluno.nome}`;
                            div.onclick = () => {
                                avisoMatriculaSelect.value = aluno.matricula;
                                avisoResultadosDiv.classList.add('hidden');
                            };
                            avisoResultadosDiv.appendChild(div);
                        });
                        avisoResultadosDiv.classList.remove('hidden');
                    } else {
                        avisoResultadosDiv.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar alunos:', error);
                    avisoResultadosDiv.classList.add('hidden');
                });
        }

        // Função para buscar alunos no modal de ocorrências
        function buscarAlunosOcorrencia(termo) {
            if (termo.length < 3) {
                ocorrenciaResultadosDiv.classList.add('hidden');
                return;
            }

            fetch(`../control/ocorrenciasControl.php?action=buscar_alunos&termo=${encodeURIComponent(termo)}`)
                .then(response => response.json())
                .then(response => {
                    if (!response.success) {
                        throw new Error('Erro ao buscar alunos: ' + (response.error || 'Erro desconhecido'));
                    }
                    const data = response.data;
                    ocorrenciaResultadosDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(aluno => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            div.textContent = `${aluno.matricula} - ${aluno.nome}`;
                            div.onclick = () => {
                                ocorrenciaMatriculaSelect.value = aluno.matricula;
                                ocorrenciaResultadosDiv.classList.add('hidden');
                            };
                            ocorrenciaResultadosDiv.appendChild(div);
                        });
                        ocorrenciaResultadosDiv.classList.remove('hidden');
                    } else {
                        ocorrenciaResultadosDiv.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar alunos:', error);
                    ocorrenciaResultadosDiv.classList.add('hidden');
                });
        }

        // Event listeners para busca de alunos
        avisoBuscaInput.addEventListener('input', (e) => {
            buscarAlunosAviso(e.target.value);
        });

        ocorrenciaBuscaInput.addEventListener('input', (e) => {
            buscarAlunosOcorrencia(e.target.value);
        });

        // Fechar resultados ao clicar fora
        document.addEventListener('click', (e) => {
            if (!avisoBuscaInput.contains(e.target) && !avisoResultadosDiv.contains(e.target)) {
                avisoResultadosDiv.classList.add('hidden');
            }
            if (!ocorrenciaBuscaInput.contains(e.target) && !ocorrenciaResultadosDiv.contains(e.target)) {
                ocorrenciaResultadosDiv.classList.add('hidden');
            }
        });

        // Close modal buttons
        avisoCloseBtn.onclick = function() {
            avisoModal.classList.add('hidden');
            avisoModal.classList.remove('flex');
        }

        ocorrenciaCloseBtn.onclick = function() {
            ocorrenciaModal.classList.add('hidden');
            ocorrenciaModal.classList.remove('flex');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == avisoModal) {
                avisoModal.classList.add('hidden');
                avisoModal.classList.remove('flex');
            }
            if (event.target == ocorrenciaModal) {
                ocorrenciaModal.classList.add('hidden');
                ocorrenciaModal.classList.remove('flex');
            }
        }

        // Função para carregar avisos
        function carregarAvisos() {
            console.log('Iniciando carregamento de avisos...');
            fetch('../control/avisosControl.php?action=buscar_aviso_recente')
                .then(response => {
                    console.log('Resposta recebida:', response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(response => {
                    console.log('Dados recebidos:', response);
                    const listaAvisos = document.getElementById('listaAvisos');
                    listaAvisos.innerHTML = '';
                    
                    if (!response.success) {
                        throw new Error('Erro ao carregar avisos: ' + (response.error || 'Erro desconhecido'));
                    }

                    const data = response.data;
                    
                    if (Array.isArray(data) && data.length > 0) {
                        // Pega apenas o aviso mais recente
                        const aviso = data[0];
                        console.log('Aviso mais recente:', aviso);
                        
                        const div = document.createElement('div');
                        div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                        
                        const dataAviso = new Date(aviso.data_aviso);
                        const dataFormatada = dataAviso.toLocaleDateString('pt-BR') + ' ' + dataAviso.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                        
                        // Verifica se o aviso é recente (últimas 24 horas)
                        const agora = new Date();
                        const diferenca = agora - dataAviso;
                        const ehRecente = diferenca <= 24 * 60 * 60 * 1000;
                        
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">${dataFormatada}</span>
                                ${ehRecente ? '<span class="px-2 py-1 text-xs bg-[#FFA500] text-white rounded-full">Novo</span>' : ''}
                            </div>
                            <h3 class="text-lg font-semibold mb-2">${aviso.aviso}</h3>
                            <p class="text-gray-600 mb-2">Aluno: ${aviso.nome_aluno}</p>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Matrícula: ${aviso.matricula_aluno}</span>
                            </div>
                        `;
                        
                        listaAvisos.appendChild(div);
                    } else {
                        console.log('Nenhum aviso encontrado');
                        listaAvisos.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum aviso registrado.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar avisos:', error);
                    const listaAvisos = document.getElementById('listaAvisos');
                    listaAvisos.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao carregar avisos. Por favor, verifique o console para mais detalhes.</p>';
                });
        }

        // Função para carregar ocorrências
        function carregarOcorrencias() {
            console.log('Iniciando carregamento de ocorrências...');
            fetch('../control/ocorrenciasControl.php?action=buscar_ocorrencia_recente')
                .then(response => {
                    console.log('Resposta recebida:', response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(response => {
                    console.log('Dados recebidos:', response);
                    const listaOcorrencias = document.getElementById('listaOcorrencias');
                    listaOcorrencias.innerHTML = '';
                    
                    if (!response.success) {
                        throw new Error('Erro ao carregar ocorrências: ' + (response.error || 'Erro desconhecido'));
                    }

                    const data = response.data;
                    
                    if (Array.isArray(data) && data.length > 0) {
                        // Pega a ocorrência mais recente
                        const ocorrencia = data[0];
                        console.log('Ocorrência mais recente:', ocorrencia);
                        
                        const div = document.createElement('div');
                        div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                        
                        const dataOcorrencia = new Date(ocorrencia.data_ocorrencia);
                        const dataFormatada = dataOcorrencia.toLocaleDateString('pt-BR') + ' ' + dataOcorrencia.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                        
                        // Verifica se a ocorrência é recente (últimas 24 horas)
                        const agora = new Date();
                        const diferenca = agora - dataOcorrencia;
                        const ehRecente = diferenca <= 24 * 60 * 60 * 1000;
                        
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">${dataFormatada}</span>
                                ${ehRecente ? '<span class="px-2 py-1 text-xs bg-[#FFA500] text-white rounded-full">Nova</span>' : ''}
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Ocorrência: ${ocorrencia.nome_aluno}</h3>
                            <p class="text-gray-600 mb-2">${ocorrencia.ocorrencia}</p>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Aluno: ${ocorrencia.nome_aluno}</span>
                                <span>Matrícula: ${ocorrencia.matricula_aluno}</span>
                            </div>
                        `;
                        
                        listaOcorrencias.appendChild(div);
                    } else {
                        console.log('Nenhuma ocorrência encontrada');
                        listaOcorrencias.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhuma ocorrência registrada.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar ocorrências:', error);
                    const listaOcorrencias = document.getElementById('listaOcorrencias');
                    listaOcorrencias.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao carregar ocorrências. Por favor, verifique o console para mais detalhes.</p>';
                });
        }

        // Função para carregar o último mapeamento
        function carregarUltimoMapeamento() {
            console.log('Iniciando carregamento do último mapeamento...');
            fetch('../control/mapeamentoControl.php?action=listar_mapeamento')
                .then(response => {
                    console.log('Resposta recebida:', response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Dados recebidos:', data);
                    const listaMapeamento = document.getElementById('listaMapeamento');
                    listaMapeamento.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        // Pega apenas o mapeamento mais recente
                        const mapeamento = data[0];
                        console.log('Mapeamento mais recente:', mapeamento);
                        
                        const div = document.createElement('div');
                        div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                        
                        const dataMapeamento = new Date(mapeamento.data_mapeamento);
                        const dataFormatada = dataMapeamento.toLocaleDateString('pt-BR');
                        
                        // Verifica se o mapeamento é recente (últimas 24 horas)
                        const agora = new Date();
                        const diferenca = agora - dataMapeamento;
                        const ehRecente = diferenca <= 24 * 60 * 60 * 1000;
                        
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">${dataFormatada}</span>
                                ${ehRecente ? '<span class="px-2 py-1 text-xs bg-[#FFA500] text-white rounded-full">Novo</span>' : ''}
                            </div>
                            <h3 class="text-lg font-semibold mb-2">${mapeamento.nome_aluno}</h3>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Carteira: ${mapeamento.numero_carteira}</span>
                                <span>Matrícula: ${mapeamento.matricula_aluno}</span>
                            </div>
                        `;
                        
                        listaMapeamento.appendChild(div);
                    } else {
                        console.log('Nenhum mapeamento encontrado');
                        listaMapeamento.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum mapeamento registrado.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar mapeamento:', error);
                    const listaMapeamento = document.getElementById('listaMapeamento');
                    listaMapeamento.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao carregar mapeamento. Por favor, verifique o console para mais detalhes.</p>';
                });
        }

        // Atualizar o carregamento inicial para incluir o mapeamento
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Página carregada, iniciando carregamento...');
            carregarAvisos();
            carregarOcorrencias();
            carregarUltimoMapeamento();
            carregarLideranca();
            carregarViceLideranca();
            carregarSecretaria();
        });

        // Atualizar a lista após registrar um novo aviso
        avisoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../control/avisosControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    avisoModal.classList.add('hidden');
                    avisoModal.classList.remove('flex');
                    this.reset();
                    carregarAvisos(); // Atualiza a lista de avisos
                    alert('Aviso registrado com sucesso!');
                } else {
                    alert(data.message || 'Erro ao registrar aviso.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao registrar aviso.');
            });
        });

        // Atualizar a lista após registrar uma nova ocorrência
        ocorrenciaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../control/ocorrenciasControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    ocorrenciaModal.classList.add('hidden');
                    ocorrenciaModal.classList.remove('flex');
                    this.reset();
                    carregarOcorrencias(); // Atualiza a lista de ocorrências
                    alert('Ocorrência registrada com sucesso!');
                } else {
                    alert(data.message || 'Erro ao registrar ocorrência.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao registrar ocorrência.');
            });
        });

        // Mapeamento Modal Functions
        editarMapeamentoBtn.onclick = function() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            
            document.getElementById('mapeamentoData').value = `${yyyy}-${mm}-${dd}`;
            carregarAlunosMapeamento();
            mapeamentoModal.classList.remove('hidden');
            mapeamentoModal.classList.add('flex');
        }

        // Função para carregar alunos no modal de mapeamento
        function carregarAlunosMapeamento() {
            console.log('Iniciando carregamento de alunos para mapeamento...');
            fetch('../control/mapeamentoControl.php?action=listar_alunos')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    mapeamentoMatriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    if (Array.isArray(data)) {
                        data.forEach(aluno => {
                            const option = document.createElement('option');
                            option.value = aluno.matricula;
                            option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                            mapeamentoMatriculaSelect.appendChild(option);
                        });
                    } else {
                        console.error('Dados recebidos não são um array:', data);
                        alert('Erro ao carregar lista de alunos. Formato de dados inválido.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos. Por favor, tente novamente.');
                });
        }

        // Função para buscar alunos no modal de mapeamento
        function buscarAlunosMapeamento(termo) {
            if (termo.length < 3) {
                mapeamentoResultadosDiv.classList.add('hidden');
                return;
            }

            fetch(`../control/mapeamentoControl.php?action=buscar_alunos&termo=${encodeURIComponent(termo)}`)
                .then(response => response.json())
                .then(data => {
                    mapeamentoResultadosDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(aluno => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            div.textContent = `${aluno.matricula} - ${aluno.nome}`;
                            div.onclick = () => {
                                mapeamentoMatriculaSelect.value = aluno.matricula;
                                mapeamentoResultadosDiv.classList.add('hidden');
                                mapeamentoBuscaInput.value = '';
                            };
                            mapeamentoResultadosDiv.appendChild(div);
                        });
                        mapeamentoResultadosDiv.classList.remove('hidden');
                    } else {
                        mapeamentoResultadosDiv.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar alunos:', error);
                    mapeamentoResultadosDiv.classList.add('hidden');
                });
        }

        // Event listener para busca de alunos no mapeamento
        mapeamentoBuscaInput.addEventListener('input', (e) => {
            buscarAlunosMapeamento(e.target.value);
        });

        // Fechar modal de mapeamento
        mapeamentoCloseBtn.onclick = function() {
            mapeamentoModal.classList.add('hidden');
            mapeamentoModal.classList.remove('flex');
        }

        // Atualizar a lista após salvar um novo mapeamento
        mapeamentoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'salvar_mapeamento');
            formData.append('numero_carteira', document.getElementById('mapeamentoCarteira').value);
            formData.append('matricula_aluno', document.getElementById('mapeamentoMatricula').value);
            formData.append('data_mapeamento', document.getElementById('mapeamentoData').value);

            fetch('../control/mapeamentoControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mapeamentoModal.classList.add('hidden');
                    mapeamentoModal.classList.remove('flex');
                    this.reset();
                    carregarUltimoMapeamento(); // Atualiza o mapeamento no dashboard
                    alert('Mapeamento salvo com sucesso!');
                } else {
                    alert(data.message || 'Erro ao salvar mapeamento.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao salvar mapeamento.');
            });
        });

        // Função para carregar liderança
        function carregarLideranca() {
            console.log('Iniciando carregamento de liderança...');
            fetch('../control/liderancaControl.php?action=listar_lideranca')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const listaLideranca = document.getElementById('listaLideranca');
                    listaLideranca.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        const lideranca = data[0];
                        const div = document.createElement('div');
                        div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                        
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Bimestre: ${lideranca.bimestre}</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">${lideranca.nome}</h3>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Matrícula: ${lideranca.matricula_lider}</span>
                            </div>
                        `;
                        
                        listaLideranca.appendChild(div);
                    } else {
                        listaLideranca.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhuma liderança registrada.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar liderança:', error);
                    const listaLideranca = document.getElementById('listaLideranca');
                    listaLideranca.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao carregar liderança. Por favor, tente novamente.</p>';
                });
        }

        // Função para carregar vice-liderança
        function carregarViceLideranca() {
            console.log('Iniciando carregamento de vice-liderança...');
            fetch('../control/liderancaControl.php?action=listar_vice_lideranca')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const listaViceLideranca = document.getElementById('listaViceLideranca');
                    listaViceLideranca.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        const viceLideranca = data[0];
                        const div = document.createElement('div');
                        div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                        
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Bimestre: ${viceLideranca.bimestre}</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">${viceLideranca.nome}</h3>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Matrícula: ${viceLideranca.matricula_vice_lider}</span>
                            </div>
                        `;
                        
                        listaViceLideranca.appendChild(div);
                    } else {
                        listaViceLideranca.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhuma vice-liderança registrada.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar vice-liderança:', error);
                    const listaViceLideranca = document.getElementById('listaViceLideranca');
                    listaViceLideranca.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao carregar vice-liderança. Por favor, tente novamente.</p>';
                });
        }

        // Função para carregar secretaria
        function carregarSecretaria() {
            console.log('Iniciando carregamento de secretaria...');
            fetch('../control/liderancaControl.php?action=listar_secretaria')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const listaSecretaria = document.getElementById('listaSecretaria');
                    listaSecretaria.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        const secretaria = data[0];
                        const div = document.createElement('div');
                        div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                        
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Bimestre: ${secretaria.bimestre}</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">${secretaria.nome}</h3>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Matrícula: ${secretaria.matricula_secretario}</span>
                            </div>
                        `;
                        
                        listaSecretaria.appendChild(div);
                    } else {
                        listaSecretaria.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhuma secretaria registrada.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar secretaria:', error);
                    const listaSecretaria = document.getElementById('listaSecretaria');
                    listaSecretaria.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao carregar secretaria. Por favor, tente novamente.</p>';
                });
        }

        // Liderança Modal Functions
        novaLiderancaBtn.onclick = function() {
            carregarAlunosLideranca();
            liderancaModal.classList.remove('hidden');
            liderancaModal.classList.add('flex');
        }

        // Função para carregar alunos no modal de liderança
        function carregarAlunosLideranca() {
            fetch('../control/liderancaControl.php?action=listar_alunos')
                .then(response => response.json())
                .then(data => {
                    liderancaMatriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    data.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.matricula;
                        option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                        liderancaMatriculaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos.');
                });
        }

        // Event listeners para os formulários
        liderancaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'salvar_lideranca');

            fetch('../control/liderancaControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    liderancaModal.classList.add('hidden');
                    liderancaModal.classList.remove('flex');
                    this.reset();
                    carregarLideranca();
                    alert('Liderança salva com sucesso!');
                } else {
                    alert(data.message || 'Erro ao salvar liderança.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao salvar liderança.');
            });
        });

        // Vice-Liderança Modal Functions
        novaViceLiderancaBtn.onclick = function() {
            carregarAlunosViceLideranca();
            viceLiderancaModal.classList.remove('hidden');
            viceLiderancaModal.classList.add('flex');
        }

        // Função para carregar alunos no modal de vice-liderança
        function carregarAlunosViceLideranca() {
            fetch('../control/liderancaControl.php?action=listar_alunos')
                .then(response => response.json())
                .then(data => {
                    viceLiderancaMatriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    data.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.matricula;
                        option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                        viceLiderancaMatriculaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos.');
                });
        }

        // Função para carregar alunos no modal de vice-liderança
        function carregarAlunosViceLideranca() {
            fetch('../control/liderancaControl.php?action=listar_alunos')
                .then(response => response.json())
                .then(data => {
                    viceLiderancaMatriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    data.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.matricula;
                        option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                        viceLiderancaMatriculaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos.');
                });
        }

        // Função para carregar alunos no modal de secretaria
        function carregarAlunosSecretaria() {
            fetch('../control/liderancaControl.php?action=listar_alunos')
                .then(response => response.json())
                .then(data => {
                    secretariaMatriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    data.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.matricula;
                        option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                        secretariaMatriculaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos.');
                });
        }

        // Event listeners para os formulários
        viceLiderancaForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'salvar_vice_lideranca');

            fetch('../control/liderancaControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    viceLiderancaModal.classList.add('hidden');
                    viceLiderancaModal.classList.remove('flex');
                    this.reset();
                    carregarViceLideranca();
                    alert('Vice-liderança salva com sucesso!');
                } else {
                    alert(data.message || 'Erro ao salvar vice-liderança.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao salvar vice-liderança.');
            });
        });

        // Close buttons for modals
        liderancaCloseBtn.onclick = function() {
            liderancaModal.classList.add('hidden');
            liderancaModal.classList.remove('flex');
        }

        viceLiderancaCloseBtn.onclick = function() {
            viceLiderancaModal.classList.add('hidden');
            viceLiderancaModal.classList.remove('flex');
        }

        secretariaCloseBtn.onclick = function() {
            secretariaModal.classList.add('hidden');
            secretariaModal.classList.remove('flex');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == liderancaModal) {
                liderancaModal.classList.add('hidden');
                liderancaModal.classList.remove('flex');
            }
            if (event.target == viceLiderancaModal) {
                viceLiderancaModal.classList.add('hidden');
                viceLiderancaModal.classList.remove('flex');
            }
            if (event.target == secretariaModal) {
                secretariaModal.classList.add('hidden');
                secretariaModal.classList.remove('flex');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            carregarAvisos();
            carregarOcorrencias();
            carregarMapeamentoRecente();
            carregarLideranca();
            carregarViceLideranca();
            carregarSecretaria();

            // Botões de ação
            document.getElementById('novoAviso').addEventListener('click', function() {
                window.location.href = 'avisos.php';
            });

            document.getElementById('novaOcorrencia').addEventListener('click', function() {
                window.location.href = 'ocorrencias.php';
            });

            document.getElementById('editarMapeamento').addEventListener('click', function() {
                window.location.href = 'mapeamento.php';
            });

            document.getElementById('novaLideranca').addEventListener('click', function() {
                window.location.href = 'lideranca.php';
            });

            document.getElementById('novaViceLideranca').addEventListener('click', function() {
                window.location.href = 'lideranca.php';
            });

            document.getElementById('novaSecretaria').addEventListener('click', function() {
                window.location.href = 'lideranca.php';
            });
        });

        function carregarMapeamentoRecente() {
            fetch('../control/mapeamentoControl.php?action=buscar_mapeamento_recente')
                .then(response => response.json())
                .then(data => {
                    const listaMapeamento = document.getElementById('listaMapeamento');
                    listaMapeamento.innerHTML = '';

                    if (data.success && data.data && data.data.length > 0) {
                        const mapeamento = data.data[0];
                        const dataFormatada = new Date(mapeamento.data_mapeamento).toLocaleDateString('pt-BR');
                        
                        const card = document.createElement('div');
                        card.className = 'bg-gray-50 p-4 rounded-md shadow';
                        card.innerHTML = `
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-lg">${mapeamento.nome_aluno}</h3>
                                    <p class="text-gray-600">Carteira: ${mapeamento.numero_carteira}</p>
                                    <p class="text-gray-600">Data: ${dataFormatada}</p>
                                </div>
                            </div>
                        `;
                        listaMapeamento.appendChild(card);
                    } else {
                        listaMapeamento.innerHTML = '<p class="text-gray-500">Nenhum mapeamento encontrado.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar mapeamento:', error);
                    document.getElementById('listaMapeamento').innerHTML = 
                        '<p class="text-red-500">Erro ao carregar o mapeamento. Por favor, tente novamente mais tarde.</p>';
                });
        }

        function carregarAvisos() {
            console.log('Iniciando carregamento de avisos...');
            fetch('../control/avisosControl.php?action=buscar_aviso_recente')
                .then(response => {
                    console.log('Resposta recebida:', response);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(response => {
                    console.log('Dados recebidos:', response);
                    const listaAvisos = document.getElementById('listaAvisos');
                    listaAvisos.innerHTML = '';
                    
                    if (!response.success) {
                        throw new Error('Erro ao carregar avisos: ' + (response.error || 'Erro desconhecido'));
                    }

                    const data = response.data;
                    
                    if (Array.isArray(data) && data.length > 0) {
                        // Pega apenas o aviso mais recente
                        const aviso = data[0];
                        console.log('Aviso mais recente:', aviso);
                        
                        const div = document.createElement('div');
                        div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                        
                        const dataAviso = new Date(aviso.data_aviso);
                        const dataFormatada = dataAviso.toLocaleDateString('pt-BR') + ' ' + dataAviso.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                        
                        // Verifica se o aviso é recente (últimas 24 horas)
                        const agora = new Date();
                        const diferenca = agora - dataAviso;
                        const ehRecente = diferenca <= 24 * 60 * 60 * 1000;
                        
                        div.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">${dataFormatada}</span>
                                ${ehRecente ? '<span class="px-2 py-1 text-xs bg-[#FFA500] text-white rounded-full">Novo</span>' : ''}
                            </div>
                            <h3 class="text-lg font-semibold mb-2">${aviso.aviso}</h3>
                            <p class="text-gray-600 mb-2">Aluno: ${aviso.nome_aluno}</p>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Matrícula: ${aviso.matricula_aluno}</span>
                            </div>
                        `;
                        
                        listaAvisos.appendChild(div);
                    } else {
                        console.log('Nenhum aviso encontrado');
                        listaAvisos.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum aviso registrado.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar avisos:', error);
                    const listaAvisos = document.getElementById('listaAvisos');
                    listaAvisos.innerHTML = '<p class="text-red-500 text-center py-4">Erro ao carregar avisos. Por favor, verifique o console para mais detalhes.</p>';
                });
        }
    </script>
</body>
</html> 