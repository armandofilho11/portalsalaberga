<?php
ob_start();
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Turma.php';
require_once __DIR__ . '/../vendor/fpdf/fpdf186/fpdf.php';

ini_set('display_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

if (!isset($_SESSION['responsavel_id'])) {
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'listar_turmas') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $turma = new Turma();
        $turmas = $turma->listar_turmas();
        ob_end_clean();
        echo json_encode(['success' => true, 'turmas' => $turmas]);
        exit;
    } catch (Exception $e) {
        error_log("[RelatorioController] Erro ao listar turmas: " . $e->getMessage());
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Erro ao listar turmas']);
        exit;
    }
}

if ($action === 'gerar_relatorio') {
    $tipo = $_GET['tipo'] ?? '';

    try {
        // Definir fuso horário para Brasil
        date_default_timezone_set('America/Sao_Paulo');
        
        // Criar PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Configurações de estilo
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->SetMargins(15, 15, 15);
        
        // Cores
        $primaryColor = [0, 122, 51]; // #007A33
        $secondaryColor = [255, 165, 0]; // #FFA500
        $grayColor = [200, 200, 200];
        
        // Cabeçalho
        $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 15, utf8_decode('Relatório do Sistema AEE'), 0, 1, 'C', true);
        $pdf->Ln(5);
        
        // Data e Hora atual
        $dataHoraAtual = new DateTime();
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, utf8_decode('Data: ') . $dataHoraAtual->format('d/m/Y'), 0, 1, 'L');
        $pdf->Cell(0, 10, utf8_decode('Hora: ') . $dataHoraAtual->format('H:i:s'), 0, 1, 'L');
        $pdf->Ln(10);

        // Conteúdo específico do relatório
        switch ($tipo) {
            case 'equipamentos':
                $sql = "SELECT nome, descricao, quantidade_disponivel, disponivel 
                        FROM equipamentos 
                        ORDER BY nome";
                $stmt = getDatabaseConnection()->prepare($sql);
                $stmt->execute();
                $equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                $pdf->Cell(0, 10, utf8_decode('Relatório de Equipamentos Cadastrados'), 0, 1, 'L');
                $pdf->Ln(5);

                if (empty($equipamentos)) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(0, 10, utf8_decode('Nenhum equipamento cadastrado.'), 0, 1);
                } else {
                    // Cabeçalho da tabela
                    $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                    $pdf->SetTextColor(255, 255, 255);
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(60, 10, utf8_decode('Nome'), 1, 0, 'C', true);
                    $pdf->Cell(70, 10, utf8_decode('Descrição'), 1, 0, 'C', true);
                    $pdf->Cell(29, 10, utf8_decode('Quantidade'), 1, 0, 'C', true);
                    $pdf->Cell(25, 10, utf8_decode('Status'), 1, 1, 'C', true);

                    // Dados da tabela
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', '', 10);
                    $fill = false;
                    foreach ($equipamentos as $equip) {
                        $pdf->SetFillColor($fill ? 248 : 255, $fill ? 249 : 255, $fill ? 250 : 255);
                        $pdf->Cell(60, 10, utf8_decode($equip['nome']), 1, 0, 'L', $fill);
                        $pdf->Cell(70, 10, utf8_decode($equip['descricao']), 1, 0, 'L', $fill);
                        $pdf->Cell(29, 10, $equip['quantidade_disponivel'], 1, 0, 'C', $fill);
                        $pdf->Cell(25, 10, $equip['disponivel'] ? utf8_decode('Ativo') : utf8_decode('Inativo'), 1, 1, 'C', $fill);
                        $fill = !$fill;
                    }
                }
                break;

            case 'turma':
                $turma_id = $_GET['turma_id'] ?? null;
                error_log('TURMA_ID NO RELATORIO: ' . $turma_id);
                if (!$turma_id) {
                    throw new Exception('Turma não informada');
                }
                // Buscar todos os alunos da turma
                $sqlAlunos = "SELECT id, nome FROM alunos WHERE turma_id = :turma_id ORDER BY nome";
                $stmtAlunos = getDatabaseConnection()->prepare($sqlAlunos);
                $stmtAlunos->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
                $stmtAlunos->execute();
                $alunos = $stmtAlunos->fetchAll(PDO::FETCH_ASSOC);

                // Buscar nome da turma
                $sqlTurma = "SELECT nome FROM turmas WHERE id = :turma_id";
                $stmtTurma = getDatabaseConnection()->prepare($sqlTurma);
                $stmtTurma->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
                $stmtTurma->execute();
                $nome_turma = $stmtTurma->fetchColumn();
                $nome_turma = str_replace(['??', '??'], ['º', 'ª'], $nome_turma);
                $nome_turma = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $nome_turma);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                $pdf->Cell(0, 10, utf8_decode('Relatório por Turma'), 0, 1, 'L');
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                $pdf->Cell(0, 10, 'Turma: ' . $nome_turma, 0, 1, 'L');
                $pdf->Ln(2);

                if (empty($alunos)) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->Cell(0, 10, utf8_decode('Nenhum aluno encontrado nesta turma.'), 0, 1);
                } else {
                    foreach ($alunos as $aluno) {
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->Cell(0, 8, utf8_decode('Aluno: ' . $aluno['nome']), 0, 1, 'L');
                        // Buscar agendamentos do aluno
                        $sqlAg = "SELECT ag.data_hora, ag.tipo, 
                            CASE WHEN ag.tipo = 'Equipamento' THEN e.nome WHEN ag.tipo = 'Espaço' THEN es.nome END as item_nome, ag.status
                            FROM agendamentos ag
                            LEFT JOIN equipamentos e ON ag.tipo = 'Equipamento' AND ag.id_item = e.id
                            LEFT JOIN espacos es ON ag.tipo = 'Espaço' AND ag.id_item = es.id
                            WHERE ag.aluno_id = :aluno_id
                            ORDER BY ag.data_hora DESC";
                        $stmtAg = getDatabaseConnection()->prepare($sqlAg);
                        $stmtAg->bindParam(':aluno_id', $aluno['id'], PDO::PARAM_INT);
                        $stmtAg->execute();
                        $agendamentos = $stmtAg->fetchAll(PDO::FETCH_ASSOC);
                        // Cabeçalho da tabela
                        $pdf->SetFont('Arial', 'B', 9);
                        $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                        $pdf->SetTextColor(255, 255, 255);
                        $pdf->Cell(40, 6, utf8_decode('Data/Hora'), 1, 0, 'C', true);
                        $pdf->Cell(30, 6, utf8_decode('Tipo'), 1, 0, 'C', true);
                        $pdf->Cell(60, 6, utf8_decode('Item'), 1, 0, 'C', true);
                        $pdf->Cell(30, 6, utf8_decode('Status'), 1, 1, 'C', true);
                        $pdf->SetFont('Arial', '', 9);
                        $pdf->SetTextColor(0, 0, 0);
                        if (empty($agendamentos)) {
                            $pdf->Cell(160, 6, utf8_decode('Nenhum agendamento encontrado para este aluno.'), 1, 1, 'C');
                        } else {
                            foreach ($agendamentos as $ag) {
                                $pdf->Cell(40, 6, date('d/m/Y H:i', strtotime($ag['data_hora'])), 1, 0, 'C');
                                $pdf->Cell(30, 6, utf8_decode($ag['tipo']), 1, 0, 'C');
                                $pdf->Cell(60, 6, utf8_decode($ag['item_nome']), 1, 0, 'L');
                                $pdf->Cell(30, 6, utf8_decode($ag['status']), 1, 1, 'C');
                            }
                        }
                        $pdf->Ln(2);
                    }
                }
                break;

            case 'espacos':
                $sql = "SELECT nome, descricao, quantidade_disponivel, disponivel 
                        FROM espacos 
                        ORDER BY nome";
                $stmt = getDatabaseConnection()->prepare($sql);
                $stmt->execute();
                $espacos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                $pdf->Cell(0, 10, utf8_decode('Relatório de Espaços Cadastrados'), 0, 1, 'L');
                $pdf->Ln(5);

                if (empty($espacos)) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(0, 10, utf8_decode('Nenhum espaço cadastrado.'), 0, 1);
                } else {
                    // Cabeçalho da tabela
                    $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                    $pdf->SetTextColor(255, 255, 255);
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(60, 10, utf8_decode('Nome'), 1, 0, 'C', true);
                    $pdf->Cell(70, 10, utf8_decode('Descrição'), 1, 0, 'C', true);
                    $pdf->Cell(29, 10, utf8_decode('Capacidade'), 1, 0, 'C', true);
                    $pdf->Cell(25, 10, utf8_decode('Status'), 1, 1, 'C', true);

                    // Dados da tabela
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', '', 10);
                    $fill = false;
                    foreach ($espacos as $espaco) {
                        $pdf->SetFillColor($fill ? 248 : 255, $fill ? 249 : 255, $fill ? 250 : 255);
                        $pdf->Cell(60, 10, utf8_decode($espaco['nome']), 1, 0, 'L', $fill);
                        $pdf->Cell(70, 10, utf8_decode($espaco['descricao']), 1, 0, 'L', $fill);
                        $pdf->Cell(29, 10, $espaco['quantidade_disponivel'], 1, 0, 'C', $fill);
                        $pdf->Cell(25, 10, $espaco['disponivel'] ? utf8_decode('Ativo') : utf8_decode('Inativo'), 1, 1, 'C', $fill);
                        $fill = !$fill;
                    }
                }
                break;

            case 'agendamentos':
                // Buscar agendamentos com informações das turmas
                $sql = "SELECT a.id, a.tipo, a.data_hora, a.status, 
                        t.nome as turma_nome,
                        CASE 
                            WHEN a.tipo = 'Equipamento' THEN e.nome
                            WHEN a.tipo = 'Espaço' THEN es.nome
                        END as item_nome,
                        al.nome as aluno_nome
                        FROM agendamentos a
                        JOIN turmas t ON a.turma_id = t.id
                        JOIN alunos al ON a.aluno_id = al.id
                        LEFT JOIN equipamentos e ON a.tipo = 'Equipamento' AND a.id_item = e.id
                        LEFT JOIN espacos es ON a.tipo = 'Espaço' AND a.id_item = es.id
                        ORDER BY a.data_hora DESC";
                
                $stmt = getDatabaseConnection()->prepare($sql);
                $stmt->execute();
                $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $pdf->SetFont('Arial', 'B', 14);
                $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                $pdf->Cell(0, 10, utf8_decode('Relatório de Agendamentos'), 0, 1, 'L');
                $pdf->Ln(5);

                if (empty($agendamentos)) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->Cell(0, 10, utf8_decode('Nenhum agendamento encontrado.'), 0, 1);
                } else {
                    // Cabeçalho da tabela
                    $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
                    $pdf->SetTextColor(255, 255, 255);
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->Cell(30, 10, utf8_decode('Data/Hora'), 1, 0, 'C', true);
                    $pdf->Cell(30, 10, utf8_decode('Tipo'), 1, 0, 'C', true);
                    $pdf->Cell(26, 10, utf8_decode('Item'), 1, 0, 'C', true);
                    $pdf->Cell(25, 10, utf8_decode('Turma'), 1, 0, 'C', true);
                    $pdf->Cell(45, 10, utf8_decode('Aluno'), 1, 0, 'C', true);
                    $pdf->Cell(25, 10, utf8_decode('Status'), 1, 1, 'C', true);

                    // Dados da tabela
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', '', 10);
                    $fill = false;
                    foreach ($agendamentos as $agendamento) {
                        $pdf->SetFillColor($fill ? 248 : 255, $fill ? 249 : 255, $fill ? 250 : 255);
                        $pdf->Cell(30, 10, date('d/m/Y H:i', strtotime($agendamento['data_hora'])), 1, 0, 'C', $fill);
                        $pdf->Cell(30, 10, utf8_decode($agendamento['tipo']), 1, 0, 'C', $fill);
                        $pdf->Cell(26, 10, utf8_decode($agendamento['item_nome']), 1, 0, 'L', $fill);
                        $pdf->Cell(25, 10, utf8_decode($agendamento['turma_nome']), 1, 0, 'L', $fill);
                        $pdf->Cell(45, 10, utf8_decode($agendamento['aluno_nome']), 1, 0, 'L', $fill);
                        $pdf->Cell(25, 10, utf8_decode($agendamento['status']), 1, 1, 'C', $fill);
                        $fill = !$fill;
                    }
                }
                break;

            default:
                throw new Exception('Tipo de relatório inválido');
        }
        
        // Rodapé
        $pdf->Ln(20);
        $pdf->SetTextColor(102, 102, 102);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, utf8_decode('Relatório gerado automaticamente pelo Sistema AEE'), 0, 1, 'C');
        
        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="relatorio_' . $tipo . '_' . $dataHoraAtual->format('Y-m-d_H-i-s') . '.pdf"');
        $pdf->Output('I');
        exit;
    } catch (Exception $e) {
        error_log("[RelatorioController] Erro ao gerar relatório: " . $e->getMessage());
        ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Erro ao gerar relatório']);
        exit;
    }
}

