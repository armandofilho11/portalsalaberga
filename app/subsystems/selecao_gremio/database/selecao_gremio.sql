-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS selecao_gremio;
USE selecao_gremio;

-- Tabela de votos
CREATE TABLE IF NOT EXISTS votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NULL,
    voto ENUM('sim', 'nao') NOT NULL,
    data_voto TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de resultados
CREATE TABLE IF NOT EXISTS resultados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_votos INT DEFAULT 0,
    votos_sim INT DEFAULT 0,
    votos_nao INT DEFAULT 0,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir registro inicial na tabela de resultados
INSERT INTO resultados (total_votos, votos_sim, votos_nao) VALUES (0, 0, 0); 