<?php
require_once '../config/session.php';

// Registrar a atividade de logout
registrarAtividade('Logout');

// Destruir a sessão
destruirSessao();

// Redirecionar para a página de login
header('Location: ../index.php');
exit; 