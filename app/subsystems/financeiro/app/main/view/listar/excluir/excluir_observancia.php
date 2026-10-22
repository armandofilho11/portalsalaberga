<?php
$conn = new mysqli('localhost', 'root', '', 'u750204740_sistemafinanceiro');
if ($conn->connect_error) die("Erro na conexão: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && is_array($_POST['ids'])) {
    $ids = array_map('intval', $_POST['ids']);
    $ids_str = implode(',', $ids);
    
    // Primeiro, buscar os caminhos dos PDFs
    $sql = "SELECT caminho_pdf FROM observancias WHERE id IN ($ids_str)";
    $result = $conn->query($sql);
    
    if ($result) {
        // Excluir os arquivos PDF
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['caminho_pdf'])) {
                // Log do caminho original
                error_log("Caminho original do PDF: " . $row['caminho_pdf']);
                
                // Tentar diferentes caminhos possíveis
                $caminhos_possiveis = [
                    $row['caminho_pdf'], // Caminho original
                    '../gerar/declaracoes/' . basename($row['caminho_pdf']), // Caminho relativo
                    '../../gerar/declaracoes/' . basename($row['caminho_pdf']), // Caminho relativo alternativo
                    'C:/xampp/htdocs/estudo.php/financeiro/view/gerar/declaracoes/' . basename($row['caminho_pdf']) // Caminho absoluto
                ];
                
                $arquivo_excluido = false;
                foreach ($caminhos_possiveis as $caminho) {
                    error_log("Tentando excluir arquivo em: " . $caminho);
                    if (file_exists($caminho)) {
                        error_log("Arquivo encontrado em: " . $caminho);
                        if (unlink($caminho)) {
                            error_log("Arquivo excluído com sucesso de: " . $caminho);
                            $arquivo_excluido = true;
                            break;
                        } else {
                            error_log("Falha ao excluir arquivo de: " . $caminho);
                        }
                    }
                }
                
                if (!$arquivo_excluido) {
                    error_log("Não foi possível excluir o arquivo em nenhum dos caminhos tentados");
                }
            }
        }
        
        // Excluir os registros do banco de dados
        $sql = "DELETE FROM observancias WHERE id IN ($ids_str)";
        if ($conn->query($sql)) {
            header("Location: ../listar_observancia.php?success=1");
            exit;
        } else {
            error_log("Erro ao excluir do banco: " . $conn->error);
            header("Location: ../listar_observancia.php?error=1&msg=" . urlencode($conn->error));
            exit;
        }
    } else {
        error_log("Erro na consulta: " . $conn->error);
        header("Location: ../listar_observancia.php?error=1&msg=" . urlencode($conn->error));
        exit;
    }
} else {
    header("Location: ../listar_observancia.php");
    exit;
}
$conn->close();
?> 