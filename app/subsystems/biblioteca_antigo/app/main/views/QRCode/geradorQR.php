<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios Biblioteca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <style>
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .gradient-text {
            background: linear-gradient(45deg, #008C45, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-gradient {
            background: linear-gradient(45deg, #008C45, #FFA500);
            transition: all 0.4s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(45deg, #006633, #FF8C00);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 140, 69, 0.3);
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

<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center p-6 sm:p-8 md:p-12 select-none"
    style="background-image: url('../../assets/img/layout.png');">

    <a href="decisao.php" class="fixed top-5 left-5 z-50 group flex items-center space-x-2 bg-white/80 rounded-full px-4 py-2 shadow-lg hover:bg-ceara-green transition-all duration-300">
        <i class="fa-solid fa-arrow-left text-ceara-green group-hover:text-white"></i>
        <span class="text-ceara-green group-hover:text-white font-medium">Voltar</span>
    </a>

    <div class="glass-effect rounded-2xl shadow-2xl p-10 w-full max-w-lg transform transition-all duration-300 card-hover">
        <div class="text-center mb-10">
            <div class="mb-6">
                <i class="fas fa-book-reader text-5xl gradient-text"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3 tracking-tight gradient-text">
                Gerar QRCode
            </h1>
            <p class="text-gray-600 text-lg">
                Sistema de Gerenciamento de QRCode
            </p>
        </div>

        <form action="../../controllers/qr_controller.php" method="post" class="space-y-6">
            <div class="space-y-4">
                <!-- Select para Estante com ícone -->
                <div class="relative">
                    <select id="relatorioSelect" name="estante"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ceara-green focus:border-ceara-green appearance-none bg-white text-gray-700 cursor-pointer shadow-sm transition-all duration-300 text-base" required>
                        <option value="" disabled selected>Selecione a Estante</option>
                        <?php for ($i = 1; $i <= 40; $i++) { ?>
                            <option value="<?= $i ?>">Estante <?= $i ?></option>
                        <?php } ?>
                    </select>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>

                <!-- Select para Prateleira com ícone -->
                <div class="relative">
                    <select id="relatorioSelect" name="prateleira"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ceara-green focus:border-ceara-green appearance-none bg-white text-gray-700 cursor-pointer shadow-sm transition-all duration-300 text-base" required>
                        <option value="" disabled selected>Selecione a prateleira</option>
                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <option value="<?= $i ?>">Prateleira <?= $i ?></option>
                        <?php } ?>
                    </select>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full btn-gradient text-white py-3 rounded-lg font-medium flex items-center justify-center space-x-2">
                <i class="fas fa-file-alt"></i>
                <span>Gerar</span>
            </button>
        </form>
    </div>
</body>

</html>