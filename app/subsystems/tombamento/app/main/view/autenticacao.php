<?php
require("../model/modelaluno.php");
session_start();

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
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

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
            position: fixed;
            z-index: 1;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .login-title {
            color: #2e7d32;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .role-selector {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .role-option input {
            margin-right: 5px;
        }

        .input-container {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            background-color: #f5f5f5;
        }

        .icon {
            margin-right: 10px;
        }

        .input-container input {
            border: none;
            outline: none;
            background: transparent;
            flex-grow: 1;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            color: #388e3c;
            text-decoration: none;
            font-size: 14px;
        }

        input[type="submit"] {
            background: linear-gradient(to right, #43a047, #fb8c00);
            border: none;
            padding: 10px;
            color: white;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right, #388e3c, #f57c00);
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="login-container">
        <h2 class="login-title">Login</h2>
        <form action="../control/controleautenticar.php" method="post">
            <div class="role-selector">
                <label class="role-option">
                    <input type="radio" name="role" value="aluno" id="aluno-radio"> Aluno
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="professor" id="professor-radio"> Professor
                </label>
            </div>

            <!-- Campo Matrícula -->
            <div class="input-container hidden" id="matricula-container">
                <span class="icon">🎓</span>
                <input type="text" id="matricula" placeholder="Matrícula" name="matricula">
            </div>

            <!-- Campos E-mail e Senha -->
            <div class="input-container hidden" id="email-container">
                <span class="icon">📧</span>
                <input type="email" id="email" placeholder="E-mail institucional" name="email">
            </div>

            <div class="input-container hidden" id="password-container">
                <span class="icon">🔒</span>
                <input type="password" id="password" placeholder="Senha" name="senha">
            </div>

            <input type="submit" value="Entrar">
        
        </form>
    </div>
        
    <script>
        const alunoRadio = document.getElementById("aluno-radio");
        const professorRadio = document.getElementById("professor-radio");
        const loginForm = document.querySelector("form");

        const matriculaContainer = document.getElementById("matricula-container");
        const emailContainer = document.getElementById("email-container");
        const passwordContainer = document.getElementById("password-container");

        const matriculaInput = document.getElementById("matricula");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");

        alunoRadio.addEventListener("change", () => {
            if (alunoRadio.checked) {
                matriculaContainer.classList.remove("hidden");
                emailContainer.classList.add("hidden");
                passwordContainer.classList.add("hidden");
                
                // Reset professor fields
                emailInput.value = "";
                passwordInput.value = "";
                
                // Make matrícula required and others not
                matriculaInput.required = true;
                emailInput.required = false;
                passwordInput.required = false;
            }
        });

        professorRadio.addEventListener("change", () => {
            if (professorRadio.checked) {
                matriculaContainer.classList.add("hidden");
                emailContainer.classList.remove("hidden");
                passwordContainer.classList.remove("hidden");
                
                // Reset aluno field
                matriculaInput.value = "";
                
                // Make email and password required and matrícula not
                matriculaInput.required = false;
                emailInput.required = true;
                passwordInput.required = true;
            }
        });

        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            
            if (!alunoRadio.checked && !professorRadio.checked) {
                alert("Por favor, selecione se você é Aluno ou Professor.");
                return;
            }

            if (alunoRadio.checked && !matriculaInput.value) {
                alert("Por favor, insira sua matrícula.");
                return;
            }

            if (professorRadio.checked && (!emailInput.value || !passwordInput.value)) {
                alert("Por favor, preencha email e senha.");
                return;
            }

            loginForm.submit();
        });
    </script>
</body>
</html>
