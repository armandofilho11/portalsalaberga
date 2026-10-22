<?php
require("../model/modelprofessor.php");
require("../fpdf186/fpdf.php");

if($_SERVER['REQUEST_METHOD'] === "POST"){

    $tipo = $_POST["tipoRelatorio"];
    $turma = $_POST['turma'];
    $disciplina = $_POST['disciplina'];
    $querpdf = $_POST['gerarpdf'];



    if($tipo == "individual"){
        // ----------- INDIVIDUAL -----------
        $x = new Professor();
        $resultado = $x -> gerar_relatorio_individual($turma,$disciplina);
        
        if ($querpdf == 'sim') {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, utf8_decode("Relatório Individual de Desempenho"), 0, 1, 'C');
            $pdf->Ln(10);

            $pdf->SetFont('Arial', '', 12);
            if (!empty($resultado)) {
                foreach ($resultado as $key) {
                    $id_aluno = $key['id_aluno'];
                    $disciplina = utf8_decode($key['disciplina']);
                    $acertos = $key['acertos'];
                    $turma = utf8_decode($key['turma']);
                    $id_prova = $key['id_prova'];

                    // Adiciona dados ao PDF
                    $pdf->Cell(0, 10, "ID Aluno: $id_aluno", 0, 1);
                    $pdf->Cell(0, 10, "Disciplina: $disciplina", 0, 1);
                    $pdf->Cell(0, 10, "Turma: $turma", 0, 1);
                    $pdf->Cell(0, 10, "Prova: $id_prova", 0, 1);
                    $pdf->Cell(0, 10, "Acertos: $acertos", 0, 1);
                    $pdf->Ln(5);

                    

                }
            } else {
                $pdf->Cell(0, 10, "Nenhum dado encontrado para o relatório.", 0, 1);
            }

            $pdf->Output("I", "relatorio_individual.pdf"); // "I" para exibir no navegador

            exit; // Garante que nada mais será enviado

        }else{
            echo "ainda em construção🛠️";
        }

    }else{
        // ----------- COLETIVO -----------

        echo "relatorio individual (ainda em construção🛠️)";
    }

}

// <?php
// require("../model/modelprofessor.php");
// require("../fpdf186/fpdf.php");

// if ($_SERVER['REQUEST_METHOD'] === "POST") {

//     $tipo = $_POST["tipoRelatorio"];
//     $turma = $_POST['turma'];
//     $disciplina = $_POST['disciplina'];
//     $querpdf = $_POST['gerarPdf']; // Corrigido: campo do seu form é "gerarPdf"
//     $aluno = $_POST['aluno'] ?? null;

//     if ($tipo === "individual") {
//         $x = new Professor();
//         $resultado = $x->gerar_relatorio_individual($turma, $disciplina);

//         if ($querpdf === 'sim') {

//             // Criação do PDF
//             $pdf = new FPDF();
//             $pdf->AddPage();
//             $pdf->SetFont('Arial', 'B', 16);
//             $pdf->Cell(0, 10, utf8_decode("Relatório Individual de Desempenho"), 0, 1, 'C');
//             $pdf->Ln(10);

//             $pdf->SetFont('Arial', '', 12);

//             if (!empty($resultado)) {
//                 foreach ($resultado as $key) {
//                     $id_aluno = $key['id_aluno'];
//                     $disciplina = utf8_decode($key['disciplina']);
//                     $acertos = $key['acertos'];
//                     $turma = utf8_decode($key['turma']);
//                     $id_prova = $key['id_prova'];

//                     // Adiciona dados ao PDF
//                     $pdf->Cell(0, 10, "ID Aluno: $id_aluno", 0, 1);
//                     $pdf->Cell(0, 10, "Disciplina: $disciplina", 0, 1);
//                     $pdf->Cell(0, 10, "Turma: $turma", 0, 1);
//                     $pdf->Cell(0, 10, "Prova: $id_prova", 0, 1);
//                     $pdf->Cell(0, 10, "Acertos: $acertos", 0, 1);
//                     $pdf->Ln(5);
//                 }
//             } else {
//                 $pdf->Cell(0, 10, "Nenhum dado encontrado para o relatório.", 0, 1);
//             }

//             $pdf->Output("I", "relatorio_individual.pdf"); // "I" para exibir no navegador

//             exit; // Garante que nada mais será enviado
//         } else {
//             echo "Relatório exibido na tela (modo não-PDF ainda em construção 🛠️)";
//         }

//     } else {
//         echo "Relatório coletivo (ainda em construção 🛠️)";
//     }
// }
// 


?>