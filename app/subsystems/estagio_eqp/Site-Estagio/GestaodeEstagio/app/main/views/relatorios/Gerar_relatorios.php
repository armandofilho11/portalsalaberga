<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Sistema de Gestão de Estágio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../../config/img/logo_Salaberga-removebg-preview.png" type="image/x-icon">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        'ceara-moss': '#2d4739',
                        primary: '#008C45',
                        secondary: '#FFA500',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f3f4f6;
        }

        .header-moss {
            background: #2d4739;
        }
        .header-moss * {
            color: #fff !important;
        }

        .transparent-button {
            background: none;
            transition: all 0.3s ease;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            color: #ffffff;
        }

        .transparent-button:hover {
            color: #FFA500;
            transform: translateY(-1px);
        }

        .main-container {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            margin: 0 auto;
            max-width: 1200px;
            padding: 2rem;
        }

        .gradient-button {
            background: linear-gradient(to right, #FFA500, #008C45);
            transition: all 0.3s ease;
        }
        .gradient-button:hover {
            background: linear-gradient(to right, #008C45, #FFA500);
            transform: scale(1.05);
        }

        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .main-container {
                padding: 1rem;
            }
        }
        @media (max-width: 600px) {
            .main-container {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Cabeçalho verde musgo -->
    <header class="header-moss w-full shadow-lg mb-8">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Left section with back button, logo and school name -->
                <div class="flex items-center gap-3">
                    <a href="javascript:history.back()" class="transparent-button">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <img src="../../config/img/logo_Salaberga-removebg-preview.png" alt="Logo EEEP Salaberga" class="w-10 h-10 object-contain">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium">EEEP Salaberga</span>
                        <h1 class="text-lg font-bold">Geração de Relatórios</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto px-4 py-8 fade-in">
        <div class="main-container">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Relatório de Alunos -->
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-6">
                        <div class="bg-[#008C45]/10 p-3 rounded-lg mr-4">
                            <i class="fas fa-user-graduate text-[#008C45] text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Relatório de Alunos</h2>
                    </div>
                    <form action="gerar_relatorio_alunos.php" method="POST" target="_blank">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Filtrar por:</label>
                            <select name="filtro_aluno" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent mb-3">
                                <option value="todos">Todos os Alunos</option>
                                <option value="curso">Por Curso</option>
                                <option value="nome">Por Nome</option>
                                <option value="local">Por Local</option>
                            </select>
                            
                            <div id="curso_select" class="hidden">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Selecione o Curso:</label>
                                <select name="curso" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent">
                                    <option value="enfermagem">Enfermagem</option>
                                    <option value="informatica">Informática</option>
                                    <option value="administracao">Administração</option>
                                    <option value="edificacoes">Edificações</option>
                                    <option value="meio_ambiente">Meio Ambiente</option>
                                </select>
                            </div>

                            <div id="nome_input" class="hidden">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Digite o Nome:</label>
                                <input type="text" name="nome_aluno" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent" placeholder="Nome do aluno">
                            </div>

                            <div id="local_input" class="hidden">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Digite o Local:</label>
                                <input type="text" name="local_aluno" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent" placeholder="Local do aluno">
                            </div>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-ceara-orange to-ceara-green hover:from-ceara-green hover:to-ceara-orange text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                            <i class="fas fa-file-pdf"></i>
                            Gerar Relatório
                        </button>
                    </form>
                </div>

                <!-- Relatório de Concedentes -->
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-6">
                        <div class="bg-[#008C45]/10 p-3 rounded-lg mr-4">
                            <i class="fas fa-building text-[#008C45] text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Relatório de Concedentes</h2>
                    </div>
                    <form action="gerar_relatorio_concedentes.php" method="POST" target="_blank">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Filtrar por:</label>
                            <select name="filtro_concedente" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent mb-3">
                                <option value="todos">Todos os Concedentes</option>
                                <option value="perfil">Por Perfil</option>
                                <option value="endereco">Por Endereço</option>
                                <option value="numero_vagas">Por Quantidade de Vagas</option>
                            </select>

                            <div id="perfil_input" class="hidden">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Digite o Perfil:</label>
                                <input type="text" name="perfil" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent" placeholder="Perfil do concedente">
                            </div>

                            <div id="endereco_input" class="hidden">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Digite o Endereço:</label>
                                <input type="text" name="endereco" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent" placeholder="Endereço do concedente">
                            </div>

                            <div id="vagas_input" class="hidden">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Quantidade de Vagas:</label>
                                <select name="numero_vagas" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent">
                                    <option value="1-5">1 a 5 vagas</option>
                                    <option value="6-10">6 a 10 vagas</option>
                                    <option value="11-20">11 a 20 vagas</option>
                                    <option value="21+">Mais de 20 vagas</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-ceara-orange to-ceara-green hover:from-ceara-green hover:to-ceara-orange text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                            <i class="fas fa-file-pdf"></i>
                            Gerar Relatório
                        </button>
                    </form>
                </div>

                <!-- Relatório de Seleções -->
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center mb-6">
                        <div class="bg-[#008C45]/10 p-3 rounded-lg mr-4">
                            <i class="fas fa-clipboard-list text-[#008C45] text-xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">Relatório de Seleções</h2>
                    </div>
                    <form action="gerar_relatorio_selecoes.php" method="POST" target="_blank">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Tipo de Relatório:</label>
                            <select name="tipo_relatorio" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent mb-3">
                                <option value="processo_seletivo">Processo Seletivo</option>
                                <option value="inscricoes">Inscrições</option>
                                <option value="alunos_alocados">Alunos Alocados</option>
                            </select>

                            <div id="curso_select_selecao" class="mb-3">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Filtrar por Curso:</label>
                                <select name="curso_selecao" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:border-transparent">
                                    <option value="todos">Todos os Cursos</option>
                                    <option value="enfermagem">Enfermagem</option>
                                    <option value="informatica">Informática</option>
                                    <option value="administracao">Administração</option>
                                    <option value="edificacoes">Edificações</option>
                                    <option value="meio_ambiente">Meio Ambiente</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-ceara-orange to-ceara-green hover:from-ceara-green hover:to-ceara-orange text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                            <i class="fas fa-file-pdf"></i>
                            Gerar Relatório
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-ceara-moss text-white shadow-lg mt-12">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm sm:text-base mb-4 sm:mb-0">© 2025 Sistema de Gestão de Estágio. Todos os direitos reservados.</p>
                <div class="flex space-x-6">
                    <a href="https://www.instagram.com/eeepsalabergampe/" class="text-gray-400 hover:text-ceara-orange transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Função para mostrar/esconder campos baseado na seleção
        function toggleFields(selectId, fieldId) {
            const select = document.querySelector(selectId);
            const field = document.querySelector(fieldId);
            
            select.addEventListener('change', function() {
                // Esconder todos os campos primeiro
                document.querySelector('#curso_select').classList.add('hidden');
                document.querySelector('#nome_input').classList.add('hidden');
                document.querySelector('#local_input').classList.add('hidden');
                
                // Mostrar o campo apropriado
                if (this.value === 'curso') {
                    document.querySelector('#curso_select').classList.remove('hidden');
                } else if (this.value === 'nome') {
                    document.querySelector('#nome_input').classList.remove('hidden');
                } else if (this.value === 'local') {
                    document.querySelector('#local_input').classList.remove('hidden');
                }
            });
        }

        // Função para mostrar/esconder campos do concedente
        function toggleConcedenteFields() {
            const select = document.querySelector('select[name="filtro_concedente"]');
            const perfilInput = document.querySelector('#perfil_input');
            const enderecoInput = document.querySelector('#endereco_input');
            const vagasInput = document.querySelector('#vagas_input');
            
            select.addEventListener('change', function() {
                // Esconder todos os campos primeiro
                perfilInput.classList.add('hidden');
                enderecoInput.classList.add('hidden');
                vagasInput.classList.add('hidden');
                
                // Mostrar o campo apropriado
                if (this.value === 'perfil') {
                    perfilInput.classList.remove('hidden');
                } else if (this.value === 'endereco') {
                    enderecoInput.classList.remove('hidden');
                } else if (this.value === 'numero_vagas') {
                    vagasInput.classList.remove('hidden');
                }
            });
        }

        // Inicializar as funções
        toggleFields('select[name="filtro_aluno"]', '#curso_select');
        toggleConcedenteFields();
    });
    </script>
</body>
</html>
