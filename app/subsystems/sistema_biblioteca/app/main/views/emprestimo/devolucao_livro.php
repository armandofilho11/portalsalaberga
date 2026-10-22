<?php
require_once('../../models/select_model.php');
$select_model = new select_model();

$livros_emprestados = $select_model->select_emprestimo();

$id_emprestimo_selecionado = null;
if (isset($_POST['livro_emprestimo']) && $_POST['livro_emprestimo'] !== '') {
  $id_emprestimo_selecionado = (int)$_POST['livro_emprestimo'];
} elseif (isset($_GET['id_emprestimo']) && $_GET['id_emprestimo'] !== '') {
  $id_emprestimo_selecionado = (int)$_GET['id_emprestimo'];
}

if ($id_emprestimo_selecionado) {
  $info_selecionada = $select_model->dados_aluno($id_emprestimo_selecionado);
} else {
  $info_selecionada = null;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro de Devolução</title>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="shortcut icon" href="../../assets/img/icon.png" type="image/x-icon">
  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'ceara-green': '#007A33',
            'ceara-green-dark': '#005F27',
            'ceara-orange': '#FFA500',
            'ceara-orange-dark': '#FF8C00'
          }
        }
      }
    }
  </script>

  <style>
    body {
      background-image: url('../../assets/img/layout.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

  <form id="devolucaoForm" method="POST" action="../../controllers/devolucao_controller.php" class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl w-full max-w-md space-y-4">
    <h2 class="text-xl font-bold text-center text-ceara-green-dark">Registro de Devolução</h2>

    <div class="space-y-4">
      <h3 class="text-lg font-semibold text-ceara-green-dark text-center">Informações do Empréstimo</h3>

      <div>
        <label for="livro_emprestimo" class="block text-sm font-medium text-gray-700">Selecione o Livro Emprestado:</label>
        <div>
   
          <select name="livro_emprestimo" id="livro_emprestimo" class="mt-1 w-full p-2 border border-gray-300 rounded">
            <option value="">-- Selecione --</option>
            <?php
            foreach ($livros_emprestados as $livro):
              $info = $select_model->dados_aluno($livro['id']);
              if (!$info && !empty($livro['id_aluno'])) {
                $info = $select_model->id_aluno_selecionado($livro['id_aluno']);
              }
              $aluno_nome = $info['nome'] ?? '';
              $aluno_turma = $info['turma'] ?? '';
              $aluno_curso = $info['curso'] ?? '';
            ?>
              <option value="<?= (int)$livro['id'] ?>" <?= (string)$id_emprestimo_selecionado === (string)$livro['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars((string)$livro['titulo_livro']) ?> | Aluno: <?= htmlspecialchars((string)$aluno_nome) ?> | Turma: <?= htmlspecialchars((string)$aluno_turma) ?> | Curso: <?= htmlspecialchars((string)$aluno_curso) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
        <label for="data_devolucao" class="block text-sm font-medium text-gray-700">Data de Devolução:</label>
        <input type="date" name="data_devolucao" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 w-full p-2 border border-gray-300 rounded">
      </div>
        </div>
        <div id="devolucaoMessage"></div>
        <div class="flex justify-between">
        <button type="submit" class="bg-ceara-green text-white font-semibold py-2 px-4 rounded hover:bg-ceara-green-dark transition">
          Registrar Devolução
        </button>
  </form>

  <script>
    $(document).ready(function() {
      $('#livro_emprestimo').select2({
        placeholder: "-- Selecione o Livro --",
        allowClear: true
      });

      $('#devolucaoForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
          type: form.attr('method'),
          url: form.attr('action'),
          data: form.serialize(),
          success: function(response) {
            var data = response;
            if (typeof response === 'string') {
              try {
                data = JSON.parse(response);
              } catch (err) {
                data = {};
              }
            }

            if (data.success) {
              $('#devolucaoMessage').html('<div class="flex items-center p-4 mb-4 text-green-800 border-l-4 border-green-500 bg-green-50 rounded-md" role="alert"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-6 h-6 mr-3 flex-shrink-0" fill="currentColor" aria-hidden="true"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.9 176.6L233.4 329.1c-4.7 4.7-12.3 4.7-17 0l-82.3-82.3c-4.7-4.7-4.7-12.3 0-17l28.3-28.3c4.7-4.7 12.3-4.7 17 0l45.5 45.5 107.6-107.6c4.7-4.7 12.3-4.7 17 0l28.3 28.3c4.8 4.7 4.8 12.3.1 16.9z"/></svg><span class="text-sm font-medium">Devolução registrada com sucesso!</span></div>');
              form[0].reset();
              $('#livro_emprestimo').val(null).trigger('change');
            } else {
              var message = 'Erro ao registrar devolução.';
              if (data.error) {
                if (data.error === 'registro') {
                  message = 'Falha ao salvar a devolução. Tente novamente.';
                } else if (data.error === 'dados_incompletos') {
                  message = 'Dados incompletos. Preencha o formulário corretamente.';
                }
              }
              $('#devolucaoMessage').html('<div class="flex items-center p-4 mb-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-md" role="alert"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-6 h-6 mr-3 flex-shrink-0" fill="none" aria-hidden="true"><circle cx="50" cy="50" r="42" stroke="currentColor" stroke-width="4"/><line x1="50" y1="28" x2="50" y2="58" stroke="currentColor" stroke-width="6"/><rect x="47" y="64" width="6" height="6" fill="currentColor"/></svg><span class="text-sm font-medium">' + message + '</span></div>');
            }
          },
          error: function() {
            $('#devolucaoMessage').html('<div class="flex items-center p-4 mb-4 text-red-800 border-l-4 border-red-500 bg-red-50 rounded-md" role="alert"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="w-6 h-6 mr-3 flex-shrink-0" fill="none" aria-hidden="true"><circle cx="50" cy="50" r="42" stroke="currentColor" stroke-width="4"/><line x1="50" y1="28" x2="50" y2="58" stroke="currentColor" stroke-width="6"/><rect x="47" y="64" width="6" height="6" fill="currentColor"/></svg><span class="text-sm font-medium">Erro de conexão. Tente novamente.</span></div>');
          }
        });
      });
    });
  </script>

</body>

</html>
