<?php
if (!isset($dados_aluno) || empty($dados_aluno)) {
    header('Location: perfildoaluno.php?error=dados_nao_encontrados');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno</title>
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
        <!-- Right Side: Edit Form -->
        <div class="w-full p-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 mb-4">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Editar Aluno</h2>
                <p class="text-gray-600">Atualize os dados do aluno</p>
            </div>

            <form action="../controllers/Controller-Edicoes.php" method="POST" class="space-y-4" autocomplete="off" id="alunoForm">
                <input type="hidden" name="tipo" value="aluno">
                <input type="hidden" name="id" value="<?php echo $dados_aluno['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="input-group">
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                        <input type="text" id="nome" name="nome" 
                               value="<?php echo htmlspecialchars($dados_aluno['nome']) ?>"
                               class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                               placeholder="Digite o nome completo" 
                               required
                               minlength="3"
                               maxlength="100">
                        <span class="error-message" id="nomeError">Por favor, insira um nome válido</span>
                    </div>

                    <div class="input-group">
                        <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Matrícula</label>
                        <input type="text" id="matricula" name="matricula" 
                               value="<?php echo htmlspecialchars($dados_aluno['matricula']) ?>"
                               class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                               placeholder="Digite a matrícula" 
                               required>
                        <span class="error-message" id="matriculaError">Por favor, insira uma matrícula válida</span>
                    </div>

                    <div class="input-group">
                        <label for="contato" class="block text-sm font-medium text-gray-700 mb-1">Contato</label>
                        <input type="tel" id="contato" name="contato" 
                               value="<?php echo htmlspecialchars($dados_aluno['contato']) ?>"
                               class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                               placeholder="Digite o contato" 
                               required>
                        <span class="help-text">Digite apenas números (DDD + número)</span>
                        <span class="error-message" id="contatoError">Por favor, insira um número válido</span>
                    </div>

                    <div class="input-group">
                        <label for="curso" class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                        <input type="text" id="curso" name="curso" 
                               value="<?php echo htmlspecialchars($dados_aluno['curso']) ?>"
                               class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                               placeholder="Digite o curso" 
                               required>
                        <span class="error-message" id="cursoError">Por favor, insira um curso válido</span>
                    </div>

                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail Institucional</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo htmlspecialchars($dados_aluno['email']) ?>"
                               class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                               placeholder="Digite o email" 
                               required>
                        <span class="error-message" id="emailError">Por favor, insira um e-mail válido</span>
                    </div>

                    <div class="input-group">
                        <label for="endereco" class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                        <input type="text" id="endereco" name="endereco" 
                               value="<?php echo htmlspecialchars($dados_aluno['endereco']) ?>"
                               class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                               placeholder="Digite o endereço" 
                               required>
                        <span class="error-message" id="enderecoError">Por favor, insira um endereço válido</span>
                    </div>
                </div>

                <input type="submit" value="Salvar Alterações"
                    class="w-full bg-[#005A24] hover:bg-[#004A1D] text-white py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#005A24] transition-all duration-300 font-semibold text-lg">
            </form>
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