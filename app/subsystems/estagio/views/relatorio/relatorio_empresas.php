<?php
require_once('../../models/select_model.php');
require_once('../../assets/fpdf/fpdf.php');

date_default_timezone_set('America/Sao_Paulo');

function formatar_telefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) == 11) {
        return sprintf('(%s) %s %s-%s',
            substr($telefone, 0, 2),
            substr($telefone, 2, 1),
            substr($telefone, 3, 4),
            substr($telefone, 7, 4)
        );
    } elseif (strlen($telefone) == 10) {
        return sprintf('(%s) %s-%s',
            substr($telefone, 0, 2),
            substr($telefone, 2, 4),
            substr($telefone, 6, 4)
        );
    }
    return $telefone;
}


function gerar_link_whatsapp($telefone) {
    $numero = preg_replace('/[^0-9]/', '', $telefone);

    // precisa ter 11 dígitos (DDD + 9 dígitos)
    if (strlen($numero) == 11) {
        $primeiroNumero = substr($numero, 2, 1);

        // celular no Brasil começa com 9
        if ($primeiroNumero == '9') {
            return 'https://wa.me/55' . $numero;
        }
    }

    return false; // não é WhatsApp válido
}




class ModernEmpresasPDF extends FPDF {
    private $cores = [
        'primaria' => [0, 122, 51],
        'secundaria' => [240, 240, 240],
        'destaque' => [0, 90, 40],
        'texto' => [70, 70, 70],
        'subtitulo' => [100, 100, 100]
    ];
    private $inicioX = 10;
    private $fimX = 288;

    function Header() {
        if ($this->PageNo() == 1) {
            $this->SetFillColor(248, 248, 248);
            $this->Rect(0, 0, $this->GetPageWidth(), 40, 'F');
            $this->Image('https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png', 15, 10, 25);
            $this->SetFont('Arial', 'B', 18);
            $this->SetTextColor(...$this->cores['primaria']);
            $this->SetXY(45, 15);
            $this->Cell(100, 10, utf8_decode('Relatório de Concedentes'), 0, 0, 'L');
            $this->SetFont('Arial', 'I', 9);
            $this->SetTextColor(...$this->cores['subtitulo']);
            $this->SetXY(45, 25);
            $this->Cell(100, 5, utf8_decode('Gerado em: ' . date('d/m/Y H:i')), 0, 0, 'L');
            $this->SetDrawColor(...$this->cores['primaria']);
            $this->SetLineWidth(0.7);
            $this->Line($this->inicioX, 40, $this->fimX, 40);
            $this->SetY(45);
        } else {
            $this->SetY(15);
        }
    }

    function Footer() {
        $this->SetDrawColor(...$this->cores['primaria']);
        $this->SetLineWidth(0.7);
        $this->Line($this->inicioX, $this->GetPageHeight() - 20, $this->fimX, $this->GetPageHeight() - 20);
        $this->SetY(-18);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(...$this->cores['subtitulo']);
        $this->Cell(0, 5, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('Sistema de Gestão de Empresas - Todos os direitos reservados'), 0, 0, 'C');
    }

