<?php
require_once __DIR__ . '/../../config.php';

class Cardapio {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = getPDOConnection();
        } catch (PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function cadastrarCardapio($data, $tipo, $nome, $descricao) {
        $consulta = "INSERT INTO cardápio (data, tipo, nome, descricao) VALUES (:data, :tipo, :nome, :descricao)";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":data", $data);
        $query->bindValue(":tipo", $tipo);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":descricao", $descricao);
        $query->execute();
    }

    public function listarCardapios() {
        $consulta = "SELECT * FROM cardápio ORDER BY data, tipo";
        $query = $this->pdo->prepare($consulta);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterCardapioPorId($id) {
        $consulta = "SELECT * FROM cardápio WHERE id = :id";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarCardapio($id, $data, $tipo, $nome, $descricao) {
        $consulta = "UPDATE cardápio SET data = :data, tipo = :tipo, nome = :nome, descricao = :descricao WHERE id = :id";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        $query->bindValue(":data", $data);
        $query->bindValue(":tipo", $tipo);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":descricao", $descricao);
        $query->execute();
        if ($query->rowCount() === 0) {
            throw new Exception("Nenhum cardápio foi atualizado. Verifique se o ID existe.");
        }
    }

    public function excluirCardapio($id) {
        $consulta = "DELETE FROM cardápio WHERE id = :id";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":id", $id);
        error_log("Executando query: DELETE FROM cardápio WHERE id = $id"); // Log para depuração
        $query->execute();
        if ($query->rowCount() === 0) {
            throw new Exception("Nenhum cardápio foi excluído. Verifique se o ID existe.");
        }
    }
}

class CardapioView {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = getPDOConnection();
        } catch (PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public function exibirCardapio($year = null) {
        $consulta = "SELECT * FROM cardápio WHERE YEAR(data) = :year ORDER BY data, tipo";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":year", $year ?? date('Y'), PDO::PARAM_INT);
        $query->execute();
        $cardapios = $query->fetchAll(PDO::FETCH_ASSOC);

        $cardapiosOrganizados = [
            'lanche-manha' => [],
            'almoco' => [],
            'lanche-tarde' => []
        ];

        foreach ($cardapios as $cardapio) {
            $data = $cardapio['data'];
            $tipo = $cardapio['tipo'];
            if (!isset($cardapiosOrganizados[$tipo][$data])) {
                $cardapiosOrganizados[$tipo][$data] = [];
            }
            $cardapiosOrganizados[$tipo][$data][] = $cardapio;
        }

        return $cardapiosOrganizados;
    }
}
?>