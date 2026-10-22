-- ============================================================
-- Script para inserir vídeo do Google Drive no banco de dados
-- ============================================================
-- Link do vídeo: https://drive.google.com/file/d/1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu/view?usp=drive_link
-- File ID: 1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu
-- Embed Link: https://drive.google.com/file/d/1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu/preview
-- ============================================================

-- IMPORTANTE: Altere os valores abaixo conforme necessário:
-- - modulo_id: ID do módulo onde o vídeo será inserido
-- - titulo: Título do vídeo
-- - descricao: Descrição do vídeo (pode ser NULL)
-- - ordem: Ordem do vídeo no módulo (1 = primeiro vídeo, 2 = segundo, etc.)

-- Módulos disponíveis:
-- Curso 1 - Git e Github:
--   1 = Fundamentos do Git
--   2 = Trabalhando com Branches
--   3 = Github e Colaboração
--
-- Curso 2 - Padlet:
--   4 = Introdução ao Padlet
--   5 = Gestão de Sprint com Padlet
--
-- Curso 3 - Frameworks Frontend:
--   6 = Introdução ao React
--   7 = Vue.js Essencial
--   8 = Angular Fundamentals
--
-- Curso 4 - FPDF:
--   9 = Introdução ao FPDF
--   10 = Bibliotecas Avançadas

-- ============================================================
-- EXEMPLO 1: Inserir no primeiro módulo (Fundamentos do Git)
-- ============================================================
INSERT INTO `videos` (`modulo_id`, `titulo`, `descricao`, `drive_file_id`, `drive_embed_link`, `ordem`, `ativo`) 
VALUES 
(1, 'Vídeo do Google Drive', 'Descrição do vídeo (altere conforme necessário)', '1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu', 'https://drive.google.com/file/d/1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu/preview', 1, 1);

-- ============================================================
-- Verificar se foi inserido corretamente:
-- ============================================================
-- SELECT v.*, m.titulo as modulo_titulo, c.titulo as curso_titulo 
-- FROM videos v
-- INNER JOIN modulos m ON v.modulo_id = m.id
-- INNER JOIN cursos c ON m.curso_id = c.id
-- WHERE v.drive_file_id = '1qDQu-H4F8UHBEoOrxjLV2K-3AUHt-5Iu';

-- ============================================================
-- Ver quantos vídeos já existem em um módulo:
-- ============================================================
-- SELECT modulo_id, COUNT(*) as total_videos 
-- FROM videos 
-- WHERE modulo_id = 1 
-- GROUP BY modulo_id;

-- ============================================================
-- Listar todos os módulos com seus cursos:
-- ============================================================
-- SELECT m.id, m.titulo as modulo, c.titulo as curso, COUNT(v.id) as total_videos
-- FROM modulos m
-- INNER JOIN cursos c ON m.curso_id = c.id
-- LEFT JOIN videos v ON m.id = v.modulo_id
-- GROUP BY m.id, m.titulo, c.titulo
-- ORDER BY c.id, m.ordem;

