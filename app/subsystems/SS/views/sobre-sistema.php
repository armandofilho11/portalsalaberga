<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre o Sistema - SEEPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://salaberga.com/salaberga/portalsalaberga/app/main/assets/img/Design%20sem%20nome.svg" type="image/x-icon">
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
                    <a href="#" class="text-ceara-orange font-medium">Sobre</a>
                    <a href="como-se-inscrever.html" class="hover:text-ceara-orange transition-colors">Como se Inscrever</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-ceara-green rounded-full mb-6">
                <i class="fas fa-info-circle text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Sobre o Sistema SEEPS</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Conheça o Sistema de Ensino e Educação Profissional Salaberga e como funciona nosso processo seletivo
            </p>
        </div>

        <!-- What is SEEPS -->
        <section class="mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-start space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 bg-ceara-green rounded-xl flex items-center justify-center">
                            <i class="fas fa-school text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">O que é o SEEPS?</h2>
                        <p class="text-gray-600 text-lg leading-relaxed mb-6">
                            O SEEPS (Sistema de Ensino e Educação Profissional Salaberga) é a plataforma digital oficial 
                            da EEEP Salaberga para gerenciar o processo seletivo de ingresso nos cursos técnicos integrados 
                            ao ensino médio.
                        </p>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            Nossa escola está localizada em Santa Rita, Ceará, e oferece educação profissional de qualidade, 
                            preparando jovens para o mercado de trabalho e para o ensino superior.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission, Vision, Values -->
        <section class="mb-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Mission -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-ceara-orange rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-target text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Nossa Missão</h3>
                    <p class="text-gray-600">
                        Proporcionar educação profissional de excelência, formando cidadãos críticos e competentes 
                        para o mundo do trabalho e para a vida em sociedade.
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-eye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Nossa Visão</h3>
                    <p class="text-gray-600">
                        Ser referência em educação profissional no Ceará, reconhecida pela qualidade do ensino 
                        e pela formação integral dos estudantes.
                    </p>
                </div>

                <!-- Values -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Nossos Valores</h3>
                    <p class="text-gray-600">
                        Ética, respeito, inovação, sustentabilidade e compromisso com a transformação social 
                        através da educação.
                    </p>
                </div>
            </div>
        </section>

        <!-- Courses Available -->
        <section class="mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Cursos Oferecidos</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Enfermagem -->
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-heartbeat text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-red-800">Técnico em Enfermagem</h3>
                                <p class="text-sm text-red-600">Área da Saúde</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">
                            Formação para atuar na assistência de enfermagem em hospitais, clínicas, 
                            postos de saúde e demais estabelecimentos de saúde.
                        </p>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-2 text-red-500"></i>
                                Duração: 3 anos
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-certificate mr-2 text-red-500"></i>
                                Certificação profissional
                            </div>
                        </div>
                    </div>

                    <!-- Informática -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-laptop-code text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-blue-800">Técnico em Informática</h3>
                                <p class="text-sm text-blue-600">Área de Tecnologia</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">
                            Formação em desenvolvimento de sistemas, manutenção de computadores, 
                            redes e suporte técnico em tecnologia da informação.
                        </p>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                Duração: 3 anos
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-certificate mr-2 text-blue-500"></i>
                                Certificação profissional
                            </div>
                        </div>
                    </div>

                    <!-- Administração -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-green-800">Técnico em Administração</h3>
                                <p class="text-sm text-green-600">Área de Gestão</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">
                            Formação em gestão empresarial, recursos humanos, finanças, 
                            marketing e empreendedorismo.
                        </p>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                Duração: 3 anos
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-certificate mr-2 text-green-500"></i>
                                Certificação profissional
                            </div>
                        </div>
                    </div>

                    <!-- Edificações -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Técnico em Edificações</h3>
                                <p class="text-sm text-gray-600">Área de Construção</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-4">
                            Formação em projetos arquitetônicos, construção civil, 
                            orçamentos e acompanhamento de obras.
                        </p>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-clock mr-2 text-gray-500"></i>
                                Duração: 3 anos
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-certificate mr-2 text-gray-500"></i>
                                Certificação profissional
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How the System Works -->
        <section class="mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Como Funciona o Sistema</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-plus text-white text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">1. Inscrição</h3>
                        <p class="text-sm text-gray-600">Cadastro online com dados pessoais e notas do ensino fundamental</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calculator text-white text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">2. Cálculo</h3>
                        <p class="text-sm text-gray-600">Sistema calcula automaticamente a média das notas informadas</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-sort-amount-down text-white text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">3. Classificação</h3>
                        <p class="text-sm text-gray-600">Candidatos são classificados por curso e modalidade</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-trophy text-white text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">4. Resultado</h3>
                        <p class="text-sm text-gray-600">Divulgação dos aprovados e lista de espera</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Selection Criteria -->
        <section class="mb-16">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Critérios de Seleção</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-bar text-ceara-orange mr-3"></i>
                            Sistema de Pontuação
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-600">Média aritmética das notas do 6º ao 9º ano</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-600">Disciplinas obrigatórias têm peso maior</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                                <span class="text-gray-600">9º ano: média dos 3 bimestres + média geral</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-users text-ceara-orange mr-3"></i>
                            Sistema de Cotas
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <i class="fas fa-school text-blue-500 mt-1"></i>
                                <span class="text-gray-600">Prioridade para estudantes de escola pública</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <i class="fas fa-map-marker-alt text-blue-500 mt-1"></i>
                                <span class="text-gray-600">Cotas para moradores do bairro</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <i class="fas fa-wheelchair text-blue-500 mt-1"></i>
                                <span class="text-gray-600">Vagas reservadas para PCD</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics -->
        <section class="mb-16">
            <div class="bg-gradient-to-r from-ceara-green to-green-700 rounded-xl shadow-lg p-8 text-white">
                <h2 class="text-3xl font-bold mb-8 text-center">Nossos Números</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ceara-orange mb-2">15+</div>
                        <p class="text-gray-200">Anos de Experiência</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ceara-orange mb-2">4</div>
                        <p class="text-gray-200">Cursos Técnicos</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ceara-orange mb-2">1000+</div>
                        <p class="text-gray-200">Alunos Formados</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ceara-orange mb-2">95%</div>
                        <p class="text-gray-200">Taxa de Empregabilidade</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact and Location -->
        <section class="mb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-phone text-ceara-orange mr-3"></i>
                        Entre em Contato
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-ceara-orange"></i>
                            <span class="text-gray-600">eeepsantaritama@gmail.com</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone-alt text-ceara-orange"></i>
                            <span class="text-gray-600">(85) 3101-2100</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-ceara-orange"></i>
                            <span class="text-gray-600">Santa Rita, Ceará</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-clock text-ceara-orange mr-3"></i>
                        Horário de Funcionamento
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Segunda a Sexta:</span>
                            <span class="font-medium text-gray-800">7h às 17h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sábado:</span>
                            <span class="font-medium text-gray-800">7h às 12h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Domingo:</span>
                            <span class="font-medium text-gray-800">Fechado</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Button -->
        <div class="text-center">
            <a href="como-se-inscrever.html" class="inline-flex items-center px-8 py-4 bg-ceara-orange hover:bg-orange-600 text-white font-semibold rounded-xl transition-all duration-200 hover:scale-105 shadow-lg">
                <i class="fas fa-arrow-right mr-3"></i>
                Saiba Como se Inscrever
            </a>
        </div>
    </main>

   <!-- Footer Component - Incluir em todas as páginas -->
