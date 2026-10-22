<?php
require_once __DIR__ . '/../../config.php';

class Opiniao {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = getPDOConnection();
            error_log("Conexão com o banco de dados estabelecida com sucesso.");
        } catch (PDOException $e) {
            error_log("Erro na conexão: " . $e->getMessage());
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public function cadastrarOpiniao($refeicao, $satisfacao, $id_usuario, $data) {
        try {
            $validRefeicoes = ['lanche-manha', 'almoco', 'lanche-tarde'];
            $validSatisfacao = ['horrivel', 'ruim', 'regular', 'bom', 'otimo'];
            
            if (!in_array($refeicao, $validRefeicoes)) {
                error_log("Refeição inválida: $refeicao");
                return ["success" => false, "message" => "Refeição inválida."];
            }
            if (!in_array($satisfacao, $validSatisfacao)) {
                error_log("Satisfação inválida: $satisfacao");
                return ["success" => false, "message" => "Satisfação inválida."];
            }

            $stmt = $this->pdo->prepare("SELECT id FROM usuario WHERE id = :id_usuario");
            $stmt->bindValue(":id_usuario", (int)$id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                error_log("Usuário não encontrado: id_usuario=$id_usuario");
                return ["success" => false, "message" => "Usuário não encontrado."];
            }

            $stmt = $this->pdo->prepare("SELECT id_opiniao FROM opiniao WHERE id_usuario = :id_usuario AND refeicao = :refeicao AND data = :data");
            $stmt->bindValue(":id_usuario", (int)$id_usuario, PDO::PARAM_INT);
            $stmt->bindValue(":refeicao", $refeicao);
            $stmt->bindValue(":data", $data);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                error_log("Usuário já votou nesta refeição hoje: id_usuario=$id_usuario, refeicao=$refeicao, data=$data");
                return ["success" => false, "message" => "Você já avaliou esta refeição hoje."];
            }

            $consulta = "INSERT INTO opiniao (refeicao, satisfacao, id_usuario, data) VALUES (:refeicao, :satisfacao, :id_usuario, :data)";
            $query = $this->pdo->prepare($consulta);
            $query->bindValue(":refeicao", $refeicao);
            $query->bindValue(":satisfacao", $satisfacao);
            $query->bindValue(":id_usuario", (int)$id_usuario, PDO::PARAM_INT);
            $query->bindValue(":data", $data);
            $query->execute();

            return ["success" => true, "message" => "Avaliação cadastrada com sucesso!"];
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar opinião: " . $e->getMessage());
            if ($e->getCode() == 23000) {
                return ["success" => false, "message" => "Você já avaliou esta refeição hoje."];
            }
            return ["success" => false, "message" => "Erro ao cadastrar a avaliação: " . $e->getMessage()];
        }
    }

    public function cadastrarSugestao($texto, $id_usuario) {
        try {
            $texto = trim($texto);
            if (empty($texto)) {
                error_log("Texto da sugestão está vazio.");
                return ["success" => false, "message" => "O texto da sugestão não pode estar vazio."];
            }

            $stmt = $this->pdo->prepare("SELECT id FROM usuario WHERE id = :id_usuario");
            $stmt->bindValue(":id_usuario", (int)$id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                error_log("Usuário não encontrado: id_usuario=$id_usuario");
                return ["success" => false, "message" => "Usuário não encontrado."];
            }

            $consulta = "INSERT INTO sugestoes (texto, id_usuario) VALUES (:texto, :id_usuario)";
            $query = $this->pdo->prepare($consulta);
            $query->bindValue(":texto", $texto);
            $query->bindValue(":id_usuario", (int)$id_usuario, PDO::PARAM_INT);
            $query->execute();

            return ["success" => true, "message" => "Sugestão enviada com sucesso!"];
        } catch (PDOException $e) {
            error_log("Erro ao enviar sugestão: " . $e->getMessage());
            return ["success" => false, "message" => "Erro ao enviar a sugestão: " . $e->getMessage()];
        }
    }

    public function getSatisfacaoCounts($data = null, $refeicao = 'all') {
        try {
            $sql = "SELECT satisfacao, COUNT(*) as total FROM opiniao WHERE data = :data";
            $params = [':data' => $data ?? date('Y-m-d')];
            
            if ($refeicao !== 'all') {
                $sql .= " AND refeicao = :refeicao";
                $params[':refeicao'] = $refeicao;
            }
            
            $sql .= " GROUP BY satisfacao";
            $query = $this->pdo->prepare($sql);
            $query->execute($params);
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            $counts = [
                'horrivel' => 0,
                'ruim' => 0,
                'regular' => 0,
                'bom' => 0,
                'otimo' => 0
            ];

            $total = 0;
            foreach ($results as $row) {
                $counts[$row['satisfacao']] = $row['total'];
                $total += $row['total'];
            }

            $percentages = [];
            foreach ($counts as $key => $value) {
                $percentages[$key] = $total > 0 ? round(($value / $total) * 100, 1) : 0;
            }

            return $percentages;
        } catch (PDOException $e) {
            error_log("Erro ao buscar dados de satisfação: " . $e->getMessage());
            return [
                'horrivel' => 0,
                'ruim' => 0,
                'regular' => 0,
                'bom' => 0,
                'otimo' => 0
            ];
        }
    }

    public function getUsersBySatisfacao($satisfacao, $data = null, $refeicao = 'all') {
        try {
            $sql = "SELECT u.nome 
                    FROM usuario u 
                    JOIN opiniao o ON u.id = o.id_usuario 
                    WHERE o.satisfacao = :satisfacao AND o.data = :data";
            $params = [
                ':satisfacao' => $satisfacao,
                ':data' => $data ?? date('Y-m-d')
            ];
            
            if ($refeicao !== 'all') {
                $sql .= " AND o.refeicao = :refeicao";
                $params[':refeicao'] = $refeicao;
            }
            
            $query = $this->pdo->prepare($sql);
            $query->execute($params);
            return $query->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuários por satisfação: " . $e->getMessage());
            return [];
        }
    }

    public function getSugestoes() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT s.texto, s.data_envio, u.nome 
                FROM sugestoes s 
                JOIN usuario u ON s.id_usuario = u.id 
                ORDER BY s.data_envio DESC
            ");
            $stmt->execute();
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("Erro ao buscar sugestões: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Erro ao carregar as sugestões: " . $e->getMessage(),
                'data' => []
            ];
        }
    }
}
?>