<?php
require_once '../../../../../app/main/assets/fpdf/fpdf.php';
if (!isset($_POST['json'])) die('Dados não recebidos.');
$data = json_decode($_POST['json'], true);
$projetos = $data['projetos'] ?? [];
$avaliacoes = $data['avaliacoes'] ?? [];
// Calcular médias e ranking
foreach ($projetos as &$projeto) {
    $projeto['avaliacoes'] = array_values(array_filter($avaliacoes, function($a) use ($projeto) {
        return $a['projetoId'] == $projeto['id'];
    }));
    $projeto['media'] = count($projeto['avaliacoes']) > 0 ?
        array_sum(array_column($projeto['avaliacoes'], 'total')) / count($projeto['avaliacoes']) : 0;
}
// Ranking
usort($projetos, function($a, $b) { return $b['media'] <=> $a['media']; });
$pontuacoes = [1000, 850, 700, 600, 500];
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('Relatório de Avaliação - Inovação'),0,1,'C');
$pdf->SetFont('Arial','',10);
foreach ($projetos as $idx => $projeto) {
    $pdf->Ln(2);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,8,utf8_decode(($idx+1).'º Lugar - '.($pontuacoes[$idx]??0).' pontos'),0,1);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,7,utf8_decode($projeto['nome']),0,1);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,6,utf8_decode('Curso: '.$projeto['curso']),0,1);
    $pdf->Cell(0,6,utf8_decode('Equipe: '.$projeto['equipe']),0,1);
    $pdf->MultiCell(0,6,utf8_decode('Descrição: '.$projeto['descricao']));
    $pdf->MultiCell(0,6,utf8_decode('Problema: '.$projeto['problema']));
    $pdf->MultiCell(0,6,utf8_decode('Benefícios: '.$projeto['beneficios']));
    $pdf->MultiCell(0,6,utf8_decode('Recursos: '.$projeto['recursos']));
    $pdf->MultiCell(0,6,utf8_decode('Tecnologias: '.$projeto['tecnologias']));
    $pdf->Ln(1);
    if (count($projeto['avaliacoes']) > 0) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,6,utf8_decode('Avaliações dos Jurados:'),0,1);
        $pdf->SetFont('Arial','',9);
        foreach ($projeto['avaliacoes'] as $av) {
            $pdf->Cell(0,5,utf8_decode('Jurado: '.$av['jurado']),0,1);
            $pdf->Cell(0,5,utf8_decode('  Originalidade e Inovação: '.($av['originalidade']??'').'/100'),0,1);
            $pdf->Cell(0,5,utf8_decode('  Relevância e Aplicabilidade para a Comunidade: '.($av['relevancia']??'').'/100'),0,1);
            $pdf->Cell(0,5,utf8_decode('  Viabilidade Técnica: '.($av['viabilidade']??'').'/100'),0,1);
            $pdf->Cell(0,5,utf8_decode('  Sustentabilidade e Responsabilidade Socioambiental: '.($av['sustentabilidade']??'').'/100'),0,1);
            $pdf->Cell(0,5,utf8_decode('  Clareza e Organização da Apresentação: '.($av['clareza']??'').'/100'),0,1);
            $pdf->Cell(0,5,utf8_decode('  Total: '.$av['total'].'/500'),0,1);
            if (!empty($av['observacoes']))
                $pdf->MultiCell(0,5,utf8_decode('  Observações: '.$av['observacoes']));
            $pdf->Ln(1);
        }
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(0,6,utf8_decode('Média Final: '.number_format($projeto['media'],2).'/500'),0,1);
    } else {
        $pdf->Cell(0,6,utf8_decode('Não avaliado'),0,1);
    }
    $pdf->Ln(2);
    $pdf->Cell(0,0,'','T');
    $pdf->Ln(2);
}
$pdf->Output('I','relatorio_inovacao.pdf');
exit; 