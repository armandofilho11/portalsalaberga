<?php
require_once('../../models/select_model.php');
require_once('../../assets/fpdf/fpdf.php');
require_once('../../models/sessions.php');

date_default_timezone_set('America/Sao_Paulo');

$sessions = new sessions();
$sessions->autenticar_session();

class PDF extends FPDF {

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
        $txt = iconv('UTF-8','ISO-8859-1//TRANSLIT',$txt);
        parent::Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
    }

}

class ModernPDF extends PDF {

    private $cores = [
        'primaria'=>[0,122,51],
        'subtitulo'=>[100,100,100]
    ];

    private $select_model;
    private $vagas;

    function __construct($select_model,$vagas){

        parent::__construct('P','mm','A4');

        $this->SetMargins(10,15,10);
        $this->SetAutoPageBreak(true,25);

        $this->select_model=$select_model;
        $this->vagas=$vagas;

        $this->SetFont('Arial','',10);

    }

    function Header(){

        if($this->PageNo()==1){

            $this->SetFillColor(248,248,248);
            $this->Rect(0,0,$this->GetPageWidth(),40,'F');

            // logo (troque se necessário)
            if(file_exists('../../assets/logo.png')){
                $this->Image('../../assets/logo.png',15,10,25);
            }

            $this->SetFont('Arial','B',18);
            $this->SetTextColor(
                $this->cores['primaria'][0],
                $this->cores['primaria'][1],
                $this->cores['primaria'][2]
            );

            $this->SetXY(45,15);
            $this->Cell(120,10,'Seleções / Entrevistas realizadas',0,0,'L');

            $this->SetFont('Arial','B',9);
            $this->SetTextColor(
                $this->cores['subtitulo'][0],
                $this->cores['subtitulo'][1],
                $this->cores['subtitulo'][2]
            );

            $this->SetXY(45,25);
            $this->Cell(120,5,'Ensino Médio Técnico em Informática',0,0,'L');

            $this->SetFont('Arial','I',9);

            $this->SetXY(45,30);
            $this->Cell(120,5,'Gerado em: '.date('d/m/Y H:i'),0,0,'L');

            $this->SetDrawColor(
                $this->cores['primaria'][0],
                $this->cores['primaria'][1],
                $this->cores['primaria'][2]
            );

            $this->SetLineWidth(0.5);
            $this->Line(15,40,195,40);

            $this->SetY(45);

        }else{

            $this->SetY(15);

        }

    }

    function Footer(){

        $this->SetDrawColor(
            $this->cores['primaria'][0],
            $this->cores['primaria'][1],
            $this->cores['primaria'][2]
        );

        $this->SetLineWidth(0.3);

        $this->Line(
            15,
            $this->GetPageHeight()-20,
            $this->GetPageWidth()-15,
            $this->GetPageHeight()-20
        );

        $this->SetY(-18);

        $this->SetFont('Arial','I',8);

        $this->SetTextColor(
            $this->cores['subtitulo'][0],
            $this->cores['subtitulo'][1],
            $this->cores['subtitulo'][2]
        );

        $this->Cell(0,5,'Página '.$this->PageNo().'/{nb}',0,1,'C');

        $this->Cell(0,5,'Sistema de Gestão de Vagas - Todos os direitos reservados',0,0,'C');

    }

    function formatarEmpresa($empresa){
        return mb_strtoupper($empresa,'UTF-8');
    }

    function formatarNome($nome){

        $nome = mb_strtolower($nome,'UTF-8');
        $palavras = explode(' ',$nome);

        $palavras = array_map(function($p){
            return ucfirst($p);
        },$palavras);

        return implode(' ',$palavras);

    }

    function addTabelaVagas(){

        $this->SetFillColor(220,240,230);

        $this->SetFont('Arial','B',10);

        $this->Cell(45,7,'Empresa',1,0,'L',true);
        $this->Cell(30,7,'Perfil',1,0,'L',true);
        $this->Cell(35,7,'Data | Hora',1,0,'C',true);
        $this->Cell(65,7,'Aluno',1,0,'C',true);
        $this->Cell(15,7,'Contato',1,1,'C',true);

        $this->SetFont('Arial','',10);

        $bg1=[255,255,255];
        $bg2=[245,250,245];

        $i=0;

        foreach($this->vagas as $vaga){

            $bg = ($i%2==0)?$bg1:$bg2;

            $this->SetFillColor($bg[0],$bg[1],$bg[2]);

            $empresa = $this->formatarEmpresa($vaga['nome_empresa'] ?? '-');
            $perfil = $vaga['nome_perfil'] ?? '-';

            $data = !empty($vaga['data']) ? date('d/m/Y',strtotime($vaga['data'])) : '-';
            $hora = $vaga['hora'] ?? '-';

            $data_hora = $data.' | '.$hora;

            $this->Cell(45,7,$empresa,1,0,'L',true);
            $this->Cell(30,7,$perfil,1,0,'L',true);
            $this->Cell(35,7,$data_hora,1,0,'C',true);
            $this->Cell(65,7,'-',1,0,'C',true);
            $this->Cell(15,7,'-',1,1,'C',true);

            $i++;

        }

    }

    function gerarRelatorio(){
        $this->addTabelaVagas();
    }

}

try{

    $empresa_id = isset($_GET['empresa']) ? intval($_GET['empresa']) : '';
    $perfil_id = isset($_GET['perfil']) ? intval($_GET['perfil']) : '';

    $select_model = new select_model();

    $vagas = $select_model->vagas('', $perfil_id, $empresa_id);

    $pdf = new ModernPDF($select_model,$vagas);

    $pdf->AliasNbPages();

    $pdf->AddPage();

    if(empty($vagas)){

        $pdf->SetFont('Arial','B',14);

        $pdf->SetTextColor(0,122,51);

        $pdf->Cell(
            0,
            20,
            'Nenhuma vaga encontrada com os filtros selecionados.',
            0,
            1,
            'C'
        );

    }else{

        $pdf->gerarRelatorio();

    }

    $pdf->Output('relatorio_vagas.pdf','I');

}catch(Exception $e){

    echo "Erro ao gerar PDF: ".$e->getMessage();

}
?>