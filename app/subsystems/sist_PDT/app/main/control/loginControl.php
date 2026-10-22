<?php
session_start();
require_once '../config/conexao.php';

if(isset($_POST['entrar'])) {
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $tipoUsuario = $_POST['tipoUsuario'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=sis_pdt2", "root", "");
        
        // Verificar se é um usuário PDT
        if($tipoUsuario === 'pdt') {
            $sql = "SELECT * FROM pdt WHERE nome = :nome AND matricula = :matricula";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':matricula', $matricula);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['usuario'] = $usuario;
                $_SESSION['tipo'] = 'pdt';
                header("Location: ../index.php");
                exit();
            }
        }
        // Verificar se é um professor
        else if($tipoUsuario === 'professor') {
            $sql = "SELECT * FROM professores WHERE nome = :nome AND matricula = :matricula";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':matricula', $matricula);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['usuario'] = $usuario;
                $_SESSION['tipo'] = 'professor';
                header("Location: ../index.php");
                exit();
            }
        }

        // Se não encontrou o usuário
        echo "<script>
            alert('Usuário não encontrado ou credenciais inválidas!');
            window.location.href = '../index.php';
        </script>";

    } catch(PDOException $e) {
        echo "<script>
            alert('Erro ao fazer login: " . $e->getMessage() . "');
            window.location.href = '../index.php';
        </script>";
    }
}
?> 