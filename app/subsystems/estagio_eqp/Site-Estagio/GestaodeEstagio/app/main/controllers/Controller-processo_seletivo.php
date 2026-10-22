<?php
require_once '../models/model-function.php';

if (isset($_POST['btn'])) {
    $empresa_id = $_POST['empresa_id'];
    $aluno_id = $_POST['aluno_id'];
    $vaga_id = $_POST['vaga_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $local = $_POST['local'];

    // Validações
    if (empty($empresa_id) || empty($aluno_id) || empty($vaga_id) || empty($data) || empty($hora) || empty($local)) {
        header("Location: ../views/processoseletivo.php?error=campos_vazios");
        exit;
    }

    $pdo = new PDO("mysql:host=localhost;dbname=u750204740_gestaoestagio","root","");
    
    // Verifica se a vaga ainda está disponível
    $consulta = "SELECT numero_vagas FROM concedentes WHERE id = :vaga_id";
    $query = $pdo->prepare($consulta);
    $query->bindValue(":vaga_id", $vaga_id);
    $query->execute();
    $vaga = $query->fetch();

    if ($vaga['numero_vagas'] <= 0) {
        header("Location: ../views/processoseletivo.php?error=vaga_indisponivel");
        exit;
    }

    // Verifica se o aluno já está em outro processo seletivo para a mesma vaga
    $consulta = "SELECT id FROM processo_seletivo WHERE aluno_id = :aluno_id AND vaga_id = :vaga_id AND status = 'pendente'";
    $query = $pdo->prepare($consulta);
    $query->bindValue(":aluno_id", $aluno_id);
    $query->bindValue(":vaga_id", $vaga_id);
    $query->execute();

    if ($query->rowCount() > 0) {
        header("Location: ../views/processoseletivo.php?error=aluno_ja_inscrito");
        exit;
    }

    // Insere o processo seletivo
    $consulta = "INSERT INTO processo_seletivo (empresa_id, aluno_id, vaga_id, data, hora, local) 
                 VALUES (:empresa_id, :aluno_id, :vaga_id, :data, :hora, :local)";
    $query = $pdo->prepare($consulta);
    $query->bindValue(":empresa_id", $empresa_id);
    $query->bindValue(":aluno_id", $aluno_id);
    $query->bindValue(":vaga_id", $vaga_id);
    $query->bindValue(":data", $data);
    $query->bindValue(":hora", $hora);
    $query->bindValue(":local", $local);
    
    if ($query->execute()) {
        // Atualiza o número de vagas disponíveis
        $consulta = "UPDATE concedentes SET numero_vagas = numero_vagas - 1 WHERE id = :vaga_id";
        $query = $pdo->prepare($consulta);
        $query->bindValue(":vaga_id", $vaga_id);
        $query->execute();

        header("Location: ../views/processoseletivo.php?success=processo_criado");
    } else {
        header("Location: ../views/processoseletivo.php?error=erro_criacao");
    }
    exit;
}

// Se não for POST, redireciona para a página de processo seletivo
header("Location: ../views/processoseletivo.php");
exit; 