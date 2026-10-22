<?php

class Concedente
{
    private $conn;
    private $table = "cadastro_de_concedentes";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function salvar($dados)
    {
        $sql = "INSERT INTO {$this->table}
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
            supervisor_estagio,
            email_supervisor,
            contato1,
            email_contato,
            endereco,
            numero,
            complemento,
            cep,
            bairro,
            municipio,
            observacoes,
            vagas_disponibilizadas,
            escola_beneficiada,
            municipio_escola,
            curso_atendido
        )
        VALUES
        (
            :instituicao,
            :rede,
            :razao_social,
            :nome_fantasia,
            :cnpj,
            :especificacao_atividade,
            :email_institucional,
            :telefone_institucional,
            :representante_legal,
            :email_representante,
            :cpf_representante,
            :rg_representante,
            :supervisor_estagio,
            :email_supervisor,
            :contato1,
            :email_contato,
            :endereco,
            :numero,
            :complemento,
            :cep,
            :bairro,
            :municipio,
            :observacoes,
            :vagas_disponibilizadas,
            :escola_beneficiada,
            :municipio_escola,
            :curso_atendido
        )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($dados);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id=:id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}