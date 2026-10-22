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


            // Logo esquerda
            $this->Image('../../assets/img/logo_S_de_salaberga.png',10,13,20);
            
            // Logo direita
            $this->Image('../../assets/img/logo_png_areadev.png',166,12,30);



            $this->SetFont('Arial','B',18);
            $this->SetTextColor(...$this->cores['primaria']);
            $this->SetXY(30,13);
            $this->Cell(100,10,' Resumo de Vagas',0,0,'L');

            $this->SetFont('Arial','B',9);
            $this->SetTextColor(...$this->cores['subtitulo']);
            $this->SetXY(30,23);
            $this->Cell(100,5,'  Ensino Médio Técnico em Informática',0,0,'L');

            date_default_timezone_set('America/Fortaleza');

            $this->SetFont('Arial','I',9);
            $this->SetXY(30,28);
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

        $this->SetY(-18);

        $this->SetDrawColor(0,122,51);
        $this->Line(15,$this->GetPageHeight()-20,$this->GetPageWidth()-15,$this->GetPageHeight()-20);

        $this->SetFont('Arial','I',8);
        $this->SetTextColor(100,100,100);
        
        $this->SetX(13.5);
        $this->Cell(94,5,'Sistema de Gestão de Estágio',0,0,'L');
        $this->Cell(92,5,'Página '.$this->PageNo().'/{nb}',0,0,'R');
    }

    
    

    public function gerarRelatorio(){
        $this->AliasNbPages();
        $this->AddPage();

        $this->SetFillColor(220,240,230);
        $this->SetFont('Arial','B',9);

        $this->SetX(15+(180-180)/2);

        $this->Cell(110,6,'Empresa',1,0,'L',true);
        $this->Cell(40,6,'Perfil',1,0,'L',true);
        $this->Cell(30,6,'Vagas',1,1,'C',true);

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
        
            $bg = ($contador_linhas % 2 == 0) ? $row_bg1 : $row_bg2;
        
            $this->SetFillColor(...$bg);
            $this->SetX(15+(180-180)/2);
        
            // Empresa
            $this->SetFont('Arial','',8.8);
            $this->Cell(110,5,$this->ajustarTexto($this->formatarEmpresa($vaga['empresa']),55),1,0,'L',true);
        
            // Perfil - negrito apenas para "Entrar em contato..."
            if (trim($vaga['perfil']) == 'Entrar em contato...') {
                $this->SetFont('Arial','B',8.8);
            } else {
                $this->SetFont('Arial','',8.8);
            }
        
            $this->Cell(40,5,$this->ajustarTexto($vaga['perfil'],20),1,0,'L',true);
        
            // Volta fonte normal
            $this->SetFont('Arial','',8.8);
        
            // Quantidade
            $this->Cell(30,5,$vaga['quant_vaga'],1,1,'C',true);
        
            $contador_linhas++;
        }







// RESUMO
            $this->Ln(3);
            
            // Cabeçalho
            $this->SetX(15);
            $this->SetFillColor(220,240,230);
            $this->SetFont('Arial','B',9);
            $this->Cell(180,6,'Resumo',1,1,'L',true);
            // Volta ao padrão normal da tabela
            $this->SetFont('Arial','',9);
            
            
            // Conteúdo
            $total_vagas = array_sum(array_column($vagas_agrupadas,'quant_vaga'));
            $total_alunos = $this->select_model->total_alunos();
            $deficit = max(0, $total_alunos - $total_vagas);
            
            $resumo = [
                'Total de Alunos'  => $total_alunos,
                'Vagas Confirmadas'   => $total_vagas,
                'Déficit de Vagas' => $deficit,
                'Concedentes'      => count(array_unique(array_column($vagas_agrupadas,'empresa')))
            ];
        
            $i = 0;
            foreach($resumo as $titulo => $valor){
            
                $this->SetX(15);
                $this->SetFillColor(...(($i % 2 == 0) ? $row_bg1 : $row_bg2));
                $this->SetFont('Arial','',8.8);
                $this->Cell(150,5,$titulo,1,0,'L',true);
                $this->Cell(30,5,$valor,1,1,'C',true);
            
                $i++;
            }
            






//TOTAL DE VAGAS POR PERFIL
            $this->Ln(3);
            
            // Título no padrão do cabeçalho
            $this->SetX(15+(180-180)/2);
            $this->SetFillColor(220,240,230);
            $this->SetFont('Arial','B',9);
            $this->Cell(180,6,'Vagas por Perfil',1,1,'L',true);
            // Volta ao padrão normal da tabela
            $this->SetFont('Arial','',9);

        $nomes_perfil=[
            1=>'Perfil Desenvolvimento',
            2=>'Perfil Design/Mídias',
            3=>'Perfil Suporte/Redes',
            4=>'Perfil Tutoria'
        ];

        $contador_detalhe = 0;
        foreach($parciais_perfil as $id => $q){
        
            $bg = ($contador_detalhe % 2 == 0) ? $row_bg1 : $row_bg2;
            $this->SetFillColor(...$bg);
            $this->SetFont('Arial','',8.8);
            $this->SetX(15+(180-180)/2);
            $this->Cell(150,5,$nomes_perfil[$id],1,0,'L',true);
            $this->Cell(30,5,$q,1,1,'C',true);
            $contador_detalhe++;
            
        }











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