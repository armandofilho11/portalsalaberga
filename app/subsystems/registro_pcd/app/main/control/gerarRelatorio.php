<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../model/Relatorio.php';

try {
    // Criar instância do relatório
    $relatorio = new Relatorio();
    
    // Verificar se há filtro por período
    if (isset($_POST['data_inicio']) && isset($_POST['data_fim'])) {
        $relatorio->gerarRelatorioPorPeriodo($_POST['data_inicio'], $_POST['data_fim']);
    } else {
        // Gerar relatório completo
        $relatorio->gerarRelatorio();
    }
} catch (Exception $e) {
    $_SESSION['mensagem_erro'] = "Erro ao gerar relatório: " . $e->getMessage();
    header("Location: ../view/visualizar.php");
    exit;
}
?>