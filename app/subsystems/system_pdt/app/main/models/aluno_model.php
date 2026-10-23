<?php

require_once(__DIR__ . '/../config/connect.php');

class AlunoModel extends connect
{
    function __construct()
    {
        parent::__construct();
    }

    public function listarPorTurma($id_turma = null)
    {
        try {
            if ($id_turma !== null) {
                // detectar nome da coluna de turma (turma_id ou id_turma)
                $col = null;
                $q = $this->conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'alunos' AND COLUMN_NAME IN ('turma_id','id_turma') LIMIT 1");
                if ($q->execute()) {
                    $res = $q->fetch(PDO::FETCH_ASSOC);
                    if ($res && isset($res['COLUMN_NAME'])) $col = $res['COLUMN_NAME'];
                }

                if ($col) {
                    $sql = "SELECT * FROM alunos WHERE " . $col . " = :id_turma ORDER BY data_insercao DESC";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindValue(':id_turma', $id_turma, PDO::PARAM_INT);
                    $ok = $stmt->execute();
                    if ($ok) return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                // se não existir coluna de turma, retorna vazio
                return [];
            }

            // fallback: retorna todos os alunos
            $stmt = $this->conn->prepare("SELECT * FROM alunos ORDER BY data_insercao DESC");
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];
        } catch (PDOException $e) {
            error_log('Erro listarPorTurma: ' . $e->getMessage());
            return [];
        }
    }

    public function cadastrarAluno($nome, $matricula, $email, $pcd = 'nao', $rendimento = 0.00, $turma_id = null)
    {
        try {

            // montar colunas básicas
            $cols = ['nome', 'matricula', 'email', 'pcd', 'rendimento'];
            $place = [':nome', ':matricula', ':email', ':pcd', ':rendimento'];
            $params = [
                ':nome' => $nome,
                ':matricula' => $matricula,
                ':email' => $email,
                ':pcd' => $pcd,
                ':rendimento' => $rendimento
            ];

            // detectar coluna de ligação com turma (se existir) e só usar se presente
            if ($turma_id !== null) {
                $q = $this->conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'alunos' AND COLUMN_NAME IN ('turma_id','id_turma') LIMIT 1");
                if ($q->execute()) {
                    $res = $q->fetch(PDO::FETCH_ASSOC);
                    if ($res && isset($res['COLUMN_NAME'])) {
                        $colName = $res['COLUMN_NAME'];
                        $cols[] = $colName;
                        $place[] = ':turma_id';
                        $params[':turma_id'] = $turma_id;
                    }
                }
            }

            $sql = "INSERT INTO alunos (" . implode(',', $cols) . ") VALUES (" . implode(',', $place) . ")";

            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute($params);

            if ($ok) {
                return ['success' => true, 'id' => (int) $this->conn->lastInsertId()];
            }

            // Tratamento de erros específicos
            $err = $stmt->errorInfo();
            $errorMsg = $err[2] ?? 'Erro desconhecido';
            
            // Detectar erros de constraint
            if (strpos($errorMsg, 'Duplicate entry') !== false) {
                if (strpos($errorMsg, 'matricula') !== false) {
                    $errorMsg = 'Matrícula já existe no sistema';
                } else if (strpos($errorMsg, 'email') !== false) {
                    $errorMsg = 'Email já está registrado no sistema';
                }
            }
            
            error_log('Erro ao cadastrar aluno: ' . $errorMsg);
            return ['success' => false, 'error' => $errorMsg];
        } catch (PDOException $e) {
            $errorMsg = $e->getMessage();
            
            // Tratamento de erros específicos
            if (strpos($errorMsg, 'Duplicate entry') !== false) {
                if (strpos($errorMsg, 'matricula') !== false) {
                    $errorMsg = 'Matrícula já existe no sistema';
                } else if (strpos($errorMsg, 'email') !== false) {
                    $errorMsg = 'Email já está registrado no sistema';
                }
            } else if (strpos($errorMsg, 'FOREIGN KEY') !== false) {
                $errorMsg = 'Turma não existe ou foi removida';
            }
            
            error_log('Erro cadastrarAluno: ' . $e->getMessage());
            return ['success' => false, 'error' => $errorMsg];
        }
    }

