-- ============================================================
-- Script para Ajustar Sistema de Coins e Recompensas
-- ============================================================
-- Estrutura: Cada curso tem 2 módulos, cada módulo tem 1 vídeo e 5 questões
-- Total por curso: 2 vídeos + 10 questões
-- ============================================================

-- ============================================================
-- 1. ATUALIZAR PREÇOS DOS CURSOS
-- ============================================================
-- Curso 1: 80 coins (primeiro curso - grátis inicialmente ou ganho no registro)
-- Curso 2: 40 coins  
-- Curso 3: 100 coins
-- Curso 4: 80 coins

UPDATE cursos SET preco_coins = 80 WHERE id = 1;
UPDATE cursos SET preco_coins = 40 WHERE id = 2;
UPDATE cursos SET preco_coins = 100 WHERE id = 3;
UPDATE cursos SET preco_coins = 80 WHERE id = 4;

-- ============================================================
-- 2. LIMPAR RECOMPENSAS EXISTENTES
-- ============================================================
DELETE FROM recompensas;

-- ============================================================
-- 3. ADICIONAR RECOMPENSAS PARA VÍDEOS
-- ============================================================
-- Cada vídeo: 5 coins + 25 XP
-- Total vídeos: 8 (2 por curso × 4 cursos)

-- Curso 1 - Git e Github (2 vídeos)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('video', 1, 5, 25),  -- Vídeo 1 do módulo 1
('video', 2, 5, 25);  -- Vídeo 2 do módulo 2 (ajustar IDs conforme necessário)

-- Curso 2 - Padlet (2 vídeos)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('video', 3, 5, 25),  -- Vídeo 1 do módulo 4
('video', 4, 5, 25);  -- Vídeo 2 do módulo 5 (ajustar IDs conforme necessário)

-- Curso 3 - Frameworks Frontend (2 vídeos)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('video', 5, 5, 25),  -- Vídeo 1 do módulo 6
('video', 6, 5, 25);  -- Vídeo 2 do módulo 7 (ajustar IDs conforme necessário)

-- Curso 4 - FPDF (2 vídeos)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('video', 7, 5, 25),  -- Vídeo 1 do módulo 9
('video', 8, 5, 25);  -- Vídeo 2 do módulo 10 (ajustar IDs conforme necessário)

-- ============================================================
-- 4. ADICIONAR RECOMPENSAS PARA ATIVIDADES
-- ============================================================
-- Cada questão: 10 coins + 50 XP
-- Total questões: 40 (10 por curso × 4 cursos)

-- Curso 1 - Git e Github (10 questões)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('atividade', 1, 10, 50),
('atividade', 2, 10, 50),
('atividade', 3, 10, 50),
('atividade', 4, 10, 50),
('atividade', 5, 10, 50),
('atividade', 6, 10, 50),
('atividade', 7, 10, 50),
('atividade', 8, 10, 50),
('atividade', 9, 10, 50),
('atividade', 10, 10, 50);

-- Curso 2 - Padlet (10 questões)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('atividade', 11, 10, 50),
('atividade', 12, 10, 50),
('atividade', 13, 10, 50),
('atividade', 14, 10, 50),
('atividade', 15, 10, 50),
('atividade', 16, 10, 50),
('atividade', 17, 10, 50),
('atividade', 18, 10, 50),
('atividade', 19, 10, 50),
('atividade', 20, 10, 50);

-- Curso 3 - Frameworks Frontend (10 questões)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('atividade', 21, 10, 50),
('atividade', 22, 10, 50),
('atividade', 23, 10, 50),
('atividade', 24, 10, 50),
('atividade', 25, 10, 50),
('atividade', 26, 10, 50),
('atividade', 27, 10, 50),
('atividade', 28, 10, 50),
('atividade', 29, 10, 50),
('atividade', 30, 10, 50);

-- Curso 4 - FPDF (10 questões)
INSERT INTO recompensas (tipo_referencia, referencia_id, coins, xp) VALUES
('atividade', 31, 10, 50),
('atividade', 32, 10, 50),
('atividade', 33, 10, 50),
('atividade', 34, 10, 50),
('atividade', 35, 10, 50),
('atividade', 36, 10, 50),
('atividade', 37, 10, 50),
('atividade', 38, 10, 50),
('atividade', 39, 10, 50),
('atividade', 40, 10, 50);

-- ============================================================
-- 5. ADICIONAR RECOMPENSAS DE CONCLUSÃO DE CURSO
-- ============================================================
-- Bônus por concluir 100% do curso
-- Curso 1: 10 coins (total ganho: 120 coins = 10 vídeos + 100 questões + 10 bônus)
-- Curso 2: 10 coins (total ganho: 140 coins = 10 vídeos + 100 questões + 10 bônus)
-- Curso 3: 20 coins (total ganho: 220 coins = 10 vídeos + 100 questões + 20 bônus)
-- Curso 4: 10 coins (total ganho: 110 coins = 10 vídeos + 100 questões + 10 bônus)

-- NOTA: As recompensas de conclusão de curso serão adicionadas via código PHP
-- quando o usuário completar 100% do curso (todos os vídeos e atividades)

-- ============================================================
-- CÁLCULO DE GANHOS POR CURSO
-- ============================================================
-- Curso 1:
--   2 vídeos × 5 coins = 10 coins
--   10 questões × 10 coins = 100 coins
--   Bônus conclusão = 10 coins
--   TOTAL: 120 coins
--   Custo próximo curso: 40 coins
--   Sobra: 80 coins

-- Curso 2:
--   2 vídeos × 5 coins = 10 coins
--   10 questões × 10 coins = 100 coins
--   Bônus conclusão = 10 coins
--   TOTAL: 120 coins
--   Custo próximo curso: 100 coins
--   Sobra: 20 coins

-- Curso 3:
--   2 vídeos × 5 coins = 10 coins
--   10 questões × 10 coins = 100 coins
--   Bônus conclusão = 20 coins
--   TOTAL: 130 coins
--   Custo próximo curso: 80 coins
--   Sobra: 50 coins

-- Curso 4:
--   2 vídeos × 5 coins = 10 coins
--   10 questões × 10 coins = 100 coins
--   Bônus conclusão = 10 coins
--   TOTAL: 120 coins
--   (último curso)

-- ============================================================
-- NOTAS IMPORTANTES
-- ============================================================
-- 1. Os IDs de vídeos e atividades devem ser ajustados conforme 
--    os dados reais no banco
-- 2. Use a query abaixo para verificar os IDs existentes:
--    SELECT id, titulo FROM videos ORDER BY id;
--    SELECT id, titulo FROM atividades ORDER BY id;
-- 3. A recompensa de conclusão será implementada via código PHP
-- 4. O sistema garantirá que o usuário só compre o próximo curso
--    se tiver concluído 100% do curso anterior

