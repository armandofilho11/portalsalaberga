-- Create subtopics table
CREATE TABLE IF NOT EXISTS subtopicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_subtopico (disciplina, nome)
);

-- Add subtopico column to questao table
ALTER TABLE questao ADD COLUMN subtopico INT NULL;
ALTER TABLE questao ADD CONSTRAINT fk_questao_subtopico FOREIGN KEY (subtopico) REFERENCES subtopicos(id) ON DELETE SET NULL; 