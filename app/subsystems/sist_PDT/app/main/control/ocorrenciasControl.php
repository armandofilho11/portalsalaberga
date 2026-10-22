<?php
session_start();
require_once '../config/conexao.php';

class OcorrenciasControl {
    private $pdo;

    public function __construct() {
        try {
            $conexao = new Conexao('localhost', 'root', '', 'sis_pdt2');
            $this->pdo = $conexao->getConnection();
            error_log("Conexão com o banco de dados estabelecida com sucesso");
        } catch (Exception $e) {
            error_log("Erro ao conectar com o banco de dados: " . $e->getMessage());
            throw new Exception("Erro ao conectar com o banco de dados");
        }
    }

    public function buscarAlunos($termo) {
        try {
            error_log("Executando buscarAlunos com termo: " . $termo);
            
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
                    $query = "SELECT matricula, nome, numero_chamada FROM alunos 
                             WHERE turma = :turma AND (nome LIKE :termo OR matricula LIKE :termo) 
                             ORDER BY nome";
                    $stmt = $this->pdo->prepare($query);
                    $stmt->bindValue(':turma', $turma, PDO::PARAM_STR);
                    $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
                } else {
                    return [];
                }
            } else {
                // Se não for PDT, busca todos os alunos
                $query = "SELECT matricula, nome, numero_chamada FROM alunos 
                         WHERE nome LIKE :termo OR matricula LIKE :termo 
                         ORDER BY nome";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Alunos encontrados: " . count($alunos));
            return $alunos ?: [];
        } catch (PDOException $e) {
            error_log("Erro ao buscar alunos: " . $e->getMessage());
            throw new Exception("Erro ao buscar alunos: " . $e->getMessage());
        }
    }

    public function listarTodosAlunos() {
        try {
            error_log("Executando listarTodosAlunos");
            
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
                    $query = "SELECT matricula, nome, numero_chamada FROM alunos 
                             WHERE turma = ? 
                             ORDER BY nome";
                    $stmt = $this->pdo->prepare($query);
                    $stmt->execute([$turma]);
                } else {
                    return [];
                }
            } else {
                // Se não for PDT, lista todos os alunos
                $query = "SELECT matricula, nome, numero_chamada FROM alunos ORDER BY nome";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }
            
            $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Alunos encontrados: " . count($alunos));
            return $alunos ?: [];
        } catch (PDOException $e) {
            error_log("Erro ao listar alunos: " . $e->getMessage());
            throw new Exception("Erro ao listar alunos: " . $e->getMessage());
        }
    }

    public function listarOcorrencias() {
        try {
            error_log("Iniciando listagem de ocorrências");
            
            // Primeiro, vamos verificar se existem ocorrências
            $checkQuery = "SELECT COUNT(*) FROM ocorrencias";
            $count = $this->pdo->query($checkQuery)->fetchColumn();
            error_log("Total de ocorrências na tabela: " . $count);

            if ($count == 0) {
                error_log("Nenhuma ocorrência encontrada na tabela");
                return [];
            }

            // Agora vamos buscar as ocorrências com os dados dos alunos
            $query = "
                SELECT 
                    o.id_ocorrencias as id,
                    o.ocorrencia,
                    o.data_ocorrencia,
                    o.matricula_aluno,
                    a.nome as nome_aluno,
                    a.numero_chamada
                FROM 
                    ocorrencias o
                    LEFT JOIN alunos a ON o.matricula_aluno = a.matricula
                ORDER BY 
                    o.data_ocorrencia DESC, o.id_ocorrencias DESC
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
            error_log("Número de ocorrências retornadas: " . count($result));
            error_log("Dados retornados: " . json_encode($result));
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("Erro PDO ao listar ocorrências: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Erro ao listar ocorrências: " . $e->getMessage());
        } catch (Exception $e) {
            error_log("Erro ao listar ocorrências: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function buscarOcorrenciaMaisRecente() {
        try {
            error_log("Iniciando busca da ocorrência mais recente");
            
            // Primeiro, vamos verificar se existem ocorrências
            $checkQuery = "SELECT COUNT(*) FROM ocorrencias";
            $count = $this->pdo->query($checkQuery)->fetchColumn();
            error_log("Total de ocorrências na tabela: " . $count);

            if ($count == 0) {
                error_log("Nenhuma ocorrência encontrada na tabela");
                return [];
            }

            // Agora vamos buscar apenas a ocorrência mais recente
            $query = "
                SELECT 
                    o.id_ocorrencias as id,
                    o.ocorrencia,
                    o.data_ocorrencia,
                    o.matricula_aluno,
                    a.nome as nome_aluno,
                    a.numero_chamada
                FROM 
                    ocorrencias o
                    LEFT JOIN alunos a ON o.matricula_aluno = a.matricula
                ORDER BY 
                    o.data_ocorrencia DESC, o.id_ocorrencias DESC
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
            error_log("Erro PDO ao buscar ocorrência mais recente: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Erro ao buscar ocorrência mais recente: " . $e->getMessage());
        } catch (Exception $e) {
            error_log("Erro ao buscar ocorrência mais recente: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function processarFormulario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Recebe e valida os dados do formulário
                $ocorrencia = filter_input(INPUT_POST, 'ocorrencia', FILTER_SANITIZE_STRING);
                $matricula_aluno = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_NUMBER_INT);
                $data_ocorrencia = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);

                // Validações básicas
                if (empty($ocorrencia)) {
                    throw new Exception("O campo ocorrência é obrigatório!");
                }

                if (empty($matricula_aluno)) {
                    throw new Exception("A matrícula do aluno é obrigatória!");
                }

                if (empty($data_ocorrencia)) {
                    throw new Exception("A data da ocorrência é obrigatória!");
                }

                // Verifica se o aluno existe
                if (!$this->verificarAluno($matricula_aluno)) {
                    throw new Exception("Aluno não encontrado!");
                }

                // Adiciona a ocorrência
                if ($this->adicionarOcorrencia($ocorrencia, $matricula_aluno, $data_ocorrencia)) {
                    return true;
                } else {
                    throw new Exception("Erro ao registrar ocorrência!");
                }

            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
        return false;
    }

    private function verificarAluno($matricula) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM alunos WHERE matricula = ?");
            $stmt->execute([$matricula]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar aluno: " . $e->getMessage());
            throw new Exception("Erro ao verificar aluno: " . $e->getMessage());
        }
    }

    private function adicionarOcorrencia($ocorrencia, $matricula_aluno, $data_ocorrencia) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO ocorrencias (ocorrencia, data_ocorrencia, matricula_aluno) 
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$ocorrencia, $data_ocorrencia, $matricula_aluno]);
        } catch (PDOException $e) {
            error_log("Erro ao adicionar ocorrência: " . $e->getMessage());
            throw new Exception("Erro ao adicionar ocorrência: " . $e->getMessage());
        }
    }

    public function excluirOcorrencia($id) {
        try {
            error_log("Tentando excluir ocorrência com ID: " . $id);
            
            if (empty($id)) {
                throw new Exception("ID da ocorrência é obrigatório!");
            }

            $stmt = $this->pdo->prepare("DELETE FROM ocorrencias WHERE id_ocorrencias = ?");
            $resultado = $stmt->execute([$id]);
            
            if ($resultado) {
                error_log("Ocorrência excluída com sucesso. ID: " . $id);
                return true;
            } else {
                error_log("Falha ao excluir ocorrência. ID: " . $id);
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erro ao excluir ocorrência: " . $e->getMessage());
            throw new Exception("Erro ao excluir ocorrência: " . $e->getMessage());
        }
    }
}

