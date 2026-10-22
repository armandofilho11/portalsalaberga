<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Subtópicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background-color: #006400;
            color: white;
        }
        .btn-gradient-green {
            background: linear-gradient(45deg, #2e8b57, #32cd32);
            color: white;
            border: none;
        }
        .btn-gradient-orange {
            background: linear-gradient(45deg, #ffa500, #ff8c00);
            color: white;
            border: none;
        }
    </style>
</head>
<body class="bg-light">
    <header class="header-bg py-3 px-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/book.png" alt="Logo" style="width: 30px; margin-right: 10px;">
            <h5 class="mb-0">Banco de Questões</h5>
        </div>
        <nav class="text-white">
            <a href="inicioprofessor.php" class="text-white mx-2 text-decoration-none">Início</a>
            <a href="acessar_banco.php" class="text-white mx-2 text-decoration-none">Questões</a>
            <a href="acessarrelatorioprofessor.php" class="text-white mx-2 text-decoration-none">Relatórios</a>
            <a href="veravaliacoes.php" class="text-white mx-2 text-decoration-none">Avaliações</a>
            <a href="../../index.php" class="text-white mx-2 text-decoration-none">Sair</a>
        </nav>
    </header>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Adicionar Novo Subtópico</h5>
                    </div>
                    <div class="card-body">
                        <form action="../../control/controlesubtopico.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Disciplina</label>
                                <select class="form-select" name="disciplina" required>
                                    <option value="">Selecione</option>
                                    <option value="lab._software">Laboratório de Software</option>
                                    <option value="lab._hardware">Laboratório de Hardware</option>
                                    <option value="Start_up_1">StartUp 1</option>
                                    <option value="Start_up_2">StartUp 2</option>
                                    <option value="Start_up_3">StartUp 3</option>
                                    <option value="banco_de_dados">Banco de Dados</option>
                                    <option value="logica">Lógica de Programação</option>
                                    <option value="gerenciador_de_conteudo">Gerenciador de Conteúdo</option>
                                    <option value="Informatica_basica">Informática Básica</option>
                                    <option value="Robotica">Robótica</option>
                                    <option value="programacao_web">Programação Web</option>
                                    <option value="Sistemas_operacionais">Sistemas Operacionais</option>
                                    <option value="redes_de_computadores">Redes de Computadores</option>
                                    <option value="htmlcss">HTML/CSS</option>
                                    <option value="design">Design</option>
                                    <option value="AMC">Arquitetura e Manutenção de Computadores</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nome do Subtópico</label>
                                <input type="text" class="form-control" name="nome_subtopico" required>
                            </div>
                            <input type="hidden" name="acao" value="adicionar">
                            <button type="submit" class="btn btn-gradient-green">Adicionar Subtópico</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Subtópicos Existentes</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        require_once("../../model/modelprofessor.php");
                        $professor = new Professor();
                        $subtopicos = $professor->listar_subtopicos();
                        
                        if (!empty($subtopicos)) {
                            echo '<div class="list-group">';
                            foreach ($subtopicos as $subtopico) {
                                echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
                                echo '<div>';
                                echo '<h6 class="mb-1">' . htmlspecialchars($subtopico['nome']) . '</h6>';
                                echo '<small class="text-muted">' . htmlspecialchars($subtopico['disciplina']) . '</small>';
                                echo '</div>';
                                echo '<form action="../../control/controlesubtopico.php" method="POST" class="d-inline">';
                                echo '<input type="hidden" name="acao" value="excluir">';
                                echo '<input type="hidden" name="id" value="' . $subtopico['id'] . '">';
                                echo '<button type="submit" class="btn btn-sm btn-danger">Excluir</button>';
                                echo '</form>';
                                echo '</div>';
                            }
                            echo '</div>';
                        } else {
                            echo '<p class="text-center text-muted">Nenhum subtópico cadastrado.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 