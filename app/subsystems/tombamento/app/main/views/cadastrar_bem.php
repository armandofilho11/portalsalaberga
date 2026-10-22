<?php
include '../includes/conexao.php';
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <script src="../assets/js/validacoes.js"></script>
    <script src="../assets/js/script.js" defer></script>
    <meta charset="UTF-8">
    <title>Cadastrar Bem</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        :root {
            --shadow-color: rgba(0, 0, 0, 0.3);
            --primary-color: #005A24;
            --secondary-color: #FF8C00;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-family: 'Poppins', sans-serif;
            background-color: #F5F5F5;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .retangulo {
            position: relative;
            width: 500px;
            height: 710px;
            background-color: #F5F5F5;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin-top: 150px;
            box-shadow: 0 4px 20px var(--shadow-color);
        }

        .form {
            width: 390px;
            border: none;
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-gradiente {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-family: 'Poppins', sans-serif;
            transition: background 0.3s ease, transform 0.2s, box-shadow 0.3s;
        }

        .btn-gradiente:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-gradiente:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(255, 140, 0, 0.5);
        }

        .btn-gradiente:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }

        h2 {
            color: #007A33;
            font-weight: 700;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }

        .form input,
        .form select,
        .form textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form label {
            display: flex;
            margin-bottom: 10px;
        }

        .Texto-maior {
            position: absolute;
            top: 60px;
            left: 30px;
            font-weight: bold;
            background: var(--primary-color);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-top: -20px;
            font-size: 40px;
            margin-right: 70px;
        }

        p {
            position: absolute;
            top: 130px;
            left: 30px;
            font-weight: 500;
            margin-top: -20px;
            font-size: 20px;
            margin-right: 70px;
            font-family: 'Poppins', sans-serif;
        }

        .Texto-menor {
            position: absolute;
            top: 100px;
            left: 34px;
            font-weight: 700;
            background: var(--secondary-color);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-top: -20px;
            font-size: 40px;
            margin-right: 70px;
            font-family: 'Poppins', sans-serif;
        }

        .seta-voltar {
            position: absolute;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            left: 20px;
            top: 15px;
        }

        /* Mobile Devices (up to 576px) */
        @media (max-width: 576px) {
            body {
                padding: 10px;
                margin-top: 0;
                align-items: flex-start;
            }

            .retangulo {
                width: 100%;
                max-width: 400px;
                height: auto;
                margin-top: 60px;
                padding: 15px;
                min-height: 400px;
                box-shadow: 0 2px 10px var(--shadow-color);
            }

            .form {
                width: 100%;
                padding: 0 5px;
                gap: 5px;
            }

            .Texto-maior {
                position: static;
                text-align: center;
                font-size: 24px;
                margin: 10px 0 5px;
            }

            .Texto-menor {
                position: static;
                text-align: center;
                font-size: 24px;
                margin: 0 0 10px;
            }

            p {
                position: static;
                text-align: center;
                font-size: 14px;
                margin: 10px 0;
            }

            .seta-voltar {
                left: 10px;
                top: 10px;
                font-size: 20px;
            }

            .btn-gradiente {
                font-size: 14px;
                padding: 8px;
            }

            .form input,
            .form select,
            .form textarea {
                font-size: 13px;
                padding: 6px;
                margin-bottom: 8px;
            }

            h2 {
                font-size: 20px;
                margin-bottom: 10px;
            }

            .position-absolute.top-50.start-50.translate-middle {
                position: static;
                transform: none;
                width: 100%;
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>
<a href="../includes/menu.php" class="seta-voltar">
    <i class="bi bi-arrow-left"></i>
</a>
<div class="Texto-maior">
    <div class="position-relativeX">Cadastro de</div> 
</div>
<div class="Texto-menor">
    <div class="fs-3">itens</div> 
</div>

<p>Aqui você pode cadastrar seus bens.</p>
<div class="retangulo">
    <div class="position-absolute top-50 start-50 translate-middle"> 
        <h2>Cadastrar Novo Bem</h2>
        <form action="../controllers/BemController.php" method="POST" style="max-width: 500px;" onsubmit="return validarFormularioBem();">
            <input type="hidden" name="cadastrar" value="1">

            <div class="form">
                <div class="bnt">
                    <input type="text" name="nome" required placeholder="Nome do bem">
                </div>
                <label></label>
                <input type="text" name="numero_tombamento" placeholder="Número de Tombamento">

                <label></label>
                <input type="number" name="ano_aquisicao" placeholder="Ano de Aquisição">

                <label></label>
                <select name="estado_conservacao" required>
                    <option value="" disabled selected>Estado de Conservação</option>
                    <option value="Ótimo">Ótimo</option>
                    <option value="Bom">Bom</option>
                    <option value="Ruim">Ruim</option>
                    <option value="Inutilizável">Inutilizável</option>
                </select>

                <label></label>
                <input type="number" step="0.01" name="valor" placeholder="Valor">

                <label></label>
                <textarea name="observacoes" placeholder="Observações"></textarea>

                <label></label>
                <select name="setor_id" required>
                    <option value="" disabled selected>Selecione o Setor</option>
                    <?php
                    $stmt = $pdo->query("SELECT id_setor, nome FROM Setor");
                    if ($stmt->rowCount() == 0) {
                        echo "<option value='' disabled>Nenhum setor cadastrado</option>";
                    } else {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id_setor']}'>{$row['nome']}</option>";
                        }
                    }
                    ?>
                </select>

                <input type="hidden" name="id_usuario" value="<?= isset($_SESSION['usuario']['id_usuario']) ? $_SESSION['usuario']['id_usuario'] : '' ?>">
            </div>
            <div class="btn">
                <button class="btn btn-gradiente" type="submit">Cadastrar</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>