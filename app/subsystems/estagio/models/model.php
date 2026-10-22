<?php

use LDAP\Result;

require_once('../config/connect.php');


/**
 * Camada de escrita do sistema.
 *
 * Centraliza operacoes que alteram dados: login, cadastros, edicoes,
 * exclusoes e movimentacao de alunos entre selecao e aprovados.
 *
 * Convencao historica dos retornos:
 * 1 = sucesso, 2 = falha, 3 = caso especial conforme o metodo.
 */
class main_model extends connect
{
    function __construct()
    {
        parent::__construct();
    }

    function login(string $email, string $senha): int
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
    


public function alunos_op1($perfil_opc1)
{
    $sql = "
        SELECT *
        FROM alunos
        WHERE perfil_opc1 = ?
        ORDER BY medias DESC
    ";

    $stmt = $this->connect->prepare($sql);
    $stmt->execute([$perfil_opc1]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public function alunos_op2($perfil)
{
    $sql = "
        SELECT *
        FROM alunos
        WHERE perfil_opc2 = ?
        AND perfil_opc1 <> ?
        ORDER BY medias DESC
    ";

    $stmt = $this->connect->prepare($sql);
    $stmt->execute([
        $perfil,
        $perfil
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




    
    




public function cadastrar_selecao($id_vaga, $empresa, $quant_candidatos, $data, $hora) {

    try {

        $sql = "UPDATE vagas 
                SET empresa_id = :empresa,
                    quant_candidatos = :quant_candidatos,
                    data = :data,
                    hora = :hora
                WHERE id = :id";

        $stmt = $this->connect->prepare($sql);

        $stmt->bindValue(':empresa', $empresa);
        $stmt->bindValue(':quant_candidatos', $quant_candidatos);
        $stmt->bindValue(':data', $data);
        $stmt->bindValue(':hora', $hora);
        $stmt->bindValue(':id', $id_vaga);

        if ($stmt->execute()) {
            return 1;
        } else {
            return 2;
        }

    } catch (PDOException $e) {
        return 2;
    }
}




//FUNÇÃO CADASTRAR NOVA EMPRESA
function cadastrar_empresa($nome, $endereco, $telefone = '', $nome_contato = ''){
    // Verifica se a empresa já existe
    $stmt_check = $this->connect->prepare("
        SELECT * 
        FROM concedentes 
        WHERE nome = :nome
    ");

    $stmt_check->bindValue(':nome', $nome);
    $stmt_check->execute();

    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (empty($result)) {

        try {

            $stmt = $this->connect->prepare("
                INSERT INTO concedentes
                (nome, nome_contato, contato, endereco)
                VALUES
                (:nome, :nome_contato, :contato, :endereco)
            ");

            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':nome_contato', $nome_contato);
            $stmt->bindValue(':contato', $telefone);
            $stmt->bindValue(':endereco', $endereco);

            $stmt->execute();

            return 1;

        } catch (Exception $e) {

            // DEBUG
            echo $e->getMessage();
            exit();

            return 2;
        }

    } else {

        return 3;
    }
}







    
    
    
    
    
    
function cadastrar_vaga($id_empresa, $id_area, $quant_vagas, $tipo_vaga){
    // Verifica se a vaga já existe
    $stmt_check = $this->connect->prepare("SELECT * FROM vagas WHERE id_concedente = :id_concedente AND id_perfil = :id_perfil AND tipo_vaga = :tipo_vaga");

    $stmt_check->bindValue(':id_concedente', $id_empresa);
    $stmt_check->bindValue(':id_perfil', $id_area);
    $stmt_check->bindValue(':tipo_vaga', $tipo_vaga);

    $stmt_check->execute();

    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (empty($result)) {

        try {

            $stmt = $this->connect->prepare("
                INSERT INTO vagas
                (id_concedente,id_perfil,quant_vaga,tipo_vaga,quant_cand,data,hora)
                VALUES (:id_concedente,:id_perfil,:quant_vagas,:tipo_vaga,null,null,null)");

            $stmt->bindValue(':id_concedente', $id_empresa);
            $stmt->bindValue(':id_perfil', $id_area);
            $stmt->bindValue(':quant_vagas', $quant_vagas);
            $stmt->bindValue(':tipo_vaga', $tipo_vaga);
            $stmt->execute();
            return $this->connect->lastInsertId();

        } catch (Exception $e) {
            // DEBUG
            echo $e->getMessage();
            exit();
            return 2;
        }

    } else {
        return 3;
    }
}









                    function cadastrar_entrevista(int $id_vaga, int $quant_candidatos, string $data, string $hora){
                        $stmt = $this->connect->prepare("UPDATE vagas SET quant_candidatos = :quant_candidatos,data_entrevista = :data, hora_entrevista = :hora WHERE id = :id_vaga");
                    
                        $stmt->bindValue(':id_vaga', $id_vaga);
                        $stmt->bindValue(':quant_candidatos', $quant_candidatos);
                        $stmt->bindValue(':data', $data);
                        $stmt->bindValue(':hora', $hora);
                        $stmt->execute();
                    
                        if ($stmt->rowCount() > 0) {
                            return 1;
                        } else {
                            return 2;
                        }
                    }


    /**
     * Vincula alunos a uma vaga na tabela `selecao`.
     *
     * Alunos ja vinculados a mesma vaga sao ignorados para evitar duplicidade.
     */
    function selecao(array $alunos, int $id_vaga)
    {
        foreach ($alunos as $aluno_id) {
            $aluno_id = (int) $aluno_id;
            if ($aluno_id <= 0) {
                continue;
            }

            $stmt = $this->connect->prepare("SELECT 1 FROM selecao WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
            $stmt->execute([
                ':id_aluno' => $aluno_id,
                ':id_vaga' => $id_vaga
            ]);

            if ($stmt->rowCount() > 0) {
                $update = $this->connect->prepare("UPDATE selecao SET Status = '0' WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
                $update->execute([
                    ':id_aluno' => $aluno_id,
                    ':id_vaga' => $id_vaga
                ]);
                continue;
            }

            $stmt = $this->connect->prepare("INSERT INTO selecao (id_aluno, id_vaga, Status) VALUES (:id_aluno, :id_vaga, '0')");
            $stmt->execute([
                ':id_aluno' => $aluno_id,
                ':id_vaga' => $id_vaga
            ]);
        }
        return 1;
    }

    public function atualizar_selecao_por_vaga(int $id_vaga, array $adicionar = [], array $remover = []): int
    {
        $idsAdicionar = [];
        foreach ($adicionar as $idAluno) {
            $idAluno = (int) $idAluno;
            if ($idAluno > 0) {
                $idsAdicionar[$idAluno] = true;
            }
        }

        $idsRemover = [];
        foreach ($remover as $idAluno) {
            $idAluno = (int) $idAluno;
            if ($idAluno > 0) {
                $idsRemover[$idAluno] = true;
            }
        }

        foreach ($idsRemover as $idAluno => $valor) {
            unset($idsAdicionar[$idAluno]);

            $deleteSelecionado = $this->connect->prepare("DELETE FROM selecionado WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
            $deleteSelecionado->execute([
                ':id_aluno' => $idAluno,
                ':id_vaga' => $id_vaga,
            ]);

            $deleteSelecao = $this->connect->prepare("DELETE FROM selecao WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
            $deleteSelecao->execute([
                ':id_aluno' => $idAluno,
                ':id_vaga' => $id_vaga,
            ]);
        }

        foreach ($idsAdicionar as $idAluno => $valor) {
            $stmt = $this->connect->prepare("SELECT 1 FROM selecao WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
            $stmt->execute([
                ':id_aluno' => $idAluno,
                ':id_vaga' => $id_vaga,
            ]);

            if ($stmt->fetchColumn()) {
                $update = $this->connect->prepare("UPDATE selecao SET Status = '0' WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
                $update->execute([
                    ':id_aluno' => $idAluno,
                    ':id_vaga' => $id_vaga,
                ]);
                continue;
            }

            $insert = $this->connect->prepare("INSERT INTO selecao (id_aluno, id_vaga, Status) VALUES (:id_aluno, :id_vaga, '0')");
            $insert->execute([
                ':id_aluno' => $idAluno,
                ':id_vaga' => $id_vaga,
            ]);
        }

        $contagem = $this->connect->prepare("SELECT COUNT(*) FROM selecao WHERE id_vaga = :id_vaga");
        $contagem->execute([':id_vaga' => $id_vaga]);

        if ((int) $contagem->fetchColumn() === 0) {
            $this->connect->prepare("DELETE FROM selecionado WHERE id_vaga = :id_vaga")->execute([':id_vaga' => $id_vaga]);
        }

        return 1;
    }

    public function finalizar_selecao_por_vaga(int $id_vaga, array $aprovados = []): int
    {
        $idsAprovados = [];
        foreach ($aprovados as $idAluno) {
            $idAluno = (int) $idAluno;
            if ($idAluno > 0) {
                $idsAprovados[$idAluno] = true;
            }
        }

        if (empty($idsAprovados)) {
            return 2;
        }

        try {
            $this->connect->beginTransaction();

            $placeholders = [];
            $params = [':id_vaga' => $id_vaga];
            $index = 0;
            foreach (array_keys($idsAprovados) as $idAluno) {
                $index++;
                $placeholders[] = ':id_aluno_' . $index;
                $params[':id_aluno_' . $index] = (int) $idAluno;
            }

            if (!empty($placeholders)) {
                $sqlNaoSelecionados = "DELETE FROM selecao WHERE id_vaga = :id_vaga AND id_aluno NOT IN (" . implode(',', $placeholders) . ")";
                $stmtNaoSelecionados = $this->connect->prepare($sqlNaoSelecionados);
                foreach ($params as $key => $value) {
                    $stmtNaoSelecionados->bindValue($key, $value);
                }
                $stmtNaoSelecionados->execute();

                $sqlNaoSelecionadosSelecionado = "DELETE FROM selecionado WHERE id_vaga = :id_vaga AND id_aluno NOT IN (" . implode(',', $placeholders) . ")";
                $stmtNaoSelecionadosSelecionado = $this->connect->prepare($sqlNaoSelecionadosSelecionado);
                foreach ($params as $key => $value) {
                    $stmtNaoSelecionadosSelecionado->bindValue($key, $value);
                }
                $stmtNaoSelecionadosSelecionado->execute();
            } else {
                $this->connect->prepare("DELETE FROM selecao WHERE id_vaga = :id_vaga")->execute([':id_vaga' => $id_vaga]);
                $this->connect->prepare("DELETE FROM selecionado WHERE id_vaga = :id_vaga")->execute([':id_vaga' => $id_vaga]);
                $this->connect->rollBack();
                return 2;
            }

            foreach (array_keys($idsAprovados) as $idAluno) {
                $stmtExisteSelecao = $this->connect->prepare("SELECT 1 FROM selecao WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga LIMIT 1");
                $stmtExisteSelecao->execute([
                    ':id_aluno' => $idAluno,
                    ':id_vaga' => $id_vaga,
                ]);

                if ($stmtExisteSelecao->fetchColumn()) {
                    $updateSelecao = $this->connect->prepare("UPDATE selecao SET Status = '1' WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga");
                    $updateSelecao->execute([
                        ':id_aluno' => $idAluno,
                        ':id_vaga' => $id_vaga,
                    ]);
                } else {
                    $insertSelecao = $this->connect->prepare("INSERT INTO selecao (id_aluno, id_vaga, Status) VALUES (:id_aluno, :id_vaga, '1')");
                    $insertSelecao->execute([
                        ':id_aluno' => $idAluno,
                        ':id_vaga' => $id_vaga,
                    ]);
                }

                $stmtExisteSelecionado = $this->connect->prepare("SELECT 1 FROM selecionado WHERE id_aluno = :id_aluno AND id_vaga = :id_vaga LIMIT 1");
                $stmtExisteSelecionado->execute([
                    ':id_aluno' => $idAluno,
                    ':id_vaga' => $id_vaga,
                ]);

                if (!$stmtExisteSelecionado->fetchColumn()) {
                    $stmtAluno = $this->connect->prepare("SELECT nome FROM aluno WHERE id = :id_aluno LIMIT 1");
                    $stmtAluno->execute([':id_aluno' => $idAluno]);
                    $aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);
                    $nomeAluno = $aluno['nome'] ?? '';

                    $insertSelecionado = $this->connect->prepare("INSERT INTO selecionado (id_aluno, id_vaga, nome) VALUES (:id_aluno, :id_vaga, :nome)");
                    $insertSelecionado->execute([
                        ':id_aluno' => $idAluno,
                        ':id_vaga' => $id_vaga,
                        ':nome' => $nomeAluno,
                    ]);
                }
            }

            $deletePendentes = $this->connect->prepare("DELETE FROM selecao WHERE id_vaga = :id_vaga AND Status != '1'");
            $deletePendentes->execute([':id_vaga' => $id_vaga]);

            $this->connect->commit();
            return 1;
        } catch (PDOException $e) {
            if ($this->connect->inTransaction()) {
                $this->connect->rollBack();
            }
            return 2;
        }
    }

    public function remover_aluno_da_vaga(int $id_vaga, int $id_aluno): int
    {
        try {
            $deleteSelecionado = $this->connect->prepare("DELETE FROM selecionado WHERE id_vaga = :id_vaga AND id_aluno = :id_aluno");
            $deleteSelecionado->execute([
                ':id_vaga' => $id_vaga,
                ':id_aluno' => $id_aluno,
            ]);

            $deleteSelecao = $this->connect->prepare("DELETE FROM selecao WHERE id_vaga = :id_vaga AND id_aluno = :id_aluno");
            $deleteSelecao->execute([
                ':id_vaga' => $id_vaga,
                ':id_aluno' => $id_aluno,
            ]);

            return 1;
        } catch (PDOException $e) {
            return 2;
        }
    }

    function editar_aluno(int $id, string $nome, string $contato, float $medias, string $email, string $projetos, string $perfil_opc1, string $perfil_opc2, string $localidade, int $ocorrencia, int $custeio, string $entregas_individuais, string $entregas_grupo)
    {
        $stmt = $this->connect->prepare("UPDATE `aluno` SET `nome`=:nome, `contato`=:contato, `medias`=:medias, `email`=:email, `projetos`=:projetos, `perfil_opc1`=:opc1, `perfil_opc2`=:opc2, `localidade`=:localidade, `ocorrencia`=:ocorrencia, `custeio`=:custeio, `entregas_individuais`=:entrega_individuais, `entregas_grupo`=:entrega_grupo WHERE id = :id");

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':contato', $contato);
        $stmt->bindValue(':medias', $medias);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':projetos', $projetos);
        $stmt->bindValue(':opc1', $perfil_opc1);
        $stmt->bindValue(':opc2', $perfil_opc2);
        $stmt->bindValue(':localidade', $localidade);
        $stmt->bindValue(':ocorrencia', $ocorrencia);
        $stmt->bindValue(':custeio', $custeio);
        $stmt->bindValue(':entrega_individuais', $entregas_individuais);
        $stmt->bindValue(':entrega_grupo', $entregas_grupo);
        $stmt->bindValue(':id', $id);

        if ($stmt->execute()) {
            return 1;
        } else {
            return 2;
        }
    }
    function editar_vaga(int $id, $empresa, $perfil, $quant_vaga, $quant_cand, $tipo_vaga, $data, $hora)
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
        try {
            $this->connect->beginTransaction();

            $stmt = $this->connect->prepare("DELETE FROM selecionado WHERE id_vaga = :id_vaga");
            $stmt->execute([':id_vaga' => $id_vaga]);

            $stmt = $this->connect->prepare("DELETE FROM selecao WHERE id_vaga = :id_vaga");
            $stmt->execute([':id_vaga' => $id_vaga]);

            $stmt = $this->connect->prepare("DELETE FROM vagas WHERE id = :id_vaga");
            $stmt->execute([':id_vaga' => $id_vaga]);

            $this->connect->commit();
            return 1;
        } catch (PDOException $e) {
            if ($this->connect->inTransaction()) {
                $this->connect->rollBack();
            }
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

    function excluir_ficha_concedente($id_ficha)
    {
        try {
            $this->connect->beginTransaction();

            $stmt = $this->connect->prepare("DELETE FROM ficha_vagas_concedente WHERE concedente_id = :id_ficha");
            $stmt->execute([':id_ficha' => $id_ficha]);

            $stmt = $this->connect->prepare("DELETE FROM ficha_concedente WHERE id = :id_ficha");
            $stmt->execute([':id_ficha' => $id_ficha]);

            $this->connect->commit();
            return $stmt->rowCount() > 0 ? 1 : 3;
        } catch (PDOException $e) {
            if ($this->connect->inTransaction()) {
                $this->connect->rollBack();
            }
            return 2;
        }
    }

    function criar_aluno($nome, $contato, $medias, $email, $projetos, $perfil_opc1, $perfil_opc2, $localidade, $ocorrencia, $custeio, $entregas_individuais, $entregas_grupo)
    {
        $stmt_check = $this->connect->prepare("SELECT * FROM aluno WHERE nome = :nome");
        $stmt_check->bindValue(':nome', $nome);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {


            $stmt = $this->connect->prepare("INSERT INTO `aluno` (`nome`, `contato`, `medias`, `email`, `projetos`, `perfil_opc1`, `perfil_opc2`, `localidade`, `ocorrencia`, `custeio`, `entregas_individuais`, `entregas_grupo`) VALUES (:nome, :contato, :medias, :email, :projetos, :perfil_opc1, :perfil_opc2, :localidade, :ocorrencia, :custeio, :entrega_individuais, :entrega_grupo)");
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':contato', $contato);
            $stmt->bindValue(':medias', $medias);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':projetos', $projetos);
            $stmt->bindValue(':perfil_opc1', $perfil_opc1);
            $stmt->bindValue(':perfil_opc2', $perfil_opc2);
            $stmt->bindValue(':localidade', $localidade);
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

    function excluir_aluno(int $id_aluno): int {
        try {

            $stmt_selecao = $this->connect->prepare("
                DELETE FROM selecao
                WHERE id_aluno = :id
            ");

            $stmt_selecao->bindValue(':id', $id_aluno);
            $stmt_selecao->execute();

            $stmt_selecionado = $this->connect->prepare("
                DELETE FROM selecionado
                WHERE id_aluno = :id
            ");

            $stmt_selecionado->bindValue(':id', $id_aluno);
            $stmt_selecionado->execute();

            $stmt = $this->connect->prepare("
                DELETE FROM aluno
                WHERE id = :id
            ");

            $stmt->bindValue(':id', $id_aluno);

            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }

        } catch (PDOException $e) {

            echo $e->getMessage();
            exit();

            return 3;
        }
    }
}
