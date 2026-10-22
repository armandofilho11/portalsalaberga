<?php




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome']) && isset($_POST['matricula'])) {
        $nome = $_POST['nome'];
        $matricula = $_POST['matricula'];

        

        // Aqui você pode continuar com o processamento...
    } else {
        echo "<h1>Amigo, você não colocou o nome ou a matrícula</h1>";
    }
}


?>