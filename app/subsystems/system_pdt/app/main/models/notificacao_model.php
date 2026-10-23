<?php

require_once(__DIR__ . '/../config/connect.php');

class NotificacaoModel extends connect
{
    public function __construct()
    {
        parent::__construct();
    }

    public function criar($idUsuario, $titulo, $mensagem, $tipo = 'info', $link = null, $entidadeTipo = null, $entidadeId = null)
    {
        try {
            $stmt = $this->conn->prepare(
                'INSERT INTO notificacoes (id_usuario, titulo, mensagem, tipo, link, entidade_tipo, entidade_id, criado_em) VALUES (:id_usuario, :titulo, :mensagem, :tipo, :link, :entidade_tipo, :entidade_id, NOW())'
            );

            $ok = $stmt->execute([
                ':id_usuario' => $idUsuario,
                ':titulo' => $titulo,
                ':mensagem' => $mensagem,
                ':tipo' => $tipo,
                ':link' => $link,
                ':entidade_tipo' => $entidadeTipo,
                ':entidade_id' => $entidadeId,
            ]);

            return $ok ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log('Erro ao criar notificação: ' . $e->getMessage());
            return false;
        }
    }

    public function listarParaUsuario($idUsuario, $limit = 20)
    {
        try {
            $stmt = $this->conn->prepare(
                'SELECT id_notificacao, titulo, mensagem, tipo, lida, link, entidade_tipo, entidade_id, criado_em FROM notificacoes WHERE id_usuario = :id_usuario ORDER BY lida ASC, criado_em DESC, id_notificacao DESC LIMIT :limit'
            );
            $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erro ao listar notificações: ' . $e->getMessage());
            return [];
        }
    }

    public function contarNaoLidas($idUsuario)
    {
        try {
            $stmt = $this->conn->prepare('SELECT COUNT(*) AS total FROM notificacoes WHERE id_usuario = :id_usuario AND lida = 0');
            $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Erro ao contar notificações: ' . $e->getMessage());
            return 0;
        }
    }

    public function marcarLida($idNotificacao, $idUsuario)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE notificacoes SET lida = 1 WHERE id_notificacao = :id_notificacao AND id_usuario = :id_usuario');
            $stmt->bindValue(':id_notificacao', $idNotificacao, PDO::PARAM_INT);
            $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao marcar notificação como lida: ' . $e->getMessage());
            return false;
        }
    }

    public function marcarTodasLidas($idUsuario)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE notificacoes SET lida = 1 WHERE id_usuario = :id_usuario');
            $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erro ao marcar todas as notificações: ' . $e->getMessage());
            return false;
        }
    }
}
