<?php
require_once('../models/select_model.php');
require_once('../assets/fpdf/fpdf.php');
require_once('../models/sessions.php');
// Configura o fuso horário para São Paulo
date_default_timezone_set('America/Sao_Paulo');

// Verifica se o usuário está autenticado
$sessions = new sessions();
$sessions->autenticar_session();

// Classe FPDF com suporte a UTF-8
class PDF extends FPDF {
    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        $txt = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $txt);
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }
}

try {
    class ModernPDF extends PDF {
        // Configurações de cores
        private $cores = [
            'primaria' => [0, 122, 51],    // Verde institucional
            'secundaria' => [240, 240, 240], // Cinza claro
            'destaque' => [0, 90, 40],     // Verde escuro
            'texto' => [70, 70, 70],       // Cinza escuro
            'subtitulo' => [100, 100, 100]  // Cinza médio
        ];

        private $select_model;
        private $vagas;
        private $dados_resumo;
        private $alunos_destaque;

        // Configurações de página
        function __construct($select_model, $vagas) {
            parent::__construct('P', 'mm', 'A4');
            $this->SetAutoPageBreak(true, 25);
            $this->SetMargins(10, 15, 10);
            $this->select_model = $select_model;
            $this->vagas = $vagas;
            $this->processarDados();
            // Configura fonte padrão
            $this->SetFont('Arial', '', 10);
        }

        private function processarDados() {
            $total_alunos_selecionados = 0;
            $total_alunos_espera = 0;
            $this->alunos_destaque = [];
            
            foreach ($this->vagas as $vaga) {
                // Busca alunos selecionados para esta vaga
                $alunos_selecionados = $this->select_model->alunos_selecionados($vaga['id']);
                
                // Busca alunos em espera para esta vaga
                $alunos_espera = $this->select_model->alunos_espera($vaga['id']);
                
                // Adiciona alunos selecionados
                foreach ($alunos_selecionados as $aluno) {
                    $this->alunos_destaque[] = [
                        'nome' => $aluno['nome'],
                        'empresa' => $vaga['nome_empresa'],
                        'perfil' => $vaga['nome_perfil'],
                        'status' => 'Selecionado'
                    ];
                    $total_alunos_selecionados++;
                }
                
                // Adiciona alunos em espera
                foreach ($alunos_espera as $aluno) {
                    $this->alunos_destaque[] = [
                        'nome' => $aluno['nome'],
                        'empresa' => $vaga['nome_empresa'],
                        'perfil' => $vaga['nome_perfil'],
                        'status' => 'Em Espera'
                    ];
                    $total_alunos_espera++;
                }
            }
            
            $this->dados_resumo = [
                'total_vagas' => array_sum(array_column($this->vagas, 'quantidade')),
                'total_empresas' => count(array_unique(array_column($this->vagas, 'nome_empresa'))),
                'total_alunos_selecionados' => $total_alunos_selecionados,
                'total_alunos_espera' => $total_alunos_espera
            ];
        }

        // Funções auxiliares
        function ajustarTexto($texto, $largura_celula) {
            // Remove caracteres do final até que o texto caiba na largura da célula
            while ($this->GetStringWidth($texto) > $largura_celula) {
                $texto = mb_substr($texto, 0, -1, 'UTF-8');
            }
            return $texto;
        }

        function formatarNome($nome) {
            // Converte todo o nome para minúsculo
            $nome = mb_strtolower($nome, 'UTF-8');
            // Divide o nome em palavras
            $palavras = explode(' ', $nome);
            // Capitaliza a primeira letra de cada palavra
            $palavras = array_map(function($palavra) {
                return ucfirst($palavra);
            }, $palavras);
            // Junta as palavras novamente
            return implode(' ', $palavras);
        }

        function formatarEmpresa($empresa) {
            return mb_strtoupper($empresa, 'UTF-8');
        }

        function formatarData($data) {
            if (empty($data)) return '-';
            $data_obj = new DateTime($data);
            return $data_obj->format('d/m/Y');
        }

        // Cabeçalho
        function Header() {
            if ($this->PageNo() == 1) {
                $this->SetFillColor(248, 248, 248);
                $this->Rect(0, 0, $this->GetPageWidth(), 40, 'F');
                
                // Use ImagePngWithAlpha para PNGs com transparência
                $this->ImagePngWithAlpha('https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png', 15, 10, 25);
                
                $this->SetFont('Arial', 'B', 18);
                $this->SetTextColor(...$this->cores['primaria']);
                $this->SetXY(45, 15);
                $this->Cell(100, 10, 'Relatório de seleção', 0, 0, 'L');
                
                $this->SetFont('Arial', 'I', 9);
                $this->SetTextColor(...$this->cores['subtitulo']);
                $this->SetXY(45, 25);
                $this->Cell(100, 5, 'Gerado em: ' . date('d/m/Y H:i'), 0, 0, 'L');
                
                $this->SetDrawColor(...$this->cores['primaria']);
                $this->SetLineWidth(0.5);
                $this->Line(15, 40, 195, 40);
                
                $this->SetY(45);
            } else {
                $this->SetY(15);
            }
        }

        // Rodapé
        function Footer() {
            $this->SetDrawColor(...$this->cores['primaria']);
            $this->SetLineWidth(0.3);
            $this->Line(15, $this->GetPageHeight() - 20, $this->GetPageWidth() - 15, $this->GetPageHeight() - 20);
            
            $this->SetY(-18);
            $this->SetFont('Arial', 'I', 8);
            $this->SetTextColor(...$this->cores['subtitulo']);
            $this->Cell(0, 5, 'Página ' . $this->PageNo() . '/{nb}', 0, 1, 'C');
            $this->Cell(0, 5, 'Sistema de Gestão de Vagas - Todos os direitos reservados', 0, 0, 'C');
        }

        // Tabela de Vagas
        function addTabelaVagas() {
            $this->SetY(45);
            $this->SetFont('Arial', 'B', 12);
            $this->SetTextColor(...$this->cores['primaria']);
            $this->Cell(0, 10, 'Vagas Disponíveis', 0, 1, 'L');
            
            // Cabeçalho da tabela
            $this->SetFillColor(220, 240, 230);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(55, 7, 'Empresa', 1, 0, 'L', true);
            $this->Cell(30, 7, 'Perfil', 1, 0, 'L', true);
            $this->Cell(35, 7, 'Data | Hora', 1, 0, 'C', true);
            $this->Cell(70, 7, 'Alunos', 1, 1, 'C', true);
            
            // Dados da tabela
            $this->SetFont('Arial', '', 10);
            $this->SetTextColor(40, 40, 40);
            
            $row_bg1 = [255, 255, 255];
            $row_bg2 = [245, 250, 245];
            $contador_linhas = 0;
            
            if (empty($this->vagas)) {
                $bg = $row_bg1;
                $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                $this->Cell(55, 7, '-', 1, 0, 'C', true);
                $this->Cell(30, 7, '-', 1, 0, 'C', true);
                $this->Cell(35, 7, '-', 1, 0, 'C', true);
                $this->Cell(70, 7, '-', 1, 1, 'C', true);
            } else {
                foreach ($this->vagas as $vaga) {
                    // Busca alunos selecionados e em espera para esta vaga
                    $alunos_selecionados = $this->select_model->alunos_selecionados($vaga['id']);
                    $alunos_espera = $this->select_model->alunos_espera($vaga['id']);
                    
                    $data_hora = ($this->formatarData($vaga['data'])) . ' | ' . ($vaga['hora'] ?? '-');
                    if ($data_hora === '- | -') $data_hora = '-';

                    // Se não houver alunos, mostra uma linha com traço
                    if (empty($alunos_selecionados) && empty($alunos_espera)) {
                        $bg = ($contador_linhas % 2 == 0) ? $row_bg1 : $row_bg2;
                        $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                        $this->Cell(55, 7, $this->ajustarTexto($this->formatarEmpresa($vaga['nome_empresa'] ?? '-'), 55), 1, 0, 'L', true);
                        $this->Cell(30, 7, $this->ajustarTexto($vaga['nome_perfil'] ?? '-', 30), 1, 0, 'L', true);
                        $this->Cell(35, 7, $data_hora, 1, 0, 'C', true);
                        $this->Cell(70, 7, '-', 1, 1, 'C', true);
                        $contador_linhas++;
                    } else {
                        // Lista alunos selecionados
                        foreach ($alunos_selecionados as $aluno) {
                            $bg = ($contador_linhas % 2 == 0) ? $row_bg1 : $row_bg2;
                            $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                            $this->Cell(55, 7, $this->ajustarTexto($this->formatarEmpresa($vaga['nome_empresa'] ?? '-'), 55), 1, 0, 'L', true);
                            $this->Cell(30, 7, $this->ajustarTexto($vaga['nome_perfil'] ?? '-', 30), 1, 0, 'L', true);
                            $this->Cell(35, 7, $data_hora, 1, 0, 'C', true);
                            
                            // Apenas o texto do aluno selecionado fica verde e negrito, fundo segue a alternância
                            $this->SetTextColor(0, 122, 51); // Verde para selecionados
                            $this->SetFont('Arial', 'B', 10); // Negrito para selecionados
                            $this->Cell(70, 7, $this->ajustarTexto($this->formatarNome($aluno['nome']), 70), 1, 1, 'L', true);
                            $this->SetTextColor(40, 40, 40);
                            $this->SetFont('Arial', '', 10);
                            $contador_linhas++;
                        }
                        
                        // Lista alunos em espera
                        foreach ($alunos_espera as $aluno) {
                            $bg = ($contador_linhas % 2 == 0) ? $row_bg1 : $row_bg2;
                            $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                            $this->Cell(55, 7, $this->ajustarTexto($this->formatarEmpresa($vaga['nome_empresa'] ?? '-'), 55), 1, 0, 'L', true);
                            $this->Cell(30, 7, $this->ajustarTexto($vaga['nome_perfil'] ?? '-', 30), 1, 0, 'L', true);
                            $this->Cell(35, 7, $data_hora, 1, 0, 'C', true);
                            $this->SetTextColor(100, 100, 100); // Cinza para em espera
                            $this->Cell(70, 7, $this->ajustarTexto($this->formatarNome($aluno['nome']), 70), 1, 1, 'L', true);
                            $this->SetTextColor(40, 40, 40); // Volta para cor padrão
                            $contador_linhas++;
                        }
                    }
                }
            }
        }

        // Tabela de Alunos
        function addTabelaAlunos() {
            $this->Ln(10);
            $this->SetFont('Arial', 'B', 12);
            $this->SetTextColor(...$this->cores['primaria']);
            $this->Cell(0, 10, 'Alunos Selecionados', 0, 1, 'L');
            
            // Cabeçalho da tabela
            $this->SetFillColor(220, 240, 230);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(80, 7, 'Empresa', 1, 0, 'C', true);
            $this->Cell(30, 7, 'Perfil', 1, 0, 'C', true);
            $this->Cell(70, 7, 'Aluno Selecionado', 1, 1, 'C', true);
            
            // Dados da tabela
            $this->SetFont('Arial', '', 10);
            $this->SetTextColor(40, 40, 40);
            
            $row_bg1 = [255, 255, 255];
            $row_bg2 = [245, 250, 245];
            $fill = false;
            
            // Filtra apenas alunos selecionados
            $alunos_selecionados = array_filter($this->alunos_destaque, function($aluno) {
                return $aluno['status'] === 'Selecionado';
            });
            
            if (empty($alunos_selecionados)) {
                $bg = $row_bg1;
                $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                $this->Cell(80, 7, '-', 1, 0, 'C', true);
                $this->Cell(30, 7, '-', 1, 0, 'C', true);
                $this->Cell(70, 7, '-', 1, 1, 'C', true);
            } else {
                foreach ($alunos_selecionados as $aluno) {
                    $bg = $fill ? $row_bg2 : $row_bg1;
                    $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                    
                    $this->Cell(80, 7, $this->ajustarTexto($aluno['empresa'] ?? '-', 80), 1, 0, 'L', true);
                    $this->Cell(30, 7, $this->ajustarTexto($aluno['perfil'] ?? '-', 30), 1, 0, 'L', true);
                    $this->Cell(70, 7, $this->ajustarTexto($aluno['nome'] ?? '-', 70), 1, 1, 'L', true);
                    
                    $fill = !$fill;
                }
            }
        }

        // Tabela de Alunos Não Selecionados
        function addTabelaAlunosNaoSelecionados() {
            $this->Ln(10);
            $this->SetFont('Arial', 'B', 12);
            $this->SetTextColor(...$this->cores['primaria']);
            $this->Cell(0, 10, utf8_decode('Alunos em Espera'), 0, 1, 'L');
            
            // Cabeçalho da tabela
            $this->SetFillColor(220, 240, 230);
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(80, 7, utf8_decode('Empresa'), 1, 0, 'C', true);
            $this->Cell(30, 7, utf8_decode('Perfil'), 1, 0, 'C', true);
            $this->Cell(70, 7, utf8_decode('Aluno em Espera'), 1, 1, 'C', true);
            
            // Dados da tabela
            $this->SetFont('Arial', '', 10);
            $this->SetTextColor(40, 40, 40);
            
            $row_bg1 = [255, 255, 255];
            $row_bg2 = [245, 250, 245];
            $fill = false;
            
            // Filtra apenas alunos em espera
            $alunos_nao_selecionados = array_filter($this->alunos_destaque, function($aluno) {
                return $aluno['status'] === 'Em Espera';
            });
            
            if (empty($alunos_nao_selecionados)) {
                $bg = $row_bg1;
                $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                $this->Cell(80, 7, '-', 1, 0, 'C', true);
                $this->Cell(30, 7, '-', 1, 0, 'C', true);
                $this->Cell(70, 7, '-', 1, 1, 'C', true);
            } else {
                foreach ($alunos_nao_selecionados as $aluno) {
                    $bg = $fill ? $row_bg2 : $row_bg1;
                    $this->SetFillColor($bg[0], $bg[1], $bg[2]);
                    
                    $this->Cell(80, 7, utf8_decode($this->ajustarTexto($aluno['empresa'] ?? '-', 80)), 1, 0, 'L', true);
                    $this->Cell(30, 7, utf8_decode($this->ajustarTexto($aluno['perfil'] ?? '-', 30)), 1, 0, 'L', true);
                    $this->Cell(70, 7, utf8_decode($this->ajustarTexto($aluno['nome'] ?? '-', 70)), 1, 1, 'L', true);
                    
                    $fill = !$fill;
                }
            }
        }

        function gerarRelatorio() {
            $this->addTabelaVagas();
        }

        // Funções para suporte a transparência de PNG (copiado do script FPDF alpha channel)
        var $tmpFiles = array(); 

        function Image($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='', $isMask=false, $maskImg=0)
        {
            //Put an image on the page
            if(!isset($this->images[$file]))
            {
                //First use of this image, get info
                if($type=='')
                {
                    $pos=strrpos($file,'.');
                    if(!$pos)
                        $this->Error('Image file has no extension and no type was specified: '.$file);
                    $type=substr($file,$pos+1);
                }
                $type=strtolower($type);
                if($type=='png'){
                    $info=$this->_parsepng($file);
                    if($info=='alpha')
                        return $this->ImagePngWithAlpha($file,$x,$y,$w,$h,$link);
                }
                else
                {
                    if($type=='jpeg')
                        $type='jpg';
                    $mtd='_parse'.$type;
                    if(!method_exists($this,$mtd))
                        $this->Error('Unsupported image type: '.$type);
                    $info=$this->$mtd($file);
                }
                if($isMask){
                    if(in_array($file,$this->tmpFiles))
                        $info['cs']='DeviceGray'; //hack necessary as GD can't produce gray scale images
                    if($info['cs']!='DeviceGray')
                        $this->Error('Mask must be a gray scale image');
                    if($this->PDFVersion<'1.4')
                        $this->PDFVersion='1.4';
                }
                $info['i']=count($this->images)+1;
                if($maskImg>0)
                    $info['masked'] = $maskImg;
                $this->images[$file]=$info;
            }
            else
                $info=$this->images[$file];
            //Automatic width and height calculation if needed
            if($w==0 && $h==0)
            {
                //Put image at 72 dpi
                $w=$info['w']/$this->k;
                $h=$info['h']/$this->k;
            }
            elseif($w==0)
                $w=$h*$info['w']/$info['h'];
            elseif($h==0)
                $h=$w*$info['h']/$info['w'];
            //Flowing mode
            if($y===null)
            {
                if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
                {
                    //Automatic page break
                    $x2=$this->x;
                    $this->AddPage($this->CurOrientation);
                    $this->x=$x2;
                }
                $y=$this->y;
                $this->y+=$h;
            }
            if($x===null)
                $x=$this->x;
            if(!$isMask)
                $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
            if($link)
                $this->Link($x,$y,$w,$h,$link);
            return $info['i'];
        }

        function ImagePngWithAlpha($file,$x,$y,$w=0,$h=0,$link='')
        {
            $tmp_alpha = tempnam(sys_get_temp_dir(), 'mska');
            $this->tmpFiles[] = $tmp_alpha;
            $tmp_plain = tempnam(sys_get_temp_dir(), 'mskp');
            $this->tmpFiles[] = $tmp_plain;

            list($wpx, $hpx) = getimagesize($file);
            $img = imagecreatefrompng($file);
            $alpha_img = imagecreate( $wpx, $hpx );

            // generate gray scale pallete
            for($c=0;$c<256;$c++)
                ImageColorAllocate($alpha_img, $c, $c, $c);

            // extract alpha channel
            $xpx=0;
            while ($xpx<$wpx){
                $ypx = 0;
                while ($ypx<$hpx){
                    $color_index = imagecolorat($img, $xpx, $ypx);
                    $col = imagecolorsforindex($img, $color_index);
                    imagesetpixel($alpha_img, $xpx, $ypx, $this->_gamma( (127-$col['alpha'])*255/127) );
                    ++$ypx;
                }
                ++$xpx;
            }

            imagepng($alpha_img, $tmp_alpha);
            imagedestroy($alpha_img);

            // extract image without alpha channel
            $plain_img = imagecreatetruecolor ( $wpx, $hpx );
            imagecopy($plain_img, $img, 0, 0, 0, 0, $wpx, $hpx );
            imagepng($plain_img, $tmp_plain);
            imagedestroy($plain_img);
            
            //first embed mask image (w, h, x, will be ignored)
            $maskImg = $this->Image($tmp_alpha, 0,0,0,0, 'PNG', '', true); 
            
            //embed image, masked with previously embedded mask
            $this->Image($tmp_plain,$x,$y,$w,$h,'PNG',$link, false, $maskImg);
        }

        function Close()
        {
            parent::Close();
            // clean up tmp files
            foreach($this->tmpFiles as $tmp)
                @unlink($tmp);
        }

        function _putimages()
        {
            $filter=($this->compress) ? '/Filter /FlateDecode ' : '';
            
            foreach ($this->images as $file => $info)
            {
                $this->_newobj();
                $this->images[$file]['n']=$this->n;
                $this->_out('<</Type /XObject');
                $this->_out('/Subtype /Image');
                $this->_out('/Width '.$info['w']);
                $this->_out('/Height '.$info['h']);

                if(isset($info['masked']))
                    $this->_out('/SMask '.($this->n-1).' 0 R');

                if($info['cs']=='Indexed')
                    $this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
                else
                {
                    $this->_out('/ColorSpace /'.$info['cs']);
                    if($info['cs']=='DeviceCMYK')
                        $this->_out('/Decode [1 0 1 0 1 0 1 0]');
                }
                $this->_out('/BitsPerComponent '.$info['bpc']);
                if(isset($info['f']))
                    $this->_out('/Filter /'.$info['f']);
                if(isset($info['parms']))
                    $this->_out($info['parms']);
                if(isset($info['trns']) && is_array($info['trns']))
                {
                    $trns='';
                    for($i=0;$i<count($info['trns']);$i++)
                        $trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
                    $this->_out('/Mask ['.$trns.']');
                }
                $this->_out('/Length '.strlen($info['data']).'>>');
                $this->_putstream($info['data']);
                unset($this->images[$file]['data']);
                $this->_out('endobj');
                //Palette
                if($info['cs']=='Indexed')
                {
                    $this->_newobj();
                    $pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
                    $this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
                    $this->_putstream($pal);
                    $this->_out('endobj');
                }
            }
        }

        function _gamma($v){
            return pow ($v/255, 2.2) * 255;
        }

        function _parsepng($file)
        {
            //Extract info from a PNG file
            $f=fopen($file,'rb');
            if(!$f)
                $this->Error('Can\'t open image file: '.$file);
            //Check signature
            if($this->_readstream($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
                $this->Error('Not a PNG file: '.$file);
            //Read header chunk
            $this->_readstream($f,4);
            if($this->_readstream($f,4)!='IHDR')
                $this->Error('Incorrect PNG file: '.$file);
            $w=$this->_readint($f);
            $h=$this->_readint($f);
            $bpc=ord($this->_readstream($f,1));
            if($bpc>8)
                $this->Error('16-bit depth not supported: '.$file);
            $ct=ord($this->_readstream($f,1));
            if($ct==0)
                $colspace='DeviceGray';
            elseif($ct==2)
                $colspace='DeviceRGB';
            elseif($ct==3)
                $colspace='Indexed';
            else {
                fclose($f);      // the only changes are 
                return 'alpha';  // made in those 2 lines
            }
            if(ord($this->_readstream($f,1))!=0)
                $this->Error('Unknown compression method: '.$file);
            if(ord($this->_readstream($f,1))!=0)
                $this->Error('Unknown filter method: '.$file);
            if(ord($this->_readstream($f,1))!=0)
                $this->Error('Interlacing not supported: '.$file);
            $this->_readstream($f,4);
            $parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
            //Scan chunks looking for palette, transparency and image data
            $pal='';
            $trns='';
            $data='';
            do
            {
                $n=$this->_readint($f);
                $type=$this->_readstream($f,4);
                if($type=='PLTE')
                {
                    //Read palette
                    $pal=$this->_readstream($f,$n);
                    $this->_readstream($f,4);
                }
                elseif($type=='tRNS')
                {
                    //Read transparency info
                    $t=$this->_readstream($f,$n);
                    if($ct==0)
                        $trns=array(ord(substr($t,1,1)));
                    elseif($ct==2)
                        $trns=array(ord(substr($t,3,1)), ord(substr($t,5,1)));
                    else
                    {
                        $pos=strpos($t,chr(0));
                        if($pos!==false)
                            $trns=array($pos);
                    }
                    $this->_readstream($f,4);
                }
                elseif($type=='IDAT')
                {
                    //Read image data block
                    $data.=$this->_readstream($f,$n);
                    $this->_readstream($f,4);
                }
                elseif($type=='IEND')
                    break;
                else
                    $this->_readstream($f,$n+4);
            }
            while($n);
            if($colspace=='Indexed' && empty($pal))
                $this->Error('Missing palette in '.$file);
            fclose($f);
            return array('w'=>$w, 'h'=>$h, 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'FlateDecode', 'parms'=>$parms, 'pal'=>$pal, 'trns'=>$trns, 'data'=>$data);
        }
    }

    // Obtém os parâmetros
    $empresa_id = isset($_GET['empresa']) && !empty($_GET['empresa']) ? intval($_GET['empresa']) : '';
    $perfil_id = isset($_GET['perfil']) && !empty($_GET['perfil']) ? intval($_GET['perfil']) : '';
    
    // Inicializa o modelo
    $select_model = new select_model();
    
    // Obtém as vagas com os filtros
    $vagas = $select_model->vagas('', $perfil_id, $empresa_id);
    
    // Verifica se existem vagas
    if (empty($vagas)) {
        $pdf = new ModernPDF($select_model, []);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 122, 51);
        $pdf->Cell(0, 20, 'Nenhuma vaga encontrada com os filtros selecionados.', 0, 1, 'C');
        $pdf->Output('relatorio_vagas.pdf', 'I');
        exit;
    }
    
    // Inicializa o PDF e gera o relatório
    $pdf = new ModernPDF($select_model, $vagas);
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->gerarRelatorio();
    $pdf->Output('relatorio_vagas.pdf', 'I');
    exit;

} catch (Exception $e) {
    error_log('Erro no relatório de vagas: ' . $e->getMessage());
    header('Content-Type: text/html; charset=utf-8');
    echo '<div style="color: red; padding: 20px; font-family: Arial, sans-serif;">';
    echo '<h2>Erro ao gerar relatório</h2>';
    echo '<p>Ocorreu um erro ao tentar gerar o relatório. Por favor, tente novamente mais tarde.</p>';
    echo '<p>Detalhes do erro: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="javascript:history.back()">Voltar</a></p>';
    echo '</div>';
}
?>