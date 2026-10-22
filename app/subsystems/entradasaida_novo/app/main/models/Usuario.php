<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

require_once(__DIR__ . '/../config/Database.php');

class Usuario extends Connect
{
    public function __construct()
    {
        parent::__construct();
    }

    // LOGIN / AUTENTICAÇÃO
    public function autenticar($email, $senha)
    {
        try {

            $sql = "
                SELECT *
                FROM usuario
                WHERE email = :email
                AND ativo = 1
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':email', $email);

            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return false;
            }

            // VERIFICA SENHA HASH
            if (!password_verify($senha, $usuario['senha'])) {
                return false;
            }

            // INICIA SESSÃO
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            return $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'tipo' => $usuario['tipo']
            ];
        } catch (PDOException $e) {

            error_log("Erro ao autenticar usuário: " . $e->getMessage());

            return [];
        }
    }

    // LOGOUT
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        return true;
    }

    // VERIFICA LOGIN
    public function verificarLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['usuario']);
    }

    // REGISTRAR ENTRADA
    public function registrarEntrada($id_aluno, $data_entrada, $hora_entrada, $motivo = '', $descricao = '')
    {
        try {

            $sql = "
                INSERT INTO registro_entrada
                (
                    id_aluno,
                    data_entrada,
                    hora_entrada,
                    motivo,
                    descricao
                )
                VALUES
                (
                    :id_aluno,
                    :data_entrada,
                    :hora_entrada,
                    :motivo,
                    :descricao
                )
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':data_entrada', $data_entrada);
            $stmt->bindParam(':hora_entrada', $hora_entrada);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':descricao', $descricao);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao registrar entrada: " . $e->getMessage());

            return false;
        }
    }

    // REGISTRAR SAÍDA DE ESTÁGIO
    public function registrarSaidaEstagio( $id_aluno, $data_saida, $hora_saida)
    {
        try {

            $sql = "
                INSERT INTO registro_saida_estagio
                (
                    id_aluno,
                    data_saida,
                    hora_saida
                )
                VALUES
                (
                    :id_aluno,
                    :data_saida,
                    :hora_saida
                )
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':data_saida', $data_saida);
            $stmt->bindParam(':hora_saida', $hora_saida);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao registrar saída de estágio: " . $e->getMessage());

            return false;
        }
    }

    // REGISTRAR PRÉ-ESTÁGIO (4 momentos pós-almoço)
    public function registrarPreEstagio($id_aluno, $data_registro, $hora_registro, $momento, $descricao = '')
    {
        try {
            $checkSql = "
                SELECT id
                FROM registro_pre_estagio
                WHERE id_aluno = :id_aluno
                  AND data_registro = :data_registro
                  AND momento = :momento
                LIMIT 1
            ";

            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $checkStmt->bindParam(':data_registro', $data_registro);
            $checkStmt->bindParam(':momento', $momento);
            $checkStmt->execute();

            if ($checkStmt->fetch(PDO::FETCH_ASSOC)) {
                return false;
            }

            $sql = "
                INSERT INTO registro_pre_estagio
                (
                    id_aluno,
                    data_registro,
                    hora_registro,
                    momento,
                    descricao
                )
                VALUES
                (
                    :id_aluno,
                    :data_registro,
                    :hora_registro,
                    :momento,
                    :descricao
                )
            ";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $stmt->bindParam(':data_registro', $data_registro);
            $stmt->bindParam(':hora_registro', $hora_registro);
            $stmt->bindParam(':momento', $momento);
            $stmt->bindParam(':descricao', $descricao);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao registrar pré-estágio: " . $e->getMessage());
            return false;
        }
    }

    // REGISTRAR SAÍDA
    public function registrarSaida(
        $id_aluno, 
        $tipo_responsavel, 
        $nome_responsavel, 
        $motivo, 
        $data_saida, 
        $hora_saida, 
        $tipo_conducente, 
        $descricao = ''
    ) {
        try {

            $sql = "
                INSERT INTO registro_saida
                (
                    id_aluno,
                    tipo_responsavel,
                    nome_responsavel,
                    motivo,
                    data_saida,
                    hora_saida,
                    tipo_conducente,
                    descricao
                )
                VALUES
                (
                    :id_aluno,
                    :tipo_responsavel,
                    :nome_responsavel,
                    :motivo,
                    :data_saida,
                    :hora_saida,
                    :tipo_conducente,
                    :descricao
                )
            ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_aluno', $id_aluno);
            $stmt->bindParam(':tipo_responsavel', $tipo_responsavel);
            $stmt->bindParam(':nome_responsavel', $nome_responsavel);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':data_saida', $data_saida);
            $stmt->bindParam(':hora_saida', $hora_saida);
            $stmt->bindParam(':tipo_conducente', $tipo_conducente);
            $stmt->bindParam(':descricao', $descricao);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao registrar saída: " . $e->getMessage());

            return false;
        }
    }

    // LISTAR REGISTROS
    public function listarRegistros()
    {
        try {

            $sql = "
                SELECT
                    r.id,
                    a.nome AS aluno,
                    CONCAT(r.data_entrada, ' ', r.hora_entrada) AS data_hora,
                    'entrada' AS tipo
                FROM registro_entrada r
                INNER JOIN aluno a
                    ON a.id = r.id_aluno
                UNION ALL
                SELECT
                    r.id,
                    a.nome AS aluno,
                    CONCAT(r.data_saida, ' ', r.hora_saida) AS data_hora,
                    'saida' AS tipo
                FROM registro_saida r
                INNER JOIN aluno a
                    ON a.id = r.id_aluno
                ORDER BY data_hora DESC
            ";

            $stmt = $this->conn->query($sql);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao listar registros: " . $e->getMessage());

            return [];
        }
    }

    // LISTAR ENTRADAS (com turma e descricao + Filtro de Busca)
    public function listarEntradasComDetalhes(string $busca = ''): array
    {
        try {
            // Base da query SQL
            $sql = "
                SELECT
                    r.id,
                    a.nome AS aluno,
                    t.serie,
                    t.turma,
                    r.data_entrada,
                    r.hora_entrada,
                    r.motivo,
                    r.descricao
                FROM registro_entrada r
                INNER JOIN aluno a
                    ON a.id = r.id_aluno
                INNER JOIN turma t
                    ON t.id = a.id_turma
            ";

            // Se o usuário digitou algo na barra de pesquisa, aplica a cláusula WHERE
            if (!empty($busca)) {
                $sql .= " WHERE (
                    a.nome LIKE :busca 
                    OR t.serie LIKE :busca 
                    OR t.turma LIKE :busca 
                    OR r.motivo LIKE :busca
                )";
            }

            // Ordenação padrão
            $sql .= " ORDER BY r.data_entrada DESC, r.hora_entrada DESC";

            $stmt = $this->conn->prepare($sql);

            // Vincula o parâmetro caso a busca não esteja vazia
            if (!empty($busca)) {
                $params = "%" . $busca . "%";
                $stmt->bindParam(':busca', $params);
            }

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao listar entradas: " . $e->getMessage());

            return [];
        }
    }

    // LISTAR ENTRADAS DE HOJE
    public function listarEntradasHoje(): array
    {
        try {
            $sql = "
                SELECT
                    r.id,
                    a.nome AS aluno,
                    t.serie,
                    t.turma,
                    r.data_entrada,
                    r.hora_entrada,
                    r.motivo,
                    r.descricao
                FROM registro_entrada r
                INNER JOIN aluno a
                    ON a.id = r.id_aluno
                INNER JOIN turma t
                    ON t.id = a.id_turma
                WHERE DATE(r.data_entrada) = :hoje
                ORDER BY r.hora_entrada DESC
            ";

            $stmt = $this->conn->prepare($sql);
            $hoje = date('Y-m-d');
            $stmt->bindParam(':hoje', $hoje);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar entradas de hoje: " . $e->getMessage());
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
                INNER JOIN aluno a
                    ON a.id = r.id_aluno
                INNER JOIN turma t
                    ON t.id = a.id_turma
                WHERE DATE(r.data_registro) = :hoje
                ORDER BY r.hora_registro DESC
            ";

            $stmt = $this->conn->prepare($sql);
            $hoje = date('Y-m-d');
            $stmt->bindParam(':hoje', $hoje);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar pré-estágio de hoje: " . $e->getMessage());
            return [];
        }
    }

    // LISTAR HISTÓRICO COMPLETO DE PRÉ-ESTÁGIO
    public function listarPreEstagioCompleto(): array
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
                INNER JOIN aluno a
                    ON a.id = r.id_aluno
                INNER JOIN turma t
                    ON t.id = a.id_turma
                ORDER BY r.data_registro DESC, r.hora_registro DESC
            ";

            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar histórico de pré-estágio: " . $e->getMessage());
            return [];
        }
    }

    // LISTAR SAIDAS (com turma e descricao + Filtro de Busca)
    public function listarSaidasComDetalhes(string $busca = ''): array
    {
        try {

            $sql = "
                SELECT
                    r.id,
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
                INNER JOIN aluno a
                    ON a.id = r.id_aluno
                INNER JOIN turma t
                    ON t.id = a.id_turma
            ";

            // Se houver busca, aplica o filtro nas colunas relevantes da saída
            if (!empty($busca)) {
                $sql .= " WHERE (
                    a.nome LIKE :busca 
                    OR t.serie LIKE :busca 
                    OR t.turma LIKE :busca 
                    OR r.motivo LIKE :busca
                    OR r.nome_responsavel LIKE :busca
                )";
            }

            $sql .= " ORDER BY r.data_saida DESC, r.hora_saida DESC";

            $stmt = $this->conn->prepare($sql);

            if (!empty($busca)) {
                $params = "%" . $busca . "%";
                $stmt->bindParam(':busca', $params);
            }

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            error_log("Erro ao listar saidas: " . $e->getMessage());

            return [];
        }
    }

    // CADASTRAR USUÁRIO
    public function cadastrarUsuario(
        $nome,
        $email,
        $senha,
        $tipo
    ) {
        try {

            // VERIFICA SE EMAIL JÁ EXISTE
            $checkSql = "
            SELECT id
            FROM usuario
            WHERE email = :email
            LIMIT 1
        ";

            $checkStmt = $this->conn->prepare($checkSql);

            $checkStmt->bindValue(':email', $email, PDO::PARAM_STR);

            $checkStmt->execute();

            if ($checkStmt->fetch()) {
                return false;
            }

            // GERA HASH DA SENHA
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "
            INSERT INTO usuario
            (
                nome,
                email,
                senha,
                tipo
            )
            VALUES
            (
                :nome,
                :email,
                :senha,
                :tipo
            )
        ";

            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
            $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {

            error_log("Erro ao cadastrar usuário: " . $e->getMessage());

            return false;
        }
    }
}
