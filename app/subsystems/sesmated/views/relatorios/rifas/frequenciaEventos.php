<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Classe de conexão ao banco de dados (direto no arquivo)
class connect
{
    protected $connect;

    function __construct()
    {
        $this->connect_database();
    }

    function connect_database()
    {
        try {
            $HOST = 'localhost';
            $DATABASE = 'entradasaida';
            $USER = 'root';
            $PASSWORD = '';
            $this->connect = new PDO('mysql:host=' . $HOST . ';dbname=' . $DATABASE, $USER, $PASSWORD);
        } catch (PDOException $e) {
            $HOST = 'localhost';
            $DATABASE = 'u750204740_entradasaida';
            $USER = 'u750204740_entradasaida';
            $PASSWORD = 'paoComOvo123!@##';
            $this->connect = new PDO('mysql:host=' . $HOST . ';dbname=' . $DATABASE, $USER, $PASSWORD);
        } catch (PDOException $e) {
            die('Erro! O sistema não possui conexão com o banco de dados.');
        }
    }

    public function getConnection()
    {
        return $this->connect;
    }
}

require_once('../../../assets/fpdf/fpdf.php');

try {
    $db = new connect();
    $conn = $db->getConnection();
} catch (PDOException $e) {
    die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
}

$query = "
    SELECT
        a.nome AS nome_aluno,
        a.matricula,
        a.id_turma,
        c.curso AS nome_curso,
        (CASE
            WHEN c.curso LIKE '%Enfermagem%' THEN 'A'
            WHEN c.curso LIKE '%Informática%' THEN 'B'
            WHEN c.curso LIKE '%Meio Ambiente%' THEN 'C'
            WHEN c.curso LIKE '%Administração%' THEN 'C'
            WHEN c.curso LIKE '%Edificações%' THEN 'D'
            ELSE ''
        END) AS letra_turma,
        e.nome AS nome_evento,
        e.data_evento,
        f.presente,
        f.data_registro
    FROM
        frequencia_sesmated AS f
    JOIN
        aluno AS a ON f.id_aluno = a.id_aluno
    JOIN
        evento AS e ON f.id_evento = e.id_evento
    JOIN
        curso AS c ON a.id_curso = c.id_curso
    ORDER BY
        e.nome,
        a.id_turma,
        CASE
            WHEN c.curso LIKE '%Enfermagem%' THEN 1
            WHEN c.curso LIKE '%Informática%' THEN 2
            WHEN c.curso LIKE '%Meio Ambiente%' THEN 3
            WHEN c.curso LIKE '%Administração%' THEN 4
            WHEN c.curso LIKE '%Edificações%' THEN 5
            ELSE 6
        END,
        a.nome;
";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debug: loga os resultados do banco
error_log('Resultados do banco: ' . print_r($results, true));

$turmasMapping = [
    9 => '3º Ano',
    10 => '3º Ano',
    11 => '3º Ano',
    12 => '3º Ano',
    // Adicione outros mapeamentos de id_turma para ano se necessário
];

$frequenciaPorEvento = [];
foreach ($results as $row) {
    $frequenciaPorEvento[$row['nome_evento']]['data'] = $row['data_evento'];
    $frequenciaPorEvento[$row['nome_evento']]['alunos'][] = $row;
}

// Debug: loga o array de frequência por evento
error_log('Frequencia por evento: ' . print_r($frequenciaPorEvento, true));

