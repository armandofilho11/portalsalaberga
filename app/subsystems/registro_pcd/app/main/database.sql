-- Tabela de registro médico
CREATE TABLE IF NOT EXISTS registro_medico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    registro_medico TEXT,
    registro_dia TEXT,
    data_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES registro_pcd(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de registro diário
CREATE TABLE IF NOT EXISTS registro_diario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_pcd_id INT NOT NULL,
    acompanhante_id INT NOT NULL,
    presenca BOOLEAN NOT NULL DEFAULT 1,
    observacoes TEXT,
    data_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (registro_pcd_id) REFERENCES registro_pcd(id) ON DELETE CASCADE,
    FOREIGN KEY (acompanhante_id) REFERENCES acompanhante(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de acompanhantes
CREATE TABLE IF NOT EXISTS acompanhante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    data_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
