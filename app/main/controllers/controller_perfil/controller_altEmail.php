<?php

if (isset($_GET['alt'])) {
   header('Location: ../../views/autenticacao/login.php');
   exit();  
}


if (isset($_POST['Email']) && !empty($_POST['Email'])) {
    // Verifica se os campos novaSenha e confSenha existem e não estão vazios

        $email = $_POST['Email'];

        
        // Verifica se as senhas coincidem

            require_once('../../models/model_dados.php');
            // Chama a função para alterar a senha e armazena o resultado
            $resultado = alterarEmail($email,$senha);
            
            // Switch case para tratar os resultados
            switch ($resultado) {
                case 'sucesso=1':
                    header('Location: ../../views/autenticacao/login.php');
                    break;
        }
    }


?>
