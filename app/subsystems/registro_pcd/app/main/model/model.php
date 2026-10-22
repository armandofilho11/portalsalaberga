<?php
require_once("../config/database.php");
class Model extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    public function logar($email, $senha)
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM usuario WHERE email = :email AND senha = :senha');
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senha);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                return 1; // Login bem sucedido
            } else {
                return 2; // Email ou senha incorretos
            }
        } catch (PDOException $e) {
            error_log("Erro ao fazer login: " . $e->getMessage());
            return 2;
        }
    }

    public function CriarConta($nome, $email, $senha)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)");
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senha);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erro ao criar conta: " . $e->getMessage());
            return false;
        }
    }

    public function Registrar($nome, $idade, $turma, $deficiencia = '')
    {
        try {
            // Validar dados antes de inserir
            if (empty($nome) || empty($idade) || empty($turma)) {
                error_log("Erro: Campos obrigatórios não preenchidos");
                return false;
            }

            // Limpar e validar os dados
            $nome = trim($nome);
            $idade = (int)$idade;
            $deficiencia = trim($deficiencia);
            $turma = trim($turma);

            // Verificar se a idade está no intervalo válido
            if ($idade < 10 || $idade > 100) {
                error_log("Erro: Idade fora do intervalo permitido");
                return false;
            }

            // Preparar e executar a query
            $sql = "INSERT INTO registro_pcd (nome, idade, deficiencia, turma, data_registro) 
                   VALUES (:nome, :idade, :deficiencia, :turma, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':idade', $idade);
            $stmt->bindParam(':deficiencia', $deficiencia);
            $stmt->bindParam(':turma', $turma);
            
            if ($stmt->execute()) {
                // Verificar se os dados foram realmente inseridos
                $id = $this->db->lastInsertId();
                $verificar = $this->db->prepare("SELECT * FROM registro_pcd WHERE id = :id");
                $verificar->bindParam(':id', $id);
                $verificar->execute();
                
                if ($verificar->rowCount() > 0) {
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erro ao registrar PCD: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTodosRegistros()
    {
        try {
            $sql = "SELECT 
                        rp.id,
                        rp.nome,
                        rp.idade,
                        rp.turma,
                        rp.deficiencia,
                        rp.data_registro,
                        COALESCE(rd.presenca, 0) as presenca,
                        COALESCE(rd.observacoes, '') as observacoes
                    FROM registro_pcd rp 
                    LEFT JOIN registro_diario rd ON rp.id = rd.registro_pcd_id
                    ORDER BY rp.nome ASC, rp.data_registro DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar registros: " . $e->getMessage());
            throw new Exception("Erro ao buscar registros: " . $e->getMessage());
        }
    }

    public function buscarRegistros($tipo = 'todos', $filtro = null) {
        try {
            $sql = "SELECT nome, idade, turma, deficiencia FROM registro_pcd WHERE 1=1";
            
            if ($tipo === 'turma' && $filtro) {
                $sql .= " AND turma = :turma";
            } elseif ($tipo === 'periodo' && is_array($filtro)) {
                $sql .= " AND data_registro BETWEEN :data_inicio AND :data_fim";
            }
            
            $sql .= " ORDER BY nome ASC";
            
            $stmt = $this->db->prepare($sql);
            
            if ($tipo === 'turma' && $filtro) {
                $stmt->bindParam(':turma', $filtro);
            } elseif ($tipo === 'periodo' && is_array($filtro)) {
                $stmt->bindParam(':data_inicio', $filtro['inicio']);
                $stmt->bindParam(':data_fim', $filtro['fim']);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar registros: " . $e->getMessage());
        }
    }

    public function buscarRegistrosPorPeriodo($dataInicio, $dataFim) {
        try {
            $sql = "SELECT rp.*, rd.data_registro, rd.presenca, rd.observacoes 
                    FROM registro_pcd rp 
                    LEFT JOIN registro_diario rd ON rp.id = rd.registro_pcd_id 
                    WHERE rd.data_registro BETWEEN :data_inicio AND :data_fim 
                    ORDER BY rd.data_registro DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':data_inicio', $dataInicio);
            $stmt->bindParam(':data_fim', $dataFim);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar registros por período: " . $e->getMessage());
        }
    }

    public function registrarDia($aluno_id, $presenca, $observacoes) {
        try {
            $sql = "INSERT INTO registro_diario (registro_pcd_id, presenca, observacoes, data_registro) 
                    VALUES (:aluno_id,:presenca, :observacoes, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':aluno_id', $aluno_id, PDO::PARAM_INT);
            $stmt->bindParam(':presenca', $presenca, PDO::PARAM_INT);
            $stmt->bindParam(':observacoes', $observacoes, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao registrar dia: " . $e->getMessage());
            throw new Exception("Erro ao registrar o dia: " . $e->getMessage());
        }
    }

    public function excluirRegistro($id) {
        try {
            // Primeiro, excluir registros relacionados na tabela registro_diario
            $sql = "DELETE FROM registro_diario WHERE registro_pcd_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            // Depois, excluir o registro principal
            $sql = "DELETE FROM registro_pcd WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception("Registro não encontrado.");
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao excluir registro: " . $e->getMessage());
            throw new Exception("Erro ao excluir registro. Por favor, tente novamente.");
        }
    }

    public function registrarAcompanhante($nome) {
        try {
            // Preparar e executar a query
            $sql = "INSERT INTO acompanhante (nome) VALUES (:nome)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erro ao registrar acompanhante: " . $e->getMessage());
            throw new Exception("Erro ao registrar acompanhante. Por favor, tente novamente.");
        }
    }

    public function buscarTodosAcompanhantes() {
        try {
            $sql = "SELECT id, nome FROM acompanhante ORDER BY nome ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar acompanhantes: " . $e->getMessage());
            throw new Exception("Erro ao buscar acompanhantes.");
        }
    }

    public function buscarUsuario($email)
    {
        try {
            $stmt = $this->db->prepare('SELECT nome, email, data_cadastro FROM usuario WHERE email = :email');
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário: " . $e->getMessage());
            throw new Exception("Erro ao buscar usuário.");
        }
    }

    public function buscarRegistroPorId($id)
    {
        try {
            $sql = "SELECT 
                        rp.id,
                        rp.nome,
                        rp.idade,
                        rp.turma,
                        rp.deficiencia,
                        rp.data_registro,
                        COALESCE(rd.presenca, 0) as presenca,
                        COALESCE(rd.observacoes, '') as observacoes
                    FROM registro_pcd rp 
                    LEFT JOIN registro_diario rd ON rp.id = rd.registro_pcd_id
                    WHERE rp.id = :id
                    ORDER BY rd.data_registro DESC
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$registro) {
                throw new Exception("Registro não encontrado");
            }
            
            return $registro;
        } catch (PDOException $e) {
            error_log("Erro ao buscar registro por ID: " . $e->getMessage());
            throw new Exception("Erro ao buscar registro");
        }
    }

    public function atualizarRegistro($id, $dados)
    {
        try {
            // Validar dados obrigatórios
            if (empty($dados['nome']) || empty($dados['idade']) || empty($dados['turma'])) {
                throw new Exception("Campos obrigatórios não preenchidos");
            }

            // Iniciar transação
            $this->db->beginTransaction();

            // Atualizar registro principal
            $sql = "UPDATE registro_pcd 
                   SET nome = :nome, 
                       idade = :idade, 
                       turma = :turma, 
                       deficiencia = :deficiencia 
                   WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':idade', $dados['idade'], PDO::PARAM_INT);
            $stmt->bindParam(':turma', $dados['turma']);
            $stmt->bindParam(':deficiencia', $dados['deficiencia']);
            $stmt->execute();

            // Atualizar registro diário existente
            $sql = "UPDATE registro_diario 
                   SET presenca = :presenca,
                       observacoes = :observacoes
                   WHERE registro_pcd_id = :registro_pcd_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':registro_pcd_id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':presenca', $dados['presenca'], PDO::PARAM_INT);
            $stmt->bindParam(':observacoes', $dados['observacoes']);
            $stmt->execute();

            // Commit da transação
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback em caso de erro
            $this->db->rollBack();
            error_log("Erro ao atualizar registro: " . $e->getMessage());
            throw new Exception("Erro ao atualizar registro: " . $e->getMessage());
        }
    }

    public function registrarDeficiencia($aluno_id, $deficiencia)
    {
        try {
            // Validar dados
            if (empty($aluno_id) || empty($deficiencia)) {
                throw new Exception("Campos obrigatórios não preenchidos");
            }

            // Atualizar a deficiência do aluno
            $sql = "UPDATE registro_pcd 
                   SET deficiencia = :deficiencia 
                   WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $aluno_id, PDO::PARAM_INT);
            $stmt->bindParam(':deficiencia', $deficiencia);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao registrar deficiência: " . $e->getMessage());
            throw new Exception("Erro ao registrar deficiência: " . $e->getMessage());
        }
    }
    
}