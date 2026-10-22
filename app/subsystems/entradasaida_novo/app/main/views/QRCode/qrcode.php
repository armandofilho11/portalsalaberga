<?php
// Garantir que nenhum buffer de saída quebre o cabeçalho do PDF
ob_start();

define('FPDF_FONTPATH', __DIR__ . '/../../assets/lib/fpdf/font/');
require_once(__DIR__ . '/../../config/Database.php');
require_once(__DIR__ . '/../../assets/lib/fpdf/BasePDF.php');
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

        $curso = $_POST["turma"];

        // --- AJUSTE AQUI: Selecionando apenas o 'nome' para evitar erro de coluna inexistente ---
        $queryStr = "SELECT nome FROM aluno WHERE id_turma = :turma";
        $query = $this->conn->prepare($queryStr);
        $query->bindValue(":turma", $curso, PDO::PARAM_STR);
        $query->execute();
        $id_aluno = $query->fetchAll(PDO::FETCH_ASSOC);

        $pdf = new BasePDF("L", "cm", "A4");
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Verificar se há alunos
        if (empty($id_aluno)) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 1, utf8_decode('Nenhum aluno encontrado para a turma especificada.'), 0, 1, 'C');
            ob_end_clean();
            $pdf->Output('I', 'crachas_turma_' . $curso . '.pdf');
            return;
        }

        // Configurações de layout
        $cracha_width = 5; 
        $cracha_height = 8; 
        $qr_size = 3.5; 
        $space_between = 1; 
        $start_x = 2; 
        $start_y = 0.5; 
        $current_x = $start_x;
        $current_y = $start_y;

        // Definir diretório dinâmico com base na turma
        $turma_dir = match ($curso) {
            '9'  => 'img3A',
            '10' => 'img3B',
            '11' => 'img3C',
            '12' => 'img3D',
            default => 'imgGeral',
        };

        // Criar diretório se não existir
        $qr_dir = __DIR__ . '/../../assets/img/imgAlunos/' . $turma_dir . '/';
        if (!is_dir($qr_dir)) {
            mkdir($qr_dir, 0777, true);
        }

        // Array para rastrear arquivos criados
        $arquivos_temporarios = [];
        $contador = 1;

        foreach ($id_aluno as $id) {
            // Verificar se o crachá cabe na página atual
            if ($current_y + $cracha_height + 1 > $pdf->GetPageHeight() - 1) {
                $pdf->AddPage();
                $current_x = $start_x;
                $current_y = $start_y;
            }

            // Gerar nome de arquivo seguro usando apenas o nome limpo e um contador
            $nome_limpo = preg_replace('/[^a-zA-Z0-9]/', '_', $id['nome']);
            $arquivo_qrcode = $qr_dir . $contador . '_' . $nome_limpo . '.png';
            $contador++;
            
            // Retornamos a URL para buscar pelo nome do aluno, já que a coluna de ID é desconhecida
            $url = "https://salaberga.com/salaberga/portalsalaberga/app/subsystems/entradasaida/index.php?id_aluno=" . urlencode($id['nome']);
            
            QRcode::png($url, $arquivo_qrcode, QR_ECLEVEL_M, 4);
            $arquivos_temporarios[] = $arquivo_qrcode;

            // Verificar se o arquivo foi criado corretamente
            if (!file_exists($arquivo_qrcode) || !getimagesize($arquivo_qrcode)) {
                die("Erro: QR Code não pôde ser processado para " . $id['nome']);
            }

            // Inserir imagem de fundo do crachá
            $pdf->Image(__DIR__ . '/../../assets/img/crach.jpg', $current_x, $current_y, $cracha_width, $cracha_height);
            
            // Centralizar e posicionar o QR Code
            $qr_x = $current_x + ($cracha_width - $qr_size) / 2;
            $qr_y = $current_y + 3; 
            $pdf->Image($arquivo_qrcode, $qr_x, $qr_y, $qr_size, $qr_size);

            // Nome do aluno posicionado abaixo do crachá correspondente
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY($current_x, $current_y + $cracha_height + 0.2);
            $pdf->Cell($cracha_width, 0.5, utf8_decode($id['nome']), 0, 1, 'C');

            // Atualizar a posição X para o próximo bloco lateral
            $current_x += $cracha_width + $space_between;

            // Quebrar a linha caso o próximo crachá ultrapasse a margem direita da folha A4
            if ($current_x + $cracha_width > $pdf->GetPageWidth() - 2) {
                $current_x = $start_x;
                $current_y += $cracha_height + 1; 
            }
        }

        // Limpar qualquer saída acidental no buffer antes de renderizar o PDF
        ob_end_clean();

        // Enviar o PDF para o navegador
        $pdf->Output('I', 'crachas_turma_' . $curso . '.pdf');

        // Apagar os arquivos gerados após o fechamento e entrega do PDF
        foreach ($arquivos_temporarios as $arquivo) {
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
        }
    }
}

$qrcode = new qrCode1();
?>