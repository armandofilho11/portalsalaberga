<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private $conn;
    private $table = 'usuarios';
    
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $nivel_conta;
    public $ativo;
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function criar() {
        $query = "INSERT INTO " . $this->table . " 
                  (nome, email, senha, nivel_conta) 
                  VALUES (:nome, :email, :senha, :nivel_conta)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':senha', $this->senha);
        $stmt->bindParam(':nivel_conta', $this->nivel_conta);
        
        return $stmt->execute();
    }
    
    public function login($email, $senha) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND ativo = 1 LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $row = $stmt->fetch();
        
        if ($row && password_verify($senha, $row['senha'])) {
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->email = $row['email'];
            $this->nivel_conta = $row['nivel_conta'];
            return true;
        }
        
        return false;
    }
    
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    public function atualizarNivel($id, $novo_nivel) {
        $query = "UPDATE " . $this->table . " SET nivel_conta = :nivel_conta WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nivel_conta', $novo_nivel);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Métodos de recompensa removidos - agora apenas nível do usuário importa
    // O acesso aos cursos é controlado apenas pelo nível_requerido do curso
    
    public function emailExiste($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
