```php
<?php

require_once('../../models/select_model.php');
require_once('../../models/sessions.php');
require_once('../../assets/fpdf/fpdf.php');

class PDF extends FPDF {

    function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=false,$link=''){

        if(is_string($txt)){
            $txt = mb_convert_encoding($txt,'ISO-8859-1','UTF-8');
        }

        parent::Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
    }
}






class RelatorioResumoVagas extends PDF {

    private $select_model;
    private $vagas;

    public function __construct($vagas){
        parent::__construct('P','mm','A4');
        $this->SetMargins(10,15,10);
        $this->SetAutoPageBreak(true,25);
        $this->select_model = new select_model();
        $this->vagas = $vagas;
    }



function Header(){

    if($this->PageNo()==1){

        $this->SetFillColor(248,248,248);
        $this->Rect(0,0,$this->GetPageWidth(),40,'F');

        // Logo esquerda
        $this->Image('../../assets/img/logo_S_de_salaberga.png',5,13,20);

        // Logo direita
        $this->Image('../../assets/img/logo_png_areadev.png',166,12,30);

        // Título
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(0,122,51);
        $this->SetXY(25,13);
        $this->Cell(100,10,' Selecoes | Entrevistas',0,0,'L');

        // Subtítulo
        $this->SetFont('Arial','B',9);
        $this->SetTextColor(90,90,90);
        $this->SetXY(25,23);
        $this->Cell(100,5,'  Ensino Medio Tecnico em Informatica',0,0,'L');

        // Data
        date_default_timezone_set('America/Fortaleza');
        $this->SetFont('Arial','I',9);
        $this->SetXY(25,28);
        $this->Cell(100,5,'  Gerado em: '.date('d/m/Y H:i'),0,0,'L');

        // Linha inferior
        $this->SetDrawColor(0,122,51);
        $this->SetLineWidth(0.5);
        $this->Line(10,40,198,40);
        $this->SetY(45);

    }else{

        $this->SetY(15);

    }
}
    function Footer(){
        $this->SetY(-16);
        $this->SetDrawColor(0,122,51);
        $this->Line(10,280,198,280);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(100,100,100);
        $this->Cell(94,5,'Sistema de Gestão de Estágio',0,0,'L');
        $this->Cell(94,5,'Página '.$this->PageNo().'/{nb}',0,0,'R');
    }

    private function cabecalhoTabela(){
        $this->SetFont('Arial','B',10);
        $this->SetFillColor(220,240,230);
        $this->Cell(45,7,'Empresa',1,0,'C',true);
        $this->Cell(30,7,'Perfil',1,0,'C',true);
        $this->Cell(33,7,'Data | Hora',1,0,'C',true);
        $this->Cell(80,7,'Aluno(a)',1,1,'L',true);
    }

    public function gerarRelatorio(){

        $bg1 = [255,255,255];
        $bg2 = [245,250,245];
        $this->cabecalhoTabela();
        $linha = 0;
        $idsAprovados = [];
        $idsAguardando = [];
        $idsVagas = [];
        $qtdDesenvolvimento = 0;
        $qtdDesign = 0;
        $qtdSuporte = 0;
        $qtdTutoria = 0;

        foreach($this->vagas as $vaga){
        $empresa = $vaga['nome_empresa'] ?? '-';
        $perfil = $vaga['nome_perfil'] ?? '-';
        $data = date('d/m/Y',strtotime($vaga['data']));
        $hora = $vaga['hora'] ?? '-';
        $dataHora = $data.' | '.$hora;
        $dataOriginal = $vaga['data'] ?? '';
        $data = date('d/m/Y',strtotime($dataOriginal));
        $hora = $vaga['hora'] ?? '-';
            
            
            
        // Se for indicação (01/01/2026)
        if($data == '01/01/2026'){
            $dataHora = 'Indicação...';
            $isIndicacao = true;
        }else{
            $dataHora = $data.' | '.$hora;
            $isIndicacao = false;
        }
        
        
        
        
        
        
        
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            $idsVagas[$vaga['id']] = true;
            $alunos = $this->select_model->alunos_selecionados_estagio($vaga['id']);

if(empty($alunos)){
    $quantVagas = intval($vaga['quant_vaga']);
    if($quantVagas <= 0){
        $quantVagas = 1;
    }
    for($i = 1; $i <= $quantVagas; $i++){
        $bg = ($linha % 2 == 0)
            ? $bg1
            : $bg2;

        $this->SetFillColor(...$bg);
        $this->SetFont('Arial','',8);
        $this->Cell(45,5,$empresa,1,0,'L',true);
        $this->Cell(30,5,$perfil,1,0,'L',true);

        if(trim($dataHora) == 'Indicação...'){
            $this->SetFont('Arial','B',8);
        }else{
            $this->SetFont('Arial','',8);
        }
        $this->Cell(33,5," ".$dataHora,1,0,'L',true);
        $this->SetFont('Arial','I',8);
        $this->Cell(80,5,'Aguardando direcionamento ('.$i.'/'.$quantVagas.')',1,1,'L',true);
        $linha++;
    }

    continue;
}


// ======================================================================
        // ZERANDO A VARIÁVEL PARA USAR COMO CONTADOR DE INDICAÇÕES
// ======================================================================
            $linha=0;
















foreach($alunos as $a){
    $bg = ($linha % 2 == 0) ? $bg1 : $bg2;
    $this->SetFillColor(...$bg);

    $status = strtolower($a['status'] ?? ''); // Normaliza para minúsculo

    // ==================== CONTAGEM DE APROVADOS ====================
    if($status === 'approved'){
        $idsAprovados[$a['id']] = true;

        if(trim($vaga['nome_perfil']) == 'Desenvolvimento') $qtdDesenvolvimento++;
        if(trim($vaga['nome_perfil']) == 'Design/Mídias') $qtdDesign++;
        if(trim($vaga['nome_perfil']) == 'Suporte/Redes') $qtdSuporte++;
        if(trim($vaga['nome_perfil']) == 'Tutoria') $qtdTutoria++;
    }

    if($status === 'waiting'){
        $idsAguardando[$a['id']] = true;
    }

    // ==================== IMPRESSÃO DA LINHA ====================
    $this->SetFont('Arial','',8);

    $this->Cell(45,5,$empresa,1,0,'L',true);
    $this->Cell(30,5,$perfil,1,0,'L',true);

    // Data/Hora (negrito apenas se for indicação)
    if(trim($dataHora) == 'Indicação...'){
        $this->SetFont('Arial','B',8);
    } else {
        $this->SetFont('Arial','',8);
    }
    $this->Cell(33,5," ".$dataHora,1,0,'L',true);

    // Nome do aluno - AQUI ESTAVA O ERRO
    if($status === 'approved'){
        $this->SetFont('Arial','B',8);   // Negrito para aprovados
    } else {
        $this->SetFont('Arial','',8);    // Normal para os demais
    }

    $this->Cell(80,5,$a['nome'],1,1,'L',true);

    $linha++;
}






















            $qtdIndicados = count($alunos);
            $quantVagas   = intval($vaga['quant_vaga']);
            
            for($i = $qtdIndicados; $i < $quantVagas; $i++){
            
                $bg = ($linha % 2 == 0)
                    ? $bg1
                    : $bg2;
            
                $this->SetFillColor(...$bg);
            
                $this->SetFont('Arial','',8);
                $this->Cell(45,5,$empresa,1,0,'L',true);
                $this->Cell(30,5,$perfil,1,0,'L',true);
            
                if(trim($dataHora) == 'Indicação...'){
                    $this->SetFont('Arial','B',8);

                // CONTANDO INDICAÇÕES
                $totalIndicacoes += $linha;

                }
            
                $this->Cell(33,5," ".$dataHora,1,0,'L',true);
            
                $this->SetFont('Arial','I',8);
                $this->Cell(80,5,'Aguardando direcionamento',1,1,'L',true);
            
                $linha++;
            }
        }

//////////////////////////////////////////////////////////////////////////////////////////////////////////



$qtdAlunos = count($alunos);
$quantVagas = intval($vaga['quant_vaga']);











// ===== RESUMO =====
$totalAlunos = $this->select_model->total_alunos();
if($totalAlunos === null || $totalAlunos === ''){
    $totalAlunos = 'ERRO';
}
$totalAlocados = count($idsAprovados);



// =========================================================================
// PEGA O TOTAL DE EMPRESAS QUE REALIZARAM SELEÇÃO
// =========================================================================


$empresasComSelecao = [];
foreach($this->vagas as $vaga){
    if(!empty($vaga['data']) && $vaga['data'] != '0000-00-00' && $vaga['data'] != '2026-01-01'){
        $empresasComSelecao[$vaga['id_empresa']] = true;
    }
}
// =========================================================================
// =========================================================================


$totalSelecoes = count($empresasComSelecao);
$totalNaoAlocados = $totalAlunos - $totalAlocados;

if($totalNaoAlocados < 0){
    $totalNaoAlocados = 0;
}

































// ===== RESUMO =====

$this->Ln(5);
// Total de empresas
$totalEmpresas = $this->select_model->total_empresa();
$totalEmpresas = current($totalEmpresas);

// Cabeçalho mesclado
$this->SetFont('Arial','B',9);
$this->SetFillColor(220,240,230);
$this->Cell(188,5,'Indicadores importantes',1,1,'L',true);

// Dados
// Total de vagas para indicação (01/01/2026)

foreach($this->vagas as $vaga){

    if(!empty($vaga['data']) && date('d/m/Y', strtotime($vaga['data'])) == '01/01/2026'){
        $totalIndicacoes++;
    }
}

$resumo = [
    'Total de alunos da turma'        => $totalAlunos,
    'Alunos selecionados'             => $totalAlocados,
    'Alunos não selecionados'         => $totalNaoAlocados,
    'Empresas cadastradas'            => $totalEmpresas
];

/*     
    'Seleções realizadas'             => $totalSelecoes,
    'Vagas para indicação'            => $totalIndicacoes,
*/



$linha = 0;
$bg1 = [255,255,255];
$bg2 = [245,250,245];

$this->SetFont('Arial','',9);
$this->SetTextColor(0,0,0);

foreach($resumo as $titulo => $valor){
    $bg = ($linha % 2 == 0)
        ? $bg1
        : $bg2;

    $this->SetFillColor(...$bg);
    $this->Cell(160,5,$titulo,1,0,'L',true);
    $this->Cell(28,5,$valor,1,1,'C',true);
    $linha++;
}



$this->Ln(10);




// ====================================================
// ====================================================
// /////////////// SESSÃO DOS GRÁFICOS ////////////////
// ====================================================
// ====================================================


$alturaMax = 45;
$larguraGrafico = 32; // todos os gráficos terão exatamente esta largura
$larguraBarra   = 4;


// ------------------------------------
// CONFIGURAÇÃO DAS COLUNAS
// ------------------------------------

$margemEsq = 10;
$margemDir = 10;
$larguraUtil = 210 - $margemEsq - $margemDir; // A4 retrato

$coluna = $larguraUtil / 3;

// Centros dos gráficos
$x2 = 26;   // Esquerda
$x1 = 89;   // Centro
$x3 = 152;  // Direita

// ------------------------------------
// TÍTULOS
// ------------------------------------

$this->SetFont('Arial','B',10);

$this->SetX($margemEsq);

$this->Cell($coluna,5,'Seleções x Indicações',0,0,'C');
$this->Cell($coluna,5,'Selecionados',0,0,'C');
$this->Cell($coluna,5,'Perfil dos Selecionados',0,0,'C');

$this->Ln(16);

// ------------------------------------
// DIVISÓRIAS
// ------------------------------------

$yLinhaInicio = $this->GetY() - 10;
$yLinhaFim    = $yLinhaInicio + 78;

$this->SetDrawColor(200,200,200);
$this->SetLineWidth(0.2);

$this->Line(73.33, $yLinhaInicio, 73.33, $yLinhaFim);
$this->Line(136.66, $yLinhaInicio, 136.66, $yLinhaFim);





// ====================================================
// ====================================================
// ////////// GRÁFICO 2  - SELECIONADOS ///////////////
// ====================================================
// ====================================================



$x = $x1;
$yTopo = $this->GetY()-2;

$totalAlunos     = $totalAlunos;
$selecionados    = $totalAlocados;
$naoSelecionados = $totalNaoAlocados;

$maior = max(
    $totalAlunos,
    $selecionados,
    $naoSelecionados,
    1
);

// Linha base
$baseY = $yTopo + $alturaMax;
$this->Line($x, $baseY, $x + $larguraGrafico, $baseY);

// Posições padronizadas das barras
$barra1 = $x + 8;
$barra2 = $x + 18;

// =========================
// SELECIONADOS
// =========================

$h2 = ($selecionados / $maior) * $alturaMax;

$this->SetFillColor(0, 120, 212);
$this->Rect($barra1, $baseY - $h2, $larguraBarra, $h2, 'DF');

$this->SetFont('Arial','B',8);
$this->SetXY($barra1 - 7, $baseY - $h2 - 6);
$this->Cell(18,5,$selecionados,0,0,'C');

// =========================
// NÃO SELECIONADOS
// =========================

$h3 = ($naoSelecionados / $maior) * $alturaMax;

$this->SetFillColor(210, 0, 95);
$this->Rect($barra2, $baseY - $h3, $larguraBarra, $h3, 'DF');

$this->SetXY($barra2 - 7, $baseY - $h3 - 6);
$this->Cell(18,5,$naoSelecionados,0,0,'C');

// =========================
// LEGENDA
// =========================

$legY = $baseY + 15;

$this->SetFont('Arial','',8);

// Selecionados
$this->SetFillColor(0, 120, 212);
$this->Rect($x, $legY - 13, 4, 4, 'DF');
$this->SetXY($x + 6, $legY - 13);
$this->Cell(32,5,'Selecionados',0,0);

// Não Selecionados
$this->SetFillColor(210, 0, 95);
$this->Rect($x, $legY - 7, 4, 4, 'DF');
$this->SetXY($x + 6, $legY - 7);
$this->Cell(32,5,'Não Selecionados',0,0);














// ====================================================
// ====================================================
// /////// GRÁFICO 1 - SELEÇÃO X INDICAÇÃO ////////////
// ====================================================
// ====================================================


$x = $x2;

// Estas variáveis já existem no seu código
$selecao   = $totalSelecoes;
$indicacao = $totalIndicacoes;
$maior = max($selecao, $indicacao, 1);


// Linha base
$this->Line($x, $baseY, $x + $larguraGrafico, $baseY);

// Posições centralizadas das barras
$barra1 = $x + 8;
$barra2 = $x + 18;

// =========================
// SELEÇÕES
// =========================

$h2 = ($selecao / $maior) * $alturaMax;

$this->SetFillColor(0, 120, 212); // Azul forte
$this->SetDrawColor(0,0,0);
$this->Rect($barra1, $baseY - $h2, $larguraBarra, $h2, 'DF');

$this->SetFont('Arial','B',8);
$this->SetXY($barra1 - 7, $baseY - $h2 - 6);
$this->Cell(18,5,$selecao,0,0,'C');

// =========================
// INDICAÇÕES
// =========================

$h3 = ($indicacao / $maior) * $alturaMax;

$this->SetFillColor(210, 0, 95); // Laranja forte
$this->SetDrawColor(0,0,0);
$this->Rect($barra2, $baseY - $h3, $larguraBarra, $h3, 'DF');

$this->SetXY($barra2 - 7, $baseY - $h3 - 6);
$this->Cell(18,5,$indicacao-3,0,0,'C');

// =========================
// LEGENDA
// =========================

$this->SetFont('Arial','',8);

$this->SetFillColor(0, 120, 212);
$this->Rect($x, $legY - 13, 4, 4, 'DF');
$this->SetXY($x + 6, $legY - 13);
$this->Cell(32,5,'Seleções Realizadas',0,0);

$this->SetFillColor(210, 0, 95);
$this->Rect($x, $legY - 7, 4, 4, 'DF');
$this->SetXY($x + 6, $legY - 7);
$this->Cell(32,5,'Indicações a Fazer',0,0);








// ====================================================
// ====================================================
// //////// GRÁFICO 3 - Perfil dos selecionados////////
// ====================================================
// ====================================================


$perfil1 = $qtdDesenvolvimento;
$perfil2 = $qtdSuporte;
$perfil3 = $qtdDesign;
$perfil4 = $qtdTutoria;

$x = $x3;


// Evita divisão por zero
$maior = max($perfil1, $perfil2, $perfil3, $perfil4, 1);

$perfis = [$perfil1, $perfil2, $perfil3, $perfil4];

// Impede quebra automática durante o gráfico
$this->SetAutoPageBreak(false);

// Linha base (mesma largura dos outros gráficos)
$this->Line($x, $baseY, $x + $larguraGrafico, $baseY);

// =========================
// LEGENDA
// =========================

$this->SetFont('Arial', '', 7);

// Desenvolvimento (Azul)
$this->SetFillColor(0, 120, 212);
$this->Rect($x+1, $legY-13, 4, 4, 'DF');
$this->SetXY($x+7, $legY-13);
$this->Cell(32,5,'Desenvolvimento',0,0);

// Suporte (Laranja)
$this->SetFillColor(210, 0, 95);
$this->Rect($x+1, $legY-7, 4, 4, 'DF');
$this->SetXY($x+7, $legY-7);
$this->Cell(32,5,'Suporte / Redes',0,0);

// Design (Verde)
$this->SetFillColor(250, 205, 0);
$this->Rect($x+1, $legY-1, 4, 4, 'DF');
$this->SetXY($x+7, $legY-1);
$this->Cell(32,5,'Design',0,0);

// Tutoria (Vermelho)
$this->SetFillColor(0,0,0);
$this->Rect($x+1, $legY+5, 4, 4, 'DF');
$this->SetXY($x+7, $legY+5);
$this->Cell(32,5,'Tutoria',0,0);


// =========================
// BARRAS
// =========================


// Cores
$cores = [
    [0, 120, 212],   // Desenvolvimento
    [210, 0, 95],   // Suporte
    [250, 205, 0],   // Design
    [0, 0, 0]    // Tutoria
];

// Distribuição perfeitamente simétrica dentro dos 32 mm
$posicoes = [
    $x + 1,
    $x + 9,
    $x + 17,
    $x + 25
];

$this->SetDrawColor(0,0,0);
$this->SetLineWidth(0.3);

for ($i = 0; $i < 4; $i++) {

    $h = ($perfis[$i] / $maior) * $alturaMax;

    $bx = $posicoes[$i];

    $this->SetFillColor(
        $cores[$i][0],
        $cores[$i][1],
        $cores[$i][2]
    );

    // Barra
    $this->Rect(
        $bx,
        $baseY - $h,
        $larguraBarra,
        $h,
        'DF'
    );

    // Valor acima
    $textoY = $baseY - $h - 6;

    $this->SetFont('Arial','B',7);
    $this->SetXY($bx - 3,$textoY);
    $this->Cell(10,5,$perfis[$i],0,0,'C');
      }
    }
}













session_start();
$session = new sessions();
$session->autenticar_session();

try{

    $empresa_id = isset($_GET['empresa'])
        ? intval($_GET['empresa'])
        : '';

    $perfil_id = isset($_GET['perfil'])
        ? intval($_GET['perfil'])
        : '';

    $select_model = new select_model();

    $vagas = $select_model->vagas(
        '',
        $perfil_id,
        $empresa_id
    );


    // ==========================
    // Somente vagas com data/hora
    // ==========================

    $vagas = array_filter(
        $vagas,
        function($vaga){
            return !empty($vaga['data'])
                && $vaga['data'] != '0000-00-00'
                && !empty($vaga['hora']);
        }
    );
    
    



    // ==========================
    // Ordenação
    // ==========================

    usort(
        $vagas,
        function($a,$b){
            return strtotime(
                $a['data'].' '.$a['hora']
            ) - strtotime(
                $b['data'].' '.$b['hora']
            );
        }
    );

    $pdf = new RelatorioResumoVagas($vagas);
    $pdf->AliasNbPages();
    $pdf->AddPage();

    if(empty($vagas)){

        $pdf->SetFont('Arial','B',8);
        $pdf->SetTextColor(0,122,51);
        $pdf->Cell(0,20,'Nenhuma entrevista encontrada.',0,1,'C');

    } else {

        $pdf->gerarRelatorio();
    }

    ob_clean();
    $pdf->Output('relatorio_entrevistas.pdf','I');

}catch(Exception $e){

    echo 'Erro ao gerar PDF: '.$e->getMessage();
}
?>
