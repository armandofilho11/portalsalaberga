<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../config/img/logo_Salaberga-removebg-preview.png" type="image/x-icon">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        'ceara-moss': '#2d4739',
                        primary: '#008C45',
                        secondary: '#FFA500',
                    }
                }
            }
        }
    </script>
    <style>
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition: none !important;
            }
        }

        @media (prefers-contrast: high) {
            :root {
                --text-color: #000;
                --border-color: #000;
            }
        }

        *:focus {
            outline: 3px solid #FFA500;
            outline-offset: 2px;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #f3f4f6;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: #FFFFFF;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            backdrop-filter: blur(4px);
        }
        .back-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .school-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .header-moss {
            background: #2d4739;
        }
        .header-moss * {
            color: #fff !important;
        }

        .main-container {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            margin: 0 auto;
            max-width: 1200px;
            padding: 2rem;
        }

        .gradient-button {
            background: linear-gradient(to right, #FFA500, #008C45);
            transition: all 0.3s ease;
        }
        .gradient-button:hover {
            background: linear-gradient(to right, #008C45, #FFA500);
            transform: scale(1.05);
        }

        .fade-in {
            animation: fadeIn 1s ease-out forwards;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .main-container {
                padding: 1rem;
            }
        }
        @media (max-width: 600px) {
            .main-container {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body class="min-h-screen font-['Roboto'] select-none">
    <!-- Cabeçalho verde musgo -->
    <header class="header-moss w-full shadow-lg mb-8">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <!-- Left section with back button, logo and title -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="javascript:history.back()" class="back-button">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <img src="../config/img/logo_Salaberga-removebg-preview.png" alt="Logo EEEP Salaberga" class="school-logo">
                    <h1 class="text-xl md:text-2xl font-bold mb-0">Detalhes do Aluno</h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container mx-auto px-4 py-4 md:py-8 fade-in">
        <div class="main-container">
            <?php
            require("../models/model-function.php");
            $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
            
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $consulta = 'SELECT * FROM aluno WHERE id = :id';
                $query = $pdo->prepare($consulta);
                $query->bindValue(':id', $id);
                $query->execute();
                
                if ($query->rowCount() > 0) {
                    $aluno = $query->fetch();
                    ?>
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Foto e Informações Básicas -->
                        <div class="md:w-1/3">
                            <div class="flex flex-col items-center">
                                <img class="h-32 w-32 md:h-48 md:w-48 rounded-full mb-4" 
                                     src="https://ui-avatars.com/api/?name=<?php echo urlencode($aluno['nome']); ?>" 
                                     alt="Foto do aluno <?php echo htmlspecialchars($aluno['nome']); ?>">
                                <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($aluno['nome']); ?></h1>
                                <p class="text-gray-600"><?php echo htmlspecialchars($aluno['curso']); ?></p>
                            </div>
                        </div>

                        <!-- Informações Detalhadas -->
                        <div class="md:w-2/3">
                            <div class="space-y-6">
                                <!-- Informações Pessoais -->
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informações Pessoais</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Matrícula</label>
                                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($aluno['matricula']); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Contato</label>
                                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($aluno['contato']); ?></p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">E-mail</label>
                                            <p class="mt-1 text-gray-900">
                                                <a href="mailto:<?php echo htmlspecialchars($aluno['email']); ?>" 
                                                   class="text-ceara-green hover:text-ceara-moss transition-colors duration-300">
                                                    <?php echo htmlspecialchars($aluno['email']); ?>
                                                </a>
                                            </p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Endereço</label>
                                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars($aluno['endereco']); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botões de Ação -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <form action="../controllers/Controller-botao_acao.php" method="GET" class="w-full sm:w-auto">
                                        <input type="hidden" name="btn-editar" value="<?php echo htmlspecialchars($aluno['id']); ?>">
                                        <button type="submit" 
                                                class="w-full gradient-button text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2"
                                                aria-label="Editar informações do aluno">
                                            <i class="fas fa-edit"></i>
                                            Editar Aluno
                                        </button>
                                    </form>
                                    <a href="processoseletivo_aluno.php" 
                                       class="w-full sm:w-auto gradient-button text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2"
                                       aria-label="Acessar processo seletivo">
                                        <i class="fas fa-clipboard-list"></i>
                                        Processo Seletivo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "<p class='text-center text-gray-600'>Aluno não encontrado.</p>";
                }
            } else {
                echo "<p class='text-center text-gray-600'>ID do aluno não fornecido.</p>";
            }
            ?>
        </div>
    </main>

    <script>
    let horasTotais = 0;
    const totalHorasNecessarias = 400;

    function calcularHoras(inicio, fim) {
        const [horaInicio, minutoInicio] = inicio.split(':').map(Number);
        const [horaFim, minutoFim] = fim.split(':').map(Number);
        
        let horas = horaFim - horaInicio;
        let minutos = minutoFim - minutoInicio;
        
        if (minutos < 0) {
            horas--;
            minutos += 60;
        }
        
        return horas + (minutos / 60);
    }

    function atualizarBarraProgresso() {
        const porcentagem = (horasTotais / totalHorasNecessarias) * 100;
        document.querySelector('.bg-gradient-to-r').style.width = `${porcentagem}%`;
        document.querySelector('.text-sm.font-medium.text-gray-700:last-child').textContent = 
            `${horasTotais.toFixed(1)}/${totalHorasNecessarias} horas`;
    }

    function adicionarHoras() {
        const inicio = document.getElementById('hora_inicio').value;
        const fim = document.getElementById('hora_fim').value;
        
        if (!inicio || !fim) {
            alert('Por favor, preencha os horários de início e término');
            return;
        }
        
        const horas = calcularHoras(inicio, fim);
        
        if (horas <= 0) {
            alert('O horário de término deve ser maior que o horário de início');
            return;
        }
        
        if (horasTotais + horas > totalHorasNecessarias) {
            alert('O total de horas não pode exceder 400 horas');
            return;
        }
        
        horasTotais += horas;
        atualizarBarraProgresso();
        
        // Limpa os campos
        document.getElementById('hora_inicio').value = '';
        document.getElementById('hora_fim').value = '';
    }

    function removerHoras() {
        const inicio = document.getElementById('hora_inicio').value;
        const fim = document.getElementById('hora_fim').value;
        
        if (!inicio || !fim) {
            alert('Por favor, preencha os horários de início e término');
            return;
        }
        
        const horas = calcularHoras(inicio, fim);
        
        if (horas <= 0) {
            alert('O horário de término deve ser maior que o horário de início');
            return;
        }
        
        if (horasTotais - horas < 0) {
            alert('Não é possível remover mais horas do que já foram registradas');
            return;
        }
        
        horasTotais -= horas;
        atualizarBarraProgresso();
        
        // Limpa os campos
        document.getElementById('hora_inicio').value = '';
        document.getElementById('hora_fim').value = '';
    }
    </script>
</body>
</html> 