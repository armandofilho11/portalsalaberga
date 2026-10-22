<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once(__DIR__ . '/../models/usuario_model.php');
require_once(__DIR__ . '/../models/autorizacao.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_REQUEST['action'] ?? '';

if ($action === 'listar_usuarios' || $action === 'listar_solicitacoes_cadastro') {
    header('Content-Type: application/json; charset=utf-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {

    if ($action === 'primeiro_acesso') {
        
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $matricula = trim($_POST['matricula'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $serie = trim($_POST['serie'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $id_projeto = isset($_POST['id_projeto']) ? (int) $_POST['id_projeto'] : 0;
        
        $serie = trim((string) ($_POST['serie'] ?? ''));
        $tipo = trim((string) ($_POST['tipo'] ?? ''));

        if ($nome === '' || $email === '' || $senha === '' || $matricula === '' || $serie === '' || $tipo === '') {
            header("Location: ../index.php?erro=campos_vazios");
            exit;
        }

        if (!preg_match('/^\d{7}$/', $matricula)) {
            header("Location: ../index.php?erro=matricula_invalida");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../index.php?erro=email_invalido");
            exit;
        }

        if (strlen($senha) < 6) {
            header("Location: ../index.php?erro=senha_fraca");
            exit;
        }

        $tiposValidos = ['administrador', 'usuario'];
        if (!in_array($tipo, $tiposValidos, true)) {
            header("Location: ../index.php?erro=tipo_invalido");
            exit;
        }

        if ($tipo === 'usuario' && $id_projeto <= 0) {
            header("Location: ../index.php?erro=projeto_invalido");
            exit;
        }

        $usuario = new usuario_model();
        $result = $usuario->primeiro_acesso($nome, $matricula, $email, $senha, $serie, $tipo, $id_projeto > 0 ? $id_projeto : null);

        switch ($result) {
            case 1:
                header("Location: ../index.php?sucesso=primeiro_acesso_pendente");
                exit;

            case 2:
                header("Location: ../index.php?erro=usuario_existe");
                exit;

            case 3:
            default:
                header("Location: ../index.php?erro=erro_servidor");
                exit;
        }
    }

    else if ($action === 'login') {

        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if (empty($email) || empty($senha)) {
            header("Location: ../index.php?erro=campos_vazios");
            exit;
        }

        $usuario = new usuario_model();
        $result = $usuario->login($email, $senha);

        switch ($result['status']) {
            case 1:
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                $_SESSION['id_usuario'] = $result['usuario']['id'];
                $_SESSION['nome'] = $result['usuario']['nome'];
                $_SESSION['email'] = $result['usuario']['email'];
                $_SESSION['tipo'] = $result['usuario']['tipo'];
                $_SESSION['pontuacao'] = $result['usuario']['pontuacao'];
                $_SESSION['foto'] = $result['usuario']['foto'] ?? null;

                if (in_array($_SESSION['tipo'], ['master', 'administrador'], true)) {
                    header("Location: ../views/principal_adm.php?sucesso=login_realizado");
                    exit;
                } else {
                    header("Location: ../views/principal_aluno.php?sucesso=login_realizado");
                    exit;
                }

            case 6:
                header("Location: ../index.php?erro=cadastro_pendente");
                exit;

            case 7:
                header("Location: ../index.php?erro=cadastro_recusado");
                exit;

            case 2:
                header("Location: ../index.php?erro=usuario_nao_encontrado");
                exit;

            case 3:
                header("Location: ../index.php?erro=senha_incorreta");
                exit;

            case 5:
                header("Location: ../index.php?login=erro");
                exit;

            case 4:
            default:
                header("Location: ../index.php?erro=erro_servidor");
                exit;
        }
    }

    else if ($action === 'recuperar_senha') {

        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            header("Location: ../views/recuperar-senha.php?erro=campo_vazio");
            exit;
        }

        $usuario = new usuario_model();
        $result = $usuario->recuperar_senha($email) ?? [];

        if ($result) {
            header("Location: ../views/verificar-codigo.php?sucesso=codigo_enviado");
            exit;
        } else {
            header("Location: ../views/recuperar-senha.php?erro=email_nao_encontrado");
            exit;
        }
    }

    else if ($action === 'verificar_codigo') {

        $email = trim($_POST['email'] ?? '');
        $codigo = trim($_POST['codigo'] ?? '');

        if (empty($email) || empty($codigo)) {
            header("Location: ../views/verificar-codigo.php?erro=campo_vazio");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuario = new usuario_model();
        $result = $usuario->verificar_codigo($email, $codigo);

        if ($result['status']) {
            $_SESSION['reset_verified'] = true;
            header("Location: ../views/redefinir-senha.php?sucesso=codigo_verificado");
            exit;
        } else {
            $erro = 'erro_servidor';
            
            // Mapear mensagens de erro para parâmetros na URL
            if (strpos($result['message'], 'expirado') !== false) {
                $erro = 'codigo_expirado';
            } elseif (strpos($result['message'], 'inválido') !== false) {
                $erro = 'codigo_invalido';
            } elseif (strpos($result['message'], 'não corresponde') !== false) {
                $erro = 'email_invalido';
            }

            header("Location: ../views/verificar-codigo.php?erro={$erro}");
            exit;
        }
    }

    else if ($action === 'redefinir_senha') {

        $email = trim($_POST['email'] ?? '');
        $nova_senha = $_POST['nova_senha'] ?? '';
        $confirmar_senha = $_POST['confirmar_senha'] ?? '';

        if (empty($email) || empty($nova_senha) || empty($confirmar_senha)) {
            header("Location: ../views/redefinir-senha.php?erro=campos_vazios");
            exit;
        }

        // Verificar se as senhas coincidem
        if ($nova_senha !== $confirmar_senha) {
            header("Location: ../views/redefinir-senha.php?erro=senhas_nao_coincide");
            exit;
        }

        $usuario = new usuario_model();
        $result = $usuario->redefinir_senha($email, $nova_senha);

        if ($result['status']) {
            header("Location: ../index.php?sucesso=senha_redefinida");
            exit;
        } else {
            $erro = 'erro_servidor';
            
            // Mapear mensagens de erro para parâmetros na URL
            if (strpos($result['message'], 'verificar') !== false) {
                $erro = 'nao_verificado';
            } elseif (strpos($result['message'], 'mínimo') !== false) {
                $erro = 'senha_fraca';
            } elseif (strpos($result['message'], 'não corresponde') !== false) {
                $erro = 'email_invalido';
            }

            header("Location: ../views/redefinir-senha.php?erro={$erro}");
            exit;
        }
    }

    else if ($action === 'upload_foto') {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada.']);
            exit;
        }

        $arquivo = $_FILES['foto'] ?? null;
        if (!$arquivo || $arquivo['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['sucesso' => false, 'erro' => 'Selecione uma imagem válida.']);
            exit;
        }

        if ($arquivo['size'] > 5 * 1024 * 1024) {
            echo json_encode(['sucesso' => false, 'erro' => 'A imagem deve ter no máximo 5 MB.']);
            exit;
        }

        $informacoesImagem = @getimagesize($arquivo['tmp_name']);
        $tiposAceitos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
        if (!$informacoesImagem || !in_array($informacoesImagem['mime'], $tiposAceitos, true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Formato de imagem não suportado.']);
            exit;
        }

        $imagemOrigem = @imagecreatefromstring(file_get_contents($arquivo['tmp_name']));
        if (!$imagemOrigem) {
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível processar a imagem.']);
            exit;
        }

        $larguraOrigem = imagesx($imagemOrigem);
        $alturaOrigem = imagesy($imagemOrigem);
        $ladoOrigem = min($larguraOrigem, $alturaOrigem);
        $inicioX = (int) (($larguraOrigem - $ladoOrigem) / 2);
        $inicioY = (int) (($alturaOrigem - $ladoOrigem) / 2);
        $ladoDestino = min($ladoOrigem, 800);
        $imagemDestino = imagecreatetruecolor($ladoDestino, $ladoDestino);
        $fundo = imagecolorallocate($imagemDestino, 255, 255, 255);
        imagefill($imagemDestino, 0, 0, $fundo);
        imagecopyresampled($imagemDestino, $imagemOrigem, 0, 0, $inicioX, $inicioY, $ladoDestino, $ladoDestino, $ladoOrigem, $ladoOrigem);

        $pastaFotos = __DIR__ . '/../assets/fotos_usuarios';
        if (!is_dir($pastaFotos) && !mkdir($pastaFotos, 0755, true)) {
            imagedestroy($imagemOrigem);
            imagedestroy($imagemDestino);
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível preparar a pasta de fotos.']);
            exit;
        }

        $idUsuario = (int) $_SESSION['id_usuario'];
        $nomeArquivo = 'usuario_' . $idUsuario . '_' . bin2hex(random_bytes(6)) . '.jpg';
        $caminhoArquivo = $pastaFotos . DIRECTORY_SEPARATOR . $nomeArquivo;
        $caminhoBanco = 'fotos_usuarios/' . $nomeArquivo;
        $salvouArquivo = imagejpeg($imagemDestino, $caminhoArquivo, 88);
        imagedestroy($imagemOrigem);
        imagedestroy($imagemDestino);

        if (!$salvouArquivo) {
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível salvar a foto.']);
            exit;
        }

        $usuario = new usuario_model();
        $resultado = $usuario->atualizar_foto_usuario($idUsuario, $caminhoBanco);
        if ($resultado['status'] !== 1) {
            @unlink($caminhoArquivo);
            echo json_encode(['sucesso' => false, 'erro' => $resultado['message']]);
            exit;
        }

        if (!empty($resultado['foto_anterior'])) {
            $fotoAnterior = basename($resultado['foto_anterior']);
            $arquivoAnterior = $pastaFotos . DIRECTORY_SEPARATOR . $fotoAnterior;
            if (is_file($arquivoAnterior)) {
                @unlink($arquivoAnterior);
            }
        }

        $_SESSION['foto'] = $caminhoBanco;
        echo json_encode(['sucesso' => true, 'foto' => $caminhoBanco, 'message' => 'Foto atualizada com sucesso.']);
        exit;
    }

    else if ($action === 'remover_foto') {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada.']);
            exit;
        }

        $idUsuario = (int) $_SESSION['id_usuario'];
        $usuario = new usuario_model();
        $resultado = $usuario->remover_foto_usuario($idUsuario);

        if ($resultado['status'] !== 1) {
            echo json_encode(['sucesso' => false, 'erro' => $resultado['message']]);
            exit;
        }

        if (!empty($resultado['foto_anterior'])) {
            $arquivoAnterior = __DIR__ . '/../assets/fotos_usuarios/' . basename($resultado['foto_anterior']);
            if (is_file($arquivoAnterior)) {
                @unlink($arquivoAnterior);
            }
        }

        $_SESSION['foto'] = null;
        echo json_encode(['sucesso' => true, 'message' => 'Foto removida com sucesso.']);
        exit;
    }

    else if ($action === 'listar_ranking_pontuacao') {
        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['id_usuario'])) {
            http_response_code(401);
            echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada.']);
            exit;
        }

        $usuario = new usuario_model();
        $idProjeto = isset($_GET['id_projeto']) && $_GET['id_projeto'] !== '' ? (int) $_GET['id_projeto'] : null;
        echo json_encode([
            'sucesso' => true,
            'dados' => $usuario->listar_ranking_pontuacao((int) $_SESSION['id_usuario'], $idProjeto)
        ]);
        exit;
    }

    else if ($action === 'listar_usuarios') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
            exit;
        }

        $pesquisa = trim((string) ($_GET['pesquisa'] ?? ''));
        $tipo = trim((string) ($_GET['tipo'] ?? ''));
        $ativo = isset($_GET['ativo']) ? (int) $_GET['ativo'] : null;
        $idProjeto = isset($_GET['id_projeto']) && $_GET['id_projeto'] !== '' ? (int) $_GET['id_projeto'] : null;

        try {
            $usuario = new usuario_model();
            $incluirAdministradores = ($_SESSION['tipo'] ?? '') === 'master';
            $dados = $usuario->listar_usuarios($pesquisa !== '' ? $pesquisa : null, $tipo !== '' ? $tipo : null, $ativo !== '' && $ativo !== null ? $ativo : null, (int) $_SESSION['id_usuario'], $idProjeto, $incluirAdministradores, (int) $_SESSION['id_usuario']);
        } catch (Throwable $e) {
            error_log('Erro ao carregar usuários: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível carregar os usuários.']);
            exit;
        }

        echo json_encode(['sucesso' => true, 'dados' => $dados]);
        exit;
    }

    else if ($action === 'resumo_usuarios') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
            exit;
        }

        $usuario = new usuario_model();
        echo json_encode(['sucesso' => true, 'dados' => $usuario->obter_resumo_usuarios()]);
        exit;
    }

    else if ($action === 'listar_solicitacoes_cadastro') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
            exit;
        }

        try {
            $usuario = new usuario_model();
            $dados = $usuario->listar_solicitacoes_cadastro((int) $_SESSION['id_usuario']);
        } catch (Throwable $e) {
            error_log('Erro ao carregar solicitações de cadastro: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível carregar as solicitações de cadastro.']);
            exit;
        }

        echo json_encode(['sucesso' => true, 'dados' => $dados]);
        exit;
    }

    else if ($action === 'decidir_solicitacao_cadastro') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = (int) ($dados['id'] ?? 0);
        $decisao = trim((string) ($dados['decisao'] ?? ''));
        $usuario = new usuario_model();
        $resultado = $usuario->decidir_solicitacao_cadastro($id, $decisao, (int) $_SESSION['id_usuario']);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao decidir solicitação.')
        ]);
        exit;
    }

    else if ($action === 'obter_usuario') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido.']);
            exit;
        }

        $usuario = new usuario_model();
        $dados = $usuario->obter_usuario_por_id($id);

        $auth = new autorizacao();
        if (!$auth->usuarioPodeAcessarUsuario((int) $_SESSION['id_usuario'], $id)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Você não tem acesso a este usuário.']);
            exit;
        }

        echo json_encode(['sucesso' => !empty($dados), 'dados' => $dados, 'erro' => empty($dados) ? 'Usuário não encontrado.' : null]);
        exit;
    }

    else if ($action === 'cadastrar_usuario') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem cadastrar usuários.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $usuario = new usuario_model();
        $resultado = $usuario->cadastrar_usuario($dados);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao cadastrar usuário.')
        ]);
        exit;
    }

    else if ($action === 'atualizar_usuario') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem editar usuários.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = (int) ($dados['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido.']);
            exit;
        }

        if ((int) $_SESSION['id_usuario'] === $id) {
            echo json_encode(['sucesso' => false, 'erro' => 'Você não pode editar sua própria conta a partir desta ação.']);
            exit;
        }

        $auth = new autorizacao();
        if (!$auth->usuarioPodeAcessarUsuario((int) $_SESSION['id_usuario'], $id)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Você não tem acesso a este usuário.']);
            exit;
        }

        $usuario = new usuario_model();
        $resultado = $usuario->atualizar_usuario($id, $dados);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao atualizar usuário.')
        ]);
        exit;
    }

    else if ($action === 'alterar_tipo_usuario') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem alterar tipos.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = (int) ($dados['id'] ?? 0);
        $tipo = trim((string) ($dados['tipo'] ?? ''));

        if ($id <= 0 || $id === (int) $_SESSION['id_usuario']) {
            echo json_encode(['sucesso' => false, 'erro' => 'Não é permitido alterar o próprio tipo de usuário.']);
            exit;
        }

        $usuario = new usuario_model();
        $resultado = $usuario->alterar_tipo_usuario($id, $tipo);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao alterar tipo.')
        ]);
        exit;
    }

    else if ($action === 'desativar_usuario') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem desativar usuários.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = (int) ($dados['id'] ?? 0);

        if ($id <= 0 || $id === (int) $_SESSION['id_usuario']) {
            echo json_encode(['sucesso' => false, 'erro' => 'Você não pode desativar a própria conta.']);
            exit;
        }

        $usuario = new usuario_model();
        $resultado = $usuario->alternar_ativo_usuario($id, 0);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao desativar usuário.')
        ]);
        exit;
    }

    else if ($action === 'ativar_usuario') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem ativar usuários.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = (int) ($dados['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido.']);
            exit;
        }

        $usuario = new usuario_model();
        $resultado = $usuario->alternar_ativo_usuario($id, 1);

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao ativar usuário.')
        ]);
        exit;
    }

    else if ($action === 'excluir_usuario') {
        if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['tipo'] ?? '', ['administrador', 'master'], true)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Apenas administradores podem excluir usuários.']);
            exit;
        }

        $dados = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = (int) ($dados['id'] ?? 0);

        if ($id <= 0 || $id === (int) $_SESSION['id_usuario']) {
            echo json_encode(['sucesso' => false, 'erro' => 'Você não pode excluir a própria conta.']);
            exit;
        }

        $usuario = new usuario_model();
        $resultado = $usuario->excluir_usuario($id);

        if ($resultado['status'] === 1 && !empty($resultado['foto'])) {
            $arquivoFoto = __DIR__ . '/../assets/fotos_usuarios/' . basename($resultado['foto']);
            if (is_file($arquivoFoto)) {
                @unlink($arquivoFoto);
            }
        }

        echo json_encode([
            'sucesso' => $resultado['status'] === 1,
            'message' => $resultado['message'] ?? null,
            'erro' => $resultado['status'] === 1 ? null : ($resultado['message'] ?? 'Erro ao excluir usuário.')
        ]);
        exit;
    }

    else if ($action === 'logout') {
        session_unset();
        session_destroy();
        header("Location: ../index.php?sucesso=logout");
        exit;
    }

}

?>