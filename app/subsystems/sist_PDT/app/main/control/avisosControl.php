<?php
session_start();
require_once '../config/conexao.php';

class AvisosControl {
    private $pdo;

    public function __construct() {
        $conexao = new Conexao('localhost', 'root', '', 'sis_pdt2');
        $this->pdo = $conexao->getConnection();
    }

    // Processar formulário de aviso
    public function processarFormulario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Recebe e valida os dados do formulário
                $aviso = filter_input(INPUT_POST, 'aviso', FILTER_SANITIZE_STRING);
                $matricula_aluno = filter_input(INPUT_POST, 'matricula_aluno', FILTER_SANITIZE_NUMBER_INT);
                $data_aviso = filter_input(INPUT_POST, 'data_aviso', FILTER_SANITIZE_STRING);

                // Validações básicas
                if (empty($aviso)) {
                    throw new Exception("O campo aviso é obrigatório!");
                }

                if (empty($matricula_aluno)) {
                    throw new Exception("A matrícula do aluno é obrigatória!");
                }

                if (empty($data_aviso)) {
                    throw new Exception("A data do aviso é obrigatória!");
                }

                // Verifica se o aluno existe
                if (!$this->verificarAluno($matricula_aluno)) {
                    throw new Exception("Aluno não encontrado!");
                }

                // Adiciona o aviso
                if ($this->adicionarAviso($aviso, $matricula_aluno, $data_aviso)) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Aviso cadastrado com sucesso!']);
                    exit();
                } else {
                    throw new Exception("Erro ao cadastrar aviso!");
                }

            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit();
            }
        }
    }

    // Listar todos os avisos
    public function listarAvisos() {
        try {
            error_log("Iniciando listagem de avisos");
            
            $query = "
                SELECT 
                    a.id_aviso as id,
                    a.aviso,
                    a.data_aviso,
                    a.matricula_aluno,
                    al.nome as nome_aluno,
                    al.numero_chamada
                FROM 
                    avisos a
                    LEFT JOIN alunos al ON a.matricula_aluno = al.matricula
                ORDER BY 
                    a.data_aviso DESC
            ";
            
            error_log("Executando query: " . $query);
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            if ($stmt->errorCode() !== '00000') {
                $error = $stmt->errorInfo();
                error_log("Erro na query: " . print_r($error, true));
                throw new Exception("Erro ao executar a query: " . $error[2]);
            }
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Número de avisos retornados: " . count($result));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao listar avisos: " . $e->getMessage());
            throw new Exception("Erro ao listar avisos: " . $e->getMessage());
        }
    }

    // Listar apenas o aviso mais recente
    public function listarAvisoRecente() {
        try {
            error_log("Iniciando busca do aviso mais recente");
            
            // Primeiro, vamos verificar se existem avisos
            $checkQuery = "SELECT COUNT(*) FROM avisos";
            $count = $this->pdo->query($checkQuery)->fetchColumn();
            error_log("Total de avisos na tabela: " . $count);

            if ($count == 0) {
                error_log("Nenhum aviso encontrado na tabela");
                return [];
            }

            // Agora vamos buscar apenas o aviso mais recente
            $query = "
                SELECT 
                    a.id_aviso as id,
                    a.aviso,
                    a.data_aviso,
                    a.matricula_aluno,
                    al.nome as nome_aluno,
                    al.numero_chamada
                FROM 
                    avisos a
                    LEFT JOIN alunos al ON a.matricula_aluno = al.matricula
                ORDER BY 
                    a.data_aviso DESC, a.id_aviso DESC
                LIMIT 1
            ";
            
            error_log("Executando query: " . $query);
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            
            if ($stmt->errorCode() !== '00000') {
                $error = $stmt->errorInfo();
                error_log("Erro na query: " . print_r($error, true));
                throw new Exception("Erro ao executar a query: " . $error[2]);
            }
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Dados retornados: " . json_encode($result));
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Erro PDO ao buscar aviso mais recente: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Erro ao buscar aviso mais recente: " . $e->getMessage());
        } catch (Exception $e) {
            error_log("Erro ao buscar aviso mais recente: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    // Buscar aviso por ID
    public function buscarAvisoPorId($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT a.*, al.nome as nome_aluno 
                FROM avisos a 
                JOIN alunos al ON a.matricula_aluno = al.matricula 
                WHERE a.id_aviso = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar aviso: " . $e->getMessage());
        }
    }

    // Adicionar novo aviso
    public function adicionarAviso($aviso, $matricula_aluno, $data_aviso) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO avisos (aviso, data_aviso, matricula_aluno) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$aviso, $data_aviso, $matricula_aluno]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao adicionar aviso: " . $e->getMessage());
        }
    }

    // Atualizar aviso existente
    public function atualizarAviso($id, $aviso) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE avisos 
                SET aviso = ? 
                WHERE id_aviso = ?
            ");
            return $stmt->execute([$aviso, $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar aviso: " . $e->getMessage());
        }
    }

    // Excluir aviso
    public function excluirAviso($id) {
        try {
            error_log("Tentando excluir aviso com ID: " . $id);
            
            if (empty($id)) {
                throw new Exception("ID do aviso é obrigatório!");
            }

            $stmt = $this->pdo->prepare("DELETE FROM avisos WHERE id_aviso = ?");
            $resultado = $stmt->execute([$id]);
            
            if ($resultado) {
                error_log("Aviso excluído com sucesso. ID: " . $id);
                return true;
            } else {
                error_log("Falha ao excluir aviso. ID: " . $id);
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir aviso: " . $e->getMessage());
            throw new Exception("Erro ao excluir aviso: " . $e->getMessage());
        }
    }

    // Listar avisos por aluno
    public function listarAvisosPorAluno($matricula_aluno) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT a.*, al.nome as nome_aluno 
                FROM avisos a 
                JOIN alunos al ON a.matricula_aluno = al.matricula 
                WHERE a.matricula_aluno = ? 
                ORDER BY a.data_aviso DESC
            ");
            $stmt->execute([$matricula_aluno]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar avisos do aluno: " . $e->getMessage());
        }
    }

    // Verificar se o aluno existe
    public function verificarAluno($matricula) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM alunos WHERE matricula = ?");
            $stmt->execute([$matricula]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar aluno: " . $e->getMessage());
        }
    }

    // Buscar todos os alunos
    public function listarTodosAlunos() {
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
                    // Se não encontrar a turma, retorna lista vazia
                    return [];
                }
            } else {
                // Se não for PDT, lista todos os alunos
                $stmt = $this->pdo->query("
                    SELECT matricula, nome 
                    FROM alunos 
                    ORDER BY nome
                ");
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar alunos: " . $e->getMessage());
        }
    }

    // Buscar alunos por termo
    public function buscarAlunos($termo = '') {
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
                    $sql = "SELECT matricula, nome FROM alunos WHERE turma = :turma";
                    if (!empty($termo)) {
                        $sql .= " AND (matricula LIKE :termo OR nome LIKE :termo)";
                    }
                    $sql .= " ORDER BY nome";
                    
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->bindParam(':turma', $turma);
                    if (!empty($termo)) {
                        $termo = "%{$termo}%";
                        $stmt->bindParam(':termo', $termo);
                    }
                } else {
                    return [];
                }
            } else {
                // Se não for PDT, busca todos os alunos
                $sql = "SELECT matricula, nome FROM alunos";
                if (!empty($termo)) {
                    $sql .= " WHERE matricula LIKE :termo OR nome LIKE :termo";
                }
                $sql .= " ORDER BY nome";
                
                $stmt = $this->pdo->prepare($sql);
                if (!empty($termo)) {
                    $termo = "%{$termo}%";
                    $stmt->bindParam(':termo', $termo);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar alunos: " . $e->getMessage());
        }
    }
}

