<?php

use LDAP\Result;

require_once('../config/connect.php');


class main_model extends connect
{
    function __construct()
    {
        parent::__construct();
    }

    function login($email, $senha)
    {
        session_start();
        $stmt_cadastro = $this->connect->prepare("SELECT * FROM usuario WHERE email = :email AND senha = :senha");
        $stmt_cadastro->bindValue(':email', $email);
        $stmt_cadastro->bindValue(':senha', $senha);
        $stmt_cadastro->execute();

        if ($stmt_cadastro->rowCount() > 0) {

            $_SESSION['email'] = $email;
            return 1;
        } else {

            unset($_SESSION['email']);
            return 2;
        }
    }
    function cadastrar_empresa($nome, $endereco, $telefone, $nome_contato = '')
    {
        // Verificar se a empresa já existe
        $stmt_check = $this->connect->prepare("SELECT * FROM concedentes WHERE nome = :nome");
        $stmt_check->bindValue(':nome', $nome);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if (empty($result)) {

            try {

                $stmt_cadastrar_empresa = $this->connect->prepare("INSERT INTO concedentes VALUES (null, :nome, :nome_contato, :contato, :endereco)");
                $stmt_cadastrar_empresa->bindValue(':nome', $nome);
                $stmt_cadastrar_empresa->bindValue(':nome_contato', $nome_contato);
                $stmt_cadastrar_empresa->bindValue(':contato', $telefone);
                $stmt_cadastrar_empresa->bindValue(':endereco', $endereco);
                $stmt_cadastrar_empresa->execute();

                return 1;
            } catch (Exception $e) {

                return 2;
            }
        } else {
            return 3;
        }
    }
    function cadastrar_vaga($id_empresa, $id_area, $quant_vagas, $quant_candidatos, $data, $tipo_vaga, $hora)
    {
        $stmt_cadastrar_vagas = $this->connect->prepare("INSERT INTO vagas VALUES (null, :id_concedente, :id_perfil, :quant_vagas, :quant_candidatos, :data, :tipo_vaga, :hora)");
        $stmt_cadastrar_vagas->bindValue(':id_perfil', $id_area);
        $stmt_cadastrar_vagas->bindValue(':id_concedente', $id_empresa);
        $stmt_cadastrar_vagas->bindValue(':quant_vagas', $quant_vagas);
        $stmt_cadastrar_vagas->bindValue(':quant_candidatos', $quant_candidatos);
        $stmt_cadastrar_vagas->bindValue(':data', $data);
        $stmt_cadastrar_vagas->bindValue(':tipo_vaga', $tipo_vaga);
        $stmt_cadastrar_vagas->bindValue(':hora', $hora);
        $stmt_cadastrar_vagas->execute();

        if ($stmt_cadastrar_vagas) {
            return 1;
        } else {
            return 2;
        }
    }

