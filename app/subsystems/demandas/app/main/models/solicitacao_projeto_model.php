<?php

require_once(__DIR__ . '/../config/connect.php');
require_once(__DIR__ . '/autorizacao.php');

class solicitacao_projeto_model extends connect {

    private string $table_solicitacoes = 'solicitacoes_projeto';
    private string $table_usuario_projeto = 'usuario_projeto';
    private string $table_usuarios = 'usuarios';
    private string $table_projetos = 'projetos';
    private autorizacao $auth;

    function __construct() {
        parent::__construct();
        $this->auth = new autorizacao();
    }

    /**
     * Criar solicitação de participação em projeto
     * 
     * @param int $id_usuario ID do usuário solicitante
     * @param int $id_projeto ID do projeto
     * @param string|null $mensagem Mensagem da solicitação
     * @return array ['status' => 1|2|3, 'id' => int, 'message' => string]
     */
    public function criarSolicitacao(int $id_usuario, int $id_projeto, ?string $mensagem = null): array {
        try {
            // Verificar se usuário existe
            $stmt_user = $this->conn->prepare("SELECT 1 FROM {$this->table_usuarios} WHERE id = :id LIMIT 1");
            $stmt_user->bindValue(':id', $id_usuario, PDO::PARAM_INT);
            $stmt_user->execute();
            if (!$stmt_user->fetchColumn()) {
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            // Verificar se projeto existe
            $stmt_proj = $this->conn->prepare("SELECT 1 FROM {$this->table_projetos} WHERE id = :id AND ativo = 1 LIMIT 1");
            $stmt_proj->bindValue(':id', $id_projeto, PDO::PARAM_INT);
            $stmt_proj->execute();
            if (!$stmt_proj->fetchColumn()) {
                return ['status' => 2, 'message' => 'Projeto não encontrado ou inativo.'];
            }

            // Verificar se usuário já está vinculado
            $stmt_check_vinculo = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_usuario_projeto} 
                 WHERE id_usuario = :id_usuario AND id_projeto = :id_projeto AND ativo = 1 LIMIT 1"
            );
            $stmt_check_vinculo->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_check_vinculo->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_check_vinculo->execute();
            if ($stmt_check_vinculo->fetchColumn()) {
                return ['status' => 2, 'message' => 'Você já está vinculado a este projeto.'];
            }

            // Verificar se já existe solicitação pendente
            $stmt_check_pendente = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_solicitacoes} 
                 WHERE id_usuario = :id_usuario AND id_projeto = :id_projeto AND status = 'pendente' LIMIT 1"
            );
            $stmt_check_pendente->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_check_pendente->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_check_pendente->execute();
            if ($stmt_check_pendente->fetchColumn()) {
                return ['status' => 2, 'message' => 'Você já possui uma solicitação pendente para este projeto.'];
            }

            // Validar mensagem
            $mensagem = $mensagem ? trim($mensagem) : null;
            if ($mensagem && strlen($mensagem) > 500) {
                return ['status' => 2, 'message' => 'Mensagem muito longa (máximo 500 caracteres).'];
            }

            // Criar solicitação
            $stmt_insert = $this->conn->prepare(
                 "INSERT INTO {$this->table_solicitacoes} (id_usuario, id_projeto, status, mensagem)
                  VALUES (:id_usuario, :id_projeto, 'pendente', :mensagem)"
            );
            $stmt_insert->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_insert->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_insert->bindValue(':mensagem', $mensagem);

            if (!$stmt_insert->execute()) {
                return ['status' => 3, 'message' => 'Erro ao criar solicitação.'];
            }

            return [
                'status' => 1,
                'id' => (int) $this->conn->lastInsertId(),
                'message' => 'Solicitação criada com sucesso.'
            ];

        } catch (PDOException $e) {
            error_log('Erro ao criar solicitação: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao criar solicitação.'];
        }
    }

    /**
     * Obter informações de uma solicitação
     * 
     * @param int $id_solicitacao ID da solicitação
     * @return array|null Array com dados da solicitação ou null
     */
    public function obterSolicitacao(int $id_solicitacao): ?array {
        try {
            $stmt = $this->conn->prepare(
                "SELECT s.*, 
                        u.nome AS usuario_nome, u.email AS usuario_email, u.tipo AS usuario_tipo,
                        p.nome AS projeto_nome,
                        COALESCE(ad.nome, 'Não decidido') AS admin_nome
                 FROM {$this->table_solicitacoes} s
                 LEFT JOIN {$this->table_usuarios} u ON s.id_usuario = u.id
                 LEFT JOIN {$this->table_projetos} p ON s.id_projeto = p.id
                 LEFT JOIN {$this->table_usuarios} ad ON s.id_administrador_decisao = ad.id
                 WHERE s.id = :id LIMIT 1"
            );
            $stmt->bindValue(':id', $id_solicitacao, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            error_log('Erro ao obter solicitação: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Listar solicitações pendentes (para Administrador Master)
     * 
     * @param int $id_usuario ID do usuário solicitante (validação)
     * @param int|null $id_projeto Filtrar por projeto (opcional)
     * @return array Array de solicitações
     */
    public function listarSolicitacoesPendentes(int $id_usuario, ?int $id_projeto = null): array {
        try {
            // Validar autorização
            if (!$this->auth->usuarioPodeGerenciarSolicitacoes($id_usuario)) {
                return [];
            }

            $sql = "SELECT s.*, 
                        u.nome AS usuario_nome, u.email AS usuario_email, u.tipo AS usuario_tipo,
                        p.nome AS projeto_nome
                 FROM {$this->table_solicitacoes} s
                 LEFT JOIN {$this->table_usuarios} u ON s.id_usuario = u.id
                 LEFT JOIN {$this->table_projetos} p ON s.id_projeto = p.id
                 WHERE s.status = 'pendente'";

            if ($id_projeto !== null) {
                $sql .= " AND s.id_projeto = :id_projeto";
            }

            $sql .= " ORDER BY s.data_solicitacao DESC";

            $stmt = $this->conn->prepare($sql);
            if ($id_projeto !== null) {
                $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            }
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log('Erro ao listar solicitações pendentes: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar solicitações de um usuário específico
     * 
     * @param int $id_usuario ID do usuário
     * @return array Array de solicitações do usuário
     */
    public function listarSolicitacoesUsuario(int $id_usuario): array {
        try {
            $stmt = $this->conn->prepare(
                "SELECT s.*, 
                        p.nome AS projeto_nome, p.ativo AS projeto_ativo
                 FROM {$this->table_solicitacoes} s
                 LEFT JOIN {$this->table_projetos} p ON s.id_projeto = p.id
                 WHERE s.id_usuario = :id_usuario
                 ORDER BY s.data_solicitacao DESC"
            );
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log('Erro ao listar solicitações do usuário: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Aprovar solicitação de participação
     * 
     * @param int $id_solicitacao ID da solicitação
     * @param int $id_administrador ID do Administrador Master
     * @return array ['status' => 1|2|3, 'message' => string]
     */
    public function aprovarSolicitacao(int $id_solicitacao, int $id_administrador): array {
        try {
            // Validar autorização
            if (!$this->auth->usuarioPodeGerenciarSolicitacoes($id_administrador)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para gerenciar solicitações.'];
            }

            // Obter solicitação
            $solicitacao = $this->obterSolicitacao($id_solicitacao);
            if (!$solicitacao) {
                return ['status' => 2, 'message' => 'Solicitação não encontrada.'];
            }

            if ($solicitacao['status'] !== 'pendente') {
                return ['status' => 2, 'message' => 'Esta solicitação já foi decidida.'];
            }

            // Iniciar transação
            $this->conn->beginTransaction();

            try {
                // Atualizar status da solicitação
                $stmt_update = $this->conn->prepare(
                    "UPDATE {$this->table_solicitacoes} 
                     SET status = 'aprovada', 
                         id_administrador_decisao = :id_admin, 
                         data_decisao = NOW() 
                     WHERE id = :id_solicitacao"
                );
                $stmt_update->bindValue(':id_admin', $id_administrador, PDO::PARAM_INT);
                $stmt_update->bindValue(':id_solicitacao', $id_solicitacao, PDO::PARAM_INT);

                if (!$stmt_update->execute()) {
                    $this->conn->rollBack();
                    return ['status' => 3, 'message' => 'Erro ao aprovar solicitação.'];
                }

                // Criar vínculo
                $stmt_vinculo = $this->conn->prepare(
                    "INSERT INTO {$this->table_usuario_projeto} (id_usuario, id_projeto, vinculado_por, ativo)
                     VALUES (:id_usuario, :id_projeto, :id_admin, 1)"
                );
                $stmt_vinculo->bindValue(':id_usuario', $solicitacao['id_usuario'], PDO::PARAM_INT);
                $stmt_vinculo->bindValue(':id_projeto', $solicitacao['id_projeto'], PDO::PARAM_INT);
                $stmt_vinculo->bindValue(':id_admin', $id_administrador, PDO::PARAM_INT);

                if (!$stmt_vinculo->execute()) {
                    $this->conn->rollBack();
                    return ['status' => 3, 'message' => 'Erro ao criar vínculo.'];
                }

                $this->conn->commit();
                return ['status' => 1, 'message' => 'Solicitação aprovada com sucesso.'];

            } catch (PDOException $e) {
                $this->conn->rollBack();
                throw $e;
            }

        } catch (PDOException $e) {
            error_log('Erro ao aprovar solicitação: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao aprovar solicitação.'];
        }
    }

    /**
     * Recusar solicitação de participação
     * 
     * @param int $id_solicitacao ID da solicitação
     * @param int $id_administrador ID do Administrador Master
     * @param string|null $motivo Motivo da recusa
     * @return array ['status' => 1|2|3, 'message' => string]
     */
    public function recusarSolicitacao(int $id_solicitacao, int $id_administrador, ?string $motivo = null): array {
        try {
            // Validar autorização
            if (!$this->auth->usuarioPodeGerenciarSolicitacoes($id_administrador)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para gerenciar solicitações.'];
            }

            // Obter solicitação
            $solicitacao = $this->obterSolicitacao($id_solicitacao);
            if (!$solicitacao) {
                return ['status' => 2, 'message' => 'Solicitação não encontrada.'];
            }

            if ($solicitacao['status'] !== 'pendente') {
                return ['status' => 2, 'message' => 'Esta solicitação já foi decidida.'];
            }

            $motivo = $motivo ? trim($motivo) : null;

            // Atualizar status da solicitação
            $stmt_update = $this->conn->prepare(
                "UPDATE {$this->table_solicitacoes} 
                 SET status = 'recusada', 
                     id_administrador_decisao = :id_admin, 
                     data_decisao = NOW(),
                     mensagem = :motivo 
                 WHERE id = :id_solicitacao"
            );
            $stmt_update->bindValue(':id_admin', $id_administrador, PDO::PARAM_INT);
            $stmt_update->bindValue(':motivo', $motivo);
            $stmt_update->bindValue(':id_solicitacao', $id_solicitacao, PDO::PARAM_INT);

            if (!$stmt_update->execute()) {
                return ['status' => 3, 'message' => 'Erro ao recusar solicitação.'];
            }

            return ['status' => 1, 'message' => 'Solicitação recusada.'];

        } catch (PDOException $e) {
            error_log('Erro ao recusar solicitação: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao recusar solicitação.'];
        }
    }

    /**
     * Obter status da solicitação de um usuário para um projeto
     * 
     * @param int $id_usuario ID do usuário
     * @param int $id_projeto ID do projeto
     * @return string|null 'pendente', 'aprovada', 'recusada', ou null
     */
    public function obterStatusSolicitacao(int $id_usuario, int $id_projeto): ?string {
        try {
            $stmt = $this->conn->prepare(
                "SELECT status FROM {$this->table_solicitacoes} 
                 WHERE id_usuario = :id_usuario AND id_projeto = :id_projeto 
                 ORDER BY data_solicitacao DESC LIMIT 1"
            );
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchColumn() ?: null;

        } catch (PDOException $e) {
            error_log('Erro ao obter status de solicitação: ' . $e->getMessage());
            return null;
        }
    }
}
?>
