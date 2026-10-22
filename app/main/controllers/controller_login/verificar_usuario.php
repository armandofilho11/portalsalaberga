<?php
require_once(__DIR__ . '/../../config/connect.php');

try {
    $conexao = getConnection();
    
    // Verifica se o usuário existe
    $email = '1@gmail.com';
    $query = "SELECT id, email, nome, senha FROM usuarios WHERE email = :email";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "Usuário encontrado:<br>";
        echo "ID: " . $usuario['id'] . "<br>";
        echo "Email: " . $usuario['email'] . "<br>";
        echo "Nome: " . $usuario['nome'] . "<br>";
        echo "Senha (MD5): " . $usuario['senha'] . "<br>";
        echo "Senha '1' em MD5: " . md5('1') . "<br>";
        
        // Verifica se a senha está correta
        if ($usuario['senha'] === md5('1')) {
            echo "<br>Senha está correta!<br>";
        } else {
            echo "<br>Senha está incorreta!<br>";
        }
        
        // Verifica as permissões do usuário
        $usuario_id = $usuario['id'];
        $query = "
            SELECT 
                p.id as permissao_id,
                p.descricao as permissao_descricao,
                s.id as sistema_id,
                s.sistema as sistema_nome
            FROM usu_sist us
            JOIN sist_perm sp ON us.sist_perm_id = sp.id
            JOIN permissoes p ON sp.permissao_id = p.id
            JOIN sistemas s ON sp.sistema_id = s.id
            WHERE us.usuario_id = :usuario_id
        ";
        $stmt = $conexao->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $permissoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<br>Permissões do usuário:<br>";
        if (count($permissoes) > 0) {
            foreach ($permissoes as $permissao) {
                echo "Sistema: " . $permissao['sistema_nome'] . " - Permissão: " . $permissao['permissao_descricao'] . "<br>";
            }
        } else {
            echo "Usuário não possui permissões cadastradas<br>";
        }
    } else {
        echo "Usuário não encontrado";
    }
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
} 