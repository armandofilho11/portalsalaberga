<?php
require('../../assets/fpdf/fpdf.php');
require('../../models/select_model.php');
require_once('../../config/connect.php');

// Pega o id_vaga da URL
$id_vaga = isset($_GET['id_vaga']) ? $_GET['id_vaga'] : null;
$responsavel = isset($_GET['responsavel']) ? $_GET['responsavel'] : '';

if (!$id_vaga) {
    die('ID da vaga não informado!');
}

// Use a conexão já existente
$conexao = new connect();
$pdo = $conexao->getConnection();

if (!$pdo) {
    die('Erro ao conectar ao banco de dados!');
}

$stmtVaga = $pdo->prepare('SELECT data, hora, id, id_concedente FROM vagas WHERE id = ? LIMIT 1');
$stmtVaga->execute([$id_vaga]);
$vaga = $stmtVaga->fetch(PDO::FETCH_ASSOC);

$data = isset($vaga['data']) ? $vaga['data'] : date('Y-m-d');
$horario = isset($vaga['hora']) ? $vaga['hora'] : '';

// Busca o nome da empresa e endereço
$stmtEmpresa = $pdo->prepare('SELECT nome, endereco FROM concedentes WHERE id = ? LIMIT 1');
$stmtEmpresa->execute([$vaga['id_concedente']]);
$empresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);

// Busca todos os alunos relacionados à vaga
$stmtAlunos = $pdo->prepare('SELECT aluno.nome FROM aluno 
    INNER JOIN selecao ON aluno.id = selecao.id_aluno 
    WHERE selecao.id_vaga = ?');
$stmtAlunos->execute([$id_vaga]);
$alunos = $stmtAlunos->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF
{
    function Header()
    {
        // Coloca o background em toda a página
        $this->Image('./img/fundo.jpg', 0, 0, $this->w, $this->h);
    }

    function Footer()
    {
        // Se quiser rodapé, adicione aqui
    }
}

function removerAcentos($string)
{
    return iconv('UTF-8', 'ASCII//TRANSLIT', $string);
}

function obterNomeMes($mesNumero)
{
    switch ($mesNumero) {
        case 1:
            return 'janeiro';
        case 2:
            return 'fevereiro';
        case 3:
            return 'março';
        case 4:
            return 'abril';
        case 5:
            return 'maio';
        case 6:
            return 'junho';
        case 7:
            return 'julho';
        case 8:
            return 'agosto';
        case 9:
            return 'setembro';
        case 10:
            return 'outubro';
        case 11:
            return 'novembro';
        case 12:
            return 'dezembro';
        default:
            return '';
    }
}

$pdf = new PDF();

foreach ($alunos as $aluno) {
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    $dataTimestamp = strtotime($data);
    $dia = date('d', $dataTimestamp);
    $mes = date('n', $dataTimestamp);
    $ano = date('Y', $dataTimestamp);
    $nomeMes = obterNomeMes($mes);
    $dataExtenso = "$dia de $nomeMes de $ano";

    // Adicionar conteúdo da carta
    $pdf->Ln(35);
    $pdf->Cell(0, 20, 'Maranguape, ' . $dataExtenso . '.', 0, 1, 'R');
    $pdf->Cell(0, 10, 'Prezado(a) Sr(a). ' . removerAcentos($responsavel), 0, 1);
    $pdf->Ln(0);
    $pdf->MultiCell(0, 10, utf8_decode("Ao cumprimentá-la, encaminhamos o(a) estudante " . removerAcentos(strtoupper($aluno['nome'])) . " para seleção na referida empresa, objetivando vaga para o cumprimento do estágio curricular obrigatório."));

    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Empresa: ' . utf8_decode($empresa['nome']), 0, 1);
    $pdf->MultiCell(0, 10, 'Endereco: ' . utf8_decode($empresa['endereco']), 0, 1);
    $pdf->Cell(0, 10, 'Data: ' . date('d/m/Y', strtotime($data)), 0, 1);
    $pdf->Cell(0, 10, utf8_decode('Horário: ') . $horario, 0, 1);
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'U', 12);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->SetFont('Arial', 'B', 12); // Definir fonte em negrito
    $pdf->SetTextColor(255, 0, 0); // Definir cor do texto

    // Calcular a largura total disponível
    $larguraTotal = $pdf->GetPageWidth() - $pdf->GetX() - 10; // Ajuste conforme necessário

    // Imprimir a linha completa

    $pdf->MultiCell(0, 10, utf8_decode("Obs.: O aluno deverá comparecer no horário marcado uniformizado, portando RG, CPF e
currículo."));
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Cordialmente,', 0, 1);
    $pdf->Cell(0, 20, '________________________________________________________________________________');
    $pdf->Ln(17);
    $pdf->SetFont('Arial', '', 8);
    
    $pdf->Cell(0, 5, utf8_decode('Prof. Francisco Otávio de Menezes Filho '), 0, 1, 'L');
    $pdf->Cell(0, 5, utf8_decode('Coordenador do Curso Técnico em Informática'), 0, 1, 'L');
    $pdf->Cell(0, -5, utf8_decode('Av. Marta Carvalho Nojoza, S/Nº, Outra Banda - Maranguape - CE'), 0, 1, 'R');
    $pdf->Cell(0, -5, utf8_decode('WhatsApp: (85) 3341-3990 / Email: 23091996@prof.ce.gov.br'), 0, 1, 'R');
}

$pdf->Output();
