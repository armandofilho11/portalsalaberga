<?php
require_once '../includes/conexao.php';

class Relatorio {
    public static function gerarRelatorio($filtros = []) {
        $pdo = Conexao::getInstance();

        $sql = "SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome
                FROM Bem
                LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor
                LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria
                WHERE 1=1";

        $params = [];

        if (!empty($filtros['setor'])) {
            $sql .= " AND Bem.setor_id = :setor";
            $params[':setor'] = $filtros['setor'];
        }

        if (!empty($filtros['categoria'])) {
            $sql .= " AND Bem.categoria_id = :categoria";
            $params[':categoria'] = $filtros['categoria'];
        }

        if (!empty($filtros['estado'])) {
            $sql .= " AND Bem.estado_conservacao = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
