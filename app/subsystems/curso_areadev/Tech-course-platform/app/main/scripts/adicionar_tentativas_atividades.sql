-- Script para adicionar campos de tentativas na tabela respostas_usuarios
-- Permite rastrear quantas tentativas o usuário fez e quando foi a última

ALTER TABLE `respostas_usuarios` 
ADD COLUMN `tentativas` int(11) DEFAULT 1 AFTER `pontuacao`,
ADD COLUMN `ultima_tentativa` datetime DEFAULT current_timestamp() AFTER `tentativas`;

-- Atualiza registros existentes
UPDATE `respostas_usuarios` SET `tentativas` = 1, `ultima_tentativa` = `data_resposta` WHERE `tentativas` IS NULL;

