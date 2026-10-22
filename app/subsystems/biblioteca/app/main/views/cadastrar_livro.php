<?php
require_once('../models/select_model.php');
$select_model = new select_model();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if (isset($_GET['true']) || isset($_GET['erro']) || isset($_GET['ja_cadastrado'])) {
        echo '<meta http-equiv="refresh" content="3; url=https://salaberga.com/salaberga/portalsalaberga/app/subsystems/biblioteca/app/main/views/decisão.php">';
    }
    ?>
    <title>Formulário Biblioteca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#007A33',
                        'ceara-green-dark': '#005F27',
                        'ceara-orange': '#FFA500',
                        'ceara-orange-dark': '#FF8C00',
                    },
                    boxShadow: {
                        'custom': '0 10px 25px -5px rgba(0, 122, 51, 0.1), 0 8px 10px -6px rgba(0, 122, 51, 0.1)',
                    },
                    backgroundImage: {
                        'gradient-ceara': 'linear-gradient(to right, #007A33, #005F27)',
                        'gradient-orange': 'linear-gradient(to right, #FFA500, #FF8C00)',
                    }
                }
            }
        }
    </script>
    <style>
        input[type="checkbox"]:checked,
        input[type="radio"]:checked {
            background-color: #007A33 !important;
            border-color: #007A33 !important;
        }
        input[type="checkbox"]:checked:focus,
        input[type="radio"]:checked:focus {
            --tw-ring-color: rgba(0, 122, 51, 0.2);
        }
        input[type="radio"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            outline: none;
            transition: all 0.2s ease-in-out;
        }
        input[type="radio"]:checked {
            border-color: #007A33;
            background-color: #007A33;
            box-shadow: inset 0 0 0 4px #fff;
        }
        input[type="radio"]:focus {
            box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.2);
        }
        .tab-button.active {
            color: #007A33 !important;
            border-bottom: 2px solid #007A33 !important;
        }
        input[type="checkbox"] {
            accent-color: #007A33;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 p-4 sm:p-6 md:p-8 lg:p-12 select-none"
    style="background-image: url('../assets/img/layout.png'); background-size: cover; background-attachment: fixed;">
    <a href="decisão.php"
        class="fixed top-5 left-5 z-50 group flex items-center space-x-2 bg-white/80 rounded-full px-4 py-2 shadow-lg hover:bg-ceara-green transition-all duration-300">
        <i class="fa-solid fa-arrow-left text-ceara-green group-hover:text-white"></i>
        <span class="text-ceara-green group-hover:text-white font-medium">Voltar</span>
    </a>

    <div class="max-w-4xl mx-auto">
        <img src="../assets/img/logo1.png"
            class="w-[200px] sm:w-[250px] md:w-[300px] mx-auto mb-8 drop-shadow-xl hover:scale-105 transition-transform duration-300"
            alt="Logo">
        <div class="bg-white rounded-xl shadow-custom overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-lg">
            <div class="bg-gradient-ceara p-6 sm:p-8">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white text-center tracking-wide">
                    <i class="fas fa-book mr-3"></i>Cadastro de Livros
                </h2>
            </div>

            <form id="bookForm" action="../controllers/main_controller.php" method="post" class="p-6 sm:p-8">
                <div class="mb-8">
                    <div class="flex border-b border-gray-200">
                        <button type="button"
                            class="tab-button py-2 px-4 font-medium text-ceara-green border-b-2 border-ceara-green cursor-default active"
                            data-tab="book-info">Informações do Livro</button>
                        <button type="button" class="tab-button py-2 px-4 font-medium text-gray-500 cursor-not-allowed"
                            data-tab="location" disabled>Localização e Estoque</button>
                    </div>
                </div>

                <div id="validationMessage" class="hidden"></div>

                <div id="book-info" class="tab-content space-y-6">
                    <div class="space-y-6">
                        <!-- Origem, Tipo e Empréstimo -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Literatura</label>
                                <div class="flex flex-col space-y-2">
                                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                                        <input type="radio" name="literatura" value="Brasileira"
                                            class="h-5 w-5 text-ceara-green border-gray-300 focus:ring-ceara-green"
                                            required>
                                        <span class="text-sm">Brasileira</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                                        <input type="radio" name="literatura" value="Estrangeira"
                                            class="h-5 w-5 text-ceara-green border-gray-300 focus:ring-ceara-green">
                                        <span class="text-sm">Estrangeira</span>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <div class="flex flex-col space-y-2">
                                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                                        <input type="radio" name="ficcao" value="1"
                                            class="h-5 w-5 text-ceara-green border-gray-300 focus:ring-ceara-green"
                                            required>
                                        <span class="text-sm">Ficção</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                                        <input type="radio" name="ficcao" value="0"
                                            class="h-5 w-5 text-ceara-green border-gray-300 focus:ring-ceara-green">
                                        <span class="text-sm">Não Ficção</span>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Empréstimo</label>
                                <div class="flex flex-col space-y-2">
                                    <label class="flex items-center space-x-2 text-gray-600 cursor-pointer">
                                        <input type="radio" name="cativo" value="1"
                                            class="h-5 w-5 text-ceara-green border-gray-300 focus:ring-ceara-green">
                                        <span class="text-sm">Cativo</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Subgênero -->
                        <div class="space-y-2">
                            <label for="nomesubGenero" class="block text-sm font-medium text-gray-700">Subgênero</label>
                            <div class="relative">
                                <select id="nomesubGenero" name="nomesubGenero"
                                    class="w-full pl-3 pr-10 py-3 text-base border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none appearance-none bg-white hover:border-gray-300 text-gray-600 transition-all duration-200 cursor-pointer"
                                    required>
                                    <option value="" disabled selected>Selecione o Subgênero</option>
                                    <?php
                                    $generos = $select_model->select_genero();
                                    foreach ($generos as $genero) {
                                        $subgeneros = $select_model->select_subgenero(genero: $genero['generos']);
                                        ?>
                                        <optgroup label="<?= $genero['generos'] ?>"></optgroup>
                                        <?php
                                        foreach ($subgeneros as $subgenero) {
                                            ?>
                                            <option value="<?= $subgenero['subgenero'] ?>"><?= $subgenero['subgenero'] ?></option>
                                        <?php } ?>
                                        </optgroup>
                                    <?php } ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            <button type="button" id="revertSubgenero"
                                class="hidden mt-2 text-ceara-green hover:text-ceara-green-dark text-sm">
                                <i class="fas fa-undo mr-1"></i> Reverter e mostrar Gênero
                            </button>
                        </div>

                        <!-- Título -->
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                            <input type="text" id="titulo" name="titulo"
                                class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200"
                                placeholder="Título do livro" required>
                        </div>

                        <!-- Autores -->
                        <div id="authorFields" class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Autores</label>
                            <div class="author-row space-y-4" data-index="1">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-grow">
                                        <input type="text" id="nome1" name="nome[]"
                                            class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200"
                                            placeholder="Autor" required>
                                    </div>
                                    <div class="flex-grow">
                                        <input type="text" id="sobrenome1" name="sobrenome[]"
                                            class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200"
                                            placeholder="Sobrenome" required>
                                    </div>
                                    <button type="button" id="addAuthor"
                                        class="text-ceara-green p-2 rounded-full transition-colors duration-200">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Editora e Data de Criação -->
                        <div>
                            <div class="flex items-center space-x-4">
                                <div class="flex-grow">
                                    <label for="editora" class="block text-sm font-medium text-gray-700 mb-1">Editora</label>
                                    <input type="text" id="editora" name="editora"
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200"
                                        placeholder="Nome da editora" required>
                                </div>
                                <div class="flex-grow">
                                    <label for="data" class="block text-sm font-medium text-gray-700 mb-1">Data de criação</label>
                                    <input type="text" id="data" name="data"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200"
                                        placeholder="DD/MM/AAAA" required>
                                    <span id="dataError" class="text-red-500 text-xs hidden mt-1">Data inválida</span>
                                </div>
                            </div>
                        </div>

                        <!-- Edição e Quantidade -->
                        <div>
                            <div class="flex items-center space-x-4">
                                <div class="flex-grow">
                                    <label for="edicao" class="block text-sm font-medium text-gray-700 mb-1">Edição</label>
                                    <input type="text" id="edicao" name="edicao"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200"
                                        placeholder="Edição"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="flex-grow">
                                    <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-1">Quantidade</label>
                                    <input type="number" id="quantidade" name="quantidade" min="1"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200"
                                        placeholder="Quantidade" required>
                                </div>
                            </div>
                            <div class="mt-2">
                                <input type="checkbox" id="semEdicao" name="semEdicao"
                                    class="mr-2 h-4 w-4 text-ceara-green focus:ring-ceara-green border-gray-300 rounded accent-ceara-green"
                                    onchange="document.getElementById('edicao').disabled = this.checked; document.getElementById('edicao').value = this.checked ? '' : document.getElementById('edicao').value;">
                                <label for="semEdicao" class="text-sm text-gray-600">Não existe edição</label>
                            </div>
                        </div>

                        <?php if (isset($_GET['true'])): ?>
                            <div class="flex items-center p-4 mb-4 text-green-800 border-l-4 border-green-500 bg-green-50 rounded-md"
                                role="alert">
                                <i class="fas fa-check-circle text-xl mr-3"></i>
                                <span class="text-sm font-medium">Livro cadastrado com sucesso!</span>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['false'])): ?>
                            <div class="flex items-center p-4 mb-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-md"
                                role="alert">
                                <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                                <span class="text-sm font-medium">ERRO ao cadastrar livro!</span>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['ja_cadastrado'])): ?>
                            <div class="flex items-center p-4 mb-4 text-yellow-800 border-l-4 border-yellow-500 bg-yellow-50 rounded-md"
                                role="alert">
                                <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
                                <span class="text-sm font-medium">Livro já cadastrado!</span>
                            </div>
                        <?php endif; ?>

                        <div class="mt-8">
                            <button type="button" id="nextButton"
                                class="w-full bg-gradient-ceara text-white font-medium py-4 px-6 rounded-lg transition duration-300 ease-in-out hover:shadow-lg flex items-center justify-center text-lg shadow-md">
                                <i class="fas fa-arrow-right mr-3"></i>
                                Avançar
                            </button>
                        </div>
                    </div>
                </div>

                <div id="location" class="tab-content space-y-6 hidden">
                    <div>
                        <label for="corredor" class="block text-sm font-medium text-gray-700 mb-1">Corredor</label>
                        <div class="relative">
                            <select id="corredor" name="corredor"
                                class="w-full pl-3 pr-10 py-3 text-base border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none appearance-none bg-white hover:border-gray-300 text-gray-600 transition-all duration-200 cursor-pointer"
                                required>
                                <option value="" disabled selected>Selecione o Corredor</option>
                                <option value="A">Corredor A</option>
                                <option value="B">Corredor B</option>
                                <option value="C">Corredor C</option>
                                <option value="D">Corredor D</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="estante" class="block text-sm font-medium text-gray-700 mb-1">Estante</label>
                        <div class="relative">
                            <select id="estante" name="livro_estante"
                                class="w-full pl-3 pr-10 py-3 text-base border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none appearance-none bg-white hover:border-gray-300 text-gray-600 transition-all duration-200 cursor-pointer"
                                required>
                                <option value="" disabled selected>Selecione a Estante</option>
                                <?php for ($i = 1; $i <= 40; $i++) { ?>
                                    <option value="<?= $i ?>">Estante <?= $i ?></option>
                                <?php } ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="prateleira" class="block text-sm font-medium text-gray-700 mb-1">Prateleira</label>
                        <div class="relative">
                            <select id="prateleira" name="livro_prateleira"
                                class="w-full pl-3 pr-10 py-3 text-base border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none appearance-none bg-white hover:border-gray-300 text-gray-600 transition-all duration-200 cursor-pointer"
                                required>
                                <option value="" disabled selected>Selecione a Prateleira</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="P<?= $i ?>">Prateleira <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-4">
                        <button type="button" id="backButton"
                            class="w-full bg-gradient-ceara text-white font-medium py-4 px-6 rounded-lg transition duration-300 ease-in-out hover:shadow-lg flex items-center justify-center text-lg shadow-md">
                            <i class="fas fa-arrow-left mr-3"></i>
                            Voltar
                        </button>
                        <button type="submit"
                            class="w-full bg-gradient-orange text-white font-medium py-4 px-6 rounded-lg transition duration-300 ease-in-out hover:shadow-lg flex items-center justify-center text-lg shadow-md">
                            <i class="fas fa-paper-plane mr-3"></i>
                            Cadastrar Livro
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Autores
            const authorFields = document.getElementById('authorFields');
            const addAuthorBtn = document.getElementById('addAuthor');
            let authorCount = 1;

            function addAuthor() {
                authorCount++;
                const newAuthorRow = document.createElement('div');
                newAuthorRow.className = 'author-row space-y-4';
                newAuthorRow.setAttribute('data-index', authorCount);
                newAuthorRow.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <div class="flex-grow">
                            <input type="text" id="nome${authorCount}" name="nome[]" class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200" placeholder="Autor" required>
                        </div>
                        <div class="flex-grow">
                            <input type="text" id="sobrenome${authorCount}" name="sobrenome[]" class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-600 placeholder-gray-400 transition-all duration-200" placeholder="Sobrenome" required>
                        </div>
                        <button type="button" class="remove-author text-red-500 p-2 rounded-full transition-colors duration-200">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                `;
                authorFields.appendChild(newAuthorRow);

                newAuthorRow.querySelector('.remove-author').addEventListener('click', function (e) {
                    e.preventDefault();
                    newAuthorRow.remove();
                    authorCount--;
                    updateAuthorIndices();
                });
            }

            function updateAuthorIndices() {
                const rows = authorFields.querySelectorAll('.author-row');
                rows.forEach((row, index) => {
                    const newIndex = index + 1;
                    row.setAttribute('data-index', newIndex);
                    const nomeInput = row.querySelector('input[name="nome[]"]');
                    const sobrenomeInput = row.querySelector('input[name="sobrenome[]"]');
                    nomeInput.id = `nome${newIndex}`;
                    sobrenomeInput.id = `sobrenome${newIndex}`;
                });
            }

            addAuthorBtn.addEventListener('click', function (e) {
                e.preventDefault();
                addAuthor();
            });

            // Tabs functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            const nextButton = document.getElementById('nextButton');
            const backButton = document.getElementById('backButton');
            const validationMessage = document.getElementById('validationMessage');

            function switchTab(tabName) {
                tabButtons.forEach(btn => {
                    btn.classList.remove('active', 'text-ceara-green', 'border-b-2', 'border-ceara-green');
                    if (btn.getAttribute('data-tab') === tabName) {
                        btn.classList.add('active', 'text-ceara-green', 'border-b-2', 'border-ceara-green');
                    } else {
                        btn.classList.add('text-gray-500');
                    }
                });

                tabContents.forEach(content => {
                    content.classList.add('hidden');
                    if (content.id === tabName) {
                        content.classList.remove('hidden');
                    }
                });

                tabButtons.forEach(btn => {
                    if (btn.getAttribute('data-tab') === 'location' && tabName === 'location') {
                        btn.removeAttribute('disabled');
                        btn.classList.add('hover:text-ceara-green');
                        btn.classList.remove('cursor-not-allowed', 'text-gray-500');
                    } else if (btn.getAttribute('data-tab') === 'location' && !btn.classList.contains('active')) {
                        btn.setAttribute('disabled', 'true');
                        btn.classList.remove('hover:text-ceara-green');
                        btn.classList.add('cursor-not-allowed', 'text-gray-500');
                    }
                });
            }

            nextButton.addEventListener('click', function () {
                const requiredFields = document.querySelectorAll('#book-info [required]');
                const literatura = document.querySelector('input[name="literatura"]:checked');
                const ficcao = document.querySelector('input[name="ficcao"]:checked');
                const subgenero = document.getElementById('nomesubGenero');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });

                if (!literatura) {
                    isValid = false;
                    document.querySelectorAll('input[name="literatura"]').forEach(radio =>
                        radio.closest('label').classList.add('border-red-500')
                    );
                }
                if (!ficcao) {
                    isValid = false;
                    document.querySelectorAll('input[name="ficcao"]').forEach(radio =>
                        radio.closest('label').classList.add('border-red-500')
                    );
                }

                const authorNames = document.querySelectorAll('input[name="nome[]"]');
                const authorSurnames = document.querySelectorAll('input[name="sobrenome[]"]');
                authorNames.forEach(name => {
                    if (!name.value.trim()) {
                        isValid = false;
                        name.classList.add('border-red-500');
                    }
                });
                authorSurnames.forEach(surname => {
                    if (!surname.value.trim()) {
                        isValid = false;
                        surname.classList.add('border-red-500');
                    }
                });

                if (isValid) {
                    switchTab('location');
                    validationMessage.classList.add('hidden');
                } else {
                    validationMessage.innerHTML = `
                        <div class="flex items-center p-4 mb-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-md" role="alert">
                            <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                            <span class="text-sm font-medium">Por favor, preencha todos os campos obrigatórios!</span>
                        </div>
                    `;
                    validationMessage.classList.remove('hidden');
                }
            });

            backButton.addEventListener('click', function () {
                switchTab('book-info');
                validationMessage.classList.add('hidden');
            });

            tabButtons.forEach(button => {
                if (button.getAttribute('data-tab') === 'location') {
                    button.addEventListener('click', (e) => {
                        if (button.hasAttribute('disabled')) {
                            e.preventDefault();
                        } else {
                            switchTab('location');
                        }
                    });
                } else if (button.getAttribute('data-tab') === 'book-info') {
                    button.addEventListener('click', () => switchTab('book-info'));
                }
            });

            // Validação de Data
            const dataInput = document.getElementById('data');
            const dataError = document.getElementById('dataError');

            dataInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.slice(0, 8);

                if (value.length >= 5) {
                    value = value.replace(/(\d{2})(\d{2})(\d{0,4})/, '$1/$2/$3');
                } else if (value.length >= 3) {
                    value = value.replace(/(\d{2})(\d{0,2})/, '$1/$2');
                }
                e.target.value = value;

                if (value.length === 10) {
                    const [day, month, year] = value.split('/').map(Number);
                    const date = new Date(year, month - 1, day);
                    const isValid = date.getFullYear() === year &&
                        date.getMonth() === month - 1 &&
                        date.getDate() === day &&
                        date <= new Date();

                    if (!isValid) {
                        dataError.classList.remove('hidden');
                    } else {
                        dataError.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</body>
</html>