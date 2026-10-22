<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../../../assets/fpdf/fpdf.php');

// Conexão direta (igual ao frequenciaEventos.php)
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

$db = new connect();
$conn = $db->getConnection();

// Buscar cursos e turmas
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

$eventos = [];
$sqlEventos = $conn->query("SELECT id_evento, nome, tipo FROM evento");
while ($row = $sqlEventos->fetch(PDO::FETCH_ASSOC)) {
    $eventos[$row['id_evento']] = [
        'nome' => $row['nome'],
        'tipo' => $row['tipo']
    ];
}

$presencasPorCursoEvento = [];
$sqlPresencas = $conn->query("SELECT f.id_evento, a.id_curso, COUNT(DISTINCT f.id_aluno) as total FROM frequencia_sesmated f JOIN aluno a ON f.id_aluno = a.id_aluno WHERE f.presente = 1 GROUP BY f.id_evento, a.id_curso");
while ($row = $sqlPresencas->fetch(PDO::FETCH_ASSOC)) {
    $presencasPorCursoEvento[$row['id_evento']][$row['id_curso']] = $row['total'];
}

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
    } elseif ($porcentagemPalestra >= 80 && $porcentagemPalestra < 100) {
        $pontosPalestra = 400;
    } elseif ($porcentagemPalestra >= 50 && $porcentagemPalestra < 80) {
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
    } elseif ($porcentagemWorkshop >= 80 && $porcentagemWorkshop < 100) {
        $pontosWorkshop = 400;
    } elseif ($porcentagemWorkshop >= 50 && $porcentagemWorkshop < 80) {
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

// ==== GERAÇÃO DO PDF NO PADRÃO relatorio_logo.php ====
$fpdf = new FPDF('P', 'pt', 'A4');
$fpdf->AliasNbPages();
$fpdf->AddPage();
$fpdf->Image('../../../assets/img/fundo.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

// Título principal
$fpdf->SetFont('Arial', 'B', 24);
$fpdf->SetY(140);
$fpdf->SetX(0);
$fpdf->Cell(595, 20, utf8_decode('Resumo de Pontuação por Curso'), 0, 1, 'C');

// Título da tabela
$fpdf->SetFont('Arial', 'B', 14);
$fpdf->SetY(180);
$fpdf->SetX(0);
$fpdf->Cell(595, 20, utf8_decode('Presença em Palestras e Workshops'), 0, 1, 'C');

// Cabeçalho da tabela
$fpdf->SetFont('Arial', 'B', 10);
$fpdf->SetFillColor(255, 255, 255);
$fpdf->SetTextColor(0, 0, 0);
$fpdf->SetY(220);
$fpdf->SetX(0);
$colCurso = 160;
$colPorcentagemPalestra = 110;
$colPontosPalestra = 60;
$colPorcentagemWorkshop = 110;
$colPontosWorkshop = 60;
$totalWidth = $colCurso + $colPorcentagemPalestra + $colPontosPalestra + $colPorcentagemWorkshop + $colPontosWorkshop;
$startX = ($fpdf->GetPageWidth() - $totalWidth) / 2;
$fpdf->SetX($startX);
$fpdf->Cell($colCurso, 20, utf8_decode('Curso'), 1, 0, 'C', true);
$fpdf->Cell($colPorcentagemPalestra, 20, utf8_decode('% Palestras'), 1, 0, 'C', true);
$fpdf->Cell($colPontosPalestra, 20, utf8_decode('Pontos'), 1, 0, 'C', true);
$fpdf->Cell($colPorcentagemWorkshop, 20, utf8_decode('% Workshops'), 1, 0, 'C', true);
$fpdf->Cell($colPontosWorkshop, 20, utf8_decode('Pontos'), 1, 1, 'C', true);

// Dados da tabela
$fpdf->SetFont('Arial', '', 10);
$fpdf->SetFillColor(240, 240, 240);
$fill = false;
foreach ($resumoPontuacao as $curso => $dados) {
    $fpdf->SetX($startX);
    $porcentagemPalestra = $dados['palestra']['esperado'] > 0 ? ($dados['palestra']['presenca'] / $dados['palestra']['esperado']) * 100 : 0;
    $porcentagemWorkshop = $dados['workshop']['esperado'] > 0 ? ($dados['workshop']['presenca'] / $dados['workshop']['esperado']) * 100 : 0;
    if ($porcentagemPalestra > 100) $porcentagemPalestra = 100;
    if ($porcentagemWorkshop > 100) $porcentagemWorkshop = 100;
    $porcentagemPalestra = number_format($porcentagemPalestra, 2, ',', '');
    $porcentagemWorkshop = number_format($porcentagemWorkshop, 2, ',', '');
    $fpdf->Cell($colCurso, 20, utf8_decode(ucfirst($curso)), 1, 0, 'C', $fill);
    $fpdf->Cell($colPorcentagemPalestra, 20, $porcentagemPalestra . '%', 1, 0, 'C', $fill);
    $fpdf->Cell($colPontosPalestra, 20, $dados['palestra']['pontos'], 1, 0, 'C', $fill);
    $fpdf->Cell($colPorcentagemWorkshop, 20, $porcentagemWorkshop . '%', 1, 0, 'C', $fill);
    $fpdf->Cell($colPontosWorkshop, 20, $dados['workshop']['pontos'], 1, 1, 'C', $fill);
    $fill = !$fill;
}

$fpdf->Output('I', 'Resultado_Pontuacao_Cursos.pdf'); 