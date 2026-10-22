<?php
require_once(__DIR__ . '/../config/connect.php');
require_once(__DIR__ . '/../../subsystems/gerenciador_usuario/models/model.solicitacoes_primeiro_acesso.php');

class model_usuario extends connect
{
    private string $table1;
    private string $table2;
    private string $table3;
    private string $table4;
    private string $table5;

    function __construct()
    {
        parent::__construct();
        $table = require(__DIR__ . '/../../../.env/tables.php');
        $this->table1 = $table['salaberga_users'][1];
        $this->table2 = $table['salaberga_users'][2];
        $this->table3 = $table['salaberga_users'][3];
        $this->table4 = $table['salaberga_users'][4];
        $this->table5 = $table['salaberga_users'][5];
    }

    public function solicitar_primeiro_acesso(string $cpf, string $email, string $senha): int
    {
        try {
            $solicitacoes = new model_solicitacoes_primeiro_acesso();
            $usuario = $solicitacoes->validarIdentidade($email, $cpf);
            if ($usuario === null) {
                return 2;
            }
            if ($usuario['pendente']) {
                return 3;
            }

            return $solicitacoes->criarSolicitacao($usuario['id'], $senha) ? 1 : 3;
        } catch (Throwable $e) {
            error_log('Erro ao solicitar primeiro acesso: ' . $e->getMessage());
            return 0;
        }
    }

    public function verificar_primeiro_acesso(string $cpf, string $email): bool
    {
        try {
            $solicitacoes = new model_solicitacoes_primeiro_acesso();
            return $solicitacoes->validarIdentidade($email, $cpf) !== null;
        } catch (Throwable $e) {
            error_log('Erro ao verificar primeiro acesso: ' . $e->getMessage());
            throw new RuntimeException('Não foi possível verificar os dados de primeiro acesso.', 0, $e);
        }
    }

    public function login(string $email, string $senha): int
    {
        try {
            $stmt_check = $this->connect->prepare("SELECT u.*, s.nome AS setor FROM $this->table1 u INNER JOIN $this->table2 s ON u.id_setor = s.id WHERE email = :email");
            $stmt_check->bindValue(':email', $email);
            $stmt_check->execute();

            $user = $stmt_check->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (!is_string($user['senha']) || $user['senha'] === '') {
                    return 5;
                }

                if (password_verify($senha, $user['senha'])) {

                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $dados = $this->listarPermissoesUsuario((int) $user['id']);
                    $sistemasPermitidos = [];
                    $tiposUsuarioPermitidos = [];
                    foreach ($dados as $dado) {
                        $sistemasPermitidos[$dado['nome']] = true;
                        $tiposUsuarioPermitidos[$dado['tipo']] = true;
                        $_SESSION[$dado['tipo']] = $dado['tipo'];
                        $_SESSION[$dado['nome']] = $dado['nome'];
                    }

                    $_SESSION['sistemas_permitidos'] = array_keys($sistemasPermitidos);
                    $_SESSION['tipos_usuario_permitidos'] = array_keys($tiposUsuarioPermitidos);
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['nome'] = $user['nome'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['setor'] = $user['setor'];
                    $_SESSION['foto_perfil'] = $user['foto_perfil'];
                    return 1;
                } else {
                    return 4;
                }
            } else {

                return 3;
            }
        } catch (Exception $e) {

            error_log("Erro no login: " . $e->getMessage());
            return 0;
        }
    }

    public function listarPermissoesUsuario(int $idUsuario): array
    {
        try {
            $stmt = $this->connect->prepare(
                "SELECT t.tipo, s.nome
                 FROM {$this->table4} p
                 INNER JOIN {$this->table3} t ON t.id = p.id_tipos_usuarios
                 INNER JOIN {$this->table5} s ON s.id = p.id_sistemas
                 WHERE p.id_usuarios = :id"
            );
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Erro ao carregar permissões do usuário: ' . $e->getMessage());
            throw new RuntimeException('Não foi possível carregar as permissões do usuário.', 0, $e);
        }
    }

