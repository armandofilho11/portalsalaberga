<?php
require_once(dirname(__DIR__) . "/fpdf186/fpdf.php");
require_once(__DIR__ . '/../config/connect.php');
class aluno extends connect
{

    public function __construct()
    {
        parent::__construct();
    }

    public function validar_email_e_senha_aluno($matricula)
    {
        $sql = 'select * from aluno WHERE matricula = :matricula';
        $prep = $this->conexao->prepare($sql);
        $prep->bindParam(':matricula', $matricula);
        $prep->execute();

        return $prep->fetch() ? true : false;
    }

    public function aluno_existe($matricula)
    {
        $sql = "select * from aluno where matricula = :matricula";
        $prep = $this->conexao->prepare($sql);
        $prep->bindvalue(":matricula", $matricula);
        $prep->execute();
        $resultado = $prep->fetchall(PDO::FETCH_ASSOC);
        return $resultado;
    }

    public function nome_aluno($matricula)
    {
        $sql = "select nome from aluno where matricula = :matricula";
        $prep = $this->conexao->prepare($sql);
        $prep->bindparam(":matricula", $matricula);
        $prep->execute();
        $resultado = $prep->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['nome'] : '';
    }

    public function ano_aluno($matricula)
    {
        try {
            // Buscar ano do aluno
            $sql = "SELECT ano FROM aluno WHERE matricula = :matricula";
            $prep = $this->conexao->prepare($sql);
            $prep->bindValue(":matricula", $matricula);
            $prep->execute();
            $ano = $prep->fetch(PDO::FETCH_ASSOC);

            if (!$ano) {
                echo "Matrícula não encontrada.";
                return;
            }

            // Pegar avaliações do mesmo ano
            $sql = "select * FROM avaliacao WHERE ano = :ano";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":ano", $ano['ano']);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function exibir_aval_pdf($id_avaliacao)
    {
        $sql = 'select * from questao_prova where id_avaliacao = :id_avaliacao ';
        $prep = $this->conexao->prepare($sql);
        $prep->bindvalue(":id_avaliacao", $id_avaliacao);
        $prep->execute();
        $resultado = $prep->fetchall(PDO::FETCH_ASSOC);
        $todas_questoes = []; // array para armazenar todas as questões

        foreach ($resultado as $key) {
            $id = $key['id'];
            $id_avaliacao = $key['id_avaliacao'];
            $id_questoes = str_split($key['id_questao']); // separa os caracteres

            // Junta ao array principal
            $todas_questoes = array_merge($todas_questoes, $id_questoes);
        }

        $sql = 'select * from questao where id = :id_avaliacao ';
        $prep = $this->conexao->prepare($sql);
        $prep->bindvalue(":id_avaliacao", $id_avaliacao);
        $prep->execute();

        // $pdf = new FPDF();
        // $pdf->AddPage();
        // $pdf->SetFont('Arial','B',16);
        // $pdf->Cell(40,10,'Hello World!');
        // $pdf->Output();
    }
}
