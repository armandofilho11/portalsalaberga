<?php
require_once __DIR__ . '/../config/database.php';

class Agendamento {
    private $conn;

    public function __construct() {
        try {
            $this->conn = getDatabaseConnection();
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->conn->exec("SET NAMES utf8");
            $this->conn->exec("SET SESSION sql_mode = 'STRICT_ALL_TABLES'");
        } catch (PDOException $e) {
            error_log("[Agendamento] Erro na conexão com o banco: " . $e->getMessage());
            throw new Exception("Erro na conexão com o banco de dados");
        }
    }

    /**
     * Cadastra um novo equipamento
     * @param string $nome Nome do equipamento
     * @param string $descricao Descrição do equipamento
     * @param int $quantidade Quantidade disponível
     * @return bool Sucesso da operação
     */
    public function cadastro_equipamento($nome, $descricao, $quantidade) {
        try {
            $sql = "INSERT INTO equipamentos (nome, descricao, quantidade_disponivel, disponivel) 
                    VALUES (:nome, :descricao, :quantidade, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[cadastro_equipamento] Erro: " . $e->getMessage());
            throw new Exception("Erro ao cadastrar equipamento");
        }
    }

    /**
     * Cadastra um novo espaço
     * @param string $nome Nome do espaço
     * @param string $descricao Descrição do espaço
     * @param int $quantidade Quantidade disponível
     * @return bool Sucesso da operação
     */
    public function cadastro_espaco($nome, $descricao, $quantidade) {
        try {
            $sql = "INSERT INTO espacos (nome, descricao, quantidade_disponivel, disponivel) 
                    VALUES (:nome, :descricao, :quantidade, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[cadastro_espaco] Erro: " . $e->getMessage());
            throw new Exception("Erro ao cadastrar espaço");
        }
    }

    /**
     * Atualiza um cadastro existente
     * @param string $tipo Tipo ('Equipamento' ou 'Espaço')
     * @param int $id ID do item
     * @param string $nome Novo nome
     * @param string $descricao Nova descrição
     * @param int $quantidade Nova quantidade
     * @return bool Sucesso da operação
     */
    public function atualizar_cadastro($tipo, $id, $nome, $descricao, $quantidade) {
        try {
            // Validar tipo
            if (!in_array($tipo, ['Equipamento', 'Espaço'])) {
                throw new Exception("Tipo inválido");
            }
            
            $tabela = $tipo === 'Equipamento' ? 'equipamentos' : 'espacos';
            
            // Validar quantidade
            if ($quantidade < 0) {
                throw new Exception("Quantidade não pode ser negativa");
            }

            // Verificar se o registro existe
            $sql_check = "SELECT id FROM $tabela WHERE id = :id";
            $stmt_check = $this->conn->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            
            if (!$stmt_check->fetch()) {
                // Considerar se isso deve ser um erro ou se a atualização deve prosseguir
                // Se o ID não existe, a atualização falhará de qualquer maneira ou não afetará linhas.
                // Para ser explícito, podemos lançar um erro aqui.
                throw new Exception("Registro com ID $id não encontrado para o tipo $tipo.");
            }
            
            $this->conn->beginTransaction();
            
            $sql = "UPDATE $tabela 
                   SET nome = :nome, 
                       descricao = :descricao, 
                       quantidade_disponivel = :quantidade 
                   WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            error_log("[atualizar_cadastro] Executando SQL: $sql");
            error_log("[atualizar_cadastro] Parâmetros: tipo=$tipo, id=$id, nome=$nome, descricao=$descricao, quantidade=$quantidade");
            
            $result = $stmt->execute();
            
            if ($result) {
                // $rowsAffected = $stmt->rowCount(); // Opcional verificar, mas não mais crítico para o retorno de sucesso
                // error_log("[atualizar_cadastro] Registros afetados: $rowsAffected");
                // Mesmo que $rowsAffected seja 0 (nenhuma mudança nos dados), consideramos a operação bem-sucedida se execute() for true.
                $this->conn->commit();
                return true;
            } else {
                // Se $stmt->execute() retornar false, houve um erro na execução do SQL.
                $errorInfo = $stmt->errorInfo();
                $this->conn->rollBack();
                error_log("[atualizar_cadastro] Erro na execução SQL: " . print_r($errorInfo, true));
                // Lançar uma exceção com a informação do erro SQL
                throw new PDOException("Erro na execução da atualização SQL: " . ($errorInfo[2] ?? 'Detalhe não disponível'));
            }
            
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[atualizar_cadastro] Erro PDO: " . $e->getMessage());
            // Pode-se optar por repassar a exceção ou retornar uma mensagem de erro mais genérica para o controller
            throw new Exception("Erro no banco de dados ao tentar atualizar: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[atualizar_cadastro] Erro Geral: " . $e->getMessage());
            // Repassar outras exceções (como validações de tipo, ID não encontrado, etc.)
            throw $e;
        }
    }

    /**
     * Exclui um cadastro
     * @param string $tipo Tipo ('Equipamento' ou 'Espaço')
     * @param int $id ID do item
     * @return bool Sucesso da operação
     */
    public function excluir_cadastro($tipo, $id) {
        try {
            $tabela = $tipo === 'Equipamento' ? 'equipamentos' : 'espacos';
            $sql = "DELETE FROM $tabela WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("[excluir_cadastro] Erro: " . $e->getMessage());
            throw new Exception("Erro ao excluir cadastro");
        }
    }

    /**
     * Alterna a disponibilidade de um item
     * @param string $tipo Tipo ('Equipamento' ou 'Espaço')
     * @param int $id ID do item
     * @param int $disponivel Novo estado (0 ou 1)
     * @return bool Sucesso da operação
     */
    public function alternar_disponibilidade($tipo, $id, $disponivel) {
        try {
            $this->conn->beginTransaction();
            
            // Validar tipo
            if (!in_array($tipo, ['Equipamento', 'Espaço'])) {
                throw new Exception("Tipo inválido");
            }
            
            $tabela = $tipo === 'Equipamento' ? 'equipamentos' : 'espacos';
            
            // Garantir que disponivel seja 0 ou 1
            $disponivel = (int)$disponivel;
            if (!in_array($disponivel, [0, 1])) {
                throw new Exception("Valor de disponibilidade inválido");
            }
            
            $sql = "UPDATE $tabela 
                   SET disponivel = :disponivel 
                   WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':disponivel', $disponivel, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            error_log("[alternar_disponibilidade] Executando SQL: $sql");
            error_log("[alternar_disponibilidade] Parâmetros: tipo=$tipo, id=$id, disponivel=$disponivel");
            
            $result = $stmt->execute();
            
            if ($result) {
                $rowsAffected = $stmt->rowCount();
                error_log("[alternar_disponibilidade] Registros afetados: $rowsAffected");
                
                if ($rowsAffected > 0) {
                    $this->conn->commit();
                    return true;
                } else {
                    $this->conn->rollBack();
                    error_log("[alternar_disponibilidade] Nenhum registro foi atualizado");
                    return false;
                }
            }
            
            $this->conn->rollBack();
            error_log("[alternar_disponibilidade] Erro na execução: " . print_r($stmt->errorInfo(), true));
            return false;
            
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[alternar_disponibilidade] Erro: " . $e->getMessage());
            throw new Exception("Erro ao atualizar disponibilidade");
        }
    }

    /**
     * Verifica a disponibilidade de um item
     * @param string $tipo Tipo ('Equipamento' ou 'Espaço')
     * @param int $id_item ID do item
     * @return array Dados do item ou false se não encontrado
     */
    public function verificar_disponibilidade($tipo, $id_item) {
        try {
            $tabela = $tipo === 'Equipamento' ? 'equipamentos' : 'espacos';
            $sql = "SELECT disponivel, quantidade_disponivel FROM $tabela WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id_item, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[verificar_disponibilidade] Erro: " . $e->getMessage());
            throw new Exception("Erro ao verificar disponibilidade");
        }
    }

    /**
     * Agenda um item
     * @param int $id_item ID do item
     * @param string $tipo Tipo ('Equipamento' ou 'Espaço')
     * @param string $data_hora Data e hora do agendamento
     * @param int $aluno_id ID do aluno
     * @param int $turma_id ID da turma do aluno
     * @return bool Sucesso da operação
     */
    public function agendar_item($id_item, $tipo, $data_hora, $aluno_id, $turma_id) {
        try {
            // Validar turma_id
            if ($turma_id <= 0) {
                throw new Exception("ID da Turma inválido");
            }

            // Verificar se o aluno pertence à turma informada
            $sql_check_turma = "SELECT turma_id FROM alunos WHERE id = :aluno_id LIMIT 1";
            $stmt_check = $this->conn->prepare($sql_check_turma);
            $stmt_check->bindParam(':aluno_id', $aluno_id, PDO::PARAM_INT);
            $stmt_check->execute();
            $aluno_turma = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if (!$aluno_turma || $aluno_turma['turma_id'] != $turma_id) {
                throw new Exception("A turma informada não corresponde à turma do aluno");
            }
            
            $this->conn->beginTransaction();

            // Verificar disponibilidade do item
            $itemInfo = $this->verificar_disponibilidade($tipo, $id_item);
            if (!$itemInfo || $itemInfo['disponivel'] != 1 || $itemInfo['quantidade_disponivel'] <= 0) {
                $this->conn->rollBack();
                throw new Exception('Item não disponível para agendamento ou quantidade esgotada.');
            }

            // Inserir agendamento incluindo turma_id
            $sql = "INSERT INTO agendamentos (id_item, tipo, data_hora, aluno_id, turma_id, status) 
                    VALUES (:id_item, :tipo, :data_hora, :aluno_id, :turma_id, 'pendente')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_item', $id_item, PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':data_hora', $data_hora);
            $stmt->bindParam(':aluno_id', $aluno_id, PDO::PARAM_INT);
            $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
            $success_agendamento = $stmt->execute();

            if ($success_agendamento) {
                // Decrementar quantidade disponível
                $tabela = $tipo === 'Equipamento' ? 'equipamentos' : 'espacos';
                $sql_update = "UPDATE $tabela SET quantidade_disponivel = quantidade_disponivel - 1 
                              WHERE id = :id AND quantidade_disponivel > 0";
                $stmt_update = $this->conn->prepare($sql_update);
                $stmt_update->bindParam(':id', $id_item, PDO::PARAM_INT);
                $success_update_item = $stmt_update->execute();

                if ($success_update_item) {
                    $this->conn->commit();
                    return true;
                } else {
                    $this->conn->rollBack();
                    error_log("[agendar_item] Erro ao decrementar quantidade do item $tipo ID $id_item.");
                    throw new Exception("Erro ao atualizar a quantidade do item após o agendamento.");
                }
            } else {
                $this->conn->rollBack();
                error_log("[agendar_item] Erro ao inserir agendamento para o item $tipo ID $id_item. SQL: $sql");
                throw new Exception("Erro ao registrar o agendamento.");
            }
            
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[agendar_item] Erro PDO: " . $e->getMessage());
            throw new Exception("Erro no banco de dados ao tentar agendar o item: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[agendar_item] Erro Geral: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Assina (aprova ou rejeita) um agendamento
     * @param int $id ID do agendamento
     * @param string $tipo Tipo ('Equipamento' ou 'Espaço')
     * @param string $status Status ('Aprovado' ou 'Rejeitado')
     * @return bool Sucesso da operação
     */
    public function assinar_agendamento($id, $tipo, $status) {
        try {
            $this->conn->beginTransaction();

            // Primeiro, obter informações do agendamento
            $sql = "SELECT id_item, status as status_atual FROM agendamentos WHERE id = :id FOR UPDATE";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$agendamento) {
                $this->conn->rollBack();
                throw new Exception("Agendamento não encontrado");
            }

            if ($agendamento['status_atual'] !== 'pendente') {
                $this->conn->rollBack();
                throw new Exception("Agendamento já foi processado anteriormente");
            }

            // Converter status para o valor aceito pelo banco
            $status_db = ($status === 'Aprovado') ? 'confirmado' : (($status === 'Rejeitado') ? 'cancelado' : $status);
            $sql = "UPDATE agendamentos SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status_db);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $success = $stmt->execute();

            if (!$success) {
                $this->conn->rollBack();
                throw new Exception("Erro ao atualizar status do agendamento");
            }

            // Se foi rejeitado, devolver a quantidade ao item
            if ($status === 'Rejeitado') {
                $tabela = $tipo === 'Equipamento' ? 'equipamentos' : 'espacos';
                $sql = "UPDATE $tabela SET quantidade_disponivel = quantidade_disponivel + 1 
                       WHERE id = :id_item";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id_item', $agendamento['id_item'], PDO::PARAM_INT);
                $success = $stmt->execute();

                if (!$success) {
                    $this->conn->rollBack();
                    throw new Exception("Erro ao atualizar quantidade disponível do item");
                }
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[assinar_agendamento] Erro PDO: " . $e->getMessage());
            throw new Exception("Erro ao assinar agendamento: " . $e->getMessage());
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("[assinar_agendamento] Erro: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lista todos os cadastros (equipamentos e espaços)
     * @return array Lista de cadastros
     */
    public function listar_cadastros() {
        try {
            $cadastros = [];

            // Equipamentos
            $sql_equip = "SELECT id, 'Equipamento' AS tipo, nome, descricao, quantidade_disponivel, disponivel 
                          FROM equipamentos";
            $stmt_equip = $this->conn->prepare($sql_equip);
            $stmt_equip->execute();
            $cadastros = array_merge($cadastros, $stmt_equip->fetchAll(PDO::FETCH_ASSOC));

            // Espaços
            $sql_espacos = "SELECT id, 'Espaço' AS tipo, nome, descricao, quantidade_disponivel, disponivel 
                            FROM espacos";
            $stmt_espacos = $this->conn->prepare($sql_espacos);
            $stmt_espacos->execute();
            $cadastros = array_merge($cadastros, $stmt_espacos->fetchAll(PDO::FETCH_ASSOC));

            return $cadastros;
        } catch (PDOException $e) {
            error_log("[listar_cadastros] Erro: " . $e->getMessage());
            throw new Exception("Erro ao listar cadastros");
        }
    }

    /**
     * Lista agendamentos, opcionalmente filtrados por tipo e aluno
     * @param string $tipo Tipo ('Equipamento', 'Espaço' ou vazio para todos)
     * @param int $aluno_id ID do aluno (opcional)
     * @return array Lista de agendamentos
     */
    public function listar_agendamentos($tipo = '', $aluno_id = null) {
        try {
            $sql = "SELECT a.id, a.id_item, a.tipo, a.data_hora, a.aluno_id, a.status, 
                           al.nome AS aluno_nome, t.nome AS turma_nome,
                           CASE 
                               WHEN a.tipo = 'Equipamento' THEN e.nome 
                               WHEN a.tipo = 'Espaço' THEN s.nome 
                           END AS nome_item
                    FROM agendamentos a
                    LEFT JOIN equipamentos e ON a.id_item = e.id AND a.tipo = 'Equipamento'
                    LEFT JOIN espacos s ON a.id_item = s.id AND a.tipo = 'Espaço'
                    LEFT JOIN alunos al ON a.aluno_id = al.id
                    LEFT JOIN turmas t ON al.turma_id = t.id
                    WHERE 1=1";
            
            $params = [];

            if ($tipo && in_array($tipo, ['Equipamento', 'Espaço'])) {
                $sql .= " AND a.tipo = :tipo";
                $params[':tipo'] = $tipo;
            }
            
            if ($aluno_id !== null) {
                $sql .= " AND a.aluno_id = :aluno_id";
                $params[':aluno_id'] = $aluno_id;
            } else {
                // Se não for especificado um aluno, mostrar apenas os pendentes
                $sql .= " AND a.status = 'pendente'";
            }

            $sql .= " ORDER BY a.data_hora DESC";

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[listar_agendamentos] Erro: " . $e->getMessage());
            throw new Exception("Erro ao listar agendamentos: " . $e->getMessage());
        }
    }

    /**
     * Lista agendamentos de um aluno específico
     * @param int $aluno_id ID do aluno
     * @return array Lista de agendamentos
     */
    public function listar_agendamentos_aluno($aluno_id) {
        return $this->listar_agendamentos('', $aluno_id);
    }

    /**
     * Obtém um agendamento específico pelo ID
     * @param int $id ID do agendamento
     * @return array|false Dados do agendamento ou false se não encontrado
     */
    public function obter_agendamento($id) {
        try {
            $sql = "SELECT a.id, a.id_item, a.tipo, a.data_hora, a.aluno_id, a.status, 
                           al.nome AS aluno_nome, t.nome AS turma_nome,
                           CASE 
                               WHEN a.tipo = 'Equipamento' THEN e.nome 
                               WHEN a.tipo = 'Espaço' THEN s.nome 
                           END AS nome_item
                    FROM agendamentos a
                    LEFT JOIN equipamentos e ON a.id_item = e.id AND a.tipo = 'Equipamento'
                    LEFT JOIN espacos s ON a.id_item = s.id AND a.tipo = 'Espaço'
                    LEFT JOIN alunos al ON a.aluno_id = al.id
                    LEFT JOIN turmas t ON a.turma_id = t.id
                    WHERE a.id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[obter_agendamento] Erro: " . $e->getMessage());
            throw new Exception("Erro ao obter agendamento: " . $e->getMessage());
        }
    }
}
?>