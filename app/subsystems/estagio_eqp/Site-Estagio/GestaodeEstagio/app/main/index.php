<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecione seu Perfil</title>
    <link rel="icon" type="image/x-icon" href="views/logo_Salaberga-removebg-preview.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F5F5F5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .btn-primary {
            background: linear-gradient(45deg, #005A24, #FF8C00);
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            border-radius: 16px;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #FF8C00, #005A24);
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .card-container {
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            border-radius: 20px;
            overflow: hidden;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md flex flex-col items-center justify-center px-8 py-10 bg-white card-container">
        <!-- Logo -->
        <div class="w-full flex flex-col items-center mb-8">
            <img src="config/img/nome_Salaberga2.jpg" alt="Logo" class="mb-6 rounded-2xl shadow-md w-40 h-auto transform hover:scale-105 transition-transform duration-300">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Bem-vindo ao Sistema de Estágio!</h2>
            <p class="text-gray-600 text-base text-center">Escolha como realizar o login!</p>
        </div>

        <!-- Buttons Section -->
        <div class="w-full flex flex-col space-y-4">
            <form method="POST" action="controllers/Controller_Index.php" class="space-y-4">
                <input type="hidden" name="userType" value="student">
                <input type="submit" name="btn" value="Aluno" 
                    class="w-full btn-primary text-white py-4 px-6 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#005A24] transition-all duration-300 font-semibold text-lg">
                
                <input type="hidden" name="userType" value="teacher">
                <input type="submit" name="btn" value="Professor" 
                    class="w-full btn-primary text-white py-4 px-6 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#005A24] transition-all duration-300 font-semibold text-lg">
            </form>
        </div>
    </div>
</body>

</html>