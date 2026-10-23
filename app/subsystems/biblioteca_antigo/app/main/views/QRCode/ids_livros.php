<?php
require_once('../../models/select_model.php');
if (isset($_POST['titulo2']) && !empty($_POST['titulo2'])) {
    $titulo2 = $_POST['titulo2'];
    $select = new select_model();
    $result = $select->select_livro_especifico($titulo2);
} else {
    header('location:gerador_especifico_livro.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Livros - Biblioteca STGM</title>
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
            transform: scale(1.02);
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
        body {
            background-image: url('../../assets/img/layout.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
    </style>
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
</head>
<body class="min-h-screen flex items-center justify-center p-6 sm:p-8 md:p-12 select-none">
    <a href="geradorQR_especifico_livro.php" class="fixed top-5 left-5 z-50 group flex items-center space-x-2 bg-white/80 rounded-full px-4 py-2 shadow-lg hover:bg-ceara-green transition-all duration-300">
        <i class="fa-solid fa-arrow-left text-ceara-green group-hover:text-white"></i>
        <span class="text-ceara-green group-hover:text-white font-medium">Voltar</span>
    </a>
    <div class="glass-effect rounded-2xl shadow-2xl p-10 w-full max-w-4xl transform transition-all duration-300 card-hover">
        <div class="text-center mb-10">
            <div class="mb-6">
                <i class="fas fa-book-open text-5xl gradient-text"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3 tracking-tight gradient-text">Selecionar Livros para QR Code</h1>
            <p class="text-gray-600 text-lg">Escolha os livros para gerar os QR Codes</p>
        </div>
        <form action="./QRCode_especifico_livro.php" method="post" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($result as $dados): ?>
                    <?php foreach ($dados as $dado): ?>
                        <?php for ($i = 1; $i <= $dado['quantidade']; $i++): ?>
                            <div class="card-hover bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-center space-x-4">
                                <input 
                                    type="checkbox" 
                                    name="livro[]" 
                                    id="livro_<?= $dado['id'] ?>_<?= $i ?>" 
                                    value="<?= htmlspecialchars($dado['titulo_livro'] . '_' . $dado['id'] . '_' . $dado['edicao'] . '_' . $i . '_' . $dado['estantes'] . '_' . $dado['prateleiras'] . '_' . $dado['cativo']) ?>" 
                                    class="h-5 w-5 text-ceara-green rounded focus:ring-ceara-green"
                                >
                                <div class="flex-1">
                                    <p class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($dado['titulo_livro']) ?></p>
                                    <p class="text-sm text-gray-600">ID: <?= htmlspecialchars($dado['id']) ?></p>
                                    <p class="text-sm text-gray-600">Edição: <?= htmlspecialchars($dado['edicao']) ?></p>
                                    <p class="text-sm text-gray-600">Livro: <?= $i ?></p>
                                    <p class="text-sm text-gray-600">Estante: <?= htmlspecialchars($dado['estantes']) ?>, Prateleira: <?= htmlspecialchars($dado['prateleiras']) ?></p>
                                    <p class="text-sm text-gray-600">Status: <?= htmlspecialchars($dado['cativo']) ?></p>
                                </div>
                            </div>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <button 
                    type="submit" 
                    class="btn-gradient text-white py-3 px-6 rounded-lg font-medium flex items-center justify-center space-x-2 mx-auto"
                >
                    <i class="fas fa-qrcode"></i>
                    <span>Gerar QR Code</span>
                </button>
            </div>
        </form>
    </div>
</body>
</html>