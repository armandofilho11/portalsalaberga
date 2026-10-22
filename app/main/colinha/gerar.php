<?php

require("FPDF/fpdf.php");

$n1 = $_POST['n1'] ?? '';
$n2 = $_POST['n2'] ?? '';
$n3 = $_POST['n3'] ?? '';
$n4 = $_POST['n4'] ?? '';
$n5 = $_POST['n5'] ?? '';
$n6 = $_POST['n6'] ?? '';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Candidatos</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: white;
            font-family: Arial, Helvetica, sans-serif;

            display: flex;
            justify-content: center;
            align-items: center;

            min-height: 100vh;
        }

        .container {
            width: 600px;
            padding: 35px 40px;

            background-image: url('x.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            border-radius: 15px;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.30);
        }

        .titulo {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            color: rgb(255, 255, 255);
        }

        .campo {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .campo label {
            width: 200px;

            font-size: 18px;
            font-weight: bold;

            color: rgb(255, 255, 255);
        }

        .campo input {
            flex: 1;

            height: 42px;

            border: 1px solid #999;
            border-radius: 6px;

            padding: 5px 10px;

            font-size: 20px;
            text-align: center;

            background: white;
        }

    </style>

</head>

<body>

    <div class="container">

        <div class="titulo">
            Colinha Eleições 2026
        </div>

        <div class="campo">
            <label>Deputado Federal:</label>
            <input type="text" name="n1" value="<?= htmlspecialchars($n1) ?>">
        </div>

        <div class="campo">
            <label>Deputado Estadual:</label>
            <input type="text" name="n2" value="<?= htmlspecialchars($n2) ?>">
        </div>

        <div class="campo">
            <label>Senador 1:</label>
            <input type="text" name="n3" value="<?= htmlspecialchars($n3) ?>">
        </div>

        <div class="campo">
            <label>Senador 2:</label>
            <input type="text" name="n4" value="<?= htmlspecialchars($n4) ?>">
        </div>

        <div class="campo">
            <label>Governador:</label>
            <input type="text" name="n5" value="<?= htmlspecialchars($n5) ?>">
        </div>

        <div class="campo">
            <label>Presidente:</label>
            <input type="text" name="n6" value="<?= htmlspecialchars($n6) ?>">
        </div>

    </div>

</body>
</html>