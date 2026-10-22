<?php
// Incluir arquivo de configuração
require_once 'config.php';

// Conectar ao banco de dados
$pdo = getConnection();

echo "<h1>Debug do Banco de Dados - Copa Grêmio 2025</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    .error { background: #ffe6e6; border-color: #ff9999; }
    .success { background: #e6ffe6; border-color: #99ff99; }
    .info { background: #e6f3ff; border-color: #99ccff; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>";

// 1. Verificar se as tabelas existem
echo "<div class='section'>";
echo "<h2>1. Verificação das Tabelas</h2>";

$tabelas_esperadas = ['inscricoes', 'equipes', 'equipe_membros', 'alunos'];
$tabelas_existentes = [];

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tabelas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tabelas_esperadas as $tabela) {
        if (in_array($tabela, $tabelas)) {
            echo "<p class='success'>✅ Tabela '$tabela' existe</p>";
            $tabelas_existentes[] = $tabela;
        } else {
            echo "<p class='error'>❌ Tabela '$tabela' NÃO existe</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class='error'>Erro ao verificar tabelas: " . $e->getMessage() . "</p>";
}
echo "</div>";

// 2. Verificar estrutura das tabelas existentes
if (!empty($tabelas_existentes)) {
    echo "<div class='section'>";
    echo "<h2>2. Estrutura das Tabelas</h2>";
    
    foreach ($tabelas_existentes as $tabela) {
        try {
            $stmt = $pdo->query("DESCRIBE $tabela");
            $colunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h3>Tabela: $tabela</h3>";
            echo "<table>";
            echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            
            foreach ($colunas as $coluna) {
                echo "<tr>";
                echo "<td>{$coluna['Field']}</td>";
                echo "<td>{$coluna['Type']}</td>";
                echo "<td>{$coluna['Null']}</td>";
                echo "<td>{$coluna['Key']}</td>";
                echo "<td>{$coluna['Default']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (Exception $e) {
            echo "<p class='error'>Erro ao verificar estrutura de '$tabela': " . $e->getMessage() . "</p>";
        }
    }
    echo "</div>";
}

// 3. Verificar dados nas tabelas
if (!empty($tabelas_existentes)) {
    echo "<div class='section'>";
    echo "<h2>3. Dados nas Tabelas</h2>";
    
    foreach ($tabelas_existentes as $tabela) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabela");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $resultado['total'];
            
            echo "<h3>Tabela: $tabela ($total registros)</h3>";
            
            if ($total > 0) {
                // Mostrar primeiros 5 registros
                $stmt = $pdo->query("SELECT * FROM $tabela LIMIT 5");
                $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (!empty($registros)) {
                    echo "<table>";
                    echo "<tr>";
                    foreach (array_keys($registros[0]) as $coluna) {
                        echo "<th>$coluna</th>";
                    }
                    echo "</tr>";
                    
                    foreach ($registros as $registro) {
                        echo "<tr>";
                        foreach ($registro as $valor) {
                            echo "<td>" . htmlspecialchars($valor) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    
                    if ($total > 5) {
                        echo "<p><em>Mostrando apenas os primeiros 5 registros de $total</em></p>";
                    }
                }
            } else {
                echo "<p class='info'>Tabela vazia</p>";
            }
        } catch (Exception $e) {
            echo "<p class='error'>Erro ao verificar dados de '$tabela': " . $e->getMessage() . "</p>";
        }
    }
    echo "</div>";
}

// 4. Testar a query principal
echo "<div class='section'>";
echo "<h2>4. Teste da Query Principal</h2>";

try {
    $sql = "SELECT 
                e.id as equipe_id,
                e.nome as nome_equipe,
                e.modalidade,
                GROUP_CONCAT(DISTINCT a.nome ORDER BY a.nome SEPARATOR ', ') as membros,
                COUNT(DISTINCT a.id) as total_membros
            FROM equipes e
            INNER JOIN equipe_membros em ON e.id = em.equipe_id
            INNER JOIN alunos a ON em.aluno_id = a.id
            INNER JOIN inscricoes i ON e.id = i.equipe_id AND i.status = 'aprovado'
            GROUP BY e.id, e.nome, e.modalidade
            ORDER BY e.modalidade, e.nome";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p class='info'>Query executada com sucesso</p>";
    echo "<p>Total de inscrições aprovadas encontradas: <strong>" . count($resultados) . "</strong></p>";
    
    if (!empty($resultados)) {
                 echo "<table>";
         echo "<tr><th>ID Equipe</th><th>Modalidade</th><th>Equipe</th><th>Total Membros</th><th>Membros</th></tr>";
         
         foreach ($resultados as $resultado) {
             echo "<tr>";
             echo "<td>{$resultado['equipe_id']}</td>";
             echo "<td>{$resultado['modalidade']}</td>";
             echo "<td>{$resultado['nome_equipe']}</td>";
             echo "<td>{$resultado['total_membros']}</td>";
             echo "<td>{$resultado['membros']}</td>";
             echo "</tr>";
         }
        echo "</table>";
    } else {
        echo "<p class='info'>Nenhuma inscrição aprovada encontrada</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>Erro na query principal: " . $e->getMessage() . "</p>";
}
echo "</div>";

// 5. Verificar status das inscrições
echo "<div class='section'>";
echo "<h2>5. Status das Inscrições</h2>";

try {
    $stmt = $pdo->query("SELECT status, COUNT(*) as total FROM inscricoes GROUP BY status");
    $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($status)) {
        echo "<table>";
        echo "<tr><th>Status</th><th>Quantidade</th></tr>";
        
        foreach ($status as $row) {
            $classe = ($row['status'] == 'aprovado') ? 'success' : 'info';
            echo "<tr class='$classe'>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['total']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='info'>Nenhuma inscrição encontrada</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>Erro ao verificar status: " . $e->getMessage() . "</p>";
}
echo "</div>";

// 6. Sugestões
echo "<div class='section'>";
echo "<h2>6. Sugestões</h2>";

if (empty($tabelas_existentes)) {
    echo "<p class='error'>❌ Nenhuma das tabelas esperadas existe. Você precisa criar as tabelas primeiro.</p>";
    echo "<p>Execute o arquivo SQL para criar as tabelas necessárias.</p>";
} else {
    $inscricoes_aprovadas = 0;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM inscricoes WHERE status = 'aprovada'");
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $inscricoes_aprovadas = $resultado['total'];
    } catch (Exception $e) {
        // Ignorar erro se a tabela não existir
    }
    
    if ($inscricoes_aprovadas == 0) {
        echo "<p class='info'>ℹ️ Não há inscrições com status 'aprovada'.</p>";
        echo "<p>Possíveis soluções:</p>";
        echo "<ul>";
        echo "<li>Verificar se existem inscrições no sistema</li>";
        echo "<li>Atualizar o status das inscrições para 'aprovada'</li>";
        echo "<li>Verificar se o campo 'status' está correto</li>";
        echo "</ul>";
    } else {
        echo "<p class='success'>✅ Encontradas $inscricoes_aprovadas inscrições aprovadas!</p>";
    }
}
echo "</div>";

echo "<div class='section'>";
echo "<h2>7. Links Úteis</h2>";
echo "<p><a href='inscricoes_aprovadas.php'>Ver página de inscrições aprovadas</a></p>";
echo "<p><a href='index.php'>Voltar para a programação</a></p>";
echo "</div>";
?> 