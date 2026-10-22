<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Adiciona verificação para redirecionar se a variável de equipe não estiver na sessão
if (!isset($_SESSION['equipe'])) {
    error_log("Debug: SESSION['equipe'] is NOT set. Redirecting to login_parcial.php");
    header('Location: ../autenticacao/login_parcial.php');
    exit();
}

error_log("Debug: form_parcial.php loaded.");

// Check if session is active
if (session_status() == PHP_SESSION_ACTIVE) {
    error_log("Debug: Session is active.");
} else {
    error_log("Debug: Session is NOT active.");
}

// Check for equipe in session
if (isset($_SESSION['equipe'])) {
    error_log("Debug: SESSION['equipe'] is set. Value: " . $_SESSION['equipe']);
} else {
    error_log("Debug: SESSION['equipe'] is NOT set.");
}

require_once(__DIR__ . '/../../models/model_dados.php');
require_once(__DIR__ . '/../../models/sessions.php');

// Define o nome da equipe inicialmente com base no ID ou um placeholder
$id_equipe_logada = $_SESSION['equipe'] ?? null;

// Mapeamento de IDs de equipe para nomes
$nomes_equipes = [
    1 => 'Entrada e saída de alunos',
    2 => 'Gestão da alimentação escolar',
    3 => 'Controle de estoque de materiais',
    4 => 'Gestão de estágio',
    5 => 'Chamados de suporte',
    6 => 'Gerência de espaços e equipamentos',
    7 => 'Banco de questões',
    8 => 'Biblioteca',
    9 => 'Registros PCD',
    10 => 'Tombamento',
    11 => 'Financeiro',
    12 => 'Sistema Professor Diretor de Turma (PDT)'
];

// Obtém o nome da equipe usando o ID da sessão e o mapeamento
$nome_equipe = $nomes_equipes[$id_equipe_logada] ?? 'Nome da Equipe não encontrado';

$alunos_da_equipe = [];
$total_alunos = 0;
$opcoes_pontos = [];

if ($id_equipe_logada !== null) {
    // Obter os alunos da equipe
    $alunos_da_equipe = getAlunosByEquipe($id_equipe_logada);
    $total_alunos = count($alunos_da_equipe);

    // Definir as opções de pontos com base no total de alunos
    if ($total_alunos == 3) {
        $opcoes_pontos = [4.00, 2.50, 1.00];
    } elseif ($total_alunos == 4) {
        $opcoes_pontos = [4.00, 3.00, 2.00, 1.00];
    } elseif ($total_alunos == 6) {
        $opcoes_pontos = [4.00, 3.25, 2.75, 2.00, 1.50, 1.00];
    } else {
        // Opcional: definir um padrão ou exibir uma mensagem se o número de alunos for diferente dos esperados
        $opcoes_pontos = []; // Define como array vazia quando o número de alunos não é 3, 4 ou 6
    }
}

$equipeData = [
    'name' => $nome_equipe,
    'students' => $alunos_da_equipe,
    'pointOptions' => $opcoes_pontos
];

error_log("Debug: alunos_da_equipe before JSON encode: " . print_r($alunos_da_equipe, true));

