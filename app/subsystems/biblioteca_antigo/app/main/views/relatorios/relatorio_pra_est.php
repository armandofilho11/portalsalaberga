<?php
require_once('../../assets/fpdf/fpdf.php');
require_once('../../config/connect.php');
if (isset($_GET['estante']) && isset($_GET['prateleira'])) {
    $estante = $_GET['estante'];
    $prateleira = $_GET['prateleira'];
} else {
    header('Location: relatorios.php');
    exit();
}

class PDF extends connect
{
    public function __construct($estante, $prateleira)
    {
        parent::__construct();
        $this->Header();
        $this->main($estante, $prateleira);
        $this->Footer();
    }
    public function Header()
    {
        $fpdf = new FPDF("L", "pt", "A4");
        if ($fpdf->PageNo() == 1) {
            $fpdf->Image('../../assets/img/logo.png', 20, 14, 50);
            $fpdf->Ln(20);

            $fpdf->SetX(20);
            $fpdf->SetFont('Arial', 'B', 20);
            $fpdf->SetTextColor(0, 122, 51);
            $fpdf->Cell($fpdf->GetPageWidth() - 40, 20, utf8_decode('RELATÓRIO GERAL DE ACERVO'), 0, 1, 'C');

            $fpdf->SetX(20);
            $fpdf->SetFont('Arial', 'B', 14);
            $fpdf->SetTextColor(255, 165, 0);
            $fpdf->Cell($fpdf->GetPageWidth() - 40, 20, "BIBLIOTECA STGM", 0, 1, 'C');

            $fpdf->SetX(20);
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetTextColor(0, 122, 51);
            // Alinhar Estante e Prateleira lado a lado
            $estante = 'Estante: ' . $_GET['estante'];
            $prateleira = 'Prateleira: ' . $_GET['prateleira'];
            $texto = $estante . '    |    ' . $prateleira;
            $fpdf->Cell($fpdf->GetPageWidth() - 38, 20, $texto, 0, 1, 'R');

            $pageWidth = $fpdf->GetPageWidth() - 160;
            $texto = utf8_decode('*ENI: Edição Não Informada');
            $textoLargura = $fpdf->GetStringWidth($texto);
            $colunaQtdLargura = $pageWidth * 0.08;
            $posX = $pageWidth - $colunaQtdLargura;

            $fpdf->SetX($posX);
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetTextColor(0, 122, 51);
            $fpdf->Cell(195, 10, $texto, 0, 1, 'R');

            $fpdf->Ln(10);
        }
        return $fpdf;
    }

