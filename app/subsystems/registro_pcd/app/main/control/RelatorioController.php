<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../model/Relatorio.php';

class RelatorioController {
    private $relatorio;

    public function __construct() {
        $this->relatorio = new Relatorio();
    }

    public function gerarRelatorio() {
        try {
            // Gerar relatório completo
            $this->relatorio->gerarRelatorio();
        } catch (Exception $e) {
            $_SESSION['mensagem_erro'] = "Erro ao gerar relatório: " . $e->getMessage();
            header("Location: ../view/visualizar.php");
            exit;
        }
    }
}

// Instanciar o controller e gerar o relatório
$controller = new RelatorioController();
$controller->gerarRelatorio();
?> 