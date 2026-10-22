<?php
session_start();
require_once '../config/conexao.php';

class LiderancaControl {
    private $pdo;

    public function __construct() {
        try {
            $conexao = new Conexao('localhost', 'root', '', 'sis_pdt2');
            $this->pdo = $conexao->getConnection();
        } catch (Exception $e) {
            throw new Exception("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    public function listarLideranca() {
        try {
            $stmt = $this->pdo->query("
                SELECT l.*, a.nome, a.matricula 
                FROM lider l 
                JOIN alunos a ON l.matricula_lider = a.matricula 
                ORDER BY l.bimestre DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar liderança: " . $e->getMessage());
        }
    }

    public function listarViceLideranca() {
        try {
            $stmt = $this->pdo->query("
                SELECT v.*, a.nome, a.matricula 
                FROM vice_lider v 
                JOIN alunos a ON v.matricula_vice_lider = a.matricula 
                ORDER BY v.bimestre DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar vice-liderança: " . $e->getMessage());
        }
    }

    public function listarSecretaria() {
        try {
            $stmt = $this->pdo->query("
                SELECT s.*, a.nome, a.matricula 
                FROM secretario s 
                JOIN alunos a ON s.matricula_secretario = a.matricula 
                ORDER BY s.bimestre DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar secretaria: " . $e->getMessage());
        }
    }

    public function listarAlunos() {
        try {
            // Verifica se é um PDT logado
            if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'pdt') {
                // Busca a turma responsável do PDT
                $stmt = $this->pdo->prepare("
                    SELECT turma_responsavel 
                    FROM pdts 
                    WHERE matricula_prof = ?
                ");
                $stmt->execute([$_SESSION['usuario']['matricula']]);
                $turma = $stmt->fetchColumn();

                if ($turma) {
                    // Lista apenas alunos da turma do PDT
                    $stmt = $this->pdo->prepare("
                        SELECT matricula, nome 
                        FROM alunos 
                        WHERE turma = ? 
                        ORDER BY nome
                    ");
                    $stmt->execute([$turma]);
                } else {
                    throw new Exception('Turma não encontrada para o PDT');
                }
            } else {
                // Se não for PDT, lista todos os alunos
                $stmt = $this->pdo->query("SELECT matricula, nome FROM alunos ORDER BY nome");
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar alunos: " . $e->getMessage());
        }
    }

    public function buscarAlunos($termo) {
        try {
            // Verifica se é um PDT logado
            if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'pdt') {
                // Busca a turma responsável do PDT
                $stmt = $this->pdo->prepare("
                    SELECT turma_responsavel 
                    FROM pdts 
                    WHERE matricula_prof = ?
                ");
                $stmt->execute([$_SESSION['usuario']['matricula']]);
                $turma = $stmt->fetchColumn();

                if ($turma) {
                    // Busca alunos da turma do PDT com o termo
                    $stmt = $this->pdo->prepare("
                        SELECT matricula, nome 
                        FROM alunos 
                        WHERE turma = :turma AND (matricula LIKE :termo OR nome LIKE :termo) 
                        ORDER BY nome
                    ");
                    $termo = "%{$termo}%";
                    $stmt->bindParam(':turma', $turma);
                    $stmt->bindParam(':termo', $termo);
                } else {
                    return [];
                }
            } else {
                // Se não for PDT, busca todos os alunos
                $stmt = $this->pdo->prepare("
                    SELECT matricula, nome 
                    FROM alunos 
                    WHERE matricula LIKE :termo OR nome LIKE :termo 
                    ORDER BY nome
                ");
                $termo = "%{$termo}%";
                $stmt->bindParam(':termo', $termo);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar alunos: " . $e->getMessage());
        }
    }

    public function salvarLideranca($matricula, $bimestre) {
        try {
            if ($this->verificarLiderancaExistente($bimestre)) {
                throw new Exception('Já existe um líder registrado para este bimestre');
            }

            // Primeiro, buscar o nome do aluno
            $stmt = $this->pdo->prepare("SELECT nome FROM alunos WHERE matricula = ?");
            $stmt->execute([$matricula]);
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$aluno) {
                throw new Exception('Aluno não encontrado');
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO lider (nome, matricula_lider, bimestre) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$aluno['nome'], $matricula, $bimestre]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao salvar liderança: " . $e->getMessage());
        }
    }

    public function salvarViceLideranca($matricula, $bimestre) {
        try {
            if ($this->verificarViceLiderancaExistente($bimestre)) {
                throw new Exception('Já existe um vice-líder registrado para este bimestre');
            }

            // Primeiro, buscar o nome do aluno
            $stmt = $this->pdo->prepare("SELECT nome FROM alunos WHERE matricula = ?");
            $stmt->execute([$matricula]);
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$aluno) {
                throw new Exception('Aluno não encontrado');
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO vice_lider (nome, matricula_vice_lider, bimestre) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$aluno['nome'], $matricula, $bimestre]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao salvar vice-liderança: " . $e->getMessage());
        }
    }

    public function salvarSecretaria($matricula, $bimestre) {
        try {
            if ($this->verificarSecretariaExistente($bimestre)) {
                throw new Exception('Já existe um secretário registrado para este bimestre');
            }

            // Primeiro, buscar o nome do aluno
            $stmt = $this->pdo->prepare("SELECT nome FROM alunos WHERE matricula = ?");
            $stmt->execute([$matricula]);
            $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$aluno) {
                throw new Exception('Aluno não encontrado');
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO secretario (nome, matricula_secretario, bimestre) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$aluno['nome'], $matricula, $bimestre]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao salvar secretaria: " . $e->getMessage());
        }
    }

    private function verificarLiderancaExistente($bimestre) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM lider WHERE bimestre = ?");
            $stmt->execute([$bimestre]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar liderança existente: " . $e->getMessage());
        }
    }

    private function verificarViceLiderancaExistente($bimestre) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM vice_lider WHERE bimestre = ?");
            $stmt->execute([$bimestre]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar vice-liderança existente: " . $e->getMessage());
        }
    }

    private function verificarSecretariaExistente($bimestre) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM secretario WHERE bimestre = ?");
            $stmt->execute([$bimestre]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar secretaria existente: " . $e->getMessage());
        }
    }

    public function excluirLideranca($matricula) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM lider WHERE matricula_lider = ?");
            return $stmt->execute([$matricula]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir liderança: " . $e->getMessage());
        }
    }

    public function excluirViceLideranca($matricula) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM vice_lider WHERE matricula_vice_lider = ?");
            return $stmt->execute([$matricula]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir vice-liderança: " . $e->getMessage());
        }
    }

    public function excluirSecretario($matricula) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM secretario WHERE matricula_secretario = ?");
            return $stmt->execute([$matricula]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir secretário: " . $e->getMessage());
        }
    }
}

// Processar requisições
if (isset($_GET['action']) || isset($_POST['action'])) {
    header('Content-Type: application/json');
    try {
        $liderancaControl = new LiderancaControl();
        $action = $_GET['action'] ?? $_POST['action'] ?? '';

        switch ($action) {
            case 'listar_lideranca':
                $lideres = $liderancaControl->listarLideranca();
                echo json_encode($lideres);
                break;

            case 'listar_vice_lideranca':
                $viceLideres = $liderancaControl->listarViceLideranca();
                echo json_encode($viceLideres);
                break;

            case 'listar_secretaria':
                $secretarios = $liderancaControl->listarSecretaria();
                echo json_encode($secretarios);
                break;

            case 'listar_alunos':
                $alunos = $liderancaControl->listarAlunos();
                echo json_encode($alunos);
                break;

            case 'buscar_alunos':
                $termo = $_GET['termo'] ?? '';
                if (empty($termo)) {
                    throw new Exception('Termo de busca é obrigatório');
                }
                $alunos = $liderancaControl->buscarAlunos($termo);
                echo json_encode($alunos);
                break;

            case 'salvar_lideranca':
                $matricula = $_POST['matricula_aluno'] ?? '';
                $bimestre = $_POST['bimestre'] ?? '';

                if (empty($matricula) || empty($bimestre)) {
                    throw new Exception('Todos os campos são obrigatórios');
                }

                if ($liderancaControl->salvarLideranca($matricula, $bimestre)) {
                    echo json_encode(['success' => true, 'message' => 'Liderança salva com sucesso']);
                } else {
                    throw new Exception('Erro ao salvar liderança');
                }
                break;

            case 'salvar_vice_lideranca':
                $matricula = $_POST['matricula_aluno'] ?? '';
                $bimestre = $_POST['bimestre'] ?? '';

                if (empty($matricula) || empty($bimestre)) {
                    throw new Exception('Todos os campos são obrigatórios');
                }

                if ($liderancaControl->salvarViceLideranca($matricula, $bimestre)) {
                    echo json_encode(['success' => true, 'message' => 'Vice-liderança salva com sucesso']);
                } else {
                    throw new Exception('Erro ao salvar vice-liderança');
                }
                break;

            case 'salvar_secretaria':
                $matricula = $_POST['matricula_aluno'] ?? '';
                $bimestre = $_POST['bimestre'] ?? '';

                if (empty($matricula) || empty($bimestre)) {
                    throw new Exception('Todos os campos são obrigatórios');
                }

                if ($liderancaControl->salvarSecretaria($matricula, $bimestre)) {
                    echo json_encode(['success' => true, 'message' => 'Secretaria salva com sucesso']);
                } else {
                    throw new Exception('Erro ao salvar secretaria');
                }
                break;

            case 'excluir_lideranca':
                $matricula = $_POST['matricula'] ?? '';
                if (empty($matricula)) {
                    throw new Exception('Matrícula da liderança é obrigatória');
                }
                if ($liderancaControl->excluirLideranca($matricula)) {
                    echo json_encode(['success' => true, 'message' => 'Liderança excluída com sucesso']);
                } else {
                    throw new Exception('Erro ao excluir liderança');
                }
                break;

            case 'excluir_vice_lideranca':
                $matricula = $_POST['matricula'] ?? '';
                if (empty($matricula)) {
                    throw new Exception('Matrícula do vice-líder é obrigatória');
                }
                if ($liderancaControl->excluirViceLideranca($matricula)) {
                    echo json_encode(['success' => true, 'message' => 'Vice-liderança excluída com sucesso']);
                } else {
                    throw new Exception('Erro ao excluir vice-liderança');
                }
                break;

            case 'excluir_secretaria':
                $matricula = $_POST['matricula'] ?? '';
                if (empty($matricula)) {
                    throw new Exception('Matrícula do secretário é obrigatória');
                }
                if ($liderancaControl->excluirSecretario($matricula)) {
                    echo json_encode(['success' => true, 'message' => 'Secretário excluído com sucesso']);
                } else {
                    throw new Exception('Erro ao excluir secretário');
                }
                break;

            default:
                throw new Exception('Ação não reconhecida');
        }
    } catch (Exception $e) {
        error_log('Erro em liderancaControl.php: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} 