if ($_SESSION['login'] == true) {
    if ($_SESSION['voto'] == 1) {
        // Usuário já votou, redirecionar
        header('Location: ../../views/form/already_voted.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação da Equipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#007A33',
                        secondary: '#FFA500',
                        success: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                        info: '#3B82F6'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #1a1a1a;
            color: #ffffff;
            min-height: 100vh;
            background-image:
                radial-gradient(circle at 10% 20%, rgba(0, 122, 51, 0.05) 0%, rgba(0, 122, 51, 0) 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 165, 0, 0.05) 0%, rgba(255, 165, 0, 0) 20%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cpath fill='%23007A33' fill-opacity='0.03' d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.105 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z'%3E%3Cpath fill='%23007A33' fill-opacity='0.03' d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.105 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z'%3E%3C/path%3E%3C/svg%3E");
            transition: all 0.3s ease;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(0, 122, 51, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 165, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .custom-select {
            background: rgba(15, 23, 42, 0.8);
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .custom-select:focus {
            border-color: #007A33;
            box-shadow: 0 0 0 3px rgba(0, 122, 51, 0.1);
            outline: none;
        }

        .custom-select:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .student-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .student-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(0, 122, 51, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #007A33 0%, #00a843 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px -5px rgba(0, 122, 51, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00a843 0%, #00c250 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(0, 122, 51, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
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

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stagger-animation {
            animation-delay: calc(var(--index) * 0.1s);
        }

        .progress-bar {
            background: linear-gradient(90deg, #007A33 0%, #00a843 50%, #00c250 100%);
            height: 4px;
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #3d3d3d;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #007A33;
        }

        .container {
            background: linear-gradient(135deg, rgba(49, 49, 49, 0.95) 0%, rgba(37, 37, 37, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .list-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0.75rem 0;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .custom-select {
            background-color: rgba(35, 35, 35, 0.8) !important;
            border: 2px solid rgba(61, 61, 61, 0.8) !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            backdrop-filter: blur(5px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            cursor: pointer;
        }

        .custom-select:focus {
            border-color: #00C250 !important;
            box-shadow: 0 0 0 2px rgba(0, 194, 80, 0.2), inset 0 2px 4px rgba(0, 0, 0, 0.1) !important;
            outline: none !important;
            background-color: rgba(40, 40, 40, 0.9) !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #3d3d3d;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #007A33;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
<div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
        <div class="absolute top-3/4 left-3/4 w-48 h-48 bg-info/5 rounded-full blur-3xl"></div>
    </div>

    <div class="glass-card rounded-2xl p-8 max-w-2xl w-full mx-4 fade-in">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/20 rounded-full mb-4 floating-icon">
                <i class="fas fa-users text-2xl text-primary"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Avaliação da Equipe</h1>
            <h2 class="text-xl text-gray-300 font-medium" id="team-name"></h2>
            <div class="mt-4 bg-slate-800/50 rounded-lg p-3">
                <div class="flex items-center justify-between text-sm text-gray-400 mb-2">
                    <span>Progresso da Avaliação</span>
                    <span id="progress-text">0/4 avaliados</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2">
                    <div class="progress-bar w-0" id="progress-bar"></div>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <form id="evaluation-form" action="../../controllers/controller_avaliacao.php" method="post" class="space-y-4">
            <input type="hidden" name="id_equipe" value="<?= htmlspecialchars($id_equipe_logada ?? '') ?>">
            <div class="space-y-4" id="students-container">
                <!-- Student cards will be generated by JavaScript -->
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center pt-6">
                <button type="submit" class="btn-primary px-8 py-4 rounded-xl text-white font-semibold text-lg flex items-center space-x-3 disabled:opacity-50 disabled:cursor-not-allowed" id="submit-btn" disabled>
                    <i class="fas fa-save"></i>
                    <span>Salvar Avaliação</span>
                </button>
            </div>
        </form>

        <!-- Success Message (hidden by default) -->
        <div id="success-message" class="hidden mt-6 p-4 bg-success/20 border border-success/30 rounded-lg text-success text-center">
            <i class="fas fa-check-circle mr-2"></i>
            Avaliação salva com sucesso!
        </div>
    </div>

    <script>
        // Sample data - replace with your PHP data
        const teamData = <?php echo json_encode($equipeData); ?>;

        console.log('Debug JS: teamData:', teamData);
        console.log('Debug JS: teamData.students:', teamData.students);

        let evaluatedCount = 0;
        const totalStudents = teamData.students.length;

        let assignedScores = {}; // Object to track which score is assigned to which student ID

        function updateProgress() {
            const percentage = (evaluatedCount / totalStudents) * 100;
            document.getElementById('progress-bar').style.width = `${percentage}%`;
            document.getElementById('progress-text').textContent = `${evaluatedCount}/${totalStudents} avaliados`;
            
            const submitBtn = document.getElementById('submit-btn');
            if (evaluatedCount === totalStudents) {
                submitBtn.disabled = false;
                submitBtn.classList.add('pulse');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.remove('pulse');
            }
        }

        function createStudentCard(student, index) {
            const card = document.createElement('div');
            card.className = 'student-card rounded-xl p-6 slide-up stagger-animation';
            card.style.setProperty('--index', index);
            
            card.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary/70 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">${student.nome}</h3>
                            <p class="text-sm text-gray-400">Membro da equipe</p>
                            <input type="hidden" name="alunos[${student.id}][id]" value="${student.id}">
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select name="alunos[${student.id}][ponto]" class="custom-select rounded-lg px-4 py-3 text-white min-w-[120px]" onchange="handleScoreChange(this)" required>
                            <option value="">Nota</option>
                            ${teamData.pointOptions.map(point =>
                                 `<option value="${point}">${point.toFixed(2).replace('.', ',')}</option>`
                             ).join('')}
                        </select>
                        <div class="score-indicator w-3 h-3 rounded-full bg-gray-600 transition-colors duration-300" id="indicator_${student.id}"></div>
                    </div>
                </div>
            `;
            
            return card;
        }

        function handleScoreChange(select) {
            const studentId = select.name.split('[')[1].split(']')[0];
            const selectedScore = select.value;
            const indicator = document.getElementById(`indicator_${studentId}`);
            
            // Get the previously assigned score for this student
            const previousScore = Object.keys(assignedScores).find(score => assignedScores[score] === studentId);

            if (selectedScore) {
                // Check if the selected score is already assigned to a different student
                if (assignedScores[selectedScore] && assignedScores[selectedScore] !== studentId) {
                    alert(`Nota ${selectedScore} já foi atribuída a outro aluno.`);
                    select.value = ''; // Revert the selection
                    // Update progress and indicator based on reverting
                    if (select.dataset.evaluated === 'true') {
                         evaluatedCount--;
                         select.dataset.evaluated = 'false';
                         indicator.classList.remove('bg-success');
                         indicator.classList.add('bg-gray-600');
                    }
                     updateProgress();
                    return; // Stop processing
                }

                // Remove the previous score assignment if exists
                if (previousScore) {
                    delete assignedScores[previousScore];
                }

                // Assign the new score to the student
                assignedScores[selectedScore] = studentId;

                indicator.classList.remove('bg-gray-600');
                indicator.classList.add('bg-success');
                
                // Check if this student wasn't evaluated before
                if (!select.dataset.evaluated) {
                    evaluatedCount++;
                    select.dataset.evaluated = 'true';
                }
            } else { // Score is being unassigned
                // Remove the score assignment
                if (previousScore) {
                    delete assignedScores[previousScore];
                }

                indicator.classList.remove('bg-success');
                indicator.classList.add('bg-gray-600');
                
                // Check if this student was evaluated before
                if (select.dataset.evaluated) {
                    evaluatedCount--;
                    select.dataset.evaluated = 'false';
                }
            }
            
            updateProgress();
        }

        function initializeForm() {
            // Set team name
            document.getElementById('team-name').textContent = teamData.name;
            
            // Create student cards
            const container = document.getElementById('students-container');
            // Clear existing sample cards if any (though the current HTML doesn't have them, good practice)
            container.innerHTML = ''; 
            teamData.students.forEach((student, index) => {
                const card = createStudentCard(student, index);
                container.appendChild(card);
            });
            
            // Initialize progress
            updateProgress();
        }

        // Form submission
        document.getElementById('evaluation-form').addEventListener('submit', function(e) {
            // Standard form submission will happen, so no need to preventDefault or simulate
            // e.preventDefault(); // Remove or comment out this line
            
            // Ensure all students are evaluated before allowing submission
            if (evaluatedCount < totalStudents) {
                alert("Por favor, avalie todos os alunos antes de enviar.");
                e.preventDefault(); // Prevent submission if not all evaluated
                return;
            }

            // Simulate form submission (remove or adapt for real submission)
            // const submitBtn = document.getElementById('submit-btn');
            // const originalText = submitBtn.innerHTML;
            
            // submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            // submitBtn.disabled = true;
            
            // setTimeout(() => {
                // Show success message
                // document.getElementById('success-message').classList.remove('hidden');
                
                // Reset button
                // submitBtn.innerHTML = originalText;
                // submitBtn.disabled = false;
                
                // Scroll to success message
                // document.getElementById('success-message').scrollIntoView({
                    // behavior: 'smooth',
                    // block: 'center'
                // });
            // }, 2000); // Remove this entire block if doing real submission
        });

        // Add pulse animation class
        const style = document.createElement('style');
        style.textContent = `
            .pulse {
                animation: pulse 2s infinite;
            }
            
            @keyframes pulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
            }
        `;
        document.head.appendChild(style);

        // Initialize the form when the page loads
        document.addEventListener('DOMContentLoaded', initializeForm);
    </script>
</body>

</html>