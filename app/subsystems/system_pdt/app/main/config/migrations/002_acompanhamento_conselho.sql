CREATE TABLE IF NOT EXISTS acompanhamentos_pedagogicos (
    id_acompanhamento INT AUTO_INCREMENT PRIMARY KEY,
    id_turma INT NOT NULL,
    id_aluno INT NOT NULL,
    id_responsavel INT NULL,
    data_registro DATE NOT NULL,
    motivo VARCHAR(150) NOT NULL,
    observacao TEXT NOT NULL,
    encaminhamento TEXT NULL,
    data_retorno DATE NULL,
    status ENUM('aberto', 'em_acompanhamento', 'concluido') NOT NULL DEFAULT 'aberto',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_ap_turma FOREIGN KEY (id_turma) REFERENCES turmas(id_turma) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ap_aluno FOREIGN KEY (id_aluno) REFERENCES alunos(id_aluno) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ap_responsavel FOREIGN KEY (id_responsavel) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_ap_turma_data (id_turma, data_registro),
    INDEX idx_ap_aluno_status (id_aluno, status)
);

CREATE TABLE IF NOT EXISTS conselhos_classe (
    id_conselho INT AUTO_INCREMENT PRIMARY KEY,
    id_turma INT NOT NULL,
    id_responsavel INT NULL,
    data_conselho DATE NOT NULL,
    bimestre TINYINT NOT NULL,
    pauta VARCHAR(200) NOT NULL,
    ata TEXT NOT NULL,
    status ENUM('rascunho', 'finalizado') NOT NULL DEFAULT 'rascunho',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cc_turma FOREIGN KEY (id_turma) REFERENCES turmas(id_turma) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cc_responsavel FOREIGN KEY (id_responsavel) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_cc_turma_data (id_turma, data_conselho)
);

CREATE TABLE IF NOT EXISTS conselho_classe_alunos (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_conselho INT NOT NULL,
    id_aluno INT NOT NULL,
    observacao TEXT NOT NULL,
    encaminhamento TEXT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_cc_aluno UNIQUE (id_conselho, id_aluno),
    CONSTRAINT fk_cca_conselho FOREIGN KEY (id_conselho) REFERENCES conselhos_classe(id_conselho) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cca_aluno FOREIGN KEY (id_aluno) REFERENCES alunos(id_aluno) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_cca_aluno (id_aluno)
);
