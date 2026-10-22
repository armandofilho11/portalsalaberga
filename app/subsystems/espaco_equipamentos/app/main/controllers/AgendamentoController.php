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
    error_log("[AgendamentoController] Conexão com o banco estabelecida com sucesso");
} catch (PDOException $e) {
    error_log("[AgendamentoController] Erro na conexão com o banco: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao servidor']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $btn = $_POST['btn'] ?? '';
    
    try {
        if ($btn === 'agendar_item') {
            if (!isset($_SESSION['aluno_id'])) {
                echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
                exit;
            }

            $id_item = $_POST['id_item'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
            $data_hora = $_POST['data_hora'] ?? '';

            if (empty($id_item) || empty($tipo) || empty($data_hora)) {
                throw new Exception('Dados incompletos');
            }

            // Verificar disponibilidade
            $tabela = $tipo === 'Equipamento' ? 'equipamentos' : 'espacos';
            $sql = "SELECT quantidade_disponivel FROM $tabela WHERE id = :id AND disponivel = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id_item);
            $stmt->execute();
            $item = $stmt->fetch();

            if (!$item || $item['quantidade_disponivel'] <= 0) {
                throw new Exception('Item não disponível');
            }

            // Verificar conflito de horário
            $sql = "SELECT COUNT(*) FROM agendamentos \
                   WHERE id_item = :id_item AND tipo = :tipo \
                   AND data_hora = :data_hora AND status != 'cancelado'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_item', $id_item);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':data_hora', $data_hora);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                throw new Exception('Horário já agendado');
            }

            // Inserir agendamento
            $sql = "INSERT INTO agendamentos (aluno_id, id_item, tipo, data_hora, status) \
                   VALUES (:aluno_id, :id_item, :tipo, :data_hora, 'pendente')";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':aluno_id', $_SESSION['aluno_id']);
            $stmt->bindParam(':id_item', $id_item);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':data_hora', $data_hora);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Agendamento realizado com sucesso']);
        }
        else if ($btn === 'cancelar_agendamento') {
            if (!isset($_SESSION['aluno_id'])) {
                echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
                exit;
            }

            $id = $_POST['id'] ?? '';
            if (empty($id)) {
                throw new Exception('ID do agendamento não fornecido');
            }

            // Verificar se o agendamento pertence ao aluno
            $sql = "SELECT * FROM agendamentos WHERE id = :id AND aluno_id = :aluno_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':aluno_id', $_SESSION['aluno_id']);
            $stmt->execute();
            $agendamento = $stmt->fetch();

            if (!$agendamento) {
                throw new Exception('Agendamento não encontrado');
            }

            if ($agendamento['status'] !== 'pendente') {
                throw new Exception('Apenas agendamentos pendentes podem ser cancelados');
            }

            // Cancelar agendamento
            $sql = "UPDATE agendamentos SET status = 'cancelado' WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Agendamento cancelado com sucesso']);
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Ação inválida']);
        }
    } catch (Exception $e) {
        error_log("[AgendamentoController] Erro: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}
?> 