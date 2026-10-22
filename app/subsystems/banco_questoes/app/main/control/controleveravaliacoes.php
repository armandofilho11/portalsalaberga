<?php
ob_start(); // Prevent any accidental output
require_once("../model/modelprofessor.php");
require_once("../model/modelquestoes.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $avaliacaoId = isset($_POST['avaliacao_id']) ? $_POST['avaliacao_id'] : null;
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $dificuldade = isset($_POST['dificuldade']) ? $_POST['dificuldade'] : '';
    $ano = isset($_POST['ano']) ? $_POST['ano'] : ''; // Pegando o ano diretamente da tabela avaliacao
    $turma = isset($_POST['turma']) ? $_POST['turma'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if (($action === 'view' || $action === 'download') && $avaliacaoId) {
        try {
            // Limpar qualquer saída anterior
            ob_clean();
            
            // Criar instância do PDF
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();

            // Configurar informações do cabeçalho
            $pdf->setHeaderText($tipo); // Tipo da avaliação como componente curricular
            
            // Configurar informações da avaliação
            $avaliacaoInfo = array(
                'nome' => $nome,
                'ano' => $ano // Usando o ano diretamente da tabela
            );
            $pdf->setEvaluationInfo($avaliacaoInfo);

            // Buscar questões da avaliação do banco de dados
            $professor = new professor();
            $questoes = $professor->buscar_questoes_avaliacao($avaliacaoId);

            // Formatar cada questão no PDF
            if ($questoes && !empty($questoes)) {
                $numero = 1;
                foreach ($questoes as $questao) {
                    if (isset($questao['id'])) {
                        // Buscar alternativas da questão
                        $alternativas = $professor->buscar_alternativas_questao($questao['id']);
                        
                        if ($alternativas && !empty($alternativas)) {
                            // Formatar e adicionar a questão ao PDF
                            $pdf->formatQuestion($numero, $questao, $alternativas);
                            $numero++;
                        }
                    }
                }

                // Gerar o PDF
                if ($action === 'download') {
                    // Download do arquivo
                    $pdf->Output('D', 'Avaliacao_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nome) . '.pdf');
                } else {
                    // Visualizar no navegador
                    $pdf->Output('I', 'Avaliacao_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nome) . '.pdf');
                }
            } else {
                throw new Exception("Nenhuma questão encontrada para esta avaliação.");
            }
        } catch (Exception $e) {
            ob_clean();
            header('Content-Type: text/html; charset=utf-8');
            echo "Erro ao gerar PDF: " . htmlspecialchars($e->getMessage());
        }
        exit();
    } elseif ($action === 'share') {
        // Implementar lógica de compartilhamento aqui
        echo "Compartilhando avaliação com a turma " . htmlspecialchars($turma);
    }
}
?>
