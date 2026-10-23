<?php
//redirecionamento para o qrcodes por estantes e prateleiras
if (isset($_POST['estante']) && isset($_POST['prateleira']) && !empty($_POST['estante']) && !empty($_POST['prateleira'])) {

    $estante = $_POST['estante'];
    $prateleira = $_POST['prateleira'];

    header('Location: ../index.php?section=qrcode_geral&' . http_build_query([
        'estante' => $estante,
        'prateleira' => 'p' . $prateleira
    ]));
} 
//redirecionamento para os qrcodes especificos 
else if (isset($_POST['titulo']) && !empty($_POST['titulo'])) {

    $titulo = $_POST['titulo'];
    $dados = [
        'titulo_livro' => $titulo
    ];

    $http = http_build_query($dados);
    header('Location: ../index.php?section=qrcode_especifico&gerar=1&' . $http);
    exit();
}
