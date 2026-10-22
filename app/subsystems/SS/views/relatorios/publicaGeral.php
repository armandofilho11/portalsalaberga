<?php
function publicaGeral($curso)
{
    require_once('../config/connect.php');

    session_start();

    if ((isset($_SESSION['status']) && $_SESSION['status'] == 1)){
        //ADMIN
        $n = 102;
        $p = 0; // ñ quebra a linha caso seja cliente 
        $orientacao = 'L';
    } else if ((isset($_SESSION['status']) && $_SESSION['status'] == 0)){
        //CLIENTE
        $n = 105;
        $p = 1; // quebra a linha caso seja cliente 
        $orientacao = 'P';
    }

    if (isset($_SESSION['status']) && $_SESSION['status'] == 1){
    $stmtSelect = $conexao->prepare("
    SELECT candidato.id_candidato, candidato.id_cadastrador, usuario.nome_user, candidato.nome, 
    candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd, nota.media
    FROM candidato 
    INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
    INNER JOIN usuario ON candidato.id_cadastrador = usuario.id
    WHERE candidato.id_curso1_fk = :curso
    AND candidato.publica = 1
    ORDER BY nota.media DESC,
    candidato.data_nascimento DESC,
    nota.l_portuguesa DESC,
    nota.matematica DESC;


");
    } else if (isset($_SESSION['status']) && $_SESSION['status'] == 0){
        $stmtSelect = $conexao->prepare("
    SELECT candidato.nome, candidato.id_curso1_fk, candidato.publica, candidato.bairro, candidato.pcd
    FROM candidato 
    INNER JOIN nota ON nota.candidato_id_candidato = candidato.id_candidato 
    WHERE candidato.id_curso1_fk = :curso
    AND candidato.publica = 1
    ORDER BY nome ASC
        ");
    }
    $stmtSelect->BindValue(':curso', $curso);
    $stmtSelect->execute();
    $result = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

    require_once('../assets/fpdf/fpdf.php');
    $pdf = new FPDF($orientacao, 'mm', 'A4');
    $pdf->AddPage();

    // Cabeçalho com larguras ajustadas
    $pdf->Image('../assets/images/logo.png', 8, 8, 15, 0, 'PNG');
    $pdf->SetFont('Arial', 'B', 25);
    $pdf->Cell(107, 5, ('PUBLICA GERAL'), 0, 1, 'C');
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(188, 10, ('PCD = PESSOA COM DEFICIENCIA | COTISTA = INCLUSO NA COTA DO BAIRRO | AC = AMPLA CONCORRENCIA'), 0, 1, 'C');
    $pdf->SetFont('Arial', 'b', 12);
    $pdf->Cell(185, 10, '', 0, 1, 'C');

    // Fonte do cabeçalho
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(93, 164, 67); //fundo verde
    $pdf->SetTextColor(255, 255, 255);  //texto branco
    $pdf->Cell(10, 7, 'CH', 1, 0, 'C', true);
    $pdf->Cell($n, 7, 'Nome', 1, 0, 'C', true);
    $pdf->Cell(30, 7, 'Curso', 1, 0, 'C', true);
    $pdf->Cell(18, 7, 'Origem', 1, 0, 'C', true);
    if (isset($_SESSION['status']) && $_SESSION['status'] == 1) { 
        $pdf->Cell(26, 7, 'Segmento', 1, 0, 'C', true);
        $pdf->Cell(20, 7, 'Id Aluno', 1, 0, 'C', true);
        $pdf->Cell(15, 7, 'Media', 1, 0, 'C', true);
        $pdf->Cell(55, 7, 'Resp. Cadastro', 1, 1, 'C', true);
    } else {
        $pdf->Cell(26, 7, 'Segmento', 1, 1, 'C', true); 
    }
    // Resetar cor do texto para preto
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 8);

    // Dados com cores alternadas
    $classificacao = 001;

    foreach ($result as $row) {
        // Definir curso
        switch ($row['id_curso1_fk']) {
            case 1:
                $curso = ('ENFERMAGEM');
                break;
            case 2:
                $curso = ('INFORMATICA');
                break;
            case 3:
                $curso = ('ADMINISTRACAO');
                break;
            case 4:
                $curso = ('EDIFICACOES');
                break;
            default:
                $curso = ('Não definido');
                break;
        }

        // Definir escola
        $escola = ($row['publica'] == 1) ? ('PUBLICA') : ('PRIVADA');

        // Definir cota
        if ($row['pcd'] == 1) {
            $cota = 'PCD';
        } else if ($row['publica'] == 1 && $row['bairro'] == 1) {
            $cota = 'COTISTA';
        } else {
            $cota = 'AC';
        }

        // Definir cor da linha
        $cor = $classificacao % 2 ? 255 : 192;
        $pdf->SetFillColor($cor, $cor, $cor);

        // Imprimir linha no PDF
        $pdf->Cell(10, 7, sprintf("%03d", $classificacao), 1, 0, 'C', true);
        $pdf->Cell($n, 7, strToUpper(utf8_decode($row['nome'])), 1, 0, 'L', true);
        $pdf->Cell(30, 7, $curso, 1, 0, 'L', true);
        $pdf->Cell(18, 7, $escola, 1, 0, 'L', true);
        $pdf->Cell(26, 7, $cota, 1, $p, 'L', true); // verificar parâmetro 'p' na parte superior do relatório
        if (isset($_SESSION['status']) && $_SESSION['status'] == 1) {
            $pdf->Cell(20, 7, $row['id_candidato'], 1, 0, 'C', true);
            $pdf->Cell(15, 7, number_format($row['media'], 2), 1, 0, 'C', true);
            $pdf->Cell(55, 7, strtoupper(utf8_decode($row['nome_user'])), 1, 1, 'L', true);
        }
        $classificacao++;
    }

    $pdf->Output('classificacao.pdf', 'I');
}

publicaGeral($curso);
