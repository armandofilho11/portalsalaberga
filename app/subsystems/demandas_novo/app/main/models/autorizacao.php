<?php
/**
 * Classe de Autorização Centralizada
 * 
 * Centraliza todas as regras de verificação de permissão para evitar
 * lógica espalhada pelo código. Segue o padrão de separação entre:
 * - TIPO DE USUÁRIO: Define o nível de permissão
 * - PROJETO: Define quais demandas/áreas o usuário pode acessar
 */

require_once(__DIR__ . '/../config/connect.php');

class autorizacao extends connect {
    
    private $table_usuarios = 'usuarios';
    private $table_usuario_projeto = 'usuario_projeto';
    private $table_projetos = 'projetos';
    private $table_demandas = 'demandas';

    /**
     * Verifica se o usuário é Administrador Master
     * 
     * Administrador Master possui acesso global ao sistema
     * e não precisa estar vinculado a projetos específicos.
     * 
     * @param int $id_usuario ID do usuário
     * @return bool
     */
    public function ehAdministradorMaster(int $id_usuario): bool {
        try {
            $stmt = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_usuarios} 
                 WHERE id = :id_usuario AND tipo = 'master' LIMIT 1"
            );
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao verificar administrador master: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário é um Administrador (comum)
     * 
     * Administrador comum que precisa estar vinculado a projetos
     * para ter acesso a demandas.
     * 
     * @param int $id_usuario ID do usuário
     * @return bool
     */
    public function ehAdministrador(int $id_usuario): bool {
        try {
            $stmt = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_usuarios} 
                 WHERE id = :id_usuario AND tipo = 'administrador' LIMIT 1"
            );
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao verificar administrador: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário pode acessar um projeto específico
     * 
     * Regra:
     * - Administrador Master: sempre tem acesso (retorna true)
     * - Administrador comum: verifica vínculo na tabela usuario_projeto
     * - Outros: sem acesso
     * 
     * @param int $id_usuario ID do usuário
     * @param int $id_projeto ID do projeto
     * @return bool
     */
    public function usuarioPodeAcessarProjeto(int $id_usuario, int $id_projeto): bool {
        try {
            // Administrador Master tem acesso a todos os projetos
            if ($this->ehAdministradorMaster($id_usuario)) {
                return true;
            }

            // Verificar vínculo na tabela usuario_projeto
            $stmt = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_usuario_projeto} 
                 WHERE id_usuario = :id_usuario 
                 AND id_projeto = :id_projeto 
                 AND ativo = 1 
                 LIMIT 1"
            );
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(':id_projeto', $id_projeto, PDO::PARAM_INT);
            $stmt->execute();
            
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao verificar acesso a projeto: ' . $e->getMessage());
            return false;
        }
    }

    public function usuarioPodeAcessarUsuario(int $id_usuario, int $id_usuario_alvo): bool {
        if ($this->ehAdministradorMaster($id_usuario)) {
            return true;
        }

        try {
            $stmt = $this->conn->prepare(
                "SELECT 1
                 FROM {$this->table_usuario_projeto} up_admin
                 INNER JOIN {$this->table_usuario_projeto} up_alvo
                    ON up_alvo.id_projeto = up_admin.id_projeto
                   AND up_alvo.id_usuario = :id_usuario_alvo
                   AND up_alvo.ativo = 1
                 INNER JOIN {$this->table_projetos} p
                    ON p.id = up_admin.id_projeto
                   AND p.ativo = 1
                 WHERE up_admin.id_usuario = :id_usuario
                   AND up_admin.ativo = 1
                 LIMIT 1"
            );
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_usuario_alvo' => $id_usuario_alvo
            ]);
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao verificar acesso ao usuário: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os projetos que um usuário pode acessar
     * 
     * @param int $id_usuario ID do usuário
     * @return array Array de projetos com id, nome, descricao, ativo
     */
    public function listarProjetosDoUsuario(int $id_usuario): array {
        try {
            // Administrador Master tem acesso a TODOS os projetos
            if ($this->ehAdministradorMaster($id_usuario)) {
                $stmt = $this->conn->prepare(
                    "SELECT id, nome, descricao, ativo, data_criacao, criado_por 
                     FROM {$this->table_projetos} 
                     ORDER BY nome ASC"
                );
                $stmt->execute();
            } else {
                // Usuários comuns acessam apenas projetos vinculados
                $stmt = $this->conn->prepare(
                                        "SELECT p.id, p.nome, p.descricao, p.ativo, p.data_criacao, p.criado_por
                     FROM {$this->table_projetos} p
                     INNER JOIN {$this->table_usuario_projeto} up 
                         ON up.id_projeto = p.id
                     WHERE up.id_usuario = :id_usuario 
                     AND up.ativo = 1
                     ORDER BY p.nome ASC"
                );
                $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log('Erro ao listar projetos do usuário: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Verifica se o usuário possui vínculo com algum projeto
     * 
     * Útil para determinar se deve mostrar a mensagem "sem projetos"
     * 
     * @param int $id_usuario ID do usuário
     * @return bool
     */
    public function usuarioPossuiVinculo(int $id_usuario): bool {
        try {
            $stmt = $this->conn->prepare(
                "SELECT 1 FROM {$this->table_usuario_projeto} 
                 WHERE id_usuario = :id_usuario AND ativo = 1 LIMIT 1"
            );
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao verificar vínculo de usuário: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário pode criar/editar uma demanda em um projeto
     * 
     * @param int $id_usuario ID do usuário
     * @param int $id_projeto ID do projeto
     * @return bool
     */
    public function usuarioPodeTrabalharComDemanda(int $id_usuario, int $id_projeto): bool {
        // Mesma lógica que acessar projeto
        return $this->usuarioPodeAcessarProjeto($id_usuario, $id_projeto);
    }

    /**
     * Verifica se o usuário pode ser atribuído a uma demanda
     * 
     * Regra: usuário deve estar vinculado ao projeto da demanda
     * 
     * @param int $id_usuario ID do usuário a ser atribuído
     * @param int $id_projeto ID do projeto da demanda
     * @return bool
     */
    public function usuarioPodeSerAtribuidoADemanda(int $id_usuario, int $id_projeto): bool {
        return $this->usuarioPodeAcessarProjeto($id_usuario, $id_projeto);
    }

    /**
     * Verifica se o usuário pode gerenciar (editar, ativar/desativar) um projeto
     * 
     * Apenas Administrador Master pode gerenciar projetos
     * 
     * @param int $id_usuario ID do usuário
     * @return bool
     */
    public function usuarioPodeGerenciarProjetos(int $id_usuario): bool {
        if ($this->ehAdministradorMaster($id_usuario)) {
            return true;
        }

        try {
            $stmtCampo = $this->conn->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuarios' AND COLUMN_NAME = 'pode_criar_projeto'");
            if ((int) $stmtCampo->fetchColumn() === 0) {
                return false;
            }

            $stmt = $this->conn->prepare("SELECT 1 FROM {$this->table_usuarios} WHERE id = :id_usuario AND tipo = 'administrador' AND pode_criar_projeto = 1 LIMIT 1");
            $stmt->execute([':id_usuario' => $id_usuario]);
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao verificar permissão de criação de projetos: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário pode vincular/remover usuários de um projeto.
     *
     * Administrador Master sempre pode; administradores comuns dependem da
     * permissão concedida pelo master.
     */
    public function usuarioPodeGerenciarVinculos(int $id_usuario): bool {
        if ($this->ehAdministradorMaster($id_usuario)) {
            return true;
        }

        try {
            $stmtCampo = $this->conn->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuarios' AND COLUMN_NAME = 'pode_vincular_projeto'");
            if ((int) $stmtCampo->fetchColumn() === 0) {
                return false;
            }

            $stmt = $this->conn->prepare("SELECT 1 FROM {$this->table_usuarios} WHERE id = :id_usuario AND tipo = 'administrador' AND pode_vincular_projeto = 1 LIMIT 1");
            $stmt->execute([':id_usuario' => $id_usuario]);
            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao verificar permissão de vínculos: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se o usuário pode visualizar/gerenciar solicitações de participação
     * 
     * Apenas Administrador Master pode gerenciar solicitações
     * 
     * @param int $id_usuario ID do usuário
     * @return bool
     */
    public function usuarioPodeGerenciarSolicitacoes(int $id_usuario): bool {
        return $this->ehAdministradorMaster($id_usuario);
    }

    /**
     * Retorna o tipo de usuário
     * 
     * @param int $id_usuario ID do usuário
     * @return string|null Tipo do usuário ou null
     */
    public function obterTipoUsuario(int $id_usuario): ?string {
        try {
            $stmt = $this->conn->prepare(
                "SELECT tipo FROM {$this->table_usuarios} 
                 WHERE id = :id_usuario LIMIT 1"
            );
            $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() ?: null;
        } catch (PDOException $e) {
            error_log('Erro ao obter tipo de usuário: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtém informações detalhadas sobre as permissões de um usuário
     * 
     * Útil para debug e logging
     * 
     * @param int $id_usuario ID do usuário
     * @return array Array com informações de permissões
     */
    public function obterInformacoesPermissoes(int $id_usuario): array {
        try {
            $tipo = $this->obterTipoUsuario($id_usuario);
            $ehMaster = $this->ehAdministradorMaster($id_usuario);
            $ehAdmin = $this->ehAdministrador($id_usuario);
            $projetos = $this->listarProjetosDoUsuario($id_usuario);
            $temVinculo = $this->usuarioPossuiVinculo($id_usuario);

            return [
                'id_usuario' => $id_usuario,
                'tipo' => $tipo,
                'eh_administrador_master' => $ehMaster,
                'eh_administrador' => $ehAdmin,
                'projetos' => $projetos,
                'quantidade_projetos' => count($projetos),
                'possui_vinculo' => $temVinculo,
                'pode_gerenciar_projetos' => $this->usuarioPodeGerenciarProjetos($id_usuario),
                'pode_gerenciar_vinculos' => $this->usuarioPodeGerenciarVinculos($id_usuario),
                'pode_gerenciar_solicitacoes' => $this->usuarioPodeGerenciarSolicitacoes($id_usuario)
            ];
        } catch (Exception $e) {
            error_log('Erro ao obter informações de permissões: ' . $e->getMessage());
            return [];
        }
    }
}
?>