    public function getDadosUsuario(int $id): array
    {
        try {
            $stmt = $this->connect->prepare("SELECT u.*, s.nome AS setor FROM $this->table1 u INNER JOIN $this->table2 s ON u.id_setor = s.id WHERE u.id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            return $usuario ? $usuario : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function atualizarTelefone(int $id, string $telefone): int
    {
        try {
            $stmt = $this->connect->prepare("UPDATE $this->table1 SET telefone = :telefone WHERE id = :id");
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':id', $id);

            if ($stmt->execute()) {
                return 1; // Sucesso
            } else {
                return 2; // Erro na execução
            }
        } catch (Exception $e) {
            return 0; // Erro de exceção
        }
    }

    public function uploadFotoPerfil(int $id, array $arquivo): array
    {
        try {
            // Verificar se o arquivo foi enviado
            if (!isset($arquivo['tmp_name']) || empty($arquivo['tmp_name'])) {
                return ['success' => false, 'message' => 'Nenhum arquivo foi enviado'];
            }

            // Verificar se houve erro no upload
            if ($arquivo['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Erro no upload do arquivo'];
            }

            // Verificar tipo de arquivo
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($arquivo['type'], $tiposPermitidos)) {
                return ['success' => false, 'message' => 'Tipo de arquivo não permitido. Use apenas JPG, PNG ou GIF'];
            }

            // Verificar tamanho (máximo 5MB)
            if ($arquivo['size'] > 5 * 1024 * 1024) {
                return ['success' => false, 'message' => 'Arquivo muito grande. Máximo 5MB permitido'];
            }

            // Criar pasta se não existir
            $pastaDestino = __DIR__ . '/../assets/fotos_perfil/';
            if (!is_dir($pastaDestino)) {
                mkdir($pastaDestino, 0755, true);
            }

            // Gerar nome único para o arquivo
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nomeArquivo = 'perfil_' . $id . '_' . time() . '.' . $extensao;
            $caminhoCompleto = $pastaDestino . $nomeArquivo;

            // Mover arquivo
            if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                // Atualizar banco de dados
                $stmt = $this->connect->prepare("UPDATE $this->table1 SET foto_perfil = :foto_perfil WHERE id = :id");
                $stmt->bindValue(':foto_perfil', $nomeArquivo);
                $stmt->bindValue(':id', $id);

                if ($stmt->execute()) {
                    return ['success' => true, 'message' => 'Foto atualizada com sucesso', 'filename' => $nomeArquivo];
                } else {
                    // Se falhou no banco, remover arquivo
                    unlink($caminhoCompleto);
                    return ['success' => false, 'message' => 'Erro ao salvar no banco de dados'];
                }
            } else {
                return ['success' => false, 'message' => 'Erro ao mover arquivo'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()];
        }
    }

    public function uploadFotoPerfilRecortada(int $id, string $imageData): array
    {
        try {
            // Verificar se os dados da imagem estão presentes
            if (empty($imageData)) {
                return ['success' => false, 'message' => 'Nenhuma imagem foi fornecida'];
            }

            // Extrair dados da imagem base64
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageType = $matches[1];
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            } else {
                return ['success' => false, 'message' => 'Formato de imagem inválido'];
            }

            // Decodificar base64
            $imageData = base64_decode($imageData);
            if ($imageData === false) {
                return ['success' => false, 'message' => 'Erro ao decodificar imagem'];
            }

            // Verificar se é uma imagem válida
            if (!getimagesizefromstring($imageData)) {
                return ['success' => false, 'message' => 'Arquivo não é uma imagem válida'];
            }

            // Criar pasta se não existir
            $pastaDestino = __DIR__ . '/../assets/fotos_perfil/';
            if (!is_dir($pastaDestino)) {
                mkdir($pastaDestino, 0755, true);
            }

            // Gerar nome único para o arquivo
            $nomeArquivo = 'perfil_' . $id . '_' . time() . '.jpg';
            $caminhoCompleto = $pastaDestino . $nomeArquivo;

            // Salvar arquivo
            if (file_put_contents($caminhoCompleto, $imageData)) {
                // Atualizar banco de dados
                $stmt = $this->connect->prepare("UPDATE $this->table1 SET foto_perfil = :foto_perfil WHERE id = :id");
                $stmt->bindValue(':foto_perfil', $nomeArquivo);
                $stmt->bindValue(':id', $id);

                if ($stmt->execute()) {
                    return ['success' => true, 'message' => 'Foto atualizada com sucesso', 'filename' => $nomeArquivo];
                } else {
                    // Se falhou no banco, remover arquivo
                    unlink($caminhoCompleto);
                    return ['success' => false, 'message' => 'Erro ao salvar no banco de dados'];
                }
            } else {
                return ['success' => false, 'message' => 'Erro ao salvar arquivo'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()];
        }
    }

    public function removerFotoPerfil(int $id): array
    {
        try {
            // Buscar foto atual
            $stmt = $this->connect->prepare("SELECT foto_perfil FROM $this->table1 WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario && $usuario['foto_perfil'] && $usuario['foto_perfil'] !== 'default.png') {
                // Remover arquivo físico
                $caminhoArquivo = __DIR__ . '/../assets/fotos_perfil/' . $usuario['foto_perfil'];
                if (file_exists($caminhoArquivo)) {
                    unlink($caminhoArquivo);
                }
            }

            // Atualizar banco para foto padrão
            $stmt = $this->connect->prepare("UPDATE $this->table1 SET foto_perfil = 'default.png' WHERE id = :id");
            $stmt->bindValue(':id', $id);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Foto removida com sucesso'];
            } else {
                return ['success' => false, 'message' => 'Erro ao remover foto'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()];
        }
    }
}
