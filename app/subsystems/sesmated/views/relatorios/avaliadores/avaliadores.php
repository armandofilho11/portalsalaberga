<?php
require_once('../../../assets/fpdf/fpdf.php');
require_once('../../../config/connect.php');
require_once('../../../../../main/models/sessions.php');
$session = new sessions();
$session->autenticar_session();
class PDF extends connect
{
    public function __construct()
    {
        parent::__construct();
        $this->main();
    }

    public function main()
    {
        $fpdf = new FPDF('P', 'pt', 'A4');
        $fpdf->AliasNbPages();
        $fpdf->AddPage('P'); // Orientação vertical

        // Imagem de fundo
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Cabeçalho da tabela
        $fpdf->SetY($fpdf->GetY() + 125); // Pequeno espaço após o texto
        $fpdf->SetX(90);
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->SetFillColor(240, 240, 240); // Cinza claro para o cabeçalho
        $fpdf->SetTextColor(0, 0, 0); // Preto para o texto do cabeçalho
        $fpdf->Cell(250, 20, utf8_decode('Nome'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Data'), 1, 0, 'C', true);
        $fpdf->Cell(100, 20, utf8_decode('Turno'), 1, 1, 'C', true);

        // Espaço entre cabeçalho e dados
        $fpdf->Ln(2);

        // Dados
        $fpdf->SetFont('Arial', '', 11);
        $fpdf->SetDrawColor(0, 179, 72); // Verde para bordas
        $fill = false;

        $result = $this->connect->prepare("SELECT * FROM avaliadores");
        $result->execute();
        $dados = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dados as $dado) {
            $fpdf->SetX(90);

            $fpdf->SetFillColor(255, 255, 255); // Branco para fundo
            $fpdf->SetTextColor(0, 0, 0); // Preto para texto em fundo branco

            $fpdf->Cell(250, 18, utf8_decode($dado['nome']), 1, 0, 'C', true);
            $fpdf->Cell(100, 18, utf8_decode($dado['data']), 1, 0, 'C', true);
            $fpdf->Cell(100, 18, utf8_decode($dado['turno']), 1, 0, 'C', true);
        }
        $fpdf->Output('relatorio_avaliadores.pdf', 'I');
    }
}

$relatorio = new PDF();
