-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/08/2026 às 13:26
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
-- Banco de dados: `sistema_de_demandas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atribuicao`
--

CREATE TABLE `atribuicao` (
  `id` int(11) NOT NULL,
  `id_demanda` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data_inicio` datetime NOT NULL DEFAULT current_timestamp(),
  `data_fim` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `demandas`
--

CREATE TABLE `demandas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `prioridade` enum('alta','media','baixa') NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `status` enum('aberta','em_andamento','finalizada') DEFAULT 'aberta',
  `situacao` enum('pendente','concluida','nao_concluida') NOT NULL DEFAULT 'pendente',
  `solucao` text DEFAULT NULL,
  `justificativa_nao_conclusao` text DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `prazo` datetime DEFAULT NULL,
  `data_finalizacao` datetime DEFAULT NULL,
  `justificativa_validacao_status` enum('pendente','validada','invalidada') NOT NULL DEFAULT 'pendente',
  `id_admin_decisao_justificativa` int(11) DEFAULT NULL,
  `data_decisao_justificativa` datetime DEFAULT NULL,
  `id_projeto` int(11) NOT NULL,
  `orientador` int(11) DEFAULT NULL,
  `quantidade_alunos` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `projetos`
--

CREATE TABLE `projetos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `criado_por` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_projeto`
--

CREATE TABLE `usuario_projeto` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `data_vinculo` datetime NOT NULL DEFAULT current_timestamp(),
  `vinculado_por` int(11) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes_projeto`
--

CREATE TABLE `solicitacoes_projeto` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `status` enum('pendente','aprovada','recusada') NOT NULL DEFAULT 'pendente',
  `mensagem` text DEFAULT NULL,
  `data_solicitacao` datetime NOT NULL DEFAULT current_timestamp(),
  `id_administrador_decisao` int(11) DEFAULT NULL,
  `data_decisao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias_projeto`
--

CREATE TABLE `categorias_projeto` (
  `id` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `criado_por` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pontuacao`
--

CREATE TABLE `pontuacao` (
  `id` int(11) NOT NULL,
  `id_demanda` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `pontos` int(11) NOT NULL DEFAULT 0,
  `data_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `situacao` enum('pendente','concluida','nao_concluida') NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `matricula` varchar(7) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `serie` enum('0','1','2','3') DEFAULT NULL,
  `tipo` ENUM('master','administrador','usuario') NOT NULL,
  `pode_criar_projeto` TINYINT(1) NOT NULL DEFAULT 0,
  `pode_vincular_projeto` TINYINT(1) NOT NULL DEFAULT 0,
  `pontuacao` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `status_cadastro` enum('pendente','aprovado','recusado') NOT NULL DEFAULT 'aprovado',
  `motivo_recusa` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`


--

INSERT INTO `usuarios` (`id`, `nome`, `matricula`, `email`, `senha`, `serie`, `tipo`, `pontuacao`, `foto`, `ativo`, `data_criacao`) VALUES
(1, 'Admin Master', 1234567, 'admin@system.com', '$2y$10$VsCSVKVc7.REXYr/sVVWYu9y0ZbM0V0yZekhhWJmt3njMe74onfsi', NULL, 'master', 0, NULL, 1, DEFAULT);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atribuicao`
--
ALTER TABLE `atribuicao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_atribuicao_demanda_usuario` (`id_demanda`,`id_usuario`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_data_inicio` (`data_inicio`);

--
-- Índices de tabela `demandas`
--
ALTER TABLE `demandas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_situacao` (`situacao`),
  ADD KEY `idx_prazo` (`prazo`),
  ADD KEY `idx_data_criacao` (`data_criacao`),
  ADD KEY `idx_data_inicio` (`data_inicio`),
  ADD KEY `idx_data_finalizacao` (`data_finalizacao`),
  ADD KEY `idx_orientador` (`orientador`),
  ADD KEY `idx_status_prioridade` (`status`,`prioridade`),
  ADD KEY `idx_demandas_projeto` (`id_projeto`),
  ADD KEY `idx_demandas_projeto_status` (`id_projeto`,`status`);

--
-- Índices de tabela `projetos`
--
ALTER TABLE `projetos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_projetos_nome` (`nome`),
  ADD KEY `idx_ativo` (`ativo`),
  ADD KEY `idx_data_criacao` (`data_criacao`);

--
-- Índices de tabela `usuario_projeto`
--
ALTER TABLE `usuario_projeto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario_projeto` (`id_usuario`,`id_projeto`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_projeto` (`id_projeto`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `solicitacoes_projeto`
--
ALTER TABLE `solicitacoes_projeto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_solicitacao_usuario_projeto_pendente` (`id_usuario`,`id_projeto`,`status`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_data_solicitacao` (`data_solicitacao`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_projeto` (`id_projeto`);

--
-- Índices de tabela `categorias_projeto`
--
ALTER TABLE `categorias_projeto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categoria_projeto_nome` (`id_projeto`,`nome`),
  ADD KEY `idx_categorias_projeto` (`id_projeto`);

--
-- Índices de tabela `pontuacao`
--
ALTER TABLE `pontuacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pontuacao_demanda_usuario` (`id_demanda`,`id_usuario`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_pontos` (`pontos`),
  ADD KEY `idx_situacao` (`situacao`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- Auto incremento para tabelas despejadas
--

ALTER TABLE `atribuicao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `demandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `pontuacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `usuario_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `solicitacoes_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `categorias_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atribuicao`
--
ALTER TABLE `atribuicao`
  ADD CONSTRAINT `fk_atribuicao_demanda` FOREIGN KEY (`id_demanda`) REFERENCES `demandas` (`id`),
  ADD CONSTRAINT `fk_atribuicao_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `demandas`
--
ALTER TABLE `demandas`
  ADD CONSTRAINT `demandas_ibfk_1` FOREIGN KEY (`orientador`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_demandas_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `projetos` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Restrições para tabelas `projetos`
--
ALTER TABLE `projetos`
  ADD CONSTRAINT `fk_projetos_criado_por` FOREIGN KEY (`criado_por`) REFERENCES `usuarios` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Restrições para tabelas `usuario_projeto`
--
ALTER TABLE `usuario_projeto`
  ADD CONSTRAINT `fk_usuario_projeto_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_projeto_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `projetos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_projeto_vinculado_por` FOREIGN KEY (`vinculado_por`) REFERENCES `usuarios` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `solicitacoes_projeto`
--
ALTER TABLE `solicitacoes_projeto`
  ADD CONSTRAINT `fk_solicitacao_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitacao_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `projetos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_solicitacao_administrador` FOREIGN KEY (`id_administrador_decisao`) REFERENCES `usuarios` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Restrições para tabelas `categorias_projeto`
--
ALTER TABLE `categorias_projeto`
  ADD CONSTRAINT `fk_categorias_projeto_projeto` FOREIGN KEY (`id_projeto`) REFERENCES `projetos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_categorias_projeto_criado_por` FOREIGN KEY (`criado_por`) REFERENCES `usuarios` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Restrições para tabelas `pontuacao`
--
ALTER TABLE `pontuacao`
  ADD CONSTRAINT `pontuacao_ibfk_1` FOREIGN KEY (`id_demanda`) REFERENCES `demandas` (`id`),
  ADD CONSTRAINT `pontuacao_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Estrutura para tabela `reabertura_demandas`
--
CREATE TABLE IF NOT EXISTS `reabertura_demandas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_demanda` int(11) NOT NULL,
  `id_usuario_solicitante` int(11) NOT NULL,
  `status` enum('pendente','aprovada','rejeitada') NOT NULL DEFAULT 'pendente',
  `justificativa` text NOT NULL,
  `data_solicitacao` datetime NOT NULL,
  `id_admin_responsavel` int(11) DEFAULT NULL,
  `data_decisao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_reabertura_demanda` (`id_demanda`),
  KEY `idx_reabertura_status` (`status`),
  KEY `idx_reabertura_solicitante` (`id_usuario_solicitante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
