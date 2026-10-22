<?php
header('Content-Type: application/json');
if (!isset($_GET['nome'])) { 
    header('Location: ../controllers/Controller-Buscas.php?action=get_alunos_suggestions&search='); 
    exit; 
}
header('Location: ../controllers/Controller-Buscas.php?action=get_alunos_suggestions&search=' . urlencode($_GET['nome']));
exit; 