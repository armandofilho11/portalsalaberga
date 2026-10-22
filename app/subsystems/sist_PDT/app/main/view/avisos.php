<!DOCTYPE html>
<?php
session_start();
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avisos - Plataforma de Gestão Escolar</title>
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
        <button id="menuToggle" class="p-2 rounded-md bg-secondary shadow-md">
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
                <a href="avisos.php" class="flex items-center p-2 text-white bg-[#FFA500] rounded-md">
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
                <a href="avisos.php" class="flex items-center p-2 bg-[#FFA500] rounded-md">
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
                    <h1 class="text-2xl font-bold">Avisos</h1>
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
                    <!-- Avisos Panel -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">Avisos da Turma</h2>
                            <div class="flex space-x-2">
                                <button id="novoAviso" class="flex items-center px-4 py-2 bg-secondary text-white rounded-md hover:bg-secondary/90 transition-colors duration-200 shadow-md">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Novo Aviso
                                </button>
                                <a href="../view/gerar_relatorio.php?tipo=avisos" target="_blank" class="flex items-center px-4 py-2 bg-[#007A33] text-white rounded-md hover:bg-[#007A33]/90 transition-colors duration-200 shadow-md">
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
                        <div class="space-y-4" id="listaAvisos">
                            <!-- Os avisos serão carregados aqui dinamicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Novo Aviso -->
    <div id="avisoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
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
                
                <input type="submit" name="registrar" value="Registrar Aviso" class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary/90 transition-colors duration-200">
            </form>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script src="../js/relatorios.js"></script>
    <script src="../assets/js/alunos.js"></script>
    <script>
        // Modal functionality for Novo Aviso
        const modalAviso = document.getElementById('avisoModal');
        const btnNovoAviso = document.getElementById('novoAviso');
        const closeAvisoModal = document.getElementsByClassName('close-modal')[0];
        const buscaInput = document.getElementById('buscaAluno');
        const resultadosDiv = document.getElementById('resultadosBusca');
        const matriculaSelect = document.getElementById('matricula');

        btnNovoAviso.onclick = function() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            
            document.getElementById('data').value = `${yyyy}-${mm}-${dd}`;
            carregarAlunos(matriculaSelect);
            modalAviso.classList.remove('hidden');
            modalAviso.classList.add('flex');
        }

        inicializarBuscaAlunos(buscaInput, resultadosDiv, matriculaSelect);

        closeAvisoModal.onclick = function() {
            modalAviso.classList.add('hidden');
            modalAviso.classList.remove('flex');
        }

        // Click fora do modal para fechar o modal Novo Aviso
        window.onclick = function(event) {
            if (event.target == modalAviso) {
                modalAviso.classList.add('hidden');
                modalAviso.classList.remove('flex');
            }
        }

        // Função para carregar avisos na lista principal
        function carregarAvisos() {
            fetch('../control/avisosControl.php?action=listar_avisos')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao carregar avisos');
                    }
                    return response.json();
                })
                .then(data => {
                    const listaAvisos = document.getElementById('listaAvisos');
                    listaAvisos.innerHTML = '';
                    
                    if (Array.isArray(data) && data.length > 0) {
                        // Ordenar os avisos por data (mais recente primeiro)
                        data.sort((a, b) => new Date(b.data_aviso) - new Date(a.data_aviso));
                        
                        data.forEach(aviso => {
                            const div = document.createElement('div');
                            div.className = 'border rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200 mb-4';
                            
                            const data = new Date(aviso.data_aviso);
                            const dataFormatada = data.toLocaleDateString('pt-BR') + ' ' + data.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
                            
                            // Verifica se o aviso é recente (últimas 24 horas)
                            const agora = new Date();
                            const diferenca = agora - data;
                            const ehRecente = diferenca <= 24 * 60 * 60 * 1000;
                            
                            div.innerHTML = `
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-500">${dataFormatada}</span>
                                    <div class="flex items-center space-x-2">
                                        ${ehRecente ? '<span class="px-2 py-1 text-xs bg-secondary text-white rounded-full">Novo</span>' : ''}
                                        <button onclick="excluirAviso(${aviso.id})" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Aluno:</span> ${aviso.nome_aluno}
                                </div>
                                <div class="text-gray-700">
                                    ${aviso.aviso}
                                </div>
                            `;
                            listaAvisos.appendChild(div);
                        });
                    } else {
                        listaAvisos.innerHTML = '<p class="text-gray-500 text-center">Nenhum aviso encontrado.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar avisos:', error);
                    alert('Erro ao carregar avisos. Por favor, tente novamente.');
                });
        }

        // Carregar avisos ao iniciar a página
        carregarAvisos();

        // Atualizar a lista após registrar um novo aviso
        document.getElementById('avisoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('../control/avisosControl.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalAviso.classList.add('hidden');
                    modalAviso.classList.remove('flex');
                    this.reset();
                    carregarAvisos();
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

        // Função para excluir aviso
        function excluirAviso(id) {
            console.log('Tentando excluir aviso com ID:', id);
            
            if (!id) {
                console.error('ID do aviso não fornecido');
                alert('Erro: ID do aviso não encontrado');
                return;
            }

            if (confirm('Tem certeza que deseja excluir este aviso?')) {
                const formData = new FormData();
                formData.append('action', 'excluir_aviso');
                formData.append('id', String(id));

                fetch('../control/avisosControl.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Status da resposta:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Resposta do servidor:', data);
                    if (data.success) {
                        carregarAvisos();
                        alert('Aviso excluído com sucesso!');
                    } else {
                        throw new Error(data.message || 'Erro ao excluir aviso.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert(error.message || 'Erro ao excluir aviso. Por favor, tente novamente.');
                });
            }
        }
    </script>
</body>
</html> 