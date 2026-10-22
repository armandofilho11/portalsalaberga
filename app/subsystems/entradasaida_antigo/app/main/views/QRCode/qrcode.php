<?php
define('FPDF_FONTPATH', __DIR__ . '/../../assets/lib/fpdf/font/');
require_once(__DIR__ . '/../../config/Database.php');
require_once(__DIR__ . '/../../assets/lib/fpdf/fpdf.php');
require_once(__DIR__ . '/../../assets/lib/phpqrcode/qrlib.php');

class qrCode1 extends connect
{
    public function __construct()
    {
        parent::__construct();
        $this->pdf();
    }

    public function pdf()
    {
        // Verificar se a extensão GD está ativada
        if (!function_exists('imagecreate')) {
            die("Erro: A extensão GD do PHP não está ativada. Habilite-a no php.ini.");
        }

        // Validar entrada
        if (!isset($_POST['turma']) || empty($_POST['turma'])) {
            die("Erro: Turma não especificada.");
        }

        $pdf = new FPDF("L", "cm", "A4");
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $curso = $_POST["turma"];

        // Consultar alunos da turma
        $queryStr = "SELECT id_aluno, nome FROM aluno WHERE id_turma = :turma";
        $query = $this->connect->prepare($queryStr);
        $query->bindValue(":turma", $curso, PDO::PARAM_STR);
        $query->execute();
        $id_aluno = $query->fetchAll(PDO::FETCH_ASSOC);

        // Verificar se há alunos
        if (empty($id_aluno)) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 1, utf8_decode('Nenhum aluno encontrado para a turma especificada.'), 0, 1, 'C');
            $pdf->Output('I', 'crachas_turma_' . $curso . '.pdf');
            return;
        }

        // Configurações de layout
        $cracha_width = 5; // Largura do crachá (cm)
        $cracha_height = 8; // Altura do crachá (cm)
        $qr_size = 3.5; // Tamanho do QR code (cm)
        $space_between = 1; // Espaço entre crachás (cm)
        $max_per_line = 3; // Máximo de crachás por linha
        $start_x = 2; // Posição X inicial (cm)
        $start_y = 0.5; // Posição Y inicial (cm)
        $current_x = $start_x;
        $current_y = $start_y;

        // Definir diretório dinâmico com base na turma
        $turma_dir = '';
        switch ($curso) {
            case '9':
                $turma_dir = 'img3A';
                break;
            case '10':
                $turma_dir = 'img3B';
                break;
            case '11':
                $turma_dir = 'img3C';
                break;
            case '12':
                $turma_dir = 'img3D';
                break;
        }

        // Criar diretório se não existir
        $qr_dir = __DIR__ . '/../../assets/img/imgAlunos/' . $turma_dir . '/';
        if (!is_dir($qr_dir)) {
            mkdir($qr_dir, 0777, true);
        }

        foreach ($id_aluno as $id) {
            // Verificar se o crachá cabe na página atual
            if ($current_y + $cracha_height + 1 > $pdf->GetPageHeight() - 1) {
                $pdf->AddPage();
                $current_x = $start_x;
                $current_y = $start_y;
            }

            // Gerar o QR code
            $arquivo_qrcode = $qr_dir . str_replace(' ', '_', $id['nome']) . '.png';
            $url = "https://salaberga.com/salaberga/portalsalaberga/app/subsystems/entradasaida/index.php?id_aluno=" . urlencode($id['nome']);
            QRcode::png($url, $arquivo_qrcode, QR_ECLEVEL_M, 4);

            // Verificar se o arquivo foi criado
            if (!file_exists($arquivo_qrcode) || !getimagesize($arquivo_qrcode)) {
                die("Erro: QR Code não gerado para " . $id['nome']);
            }

            // Colocar a imagem de fundo (crachá)
            $pdf->Image(__DIR__ . '/../../assets/img/crach.jpg', $current_x, $current_y, $cracha_width, $cracha_height);
            // Calcular a posição do QR code para centralizá-lo em cima da imagem
            $qr_x = $current_x + ($cracha_width - $qr_size) / 2; // Centraliza o QR code
            $qr_y = $current_y + 3; // 0.5 cm de margem do topo da imagem
            $pdf->Image($arquivo_qrcode, $qr_x, $qr_y, $qr_size, $qr_size);

            // Exibir o nome do aluno abaixo do crachá
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY($current_x, $current_y + $cracha_height + 0.2); // 0.2 cm abaixo do crachá
            $pdf->Cell($cracha_width, 0.5, utf8_decode($id['nome']), 0, 1, 'C');

            // Remover o arquivo temporário
            if (file_exists($arquivo_qrcode)) {
                unlink($arquivo_qrcode);
            }

            // Atualizar a posição X para o próximo crachá
            $current_x += $cracha_width + $space_between;

            // Verificar se atingiu o limite da linha
            if ($current_x + $cracha_width > $pdf->GetPageWidth() - 2) {
                $current_x = $start_x;
                $current_y += $cracha_height + 1; // Espaço vertical para a próxima linha
            }
        }

        // Finalizar o PDF
        $pdf->Output('I', 'crachas_turma_' . $curso . '.pdf');
    }
}

$qrcode = new qrCode1();
?>