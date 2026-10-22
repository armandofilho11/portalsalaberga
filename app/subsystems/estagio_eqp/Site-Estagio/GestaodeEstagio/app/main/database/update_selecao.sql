-- Adicionar coluna perfis_selecionados à tabela selecao
ALTER TABLE selecao
ADD COLUMN perfis_selecionados JSON NULL AFTER id_aluno;

-- Atualizar registros existentes para ter um array vazio como valor padrão
UPDATE selecao
SET perfis_selecionados = '[]'
WHERE perfis_selecionados IS NULL; 