<footer class="bg-gradient-to-br from-ceara-green via-green-600 to-green-800 text-white w-full mt-16 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-y-1"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-ceara-orange/10 rounded-full blur-3xl"></div>
        <div class="absolute top-0 left-0 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 py-12">
        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <!-- SEEPS Section -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-ceara-orange rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-ceara-orange text-xl font-bold">SEEPS</h4>
                        <p class="text-xs text-gray-200">Sistema Educacional</p>
                    </div>
                </div>
                <p class="text-sm text-gray-100 leading-relaxed">
                    Sistema de Ensino e Educação Profissional Salaberga - Transformando vidas através da educação de qualidade.
                </p>
                
                <!-- Social Media -->
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/groups/salaberga/" target="_blank" 
                       class="group w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="fab fa-facebook text-lg group-hover:text-white"></i>
                    </a>
                    <a href="https://www.instagram.com/eeepsalabergampe/" target="_blank"
                       class="group w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-500 hover:scale-110 transition-all duration-300 shadow-lg">
                        <i class="fab fa-instagram text-lg group-hover:text-white"></i>
                    </a>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-phone text-ceara-orange"></i>
                    </div>
                    <h4 class="text-ceara-orange text-lg font-semibold">CONTATO</h4>
                </div>
                
                <div class="space-y-4">
                    <a href="mailto:eeepsantaritama@gmail.com" 
                       class="group flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                        <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-ceara-orange group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-envelope text-ceara-orange group-hover:text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-300">Email</p>
                            <p class="text-sm font-medium group-hover:text-ceara-orange transition-colors">eeepsantaritama@gmail.com</p>
                        </div>
                    </a>
                    
                    <a href="tel:+558531012100" 
                       class="group flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all duration-300">
                        <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center mr-3 group-hover:bg-ceara-orange group-hover:scale-110 transition-all duration-300">
                            <i class="fas fa-phone-alt text-ceara-orange group-hover:text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-300">Telefone</p>
                            <p class="text-sm font-medium group-hover:text-ceara-orange transition-colors">(85) 3101-2100</p>
                        </div>
                    </a>

                    <div class="flex items-center p-3 bg-white/5 rounded-lg">
                        <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-map-marker-alt text-ceara-orange text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-300">Localização</p>
                            <p class="text-sm font-medium">Santa Rita, CE</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-link text-ceara-orange"></i>
                    </div>
                    <h4 class="text-ceara-orange text-lg font-semibold">LINKS RÁPIDOS</h4>
                </div>
                
                <div class="space-y-2">
                    <a href="inicio.php" class="block text-sm hover:text-ceara-orange transition-colors py-1 hover:translate-x-2 transform duration-200">
                        <i class="fas fa-chevron-right text-xs mr-2 text-ceara-orange"></i>
                        Início
                    </a>
                    <a href="sobre-sistema.html" class="block text-sm hover:text-ceara-orange transition-colors py-1 hover:translate-x-2 transform duration-200">
                        <i class="fas fa-chevron-right text-xs mr-2 text-ceara-orange"></i>
                        Sobre o Sistema
                    </a>
                    <a href="como-se-inscrever.html" class="block text-sm hover:text-ceara-orange transition-colors py-1 hover:translate-x-2 transform duration-200">
                        <i class="fas fa-chevron-right text-xs mr-2 text-ceara-orange"></i>
                        Como se Inscrever
                    </a>
                    <a href="suporte.html" class="block text-sm hover:text-ceara-orange transition-colors py-1 hover:translate-x-2 transform duration-200">
                        <i class="fas fa-chevron-right text-xs mr-2 text-ceara-orange"></i>
                        Suporte
                    </a>
                    <a href="faq.html" class="block text-sm hover:text-ceara-orange transition-colors py-1 hover:translate-x-2 transform duration-200">
                        <i class="fas fa-chevron-right text-xs mr-2 text-ceara-orange"></i>
                        FAQ
                    </a>
                </div>
            </div>

            <!-- Developers Section -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-ceara-orange/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-code text-ceara-orange"></i>
                    </div>
                    <h4 class="text-ceara-orange text-lg font-semibold">DESENVOLVEDORES</h4>
                </div>
                
                <div class="space-y-3">
                    <div class="group">
                        <p class="text-xs text-gray-300 mb-2">Equipe de Desenvolvimento</p>
                        <div class="space-y-2">
                            <a href="https://www.instagram.com/otavio.ce/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-instagram text-ceara-orange mr-2 text-xs"></i>
                                Otavio Menezes
                            </a>
                            <a href="https://www.linkedin.com/in/matheus-felix-74489329a/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-linkedin text-ceara-orange mr-2 text-xs"></i>
                                Matheus Felix
                            </a>
                            <a href="https://www.linkedin.com/in/lavosier-nascimento-4b124a2b8/" target="_blank" 
                               class="flex items-center text-sm hover:text-ceara-orange transition-all duration-200 group-hover:translate-x-1">
                                <i class="fab fa-linkedin text-ceara-orange mr-2 text-xs"></i>
                                Lavosier Nascimento
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="border-t border-white/20 pt-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <p class="text-sm text-gray-200">
                        &copy; 2024 SEEPS - Todos os direitos reservados
                    </p>
                    <div class="hidden md:flex items-center space-x-4 text-xs text-gray-300">
                        <a href="#" class="hover:text-ceara-orange transition-colors">Política de Privacidade</a>
                        <span>•</span>
                        <a href="#" class="hover:text-ceara-orange transition-colors">Termos de Uso</a>
                        <span>•</span>
                        <a href="#" class="hover:text-ceara-orange transition-colors">Cookies</a>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <button onclick="scrollToTop()" 
                            class="w-10 h-10 bg-ceara-orange hover:bg-orange-600 rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-lg">
                        <i class="fas fa-chevron-up text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to top functionality -->
    <script>
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</footer>

</body>
</html>