    // Função para calcular linhas para MultiCell
    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i<$nb)
        {
            $c = $s[$i];
            if($c=="\n")
            {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep = $i;
            $l += $cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i = $sep+1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

function tabelaEmpresas($empresas) {

    // Cabeçalho
    $this->SetFont('Arial', 'B', 11);
    $this->SetFillColor(220, 240, 230);
    $this->SetTextColor(...$this->cores['primaria']);

    $this->Cell(60, 10, 'Nome', 1, 0, 'C', true);
    $this->Cell(50, 10, utf8_decode('Responsável'), 1, 0, 'C', true);
    $this->Cell(35, 10, 'Telefone', 1, 0, 'C', true);
    $this->Cell(133, 10, utf8_decode('Endereço'), 1, 1, 'C', true);

    $this->SetFont('Arial', '', 10);
    $this->SetTextColor(40,40,40);

    // cores das linhas
    $row_branco = [255,255,255];
    $row_verde = [235,245,238]; // verde claro

    $fill = false;

    foreach ($empresas as $empresa) {

        $bg = $fill ? $row_verde : $row_branco;
        $this->SetFillColor($bg[0], $bg[1], $bg[2]);

        $w_nome = 60;
        $w_contato = 50;
        $w_tel = 35;
        $w_end = 133;

        $nome = utf8_decode(mb_strtoupper($empresa['nome'], 'UTF-8'));
        $contato = utf8_decode(mb_strtoupper($empresa['nome_contato'], 'UTF-8'));
        $telefone = formatar_telefone($empresa['contato']);
        $endereco = utf8_decode($empresa['endereco']);

        $lineHeight = 5;

        // calcular linhas
        $nb_nome = $this->NbLines($w_nome, $nome);
        $nb_contato = $this->NbLines($w_contato, $contato);
        $nb_tel = $this->NbLines($w_tel, $telefone);
        $nb_end = $this->NbLines($w_end, $endereco);

        $nb = max($nb_nome, $nb_contato, $nb_tel, $nb_end);

        $h = $lineHeight * $nb;

        if($this->GetY() + $h > $this->PageBreakTrigger){
            $this->AddPage($this->CurOrientation);
        }

        $x = $this->GetX();
        $y = $this->GetY();

        // desenha células
        $this->Rect($x, $y, $w_nome, $h, 'DF');
        $this->Rect($x+$w_nome, $y, $w_contato, $h, 'DF');
        $this->Rect($x+$w_nome+$w_contato, $y, $w_tel, $h, 'DF');
        $this->Rect($x+$w_nome+$w_contato+$w_tel, $y, $w_end, $h, 'DF');

        // CENTRALIZAÇÃO VERTICAL
        $y_nome = $y + ($h - ($nb_nome * $lineHeight)) / 2;
        $y_contato = $y + ($h - ($nb_contato * $lineHeight)) / 2;
        $y_tel = $y + ($h - ($nb_tel * $lineHeight)) / 2;
        $y_end = $y + ($h - ($nb_end * $lineHeight)) / 2;

        // escreve textos
        $this->SetXY($x, $y_nome);
        $this->MultiCell($w_nome, $lineHeight, $nome, 0, 'L');

        $this->SetXY($x+$w_nome, $y_contato);
        $this->MultiCell($w_contato, $lineHeight, $contato, 0, 'L');





$link = gerar_link_whatsapp($empresa['contato']);

$this->SetXY($x+$w_nome+$w_contato, $y_tel);

if ($link) {
    // telefone com link
    $this->SetTextColor(0, 102, 0);
    $this->SetFont('', 'U');
    $this->Write($lineHeight, $telefone, $link);

    // reset
    $this->SetFont('', '');
    $this->SetTextColor(40,40,40);
} else {
    // telefone comum (fixo)
    $this->MultiCell($w_tel, $lineHeight, $telefone, 0, 'L');
}












        $this->SetXY($x+$w_nome+$w_contato+$w_tel, $y_end);
        $this->MultiCell($w_end, $lineHeight, $endereco, 0, 'L');

        $this->SetY($y + $h);

        $fill = !$fill;
    }
}

}
// --- BUSCA DADOS ---
$empresa_id = isset($_GET['empresa_id']) ? $_GET['empresa_id'] : '';
$perfil = isset($_GET['perfil']) ? $_GET['perfil'] : '';

$select_model = new select_model;
if ($empresa_id) {
    $empresas = [];
    $empresa = $select_model->concedente_por_id($empresa_id);
    if ($empresa) $empresas[] = $empresa;
} else {
    $empresas = $select_model->concedentes();
}

// --- GERA PDF ---
$pdf = new ModernEmpresasPDF('L', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->tabelaEmpresas($empresas);
$pdf->Output('I', 'relatorio_empresas.pdf');
exit;