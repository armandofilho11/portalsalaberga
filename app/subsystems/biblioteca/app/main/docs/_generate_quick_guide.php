<?php
require_once __DIR__ . '/../assets/fpdf/fpdf.php';

function guiaTexto(string $texto): string
{
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

class GuiaBibliotecaPDF extends FPDF
{
    private string $faixaInstitucional;
    private string $logoDesenvolvimento;
    private string $logoEscola;

    public function __construct(string $faixaInstitucional, string $logoDesenvolvimento, string $logoEscola)
    {
        parent::__construct('P', 'mm', 'A4');
        $this->faixaInstitucional = $faixaInstitucional;
        $this->logoDesenvolvimento = $logoDesenvolvimento;
        $this->logoEscola = $logoEscola;
        $this->SetMargins(14, 44, 14);
        $this->SetAutoPageBreak(false);
        $this->AliasNbPages();
        $this->SetTitle(guiaTexto('Guia rápido | Sistema da Biblioteca STGM'));
        $this->SetAuthor(guiaTexto('Escola Estadual de Educação Profissional Salaberga Torquato Gomes de Matos'));
        $this->SetCreator(guiaTexto('Área de Desenvolvimento | Portal Salaberga'));
    }

    public function Header(): void
    {
        $this->SetFillColor(238, 243, 236);
        $this->Rect(0, 0, 210, 40, 'F');
        $this->Image($this->faixaInstitucional, 10, 5, 148);
        $this->Image($this->logoDesenvolvimento, 171, 7, 28);
        $this->SetDrawColor(0, 122, 51);
        $this->SetLineWidth(0.7);
        $this->Line(10, 39, 200, 39);
    }

    public function Footer(): void
    {
        $this->SetDrawColor(0, 122, 51);
        $this->SetLineWidth(0.4);
        $this->Line(14, 280, 196, 280);
        $this->SetY(283);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(90, 105, 96);
        $this->Cell(120, 5, guiaTexto('BIBLIOTECA STGM  |  GUIA RÁPIDO'), 0, 0, 'L');
        $this->SetTextColor(0, 122, 51);
        $this->Cell(62, 5, guiaTexto('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

    public function tituloPagina(string $etapa, string $titulo, string $descricao): void
    {
        $this->SetXY(14, 47);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(220, 126, 0);
        $this->Cell(182, 5, guiaTexto(mb_strtoupper($etapa, 'UTF-8')), 0, 1, 'L');

        $this->SetX(14);
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(18, 51, 40);
        $this->MultiCell(182, 9, guiaTexto($titulo), 0, 'L');

        $this->SetX(14);
        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(80, 95, 87);
        $this->MultiCell(182, 5, guiaTexto($descricao), 0, 'L');
    }

    public function passo(float $x, float $y, float $largura, float $altura, int $numero, string $titulo, string $descricao, bool $alternado = false): void
    {
        $fundo = $alternado ? [246, 249, 246] : [255, 255, 255];
        $this->SetFillColor($fundo[0], $fundo[1], $fundo[2]);
        $this->SetDrawColor(220, 229, 222);
        $this->SetLineWidth(0.25);
        $this->Rect($x, $y, $largura, $altura, 'DF');

        $this->SetFillColor(0, 122, 51);
        $this->Rect($x, $y, 2.2, $altura, 'F');
        $this->SetFillColor(238, 243, 236);
        $this->Rect($x + 5.5, $y + 5.5, 9, 9, 'F');
        $this->SetXY($x + 5.5, $y + 7.4);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(0, 122, 51);
        $this->Cell(9, 5, (string) $numero, 0, 0, 'C');

        $this->SetXY($x + 18, $y + 5);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(31, 50, 40);
        $this->MultiCell($largura - 23, 5, guiaTexto($titulo), 0, 'L');

        $this->SetX($x + 18);
        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(75, 88, 80);
        $this->MultiCell($largura - 23, 4.5, guiaTexto($descricao), 0, 'L');
    }

    public function aviso(float $x, float $y, float $largura, float $altura, string $rotulo, string $texto, bool $destaque = false): void
    {
        $fundo = $destaque ? [255, 248, 229] : [237, 246, 240];
        $cor = $destaque ? [214, 126, 0] : [0, 122, 51];
        $this->SetFillColor($fundo[0], $fundo[1], $fundo[2]);
        $this->Rect($x, $y, $largura, $altura, 'F');
        $this->SetFillColor($cor[0], $cor[1], $cor[2]);
        $this->Rect($x, $y, 2.2, $altura, 'F');

        $this->SetXY($x + 7, $y + 5);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor($cor[0], $cor[1], $cor[2]);
        $this->Cell($largura - 12, 4, guiaTexto(mb_strtoupper($rotulo, 'UTF-8')), 0, 1, 'L');

        $this->SetX($x + 7);
        $this->SetFont('Arial', '', 8.5);
        $this->SetTextColor(50, 65, 56);
        $this->MultiCell($largura - 12, 4.5, guiaTexto($texto), 0, 'L');
    }

    public function capa(): void
    {
        $this->AddPage();
        $this->SetFillColor(255, 255, 255);
        $this->Rect(0, 40, 210, 205, 'F');
        $this->SetFillColor(0, 140, 69);
        $this->Rect(0, 40, 210, 3, 'F');
        $this->SetFillColor(0, 122, 51);
        $this->Rect(0, 242, 210, 3, 'F');

        $this->Image($this->logoEscola, 65, 58, 80);

        $this->SetXY(15, 103);
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(0, 122, 51);
        $this->Cell(180, 7, guiaTexto('MATERIAL DE APOIO  |  EDIÇÃO 2026'), 0, 1, 'C');

        $this->SetX(15);
        $this->SetFont('Arial', 'B', 31);
        $this->SetTextColor(18, 51, 40);
        $this->Cell(180, 15, guiaTexto('GUIA RÁPIDO'), 0, 1, 'C');

        $this->SetX(15);
        $this->SetFont('Arial', 'B', 21);
        $this->SetTextColor(0, 122, 51);
        $this->Cell(180, 12, guiaTexto('Sistema da Biblioteca'), 0, 1, 'C');

        $this->SetXY(15, 150);
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(70, 84, 76);
        $this->MultiCell(
            170,
            6,
            guiaTexto('Um roteiro prático para organizar o acervo, cadastrar livros, atender empréstimos e consultar informações com segurança.'),
            0,
            'C'
        );

        $this->SetXY(15, 190);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(18, 51, 40);
        $this->Cell(180, 6, guiaTexto('CLASSIFICAÇÃO  •  ACERVO  •  QR CODES  •  RELATÓRIOS'), 0, 1, 'C');

        $this->SetXY(15, 258);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(18, 51, 40);
        $this->Cell(180, 7, guiaTexto('Escola Estadual de Educação Profissional'), 0, 1, 'C');
        $this->SetX(15);
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(70, 84, 76);
        $this->Cell(180, 6, guiaTexto('Salaberga Torquato Gomes de Matos  |  Portal Salaberga'), 0, 1, 'C');
    }
}

$assets = __DIR__ . '/../assets/img/';
$imagens = [
    $assets . 'cabeçalho_relatorios.jpg',
    $assets . 'logo_area_dev.png',
    $assets . 'logo_salaberga.png',
];
foreach ($imagens as $imagem) {
    if (!is_file($imagem)) {
        throw new RuntimeException('Imagem institucional não encontrada: ' . $imagem);
    }
}

$pdf = new GuiaBibliotecaPDF($imagens[0], $imagens[1], $imagens[2]);
$pdf->capa();

$pdf->AddPage();
$pdf->tituloPagina(
    'COMECE POR AQUI',
    'Conheça a navegação',
    'O menu lateral reúne as tarefas. A dashboard mostra indicadores e oferece o campo para leitura de QR Codes.'
);
$pdf->passo(14, 88, 182, 34, 1, 'Dashboard', 'A página inicial apresenta o resumo do acervo e o campo “Escaneie o QRcode aqui” para iniciar a conferência de empréstimo ou devolução.', false);
$pdf->passo(14, 126, 182, 34, 2, 'Classificação', 'Cadastre categorias, gêneros e subgêneros antes de usar esses itens na organização dos livros.', true);
$pdf->passo(14, 164, 182, 34, 3, 'Gerenciar Acervo', 'Cadastre livros, consulte títulos existentes e mantenha atualizados os dados do livro e do autor.', false);
$pdf->passo(14, 202, 182, 34, 4, 'QR Codes e Relatórios', 'Gere códigos para identificar livros e escolha relatórios para consultar acervo, empréstimos, estantes, turmas ou livros.', true);
$pdf->aviso(14, 246, 182, 27, 'DICA DE NAVEGAÇÃO', 'Use os controles junto ao menu lateral para recolhê-lo e para alternar entre os temas claro e escuro.');

$pdf->AddPage();
$pdf->tituloPagina(
    '01  |  CLASSIFICAÇÃO E CADASTRO',
    'Prepare os dados e cadastre o livro',
    'Uma classificação consistente facilita a busca e mantém o catálogo organizado.'
);
$pdf->passo(14, 88, 182, 34, 1, 'Crie a hierarquia necessária', 'Em Classificação, cadastre primeiro a categoria, depois o gênero e, se fizer sentido, o subgênero.', false);
$pdf->passo(14, 126, 182, 34, 2, 'Abra o cadastro de livro', 'No menu, acesse Gerenciar Acervo > Cadastrar Livro. Preencha os dados de identificação solicitados na primeira etapa.', true);
$pdf->passo(14, 164, 182, 34, 3, 'Informe localização e quantidade', 'Avance para a etapa seguinte e registre a localização física e a quantidade de exemplares conforme o acervo.', false);
$pdf->passo(14, 202, 182, 34, 4, 'Revise e envie', 'Confira título, autor, classificação e localização. Envie o formulário e aguarde a mensagem de confirmação.', true);
$pdf->aviso(14, 246, 182, 29, 'ANTES DE COMEÇAR', 'Se uma categoria ou gênero necessário não estiver disponível no cadastro, crie-o primeiro na seção Classificação.', true);

$pdf->AddPage();
$pdf->tituloPagina(
    '02  |  CONSULTA E MANUTENÇÃO',
    'Mantenha o acervo confiável',
    'Pesquise o registro antes de cadastrar outro livro ou alterar informações.'
);
$pdf->passo(14, 88, 182, 34, 1, 'Localize o título', 'Abra Gerenciar Acervo > Consultar Livros e utilize os campos de busca disponíveis para encontrar o registro.', false);
$pdf->passo(14, 126, 182, 34, 2, 'Edite o livro correto', 'Confira título e edição antes de alterar os dados. Salve somente depois de revisar as informações atualizadas.', true);
$pdf->passo(14, 164, 182, 34, 3, 'Atualize o autor', 'Use Editar Autor para corrigir os dados do autor, sem confundir o cadastro do autor com o registro do livro.', false);
$pdf->passo(14, 202, 182, 34, 4, 'Exclua apenas quando necessário', 'Antes de confirmar uma exclusão, verifique o título e os dados do exemplar. Em caso de dúvida, consulte a equipe responsável.', true);
$pdf->aviso(14, 246, 182, 29, 'BOA PRÁTICA', 'Evite duplicidade: pesquise primeiro e confirme cuidadosamente qualquer operação que remova ou altere registros.', true);

$pdf->AddPage();
$pdf->tituloPagina(
    '03  |  EMPRÉSTIMO E DEVOLUÇÃO',
    'Use o leitor de QR Code na dashboard',
    'A leitura do código de um livro encaminha para a etapa adequada conforme a situação do exemplar.'
);
$pdf->passo(14, 88, 182, 34, 1, 'Leia ou informe o código', 'Na dashboard, posicione o cursor no campo de QR Code e faça a leitura. Também é possível colar ou digitar o código e pressionar Enter.', false);
$pdf->passo(14, 126, 182, 34, 2, 'Confira o destino', 'Se houver empréstimo ativo para o livro, o sistema encaminha para a devolução. Caso contrário, abre o formulário de empréstimo.', true);
$pdf->passo(14, 164, 182, 34, 3, 'Revise os dados da operação', 'Confira o livro, o aluno e as datas apresentadas antes de confirmar o registro.', false);
$pdf->passo(14, 202, 182, 34, 4, 'Conclua e valide o retorno', 'Confirme a operação e aguarde a mensagem do sistema. Se algo estiver incorreto, interrompa antes de salvar e revise a seleção.', true);
$pdf->aviso(14, 246, 182, 29, 'IMPORTANTE', 'O código identifica o livro, mas a pessoa e as datas devem ser conferidas no formulário antes da confirmação.', true);

$pdf->AddPage();
$pdf->tituloPagina(
    '04  |  QR CODES E RELATÓRIOS',
    'Consulte e compartilhe informações',
    'As ferramentas de consulta ajudam a identificar exemplares e acompanhar a biblioteca sem alterar cadastros.'
);
$pdf->passo(14, 88, 182, 30, 1, 'QR Code Geral', 'Acesse QR Codes > QR Code Geral e informe os critérios solicitados na tela.', false);
$pdf->passo(14, 122, 182, 30, 2, 'QR Code Específico', 'Escolha a opção específica e preencha os dados pedidos para gerar o código desejado.', true);
$pdf->passo(14, 156, 182, 30, 3, 'QR Code do livro', 'Use a opção específica do livro para localizar e selecionar os títulos antes de gerar os códigos.', false);
$pdf->passo(14, 190, 182, 30, 4, 'Escolha o relatório', 'Em Relatórios, selecione Geral, Empréstimos, Por Estante, Por Turma ou Por Livro.', true);
$pdf->passo(14, 224, 182, 30, 5, 'Preencha os filtros e gere', 'Informe os critérios solicitados. Dependendo da opção, o resultado é aberto ou baixado como PDF.', false);
$pdf->aviso(14, 258, 182, 17, 'LEMBRETE', 'Confira os filtros antes de compartilhar ou arquivar o resultado.');

$pdf->AddPage();
$pdf->tituloPagina(
    '05  |  CONFERÊNCIA RÁPIDA',
    'Se algo não sair como esperado',
    'Antes de encerrar o atendimento, faça estas verificações simples.'
);
$pdf->passo(14, 88, 182, 34, 1, 'Livro não aparece na busca', 'Verifique os termos pesquisados e confirme se o cadastro foi concluído. Se necessário, consulte a equipe antes de criar um novo registro.', false);
$pdf->passo(14, 126, 182, 34, 2, 'Classificação indisponível', 'Volte à seção Classificação, cadastre o item necessário e então retome o cadastro do livro.', true);
$pdf->passo(14, 164, 182, 34, 3, 'QR Code não encaminha', 'Confira se o código corresponde a um livro cadastrado. Tente novamente no campo da dashboard e valide o livro apresentado.', false);
$pdf->passo(14, 202, 182, 34, 4, 'Não recebeu confirmação', 'Verifique a mensagem exibida e os dados preenchidos. Não repita a operação até confirmar se o registro anterior foi concluído.', true);
$pdf->aviso(14, 246, 182, 29, 'CHECKLIST DE ENCERRAMENTO', 'Livro e aluno corretos • datas conferidas • confirmação exibida • relatório e filtros adequados ao objetivo.');

$pdf->Output('F', __DIR__ . '/Guia_rapido_biblioteca.pdf');