// Processar requisições AJAX
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    try {
        error_log("\n\n=== Nova requisição AJAX ===");
        error_log("Ação solicitada: " . $_GET['action']);
        
        $ocorrenciasControl = new OcorrenciasControl();
        
        switch ($_GET['action']) {
            case 'buscar_alunos':
                $termo = isset($_GET['termo']) ? $_GET['termo'] : '';
                $alunos = $ocorrenciasControl->buscarAlunos($termo);
                echo json_encode(['success' => true, 'data' => $alunos]);
                break;
                
            case 'listar_alunos':
                error_log("Listando todos os alunos");
                $alunos = $ocorrenciasControl->listarTodosAlunos();
                echo json_encode(['success' => true, 'data' => $alunos]);
                break;

            case 'listar_ocorrencias':
                try {
                    error_log("Tentando listar ocorrências...");
                    $ocorrencias = $ocorrenciasControl->listarOcorrencias();
                    error_log("Ocorrências recuperadas com sucesso");
                    echo json_encode([
                        'success' => true,
                        'data' => $ocorrencias
                    ]);
                } catch (Exception $e) {
                    error_log("Erro ao listar ocorrências: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'details' => 'Erro ao listar ocorrências'
                    ]);
                }
                break;

            case 'buscar_ocorrencia_recente':
                try {
                    error_log("Tentando buscar ocorrência mais recente...");
                    $ocorrencia = $ocorrenciasControl->buscarOcorrenciaMaisRecente();
                    error_log("Ocorrência mais recente recuperada com sucesso");
                    echo json_encode([
                        'success' => true,
                        'data' => $ocorrencia
                    ]);
                } catch (Exception $e) {
                    error_log("Erro ao buscar ocorrência mais recente: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'error' => $e->getMessage(),
                        'details' => 'Erro ao buscar ocorrência mais recente'
                    ]);
                }
                break;

            default:
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Ação inválida',
                    'action' => $_GET['action']
                ]);
                break;
        }
    } catch (Exception $e) {
        error_log("Erro geral na requisição AJAX: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit();
}

// Processar o formulário se for uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $ocorrenciasControl = new OcorrenciasControl();
    try {
        if (isset($_POST['action']) && $_POST['action'] === 'excluir_ocorrencia') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            error_log("ID recebido para exclusão: " . $id); // Log para debug
            
            if (empty($id)) {
                throw new Exception("ID da ocorrência é obrigatório!");
            }
            
            if ($ocorrenciasControl->excluirOcorrencia($id)) {
                echo json_encode(['success' => true, 'message' => 'Ocorrência excluída com sucesso!']);
            } else {
                throw new Exception("Erro ao excluir ocorrência!");
            }
        } else {
            $result = $ocorrenciasControl->processarFormulario();
            echo json_encode(['success' => true, 'message' => 'Ocorrência registrada com sucesso!']);
        }
    } catch (Exception $e) {
        error_log("Erro na requisição POST: " . $e->getMessage()); // Log para debug
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} 