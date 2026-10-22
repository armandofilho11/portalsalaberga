<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

require_once(__DIR__ . '/../config/Database.php');

class Aluno extends Connect
{
    public function __construct()
    {
        parent::__construct();
    }


    // LISTAR TODOS OS ALUNOS
    public function listarAlunos(): array
    {
        try {

            $sql = "
                SELECT
                    a.id,
                    a.nome,
                    a.matricula,
                    a.data_nascimento,
                    t.turma,
                    t.serie
                FROM aluno a
                INNER JOIN turma t
                    ON t.id = a.id_turma
                WHERE a.ativo = 1
                ORDER BY a.nome ASC
            ";

            $stmt = $this->conn->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao listar alunos: " . $e->getMessage());

            return [];
        }
    }

    // CONTAR ALUNOS ATIVOS
    public function contarAlunosAtivos(): int
    {
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM aluno WHERE ativo = 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erro ao contar alunos ativos: " . $e->getMessage());
            return 0;
        }
    }

    // CONTAR ENTRADAS E SAÍDAS DO DIA ATUAL
    public function contarEntradasHoje(): int
    {
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM registro_entrada WHERE DATE(data_entrada) = CURDATE()");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erro ao contar entradas de hoje: " . $e->getMessage());
            return 0;
        }
    }

    // CONTAR SAÍDAS DO DIA ATUAL
    public function contarSaidasHoje(): int
    {
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM registro_saida WHERE DATE(data_saida) = CURDATE()");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erro ao contar saídas de hoje: " . $e->getMessage());
            return 0;
        }
    }

    // CONTAR SAÍDAS DE ESTÁGIO DO DIA ATUAL
    public function contarSaidasEstagioHoje(): int
    {
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) AS total FROM registro_saida_estagio WHERE DATE(data_saida) = CURDATE()");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return intval($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erro ao contar saídas de estágio de hoje: " . $e->getMessage());
            return 0;
        }
    }

    // LISTAR APENAS AS SAÍDAS DO DIA ATUAL (PAINEL DIÁRIO)
    public function listarSaidasDoDia(): array
    {
        try {
            $sql = "
                SELECT 
                    a.nome AS aluno,
                    t.serie,
                    t.turma,
                    r.data_saida,
                    r.hora_saida,
                    r.tipo_responsavel,
                    r.nome_responsavel,
                    r.motivo,
                    r.tipo_conducente,
                    r.descricao
                FROM registro_saida r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE DATE(r.data_saida) = CURDATE()
                ORDER BY r.hora_saida DESC
            ";

            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar saídas do dia: " . $e->getMessage());
            return [];
        }
    }

    // LISTAR PRÉ-ESTÁGIO DE HOJE
    public function listarPreEstagioHoje(): array
    {
        try {
            $sql = "
                SELECT
                    r.id,
                    a.nome AS aluno,
                    t.serie,
                    t.turma,
                    r.data_registro,
                    r.hora_registro,
                    r.momento,
                    r.descricao
                FROM registro_pre_estagio r
                INNER JOIN aluno a ON a.id = r.id_aluno
                INNER JOIN turma t ON t.id = a.id_turma
                WHERE DATE(r.data_registro) = CURDATE()
                ORDER BY r.hora_registro DESC
            ";

            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar pré-estágio de hoje: " . $e->getMessage());
            return [];
        }
    }
    

    // BUSCAR ALUNO POR ID
    public function buscarAlunoPorId($id)
    {
        try {

            $sql = "
                SELECT
                    a.*,
                    t.turma,
                    t.serie
                FROM aluno a
                INNER JOIN turma t
                    ON t.id = a.id_turma
                WHERE a.id = :id
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao buscar aluno: " . $e->getMessage());

            return false;
        }
    }

 public function listarSaidasEstagioPorCategoria(string $categoria): array
    {
        try {
            // Mapear categoria técnica para curso + turma correspondente
            $turmaCond = '';
            switch ($categoria) {
                case 'enfermagem':
                    $condicao = "a.curso = 'enfermagem'";
                    $turmaCond = "t.turma = 'A'";
                    break;
                case 'informatica':
                    $condicao = "a.curso = 'informática'";
                    $turmaCond = "t.turma = 'B'";
                    break;
                case 'administracao_meio_ambiente':
                    $condicao = "a.curso IN ('administração', 'meio ambiente')";
                    $turmaCond = "t.turma = 'C'";
                    break;
                case 'edificacao':
                    $condicao = "a.curso = 'edificações'";
                    $turmaCond = "t.turma = 'D'";
                    break;
                default:
                    return [];
            }

            // 'r.id' adicionado ao SELECT para que a view consiga ler o ID do registro!
            $sql = "
                SELECT
                    r.id,
                    a.nome,
                    a.matricula,
                    a.curso,
                    t.serie,
                    t.turma,
                    r.data_saida,
                    r.hora_saida
                FROM registro_saida_estagio r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE {$condicao} AND t.serie = '3 ano' AND {$turmaCond}
                ORDER BY r.data_saida DESC, r.hora_saida DESC
            ";

            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar saídas de estágio por categoria: " . $e->getMessage());
            return [];
        }
    }

    // BUSCAR SAÍDA DE ESTÁGIO POR ID
    public function buscarSaidaEstagioPorId(int $id)
    {
        try {
            $sql = "
                SELECT
                    r.id,
                    r.id_aluno,
                    r.data_saida,
                    r.hora_saida,
                    a.nome AS aluno,
                    a.matricula,
                    t.serie,
                    t.turma
                FROM registro_saida_estagio r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE r.id = :id
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar saída de estágio por id: " . $e->getMessage());
            return false;
        }
    }

    // LISTAR SAÍDAS DE ESTÁGIO POR SÉRIE E TURMA (garante agrupamento por turma)
    public function listarSaidasEstagioPorTurma(string $serie, string $turma): array
    {
        try {
            $sql = "
                SELECT
                    r.id,
                    a.nome,
                    a.matricula,
                    a.curso,
                    t.serie,
                    t.turma,
                    r.data_saida,
                    r.hora_saida
                FROM registro_saida_estagio r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE t.serie = :serie AND t.turma = :turma
                ORDER BY r.data_saida DESC, r.hora_saida DESC
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':serie', $serie);
            $stmt->bindParam(':turma', $turma);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar saídas de estágio por turma: " . $e->getMessage());
            return [];
        }
    }

    // BUSCAR ALUNO POR MATRÍCULA
    public function buscarPorMatricula($matricula)
    {
        try {

            $sql = "
                SELECT *
                FROM aluno
                WHERE matricula = :matricula
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':matricula', $matricula);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao buscar matrícula: " . $e->getMessage());

            return false;
        }
    }

    // CADASTRAR ALUNO
    public function cadastrarAluno(
        $id_turma,
        $nome,
        $matricula,
        $data_nascimento
    ) {
        try {

            $sql = "
                INSERT INTO aluno
                (
                    id_turma,
                    nome,
                    matricula,
                    data_nascimento
                )
                VALUES
                (
                    :id_turma,
                    :nome,
                    :matricula,
                    :data_nascimento
                )
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_turma', $id_turma);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':data_nascimento', $data_nascimento);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao cadastrar aluno: " . $e->getMessage());

            return false;
        }
    }

    // ATUALIZAR ALUNO
    public function atualizarAluno(
        $id,
        $id_turma,
        $nome,
        $matricula,
        $data_nascimento
    ) {
        try {

            $sql = "
                UPDATE aluno
                SET
                    id_turma = :id_turma,
                    nome = :nome,
                    matricula = :matricula,
                    data_nascimento = :data_nascimento
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':id_turma', $id_turma);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':data_nascimento', $data_nascimento);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao atualizar aluno: " . $e->getMessage());

            return false;
        }
    }

    // DESATIVAR ALUNO
    public function desativarAluno($id)
    {
        try {

            $sql = "
                UPDATE aluno
                SET ativo = 0
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao desativar aluno: " . $e->getMessage());

            return false;
        }
    }

    // LISTAR REGISTROS DO ALUNO
    public function listarRegistrosAluno($id_aluno)
    {
        try {

            $sql = "
                SELECT
                    r.id,
                    r.data_entrada AS data,
                    r.hora_entrada AS hora,
                    'entrada' AS tipo,
                    r.descricao
                FROM registro_entrada r
                WHERE r.id_aluno = :id_aluno
                UNION ALL
                SELECT
                    r.id,
                    r.data_saida AS data,
                    r.hora_saida AS hora,
                    'saida' AS tipo,
                    r.descricao
                FROM registro_saida r
                WHERE r.id_aluno = :id_aluno
                ORDER BY data DESC, hora DESC
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_aluno', $id_aluno);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao listar registros do aluno: " . $e->getMessage());

            return [];
        }
    }

    // LISTAR ALUNOS POR TURMA
    public function listarPorTurma($id_turma)
    {
        try {

            $sql = "
                SELECT *
                FROM aluno
                WHERE id_turma = :id_turma
                AND ativo = 1
                ORDER BY nome ASC
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_turma', $id_turma);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao listar alunos da turma: " . $e->getMessage());

            return [];
        }
    }

    // BUSCAR REGISTRO DE ENTRADA POR ID
    public function buscarEntradaPorId(int $id)
    {
        try {
            $sql = "
                SELECT
                    r.id,
                    r.id_aluno,
                    r.data_entrada,
                    r.hora_entrada,
                    r.motivo,
                    r.descricao,
                    a.nome AS aluno,
                    a.matricula,
                    t.serie,
                    t.turma
                FROM registro_entrada r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE r.id = :id
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao buscar entrada por id: ' . $e->getMessage());
            return false;
        }
    }

    // BUSCAR REGISTRO DE SAÍDA POR ID
    public function buscarSaidaPorId(int $id)
    {
        try {
            $sql = "
                SELECT
                    r.id,
                    r.id_aluno,
                    r.tipo_responsavel,
                    r.nome_responsavel,
                    r.motivo,
                    r.data_saida,
                    r.hora_saida,
                    r.tipo_conducente,
                    r.descricao,
                    a.nome AS aluno,
                    a.matricula,
                    t.serie,
                    t.turma
                FROM registro_saida r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE r.id = :id
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao buscar saída por id: ' . $e->getMessage());
            return false;
        }
    }

    // LISTAR ALUNOS DE ESTAGIO
    public function listarAlunosEstagio(): array
    {
        try {
            $sql = "
                SELECT
                    a.id,
                    a.nome,
                    a.matricula,
                    a.curso,
                    t.serie,
                    t.turma
                FROM aluno a
                INNER JOIN turma t ON t.id = a.id_turma
                WHERE a.ativo = 1 AND t.serie = '3 ano'
                ORDER BY t.turma ASC
            ";

            $stmt = $this->conn->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar alunos de estágio: " . $e->getMessage());
            return [];
        }
    }

    // BUSCAR ENTRADAS POR DATA
    public function listarEntradasPorData(string $data): array
    {
        try {
            $sql = "
                SELECT 
                    a.nome AS aluno, 
                    t.serie, 
                    t.turma, 
                    r.hora_entrada,
                    r.motivo
                FROM registro_entrada r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE DATE(r.data_entrada) = :data
                ORDER BY r.hora_entrada DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':data' => $data]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar entradas por data: " . $e->getMessage());
            return [];
        }
    }

    // BUSCAR SAÍDAS REGULARES POR DATA
    public function listarSaidasPorData(string $data): array
    {
        try {
            $sql = "
                SELECT 
                    a.nome AS aluno, 
                    t.serie, 
                    t.turma, 
                    r.hora_saida, 
                    r.motivo 
                FROM registro_saida r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE DATE(r.data_saida) = :data
                ORDER BY r.hora_saida DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':data' => $data]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar saídas por data: " . $e->getMessage());
            return [];
        }
    }

    // BUSCAR SAÍDAS PARA ESTÁGIO POR DATA (CORRIGIDO)
    public function listarSaidasEstagioPorData(string $data): array
    {
        try {
            $sql = "
                SELECT 
                    a.nome, 
                    a.matricula, 
                    a.curso, 
                    t.serie, 
                    t.turma, 
                    r.hora_saida 
                FROM registro_saida_estagio r
                INNER JOIN aluno a ON r.id_aluno = a.id
                INNER JOIN turma t ON a.id_turma = t.id
                WHERE DATE(r.data_saida) = :data
                ORDER BY r.hora_saida DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':data' => $data]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar saídas de estágio por data: " . $e->getMessage());
            return [];
        }
    }

    // EDITAR ENTRADA
    public function editarEntrada($id, $id_aluno, $data_entrada, $hora_entrada, $motivo, $descricao = '')
    {
        try {
            $sql = "
                UPDATE registro_entrada 
                SET id_aluno = :id_aluno, 
                    data_entrada = :data_entrada, 
                    hora_entrada = :hora_entrada, 
                    motivo = :motivo, 
                    descricao = :descricao 
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $stmt->bindParam(':data_entrada', $data_entrada);
            $stmt->bindParam(':hora_entrada', $hora_entrada);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':descricao', $descricao);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao editar entrada: " . $e->getMessage());
            return false;
        }
    }

    // EDITAR SAÍDA
    public function editarSaida($id, $id_aluno, $tipo_responsavel, $nome_responsavel, $motivo, $data_saida, $hora_saida, $tipo_conducente, $descricao = '')
    {
        try {
            $sql = "
                UPDATE registro_saida 
                SET id_aluno = :id_aluno, 
                    tipo_responsavel = :tipo_responsavel, 
                    nome_responsavel = :nome_responsavel, 
                    motivo = :motivo, 
                    data_saida = :data_saida, 
                    hora_saida = :hora_saida, 
                    tipo_conducente = :tipo_conducente, 
                    descricao = :descricao 
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_responsavel', $tipo_responsavel);
            $stmt->bindParam(':nome_responsavel', $nome_responsavel);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':data_saida', $data_saida);
            $stmt->bindParam(':hora_saida', $hora_saida);
            $stmt->bindParam(':tipo_conducente', $tipo_conducente);
            $stmt->bindParam(':descricao', $descricao);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao editar saída: " . $e->getMessage());
            return false;
        }
    }

    // EDITAR SAÍDA DE ESTÁGIO
    public function editarSaidaEstagio($id, $id_aluno, $data_saida, $hora_saida)
    {
        try {
            $sql = "
                UPDATE registro_saida_estagio 
                SET id_aluno = :id_aluno, 
                    data_saida = :data_saida, 
                    hora_saida = :hora_saida 
                WHERE id = :id
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $stmt->bindParam(':data_saida', $data_saida);
            $stmt->bindParam(':hora_saida', $hora_saida);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao editar saída de estágio: " . $e->getMessage());
            return false;
        }
    }



    // APAGAR REGISTRO (Genérico por Tabela)
    public function apagarRegistro(string $tabela, int $id): bool
    {
        try {
            // Lista de tabelas permitidas para evitar SQL Injection via parâmetro
            $tabelasPermitidas = ['registro_entrada', 'registro_saida', 'registro_saida_estagio'];
            
            if (!in_array($tabela, $tabelasPermitidas)) {
                throw new Exception("Tabela inválida ou não autorizada.");
            }

            $sql = "DELETE FROM {$tabela} WHERE id = :id";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao apagar registro na tabela {$tabela}: " . $e->getMessage());
            return false;
        }
    }
    
}
