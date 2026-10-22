<?php

require_once(__DIR__ . '/../config/connect.php');
require_once(__DIR__ . '/autorizacao.php');

class projeto_model extends connect {

    private string $table_projetos = 'projetos';
    private string $table_usuarios = 'usuarios';
    private string $table_usuario_projeto = 'usuario_projeto';
    private string $table_demandas = 'demandas';
    private autorizacao $auth;

    function __construct() {
        parent::__construct();
        $this->auth = new autorizacao();
    }

    /**
     * Criar novo projeto
     * 
     * Apenas Administrador Master pode criar projetos
     * 
     * @param string $nome Nome do projeto
     * @param string|null $descricao Descrição do projeto
     * @param int $id_usuario_criador ID do usuário criador
     * @return array ['status' => 1|2|3, 'id' => int, 'message' => string]
     */
    public function criarProjeto(string $nome, ?string $descricao, int $id_usuario_criador): array {
        try {
            // Validar autorização
            if (!$this->auth->usuarioPodeGerenciarProjetos($id_usuario_criador)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para criar projetos.'];
            }

            // Validar dados
            $nome = trim($nome);
            if (empty($nome) || strlen($nome) < 3 || strlen($nome) > 100) {
                return ['status' => 2, 'message' => 'Nome do projeto inválido (mín. 3, máx. 100 caracteres).'];
            }

            $descricao = $descricao ? trim($descricao) : null;

            // Verificar se nome já existe
            $stmt_check = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_projetos} WHERE nome = :nome LIMIT 1"
            );
            $stmt_check->bindValue(':nome', $nome);
            $stmt_check->execute();

            if ($stmt_check->fetchColumn()) {
                return ['status' => 2, 'message' => 'Já existe um projeto com esse nome.'];
            }

            // Inserir projeto
            $stmt_insert = $this->conn->prepare(
                "INSERT INTO {$this->table_projetos} (nome, descricao, ativo, criado_por) 
                 VALUES (:nome, :descricao, 1, :criado_por)"
            );
            $stmt_insert->bindValue(':nome', $nome);
            $stmt_insert->bindValue(':descricao', $descricao);
            $stmt_insert->bindValue(':criado_por', $id_usuario_criador, PDO::PARAM_INT);
            
            if (!$stmt_insert->execute()) {
                return ['status' => 3, 'message' => 'Erro ao criar projeto.'];
            }

            $id_projeto = $this->conn->lastInsertId();

