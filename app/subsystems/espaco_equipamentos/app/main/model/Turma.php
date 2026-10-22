<?php
require_once __DIR__ . '/../config/database.php';

class Turma {
    private $conn;

    public function __construct() {
        try {
            $this->conn = getDatabaseConnection();
        } catch (PDOException $e) {
            error_log("[Turma] Erro na conexão com o banco: " . $e->getMessage());
            throw new Exception("Erro na conexão com o banco de dados");
        }
    }

    public function listar_turmas() {
        try {
            $sql = "SELECT DISTINCT id, nome FROM turmas ORDER BY nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Corrigir caracteres especiais na listagem
            foreach ($turmas as &$turma) {
                $turma['nome'] = str_replace(['??', '??'], ['º', 'ª'], $turma['nome']);
            }
            return $turmas;
        } catch (PDOException $e) {
            error_log("[Turma] Erro ao listar turmas: " . $e->getMessage());
            throw new Exception("Erro ao listar turmas");
        }
    }

    public function listar_alunos_por_turma($turma_id) {
        try {
            $sql = "SELECT a.id, a.nome 
                    FROM alunos a 
                    JOIN alunos_turmas at ON a.id = at.aluno_id 
                    WHERE at.turma_id = :turma_id 
                    ORDER BY a.nome";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("[Turma] Erro ao listar alunos por turma: " . $e->getMessage());
            throw new Exception("Erro ao listar alunos por turma");
        }
    }
}
?>