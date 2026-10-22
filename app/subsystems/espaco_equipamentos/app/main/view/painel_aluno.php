<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['aluno_id']) || !isset($_SESSION['aluno_nome'])) {
    header('Location: index.php');
    exit;
}

// Proteção CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Obter o nome e a turma do aluno da sessão
$aluno_nome = $_SESSION['aluno_nome'];
$aluno_turma_id = $_SESSION['aluno_turma_id'] ?? null;
$aluno_turma_nome = $_SESSION['aluno_turma_nome'] ?? 'Turma não definida';

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sis_aee";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
$conn->set_charset('utf8');

// Se não tiver turma na sessão, tentar buscar do banco
if (!$aluno_turma_id) {
    $sql = "SELECT t.id, t.nome 
            FROM alunos a 
            JOIN turmas t ON a.turma_id = t.id 
            WHERE a.id = ?";
    $stmt = $conn->prepare($sql);
    $aluno_id = $_SESSION['aluno_id'];
    $stmt->bind_param("i", $aluno_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $aluno_turma_id = $row['id'];
        // Normalizar o nome da turma
        $aluno_turma_nome = str_replace(['??', '??'], ['º', 'ª'], $row['nome']);
        $_SESSION['aluno_turma_id'] = $aluno_turma_id;
        $_SESSION['aluno_turma_nome'] = $aluno_turma_nome;
    }
}

