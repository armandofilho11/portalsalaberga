<?php
require_once '../config/db.php';

class Declaracao {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConexao();
    }

    public function registrar($tipo, $nup, $natureza) {
        try {
            $sql = "INSERT INTO declaracoes (tipo, nup, natureza) VALUES (:tipo, :nup, :natureza)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':nup', $nup);
            $stmt->bindParam(':natureza', $natureza);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao registrar declaração: " . $e->getMessage());
            return false;
        }
    }

    public function listarTodas() {
        try {
            $sql = "SELECT * FROM declaracoes ORDER BY id DESC";
            return $this->conexao->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar declarações: " . $e->getMessage());
            return [];
        }
    }
    
    public function editar($id, $tipo, $nup, $natureza) {
        try {
            $sql = "UPDATE declaracoes SET tipo = :tipo, nup = :nup, natureza = :natureza WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':nup', $nup);
            $stmt->bindParam(':natureza', $natureza);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao editar declaração: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM declaracoes WHERE id = :id LIMIT 1";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar declaração por ID: " . $e->getMessage());
            return null;
        }
    }

    public function excluir($id) {
        try {
            $sql = "DELETE FROM declaracoes WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao excluir declaração: " . $e->getMessage());
            return false;
        }
    }
}
