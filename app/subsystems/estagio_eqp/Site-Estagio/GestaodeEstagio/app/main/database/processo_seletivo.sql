CREATE TABLE IF NOT EXISTS processo_seletivo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    aluno_id INT NOT NULL,
    vaga_id INT NOT NULL,
    data DATE NOT NULL,
    hora TIME NOT NULL,
    local VARCHAR(255) NOT NULL,
    status ENUM('pendente', 'confirmado', 'cancelado') DEFAULT 'pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (empresa_id) REFERENCES concedentes(id),
    FOREIGN KEY (aluno_id) REFERENCES aluno(id),
    FOREIGN KEY (vaga_id) REFERENCES concedentes(id)
); 