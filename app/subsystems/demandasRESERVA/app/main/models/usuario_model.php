<?php
require_once(__DIR__ . '/../config/connect.php');
require_once(__DIR__ . '/projeto_model.php');

//inicialização do framework
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class usuario_model extends connect 
{
    //atributos

    private $table1;

    //metodos

    function __construct()
    {
        parent::__construct();
        $this->table1 = 'usuarios';
    }

    private function usuarioTemCampo(string $campo): bool
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table AND COLUMN_NAME = :campo");
            $stmt->bindValue(':table', $this->table1);
            $stmt->bindValue(':campo', $campo);
            $stmt->execute();
            return (int) $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Erro ao verificar campo do usuário: ' . $e->getMessage());
            return false;
        }
    }

    public function primeiro_acesso($nome, $matricula, $email, $senha, $serie, $tipo, ?int $id_projeto = null)
    {   
        try {
            $matricula = trim((string) $matricula);
            if (!preg_match('/^\d{7}$/', $matricula)) {
                return 3;
            }

            $this->conn->beginTransaction();
            $serie = $serie === 'nao_se_aplica' ? '0' : $serie;
            $stmt_check = $this->conn->prepare("SELECT 1 FROM {$this->table1} WHERE email = :email OR matricula = :matricula LIMIT 1");
            $stmt_check->bindValue(":email", $email);
            $stmt_check->bindValue(":matricula", $matricula, PDO::PARAM_STR);
            $stmt_check->execute();

            if ($stmt_check->fetch()) {
                $this->conn->rollBack();
                return 2;
            }

            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $temStatusCadastro = $this->usuarioTemCampo('status_cadastro');
            $temAtivo = $this->usuarioTemCampo('ativo');
            $colunas = 'nome, matricula, email, senha, serie, tipo, data_criacao';
            $valores = ':nome, :matricula, :email, :senha, :serie, :tipo, NOW()';

            if ($temStatusCadastro) {
                $colunas .= ', status_cadastro';
                $valores .= ", 'pendente'";
            }

            if ($temAtivo) {
                $colunas .= ', ativo';
                $valores .= ', 0';
            }

            $stmt_insert = $this->conn->prepare("INSERT INTO {$this->table1} ({$colunas}) VALUES ({$valores})");
            $params = [
                ':nome' => $nome,
                ':matricula' => $matricula,
                ':email' => $email,
                ':senha' => $hash,
                ':serie' => $serie,
                ':tipo' => $tipo
            ];
            $result = $stmt_insert->execute($params);

            if (!$result) {
                return 3;
            }

            $id_usuario = (int) $this->conn->lastInsertId();
            if ($id_projeto && $id_projeto > 0) {
                $stmt_projeto = $this->conn->prepare(
                    "SELECT id FROM projetos WHERE id = :id_projeto AND ativo = 1 LIMIT 1"
                );
                $stmt_projeto->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
                $stmt_projeto->execute();
                $projetoValido = $stmt_projeto->fetchColumn();

                if ($projetoValido !== false) {
                    $stmt_tipo_usuario = $this->conn->prepare(
                        "UPDATE {$this->table1} SET tipo = :tipo WHERE id = :id_usuario"
                    );
                    $stmt_tipo_usuario->execute([':tipo' => 'usuario', ':id_usuario' => $id_usuario]);

                    $stmt_solicitacao = $this->conn->prepare(
                        "INSERT INTO solicitacoes_projeto (id_usuario, id_projeto, status, mensagem) VALUES (:id_usuario, :id_projeto, 'pendente', :mensagem)"
                    );
                    $stmt_solicitacao->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmt_solicitacao->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
                    $stmt_solicitacao->bindValue(':mensagem', 'Solicitação de acesso ao projeto no primeiro acesso.');
                    $stmt_solicitacao->execute();
                } else {
                    $this->conn->rollBack();
                    return 2;
                }
            }

            $this->conn->commit();
            return 1;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log('Erro ao cadastrar usuário no primeiro acesso: ' . $e->getMessage());
            return 3;
        }
    }

    public function login($email, $senha)
    {
        try {
            // autentificação
            $stmt_check = $this->conn->prepare("SELECT * FROM {$this->table1} WHERE email = :email LIMIT 1");
            $stmt_check->bindValue(":email", $email);
            $stmt_check->execute();

            $usuario = $stmt_check->fetch(PDO::FETCH_ASSOC);

            // verificação de existencia de email
            if (!$usuario) {
                return ['status' => 2, 'usuario' => null];
            }

            if (($usuario['status_cadastro'] ?? 'aprovado') === 'pendente') {
                return ['status' => 6, 'usuario' => null];
            }

            if (($usuario['status_cadastro'] ?? 'aprovado') === 'recusado') {
                return ['status' => 7, 'usuario' => null];
            }

            if (array_key_exists('ativo', $usuario) && (int) $usuario['ativo'] !== 1) {
                return ['status' => 5, 'usuario' => null];
            }
            
            // verificação de senha
            if (!password_verify($senha, $usuario['senha'])) {
                return ['status' => 3,'usuario' => null];
            } 

            return ['status' => 1, 'usuario' => $usuario];

        } catch (PDOException $e) {
            die('Erro ao tentar fazer autenticação!');
        }

    }

    
    public function recuperar_Senha($email)
    {

        try{
            // verificação da existencia de email
            $stmt = $this->conn->prepare("SELECT id,nome FROM {$this->table1} WHERE email = :email LIMIT 1");
            $stmt->bindValue(":email",$email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return [];
            }

            // codigo de verificação
            $codigo = rand(100000,999999);

            // atribuição de valor 
            $phpmailer = require_once __DIR__ . "/../../.env/phpmailer.php";

            // buscando os arquivos necessarios
            require __DIR__ . "/../../libs/PHPmailer/src/Exception.php";
            require __DIR__ . "/../../libs/PHPmailer/src/PHPMailer.php";
            require __DIR__ . "/../../libs/PHPmailer/src/SMTP.php";

            $mail = new PHPMailer(true);
            try {
                // login
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Username = $phpmailer["local"]["email"];
                $mail->Password = $phpmailer["local"]["password"];

                $mail->SMTPSecure = "tls";

                $mail->Host = "smtp.gmail.com";
                $mail->Port = 587;

                // montagem de mensagem profissional
            $mail->setFrom($phpmailer["local"]["email"], "Sistema de Demandas");

            $mail->addAddress($email, $usuario['nome']); // usa o nome do usuário do banco

            $mail->isHTML(true);
            $mail->Subject = "Recuperar senha - Sistema de Demandas";

            $mail->Body = "
            <html>
            <head>
              <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                .header { font-size: 20px; font-weight: bold; margin-bottom: 10px; }
                .code { font-size: 24px; font-weight: bold; color: #007BFF; }
                .footer { font-size: 12px; color: #999; margin-top: 20px; }
              </style>
            </head>
            <body>
              <div class='container'>
                <div class='header'>Olá {$usuario['nome']},</div>
                <p>Recebemos uma solicitação para redefinir a senha da sua conta no Sistema de Demandas.</p>
                <p>Use o código abaixo para completar a recuperação da senha:</p>
                <p class='code'>$codigo</p>
                <p>Se você não solicitou essa alteração, ignore este e-mail.</p>
                <div class='footer'>
                  EEEP Salaberga Torquato Gomes de Matos - Sistema de Demandas
                </div>
              </div>
            </body>
            </html>
            ";

            $mail->AltBody = "Olá {$usuario['nome']},\n\nRecebemos uma solicitação para redefinir sua senha.\nSeu código de verificação é: $codigo\n\nSe você não solicitou, ignore este e-mail.\n\nSistema de Suporte";

                // envio da mensagem
                $mail->send();
                
                // armazenar código na sessão
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['reset_code'] = $codigo;
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_code_time'] = time();
                
                $check[] = $codigo;
                $check[] = 1;
                return $check;


            } catch (Exception $e) {
               echo "{$mail->ErrorInfo}"; 
               return [];
            }
            



        }catch(PDOException $e){
            error_log("Erro ao enviar e-mail: ". $e->getMessage());
            return [];
        }

    }

    public function verificar_codigo($email, $codigo)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // verificar se existe código na sessão
            if (!isset($_SESSION['reset_code']) || !isset($_SESSION['reset_email'])) {
                return ['status' => false, 'message' => 'Nenhuma solicitação de recuperação ativa'];
            }

            // verificar if email coincide
            if ($_SESSION['reset_email'] !== $email) {
                return ['status' => false, 'message' => 'Email não corresponde à solicitação'];
            }

            // verificar se o código está dentro do prazo (15 minutos)
            $tempo_decorrido = time() - $_SESSION['reset_code_time'];
            if ($tempo_decorrido > 60) { // 60 segundos = 1 minuto
                unset($_SESSION['reset_code']);
                unset($_SESSION['reset_email']);
                unset($_SESSION['reset_code_time']);
                return ['status' => false, 'message' => 'Código expirado. Solicite um novo código'];
            }

            // verificar se o código coincide
            if ((string)$_SESSION['reset_code'] !== (string)$codigo) {
                return ['status' => false, 'message' => 'Código inválido'];
            }

            // código válido - armazenar verificação
            $_SESSION['reset_verified'] = true;

            return ['status' => true, 'message' => 'Código verificado com sucesso'];

        } catch (Exception $e) {
            error_log("Erro ao verificar código: " . $e->getMessage());
            return ['status' => false, 'message' => 'Erro ao verificar código'];
        }
    }

    public function redefinir_senha($email, $novaSenha)
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // verificar se o código foi validado
            if (!isset($_SESSION['reset_verified']) || $_SESSION['reset_verified'] !== true) {
                return ['status' => false, 'message' => 'Verifique o código primeiro'];
            }

            // verificar email
            if (!isset($_SESSION['reset_email']) || $_SESSION['reset_email'] !== $email) {
                return ['status' => false, 'message' => 'Email não corresponde'];
            }

            if (!$this->conn) {
                return ['status' => false, 'message' => 'Erro de conexão com banco de dados'];
            }

            // validar força da senha
            if (strlen($novaSenha) < 6) {
                return ['status' => false, 'message' => 'Senha deve ter no mínimo 6 caracteres'];
            }

            // verificar se o usuário existe
            $sql = "SELECT id FROM {$this->table1} WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return ['status' => false, 'message' => 'Usuário não encontrado'];
            }

            // fazer hash da nova senha
            $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);

            // atualizar senha no banco
            $sql = "UPDATE {$this->table1} SET senha = :senha WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":senha", $senhaHash);
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            // limpar dados da sessão
            unset($_SESSION['reset_code']);
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_code_time']);
            unset($_SESSION['reset_verified']);

            return ['status' => true, 'message' => 'Senha alterada com sucesso'];

        } catch (PDOException $e) {
            error_log("PDOException ao redefinir senha: " . $e->getMessage());
            return ['status' => false, 'message' => 'Erro ao atualizar senha no banco'];
        } catch (Exception $e) {
            error_log("Exception ao redefinir senha: " . $e->getMessage());
            return ['status' => false, 'message' => 'Erro ao redefinir senha'];
        }
    }

    public function listar_usuarios(?string $pesquisa = null, ?string $tipo = null, ?int $ativo = null, ?int $idExcluir = null, ?int $idProjeto = null, bool $incluirAdministradores = true, ?int $idSolicitante = null): array
    {
        try {
            $temAtivo = $this->usuarioTemCampo('ativo');
            $temStatusCadastro = $this->usuarioTemCampo('status_cadastro');
            $temFoto = $this->usuarioTemCampo('foto');
                $sql = "SELECT u.id, u.nome, u.matricula, u.email, u.serie, u.tipo,
                    GREATEST(0, COALESCE((SELECT SUM(p.pontos) FROM pontuacao p WHERE p.id_usuario = u.id), 0)) AS pontuacao";
            if ($temAtivo) {
                $sql .= ", u.ativo";
            }
            if ($temStatusCadastro) {
                $sql .= ", u.status_cadastro";
            }
            if ($temFoto) {
                $sql .= ", u.foto";
            }
            if ($this->usuarioTemCampo('pode_criar_projeto')) {
                $sql .= ", u.pode_criar_projeto";
            }
            if ($this->usuarioTemCampo('pode_vincular_projeto')) {
                $sql .= ", u.pode_vincular_projeto";
            }
            $sql .= ", u.data_criacao,
                    (SELECT GROUP_CONCAT(DISTINCT p.nome ORDER BY p.nome SEPARATOR ', ')
                     FROM usuario_projeto up
                     INNER JOIN projetos p ON p.id = up.id_projeto
                     WHERE up.id_usuario = u.id AND up.ativo = 1) AS projetos_nomes,
                    (SELECT COUNT(*) FROM demandas d WHERE d.status = 'finalizada' AND d.situacao = 'concluida' AND EXISTS (SELECT 1 FROM atribuicao a WHERE a.id_demanda = d.id AND a.id_usuario = u.id)) AS demandas_concluidas,
                    (SELECT COUNT(*) FROM demandas d WHERE d.status = 'finalizada' AND d.situacao = 'nao_concluida' AND EXISTS (SELECT 1 FROM atribuicao a WHERE a.id_demanda = d.id AND a.id_usuario = u.id)) AS demandas_nao_concluidas,
                    (SELECT COUNT(*) FROM atribuicao a INNER JOIN demandas d ON d.id = a.id_demanda WHERE a.id_usuario = u.id AND d.status = 'em_andamento') AS demandas_em_andamento
                    FROM {$this->table1} u WHERE 1 = 1";

            $params = [];

            if ($pesquisa !== null && trim($pesquisa) !== '') {
                $sql .= " AND (u.nome LIKE :pesquisa OR u.email LIKE :pesquisa OR u.matricula LIKE :pesquisa)";
                $params[':pesquisa'] = '%' . trim($pesquisa) . '%';
            }

            if ($tipo !== null && $tipo !== '') {
                $sql .= " AND u.tipo = :tipo";
                $params[':tipo'] = $tipo;
            }

            if ($idProjeto !== null && $idProjeto > 0) {
                $sql .= " AND EXISTS (SELECT 1 FROM usuario_projeto up_filtro WHERE up_filtro.id_usuario = u.id AND up_filtro.id_projeto = :id_projeto_filtro AND up_filtro.ativo = 1)";
                $params[':id_projeto_filtro'] = $idProjeto;
            }

            if (!$incluirAdministradores) {
                $sql .= " AND u.tipo NOT IN ('administrador', 'master')";

                if ($idSolicitante !== null && $idSolicitante > 0) {
                    $sql .= " AND EXISTS (
                        SELECT 1
                        FROM usuario_projeto up_admin
                        INNER JOIN usuario_projeto up_alvo
                            ON up_alvo.id_projeto = up_admin.id_projeto
                           AND up_alvo.id_usuario = u.id
                           AND up_alvo.ativo = 1
                        INNER JOIN projetos p_acesso
                            ON p_acesso.id = up_admin.id_projeto
                           AND p_acesso.ativo = 1
                        WHERE up_admin.id_usuario = :id_solicitante
                          AND up_admin.ativo = 1
                    )";
                    $params[':id_solicitante'] = $idSolicitante;
                }
            }

            if ($ativo !== null && $temAtivo) {
                $sql .= " AND u.ativo = :ativo";
                $params[':ativo'] = $ativo;
            }

            if ($idExcluir !== null && $idExcluir > 0) {
                $sql .= " AND u.id != :id_excluir";
                $params[':id_excluir'] = $idExcluir;
            }

            if ($temStatusCadastro) {
                $sql .= " AND u.status_cadastro = 'aprovado'";
            }

            $sql .= " ORDER BY u.nome ASC";

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$temAtivo) {
                foreach ($usuarios as &$usuario) {
                    $usuario['ativo'] = 1;
                }
                unset($usuario);
            }
            if (!$temStatusCadastro) {
                foreach ($usuarios as &$usuario) {
                    $usuario['status_cadastro'] = 'aprovado';
                }
                unset($usuario);
            }

            return $usuarios;
        } catch (PDOException $e) {
            error_log('Erro ao listar usuários: ' . $e->getMessage());
            return [];
        }
    }

    public function listar_ranking_pontuacao(?int $idUsuario = null, ?int $idProjeto = null): array
    {
        try {
            $campoFoto = $this->usuarioTemCampo('foto') ? ', u.foto' : '';
            $filtroCadastro = $this->usuarioTemCampo('status_cadastro')
                ? " AND u.status_cadastro = 'aprovado'"
                : '';
            $filtroProjeto = '';
            $params = [];
            if ($idUsuario !== null && !(new autorizacao())->ehAdministradorMaster($idUsuario)) {
                $filtroProjeto = " AND EXISTS (
                    SELECT 1 FROM usuario_projeto up_ranking
                    WHERE up_ranking.id_usuario = u.id
                      AND up_ranking.ativo = 1
                      AND up_ranking.id_projeto IN (
                          SELECT up_usuario.id_projeto FROM usuario_projeto up_usuario
                          WHERE up_usuario.id_usuario = :id_usuario_ranking AND up_usuario.ativo = 1
                      )
                )";
                $params[':id_usuario_ranking'] = $idUsuario;
                if ($idProjeto !== null && $idProjeto > 0) {
                    $filtroProjeto .= " AND EXISTS (
                        SELECT 1 FROM usuario_projeto up_usuario_projeto
                        WHERE up_usuario_projeto.id_usuario = :id_usuario_projeto
                          AND up_usuario_projeto.id_projeto = :id_projeto_selecionado
                          AND up_usuario_projeto.ativo = 1
                    )";
                    $params[':id_usuario_projeto'] = $idUsuario;
                    $params[':id_projeto_selecionado'] = $idProjeto;
                    $filtroProjeto .= " AND EXISTS (
                        SELECT 1 FROM usuario_projeto up_projeto
                        WHERE up_projeto.id_usuario = u.id AND up_projeto.id_projeto = :id_projeto_ranking AND up_projeto.ativo = 1
                    )";
                    $params[':id_projeto_ranking'] = $idProjeto;
                }
            } elseif ($idProjeto !== null && $idProjeto > 0) {
                $filtroProjeto = " AND EXISTS (
                    SELECT 1 FROM usuario_projeto up_ranking
                    WHERE up_ranking.id_usuario = u.id AND up_ranking.id_projeto = :id_projeto_ranking AND up_ranking.ativo = 1
                )";
                $params[':id_projeto_ranking'] = $idProjeto;
            }
            $stmt = $this->conn->prepare(
                    "SELECT u.id, u.nome, u.tipo,
                        CASE WHEN u.tipo = 'usuario' THEN CONCAT('Usuário - ', COALESCE((SELECT GROUP_CONCAT(DISTINCT p.nome ORDER BY p.nome SEPARATOR ', ') FROM usuario_projeto up_perfil INNER JOIN projetos p ON p.id = up_perfil.id_projeto AND p.ativo = 1 WHERE up_perfil.id_usuario = u.id AND up_perfil.ativo = 1), 'Projeto não informado')) ELSE u.tipo END AS tipo_exibicao{$campoFoto},
                        COALESCE(SUM(p.pontos), 0) AS pontuacao
                 FROM {$this->table1} u
                 LEFT JOIN pontuacao p ON p.id_usuario = u.id
                 WHERE u.tipo = 'usuario'{$filtroCadastro}{$filtroProjeto}
                 GROUP BY u.id, u.nome, u.tipo
                 ORDER BY pontuacao DESC, u.nome ASC"
            );
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar ranking de pontuação: ' . $e->getMessage());
            return [];
        }
    }

    public function obter_usuario_por_id(int $id): array
    {
        try {
            $temAtivo = $this->usuarioTemCampo('ativo');
            $temFoto = $this->usuarioTemCampo('foto');
            $campos = "SELECT u.id, u.nome, u.matricula, u.email, u.serie, u.tipo,
                       GREATEST(0, COALESCE((SELECT SUM(p.pontos) FROM pontuacao p WHERE p.id_usuario = u.id), 0)) AS pontuacao";
            if ($temAtivo) {
                $campos .= ", u.ativo";
            }
            if ($temFoto) {
                $campos .= ", u.foto";
            }
            if ($this->usuarioTemCampo('pode_criar_projeto')) {
                $campos .= ", u.pode_criar_projeto";
            }
            if ($this->usuarioTemCampo('pode_vincular_projeto')) {
                $campos .= ", u.pode_vincular_projeto";
            }
                $campos .= ", u.data_criacao,
                    (SELECT GROUP_CONCAT(DISTINCT p.nome ORDER BY p.nome SEPARATOR ', ')
                     FROM usuario_projeto up
                     INNER JOIN projetos p ON p.id = up.id_projeto
                     WHERE up.id_usuario = u.id AND up.ativo = 1 AND p.ativo = 1) AS projetos_nomes,
                    (SELECT COUNT(*) FROM demandas d WHERE d.status = 'finalizada' AND d.situacao = 'concluida' AND EXISTS (SELECT 1 FROM atribuicao a WHERE a.id_demanda = d.id AND a.id_usuario = u.id)) AS demandas_concluidas,
                    (SELECT COUNT(*) FROM demandas d WHERE d.status = 'finalizada' AND d.situacao = 'nao_concluida' AND EXISTS (SELECT 1 FROM atribuicao a WHERE a.id_demanda = d.id AND a.id_usuario = u.id)) AS demandas_nao_concluidas,
                    (SELECT COUNT(*) FROM atribuicao a INNER JOIN demandas d ON d.id = a.id_demanda WHERE a.id_usuario = u.id AND d.status = 'em_andamento') AS demandas_em_andamento
                    FROM {$this->table1} u WHERE u.id = :id LIMIT 1";

            $stmt = $this->conn->prepare($campos);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return [];
            }

            if (!$temAtivo) {
                $usuario['ativo'] = 1;
            }

            $stmtHistorico = $this->conn->prepare("SELECT d.id, d.titulo, d.status, d.situacao, d.prioridade, d.data_criacao, d.data_finalizacao,
                    d.data_inicio, d.id_projeto
                    FROM atribuicao a
                    INNER JOIN demandas d ON d.id = a.id_demanda
                    WHERE a.id_usuario = :id_usuario
                    ORDER BY d.data_criacao DESC LIMIT 20");
            $stmtHistorico->bindValue(':id_usuario', $id, PDO::PARAM_INT);
            $stmtHistorico->execute();
            $usuario['historico'] = $stmtHistorico->fetchAll(PDO::FETCH_ASSOC);

            return $usuario;
        } catch (PDOException $e) {
            error_log('Erro ao obter usuário: ' . $e->getMessage());
            return [];
        }
    }

    public function cadastrar_usuario(array $dados): array
    {
        $nome = trim((string) ($dados['nome'] ?? ''));
        $email = trim((string) ($dados['email'] ?? ''));
        $matricula = trim((string) ($dados['matricula'] ?? ''));
        $senha = (string) ($dados['senha'] ?? '');
        $tipo = trim((string) ($dados['tipo'] ?? ''));
        $serie = trim((string) ($dados['serie'] ?? ''));
        $temAtivo = $this->usuarioTemCampo('ativo');
        $projetos = array_values(array_filter(array_map('intval', (array) ($dados['projetos'] ?? []))));

        if ($nome === '' || strlen($nome) < 3) {
            return ['status' => 2, 'message' => 'Nome inválido.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 2, 'message' => 'Email inválido.'];
        }

        if (!preg_match('/^\d{7}$/', $matricula)) {
            return ['status' => 2, 'message' => 'Matrícula deve conter 7 dígitos.'];
        }

        if (strlen($senha) < 6) {
            return ['status' => 2, 'message' => 'Senha deve ter no mínimo 6 caracteres.'];
        }

        $tiposValidos = ['master', 'administrador', 'usuario'];
        if (!in_array($tipo, $tiposValidos, true)) {
            return ['status' => 2, 'message' => 'Tipo inválido.'];
        }

        $projetos = array_values(array_unique(array_filter(array_map('intval', (array) ($dados['projetos'] ?? [])), static fn (int $id): bool => $id > 0)));
        if ($tipo === 'usuario' && empty($projetos)) {
            return ['status' => 2, 'message' => 'Selecione ao menos um projeto para este usuário.'];
        }

        $seriesValidas = ['1', '2', '3'];
        if ($tipo === 'usuario' && !in_array($serie, $seriesValidas, true)) {
            return ['status' => 2, 'message' => 'Série inválida.'];
        }

        try {
            $check = $this->conn->prepare('SELECT id FROM usuarios WHERE email = :email OR matricula = :matricula LIMIT 1');
            $check->bindValue(':email', $email);
            $check->bindValue(':matricula', $matricula, PDO::PARAM_STR);
            $check->execute();

            if ($check->fetch()) {
                return ['status' => 2, 'message' => 'Email ou matrícula já cadastrados.'];
            }

            $hash = password_hash($senha, PASSWORD_BCRYPT);
            $sql = 'INSERT INTO usuarios (nome, matricula, email, senha, serie, tipo, pontuacao';
            $params = [':nome' => $nome, ':matricula' => $matricula, ':email' => $email, ':senha' => $hash, ':serie' => $tipo === 'usuario' ? $serie : '', ':tipo' => $tipo];
            if ($temAtivo) {
                $sql .= ', ativo';
                $params[':ativo'] = 1;
            }
            $sql .= ', data_criacao) VALUES (:nome, :matricula, :email, :senha, :serie, :tipo, 0';
            if ($temAtivo) {
                $sql .= ', :ativo';
            }
            $sql .= ', NOW())';

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            $idUsuario = (int) $this->conn->lastInsertId();
            if ($tipo === 'usuario' && !empty($projetos)) {
                $projetoModel = new projeto_model();
                $idVinculador = (int) ($_SESSION['id_usuario'] ?? 0);
                if ($idVinculador > 0) {
                    $vinculo = $projetoModel->sincronizarVinculosUsuario($idUsuario, $projetos, $idVinculador);
                    if (($vinculo['status'] ?? 3) !== 1) {
                        return ['status' => 3, 'message' => $vinculo['message'] ?? 'Erro ao vincular projetos.'];
                    }
                }
            }

            return ['status' => 1, 'message' => 'Usuário cadastrado com sucesso.', 'id' => $idUsuario];
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao cadastrar usuário.'];
        }
    }

    public function listar_solicitacoes_cadastro(int $idAdministrador = 0): array
    {
        if (!$this->usuarioTemCampo('status_cadastro')) {
            return [];
        }

        try {
            $sql = "SELECT u.id, u.nome, u.matricula, u.email, u.serie, u.tipo, u.data_criacao, u.status_cadastro,
                           p.nome AS projeto_nome, sp.id_projeto
                    FROM {$this->table1} u
                    LEFT JOIN solicitacoes_projeto sp
                        ON sp.id_usuario = u.id AND sp.status = 'pendente'
                    LEFT JOIN projetos p ON p.id = sp.id_projeto
                    WHERE u.status_cadastro = 'pendente'";
            $params = [];

            $auth = new autorizacao();
            if (!$auth->ehAdministradorMaster($idAdministrador)) {
                $sql .= " AND EXISTS (
                    SELECT 1 FROM usuario_projeto up_admin
                    WHERE up_admin.id_usuario = :id_administrador
                      AND up_admin.id_projeto = sp.id_projeto
                      AND up_admin.ativo = 1
                )";
                $params[':id_administrador'] = $idAdministrador;
            }

            $sql .= ' ORDER BY u.data_criacao ASC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar solicitações de cadastro: ' . $e->getMessage());
            return [];
        }
    }

    public function decidir_solicitacao_cadastro(int $id, string $decisao, int $idAdministrador = 0): array
    {
        if (!$this->usuarioTemCampo('status_cadastro') || !$this->usuarioTemCampo('ativo')) {
            return ['status' => 2, 'message' => 'A aprovação de cadastros ainda não foi habilitada no banco.'];
        }
        if (!in_array($decisao, ['aprovado', 'recusado'], true)) {
            return ['status' => 2, 'message' => 'Decisão inválida.'];
        }

        try {
            $this->conn->beginTransaction();

            $auth = new autorizacao();
            if (!$auth->ehAdministradorMaster($idAdministrador)) {
                $stmtPermissao = $this->conn->prepare(
                    "SELECT 1
                     FROM solicitacoes_projeto sp
                     INNER JOIN usuario_projeto up
                         ON up.id_projeto = sp.id_projeto
                        AND up.id_usuario = :id_administrador
                        AND up.ativo = 1
                     INNER JOIN {$this->table1} u ON u.id = sp.id_usuario
                     WHERE sp.id_usuario = :id_usuario
                       AND sp.status = 'pendente'
                                             AND u.status_cadastro = 'pendente'
                     LIMIT 1"
                );
                $stmtPermissao->execute([
                    ':id_administrador' => $idAdministrador,
                    ':id_usuario' => $id
                ]);
                if (!$stmtPermissao->fetchColumn()) {
                    $this->conn->rollBack();
                    return ['status' => 2, 'message' => 'Você não tem permissão para decidir este cadastro.'];
                }
            }

            $stmt = $this->conn->prepare("UPDATE {$this->table1}
                SET status_cadastro = :status_cadastro, ativo = :ativo
                WHERE id = :id AND status_cadastro = 'pendente'");
            $stmt->execute([
                ':status_cadastro' => $decisao,
                ':ativo' => $decisao === 'aprovado' ? 1 : 0,
                ':id' => $id
            ]);

            if ($stmt->rowCount() === 0) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Solicitação pendente não encontrada.'];
            }

            if ($decisao === 'aprovado') {
                $stmtProjeto = $this->conn->prepare(
                    "SELECT id_projeto FROM solicitacoes_projeto
                     WHERE id_usuario = :id_usuario AND status = 'pendente'
                     ORDER BY data_solicitacao DESC LIMIT 1"
                );
                $stmtProjeto->execute([':id_usuario' => $id]);
                $solicitacao = $stmtProjeto->fetch(PDO::FETCH_ASSOC) ?: [];
                $idProjeto = (int) ($solicitacao['id_projeto'] ?? 0);
                if ($idProjeto > 0) {
                    $stmtVinculo = $this->conn->prepare(
                        "INSERT INTO usuario_projeto (id_usuario, id_projeto, ativo)
                         VALUES (:id_usuario, :id_projeto, 1)
                         ON DUPLICATE KEY UPDATE ativo = 1"
                    );
                    $stmtVinculo->execute([
                        ':id_usuario' => $id,
                        ':id_projeto' => $idProjeto
                    ]);

                }
            }

            $stmtSolicitacao = $this->conn->prepare(
                "UPDATE solicitacoes_projeto
                 SET status = :status, id_administrador_decisao = :id_administrador, data_decisao = NOW()
                 WHERE id_usuario = :id_usuario AND status = 'pendente'"
            );
            $stmtSolicitacao->execute([
                ':status' => $decisao === 'aprovado' ? 'aprovada' : 'recusada',
                ':id_administrador' => $idAdministrador > 0 ? $idAdministrador : null,
                ':id_usuario' => $id
            ]);

            $this->conn->commit();
            return ['status' => 1, 'message' => $decisao === 'aprovado'
                ? 'Cadastro aprovado com sucesso. O usuário já pode acessar o sistema.'
                : 'Cadastro recusado. O usuário não poderá acessar o sistema.'];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log('Erro ao atualizar solicitação de cadastro: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao atualizar solicitação.'];
        }
    }

    public function atualizar_usuario(int $id, array $dados): array
    {
        $nome = trim((string) ($dados['nome'] ?? ''));
        $email = trim((string) ($dados['email'] ?? ''));
        $matricula = trim((string) ($dados['matricula'] ?? ''));
        $tipo = trim((string) ($dados['tipo'] ?? ''));
        $serie = trim((string) ($dados['serie'] ?? ''));
        $senha = isset($dados['senha']) ? (string) $dados['senha'] : '';
        $podeCriarProjeto = !empty($dados['pode_criar_projeto']) ? 1 : 0;
        $podeVincularProjeto = !empty($dados['pode_vincular_projeto']) ? 1 : 0;
        $projetos = array_values(array_filter(array_map('intval', (array) ($dados['projetos'] ?? []))));

        if ($nome === '' || strlen($nome) < 3) {
            return ['status' => 2, 'message' => 'Nome inválido.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 2, 'message' => 'Email inválido.'];
        }

        if (!preg_match('/^\d{7}$/', $matricula)) {
            return ['status' => 2, 'message' => 'Matrícula deve conter 7 dígitos.'];
        }

        $tiposValidos = ['master', 'administrador', 'usuario'];
        if (!in_array($tipo, $tiposValidos, true)) {
            return ['status' => 2, 'message' => 'Tipo inválido.'];
        }

        $projetos = array_values(array_unique(array_filter(array_map('intval', (array) ($dados['projetos'] ?? [])), static fn (int $id): bool => $id > 0)));
        if ($tipo === 'usuario' && empty($projetos)) {
            return ['status' => 2, 'message' => 'Selecione ao menos um projeto para este usuário.'];
        }

        $seriesValidas = ['1', '2', '3'];
        if ($tipo === 'usuario' && !in_array($serie, $seriesValidas, true)) {
            return ['status' => 2, 'message' => 'Série inválida.'];
        }

        try {
            $check = $this->conn->prepare('SELECT id FROM usuarios WHERE (email = :email OR matricula = :matricula) AND id != :id LIMIT 1');
            $check->bindValue(':email', $email);
            $check->bindValue(':matricula', $matricula, PDO::PARAM_STR);
            $check->bindValue(':id', $id, PDO::PARAM_INT);
            $check->execute();

            if ($check->fetch()) {
                return ['status' => 2, 'message' => 'Já existe outro usuário com este email ou matrícula.'];
            }

            $sql = 'UPDATE usuarios SET nome = :nome, email = :email, matricula = :matricula, tipo = :tipo, serie = :serie';
            $params = [
                ':nome' => $nome,
                ':email' => $email,
                ':matricula' => $matricula,
                ':tipo' => $tipo,
                ':serie' => $tipo === 'usuario' ? $serie : '',
            ];

            if ($this->usuarioTemCampo('pode_criar_projeto') && (new autorizacao())->ehAdministradorMaster((int) ($_SESSION['id_usuario'] ?? 0))) {
                $sql .= ', pode_criar_projeto = :pode_criar_projeto';
                $params[':pode_criar_projeto'] = $tipo === 'administrador' ? $podeCriarProjeto : 0;
            }

            if ($this->usuarioTemCampo('pode_vincular_projeto') && (new autorizacao())->ehAdministradorMaster((int) ($_SESSION['id_usuario'] ?? 0))) {
                $sql .= ', pode_vincular_projeto = :pode_vincular_projeto';
                $params[':pode_vincular_projeto'] = $tipo === 'administrador' ? $podeVincularProjeto : 0;
            }

            if ($senha !== '') {
                if (strlen($senha) < 6) {
                    return ['status' => 2, 'message' => 'Senha deve ter no mínimo 6 caracteres.'];
                }
                $sql .= ', senha = :senha';
                $params[':senha'] = password_hash($senha, PASSWORD_BCRYPT);
            }

            $sql .= ' WHERE id = :id';
            $params[':id'] = $id;

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            $idVinculador = (int) ($_SESSION['id_usuario'] ?? $id);
            $podeSincronizarAdministrador = $tipo === 'administrador'
                && (new autorizacao())->usuarioPodeGerenciarVinculos($idVinculador);
            if ($tipo === 'usuario' || $podeSincronizarAdministrador) {
                $projetoModel = new projeto_model();
                $vinculo = $projetoModel->sincronizarVinculosUsuario($id, $projetos, $idVinculador);
                if (($vinculo['status'] ?? 3) !== 1) {
                    return ['status' => 3, 'message' => $vinculo['message'] ?? 'Erro ao vincular projetos.'];
                }
            }

            return ['status' => 1, 'message' => 'Usuário atualizado com sucesso.'];
        } catch (PDOException $e) {
            error_log('Erro ao atualizar usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao atualizar usuário.'];
        }
    }

    public function atualizar_foto_usuario(int $id, string $caminho): array
    {
        if (!$this->usuarioTemCampo('foto')) {
            return ['status' => 2, 'message' => 'A coluna de fotos ainda não existe no banco de dados.'];
        }

        try {
            $stmtAnterior = $this->conn->prepare('SELECT foto FROM usuarios WHERE id = :id LIMIT 1');
            $stmtAnterior->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtAnterior->execute();
            $usuario = $stmtAnterior->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            $stmt = $this->conn->prepare('UPDATE usuarios SET foto = :foto WHERE id = :id');
            $stmt->bindValue(':foto', $caminho);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ['status' => 1, 'foto_anterior' => $usuario['foto'] ?? null, 'message' => 'Foto atualizada com sucesso.'];
        } catch (PDOException $e) {
            error_log('Erro ao atualizar foto do usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao atualizar foto.'];
        }
    }

    public function remover_foto_usuario(int $id): array
    {
        if (!$this->usuarioTemCampo('foto')) {
            return ['status' => 2, 'message' => 'A coluna de fotos ainda não existe no banco de dados.'];
        }

        try {
            $stmtAnterior = $this->conn->prepare('SELECT foto FROM usuarios WHERE id = :id LIMIT 1');
            $stmtAnterior->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtAnterior->execute();
            $usuario = $stmtAnterior->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            $stmt = $this->conn->prepare('UPDATE usuarios SET foto = NULL WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0 && !empty($usuario['foto'])) {
                return ['status' => 3, 'message' => 'Não foi possível remover a foto do usuário.'];
            }

            return ['status' => 1, 'foto_anterior' => $usuario['foto'] ?? null, 'message' => 'Foto removida com sucesso.'];
        } catch (PDOException $e) {
            error_log('Erro ao remover foto do usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao remover foto.'];
        }
    }

    public function alterar_tipo_usuario(int $id, string $novoTipo): array
    {
        $tiposValidos = ['master', 'administrador', 'usuario'];
        if (!in_array($novoTipo, $tiposValidos, true)) {
            return ['status' => 2, 'message' => 'Tipo inválido.'];
        }

        try {
            $stmt = $this->conn->prepare('UPDATE usuarios SET tipo = :tipo WHERE id = :id');
            $stmt->bindValue(':tipo', $novoTipo);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            return ['status' => 1, 'message' => 'Tipo alterado com sucesso.'];
        } catch (PDOException $e) {
            error_log('Erro ao alterar tipo do usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao alterar tipo.'];
        }
    }

    public function alternar_ativo_usuario(int $id, int $novoStatus): array
    {
        if (!in_array($novoStatus, [0, 1], true)) {
            return ['status' => 2, 'message' => 'Status inválido.'];
        }

        if (!$this->usuarioTemCampo('ativo')) {
            return ['status' => 2, 'message' => 'A funcionalidade de status não está habilitada neste banco.'];
        }

        try {
            $stmt = $this->conn->prepare('UPDATE usuarios SET ativo = :ativo WHERE id = :id');
            $stmt->bindValue(':ativo', $novoStatus, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            return ['status' => 1, 'message' => $novoStatus === 1 ? 'Usuário ativado com sucesso.' : 'Usuário desativado com sucesso.'];
        } catch (PDOException $e) {
            error_log('Erro ao alterar status do usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Erro ao alterar status.'];
        }
    }

    public function excluir_usuario(int $id): array
    {
        try {
            $this->conn->beginTransaction();

            $stmtUsuario = $this->conn->prepare('SELECT foto FROM usuarios WHERE id = :id LIMIT 1 FOR UPDATE');
            $stmtUsuario->execute([':id' => $id]);
            $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            $stmtDemandas = $this->conn->prepare('UPDATE demandas SET orientador = NULL WHERE orientador = :id');
            $stmtDemandas->execute([':id' => $id]);

            $stmtPontuacao = $this->conn->prepare('DELETE FROM pontuacao WHERE id_usuario = :id');
            $stmtPontuacao->execute([':id' => $id]);

            $stmtAtribuicoes = $this->conn->prepare('DELETE FROM atribuicao WHERE id_usuario = :id');
            $stmtAtribuicoes->execute([':id' => $id]);

            $stmtExcluir = $this->conn->prepare('DELETE FROM usuarios WHERE id = :id');
            $stmtExcluir->execute([':id' => $id]);

            if ($stmtExcluir->rowCount() !== 1) {
                $this->conn->rollBack();
                return ['status' => 2, 'message' => 'Usuário não encontrado.'];
            }

            $this->conn->commit();
            return ['status' => 1, 'foto' => $usuario['foto'] ?? null, 'message' => 'Usuário excluído permanentemente.'];
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log('Erro ao excluir usuário: ' . $e->getMessage());
            return ['status' => 3, 'message' => 'Não foi possível excluir o usuário. Verifique se ele possui vínculos no sistema.'];
        }
    }

    public function obter_resumo_usuarios(): array
    {
        try {
            $temAtivo = $this->usuarioTemCampo('ativo');
            $resumo = [
                'total' => (int) $this->conn->query('SELECT COUNT(*) FROM usuarios')->fetchColumn(),
                'administradores' => (int) $this->conn->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'administrador'")->fetchColumn(),
                'masters' => (int) $this->conn->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'master'")->fetchColumn(),
                'usuarios' => (int) $this->conn->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'usuario'")->fetchColumn(),
                'ativos' => $temAtivo ? (int) $this->conn->query('SELECT COUNT(*) FROM usuarios WHERE ativo = 1')->fetchColumn() : (int) $this->conn->query('SELECT COUNT(*) FROM usuarios')->fetchColumn(),
                'inativos' => $temAtivo ? (int) $this->conn->query('SELECT COUNT(*) FROM usuarios WHERE ativo = 0')->fetchColumn() : 0,
            ];
            return $resumo;
        } catch (PDOException $e) {
            error_log('Erro ao obter resumo: ' . $e->getMessage());
            return ['total' => 0, 'administradores' => 0, 'masters' => 0, 'usuarios' => 0, 'ativos' => 0, 'inativos' => 0];
        }
    }
}

?>