<?php
/**
 * Script para inserir vídeo do Google Drive no banco de dados
 * 
 * Link do vídeo: https://drive.google.com/file/d/1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu/view?usp=drive_link
 * File ID: 1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu
 */

// Ajuste o caminho conforme necessário
require_once __DIR__ . '/../config/Database.php';

// Configurações do vídeo
$video_data = [
    'modulo_id' => 1, // ALTERE AQUI: ID do módulo (1-10)
    'titulo' => 'Vídeo do Google Drive', // ALTERE AQUI: Título do vídeo
    'descricao' => 'Descrição do vídeo (opcional)', // ALTERE AQUI: Descrição do vídeo
    'drive_file_id' => '1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu',
    'drive_embed_link' => 'https://drive.google.com/file/d/1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu/preview',
    'ordem' => 1, // ALTERE AQUI: Ordem do vídeo no módulo
    'ativo' => 1
];

try {
    $db = Database::getInstance()->getConnection();
    
    // Verifica se o vídeo já existe
    $check_query = "SELECT id FROM videos WHERE drive_file_id = :file_id";
    $stmt = $db->prepare($check_query);
    $stmt->bindParam(':file_id', $video_data['drive_file_id']);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "❌ Erro: Vídeo com este file_id já existe no banco de dados!\n";
        exit;
    }
    
    // Verifica se o módulo existe
    $modulo_query = "SELECT id, titulo FROM modulos WHERE id = :modulo_id";
    $stmt = $db->prepare($modulo_query);
    $stmt->bindParam(':modulo_id', $video_data['modulo_id']);
    $stmt->execute();
    $modulo = $stmt->fetch();
    
    if (!$modulo) {
        echo "❌ Erro: Módulo com ID {$video_data['modulo_id']} não encontrado!\n";
        echo "\nMódulos disponíveis:\n";
        $modulos_query = "SELECT id, titulo, curso_id FROM modulos ORDER BY curso_id, ordem";
        $stmt = $db->prepare($modulos_query);
        $stmt->execute();
        $modulos = $stmt->fetchAll();
        foreach ($modulos as $m) {
            echo "  ID {$m['id']}: {$m['titulo']} (Curso {$m['curso_id']})\n";
        }
        exit;
    }
    
    // Conta quantos vídeos já existem no módulo para definir a ordem
    $count_query = "SELECT COUNT(*) as total FROM videos WHERE modulo_id = :modulo_id";
    $stmt = $db->prepare($count_query);
    $stmt->bindParam(':modulo_id', $video_data['modulo_id']);
    $stmt->execute();
    $count = $stmt->fetch();
    
    if ($video_data['ordem'] == 1 && $count['total'] > 0) {
        $video_data['ordem'] = $count['total'] + 1;
        echo "ℹ️  Ordem ajustada para {$video_data['ordem']} (já existem {$count['total']} vídeo(s) neste módulo)\n";
    }
    
    // Insere o vídeo
    $insert_query = "INSERT INTO videos (modulo_id, titulo, descricao, drive_file_id, drive_embed_link, ordem, ativo) 
                     VALUES (:modulo_id, :titulo, :descricao, :drive_file_id, :drive_embed_link, :ordem, :ativo)";
    
    $stmt = $db->prepare($insert_query);
    $stmt->bindParam(':modulo_id', $video_data['modulo_id']);
    $stmt->bindParam(':titulo', $video_data['titulo']);
    $stmt->bindParam(':descricao', $video_data['descricao']);
    $stmt->bindParam(':drive_file_id', $video_data['drive_file_id']);
    $stmt->bindParam(':drive_embed_link', $video_data['drive_embed_link']);
    $stmt->bindParam(':ordem', $video_data['ordem']);
    $stmt->bindParam(':ativo', $video_data['ativo']);
    
    if ($stmt->execute()) {
        $video_id = $db->lastInsertId();
        echo "✅ Vídeo inserido com sucesso!\n\n";
        echo "Detalhes:\n";
        echo "  ID: {$video_id}\n";
        echo "  Título: {$video_data['titulo']}\n";
        echo "  Módulo: {$modulo['titulo']} (ID: {$video_data['modulo_id']})\n";
        echo "  File ID: {$video_data['drive_file_id']}\n";
        echo "  Ordem: {$video_data['ordem']}\n";
        echo "  Link: {$video_data['drive_embed_link']}\n\n";
        echo "🎉 O vídeo já está disponível para visualização no sistema!\n";
    } else {
        echo "❌ Erro ao inserir vídeo no banco de dados.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

