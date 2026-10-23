<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
unset($_SESSION['demandas_logout_redirect']);
header("Location: app/main/");
exit();

?>