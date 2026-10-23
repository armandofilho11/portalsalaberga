<?php
require_once('../../assets/fpdf/fpdf.php');
require_once('../../config/connect.php');
require_once('../../assets/phpqrcode/qrlib.php');

class qrCode1 extends connect
{
    function __construct()
    {
        parent::__construct();
        $this->pdf();
    }

    public function pdf()
    {
        ob_start(); // Inicia o buffer de saída
        $pdf = new FPDF("P", "pt", "A4");
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Cabeçalho
        $pdf->Image('../../assets/img/logo_incolor.jpg', 8, 5, 60, 60, 'JPG');
        $pdf->SetY(15);
        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($pdf->GetPageWidth() - 40, 20, utf8_decode('Sistema Biblioteca STGM'), 0, 1, 'C');
        $pdf->SetY(35);
        $pdf->SetX(20);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 0, 0);

        $titulos = $_GET['titulo_livro'] ?? []; // Protege contra $_GET undefined

        // Configurações de layout
        $qr_size = 80; // Tamanho do QR code em pontos (80x80)
        $space_between = 60; // Espaço entre QR codes
        $max_per_line = 4; // Máximo de QR codes por linha
        $start_x = 30; // Posição X inicial
        $start_y = 70; // Posição Y inicial (após o cabeçalho)
        $current_x = $start_x;
        $current_y = $start_y;

        foreach ($titulos as $titulo) {
            $titulo_array = explode("_", $titulo);
            // Usar prepared statement para evitar SQL Injection
            $select_dados_livro = $this->connect->prepare("SELECT id, titulo_livro, edicao, estantes, prateleiras, quantidade, cativo FROM catalogo WHERE titulo_livro = ? AND editora = ?");
            $select_dados_livro->execute([$titulo_array[0], $titulo_array[1]]);

            $dados_livros = $select_dados_livro->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dados_livros as $cod_livro) {
                for ($i = 1; $i <= $cod_livro['quantidade']; $i++) {
                    $prateleira = $cod_livro['prateleiras'];
                    $estante = $cod_livro['estantes'];

                    // Determinar a edição para a URL
                    $edicao = ($cod_livro['edicao'] == 'ENI*' || empty($cod_livro['edicao'])) ? '0' : $cod_livro['edicao'];

                    // Dados do QR Code
                    $dados = "https://salaberga.com/salaberga/portalsalaberga/app/subsystems/biblioteca/app/main/views/emprestimo/decisao.php?id_livro=" . $cod_livro['id'] . "_" . $edicao . "_" . $i . "_" . $estante . "_" . $prateleira;

                    // Usar um nome de arquivo único para cada QR Code
                    $arquivo_qrcode = __DIR__ . "/qrcode_" . $cod_livro['id'] . "_" . $i . ".png";

                    // Gerar o QR Code
                    QRcode::png($dados, $arquivo_qrcode, QR_ECLEVEL_M, 4);

                    // Verificar se o arquivo foi criado
                    if (!file_exists($arquivo_qrcode) || !getimagesize($arquivo_qrcode)) {
                        die("Erro: QR Code não gerado para $dados");
                    }

                    // Colocar o QR code na posição atual
                    $pdf->Image($arquivo_qrcode, $current_x, $current_y, $qr_size, $qr_size);

                    // Configurar fonte e cor preta para o título
                    $pdf->SetFont('Arial', 'B', 7.5);
                    $pdf->SetTextColor(0, 0, 0); // Cor preta

                    // Primeira linha: Nome do livro
                    $nome_livro = substr(utf8_decode($cod_livro['titulo_livro']), 0, 25);
                    $pdf->SetXY($current_x, $current_y + $qr_size + 2); // 5 pontos abaixo do QR code
                    $pdf->Cell($qr_size, 10, $nome_livro, 0, 0, 'L');

                    // Segunda linha: ID, Edição, Número
                    $codigo = utf8_decode("Id: " . $cod_livro['id'] . " | Edição: " . $edicao . " | Livro: " . $i);
                    $pdf->SetXY($current_x, $current_y + $qr_size + 10); // 15 pontos abaixo do QR code
                    $pdf->Cell($qr_size, 10, $codigo, 0, 0, 'L');

                    // Terceira linha: Estante, Prateleira
                    $localizacao = utf8_decode("Estante: " . $estante . " | Prateleira: " . $prateleira);
                    $pdf->SetXY($current_x, $current_y + $qr_size + 18); // 25 pontos abaixo do QR code
                    $pdf->Cell($qr_size, 10, $localizacao, 0, 0, 'L');

                    // Quarta linha: Cativo/Não cativo (ajustada para evitar sobreposição)
                    $cativo_texto = ($cod_livro['cativo'] == 0) ? utf8_decode("Não cativo") : utf8_decode("Cativo");
                    $pdf->SetXY($current_x, $current_y + $qr_size + 25); // Aumentado para 35 pontos abaixo do QR code
                    $pdf->Cell($qr_size, 10, $cativo_texto, 0, 0, 'L');

                    // Remover o arquivo temporário
                    if (file_exists($arquivo_qrcode)) {
                        unlink($arquivo_qrcode);
                    }

                    // Atualizar a posição X para o próximo QR code
                    $current_x += $qr_size + $space_between;

                    // Verificar se atingiu o limite da linha
                    if ($current_x + $qr_size > $pdf->GetPageWidth() - 20) {
                        $current_x = $start_x;
                        $current_y += $qr_size + 50; // Aumentado para 50 para acomodar a nova linha de texto
                    }

                    // Verificar se precisa de nova página
                    if ($current_y + $qr_size + 50 > $pdf->GetPageHeight() - 20) {
                        $pdf->AddPage();
                        $current_x = $start_x;
                        $current_y = 20; // Posição Y inicial na nova página
                    }
                }
            }
        }

        ob_end_clean(); // Limpa o buffer
        $pdf->Output('I', 'relatorio_acervo.pdf');
    }
}

if (isset($_GET['titulo_livro']) && !empty($_GET['titulo_livro'])) {
    $qrcode = new qrCode1;
} else {
    header('location:geradorQR_especifico.php');
    exit();
}
