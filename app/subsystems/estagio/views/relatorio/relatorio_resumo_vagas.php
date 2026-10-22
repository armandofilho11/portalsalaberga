<?php
require_once('../../models/select_model.php');
require_once('../../models/sessions.php');
require_once('../../assets/fpdf/fpdf.php');

class PDF extends FPDF {

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        if (is_string($txt)) {
            $txt = mb_convert_encoding($txt, 'ISO-8859-1', 'UTF-8');
        }
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

}

class RelatorioResumoVagas extends PDF {

    private $select_model;
    private $vagas;
    private $cores;

    public function __construct() {

        parent::__construct('P','mm','A4');

        $this->SetAutoPageBreak(true,25);
        $this->SetMargins(15,15,15);

        $this->select_model = new select_model();
        $this->vagas = $this->select_model->vagas();

        $this->cores = [
            'primaria'=>[0,122,51],
            'secundaria'=>[240,240,240],
            'destaque'=>[0,90,40],
            'texto'=>[70,70,70],
            'subtitulo'=>[100,100,100]
        ];
    }

    function Header(){

        if($this->PageNo()==1){

            $this->SetFillColor(248,248,248);
            $this->Rect(0,0,$this->GetPageWidth(),40,'F');

            $this->Image('https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png',15,10,25);

            $this->SetFont('Arial','B',18);
            $this->SetTextColor(...$this->cores['primaria']);
            $this->SetXY(45,15);
            $this->Cell(100,10,' Resumo de Vagas',0,0,'L');

            $this->SetFont('Arial','B',9);
            $this->SetTextColor(...$this->cores['subtitulo']);
            $this->SetXY(45,25);
            $this->Cell(100,5,'  Ensino Médio Técnico em Informática',0,0,'L');

            date_default_timezone_set('America/Fortaleza');

            $this->SetFont('Arial','I',9);
            $this->SetXY(45,30);
            $this->Cell(100,5,'  Gerado em: '.date('d/m/Y H:i'),0,0,'L');

            $this->SetDrawColor(...$this->cores['primaria']);
            $this->SetLineWidth(0.5);
            $this->Line(15,40,195,40);

            $this->SetY(45);

        }else{

            $this->SetY(15);

        }

    }

    function Footer(){

        $this->SetDrawColor(...$this->cores['primaria']);
        $this->SetLineWidth(0.3);
        $this->Line(15,$this->GetPageHeight()-20,$this->GetPageWidth()-15,$this->GetPageHeight()-20);

        $this->SetY(-18);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(...$this->cores['subtitulo']);

        $this->Cell(0,5,'Página '.$this->PageNo().'/{nb}',0,1,'C');
        $this->Cell(0,5,'Sistema de Gestão de Vagas - Todos os direitos reservados',0,0,'C');

    }

