<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Remover Questões</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .form-container {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      border: 1px solid #e5e7eb;
      padding: 1rem;
      border-radius: 0.5rem;
      background-color: #fff;
    }
    .input-container {
      position: relative;
    }
    .hash-symbol {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
      font-weight: 500;
    }
    .number-input {
      width: 100%;
      padding: 0.5rem 0.75rem 0.5rem 2rem;
      border: 1px solid #e5e7eb;
      border-radius: 0.375rem;
      outline: none;
      transition: all 0.2s;
    }
    .number-input:focus {
      border-color: #22c55e;
      box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- Cabeçalho -->
  <header class="bg-green-900 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 py-3">
      <div class="flex justify-between items-center">
        <div class="flex items-center">
          <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" class="w-8 mr-3">
          <h5 class="text-white text-lg font-semibold">Banco de Questões</h5>
        </div>
        <nav class="text-white">
          <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
          <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
          <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
          <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
          <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
        </nav>
      </div>
    </div>
  </header>

  <!-- Conteúdo Principal -->
  <main class="p-6">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-lg border-t-8 border-green-700">
      <h2 class="text-2xl font-bold text-green-700 mb-8">Remover Questões</h2>

      <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
          <span class="block sm:inline">Questão removida com sucesso!</span>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
          <span class="block sm:inline">Erro ao remover a questão. Por favor, tente novamente.</span>
        </div>
      <?php endif; ?>

      <form class="space-y-6" method="POST" action="../../control/controleremoverquestao.php">
        <div class="form-container">
          <div class="space-y-4">
            <div>
              <label class="block font-semibold mb-2">Digite o numero da questão</label>
              <div class="input-container">
                <span class="hash-symbol">#</span>
                <input type="number" name="numero_questao" class="number-input" placeholder="Digite o número">
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </main>
</body>
</html> 