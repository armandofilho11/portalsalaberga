<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Banco de Questões</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    .header-bg {
      background-color: #006400;
      color: white;
    }
    .feature-box {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 15px;
      background-color: white;
      transition: 0.3s;
    }
    .feature-box:hover {
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    .btn-gradient-green {
      background: linear-gradient(45deg, #2e8b57, #32cd32);
      color: white;
      border: none;
    }
    .btn-gradient-orange {
      background: linear-gradient(45deg, #ffa500, #ff8c00);
      color: white;
      border: none;
    }
    .btn-gradient-blue {
      background: linear-gradient(45deg, #2e8b57, #32cd32);
      color: white;
      border: none;
    }
    .card-placeholder {
      border: 2px dashed #ccc;
      padding: 30px;
      text-align: center;
      color: #666;
      background-color: #f9f9f9;
      border-radius: 10px;
    }
  </style>
</head>
<body class="bg-light">

  <header class="header-bg py-3 px-4 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
      <h5 class="mb-0">Banco de Questões</h5>
    </div>
    <nav class="text-white">
      <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
      <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
      <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
      <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
      <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
    </nav>
  </header>

  <div class="container mt-4">
    <div class="bg-success text-white p-4 rounded">
      <h4>Seja Bem-Vindo, Professor!</h4>
      <p>Boas-vindas ao Banco de Questões STGM. Crie, organize e compartilhe com facilidade!</p>
    </div>

    <!-- Ações principais -->
  <div class="row my-4 text-center g-3">
    <div class="col-md-4">
      <div class="feature-box">
        <a href="criarprova.php">
          <button class="btn btn-gradient-green w-100">📝 Criar Avaliações</button>
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-box">
        <a href="acessar_banco.php">
          <button class="btn btn-gradient-orange w-100">📚 Banco de Questões</button>
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-box">
        <a href="corrigir_prova.php">
          <button class="btn btn-gradient-green w-100">✅ Corrigir Provas</button>
        </a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="feature-box">
        <a href="acessarrelatorioprofessor.php">
          <button class="btn btn-gradient-green w-100">📊 Relatórios</button>
        </a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="feature-box">
        <a href="gerenciar_subtopicos.php">
          <button class="btn btn-gradient-green w-100">🔖 Gerenciar Subtópicos</button>
        </a>
      </div>
    </div>
  </div>  

      <!-- Filtros -->
  <div class="bg-white p-4 rounded shadow-sm">
    <h5 class="mb-4">Banco de Questões</h5>
    <form action="../../control/controleacessarbanco.php" method="POST">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Enunciado</label>
          <input type="text" class="form-control" name="enunciado" placeholder="Pesquise por enunciado..." />
        </div>
        <div class="col-md-2">
          <label class="form-label">Disciplina</label>
          <select class="form-select" name="disciplina">
            <option value="">Selecione</option>
            <option value='lab._software'>Laboratório de Software</option>
            <option value='lab._hardware'>Laboratório de Hardware</option>
            <option value='Start_up_1'>StartUp 1</option>
            <option value='Start_up_2'>StartUp 2</option>
            <option value='Start_up_3'>StartUp 3</option>
            <option value='banco_de_dados'>Banco de Dados</option>
            <option value='logica'>Lógica de Programação</option>
            <option value='gerenciador_de_conteudo'>Gerenciador de Conteúdo</option>
            <option value='Informatica_basica'>Informática Básica</option>
            <option value='Robotica'>Robótica</option>
            <option value='programacao_web'>Programação Web</option>
            <option value='Sistemas_operacionais'>Sistemas Operacionais</option>
            <option value='redes_de_computadores'>Redes de Computadores</option>
            <option value='htmlcss'>HTML/CSS</option>
            <option value='design'>Design</option>
            <option value='AMC'>Arquitetura e Manutenção de Computadores</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Subtópico</label>
          <select class="form-select" name="subtopico" id="subtopico">
            <option value="">Selecione uma disciplina primeiro</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Dificuldade</label>
          <select class="form-select" name="dificuldade">
            <option value="">Selecione</option>
            <option value="facil">Fácil</option>
            <option value="medio">Médio</option>
            <option value="dificil">Difícil</option>
          </select>
        </div>

        <!-- Comentado temporariamente: Classificação -->
        <!--
        <div class="col-md-2">
          <label class="form-label">Classificação</label>
          <select class="form-select" name="classificacao">
            <option value="">Selecione</option>
            <option value="parcial">Parcial</option>
            <option value="somativa">Somativa</option>
            <option value="bimestral">Bimestral</option>
          </select>
        </div>
        -->

        <div class="col-md-1 d-flex align-items-end">
          <button type="submit" class="btn btn-gradient-green w-100">Filtrar</button>
        </div>
      </div>
    </form>

    <!-- Placeholder -->
    <div class="text-center mt-5 card-placeholder">
      <img src="https://img.icons8.com/ios/50/000000/nothing-found.png" alt="placeholder" />
      <p class="mt-2">Utilize os filtros para buscar questões</p>
    </div>

    <!-- Botão de criar questão -->
    <div class="text-end mt-4">
      <a href="criarquestao.php">
        <button class="btn btn-gradient-orange">+ Criar Questões</button>
      </a>
    </div>
  </div>

  <footer class="text-center mt-4 text-muted" style="font-size: 0.9rem;">
    Desenvolvido por Yudi Bezerra, Nicole Kelly e Leticia Barbosa
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const disciplinaSelect = document.querySelector('select[name="disciplina"]');
      const subtopicosSelect = document.getElementById('subtopico');

      disciplinaSelect.addEventListener('change', function() {
        const disciplina = this.value;
        subtopicosSelect.innerHTML = '<option value="">Carregando...</option>';
        subtopicosSelect.disabled = !disciplina;
        
        if (disciplina) {
          fetch('../../control/controlesubtopico.php?disciplina=' + encodeURIComponent(disciplina))
            .then(response => response.json())
            .then(data => {
              subtopicosSelect.innerHTML = '<option value="">Selecione um subtópico</option>';
              data.forEach(subtopico => {
                const option = document.createElement('option');
                option.value = subtopico.id;
                option.textContent = subtopico.nome;
                subtopicosSelect.appendChild(option);
              });
            })
            .catch(error => {
              console.error('Error:', error);
              subtopicosSelect.innerHTML = '<option value="">Erro ao carregar subtópicos</option>';
            });
        } else {
          subtopicosSelect.innerHTML = '<option value="">Selecione uma disciplina primeiro</option>';
        }
      });
    });
  </script>
</body>
</html>