    public function excluirAluno($id_aluno)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM alunos WHERE id_aluno = :id_aluno");
            $stmt->bindValue(':id_aluno', $id_aluno, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro excluirAluno: ' . $e->getMessage());
            return false;
        }
    }

    public function buscarAlunoPorId($id_aluno)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM alunos WHERE id_aluno = :id_aluno LIMIT 1");
            $stmt->bindValue(':id_aluno', $id_aluno, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            }
            return [];
        } catch (PDOException $e) {
            error_log('Erro buscarAlunoPorId: ' . $e->getMessage());
            return [];
        }
    }

    public function atualizarAluno($id_aluno, $nome, $matricula, $email, $pcd = 'nao', $rendimento = 0.00)
    {
        try {
            $sql = "UPDATE alunos SET nome = :nome, matricula = :matricula, email = :email, pcd = :pcd, rendimento = :rendimento WHERE id_aluno = :id_aluno";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':matricula', $matricula);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':pcd', $pcd);
            $stmt->bindValue(':rendimento', $rendimento);
            
            $ok = $stmt->execute();
            
            if ($ok) {
                return ['success' => true];
            }
            
            $err = $stmt->errorInfo();
            return ['success' => false, 'error' => ($err[2] ?? 'Erro desconhecido')];
        } catch (PDOException $e) {
            error_log('Erro atualizarAluno: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function contarAlunosPorProfessor($id_professor)
    {
        try {
            if (!$id_professor) {
                return 0;
            }

            $col = null;
            $q = $this->conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'alunos' AND COLUMN_NAME IN ('turma_id','id_turma') LIMIT 1");
            if ($q->execute()) {
                $res = $q->fetch(PDO::FETCH_ASSOC);
                if ($res && isset($res['COLUMN_NAME'])) {
                    $col = $res['COLUMN_NAME'];
                }
            }

            if (!$col) {
                return 0;
            }

            $sql = "SELECT COUNT(*) AS total FROM alunos a INNER JOIN turmas t ON a." . $col . " = t.id_turma WHERE t.id_professor = :id_professor";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return intval($result['total'] ?? 0);
            }

            return 0;
        } catch (PDOException $e) {
            error_log('Erro contarAlunosPorProfessor: ' . $e->getMessage());
            return 0;
        }
    }

    public function salvarNotas($id_aluno, $bimestre, $media_final = null, $id_turma = null, $id_professor = null, $disciplina = null)
    {
        try {
            if (!$disciplina) {
                return ['success' => false, 'error' => 'Disciplina é obrigatória'];
            }

            $sql = "INSERT INTO media_final (id_aluno, id_turma, id_professor, bimestre, disciplina, valor) VALUES (:id_aluno, :id_turma, :id_professor, :bimestre, :disciplina, :valor) 
                    ON DUPLICATE KEY UPDATE valor = VALUES(valor), atualizado_em = CURRENT_TIMESTAMP";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_aluno', $id_aluno, PDO::PARAM_INT);
            $stmt->bindValue(':id_turma', $id_turma !== null ? $id_turma : null, PDO::PARAM_INT);
            $stmt->bindValue(':id_professor', $id_professor !== null ? $id_professor : null, PDO::PARAM_INT);
            $stmt->bindValue(':bimestre', $bimestre, PDO::PARAM_INT);
            $stmt->bindValue(':disciplina', $disciplina);
            $stmt->bindValue(':valor', $media_final !== null && $media_final !== '' ? $media_final : null);

            $ok = $stmt->execute();
            if ($ok) {
                error_log('[salvarNotas] Sucesso: id_aluno='.$id_aluno.' bimestre='.$bimestre.' disciplina='.$disciplina.' valor='.$media_final);
                return ['success' => true];
            }
            $err = $stmt->errorInfo();
            error_log('[salvarNotas] PDO Error: '.json_encode($err));
            return ['success' => false, 'error' => ($err[2] ?? 'Erro ao salvar nota')];
        } catch (PDOException $e) {
            error_log('Erro salvarNotas: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function salvarNotasLote($bimestre, $notas = [], $id_turma = null, $id_professor = null, $disciplina = null)
    {
        try {
            if (!$disciplina) {
                return ['success' => false, 'error' => 'Disciplina é obrigatória'];
            }
            $this->conn->beginTransaction();
            $sql = "INSERT INTO media_final (id_aluno, id_turma, id_professor, bimestre, disciplina, valor) VALUES (:id_aluno, :id_turma, :id_professor, :bimestre, :disciplina, :valor) 
                    ON DUPLICATE KEY UPDATE valor = VALUES(valor), atualizado_em = CURRENT_TIMESTAMP";
            $stmt = $this->conn->prepare($sql);
            foreach ($notas as $n) {
                $id_aluno = isset($n['id_aluno']) ? intval($n['id_aluno']) : 0;
                $media_final = $n['media_final'] ?? null;

                $stmt->bindValue(':id_aluno', $id_aluno, PDO::PARAM_INT);
                $stmt->bindValue(':id_turma', $id_turma !== null ? $id_turma : null, PDO::PARAM_INT);
                $stmt->bindValue(':id_professor', $id_professor !== null ? $id_professor : null, PDO::PARAM_INT);
                $stmt->bindValue(':bimestre', $bimestre, PDO::PARAM_INT);
                $stmt->bindValue(':disciplina', $disciplina);
                $stmt->bindValue(':valor', $media_final !== null && $media_final !== '' ? $media_final : null);

                $ok = $stmt->execute();
                if (!$ok) {
                    $err = $stmt->errorInfo();
                    $this->conn->rollBack();
                    return ['success' => false, 'error' => ($err[2] ?? 'Erro ao salvar notas em lote')];
                }
            }
            $this->conn->commit();
            return ['success' => true];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log('Erro salvarNotasLote: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function listarNotasPorTurmaBimestre($id_turma = null, $bimestre = null, $disciplina = null)
    {
        try {
            if (!$id_turma || !$bimestre || !$disciplina) {
                return [];
            }

            $sql = "SELECT id_aluno, valor FROM media_final WHERE id_turma = :id_turma AND bimestre = :bimestre AND disciplina = :disciplina";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_turma', $id_turma, PDO::PARAM_INT);
            $stmt->bindValue(':bimestre', $bimestre, PDO::PARAM_INT);
            $stmt->bindValue(':disciplina', $disciplina);
            if ($stmt->execute()) {
                $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result = [];
                foreach ($dados as $row) {
                    $result[intval($row['id_aluno'])] = [
                        'media_final' => $row['valor'],
                    ];
                }
                return $result;
            }
            return [];
        } catch (PDOException $e) {
            error_log('Erro listarNotasPorTurmaBimestre: ' . $e->getMessage());
            return [];
        }
    }

    public function classificarDesempenho(float $media): string
    {
        if ($media < 4.0) {
            return 'Muito Crítico';
        }

        if ($media < 6.0) {
            return 'Crítico';
        }

        if ($media < 7.0) {
            return 'Intermediário';
        }

        return 'Adequado';
    }

    public function calcularDesempenhoPorTurma($id_turma)
    {
        try {
            if (!$id_turma) {
                return null;
            }

            $sql = "SELECT
                        t.id_turma,
                        t.nome_turma,
                        t.serie_turma,
                        t.letra,
                        t.sala_turma,
                        t.ano_letivo,
                        c.nome_curso,
                        COUNT(DISTINCT a.id_aluno) AS total_alunos,
                        COUNT(m.id) AS total_notas,
                        AVG(m.valor) AS media_geral
                    FROM turmas t
                    LEFT JOIN alunos a ON a.id_turma = t.id_turma
                    LEFT JOIN media_final m ON m.id_aluno = a.id_aluno AND m.valor IS NOT NULL
                    LEFT JOIN cursos c ON t.id_curso = c.id_curso
                    WHERE t.id_turma = :id_turma
                    GROUP BY t.id_turma";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_turma', $id_turma, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                return null;
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }

            $media = $row['media_geral'] !== null ? floatval($row['media_geral']) : 0.0;
            $classificacao = $this->classificarDesempenho($media);

            return [
                'id_turma' => intval($row['id_turma']),
                'nome_turma' => trim($row['nome_turma'] ?? ''),
                'serie_turma' => $row['serie_turma'] ?? '',
                'letra' => $row['letra'] ?? '',
                'sala_turma' => $row['sala_turma'] ?? '',
                'ano_letivo' => $row['ano_letivo'] ?? '',
                'nome_curso' => $row['nome_curso'] ?? '',
                'turma_descricao' => trim(sprintf('%sº %s - %s', $row['serie_turma'] ?? '', $row['letra'] ?? '', $row['nome_curso'] ?? '')),
                'total_alunos' => intval($row['total_alunos'] ?? 0),
                'total_notas' => intval($row['total_notas'] ?? 0),
                'media_geral' => round($media, 2),
                'classificacao' => $classificacao,
            ];
        } catch (PDOException $e) {
            error_log('Erro calcularDesempenhoPorTurma: ' . $e->getMessage());
            return null;
        }
    }

    public function calcularDesempenhoMedioProfessor($id_professor, $bimestre = null)
    {
        try {
            if (!$bimestre) {
                $bimestre = (date('n') <= 3) ? 1 : ((date('n') <= 6) ? 2 : ((date('n') <= 9) ? 3 : 4));
            }

            // Buscar todas as notas dos alunos de turmas do professor para o bimestre
            $sql = "SELECT AVG(n.valor) as media_geral, COUNT(n.id) as total_notas
                    FROM media_final n
                    INNER JOIN turmas t ON n.id_turma = t.id_turma
                    WHERE t.id_professor = :id_professor AND n.bimestre = :bimestre AND n.valor IS NOT NULL";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_professor', $id_professor, PDO::PARAM_INT);
            $stmt->bindValue(':bimestre', $bimestre, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                return 'Adequado';
            }

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$resultado || !$resultado['total_notas']) {
                return 'Adequado';
            }

            $mediaGeral = floatval($resultado['media_geral'] ?? 0);

            // Classificar de acordo com as faixas especificadas
            if ($mediaGeral < 4) {
                return 'Muito Crítico';
            } else if ($mediaGeral >= 4 && $mediaGeral < 6) {
                return 'Crítico';
            } else if ($mediaGeral >= 6 && $mediaGeral < 7) {
                return 'Intermediário';
            } else {
                return 'Adequado';
            }

        } catch (PDOException $e) {
            error_log('Erro calcularDesempenhoMedioProfessor: ' . $e->getMessage());
            return 'Adequado';
        }
    }

}

?>
