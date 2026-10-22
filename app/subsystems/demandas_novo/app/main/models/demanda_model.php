<?php

require_once(__DIR__ . '/../config/connect.php');
require_once(__DIR__ . '/autorizacao.php');

class demanda_model extends connect {

    // atributos

    private string $table1;
    private string $table2;
    private string $table3;
    private string $table4;
    private autorizacao $auth;

    //metodos

    function __construct()
    {
        parent::__construct();
        $this->table1 = 'usuarios';
        $this->table2 = 'demandas';
        $this->table3 = 'atribuicao';
        $this->table4 = 'pontuacao';
        $this->garantir_coluna_quantidade_alunos();
        $this->garantir_tabela_reabertura();
        $this->auth = new autorizacao();
    }

    private function garantir_tabela_reabertura(): void
    {
        try {
            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS reabertura_demandas (
                    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    id_demanda INT NOT NULL,
                    id_usuario_solicitante INT NOT NULL,
                    status ENUM('pendente', 'aprovada', 'rejeitada') NOT NULL DEFAULT 'pendente',
                    justificativa TEXT NOT NULL,
                    data_solicitacao DATETIME NOT NULL,
                    id_admin_responsavel INT NULL,
                    data_decisao DATETIME NULL,
                    PRIMARY KEY (id),
                    KEY idx_reabertura_demanda (id_demanda),
                    KEY idx_reabertura_status (status),
                    KEY idx_reabertura_solicitante (id_usuario_solicitante)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );

            $colunas = [
                'id_demanda' => 'INT NOT NULL',
                'id_usuario_solicitante' => 'INT NOT NULL',
                'status' => "ENUM('pendente', 'aprovada', 'rejeitada') NOT NULL DEFAULT 'pendente'",
                'justificativa' => 'TEXT NOT NULL',
                'data_solicitacao' => 'DATETIME NOT NULL',
                'id_admin_responsavel' => 'INT NULL',
                'data_decisao' => 'DATETIME NULL'
            ];
            foreach ($colunas as $nome => $definicao) {
                $stmtColuna = $this->conn->prepare("SHOW COLUMNS FROM reabertura_demandas LIKE :nome");
                $stmtColuna->execute([':nome' => $nome]);
                if (!$stmtColuna->fetch(PDO::FETCH_ASSOC)) {
                    $this->conn->exec("ALTER TABLE reabertura_demandas ADD COLUMN {$nome} {$definicao}");
                }
            }
        } catch (PDOException $exception) {
            error_log('Erro ao garantir tabela de reabertura: ' . $exception->getMessage());
        }
    }

