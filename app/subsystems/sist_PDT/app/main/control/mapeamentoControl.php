<?php
session_start();
require_once '../config/conexao.php';

// Configuração da conexão
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sis_pdt2';

// Criar instância da conexão
try {
    $conexao = new Conexao($host, $username, $password, $dbname);
    $conn = $conexao->getConnection();
    
    // Verificar se a tabela alunos existe
    $stmt = $conn->query("SHOW TABLES LIKE 'alunos'");
    if ($stmt->rowCount() == 0) {
        throw new Exception("A tabela 'alunos' não existe no banco de dados");
    }
    
    // Verificar se existem alunos na tabela
    $stmt = $conn->query("SELECT COUNT(*) FROM alunos");
    $count = $stmt->fetchColumn();
    if ($count == 0) {
        throw new Exception("A tabela 'alunos' está vazia");
    }
    
} catch (Exception $e) {
    error_log("Erro na conexão: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

function excluirMapeamento($numero_carteira) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM mapeamento WHERE numero_carteira = ?");
        $stmt->execute([$numero_carteira]);
        return true;
    } catch (PDOException $e) {
        error_log("Erro ao excluir mapeamento: " . $e->getMessage());
        return false;
    }
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    error_log("Ação recebida: " . $action);

    switch ($action) {
        case 'listar_alunos':
            listarAlunos();
            break;
        case 'buscar_alunos':
            $termo = $_GET['termo'] ?? '';
            buscarAlunos($termo);
            break;
        case 'listar_mapeamento':
            listarMapeamento();
            break;
        case 'buscar_mapeamento_recente':
            buscarMapeamentoRecente();
            break;
        default:
            echo json_encode(['error' => 'Ação não reconhecida: ' . $action]);
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'salvar_mapeamento':
            salvarMapeamento();
            break;
        case 'excluir_mapeamento':
            $numero_carteira = $_POST['numero_carteira'] ?? '';
            if (empty($numero_carteira)) {
                echo json_encode(['success' => false, 'error' => 'Número da carteira é obrigatório']);
                exit;
            }
            if (excluirMapeamento($numero_carteira)) {
                echo json_encode(['success' => true, 'message' => 'Mapeamento excluído com sucesso']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao excluir mapeamento']);
            }
            break;
        default:
            echo json_encode(['error' => 'Ação não reconhecida: ' . $action]);
            break;
    }
}

function listarAlunos() {
    global $conn;
    try {
        error_log("Iniciando listagem de alunos");
        
        // Verifica se é um PDT logado
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'pdt') {
            // Busca a turma responsável do PDT
            $stmt = $conn->prepare("
                SELECT turma_responsavel 
                FROM pdts 
                WHERE matricula_prof = ?
            ");
            $stmt->execute([$_SESSION['usuario']['matricula']]);
            $turma = $stmt->fetchColumn();

            if ($turma) {
                // Lista apenas alunos da turma do PDT
                $stmt = $conn->prepare("
                    SELECT matricula, nome 
                    FROM alunos 
                    WHERE turma = ? 
                    ORDER BY nome
                ");
                $stmt->execute([$turma]);
            } else {
                echo json_encode(['error' => 'Turma não encontrada para o PDT']);
                return;
            }
        } else {
            // Se não for PDT, lista todos os alunos
            $stmt = $conn->prepare("SELECT matricula, nome FROM alunos ORDER BY nome");
            $stmt->execute();
        }
        
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Alunos encontrados: " . count($alunos));
        
        if (empty($alunos)) {
            error_log("Nenhum aluno encontrado na tabela");
            echo json_encode(['error' => 'Nenhum aluno encontrado na tabela']);
            return;
        }
        
        echo json_encode($alunos);
    } catch (PDOException $e) {
        error_log("Erro ao listar alunos: " . $e->getMessage());
        echo json_encode(['error' => 'Erro ao listar alunos: ' . $e->getMessage()]);
    }
}

function buscarAlunos($termo) {
    global $conn;
    try {
        error_log("Buscando alunos com termo: " . $termo);
        
        // Verifica se é um PDT logado
        if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'pdt') {
            // Busca a turma responsável do PDT
            $stmt = $conn->prepare("
                SELECT turma_responsavel 
                FROM pdts 
                WHERE matricula_prof = ?
            ");
            $stmt->execute([$_SESSION['usuario']['matricula']]);
            $turma = $stmt->fetchColumn();

            if ($turma) {
                // Busca alunos da turma do PDT com o termo
                $stmt = $conn->prepare("
                    SELECT matricula, nome 
                    FROM alunos 
                    WHERE turma = :turma AND (nome LIKE :termo OR matricula LIKE :termo) 
                    ORDER BY nome
                ");
                $termo = "%$termo%";
                $stmt->bindParam(':turma', $turma);
                $stmt->bindParam(':termo', $termo);
            } else {
                echo json_encode([]);
                return;
            }
        } else {
            // Se não for PDT, busca todos os alunos
            $stmt = $conn->prepare("
                SELECT matricula, nome 
                FROM alunos 
                WHERE nome LIKE :termo OR matricula LIKE :termo 
                ORDER BY nome
            ");
            $termo = "%$termo%";
            $stmt->bindParam(':termo', $termo);
        }
        
        $stmt->execute();
        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Alunos encontrados na busca: " . count($alunos));
        echo json_encode($alunos);
    } catch (PDOException $e) {
        error_log("Erro ao buscar alunos: " . $e->getMessage());
        echo json_encode(['error' => 'Erro ao buscar alunos: ' . $e->getMessage()]);
    }
}

function listarMapeamento() {
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT m.*, a.nome as nome_aluno 
            FROM mapeamento m 
            JOIN alunos a ON m.matricula_aluno = a.matricula 
            ORDER BY m.data_mapeamento DESC, m.id_mapeamento DESC
        ");
        $stmt->execute();
        $mapeamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($mapeamentos);
    } catch (PDOException $e) {
        error_log("Erro ao listar mapeamento: " . $e->getMessage());
        echo json_encode(['error' => 'Erro ao listar mapeamento: ' . $e->getMessage()]);
    }
}

function buscarMapeamentoRecente() {
    global $conn;
    try {
        error_log("Iniciando busca do mapeamento mais recente");
        
        // Primeiro, vamos verificar se existem mapeamentos
        $checkQuery = "SELECT COUNT(*) FROM mapeamento";
        $count = $conn->query($checkQuery)->fetchColumn();
        error_log("Total de mapeamentos na tabela: " . $count);

        if ($count == 0) {
            error_log("Nenhum mapeamento encontrado na tabela");
            echo json_encode([
                'success' => true,
                'data' => []
            ]);
            return;
        }

        // Agora vamos buscar apenas o mapeamento mais recente
        $query = "
            SELECT 
                m.id_mapeamento as id,
                m.numero_carteira,
                m.data_mapeamento,
                m.matricula_aluno,
                a.nome as nome_aluno
            FROM 
                mapeamento m
                LEFT JOIN alunos a ON m.matricula_aluno = a.matricula
            ORDER BY 
                m.data_mapeamento DESC, m.id_mapeamento DESC
            LIMIT 1
        ";
        
        error_log("Executando query: " . $query);
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        if ($stmt->errorCode() !== '00000') {
            $error = $stmt->errorInfo();
            error_log("Erro na query: " . print_r($error, true));
            throw new Exception("Erro ao executar a query: " . $error[2]);
        }
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Dados retornados: " . json_encode($result));
        
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
        
    } catch (PDOException $e) {
        error_log("Erro PDO ao buscar mapeamento mais recente: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'details' => 'Erro ao buscar mapeamento mais recente'
        ]);
    } catch (Exception $e) {
        error_log("Erro ao buscar mapeamento mais recente: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'details' => 'Erro ao buscar mapeamento mais recente'
        ]);
    }
}

function salvarMapeamento() {
    global $conn;
    try {
        $numero_carteira = $_POST['numero_carteira'] ?? '';
        $matricula_aluno = $_POST['matricula_aluno'] ?? '';
        $data_mapeamento = $_POST['data_mapeamento'] ?? date('Y-m-d');

        if (empty($numero_carteira) || empty($matricula_aluno)) {
            throw new Exception("Todos os campos são obrigatórios");
        }

        // Verificar se já existe mapeamento para esta carteira
        $stmt = $conn->prepare("SELECT id_mapeamento FROM mapeamento WHERE numero_carteira = :numero_carteira");
        $stmt->bindParam(':numero_carteira', $numero_carteira);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Atualizar mapeamento existente
            $stmt = $conn->prepare("
                UPDATE mapeamento 
                SET matricula_aluno = :matricula_aluno, 
                    data_mapeamento = :data_mapeamento 
                WHERE numero_carteira = :numero_carteira
            ");
        } else {
            // Inserir novo mapeamento
            $stmt = $conn->prepare("
                INSERT INTO mapeamento (numero_carteira, matricula_aluno, data_mapeamento) 
                VALUES (:numero_carteira, :matricula_aluno, :data_mapeamento)
            ");
        }

        $stmt->bindParam(':numero_carteira', $numero_carteira);
        $stmt->bindParam(':matricula_aluno', $matricula_aluno);
        $stmt->bindParam(':data_mapeamento', $data_mapeamento);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Mapeamento salvo com sucesso']);
    } catch (Exception $e) {
        error_log("Erro ao salvar mapeamento: " . $e->getMessage());
        echo json_encode(['error' => 'Erro ao salvar mapeamento: ' . $e->getMessage()]);
    }
}
?> 