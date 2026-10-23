-- Migração: Adicionar coluna deletado_em para soft delete de ocorrências
-- Esta migração adiciona suporte a soft delete com limpeza automática de 24 horas

ALTER TABLE ocorrencias 
ADD COLUMN IF NOT EXISTS deletado_em TIMESTAMP NULL DEFAULT NULL;

-- Criar índice para melhor performance nas consultas de soft delete
CREATE INDEX IF NOT EXISTS idx_ocorrencias_deletado_em ON ocorrencias(deletado_em);

-- Criar evento de limpeza automática (executar a cada hora)
-- Nota: Executar este comando apenas se suportado pelo seu servidor MySQL/MariaDB
-- Descomente a linha abaixo se quiser habilitar a limpeza automática
-- CREATE EVENT IF NOT EXISTS limpar_ocorrencias_deletadas
-- ON SCHEDULE EVERY 1 HOUR
-- DO
--   DELETE FROM ocorrencias WHERE deletado_em IS NOT NULL AND TIMESTAMPDIFF(HOUR, deletado_em, NOW()) >= 24;
