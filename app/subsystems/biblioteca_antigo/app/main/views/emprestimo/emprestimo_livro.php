<?php
require_once('../../models/select_model.php');
$select_model = new select_model();

// Inicializa as variáveis
$id_aluno_selecionado = $_POST['aluno'] ?? null;
$turma = null;
$curso = null;

if ($id_aluno_selecionado) {
  $info = $select_model->id_aluno_selecionado($id_aluno_selecionado);
  if ($info) {
    $turma = $info['turma'];
    $curso = $info['curso'];
  }
}

$livros = $select_model->select_livros();
$dados_aluno = $select_model->select_aluno();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro de Empréstimo</title>

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

  <form method="POST" action="../../controllers/emprestimo_controller.php" class="bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl w-full max-w-md space-y-4">
    <h2 class="text-xl font-bold text-center text-ceara-green-dark">Registro de Empréstimo</h2>

      <div class="space-y-4">
        <h3 class="text-lg font-semibold text-ceara-green-dark text-center">Informações do Aluno</h3>


        <div>
          <label for="aluno" class="block text-sm font-medium text-gray-700">Selecione o Aluno:</label>
          <select name="aluno" id="aluno" onchange="this.form.submit()" class="mt-1 w-full p-2 border border-gray-300 rounded">
            <option value="">-- Selecione --</option>
            <?php
            if(isset($_GET['nome'])){
              echo '<option value="'.$_GET['id_aluno'].'" selected>'.$_GET['nome'].'</option>';
            }
            foreach ($dados_aluno as $aluno): ?>
              <option value="<?= $aluno['id_aluno']?>" <?= $id_aluno_selecionado == $aluno['id_aluno'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($aluno['nome']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="turma" class="block text-sm font-medium text-gray-700">Turma:</label>
          <select name="turma" id="turma" disabled class="mt-1 w-full p-2 border border-gray-300 rounded">
            <option value="">
              <?= $turma = !empty($_GET['turma']) ? htmlspecialchars($_GET['turma']) : '-- Selecione --' ?>
            </option>
          </select>
        </div>

        <div>
          <label for="curso" class="block text-sm font-medium text-gray-700">Curso:</label>
          <select name="curso" id="curso" disabled class="mt-1 w-full p-2 border border-gray-300 rounded">
            <option value="">
            <?= $curso = !empty($_GET['curso']) ? htmlspecialchars($_GET['curso']) : '-- Selecione --' ?>
              </option>
          </select>
        </div>

      </div>
    <div class="space-y-4">
      <h3 class="text-lg font-semibold text-ceara-green-dark text-center">Livro e Datas</h3>

      <div>
        <label for="livro" class="block text-sm font-medium text-gray-700">Selecione o Livro:</label>
        <select name="livro" id="livro" class="mt-1 w-full p-2 border border-gray-300 rounded">
          <?php
          foreach ($livros as $livro): ?>
            <option value="<?= $livro['id'] ?>">
              <?= htmlspecialchars($livro['titulo_livro']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="data_emprestimo" class="block text-sm font-medium text-gray-700">Data de Empréstimo:</label>
        <input type="date" name="data_emprestimo" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 w-full p-2 border border-gray-300 rounded">
      </div>

      <div>
        <label for="data_devolucao_estipulada" class="block text-sm font-medium text-gray-700">Data de Devolução:</label>
        <input type="date" name="data_devolucao_estipulada" required class="mt-1 w-full p-2 border border-gray-300 rounded">
      </div>

      <div class="flex justify-between">
        <button type="submit" class="bg-ceara-green text-white font-semibold py-2 px-4 rounded hover:bg-ceara-green-dark transition">
          Registrar Empréstimo
        </button>
      </div>
    </div>
  </form>

  <script>
    $(document).ready(function() {
      $('#aluno').select2({
        placeholder: "-- Selecione --",
        allowClear: true
      });
      $('#livro').select2({
        placeholder: "-- Selecione --",
        allowClear: true
      });
    });
  </script>

</body>

</html>