// Buscar todas as turmas
$turmas = [];
$sql = "SELECT id, nome FROM turmas";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $turmas[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Aluno | EEEP Salaberga</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" href="https://salaberga.com/salaberga/portalsalaberga/app/main/assets/img/Design%20sem%20nome.svg" type="image/x-icon">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
    :root {
      --primary-color: #007A33;
      --secondary-color: #FFA500;
      --text-color: #333333;
      --bg-color: #F0F2F5;
      --input-bg: #FFFFFF;
      --shadow-color: rgba(0, 0, 0, 0.1);
      --error-color: #dc3545;
      --success-color: #28a745;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; display: flex; justify-content: center; align-items: center; color: var(--text-color); }
    .main-container { display: flex; width: 100%; max-width: 1200px; height: 650px; background-color: var(--bg-color); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px var(--shadow-color); }
    .sidebar { width: 250px; background: var(--primary-color); color: #FFFFFF; padding: 2rem 1.5rem; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h3 { font-size: 1.5rem; font-weight: 600; margin-bottom: 2rem; text-align: center; }
    .sidebar ul { list-style: none; flex-grow: 1; }
    .sidebar ul li { margin-bottom: 1rem; }
    .sidebar ul li a.menu-item { display: flex; align-items: center; gap: 0.75rem; padding: 10px 15px; color: #FFFFFF; text-decoration: none; font-size: 1rem; border-radius: 10px; transition: all 0.3s ease; }
    .sidebar ul li a.menu-item:hover { background-color: rgba(255, 255, 255, 0.1); }
    .sidebar ul li a.menu-item.active { background-color: var(--secondary-color); color: #FFFFFF; }
    .sidebar ul li a.menu-item i { font-size: 1.2rem; }
    .sidebar .logout { display: flex; align-items: center; gap: 0.75rem; padding: 10px 15px; color: #FFFFFF; text-decoration: none; font-size: 1rem; border-radius: 10px; transition: all 0.3s ease; text-align: center; background-color: rgba(255, 255, 255, 0.1); }
    .sidebar .logout:hover { background-color: var(--secondary-color); }
    .content { flex: 1; background-color: var(--input-bg); padding: 2.5rem; overflow-y: auto; }
    .section { display: none; }
    .section.active { display: block; }
    .section h2 { color: var(--primary-color); font-size: 2rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center; }
    .form-group { position: relative; margin-bottom: 1.5rem; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 20px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; background-color: var(--input-bg); color: var(--text-color); resize: vertical; }
    .form-group textarea { min-height: 100px; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(0, 122, 51, 0.1); }
    .form-group label { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #999; font-size: 1rem; transition: all 0.3s ease; pointer-events: none; padding: 0 5px; background-color: var(--input-bg); }
    .form-group input:focus + label, .form-group input:not(:placeholder-shown) + label, .form-group select:focus + label, .form-group select:not(:placeholder-shown) + label, .form-group textarea:focus + label, .form-group textarea:not(:placeholder-shown) + label { top: 0; font-size: 0.8rem; color: var(--primary-color); }
    button[type="submit"] { width: 100%; padding: 12px; background-color: var(--secondary-color); color: #FFFFFF; border: none; border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 6px var(--shadow-color); }
    button[type="submit"]:hover:not(:disabled) { background-color: #e69500; transform: translateY(-2px); box-shadow: 0 6px 12px var(--shadow-color); }
    button[type="submit"]:disabled { background-color: #cccccc; cursor: not-allowed; transform: none; box-shadow: none; }
    .confirmation-message { display: none; background-color: var(--success-color); color: #FFFFFF; border-radius: 10px; padding: 1rem; text-align: center; font-size: 1rem; font-weight: 500; margin-top: 1.5rem; box-shadow: 0 4px 8px var(--shadow-color); }
    .confirmation-message.active { display: block; }
    .error-message { display: none; background-color: var(--error-color); color: #FFFFFF; border-radius: 10px; padding: 1rem; text-align: center; font-size: 0.9rem; font-weight: 500; margin-top: 1rem; box-shadow: 0 4px 8px var(--shadow-color); }
    .error-message.active { display: block; }
    .items-table-container, .agendamentos-table-container { overflow-x: auto; margin-top: 1rem; }
    .items-table, .agendamentos-table { width: 100%; border-collapse: collapse; background-color: var(--input-bg); border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px var(--shadow-color); }
    .items-table th, .items-table td, .agendamentos-table th, .agendamentos-table td { padding: 12px 15px; text-align: left; font-size: 0.9rem; }
    .items-table th, .agendamentos-table th { background-color: var(--primary-color); color: #FFFFFF; font-weight: 600; }
    .items-table td, .agendamentos-table td { border-bottom: 1px solid #e0e0e0; }
    .items-table tr:last-child td, .agendamentos-table tr:last-child td { border-bottom: none; }
    .action-btn { padding: 8px 12px; border: none; border-radius: 8px; font-size: 0.8rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease; margin-right: 5px; display: inline-flex; align-items: center; gap: 5px; }
    .action-btn:disabled { background-color: #cccccc; cursor: not-allowed; }
    .agendar-btn { background-color: var(--secondary-color); color: #FFFFFF; }
    .agendar-btn:hover:not(:disabled) { background-color: #e69500; }
    .cancel-btn { background-color: var(--error-color); color: #FFFFFF; }
    .cancel-btn:hover:not(:disabled) { background-color: #c82333; }
    .no-items-message { text-align: center; font-size: 1rem; color: var(--text-color); margin-top: 1.5rem; }
    .status-pendente { color: #6c757d; font-weight: 500; }
    .status-aprovado { color: var(--success-color); font-weight: 600; }
    .status-rejeitado { color: var(--error-color); font-weight: 600; }
    .hidden { display: none; }
    .cards-list { display: none; } /* Esconde cards por padrão */
    @media (max-width: 768px) {
      body {
        align-items: flex-start;
        padding: 0;
        min-height: 100vh;
        background: var(--bg-color);
        overflow-x: hidden;
      }
      .main-container { 
        flex-direction: column; 
        height: auto; 
        max-width: 100%; 
        margin: 0;
        border-radius: 0;
        box-shadow: none;
        min-height: 100vh;
        overflow: hidden;
      }
      .sidebar { 
        display: none !important; 
      }
      .content { 
        padding: 1rem;
        margin-bottom: 70px;
        width: 100%;
        height: calc(100vh - 70px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
      }
      .section h2 { 
        font-size: 1.3rem;
        margin-bottom: 1rem;
        padding: 0 0.5rem;
      }
      .form-group input, 
      .form-group select, 
      .form-group textarea { 
        padding: 10px;
        font-size: 0.9rem;
        width: 100%;
      }
      .items-table-container, .agendamentos-table-container {
        margin: 0 -0.5rem;
        padding: 0 0.5rem;
        border-radius: 0;
        box-shadow: none;
        overflow-x: auto;
      }
      .items-table, .agendamentos-table {
        min-width: 480px;
        width: 100%;
        font-size: 0.85rem;
      }
      .items-table th, .items-table td, .agendamentos-table th, .agendamentos-table td { 
        padding: 8px;
        font-size: 0.8rem;
        white-space: nowrap;
      }
      .items-table th, .agendamentos-table th {
        font-size: 0.85rem;
      }
      .action-btn { 
        padding: 8px 10px;
        font-size: 0.75rem;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.3rem;
      }
      .confirmation-message,
      .error-message {
        font-size: 0.9rem;
        padding: 0.8rem;
        margin: 0.5rem 0;
        width: 100%;
        text-align: center;
      }
      .mobile-logout {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        z-index: 2000;
        background: var(--primary-color);
        color: #fff;
        align-items: center;
        justify-content: space-between;
        padding: 0.4rem 0.8rem;
        box-shadow: 0 2px 8px var(--shadow-color);
        height: 48px;
      }
      .mobile-logout a {
        color: #fff;
        font-size: 1rem;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.1);
        padding: 10px 15px;
        border-radius: 10px;
        transition: background 0.2s, color 0.2s;
      }
      .mobile-logout a:hover {
        background: var(--secondary-color);
        color: #fff;
      }
      .main-container { margin-top: 60px; }
      .mobile-menu-btn {
        display: block;
        position: static;
        z-index: auto;
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 1.3rem;
        cursor: pointer;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .mobile-menu-btn i {
        pointer-events: none;
      }
      .mobile-navbar {
        display: flex;
        position: fixed;
        top: 0;
        left: -100vw;
        width: 80vw;
        max-width: 320px;
        height: 100vh;
        background: var(--primary-color);
        box-shadow: 2px 0 12px rgba(0,0,0,0.12);
        flex-direction: column;
        padding-top: 60px;
        transition: left 0.3s cubic-bezier(.4,1.3,.5,1);
        z-index: 2000;
      }
      .mobile-navbar.open {
        left: 0;
      }
      .mobile-navbar a {
        color: #fff;
        text-decoration: none;
        padding: 18px 24px;
        font-size: 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        gap: 0.7rem;
        transition: background 0.2s;
      }
      .mobile-navbar a:hover, .mobile-navbar a.active {
        background: var(--secondary-color);
        color: #fff;
      }
    }
    @media (min-width: 769px) {
      .mobile-menu-btn, .mobile-navbar {
        display: none !important;
      }
    }
    @media (max-width: 480px) {
      .content {
        padding: 0.5rem;
        margin-bottom: 60px;
      }
      .section h2 {
        font-size: 1.1rem;
        margin-bottom: 0.7rem;
      }
      .items-table th, .items-table td, .agendamentos-table th, .agendamentos-table td {
        padding: 5px;
        font-size: 0.7rem;
      }
      .items-table, .agendamentos-table {
        min-width: 350px;
      }
      .action-btn {
        padding: 8px 8px;
        font-size: 0.7rem;
        min-width: 60px;
      }
      .form-group label {
        font-size: 0.8rem;
      }
      .form-group input:focus + label,
      .form-group input:not(:placeholder-shown) + label,
      .form-group select:focus + label,
      .form-group select:not(:placeholder-shown) + label,
      .form-group textarea:focus + label,
      .form-group textarea:not(:placeholder-shown) + label {
        font-size: 0.65rem;
      }
      .sidebar ul li a.menu-item {
        font-size: 0.65rem;
        padding: 0.45rem 0.15rem;
      }
      .sidebar ul li a.menu-item i {
        font-size: 0.95rem;
      }
      .confirmation-message,
      .error-message {
        font-size: 0.75rem;
        padding: 0.5rem;
      }
      button[type="submit"] {
        padding: 6px;
        font-size: 0.75rem;
      }
    }
    /* NOVO: Cards responsivos para mobile */
    @media (max-width: 900px) {
      .items-table-container, .agendamentos-table-container {
        padding: 0;
        margin: 0;
        box-shadow: none;
        background: none;
      }
      .items-table, .agendamentos-table {
        display: none;
      }
      .cards-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1rem;
      }
      .item-card, .agendamento-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px var(--shadow-color);
        padding: 1rem 1.2rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.98rem;
      }
      .item-card .card-title, .agendamento-card .card-title {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.1rem;
        margin-bottom: 0.2rem;
      }
      .item-card .card-desc, .agendamento-card .card-desc {
        color: #555;
        font-size: 0.95rem;
      }
      .item-card .card-qty {
        color: var(--secondary-color);
        font-size: 0.93rem;
        font-weight: 500;
      }
      .agendamento-card .card-status {
        font-size: 0.93rem;
        font-weight: 500;
        margin-bottom: 0.2rem;
      }
      .agendamento-card .card-status.status-pendente { color: #6c757d; }
      .agendamento-card .card-status.status-aprovado { color: var(--success-color); }
      .agendamento-card .card-status.status-rejeitado { color: var(--error-color); }
      .card-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.3rem;
      }
      .card-actions .action-btn {
        flex: 1;
        justify-content: center;
      }
      .mobile-logout {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        z-index: 2000;
        background: var(--primary-color);
        color: #fff;
        align-items: center;
        justify-content: flex-end;
        padding: 0.4rem 1.2rem 0.4rem 0.7rem;
        box-shadow: 0 2px 8px var(--shadow-color);
        height: 48px;
      }
      .mobile-logout a {
        color: #fff;
        font-size: 1rem;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.1);
        padding: 10px 15px;
        border-radius: 10px;
        transition: background 0.2s, color 0.2s;
      }
      .mobile-logout a:hover {
        background: var(--secondary-color);
        color: #fff;
      }
      .main-container { margin-top: 60px; }
    }
    @media (min-width: 901px) {
      .mobile-logout { display: none !important; }
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 90%;
      max-width: 500px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
    }

    .modal-header h2 {
      margin: 0;
      color: #333;
    }

    .close-modal {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close-modal:hover {
      color: #333;
    }

    .modal-body {
      padding: 20px 0;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #eee;
    }

    .btn-cancel {
      padding: 8px 16px;
      background-color: #f44336;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn-submit {
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn-cancel:hover {
      background-color: #d32f2f;
    }

    .btn-submit:hover {
      background-color: #45a049;
    }

    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #45a049;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      display: none;
      z-index: 1000;
    }
    .toast.show {
      display: block;
    }

    .mobile-logout {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        z-index: 2000;
        background: var(--primary-color);
        color: #fff;
        align-items: center;
        justify-content: space-between;
        padding: 0.4rem 0.8rem;
        box-shadow: 0 2px 8px var(--shadow-color);
        height: 48px;
    }

    .mobile-menu-btn {
        display: block;
        position: static;
        z-index: auto;
    }

    .mobile-navbar {
        display: flex;
        position: fixed;
        top: 0;
        left: -100vw;
        width: 80vw;
        max-width: 320px;
        height: 100vh;
        background: var(--primary-color);
        box-shadow: 2px 0 12px rgba(0,0,0,0.12);
        flex-direction: column;
        padding-top: 60px;
        transition: left 0.3s cubic-bezier(.4,1.3,.5,1);
        z-index: 2000;
    }

    .mobile-navbar.open {
        left: 0;
    }

    .close-mobile-menu {
        position: absolute;
        top: 10px;
        left: 10px;
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 10px;
        z-index: 2001;
    }

    .close-mobile-menu:hover {
        opacity: 0.8;
    }

    .mobile-navbar a {
        color: #fff;
        text-decoration: none;
        padding: 18px 24px;
        font-size: 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        gap: 0.7rem;
        transition: background 0.2s;
    }

    .mobile-navbar a:hover, .mobile-navbar a.active {
        background: var(--secondary-color);
        color: #fff;
    }

    @media (max-width: 768px) {
        .mobile-menu-btn {
            display: block;
            position: static;
            z-index: auto;
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 1.3rem;
            cursor: pointer;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-menu-btn i {
            pointer-events: none;
        }

        .mobile-navbar {
            display: flex;
            position: fixed;
            top: 0;
            left: -100vw;
            width: 80vw;
            max-width: 320px;
            height: 100vh;
            background: var(--primary-color);
            box-shadow: 2px 0 12px rgba(0,0,0,0.12);
            flex-direction: column;
            padding-top: 60px;
            transition: left 0.3s cubic-bezier(.4,1.3,.5,1);
            z-index: 2000;
        }

        .mobile-navbar.open {
            left: 0;
        }

        .mobile-navbar a {
            color: #fff;
            text-decoration: none;
            padding: 18px 24px;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: background 0.2s;
        }

        .mobile-navbar a:hover, .mobile-navbar a.active {
            background: var(--secondary-color);
            color: #fff;
        }
    }

    @media (min-width: 769px) {
        .mobile-menu-btn, .mobile-navbar {
            display: none !important;
        }
    }
  </style>
</head>
<body>
  <div class="mobile-logout">
    <!-- Botão de menu hambúrguer para mobile -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
      <i class="fas fa-bars"></i>
    </button>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
  </div>
  <div class="main-container">
    <div class="sidebar">
      <h3>Bem-vindo(a), <?php echo htmlspecialchars($aluno_nome); ?></h3>
      <ul>
        <li><a href="#" class="menu-item active" data-section="agendar-equipamentos"><i class="fas fa-laptop"></i> Agendar Equipamentos</a></li>
        <li><a href="#" class="menu-item" data-section="agendar-espacos"><i class="fas fa-building"></i> Agendar Espaços</a></li>
        <li><a href="#" class="menu-item" data-section="meus-agendamentos"><i class="fas fa-calendar-check"></i> Meus Agendamentos</a></li>
      </ul>
      <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
    <div class="content">
      <div id="agendar-equipamentos-section" class="section active">
        <h2>Agendar Equipamentos</h2>
        <div class="items-table-container">
          <table class="items-table">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody id="equipamentos-tbody">
              <!-- Equipamentos serão inseridos aqui -->
            </tbody>
          </table>
          <div class="cards-list" id="equipamentos-cards"></div>
        </div>
        <div class="no-items-message hidden" id="equipamentos-no-items">Nenhum equipamento disponível no momento. Tente novamente mais tarde.</div>
        <form id="agendar-equipamento-form" class="hidden">
          <input type="hidden" id="agendar-equipamento-id" name="id_item">
          <input type="hidden" id="agendar-equipamento-tipo" name="tipo" value="Equipamento">
          <input type="hidden" name="turma_id" value="<?php echo htmlspecialchars($aluno_turma_id); ?>">
          <div class="form-group">
            <input type="text" id="agendar-equipamento-nome" name="nome" readonly>
            <label for="agendar-equipamento-nome">Equipamento</label>
          </div>
          <div class="form-group">
            <select id="agendar-equipamento-turma" disabled>
              <option value="<?php echo htmlspecialchars($aluno_turma_id); ?>"><?php echo htmlspecialchars($aluno_turma_nome); ?></option>
            </select>
            <label for="agendar-equipamento-turma">Turma</label>
          </div>
          <div class="form-group">
            <input type="datetime-local" id="agendar-equipamento-data" name="data_hora" required>
            <label for="agendar-equipamento-data">Data e Hora</label>
          </div>
          <input type="hidden" name="btn" value="agendar_item">
          <button type="submit">Agendar</button>
        </form>
        <div class="confirmation-message hidden" id="agendar-equipamento-confirmation">Equipamento agendado com sucesso!</div>
        <div class="error-message hidden" id="agendar-equipamento-error"></div>
      </div>
      <div id="agendar-espacos-section" class="section hidden">
        <h2>Agendar Espaços</h2>
        <div class="items-table-container">
          <table class="items-table">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Ação</th>
              </tr>
            </thead>
            <tbody id="espacos-tbody">
              <!-- Espaços serão inseridos aqui -->
            </tbody>
          </table>
          <div class="cards-list" id="espacos-cards"></div>
        </div>
        <div class="no-items-message hidden" id="espacos-no-items">Nenhum espaço disponível no momento. Tente novamente mais tarde.</div>
        <form id="agendar-espaco-form" class="hidden">
          <input type="hidden" id="agendar-espaco-id" name="id_item">
          <input type="hidden" id="agendar-espaco-tipo" name="tipo" value="Espaço">
          <input type="hidden" name="turma_id" value="<?php echo htmlspecialchars($aluno_turma_id); ?>">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
          <div class="form-group">
            <input type="text" id="agendar-espaco-nome" name="nome" readonly>
            <label for="agendar-espaco-nome">Espaço</label>
          </div>
          <div class="form-group">
            <select id="agendar-espaco-turma" disabled>
              <option value="<?php echo htmlspecialchars($aluno_turma_id); ?>"><?php echo htmlspecialchars($aluno_turma_nome); ?></option>
            </select>
            <label for="agendar-espaco-turma">Turma</label>
          </div>
          <div class="form-group">
            <input type="datetime-local" id="agendar-espaco-data" name="data_hora" required>
            <label for="agendar-espaco-data">Data e Hora</label>
          </div>
          <input type="hidden" name="btn" value="agendar_item">
          <button type="submit">Agendar</button>
        </form>
        <div class="confirmation-message hidden" id="agendar-espaco-confirmation">Espaço agendado com sucesso!</div>
        <div class="error-message hidden" id="agendar-espaco-error"></div>
      </div>
      <div id="meus-agendamentos-section" class="section hidden">
        <h2>Meus Agendamentos</h2>
        <div class="agendamentos-table-container">
          <table class="agendamentos-table">
            <thead>
              <tr>
                <th>Tipo</th>
                <th>Nome</th>
                <th>Data e Hora</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody id="agendamentos-tbody">
              <!-- Agendamentos serão inseridos aqui -->
            </tbody>
          </table>
          <div class="cards-list" id="agendamentos-cards"></div>
        </div>
        <div class="no-items-message hidden" id="agendamentos-no-items">Você não tem agendamentos no momento.</div>
        <div class="confirmation-message hidden" id="agendamentos-confirmation">Ação realizada com sucesso!</div>
        <div class="error-message hidden" id="agendamentos-error"></div>
      </div>
    </div>
  </div>

  <!-- Modal de Agendamento -->
  <div id="agendamento-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Agendar <span id="modal-tipo-item"></span></h2>
        <span class="close-modal">&times;</span>
      </div>
      <div class="modal-body">
        <form id="modal-agendamento-form">
          <input type="hidden" id="modal-item-id" name="id_item">
          <input type="hidden" id="modal-item-tipo" name="tipo">
          <input type="hidden" name="turma_id" value="<?php echo htmlspecialchars($aluno_turma_id); ?>">
          <div class="form-group">
            <input type="text" id="modal-item-nome" name="nome" readonly>
            <label for="modal-item-nome">Nome</label>
          </div>
          <div class="form-group">
            <select id="modal-item-turma" disabled>
              <option value="<?php echo htmlspecialchars($aluno_turma_id); ?>"><?php echo htmlspecialchars($aluno_turma_nome); ?></option>
            </select>
            <label for="modal-item-turma">Turma</label>
          </div>
          <div class="form-group">
            <input type="datetime-local" id="modal-item-data" name="data_hora" required>
            <label for="modal-item-data">Data e Hora</label>
          </div>
          <input type="hidden" name="btn" value="agendar_item">
          <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <button type="submit" class="btn-submit">Agendar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Adicionar o elemento HTML para o toast -->
  <div id="toast" class="toast"></div>

  <!-- Botão de menu hambúrguer para mobile -->
  <!-- Navbar animada para mobile -->
  <nav class="mobile-navbar" id="mobileNavbar">
    <button class="close-mobile-menu" id="closeMobileMenu"><i class="fas fa-times"></i></button>
    <a href="#" data-section="agendar-equipamentos"><i class="fas fa-laptop"></i> Equipamentos</a>
    <a href="#" data-section="agendar-espacos"><i class="fas fa-building"></i> Espaços</a>
    <a href="#" data-section="meus-agendamentos"><i class="fas fa-calendar-check"></i> Agendamentos</a>
  </nav>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const menuItems = document.querySelectorAll('.menu-item');
      const sections = document.querySelectorAll('.section');
      const modal = document.getElementById('agendamento-modal');
      const closeBtn = document.querySelector('.close-modal');
      const modalForm = document.getElementById('modal-agendamento-form');
      const equipamentosTbody = document.getElementById('equipamentos-tbody');
      const espacosTbody = document.getElementById('espacos-tbody');
      const agendamentosTbody = document.getElementById('agendamentos-tbody');
      const equipamentosNoItems = document.getElementById('equipamentos-no-items');
      const espacosNoItems = document.getElementById('espacos-no-items');
      const agendamentosNoItems = document.getElementById('agendamentos-no-items');
      const equipamentosCards = document.getElementById('equipamentos-cards');
      const espacosCards = document.getElementById('espacos-cards');
      const agendamentosCards = document.getElementById('agendamentos-cards');
      const menuBtn = document.getElementById('mobileMenuBtn');
      const navbar = document.getElementById('mobileNavbar');

      // Mobile menu functionality
      if (menuBtn && navbar) {
        const navLinks = navbar.querySelectorAll('a');
        menuBtn.addEventListener('click', function() {
          navbar.classList.toggle('open');
        });
        navLinks.forEach(link => {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            showSection(section);
            navbar.classList.remove('open');
            // Destaca o botão ativo
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
          });
        });
        // Fecha o menu ao clicar fora dele
        document.addEventListener('click', function(e) {
          if (navbar.classList.contains('open') && !navbar.contains(e.target) && e.target !== menuBtn) {
            navbar.classList.remove('open');
          }
        });
      }

      // Add event listener for the close button
      const closeMenuBtn = document.getElementById('closeMobileMenu');
      if (closeMenuBtn && navbar) {
        closeMenuBtn.addEventListener('click', function() {
          navbar.classList.remove('open');
        });
      }

      // Funções do Modal
      window.showModal = function(tipo, id, nome) {
        document.getElementById('modal-tipo-item').textContent = tipo === 'equipamento' ? 'Equipamento' : 'Espaço';
        document.getElementById('modal-item-id').value = id;
        document.getElementById('modal-item-tipo').value = tipo === 'equipamento' ? 'Equipamento' : 'Espaço';
        document.getElementById('modal-item-nome').value = nome;
        modal.style.display = 'block';
      };

      window.closeModal = function() {
        modal.style.display = 'none';
        modalForm.reset();
      };

      // Fechar modal ao clicar no X ou fora dele
      closeBtn.onclick = closeModal;
      window.onclick = function(event) {
        if (event.target == modal) {
          closeModal();
        }
      };

      // Função para mostrar seção
      function showSection(sectionId) {
        sections.forEach(section => section.classList.remove('active'));
        document.getElementById(`${sectionId}-section`).classList.add('active');
        equipamentosNoItems.classList.add('hidden');
        espacosNoItems.classList.add('hidden');
        agendamentosNoItems.classList.add('hidden');
        document.querySelectorAll('.confirmation-message').forEach(msg => msg.classList.remove('active'));
        document.querySelectorAll('.error-message').forEach(msg => msg.classList.remove('active'));
        
        if (sectionId === 'agendar-equipamentos') {
          loadItens('equipamentos', equipamentosTbody, equipamentosNoItems);
        } else if (sectionId === 'agendar-espacos') {
          loadItens('espacos', espacosTbody, espacosNoItems);
        } else if (sectionId === 'meus-agendamentos') {
          loadAgendamentos();
        }
      }

      // Carregar itens disponíveis
      function loadItens(tipo, tbody, noItemsDiv) {
        const formData = new FormData();
        formData.append('tipo', tipo);

        fetch('../controllers/ListarController.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              tbody.innerHTML = '';
              let cardsDiv = tipo === 'equipamentos' ? equipamentosCards : espacosCards;
              cardsDiv.innerHTML = '';
              if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                  // Tabela (desktop)
                  const tr = document.createElement('tr');
                  tr.innerHTML = `
                    <td>${item.nome}</td>
                    <td>${item.descricao}</td>
                    <td>${item.quantidade_disponivel}</td>
                    <td>
                      <button class="action-btn agendar-btn" onclick="showModal('${tipo === 'equipamentos' ? 'equipamento' : 'espaco'}', ${item.id}, '${item.nome.replace(/'/g, "\\'")}')">
                        <i class="fas fa-calendar-plus"></i> Agendar
                      </button>
                    </td>
                  `;
                  tbody.appendChild(tr);
                  // Card (mobile)
                  const card = document.createElement('div');
                  card.className = 'item-card';
                  card.innerHTML = `
                    <div class="card-title">${item.nome}</div>
                    <div class="card-desc">${item.descricao}</div>
                    <div class="card-qty">Disponível: ${item.quantidade_disponivel}</div>
                    <div class="card-actions">
                      <button class="action-btn agendar-btn" onclick="showModal('${tipo === 'equipamentos' ? 'equipamento' : 'espaco'}', ${item.id}, '${item.nome.replace(/'/g, "\\'")}')">
                        <i class='fas fa-calendar-plus'></i> Agendar
                      </button>
                    </div>
                  `;
                  cardsDiv.appendChild(card);
                });
                noItemsDiv.classList.add('hidden');
              } else {
                noItemsDiv.classList.remove('hidden');
                cardsDiv.innerHTML = '';
              }
            } else {
              throw new Error(data.message || 'Erro ao carregar itens');
            }
          })
          .catch(error => {
            console.error('Erro:', error);
            noItemsDiv.textContent = 'Erro ao carregar itens. Tente novamente mais tarde.';
            noItemsDiv.classList.remove('hidden');
            if (tipo === 'equipamentos') equipamentosCards.innerHTML = '';
            else espacosCards.innerHTML = '';
          });
      }

      // Carregar agendamentos
      function loadAgendamentos() {
        const formData = new FormData();
        formData.append('tipo', 'agendamentos');

        fetch('../controllers/ListarController.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              agendamentosTbody.innerHTML = '';
              agendamentosCards.innerHTML = '';
              if (data.agendamentos && data.agendamentos.length > 0) {
                data.agendamentos.forEach(agendamento => {
                  // Tabela (desktop)
                  const tr = document.createElement('tr');
                  tr.innerHTML = `
                    <td>${agendamento.tipo}</td>
                    <td>${agendamento.nome}</td>
                    <td>${new Date(agendamento.data_hora).toLocaleString()}</td>
                    <td class="status-${agendamento.status.toLowerCase()}">${agendamento.status}</td>
                    <td>
                      ${agendamento.status === 'Pendente' ? `
                        <button class="action-btn cancel-btn" onclick="cancelarAgendamento(${agendamento.id})">
                          <i class="fas fa-times"></i> Cancelar
                        </button>
                      ` : ''}
                    </td>
                  `;
                  agendamentosTbody.appendChild(tr);
                  // Card (mobile)
                  const card = document.createElement('div');
                  card.className = 'agendamento-card';
                  card.innerHTML = `
                    <div class="card-title">${agendamento.nome} <span style='font-size:0.9em;color:#888;'>(${agendamento.tipo})</span></div>
                    <div class="card-desc">Data e Hora: ${new Date(agendamento.data_hora).toLocaleString()}</div>
                    <div class="card-status status-${agendamento.status.toLowerCase()}">${agendamento.status}</div>
                    <div class="card-actions">
                      ${agendamento.status === 'Pendente' ? `
                        <button class='action-btn cancel-btn' onclick='cancelarAgendamento(${agendamento.id})'>
                          <i class='fas fa-times'></i> Cancelar
                        </button>
                      ` : ''}
                    </div>
                  `;
                  agendamentosCards.appendChild(card);
                });
                agendamentosNoItems.classList.add('hidden');
              } else {
                agendamentosNoItems.classList.remove('hidden');
                agendamentosCards.innerHTML = '';
              }
            } else {
              throw new Error(data.message || 'Erro ao carregar agendamentos');
            }
          })
          .catch(error => {
            console.error('Erro:', error);
            agendamentosNoItems.textContent = 'Erro ao carregar agendamentos. Tente novamente mais tarde.';
            agendamentosNoItems.classList.remove('hidden');
            agendamentosCards.innerHTML = '';
          });
      }

      // Cancelar agendamento
      window.cancelarAgendamento = function(id) {
        if (!confirm('Tem certeza que deseja cancelar este agendamento?')) {
          return;
        }

        const formData = new FormData();
        formData.append('id', id);
        formData.append('btn', 'cancelar_agendamento');

        fetch('../controllers/AgendamentoController.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              document.getElementById('agendamentos-confirmation').classList.add('active');
              setTimeout(() => {
                document.getElementById('agendamentos-confirmation').classList.remove('active');
                loadAgendamentos();
              }, 2000);
            } else {
              throw new Error(data.message || 'Erro ao cancelar agendamento');
            }
          })
          .catch(error => {
            console.error('Erro:', error);
            document.getElementById('agendamentos-error').textContent = error.message;
            document.getElementById('agendamentos-error').classList.add('active');
            setTimeout(() => {
              document.getElementById('agendamentos-error').classList.remove('active');
            }, 3000);
          });
      };

      // Event listeners
      menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          menuItems.forEach(mi => mi.classList.remove('active'));
          this.classList.add('active');
          showSection(this.dataset.section);
        });
      });

      // Formulário de agendamento
      modalForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Validar dados antes de enviar
        const itemId = formData.get('id_item');
        const dataHora = formData.get('data_hora');
        const turmaId = formData.get('turma_id');

        if (!itemId || !dataHora || !turmaId) {
          showToast('Por favor, preencha todos os campos');
          return;
        }

        fetch('../controllers/CadastroController.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showToast('Item agendado com sucesso!');
              closeModal();
              // Recarregar a lista de itens
              const tipo = formData.get('tipo').toLowerCase();
              if (tipo === 'equipamento') {
                loadItens('equipamentos', equipamentosTbody, equipamentosNoItems);
              } else {
                loadItens('espacos', espacosTbody, espacosNoItems);
              }
            } else {
              throw new Error(data.message || 'Erro ao agendar item');
            }
          })
          .catch(error => {
            console.error('Erro:', error);
            showToast(error.message);
          });
      });

      // Adicionar a função JavaScript para exibir o toast
      function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.add('show');
        setTimeout(() => {
          toast.classList.remove('show');
        }, 3000);
      }

      // Inicializar
      showSection('agendar-equipamentos');
    });
  </script>
</body>
</html>