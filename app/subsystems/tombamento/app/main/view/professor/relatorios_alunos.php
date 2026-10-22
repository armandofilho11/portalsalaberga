<?php
require_once("../../model/modelprofessor.php");

$professor = new Professor();
$alunos = $professor->get_all_students();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios dos Alunos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background: linear-gradient(135deg, #ff6b6b, #ffd93d, #6fb936);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .student-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .student-header {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 1.5rem;
        }
        .student-stats {
            display: flex;
            justify-content: space-around;
            padding: 1rem;
            background-color: #f8f9fa;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .search-box {
            background: rgba(255,255,255,0.2);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .search-input {
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            width: 100%;
        }
        .grade-badge {
            font-size: 2rem;
            font-weight: bold;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }
        .grade-excellent {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }
        .grade-good {
            background: linear-gradient(45deg, #17a2b8, #0dcaf0);
            color: white;
        }
        .grade-average {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: white;
        }
        .grade-poor {
            background: linear-gradient(45deg, #dc3545, #f11);
            color: white;
        }
        .grade-none {
            background: linear-gradient(45deg, #6c757d, #495057);
            color: white;
            font-size: 1.2rem !important;
        }
        .no-evaluations {
            font-style: italic;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <header class="header-bg">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
                    <h4 class="mb-0">Relatórios dos Alunos</h4>
                </div>
                <nav>
                    <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
                    <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
                    <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
                    <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
                </nav>
            </div>
            
            <!-- Search Box -->
            <div class="search-box mt-4">
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar aluno por nome ou matrícula...">
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row" id="studentsList">
            <?php foreach ($alunos as $aluno): ?>
                <?php
                $total_avaliacoes = intval($aluno['total_avaliacoes']);
                $media = floatval($aluno['media_notas']);
                
                if ($total_avaliacoes === 0) {
                    $gradeClass = 'grade-none';
                    $gradeDisplay = 'N/A';
                } else {
                    $gradeClass = 'grade-poor';
                    if ($media >= 9) {
                        $gradeClass = 'grade-excellent';
                    } elseif ($media >= 7) {
                        $gradeClass = 'grade-good';
                    } elseif ($media >= 5) {
                        $gradeClass = 'grade-average';
                    }
                    $gradeDisplay = number_format($media, 1);
                }
                ?>
                <div class="col-md-6 col-lg-4 student-item">
                    <div class="card student-card">
                        <div class="student-header d-flex align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($aluno['nome']); ?></h5>
                                <small>Matrícula: <?php echo htmlspecialchars($aluno['matricula']); ?></small>
                                <?php if ($total_avaliacoes === 0): ?>
                                    <br><small class="no-evaluations">Sem avaliações realizadas</small>
                                <?php endif; ?>
                            </div>
                            <div class="grade-badge <?php echo $gradeClass; ?>">
                                <?php echo $gradeDisplay; ?>
                            </div>
                        </div>
                        <div class="student-stats">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $aluno['total_avaliacoes']; ?></div>
                                <div class="stat-label">Avaliações</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $aluno['ano']; ?></div>
                                <div class="stat-label">Ano</div>
                            </div>
                            <div class="stat-item">
                                <a href="detalhes_aluno.php?id=<?php echo $aluno['id']; ?>" 
                                   class="btn btn-sm btn-outline-success">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const students = document.getElementsByClassName('student-item');
            
            Array.from(students).forEach(function(student) {
                const name = student.querySelector('h5').textContent.toLowerCase();
                const matricula = student.querySelector('small').textContent.toLowerCase();
                
                if (name.includes(searchValue) || matricula.includes(searchValue)) {
                    student.style.display = '';
                } else {
                    student.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html> 