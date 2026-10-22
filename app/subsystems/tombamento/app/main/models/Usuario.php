<?php
require_once __DIR__ . '/../includes/conexao.php';

class Usuario {
    public static function autenticar($login) {
        global $pdo;
        if (!$pdo) {
            file_put_contents('debug.log', "Erro: Falha na conexão com o banco.\n", FILE_APPEND);
            return false;
        }
        $sql = "SELECT * FROM Usuario WHERE login = :login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>