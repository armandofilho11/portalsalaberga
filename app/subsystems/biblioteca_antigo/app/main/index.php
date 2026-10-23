<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/img/icon.png" type="image/x-icon">
    <title>Sistema Biblioteca</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        primary: '#4CAF50',
                        secondary: '#FFB74D',
                    },
                },
            },
        };
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Anton&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        .gradient-text {
            background: linear-gradient(45deg, #008C45, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .footer-link {
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: #FFA500;
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-ceara-white to-gray-100 text-gray-800 min-h-screen select-none">

    <nav class="fixed w-full bg-ceara-white/90 backdrop-blur-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl sm:text-3xl font-bold title-glow" style="font-family: 'Anton', serif;">
                        Biblioteca</h1>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="#features" class="hover:text-ceara-orange transition-colors">
                        <i class="fas fa-tools mr-2"></i>Recursos
                    </a>

                    <a href="#footer" class="hover:text-ceara-orange transition-colors">
                        <i class="fas fa-info-circle mr-2"></i>Sobre
                    </a>
                </div>

                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-800 p-2">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="md:hidden hidden pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="#footer" class="hover:text-ceara-orange transition-colors px-4 py-2">
                        <i class="fas fa-info-circle mr-2"></i>Sobre
                    </a>

                    <a href="#features" class="hover:text-ceara-orange transition-colors px-4 py-2">
                        <i class="fas fa-tools mr-2"></i>Recursos
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-24 md:pt-32 pb-16 md:pb-20 px-4">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="lg:w-1/2 text-center lg:text-left space-y-6">
                <h2 class="text-4xl sm:text-5xl lg:text-7xl font-bold gradient-text"
                    style="font-family: 'Anton', serif;">
                    GERENCIE SUA BIBLIOTECA
                </h2>
                <p class="text-base sm:text-lg text-gray-600 font-light max-w-xl mx-auto lg:mx-0"
                    style="font-family: 'Poppins', sans-serif;">
                    Organize livros, gerencie empréstimos e simplifique a administração da sua biblioteca.
                </p>
                <a href=""
                    class="inline-block bg-ceara-green px-6 sm:px-8 py-3 sm:py-4 rounded-lg text-base sm:text-lg font-semibold hover:bg-opacity-90 transition-all transform hover:scale-105 text-ceara-white">
                    Manual do usuário
                </a>
            </div>
        </div>
    </section>

    <section id="features" class="py-12 md:py-16 bg-gradient-to-b from-white to-gray-50/50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12 md:mb-16">
      <h3 class="text-2xl md:text-3xl lg:text-4xl font-bold text-ceara-green/90 mb-3">Recursos Principais</h3>
   
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
      <!-- Card 1 -->
      <div class="group h-full">
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 h-full flex flex-col border border-gray-100 hover:border-ceara-green/20">
          <div class="h-1.5 bg-gradient-to-r from-ceara-green to-ceara-green/80"></div>
          <a href="./views/decisão.php" class="block p-6 flex-grow">
            <div class="flex flex-col items-center text-center h-full">
              <div class="w-16 h-16 flex items-center justify-center bg-ceara-green/5 rounded-2xl mb-5 group-hover:bg-ceara-green/10 transition-all duration-300">
                <i class="fas fa-book text-ceara-green text-2xl"></i>
              </div>
              <h4 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">Gerenciamento de Cadastros</h4>
              <p class="text-gray-600 text-sm md:text-base mb-4 flex-grow">Cadastre e organize seus livros de forma eficiente e intuitiva.</p>
           
            </div>
          </a>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="group h-full">
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 h-full flex flex-col border border-gray-100 hover:border-ceara-orange/20">
          <div class="h-1.5 bg-gradient-to-r from-ceara-orange to-ceara-orange/70"></div>
          <a href="./views/emprestimo/decisao.php" class="block p-6 flex-grow">
            <div class="flex flex-col items-center text-center h-full">
              <div class="w-16 h-16 flex items-center justify-center bg-ceara-orange/5 rounded-2xl mb-5 group-hover:bg-ceara-orange/10 transition-all duration-300">
                <i class="fas fa-users text-ceara-orange text-2xl"></i>
              </div>
              <h4 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">Gerenciamento de Empréstimos</h4>
              <p class="text-gray-600 text-sm md:text-base mb-4 flex-grow">Controle empréstimos e devoluções com facilidade e precisão.</p>
             
            </div>
          </a>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="group h-full">
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 h-full flex flex-col border border-gray-100 hover:border-ceara-green/20">
          <div class="h-1.5 bg-gradient-to-r from-ceara-green to-ceara-green/80"></div>
          <a href="views/relatorios/decisao.php" class="block p-6 flex-grow">
            <div class="flex flex-col items-center text-center h-full">
              <div class="w-16 h-16 flex items-center justify-center bg-ceara-green/5 rounded-2xl mb-5 group-hover:bg-ceara-green/10 transition-all duration-300">
                <i class="fas fa-chart-line text-ceara-green text-2xl"></i>
              </div>
              <h4 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">Relatórios</h4>
              <p class="text-gray-600 text-sm md:text-base mb-4 flex-grow">Gere relatórios detalhados para análise e tomada de decisões.</p>
           
            </div>
          </a>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="group h-full">
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md overflow-hidden transition-all duration-300 h-full flex flex-col border border-gray-100 hover:border-ceara-orange/20">
          <div class="h-1.5 bg-gradient-to-r from-ceara-orange to-ceara-orange/70"></div>
          <a href="views/QRCode/decisao.php" class="block p-6 flex-grow">
            <div class="flex flex-col items-center text-center h-full">
              <div class="w-16 h-16 flex items-center justify-center bg-ceara-orange/5 rounded-2xl mb-5 group-hover:bg-ceara-orange/10 transition-all duration-300">
                <i class="fas fa-qrcode text-ceara-orange text-2xl"></i>
              </div>
              <h4 class="text-lg md:text-xl font-semibold mb-3 text-gray-800">Gerar QR Code</h4>
              <p class="text-gray-600 text-sm md:text-base mb-4 flex-grow">Crie QR Codes para facilitar o acesso e catalogação dos livros.</p>
             
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


    <footer id="footer" class="bg-ceara-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center md:items-start space-y-4">
                    <h1 class="text-3xl font-bold gradient-text" style="font-family: 'Anton', serif;">
                        Biblioteca
                    </h1>
                    <p class="text-gray-600 text-sm max-w-xs text-center md:text-left leading-relaxed">
                        Simplificando a gestão de bibliotecas com ferramentas eficientes.
                    </p>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-600 text-sm">© 2025 Sistema Biblioteca. Todos os direitos reservados.</p>
                    <div class="flex space-x-4 mt-4 md:mt-0">
                        <a href="#" class="text-gray-600 hover:text-ceara-orange text-sm transition-colors">Termos</a>
                        <span class="text-gray-400">•</span>
                        <a href="#"
                            class="text-gray-600 hover:text-ceara-orange text-sm transition-colors">Privacidade</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>

</html>