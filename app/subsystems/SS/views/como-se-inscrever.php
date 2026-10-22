<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Como se Inscrever - SEEPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#2D5016',
                        'ceara-orange': '#FF8C00'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-gradient-to-r from-ceara-green to-green-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-ceara-orange rounded-xl flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">SEEPS</h1>
                        <p class="text-sm text-gray-200">Sistema de Seleção</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="inicio.php" class="hover:text-ceara-orange transition-colors">Início</a>
                    <a href="sobre-sistema.html" class="hover:text-ceara-orange transition-colors">Sobre</a>
                    <a href="#" class="text-ceara-orange font-medium">Como se Inscrever</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-ceara-orange rounded-full mb-6">
                <i class="fas fa-user-plus text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Como se Inscrever</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Siga nosso guia passo a passo para realizar sua inscrição no processo seletivo da EEEP Salaberga
            </p>
        </div>

        <!-- Requirements Alert -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800">Importante!</h3>
                    <p class="text-blue-700">Tenha em mãos todas as suas notas do 6º, 7º, 8º e 9º anos antes de iniciar a inscrição.</p>
                </div>
            </div>
        </div>

        <!-- Step by Step Guide -->
        <div class="space-y-8">
            <!-- Step 1 -->
            <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-ceara-green">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-ceara-green rounded-full flex items-center justify-center text-white font-bold text-lg">
                            1
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Escolha seu Curso</h3>
                        <p class="text-gray-600 mb-6">
                            Selecione o curso técnico que deseja cursar. Temos 4 opções disponíveis:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-heartbeat text-red-600 text-xl"></i>
                                    <div>
                                        <h4 class="font-semibold text-red-800">Enfermagem</h4>
                                        <p class="text-sm text-red-600">Cuidados com a saúde</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-laptop-code text-blue-600 text-xl"></i>
                                    <div>
                                        <h4 class="font-semibold text-blue-800">Informática</h4>
                                        <p class="text-sm text-blue-600">Tecnologia e programação</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                                    <div>
                                        <h4 class="font-semibold text-green-800">Administração</h4>
                                        <p class="text-sm text-green-600">Gestão e negócios</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-building text-gray-600 text-xl"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Edificações</h4>
                                        <p class="text-sm text-gray-600">Construção civil</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-ceara-orange">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-ceara-orange rounded-full flex items-center justify-center text-white font-bold text-lg">
                            2
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Selecione a Modalidade</h3>
                        <p class="text-gray-600 mb-6">
                            Escolha se você estudou em escola pública ou privada:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                <div class="text-center">
                                    <i class="fas fa-school text-green-600 text-3xl mb-3"></i>
                                    <h4 class="font-semibold text-green-800 mb-2">Escola Pública</h4>
                                    <p class="text-sm text-green-600">Para estudantes que cursaram o ensino fundamental em escola pública</p>
                                </div>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <div class="text-center">
                                    <i class="fas fa-university text-blue-600 text-3xl mb-3"></i>
                                    <h4 class="font-semibold text-blue-800 mb-2">Escola Privada</h4>
                                    <p class="text-sm text-blue-600">Para estudantes que cursaram o ensino fundamental em escola privada</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-purple-500">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            3
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Preencha seus Dados Pessoais</h3>
                        <p class="text-gray-600 mb-6">
                            Informe suas informações pessoais corretamente:
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-user text-purple-500"></i>
                                <span class="text-gray-700">Nome completo</span>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-calendar text-purple-500"></i>
                                <span class="text-gray-700">Data de nascimento</span>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-map-marker-alt text-purple-500"></i>
                                <span class="text-gray-700">Bairro (Cota ou Outros)</span>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-wheelchair text-purple-500"></i>
                                <span class="text-gray-700">PCD (se aplicável)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-yellow-500">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            4
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Insira suas Notas</h3>
                        <p class="text-gray-600 mb-6">
                            Preencha suas notas do ensino fundamental. Campos obrigatórios estão marcados:
                        </p>
                        
                        <!-- Required Subjects -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3">Disciplinas Obrigatórias:</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <div class="flex items-center space-x-2 p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-star text-red-500 text-sm"></i>
                                    <span class="text-sm text-red-700 font-medium">Português</span>
                                </div>
                                <div class="flex items-center space-x-2 p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-star text-red-500 text-sm"></i>
                                    <span class="text-sm text-red-700 font-medium">Matemática</span>
                                </div>
                                <div class="flex items-center space-x-2 p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-star text-red-500 text-sm"></i>
                                    <span class="text-sm text-red-700 font-medium">História</span>
                                </div>
                                <div class="flex items-center space-x-2 p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-star text-red-500 text-sm"></i>
                                    <span class="text-sm text-red-700 font-medium">Geografia</span>
                                </div>
                                <div class="flex items-center space-x-2 p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-star text-red-500 text-sm"></i>
                                    <span class="text-sm text-red-700 font-medium">Ciências</span>
                                </div>
                                <div class="flex items-center space-x-2 p-2 bg-red-50 rounded-lg">
                                    <i class="fas fa-star text-red-500 text-sm"></i>
                                    <span class="text-sm text-red-700 font-medium">Inglês</span>
                                </div>
                            </div>
                        </div>

                        <!-- Optional Subjects -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3">Disciplinas Opcionais:</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-circle text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">Artes</span>
                                </div>
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-circle text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">Educação Física</span>
                                </div>
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg">
                                    <i class="fas fa-circle text-gray-400 text-sm"></i>
                                    <span class="text-sm text-gray-600">Religião</span>
                                </div>
                            </div>
                        </div>

                        <!-- Years -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                <h5 class="font-semibold text-blue-800">6º Ano</h5>
                                <p class="text-sm text-blue-600">Notas finais</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <h5 class="font-semibold text-green-800">7º Ano</h5>
                                <p class="text-sm text-green-600">Notas finais</p>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <h5 class="font-semibold text-yellow-800">8º Ano</h5>
                                <p class="text-sm text-yellow-600">Notas finais</p>
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                                <h5 class="font-semibold text-red-800">9º Ano</h5>
                                <p class="text-sm text-red-600">Por bimestre + média</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-green-500">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            5
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Finalize sua Inscrição</h3>
                        <p class="text-gray-600 mb-6">
                            Após preencher todas as informações, revise seus dados e confirme a inscrição.
                        </p>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                <div>
                                    <h4 class="font-semibold text-green-800">Inscrição Concluída!</h4>
                                    <p class="text-sm text-green-600">Você receberá uma confirmação e poderá acompanhar o processo seletivo.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="mt-12 bg-yellow-50 border border-yellow-200 rounded-xl p-8">
            <h3 class="text-xl font-bold text-yellow-800 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                Informações Importantes
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-yellow-800 mb-2">Documentação</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Histórico escolar atualizado</li>
                        <li>• Documento de identidade</li>
                        <li>• Comprovante de residência</li>
                        <li>• Certidão de nascimento</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-yellow-800 mb-2">Critérios de Seleção</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Média das notas do ensino fundamental</li>
                        <li>• Prioridade para escola pública</li>
                        <li>• Sistema de cotas por bairro</li>
                        <li>• Vagas reservadas para PCD</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="text-center mt-12">
            <a href="inicio.php" class="inline-flex items-center px-8 py-4 bg-ceara-green hover:bg-green-700 text-white font-semibold rounded-xl transition-all duration-200 hover:scale-105 shadow-lg">
                <i class="fas fa-rocket mr-3"></i>
                Iniciar Inscrição Agora
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-ceara-green via-green-600 to-green-800 text-white w-full mt-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-y-1"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-8">
            <div class="text-center">
                <p class="text-sm text-gray-200">&copy; 2024 SEEPS - Todos os direitos reservados</p>
            </div>
        </div>
    </footer>
</body>
</html>
