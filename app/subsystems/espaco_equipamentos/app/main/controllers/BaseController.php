<?php
class BaseController {
    protected $conn;
    protected $response = ['success' => false, 'message' => ''];

    public function __construct() {
        try {
            ob_start();
            session_start();
            header('Content-Type: application/json; charset=utf-8');
            ini_set('display_errors', 0);
            error_reporting(E_ALL);
            ini_set('log_errors', 1);
            ini_set('error_log', __DIR__ . '/php_errors.log');

            require_once __DIR__ . '/../../config/database.php';
            $this->conn = getDatabaseConnection();
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            error_log("[BaseController] Conexão com o banco estabelecida com sucesso");
        } catch (PDOException $e) {
            error_log("[BaseController] Erro na conexão com o banco: " . $e->getMessage());
            $this->sendError('Erro ao conectar ao servidor');
        } catch (Exception $e) {
            error_log("[BaseController] Erro não tratado: " . $e->getMessage());
            $this->sendError('Erro interno do servidor');
        }
    }

    protected function sendResponse($data = null) {
        try {
            if (ob_get_length()) ob_end_clean();
            if ($data !== null) {
                $this->response = array_merge($this->response, $data);
            }
            echo json_encode($this->response);
            exit;
        } catch (Exception $e) {
            error_log("[BaseController] Erro ao enviar resposta: " . $e->getMessage());
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
            exit;
        }
    }

    protected function sendError($message) {
        $this->response['success'] = false;
        $this->response['message'] = $message;
        $this->sendResponse();
    }

    protected function sendSuccess($message, $data = []) {
        $this->response['success'] = true;
        $this->response['message'] = $message;
        $this->sendResponse($data);
    }

    protected function validateRequiredFields($fields) {
        try {
            foreach ($fields as $field) {
                if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    $this->sendError("Campo {$field} é obrigatório");
                }
            }
        } catch (Exception $e) {
            error_log("[BaseController] Erro na validação de campos: " . $e->getMessage());
            $this->sendError('Erro na validação dos campos');
        }
    }
} 