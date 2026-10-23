<?php

require_once(__DIR__ . '/../config/connect.php');

class OcorrenciaModel extends connect
{
    function __construct() {
        parent::__construct();
    }

    // =========================
    // CRIAR OCORRÊNCIA
    // =========================
    public function criarOcorrencia($id_aluno, $id_turma, $id_professor, $tipo_ocorrencia, $descricao = '')
    {
        try {
            if (!$this->conn) {
                return false;
            }

            $stmt = $this->conn->prepare("
                INSERT INTO ocorrencias (id_aluno, id_turma, id_professor, tipo_ocorrencia, descricao)
                VALUES (:id_aluno, :id_turma, :id_professor, :tipo_ocorrencia, :descricao)
            ");

            $sucesso = $stmt->execute([
                ':id_aluno' => $id_aluno,
                ':id_turma' => $id_turma,
                ':id_professor' => $id_professor,
                ':tipo_ocorrencia' => $tipo_ocorrencia,
                ':descricao' => $descricao
            ]);

            if (!$sucesso) {
                return 2;
            }

            $id_ocorrencia = $this->conn->lastInsertId();

            return [
                "status" => 1,
                "id" => (int) $id_ocorrencia
            ];
        } catch (PDOException $e) {
            error_log("Erro ao criar ocorrência: " . $e->getMessage());
            return 3;
        }
    }

    // =========================
    // LISTAR OCORRÊNCIAS
    // =========================
    public function listarOcorrencias(?int $id_professor = null, $filtro = 'mes')
    {
        try {
            if (!$this->conn) {
                return [];
            }

            // Determinar data de início baseado no filtro
            $dataInicio = '';
            switch ($filtro) {
                case 'mes':
                    $dataInicio = date('Y-m-01');
                    break;
                case 'trimestre':
                    $mes = date('m');
                    $trimestre = ceil($mes / 3);
                    $mesInicio = ($trimestre - 1) * 3 + 1;
                    $dataInicio = date('Y-') . str_pad($mesInicio, 2, '0', STR_PAD_LEFT) . '-01';
                    break;
                case 'semestre':
                    $mes = date('m');
                    $semestre = ceil($mes / 6);
                    $mesInicio = ($semestre - 1) * 6 + 1;
                    $dataInicio = date('Y-') . str_pad($mesInicio, 2, '0', STR_PAD_LEFT) . '-01';
                    break;
                case 'ano':
                    $dataInicio = date('Y-01-01');
                    break;
                default:
                    $dataInicio = date('Y-m-01');
            }

            if ($id_professor !== null) {
                $stmt = $this->conn->prepare("
                    SELECT o.id_ocorrencia, o.tipo_ocorrencia, o.descricao, o.data_ocorrencia,
                           a.nome as nome_aluno, a.matricula,
                           t.nome_turma, t.serie_turma,
                           u.nome as professor_nome
                    FROM ocorrencias o
                    JOIN alunos a ON o.id_aluno = a.id_aluno
                    JOIN turmas t ON o.id_turma = t.id_turma
                    JOIN usuarios u ON o.id_professor = u.id
                    WHERE o.id_professor = :id_professor AND DATE(o.data_ocorrencia) >= :data_inicio
                    ORDER BY o.data_ocorrencia DESC
                ");
                $stmt->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
                $stmt->bindValue(':data_inicio', $dataInicio, PDO::PARAM_STR);
            } else {
                $stmt = $this->conn->prepare("
                    SELECT o.id_ocorrencia, o.tipo_ocorrencia, o.descricao, o.data_ocorrencia,
                           a.nome as nome_aluno, a.matricula,
                           t.nome_turma, t.serie_turma,
                           u.nome as professor_nome
                    FROM ocorrencias o
                    JOIN alunos a ON o.id_aluno = a.id_aluno
                    JOIN turmas t ON o.id_turma = t.id_turma
                    JOIN usuarios u ON o.id_professor = u.id
                    WHERE DATE(o.data_ocorrencia) >= :data_inicio
                    ORDER BY o.data_ocorrencia DESC
                    LIMIT 50
                ");
                $stmt->bindValue(':data_inicio', $dataInicio, PDO::PARAM_STR);
            }

            if (!$stmt->execute()) {
                return [];
            }

            $ocorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $ocorrencias ?: [];
        } catch (PDOException $e) {
            error_log("Erro ao listar ocorrências: " . $e->getMessage());
            return [];
        }
    }

    // =========================
    // CONTAR OCORRÊNCIAS POR ALUNO
    // =========================
    public function contarOcorrenciasPorAluno($id_aluno)
    {
        try {
            if (!$this->conn) {
                return 0;
            }

            $stmt = $this->conn->prepare("
                SELECT COUNT(*) as total FROM ocorrencias
                WHERE id_aluno = :id_aluno
            ");

            $stmt->execute([':id_aluno' => $id_aluno]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar ocorrências: " . $e->getMessage());
            return 0;
        }
    }

    // =========================
    // DELETAR OCORRÊNCIA
    // =========================
    public function deletarOcorrencia($id_ocorrencia)
    {
        try {
            if (!$this->conn) {
                return false;
            }

            $stmt = $this->conn->prepare("
                DELETE FROM ocorrencias
                WHERE id_ocorrencia = :id_ocorrencia
            ");

            return $stmt->execute([':id_ocorrencia' => $id_ocorrencia]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar ocorrência: " . $e->getMessage());
            return false;
        }
    }
}

?>
