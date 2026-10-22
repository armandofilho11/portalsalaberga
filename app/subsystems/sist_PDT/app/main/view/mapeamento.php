<!DOCTYPE html>
<?php
session_start();
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapeamento - Plataforma de Gestão Escolar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#007A33',
                        'secondary': '#FFA500',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white">
    <!-- Mobile Menu Toggle -->
    <div class="fixed top-4 left-4 md:hidden">
        <button id="menuToggle" class="p-2 rounded-md bg-primary shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
    </div>

    <div class="flex h-screen">
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="fixed inset-0 bg-primary bg-opacity-75 z-50 hidden">
            <div class="absolute top-0 right-0 p-4">
                <button id="closeMenu" class="p-2 rounded-md bg-secondary shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="flex flex-col p-4 space-y-4">
                <a href="dashboard.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
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
                <a href="mapeamento.php" class="flex items-center p-2 text-white bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Mapeamento
                </a>
                <a href="lideranca.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
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
        <div class="hidden md:flex md:flex-col w-64 bg-primary text-white">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">Menu</h2>
            </div>
            <div class="flex flex-col p-4 space-y-2">
                <a href="dashboard.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
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
                <a href="mapeamento.php" class="flex items-center p-2 text-white bg-[#FFA500] rounded-md">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Mapeamento
                </a>
                <a href="lideranca.php" class="flex items-center p-2 text-white hover:bg-[#FFA500] rounded-md" onclick="return manterMenu(event)">
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
                    <h1 class="text-2xl font-bold">Mapeamento</h1>
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
                    <!-- Section Header -->
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold">Mapeamento da Sala</h2>
                        <div class="flex space-x-2">
                            <button id="editarMapeamento" class="flex items-center px-4 py-2 bg-secondary text-white rounded-md hover:bg-secondary/90 transition-colors duration-200 shadow-md">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Editar Mapeamento
                            </button>
                            <a href="../view/gerar_relatorio.php?tipo=mapeamento" target="_blank" class="flex items-center px-4 py-2 bg-[#007A33] text-white rounded-md hover:bg-[#007A33]/90 transition-colors duration-200 shadow-md">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                Gerar Relatório
                            </a>
                        </div>
                    </div>

                    <!-- Mapeamento Grid -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carteira</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aluno</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data do Mapeamento</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="listaMapeamento">
                                    <!-- Os dados serão carregados aqui dinamicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Mapeamento -->
    <div id="mapeamentoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
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
                <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary/90 transition-colors duration-200">
                    Salvar Alterações
                </button>
            </form>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="../js/relatorios.js"></script>
    <script src="../assets/js/alunos.js"></script>
    <script>
        // Modal functionality
        const modal = document.getElementById('mapeamentoModal');
        const btn = document.getElementById('editarMapeamento');
        const span = document.getElementsByClassName('close-modal')[0];
        const buscaInput = document.getElementById('buscaAluno');
        const resultadosDiv = document.getElementById('resultadosBusca');
        const matriculaSelect = document.getElementById('mapeamentoMatricula');

        // Função para carregar o mapeamento
        function carregarMapeamento() {
            fetch('../control/mapeamentoControl.php?action=listar_mapeamento')
                .then(response => response.json())
                .then(data => {
                    const listaMapeamento = document.getElementById('listaMapeamento');
                    listaMapeamento.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(mapeamento => {
                            const tr = document.createElement('tr');
                            const data = new Date(mapeamento.data_mapeamento);
                            const dataFormatada = data.toLocaleDateString('pt-BR');
                            
                            tr.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${mapeamento.numero_carteira}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${mapeamento.matricula_aluno} - ${mapeamento.nome_aluno}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${dataFormatada}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button onclick="excluirMapeamento(${mapeamento.numero_carteira})" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            `;
                            
                            listaMapeamento.appendChild(tr);
                        });
                    } else {
                        listaMapeamento.innerHTML = `
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Nenhum mapeamento registrado
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar mapeamento:', error);
                    const listaMapeamento = document.getElementById('listaMapeamento');
                    listaMapeamento.innerHTML = `
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-red-500">
                                Erro ao carregar mapeamento. Por favor, tente novamente.
                            </td>
                        </tr>
                    `;
                });
        }

        // Carregar mapeamento quando a página carregar
        document.addEventListener('DOMContentLoaded', carregarMapeamento);

        btn.onclick = function() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            
            document.getElementById('mapeamentoData').value = `${yyyy}-${mm}-${dd}`;
            carregarAlunos(matriculaSelect);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Inicializar busca de alunos
        inicializarBuscaAlunos(buscaInput, resultadosDiv, matriculaSelect);

        span.onclick = function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Form validation
        document.getElementById('mapeamentoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const carteira = document.getElementById('mapeamentoCarteira').value;
            const matricula = document.getElementById('mapeamentoMatricula').value;
            const data = document.getElementById('mapeamentoData').value;

            if (!carteira || !matricula || !data) {
                alert('Por favor, preencha todos os campos.');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'salvar_mapeamento');
            formData.append('numero_carteira', carteira);
            formData.append('matricula_aluno', matricula);
            formData.append('data_mapeamento', data);

            fetch('../control/mapeamentoControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Mapeamento salvo com sucesso!');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    this.reset();
                    carregarMapeamento();
                } else {
                    alert(data.error || 'Erro ao salvar mapeamento.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao salvar mapeamento.');
            });
        });

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
        });

        // Adiciona o botão de relatório quando a página carregar
        document.addEventListener('DOMContentLoaded', () => {
            carregarMapeamento();
            adicionarBotaoRelatorio('listaMapeamento', 'mapeamento');
        });

        // Função para excluir mapeamento
        function excluirMapeamento(numero_carteira) {
            if (confirm('Tem certeza que deseja excluir este mapeamento?')) {
                const formData = new FormData();
                formData.append('action', 'excluir_mapeamento');
                formData.append('numero_carteira', numero_carteira);

                fetch('../control/mapeamentoControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Mapeamento excluído com sucesso!');
                        carregarMapeamento();
                    } else {
                        alert(data.error || 'Erro ao excluir mapeamento');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao excluir mapeamento. Por favor, tente novamente.');
                });
            }
        }
    </script>
</body>
</html> 