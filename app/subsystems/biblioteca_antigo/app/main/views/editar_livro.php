<?php
require_once('../models/select_model.php');
$select_model = new select_model();
$livros = $select_model->select_nome_livro();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário Biblioteca</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: white;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            min-height: 48px;
            padding: 4px;
            transition: all 0.2s ease-in-out;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #007A33;
            box-shadow: 0 0 0 2px rgba(0, 122, 51, 0.2);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #4b5563;
            line-height: 40px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007A33;
            color: white;
        }

        .select2-dropdown {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 2px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 8px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #007A33;
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 122, 51, 0.2);
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e6f3ed;
            color: #007A33;
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
                    <i class="fas fa-edit mr-3"></i>Editar Livro
                </h2>
            </div>

            <form id="editBookForm" action="../controllers/excluir_editar_livro.php" method="post" class="p-6 sm:p-8">
                <div class="mb-8">
                    <div class="flex border-b border-gray-200">
                        <button type="button"
                            class="tab-button py-2 px-4 font-medium text-ceara-green border-b-2 border-ceara-green cursor-default active"
                            data-tab="search-book">Buscar Livro</button>
                    </div>
                </div>

                <div id="validationMessage" class="hidden"></div>

                <div id="search-book" class="tab-content space-y-6">
                    <div class="space-y-6">
                        <div class="relative">
                            <label for="searchBook" class="block text-base font-medium text-gray-700 mb-2">Buscar Livro</label>
                            <div class="relative">
                                <select class="js-example-basic-single border-2 border-gray-200 rounded-lg px-5 py-3 w-full focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none text-lg bg-white text-gray-700 transition-all duration-200" name="id_livro" required>
                                    <option value="" selected disabled>Selecione um livro para editar</option>
                                    <?php
                                    foreach ($livros as $livro) {
                                        $prateleira = strtoupper($livro['prateleiras']);
                                        $estante = $livro['estantes'];
                                        $displayEstante = $estante ?: 'N/A';
                                    ?>
                                        <option value="<?= htmlspecialchars($livro['id']) ?>"
                                            data-titulo="<?= htmlspecialchars($livro['titulo_livro']) ?>"
                                            data-edicao="<?= htmlspecialchars($livro['edicao']) ?>"
                                            data-editora="<?= htmlspecialchars($livro['editora']) ?>"
                                            data-estante="<?= htmlspecialchars($estante) ?>"
                                            data-prateleira="<?= htmlspecialchars($prateleira) ?>"
                                            data-quantidade="<?= htmlspecialchars($livro['quantidade']) ?>"
                                            data-genero="<?= htmlspecialchars($livro['generos']) ?>"
                                            data-subgenero="<?= htmlspecialchars($livro['subgenero']) ?>"
                                            data-ano_publicacao="<?= htmlspecialchars($livro['ano_publicacao']) ?>"
                                            data-corredor="<?= htmlspecialchars($livro['corredor']) ?>"
                                            data-ficcao="<?= htmlspecialchars($livro['ficcao']) ?>"
                                            data-brasileira="<?= htmlspecialchars($livro['brasileira']) ?>"
                                            data-cativo="<?= htmlspecialchars($livro['cativo']) ?>">
                                            <?= htmlspecialchars($livro['titulo_livro']) ?> | edição: <?= htmlspecialchars($livro['edicao']) ?> | editora: <?= htmlspecialchars($livro['editora']) ?> | estante/armário: <?= htmlspecialchars($displayEstante) ?> | prateleira: <?= htmlspecialchars($prateleira) ?> | quantidade: <?= htmlspecialchars($livro['quantidade']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div id="editForm" class="hidden mt-8 space-y-8">
                            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-200 shadow-inner">
                                <h3 class="text-xl font-bold text-ceara-green mb-6 flex items-center gap-2"><i class="fas fa-id-card-alt"></i> Informações do Livro</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="editTitulo" class="block text-base font-medium text-gray-700 mb-2">Título</label>
                                        <input type="text" id="editTitulo" name="titulo_livro" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 placeholder-gray-400 transition-all duration-200 text-lg" required>
                                    </div>
                                    <div>
                                        <label for="editEdicao" class="block text-base font-medium text-gray-700 mb-2">Edição</label>
                                        <input type="text" id="editEdicao" name="edicao" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 placeholder-gray-400 transition-all duration-200 text-lg" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="editEditora" class="block text-base font-medium text-gray-700 mb-2">Editora</label>
                                        <input type="text" id="editEditora" name="editora" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 placeholder-gray-400 transition-all duration-200 text-lg" required>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label for="editEstante" class="block text-base font-medium text-gray-700 mb-2">Estante/Armário</label>
                                            <select id="editEstante" name="estante" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 transition-all duration-200 text-lg" required>
                                                <option value="">Selecione uma estante/armário</option>
                                                <?php for ($i = 1; $i <= 40; $i++) { ?>
                                                    <option value="<?= $i ?>">Estante <?= $i ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="editPrateleira" class="block text-base font-medium text-gray-700 mb-2">Prateleira</label>
                                            <select id="editPrateleira" name="prateleira" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 transition-all duration-200 text-lg" required>
                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                    <option value="P<?= $i ?>">Prateleira <?= $i ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="editQuantidade" class="block text-base font-medium text-gray-700 mb-2">Quantidade</label>
                                        <input type="number" id="editQuantidade" name="quantidade" min="1" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 placeholder-gray-400 transition-all duration-200 text-lg" required>
                                    </div>
                                    <div>
                                        <label for="editAnoPublicacao" class="block text-base font-medium text-gray-700 mb-2">Ano de Publicação</label>
                                        <input type="text" id="editAnoPublicacao" name="ano_publicacao" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 placeholder-gray-400 transition-all duration-200 text-lg" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="editGenero" class="block text-base font-medium text-gray-700 mb-2">Gênero</label>
                                        <select name="genero" id="editGenero" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 transition-all duration-200 text-lg" required>
                                            <option value="">Selecione um gênero</option>
                                            <?php
                                            $generos = $select_model->select_genero();
                                            foreach ($generos as $genero) { ?>
                                                <option value="<?= htmlspecialchars($genero['id']) ?>"><?= htmlspecialchars($genero['generos']) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="editSubgenero" class="block text-base font-medium text-gray-700 mb-2">Subgênero</label>
                                        <select name="subgenero" id="editSubgenero" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 transition-all duration-200 text-lg" required>
                                            <option value="">Selecione um subgênero</option>
                                            <?php
                                            $generos = $select_model->select_genero();
                                            foreach ($generos as $genero) {
                                                $subgeneros = $select_model->select_subgenero(genero: $genero['generos']);
                                            ?>
                                                <optgroup label="<?= htmlspecialchars($genero['generos']) ?>">
                                                <?php foreach ($subgeneros as $subgenero) { ?>
                                                    <option value="<?= htmlspecialchars($subgenero['id']) ?>"><?= htmlspecialchars($subgenero['subgenero']) ?></option>
                                                <?php } ?>
                                                </optgroup>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="editCorredor" class="block text-base font-medium text-gray-700 mb-2">Corredor</label>
                                        <input type="text" id="editCorredor" name="corredor" class="w-full px-5 py-3 bg-white border-2 border-gray-200 rounded-lg focus:border-ceara-green focus:ring-2 focus:ring-ceara-green/20 focus:outline-none hover:border-gray-300 text-gray-700 placeholder-gray-400 transition-all duration-200 text-lg" required>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="flex items-center">
                                            <input type="radio" id="editFiccaoTrue" name="ficcao" value="1" class="w-5 h-5 text-ceara-green border-gray-300 rounded focus:ring-ceara-green" required>
                                            <label for="editFiccaoTrue" class="ml-2 text-base font-medium text-gray-700">Ficção</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="editFiccaoFalse" name="ficcao" value="0" class="w-5 h-5 text-ceara-green border-gray-300 rounded focus:ring-ceara-green" required>
                                            <label for="editFiccaoFalse" class="ml-2 text-base font-medium text-gray-700">Não Ficção</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="editLiteraturaTrue" name="literatura" value="1" class="w-5 h-5 text-ceara-green border-gray-300 rounded focus:ring-ceara-green" required>
                                            <label for="editLiteraturaTrue" class="ml-2 text-base font-medium text-gray-700">Brasileira</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="editLiteraturaFalse" name="literatura" value="0" class="w-5 h-5 text-ceara-green border-gray-300 rounded focus:ring-ceara-green" required>
                                            <label for="editLiteraturaFalse" class="ml-2 text-base font-medium text-gray-700">Estrangeira</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="editCativoTrue" name="cativo" value="1" class="w-5 h-5 text-ceara-green border-gray-300 rounded focus:ring-ceara-green" required>
                                            <label for="editCativoTrue" class="ml-2 text-base font-medium text-gray-700">Cativo</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="editCativoFalse" name="cativo" value="0" class="w-5 h-5 text-ceara-green border-gray-300 rounded focus:ring-ceara-green" required>
                                            <label for="editCativoFalse" class="ml-2 text-base font-medium text-gray-700">Não Cativo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col md:flex-row gap-4 mt-6">
                                    <button type="submit" name="action" value="edit"
                                        class="flex-1 bg-ceara-green hover:bg-ceara-green-dark text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out hover:shadow-lg flex items-center justify-center text-lg shadow-md">
                                        <i class="fas fa-save mr-3"></i>
                                        Salvar Alterações
                                    </button>
                                    <button type="button" id="cancelEdit"
                                        class="flex-1 bg-gray-400 hover:bg-gray-600 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out hover:shadow-lg flex items-center justify-center text-lg shadow-md">
                                        <i class="fas fa-times mr-3"></i>
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_GET['true'])): ?>
                        <div class="flex items-center p-4 mb-4 text-green-800 border-l-4 border-green-500 bg-green-50 rounded-md"
                            role="alert">
                            <i class="fas fa-check-circle text-xl mr-3"></i>
                            <span class="text-sm font-medium">Livro editado com sucesso!</span>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="flex items-center p-4 mb-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-md"
                            role="alert">
                            <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                            <span class="text-sm font-medium">ERRO ao editar livro!</span>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const validationMessage = document.getElementById('validationMessage');
            const editForm = document.getElementById('editForm');
            const cancelEditBtn = document.getElementById('cancelEdit');

            $('.js-example-basic-single').select2({
                placeholder: "Digite o título do livro...",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Nenhum livro encontrado";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            }).on('select2:select', function(e) {
                const selectedOption = e.params.data.element;
                if (selectedOption) {
                    document.getElementById('editTitulo').value = selectedOption.dataset.titulo || '';
                    document.getElementById('editEdicao').value = selectedOption.dataset.edicao || '';
                    document.getElementById('editEditora').value = selectedOption.dataset.editora || '';
                    const estanteValue = selectedOption.dataset.estante;
                    const validEstante = estanteValue && (
                        (!isNaN(estanteValue) && estanteValue >= 1 && estanteValue <= 33) ||
                        /^armario-[1-5]$/.test(estanteValue)
                    ) ? estanteValue : '';
                    document.getElementById('editEstante').value = validEstante;
                    document.getElementById('editPrateleira').value = selectedOption.dataset.prateleira || '';
                    document.getElementById('editQuantidade').value = selectedOption.dataset.quantidade || '';
                    document.getElementById('editAnoPublicacao').value = selectedOption.dataset.ano_publicacao || '';
                    document.getElementById('editCorredor').value = selectedOption.dataset.corredor || '';

                    const generoSelect = document.getElementById('editGenero');
                    const generoOptions = Array.from(generoSelect.options);
                    const generoOption = generoOptions.find(option => option.text === selectedOption.dataset.genero);
                    if (generoOption) {
                        generoSelect.value = generoOption.value;
                    } else {
                        generoSelect.value = '';
                    }
                    const subgeneroSelect = document.getElementById('editSubgenero');
                    const subgeneroOptions = Array.from(subgeneroSelect.options);
                    const subgeneroOption = subgeneroOptions.find(option => option.text === selectedOption.dataset.subgenero);
                    if (subgeneroOption) {
                        subgeneroSelect.value = subgeneroOption.value;
                    } else {
                        subgeneroSelect.value = '';
                    }

                    document.getElementById('editFiccaoTrue').checked = selectedOption.dataset.ficcao === '1';
                    document.getElementById('editFiccaoFalse').checked = selectedOption.dataset.ficcao === '0';
                    document.getElementById('editLiteraturaTrue').checked = selectedOption.dataset.brasileira === '1';
                    document.getElementById('editLiteraturaFalse').checked = selectedOption.dataset.brasileira === '0';
                    document.getElementById('editCativoTrue').checked = selectedOption.dataset.cativo === '1';
                    document.getElementById('editCativoFalse').checked = selectedOption.dataset.cativo === '0';

                    editForm.classList.remove('hidden');
                }
            });

            cancelEditBtn.addEventListener('click', function() {
                editForm.classList.add('hidden');
                $('.js-example-basic-single').val('').trigger('change');
            });

            document.getElementById('editBookForm').addEventListener('submit', function(e) {
                const selectedBook = $('.js-example-basic-single').val();
                let isValid = true;

                if (!selectedBook) {
                    isValid = false;
                    validationMessage.innerHTML = `
                        <div class="flex items-center p-4 mb-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-md" role="alert">
                            <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                            <span class="text-base font-semibold">Por favor, selecione um livro para editar!</span>
                        </div>
                    `;
                    validationMessage.classList.remove('hidden');
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>