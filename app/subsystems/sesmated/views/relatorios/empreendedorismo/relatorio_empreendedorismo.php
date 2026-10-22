<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../../config/connect.php');
require_once('../../../assets/fpdf/fpdf.php');
require_once('../../../../../main/models/sessions.php');
$session = new sessions();
$session->autenticar_session();
class PDF extends connect
{
    protected $connect;
    public function __construct()
    {
        parent::__construct();
        $this->main();
    }

    public function main()
    {
        $fpdf = new FPDF('P', 'pt', 'A4');
        $fpdf->AliasNbPages();
        $db = $this->connect;
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        // Espaço do topo para não sobrepor o logotipo
        $fpdf->SetY(140);

        // 1. Tabela: Curso - Valor Declarado
        $queryDeclarado = "
            SELECT c.nome_curso, SUM(p.valor_unitario * p.quantidade) as valor_declarado
            FROM cursos c
            INNER JOIN produtos p ON p.curso_id = c.curso_id
            GROUP BY c.nome_curso
            ORDER BY valor_declarado DESC;
        ";
        $stmt = $db->query($queryDeclarado);
        $declarados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Montar um array associativo para buscar o valor declarado por curso
        $valoresDeclarados = [];
        foreach ($declarados as $row) {
            $valoresDeclarados[$row['nome_curso']] = $row['valor_declarado'];
        }

        // 2. Tabela: Curso - Valor Arrecadado (Espécie, Conta)
        $queryArrecadado = "
            SELECT c.nome_curso,
                   COALESCE(SUM(e.em_especie),0) as especie,
                   COALESCE(SUM(e.em_conta),0) as conta
            FROM cursos c
            LEFT JOIN tarefa_12_empreendedorismo e ON e.curso_id = c.curso_id
            GROUP BY c.nome_curso
            ORDER BY c.nome_curso;
        ";
        $stmt = $db->query($queryArrecadado);
        $arrecadados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Definir a ordem fixa dos cursos pelo nome desejado
        $ordemCursos = [
            'Enfermagem',
            'Informática',
            'Meio Ambiente',
            'Administração',
            'Edificações'
        ];
        // Reordenar $declarados
        $declarados_ordenados = [];
        foreach ($ordemCursos as $nome) {
            foreach ($declarados as $row) {
                if (mb_strtolower($row['nome_curso']) === mb_strtolower($nome)) {
                    $declarados_ordenados[] = $row;
                    break;
                }
            }
        }
        $declarados = $declarados_ordenados;
        // Reordenar $arrecadados
        $arrecadados_ordenados = [];
        foreach ($ordemCursos as $nome) {
            foreach ($arrecadados as $row) {
                if (mb_strtolower($row['nome_curso']) === mb_strtolower($nome)) {
                    $arrecadados_ordenados[] = $row;
                    break;
                }
            }
        }
        $arrecadados = $arrecadados_ordenados;

        // Título principal
        $fpdf->SetFont('Arial', 'B', 20);
        $fpdf->Cell(0, 24, utf8_decode('Relatório Empreendedorismo'), 0, 1, 'C');
        $fpdf->Ln(6);
        $fpdf->Ln(2);
        $fpdf->SetFont('Arial', 'B', 13);
        $fpdf->SetFillColor(230,230,230);
        $fpdf->SetTextColor(0,0,0);
        $tableWidth = 480;
        $col1 = 240; $col2 = 240;
        $xTable = ($fpdf->GetPageWidth() - $tableWidth) / 2;
        $fpdf->SetX($xTable);
        $fpdf->Cell($col1, 20, utf8_decode('Curso'), 1, 0, 'L', true);
        $fpdf->Cell($col2, 20, utf8_decode('Valor Declarado'), 1, 1, 'L', true);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetFillColor(245,245,245);
        $fill = false;
        foreach ($declarados as $row) {
            $fpdf->SetX($xTable);
            $fpdf->Cell($col1, 20, utf8_decode($row['nome_curso']), 1, 0, 'L', $fill);
            $fpdf->Cell($col2, 20, utf8_decode('R$ ') . utf8_decode(number_format($row['valor_declarado'],2,',','.')), 1, 1, 'L', $fill);
            $fill = !$fill;
        }

        // Espaço entre tabelas
        $fpdf->Ln(18);

        // Título principal
        $fpdf->SetFont('Arial', 'B', 13);
        $fpdf->SetFillColor(230,230,230);
        $fpdf->SetTextColor(0,0,0);
        $tableWidth2 = 480;
        $colA = 120; $colB = 120; $colC = 120; $colD = 120;
        $xTable2 = ($fpdf->GetPageWidth() - $tableWidth2) / 2;
        $fpdf->SetX($xTable2);
        $fpdf->Cell($colA, 20, utf8_decode('Curso'), 1, 0, 'L', true);
        $fpdf->Cell($colB, 20, utf8_decode('Espécie'), 1, 0, 'L', true);
        $fpdf->Cell($colC, 20, utf8_decode('Conta'), 1, 0, 'L', true);
        $fpdf->Cell($colD, 20, utf8_decode('Total'), 1, 1, 'L', true);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetFillColor(245,245,245);
        $fill = false;
        foreach ($arrecadados as $row) {
            $total = $row['especie'] + $row['conta'];
            $fpdf->SetX($xTable2);
            $fpdf->Cell($colA, 20, utf8_decode($row['nome_curso']), 1, 0, 'L', $fill);
            $fpdf->Cell($colB, 20, utf8_decode('R$ ') . utf8_decode(number_format($row['especie'],2,',','.')), 1, 0, 'L', $fill);
            $fpdf->Cell($colC, 20, utf8_decode('R$ ') . utf8_decode(number_format($row['conta'],2,',','.')), 1, 0, 'L', $fill);
            $fpdf->Cell($colD, 20, utf8_decode('R$ ') . utf8_decode(number_format($total,2,',','.')), 1, 1, 'L', $fill);
            $fill = !$fill;
        }

        // Espaço entre tabelas
        $fpdf->Ln(18);

        // 3. Tabela: Curso - Pontuação por Colocação
        $ranking = $arrecadados;
        foreach ($ranking as &$r) {
            $total = $r['especie'] + $r['conta'];
            $valor_declarado = $valoresDeclarados[$r['nome_curso']] ?? 0;
            $r['total'] = ($total > $valor_declarado) ? $valor_declarado : $total;
        }
        unset($r);
        usort($ranking, function($a, $b) { return $b['total'] <=> $a['total']; });
        $fpdf->Ln(2);
        $fpdf->SetFont('Arial', 'B', 13);
        $tableWidth3 = 480;
        $colCurso = 240; $colPontos = 240;
        $xTable3 = ($fpdf->GetPageWidth() - $tableWidth3) / 2;
        $fpdf->SetX($xTable3);
        $fpdf->Cell($colCurso, 20, utf8_decode('Curso'), 1, 0, 'L', true);
        $fpdf->Cell($colPontos, 20, utf8_decode('Pontuação'), 1, 1, 'L', true);
        $fpdf->SetFont('Arial', '', 12);
        $fpdf->SetFillColor(245,245,245);
        $fill = false;
        $pos = 1;
        foreach ($ranking as $row) {
            $pontos = 0;
            switch ($pos) {
                case 1: $pontos = 500; break;
                case 2: $pontos = 450; break;
                case 3: $pontos = 400; break;
                case 4: $pontos = 350; break;
                case 5: $pontos = 300; break;
                default: $pontos = 0; break;
            }
            $fpdf->SetX($xTable3);
            $fpdf->Cell($colCurso, 20, utf8_decode($row['nome_curso']), 1, 0, 'L', $fill);
            $fpdf->Cell($colPontos, 20, utf8_decode($pontos . ' pontos'), 1, 1, 'L', $fill);
            $fill = !$fill;
            $pos++;
        }

        // Adiciona nova página para o texto explicativo
        $fpdf->Ln(18);
        $fpdf->AddPage();
        $fpdf->Image('../../../assets/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
        $fpdf->SetY(150);
        $fpdf->SetFont('Arial', '', 10);
        $fpdf->SetTextColor(50, 50, 50);
        $fpdf->SetFillColor(255,255,255);
        $fpdf->SetX(40);
        $texto = "1. Coleta de Dados\nCada barraca foi orientada a informar os seguintes dados:\n- Quantidade de cada produto oferecido;\n- Valor unitário de cada produto;\n- Quantidade de produtos vendidos.\n\nCom base nesses dados, foi possível calcular:\n- Subtotal de cada produto: quantidade vendida x valor unitário;\n- Total declarado da barraca: soma dos subtotais de todos os produtos.\n\n2. Comprovação Financeira\nApós o envio do total declarado de vendas, foi solicitado o valor efetivamente arrecadado, discriminado em:\n- Valor arrecadado em espécie (dinheiro físico);\n- Valor arrecadado por meios digitais: PIX, cartão de débito ou crédito.\n\n3. Validação e Conciliação\n\nA validação dos dados financeiros seguiu o seguinte critério:\n\n   3.1. O valor declarado (baseado na quantidade de produtos vendidos) deve ser menor ou igual ao valor arrecadado.\n\n   3.2. Se o valor arrecadado for superior ao valor declarado, considera-se o valor declarado como referência para pontuação, uma vez que o que eu declarei em vendas foi validado pelo que foi arrecadado, porém, não posso garantir que a conta foi inflada, assim, o valor arrecadado é validado e vira a referência.\n   \n   3.3. Se o valor arrecadado for inferior ao valor declarado, considera-se o valor arrecadado como referência para pontuação, uma vez que o que eu declarei em vendas não foi validado pelo que foi arrecadado, isto significa que o que eu declarei ter vendido, não foi comprovado por meio dos valores arrecadados em pix e em espécie.\n   \n   3.4. Essa conciliação entre o valor declarado e o valor arrecadado visa garantir a transparência, a coerência contábil e a responsabilidade dos grupos envolvidos.\n\n*Valor declarado = (valor unitário x quantidade de cada produto vendido)\n**Valor arrecadado = O que foi arrecadado em conta e em espécie.";
        $fpdf->SetX(40);
        $fpdf->MultiCell($fpdf->GetPageWidth() - 80, 14, utf8_decode($texto), 0, 'J', false);

        $fpdf->Output('relatorio_empreendedorismo.pdf', 'I');
    }
}

$relatorio = new PDF();
