CREATE DATABASE sistema_entrada_saida_alunos;
USE sistema_entrada_saida_alunos;

CREATE TABLE turma (
    id INT PRIMARY KEY AUTO_INCREMENT,
    turma ENUM('A','B','C','D') NOT NULL,
    serie ENUM('1 ano','2 ano','3 ano') NOT NULL,
    ativo TINYINT(1) DEFAULT 1
);

CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin','funcionario','professor','desenvolvedor','suporte') NOT NULL,
    ativo TINYINT(1) DEFAULT 1
);

CREATE TABLE aluno (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_turma INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    matricula VARCHAR(15) NOT NULL UNIQUE,
    data_nascimento DATE,
    ano_corrente YEAR(4),
    ativo TINYINT(1) DEFAULT 1,
    curso ENUM('enfermagem','informática','administração','meio ambiente','edificações') NOT NULL,
    
    FOREIGN KEY (id_turma)
    REFERENCES turma(id)
);

create table registro_entrada (
    id int primary key auto_increment,
    id_aluno int not null,
    data_entrada date not null,
    hora_entrada time not null,
    motivo enum('atraso','saúde', 'compromisso pessoal' ,'outros') not null,
    descricao text,

    foreign key (id_aluno)
    references aluno(id)
);

CREATE TABLE registro_saida (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_aluno INT NOT NULL,
    tipo_responsavel ENUM('pai','mae','outros') NOT NULL,
    nome_responsavel VARCHAR(100) NOT NULL,
    motivo ENUM('saúde','imprevisto','compromisso pessoal','outros') NOT NULL,
    data_saida DATE NOT NULL,
    hora_saida TIME NOT NULL,    
    tipo_conducente enum('uber','responsavel','amigo(a)','outros') NOT NULL,
    descricao text,

    FOREIGN KEY (id_aluno)
    REFERENCES aluno(id)
);

CREATE TABLE registro_saida_estagio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_aluno INT NOT NULL,
    data_saida DATE NOT NULL,
    hora_saida TIME NOT NULL,
    FOREIGN KEY (id_aluno) REFERENCES aluno(id)
);

CREATE TABLE registro_pre_estagio (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_aluno INT NOT NULL,
    data_registro DATE NOT NULL,
    hora_registro TIME NOT NULL,
    momento ENUM('ida_almoco','retorno_almoco','ida_lanche','retorno_lanche') NOT NULL,
    descricao TEXT,
    UNIQUE KEY uq_registro_pre_estagio_aluno_data_momento (id_aluno, data_registro, momento),
    FOREIGN KEY (id_aluno) REFERENCES aluno(id)
);