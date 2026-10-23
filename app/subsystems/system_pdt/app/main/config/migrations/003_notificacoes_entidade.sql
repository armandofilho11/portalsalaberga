ALTER TABLE notificacoes
    ADD COLUMN IF NOT EXISTS entidade_tipo VARCHAR(50) DEFAULT NULL,
    ADD COLUMN IF NOT EXISTS entidade_id INT DEFAULT NULL;

CREATE INDEX IF NOT EXISTS idx_notificacoes_entidade
    ON notificacoes (entidade_tipo, entidade_id);
