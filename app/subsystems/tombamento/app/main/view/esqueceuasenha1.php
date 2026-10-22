<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #ff8c00, #176b24);
            font-family: Arial, sans-serif;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        h2 {
            color: #176b24;
            font-size: 24px;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .input-box {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            align-items: center;
            background: #f2f2f2;
            overflow: hidden;
        }
        .input-box input {
            border: none;
            background: transparent;
            width: 100%;
            outline: none;
            padding-left: 10px;
            font-size: 16px;
        }
        .input-box img {
            width: 20px;
            margin: 0 10px;
        }
        .button {
            background: linear-gradient(to right, #176b24, #ff8c00);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
        }
        .button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Esqueceu sua senha?</h2>
        <p>Insira seu e-mail institucional no campo abaixo. Você receberá um e-mail com um link onde poderá definir uma nova senha com segurança.</p>
        <div class="input-box">
            <img src="https://cdn-icons-png.flaticon.com/512/561/561127.png" alt="Email Icon">
            <input type="email" placeholder="Seu e-mail">
        </div>
        <button class="button">Receber e-mail</button>
    </div>
</body>
</html>
