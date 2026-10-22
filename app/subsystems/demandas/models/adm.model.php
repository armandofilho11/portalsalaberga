<?php
require_once('usuario.model.php');
class adm_model extends usuario_model
{
    private $tabela1;
    private $tabela2;
    private $tabela3;
    function __construct()
    {
        parent::__construct();
        $this->tabela1 = 'areas';
        $this->tabela2 = 'demandas';
        $this->tabela3 = 'demanda_usuarios';
    }

    public function cadastrar_demanda($titulo, $descricao, $prioridade, $id_admin, $prazo)
    {
        $stmt_cadastrar = $this->connect_demandas->prepare("SELECT * FROM $this->tabela2 WHERE titulo = :titulo");
        $stmt_cadastrar->bindValue(':titulo', $titulo);
        $stmt_cadastrar->execute();
        $result = $stmt_cadastrar->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            $stmt_cadastrar = $this->connect_demandas->prepare("INSERT INTO $this->tabela2(`titulo`, `descricao`, `prioridade`, `admin_id`, `prazo`) VALUES (:titulo, :descricao, :prioridade, :id_admin, :prazo)");
            $stmt_cadastrar->bindValue(':titulo', $titulo);
            $stmt_cadastrar->bindValue(':descricao', $descricao);
            $stmt_cadastrar->bindValue(':prioridade', $prioridade);
            $stmt_cadastrar->bindValue(':id_admin', $id_admin);
            $stmt_cadastrar->bindValue(':prazo', $prazo);

            if ($stmt_cadastrar->execute()) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    }
    public function editar_demanda($id_demanda, $titulo, $descricao, $prioridade, $status, $prazo)
    {
        $stmt_cadastrar = $this->connect_demandas->prepare("SELECT * FROM $this->tabela2 WHERE titulo = :titulo AND id != :id_demanda");
        $stmt_cadastrar->bindValue(':titulo', $titulo);
        $stmt_cadastrar->bindValue(':id_demanda', $id_demanda);
        $stmt_cadastrar->execute();
        $result = $stmt_cadastrar->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            if ($status = 'pendente'){

            }else if($status !== 'concluida') {
                $stmt_excluir_usuario = $this->connect_demandas->prepare("DELETE $this");
                $stmt_cadastrar = $this->connect_demandas->prepare("UPDATE `demandas` SET `titulo`= :titulo, `descricao`= :descricao, `prioridade`= :prioridade, `status`= :status, `data_conclusao`= NULL, `prazo`= :prazo WHERE id = :id_demanda");
                $stmt_cadastrar->bindValue(':titulo', $titulo);
                $stmt_cadastrar->bindValue(':descricao', $descricao);
                $stmt_cadastrar->bindValue(':prioridade', $prioridade);
                $stmt_cadastrar->bindValue(':status', $status);
                $stmt_cadastrar->bindValue(':prazo', $prazo);
                $stmt_cadastrar->bindValue(':id_demanda', $id_demanda);

                if ($stmt_cadastrar->execute()) {
                    return 1;
                } else {
                    return 2;
                }
            } else {
                $date = date('Y-m-d H:i:s');
                $stmt_cadastrar = $this->connect_demandas->prepare("UPDATE `demandas` SET `titulo`= :titulo, `descricao`= :descricao, `prioridade`= :prioridade, `status`= :status, `data_conclusao`= :data_conclusao, `prazo`= :prazo WHERE id = :id_demanda");
                $stmt_cadastrar->bindValue(':titulo', $titulo);
                $stmt_cadastrar->bindValue(':descricao', $descricao);
                $stmt_cadastrar->bindValue(':status', $status);
                $stmt_cadastrar->bindValue(':prioridade', $prioridade);
                $stmt_cadastrar->bindValue(':data_conclusao', $date);
                $stmt_cadastrar->bindValue(':prazo', $prazo);
                $stmt_cadastrar->bindValue(':id_demanda', $id_demanda);

                if ($stmt_cadastrar->execute()) {
                    return 1;
                } else {
                    return 2;
                }
            }
        } else {
            return 3;
        }
    }

    public function excluir_demanda($id_demanda)
    {
        $stmt_excluir_relacionamentos = $this->connect_demandas->prepare("DELETE FROM $this->tabela3 WHERE id_demanda = :id_demanda");
        $stmt_excluir_relacionamentos->bindValue(':id_demanda', $id_demanda);
        $stmt_excluir_relacionamentos->execute();

        $stmt_excluir = $this->connect_demandas->prepare("DELETE FROM $this->tabela2 WHERE id = :id_demanda");
        $stmt_excluir->bindValue(':id_demanda', $id_demanda);

        if ($stmt_excluir->execute()) {
            return 1; 
        } else {
            return 2;
        }
    }
}