    public function gerarRelatorio(){

        $this->AliasNbPages();
        $this->AddPage();

        $this->SetFillColor(220,240,230);
        $this->SetFont('Arial','B',10);

        $this->SetX(15+(180-170)/2);

        $this->Cell(100,6,'Empresa',1,0,'L',true);
        $this->Cell(40,6,'Perfil',1,0,'L',true);
        $this->Cell(30,6,'Quantidade',1,1,'C',true);

        $this->SetFont('Arial','',9);
        $this->SetTextColor(40,40,40);

        $row_bg1=[255,255,255];
        $row_bg2=[245,250,245];

        $contador_linhas=0;

        $vagas_agrupadas=[];
        $parciais_perfil=[1=>0,2=>0,3=>0,4=>0];

        foreach($this->vagas as $vaga){

            $chave=$vaga['nome_empresa'].'|'.$vaga['nome_perfil'];

            if(!isset($vagas_agrupadas[$chave])){

                $vagas_agrupadas[$chave]=[
                    'empresa'=>$vaga['nome_empresa'],
                    'perfil'=>$vaga['nome_perfil'],
                    'quant_vaga'=>0
                ];

            }

            $vagas_agrupadas[$chave]['quant_vaga']+=$vaga['quant_vaga'];

$perfil = strtolower(trim($vaga['nome_perfil']));

if (strpos($perfil, 'desenvolv') !== false) {
    $parciais_perfil[1] += $vaga['quant_vaga'];
}
elseif (strpos($perfil, 'design') !== false || strpos($perfil, 'mídia') !== false) {
    $parciais_perfil[2] += $vaga['quant_vaga'];
}
elseif (strpos($perfil, 'suporte') !== false || strpos($perfil, 'rede') !== false) {
    $parciais_perfil[3] += $vaga['quant_vaga'];
}
elseif (strpos($perfil, 'tutoria') !== false) {
    $parciais_perfil[4] += $vaga['quant_vaga'];
}

        }

        uasort($vagas_agrupadas,function($a,$b){

            $cmp=strcmp($a['empresa'],$b['empresa']);
            if($cmp===0) return strcmp($a['perfil'],$b['perfil']);
            return $cmp;

        });

        foreach($vagas_agrupadas as $vaga){

            $bg=($contador_linhas%2==0)?$row_bg1:$row_bg2;

            $this->SetFillColor(...$bg);
            $this->SetX(15+(180-170)/2);

            $this->Cell(100,5.5,$this->ajustarTexto($this->formatarEmpresa($vaga['empresa']),55),1,0,'L',true);
            $this->Cell(40,5.5,$this->ajustarTexto($vaga['perfil'],20),1,0,'L',true);
            $this->Cell(30,5.5,$vaga['quant_vaga'],1,1,'C',true);

            $contador_linhas++;

        }

        $this->Ln(3);

        $this->SetFont('Arial','B',10);
        $this->SetX(15+(180-(140+30))/2);
        $this->Cell(170,6,'Detalhamento das Vagas:',0,1,'L');

        $nomes_perfil=[
            1=>'Perfil Desenvolvimento',
            2=>'Perfil Design/Mídias',
            3=>'Perfil Suporte/Redes',
            4=>'Perfil Tutoria'
        ];

        foreach($parciais_perfil as $id=>$q){

            $this->SetX(15+(180-(140+30))/2);

            $this->Cell(140,5.5,$nomes_perfil[$id].':',1,0,'L',true);
            $this->Cell(30,5.5,$q,1,1,'C',true);

        }

        $this->Ln(3);
        
        $this->SetFont('Arial','B',10);
        $this->SetX(15+(180-(140+30))/2);
        $this->Cell(170,6,'Resumo:',0,1,'L');
        
        $this->SetX(15+(180-(140+30))/2);
        $this->Cell(140,5.5,'Total de Vagas:',1,0,'L',true);

        $this->Cell(30,5.5,array_sum(array_column($vagas_agrupadas,'quant_vaga')),1,1,'C',true);

        $this->SetX(15+(180-(140+30))/2);
        $this->Cell(140,5.5,'Déficit de Vagas:',1,0,'L',true);
        $this->Cell(30,5.5,49-array_sum(array_column($vagas_agrupadas,'quant_vaga')),1,1,'C',true);

        $this->SetX(15+(180-(140+30))/2);
        $this->Cell(140,5.5,'Concedentes:',1,0,'L',true);
        $this->Cell(30,5.5,count(array_unique(array_column($vagas_agrupadas,'empresa'))),1,1,'C',true);

    }

    private function ajustarTexto($texto,$tamanho){

        if(strlen($texto)>$tamanho){
            return substr($texto,0,$tamanho-3).'...';
        }

        return $texto;

    }

    private function formatarEmpresa($empresa){
        return mb_strtoupper($empresa,'UTF-8');
    }

}

session_start();

$session=new sessions();
$session->autenticar_session();

try{

    $relatorio=new RelatorioResumoVagas();

    $relatorio->gerarRelatorio();

    ob_clean();

    $relatorio->Output('Relatorio_Resumo_Vagas.pdf','I');

}catch(Exception $e){

    header('Content-Type:text/html;charset=utf-8');

    echo "<h2>Erro ao gerar relatório</h2>";
    echo $e->getMessage();

}
?>