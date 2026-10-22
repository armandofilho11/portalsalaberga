<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário Biblioteca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <style>
        .card-hover {
            transition: all 0.2s ease-in-out;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .gradient-text {
            background: linear-gradient(45deg, #008C45, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
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
    style="background-image: url('../../assets/img/layout.png'); background-opacity: 0.3;">

    <a href="../../index.php" class="fixed top-5 left-5 z-50 group flex items-center space-x-2 bg-white/80 rounded-full px-4 py-2 shadow-lg hover:bg-ceara-green transition-all duration-300">
        <i class="fa-solid fa-arrow-left text-ceara-green group-hover:text-white"></i>
        <span class="text-ceara-green group-hover:text-white font-medium">Voltar</span>
    </a>

    <div class="bg-white bg-opacity-90 rounded-xl shadow-2xl p-8 w-full max-w-md transform transition-all duration-300">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-black-500 mb-3 tracking-tight gradient-text">Sistema Biblioteca</h1>
            <p class="text-gray-600 text-lg">Escolha uma opção para continuar</p>
        </div>

        <div class="space-y-5">
        <a href="./relatorio_acervo.php"
                class="card-hover block w-full bg-gray-50 border border-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-lg transition-all duration-300 flex items-center justify-between hover:bg-gray-100">
                <span class="flex items-center">
                    <i class="fas fa-tags mr-4 text-xl text-ceara-green"></i>
                    <span class="text-lg">Relatório geral</span>
                </span>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <a href="./relatorios.php"
                class="card-hover block w-full bg-gray-50 border border-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-lg transition-all duration-300 flex items-center justify-between hover:bg-gray-100">
                <span class="flex items-center">
                    <i class="fas fa-tags mr-4 text-xl text-ceara-green"></i>
                    <span class="text-lg">Relatório Partial</span>
                </span>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <a href="./geral_turma.php"
                class="card-hover block w-full bg-gray-50 border border-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-lg transition-all duration-300 flex items-center justify-between hover:bg-gray-100">
                <span class="flex items-center">
                    <i class="fas fa-tags mr-4 text-xl text-ceara-green"></i>
                    <span class="text-lg">Turma geral</span>
                </span>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <a href="./relatorio_livros.php"
                class="card-hover block w-full bg-gray-50 border border-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-lg transition-all duration-300 flex items-center justify-between hover:bg-gray-100">
                <span class="flex items-center">
                    <i class="fas fa-tags mr-4 text-xl text-ceara-green"></i>
                    <span class="text-lg">Relatório de livros</span>
                </span>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
        </div>
        <div class="mt-10 pt-6 border-t border-gray-200 text-center">
            <p class="text-gray-500 text-sm font-medium">Sistema Biblioteca © 2025</p>
        </div>
    </div>

</body>

</html>,