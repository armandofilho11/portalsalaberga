<?php

if (isset($_GET['alt'])) {
   header('Location: ../../views/autenticacao/login.php');
   exit();  
}


if (isset($_POST['Senha']) && !empty($_POST['Senha'])) {
    // Verifica se os campos novaSenha e confSenha existem e não estão vazios
    if (isset($_POST['novaSenha']) && !empty($_POST['novaSenha']) && 
        isset($_POST['confSenha']) && !empty($_POST['confSenha'])) {
        
        $novaSenha = $_POST['novaSenha'];
        $confSenha = $_POST['confSenha'];
        $senhaAntiga = md5($_POST['Senha']);
        
        // Verifica se as senhas coincidem
        if ($novaSenha === $confSenha) {
            require_once('../../models/model_dados.php');
            // Chama a função para alterar a senha e armazena o resultado
            $resultado = alterarSenha($novaSenha, $confSenha, $senhaAntiga);
            echo $resultado;
            // Switch case para tratar os resultados
           switch ($resultado) {
                case 'sucesso=1':
                    header('Location: ../../views/autenticacao/login.php');
                    break;
                    
                case 'erro=1':
                    header('Location: ../../views/autenticacao/perfil.php?erro=1');
                    break;
                    
                case 'erro=2':
                    header('Location: ../../views/autenticacao/perfil.php?erro=2');
                    break;
            }
        } else {
            // Senhas não coincidem
            header('Location: ../../views/autenticacao/perfil.php?erro=1');
            exit();
        }
    } else {
        // Campos obrigatórios não preenchidos
        header('Location: ../../views/autenticacao/perfil.php?erro=2');
    }
} else {
    // Senha antiga não informada
    header('Location: ../../views/autenticacao/perfil.php?erro=2');
}


?>
