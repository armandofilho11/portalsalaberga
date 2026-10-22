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

class RelatorioAlunosStatus extends PDF {

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

            $this->Image('../../assets/img/logo_S_de_salaberga.png',5,13,20);
            $this->Image('../../assets/img/logo_png_areadev.png',166,12,30);

            $this->SetFont('Arial','B',18);
            $this->SetTextColor(0,122,51);
            $this->SetXY(25,13);
            $this->Cell(100,10,'Estágio Supervisionado 2026',0,0,'L');

            $this->SetFont('Arial','B',9);
            $this->SetTextColor(90,90,90);
            $this->SetXY(25,23);
            $this->Cell(100,5,'Ensino Médio Técnico em Informática',0,0,'L');

            date_default_timezone_set('America/Fortaleza');

            $this->SetFont('Arial','I',9);
            $this->SetXY(25,28);
            $this->Cell(
                100,
                5,
                'Gerado em: '.date('d/m/Y H:i'),
                0,
                0,
                'L'
            );

            $this->SetDrawColor(0,122,51);
            $this->SetLineWidth(0.5);
            $this->Line(10,40,198,40);

            $this->SetY(45);

        } else {

            $this->SetY(15);

        }
    }

    function Footer(){

        $this->SetY(-16);

        $this->SetDrawColor(0,122,51);
        $this->Line(10,280,198,280);

        $this->SetFont('Arial','I',8);
        $this->SetTextColor(100,100,100);

        $this->Cell(
            94,
            5,
            'Sistema de Gestão de Estágio',
            0,
            0,
            'L'
        );

        $this->Cell(
            94,
            5,
            'Página '.$this->PageNo().'/{nb}',
            0,
            0,
            'R'
        );
    }

    /**
     * Cabeçalho da tabela
     *
     * Empresa | Perfil | Origem | Aluno(a) | TCE
     *
     * Total = 190 mm
     */
    private function cabecalhoTabela(){

        $this->SetFont('Arial','B',9);
        $this->SetFillColor(220,240,230);
        $this->SetTextColor(0,0,0);

        $this->Cell(50,8,'Empresa',1,0,'C',true);
        $this->Cell(27,8,'Perfil',1,0,'C',true);
        $this->Cell(15,8,'Origem',1,0,'C',true);
        $this->Cell(78,8,'Aluno(a)',1,0,'C',true);
        $this->Cell(20,8,'TCE',1,1,'C',true);
    }

    public function gerarRelatorio(){

        $bg1 = [255,255,255];
        $bg2 = [245,250,245];

        $linha = 0;

        $aprovados = [];

        foreach($this->vagas as $vaga){

            $empresa = $vaga['nome_empresa'] ?? '-';
            $perfil  = $vaga['nome_perfil'] ?? '-';

            $data = date(
                'd/m/Y',
                strtotime($vaga['data'])
            );

            $hora = $vaga['hora'] ?? '-';

            /*
             * Se a data for 01/01/2026,
             * considera como indicação.
             * Caso contrário, seleção.
             */
            $dataHora = ($data == '01/01/2026')
                ? 'Indicação'
                : 'Seleção';

            $alunos = $this->select_model
                           ->alunos_selecionados_estagio($vaga['id']);

            foreach($alunos as $a){

                $status = strtolower(
                    trim($a['status'] ?? '')
                );

                if($status === 'approved'){

                    $aprovados[] = [

                        'empresa'  => trim($empresa),

                        'perfil'   => trim($perfil),

                        'dataHora' => trim($dataHora),

                        'nome'     => trim($a['nome'])

                    ];
                }
            }
        }

        /*
         * =====================================================
         * ORDENAÇÃO
         * =====================================================
         *
         * 1º Empresa
         * 2º Perfil
         * 3º Origem
         * 4º Aluno(a)
         */
        usort($aprovados, function($a, $b){

            $resultado = strcasecmp(
                $a['empresa'],
                $b['empresa']
            );

            if($resultado !== 0){
                return $resultado;
            }

            $resultado = strcasecmp(
                $a['perfil'],
                $b['perfil']
            );

            if($resultado !== 0){
                return $resultado;
            }

            $resultado = strcasecmp(
                $a['dataHora'],
                $b['dataHora']
            );

            if($resultado !== 0){
                return $resultado;
            }

            return strcasecmp(
                $a['nome'],
                $b['nome']
            );
        });

        // =====================================================
        // TÍTULO
        // =====================================================

        $this->SetFont('Arial','B',14);
        $this->SetTextColor(0,122,51);

        $this->Cell(
            0,
            12,
            "Relatório de TCE's",
            0,
            1,
            'C'
        );

        $this->SetTextColor(0,0,0);

        // Cabeçalho
        $this->cabecalhoTabela();

        // =====================================================
        // TABELA
        // =====================================================

        foreach($aprovados as $aluno){

            /*
             * Verifica se há espaço suficiente para a próxima
             * linha. Se não houver, cria uma nova página.
             */
            if($this->GetY() > 265){

                $this->AddPage();

                $this->cabecalhoTabela();
            }

            $bg = ($linha % 2 == 0)
                ? $bg1
                : $bg2;

            $this->SetFillColor(...$bg);

            // -------------------------------------------------
            // EMPRESA
            // -------------------------------------------------

            $this->SetFont('Arial','',8);

            $this->Cell(
                50,
                7,
                $aluno['empresa'],
                1,
                0,
                'L',
                true
            );

            // -------------------------------------------------
            // PERFIL
            // -------------------------------------------------

            $this->Cell(
                27,
                7,
                $aluno['perfil'],
                1,
                0,
                'L',
                true
            );

            // -------------------------------------------------
            // ORIGEM
            // -------------------------------------------------

            $this->Cell(
                15,
                7,
                $aluno['dataHora'],
                1,
                0,
                'L',
                true
            );

            // -------------------------------------------------
            // ALUNO
            // -------------------------------------------------

            $this->SetFont('Arial','B',8);

            $this->Cell(
                78,
                7,
                $aluno['nome'],
                1,
                0,
                'L',
                true
            );

            // -------------------------------------------------
            // TCE - CHECKBOX
            // -------------------------------------------------

            $x = $this->GetX();
            $y = $this->GetY();

            // Célula da coluna TCE
            $this->Cell(
                20,
                7,
                'Recebido',
                1,
                1,
                'R',
                true
            );

            /*
             * Desenha a caixa de seleção no centro
             * da coluna TCE.
             */
            $tamanhoCheckbox = 4;

            $checkboxX = $x + (7 - $tamanhoCheckbox) / 2;
            $checkboxY = $y + (7 - $tamanhoCheckbox) / 2;

            $this->SetDrawColor(60,60,60);
            $this->SetLineWidth(0.3);

            $this->Rect(
                $checkboxX,
                $checkboxY,
                $tamanhoCheckbox,
                $tamanhoCheckbox
            );

            $this->SetTextColor(0,0,0);

            $linha++;
        }

        // =====================================================
        // RESUMO
        // =====================================================

        $this->Ln(10);

        $this->SetFont('Arial','B',10);

        $this->Write(
            8,
            'Total de alunos encaminhados até o momento: '
        );

        $this->SetFont('Arial','',10);

        $this->Write(
            8,
            count($aprovados)
        );
    }
}


// =============================================================
// EXECUÇÃO
// =============================================================

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

    /*
     * Remove vagas sem data válida.
     */
    $vagas = array_filter(
        $vagas,
        function($vaga){

            return !empty($vaga['data'])
                && $vaga['data'] != '0000-00-00';
        }
    );

    $pdf = new RelatorioAlunosStatus($vagas);

    $pdf->AliasNbPages();

    $pdf->AddPage();

    if(empty($vagas)){

        $pdf->SetFont('Arial','B',12);

        $pdf->Cell(
            0,
            20,
            'Nenhuma vaga encontrada.',
            0,
            1,
            'C'
        );

    } else {

        $pdf->gerarRelatorio();
    }

    ob_clean();

    $pdf->Output(
        'relatorio_alunos_status.pdf',
        'I'
    );

} catch(Exception $e){

    echo 'Erro ao gerar PDF: '.$e->getMessage();
}

?>