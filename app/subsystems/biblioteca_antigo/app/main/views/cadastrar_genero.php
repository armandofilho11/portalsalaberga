<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Verifica se algum dos parâmetros está presente
    if (isset($_GET['true']) || isset($_GET['erro']) || isset($_GET['ja_cadastrado'])) {
        // Redireciona após 3 segundos
        echo '<meta http-equiv="refresh" content="3; url=https://salaberga.com/salaberga/portalsalaberga/app/subsystems/biblioteca/app/main/views/decisão.php">';
    }
    ?>
    <title>Cadastro de Gêneros Bibliográficos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
</head>
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
                }
            }
        }
    }
</script>

<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center p-4 sm:p-6 md:p-8 select-none"
    style="background-image: url('../assets/img/layout.png'); background-opacity: 0.3;">

    <a href="decisão.php" class="fixed top-5 left-5 z-50 group flex items-center space-x-2 bg-white/80 rounded-full px-4 py-2 shadow-lg hover:bg-ceara-green transition-all duration-300">
        <i class="fa-solid fa-arrow-left text-ceara-green group-hover:text-white"></i>
        <span class="text-ceara-green group-hover:text-white font-medium">Voltar</span>
    </a>

    <div class="flex flex-col items-center">
        <img src="../assets/img/logo1.png" class="w-[250px] h-auto xs:w-[280px] sm:w-[320px] md:w-[350px] lg:w-[400px] mt-[-150px]" alt="Logo">

        <div class="w-full max-w-xl h-full bg-white rounded-xl shadow-2xl overflow-hidden ">
            <div class="bg-[#007A33] p-4 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-bold text-white text-center">
                    <i class="fas fa-book-open mr-2"></i>Cadastro de Gêneros Bibliográficos
                </h2>
            </div>

            <form id="genreForm" action="../controllers/main_controller.php" method="post" class="p-4 sm:p-6 space-y-4">
                <div class="relative">
                    <div class="relative group">

                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="relative group">
                        <i class="fas fa-book-open absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 group-hover:text-[#007A33] transition-colors duration-200"></i>
                        <input type="text" id="novo_genero" name="novo_genero"
                            class="w-full pl-10 pr-3 py-2.5 border-2 border-gray-200 rounded-lg focus:border-[#007A33] focus:ring focus:ring-[#007A33]/20 focus:outline-none hover:border-gray-300 transition-all duration-200 shadow-sm text-gray-500"
                            placeholder="Digite o novo gênero" required>
                    </div>
                </div>


                <div class="mt-4 sm:mt-6">
                    <button type="submit"
                        class="w-full card-hover bg-[#FFA500] hover:bg-[#FFB74D] text-white font-medium py-2.5 sm:py-3 px-4 rounded-lg transition duration-300 ease-in-out flex items-center justify-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Cadastrar
                    </button>
                </div>

                <div class="mt-2 space-y-2">
                    <?php if (isset($_GET['true'])) { ?>
                        <p class="text-green-600 bg-green-100 border border-green-300 rounded-md p-2 text-center font-medium text-sm sm:text-base"> Gênero cadastrado com sucesso!</p>
                    <?php } ?>
                    <?php if (isset($_GET['false'])) { ?>
                        <p class="text-red-600 bg-red-100 border border-red-300 rounded-md p-2 text-center font-medium text-sm sm:text-base">Erro ao cadastrar!</p>
                    <?php } ?>
                    <?php if (isset($_GET['ja_cadastrado'])) { ?>
                        <p class="text-yellow-600 bg-yellow-100 border border-yellow-300 rounded-md p-2 text-center font-medium text-sm sm:text-base">Gênero já cadastrado!</p>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <style>
        .card-hover {
            transition: transform 0.2s ease-in-out;
        }

        .card-hover:hover {
            transform: translateY(-2px);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('genreForm');
            const subgeneroInput = document.getElementById('subgenero');
            const checkbox = document.getElementById('naoExisteSubgenero');

            checkbox.addEventListener('change', function() {
                subgeneroInput.disabled = this.checked;
                subgeneroInput.required = !this.checked;

                if (this.checked) {
                    subgeneroInput.value = 'Sem Subgênero';
                } else {
                    subgeneroInput.value = '';
                }
            });

        });
    </script>
</body>

</html>