    private function garantir_coluna_quantidade_alunos(): void
    {
        $arquivoLock = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'demandas_schema.lock';
        $lock = @fopen($arquivoLock, 'c');

        try {
            if ($lock) {
                flock($lock, LOCK_EX);
            }

            $stmt = $this->conn->prepare("SHOW COLUMNS FROM {$this->table2} LIKE 'quantidade_alunos'");
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                $this->conn->exec("ALTER TABLE {$this->table2} ADD COLUMN quantidade_alunos INT NOT NULL DEFAULT 1 AFTER orientador");
            }
        } catch (PDOException $e) {
            error_log('Erro ao garantir coluna quantidade_alunos: ' . $e->getMessage());
        } finally {
            if ($lock) {
                flock($lock, LOCK_UN);
                fclose($lock);
            }
        }
    }

    public function nova_demanda($titulo, $descricao, $prioridade, $status, $categoria, $prazo, $id_projeto, $id_usuario_responsavel, array $usuarios_atribuidos = [], int $quantidade_alunos = 1)
    {
        try {
            $this->conn->beginTransaction();

            if (array_filter($usuarios_atribuidos, static fn ($id): bool => !is_scalar($id) || !ctype_digit((string) $id))) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Os usuários atribuídos são inválidos.'];
            }

            $usuarios_atribuidos = array_values(array_unique(array_map('intval', $usuarios_atribuidos)));
            $usuarios_validos = [];

            if ($usuarios_atribuidos) {
                $placeholders = implode(',', array_fill(0, count($usuarios_atribuidos), '?'));
                $stmt_usuarios = $this->conn->prepare("SELECT id FROM {$this->table1} WHERE id IN ({$placeholders}) AND ativo = 1");
                $stmt_usuarios->execute($usuarios_atribuidos);
                $usuarios_validos = $stmt_usuarios->fetchAll(PDO::FETCH_ASSOC);

                if (count($usuarios_validos) !== count($usuarios_atribuidos)) {
                    $this->conn->rollBack();
                    return ['status' => 2, 'message' => 'Um ou mais usuários não foram encontrados ou estão inativos.'];
                }

                foreach ($usuarios_atribuidos as $id_usuario) {
                    if (!$this->auth->usuarioPodeSerAtribuidoADemanda($id_usuario, $id_projeto)) {
                        $this->conn->rollBack();
                        return ['status' => 2, 'message' => 'Um ou mais usuários não estão vinculados ao projeto selecionado.'];
                    }
                }
            }

            $quantidade_alunos = max(1, (int) $quantidade_alunos);
            $status = ($usuarios_atribuidos && count($usuarios_atribuidos) >= $quantidade_alunos) ? 'em_andamento' : $status;
            $data_inicio = ($usuarios_atribuidos && count($usuarios_atribuidos) >= $quantidade_alunos) ? 'NOW()' : 'NULL';

            $stmt_insert = $this->conn->prepare("INSERT INTO {$this->table2} (titulo, descricao, prioridade, categoria, status, situacao, data_criacao, data_inicio, prazo, id_projeto, orientador, quantidade_alunos) VALUES (:titulo, :descricao, :prioridade, :categoria, :status, 'pendente', NOW(), {$data_inicio}, :prazo, :id_projeto, :orientador, :quantidade_alunos)");
            $sucesso = $stmt_insert->execute([
                ':titulo' => $titulo,
                ':descricao' => $descricao ?: null,
                ':prioridade' => $prioridade,
                ':categoria' => $categoria,
                ':status' => $status,
                ':prazo' => $prazo->format('Y-m-d H:i:s'),
                ':id_projeto' => $id_projeto,
                ':orientador' => $id_usuario_responsavel,
                ':quantidade_alunos' => $quantidade_alunos,
            ]);

            if (!$sucesso) {
                $this->conn->rollBack();
                return ['status' => 2];
            }

            $id = $this->conn->lastInsertId();

            foreach ($usuarios_atribuidos as $id_usuario) {
                $stmt_atribuicao = $this->conn->prepare("INSERT INTO {$this->table3} (id_demanda, id_usuario, data_inicio, data_fim) VALUES (:id_demanda, :id_usuario, NOW(), NULL)");
                $stmt_atribuicao->execute([
                    ':id_demanda' => $id,
                    ':id_usuario' => $id_usuario
                ]);

                // $this->criar_notificacao(
                //     $id_usuario,
                //     'informacao',
                //     'Nova demanda atribuída',
                //     'Uma nova demanda foi atribuída a você.',
                //     (int) $id
                // );
            }

            $this->conn->commit();

            return [
                "status" => 1,
                "id" => (int)$id,
                "usuarios_atribuidos" => $usuarios_atribuidos,
                "quantidade_alunos" => $quantidade_alunos
            ];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['status' => 3];
        }

    }

    public function listar_demandas(?int $orientador = null)
    {
        try {
            if ($orientador !== null) {
                $stmt_select = $this->conn->prepare("SELECT  d.id, d.titulo, d.descricao, d.prioridade, d.categoria, d.status, d.situacao, d.data_criacao, d.prazo, d.id_projeto, d.orientador AS orientador_id, d.quantidade_alunos,
                COALESCE(u.nome, 'N/A') AS orientador_nome,
                (SELECT COUNT(*) FROM {$this->table3} a WHERE a.id_demanda = d.id AND a.data_fim IS NULL) AS total_assumidos
                FROM {$this->table2} d 
                LEFT JOIN {$this->table1} u ON d.orientador = u.id 
                WHERE d.orientador = :orientador 
                ORDER BY d.data_criacao DESC");
                $stmt_select->bindValue(':orientador', $orientador, PDO::PARAM_INT);

            } else {
                $stmt_select = $this->conn->prepare("SELECT  d.id, d.titulo, d.descricao, d.prioridade, d.categoria, d.status, d.situacao, d.data_criacao, d.prazo, d.id_projeto, d.orientador AS orientador_id, d.quantidade_alunos,
                COALESCE(u.nome, 'N/A') AS orientador_nome,
                (SELECT COUNT(*) FROM {$this->table3} a WHERE a.id_demanda = d.id AND a.data_fim IS NULL) AS total_assumidos
                FROM {$this->table2} d 
                LEFT JOIN {$this->table1} u ON d.orientador = u.id 
                ORDER BY d.data_criacao DESC");
            }

            if (!$stmt_select->execute()) {
                return [];
            }

            $demandas = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
            return $demandas ?: [];
        } catch (PDOException $e) {
            return 3;
        }

    }

    public function listar_orientadores(): array
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT id, nome FROM {$this->table1}
                 WHERE tipo IN ('administrador', 'master')
                 ORDER BY nome ASC"
            );
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function listar_atendentes(): array
    {
        try {
            $stmt = $this->conn->prepare(
                     "SELECT u.id, u.nome, u.tipo,
                                GROUP_CONCAT(DISTINCT up.id_projeto ORDER BY up.id_projeto SEPARATOR ',') AS projetos_ids
                      FROM {$this->table1} u
                      LEFT JOIN usuario_projeto up
                          ON up.id_usuario = u.id AND up.ativo = 1
                      WHERE u.tipo IN ('usuario') AND u.ativo = 1
                      GROUP BY u.id, u.nome, u.tipo
                 ORDER BY nome ASC"
            );
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function assumir_demanda($id_demanda, $id_usuario)
    {
        try {
            $this->finalizar_demandas_expiradas();
            $this->conn->beginTransaction();

            $stmt_check = $this->conn->prepare("SELECT d.id, d.status, d.prazo, d.id_projeto, d.quantidade_alunos FROM {$this->table2} d WHERE d.id = :id LIMIT 1 FOR UPDATE");
            $stmt_check->bindValue(':id', $id_demanda, PDO::PARAM_INT);
            $stmt_check->execute();
            $demanda = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if (!$demanda) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Demanda não encontrada."];
            }

            $quantidade_alunos = max(1, (int) ($demanda['quantidade_alunos'] ?? 1));

            if (!$this->auth->usuarioPodeAcessarProjeto($id_usuario, $demanda['id_projeto'])) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Você não tem permissão para atender esta demanda."];
            }

            if ($demanda['prazo'] !== null) {
                $prazoData = date('Y-m-d', strtotime((string) $demanda['prazo']));
                $dataHoje = date('Y-m-d');

                if ($prazoData < $dataHoje) {
                    $this->conn->rollBack();
                    return ["status" => 2, "message" => "O prazo desta demanda já terminou e ela não pode mais ser assumida."];
                }
            }

            if ($demanda['status'] !== 'aberta' && $demanda['status'] !== 'em_andamento') {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Demanda não está disponível para assunção."];
            }

            $stmt_attr_check = $this->conn->prepare("SELECT id FROM {$this->table3} WHERE id_demanda = :id_demanda AND id_usuario = :id_usuario AND data_fim IS NULL LIMIT 1");
            $stmt_attr_check->bindValue(':id_demanda', $id_demanda, PDO::PARAM_INT);
            $stmt_attr_check->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_attr_check->execute();

            if ($stmt_attr_check->fetchColumn()) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Você já está atribuído a esta demanda."];
            }

            $stmt_insert = $this->conn->prepare("INSERT INTO {$this->table3} (id_demanda, id_usuario, data_inicio, data_fim) VALUES (:id_demanda, :id_usuario, NOW(), NULL)");
            $sucesso = $stmt_insert->execute([
                ':id_demanda' => $id_demanda,
                ':id_usuario' => $id_usuario
            ]);

            if (!$sucesso) {
                $this->conn->rollBack();
                return ["status" => 3, "message" => "Erro ao registrar atribuição."];
            }

            $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table3} WHERE id_demanda = :id_demanda AND data_fim IS NULL");
            $stmt_total->bindValue(':id_demanda', $id_demanda, PDO::PARAM_INT);
            $stmt_total->execute();
            $total_assumidos = (int) $stmt_total->fetchColumn();

            if ($total_assumidos >= $quantidade_alunos && $demanda['status'] !== 'finalizada') {
                $stmt_update = $this->conn->prepare("UPDATE {$this->table2} SET status = 'em_andamento', data_inicio = COALESCE(data_inicio, NOW()) WHERE id = :id");
                $stmt_update->bindValue(':id', $id_demanda, PDO::PARAM_INT);
                $stmt_update->execute();
            }

            if ($total_assumidos < $quantidade_alunos && $demanda['status'] === 'em_andamento') {
                $stmt_update = $this->conn->prepare("UPDATE {$this->table2} SET status = 'aberta' WHERE id = :id");
                $stmt_update->bindValue(':id', $id_demanda, PDO::PARAM_INT);
                $stmt_update->execute();
            }

            $id_atribuicao = $this->conn->lastInsertId();
            $this->conn->commit();

            if ($total_assumidos >= $quantidade_alunos) {
                $message = "Demanda iniciada com sucesso.";
            } else {
                $faltam = max(0, $quantidade_alunos - $total_assumidos);
                $message = $faltam === 1
                    ? "Você assumiu a demanda. Agora aguarda 1 pessoa para o início da demanda."
                    : "Você assumiu a demanda. Agora aguardam-se {$faltam} pessoas para o início da demanda.";
            }

            return [
                "status" => 1,
                "id" => (int)$id_atribuicao,
                "message" => $message
            ];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return ["status" => 4, "message" => "Erro no servidor ao assumir a demanda."];
        }
    }

    public function listar_demandas_filtradas(
        ?string $pesquisa = null,
        ?string $status = null,
        ?string $prioridade = null,
        ?string $categoria = null,
        ?int $id_projeto = null,
        ?string $data_inicio = null,
        ?string $data_fim = null,
        ?int $id_usuario = null
    ): array {
        try {
            $sql = "SELECT d.id, d.titulo, d.descricao, d.prioridade, d.categoria, d.status, d.situacao, d.solucao, d.justificativa_nao_conclusao, d.justificativa_validacao_status, d.id_admin_decisao_justificativa, d.data_decisao_justificativa, d.data_criacao, d.data_finalizacao, d.prazo, d.id_projeto, p.nome AS projeto, d.orientador AS orientador_id, d.quantidade_alunos, u.nome AS orientador_nome,
                (SELECT COUNT(*) FROM {$this->table3} a WHERE a.id_demanda = d.id AND a.data_fim IS NULL) AS total_assumidos,
                CASE WHEN EXISTS (SELECT 1 FROM {$this->table3} a_usuario WHERE a_usuario.id_demanda = d.id AND a_usuario.id_usuario = :id_usuario_atribuido AND a_usuario.data_fim IS NULL) THEN 1 ELSE 0 END AS usuario_atribuido,
                (SELECT GROUP_CONCAT(u2.nome ORDER BY a2.data_inicio ASC SEPARATOR ', ') FROM {$this->table3} a2 INNER JOIN {$this->table1} u2 ON u2.id = a2.id_usuario WHERE a2.id_demanda = d.id AND a2.data_fim IS NULL) AS responsavel_nome
                ,(SELECT MIN(a3.data_inicio) FROM {$this->table3} a3 WHERE a3.id_demanda = d.id) AS responsavel_data_inicio
                ,(SELECT MAX(a4.data_fim) FROM {$this->table3} a4 WHERE a4.id_demanda = d.id) AS responsavel_data_fim
                FROM {$this->table2} d
                LEFT JOIN {$this->table1} u ON d.orientador = u.id
                LEFT JOIN projetos p ON d.id_projeto = p.id
                WHERE 1=1";
            $params = [':id_usuario_atribuido' => $id_usuario];
            $ids_projetos_usuario = [];

            // Filtrar por projetos acessíveis do usuário
            if ($id_usuario !== null) {
                $projetos_do_usuario = $this->auth->listarProjetosDoUsuario($id_usuario);
                $ids_projetos_usuario = array_column($projetos_do_usuario, 'id');

                if (empty($ids_projetos_usuario)) {
                    return [];
                }

                $placeholders = implode(',', $ids_projetos_usuario);
                $sql .= " AND d.id_projeto IN ({$placeholders})";
            }

            if ($id_projeto) {
                if ($id_usuario !== null && !in_array($id_projeto, $ids_projetos_usuario)) {
                    return [];
                }
                $sql .= " AND d.id_projeto = :id_projeto";
                $params[':id_projeto'] = $id_projeto;
            }

            // Pesquisa
            if ($pesquisa) {
                $sql .= " AND (d.titulo LIKE :pesquisa OR d.descricao LIKE :pesquisa)";
                $params[':pesquisa'] = "%{$pesquisa}%";
            }

            // Status
            if ($status) {
                $sql .= " AND d.status = :status";
                $params[':status'] = $status;
            }

            // Prioridade
            if ($prioridade) {
                $sql .= " AND d.prioridade = :prioridade";
                $params[':prioridade'] = $prioridade;
            }

            // Categoria
            if ($categoria) {
                $sql .= " AND d.categoria = :categoria";
                $params[':categoria'] = $categoria;
            }

            // Período
            if ($data_inicio) {
                $sql .= " AND d.data_criacao >= :data_inicio";
                $params[':data_inicio'] = $data_inicio . " 00:00:00";
            }

            if ($data_fim) {
                $sql .= " AND d.data_criacao <= :data_fim";
                $params[':data_fim'] = $data_fim . " 23:59:59";
            }

            $sql .= " ORDER BY d.data_criacao DESC";

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $demandas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $demandas ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    public function listar_demandas_usuario(int $id_usuario): array
    {
        try {
            $sql = "SELECT DISTINCT d.id, d.titulo, d.descricao, d.prioridade, d.categoria, d.status, d.situacao, d.solucao, d.justificativa_nao_conclusao, d.data_criacao, d.data_finalizacao, d.prazo, d.id_projeto, d.orientador AS orientador_id, d.quantidade_alunos,
                (SELECT COUNT(*) FROM {$this->table3} a WHERE a.id_demanda = d.id AND a.data_fim IS NULL) AS total_assumidos,
                CASE WHEN EXISTS (SELECT 1 FROM {$this->table3} a_usuario WHERE a_usuario.id_demanda = d.id AND a_usuario.id_usuario = :id_usuario_atribuido AND a_usuario.data_fim IS NULL) THEN 1 ELSE 0 END AS usuario_atribuido,
                u.nome AS orientador_nome,
                (SELECT GROUP_CONCAT(u2.nome ORDER BY a2.data_inicio ASC SEPARATOR ', ') FROM {$this->table3} a2 INNER JOIN {$this->table1} u2 ON u2.id = a2.id_usuario WHERE a2.id_demanda = d.id AND a2.data_fim IS NULL) AS responsavel_nome
                ,a.data_inicio AS responsavel_data_inicio, a.data_fim AS responsavel_data_fim
                FROM {$this->table2} d INNER JOIN {$this->table1} u ON d.orientador = u.id INNER JOIN {$this->table3} a ON a.id_demanda = d.id AND a.data_fim IS NULL WHERE a.id_usuario = :id_usuario ORDER BY d.data_criacao DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(':id_usuario_atribuido', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    public function listar_registros_usuario(int $id_usuario, ?int $id_projeto = null): array
    {
        try {
            $sql = "SELECT d.id, d.titulo, d.descricao, d.prioridade, d.categoria, d.id_projeto, d.status, d.situacao,
                    d.data_finalizacao, d.prazo, a.data_inicio AS atribuicao_inicio, a.data_fim AS atribuicao_fim
                    FROM {$this->table2} d
                    INNER JOIN {$this->table3} a ON a.id_demanda = d.id
                        WHERE a.id_usuario = :id_usuario
                        " . ($id_projeto !== null ? "AND d.id_projeto = :id_projeto " : '') .
                        "ORDER BY COALESCE(a.data_inicio, d.data_criacao) DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    if ($id_projeto !== null) {
                    $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
                    }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Buscar detalhes da demanda com responsáveis
     */
    public function buscar_demanda_detalhes(int $id_demanda): array
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table2} WHERE id = :id LIMIT 1");
            $stmt->bindValue(':id', $id_demanda, PDO::PARAM_INT);
            $stmt->execute();
            $demanda = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$demanda) {
                return ['status' => 2, 'dados' => null];
            }

            // Buscar responsáveis
            $stmt_resp = $this->conn->prepare("SELECT a.id, a.id_usuario, a.data_inicio, a.data_fim, u.nome, u.email FROM {$this->table3} a INNER JOIN {$this->table1} u ON a.id_usuario = u.id WHERE a.id_demanda = :id_demanda ORDER BY a.data_inicio ASC");
            $stmt_resp->bindValue(':id_demanda', $id_demanda, PDO::PARAM_INT);
            $stmt_resp->execute();
            $responsaveis = $stmt_resp->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => 1,
                'dados' => $demanda,
                'responsaveis' => $responsaveis
            ];
        } catch (PDOException $e) {
            return ['status' => 3, 'dados' => null];
        }
    }

    /**
     * Finalizar atribuição individual do usuário
     */
    public function finalizar_atribuicao_usuario(int $id_demanda, int $id_usuario): array
    {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table3} SET data_fim = NOW() WHERE id_demanda = :id_demanda AND id_usuario = :id_usuario AND data_fim IS NULL");
            $sucesso = $stmt->execute([
                ':id_demanda' => $id_demanda,
                ':id_usuario' => $id_usuario
            ]);

            if (!$sucesso) {
                return ["status" => 3, "message" => "Erro ao finalizar atribuição."];
            }

            if ($stmt->rowCount() === 0) {
                return ["status" => 2, "message" => "Atribuição não encontrada."];
            }

            return [
                "status" => 1,
                "message" => "Atribuição finalizada com sucesso."
            ];
        } catch (PDOException $e) {
            return ["status" => 4, "message" => "Erro no servidor."];
        }
    }

    public function desvincular_atribuicao_admin(int $id_demanda, int $id_atribuicao): array
    {
        try {
            $this->conn->beginTransaction();

            $stmt_demanda = $this->conn->prepare("SELECT status, quantidade_alunos FROM {$this->table2} WHERE id = :id FOR UPDATE");
            $stmt_demanda->execute([':id' => $id_demanda]);
            $demanda = $stmt_demanda->fetch(PDO::FETCH_ASSOC);

            if (!$demanda) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demanda não encontrada.'];
            }

            if ($demanda['status'] === 'finalizada') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demandas finalizadas não possuem atribuições ativas para desvincular.'];
            }

            $stmt_atribuicao = $this->conn->prepare("UPDATE {$this->table3} SET data_fim = NOW() WHERE id = :id_atribuicao AND id_demanda = :id_demanda AND data_fim IS NULL");
            $stmt_atribuicao->execute([
                ':id_atribuicao' => $id_atribuicao,
                ':id_demanda' => $id_demanda
            ]);

            if ($stmt_atribuicao->rowCount() === 0) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Atribuição ativa não encontrada.'];
            }

            $stmt_total = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table3} WHERE id_demanda = :id_demanda AND data_fim IS NULL");
            $stmt_total->execute([':id_demanda' => $id_demanda]);
            $total_assumidos = (int) $stmt_total->fetchColumn();
            $quantidade_alunos = max(1, (int) ($demanda['quantidade_alunos'] ?? 1));
            $novo_status = $total_assumidos >= $quantidade_alunos ? 'em_andamento' : 'aberta';

            $stmt_status = $this->conn->prepare("UPDATE {$this->table2} SET status = :status WHERE id = :id");
            $stmt_status->execute([
                ':status' => $novo_status,
                ':id' => $id_demanda
            ]);

            $this->conn->commit();

            return [
                'status' => 1,
                'message' => 'Usuário desvinculado da demanda.',
                'novo_status' => $novo_status,
                'total_assumidos' => $total_assumidos
            ];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['status' => 4, 'message' => 'Erro no servidor ao desvincular o usuário.'];
        }
    }

    public function listar_atribuicoes_ativas(int $id_demanda): array
    {
        try {
            $stmt = $this->conn->prepare("SELECT a.id, a.id_usuario, u.nome, u.email FROM {$this->table3} a INNER JOIN {$this->table1} u ON u.id = a.id_usuario WHERE a.id_demanda = :id_demanda AND a.data_fim IS NULL ORDER BY a.data_inicio ASC");
            $stmt->execute([':id_demanda' => $id_demanda]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    public function finalizar_demandas_expiradas(): int
    {
        try {
            $stmt = $this->conn->prepare("UPDATE {$this->table2}
                SET status = 'finalizada', situacao = 'nao_concluida',
                    data_finalizacao = NOW(),
                    justificativa_nao_conclusao = 'Prazo encerrado sem conclusão.'
                WHERE status IN ('aberta', 'em_andamento')
                  AND situacao = 'pendente'
                  AND prazo IS NOT NULL
                      AND DATE(prazo) < CURDATE()");
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log('Erro ao finalizar demandas expiradas: ' . $e->getMessage());
            return 0;
        }
    }

    public function concluir_demanda(int $id_demanda, int $id_usuario, string $situacao, ?string $solucao = null, ?string $justificativa = null): array
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("SELECT d.id, d.status, d.situacao, d.prioridade, d.id_projeto, d.quantidade_alunos FROM {$this->table2} d WHERE d.id = :id LIMIT 1 FOR UPDATE");
            $stmt->bindValue(':id', $id_demanda, PDO::PARAM_INT);
            $stmt->execute();
            $demanda = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$demanda) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Demanda não encontrada."];
            }

            $stmt_atribuicao = $this->conn->prepare("SELECT id FROM {$this->table3} WHERE id_demanda = :id_demanda AND id_usuario = :id_usuario AND data_fim IS NULL LIMIT 1");
            $stmt_atribuicao->bindValue(':id_demanda', $id_demanda, PDO::PARAM_INT);
            $stmt_atribuicao->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_atribuicao->execute();

            if (!$stmt_atribuicao->fetchColumn()) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Você não está atribuído a esta demanda."];
            }

            $stmt_total_ativos = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table3} WHERE id_demanda = :id_demanda AND data_fim IS NULL");
            $stmt_total_ativos->execute([':id_demanda' => $id_demanda]);
            $total_ativos = (int) $stmt_total_ativos->fetchColumn();
            $quantidade_alunos = max(1, (int) ($demanda['quantidade_alunos'] ?? 1));

            // Corrige registros antigos ou concorrentes que ficaram abertos mesmo
            // depois de atingirem a quantidade necessária de responsáveis.
            if ($demanda['status'] === 'aberta' && $total_ativos >= $quantidade_alunos) {
                $stmt_sincronizar_status = $this->conn->prepare("UPDATE {$this->table2} SET status = 'em_andamento', data_inicio = COALESCE(data_inicio, NOW()) WHERE id = :id");
                $stmt_sincronizar_status->execute([':id' => $id_demanda]);
                $demanda['status'] = 'em_andamento';
            }

            if ($demanda['status'] !== 'em_andamento') {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "A demanda precisa estar em andamento para ser concluída."];
            }

            $situacoesValidas = ['concluida', 'nao_concluida'];
            if (!in_array($situacao, $situacoesValidas, true)) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Situação inválida."];
            }

            if ($situacao === 'nao_concluida' && empty($justificativa)) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Justificativa é obrigatória para demandas não concluídas."];
            }

            $stmt_responsaveis = $this->conn->prepare("SELECT DISTINCT id_usuario FROM {$this->table3} WHERE id_demanda = :id_demanda AND data_fim IS NULL");
            $stmt_responsaveis->bindValue(':id_demanda', $id_demanda, PDO::PARAM_INT);
            $stmt_responsaveis->execute();
            $responsaveis = array_map('intval', $stmt_responsaveis->fetchAll(PDO::FETCH_COLUMN));

            if (!$responsaveis) {
                $this->conn->rollBack();
                return ["status" => 2, "message" => "Nenhum responsável ativo foi encontrado para esta demanda."];
            }

            $stmt_update = $this->conn->prepare("UPDATE {$this->table2} SET status = 'finalizada', situacao = :situacao, data_finalizacao = NOW(), solucao = :solucao, justificativa_nao_conclusao = :justificativa, justificativa_validacao_status = :validacao_status, id_admin_decisao_justificativa = NULL, data_decisao_justificativa = NULL WHERE id = :id");
            $stmt_update->execute([
                ':id' => $id_demanda,
                ':situacao' => $situacao,
                ':solucao' => $solucao,
                ':justificativa' => $justificativa,
                ':validacao_status' => $situacao === 'nao_concluida' ? 'pendente' : 'pendente'
            ]);

            $stmt_attr = $this->conn->prepare("UPDATE {$this->table3} SET data_fim = NOW() WHERE id_demanda = :id_demanda AND data_fim IS NULL");
            $stmt_attr->execute([
                ':id_demanda' => $id_demanda
            ]);

            $stmtPontuacao = $this->conn->prepare(
                "INSERT INTO {$this->table4} (id_demanda, id_usuario, pontos, situacao)
                 VALUES (:id_demanda, :id_usuario, :pontos, :situacao)
                 ON DUPLICATE KEY UPDATE pontos = VALUES(pontos), situacao = VALUES(situacao), data_registro = NOW()"
            );
            $pesoPrioridade = [
                'baixa' => 1,
                'media' => 2,
                'alta' => 3
            ][$demanda['prioridade']] ?? 1;
            $pontos = $situacao === 'concluida' ? $pesoPrioridade : 0;
            $situacaoPontuacao = $situacao === 'concluida' ? 'concluida' : 'pendente';

            foreach ($responsaveis as $id_responsavel) {
                $stmtPontuacao->execute([
                    ':id_demanda' => $id_demanda,
                    ':id_usuario' => $id_responsavel,
                    ':pontos' => $pontos,
                    ':situacao' => $situacaoPontuacao
                ]);

                // $this->criar_notificacao(
                //     $id_responsavel,
                //     $situacao === 'concluida' ? 'sucesso' : 'alerta',
                //     $situacao === 'concluida' ? 'Demanda concluída' : 'Demanda não concluída',
                //     $situacao === 'concluida'
                //         ? 'Sua demanda foi concluída com sucesso e registrada no histórico.'
                //         : 'A demanda foi registrada como não concluída e sua justificativa aguarda validação.',
                //     (int) $id_demanda
                // );
            }

            $this->conn->commit();
            return ["status" => 1];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            return ["status" => 4, "message" => "Erro no servidor ao concluir a demanda."];
        }
    }

    public function validar_justificativa(int $id_demanda, int $id_admin, string $decisao): array
    {
        try {
            $this->conn->beginTransaction();

            if (!in_array($decisao, ['validada', 'invalidada'], true)) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Decisão de validação inválida.'];
            }

            $stmt = $this->conn->prepare("SELECT d.id, d.status, d.situacao, d.prioridade, d.justificativa_nao_conclusao, d.justificativa_validacao_status, d.orientador FROM {$this->table2} d WHERE d.id = :id FOR UPDATE");
            $stmt->execute([':id' => $id_demanda]);
            $demanda = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$demanda) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demanda não encontrada.'];
            }

            if ((int) $demanda['orientador'] !== $id_admin) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Apenas o administrador responsável pode validar esta justificativa.'];
            }

            if ($demanda['status'] !== 'finalizada' || $demanda['situacao'] !== 'nao_concluida') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'A demanda não é uma finalizada não concluída.'];
            }

            if (empty(trim((string) $demanda['justificativa_nao_conclusao']))) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'A demanda não possui justificativa para validar.'];
            }

            if ($demanda['justificativa_validacao_status'] !== 'pendente') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Esta justificativa já foi analisada.'];
            }

            $stmtResponsaveis = $this->conn->prepare("SELECT DISTINCT id_usuario FROM {$this->table3} WHERE id_demanda = :id_demanda");
            $stmtResponsaveis->execute([':id_demanda' => $id_demanda]);
            $responsaveis = array_map('intval', $stmtResponsaveis->fetchAll(PDO::FETCH_COLUMN));
            if (!$responsaveis) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Nenhum responsável foi encontrado para esta demanda.'];
            }
            $peso = ['baixa' => 1, 'media' => 2, 'alta' => 3][$demanda['prioridade']] ?? 1;
            $pontos = $decisao === 'invalidada' ? -$peso : 0;

            $stmtPontuacao = $this->conn->prepare("INSERT INTO {$this->table4} (id_demanda, id_usuario, pontos, situacao) VALUES (:id_demanda, :id_usuario, :pontos, 'nao_concluida') ON DUPLICATE KEY UPDATE pontos = VALUES(pontos), situacao = VALUES(situacao), data_registro = NOW()");
            foreach ($responsaveis as $id_responsavel) {
                $stmtPontuacao->execute([
                    ':id_demanda' => $id_demanda,
                    ':id_usuario' => $id_responsavel,
                    ':pontos' => $pontos
                ]);
            }

            $stmtUpdate = $this->conn->prepare("UPDATE {$this->table2} SET justificativa_validacao_status = :decisao, id_admin_decisao_justificativa = :id_admin, data_decisao_justificativa = NOW() WHERE id = :id AND justificativa_validacao_status = 'pendente'");
            $stmtUpdate->execute([':decisao' => $decisao, ':id_admin' => $id_admin, ':id' => $id_demanda]);
            if ($stmtUpdate->rowCount() !== 1) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Esta justificativa já foi analisada.'];
            }

            $this->conn->commit();
            return ['status' => 1, 'message' => $decisao === 'validada' ? 'Justificativa validada.' : 'Justificativa invalidada e pontuação atualizada.'];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return ['status' => 3, 'message' => 'Erro ao processar a validação da justificativa.'];
        }
    }

    public function obter_metricas_usuario(int $id_usuario, ?int $id_projeto = null): array
    {
        try {
            $filtroProjetoHistorico = $id_projeto !== null ? ' AND d.id_projeto = :id_projeto_historico' : '';
            $filtroProjetoPontuacao = $id_projeto !== null ? ' AND d.id_projeto = :id_projeto_pontuacao' : '';
            $stmt = $this->conn->prepare(
                "SELECT
                    (
                        SELECT COUNT(DISTINCT d.id)
                        FROM {$this->table2} d
                        INNER JOIN {$this->table3} a ON a.id_demanda = d.id
                        WHERE a.id_usuario = :id_usuario_historico
                          AND d.status = 'finalizada'
                          AND d.situacao = 'concluida'
                          {$filtroProjetoHistorico}
                    ) AS concluidas,
                    COUNT(p.id) AS registradas,
                    COALESCE(SUM(p.pontos), 0) AS pontos,
                    COALESCE(SUM(CASE d.prioridade WHEN 'alta' THEN 3 WHEN 'media' THEN 2 ELSE 1 END), 0) AS pontos_maximos
                 FROM {$this->table4} p
                 INNER JOIN {$this->table2} d ON d.id = p.id_demanda
                 WHERE p.id_usuario = :id_usuario_pontuacao
                   AND d.status = 'finalizada'
                   {$filtroProjetoPontuacao}"
            );
            $parametros = [
                ':id_usuario_historico' => $id_usuario,
                ':id_usuario_pontuacao' => $id_usuario
            ];
            if ($id_projeto !== null) {
                $parametros[':id_projeto_historico'] = $id_projeto;
                $parametros[':id_projeto_pontuacao'] = $id_projeto;
            }
            $stmt->execute($parametros);
            $metricas = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

            return [
                'concluidas' => (int) ($metricas['concluidas'] ?? 0),
                'registradas' => (int) ($metricas['registradas'] ?? 0),
                'pontos' => (int) ($metricas['pontos'] ?? 0),
                'pontos_maximos' => (int) ($metricas['pontos_maximos'] ?? 0),
                'rendimento' => (float) ($metricas['pontos_maximos'] ?? 0) > 0
                    ? max(0, min(100, ((float) $metricas['pontos'] / (float) $metricas['pontos_maximos']) * 100))
                    : 0
            ];
        } catch (PDOException $e) {
            return ['concluidas' => 0, 'registradas' => 0, 'pontos' => 0, 'pontos_maximos' => 0, 'rendimento' => 0];
        }
    }

    public function buscar_demanda($id)
    {
        try {
            if ($id === null) {
                return[
                    "status" => 2,
                    "dados" => null
                ];
            }
            $stmt_select = $this->conn->prepare("SELECT * FROM  {$this->table2} where id = :id");
            $stmt_select->bindValue(":id",$id, PDO::PARAM_INT);
            $stmt_select->execute();
            $demanda = $stmt_select->fetch();

            if (empty($demanda)) {
                return [
                    "status" => 2,
                    "dados" => null
                ];
            }
            
            return [
                "status" => 1,
                "dados" => $demanda
            ];

         } catch (PDOException $e) {
             return [
                "status" => 3,
                "dados" => null
             ];
         }
      }

    public function excluir_demanda(int $id_demanda, int $id_usuario): array
    {
        try {
            $this->conn->beginTransaction();

            $stmtDono = $this->conn->prepare("SELECT orientador, id_projeto FROM {$this->table2} WHERE id = :id_demanda LIMIT 1");
            $stmtDono->execute([':id_demanda' => $id_demanda]);
            $demanda = $stmtDono->fetch(PDO::FETCH_ASSOC);
            if (!$demanda) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demanda não encontrada.'];
            }

            $ehMaster = (new autorizacao())->ehAdministradorMaster($id_usuario);
            $temAcessoAoProjeto = $this->auth->usuarioPodeAcessarProjeto($id_usuario, (int) $demanda['id_projeto']);
            if (!$ehMaster && !$temAcessoAoProjeto) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Você não tem acesso ao projeto desta demanda.'];
            }

            // As tabelas relacionadas não possuem ON DELETE CASCADE.
            $stmtAtribuicao = $this->conn->prepare(
                "DELETE FROM {$this->table3} WHERE id_demanda = :id_demanda"
            );
            $stmtAtribuicao->execute([':id_demanda' => $id_demanda]);

            $stmtPontuacao = $this->conn->prepare(
                "DELETE FROM {$this->table4} WHERE id_demanda = :id_demanda"
            );
            $stmtPontuacao->execute([':id_demanda' => $id_demanda]);

            $stmtReabertura = $this->conn->prepare(
                "DELETE FROM reabertura_demandas WHERE id_demanda = :id_demanda"
            );
            $stmtReabertura->execute([':id_demanda' => $id_demanda]);

            $stmtDemanda = $this->conn->prepare(
                "DELETE FROM {$this->table2} WHERE id = :id_demanda"
            );
            $stmtDemanda->execute([':id_demanda' => $id_demanda]);

            if ($stmtDemanda->rowCount() === 0) {
                $this->conn->rollBack();
                return [
                    'status' => 2,
                    'message' => 'Demanda não encontrada.'
                ];
            }

            $this->conn->commit();

            return ['status' => 1];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            error_log('Erro ao excluir demanda: ' . $e->getMessage());

            return [
                'status' => 3,
                'message' => 'Erro ao excluir demanda.'
            ];
        }
    }

    public function atualizar_demanda(
        int $id_demanda,
        string $titulo,
        string $descricao,
        string $prioridade,
        string $categoria,
        DateTime $prazo,
        ?int $id_projeto,
        int $quantidade_alunos,
        int $id_usuario
    ): array {
        try {
            $this->conn->beginTransaction();

            $stmtDono = $this->conn->prepare("SELECT orientador, status FROM {$this->table2} WHERE id = :id_demanda LIMIT 1 FOR UPDATE");
            $stmtDono->execute([':id_demanda' => $id_demanda]);
            $demandaAtual = $stmtDono->fetch(PDO::FETCH_ASSOC);
            if (!$demandaAtual) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demanda não encontrada.'];
            }

            if ((int) $demandaAtual['orientador'] !== $id_usuario) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Você só pode editar demandas criadas por você.'];
            }

            if ($demandaAtual['status'] === 'finalizada') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demandas finalizadas não podem ser editadas.'];
            }

            $quantidade_alunos = max(1, min(10, $quantidade_alunos));
            $stmtTotal = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table3} WHERE id_demanda = :id_demanda AND data_fim IS NULL");
            $stmtTotal->execute([':id_demanda' => $id_demanda]);
            $total_assumidos = (int) $stmtTotal->fetchColumn();
            $novo_status = $demandaAtual['status'] === 'em_andamento'
                ? 'em_andamento'
                : ($total_assumidos >= $quantidade_alunos ? 'em_andamento' : 'aberta');

            $stmt = $this->conn->prepare(
                "UPDATE {$this->table2}
                 SET titulo = :titulo,
                     descricao = :descricao,
                     prioridade = :prioridade,
                     categoria = :categoria,
                     prazo = :prazo,
                     id_projeto = :id_projeto,
                     quantidade_alunos = :quantidade_alunos,
                     status = :status,
                     data_inicio = CASE WHEN :status_inicio = 'em_andamento' THEN COALESCE(data_inicio, NOW()) ELSE data_inicio END
                 WHERE id = :id_demanda"
            );

            $stmt->execute([
                ':id_demanda' => $id_demanda,
                ':titulo' => $titulo,
                ':descricao' => $descricao ?: null,
                ':prioridade' => $prioridade,
                ':categoria' => $categoria,
                ':prazo' => $prazo->format('Y-m-d H:i:s'),
                ':id_projeto' => $id_projeto,
                ':quantidade_alunos' => $quantidade_alunos,
                ':status' => $novo_status,
                ':status_inicio' => $novo_status
            ]);

            if ($stmt->rowCount() === 0) {
                $stmtExists = $this->conn->prepare("SELECT id FROM {$this->table2} WHERE id = :id_demanda");
                $stmtExists->execute([':id_demanda' => $id_demanda]);

                if (!$stmtExists->fetchColumn()) {
                    $this->conn->rollBack();
                    return ['status' => 2, 'message' => 'Demanda não encontrada.'];
                }
            }

            $this->conn->commit();

            return ['status' => 1, 'novo_status' => $novo_status, 'total_assumidos' => $total_assumidos];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['status' => 3, 'message' => 'Erro ao atualizar demanda.'];
        }
    }

    /**
     * Buscar detalhes da demanda com responsáveis
     */
    /**
     * Listar demandas finalizadas (histórico) com filtros
     */
    public function listar_historico(
        ?string $pesquisa = null,
        ?string $prioridade = null,
        ?string $categoria = null,
        ?int $id_projeto = null,
        ?string $situacao = null,
        ?int $id_responsavel = null,
        ?string $data_inicio = null,
        ?string $data_fim = null,
        ?int $id_usuario = null
    ): array {
        try {
            $sql = "SELECT d.id, d.titulo, d.prioridade, d.categoria, d.id_projeto, d.status, d.situacao, d.data_criacao, d.data_inicio, d.data_finalizacao, d.solucao, d.justificativa_nao_conclusao FROM {$this->table2} d WHERE d.status = 'finalizada'";
            $params = [];

            if ($id_usuario !== null && !$this->auth->ehAdministradorMaster($id_usuario)) {
                $projetos = $this->auth->listarProjetosDoUsuario($id_usuario);
                $idsProjetos = array_values(array_filter(array_map('intval', array_column($projetos, 'id'))));
                if (!$idsProjetos) {
                    return [];
                }
                $sql .= ' AND d.id_projeto IN (' . implode(',', $idsProjetos) . ')';
            }

            // Pesquisa por ID ou título
            if ($pesquisa) {
                $sql .= " AND (d.id LIKE :pesquisa OR d.titulo LIKE :pesquisa)";
                $params[':pesquisa'] = "%{$pesquisa}%";
            }

            // Prioridade
            if ($prioridade) {
                $sql .= " AND d.prioridade = :prioridade";
                $params[':prioridade'] = $prioridade;
            }

            // Categoria
            if ($categoria) {
                $sql .= " AND d.categoria = :categoria";
                $params[':categoria'] = $categoria;
            }

            // Projeto
            if ($id_projeto) {
                $sql .= " AND d.id_projeto = :id_projeto";
                $params[':id_projeto'] = $id_projeto;
            }

            // Situacao
            if ($situacao) {
                $sql .= " AND d.situacao = :situacao";
                $params[':situacao'] = $situacao;
            }

            // Responsável
            if ($id_responsavel) {
                $sql .= " AND d.id IN (SELECT id_demanda FROM {$this->table3} WHERE id_usuario = :id_responsavel)";
                $params[':id_responsavel'] = $id_responsavel;
            }

            // Período
            if ($data_inicio) {
                $sql .= " AND d.data_criacao >= :data_inicio";
                $params[':data_inicio'] = $data_inicio . " 00:00:00";
            }

            if ($data_fim) {
                $sql .= " AND d.data_criacao <= :data_fim";
                $params[':data_fim'] = $data_fim . " 23:59:59";
            }

            $sql .= " ORDER BY d.data_finalizacao DESC";

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $demandas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calcular tempo de atendimento para cada demanda
            foreach ($demandas as &$d) {
                if ($d['data_inicio'] && $d['data_finalizacao']) {
                    $inicio = new \DateTime($d['data_inicio']);
                    $fim = new \DateTime($d['data_finalizacao']);
                    $diff = $fim->diff($inicio);
                    $d['tempo_atendimento'] = ($diff->days ?? 0) . ' dia(s), ' . $diff->h . ' hora(s), ' . $diff->i . ' minuto(s)';
                }
            }

            return $demandas ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Solicitar reabertura de uma demanda
     */
    public function solicitar_reabertura(int $id_demanda, int $id_usuario, string $justificativa): array
    {
        try {
            $this->conn->beginTransaction();

            // Verificar se a demanda existe e está finalizada
            $stmt = $this->conn->prepare("SELECT id, status, situacao, id_projeto FROM {$this->table2} WHERE id = :id");
            $stmt->bindValue(':id', $id_demanda, PDO::PARAM_INT);
            $stmt->execute();
            $demanda = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$demanda) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demanda não encontrada.'];
            }

            if (!$this->auth->usuarioPodeAcessarProjeto($id_usuario, (int) $demanda['id_projeto'])) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Você não tem acesso ao projeto desta demanda.'];
            }

            if ($demanda['status'] !== 'finalizada') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Apenas demandas finalizadas podem ser reabertas.'];
            }

            // Se situacao for concluida, nao permite reabertura
            if ($demanda['situacao'] === 'concluida') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demandas concluídas não podem ser reabertas.'];
            }

            // Verificar se já existe solicitação pendente
            $stmtExist = $this->conn->prepare("SELECT id FROM reabertura_demandas WHERE id_demanda = :id_demanda AND status = 'pendente'");
            $stmtExist->bindValue(':id_demanda', $id_demanda, PDO::PARAM_INT);
            $stmtExist->execute();

            if ($stmtExist->fetchColumn()) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Já existe uma solicitação de reabertura pendente para esta demanda.'];
            }

            // Criar solicitação de reabertura
            $stmtInsert = $this->conn->prepare("INSERT INTO reabertura_demandas (id_demanda, id_usuario_solicitante, status, justificativa, data_solicitacao) VALUES (:id_demanda, :id_usuario, 'pendente', :justificativa, NOW())");
            $sucesso = $stmtInsert->execute([
                ':id_demanda' => $id_demanda,
                ':id_usuario' => $id_usuario,
                ':justificativa' => $justificativa
            ]);

            if (!$sucesso) {
                $this->conn->rollBack();
                return ['status' => 3, 'message' => 'Erro ao criar solicitação.'];
            }

            $this->conn->commit();
            return ['status' => 1, 'message' => 'Solicitação de reabertura criada com sucesso.'];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['status' => 3, 'message' => 'Erro no servidor.'];
        }
    }

    public function reabrir_demanda(int $id_demanda, int $id_usuario, DateTime $prazo): array
    {
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare("SELECT status, situacao, id_projeto FROM {$this->table2} WHERE id = :id FOR UPDATE");
            $stmt->execute([':id' => $id_demanda]);
            $demanda = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$demanda) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Demanda não encontrada.'];
            }
            if (!$this->auth->usuarioPodeAcessarProjeto($id_usuario, (int) $demanda['id_projeto'])) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Você não tem acesso ao projeto desta demanda.'];
            }
            if ($demanda['status'] !== 'finalizada' || $demanda['situacao'] !== 'nao_concluida') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Apenas demandas finalizadas como não concluídas podem ser reabertas.'];
            }

            $stmtUpdate = $this->conn->prepare(
                "UPDATE {$this->table2} SET status = 'aberta', situacao = 'pendente', data_finalizacao = NULL, data_inicio = NULL, prazo = :prazo WHERE id = :id"
            );
            $stmtUpdate->execute([
                ':id' => $id_demanda,
                ':prazo' => $prazo->format('Y-m-d H:i:s')
            ]);

            $stmtPontuacao = $this->conn->prepare("DELETE FROM {$this->table4} WHERE id_demanda = :id_demanda");
            $stmtPontuacao->execute([':id_demanda' => $id_demanda]);
            $stmtReabertura = $this->conn->prepare("DELETE FROM reabertura_demandas WHERE id_demanda = :id_demanda AND status = 'pendente'");
            $stmtReabertura->execute([':id_demanda' => $id_demanda]);

            $this->conn->commit();
            return ['status' => 1, 'message' => 'Demanda reaberta com o novo prazo.'];
        } catch (PDOException $exception) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log('Erro ao reabrir demanda: ' . $exception->getMessage());
            return ['status' => 3, 'message' => 'Erro ao reabrir a demanda.'];
        }
    }

    /**
     * Listar solicitações de reabertura pendentes
     */
    public function listar_solicitacoes_reabertura_pendentes(?int $id_admin = null): array
    {
        try {
            $sql = "SELECT r.id, r.id_demanda, r.id_usuario_solicitante, r.status, r.justificativa, r.data_solicitacao,
                           d.titulo AS demanda_titulo, d.situacao, u.nome AS usuario_nome, u.email AS usuario_email
                    FROM reabertura_demandas r
                    INNER JOIN {$this->table2} d ON r.id_demanda = d.id
                    INNER JOIN {$this->table1} u ON r.id_usuario_solicitante = u.id
                    WHERE r.status = 'pendente'";

            if ($id_admin !== null && !$this->auth->ehAdministradorMaster($id_admin)) {
                $projetos = $this->auth->listarProjetosDoUsuario($id_admin);
                $idsProjetos = array_values(array_filter(array_map('intval', array_column($projetos, 'id'))));
                if (!$idsProjetos) {
                    return [];
                }
                $sql .= ' AND d.id_projeto IN (' . implode(',', $idsProjetos) . ')';
            }

            $sql .= "
                    ORDER BY r.data_solicitacao ASC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Aprovar solicitação de reabertura
     */
    public function aprovar_reabertura(int $id_solicitacao, int $id_admin): array
    {
        try {
            $this->conn->beginTransaction();

            // Verificar solicitação
            $stmt = $this->conn->prepare("SELECT r.id_demanda, r.status, d.id_projeto FROM reabertura_demandas r INNER JOIN {$this->table2} d ON d.id = r.id_demanda WHERE r.id = :id");
            $stmt->bindValue(':id', $id_solicitacao, PDO::PARAM_INT);
            $stmt->execute();
            $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$solicitacao) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Solicitação não encontrada.'];
            }

            if ($solicitacao['status'] !== 'pendente') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Solicitação já foi processada.'];
            }

            if (!$this->auth->usuarioPodeAcessarProjeto($id_admin, (int) $solicitacao['id_projeto'])) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Você não tem acesso ao projeto desta demanda.'];
            }

            $id_demanda = $solicitacao['id_demanda'];

            // Atualizar solicitação
            $stmtUpdateSol = $this->conn->prepare("UPDATE reabertura_demandas SET status = 'aprovada', id_admin_responsavel = :id_admin, data_decisao = NOW() WHERE id = :id");
            $stmtUpdateSol->execute([
                ':id' => $id_solicitacao,
                ':id_admin' => $id_admin
            ]);

            // Reabrir demanda
            $stmtUpdateDem = $this->conn->prepare("UPDATE {$this->table2} SET status = 'aberta', situacao = 'pendente', data_finalizacao = NULL WHERE id = :id");
            $stmtUpdateDem->bindValue(':id', $id_demanda, PDO::PARAM_INT);
            $stmtUpdateDem->execute();

            // A demanda volta a um novo ciclo; qualquer pontuação do ciclo
            // anterior não deve permanecer enquanto ela estiver reaberta.
            $stmtPontuacao = $this->conn->prepare(
                "DELETE FROM {$this->table4} WHERE id_demanda = :id_demanda"
            );
            $stmtPontuacao->execute([':id_demanda' => $id_demanda]);

            $this->conn->commit();
            return ['status' => 1, 'message' => 'Solicitação aprovada. Demanda reabererta.'];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['status' => 3, 'message' => 'Erro no servidor.'];
        }
    }

    /**
     * Rejeitar solicitação de reabertura
     */
    public function rejeitar_reabertura(int $id_solicitacao, int $id_admin): array
    {
        try {
            $this->conn->beginTransaction();

            // Verificar solicitação
            $stmt = $this->conn->prepare("SELECT r.status, d.id_projeto FROM reabertura_demandas r INNER JOIN {$this->table2} d ON d.id = r.id_demanda WHERE r.id = :id");
            $stmt->bindValue(':id', $id_solicitacao, PDO::PARAM_INT);
            $stmt->execute();
            $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$solicitacao) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Solicitação não encontrada.'];
            }

            if ($solicitacao['status'] !== 'pendente') {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Solicitação já foi processada.'];
            }

            if (!$this->auth->usuarioPodeAcessarProjeto($id_admin, (int) $solicitacao['id_projeto'])) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Você não tem acesso ao projeto desta demanda.'];
            }

            // Atualizar solicitação
            $stmtUpdate = $this->conn->prepare("UPDATE reabertura_demandas SET status = 'rejeitada', id_admin_responsavel = :id_admin, data_decisao = NOW() WHERE id = :id");
            $stmtUpdate->execute([
                ':id' => $id_solicitacao,
                ':id_admin' => $id_admin
            ]);

            $this->conn->commit();
            return ['status' => 1, 'message' => 'Solicitação rejeitada.'];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return ['status' => 3, 'message' => 'Erro no servidor.'];
        }
    }

    /**
     * Obter informações de reabertura de uma demanda
     */
    public function obter_info_reabertura(int $id_demanda): array
    {
        try {
            $sql = "SELECT r.id, r.id_usuario_solicitante, r.id_admin_responsavel, r.status, r.justificativa, r.data_solicitacao, r.data_decisao,
                           u.nome AS solicitante_nome, a.nome AS admin_nome
                    FROM reabertura_demandas r
                    INNER JOIN {$this->table1} u ON r.id_usuario_solicitante = u.id
                    LEFT JOIN {$this->table1} a ON r.id_admin_responsavel = a.id
                    WHERE r.id_demanda = :id_demanda
                    ORDER BY r.data_solicitacao DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_demanda', $id_demanda, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }
}

?>