<!DOCTYPE html>
<?php
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liderança - Plataforma de Gestão Escolar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <?php
    require_once('../config/conexao.php');
    require_once('../control/liderancaControl.php');

    $liderancaControl = new LiderancaControl();

    if(isset($_POST['excluir']) && isset($_POST['cargo']) && isset($_POST['matricula'])) {
        try {
            $cargo = $_POST['cargo'];
            $matricula = $_POST['matricula'];
            $sucesso = false;
            
            switch($cargo) {
                case 'lider':
                    $sucesso = $liderancaControl->excluirLideranca($matricula);
                    break;
                case 'vice':
                    $sucesso = $liderancaControl->excluirViceLideranca($matricula);
                    break;
                case 'secretario':
                    $sucesso = $liderancaControl->excluirSecretario($matricula);
                    break;
            }
            
            if($sucesso) {
                echo "<script>
                    alert('Excluído com sucesso!');
                    window.location.href = 'lideranca.php';
                </script>";
                exit();
            } else {
                echo "<script>alert('Erro ao excluir.');</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Erro ao excluir: " . $e->getMessage() . "');</script>";
        }
    }
    ?>
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
                <a href="dashboard.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="9" y1="21" x2="9" y2="9"></line>
                    </svg>
                    Dashboard
                </a>
                <a href="avisos.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Avisos
                </a>
                <a href="ocorrencias.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <path d="M12 8v8"></path>
                        <path d="M12 12h.01"></path>
                    </svg>
                    Ocorrências
                </a>
                <a href="mapeamento.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Mapeamento
                </a>
                <a href="lideranca.php" class="flex items-center p-2 text-white bg-[#FFA500] rounded-md">
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
                <a href="dashboard.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="9" y1="21" x2="9" y2="9"></line>
                    </svg>
                    Dashboard
                </a>
                <a href="avisos.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Avisos
                </a>
                <a href="ocorrencias.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <path d="M12 8v8"></path>
                        <path d="M12 12h.01"></path>
                    </svg>
                    Ocorrências
                </a>
                <a href="mapeamento.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Mapeamento
                </a>
                <a href="lideranca.php" class="flex items-center p-2 text-white bg-[#FFA500] rounded-md">
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
                    <h1 class="text-2xl font-bold">Liderança</h1>
                    <div class="flex items-center space-x-4">
                        <?php
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
                    <!-- Liderança Atual -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Lider Atual</h2>
                            <div class="flex space-x-2">
                                <a href="../view/gerar_relatorio.php?tipo=lideranca" target="_blank" class="flex items-center px-4 py-2 bg-[#007A33] text-white rounded-md hover:bg-[#007A33]/90 transition-colors duration-200 shadow-md">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                    Gerar Relatório
                                </a>
                                <button id="novaLideranca" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Novo Lider
                                </button>
                            </div>
                        </div>
                        <div id="listaLideranca" class="space-y-4">
                            <!-- Liderança será carregada aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Vice-Liderança Atual -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Vice-Lider Atual</h2>
                            <div class="flex space-x-2">
                                <a href="../view/gerar_relatorio.php?tipo=vice_lideranca" target="_blank" class="flex items-center px-4 py-2 bg-[#007A33] text-white rounded-md hover:bg-[#007A33]/90 transition-colors duration-200 shadow-md">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                    Gerar Relatório
                                </a>
                                <button id="novaViceLideranca" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Novo Vice-Lider
                                </button>
                            </div>
                        </div>
                        <div id="listaViceLideranca" class="space-y-4">
                            <!-- Vice-Liderança será carregada aqui dinamicamente -->
                        </div>
                    </div>

                    <!-- Secretaria Atual -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Secretario Atual</h2>
                            <div class="flex space-x-2">
                                <a href="../view/gerar_relatorio.php?tipo=secretaria" target="_blank" class="flex items-center px-4 py-2 bg-[#007A33] text-white rounded-md hover:bg-[#007A33]/90 transition-colors duration-200 shadow-md">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                    </svg>
                                    Gerar Relatório
                                </a>
                                <button id="novaSecretaria" class="bg-[#FFA500] text-white px-4 py-2 rounded-md hover:bg-[#FFA500] transition-colors duration-200 shadow-md hover:shadow-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Novo Secretário
                                </button>
                            </div>
                        </div>
                        <div id="listaSecretaria" class="space-y-4">
                            <!-- Secretaria será carregada aqui dinamicamente -->
                        </div>
                    </div>
                </div>
            </div>
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
                    <label for="buscaAluno" class="block text-sm font-medium text-gray-700 mb-1">Buscar Aluno</label>
                    <div class="relative">
                        <input type="text" id="buscaAluno" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Digite o nome ou matrícula do aluno...">
                        <div id="resultadosBusca" class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-60 overflow-y-auto" style="top: calc(100% + 0.25rem);"></div>
                    </div>
                </div>
                <div>
                    <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Aluno Selecionado</label>
                    <select id="matricula" name="matricula_aluno" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Selecione o aluno</option>
                    </select>
                </div>
                <div>
                    <label for="bimestre" class="block text-sm font-medium text-gray-700 mb-1">Bimestre</label>
                    <select id="bimestre" name="bimestre" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
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
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="viceLiderancaModal">
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
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="secretariaModal">
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
    <script src="../js/relatorios.js"></script>
    <script src="../assets/js/alunos.js"></script>
    <script>
        // Mobile menu functionality
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMenu = document.getElementById('closeMenu');

        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
        });

        closeMenu.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });

        // Load students for all modals
        function carregarAlunos() {
            console.log('Carregando alunos...'); // Debug log
            fetch('../control/liderancaControl.php?action=listar_alunos')
                .then(response => response.json())
                .then(data => {
                    console.log('Alunos carregados:', data); // Debug log
                    
                    // Atualizar select do modal de liderança
                    const matriculaSelect = document.getElementById('matricula');
                    matriculaSelect.innerHTML = '<option value="">Selecione o aluno</option>';
                    data.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.matricula;
                        option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                        matriculaSelect.appendChild(option);
                    });

                    // Atualizar select do modal de vice-liderança
                    const matriculaSelectVice = document.getElementById('matriculaVice');
                    matriculaSelectVice.innerHTML = '<option value="">Selecione o aluno</option>';
                    data.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.matricula;
                        option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                        matriculaSelectVice.appendChild(option);
                    });

                    // Atualizar select do modal de secretaria
                    const matriculaSelectSecretaria = document.getElementById('matriculaSecretaria');
                    matriculaSelectSecretaria.innerHTML = '<option value="">Selecione o aluno</option>';
                    data.forEach(aluno => {
                        const option = document.createElement('option');
                        option.value = aluno.matricula;
                        option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                        matriculaSelectSecretaria.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar alunos:', error);
                    alert('Erro ao carregar lista de alunos.');
                });
        }

        // Search students for all modals
        function buscarAlunos(termo, resultadosDiv, matriculaSelect, buscaInput) {
            if (termo.length < 3) {
                resultadosDiv.classList.add('hidden');
                return;
            }

            fetch(`../control/liderancaControl.php?action=buscar_alunos&termo=${encodeURIComponent(termo)}`)
                .then(response => response.json())
                .then(data => {
                    resultadosDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(aluno => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                            div.textContent = `${aluno.matricula} - ${aluno.nome}`;
                            div.onclick = () => {
                                matriculaSelect.value = aluno.matricula;
                                resultadosDiv.classList.add('hidden');
                                buscaInput.value = '';
                            };
                            resultadosDiv.appendChild(div);
                        });
                        resultadosDiv.classList.remove('hidden');
                    } else {
                        resultadosDiv.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar alunos:', error);
                    resultadosDiv.classList.add('hidden');
                });
        }

        // Event listeners for all modals
        document.addEventListener('DOMContentLoaded', function() {
            // Modal de Liderança
            const novaLiderancaBtn = document.getElementById('novaLideranca');
            const liderancaModal = document.getElementById('liderancaModal');
            const liderancaCloseBtn = liderancaModal.querySelector('.close-modal');
            const buscaInput = liderancaModal.querySelector('#buscaAluno');
            const resultadosDiv = liderancaModal.querySelector('#resultadosBusca');
            const matriculaSelect = liderancaModal.querySelector('#matricula');
            const liderancaForm = document.getElementById('liderancaForm');

            novaLiderancaBtn.addEventListener('click', function() {
                console.log('Botão Novo Lider clicado');
                liderancaModal.classList.remove('hidden');
                liderancaModal.classList.add('flex');
                carregarAlunos();
            });

            liderancaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(liderancaForm);
                formData.append('action', 'salvar_lideranca');

                console.log('Enviando dados do formulário de liderança:', {
                    matricula: formData.get('matricula_aluno'),
                    bimestre: formData.get('bimestre')
                });

                fetch('../control/liderancaControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Resposta do servidor:', data);
                    if (data.success) {
                        alert('Liderança salva com sucesso!');
                        liderancaModal.classList.add('hidden');
                        liderancaModal.classList.remove('flex');
                        window.location.reload(); // Recarrega a página para atualizar os dados
                    } else {
                        alert(data.message || 'Erro ao salvar liderança.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao salvar liderança. Por favor, tente novamente.');
                });
            });

            buscaInput.addEventListener('input', (e) => {
                buscarAlunos(e.target.value, resultadosDiv, matriculaSelect, buscaInput);
            });

            // Modal de Vice-Liderança
            const novaViceLiderancaBtn = document.getElementById('novaViceLideranca');
            const viceLiderancaModal = document.getElementById('viceLiderancaModal');
            const viceLiderancaCloseBtn = viceLiderancaModal.querySelector('.close-modal');
            const buscaInputVice = viceLiderancaModal.querySelector('#buscaAlunoVice');
            const resultadosDivVice = viceLiderancaModal.querySelector('#resultadosBuscaVice');
            const matriculaSelectVice = viceLiderancaModal.querySelector('#matriculaVice');
            const viceLiderancaForm = document.getElementById('viceLiderancaForm');

            novaViceLiderancaBtn.addEventListener('click', function() {
                console.log('Botão Novo Vice-Lider clicado');
                viceLiderancaModal.classList.remove('hidden');
                viceLiderancaModal.classList.add('flex');
                carregarAlunos();
            });

            viceLiderancaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(viceLiderancaForm);
                formData.append('action', 'salvar_vice_lideranca');

                console.log('Enviando dados do formulário de vice-liderança:', {
                    matricula: formData.get('matricula_aluno'),
                    bimestre: formData.get('bimestre')
                });

                fetch('../control/liderancaControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Resposta do servidor:', data);
                    if (data.success) {
                        alert('Vice-liderança salva com sucesso!');
                        viceLiderancaModal.classList.add('hidden');
                        viceLiderancaModal.classList.remove('flex');
                        window.location.reload(); // Recarrega a página para atualizar os dados
                    } else {
                        alert(data.message || 'Erro ao salvar vice-liderança.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao salvar vice-liderança. Por favor, tente novamente.');
                });
            });

            buscaInputVice.addEventListener('input', (e) => {
                buscarAlunos(e.target.value, resultadosDivVice, matriculaSelectVice, buscaInputVice);
            });

            // Modal de Secretaria
            const novaSecretariaBtn = document.getElementById('novaSecretaria');
            const secretariaModal = document.getElementById('secretariaModal');
            const secretariaCloseBtn = secretariaModal.querySelector('.close-modal');
            const buscaInputSecretaria = secretariaModal.querySelector('#buscaAlunoSecretaria');
            const resultadosDivSecretaria = secretariaModal.querySelector('#resultadosBuscaSecretaria');
            const matriculaSelectSecretaria = secretariaModal.querySelector('#matriculaSecretaria');
            const secretariaForm = document.getElementById('secretariaForm');

            novaSecretariaBtn.addEventListener('click', function() {
                console.log('Botão Novo Secretario clicado');
                secretariaModal.classList.remove('hidden');
                secretariaModal.classList.add('flex');
                carregarAlunos();
            });

            secretariaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(secretariaForm);
                formData.append('action', 'salvar_secretaria');

                console.log('Enviando dados do formulário de secretaria:', {
                    matricula: formData.get('matricula_aluno'),
                    bimestre: formData.get('bimestre')
                });

                fetch('../control/liderancaControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Resposta do servidor:', data);
                    if (data.success) {
                        alert('Secretaria salva com sucesso!');
                        secretariaModal.classList.add('hidden');
                        secretariaModal.classList.remove('flex');
                        window.location.reload(); // Recarrega a página para atualizar os dados
                    } else {
                        alert(data.message || 'Erro ao salvar secretaria.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao salvar secretaria. Por favor, tente novamente.');
                });
            });

            buscaInputSecretaria.addEventListener('input', (e) => {
                buscarAlunos(e.target.value, resultadosDivSecretaria, matriculaSelectSecretaria, buscaInputSecretaria);
            });

            // Close buttons for all modals
            liderancaCloseBtn.addEventListener('click', () => {
                liderancaModal.classList.add('hidden');
                liderancaModal.classList.remove('flex');
            });

            viceLiderancaCloseBtn.addEventListener('click', () => {
                viceLiderancaModal.classList.add('hidden');
                viceLiderancaModal.classList.remove('flex');
            });

            secretariaCloseBtn.addEventListener('click', () => {
                secretariaModal.classList.add('hidden');
                secretariaModal.classList.remove('flex');
            });

            // Close modals when clicking outside
            window.addEventListener('click', (event) => {
                if (event.target === liderancaModal) {
                    liderancaModal.classList.add('hidden');
                    liderancaModal.classList.remove('flex');
                }
                if (event.target === viceLiderancaModal) {
                    viceLiderancaModal.classList.add('hidden');
                    viceLiderancaModal.classList.remove('flex');
                }
                if (event.target === secretariaModal) {
                    secretariaModal.classList.add('hidden');
                    secretariaModal.classList.remove('flex');
                }
            });
        });

        // Load leadership data
        function carregarLideranca() {
            fetch('../control/liderancaControl.php?action=listar_lideranca')
                .then(response => response.json())
                .then(data => {
                    const listaLideranca = document.getElementById('listaLideranca');
                    listaLideranca.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(lideranca => {
                            const div = document.createElement('div');
                            div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                            
                            div.innerHTML = `
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-500">Bimestre: ${lideranca.bimestre}</span>
                                    <button onclick="excluirLideranca('${lideranca.matricula_lider}')" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <h3 class="text-lg font-semibold mb-2">${lideranca.nome}</h3>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>Matrícula: ${lideranca.matricula_lider}</span>
                                </div>
                            `;
                            
                            listaLideranca.appendChild(div);
                        });
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

        // Função para excluir liderança
        function excluirLideranca(matricula) {
            if (confirm('Tem certeza que deseja excluir este líder?')) {
                const formData = new FormData();
                formData.append('action', 'excluir_lideranca');
                formData.append('matricula', matricula);

                fetch('../control/liderancaControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        carregarLideranca();
                        alert('Líder excluído com sucesso!');
                    } else {
                        alert(data.message || 'Erro ao excluir líder.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao excluir líder. Por favor, tente novamente.');
                });
            }
        }

        // Função para manter o menu lateral visível durante a navegação
        function manterMenu(event) {
            event.preventDefault();
            const href = event.currentTarget.getAttribute('href');
            
            // Salva o estado do menu no localStorage
            localStorage.setItem('menuVisivel', 'true');
            
            // Redireciona para a página desejada
            window.location.href = href;
        }

        // Verifica se o menu deve estar visível ao carregar a página
        document.addEventListener('DOMContentLoaded', () => {
            const menuVisivel = localStorage.getItem('menuVisivel');
            if (menuVisivel === 'true') {
                document.querySelector('.md\\:flex').classList.remove('hidden');
            }
            
            // Carrega os dados iniciais
            carregarLideranca();
            carregarViceLideranca();
            carregarSecretaria();
        });

        // Load vice-liderança data
        function carregarViceLideranca() {
            fetch('../control/liderancaControl.php?action=listar_vice_lideranca')
                .then(response => response.json())
                .then(data => {
                    const listaViceLideranca = document.getElementById('listaViceLideranca');
                    listaViceLideranca.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(viceLideranca => {
                            const div = document.createElement('div');
                            div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                            
                            div.innerHTML = `
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-500">Bimestre: ${viceLideranca.bimestre}</span>
                                    <button onclick="excluirViceLideranca('${viceLideranca.matricula_vice_lider}')" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <h3 class="text-lg font-semibold mb-2">${viceLideranca.nome}</h3>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>Matrícula: ${viceLideranca.matricula_vice_lider}</span>
                                </div>
                            `;
                            
                            listaViceLideranca.appendChild(div);
                        });
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

        // Load secretaria data
        function carregarSecretaria() {
            fetch('../control/liderancaControl.php?action=listar_secretaria')
                .then(response => response.json())
                .then(data => {
                    const listaSecretaria = document.getElementById('listaSecretaria');
                    listaSecretaria.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(secretaria => {
                            const div = document.createElement('div');
                            div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200';
                            
                            div.innerHTML = `
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-500">Bimestre: ${secretaria.bimestre}</span>
                                    <button onclick="excluirSecretaria('${secretaria.matricula_secretario}')" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <h3 class="text-lg font-semibold mb-2">${secretaria.nome}</h3>
                                <div class="flex justify-between text-sm text-gray-500">
                                    <span>Matrícula: ${secretaria.matricula_secretario}</span>
                                </div>
                            `;
                            
                            listaSecretaria.appendChild(div);
                        });
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

        // Função para excluir vice-liderança
        function excluirViceLideranca(matricula) {
            if (confirm('Tem certeza que deseja excluir este vice-líder?')) {
                const formData = new FormData();
                formData.append('action', 'excluir_vice_lideranca');
                formData.append('matricula', matricula);

                fetch('../control/liderancaControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        carregarViceLideranca();
                        alert('Vice-líder excluído com sucesso!');
                    } else {
                        alert(data.message || 'Erro ao excluir vice-líder.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao excluir vice-líder. Por favor, tente novamente.');
                });
            }
        }

        // Função para excluir secretaria
        function excluirSecretaria(matricula) {
            if (confirm('Tem certeza que deseja excluir este secretário?')) {
                const formData = new FormData();
                formData.append('action', 'excluir_secretaria');
                formData.append('matricula', matricula);

                fetch('../control/liderancaControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        carregarSecretaria();
                        alert('Secretário excluído com sucesso!');
                    } else {
                        alert(data.message || 'Erro ao excluir secretário.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao excluir secretário. Por favor, tente novamente.');
                });
            }
        }
    </script>
</body>
</html> 