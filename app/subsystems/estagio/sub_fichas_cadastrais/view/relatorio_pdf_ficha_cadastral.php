
<?php
require_once('../../config/connect.php');
require_once('../../assets/fpdf/fpdf.php');


$db = new connect();
$pdo = $db->getConnection();

$sql = "SELECT id, nome_fantasia, razao_social
        FROM ficha_concedente
        ORDER BY nome_fantasia";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$concedentes = $stmt->fetchAll(PDO::FETCH_ASSOC);




$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID inválido.");
}

$sql = "SELECT *
        FROM ficha_concedente
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

$concedente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$concedente) {
    die("Concedente não encontrada.");
}

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('RELATÓRIO DA CONCEDENTE'),0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','',11);

function linha($pdf,$titulo,$valor)
{
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(60,8,utf8_decode($titulo));

    $pdf->SetFont('Arial','',11);
    $pdf->MultiCell(0,8,utf8_decode($valor));
}

linha($pdf,"Instituição:",$concedente['instituicao']);
linha($pdf,"Rede:",$concedente['rede']);
linha($pdf,"Razão Social:",$concedente['razao_social']);
linha($pdf,"Nome Fantasia:",$concedente['nome_fantasia']);
linha($pdf,"CNPJ:",$concedente['cnpj']);
linha($pdf,"Atividade:",$concedente['especificacao_atividade']);

linha($pdf,"Email:",$concedente['email_institucional']);
linha($pdf,"Telefone:",$concedente['telefone_institucional']);

linha($pdf,"Representante:",$concedente['representante_legal']);
linha($pdf,"Email Representante:",$concedente['email_representante']);
linha($pdf,"CPF:",$concedente['cpf_representante']);
linha($pdf,"RG:",$concedente['rg_representante']);

$endereco =
$concedente['rua'] . ", " .
$concedente['numero'] . " - " .
$concedente['bairro'] . " - " .
$concedente['municipio'] . " - CEP: " .
$concedente['cep'];

linha($pdf,"Endereço:",$endereco);

linha($pdf,"Observações:",$concedente['observacoes']);

linha(
    $pdf,
    "Cadastro:",
    date('d/m/Y', strtotime($concedente['data_cadastro']))
);

$pdf->Output(
    'I',
    'Concedente_'.$concedente['nome_fantasia'].'.pdf'
);