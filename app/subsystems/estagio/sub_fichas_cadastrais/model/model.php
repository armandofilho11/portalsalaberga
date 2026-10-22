<?php

use LDAP\Result;

require_once('../../config/connect.php');


class model extends connect{
   
   
    function __construct(){
        parent::__construct();
    }









// =================================================================
// ======================= LISTAR CONCEDENTES ======================
// =================================================================

public function listar_concedentes()
{
    $sql = "SELECT id, nome
            FROM concedentes
            ORDER BY nome ASC";

    $stmt = $this->connect->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// =================================================================
// =================================================================







// =================================================================
// ===================== CADASTRAR CONCEDENTES =====================
// =================================================================

public function cadastrar_concedente($dados){
    $sql = "
        INSERT INTO ficha_concedente
        (
            instituicao,
            rede,
            razao_social,
            nome_fantasia,
            cnpj,
            especificacao_atividade,
            email_institucional,
            telefone_institucional,
            representante_legal,
            email_representante,
            cpf_representante,
            rg_representante,
            rua,
            numero,
            bairro,
            municipio,
            cep,
            observacoes
        )
        VALUES
        (
            ?,?,?,?,?,?,?,?,?,?,
            ?,?,?,?,?,?,?,?
        )
    ";

    $stmt = $this->connect->prepare($sql);

    return $stmt->execute([
        $dados['instituicao'],
        $dados['rede'],
        $dados['razao_social'],
        $dados['nome_fantasia'],
        $dados['cnpj'],
        $dados['especificacao_atividade'],
        $dados['email_institucional'],
        $dados['telefone_institucional'],
        $dados['representante_legal'],
        $dados['email_representante'],
        $dados['cpf_representante'],
        $dados['rg_representante'],
        $dados['rua'],
        $dados['numero'],
        $dados['bairro'],
        $dados['municipio'],
        $dados['cep'],
        $dados['observacoes']
    ]);
}

// =================================================================
// =================================================================






    
// =================================================================
// ======================== CADASTRAR VAGAS ========================
// =================================================================

public function cadastrar_vagas($dados){
    $perfis = [
        'DESENVOLVEDOR' => $dados['vagas_desenvolvedor'],
        'DESIGN'        => $dados['vagas_design'],
        'SUPORTE'       => $dados['vagas_suporte'],
        'TUTORIA'       => $dados['vagas_tutoria']
    ];



foreach ($perfis as $perfil => $quantidade) {

    // Não grava perfis sem vagas
    if ($quantidade <= 0) {
        continue;
    }

$concedente_id = $dados['concedente_id'];
$forma_selecao = $dados['forma_selecao'];
$data_selecao = $dados['data_selecao'];
$horario_selecao = $dados['horario_selecao'];
$procurar_por = $dados['procurar_por'];
$observacoes = $dados['observacoes'];

    $sql_cad_vagas = "
INSERT INTO ficha_vagas_concedente
(
    concedente_id,
    perfil,
    quantidade,
    forma_selecao,
    data_selecao,
    horario_selecao,
    procurar_por,
    observacoes
)
VALUES
(
    $concedente_id,
    '$perfil',
    $quantidade,
    '$forma_selecao',
    '$data_selecao',
    '$horario_selecao',
    '$procurar_por',
    '$observacoes'
)";


$stmt = $this->connect->prepare($sql_cad_vagas);

try{
    $stmt->execute();
}catch(PDOException $e){
    die($e->getMessage());
}
    
}
    return true;
}




}