ob_end_clean();
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['success' => false, 'message' => 'Ação inválida']);
exit;
?>

private function gerarHTMLRelatorio($resultEquipamentos, $resultEspacos) {
    $html = '';
    
    // Resumo dos agendamentos
    $totalEquipamentos = $resultEquipamentos->num_rows;
    $totalEspacos = $resultEspacos->num_rows;
    
    $html .= '<div class="summary">';
    $html .= '<div class="summary-box">';
    $html .= '<h3>Total de Agendamentos de Equipamentos</h3>';
    $html .= '<div class="number">' . $totalEquipamentos . '</div>';
    $html .= '</div>';
    
    $html .= '<div class="summary-box">';
    $html .= '<h3>Total de Agendamentos de Espaços</h3>';
    $html .= '<div class="number">' . $totalEspacos . '</div>';
    $html .= '</div>';
    
    $html .= '<div class="summary-box">';
    $html .= '<h3>Total Geral</h3>';
    $html .= '<div class="number">' . ($totalEquipamentos + $totalEspacos) . '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    // Seção de Agendamentos de Equipamentos
    $html .= '<div class="section">';
    $html .= '<h2>Agendamentos de Equipamentos</h2>';
    
    if ($resultEquipamentos->num_rows > 0) {
        $html .= '<table>';
        $html .= '<thead><tr><th>Data</th><th>Horário</th><th>Equipamento</th><th>Aluno</th><th>Status</th></tr></thead>';
        $html .= '<tbody>';
        
        while ($row = $resultEquipamentos->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . date('d/m/Y', strtotime($row['data_agendamento'])) . '</td>';
            $html .= '<td>' . date('H:i', strtotime($row['horario_inicio'])) . ' - ' . date('H:i', strtotime($row['horario_fim'])) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['equipamento_nome']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['aluno_nome']) . '</td>';
            $html .= '<td><span class="status ' . $row['status'] . '">' . ucfirst($row['status']) . '</span></td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
    } else {
        $html .= '<p class="no-data">Nenhum agendamento de equipamento encontrado.</p>';
    }
    
    $html .= '</div>';
    
    // Seção de Agendamentos de Espaços
    $html .= '<div class="section">';
    $html .= '<h2>Agendamentos de Espaços</h2>';
    
    if ($resultEspacos->num_rows > 0) {
        $html .= '<table>';
        $html .= '<thead><tr><th>Data</th><th>Horário</th><th>Espaço</th><th>Aluno</th><th>Status</th></tr></thead>';
        $html .= '<tbody>';
        
        while ($row = $resultEspacos->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . date('d/m/Y', strtotime($row['data_agendamento'])) . '</td>';
            $html .= '<td>' . date('H:i', strtotime($row['horario_inicio'])) . ' - ' . date('H:i', strtotime($row['horario_fim'])) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['espaco_nome']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['aluno_nome']) . '</td>';
            $html .= '<td><span class="status ' . $row['status'] . '">' . ucfirst($row['status']) . '</span></td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
    } else {
        $html .= '<p class="no-data">Nenhum agendamento de espaço encontrado.</p>';
    }
    
    $html .= '</div>';
    
    return $html;
}