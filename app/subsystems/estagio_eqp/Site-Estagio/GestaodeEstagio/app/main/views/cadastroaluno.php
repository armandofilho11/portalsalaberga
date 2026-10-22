<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aluno</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../config/img/logo_Salaberga-removebg-preview.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F5F5F5;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group input {
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
        }

        .input-group label {
            transition: all 0.3s ease;
        }

        .error-message {
            display: none;
            color: #ff4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .input-group.error .error-message {
            display: block;
        }

        .input-group.error input {
            border-color: #ff4444;
        }

        .input-group.error label {
            color: #ff4444;
        }

        .help-text {
            font-size: 0.8rem;
            color: #6B7280;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl flex bg-white rounded-3xl shadow-xl overflow-hidden">
        <!-- Right Side: Registration Form -->
        <div class="w-full p-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 mb-4">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Cadastro de Aluno</h2>
                <p class="text-gray-600">Preencha os dados para começar</p>
            </div>

            <form action="../controllers/Controller-Cadastros.php" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="input-group">
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                        <input type="text" id="nome" name="nome" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite seu nome completo">
                        <span class="error-message" id="nomeError">Por favor, insira um nome válido</span>
                    </div>

                    <div class="input-group">
                        <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Matrícula</label>
                        <input type="text" id="matricula" name="matricula" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite sua matrícula">
                        <span class="error-message" id="matriculaError">Por favor, insira uma matrícula válida</span>
                    </div>

                    <div class="input-group">
                        <label for="contato" class="block text-sm font-medium text-gray-700 mb-1">Contato</label>
                        <input type="tel" id="contato" name="contato" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite seu contato">
                        <span class="help-text">Digite apenas números (DDD + número)</span>
                        <span class="error-message" id="contatoError">Por favor, insira um número válido</span>
                    </div>

                    <div class="input-group">
                        <label for="curso" class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                        <input type="text" id="curso" name="curso" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite seu curso">
                        <span class="error-message" id="cursoError">Por favor, insira um curso válido</span>
                    </div>

                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail Institucional</label>
                        <input type="email" id="email" name="email" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite seu email">
                        <span class="error-message" id="emailError">Por favor, insira um e-mail válido</span>
                    </div>

                    <div class="input-group">
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                        <input type="text" id="endereco" name="endereco" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite seu endereço">
                        <span class="error-message" id="enderecoError">Por favor, insira um endereço válido</span>
                    </div>

                    <div class="input-group md:col-span-2">
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                        <input type="password" id="senha" name="senha" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite sua senha">
                        <span class="help-text">A senha deve ter entre 6 e 20 caracteres</span>
                        <span class="error-message" id="senhaError">Por favor, insira uma senha válida</span>
                    </div>
                </div>

                <input type="submit" name="btn" value="Cadastrar Aluno"
                    class="w-full bg-[#005A24] hover:bg-[#004A1D] text-white py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#005A24] transition-all duration-300 font-semibold text-lg">
            </form>

            <div class="text-center mt-4">
                <p class="text-sm font-medium text-gray-700">Já tem uma conta? <a href="Login_aluno.php" class="text-sm text-green-600 hover:text-green-700">Fazer login</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const inputs = form.querySelectorAll('input[required]');

                inputs.forEach(input => {
                    if (!input.value) {
                        input.parentElement.classList.add('error');
                        document.getElementById(input.id + 'Error').textContent = 'Por favor, preencha este campo';
                        isValid = false;
                    } else {
                        input.parentElement.classList.remove('error');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Clear errors on input
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    this.parentElement.classList.remove('error');
                });
            });
        });
    </script>
</body>
</html>