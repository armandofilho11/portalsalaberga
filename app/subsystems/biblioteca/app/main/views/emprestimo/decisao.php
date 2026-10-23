<?php
require_once('../../models/select_model.php');

$select_model = new select_model();
$param = $_GET['id_livro'] ?? '';

if (!empty($param)) {
    // QR flow: id_livro passed via QR -> auto-decision
    $parts = explode('_', $param);
    $id_livro = intval($parts[0] ?? 0);

    if (!$id_livro) {
        http_response_code(400);
        echo 'Parâmetro id_livro inválido.';
        exit;
    }

    $livro = $select_model->id_livro_selecionado($id_livro);
    if (!$livro) {
        http_response_code(404);
        echo 'Livro não encontrado.';
        exit;
    }

    $activeCount = $select_model->count_emprestimos_ativos_por_catalogo($id_livro);
    $activeLoan = $select_model->select_emprestimo_ativo_por_catalogo($id_livro);

    // Definição do destino (emprestimo ou devolução)
        // Prioriza devolução quando houver empréstimo ativo para este catálogo
        if ($activeLoan) {
            $sessao_alvo = 'devolucao_livro';
            $url_final = 'views/emprestimo/devolucao_livro.php?id_emprestimo=' . $activeLoan['id'];
        } else if (intval($livro['quantidade']) > $activeCount) {
            $sessao_alvo = 'emprestimo_livro';
            $url_final = 'views/emprestimo/emprestimo_livro.php?id_livro=' . $id_livro;
        } else {
            $sessao_alvo = 'emprestimo_livro';
            $url_final = 'views/emprestimo/emprestimo_livro.php?id_livro=' . $id_livro;
        }
        ?>
        <script>
            // Se a página atual for a janela principal (o usuário leu o QR Code pela câmera externa)
            if (window.top === window.self) {
                // Redireciona para o index.php passando as coordenadas de qual sessão abrir
                window.location.href = "../../../index.php?action=<?php echo $sessao_alvo; ?>&target_url=<?php echo urlencode($url_final); ?>";
            } else {
                // Se já estiver rodando dentro do iframe do sistema, avisa o Dashboard pai
                window.parent.ativarSessaoPeloQrCode("<?php echo $sessao_alvo; ?>", "<?php echo $url_final; ?>");
            }
        </script>
        <?php
        exit;
    }   
?>