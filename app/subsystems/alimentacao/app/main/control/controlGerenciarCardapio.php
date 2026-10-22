<?php
require_once("../model/Cardapio.class.php");
require_once("../assets/FPDF/fpdf.php"); // Use o caminho correto do FPDF

// Define headers para resposta JSON
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';
$cardapio = new Cardapio();
$response = ['success' => false, 'message' => 'Ação inválida'];

try {
    // Função auxiliar para validar datas
    function validateDates($date) {
        $dateObj = new DateTime($date);
        $today = new DateTime('today');
        if ($dateObj < $today) {
            throw new Exception('A data não pode ser no passado.');
        }
        return true;
    }

    // Função auxiliar para verificar duplicatas
    function checkDuplicate($pdo, $table, $tipo, $data) {
        $query = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE tipo = :tipo AND data = :data");
        $query->execute(['tipo' => $tipo, 'data' => $data]);
        return $query->fetchColumn() > 0;
    }

    if ($action === 'cadastrarCardapio') {
        $tipo = $_POST['tipo'] ?? '';
        $data = $_POST['data'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';

        // Validação
        if (empty($tipo) || empty($data) || empty($nome) || empty($descricao)) {
            $response['message'] = 'Todos os campos são obrigatórios.';
            echo json_encode($response);
            exit;
        }

        validateDates($data);

        if (checkDuplicate($cardapio->getPdo(), 'cardápio', $tipo, $data)) {
            $response['message'] = "Já existe um cardápio para o tipo '$tipo' na data " . date('d/m/Y', strtotime($data)) . ".";
            echo json_encode($response);
            exit;
        }

        $cardapio->cadastrarCardapio($data, $tipo, $nome, $descricao);
        $response = ['success' => true, 'message' => 'Cardápio cadastrado com sucesso!'];
    }

    if ($action === 'alterarCardapio') {
        $id = $_POST['id'] ?? '';
        $tipo = $_POST['tipo'] ?? '';
        $data = $_POST['data'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';

        if (empty($id) || empty($tipo) || empty($data) || empty($nome) || empty($descricao)) {
            $response['message'] = 'Todos os campos são obrigatórios.';
            echo json_encode($response);
            exit;
        }

        validateDates($data);

        $query = $cardapio->getPdo()->prepare("SELECT COUNT(*) FROM cardápio WHERE id = :id");
        $query->execute(['id' => $id]);
        if ($query->fetchColumn() == 0) {
            $response['message'] = 'Cardápio com o ID fornecido não existe.';
            echo json_encode($response);
            exit;
        }

        $query = $cardapio->getPdo()->prepare("SELECT COUNT(*) FROM cardápio WHERE tipo = :tipo AND data = :data AND id != :id");
        $query->execute(['tipo' => $tipo, 'data' => $data, 'id' => $id]);
        if ($query->fetchColumn() > 0) {
            $response['message'] = "Já existe um cardápio para o tipo '$tipo' na data " . date('d/m/Y', strtotime($data)) . ".";
            echo json_encode($response);
            exit;
        }

        $cardapio->atualizarCardapio($id, $data, $tipo, $nome, $descricao);
        $response = ['success' => true, 'message' => 'Cardápio atualizado com sucesso!'];
    }

    if ($action === 'excluirCardapio') {
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            $response['message'] = 'ID do cardápio não fornecido.';
            echo json_encode($response);
            exit;
        }

        // Verifica se o cardápio existe
        $query = $cardapio->getPdo()->prepare("SELECT COUNT(*) FROM cardápio WHERE id = :id");
        $query->execute(['id' => $id]);
        if ($query->fetchColumn() == 0) {
            $response['message'] = 'Cardápio com o ID fornecido não existe.';
            echo json_encode($response);
            exit;
        }

        $cardapio->excluirCardapio($id);
        $response = ['success' => true, 'message' => 'Cardápio excluído com sucesso!'];
    }

    if ($action === 'excluirCardapios') {
        $ids = $_POST['ids'] ?? [];
        if (empty($ids)) {
            $response['message'] = 'Nenhum cardápio selecionado para exclusão.';
            echo json_encode($response);
            exit;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = $cardapio->getPdo()->prepare("DELETE FROM cardápio WHERE id IN ($placeholders)");
        $query->execute($ids);
        $response = ['success' => true, 'message' => 'Cardápios excluídos com sucesso!'];
    }

    if ($action === 'gerarRelatorio') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $menus = $data['menus'] ?? [];

        if (empty($menus)) {
            $response['message'] = 'Nenhum dado disponível para gerar o relatório.';
            echo json_encode($response);
            exit;
        }

        class PDF extends FPDF {
            function Header() {
                $this->SetFont('Arial', 'B', 16);
                $this->Cell(0, 10, 'Relatório de Cardápios', 0, 1, 'C');
                $this->Ln(10);
            }

            function Footer() {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
            }

            function MenuTable($menus) {
                $this->SetFont('Arial', 'B', 12);
                $this->Cell(30, 10, 'Data', 1, 0, 'C');
                $this->Cell(40, 10, 'Tipo', 1, 0, 'C');
                $this->Cell(50, 10, 'Nome', 1, 0, 'C');
                $this->Cell(70, 10, 'Descrição', 1, 1, 'C');

                $this->SetFont('Arial', '', 10);
                foreach ($menus as $menu) {
                    $this->Cell(30, 10, $menu['data'], 1, 0, 'C');
                    $this->Cell(40, 10, $menu['tipo'], 1, 0, 'C');
                    $this->Cell(50, 10, $this->shorten($menu['nome'], 25), 1, 0, 'C');
                    $this->Cell(70, 10, $this->shorten($menu['descricao'], 35), 1, 1, 'C');
                }
            }

            function shorten($text, $max) {
                if (strlen($text) > $max) {
                    return substr($text, 0, $max - 3) . '...';
                }
                return $text;
            }
        }

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->MenuTable($menus);
        $pdf->Output('I', 'cardapio_relatorio.pdf'); // 'I' para exibição inline
        exit;
    }

    echo json_encode($response);
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}
?>