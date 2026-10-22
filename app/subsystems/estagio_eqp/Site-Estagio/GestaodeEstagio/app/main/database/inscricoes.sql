CREATE TABLE IF NOT EXISTS inscricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_selecao INT NOT NULL,
    id_aluno INT NOT NULL,
    data_inscricao DATE NOT NULL,
    perfil VARCHAR(255),
    status ENUM('pendente', 'confirmado', 'cancelado') DEFAULT 'pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_selecao) REFERENCES selecao(id),
    FOREIGN KEY (id_aluno) REFERENCES aluno(id)
); 