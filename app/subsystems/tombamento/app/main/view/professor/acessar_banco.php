<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gerenciador de Questões</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 40px 20px;
      margin: 0;
      overflow: hidden;
      position: relative;
    }

    .background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #4caf50, #ff9800);
      background-size: 200% 200%;
      animation: moveBackground 10s infinite alternate;
      z-index: -1;
    }

    @keyframes moveBackground {
      0% { background-position: 0% 0%; }
      100% { background-position: 100% 100%; }
    }

    .card {
      width: 100%;
      max-width: 700px;
      background: #fff;
      border-radius: 25px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      position: relative;
      z-index: 1;
    }

    .card-header {
      background: linear-gradient(90deg, #065f46, #f97316);
      padding: 30px;
      text-align: center;
      color: #fff;
      font-size: 1.8rem;
      font-weight: 700;
    }

    .card-body {
      padding: 40px;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 15px 20px;
      font-size: 1rem;
      border-radius: 12px;
      border: 1px solid #ddd;
      background-color: #fafafa;
      transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      outline: none;
      border-color: #f97316;
      box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
    }

    .button-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 30px;
    }

    .button-grid-2 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-top: 20px;
    }

    .btn {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 14px 20px;
      font-size: 1rem;
      font-weight: 600;
      color: white;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-inserir {
      background: linear-gradient(90deg, #f97316, #f59e0b);
    }

    .btn-remover {
      background: #dc3545;
    }

    .btn-ver {
      background: #065f46;
    }

    .btn-zerar {
      background: linear-gradient(90deg, #4f46e5, #6366f1);
    }

    .btn-atualizar {
      background: linear-gradient(90deg, #0ea5e9, #38bdf8);
    }

    .btn:hover {
      opacity: 0.9;
      transform: scale(1.02);
    }
  </style>
</head>
<body>
  <div class="background"></div>
  <div class="card">
    <div class="card-header">
      📘 Gerenciador de Questões
    </div>
    <div class="card-body">
      <div class="form-group">
        <br>
        <h2>Gerencie suas questões:</h2>
        <br>
      </div>
        
    <div class="button-grid">
      <a href="criarquestao.php" class="btn btn-inserir">➕ Inserir</a>
      <a href="removerquestao.php" class="btn btn-remover">🗑️ Remover</a>
      <a href="verquestoes.php" class="btn btn-ver">👁️ Visualizar</a>
    </div>

    <div class="button-grid-2">
      <a href="zerar_status.php" class="btn btn-zerar">🔄 Zerar Status</a>
      <a href="atualizar_questao.php" class="btn btn-atualizar">📝 Atualizar</a>
    </div>

    </div>
  </div>
</body>
</html>
