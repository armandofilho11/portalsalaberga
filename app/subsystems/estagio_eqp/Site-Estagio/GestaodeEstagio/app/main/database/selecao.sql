-- Add status field to selecao table
ALTER TABLE selecao ADD COLUMN status ENUM('pendente', 'alocado') DEFAULT 'pendente';

-- Update existing records to set status based on id_aluno
UPDATE selecao SET status = 'alocado' WHERE id_aluno IS NOT NULL;
UPDATE selecao SET status = 'pendente' WHERE id_aluno IS NULL; 