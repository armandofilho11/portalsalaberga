<?php
session_start();

// Verificar se já está logado
if (isset($_SESSION['aluno_id'])) {
    header("Location: index.php");
    exit();
}

$mensagem = '';
$tipoMensagem = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula = $_POST['matricula'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Aqui você deve implementar a validação real com o banco de dados
    // Por enquanto, vamos usar um exemplo simples
    if ($matricula && $senha) {
        $_SESSION['aluno_id'] = $matricula;
        $_SESSION['aluno_nome'] = "Aluno " . $matricula; // Substitua pelo nome real do aluno
        header("Location: index.php");
        exit();
    } else {
        $mensagem = 'Matrícula ou senha inválidos';
        $tipoMensagem = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Votação Grêmio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#e6f2ec', 100: '#cce5d9', 200: '#99cbb3', 300: '#66b18d',
                            400: '#339766', 500: '#007d40', 600: '#006a36', 700: '#005A24',
                            800: '#004d1f', 900: '#00401a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-primary-700 text-white shadow-lg">
            <div class="container mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">Votação do Grêmio</h1>
                        <p class="text-primary-200 mt-1">Escola Estadual</p>
                    </div>
                    <a href="index.php" class="text-white hover:text-primary-200 transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow container mx-auto px-4 py-8">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8 animate-fade-in">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center p-4 bg-primary-100 rounded-full mb-4">
                        <i class="fas fa-user-lock text-4xl text-primary-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                    <p class="text-gray-600 mt-2">Entre com suas credenciais para votar</p>
                </div>

                <?php if ($mensagem): ?>
                    <div class="mb-6 p-4 rounded-lg <?php echo $tipoMensagem === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                        <?php echo htmlspecialchars($mensagem); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="matricula" class="block text-sm font-medium text-gray-700 mb-1">Matrícula</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" id="matricula" name="matricula" required
                                   class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="Digite sua matrícula">
                        </div>
                    </div>

                    <div>
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="senha" name="senha" required
                                   class="w-full pl-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                   placeholder="Digite sua senha">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Entrar
                    </button>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; <?php echo date('Y'); ?> Escola. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>
</body>
</html> 