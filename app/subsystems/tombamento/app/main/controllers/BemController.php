<?php
ob_start();
session_start();
require_once '../includes/conexao.php';
header('Content-Type: application/json; charset=UTF-8');

function sendResponse($success, $message = '') {
    ob_end_clean();
    echo json_encode([
        'success' => $success,
        'error' => $message
    ]);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

        if (isset($_POST['cadastrar']) && $_POST['cadastrar'] == 1) {
            // Validate only the 'nome' field as required
            if (empty($_POST['nome'])) {
                $_SESSION['mensagem_erro'] = "Erro: Nome é obrigatório.";
                ob_end_clean();
                header("Location: ../includes/menu.php");
                exit;
            }

            $estado_conservacao = isset($_POST['estado_conservacao']) && !empty($_POST['estado_conservacao'])
                ? $_POST['estado_conservacao']
                : null;
            if (!$estado_conservacao) {
                $_SESSION['mensagem_erro'] = "Erro: Estado de Conservação é obrigatório.";
                ob_end_clean();
                header("Location: ../includes/menu.php");
                exit;
            }

            $setor_id = isset($_POST['setor_id']) && !empty($_POST['setor_id'])
                ? (int)$_POST['setor_id']
                : null;
            if (!$setor_id) {
                $_SESSION['mensagem_erro'] = "Erro: Setor é obrigatório.";
                ob_end_clean();
                header("Location: ../includes/menu.php");
                exit;
            }

            $numero_tombamento = !empty($_POST['numero_tombamento']) ? htmlspecialchars($_POST['numero_tombamento'], ENT_QUOTES, 'UTF-8') : null;
            $valor = !empty($_POST['valor']) && is_numeric($_POST['valor']) ? $_POST['valor'] : null;
            $observacoes = !empty($_POST['observacoes']) ? htmlspecialchars($_POST['observacoes'], ENT_QUOTES, 'UTF-8') : null;

            $stmt = $pdo->prepare("INSERT INTO Bem 
                (nome, numero_tombamento, ano_aquisicao, estado_conservacao, valor, observacoes, setor_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['nome'],
                $numero_tombamento,
                $_POST['ano_aquisicao'] ?: null,
                $estado_conservacao,
                $valor,
                $observacoes,
                $setor_id,
            ]);

            $_SESSION['mensagem'] = "Bem cadastrado com sucesso!";
            ob_end_clean();
            header("Location: ../includes/menu.php");
            exit;
        }

        elseif ($action === 'update') {
            if (empty($_POST['id_bem']) || empty($_POST['nome'])) {
                $_SESSION['mensagem_erro'] = "Erro: ID e Nome são obrigatórios.";
                ob_end_clean();
                header("Location: ../views/editar_bem.php");
                exit;
            }

            $estado_conservacao = isset($_POST['estado_conservacao']) && !empty($_POST['estado_conservacao'])
                ? $_POST['estado_conservacao']
                : null;
            if (!$estado_conservacao) {
                $_SESSION['mensagem_erro'] = "Erro: Estado de Conservação é obrigatório.";
                ob_end_clean();
                header("Location: ../views/editar_bem.php");
                exit;
            }

            $setor_id = isset($_POST['setor_id']) && !empty($_POST['setor_id'])
                ? (int)$_POST['setor_id']
                : null;
            if (!$setor_id) {
                $_SESSION['mensagem_erro'] = "Erro: Setor é obrigatório.";
                ob_end_clean();
                header("Location: ../views/editar_bem.php");
                exit;
            }

            $id_bem = (int)$_POST['id_bem'];
            $nome = htmlspecialchars($_POST['nome'], ENT_QUOTES, 'UTF-8');
            $numero_tombamento = !empty($_POST['numero_tombamento']) ? htmlspecialchars($_POST['numero_tombamento'], ENT_QUOTES, 'UTF-8') : null;
            $ano_aquisicao = !empty($_POST['ano_aquisicao']) ? (int)$_POST['ano_aquisicao'] : null;
            $valor = !empty($_POST['valor']) && is_numeric($_POST['valor']) ? (float)$_POST['valor'] : null;
            $observacoes = !empty($_POST['observacoes']) ? htmlspecialchars($_POST['observacoes'], ENT_QUOTES, 'UTF-8') : null;

            // Check for duplicate numero_tombamento only if provided
            if ($numero_tombamento !== null) {
                $stmt = $pdo->prepare("SELECT id_bem FROM Bem WHERE numero_tombamento = ? AND id_bem != ?");
                $stmt->execute([$numero_tombamento, $id_bem]);
                if ($stmt->rowCount() > 0) {
                    $_SESSION['mensagem_erro'] = "Erro: O Número de Tombamento já está em uso.";
                    ob_end_clean();
                    header("Location: ../views/editar_bem.php");
                    exit;
                }
            }

            $stmt = $pdo->prepare("UPDATE Bem SET 
                nome = ?, 
                numero_tombamento = ?, 
                ano_aquisicao = ?, 
                estado_conservacao = ?, 
                valor = ?, 
                observacoes = ?, 
                setor_id = ? 
                WHERE id_bem = ?");
            $stmt->execute([
                $nome,
                $numero_tombamento,
                $ano_aquisicao,
                $estado_conservacao,
                $valor,
                $observacoes,
                $setor_id,
                $id_bem
            ]);

            $_SESSION['mensagem'] = $stmt->rowCount() > 0 
                ? "Bem atualizado com sucesso!" 
                : "Nenhuma alteração foi realizada.";
            ob_end_clean();
            header("Location: ../views/editar_bem.php");
            exit;
        }

        elseif (isset($_POST['filtrar'])) {
            $sql = "SELECT Bem.*, Setor.nome AS setor_nome 
                    FROM Bem 
                    LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor 
                    WHERE estado_conservacao != 'Lixeira'";
            $params = [];

            if (!empty($_POST['nome'])) {
                $sql .= " AND Bem.nome LIKE :nome";
                $params[':nome'] = "%" . $_POST['nome'] . "%";
            }
            if (!empty($_POST['cor'])) {
                $sql .= " AND Bem.observacoes LIKE :cor";
                $params[':cor'] = "%" . $_POST['cor'] . "%";
            }
            if (!empty($_POST['estado'])) {
                $sql .= " AND Bem.estado_conservacao = :estado";
                $params[':estado'] = $_POST['estado'];
            }
            if (!empty($_POST['setor'])) {
                $sql .= " AND Setor.id_setor = :setor";
                $params[':setor'] = $_POST['setor'];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['resultados_filtro'] = $resultados;
            ob_end_clean();
            header("Location: ../views/relatorio_pesquisa.php");
            exit;
        }

        elseif (in_array($action, ['delete', 'excluir_permanente', 'restaurar'])) {
            if (!isset($_POST['id_bem']) || !is_numeric($_POST['id_bem'])) {
                sendResponse(false, 'ID do item inválido ou não fornecido.');
            }

            $id_bem = (int)$_POST['id_bem'];

            $stmt = $pdo->prepare("SELECT id_bem FROM Bem WHERE id_bem = ?");
            $stmt->execute([$id_bem]);
            if ($stmt->rowCount() === 0) {
                sendResponse(false, 'Item não encontrado.');
            }

            if ($action === 'delete') {
                $stmt = $pdo->prepare("UPDATE Bem SET estado_conservacao = 'Lixeira' WHERE id_bem = ?");
                $stmt->execute([$id_bem]);
                sendResponse($stmt->rowCount() > 0, $stmt->rowCount() > 0 ? '' : 'Erro ao mover item para a lixeira.');
            } elseif ($action === 'excluir_permanente') {
                $stmt = $pdo->prepare("DELETE FROM relatorio_bem WHERE id_bem = ?");
                $stmt->execute([$id_bem]);
                $stmt = $pdo->prepare("DELETE FROM Bem WHERE id_bem = ?");
                $stmt->execute([$id_bem]);
                sendResponse($stmt->rowCount() > 0, $stmt->rowCount() > 0 ? '' : 'Erro ao excluir item permanentemente.');
            } elseif ($action === 'restaurar') {
                $stmt = $pdo->prepare("SELECT id_bem FROM Bem WHERE id_bem = ? AND estado_conservacao = 'Lixeira'");
                $stmt->execute([$id_bem]);
                if ($stmt->rowCount() > 0) {
                    $stmt = $pdo->prepare("UPDATE Bem SET estado_conservacao = 'Bom' WHERE id_bem = ?");
                    $stmt->execute([$id_bem]);
                    sendResponse($stmt->rowCount() > 0, $stmt->rowCount() > 0 ? '' : 'Erro ao restaurar item.');
                } else {
                    sendResponse(false, 'Item não encontrado na lixeira.');
                }
            }
        } else {
            sendResponse(false, 'Ação inválida ou não especificada.');
        }
    } else {
        sendResponse(false, 'Método HTTP não permitido. Use POST.');
    }
} catch (PDOException $e) {
    sendResponse(false, 'Erro no banco de dados: ' . $e->getMessage());
} catch (Exception $e) {
    sendResponse(false, 'Erro inesperado: ' . $e->getMessage());
}
?>