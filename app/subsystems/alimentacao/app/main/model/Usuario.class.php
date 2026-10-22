<?php
require_once __DIR__ . '/../../config.php';

class Usuario {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = getPDOConnection();
        } catch (PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public function cadastrarUsuario($tipo_usuario, $nome, $email, $senha) {
        $senhaHash = $senha; // Store password as plaintext
        $consulta = "INSERT INTO usuario (tipo_usuario, nome, email, senha, profile_photo, created_at) VALUES (:tipo_usuario, :nome, :email, :senha, NULL, CURRENT_TIMESTAMP)";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":tipo_usuario", $tipo_usuario);
        $query->bindValue(":nome", $nome);
        $query->bindValue(":email", strtolower($email));
        $query->bindValue(":senha", $senhaHash);
        $query->execute();
        return $this->pdo->lastInsertId();
    }

    public function login($login, $senha) {
        $consulta = "SELECT * FROM usuario WHERE LOWER(email) = LOWER(:login) LIMIT 1";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":login", $login);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if ($senha === $result['senha']) {
                error_log("Login successful for: " . $result['email']);
                return $result;
            } else {
                error_log("Password verification failed for: $login");
            }
        } else {
            error_log("User not found for login: $login");
        }
        return false;
    }

    public function excluirUsuario($email) {
        $consulta = "DELETE FROM usuario WHERE email = :email";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(":email", $email);
        $query->execute();
    }

    public function listarUsuarios() {
        $consulta = "SELECT id, nome, email, tipo_usuario FROM usuario";
        $query = $this->pdo->prepare($consulta);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeEmail) {
        $consulta = "SELECT id FROM usuario WHERE email = :email AND email != :excludeEmail";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(':email', $email);
        $query->bindValue(':excludeEmail', $excludeEmail);
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function atualizarUsuario($originalEmail, $nome, $email, $tipo_usuario, $senha = null) {
        $consulta = "UPDATE usuario SET nome = :nome, email = :email, tipo_usuario = :tipo_usuario" . ($senha ? ", senha = :senha" : "") . " WHERE email = :originalEmail";
        $query = $this->pdo->prepare($consulta);
        $query->bindValue(':nome', $nome);
        $query->bindValue(':email', $email);
        $query->bindValue(':tipo_usuario', $tipo_usuario);
        if ($senha) {
            $query->bindValue(':senha', $senha);
        }
        $query->bindValue(':originalEmail', $originalEmail);
        return $query->execute();
    }
}
?>