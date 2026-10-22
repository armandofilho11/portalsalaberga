<?php
session_start();

// Debug - Remover em produção
error_log("Sessão atual: " . print_r($_SESSION, true));

// Verificar se o usuário está logado e é um responsável
if (!isset($_SESSION['responsavel_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'responsavel') {
    error_log("Acesso negado ao painel_responsavel.php - Sessão inválida");
    error_log("responsavel_id: " . (isset($_SESSION['responsavel_id']) ? $_SESSION['responsavel_id'] : 'não definido'));
    error_log("user_type: " . (isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'não definido'));
    header("Location: /sistema_aee_completo/app/main/index.php");
    exit;
}

// Proteção CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Obter o nome do responsável da sessão
$responsavel_nome = $_SESSION['responsavel_nome'] ?? 'Responsável';
error_log("Nome do responsável carregado: " . $responsavel_nome);

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sis_aee";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Responsável | EEEP Salaberga</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="https://salaberga.com/salaberga/portalsalaberga/app/main/assets/img/Design%20sem%20nome.svg" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        :root {
            --primary-color: #007A33;
            --secondary-color: #FFA500;
            --success-color: #28a745;
            --danger-color: #dc3545;
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
        .sidebar { 
            width: 250px; 
            background: var(--primary-color); 
            color: #FFFFFF; 
            padding: 2rem 1.5rem; 
            display: flex; 
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar h3 { 
            font-size: 1.5rem; 
            font-weight: 600; 
            margin-bottom: 2rem; 
            text-align: center; 
        }
        .sidebar ul { 
            list-style: none; 
            margin: 0;
            padding: 0;
            flex-grow: 1;
        }
        .sidebar ul li { 
            margin-bottom: 1rem; 
        }
        .sidebar ul li a.menu-item { 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
            padding: 10px 15px; 
            color: #FFFFFF; 
            text-decoration: none; 
            font-size: 1rem; 
            border-radius: 10px; 
            transition: all 0.3s ease; 
        }
        .sidebar ul li a.menu-item:hover { 
            background-color: rgba(255, 255, 255, 0.1); 
        }
        .sidebar ul li a.menu-item.active { 
            background-color: var(--secondary-color); 
            color: #FFFFFF; 
        }
        .sidebar ul li a.menu-item i { 
            font-size: 1.2rem; 
        }
        .sidebar .logout {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 10px 15px;
            color: #FFFFFF;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .logout:hover {
            background-color: var(--secondary-color);
        }
        .content { flex: 1; background-color: var(--input-bg); padding: 2.5rem; overflow-y: auto; }
        .section { display: none; }
        .section.active { display: block; }
        .section h2 { color: var(--primary-color); font-size: 2rem; font-weight: 700; margin-bottom: 1.5rem; text-align: center; }
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 20px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 1rem; transition: all 0.3s ease; background-color: var(--input-bg); color: var(--text-color); resize: vertical; }
        .form-group textarea { min-height: 100px; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(0, 122, 51, 0.1); }
        .form-group label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            padding: 0 5px;
            background-color: var(--input-bg);
        }
        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label,
        .form-group select:focus + label,
        .form-group select:not([value=""]) + label,
        .form-group textarea:focus + label,
        .form-group textarea:not(:placeholder-shown) + label {
            top: 0;
            font-size: 0.8rem;
            color: var(--primary-color);
        }
        button[type="submit"] { width: 100%; padding: 12px; background-color: var(--secondary-color); color: #FFFFFF; border: none; border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 6px var(--shadow-color); }
        button[type="submit"]:hover:not(:disabled) { background-color: #e69500; transform: translateY(-2px); box-shadow: 0 6px 12px var(--shadow-color); }
        button[type="submit"]:disabled { background-color: #cccccc; cursor: not-allowed; transform: none; box-shadow: none; }
        .confirmation-message {
            background-color: var(--success-color);
            color: #FFFFFF;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            font-size: 1rem;
            font-weight: 500;
            margin-top: 1.5rem;
            box-shadow: 0 4px 8px var(--shadow-color);
        }
        .confirmation-message.active { display: block; }
        .error-message { display: none; background-color: var(--error-color); color: #FFFFFF; border-radius: 10px; padding: 1rem; text-align: center; font-size: 0.9rem; font-weight: 500; margin-top: 1rem; box-shadow: 0 4px 8px var(--shadow-color); }
        .error-message.active { display: block; }
        .items-table-container, .agendamentos-table-container { overflow-x: auto; margin-top: 1rem; }
        .items-table-container {
            margin: 0 -1rem;
            padding: 0 1rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .items-table, 
        .agendamentos-table {
            min-width: 600px;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .items-table th, 
        .items-table td, 
        .agendamentos-table th, 
        .agendamentos-table td { 
            padding: 12px 8px;
            font-size: 0.85rem;
            white-space: nowrap;
            border-bottom: 1px solid #eee;
        }
        .items-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .items-table tr:last-child td {
            border-bottom: none;
        }
        .items-table tr:hover {
            background: #f8f9fa;
        }
        .action-btn { 
            padding: 8px 12px;
            font-size: 0.8rem;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            min-width: 80px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .action-btn.edit-btn {
            background: #007A33;
            color: white;
        }
        .action-btn.delete-btn {
            background: #dc3545;
            color: white;
        }
        .action-btn.toggle-btn {
            background: #28a745;
            color: white;
        }
        .action-btn.toggle-btn.disabled {
            background: #6c757d;
        }
        .action-btn.approve-btn {
            background: #28a745;
            color: white;
        }
        .action-btn.approve-btn:hover {
            background: #218838;
        }
        .action-btn.reject-btn {
            background: #dc3545;
            color: white;
        }
        .action-btn.reject-btn:hover {
            background: #c82333;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }
        .status-badge.available {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.unavailable {
            background: #f8d7da;
            color: #721c24;
        }
        .no-items-message { text-align: center; font-size: 1rem; color: var(--text-color); margin-top: 1.5rem; }
        .status-pendente { color: #6c757d; font-weight: 500; }
        .status-aprovado { color: var(--success-color); font-weight: 600; }
        .status-rejeitado { color: var(--error-color); font-weight: 600; }
        .hidden { display: none !important; }
        @media (max-width: 768px) {
            .mobile-logout {
                display: flex;
            }

            .main-container {
                margin-top: 48px;
                padding-top: 1rem;
                flex-direction: column;
                height: auto;
                max-width: 100%;
                border-radius: 0;
                box-shadow: none;
                min-height: calc(100vh - 48px);
                overflow: hidden;
            }

            .content {
                padding: 1rem;
                padding-top: 0;
                margin-bottom: 90px;
                position: relative;
                z-index: 1;
            }

            .section h2 {
                font-size: 1.5rem;
                margin-top: 0.5rem;
                margin-bottom: 1.5rem;
            }

            .sidebar {
                width: 100vw;
                left: 0;
                bottom: 0;
                z-index: 99999;
                position: fixed;
                border-radius: 0;
                background: var(--primary-color);
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                height: auto;
                display: flex;
                justify-content: space-around;
                pointer-events: auto;
                padding: 0.5rem;
            }

            .sidebar::before {
                content: '';
                position: absolute;
                left: 0; right: 0; top: 0; bottom: 0;
                background: var(--primary-color);
                z-index: -1;
            }

            .sidebar h3 {
                display: none;
            }

            .sidebar .logout {
                display: none;
            }

            .sidebar ul {
                display: flex;
                flex-wrap: nowrap;
                justify-content: space-around;
                gap: 0.25rem;
                margin: 0;
                padding: 0;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none; /* Firefox */
                -ms-overflow-style: none; /* IE and Edge */
            }

            .sidebar ul::-webkit-scrollbar {
                display: none; /* Chrome, Safari, Opera */
            }

            .sidebar ul li {
                margin: 0;
                flex: 0 0 auto;
                text-align: center;
                min-width: 80px;
            }

            .sidebar ul li a.menu-item {
                padding: 0.5rem 0.25rem;
                font-size: 0.7rem;
                flex-direction: column;
                text-align: center;
                gap: 0.25rem;
                border-radius: 8px;
                white-space: nowrap;
                justify-content: center;
                align-items: center;
                background: rgba(255, 255, 255, 0.1);
                transition: all 0.3s ease;
            }

            .sidebar ul li a.menu-item:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            .sidebar ul li a.menu-item.active {
                background: var(--secondary-color);
            }

            .sidebar ul li a.menu-item i {
                font-size: 1.1rem;
                margin-bottom: 0.2rem;
            }

            /* Estilos para a seção de relatórios em mobile */
            #gerar-relatorios-section {
                padding: 1rem;
            }

            #gerar-relatorios-section .form-group {
                margin-bottom: 1.5rem;
            }

            #gerar-relatorios-section select {
                width: 100%;
                padding: 12px 16px;
                font-size: 16px; /* Previne zoom em iOS */
                border: 2px solid #e0e0e0;
                border-radius: 8px;
                background-color: #fff;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right 12px center;
                background-size: 16px;
                padding-right: 40px;
            }

            #gerar-relatorios-section select:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(0, 122, 51, 0.1);
            }

            #gerar-relatorios-section button[type="submit"] {
                width: 100%;
                padding: 14px;
                font-size: 16px;
                margin-top: 1rem;
                background-color: #FFA500;
                color: white;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            #gerar-relatorios-section button[type="submit"]:hover {
                background-color: #e69500;
                transform: translateY(-2px);
            }

            #gerar-relatorios-section button[type="submit"]:active {
                transform: translateY(0);
            }

            /* Estilo para o grupo de seleção de turma */
            #turma-select-group {
                margin-top: 1rem;
                transition: all 0.3s ease;
            }

            #turma-select-group.active {
                display: block;
                animation: slideDown 0.3s ease-out;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Estilo para mensagens de feedback */
            #relatorio-confirmation,
            #relatorio-error {
                margin-top: 1rem;
                padding: 12px;
                border-radius: 8px;
                text-align: center;
                font-size: 14px;
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }
        }
        @media (max-width: 480px) {
            .content {
                padding: 0.8rem;
                margin-bottom: 60px;
            }
            .section h2 {
                font-size: 1.2rem;
                margin-bottom: 1rem;
            }
            .items-table th, 
            .items-table td, 
            .agendamentos-table th, 
            .agendamentos-table td {
                padding: 6px;
                font-size: 0.75rem;
            }
            .action-btn {
                padding: 10px 12px;
                font-size: 0.85rem;
                min-width: 80px;
            }
            .form-group label {
                font-size: 0.9rem;
            }
            .form-group input:focus + label,
            .form-group input:not(:placeholder-shown) + label,
            .form-group select:focus + label,
            .form-group select:not(:placeholder-shown) + label,
            .form-group textarea:focus + label,
            .form-group textarea:not(:placeholder-shown) + label {
                font-size: 0.7rem;
            }
            .sidebar ul li a.menu-item {
                font-size: 0.75rem;
                padding: 0.6rem 0.3rem;
            }
            .sidebar ul li a.menu-item i {
                font-size: 1.05rem;
            }
            .items-table td:last-child {
                padding-top: 1rem;
                margin-top: 0.5rem;
                border-top: 1px solid #eee;
                justify-content: flex-start;
                gap: 0.5rem;
                flex-direction: column;
                align-items: stretch;
            }
            .items-table td:last-child .action-btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .items-table td:last-child .action-btn:last-child {
                margin-bottom: 0;
            }
            .modal-content {
                padding: 0.8rem;
                margin: 0.8rem;
            }
            .modal-content .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }
            .modal-content .form-actions button {
                width: 100%;
            }
            .toggle-btn {
                padding: 4px 8px;
                font-size: 0.65rem;
                min-width: 80px;
            }
            .confirmation-message,
            .error-message {
                font-size: 0.85rem;
                padding: 0.7rem;
            }
            button[type="submit"] {
                padding: 8px;
                font-size: 0.85rem;
            }
        }
        /* Estilos para o modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
            display: flex;
        }

        .modal-content {
            background: #FFFFFF;
            padding: 2rem;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            transform: scale(0.7);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .modal.active .modal-content {
            transform: scale(1);
            opacity: 1;
        }

        .modal-content h2 {
            color: var(--text-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            position: relative;
        }

        .modal-content h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .modal-content p {
            text-align: center;
            margin-bottom: 2rem;
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .form-actions button {
            flex: 1;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(0, 122, 51, 0.2);
        }

        .btn-secondary {
            background-color: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
        }

        .btn-danger {
            background-color: #fff;
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }

        .btn-primary:hover {
            background-color: #006128;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 122, 51, 0.3);
        }

        .btn-secondary:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }

        .btn-danger:hover {
            background-color: var(--danger-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-primary:active,
        .btn-secondary:active,
        .btn-danger:active {
            transform: translateY(0);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            color: var(--text-color);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(0, 122, 51, 0.1);
        }

        .form-group label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
            transition: all 0.3s ease;
            pointer-events: none;
            padding: 0 5px;
            background-color: var(--input-bg);
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label,
        .form-group select:focus + label,
        .form-group select:not([value=""]) + label,
        .form-group textarea:focus + label,
        .form-group textarea:not(:placeholder-shown) + label {
            top: 0;
            font-size: 0.8rem;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .mobile-logout {
                display: flex;
            }

            .main-container {
                margin-top: 48px;
            }

            .section h2 {
                font-size: 1.5rem;
                margin-top: 0.5rem;
                margin-bottom: 1.5rem;
            }

            .content {
                padding-top: 0;
            }

            .mobile-logout {
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                padding: 0 1rem;
            }

            .mobile-logout a {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }

            .mobile-logout a i {
                font-size: 1rem;
            }
        }
        /* Mobile Logout Button */
        .mobile-logout {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--primary-color);
            height: 48px;
            padding: 0 1rem;
            z-index: 2000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-logout a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: var(--primary-color);
            font-weight: 400;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .mobile-logout a:hover {
            background: var(--secondary-color);
        }

        .mobile-logout a i {
            font-size: 1.1rem;
        }

        @media (min-width: 769px) {
            .mobile-logout {
                display: none !important;
            }
        }

        /* Estilos para o container de toast */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.toast {
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 250px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: slideIn 0.3s ease-in-out;
}

.toast.success {
    background-color: var(--success-color);
}

.toast.error {
    background-color: var(--error-color);
}

.toast.info {
    background-color: var(--primary-color);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1rem;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 1.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
}

.card-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.card-content {
    margin-bottom: 1rem;
}

.card-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.card-info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-info-label {
    font-weight: 500;
    color: #666;
    min-width: 100px;
}

.card-info-value {
    color: #333;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.available {
    background-color: #e6f4ea;
    color: #1e7e34;
}

.status-badge.unavailable {
    background-color: #fbe9e7;
    color: #d32f2f;
}

@media (max-width: 768px) {
    .cards-container {
        grid-template-columns: 1fr;
    }

    .card {
        margin: 0 1rem;
    }
}
    </style>
    <style>
    /* Garante que só a aba ativa aparece */
    .section { display: none !important; }
    .section.active { display: block !important; }
    </style>
    <style>
    @media (max-width: 768px) {
      .mobile-menu-btn {
        position: static; /* Changed from fixed */
        /* top: 16px; Removed */
        /* left: 16px; Removed */
        z-index: auto; /* Adjust z-index */
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
        position: fixed;
        top: 0;
        left: -100vw;
        width: 80vw;
        max-width: 320px;
        height: 100vh;
        background: var(--primary-color);
        box-shadow: 2px 0 12px rgba(0,0,0,0.12);
        display: flex;
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
        left: 10px; /* Changed from right: 10px */
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
      /* Esconde sidebar original no mobile */
      .sidebar {
        display: none !important;
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
    <!-- Adicionar o modal antes do container principal -->
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <h2>Confirmar Exclusão</h2>
            <p>
                Tem certeza que deseja excluir este cadastro? Esta ação não pode ser desfeita.
            </p>
            <div class="form-actions">
                <button class="btn-secondary" id="cancelDelete">
                    <i class="fas fa-times"></i>
                    Não, Cancelar
                </button>
                <button class="btn-danger" id="confirmDelete">
                    <i class="fas fa-trash-alt"></i>
                    Sim, Excluir
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <h2>Editar Cadastro</h2>
            <form id="edit-form">
                <input type="hidden" id="edit-id">
                <input type="hidden" id="edit-tipo">
                <div class="form-group">
                    <input type="text" id="edit-nome" required placeholder=" ">
                    <label for="edit-nome">Nome</label>
                </div>
                <div class="form-group">
                    <textarea id="edit-descricao" required placeholder=" "></textarea>
                    <label for="edit-descricao">Descrição</label>
                </div>
                <div class="form-group">
                    <input type="number" id="edit-quantidade" required min="1" placeholder=" ">
                    <label for="edit-quantidade">Quantidade</label>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="salvarEdicao()" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Salvar
                    </button>
                    <button type="button" onclick="fecharModal()" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="mobile-logout">
        <!-- Botão de menu hambúrguer para mobile -->
        <button class="mobile-menu-btn" id="mobileMenuBtn">
          <i class="fas fa-bars"></i>
        </button>
        <a href="../../app/main/index.php"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
    <div class="main-container">
        <div class="sidebar">
            <h3>Bem-vindo, <?php echo $responsavel_nome; ?></h3>
            <ul>
                <li><a href="#" target="blank" class="menu-item active" data-section="cadastrar-equipamentos"><i class="fas fa-laptop"></i> Cadastrar Equipamentos</a></li>
                <li><a href="#" target="blank" class="menu-item" data-section="cadastrar-espacos"><i class="fas fa-building"></i> Cadastrar Espaços</a></li>
                <li><a href="#" target="blank" class="menu-item" data-section="registros-realizados"><i class="fas fa-clipboard-check"></i> Registros Realizados</a></li>
                <li><a href="#" target="blank" class="menu-item" data-section="gerar-relatorios"><i class="fas fa-file-alt"></i> Gerar Relatórios</a></li>
                <li><a href="#" class="menu-item" data-section="assinaturas"><i class="fas fa-signature"></i> Assinaturas</a></li>
            </ul>
            <a href="../../app/main/index.php" class="logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
        <div class="content">
            <!-- Seção de Cadastro de Equipamentos -->
            <div id="cadastrar-equipamentos-section" class="section active">
                <h2>Cadastrar Equipamento</h2>
                <form id="cadastrar-equipamentos-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="btn" value="cadastrar_equipamento">
                    <div class="form-group">
                        <input type="text" id="equipamento-nome" name="equipamento-nome" required placeholder=" ">
                        <label for="equipamento-nome">Nome do Equipamento</label>
                    </div>
                    <div class="form-group">
                        <textarea id="equipamento-descricao" name="equipamento-descricao" required placeholder=" "></textarea>
                        <label for="equipamento-descricao">Descrição</label>
                    </div>
                    <div class="form-group">
                        <input type="number" id="equipamento-disponivel" name="equipamento-disponivel" required min="1" placeholder=" ">
                        <label for="equipamento-disponivel">Quantidade Disponível</label>
                    </div>
                    <button type="submit">Cadastrar Equipamento</button>
                </form>
                <div class="confirmation-message hidden" id="cadastrar-equipamentos-confirmation">Equipamento cadastrado com sucesso!</div>
                <div class="error-message" id="cadastrar-equipamentos-error"></div>
            </div>

            <!-- Seção de Cadastro de Espaços -->
            <div id="cadastrar-espacos-section" class="section">
                <h2>Cadastrar Espaço</h2>
                <form id="cadastrar-espaco-form" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="btn" value="cadastrar_espaco">
                    <div class="form-group">
                        <input type="text" id="espaco-nome" name="espaco-nome" required placeholder=" ">
                        <label for="espaco-nome">Nome do Espaço</label>
                    </div>
                    <div class="form-group">
                        <textarea id="espaco-descricao" name="espaco-descricao" required placeholder=" "></textarea>
                        <label for="espaco-descricao">Descrição</label>
                    </div>
                    <div class="form-group">
                        <input type="number" id="espaco-disponivel" name="espaco-disponivel" required min="1" placeholder=" ">
                        <label for="espaco-disponivel">Quantidade Disponível</label>
                    </div>
                    <button type="submit">Cadastrar Espaço</button>
                </form>
                <div class="confirmation-message hidden" id="cadastrar-espaco-confirmation">Espaço cadastrado com sucesso!</div>
                <div class="error-message" id="cadastrar-espaco-error"></div>
            </div>

            <!-- Seção de Registros Realizados -->
            <div id="registros-realizados-section" class="section">
                <h2>Registros Realizados</h2>
                <div class="cards-container" id="registros-cards">
                    <!-- Cards will be inserted here -->
                </div>
                <div class="no-items-message hidden" id="registros-no-items">
                    <i class="fas fa-info-circle"></i>
                    Nenhum registro encontrado.
                </div>
                <div class="confirmation-message hidden" id="registros-confirmation"></div>
                <div class="error-message hidden" id="registros-error"></div>
            </div>

            <!-- Seção de Gerar Relatórios -->
            <div id="gerar-relatorios-section" class="section hidden">
                <h2>Gerar Relatórios</h2>
                <form id="gerar-relatorio-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="form-group">
                        <select id="tipo-relatorio" target="blank" name="tipo" required>
                            <option  value="">Selecione o tipo de relatório</option>
                            <option target="blank" value="equipamentos">Equipamentos Cadastrados</option>
                            <option target="blank" value="espacos">Espaços Cadastrados</option>
                            <option target="blank" value="agendamentos">Relatório de Agendamentos</option>
                            <option target="blank" value="turma">Relatório por Turma</option>
                        </select>
                        <label for="tipo-relatorio">Tipo de Relatório</label>
                    </div>
                    <div class="form-group" id="turma-select-group" style="display: none;">
                        <select id="turma-select" name="turma_id">
                            <option value="">Selecione a turma</option>
                        </select>
                        <label for="turma-select">Turma</label>
                    </div>
                    <button type="submit" class="btn-primary">Gerar Relatório</button>
                </form>
                <div class="confirmation-message hidden" id="relatorio-confirmation">Relatório gerado com sucesso!</div>
                <div class="error-message hidden" id="relatorio-error"></div>
            </div>

            <!-- Seção de Assinaturas -->
            <div id="assinaturas-section" class="section hidden">
                <h2>Assinaturas Pendentes</h2>
                <div class="cards-container" id="assinaturas-cards">
                    <!-- Cards will be inserted here -->
                </div>
                <div class="no-items-message hidden" id="assinaturas-no-items">Não há agendamentos pendentes para assinatura.</div>
                <div class="confirmation-message hidden" id="assinaturas-confirmation"></div>
                <div class="error-message hidden" id="assinaturas-error"></div>
            </div>
        </div>
    </div>

    <!-- Navbar animada para mobile -->
    <nav class="mobile-navbar" id="mobileNavbar">
      <button class="close-mobile-menu" id="closeMobileMenu"><i class="fas fa-times"></i></button>
      <a href="#" data-section="cadastrar-equipamentos"><i class="fas fa-laptop"></i> Equipamentos</a>
      <a href="#" data-section="cadastrar-espacos"><i class="fas fa-building"></i> Espaços</a>
      <a href="#" data-section="registros-realizados"><i class="fas fa-clipboard-check"></i> Registros</a>
      <a href="#" data-section="gerar-relatorios"><i class="fas fa-file-alt"></i> Relatórios</a>
      <a href="#" data-section="assinaturas"><i class="fas fa-signature"></i> Assinaturas</a>
    </nav>

    <script>
        // Funções movidas para o escopo global para serem acessíveis por outras funções
        function showSection(sectionId) {
            console.log('showSection chamada para:', sectionId);
            const sections = document.querySelectorAll('.section');
            const menuItems = document.querySelectorAll('.menu-item');
            // Remove .active de todas as seções
            sections.forEach(section => {
                section.classList.remove('active');
            });
            // Adiciona .active apenas na seção correta
            const targetSection = document.getElementById(`${sectionId}-section`);
            if (targetSection) {
                targetSection.classList.add('active');
                console.log('Seção ativada:', targetSection.id);
            } else {
                console.log('Seção não encontrada:', sectionId);
            }
            // Remove .active de todos os itens do menu
            menuItems.forEach(item => item.classList.remove('active'));
            // Adiciona .active apenas no menu clicado
            const targetMenu = document.querySelector(`[data-section="${sectionId}"]`);
            if (targetMenu) {
                targetMenu.classList.add('active');
            }
            // Carregar registros quando a seção de registros for mostrada
            if (sectionId === 'registros-realizados') {
                loadRegistros();
            } else if (sectionId === 'assinaturas') {
                loadAssinaturas();
            }
        }

        function loadRegistros() {
            console.log('Carregando registros...');
            fetch('../controllers/CadastroController.php?btn=listar_cadastros')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na resposta do servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Dados recebidos:', data);
                    const cardsContainer = document.getElementById('registros-cards');
                    const noItems = document.getElementById('registros-no-items');
                    const errorMsg = document.getElementById('registros-error');
                    cardsContainer.innerHTML = '';
                    errorMsg.classList.add('hidden');
                    if (!data.success) {
                        errorMsg.textContent = data.message || 'Erro ao carregar registros';
                        errorMsg.classList.remove('hidden');
                        noItems.classList.add('hidden');
                        return;
                    }
                    if (!data.data || data.data.length === 0) {
                        noItems.classList.remove('hidden');
                        return;
                    }
                    noItems.classList.add('hidden');
                    data.data.forEach(item => {
                        const card = document.createElement('div');
                        card.className = 'card';
                        card.innerHTML = `
                            <div class="card-header">
                                <h3 class="card-title">${item.nome}</h3>
                                <span class="status-badge ${item.disponivel ? 'available' : 'unavailable'}">
                                    ${item.disponivel ? 'Disponível' : 'Indisponível'}
                                </span>
                            </div>
                            <div class="card-content">
                                <div class="card-info">
                                    <div class="card-info-item">
                                        <span class="card-info-label">Tipo:</span>
                                        <span class="card-info-value">${item.tipo}</span>
                                    </div>
                                    <div class="card-info-item">
                                        <span class="card-info-label">Descrição:</span>
                                        <span class="card-info-value">${item.descricao || '-'}</span>
                                    </div>
                                    <div class="card-info-item">
                                        <span class="card-info-label">Quantidade:</span>
                                        <span class="card-info-value">${item.quantidade_disponivel}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <button class="action-btn edit-btn" onclick="editarCadastro('${item.id}', '${item.tipo}', '${item.nome.replace(/'/g, "\\'")}', '${(item.descricao || '').replace(/'/g, "\\'")}', '${item.quantidade_disponivel}')">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="action-btn delete-btn" onclick="showModal('${item.id}', '${item.tipo}')">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                                <button class="action-btn toggle-btn ${!item.disponivel ? 'disabled' : ''}" 
                                        onclick="toggleDisponibilidade('${item.tipo}', ${item.id}, ${item.disponivel}, this)">
                                    ${item.disponivel ? 'Disponível' : 'Indisponível'}
                                </button>
                            </div>
                        `;
                        cardsContainer.appendChild(card);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar registros:', error);
                    const errorMsg = document.getElementById('registros-error');
                    errorMsg.textContent = error.message || 'Erro ao carregar registros. Tente novamente mais tarde.';
                    errorMsg.classList.remove('hidden');
                    document.getElementById('registros-no-items').classList.add('hidden');
                });
        }

        function loadAssinaturas() {
            fetch('../controllers/CadastroController.php?btn=listar_assinaturas', {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(data => {
                const cardsContainer = document.getElementById('assinaturas-cards');
                const noItems = document.getElementById('assinaturas-no-items');
                const errorMsg = document.getElementById('assinaturas-error');
                cardsContainer.innerHTML = '';
                errorMsg.classList.add('hidden');
                if (data.success) {
                    if (data.agendamentos && data.agendamentos.length > 0) {
                        data.agendamentos.forEach(function(agendamento) {
                            const card = document.createElement('div');
                            card.className = 'card';
                            card.setAttribute('data-agendamento-id', agendamento.id);
                            card.innerHTML = `
                                <div class="card-header">
                                    <h3 class="card-title">${agendamento.aluno_nome || 'N/A'}</h3>
                                    <span class="status-badge">Pendente</span>
                                </div>
                                <div class="card-content">
                                    <div class="card-info">
                                        <div class="card-info-item">
                                            <span class="card-info-label">Turma:</span>
                                            <span class="card-info-value">${agendamento.turma_nome || 'N/A'}</span>
                                        </div>
                                        <div class="card-info-item">
                                            <span class="card-info-label">Tipo:</span>
                                            <span class="card-info-value">${agendamento.tipo || 'N/A'}</span>
                                        </div>
                                        <div class="card-info-item">
                                            <span class="card-info-label">Item:</span>
                                            <span class="card-info-value">${agendamento.nome_item || 'N/A'}</span>
                                        </div>
                                        <div class="card-info-item">
                                            <span class="card-info-label">Data/Hora:</span>
                                            <span class="card-info-value">${new Date(agendamento.data_hora).toLocaleString()}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-actions">
                                    <button class="action-btn approve-btn" onclick="assinarAgendamento(${agendamento.id}, '${agendamento.tipo}', 'Aprovado')">
                                        <i class="fas fa-check"></i> Aprovar
                                    </button>
                                    <button class="action-btn reject-btn" onclick="assinarAgendamento(${agendamento.id}, '${agendamento.tipo}', 'Rejeitado')">
                                        <i class="fas fa-times"></i> Rejeitar
                                    </button>
                                </div>
                            `;
                            cardsContainer.appendChild(card);
                        });
                        noItems.classList.add('hidden');
                        errorMsg.classList.add('hidden');
                    } else {
                        noItems.classList.remove('hidden');
                        cardsContainer.innerHTML = '';
                        errorMsg.classList.add('hidden');
                    }
                } else {
                    errorMsg.textContent = data.message || 'Erro ao carregar assinaturas pendentes';
                    errorMsg.classList.remove('hidden');
                    noItems.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                const noItems = document.getElementById('assinaturas-no-items');
                const errorMsg = document.getElementById('assinaturas-error');
                const cardsContainer = document.getElementById('assinaturas-cards');
                cardsContainer.innerHTML = '';
                noItems.classList.add('hidden');
                errorMsg.textContent = error.message || 'Erro ao carregar assinaturas pendentes. Tente novamente mais tarde.';
                errorMsg.classList.remove('hidden');
            });
        }

        // Carregar assinaturas ao iniciar
        document.addEventListener('DOMContentLoaded', function() {
            loadAssinaturas();
        });

        // Atualizar assinaturas a cada 30 segundos
        setInterval(loadAssinaturas, 30000);

        // Toast Notification Functions
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-circle"></i>';
                    break;
                default:
                    icon = '<i class="fas fa-info-circle"></i>';
            }
            
            toast.innerHTML = `${icon}${message}`;
            container.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-in-out forwards';
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Updated assinarAgendamento function
        function assinarAgendamento(id, tipo, status) {
            // Normalizar tipo para o backend
            let tipoBackend = tipo;
            if (tipo.toLowerCase() === 'equipamento') tipoBackend = 'Equipamento';
            else if (tipo.toLowerCase() === 'espaco' || tipo.toLowerCase() === 'espaço') tipoBackend = 'Espaço';
            
            // Desabilitar os botões do card sendo processado
            const card = document.querySelector(`.card[data-agendamento-id="${id}"]`);
            if (card) {
                const buttons = card.querySelectorAll('button');
                buttons.forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                });
            }

            showToast('Processando agendamento...', 'info');

            const formData = new FormData();
            formData.append('btn', 'assinar_agendamento');
            formData.append('id', id);
            formData.append('tipo', tipoBackend);
            formData.append('status', status);
            formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

            fetch('../controllers/CadastroController.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na resposta do servidor: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    
                    // Remover o card com animação
                    if (card) {
                        card.style.transition = 'opacity 0.3s ease-in-out';
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.remove();
                            // Verificar se há mais agendamentos
                            const cardsContainer = document.querySelector('.cards-container');
                            if (cardsContainer && !cardsContainer.hasChildNodes()) {
                                document.getElementById('assinaturas-no-items').classList.remove('hidden');
                            }
                        }, 300);
                    }
                } else {
                    throw new Error(data.message || 'Erro ao processar assinatura');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast(error.message || 'Erro ao processar assinatura. Tente novamente mais tarde.', 'error');
                
                // Reabilitar os botões em caso de erro
                if (card) {
                    const buttons = card.querySelectorAll('button');
                    buttons.forEach(btn => {
                        btn.disabled = false;
                        btn.style.opacity = '1';
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            const cadastrarEquipamentoForm = document.getElementById('cadastrar-equipamentos-form');
            const cadastrarEspacoForm = document.getElementById('cadastrar-espaco-form');
            const gerarRelatorioForm = document.getElementById('gerar-relatorio-form');
            const sidebar = document.querySelector('.sidebar ul');

            // Adicionar indicador de scroll para o menu
            if (sidebar) {
                sidebar.addEventListener('scroll', function() {
                    const isAtStart = this.scrollLeft === 0;
                    const isAtEnd = this.scrollLeft + this.clientWidth >= this.scrollWidth;
                    
                    this.style.paddingLeft = isAtStart ? '0' : '20px';
                    this.style.paddingRight = isAtEnd ? '0' : '20px';
                });
            }

            // Event listeners para os itens do menu
            menuItems.forEach(item => {
                item.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    const section = this.getAttribute('data-section');
                    console.log('touchstart menu:', section);
                    showSection(section);
                });
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = this.getAttribute('data-section');
                    console.log('click menu:', section);
                    showSection(section);
                });
            });

            // Formulário de cadastro de equipamento
            if (cadastrarEquipamentoForm) {
                cadastrarEquipamentoForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Enviando formulário de equipamentos...');
                    
                    const formData = new FormData(this);
                    
                    fetch('../controllers/CadastroController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na resposta do servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Resposta do servidor:', data);
                        if (data.success) {
                            const confirmation = document.getElementById('cadastrar-equipamentos-confirmation');
                            confirmation.classList.remove('hidden');
                            this.reset();
                            loadRegistros(); // Atualiza a lista de registros
                            setTimeout(() => {
                                confirmation.classList.add('hidden');
                            }, 3000);
                        } else {
                            throw new Error(data.message || 'Erro ao cadastrar equipamento');
                        }
                    })
                    .catch(error => {
                        console.error('Erro no cadastro:', error);
                        const errorMsg = document.getElementById('cadastrar-equipamentos-error');
                        errorMsg.textContent = error.message;
                        errorMsg.classList.remove('hidden');
                        setTimeout(() => errorMsg.classList.add('hidden'), 3000);
                    });
                });
            }

            // Formulário de cadastro de espaço
            if (cadastrarEspacoForm) {
                cadastrarEspacoForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Enviando formulário de espaço...');
                    
                    const formData = new FormData(this);
                    // Log dos dados do formulário
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }

                    fetch('../controllers/CadastroController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log('Status da resposta:', response.status);
                        return response.json().then(data => {
                            console.log('Dados da resposta:', data);
                            if (!response.ok) {
                                throw new Error(data.message || 'Erro na resposta do servidor');
                            }
                            return data;
                        });
                    })
                    .then(data => {
                        if (data.success) {
                            const confirmation = document.getElementById('cadastrar-espaco-confirmation');
                            confirmation.classList.remove('hidden');
                            this.reset();
                            loadRegistros(); // Atualiza a lista de registros
                            setTimeout(() => {
                                confirmation.classList.add('hidden');
                            }, 3000);
                        } else {
                            throw new Error(data.message || 'Erro ao cadastrar espaço');
                        }
                    })
                    .catch(error => {
                        console.error('Erro no cadastro de espaço:', error);
                        const errorMsg = document.getElementById('cadastrar-espaco-error');
                        errorMsg.textContent = error.message;
                        errorMsg.classList.remove('hidden');
                        setTimeout(() => errorMsg.classList.add('hidden'), 3000);
                    });
                });
            }
            
            // Formulário de geração de relatório
            if (gerarRelatorioForm) {
                gerarRelatorioForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const tipo = document.getElementById('tipo-relatorio').value;
                    const turmaId = document.getElementById('turma-select').value;
                    const confirmationMsg = document.getElementById('relatorio-confirmation');
                    const errorMsg = document.getElementById('relatorio-error');

                    if (!tipo) {
                        errorMsg.textContent = 'Por favor, selecione o tipo de relatório';
                        errorMsg.classList.remove('hidden');
                        setTimeout(() => errorMsg.classList.add('hidden'), 3000);
                        return;
                    }

                    if (tipo === 'turma' && !turmaId) {
                        errorMsg.textContent = 'Por favor, selecione uma turma';
                        errorMsg.classList.remove('hidden');
                        setTimeout(() => errorMsg.classList.add('hidden'), 3000);
                        return;
                    }

                    // Mostrar mensagem de carregamento
                    confirmationMsg.textContent = 'Gerando relatório...';
                    confirmationMsg.classList.remove('hidden');
                    errorMsg.classList.add('hidden');

                    let url = `../controllers/RelatorioController.php?action=gerar_relatorio&tipo=${tipo}`;
                    if (tipo === 'turma') {
                        url += `&turma_id=${turmaId}`;
                    }

                    // Abrir em nova aba em dispositivos móveis
                    if (window.innerWidth <= 768) {
                        window.open(url, '_blank');
                    } else {
                        window.open(url, '_blank');
                    }

                    // Atualizar mensagem após um breve delay
                    setTimeout(() => {
                        confirmationMsg.textContent = 'Relatório gerado com sucesso!';
                        setTimeout(() => {
                            confirmationMsg.classList.add('hidden');
                        }, 3000);
                    }, 1000);
                });
            }

            // Ao carregar a página, verifica qual aba está ativa e carrega os dados
            const activeSection = document.querySelector('.section.active');
            if (activeSection && activeSection.id === 'registros-realizados-section') {
                loadRegistros();
            } else if (activeSection && activeSection.id === 'assinaturas-section') {
                loadAssinaturas();
            }

            // Inicializar - Chamar showSection que agora está global
            showSection('cadastrar-equipamentos'); 
        });

        // Função para abrir modal de edição
        window.openEditModal = function(tipo, id, nome, descricao, quantidade_disponivel) {
            try {
                // Limpar valores anteriores
                document.getElementById('edit-id').value = '';
                document.getElementById('edit-tipo').value = '';
                document.getElementById('edit-nome').value = '';
                document.getElementById('edit-descricao').value = '';
                document.getElementById('edit-quantidade').value = '';

                // Preencher com novos valores
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-tipo').value = tipo;
                document.getElementById('edit-nome').value = nome;
                document.getElementById('edit-descricao').value = descricao || '';
                document.getElementById('edit-quantidade').value = quantidade_disponivel;

                // Mostrar o modal
                const modal = document.getElementById('edit-modal');
                if (modal) modal.classList.add('active');

                // Focar no primeiro campo
                const editNome = document.getElementById('edit-nome');
                if (editNome) editNome.focus();
            } catch (error) {
                console.error('Erro ao abrir modal de edição:', error);
                alert('Erro ao abrir formulário de edição');
            }
        };

        // Função para fechar modal
        function fecharModal() {
            const modal = document.getElementById('edit-modal');
            if (modal) modal.classList.remove('active');
            
            const editForm = document.getElementById('edit-form');
            if (editForm) editForm.reset();
        }

        // Fechar modal ao clicar fora dele
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('edit-modal');
            // Verifica se o modal existe e está ativo antes de prosseguir
            if (modal && modal.classList.contains('active') && event.target === modal) {
                fecharModal();
            }
        });

        // Fechar modal com a tecla ESC
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('edit-modal');
            // Verifica se o modal existe e está ativo
            if (event.key === 'Escape' && modal && modal.classList.contains('active')) {
                fecharModal();
            }
        });

        // Função para alternar disponibilidade
        window.toggleDisponibilidade = function(tipo, id, disponivel, button) {
            button.disabled = true;
            
            const formData = new FormData();
            formData.append('btn', 'alternar_disponibilidade');
            formData.append('id', id);
            formData.append('tipo', tipo);
            formData.append('disponivel', disponivel ? 0 : 1);

            fetch('../controllers/CadastroController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const novoDisponivel = !disponivel;
                    const card = button.closest('.card');
                    
                    // Atualizar o botão
                    button.textContent = novoDisponivel ? 'Disponível' : 'Indisponível';
                    button.classList.toggle('disabled', !novoDisponivel);
                    button.setAttribute('onclick', `toggleDisponibilidade('${tipo}', ${id}, ${novoDisponivel}, this)`);
                    
                    // Atualizar o badge de status no card
                    const statusBadge = card.querySelector('.status-badge');
                    if (statusBadge) {
                        statusBadge.textContent = novoDisponivel ? 'Disponível' : 'Indisponível';
                        statusBadge.classList.remove(novoDisponivel ? 'unavailable' : 'available');
                        statusBadge.classList.add(novoDisponivel ? 'available' : 'unavailable');
                    }
                    
                    const confirmationMsg = document.getElementById('registros-confirmation');
                    confirmationMsg.textContent = 'Disponibilidade atualizada com sucesso!';
                    confirmationMsg.classList.remove('hidden');
                    setTimeout(() => confirmationMsg.classList.add('hidden'), 3000);
                } else {
                    throw new Error(data.message || 'Erro ao atualizar disponibilidade');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                const errorMsg = document.getElementById('registros-error');
                errorMsg.textContent = error.message || 'Erro ao alterar disponibilidade';
                errorMsg.classList.remove('hidden');
                setTimeout(() => errorMsg.classList.add('hidden'), 3000);
            })
            .finally(() => {
                button.disabled = false;
            });
        };

        // Função para excluir registro
        function excluirRegistro() {
            if (!currentDeleteId || !currentDeleteTipo) return;
            
            const formData = new FormData();
            formData.append('btn', 'excluir_cadastro');
            formData.append('id', currentDeleteId);
            formData.append('tipo', currentDeleteTipo);
            formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
            
            fetch('../controllers/CadastroController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const confirmationMsg = document.getElementById('registros-confirmation');
                    confirmationMsg.textContent = 'Registro excluído com sucesso!';
                    confirmationMsg.classList.remove('hidden');
                    loadRegistros();
                    setTimeout(() => {
                        confirmationMsg.classList.add('hidden');
                    }, 3000);
                } else {
                    throw new Error(data.message || 'Erro ao excluir registro');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                const errorMsg = document.getElementById('registros-error');
                errorMsg.textContent = error.message;
                errorMsg.classList.remove('hidden');
                setTimeout(() => {
                    errorMsg.classList.add('hidden');
                }, 3000);
            })
            .finally(() => {
                hideModal();
            });
        }

        function editarCadastro(id, tipo, nome, descricao, quantidade) {
            const modal = document.getElementById('edit-modal');
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-tipo').value = tipo;
            document.getElementById('edit-nome').value = nome;
            document.getElementById('edit-descricao').value = descricao || '';
            document.getElementById('edit-quantidade').value = quantidade;
            modal.classList.add('active');
        }

        // Adicionar antes do script existente
        let currentDeleteId = null;
        let currentDeleteTipo = null;
        const modal = document.getElementById('confirmationModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const confirmDeleteBtn = document.getElementById('confirmDelete');
        const cancelDeleteBtn = document.getElementById('cancelDelete');

        // Função para mostrar o modal
        function showModal(id, tipo) {
            currentDeleteId = id;
            currentDeleteTipo = tipo;
            document.getElementById('confirmationModal').classList.add('active');
        }

        // Função para esconder o modal
        function hideModal() {
            document.getElementById('confirmationModal').classList.remove('active');
            currentDeleteId = null;
            currentDeleteTipo = null;
        }

        // Event listeners para os botões do modal
        document.getElementById('cancelDelete').addEventListener('click', hideModal);
        document.getElementById('confirmDelete').addEventListener('click', excluirRegistro);

        // Event listener para fechar o modal com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideModal();
                const editModal = document.getElementById('edit-modal');
                if (editModal) editModal.classList.remove('active');
            }
        });

        // Event listener para fechar o modal ao clicar fora dele
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('confirmationModal');
            if (modal && event.target === modal) {
                hideModal();
            }
        });

        function salvarEdicao() {
            const id = document.getElementById('edit-id').value;
            const tipo = document.getElementById('edit-tipo').value;
            const nome = document.getElementById('edit-nome').value.trim();
            const descricao = document.getElementById('edit-descricao').value.trim();
            const quantidade = parseInt(document.getElementById('edit-quantidade').value);

            if (!nome) {
                showToast('O nome é obrigatório', 'error');
                return;
            }
            if (isNaN(quantidade) || quantidade < 0) {
                showToast('A quantidade deve ser um número positivo', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('btn', 'atualizar_cadastro');
            formData.append('id', id);
            formData.append('tipo', tipo);
            formData.append('nome', nome);
            formData.append('descricao', descricao);
            formData.append('quantidade', quantidade);
            formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');

            fetch('../controllers/CadastroController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Cadastro atualizado com sucesso!', 'success');
                    fecharModal();
                    loadRegistros();
                } else {
                    throw new Error(data.message || 'Erro ao atualizar cadastro');
                }
            })
            .catch(error => {
                console.error('Erro ao salvar edição:', error);
                showToast(error.message, 'error');
            });
        }

        // Adicionar função para carregar turmas
        function carregarTurmas() {
            fetch('../controllers/RelatorioController.php?action=listar_turmas')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('turma-select');
                        select.innerHTML = '<option value="">Selecione a turma</option>';
                        // Mapear nome para menor ID
                        const turmaMap = {};
                        data.turmas.forEach(turma => {
                            let nomeCorrigido = turma.nome.replace(/\?\?/g, 'º').replace(/\?\?/g, 'ª');
                            if (!turmaMap[nomeCorrigido] || turma.id < turmaMap[nomeCorrigido].id) {
                                turmaMap[nomeCorrigido] = { id: turma.id, nome: nomeCorrigido };
                            }
                        });
                        Object.values(turmaMap).forEach(turma => {
                            select.innerHTML += `<option value="${turma.id}">${turma.nome}</option>`;
                        });
                    } else {
                        throw new Error(data.message || 'Erro ao carregar turmas');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showToast('Erro ao carregar turmas', 'error');
                });
        }

        // Modificar o event listener do tipo de relatório
        document.getElementById('tipo-relatorio').addEventListener('change', function() {
            const turmaSelectGroup = document.getElementById('turma-select-group');
            if (this.value === 'turma') {
                turmaSelectGroup.style.display = 'block';
                turmaSelectGroup.classList.add('active');
                carregarTurmas();
            } else {
                turmaSelectGroup.classList.remove('active');
                setTimeout(() => {
                    turmaSelectGroup.style.display = 'none';
                }, 300);
            }
        });

        // Mobile navbar animada
        const menuBtn = document.getElementById('mobileMenuBtn');
        const navbar = document.getElementById('mobileNavbar');
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
    </script>
</body>
</html>