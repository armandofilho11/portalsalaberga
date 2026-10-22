-- Criar tabela de alunos
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar tabela de equipamentos
CREATE TABLE IF NOT EXISTS equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    status ENUM('disponivel', 'em_uso', 'manutencao') DEFAULT 'disponivel',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar tabela de espaços
CREATE TABLE IF NOT EXISTS espacos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    capacidade INT,
    status ENUM('disponivel', 'em_uso', 'manutencao') DEFAULT 'disponivel',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criar tabela de agendamentos de equipamentos
CREATE TABLE IF NOT EXISTS agendamentos_equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipamento_id INT NOT NULL,
    aluno_id INT NOT NULL,
    data_agendamento DATE NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (equipamento_id) REFERENCES equipamentos(id),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id)
);

-- Criar tabela de agendamentos de espaços
CREATE TABLE IF NOT EXISTS agendamentos_espacos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    espaco_id INT NOT NULL,
    aluno_id INT NOT NULL,
    data_agendamento DATE NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    status ENUM('pendente', 'aprovado', 'rejeitado') DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (espaco_id) REFERENCES espacos(id),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id)
);

-- Inserir alguns dados de exemplo
INSERT INTO alunos (nome, email, senha_hash) VALUES
('João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Maria Santos', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO equipamentos (nome, descricao) VALUES
('Notebook Dell', 'Notebook Dell Latitude para uso educacional'),
('Projetor Epson', 'Projetor Epson para apresentações'),
('Tablet Samsung', 'Tablet Samsung para atividades interativas');

INSERT INTO espacos (nome, descricao, capacidade) VALUES
('Sala de Estudos', 'Sala para estudo em grupo', 20),
('Laboratório de Informática', 'Laboratório com computadores', 30),
('Sala de Reuniões', 'Sala para reuniões e apresentações', 15);

INSERT INTO agendamentos_equipamentos (equipamento_id, aluno_id, data_agendamento, horario_inicio, horario_fim, status) VALUES
(1, 1, CURDATE(), '08:00:00', '10:00:00', 'aprovado'),
(2, 2, CURDATE(), '14:00:00', '16:00:00', 'pendente');

INSERT INTO agendamentos_espacos (espaco_id, aluno_id, data_agendamento, horario_inicio, horario_fim, status) VALUES
(1, 1, CURDATE(), '09:00:00', '11:00:00', 'aprovado'),
(2, 2, CURDATE(), '13:00:00', '15:00:00', 'pendente'); 