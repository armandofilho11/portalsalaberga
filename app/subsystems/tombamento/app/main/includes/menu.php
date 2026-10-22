<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão Patrimonial</title>

    <!-- Bootstrap CSS e ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #005A24;
            --secondary-color: #FFB74D;
            --text-color: #506B50;
            --background-color: #F5F5F5;
            --shadow-color: rgba(0, 0, 0, 0.2);
        }

        body {
            padding-top: 70px;
            background-color: var(--background-color);
            padding-bottom: 100px;
            font-family: 'Poppins', sans-serif;
        }

        .navbar-gradient {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-color));
            box-shadow: 1px 7px 9px var(--shadow-color);
            padding: 15px 0;
        }

        .navbar-brand, .btn, .dropdown-toggle {
            color: white !important;
            font-weight: 600;
            font-size: 16px;
        }

        .btn:hover {
            color: var(--secondary-color) !important;
            transform: scale(1.05);
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .Texto-container {
            padding: 50px 70px 0;
            text-align: left; /* Alinhamento à esquerda em telas maiores */
        }

        .text-menor {
            font-weight: 900;
            color: rgb(9, 107, 46);
            font-size: 40px;
        }
        .Texto-maior .fs-3 {
            font-weight: 900;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 25px;
            margin-top: -20px;
            padding-left: 70px; /* Matches the .Texto-container padding for alignment */
            margin-left: -67px; /* Ensures no additional offset */
        }
        p {
            padding-left: 70px;
            color: rgb(80, 80, 80);
            font-weight: 700;
        }

        .texto-meio {
            text-align: center;
            font-size: 35px;
            margin-top: 80px;
            color: var(--secondary-color);
            font-weight: 500;
        }

        .texto-desc {
            text-align: center;
            font-size: 15px;
            color: #007A33;
        }

        .container-botoes {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 80px;
            margin-top: 100px;
        }

        .botao {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 120px;
            background-color: #D7D7D7;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 1px 4px 6px var(--shadow-color);
            transition: background-color 0.3s, transform 0.2s;
            text-decoration: none;
        }

        .botao:hover {
            background-color: #e0e0e0;
            transform: scale(1.05);
        }

        .botao i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .botao span {
            font-size: 14px;
            color: rgb(58, 58, 58);
        }

        .logo img {
            width: 120px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .Texto-container {
                padding: 20px;
                text-align: center; /* Centraliza o texto em telas menores */
            }

            .text-menor {
                font-size: 28px;
            }

            .Texto-maior .fs-3 {
                font-size: 20px;
                padding-left: 0; /* Remove o padding para centralizar */
                text-align: center; /* Centraliza o texto "gestão patrimonial" */
            }

            p {
                padding-left: 20px;
                text-align: center;
            }

            .texto-meio {
                font-size: 28px;
                margin-top: 40px;
                padding-left: 0;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }

            .texto-desc {
                font-size: 14px;
                padding-left: 0;
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }

            .container-botoes {
                flex-direction: column;
                align-items: center; /* Centraliza horizontalmente */
                justify-content: center; /* Centraliza verticalmente */
                gap: 20px;
                padding: 20px; /* Espaço nas bordas */
                /* min-height: 100vh; */ /* Descomente se quiser centralização vertical total */
            }

            .botao {
                width: 160px;
                height: 100px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .botao i {
                font-size: 22px;
            }

            .botao span {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-gradient fixed-top">
        <div class="container-fluid">
            <div class="logo">
                <img src="../img/logo gp.png" alt="Logo Gestão Patrimonial">
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a href="../views/setores.php" class="btn"><i class="bi bi-houses-fill"></i>  Cadastro de setor</a>
                    <a href="../views/lixeira.php" class="btn"><i class="bi bi-trash-fill"></i> Lixeira</a>
                    <a href="../views/sobre.php" class="btn"><i class="bi bi-info-circle"></i> Sobre</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo principal -->
    <div class="Texto-container">
        <div class="text-menor">Sistema de</div>
        <div class="Texto-maior">
            <div class="fs-3">gestão patrimonial</div> 
        </div>
    </div>

    <p>Simplifique a gestão dos seus ativos com eficiência e praticidade. <br>
        Nosso sistema oferece soluções completas para organizar,<br>
        acompanhar e otimizar a administração patrimonial, tudo ao seu alcance.</p>

    <div id="funcionalidades" class="texto-meio">Principais Funcionalidades</div>
    <div class="texto-desc">Administre seus bens da melhor forma!</div>

    <div class="container-botoes">
        <a href="../views/cadastrar_bem.php" class="botao">
            <i class="bi bi-plus text-dark"></i>
            <span>Adicionar</span>
        </a>
        <a href="../views/editar_bem.php" class="botao">
            <i class="bi bi-pencil text-dark"></i>
            <span>Editar</span>
        </a>
        <a href="../views/Excluir.php" class="botao">
            <i class="bi bi-trash text-dark"></i>
            <span>Excluir</span>
        </a>
        <a href="../views/relatorios.php" class="botao">
            <i class="bi bi-bar-chart text-dark"></i>
            <span>Gerar relatório</span>
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>