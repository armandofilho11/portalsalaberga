-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/06/2025 às 13:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u750204740_sistemafinanceiro`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos`
--

CREATE TABLE `atendimentos` (
  `id` int(11) NOT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `cnpj` varchar(30) DEFAULT NULL,
  `natureza` varchar(255) DEFAULT NULL,
  `nup` varchar(100) DEFAULT NULL,
  `escola` varchar(255) DEFAULT NULL,
  `gestor` varchar(255) DEFAULT NULL,
  `caminho_pdf` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `atendimentos`
--

INSERT INTO `atendimentos` (`id`, `empresa`, `cnpj`, `natureza`, `nup`, `escola`, `gestor`, `caminho_pdf`, `criado_em`) VALUES
(2, 'teste', '0967565576598498498', 'teste', '4878979748', 'eeep slbg', 'cicero', 'declaracoes/atendimento_4878979748_20250611_130647.pdf', '2025-06-11 11:06:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `autoridade`
--

CREATE TABLE `autoridade` (
  `id` int(11) NOT NULL,
  `nup` varchar(100) DEFAULT NULL,
  `natureza` varchar(255) DEFAULT NULL,
  `data_dia` varchar(2) DEFAULT NULL,
  `data_mes` varchar(15) DEFAULT NULL,
  `data_ano` varchar(4) DEFAULT NULL,
  `diretor` varchar(255) DEFAULT NULL,
  `caminho_pdf` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `autoridade`
--

INSERT INTO `autoridade` (`id`, `nup`, `natureza`, `data_dia`, `data_mes`, `data_ano`, `diretor`, `caminho_pdf`, `criado_em`) VALUES
(1, '4878979895', 'teste', NULL, NULL, NULL, 'cicero', 'C:\\xampp\\htdocs\\financeiro\\app\\main\\view\\gerar/declaracoes/autoridade_4878979895_20250611_130548.pdf', '2025-06-11 11:05:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `declaracoes`
--

CREATE TABLE `declaracoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tipo` enum('PCA','Validação de Documento','Declaração de Autoridade','LOA','Declaração de Atendimento','Declaração de Observância') NOT NULL,
  `nup` varchar(50) DEFAULT NULL,
  `natureza` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `loa`
--

CREATE TABLE `loa` (
  `id` int(11) NOT NULL,
  `numero_loa` varchar(50) DEFAULT NULL,
  `data_loa` varchar(20) DEFAULT NULL,
  `dotacao` varchar(255) DEFAULT NULL,
  `ordenador` varchar(255) DEFAULT NULL,
  `caminho_pdf` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `loa`
--

INSERT INTO `loa` (`id`, `numero_loa`, `data_loa`, `dotacao`, `ordenador`, `caminho_pdf`, `criado_em`) VALUES
(4, '434435', '02042008', 'hjdvfhjwvfd', 'cicero', 'declaracoes/loa_434435_20250611_130439.pdf', '2025-06-11 11:04:39');

-- --------------------------------------------------------

--
-- Estrutura para tabela `observancias`
--

CREATE TABLE `observancias` (
  `id` int(11) NOT NULL,
  `natureza` varchar(255) DEFAULT NULL,
  `nup` varchar(100) DEFAULT NULL,
  `escola` varchar(255) DEFAULT NULL,
  `ordenador` varchar(255) DEFAULT NULL,
  `caminho_pdf` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `observancias`
--

INSERT INTO `observancias` (`id`, `natureza`, `nup`, `escola`, `ordenador`, `caminho_pdf`, `criado_em`) VALUES
(2, 'teste', '4878979747', 'eeep slbg', 'cicero', 'declaracoes/observancia_4878979747_20250611_130048.pdf', '2025-06-11 11:00:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pca`
--

CREATE TABLE `pca` (
  `id` int(11) NOT NULL,
  `natureza` varchar(255) DEFAULT NULL,
  `nup` varchar(100) DEFAULT NULL,
  `escola` varchar(255) DEFAULT NULL,
  `ordenador` varchar(255) DEFAULT NULL,
  `caminho_pdf` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pca`
--

INSERT INTO `pca` (`id`, `natureza`, `nup`, `escola`, `ordenador`, `caminho_pdf`, `criado_em`) VALUES
(2, 'teste', '4878979895', 'eeep slbg', 'cicero', 'declaracoes/pca_4878979895_20250611_130804.pdf', '2025-06-11 11:08:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'juao', 'mercurio2000@gmail.com', '$2y$10$ms37xotcfu5MNEwxqOKvL.i4z1BZjIojBeY.bCWOQrWHWtjDVfNP2'),
(2, 'ciri', 'wevertonmarques063@gmail.com', '$2y$10$UvjLR0fZyAUE9KI0rhHdbeNNOSgW18VCTstoYtQzJPA0qfJ5mHQ2K'),
(3, 'João Paulo', 'mercuryo2000@gmail.com', '$2y$10$JB2VyEQrfVWpT4wTUijQVOfgNIHvVCPWzVrK/.0MnlGXx0fMyLimC');

-- --------------------------------------------------------

--
-- Estrutura para tabela `validacoes`
--

CREATE TABLE `validacoes` (
  `id` int(11) NOT NULL,
  `descricao_objeto` varchar(255) DEFAULT NULL,
  `exercicio` varchar(10) DEFAULT NULL,
  `nup` varchar(100) DEFAULT NULL,
  `data_dia` varchar(2) DEFAULT NULL,
  `data_mes` varchar(15) DEFAULT NULL,
  `data_ano` varchar(4) DEFAULT NULL,
  `gestor` varchar(255) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `caminho_pdf` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `validacoes`
--

INSERT INTO `validacoes` (`id`, `descricao_objeto`, `exercicio`, `nup`, `data_dia`, `data_mes`, `data_ano`, `gestor`, `cpf`, `matricula`, `caminho_pdf`, `criado_em`) VALUES
(2, 'hdsfdf', 'ghghhr', '4878979748', '02', '04', '2025', 'cicero', '12312312312', '3376395', 'declaracoes/validacao_4878979748_20250611_130250.pdf', '2025-06-11 11:02:50');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `autoridade`
--
ALTER TABLE `autoridade`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `declaracoes`
--
ALTER TABLE `declaracoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `loa`
--
ALTER TABLE `loa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `observancias`
--
ALTER TABLE `observancias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pca`
--
ALTER TABLE `pca`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `validacoes`
--
ALTER TABLE `validacoes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `autoridade`
--
ALTER TABLE `autoridade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `declaracoes`
--
ALTER TABLE `declaracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `loa`
--
ALTER TABLE `loa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `observancias`
--
ALTER TABLE `observancias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pca`
--
ALTER TABLE `pca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `validacoes`
--
ALTER TABLE `validacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `declaracoes`
--
ALTER TABLE `declaracoes`
  ADD CONSTRAINT `declaracoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