    public function main($estante, $prateleira)
    {

        $fpdf = $this->Header();
        $fpdf->AliasNbPages();
        $fpdf->AddPage();
        $acervo = $this->connect->prepare("SELECT 
        c.id,
        c.titulo_livro, 
        a.nome_autor,
        a.sobrenome_autor,
        c.edicao,
        c.editora, 
        c.quantidade,
        g.generos,
        sg.subgenero
        FROM catalogo c
        INNER JOIN livros_autores l ON c.id = l.id_livro
        INNER JOIN autores a ON l.id_autor = a.id
        LEFT JOIN genero g ON c.id_genero = g.id
        LEFT JOIN subgenero sg ON c.id_subgenero = sg.id
        WHERE c.estantes = :estantes 
        AND c.prateleiras = :prateleiras
        ORDER BY c.titulo_livro, c.edicao");
        $acervo->bindParam(':estantes', $estante);
        $acervo->bindParam(':prateleiras', $prateleira);
        $acervo->execute();
        $result = $acervo->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar os dados por livro, edição e editora, consolidando os autores
        $livros = [];
        foreach ($result as $row) {
            $chave = $row['titulo_livro'] . '|' . ($row['edicao'] ?? 'ENI*') . '|' . ($row['editora'] ?? 'N/A');
            if (!isset($livros[$chave])) {
                $livros[$chave] = [
                    'id' => $row['id'],
                    'titulo_livro' => $row['titulo_livro'],
                    'edicao' => $row['edicao'],
                    'editora' => $row['editora'],
                    'quantidade' => $row['quantidade'],
                    'generos' => $row['generos'],
                    'subgenero' => $row['subgenero'],
                    'autores' => []
                ];
            }
            $livros[$chave]['autores'][] = [
                'nome_autor' => $row['nome_autor'],
                'sobrenome_autor' => $row['sobrenome_autor']
            ];
        }

        $pageWidth = $fpdf->GetPageWidth() - 40;
        $colunas = array(
            array('largura' => $pageWidth * 0.35, 'texto' => utf8_decode('TÍTULO')),
            array('largura' => $pageWidth * 0.25, 'texto' => 'AUTOR'),
            array('largura' => $pageWidth * 0.12, 'texto' => utf8_decode('GÊNERO')),
            array('largura' => $pageWidth * 0.12, 'texto' => utf8_decode('SUBGÊNERO')),
            array('largura' => $pageWidth * 0.08, 'texto' => utf8_decode('EDIÇÃO')),
            array('largura' => $pageWidth * 0.08, 'texto' => 'LIVRO')
        );

        // Calcular o total de livros antes de exibir
        $totalLivros = 0;
        foreach ($livros as $livro) {
            $totalLivros += (int)($livro['quantidade'] ?? 1);
        }

        // Adicionar ITENS EM ACERVO
        $fpdf->SetX(20);
        $fpdf->SetTextColor(0, 122, 51);
        $fpdf->SetFont('Arial', 'B', 12);
        $fpdf->Cell($pageWidth / 2, 10, 'ITENS EM ACERVO: ' . $totalLivros, 0, 1, 'L');
        $fpdf->Ln(10);

        // Cabeçalho das colunas
        $fpdf->SetX(20);
        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetFillColor(0, 122, 51);
        $fpdf->SetTextColor(255, 255, 255);

        foreach ($colunas as $coluna) {
            $fpdf->Cell($coluna['largura'], 20, $coluna['texto'], 1, 0, 'C', true);
        }
        $fpdf->Ln();

        // Listagem dos livros
        $fpdf->SetFont('Arial', '', 9);
        $rowCounter = 0; // Contador para alternar cores
        foreach ($livros as $livro) {
            for ($i = 1; $i <= $livro['quantidade']; $i++) {
                $fpdf->SetX(20); // Garantir que cada linha comece na mesma posição
                // Alternar cores: cinza claro (220, 220, 220) para par, branco (255, 255, 255) para ímpar
                $cor = ($rowCounter % 2 == 0) ? 255 : 220; // Branco para par, cinza para ímpar
                $fpdf->SetFillColor($cor, $cor, $cor); // Define a cor de fundo da célula
                $fpdf->SetTextColor(0, 0, 0); // Texto preto para legibilidade

                $titulo = utf8_decode(mb_strtoupper($livro['titulo_livro'], 'UTF-8'));
                $genero = utf8_decode(mb_strtoupper($livro['generos'] ?? 'N/A', 'UTF-8'));
                $subgenero = utf8_decode(mb_strtoupper($livro['subgenero'] ?? 'N/A', 'UTF-8'));
                $edicao = empty($livro['edicao']) ? utf8_decode('ENI*') : utf8_decode(mb_strtoupper($livro['edicao'], 'UTF-8'));
                $quantidade = $livro['quantidade'] ?? 1;

                $alturaLinha = 20;

                $autores = [];
                foreach ($livro['autores'] as $autor) {
                    $nomeAutor = utf8_decode(mb_strtoupper($autor['nome_autor'] ?? 'N/A', 'UTF-8'));
                    $sobrenome = utf8_decode(mb_strtoupper($autor['sobrenome_autor'] ?? 'N/A', 'UTF-8'));
                    $autores[] = $nomeAutor . " " . $sobrenome;
                }

                $numAutores = count($autores);
                $alturaTotal = $numAutores > 1 ? $alturaLinha * $numAutores : $alturaLinha;

                $fpdf->Cell($colunas[0]['largura'], $alturaTotal, $titulo, 1, 0, 'L', true);

                $xAntesAutor = $fpdf->GetX();
                $yAntesAutor = $fpdf->GetY();

                if ($numAutores == 1) {
                    $fpdf->Cell($colunas[1]['largura'], $alturaLinha, $autores[0], 1, 0, 'L', true);
                } else {
                    $fpdf->MultiCell($colunas[1]['largura'], $alturaLinha, implode("\n", $autores), 1, 'L', true);
                    $fpdf->SetXY($xAntesAutor + $colunas[1]['largura'], $yAntesAutor);
                }

                $fpdf->Cell($colunas[2]['largura'], $alturaTotal, $genero, 1, 0, 'L', true);
                $fpdf->Cell($colunas[3]['largura'], $alturaTotal, $subgenero, 1, 0, 'L', true);
                $fpdf->Cell($colunas[4]['largura'], $alturaTotal, $edicao, 1, 0, 'C', true);
                $fpdf->Cell($colunas[5]['largura'], $alturaTotal, $i, 1, 1, 'C', true);

                $rowCounter++; // Incrementar o contador para a próxima linha
            }
        }
        $this->Footer($fpdf);
    }
    public function Footer($fpdf)
    {
        $fpdf->SetY(-15);
        $fpdf->SetFont('Arial', 'I', 8);
        $fpdf->SetTextColor(0, 122, 51);
        $fpdf->Cell(0, 10, utf8_decode('Página ') . $fpdf->PageNo() . '/{nb}', 0, 0, 'C');
        $fpdf->Output('relatorio_acervo.pdf', 'I');
    }
}
$relatorio = new PDF($estante, $prateleira);
