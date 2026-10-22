<?php
require_once(__DIR__ . '/../../config/connect.php');

try {
    $conexao = getConnection();
    
    // Primeiro, verifica se o usuário já existe
    $email = '1@gmail.com';
    $query = "SELECT id FROM usuarios WHERE email = :email";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        // Cria o usuário
        $query = "INSERT INTO usuarios (email, nome, senha) VALUES (:email, :nome, :senha)";
        $stmt = $conexao->prepare($query);
        $nome = 'Usuário Teste';
        $senha = password_hash('1', PASSWORD_DEFAULT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        
        $usuario_id = $conexao->lastInsertId();
        echo "Usuário criado com sucesso! ID: " . $usuario_id . "<br>";
    } else {
        // Atualiza a senha do usuário existente
        $query = "UPDATE usuarios SET senha = :senha WHERE email = :email";
        $stmt = $conexao->prepare($query);
        $senha = password_hash('1', PASSWORD_DEFAULT);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        
        $usuario_id = $usuario['id'];
        echo "Senha do usuário atualizada com sucesso! ID: " . $usuario_id . "<br>";
    }
    
    // Verifica se existe o sistema 'areadev'
    $query = "SELECT id FROM sistemas WHERE sistema = 'areadev'";
    $stmt = $conexao->prepare($query);
    $stmt->execute();
    $sistema = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$sistema) {
        // Cria o sistema
        $query = "INSERT INTO sistemas (sistema) VALUES ('areadev')";
        $stmt = $conexao->prepare($query);
        $stmt->execute();
        $sistema_id = $conexao->lastInsertId();
        echo "Sistema criado com sucesso! ID: " . $sistema_id . "<br>";
    } else {
        $sistema_id = $sistema['id'];
        echo "Sistema já existe! ID: " . $sistema_id . "<br>";
    }
    
    // Verifica se existe a permissão 'usuario'
    $query = "SELECT id FROM permissoes WHERE descricao = 'usuario'";
    $stmt = $conexao->prepare($query);
    $stmt->execute();
    $permissao = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$permissao) {
        // Cria a permissão
        $query = "INSERT INTO permissoes (descricao) VALUES ('usuario')";
        $stmt = $conexao->prepare($query);
        $stmt->execute();
        $permissao_id = $conexao->lastInsertId();
        echo "Permissão criada com sucesso! ID: " . $permissao_id . "<br>";
    } else {
        $permissao_id = $permissao['id'];
        echo "Permissão já existe! ID: " . $permissao_id . "<br>";
    }
    
    // Verifica se existe a relação sistema-permissão
    $query = "SELECT id FROM sist_perm WHERE sistema_id = :sistema_id AND permissao_id = :permissao_id";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':sistema_id', $sistema_id);
    $stmt->bindParam(':permissao_id', $permissao_id);
    $stmt->execute();
    $sist_perm = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$sist_perm) {
        // Cria a relação sistema-permissão
        $query = "INSERT INTO sist_perm (sistema_id, permissao_id) VALUES (:sistema_id, :permissao_id)";
        $stmt = $conexao->prepare($query);
        $stmt->bindParam(':sistema_id', $sistema_id);
        $stmt->bindParam(':permissao_id', $permissao_id);
        $stmt->execute();
        $sist_perm_id = $conexao->lastInsertId();
        echo "Relação sistema-permissão criada com sucesso! ID: " . $sist_perm_id . "<br>";
    } else {
        $sist_perm_id = $sist_perm['id'];
        echo "Relação sistema-permissão já existe! ID: " . $sist_perm_id . "<br>";
    }
    
    // Verifica se existe a relação usuário-sistema-permissão
    $query = "SELECT id FROM usu_sist WHERE usuario_id = :usuario_id AND sist_perm_id = :sist_perm_id";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':sist_perm_id', $sist_perm_id);
    $stmt->execute();
    $usu_sist = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usu_sist) {
        // Cria a relação usuário-sistema-permissão
        $query = "INSERT INTO usu_sist (usuario_id, sist_perm_id) VALUES (:usuario_id, :sist_perm_id)";
        $stmt = $conexao->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':sist_perm_id', $sist_perm_id);
        $stmt->execute();
        echo "Relação usuário-sistema-permissão criada com sucesso!<br>";
    } else {
        echo "Relação usuário-sistema-permissão já existe!<br>";
    }
    
    echo "<br>Processo concluído com sucesso!";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
} 