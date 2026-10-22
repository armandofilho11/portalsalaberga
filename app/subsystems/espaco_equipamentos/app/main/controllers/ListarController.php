<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

require_once __DIR__ . '/../config/database.php';

try {
    $conn = getDatabaseConnection();
    error_log("[ListarController] Conexão com o banco estabelecida com sucesso");
} catch (PDOException $e) {
    error_log("[ListarController] Erro na conexão com o banco: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao servidor']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'] ?? '';
    
    try {
        if ($tipo === 'equipamentos') {
            $sql = "SELECT id, nome, descricao, quantidade_disponivel FROM equipamentos WHERE disponivel = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $items = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'items' => $items]);
        } 
        else if ($tipo === 'espacos') {
            $sql = "SELECT id, nome, descricao, quantidade_disponivel FROM espacos WHERE disponivel = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $items = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'items' => $items]);
        }
        else if ($tipo === 'agendamentos') {
            if (!isset($_SESSION['aluno_id'])) {
                echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
                exit;
            }

            $sql = "SELECT a.id, a.tipo, 
                    CASE 
                        WHEN a.tipo = 'Equipamento' THEN e.nome 
                        WHEN a.tipo = 'Espaço' THEN s.nome 
                    END as nome,
                    a.data_hora, a.status
                    FROM agendamentos a
                    LEFT JOIN equipamentos e ON a.id_item = e.id AND a.tipo = 'Equipamento'
                    LEFT JOIN espacos s ON a.id_item = s.id AND a.tipo = 'Espaço'
                    WHERE a.aluno_id = :aluno_id
                    ORDER BY a.data_hora DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':aluno_id', $_SESSION['aluno_id']);
            $stmt->execute();
            $agendamentos = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'agendamentos' => $agendamentos]);
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
        }
    } catch (PDOException $e) {
        error_log("[ListarController] Erro no banco de dados: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao listar itens']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}
?> 