            return [
                'status' => 1,
                'id' => (int) $id_projeto,
                'message' => 'Projeto criado com sucesso.'
            ];

        } catch (PDOException $e) {
            error_log('Erro ao criar projeto: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao criar projeto.'];
        }
    }

    /**
     * Atualizar projeto
     * 
     * @param int $id_projeto ID do projeto
     * @param string|null $nome Novo nome (opcional)
     * @param string|null $descricao Nova descrição (opcional)
     * @param int|null $ativo Novo status (1 ou 0, opcional)
     * @param int $id_usuario ID do usuário solicitante
     * @return array ['status' => 1|2|3, 'message' => string]
     */
    public function atualizarProjeto(
        int $id_projeto,
        ?string $nome = null,
        ?string $descricao = null,
        ?int $ativo = null,
        int $id_usuario = 0
    ): array {
        try {
            // Validar autorização
            if (!$this->auth->usuarioPodeGerenciarProjetos($id_usuario)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para editar projetos.'];
            }

            // Verificar se projeto existe
            $projeto = $this->obterProjeto($id_projeto);
            if (!$projeto) {
                return ['status' => 2, 'message' => 'Projeto não encontrado.'];
            }

            $atualizacoes = [];
            $params = [':id_projeto' => $id_projeto];

            if ($nome !== null) {
                $nome = trim($nome);
                if (empty($nome) || strlen($nome) < 3 || strlen($nome) > 100) {
                    return ['status' => 2, 'message' => 'Nome do projeto inválido.'];
                }
                $atualizacoes[] = 'nome = :nome';
                $params[':nome'] = $nome;
            }

            if ($descricao !== null) {
                $descricao = trim($descricao) ?: null;
                $atualizacoes[] = 'descricao = :descricao';
                $params[':descricao'] = $descricao;
            }

            if ($ativo !== null) {
                $ativo = (int) $ativo;
                if ($ativo !== 0 && $ativo !== 1) {
                    return ['status' => 2, 'message' => 'Valor de ativo inválido.'];
                }
                $atualizacoes[] = 'ativo = :ativo';
                $params[':ativo'] = $ativo;
            }

            if (empty($atualizacoes)) {
                return ['status' => 2, 'message' => 'Nenhum campo para atualizar.'];
            }

            $atualizacoes[] = 'data_atualizacao = NOW()';
            $sql = "UPDATE {$this->table_projetos} SET " . implode(', ', $atualizacoes) . " WHERE id = :id_projeto";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute($params)) {
                return ['status' => 3, 'message' => 'Erro ao atualizar projeto.'];
            }

            return ['status' => 1, 'message' => 'Projeto atualizado com sucesso.'];

        } catch (PDOException $e) {
            error_log('Erro ao atualizar projeto: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao atualizar projeto.'];
        }
    }

    /**
     * Obter informações de um projeto
     * 
     * @param int $id_projeto ID do projeto
     * @return array|null Array com dados do projeto ou null
     */
    public function obterProjeto(int $id_projeto): ?array {
        try {
            $stmt = $this->conn->prepare(
                "SELECT id, nome, descricao, ativo, data_criacao, data_atualizacao, criado_por 
                 FROM {$this->table_projetos} 
                 WHERE id = :id_projeto LIMIT 1"
            );
            $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt->execute();

            $projeto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $projeto ?: null;

        } catch (PDOException $e) {
            error_log('Erro ao obter projeto: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Listar todos os projetos com informações adicionais
     * 
     * @param bool $apenas_ativos Se deve listar apenas projetos ativos
     * @return array Array de projetos
     */
    public function listarProjetos(bool $apenas_ativos = true): array {
        try {
            $sql = "SELECT 
                        p.id, 
                        p.nome, 
                        p.descricao, 
                        p.ativo, 
                        p.data_criacao, 
                        p.criado_por,
                        COALESCE(u.nome, 'N/A') AS criado_por_nome,
                        (SELECT COUNT(*) FROM {$this->table_usuario_projeto} up WHERE up.id_projeto = p.id AND up.ativo = 1) AS total_usuarios,
                        (SELECT COUNT(*) FROM {$this->table_demandas} d WHERE d.id_projeto = p.id) AS total_demandas
                    FROM {$this->table_projetos} p
                    LEFT JOIN {$this->table_usuarios} u ON p.criado_por = u.id";
            
            if ($apenas_ativos) {
                $sql .= " WHERE p.ativo = 1";
            }

            $sql .= " ORDER BY p.nome ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log('Erro ao listar projetos: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Excluir projeto e seus vínculos associados
     */
    public function excluirProjeto(int $id_projeto, int $id_usuario): array {
        try {
            if (!$this->auth->usuarioPodeGerenciarProjetos($id_usuario)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para excluir projetos.'];
            }

            $projeto = $this->obterProjeto($id_projeto);
            if (!$projeto) {
                return ['status' => 2, 'message' => 'Projeto não encontrado.'];
            }

            $this->conn->beginTransaction();

            $stmt_vinculos = $this->conn->prepare("DELETE FROM {$this->table_usuario_projeto} WHERE id_projeto = :id_projeto");
            $stmt_vinculos->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_vinculos->execute();

            $stmt_demandas = $this->conn->prepare("UPDATE {$this->table_demandas} SET id_projeto = NULL WHERE id_projeto = :id_projeto");
            $stmt_demandas->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_demandas->execute();

            $stmt_solicitacoes = $this->conn->prepare("DELETE FROM solicitacoes_projeto WHERE id_projeto = :id_projeto");
            $stmt_solicitacoes->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_solicitacoes->execute();

            $stmt_delete = $this->conn->prepare("DELETE FROM {$this->table_projetos} WHERE id = :id_projeto");
            $stmt_delete->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);

            if (!$stmt_delete->execute()) {
                $this->conn->rollBack();
                return ['status' => 3, 'message' => 'Erro ao excluir projeto.'];
            }

            $this->conn->commit();
            return ['status' => 1, 'message' => 'Projeto excluído com sucesso.'];

        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log('Erro ao excluir projeto: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao excluir projeto.'];
        }
    }

    /**
     * Vincular usuário a um projeto
     * 
     * @param int $id_usuario ID do usuário
     * @param int $id_projeto ID do projeto
     * @param int $id_usuario_vinculador ID do usuário que está vinculando
     * @return array ['status' => 1|2|3, 'message' => string]
     */
    public function vincularUsuario(int $id_usuario, int $id_projeto, int $id_usuario_vinculador): array {
        try {
            // Validar autorização
            if (!$this->auth->usuarioPodeGerenciarVinculos($id_usuario_vinculador)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para vincular usuários.'];
            }

            // Verificar se projeto existe
            if (!$this->obterProjeto($id_projeto)) {
                return ['status' => 2, 'message' => 'Projeto não encontrado.'];
            }

            // Verificar se usuário existe
            $stmt_user = $this->conn->prepare("SELECT 1 FROM {$this->table_usuarios} WHERE id = :id LIMIT 1");
            $stmt_user->bindValue(':id', $id_usuario, PDO::PARAM_INT);
            $stmt_user->execute();
            if (!$stmt_user->fetchColumn()) {
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            // Inserir vínculo (com tratamento de duplicata)
            $stmt_insert = $this->conn->prepare(
                "INSERT INTO {$this->table_usuario_projeto} (id_usuario, id_projeto, vinculado_por, ativo) 
                 VALUES (:id_usuario, :id_projeto, :vinculado_por, 1)
                 ON DUPLICATE KEY UPDATE ativo = 1, vinculado_por = VALUES(vinculado_por)"
            );
            $stmt_insert->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_insert->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_insert->bindValue(':vinculado_por', $id_usuario_vinculador, PDO::PARAM_INT);
            
            if (!$stmt_insert->execute()) {
                return ['status' => 3, 'message' => 'Erro ao vincular usuário.'];
            }

            return ['status' => 1, 'message' => 'Usuário vinculado com sucesso.'];

        } catch (PDOException $e) {
            error_log('Erro ao vincular usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao vincular usuário.'];
        }
    }

    public function sincronizarVinculosUsuario(int $id_usuario, array $ids_projetos, int $id_usuario_vinculador): array {
        try {
            if (!$this->auth->usuarioPodeGerenciarVinculos($id_usuario_vinculador)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para gerenciar vínculos.'];
            }

            $stmt_user = $this->conn->prepare("SELECT 1 FROM {$this->table_usuarios} WHERE id = :id LIMIT 1");
            $stmt_user->bindValue(':id', $id_usuario, PDO::PARAM_INT);
            $stmt_user->execute();
            if (!$stmt_user->fetchColumn()) {
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            $ids = [];
            foreach ($ids_projetos as $idProjeto) {
                $idProjeto = (int) $idProjeto;
                if ($idProjeto > 0) {
                    $ids[] = $idProjeto;
                }
            }
            $ids = array_values(array_unique($ids));

            if (empty($ids)) {
                $stmt_limpar = $this->conn->prepare(
                    "UPDATE {$this->table_usuario_projeto} SET ativo = 0 WHERE id_usuario = :id_usuario AND ativo = 1"
                );
                $stmt_limpar->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt_limpar->execute();
                return ['status' => 1, 'message' => 'Vínculos atualizados com sucesso.'];
            }

            foreach ($ids as $id_projeto) {
                $projeto = $this->obterProjeto($id_projeto);
                if (!$projeto) {
                    return ['status' => 2, 'message' => 'Projeto não encontrado: ' . $id_projeto];
                }

                $stmt_insert = $this->conn->prepare(
                    "INSERT INTO {$this->table_usuario_projeto} (id_usuario, id_projeto, vinculado_por, ativo)
                     VALUES (:id_usuario, :id_projeto, :vinculado_por, 1)
                     ON DUPLICATE KEY UPDATE ativo = 1, vinculado_por = VALUES(vinculado_por)"
                );
                $stmt_insert->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt_insert->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
                $stmt_insert->bindValue(':vinculado_por', $id_usuario_vinculador, PDO::PARAM_INT);
                if (!$stmt_insert->execute()) {
                    return ['status' => 3, 'message' => 'Erro ao atualizar vínculo do projeto ' . $id_projeto . '.'];
                }
            }

            $placeholders = implode(',', array_map(
                static fn (int $index): string => ':id_projeto_' . $index,
                array_keys($ids)
            ));
            $stmt_desativar = $this->conn->prepare(
                "UPDATE {$this->table_usuario_projeto} SET ativo = 0
                 WHERE id_usuario = :id_usuario AND ativo = 1 AND id_projeto NOT IN ({$placeholders})"
            );
            $stmt_desativar->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            foreach ($ids as $index => $id_projeto) {
                $stmt_desativar->bindValue(':id_projeto_' . $index, $id_projeto, PDO::PARAM_INT);
            }
            $stmt_desativar->execute();

            return ['status' => 1, 'message' => 'Vínculos atualizados com sucesso.'];
        } catch (PDOException $e) {
            error_log('Erro ao sincronizar vínculos do usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao atualizar vínculos do usuário.'];
        }
    }

    /**
     * Remover vínculo de usuário com projeto
     * 
     * @param int $id_usuario ID do usuário
     * @param int $id_projeto ID do projeto
     * @param int $id_usuario_solicitante ID do usuário solicitante
     * @return array ['status' => 1|2|3, 'message' => string]
     */
    public function removerVinculo(int $id_usuario, int $id_projeto, int $id_usuario_solicitante): array {
        try {
            // Validar autorização
            if (!$this->auth->usuarioPodeGerenciarVinculos($id_usuario_solicitante)) {
                return ['status' => 2, 'message' => 'Você não tem permissão para remover vínculos.'];
            }

            // Verificar se vínculo existe
            $stmt_check = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_usuario_projeto} 
                 WHERE id_usuario = :id_usuario AND id_projeto = :id_projeto LIMIT 1"
            );
            $stmt_check->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_check->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt_check->execute();

            if (!$stmt_check->fetchColumn()) {
                return ['status' => 2, 'message' => 'Vínculo não encontrado.'];
            }

            // Remover vínculo (soft delete)
            $stmt_update = $this->conn->prepare(
                "UPDATE {$this->table_usuario_projeto} 
                 SET ativo = 0 
                 WHERE id_usuario = :id_usuario AND id_projeto = :id_projeto"
            );
            $stmt_update->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_update->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);

            if (!$stmt_update->execute()) {
                return ['status' => 3, 'message' => 'Erro ao remover vínculo.'];
            }

            return ['status' => 1, 'message' => 'Vínculo removido com sucesso.'];

        } catch (PDOException $e) {
            error_log('Erro ao remover vínculo: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao remover vínculo.'];
        }
    }

    /**
     * Listar usuários vinculados a um projeto
     * 
     * @param int $id_projeto ID do projeto
     * @return array Array de usuários vinculados
     */
    public function listarUsuariosProjeto(int $id_projeto): array {
        try {
            $stmt = $this->conn->prepare(
                "SELECT u.id, u.nome, u.email, u.tipo, up.data_vinculo, up.vinculado_por,
                    COALESCE(uv.nome, 'Automático') AS vinculado_por_nome
                 FROM {$this->table_usuario_projeto} up
                 INNER JOIN {$this->table_usuarios} u ON up.id_usuario = u.id
                 LEFT JOIN {$this->table_usuarios} uv ON up.vinculado_por = uv.id
                 WHERE up.id_projeto = :id_projeto AND up.ativo = 1
                 ORDER BY u.nome ASC"
            );
            $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        } catch (PDOException $e) {
            error_log('Erro ao listar usuários do projeto: ' . $e->getMessage());
            return [];
        }
    }

}
?>
