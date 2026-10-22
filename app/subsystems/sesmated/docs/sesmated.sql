-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/06/2025 às 21:42
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sesmated`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `arrecadacoes`
--

CREATE TABLE `arrecadacoes` (
  `arrecadacao_id` int(11) NOT NULL,
  `tarefa_id` int(11) DEFAULT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `valor_arrecadado` decimal(10,2) NOT NULL,
  `representante_nome` varchar(100) DEFAULT NULL,
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `criterios`
--

CREATE TABLE `criterios` (
  `criterio_id` int(11) NOT NULL,
  `tarefa_id` int(11) DEFAULT NULL,
  `nome_criterio` varchar(100) NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `curso_id` int(11) NOT NULL,
  `nome_curso` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`curso_id`, `nome_curso`, `descricao`) VALUES
(1, 'Enfermagem', NULL),
(2, 'Informática', NULL),
(3, 'Meio Ambiente', NULL),
(4, 'Administração', NULL),
(5, 'Edificações', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos_barraca`
--

CREATE TABLE `produtos_barraca` (
  `produto_id` int(11) NOT NULL,
  `arrecadacao_id` int(11) DEFAULT NULL,
  `nome_produto` varchar(100) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `quantidade_vendida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefas`
--

CREATE TABLE `tarefas` (
  `tarefa_id` int(11) NOT NULL,
  `nome_tarefa` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `pontuacao_maxima` int(11) DEFAULT NULL,
  `tipo_avaliacao` enum('VOTACAO_ABERTA','PRESENCA','ARRECADACAO','JURADOS','CUMPRIDA') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefas`
--

INSERT INTO `tarefas` (`tarefa_id`, `nome_tarefa`, `descricao`, `pontuacao_maxima`, `tipo_avaliacao`) VALUES
(1, 'Venda de Rifas', NULL, 500, 'CUMPRIDA'),
(2, 'Grito de Guerra', NULL, 500, 'CUMPRIDA'),
(3, 'Mascote do Curso', NULL, 500, 'JURADOS'),
(4, 'Logomarca da SESMATED', NULL, 500, 'JURADOS'),
(5, 'Esquete', NULL, 500, 'JURADOS'),
(6, 'Cordel', NULL, 500, 'JURADOS'),
(7, 'Paródia', NULL, 500, 'JURADOS'),
(8, 'Vestimentas Sustentáveis', NULL, 500, 'JURADOS'),
(9, 'Palestras', NULL, 500, 'PRESENCA'),
(10, 'Workshops', NULL, 500, 'PRESENCA'),
(11, 'Inovação', NULL, 1000, 'JURADOS'),
(12, 'Empreendedorismo', NULL, 500, 'ARRECADACAO'),
(13, 'Sala Temática', NULL, 1000, 'JURADOS'),
(14, 'Painel', NULL, 500, 'JURADOS');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_01_rifas`
--

CREATE TABLE `tarefa_01_rifas` (
  `rifa_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `valor_arrecadado` decimal(10,2) DEFAULT NULL,
  `quantidades_rifas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefa_01_rifas`
--

INSERT INTO `tarefa_01_rifas` (`rifa_id`, `turma_id`, `valor_arrecadado`, `quantidades_rifas`) VALUES
(1, 1, 246.00, 123),
(2, 11, 246.00, 123);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_02_grito_guerra`
--

CREATE TABLE `tarefa_02_grito_guerra` (
  `grito_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `cumprida` tinyint(1) NOT NULL,
  `pontuacao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefa_02_grito_guerra`
--

INSERT INTO `tarefa_02_grito_guerra` (`grito_id`, `curso_id`, `cumprida`, `pontuacao`) VALUES
(1, 1, 1, 500),
(2, 2, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_03_mascote`
--

CREATE TABLE `tarefa_03_mascote` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador` varchar(200) DEFAULT NULL,
  `animacao` decimal(5,2) DEFAULT NULL,
  `vestimenta` decimal(5,2) DEFAULT NULL,
  `identidade_curso` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_04_logomarca`
--

CREATE TABLE `tarefa_04_logomarca` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `elementos_cursos` decimal(5,2) DEFAULT NULL,
  `entrega_a3` tinyint(1) DEFAULT NULL,
  `entrega_digital` tinyint(1) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_05_esquete`
--

CREATE TABLE `tarefa_05_esquete` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `adequacao_tema` decimal(5,2) DEFAULT NULL,
  `tempo_apresentacao` decimal(5,2) DEFAULT NULL,
  `figurino_caracterizacao` decimal(5,2) DEFAULT NULL,
  `cenografia_acessorios` decimal(5,2) DEFAULT NULL,
  `originalidade_criatividade` decimal(5,2) DEFAULT NULL,
  `impacto_mensagem` decimal(5,2) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_06_cordel`
--

CREATE TABLE `tarefa_06_cordel` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `adequacao_tema` decimal(5,2) DEFAULT NULL,
  `estrutura_cordel` decimal(5,2) DEFAULT NULL,
  `declamacao` decimal(5,2) DEFAULT NULL,
  `criatividade_originalidade` decimal(5,2) DEFAULT NULL,
  `apresentacao_impressa` decimal(5,2) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_07_parodia`
--

CREATE TABLE `tarefa_07_parodia` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `adequacao_tema` decimal(5,2) DEFAULT NULL,
  `letra_adaptada` decimal(5,2) DEFAULT NULL,
  `diccao_clareza_entonacao` decimal(5,2) DEFAULT NULL,
  `desempenho_artistico` decimal(5,2) DEFAULT NULL,
  `trilha_sonora_sincronia` decimal(5,2) DEFAULT NULL,
  `criatividade_originalidade` decimal(5,2) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_08_vestimentas`
--

CREATE TABLE `tarefa_08_vestimentas` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `materiais_sustentaveis` decimal(5,2) DEFAULT NULL,
  `criatividade_design` decimal(5,2) DEFAULT NULL,
  `estetica_harmonia` decimal(5,2) DEFAULT NULL,
  `identidade_curso_evento` decimal(5,2) DEFAULT NULL,
  `desfile_apresentacao` decimal(5,2) DEFAULT NULL,
  `acabamento_estrutura` decimal(5,2) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_09_palestras`
--

CREATE TABLE `tarefa_09_palestras` (
  `presenca_id` int(11) NOT NULL,
  `estudante_id` int(11) DEFAULT NULL,
  `palestra_nome` varchar(100) NOT NULL,
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_10_workshops`
--

CREATE TABLE `tarefa_10_workshops` (
  `presenca_id` int(11) NOT NULL,
  `estudante_id` int(11) DEFAULT NULL,
  `workshop_nome` varchar(100) NOT NULL,
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_11_inovacao`
--

CREATE TABLE `tarefa_11_inovacao` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `originalidade_inovacao` decimal(5,2) DEFAULT NULL,
  `relevancia_aplicabilidade` decimal(5,2) DEFAULT NULL,
  `viabilidade_tecnica` decimal(5,2) DEFAULT NULL,
  `sustentabilidade_socioambiental` decimal(5,2) DEFAULT NULL,
  `clareza_organizacao` decimal(5,2) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_12_empreendedorismo`
--

CREATE TABLE `tarefa_12_empreendedorismo` (
  `arrecadacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `valor_arrecadado` decimal(10,2) NOT NULL,
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_13_sala_tematica`
--

CREATE TABLE `tarefa_13_sala_tematica` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `adequacao_tema` decimal(5,2) DEFAULT NULL,
  `qualidade_conteudo` decimal(5,2) DEFAULT NULL,
  `ambientacao_criatividade` decimal(5,2) DEFAULT NULL,
  `didatica_clareza` decimal(5,2) DEFAULT NULL,
  `trabalho_equipe` decimal(5,2) DEFAULT NULL,
  `sustentabilidade_execucao` decimal(5,2) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa_14_painel`
--

CREATE TABLE `tarefa_14_painel` (
  `avaliacao_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `avaliador_id` int(11) DEFAULT NULL,
  `adequacao_tema` decimal(5,2) DEFAULT NULL,
  `qualidade_conteudo` decimal(5,2) DEFAULT NULL,
  `organizacao_layout` decimal(5,2) DEFAULT NULL,
  `estetica_criatividade` decimal(5,2) DEFAULT NULL,
  `sustentabilidade_construcao` decimal(5,2) DEFAULT NULL,
  `data_avaliacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `turma_id` int(11) NOT NULL,
  `curso_id` int(11) DEFAULT NULL,
  `nome_turma` varchar(10) NOT NULL,
  `quantidade_estudantes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`turma_id`, `curso_id`, `nome_turma`, `quantidade_estudantes`) VALUES
(1, 1, '1A', 46),
(2, 1, '2A', 49),
(3, 1, '3A', 47),
(4, 2, '1B', 46),
(5, 2, '2B', 46),
(6, 2, '3B', 49),
(7, 3, '2C', 46),
(8, 4, '1C', 45),
(9, 4, '3C', 48),
(10, 5, '1D', 46),
(11, 5, '2D', 47),
(12, 5, '3D', 50);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `arrecadacoes`
--
ALTER TABLE `arrecadacoes`
  ADD PRIMARY KEY (`arrecadacao_id`),
  ADD KEY `tarefa_id` (`tarefa_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `criterios`
--
ALTER TABLE `criterios`
  ADD PRIMARY KEY (`criterio_id`),
  ADD KEY `tarefa_id` (`tarefa_id`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`curso_id`);

--
-- Índices de tabela `produtos_barraca`
--
ALTER TABLE `produtos_barraca`
  ADD PRIMARY KEY (`produto_id`),
  ADD KEY `arrecadacao_id` (`arrecadacao_id`);

--
-- Índices de tabela `tarefas`
--
ALTER TABLE `tarefas`
  ADD PRIMARY KEY (`tarefa_id`);

--
-- Índices de tabela `tarefa_01_rifas`
--
ALTER TABLE `tarefa_01_rifas`
  ADD PRIMARY KEY (`rifa_id`),
  ADD KEY `turma_id` (`turma_id`);

--
-- Índices de tabela `tarefa_02_grito_guerra`
--
ALTER TABLE `tarefa_02_grito_guerra`
  ADD PRIMARY KEY (`grito_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_03_mascote`
--
ALTER TABLE `tarefa_03_mascote`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_04_logomarca`
--
ALTER TABLE `tarefa_04_logomarca`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_05_esquete`
--
ALTER TABLE `tarefa_05_esquete`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_06_cordel`
--
ALTER TABLE `tarefa_06_cordel`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_07_parodia`
--
ALTER TABLE `tarefa_07_parodia`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_08_vestimentas`
--
ALTER TABLE `tarefa_08_vestimentas`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_09_palestras`
--
ALTER TABLE `tarefa_09_palestras`
  ADD PRIMARY KEY (`presenca_id`);

--
-- Índices de tabela `tarefa_10_workshops`
--
ALTER TABLE `tarefa_10_workshops`
  ADD PRIMARY KEY (`presenca_id`);

--
-- Índices de tabela `tarefa_11_inovacao`
--
ALTER TABLE `tarefa_11_inovacao`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_12_empreendedorismo`
--
ALTER TABLE `tarefa_12_empreendedorismo`
  ADD PRIMARY KEY (`arrecadacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_13_sala_tematica`
--
ALTER TABLE `tarefa_13_sala_tematica`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `tarefa_14_painel`
--
ALTER TABLE `tarefa_14_painel`
  ADD PRIMARY KEY (`avaliacao_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`turma_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `arrecadacoes`
--
ALTER TABLE `arrecadacoes`
  MODIFY `arrecadacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `criterios`
--
ALTER TABLE `criterios`
  MODIFY `criterio_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `curso_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos_barraca`
--
ALTER TABLE `produtos_barraca`
  MODIFY `produto_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefas`
--
ALTER TABLE `tarefas`
  MODIFY `tarefa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `tarefa_01_rifas`
--
ALTER TABLE `tarefa_01_rifas`
  MODIFY `rifa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tarefa_02_grito_guerra`
--
ALTER TABLE `tarefa_02_grito_guerra`
  MODIFY `grito_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tarefa_03_mascote`
--
ALTER TABLE `tarefa_03_mascote`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_04_logomarca`
--
ALTER TABLE `tarefa_04_logomarca`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_05_esquete`
--
ALTER TABLE `tarefa_05_esquete`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_06_cordel`
--
ALTER TABLE `tarefa_06_cordel`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_07_parodia`
--
ALTER TABLE `tarefa_07_parodia`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_08_vestimentas`
--
ALTER TABLE `tarefa_08_vestimentas`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_09_palestras`
--
ALTER TABLE `tarefa_09_palestras`
  MODIFY `presenca_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_10_workshops`
--
ALTER TABLE `tarefa_10_workshops`
  MODIFY `presenca_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_11_inovacao`
--
ALTER TABLE `tarefa_11_inovacao`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_12_empreendedorismo`
--
ALTER TABLE `tarefa_12_empreendedorismo`
  MODIFY `arrecadacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_13_sala_tematica`
--
ALTER TABLE `tarefa_13_sala_tematica`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tarefa_14_painel`
--
ALTER TABLE `tarefa_14_painel`
  MODIFY `avaliacao_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `turma_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `arrecadacoes`
--
ALTER TABLE `arrecadacoes`
  ADD CONSTRAINT `arrecadacoes_ibfk_1` FOREIGN KEY (`tarefa_id`) REFERENCES `tarefas` (`tarefa_id`),
  ADD CONSTRAINT `arrecadacoes_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `criterios`
--
ALTER TABLE `criterios`
  ADD CONSTRAINT `criterios_ibfk_1` FOREIGN KEY (`tarefa_id`) REFERENCES `tarefas` (`tarefa_id`);

--
-- Restrições para tabelas `produtos_barraca`
--
ALTER TABLE `produtos_barraca`
  ADD CONSTRAINT `produtos_barraca_ibfk_1` FOREIGN KEY (`arrecadacao_id`) REFERENCES `arrecadacoes` (`arrecadacao_id`);

--
-- Restrições para tabelas `tarefa_01_rifas`
--
ALTER TABLE `tarefa_01_rifas`
  ADD CONSTRAINT `tarefa_01_rifas_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`turma_id`);

--
-- Restrições para tabelas `tarefa_02_grito_guerra`
--
ALTER TABLE `tarefa_02_grito_guerra`
  ADD CONSTRAINT `tarefa_02_grito_guerra_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_03_mascote`
--
ALTER TABLE `tarefa_03_mascote`
  ADD CONSTRAINT `tarefa_03_mascote_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_04_logomarca`
--
ALTER TABLE `tarefa_04_logomarca`
  ADD CONSTRAINT `tarefa_04_logomarca_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_05_esquete`
--
ALTER TABLE `tarefa_05_esquete`
  ADD CONSTRAINT `tarefa_05_esquete_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_06_cordel`
--
ALTER TABLE `tarefa_06_cordel`
  ADD CONSTRAINT `tarefa_06_cordel_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_07_parodia`
--
ALTER TABLE `tarefa_07_parodia`
  ADD CONSTRAINT `tarefa_07_parodia_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_08_vestimentas`
--
ALTER TABLE `tarefa_08_vestimentas`
  ADD CONSTRAINT `tarefa_08_vestimentas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_11_inovacao`
--
ALTER TABLE `tarefa_11_inovacao`
  ADD CONSTRAINT `tarefa_11_inovacao_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_12_empreendedorismo`
--
ALTER TABLE `tarefa_12_empreendedorismo`
  ADD CONSTRAINT `tarefa_12_empreendedorismo_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_13_sala_tematica`
--
ALTER TABLE `tarefa_13_sala_tematica`
  ADD CONSTRAINT `tarefa_13_sala_tematica_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `tarefa_14_painel`
--
ALTER TABLE `tarefa_14_painel`
  ADD CONSTRAINT `tarefa_14_painel_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`curso_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
