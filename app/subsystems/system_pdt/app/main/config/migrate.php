<?php
/**
 * Script de migração: Adicionar coluna deletado_em para soft delete de ocorrências
 * Acesse http://localhost/portalsalaberga/app/subsystems/system_pdt/app/main/config/migrate.php
 */

session_start();

// Verificar se o usuário está autenticado (opcional - comente para primeira execução)
// if (!isset($_SESSION['id_usuario'])) {
//     die('Acesso negado. Você precisa estar autenticado.');
// }

require_once('connect.php');

try {
    $db = new connect();
    $conn = $db->getConnection();

    // Verificar se coluna já existe
    $checkCol = $conn->prepare("
        SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'ocorrencias' 
        AND COLUMN_NAME = 'deletado_em'
    ");
    $checkCol->execute();
    $colExists = $checkCol->fetch(PDO::FETCH_ASSOC);

    if ($colExists) {
        echo '<div style="background: #d4edda; padding: 20px; border-radius: 5px;">';
        echo '<h3>✓ Coluna deletado_em já existe</h3>';
        echo '<p>A migração já foi aplicada anteriormente.</p>';
        echo '</div>';
    } else {
        // Executar migração sem transação para melhor compatibilidade
        try {
            $conn->exec("ALTER TABLE ocorrencias ADD COLUMN deletado_em TIMESTAMP NULL DEFAULT NULL");
            echo '<div style="background: #c3e6cb; padding: 20px; border-radius: 5px;">';
            echo '<h3>✓ Coluna deletado_em adicionada</h3>';
            echo '</div>';
        } catch (Exception $e1) {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px;">';
            echo '<h3>✗ Erro ao adicionar coluna</h3>';
            echo '<p>' . htmlspecialchars($e1->getMessage()) . '</p>';
            echo '</div>';
        }

        try {
            $conn->exec("CREATE INDEX IF NOT EXISTS idx_ocorrencias_deletado_em ON ocorrencias(deletado_em)");
            echo '<div style="background: #d1ecf1; padding: 20px; border-radius: 5px; margin-top: 10px;">';
            echo '<h3>✓ Índice criado</h3>';
            echo '<p>Índice foi criado para melhor performance.</p>';
            echo '</div>';
        } catch (Exception $e2) {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; margin-top: 10px;">';
            echo '<h3>⚠ Aviso ao criar índice</h3>';
            echo '<p>' . htmlspecialchars($e2->getMessage()) . '</p>';
            echo '</div>';
        }

        // Migração para atribuição de PDT e conversão de contas antigas
        try {
            $checkAtribuicao = $conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'usuarios' AND COLUMN_NAME = 'atribuicao'");
            $checkAtribuicao->execute();
            $colExistsAtribuicao = $checkAtribuicao->fetch(PDO::FETCH_ASSOC);

            if ($colExistsAtribuicao) {
                echo '<div style="background: #d4edda; padding: 20px; border-radius: 5px;">';
                echo '<h3>✓ Coluna atribuicao já existe</h3>';
                echo '</div>';
            } else {
                $conn->exec("ALTER TABLE usuarios ADD COLUMN atribuicao ENUM('pdt') DEFAULT NULL");
                echo '<div style="background: #c3e6cb; padding: 20px; border-radius: 5px;">';
                echo '<h3>✓ Coluna atribuicao adicionada</h3>';
                echo '</div>';
            }

            // Converter contas antigas com tipo pdt
            try {
                $conn->exec("UPDATE usuarios SET tipo = 'professor', atribuicao = 'pdt' WHERE tipo = 'pdt'");
                echo '<div style="background: #d1ecf1; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>✓ Contas antigas pdt convertidas para professor</h3>';
                echo '</div>';
            } catch (Exception $convertErr) {
                echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>⚠ Aviso ao converter contas antigas pdt</h3>';
                echo '<p>' . htmlspecialchars($convertErr->getMessage()) . '</p>';
                echo '</div>';
            }
        } catch (Exception $e3) {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; margin-top: 10px;">';
            echo '<h3>✗ Erro na migração de atribuição</h3>';
            echo '<p>' . htmlspecialchars($e3->getMessage()) . '</p>';
            echo '</div>';
        }

        // Migração de notas para suportar disciplinas
        try {
            $checkNotasCol = $conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'notas' AND COLUMN_NAME = 'disciplina'");
            $checkNotasCol->execute();
            $colExistsNotas = $checkNotasCol->fetch(PDO::FETCH_ASSOC);

            if ($colExistsNotas) {
                echo '<div style="background: #d4edda; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>✓ Coluna disciplina já existe em notas</h3>';
                echo '</div>';
            } else {
                $conn->exec("ALTER TABLE notas ADD COLUMN disciplina VARCHAR(100) NOT NULL DEFAULT 'Geral'");
                echo '<div style="background: #c3e6cb; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>✓ Coluna disciplina adicionada em notas</h3>';
                echo '</div>';
            }

            try {
                $conn->exec("DROP INDEX uq_notas_aluno_bimestre ON notas");
            } catch (Exception $dropErr) {
                // índice antigo pode não existir; ignorar
            }

            try {
                $conn->exec("CREATE UNIQUE INDEX IF NOT EXISTS uq_notas_aluno_bimestre_disciplina ON notas(id_aluno, bimestre, disciplina)");
                echo '<div style="background: #d1ecf1; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>✓ Índice único de notas criado</h3>';
                echo '<p>Agora a combinação id_aluno + bimestre + disciplina é única.</p>';
                echo '</div>';
            } catch (Exception $idxErr) {
                echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>⚠ Aviso ao criar índice único de notas</h3>';
                echo '<p>' . htmlspecialchars($idxErr->getMessage()) . '</p>';
                echo '</div>';
            }

            try {
                $conn->exec("CREATE INDEX IF NOT EXISTS idx_notas_disciplina ON notas(disciplina)");
                echo '<div style="background: #d1ecf1; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>✓ Índice idx_notas_disciplina criado</h3>';
                echo '</div>';
            } catch (Exception $idxDiscErr) {
                echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; margin-top: 10px;">';
                echo '<h3>⚠ Aviso ao criar índice de disciplina</h3>';
                echo '<p>' . htmlspecialchars($idxDiscErr->getMessage()) . '</p>';
                echo '</div>';
            }
        } catch (Exception $e3) {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; margin-top: 10px;">';
            echo '<h3>✗ Erro na migração de notas</h3>';
            echo '<p>' . htmlspecialchars($e3->getMessage()) . '</p>';
            echo '</div>';
        }
    }

} catch (Exception $e) {
    echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px;">';
    echo '<h3>✗ Erro na migração</h3>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
?>
