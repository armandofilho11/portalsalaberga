<?php
session_start();


if (isset($_SESSION['refresh']) && $_SESSION['refresh'] == 0) {
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("refresh:0");
    $_SESSION['refresh'] = 1;
}


// Pega o nome do arquivo atual
$current_page = basename($_SERVER['PHP_SELF']);

// Função para verificar se já está na página correta
function isCurrentPage($page)
{
    global $current_page;
    return $current_page === $page;
}

// Só redireciona se NÃO estiver na página correta
if (isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['ss_usuario'])) {
    if (!isCurrentPage('inicio.php') && !isCurrentPage('index.php')) {
        header('Location: ../views/inicio.php');
        exit();
    }
} else if (isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['ss_adm'])) {
    if (!isCurrentPage('inicio_ADM.php') && !isCurrentPage('index.php')) {
        header('Location: ../views/inicio_ADM.php');
        exit();
    }
} else if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    if (!isCurrentPage('index.php')) {
        header('Location: ../../../main/views/autenticacao/login.php'); // Corrigido para um nível acima
        exit();
    }
}
?>
