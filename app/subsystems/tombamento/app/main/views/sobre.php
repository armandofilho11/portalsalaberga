<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Gestão Patrimonial EEEP Salaberga</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #005A24;
            --secondary-color: #FF8C00;
            --text-color: #2D2D2D;
            --background-color: #F5F5F5;
            --card-bg: #FFFFFF;
            --shadow-color: rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 20px var(--shadow-color);
            padding: 40px;
            margin: 50px auto;
            animation: fadeIn 1s ease-in;
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 32px;
            margin-bottom: 20px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .intro p {
            font-size: 18px;
            line-height: 1.6;
            text-align: center;
            max-width: 700px;
            margin: 0 auto 40px;
            color: var(--text-color);
            animation: fadeInUp 1s ease-in 0.2s both;
        }

        .team {
            margin-top: 40px;
        }

        .team h2 {
            text-align: center;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 30px;
            animation: fadeInUp 1s ease-in 0.4s both;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        .team-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 2px 10px var(--shadow-color);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 1s ease-in 0.6s both;
        }

        .team-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .team-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        .team-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .team-card p {
            font-size: 14px;
            color: var(--text-color);
            line-height: 1.5;
        }

        .btn-container {
            text-align: center;
            margin-top: 40px;
            animation: fadeInUp 1s ease-in 0.8s both;
        }

        .btn-gradiente {
            display: inline-block;
            padding: 12px 40px;
            background: linear-gradient(45deg, var(--primary-color), #007A33);
            color: white;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease, border 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-gradiente:hover {
            background: linear-gradient(45deg, var(--secondary-color), #FFB347);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border: 2px solid var(--primary-color);
        }

        .btn-gradiente:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(255, 140, 0, 0.5);
        }

        .btn-gradiente:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-gradiente i {
            margin-right: 8px;
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 30px auto;
            }

            h1 {
                font-size: 28px;
            }

            .intro p {
                font-size: 16px;
            }

            .team h2 {
                font-size: 20px;
            }

            .team-card {
                padding: 15px;
            }

            .team-card img {
                width: 80px;
                height: 80px;
            }

            .team-card h3 {
                font-size: 16px;
            }

            .team-card p {
                font-size: 13px;
            }

            .btn-gradiente {
                padding: 10px 30px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .team-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sobre a Gestão Patrimonial</h1>
        <div class="intro">
            <p>
                A <strong>Gestão Patrimonial EEEP Salaberga</strong> é uma ferramenta web desenvolvida para otimizar a administração de bens e setores da escola. Com foco em controle financeiro e eficiência de tempo, nossa plataforma permite o cadastro, edição e monitoramento de bens, além da organização de setores, tudo em uma interface intuitiva e moderna. Transformamos processos manuais em soluções digitais, apoiando a gestão escolar com relatórios detalhados e decisões estratégicas.
            </p>
        </div>

        <div class="team">
            <h2>Nossa Equipe</h2>
            <div class="team-grid">
                <div class="team-card">
                    
                    <h3>Letycia Santos</h3>
                    <p>Estudante do 3º ano de Informática B, responsável pelo design da interface e validações front-end.</p>
                </div>
                <div class="team-card">

                    <h3>Paulo Vitor</h3>
                    <p>Estudante do 3º ano de Informática B, focado no desenvolvimento back-end e integração com banco de dados.</p>
                </div>
                <div class="team-card">
                    
                    <h3>Carlos Eduardo Holanda</h3>
                    <p>Estudante do 3º ano de Informática B, contribuiu com a criação de banco de dados</p>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="../includes/menu.php" class="btn-gradiente"><i class="bi bi-arrow-right"></i> Começar</a>
        </div>
    </div>
</body>
</html>