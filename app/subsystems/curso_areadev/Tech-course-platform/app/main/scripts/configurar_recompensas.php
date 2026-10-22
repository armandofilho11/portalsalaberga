<?php
/**
 * Script para configurar recompensas automaticamente
 * Baseado na estrutura: Cada curso tem 2 módulos, cada módulo tem 1 vídeo e 5 questões
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Video.php';
require_once __DIR__ . '/../models/Atividade.php';

$db = Database::getInstance()->getConnection();
$videoModel = new Video();
$atividadeModel = new Atividade();

echo "========================================\n";
echo "Configurando Sistema de Recompensas\n";
echo "========================================\n\n";

// Limpa recompensas existentes
echo "1. Limpando recompensas existentes...\n";
$db->exec("DELETE FROM recompensas");
echo "   ✓ Recompensas limpas\n\n";

// Configurações de recompensa
$recompensa_video = ['coins' => 5, 'xp' => 25];
$recompensa_atividade = ['coins' => 10, 'xp' => 50];

echo "2. Configurando recompensas:\n";
echo "   - Vídeos: {$recompensa_video['coins']} coins + {$recompensa_video['xp']} XP\n";
echo "   - Atividades: {$recompensa_atividade['coins']} coins + {$recompensa_atividade['xp']} XP\n\n";

// Busca todos os cursos
$cursos = $db->query("SELECT id, titulo FROM cursos WHERE ativo = 1 ORDER BY id")->fetchAll();

$total_videos = 0;
$total_atividades = 0;

foreach ($cursos as $curso) {
    echo "3. Processando Curso {$curso['id']}: {$curso['titulo']}\n";
    
    // Busca módulos do curso
    $modulos = $db->prepare("SELECT id FROM modulos WHERE curso_id = :curso_id ORDER BY ordem");
    $modulos->bindParam(':curso_id', $curso['id']);
    $modulos->execute();
    $modulos_list = $modulos->fetchAll();
    
    echo "   Módulos encontrados: " . count($modulos_list) . "\n";
    
    foreach ($modulos_list as $modulo) {
        // Adiciona recompensas para vídeos do módulo
        $videos = $videoModel->buscarPorModulo($modulo['id']);
        foreach ($videos as $video) {
            // Verifica se já existe recompensa
            $check = $db->prepare("SELECT id FROM recompensas WHERE tipo_referencia = 'video' AND referencia_id = :video_id");
            $check->bindParam(':video_id', $video['id']);
            $check->execute();
            
            if ($check->rowCount() == 0) {
                $insert = $db->prepare("INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) 
                                       VALUES ('video', :video_id, :coins, :xp)");
                $insert->bindParam(':video_id', $video['id']);
                $insert->bindParam(':coins', $recompensa_video['coins']);
                $insert->bindParam(':xp', $recompensa_video['xp']);
                $insert->execute();
                echo "   ✓ Recompensa adicionada para vídeo: {$video['titulo']} (ID: {$video['id']})\n";
                $total_videos++;
            }
        }
        
        // Adiciona recompensas para atividades do módulo
        $atividades = $atividadeModel->buscarPorModulo($modulo['id']);
        foreach ($atividades as $atividade) {
            // Verifica se já existe recompensa
            $check = $db->prepare("SELECT id FROM recompensas WHERE tipo_referencia = 'atividade' AND referencia_id = :atividade_id");
            $check->bindParam(':atividade_id', $atividade['id']);
            $check->execute();
            
            if ($check->rowCount() == 0) {
                $insert = $db->prepare("INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) 
                                       VALUES ('atividade', :atividade_id, :coins, :xp)");
                $insert->bindParam(':atividade_id', $atividade['id']);
                $insert->bindParam(':coins', $recompensa_atividade['coins']);
                $insert->bindParam(':xp', $recompensa_atividade['xp']);
                $insert->execute();
                echo "   ✓ Recompensa adicionada para atividade: {$atividade['titulo']} (ID: {$atividade['id']})\n";
                $total_atividades++;
            }
        }
    }
    
    echo "\n";
}

echo "========================================\n";
echo "Resumo:\n";
echo "========================================\n";
echo "Total de recompensas de vídeo: {$total_videos}\n";
echo "Total de recompensas de atividade: {$total_atividades}\n";
echo "Total geral: " . ($total_videos + $total_atividades) . "\n\n";

// Atualiza preços dos cursos
echo "4. Atualizando preços dos cursos...\n";
$precos = [
    1 => 80,  // Curso 1
    2 => 40,  // Curso 2
    3 => 100, // Curso 3
    4 => 80   // Curso 4
];

foreach ($precos as $curso_id => $preco) {
    $update = $db->prepare("UPDATE cursos SET preco_coins = :preco WHERE id = :curso_id");
    $update->bindParam(':preco', $preco);
    $update->bindParam(':curso_id', $curso_id);
    $update->execute();
    echo "   ✓ Curso {$curso_id}: {$preco} coins\n";
}

echo "\n========================================\n";
echo "✓ Configuração concluída com sucesso!\n";
echo "========================================\n";

