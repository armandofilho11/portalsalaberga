<?php
require_once('../../models/select_model.php');
$select_model = new select_model();

// Corrigir busca dos livros emprestados
$livros_emprestados = $select_model->select_emprestimo();
$emprestimos = $select_model->select_emprestimo();

$id_emprestimo_selecionado = $_POST['livro'] ?? null;
$turma = null;
$curso = null;
$nome = null;
if ($id_emprestimo_selecionado) {
  $info = $select_model->dados_aluno($id_emprestimo_selecionado);
  if ($info) {
    $aluno = $info['nome'];
    $turma = $info['turma'];
    $curso = $info['curso'];
  }
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

  <form method="POST" action="../../controllers/devolucao_controller.php" class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl w-full max-w-md space-y-4">
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
              // Buscar as informações do aluno para cada empréstimo
              $info = $select_model->dados_aluno($livro['id']);
              $aluno_nome = $info['nome'] ?? '';
              $aluno_turma = $info['turma'] ?? '';
              $aluno_curso = $info['curso'] ?? '';
            ?>
              <option value="<?= $livro['id']?>" <?= $id_emprestimo_selecionado == $livro['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($livro['titulo_livro']) ?> | Aluno: <?= htmlspecialchars($aluno_nome) ?> | Turma: <?= htmlspecialchars($aluno_turma) ?> | Curso: <?= htmlspecialchars($aluno_curso) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
        <label for="data_devolucao" class="block text-sm font-medium text-gray-700">Data de Devolução:</label>
        <input type="date" name="data_devolucao" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 w-full p-2 border border-gray-300 rounded">
      </div>
        </div>
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
    });
  </script>

</body>

</html>
