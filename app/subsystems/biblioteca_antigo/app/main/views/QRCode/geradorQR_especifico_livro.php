<?php
require_once('../../models/select_model.php');
$select = new select_model;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios Biblioteca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        .select2-container--default .select2-selection--multiple {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: #ffffff;
            color: #374151;
            box-shadow: none;
            transition: border-color 0.3s ease;
            cursor: pointer;
            font-size: 0.875rem;
            min-height: 48px;
        }
        .select2-container--default .select2-selection--multiple:focus {
            outline: none;
            border-color: #008C45;
            box-shadow: 0 0 0 3px rgba(0, 140, 69, 0.2);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: linear-gradient(45deg, #008C45, #FFA500);
            color: #ffffff;
            border: none;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            max-width: 85%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin: 0.25rem;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice:hover {
           
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #ffffff;
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 50%;
            width: 16px;
            height: 16px;
            line-height: 16px;
            text-align: center;
            margin-right: 0.5rem;
            transition: background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: rgba(0, 0, 0, 0.4);
            cursor: pointer;
        }
        .select2-container--default .select2-selection--multiple::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #9ca3af;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
        .select2-container--default .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.2s ease-in-out;
        }
        .select2-container--default .select2-results__option {
            padding: 0.75rem 1rem;
            color: #374151;
            background-color: #ffffff;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .select2-container--default .select2-results__option:hover,
        .select2-container--default .select2-results__option--highlighted {
            background: linear-gradient(45deg, #008C45, #FFA500);
            color: #ffffff !important;
        }
        .select2-container--default .select2-results__option[aria-selected="true"],
        .select2-container--default .select2-results__option--selected {
            background-color: #ffffff;
            color: #374151;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .select2-container {
            width: 100% !important;
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
<body class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center p-6 sm:p-8 md:p-12 select-none" style="background-image: url('../../assets/img/layout.png');">
    <a href="decisao.php" class="fixed top-5 left-5 z-50 group flex items-center space-x-2 bg-white/80 rounded-full px-4 py-2 shadow-lg hover:bg-ceara-green transition-all duration-300">
        <i class="fa-solid fa-arrow-left text-ceara-green group-hover:text-white"></i>
        <span class="text-ceara-green group-hover:text-white font-medium">Voltar</span>
    </a>
    <div class="glass-effect rounded-2xl shadow-2xl p-10 w-full max-w-lg transform transition-all duration-300 card-hover">
        <div class="text-center mb-10">
            <div class="mb-6">
                <i class="fas fa-book-reader text-5xl gradient-text"></i>
            </div>
            <h1 class="text-4xl font-bold mb-3 tracking-tight gradient-text">Gerar QRCode</h1>
            <p class="text-gray-600 text-lg">Sistema de Gerenciamento de QRCode</p>
        </div>
        <form action="./ids_livros.php" method="post" class="space-y-6">
            <div class="space-y-4">
                <div class="relative">
                    <select id="titulos" class="js-example-basic-multiple" name="titulo2[]" multiple="multiple" required>
                        <?php
                        $titulos = $select->select_nome_livro_especifico();
                        foreach ($titulos as $titulo) {
                        ?>
                            <option value="<?= htmlspecialchars($titulo['titulo_livro'])?>">
                                <?= htmlspecialchars($titulo['titulo_livro'])?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full btn-gradient text-white py-3 rounded-lg font-medium flex items-center justify-center space-x-2">
                <i class="fas fa-file-alt"></i>
                <span>Gerar</span>
            </button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Selecione os títulos dos livros",
                width: '100%'
            });
        });
    </script>
</body>
</html>