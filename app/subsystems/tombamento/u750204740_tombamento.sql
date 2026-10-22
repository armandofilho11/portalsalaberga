-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/06/2025 às 13:24
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
-- Banco de dados: `sistema_tombamento`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `bem`
--

CREATE TABLE `bem` (
  `id_bem` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `numero_tombamento` varchar(255) DEFAULT NULL,
  `ano_aquisicao` int(11) DEFAULT NULL,
  `estado_conservacao` varchar(50) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `setor_id` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bem`
--

INSERT INTO `bem` (`id_bem`, `nome`, `numero_tombamento`, `ano_aquisicao`, `estado_conservacao`, `valor`, `observacoes`, `categoria_id`, `setor_id`, `id_usuario`) VALUES
(7, 'cadeir', '000000', 4037, 'Bom', 5000000.00, 'feia dms', NULL, NULL, NULL),
(8, 'mesa', '00000000000000', 9000, 'Lixeira', 9000.00, 'AEGasggwerg', NULL, 3, NULL),
(9, 'mesa', NULL, 9000, 'Lixeira', 9000.00, NULL, NULL, 3, NULL),
(10, 'mteclasp', '789684986', 48188, 'Bom', 9611889.00, 'fwsdgzxd', NULL, NULL, NULL),
(11, 's', '8', 8, 'Bom', 4.00, '8', NULL, 3, NULL),
(12, 's', '8ads', 835678, 'Bom', 4.00, '6456', NULL, NULL, NULL),
(13, 'carteira', '1038', 2023, 'Bom', 150.00, 'está podi igual o cu da bianca', NULL, NULL, NULL),
(14, 'po', '83684877', 342, 'Bom', 34124.00, '34124', NULL, NULL, NULL),
(16, 'letys', '85981026574', 2002, 'Bom', 20000000.00, 'estou cansada', NULL, 5, NULL),
(17, 'letys', '8598', 2002, 'Ruim', 20000000.00, NULL, NULL, 6, NULL),
(18, 'letys', '8598f000', 2002, 'Ótimo', 20000000.00, 'tf', NULL, NULL, NULL),
(19, 'ai mds', '40028922', 2000, 'Ótimo', 1022020.00, 'EU NAO AGEUNTO MAIS', NULL, 5, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `consulta`
--

CREATE TABLE `consulta` (
  `id_consulta` int(11) NOT NULL,
  `criterio` varchar(100) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `data_consulta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `consulta_bem`
--

CREATE TABLE `consulta_bem` (
  `id_consulta` int(11) NOT NULL,
  `id_bem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio`
--

CREATE TABLE `relatorio` (
  `id_relatorio` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `data_geracao` datetime NOT NULL,
  `conteudo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio_bem`
--

CREATE TABLE `relatorio_bem` (
  `id_relatorio` int(11) NOT NULL,
  `id_bem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `setor`
--

CREATE TABLE `setor` (
  `id_setor` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `setor`
--

INSERT INTO `setor` (`id_setor`, `nome`, `descricao`) VALUES
(3, 'lerry', 'laboratorio das tiszdxfcgvhbjnkm,'),
(5, 'quadra', 'sala do melo'),
(6, 'quadra', 'sala do melo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `login`, `senha`) VALUES
(1, 'letycia', '1@g.com', '123456\r\n'),
(2, 'mykerson', 'exemplo@dominio.com', '$2y$10$WCd2y2HJtuPgdNA9ZDzjc.7Mm7DPlTYj3vDBJjtdLjuavQdJdW7dS'),
(3, 'bigode', 'exemplo2@dominio.com', '$2y$10$XOhMZ2q3TiK6yY7g89E.o.Y5na.YAetrR.i2liF.J8x5iQZot4NCO');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `bem`
--
ALTER TABLE `bem`
  ADD PRIMARY KEY (`id_bem`),
  ADD UNIQUE KEY `numero_tombamento` (`numero_tombamento`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `bem_ibfk_2` (`setor_id`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Índices de tabela `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id_consulta`);

--
-- Índices de tabela `consulta_bem`
--
ALTER TABLE `consulta_bem`
  ADD PRIMARY KEY (`id_consulta`,`id_bem`),
  ADD KEY `id_bem` (`id_bem`);

--
-- Índices de tabela `relatorio`
--
ALTER TABLE `relatorio`
  ADD PRIMARY KEY (`id_relatorio`);

--
-- Índices de tabela `relatorio_bem`
--
ALTER TABLE `relatorio_bem`
  ADD PRIMARY KEY (`id_relatorio`,`id_bem`),
  ADD KEY `id_bem` (`id_bem`);

--
-- Índices de tabela `setor`
--
ALTER TABLE `setor`
  ADD PRIMARY KEY (`id_setor`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `bem`
--
ALTER TABLE `bem`
  MODIFY `id_bem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatorio`
--
ALTER TABLE `relatorio`
  MODIFY `id_relatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `setor`
--
ALTER TABLE `setor`
  MODIFY `id_setor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `bem`
--
ALTER TABLE `bem`
  ADD CONSTRAINT `bem_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`),
  ADD CONSTRAINT `bem_ibfk_2` FOREIGN KEY (`setor_id`) REFERENCES `setor` (`id_setor`) ON DELETE SET NULL,
  ADD CONSTRAINT `bem_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `consulta_bem`
--
ALTER TABLE `consulta_bem`
  ADD CONSTRAINT `consulta_bem_ibfk_1` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`),
  ADD CONSTRAINT `consulta_bem_ibfk_2` FOREIGN KEY (`id_bem`) REFERENCES `bem` (`id_bem`);

--
-- Restrições para tabelas `relatorio_bem`
--
ALTER TABLE `relatorio_bem`
  ADD CONSTRAINT `relatorio_bem_ibfk_1` FOREIGN KEY (`id_relatorio`) REFERENCES `relatorio` (`id_relatorio`),
  ADD CONSTRAINT `relatorio_bem_ibfk_2` FOREIGN KEY (`id_bem`) REFERENCES `bem` (`id_bem`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
