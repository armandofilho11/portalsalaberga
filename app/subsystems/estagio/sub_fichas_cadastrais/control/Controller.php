<?php

require_once('../model/model.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido.');
}



                $model = new model();


$acao = $_POST['acao'] ?? '';

switch ($acao) {

        case 'cadastrar_concedente':
                $dados = [
                    'instituicao'             => $_POST['instituicao'] ?? '',
                    'rede'                    => $_POST['rede'] ?? '',
                    'razao_social'            => trim($_POST['razao_social'] ?? ''),
                    'nome_fantasia'           => trim($_POST['nome_fantasia'] ?? ''),
                    'cnpj'                    => trim($_POST['cnpj'] ?? ''),
                    'especificacao_atividade' => trim($_POST['especificacao_atividade'] ?? ''),
                    'email_institucional'     => trim($_POST['email_institucional'] ?? ''),
                    'telefone_institucional'  => trim($_POST['telefone_institucional'] ?? ''),
                    'representante_legal'     => trim($_POST['representante_legal'] ?? ''),
                    'email_representante'     => trim($_POST['email_representante'] ?? ''),
                    'cpf_representante'       => trim($_POST['cpf_representante'] ?? ''),
                    'rg_representante'        => trim($_POST['rg_representante'] ?? ''),
                    'rua'                     => trim($_POST['rua'] ?? ''),
                    'numero'                  => trim($_POST['numero'] ?? ''),
                    'bairro'                  => trim($_POST['bairro'] ?? ''),
                    'municipio'               => trim($_POST['municipio'] ?? ''),
                    'cep'                     => trim($_POST['cep'] ?? ''),
                    'observacoes'             => trim($_POST['observacoes'] ?? '')
                ];
                
                
                                
                
                
                $model->cadastrar_concedente($dados);
                header('Location: ../view/sucesso.php');
                
                exit;

case 'cadastrar_vagas':

    $dados = [
        'concedente_id'        => $_POST['concedente_id'] ?? '',
        'forma_selecao'        => $_POST['forma_selecao'] ?? '',
        'vagas_desenvolvedor'  => (int)($_POST['vagas_desenvolvedor'] ?? 0),
        'vagas_design'         => (int)($_POST['vagas_design'] ?? 0),
        'vagas_suporte'        => (int)($_POST['vagas_suporte'] ?? 0),
        'vagas_tutoria'        => (int)($_POST['vagas_tutoria'] ?? 0),
        'data_selecao'         => $_POST['data'] ?? '',
        'horario_selecao'      => $_POST['hora'] ?? '',
        'procurar_por'         => $_POST['procurar_por'] ?? '',
        'observacoes'          => trim($_POST['observacoes'] ?? '')
    ];

                $model->cadastrar_vagas($dados);
//                header('Location: ../view/sucesso.php');
                
                exit;

    
    

    default:
        exit('Ação não reconhecida.');
}