    function selecao($alunos, $id_vaga)
    {
        foreach ($alunos as $aluno_id) {
            // Verifica se já existe na tabela selecao
            $stmt = $this->connect->prepare("SELECT 1 FROM selecao WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
            $stmt->execute([
                ':id_aluno' => $aluno_id,
                ':id_vaga' => $id_vaga
            ]);
            if ($stmt->rowCount() > 0) {
                continue; // Já existe
            }

            // Insere na tabela selecao
            $stmt = $this->connect->prepare("INSERT INTO selecao (id_aluno, id_vaga) VALUES (:id_aluno, :id_vaga)");
            $stmt->execute([
                ':id_aluno' => $aluno_id,
                ':id_vaga' => $id_vaga
            ]);
        }
        return 1; // Sucesso
    }
    function editar_aluno($id, $nome, $contato, $medias, $email, $projetos, $perfil_opc1, $perfil_opc2, $ocorrencia, $custeio, $entregas_individuais, $entregas_grupo)
    {
        $stmt = $this->connect->prepare("UPDATE `aluno` SET `nome`=:nome, `contato`=:contato, `medias`=:medias, `email`=:email, `projetos`=:projetos, `perfil_opc1`=:opc1, `perfil_opc2`=:opc2, `ocorrencia`=:ocorrencia, `custeio`=:custeio,  `entregas_individuais`=:entrega_individuais, `entregas_grupo`=:entrega_grupo WHERE id = :id");

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':contato', $contato);
        $stmt->bindValue(':medias', $medias);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':projetos', $projetos);
        $stmt->bindValue(':opc1', $perfil_opc1);
        $stmt->bindValue(':opc2', $perfil_opc2);
        $stmt->bindValue(':ocorrencia', $ocorrencia);
        $stmt->bindValue(':custeio', $custeio);
        $stmt->bindValue(':entrega_individuais', $entregas_individuais);
        $stmt->bindValue(':entrega_grupo', $entregas_grupo);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        if ($stmt) {

            return 1;
        } else {

            return 2;
        }
    }
    function editar_vaga($id, $empresa, $perfil, $quant_vaga, $quant_cand, $tipo_vaga, $data, $hora)
    {
        $stmt_excluirEmpresa = $this->connect->prepare("UPDATE `vagas` SET `id_concedente`= :empresa,`id_perfil`= :perfil,`quant_vaga`= :quant_vaga,`quant_cand`= :quant_candidatos,`data`= :data ,`tipo_vaga`= :tipo,`hora`= :hora WHERE id = :id");
        $stmt_excluirEmpresa->bindValue(':id', $id);
        $stmt_excluirEmpresa->bindValue(':empresa', $empresa);
        $stmt_excluirEmpresa->bindValue(':perfil', $perfil);
        $stmt_excluirEmpresa->bindValue(':quant_vaga', $quant_vaga);
        $stmt_excluirEmpresa->bindValue(':quant_candidatos', $quant_cand);
        $stmt_excluirEmpresa->bindValue(':data', $data);
        $stmt_excluirEmpresa->bindValue(':tipo', $tipo_vaga);
        $stmt_excluirEmpresa->bindValue(':hora', $hora);

        $stmt_excluirEmpresa->execute();
        if ($stmt_excluirEmpresa) {
            return 1;
        } else {
            return 2;
        }
    }
    function editar_empresa($id, $nome, $contato, $endereco, $nome_contato = '')
    {
        $stmt_excluirEmpresa = $this->connect->prepare("UPDATE `concedentes` SET `nome`= :nome, `nome_contato`= :nome_contato, `contato`= :contato, `endereco`= :endereco WHERE id = :id");
        $stmt_excluirEmpresa->bindValue(':id', $id);
        $stmt_excluirEmpresa->bindValue(':nome', $nome);
        $stmt_excluirEmpresa->bindValue(':nome_contato', $nome_contato);
        $stmt_excluirEmpresa->bindValue(':contato', $contato);
        $stmt_excluirEmpresa->bindValue(':endereco', $endereco);

        $stmt_excluirEmpresa->execute();
        if ($stmt_excluirEmpresa) {

            return 1;
        } else {

            return 2;
        }
    }
    function excluir_vaga($id_vaga)
    {
        $stmt_excluirVaga = $this->connect->query("DELETE FROM selecionado WHERE id_vaga = '$id_vaga'");
        $stmt_excluirVaga = $this->connect->query("DELETE FROM selecao WHERE id_vaga = '$id_vaga'");
        $stmt_excluirVaga = $this->connect->query("DELETE FROM vagas WHERE id = '$id_vaga'");


        if ($stmt_excluirVaga) {

            return 1;
        } else {

            return 2;
        }
    }
    function excluir_empresa($id_empresa)
    {
        $stmt_id_vaga = $this->connect->query("SELECT id FROM vagas WHERE id_concedente = '$id_empresa'");
        $id_vagas = $stmt_id_vaga->fetch(PDO::FETCH_ASSOC);
        $id_vaga = $id_vagas['id'];
        $stmt_excluirVaga = $this->connect->query("DELETE FROM selecionado WHERE id_vaga = '$id_vaga'");
        $stmt_excluirVaga = $this->connect->query("DELETE FROM selecao WHERE id_vaga = '$id_vaga'");
        $stmt_excluirVaga = $this->connect->query("DELETE FROM vagas WHERE id = '$id_vaga'");
        $stmt_excluirVaga = $this->connect->query("DELETE FROM vagas WHERE id_concedente = '$id_empresa'");

        if ($stmt_excluirVaga) {

            $stmt_excluirEmpresa = $this->connect->query("DELETE FROM concedentes WHERE id = '$id_empresa'");
            if ($stmt_excluirEmpresa) {

                return 1;
            } else {

                return 2;
            }
        } else {

            return 3;
        }
    }

    function criar_aluno($nome, $contato, $medias, $email, $projetos, $perfil_opc1, $perfil_opc2, $ocorrencia, $custeio, $entregas_individuais, $entregas_grupo)
    {
        $stmt_check = $this->connect->prepare("SELECT * FROM aluno WHERE nome = :nome");
        $stmt_check->bindValue(':nome', $nome);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {


            $stmt = $this->connect->prepare("INSERT INTO `aluno` (`nome`, `contato`, `medias`, `email`, `projetos`, `perfil_opc1`, `perfil_opc2`, `ocorrencia`, `custeio`, `entregas_individuais`, `entregas_grupo`) VALUES (:nome, :contato, :medias, :email, :projetos, :perfil_opc1, :perfil_opc2, :ocorrencia, :custeio, :entrega_individuais, :entrega_grupo)");
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':contato', $contato);
            $stmt->bindValue(':medias', $medias);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':projetos', $projetos);
            $stmt->bindValue(':perfil_opc1', $perfil_opc1);
            $stmt->bindValue(':perfil_opc2', $perfil_opc2);
            $stmt->bindValue(':ocorrencia', $ocorrencia);
            $stmt->bindValue(':custeio', $custeio);
            $stmt->bindValue(':entrega_individuais', $entregas_individuais);
            $stmt->bindValue(':entrega_grupo', $entregas_grupo);
            $stmt->execute();

            return 1;
        } else {
            return 2;
        }
    }
}