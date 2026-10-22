<?php
require_once __DIR__ . '/../config/database.php';

class VotoModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function registrarVoto($alunoId, $voto) {
        try {
            // Registrar o voto
            $queryVoto = "INSERT INTO votos (aluno_id, voto) VALUES (:aluno_id, :voto)";
            $stmtVoto = $this->db->prepare($queryVoto);
            $stmtVoto->bindParam(':aluno_id', $alunoId);
            $stmtVoto->bindParam(':voto', $voto);
            $stmtVoto->execute();

            // Verificar se existe registro na tabela resultados
            $queryVerificar = "SELECT COUNT(*) as total FROM resultados";
            $stmtVerificar = $this->db->prepare($queryVerificar);
            $stmtVerificar->execute();
            $resultado = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if ($resultado['total'] == 0) {
                // Inserir registro inicial se não existir
                $queryInserir = "INSERT INTO resultados (total_votos, votos_sim, votos_nao) VALUES (0, 0, 0)";
                $stmtInserir = $this->db->prepare($queryInserir);
                $stmtInserir->execute();
            }

            // Atualizar resultados
            $queryAtualizar = "UPDATE resultados SET 
                total_votos = total_votos + 1,
                votos_sim = votos_sim + :voto_sim,
                votos_nao = votos_nao + :voto_nao";
            
            $stmtAtualizar = $this->db->prepare($queryAtualizar);
            $votoSim = ($voto === 'sim') ? 1 : 0;
            $votoNao = ($voto === 'nao') ? 1 : 0;
            
            $stmtAtualizar->bindParam(':voto_sim', $votoSim);
            $stmtAtualizar->bindParam(':voto_nao', $votoNao);
            $stmtAtualizar->execute();

            return [
                'success' => true,
                'message' => 'Voto registrado com sucesso!'
            ];

        } catch(PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao registrar voto: ' . $e->getMessage()
            ];
        }
    }

    public function obterResultados() {
        try {
            $query = "SELECT * FROM resultados ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $total = $resultado['total_votos'];
                $sim = $resultado['votos_sim'];
                $nao = $resultado['votos_nao'];
                
                $porcentagemSim = $total > 0 ? ($sim / $total) * 100 : 0;
                $porcentagemNao = $total > 0 ? ($nao / $total) * 100 : 0;

                return [
                    'success' => true,
                    'total_votos' => $total,
                    'votos_sim' => $sim,
                    'votos_nao' => $nao,
                    'percentual_sim' => round($porcentagemSim, 2),
                    'percentual_nao' => round($porcentagemNao, 2),
                    'data_atualizacao' => $resultado['data_atualizacao']
                ];
            }

            // Retornar chaves padrão com valores zerados caso não haja resultado
            return [
                'success' => true,
                'total_votos' => 0,
                'votos_sim' => 0,
                'votos_nao' => 0,
                'percentual_sim' => 0,
                'percentual_nao' => 0,
                'data_atualizacao' => null
            ];

        } catch(PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao obter resultados: ' . $e->getMessage()
            ];
        }
    }

    public function verificarVotoAluno($alunoId) {
        try {
            $query = "SELECT voto FROM votos WHERE aluno_id = :aluno_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':aluno_id', $alunoId);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                return [
                    'success' => true,
                    'ja_votou' => true,
                    'voto' => $resultado['voto']
                ];
            }

            return [
                'success' => true,
                'ja_votou' => false
            ];

        } catch(PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao verificar voto: ' . $e->getMessage()
            ];
        }
    }
}
?> 