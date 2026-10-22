<?php
require_once('../models/select_model.php');
$select = new select_model();
$livros = $select->select_nome_livro();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Livro - Biblioteca</title>
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

<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 flex items-center justify-center p-4 sm:p-6 md:p-8 lg:p-12 select-none"
  style="background-image: url('../assets/img/layout.png'); background-size: cover; background-attachment: fixed;">

  <?php if (isset($_GET['from']) && $_GET['from'] === 'gerenciar_acervo'): ?>
    <a href="gerenciar_acervo.php" class="fixed top-5 left-12 z-50 group flex items-center space-x-2 bg-white/80 rounded-full px-4 py-2 shadow-lg hover:bg-ceara-green transition-all duration-300">
        <i class="fa-solid fa-arrow-left text-ceara-green group-hover:text-white"></i>
        <span class="text-ceara-green group-hover:text-white font-medium">Voltar</span>
    </a>
  <?php endif; ?>

  <div class="w-full max-w-4xl">
        <!-- <img src="../assets/img/logo1.png"
            class="w-[200px] sm:w-[250px] md:w-[300px] mx-auto mb-8 drop-shadow-xl hover:scale-105 transition-transform duration-300"
            alt="Logo"> -->
        <div class="bg-white rounded-xl shadow-custom overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-lg">
            <div class="bg-gradient-ceara p-6 sm:p-8">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white text-center tracking-wide">
                    <i class="fas fa-search mr-3"></i>Consultar Livro
                </h2>
            </div>

    <div class="bg-white rounded-xl shadow p-6">
      <div class="mb-6">
        <div class="mb-8">
            <div class="flex border-b border-gray-200">
                <button type="button"
                    class="tab-button py-2 px-4 font-medium text-ceara-green border-b-2 border-ceara-green cursor-default active"
                    data-tab="search-book">Buscar Livro</button>
            </div>
        </div>
        <label for="searchBook" class="block text-base font-medium text-gray-700 mb-2">Buscar Livro</label>
        <select id="bookSelect" class="js-example-basic-single w-full" name="id_livro">
          <option value="" disabled selected>Digite o título do livro...</option>
          <?php foreach ($livros as $livro): ?>
            <option value="<?= htmlspecialchars($livro['id'], ENT_QUOTES) ?>"
              data-id="<?= htmlspecialchars($livro['id'], ENT_QUOTES) ?>"
              data-titulo="<?= htmlspecialchars($livro['titulo_livro'] ?? '', ENT_QUOTES) ?>"
              data-autores="<?= htmlspecialchars($livro['autores'] ?? '', ENT_QUOTES) ?>"
              data-edicao="<?= htmlspecialchars($livro['edicao'] ?? '', ENT_QUOTES) ?>"
              data-editora="<?= htmlspecialchars($livro['editora'] ?? '', ENT_QUOTES) ?>"
              data-quantidade="<?= htmlspecialchars($livro['quantidade'] ?? '', ENT_QUOTES) ?>"
              data-ano_publicacao="<?= htmlspecialchars($livro['ano_publicacao'] ?? '', ENT_QUOTES) ?>"
              data-genero="<?= htmlspecialchars($livro['generos'] ?? '', ENT_QUOTES) ?>"
              data-subgenero="<?= htmlspecialchars($livro['subgenero'] ?? '', ENT_QUOTES) ?>"
              data-estante="<?= htmlspecialchars($livro['estantes'] ?? '', ENT_QUOTES) ?>"
              data-prateleira="<?= htmlspecialchars($livro['prateleiras'] ?? '', ENT_QUOTES) ?>"
              data-corredor="<?= htmlspecialchars($livro['corredor'] ?? '', ENT_QUOTES) ?>"
              data-categoria="<?= htmlspecialchars($livro['categoria'] ?? '', ENT_QUOTES) ?>"
              data-ficcao="<?= htmlspecialchars($livro['ficcao'] ?? '', ENT_QUOTES) ?>"
              data-brasileira="<?= htmlspecialchars($livro['brasileira'] ?? '', ENT_QUOTES) ?>"
              data-cativo="<?= htmlspecialchars($livro['cativo'] ?? '', ENT_QUOTES) ?>"
              data-local="Estante <?= htmlspecialchars($livro['estantes'] ?? '', ENT_QUOTES) ?> - Prateleira <?= htmlspecialchars($livro['prateleiras'] ?? '', ENT_QUOTES) ?>"
            ><?= htmlspecialchars($livro['titulo_livro'] . ' — ' . ($livro['autores'] ?? ''), ENT_QUOTES) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div id="bookInfo" class="hidden mt-8 space-y-8">
        <div class="bg-gray-50 rounded-2xl p-8 border border-gray-200 shadow-inner">
          <h3 class="text-xl font-bold text-ceara-green mb-6 flex items-center gap-2"><i class="fas fa-id-card-alt"></i> Informações do Livro</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-base font-medium text-gray-700 mb-2">Título</label>
              <p id="showTitulo" class="text-lg font-semibold text-gray-800 font-medium"></p>
            </div>
            <div>
              <label class="block text-base font-medium text-gray-700 mb-2">Autores</label>
              <p id="showAutores" class="text-lg font-semibold text-gray-800 font-medium"></p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-base font-medium text-gray-700 mb-2">Editora</label>
              <p id="showEditora" class="text-lg font-semibold text-gray-800 font-medium"></p>
            </div>
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-base font-medium text-gray-700 mb-2">Edição</label>
                <p id="showEdicao" class="text-lg font-semibold text-gray-800 font-medium"></p>
              </div>
              <div>
                <label class="block text-base font-medium text-gray-700 mb-2">ID</label>
                <p id="showId" class="text-lg font-semibold text-gray-800 font-medium"></p>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-base font-medium text-gray-700 mb-2">Ano de Publicação</label>
              <p id="showAno" class="text-lg font-semibold text-gray-800 font-medium"></p>
            </div>
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-base font-medium text-gray-700 mb-2">Estante</label>
                <p id="showEstante" class="text-lg font-semibold text-gray-800 font-medium"></p>
              </div>
              <div>
                <label class="block text-base font-medium text-gray-700 mb-2">Prateleira</label>
                <p id="showPrateleira" class="text-lg font-semibold text-gray-800 font-medium"></p>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-base font-medium text-gray-700 mb-2">Quantidade</label>
              <p id="showQuantidade" class="text-lg font-semibold text-gray-800 font-medium"></p>
            </div>
            <div>
              <label class="block text-base font-medium text-gray-700 mb-2">Gênero / Subgênero</label>
              <p class="text-lg font-semibold text-gray-800 font-medium"><span id="showGenero"></span> <span id="showSubgenero" class="text-gray-600"></span></p>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 mb-6">
            <div>
              <label class="block text-base font-medium text-gray-700 mb-2">Corredor</label>
              <p id="showCorredor" class="text-lg font-semibold text-gray-800 font-medium"></p>
            </div>
          </div>

          <div class="flex flex-wrap gap-3 mb-4">
            <span id="badgeCategoria" class="px-3 py-1 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hidden"></span>
            <span id="badgeFiccao" class="px-3 py-1 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hidden"></span>
            <span id="badgeLiteratura" class="px-3 py-1 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hidden"></span>
            <span id="badgeCativo" class="px-3 py-1 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hidden"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('.js-example-basic-single').select2({ placeholder: 'Digite o título do livro...' });

      $('#bookSelect').on('select2:select', function(e) {
        const el = e.params.data.element;
        if (!el) return;
        const dataset = el.dataset;
        $('#showTitulo').text(dataset.titulo || '—');
        $('#showAutores').text(dataset.autores || '—');
        $('#showEdicao').text(dataset.edicao || '—');
        $('#showId').text(dataset.id || '—');
        $('#showAno').text(dataset.ano_publicacao || '—');
        $('#showEditora').text(dataset.editora || '—');
        $('#showQuantidade').text(dataset.quantidade || '—');
        $('#showEstante').text(dataset.estante ? ('Estante ' + dataset.estante) : '—');
        $('#showPrateleira').text(dataset.prateleira ? ('Prateleira ' + dataset.prateleira) : '—');
        $('#showGenero').text(dataset.genero || '—');
        $('#showSubgenero').text(dataset.subgenero ? ('— ' + dataset.subgenero) : '');
        $('#showCorredor').text(dataset.corredor || '—');
        $('#showLocal').text(dataset.local || '—');

        // badges: Categoria, Ficcao, Literatura (Brasileira), Cativo
        const badgeT = $('#badgeCategoria');
        const badgeTLabel = {
          geral: 'Geral',
          didatico: 'Didático',
          paradidatico: 'Paradidático',
          tecnico: 'Técnico'
        };
        const badgeF = $('#badgeFiccao');
        const badgeL = $('#badgeLiteratura');
        const badgeC = $('#badgeCativo');
        if (dataset.categoria === 'Geral') {
          badgeT.text('Geral').removeClass('hidden');
        } else {
          badgeT.text(badgeTLabel[dataset.categoria]).removeClass('bg-gray-300 text-gray-700 hidden').addClass('bg-ceara-green text-white');;
        }

        if (dataset.ficcao === '1') {
          badgeF.text('Ficção').removeClass('bg-gray-300 text-gray-700 hidden').addClass('bg-ceara-green text-white');
        } else if (dataset.ficcao === '0') {
          badgeF.text('Não Ficção').removeClass('bg-ceara-green text-white hidden').addClass('bg-gray-300 text-gray-700');
        } else {
          badgeF.addClass('hidden');
        }

        if (dataset.brasileira === '1') {
          badgeL.text('Brasileira').removeClass('bg-gray-300 text-gray-700 hidden').addClass('bg-ceara-green text-white');
        } else if (dataset.brasileira === '0') {
          badgeL.text('Estrangeira').removeClass('bg-ceara-green text-white hidden').addClass('bg-gray-300 text-gray-700');
        } else {
          badgeL.addClass('hidden');
        }

        if (dataset.cativo === '1') {
          badgeC.text('Cativo').removeClass('bg-gray-300 text-gray-700 hidden').addClass('bg-ceara-green text-white');
        } else if (dataset.cativo === '0') {
          badgeC.text('Não Cativo').removeClass('bg-ceara-green text-white hidden').addClass('bg-gray-300 text-gray-700');
        } else {
          badgeC.addClass('hidden');
        }

        // $('#btnQR').attr('href', './QRCode/geradorQR_especifico_livro.php');
        // $('#btnAbrir').attr('href', './emprestimo/decisao.php?id_livro=' + $(this).val());

        $('#bookInfo').removeClass('hidden');
      });

      // limpando a seleção
      $('#bookSelect').on('select2:clear', function(){ $('#bookInfo').addClass('hidden'); });
    });
  </script>

</body>
</html>