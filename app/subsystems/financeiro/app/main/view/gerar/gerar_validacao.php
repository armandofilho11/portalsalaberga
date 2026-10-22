<?php
require('../../assets/lib/fpdf/fpdf.php');

// === RECEBE OS DADOS DO FORMULÁRIO ===
$descricao_objeto = $_POST['descricao_objeto'] ?? '';
$exercicio = $_POST['exercicio'] ?? '';
$nup = $_POST['nup'] ?? '';
$data_dia = $_POST['dia'] ?? '';
$data_mes = $_POST['mes'] ?? '';
$data_ano = $_POST['ano'] ?? '';
$nome_gestor = $_POST['gestor'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$matricula = $_POST['matricula'] ?? '';

// === CONEXÃO COM BANCO ===
$conn = new mysqli('localhost', 'root', '', 'u750204740_sistemafinanceiro'); // ajuste o nome do banco
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// === GARANTE PASTA PARA PDF ===
if (!is_dir('declaracoes')) {
    mkdir('declaracoes', 0777, true);
}

// === NOME DO PDF ===
$nome_arquivo = 'validacao_' . preg_replace('/[^0-9]/', '', $nup) . '_' . date('Ymd_His') . '.pdf';
$caminho = 'declaracoes/' . $nome_arquivo;

// === GERADOR DO PDF ===
class PDF extends FPDF {
    function Header() {
        // Define o caminho da imagem e a largura desejada
        $image_path = '../../assets/img/logo_ceara.png';
        $image_width = 30; // Largura da imagem em mm

        // Calcula a posição X para centralizar a imagem
        $page_width = $this->GetPageWidth();
        $x_position = ($page_width - $image_width) / 2;

        // Adiciona a imagem (caminho, posição X, posição Y, largura)
        $this->Image($image_path, $x_position, 10, $image_width);

        // Move para baixo para o título não sobrepor a imagem. Ajuste o valor (40) conforme necessário.
        $this->SetY(40);
    }

    function Footer() {
        $this->SetY(-45);
        $this->SetFont('Arial', '', 8);
        $texto = utf8_decode("Secretaria da Educação do Ceará\nAvenida General Afonso Albuquerque Lima, S/N - Cambeba • CEP: 60.822-325\nFortaleza / CE • Fone: (85) 3101.3700");
        for ($i = 0; $i < 3; $i++) {
            $this->MultiCell(0, 4, $texto, 0, 'C');
            $this->Ln(2);
        }
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, utf8_decode('VALIDAÇÃO DA DOCUMENTAÇÃO DA FASE INTERNA DA LICITAÇÃO'), 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->SetLeftMargin(20);
$pdf->SetRightMargin(20);

// === TEXTO ===
$texto1 = "Trata-se de procedimento de Gestão Administrativa que visa $descricao_objeto, para o Exercício de $exercicio, por meio de Dispensa Eletrônica de Licitação, fundamentada no art. 75 da Lei Federal Nº 14.133/21 e no Decreto Estadual Nº 35.341, de 09 de março de 2023.\n\n";
$texto2 = "Ficam convalidados os documentos inseridos no processo, conforme quadro discriminatório e NUP abaixo:\n\n";
$pdf->MultiCell(0, 7, utf8_decode($texto1));
$pdf->MultiCell(0, 7, utf8_decode($texto2));

// === TABELA ===
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(100, 7, 'Descrição', 1);
$pdf->Cell(40, 7, 'Documento Validado', 1);
$pdf->Cell(50, 7, 'Número do NUP', 1);
$pdf->Ln();
$pdf->SetFont('Arial', '', 11);
$documentos = [
    'C.I – Formalização de Demanda',
    'P.C.A. - Declaração do plano de Contratação Anual',
    'Mapa de Preço – (Licitaweb)',
    'Termo de Referência (De acordo com o Objeto)',
    'Declaração da LOA – (Assinada pelo Ordenador)',
    'Cronograma de Entrega'
];
foreach ($documentos as $i => $desc) {
    $nup_display = ($i == 0) ? $nup : '';
    $pdf->Cell(100, 7, utf8_decode($desc), 1);
    $pdf->Cell(40, 7, 'Sim', 1);
    $pdf->Cell(50, 7, $nup_display, 1);
    $pdf->Ln();
}

// === ASSINATURA ===
$pdf->Ln(10);
$pdf->MultiCell(0, 7, utf8_decode("Maranguape, $data_dia de $data_mes de $data_ano."));
$pdf->Ln(15);
$pdf->Cell(0, 6, '______________________________________', 0, 1, 'C');
$pdf->Cell(0, 6, utf8_decode("Gestor Escolar – Diretor(a): $nome_gestor"), 0, 1, 'C');
$pdf->Cell(0, 6, "CPF: $cpf", 0, 1, 'C');
$pdf->Cell(0, 6, "Matrícula: $matricula", 0, 1, 'C');

// === SALVA O PDF ===
$pdf->Output('F', $caminho);

// Registro no banco
$stmt = $conn->prepare("INSERT INTO validacoes (descricao_objeto, exercicio, nup, data_dia, data_mes, data_ano, gestor, cpf, matricula, caminho_pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssss", $descricao_objeto, $exercicio, $nup, $data_dia, $data_mes, $data_ano, $nome_gestor, $cpf, $matricula, $caminho);
$stmt->execute();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação Gerada - Sistema Financeiro</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #45a049;
            --accent-color: #FFC107;
            --text-color: #333;
            --background-color: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f0f0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .modal-header {
            border-bottom: 2px solid rgba(0,0,0,0.1);
            padding: 25px 30px;
        }

        .modal-title {
            color: var(--text-color);
            font-weight: 600;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 30px;
        }

        .success-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .success-message {
            color: var(--text-color);
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #4CAF50;
            border: none;
        }

        .btn-primary:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
        }

        .btn-secondary {
            background: #95a5a6;
            border: none;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(127, 140, 141, 0.3);
        }

        @media (max-width: 576px) {
            .modal-header {
                padding: 20px;
            }

            .modal-body {
                padding: 20px;
            }

            .btn-container {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade show" id="successModal" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Validação Gerada com Sucesso!</h5>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle success-icon"></i>
                    <p class="success-message">A validação foi gerada e salva com sucesso.</p>
                    <div class="btn-container">
                        <a href="<?php echo $caminho; ?>" class="btn btn-primary" target="_blank">
                            <i class="fas fa-eye"></i>
                            Visualizar PDF
                        </a>
                        <a href="../inicial.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Voltar ao Painel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