class PDF extends FPDF
{
    function Header()
    {
        // Adiciona a imagem de fundo
        $this->Image('../../../assets/img/fundo.jpg', 0, 0, $this->GetPageWidth(), $this->GetPageHeight());

        // Pula um espaço no topo para não sobrepor os logos da imagem de fundo.
        $this->Ln(50);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function ChapterTitle($label)
    {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(114, 203, 72);
        $this->Cell(0, 6, utf8_decode($label), 0, 1, 'L', true);
        $this->Ln(4);
    }
}

$pdf = new PDF();
// Debug: verifica se o PDF foi instanciado
if (!$pdf) {
    error_log('Erro ao instanciar o FPDF');
} else {
    error_log('FPDF instanciado com sucesso');
}
$pdf->AliasNbPages();
$pdf->SetMargins(5, 10, 5); // Diminui as margens para alargar a tabela

if (empty($frequenciaPorEvento)) {
    error_log('Nenhum dado de frequência encontrado');
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Nenhum dado de frequencia encontrado.', 0, 1, 'C');
} else {
    error_log('Dados de frequência encontrados');
    $pdf->AddPage('P', 'A4');
    foreach ($frequenciaPorEvento as $nomeEvento => $dadosEvento) {
        $dataEvento = date('d/m/Y', strtotime($dadosEvento['data']));
        $pdf->ChapterTitle($nomeEvento . " - " . $dataEvento);

        // Cabeçalho da tabela
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(25, 7, utf8_decode('Matrícula'), 1, 0, 'C', true);
        $pdf->Cell(65, 7, utf8_decode('Aluno'), 1, 0, 'C', true);
        $pdf->Cell(15, 7, utf8_decode('Ano'), 1, 0, 'C', true);
        $pdf->Cell(15, 7, utf8_decode('Turma'), 1, 0, 'C', true);
        $pdf->Cell(40, 7, utf8_decode('Curso'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, utf8_decode('Presença'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, utf8_decode('Registro'), 1, 1, 'C', true);
        $pdf->SetFont('Arial', '', 9);

        foreach ($dadosEvento['alunos'] as $aluno) {
            $anoTurma = $turmasMapping[$aluno['id_turma']] ?? '';

            $curso = $aluno['nome_curso'];
            if (stripos($curso, 'Enfermagem') !== false) {
                $pdf->SetFillColor(255, 192, 203); // Vermelho claro (Rosa)
            } elseif (stripos($curso, 'Informática') !== false) {
                $pdf->SetFillColor(176, 196, 222); // Azul aço claro
            } elseif (stripos($curso, 'Meio Ambiente') !== false) {
                $pdf->SetFillColor(144, 238, 144); // Verde claro
            } elseif (stripos($curso, 'Administração') !== false) {
                $pdf->SetFillColor(173, 216, 230); // Azul claro
            } elseif (stripos($curso, 'Edificações') !== false) {
                $pdf->SetFillColor(211, 211, 211); // Cinza claro
            } else {
                $pdf->SetFillColor(255, 255, 255); // Branco
            }

            $pdf->Cell(25, 6, mb_strtoupper($aluno['matricula'], 'UTF-8'), 1, 0, 'C', true);
            $pdf->Cell(65, 6, utf8_decode(mb_strtoupper($aluno['nome_aluno'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->Cell(15, 6, utf8_decode(mb_strtoupper($anoTurma, 'UTF-8')), 1, 0, 'C', true);
            $pdf->Cell(15, 6, utf8_decode(mb_strtoupper($aluno['letra_turma'], 'UTF-8')), 1, 0, 'C', true);
            $pdf->Cell(40, 6, utf8_decode(mb_strtoupper($aluno['nome_curso'], 'UTF-8')), 1, 0, 'C', true);

            $presenca = $aluno['presente'] ? 'Presente' : 'Ausente';
            $pdf->SetTextColor(0, 0, 0); // Garante que o texto de presença seja legível
            $pdf->Cell(20, 6, utf8_decode(mb_strtoupper($presenca, 'UTF-8')), 1, 0, 'C', true);
            
            $horaRegistro = date('H:i', strtotime($aluno['data_registro']));
            $pdf->Cell(20, 6, $horaRegistro, 1, 1, 'C', true);
        }
        $pdf->Ln(10); // Espaçamento entre os eventos
    }
}

// ===================== RESUMO DE PONTUAÇÃO POR CURSO =====================

// 1. Buscar todos os cursos e suas turmas
$cursos = [];
$turmasPorCurso = [];
$sqlCursos = $conn->query("SELECT id_curso, curso FROM curso");
while ($row = $sqlCursos->fetch(PDO::FETCH_ASSOC)) {
    $cursos[$row['id_curso']] = $row['curso'];
    $turmasPorCurso[$row['id_curso']] = [];
}
$sqlTurmas = $conn->query("SELECT id_turma, id_curso FROM aluno GROUP BY id_turma, id_curso");
while ($row = $sqlTurmas->fetch(PDO::FETCH_ASSOC)) {
    $turmasPorCurso[$row['id_curso']][] = $row['id_turma'];
}

// 2. Definir regras de proporcionalidade
function getProporcao($numTurmas, $tipo) {
    if ($tipo === 'palestra') {
        if ($numTurmas >= 3) return 9 * $numTurmas;
        if ($numTurmas == 2) return 6 * $numTurmas;
        if ($numTurmas == 1) return 3;
    } else if ($tipo === 'workshop') {
        if ($numTurmas >= 3) return 3 * $numTurmas;
        if ($numTurmas == 2) return 2 * $numTurmas;
        if ($numTurmas == 1) return 1;
    }
    return 0;
}

// 3. Buscar todos os eventos do tipo palestra e workshop
$eventos = [];
$sqlEventos = $conn->query("SELECT id_evento, nome, tipo FROM evento");
while ($row = $sqlEventos->fetch(PDO::FETCH_ASSOC)) {
    $eventos[$row['id_evento']] = [
        'nome' => $row['nome'],
        'tipo' => $row['tipo']
    ];
}

// 4. Contar presenças por curso em cada evento
$presencasPorCursoEvento = [];
$sqlPresencas = $conn->query("SELECT f.id_evento, a.id_curso, COUNT(DISTINCT f.id_aluno) as total FROM frequencia_sesmated f JOIN aluno a ON f.id_aluno = a.id_aluno WHERE f.presente = 1 GROUP BY f.id_evento, a.id_curso");
while ($row = $sqlPresencas->fetch(PDO::FETCH_ASSOC)) {
    $presencasPorCursoEvento[$row['id_evento']][$row['id_curso']] = $row['total'];
}

// 5. Calcular pontuação por curso para palestras e workshops
$resumoPontuacao = [];
foreach ($cursos as $id_curso => $nome_curso) {
    $numTurmas = count($turmasPorCurso[$id_curso]);
    // Palestras
    $totalEsperadoPalestra = 0;
    $totalPresentePalestra = 0;
    $numPalestras = 0;
    foreach ($eventos as $id_evento => $ev) {
        if ($ev['tipo'] === 'palestra') {
            $numPalestras++;
            $totalEsperadoPalestra += getProporcao($numTurmas, 'palestra') / ($numPalestras > 0 ? $numPalestras : 1);
            $totalPresentePalestra += $presencasPorCursoEvento[$id_evento][$id_curso] ?? 0;
        }
    }
    $porcentagemPalestra = $totalEsperadoPalestra > 0 ? ($totalPresentePalestra / $totalEsperadoPalestra) * 100 : 0;
    if ($porcentagemPalestra >= 100) {
        $pontosPalestra = 500;
    } elseif ($porcentagemPalestra >= 80) {
        $pontosPalestra = 400;
    } elseif ($porcentagemPalestra >= 50) {
        $pontosPalestra = 300;
    } else {
        $pontosPalestra = 0;
    }
    // Workshops
    $totalEsperadoWorkshop = 0;
    $totalPresenteWorkshop = 0;
    $numWorkshops = 0;
    foreach ($eventos as $id_evento => $ev) {
        if ($ev['tipo'] === 'workshop') {
            $numWorkshops++;
            $totalEsperadoWorkshop += getProporcao($numTurmas, 'workshop') / ($numWorkshops > 0 ? $numWorkshops : 1);
            $totalPresenteWorkshop += $presencasPorCursoEvento[$id_evento][$id_curso] ?? 0;
        }
    }
    $porcentagemWorkshop = $totalEsperadoWorkshop > 0 ? ($totalPresenteWorkshop / $totalEsperadoWorkshop) * 100 : 0;
    if ($porcentagemWorkshop >= 100) {
        $pontosWorkshop = 500;
    } elseif ($porcentagemWorkshop >= 80) {
        $pontosWorkshop = 400;
    } elseif ($porcentagemWorkshop >= 50) {
        $pontosWorkshop = 300;
    } else {
        $pontosWorkshop = 0;
    }
    $resumoPontuacao[$nome_curso] = [
        'palestra' => [
            'presenca' => $totalPresentePalestra,
            'esperado' => $totalEsperadoPalestra,
            'porcentagem' => $porcentagemPalestra,
            'pontos' => $pontosPalestra
        ],
        'workshop' => [
            'presenca' => $totalPresenteWorkshop,
            'esperado' => $totalEsperadoWorkshop,
            'porcentagem' => $porcentagemWorkshop,
            'pontos' => $pontosWorkshop
        ]
    ];
}

// 6. Adicionar ao PDF o quadro-resumo
$pdf->AddPage('P', 'A4');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Resumo de Pontuação por Curso'), 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 8, utf8_decode('Curso'), 1, 0, 'C', true);
$pdf->Cell(40, 8, utf8_decode('Palestras'), 1, 0, 'C', true);
$pdf->Cell(30, 8, utf8_decode('%'), 1, 0, 'C', true);
$pdf->Cell(30, 8, utf8_decode('Pontos'), 1, 0, 'C', true);
$pdf->Cell(40, 8, utf8_decode('Workshops'), 1, 0, 'C', true);
$pdf->Cell(30, 8, utf8_decode('%'), 1, 0, 'C', true);
$pdf->Cell(30, 8, utf8_decode('Pontos'), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);
foreach ($resumoPontuacao as $curso => $dados) {
    $pdf->Cell(50, 8, utf8_decode(ucfirst($curso)), 1, 0, 'C');
    $pdf->Cell(40, 8, $dados['palestra']['presenca'] . '/' . $dados['palestra']['esperado'], 1, 0, 'C');
    $pdf->Cell(30, 8, number_format($dados['palestra']['porcentagem'], 1, ',', '.') . '%', 1, 0, 'C');
    $pdf->Cell(30, 8, $dados['palestra']['pontos'], 1, 0, 'C');
    $pdf->Cell(40, 8, $dados['workshop']['presenca'] . '/' . $dados['workshop']['esperado'], 1, 0, 'C');
    $pdf->Cell(30, 8, number_format($dados['workshop']['porcentagem'], 1, ',', '.') . '%', 1, 0, 'C');
    $pdf->Cell(30, 8, $dados['workshop']['pontos'], 1, 1, 'C');
}

// Debug: verifica o tamanho do PDF gerado
$pdfContent = $pdf->Output('S');
error_log('Tamanho do PDF gerado: ' . strlen($pdfContent));
$pdf->Output('I', 'Relatorio_Frequencia.pdf');
?>