// Processar requisições AJAX
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    try {
        $avisosControl = new AvisosControl();
        
        switch ($_GET['action']) {
            case 'buscar_alunos':
                $termo = isset($_GET['termo']) ? $_GET['termo'] : '';
                $alunos = $avisosControl->buscarAlunos($termo);
                echo json_encode($alunos);
                break;
                
            case 'listar_alunos':
                $alunos = $avisosControl->listarTodosAlunos();
                echo json_encode($alunos);
                break;

            case 'listar_avisos':
                $avisos = $avisosControl->listarAvisos();
                echo json_encode($avisos);
                break;

            case 'buscar_aviso_recente':
                try {
                    error_log("Tentando buscar aviso mais recente...");
                    $aviso = $avisosControl->listarAvisoRecente();
                    error_log("Aviso mais recente recuperado com sucesso");
                    echo json_encode([
                        'success' => true,
                        'data' => $aviso
                    ]);
                } catch (Exception $e) {
                    error_log("Erro ao buscar aviso mais recente: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'details' => 'Erro ao buscar aviso mais recente'
                    ]);
                }
                break;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

// Processar o formulário se for uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $avisosControl = new AvisosControl();
        
        if (isset($_POST['action']) && $_POST['action'] === 'excluir_aviso') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            error_log("ID recebido para exclusão: " . $id);
            
            if (empty($id)) {
                throw new Exception("ID do aviso é obrigatório!");
            }
            
            if ($avisosControl->excluirAviso($id)) {
                echo json_encode(['success' => true, 'message' => 'Aviso excluído com sucesso!']);
            } else {
                throw new Exception("Erro ao excluir aviso!");
            }
        } else {
            // Processamento existente para adicionar aviso
            $result = $avisosControl->processarFormulario();
            echo json_encode(['success' => true, 'message' => 'Aviso registrado com sucesso!']);
        }
    } catch (Exception $e) {
        error_log("Erro na requisição POST: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

// Exemplo de uso:
/*
$avisosControl = new AvisosControl();

// Listar todos os avisos
$todosAvisos = $avisosControl->listarAvisos();

// Adicionar novo aviso
$avisosControl->adicionarAviso("Novo aviso", 12345678);

// Atualizar aviso
$avisosControl->atualizarAviso(1, "Aviso atualizado");

// Excluir aviso
$avisosControl->excluirAviso(1);

// Listar avisos de um aluno específico
$avisosAluno = $avisosControl->listarAvisosPorAluno(12345678);
*/
?>
