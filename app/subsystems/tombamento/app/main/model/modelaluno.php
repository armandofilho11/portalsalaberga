<?php
require_once(dirname(__DIR__) . "/fpdf186/fpdf.php");
class aluno{
    public function validar_email_e_senha_aluno($matricula){
            $conexao = new PDO('mysql:host=localhost;dbname=banco_de_questoes', 'root', '');
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'select * from aluno WHERE matricula = :matricula';
            $prep = $conexao->prepare($sql);
            $prep->bindParam(':matricula', $matricula);
            $prep->execute();

            return $prep->fetch() ? true : false;
        }

    public function aluno_existe($matricula){

        $conexao = new PDO('mysql:host=localhost;dbname=banco_de_questoes', 'root', '');
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "select * from aluno where matricula = :matricula";
        $prep = $conexao -> prepare($sql);
        $prep -> bindvalue("matricula",$matricula);
        $prep -> execute();
        $resultado = $prep->fetchall(PDO::FETCH_ASSOC);
        return $resultado;
    }
    public function nome_aluno($matricula){
        $conexao = new PDO('mysql:host=localhost;dbname=banco_de_questoes', 'root', '');
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "select nome from aluno where matricula = :matricula";
        $prep = $conexao -> prepare($sql);
        $prep -> bindparam(":matricula", $matricula);
        $prep -> execute();
        $resultado = $prep->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['nome'] : '';
    }
   public function ano_aluno($matricula) {
    try {
        $conexao = new PDO('mysql:host=localhost;dbname=banco_de_questoes', 'root', '');
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Buscar ano do aluno
        $sql = "SELECT ano FROM aluno WHERE matricula = :matricula";
        $prep = $conexao->prepare($sql);
        $prep->bindValue(":matricula", $matricula);
        $prep->execute();
        $ano = $prep->fetch(PDO::FETCH_ASSOC);

        if (!$ano) {
            echo "Matrícula não encontrada.";
            return;
        }

        // Pegar avaliações do mesmo ano
        $sql = "select * FROM avaliacao WHERE ano = :ano";
        $stmt = $conexao->prepare($sql);
        $stmt->bindValue(":ano", $ano['ano']);  // Correção aqui
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Correção aqui
        return $resultado;
        

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
    
    public function exibir_aval_pdf($id_avaliacao){
        $conexao = new PDO('mysql:host=localhost;dbname=banco_de_questoes', 'root', '');
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'select * from questao_prova where id_avaliacao = :id_avaliacao ';
        $prep = $conexao -> prepare($sql);
        $prep -> bindvalue(":id_avaliacao",$id_avaliacao);
        $prep -> execute();
        $resultado = $prep->fetchall(PDO::FETCH_ASSOC);
        $todas_questoes = []; // array para armazenar todas as questões

        foreach ($resultado as $key) {
            $id = $key['id'];
            $id_avaliacao = $key['id_avaliacao'];
            $id_questoes = str_split($key['id_questao']); // separa os caracteres

            // Junta ao array principal
            $todas_questoes = array_merge($todas_questoes, $id_questoes);
        }

   
        // print_r($todas_questoes);
        // resultado:  Array ( [0] => 1 [1] => 3 [2] => 4 [3] => 5 [4] => 6 [5] => 7 )
        // até este ponto é retornado todos os dados da tabela questao_prova
        
        

        $sql = 'select * from questao where id = :id_avaliacao ';   
        $prep = $conexao -> prepare($sql);
        $prep -> bindvalue(":id_avaliacao",$id_avaliacao);
        $prep -> execute();




      
        // $pdf = new FPDF();
        // $pdf->AddPage();
        // $pdf->SetFont('Arial','B',16);
        // $pdf->Cell(40,10,'Hello World!');
        // $pdf->Output();

    }

}

    ?>