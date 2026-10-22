<?php

require_once(__DIR__ . '/../config/connect.php');
require_once(__DIR__ . '/autorizacao.php');

class categoria_model extends connect {
    private string $table = 'categorias_projeto';
    private autorizacao $auth;

    public function __construct() {
        parent::__construct();
        $this->auth = new autorizacao();
    }

    public function listar(int $id_projeto, int $id_usuario): array {
        if (!$this->auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            return [];
        }

        $stmt = $this->conn->prepare(
            "SELECT id, nome FROM {$this->table}
             WHERE id_projeto = :id_projeto AND ativo = 1
             ORDER BY nome ASC"
        );
        $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function criar(int $id_projeto, string $nome, int $id_usuario): array {
        if (!$this->auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            return ['status' => 2, 'message' => 'Você não tem acesso a este projeto.'];
        }

        $nome = trim($nome);
        if (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
            return ['status' => 2, 'message' => 'A categoria deve ter entre 2 e 100 caracteres.'];
        }

        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->table} (id_projeto, nome, criado_por)
                 VALUES (:id_projeto, :nome, :criado_por)"
            );
            $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':criado_por', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 1, 'id' => (int) $this->conn->lastInsertId(), 'message' => 'Categoria criada com sucesso.'];
        } catch (PDOException $e) {
            if ((int) $e->errorInfo[1] === 1062) {
                return ['status' => 2, 'message' => 'Essa categoria já existe neste projeto.'];
            }
            error_log('Erro ao criar categoria: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao criar categoria.'];
        }
    }

    public function pertenceAoProjeto(string $nome, int $id_projeto): bool {
        $stmt = $this->conn->prepare(
            "SELECT 1 FROM {$this->table}
             WHERE id_projeto = :id_projeto AND nome = :nome AND ativo = 1 LIMIT 1"
        );
        $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $nome);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    public function excluir(int $id_categoria, int $id_projeto, int $id_usuario): array {
        if (!$this->auth->usuarioPodeAcessarProjeto($id_usuario, $id_projeto)) {
            return ['status' => 2, 'message' => 'Você não tem acesso a este projeto.'];
        }

        try {
            $stmt = $this->conn->prepare(
                "DELETE FROM {$this->table}
                 WHERE id = :id AND id_projeto = :id_projeto"
            );
            $stmt->bindValue(':id', $id_categoria, PDO::PARAM_INT);
            $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return ['status' => 2, 'message' => 'Categoria não encontrada.'];
            }

            return ['status' => 1, 'message' => 'Categoria excluída com sucesso.'];
        } catch (PDOException $e) {
            error_log('Erro ao excluir categoria: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao excluir categoria.'];
        }
    }
}
