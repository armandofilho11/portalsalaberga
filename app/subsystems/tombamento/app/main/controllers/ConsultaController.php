<?php
require_once '../includes/conexao.php';

class ConsultaController {
    public function salvarConsulta($criterio, $valor, $ids_bens) {
        global $pdo;

        $stmt = $pdo->prepare("INSERT INTO Consulta (criterio, valor, data_consulta) VALUES (?, ?, NOW())");
        $stmt->execute([$criterio, $valor]);
        $id_consulta = $pdo->lastInsertId();

        $stmt_bem = $pdo->prepare("INSERT INTO Consulta_Bem (id_consulta, id_bem) VALUES (?, ?)");
        foreach ($ids_bens as $id_bem) {
            $stmt_bem->execute([$id_consulta, $id_bem]);
        }

        return $id_consulta;
    }

    public function listarConsultas() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Consulta ORDER BY data_consulta DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function visualizarConsulta($id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM Consulta WHERE id_consulta = ?");
        $stmt->execute([$id]);
        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt_bens = $pdo->prepare("
            SELECT Bem.*, Setor.nome AS setor_nome, Categoria.nome AS categoria_nome 
            FROM Consulta_Bem 
            JOIN Bem ON Consulta_Bem.id_bem = Bem.id_bem 
            LEFT JOIN Setor ON Bem.setor_id = Setor.id_setor 
            LEFT JOIN Categoria ON Bem.categoria_id = Categoria.id_categoria 
            WHERE Consulta_Bem.id_consulta = ?
        ");
        $stmt_bens->execute([$id]);
        $bens = $stmt_bens->fetchAll(PDO::FETCH_ASSOC);

        return ['consulta' => $consulta, 'bens' => $bens];
    }
}