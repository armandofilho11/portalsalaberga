<?php
// Configura o fuso horário para Fortaleza
date_default_timezone_set('America/Fortaleza');

// Obtém a data e hora atual no formato compatível com o tipo DATETIME do MySQL
$data = date('Y-m-d H:i:s');


function cadastrarUsuario($nomeC, $email, $senha, $status)
{
    require_once('../config/connect.php');

    // Primeiro, verifica se o email já existe
    $stmtCheck = $conexao->prepare("SELECT COUNT(*) FROM usuario WHERE email = :email");
    $stmtCheck->bindValue(':email', $email);
    $stmtCheck->execute();

    if ($stmtCheck->fetchColumn() > 0) {

        return 0;
    } else {

        // Se o email não existe, procede com a inserção
        $stmtInsert = $conexao->prepare('INSERT INTO usuario (nome_user, email, senha, status) VALUES (:nomeC, :email, MD5(:senha), :status)');

        $stmtInsert->bindValue(':nomeC', $nomeC);
        $stmtInsert->bindValue(':email', $email);
        $stmtInsert->bindValue(':senha', $senha);
        $stmtInsert->bindValue(':status', $status);

        $stmtInsert->execute();

        return 1;
    }
}
function cadastrar($nome, $c1, $c2, $dn, $lp, $ar, $ef, $li, $ma, $ci, $ge, $hi, $re, $bairro, $publica, $pcd, $media)
{

    session_start();
    require_once('../config/connect.php');
    //inserido na tabela candidato os dados do candidato
    $result_cadastrar_candidato = $conexao->prepare("INSERT INTO candidato (nome, id_curso1_fk, id_curso2_fk, data_nascimento, bairro, publica, pcd, data, id_cadastrador) 
    VALUES( :nome, :id_curso1_fk, :id_curso2_fk, :data_nascimento, :bairro, :publica, :pcd, :data, :id)");
    $result_cadastrar_candidato->bindValue(':nome', $nome);
    $result_cadastrar_candidato->bindValue(':id', $_SESSION['id_cadastrador']);
    $result_cadastrar_candidato->bindValue(':id_curso1_fk', $c1);
    $result_cadastrar_candidato->bindValue(':id_curso2_fk', $c2);
    $result_cadastrar_candidato->bindValue(':data_nascimento', $dn);
    $result_cadastrar_candidato->bindValue(':bairro', $bairro);
    $result_cadastrar_candidato->bindValue(':publica', $publica);
    $result_cadastrar_candidato->bindValue(':pcd', $pcd);
    $result_cadastrar_candidato->bindValue(':data', $data);
    $result_cadastrar_candidato->execute();

    //procurando na tabela candidato o nome do candidato
    $result_check_id = $conexao->prepare("SELECT * FROM candidato WHERE nome = :nome");
    $result_check_id->bindValue(':nome', $nome);
    $result_check_id->execute();
    $dados = $result_check_id->fetchAll();
    foreach ($dados as $value => $x) {

        $id_candidato = $x['id_candidato'];
    }

    //inserindo na tabela nota, as notas do candidato cadastrado
    $result_cadastrar_nota = $conexao->prepare("INSERT INTO nota VALUES(:l_portuguesa, :arte, :educacao_fisica, :l_inglesa, :matematica, :ciencias, :geografia, :historia, :religiao, :candidato_id_candidato, :media )");
    $result_cadastrar_nota->BindValue(':l_portuguesa', $lp);
    $result_cadastrar_nota->BindValue(':arte', $ar);
    $result_cadastrar_nota->BindValue(':educacao_fisica', $ef);
    $result_cadastrar_nota->BindValue(':l_inglesa', $li);
    $result_cadastrar_nota->BindValue(':matematica', $ma);
    $result_cadastrar_nota->BindValue(':ciencias', $ci);
    $result_cadastrar_nota->BindValue(':geografia', $ge);
    $result_cadastrar_nota->BindValue(':historia', $hi);
    $result_cadastrar_nota->BindValue(':religiao', $re);
    $result_cadastrar_nota->BindValue(':candidato_id_candidato', $id_candidato);
    $result_cadastrar_nota->BindValue(':media', $media);
    $result_cadastrar_nota->execute();

    if ($result_cadastrar_candidato && $result_cadastrar_nota) {

        return "candidato cadastrado com sucesso";
    } elseif ($result_cadastrar_candidato) {

        return "ERRO ao cadastrar a nota";
    } elseif ($result_cadastrar_nota) {

        return "ERRO ao cadastrar o candidato";
    }
}



function cadastrar2($nome, $c1, $c2, $dn, $lp, $ar, $li, $ma, $ci, $ge, $hi, $re, $bairro, $publica, $pcd, $media)
{
    session_start();
    require_once('../config/connect.php');
    //inserido na tabela candidato os dados do candidato
    $result_cadastrar_candidato = $conexao->prepare("INSERT INTO candidato (nome, id_curso1_fk, id_curso2_fk, data_nascimento, bairro, publica, pcd, id_cadastrador) 
    VALUES( :nome, :id_curso1_fk, :id_curso2_fk, :data_nascimento, :bairro, :publica, :pcd, :id)");
    $result_cadastrar_candidato->bindValue(':nome', $nome);
    $result_cadastrar_candidato->bindValue(':id', $_SESSION['id_cadastrador']);
    $result_cadastrar_candidato->bindValue(':id_curso1_fk', $c1);
    $result_cadastrar_candidato->bindValue(':id_curso2_fk', $c2);
    $result_cadastrar_candidato->bindValue(':data_nascimento', $dn);
    $result_cadastrar_candidato->bindValue(':bairro', $bairro);
    $result_cadastrar_candidato->bindValue(':publica', $publica);
    $result_cadastrar_candidato->bindValue(':pcd', $pcd);
    $result_cadastrar_candidato->execute();

    //procurando na tabela candidato o nome do candidato
    $result_check_id = $conexao->prepare("SELECT * FROM candidato WHERE nome = :nome");
    $result_check_id->bindValue(':nome', $nome);
    $result_check_id->execute();
    $dados = $result_check_id->fetchAll();
    foreach ($dados as $value => $x) {

        $id_candidato = $x['id_candidato'];
    }

    //inserindo na tabela nota, as notas do candidato cadastrado
    $result_cadastrar_nota = $conexao->prepare("INSERT INTO nota VALUES(:l_portuguesa, :arte, 0, :l_inglesa, :matematica, :ciencias, :geografia, :historia, :religiao, :candidato_id_candidato, :media )");
    $result_cadastrar_nota->BindValue(':l_portuguesa', $lp);
    $result_cadastrar_nota->BindValue(':arte', $ar);
    $result_cadastrar_nota->BindValue(':l_inglesa', $li);
    $result_cadastrar_nota->BindValue(':matematica', $ma);
    $result_cadastrar_nota->BindValue(':ciencias', $ci);
    $result_cadastrar_nota->BindValue(':geografia', $ge);
    $result_cadastrar_nota->BindValue(':historia', $hi);
    $result_cadastrar_nota->BindValue(':religiao', $re);
    $result_cadastrar_nota->BindValue(':candidato_id_candidato', $id_candidato);
    $result_cadastrar_nota->BindValue(':media', $media);
    $result_cadastrar_nota->execute();

    if ($result_cadastrar_candidato && $result_cadastrar_nota) {

        return "candidato cadastrado com sucesso";
    } elseif ($result_cadastrar_candidato) {

        return "ERRO ao cadastrar a nota";
    } elseif ($result_cadastrar_nota) {

        return "ERRO ao cadastrar o candidato";
    }
}



function logar($email, $senha)
{
    require_once('../config/connect.php');
    
    //verificando se os dados estão no sistema 
    $result_logar = $conexao->prepare("SELECT * FROM usuario WHERE email = :email AND senha = MD5(:senha)");
    $result_logar->bindValue(':email', $email);
    $result_logar->bindValue(':senha', $senha);
    $result_logar->execute();
    $row_count = $result_logar->rowCount();
    $result = $result_logar->fetchAll(PDO::FETCH_ASSOC);
    
    // Se encontrou algum resultado
    if ($row_count > 0) {
         // Assumindo que sempre haverá apenas um usuário correspondente
        $_SESSION['login'] = true;
        $_SESSION['status'] = $result[0]['status'];
        $_SESSION['id_cadastrador'] = $result[0]['id'];
        $_SESSION['refresh'] = 0;
        return $result[0]['status'];
    } else {
        // Se não encontrou resultados, redirecionar para a página inicial
        return 2;
    }
}


function delete($senha)
{
    require_once('../config/connect.php');

    // Verificando se os dados estão no sistema 
    $result_logar = $conexao->prepare("SELECT * FROM usuario WHERE senha = MD5(:senha)");
    $result_logar->bindValue(':senha', $senha);
    $result_logar->execute();
    $result = $result_logar->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($result)) {
        try {
            // Desativa a verificação de chaves estrangeiras
            $conexao->exec('SET FOREIGN_KEY_CHECKS = 0');

            // Trunca as tabelas
            $conexao->exec('TRUNCATE TABLE nota');
            $conexao->exec('TRUNCATE TABLE candidato');

            // Reativa a verificação de chaves estrangeiras
            $conexao->exec('SET FOREIGN_KEY_CHECKS = 1');

            return true;
        } catch (PDOException $e) {
            // Garante que as chaves estrangeiras sejam reativadas
            $conexao->exec('SET FOREIGN_KEY_CHECKS = 1');
            throw new PDOException("Erro ao limpar as tabelas: " . $e->getMessage());
        }
    } else {
        return false;
        // senha incorreta
    }
}

function atualizar($lp, $ar, $ef, $li, $ma, $ci, $ge, $hi, $re, $id)
{
    require_once('../config/connect.php');
    
    // Inicializa o divisor com 9 (total de matérias)
    $divisor = 9;
    $soma = $lp + $ar + $ef + $li + $ma + $ci + $ge + $hi + $re;
    
    // Verifica cada matéria e ajusta o divisor
    if ($re == 0) {
        $divisor--;
    }
    if ($ef == 0) {
        $divisor--;
    }
    if ($ar == 0) {
        $divisor--;
    }
    
    // Calcula a média
    $md = $soma / $divisor;
    
    // Atualizando as notas do candidato
    $stmtUpdate = $conexao->prepare("UPDATE nota SET l_portuguesa=:lp, arte=:ar, educacao_fisica=:ef, l_inglesa=:li, matematica=:ma, ciencias=:ci, geografia=:ge, historia=:hi, religiao=:re, media=:media WHERE candidato_id_candidato = :id");

    $stmtUpdate->BindValue(':lp', $lp);
    $stmtUpdate->BindValue(':ar', $ar);
    $stmtUpdate->BindValue(':ef', $ef);
    $stmtUpdate->BindValue(':li', $li);
    $stmtUpdate->BindValue(':ma', $ma);
    $stmtUpdate->BindValue(':ci', $ci);
    $stmtUpdate->BindValue(':ge', $ge);
    $stmtUpdate->BindValue(':hi', $hi);
    $stmtUpdate->BindValue(':re', $re);
    $stmtUpdate->BindValue(':media', $md);
    $stmtUpdate->BindValue(':id', $id); // Usando o ID recebido como parâmetro
    $stmtUpdate->execute();
}

function notas($id)
{
    session_start();
    require_once('../config/connect.php');
    $stmtSelect = $conexao->prepare("select candidato.nome, candidato.id_candidato , candidato.data_nascimento, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd ,nota.l_portuguesa, nota.matematica, nota.historia, nota.geografia, nota.ciencias, nota.l_inglesa, nota.arte, nota.educacao_fisica, nota.religiao from candidato INNER JOIN nota 
        on candidato.id_candidato = nota.candidato_id_candidato
        where candidato.id_candidato = :id");
    $stmtSelect->BindValue(':id', $id);
    $stmtSelect->execute();
    $result = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
    if (empty($result)) {
        header('Location: ../controllers/atualizar.php?erro=1');
    }
    switch ($result[0]['id_curso1_fk']) {
        case 1:
            $_SESSION['curso'] = 'Enfermagem';
            break;

        case 2:
            $_SESSION['curso'] = 'Informática';
            break;
        case 3:
            $_SESSION['curso'] = 'Administração';
            break;
        case 3:
            $_SESSION['curso'] = 'Edificações';
            break;
    }
    switch ($result[0]['bairro']) {
        case 1:
            $_SESSION['bairro'] = '| Costista ';
            break;
        case 0:
            $_SESSION['bairro'] = '';
            break;
    }
    switch ($result[0]['pcd']) {
        case 1:
            $_SESSION['pcd'] = '| PCD';
            break;
        case 0:
            $_SESSION['pcd'] = '';
            break;
    }
    switch ($result[0]['publica']) {
        case 1:
            $_SESSION['publica'] = 'Pública ';
            break;
        case 0:
            $_SESSION['publica'] = 'Privada ';
            break;
    }
    $_SESSION['nome'] = $result[0]['nome'];
    $_SESSION['nasc'] = $result[0]['data_nascimento'];
    $_SESSION['lp'] = $result[0]['l_portuguesa'];
    $_SESSION['ar'] = $result[0]['arte'];
    $_SESSION['ef'] = $result[0]['educacao_fisica'];
    $_SESSION['li'] = $result[0]['l_inglesa'];
    $_SESSION['ma'] = $result[0]['matematica'];
    $_SESSION['ci'] = $result[0]['ciencias'];
    $_SESSION['ge'] = $result[0]['geografia'];
    $_SESSION['hi'] = $result[0]['historia'];
    $_SESSION['re'] = $result[0]['religiao'];
    $_SESSION['id'] = $result[0]['id_candidato'];
    print_r($_SESSION);
    header('Location: ../views/atualizar_nota.php');
    exit();
}
function lista_usuario(){
    require_once('../config/connect.php');
    $stmtSelect = $conexao->query('select * from usuario');
    return $result = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
}

function excluir_candidato($id_candidato)
{
    require_once('../../config/connect.php');
    $stmtCheck = $conexao->prepare("SELECT * FROM candidato WHERE id_candidato = :id_candidato");
    $stmtCheck->bindValue(':id_candidato', $id_candidato);
    $stmtCheck->execute();
    $row_count = $stmtCheck->rowCount();

    if ($row_count > 0) {

        $stmt_excluir_candidato = $conexao->prepare("DELETE FROM nota WHERE candidato_id_candidato = :id_candidato1");
        $stmt_excluir_candidato->bindValue(':id_candidato1', $id_candidato);
        $stmt_excluir_candidato->execute();

        $stmt_excluir_candidato = $conexao->prepare("DELETE FROM candidato WHERE id_candidato = :id_candidato2");
        $stmt_excluir_candidato->bindValue(':id_candidato2', $id_candidato);
        $stmt_excluir_candidato->execute();

        if ($stmt_excluir_candidato) {

            return 1;
        } else {

            return 2;
        }
    } else {

        return 3;
    }
}

function excluir_usuario($nome)
{
    require_once('../../config/connect.php');
    $stmtCheck = $conexao->prepare("SELECT * FROM usuario WHERE nome_user = :nome");
    $stmtCheck->bindValue(':nome', $nome);
    $stmtCheck->execute();
    $row_count = $stmtCheck->rowCount();

    if ($row_count > 0) {

        $stmt_excluir_usuario = $conexao->prepare("DELETE FROM usuario WHERE nome_user = :nome1");
        $stmt_excluir_usuario->bindValue(':nome1', $nome);
        $stmt_excluir_usuario->execute();

        if ($stmt_excluir_usuario) {

            return 1;
        } else {

            return 2;
        }
    } else {

        return 3;
    }
}
