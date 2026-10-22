-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/11/2025 às 02:12
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
-- Banco de dados: `curso_dev`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atividades`
--

CREATE TABLE `atividades` (
  `id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` enum('questionario','projeto','exercicio') DEFAULT 'questionario',
  `conteudo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conteudo`)),
  `ordem` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `atividades`
--

INSERT INTO `atividades` (`id`, `modulo_id`, `titulo`, `descricao`, `tipo`, `conteudo`, `ordem`, `ativo`, `data_criacao`) VALUES
(1, 1, 'Quiz: Fundamentos do Git', 'Teste completo sobre conceitos básicos do Git', 'questionario', '{\"perguntas\": [{\"pergunta\": \"O que é Git?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Um sistema de controle de versão distribuído\", \"correta\": true}, {\"texto\": \"Um editor de texto\", \"correta\": false}, {\"texto\": \"Um banco de dados\", \"correta\": false}, {\"texto\": \"Uma linguagem de programação\", \"correta\": false}], \"explicacao\": \"Git é um sistema de controle de versão distribuído\"}, {\"pergunta\": \"Qual comando inicializa um repositório Git?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"git init\", \"correta\": true}, {\"texto\": \"git start\", \"correta\": false}, {\"texto\": \"git create\", \"correta\": false}, {\"texto\": \"git new\", \"correta\": false}], \"explicacao\": \"git init cria um novo repositório Git\"}, {\"pergunta\": \"Qual é o propósito do comando git clone?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Copiar um repositório remoto para local\", \"correta\": true}, {\"texto\": \"Criar uma nova branch\", \"correta\": false}, {\"texto\": \"Excluir um repositório\", \"correta\": false}, {\"texto\": \"Renomear um repositório\", \"correta\": false}], \"explicacao\": \"git clone copia um repositório remoto para sua máquina local\"}, {\"pergunta\": \"O que significa staging area no Git?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Área onde as mudanças são preparadas para commit\", \"correta\": true}, {\"texto\": \"Local de armazenamento de backups\", \"correta\": false}, {\"texto\": \"Área de testes de código\", \"correta\": false}, {\"texto\": \"Local para resolução de conflitos\", \"correta\": false}], \"explicacao\": \"Staging area é onde você prepara as mudanças antes de fazer commit\"}, {\"pergunta\": \"Qual comando verifica o status do repositório?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"git status\", \"correta\": true}, {\"texto\": \"git check\", \"correta\": false}, {\"texto\": \"git info\", \"correta\": false}, {\"texto\": \"git state\", \"correta\": false}], \"explicacao\": \"git status mostra o estado atual do repositório\"}], \"config\": {\"tempo_limite\": 30, \"tentativas\": 3}}', 1, 1, '2025-11-10 00:52:14'),
(2, 3, 'Quiz: GitHub e Colaboração', 'Avalie seu conhecimento sobre GitHub e trabalho em equipe', 'questionario', '{\"perguntas\": [{\"pergunta\": \"O que é GitHub?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Plataforma de hospedagem de repositórios Git\", \"correta\": true}, {\"texto\": \"Um tipo de banco de dados\", \"correta\": false}, {\"texto\": \"Editor de código online\", \"correta\": false}, {\"texto\": \"Linguagem de programação\", \"correta\": false}], \"explicacao\": \"GitHub é uma plataforma para hospedar repositórios Git\"}, {\"pergunta\": \"Qual a diferença principal entre Git e GitHub?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Git é o sistema, GitHub é a plataforma\", \"correta\": true}, {\"texto\": \"São a mesma coisa\", \"correta\": false}, {\"texto\": \"GitHub é mais antigo\", \"correta\": false}, {\"texto\": \"Git é online, GitHub é local\", \"correta\": false}], \"explicacao\": \"Git é o sistema de controle de versão, GitHub é a plataforma que hospeda repositórios\"}, {\"pergunta\": \"O que é um Pull Request?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Solicitação para integrar mudanças em um repositório\", \"correta\": true}, {\"texto\": \"Comando para baixar atualizações\", \"correta\": false}, {\"texto\": \"Método para excluir branches\", \"correta\": false}, {\"texto\": \"Ferramenta de debug\", \"correta\": false}], \"explicacao\": \"Pull Request é uma solicitação para integrar mudanças de uma branch para outra\"}, {\"pergunta\": \"Como fazer fork de um repositório?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Clicar no botão Fork no GitHub\", \"correta\": true}, {\"texto\": \"Usar git fork no terminal\", \"correta\": false}, {\"texto\": \"Comando git clone especial\", \"correta\": false}, {\"texto\": \"Não é possível fazer fork\", \"correta\": false}], \"explicacao\": \"Fork é feito pelo botão Fork na interface do GitHub\"}, {\"pergunta\": \"O que são Issues no GitHub?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Sistema de acompanhamento de tarefas e bugs\", \"correta\": true}, {\"texto\": \"Problemas de conexão\", \"correta\": false}, {\"texto\": \"Erros de compilação\", \"correta\": false}, {\"texto\": \"Tipos de arquivos\", \"correta\": false}], \"explicacao\": \"Issues são usadas para acompanhar tarefas, bugs e melhorias\"}], \"config\": {\"tempo_limite\": 25, \"tentativas\": 2}}', 1, 1, '2025-11-10 00:52:14'),
(3, 4, 'Quiz: Introdução ao Padlet', 'Teste seus conhecimentos sobre a ferramenta Padlet', 'questionario', '{\"perguntas\": [{\"pergunta\": \"O que é Padlet?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Ferramenta de quadro virtual colaborativo\", \"correta\": true}, {\"texto\": \"Editor de código\", \"correta\": false}, {\"texto\": \"Rede social\", \"correta\": false}, {\"texto\": \"Plataforma de e-commerce\", \"correta\": false}], \"explicacao\": \"Padlet é um quadro virtual para colaboração em tempo real\"}, {\"pergunta\": \"Qual é a função principal do Padlet?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Organizar e compartilhar conteúdo de forma visual\", \"correta\": true}, {\"texto\": \"Programar aplicações web\", \"correta\": false}, {\"texto\": \"Gerenciar banco de dados\", \"correta\": false}, {\"texto\": \"Criar apresentações animadas\", \"correta\": false}], \"explicacao\": \"Padlet organiza conteúdo de forma visual e colaborativa\"}, {\"pergunta\": \"Que tipos de conteúdo podem ser adicionados ao Padlet?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Texto, imagens, vídeos, links e arquivos\", \"correta\": true}, {\"texto\": \"Apenas texto\", \"correta\": false}, {\"texto\": \"Somente imagens\", \"correta\": false}, {\"texto\": \"Apenas vídeos\", \"correta\": false}], \"explicacao\": \"Padlet suporta diversos tipos de mídia e conteúdo\"}, {\"pergunta\": \"Como convidar pessoas para um Padlet?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Compartilhando o link ou por email\", \"correta\": true}, {\"texto\": \"Apenas com login obrigatório\", \"correta\": false}, {\"texto\": \"Somente pessoalmente\", \"correta\": false}, {\"texto\": \"Não é possível convidar\", \"correta\": false}], \"explicacao\": \"Padlet permite compartilhamento por link ou convite por email\"}, {\"pergunta\": \"O que são templates no Padlet?\", \"tipo\": \"multipla_escolha\", \"opcoes\": [{\"texto\": \"Layouts pré-definidos para diferentes usos\", \"correta\": true}, {\"texto\": \"Tipos de usuários\", \"correta\": false}, {\"texto\": \"Planos de assinatura\", \"correta\": false}, {\"texto\": \"Métodos de pagamento\", \"correta\": false}], \"explicacao\": \"Templates são layouts prontos para diferentes finalidades\"}], \"config\": {\"tempo_limite\": 20, \"tentativas\": 3}}', 1, 1, '2025-11-10 00:52:14'),
(13, 24, 'Quiz: Padrão MVC', 'Teste seus conhecimentos sobre o padrão MVC', 'questionario', '{\r\n  \"perguntas\": [\r\n    {\r\n      \"pergunta\": \"O que significa MVC?\",\r\n      \"tipo\": \"multipla_escolha\",\r\n      \"opcoes\": [\r\n        {\"texto\": \"Model-View-Controller\", \"correta\": true},\r\n        {\"texto\": \"Main-View-Component\", \"correta\": false},\r\n        {\"texto\": \"Module-View-Control\", \"correta\": false},\r\n        {\"texto\": \"Model-View-Component\", \"correta\": false}\r\n      ],\r\n      \"explicacao\": \"MVC significa Model-View-Controller, um padrão de arquitetura de software\"\r\n    },\r\n    {\r\n      \"pergunta\": \"Qual componente do MVC é responsável pela lógica de negócio?\",\r\n      \"tipo\": \"multipla_escolha\",\r\n      \"opcoes\": [\r\n        {\"texto\": \"Model\", \"correta\": true},\r\n        {\"texto\": \"View\", \"correta\": false},\r\n        {\"texto\": \"Controller\", \"correta\": false},\r\n        {\"texto\": \"Todos os anteriores\", \"correta\": false}\r\n      ],\r\n      \"explicacao\": \"O Model é responsável pela lógica de negócio e manipulação de dados\"\r\n    }\r\n  ],\r\n  \"config\": {\"tempo_limite\": 20, \"tentativas\": 2}\r\n}', 1, 1, '2025-11-10 21:47:31'),
(14, 25, 'Quiz: Programação Orientada a Objetos', 'Avalie seu conhecimento sobre POO com PHP', 'questionario', '{\r\n  \"perguntas\": [\r\n    {\r\n      \"pergunta\": \"O que é uma classe em POO?\",\r\n      \"tipo\": \"multipla_escolha\",\r\n      \"opcoes\": [\r\n        {\"texto\": \"Um modelo para criar objetos\", \"correta\": true},\r\n        {\"texto\": \"Uma variável especial\", \"correta\": false},\r\n        {\"texto\": \"Um tipo de função\", \"correta\": false},\r\n        {\"texto\": \"Um arquivo de configuração\", \"correta\": false}\r\n      ],\r\n      \"explicacao\": \"Uma classe é um modelo que define atributos e métodos para criar objetos\"\r\n    },\r\n    {\r\n      \"pergunta\": \"Qual palavra-chave é usada para criar um objeto em PHP?\",\r\n      \"tipo\": \"multipla_escolha\",\r\n      \"opcoes\": [\r\n        {\"texto\": \"new\", \"correta\": true},\r\n        {\"texto\": \"create\", \"correta\": false},\r\n        {\"texto\": \"object\", \"correta\": false},\r\n        {\"texto\": \"make\", \"correta\": false}\r\n      ],\r\n      \"explicacao\": \"A palavra-chave new é usada para instanciar objetos a partir de classes\"\r\n    }\r\n  ],\r\n  \"config\": {\"tempo_limite\": 25, \"tentativas\": 3}\r\n}', 1, 1, '2025-11-10 21:47:31'),
(15, 26, 'Quiz: Formulários PHP', 'Teste seu conhecimento sobre formulários e PHP', 'questionario', '{\r\n  \"perguntas\": [\r\n    {\r\n      \"pergunta\": \"Qual método HTTP é mais seguro para formulários?\",\r\n      \"tipo\": \"multipla_escolha\",\r\n      \"opcoes\": [\r\n        {\"texto\": \"POST\", \"correta\": true},\r\n        {\"texto\": \"GET\", \"correta\": false},\r\n        {\"texto\": \"PUT\", \"correta\": false},\r\n        {\"texto\": \"DELETE\", \"correta\": false}\r\n      ],\r\n      \"explicacao\": \"POST é mais seguro pois os dados não ficam visíveis na URL\"\r\n    },\r\n    {\r\n      \"pergunta\": \"Como acessar dados de um formulário POST em PHP?\",\r\n      \"tipo\": \"multipla_escolha\",\r\n      \"opcoes\": [\r\n        {\"texto\": \"$_POST[\\\"nome_do_campo\\\"]\", \"correta\": true},\r\n        {\"texto\": \"$_GET[\\\"nome_do_campo\\\"]\", \"correta\": false},\r\n        {\"texto\": \"$_REQUEST[\\\"nome_do_campo\\\"]\", \"correta\": false},\r\n        {\"texto\": \"$form[\\\"nome_do_campo\\\"]\", \"correta\": false}\r\n      ],\r\n      \"explicacao\": \"A variável superglobal $_POST armazena dados enviados via método POST\"\r\n    }\r\n  ],\r\n  \"config\": {\"tempo_limite\": 15, \"tentativas\": 2}\r\n}', 1, 1, '2025-11-10 21:47:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras_cursos`
--

CREATE TABLE `compras_cursos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `data_compra` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compras_cursos`
--

INSERT INTO `compras_cursos` (`id`, `usuario_id`, `curso_id`, `data_compra`) VALUES
(3, 1, 2, '2025-11-10 20:29:23'),
(4, 1, 1, '2025-11-10 21:12:01'),
(5, 1, 8, '2025-11-10 21:48:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `imagem_capa` varchar(255) DEFAULT NULL,
  `nivel_requerido` enum('iniciante','intermediario','avancado') NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`id`, `titulo`, `descricao`, `imagem_capa`, `nivel_requerido`, `ativo`, `data_criacao`) VALUES
(1, 'Git e Github com Boas Práticas', 'Aprenda Git e Github com boas práticas e muita atividade em equipe. Desenvolva suas habilidades de controle de versão e colaboração em projetos reais.', 'uploads/cursos/git.jpg', 'intermediario', 1, '2025-11-05 23:50:48'),
(2, 'Padlet: Colaboração e Gestão de Sprint', 'Aprenda a usar e atualizar o Padlet com prática associada às ações da sprint. Domine ferramentas de colaboração e organização de equipe.', NULL, 'iniciante', 1, '2025-11-05 23:50:48'),
(8, 'Desenvolvimento Backend com PHP', 'Aprenda os fundamentos do desenvolvimento backend com PHP, incluindo POO, MVC, formulários e muito mais. Domine a criação de aplicações web robustas do lado do servidor.', NULL, 'intermediario', 1, '2025-11-10 21:47:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `modulos`
--

INSERT INTO `modulos` (`id`, `curso_id`, `titulo`, `descricao`, `ordem`) VALUES
(1, 1, 'Instalação dependências ', 'Aprenda os conceitos básicos do Git e controle de versão', 1),
(3, 1, 'Introdução ao git e github Desktop', 'Aprenda a usar Github para trabalho em equipe', 2),
(4, 2, 'Introdução ao Padlet', 'Conheça a ferramenta Padlet e suas funcionalidades', 1),
(24, 8, 'Padrão MVC', 'Aprenda o padrão Model-View-Controller para organizar seu código de forma profissional', 1),
(25, 8, 'Programação Orientada a Objetos', 'Domine os conceitos fundamentais da POO com PHP', 2),
(26, 8, 'Formulários e Processamento', 'Aprenda a criar e processar formulários HTML com PHP', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `niveis_usuario`
--

CREATE TABLE `niveis_usuario` (
  `nivel` enum('iniciante','intermediario','avancado','premium') NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `ordem_hierarquia` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `niveis_usuario`
--

INSERT INTO `niveis_usuario` (`nivel`, `descricao`, `ordem_hierarquia`) VALUES
('iniciante', 'Acesso a cursos básicos', 1),
('intermediario', 'Acesso a cursos intermediários', 2),
('avancado', 'Acesso a cursos avançados', 3),
('premium', 'Acesso a todos os cursos', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_usuarios`
--

CREATE TABLE `respostas_usuarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `atividade_id` int(11) NOT NULL,
  `respostas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`respostas`)),
  `pontuacao` decimal(5,2) DEFAULT 0.00,
  `tentativas` int(11) DEFAULT 1,
  `ultima_tentativa` datetime DEFAULT NULL,
  `data_resposta` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `respostas_usuarios`
--

INSERT INTO `respostas_usuarios` (`id`, `usuario_id`, `atividade_id`, `respostas`, `pontuacao`, `tentativas`, `ultima_tentativa`, `data_resposta`) VALUES
(1, 1, 3, '[{\"pergunta_index\":0,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":1,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":2,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":3,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":4,\"opcao_index\":0,\"correta\":true}]', 100.00, 1, '2025-11-10 21:06:27', '2025-11-10 21:06:27'),
(2, 1, 1, '[{\"pergunta_index\":0,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":1,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":2,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":3,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":4,\"opcao_index\":0,\"correta\":true}]', 100.00, 1, '2025-11-10 21:30:26', '2025-11-10 21:30:26'),
(3, 1, 2, '[{\"pergunta_index\":0,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":1,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":2,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":3,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":4,\"opcao_index\":0,\"correta\":true}]', 100.00, 1, '2025-11-10 21:32:12', '2025-11-10 21:32:12'),
(4, 1, 13, '[{\"pergunta_index\":0,\"opcao_index\":0,\"correta\":true},{\"pergunta_index\":1,\"opcao_index\":0,\"correta\":true}]', 100.00, 1, '2025-11-10 22:04:49', '2025-11-10 22:04:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `nivel_conta` enum('iniciante','intermediario','avancado','premium') DEFAULT 'iniciante',
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_cadastro`, `nivel_conta`, `ativo`) VALUES
(1, 'Matheus Felix', 'matheus.dev91@gmail.com', '$2y$10$nbtrumXfdUGNSJIQZbrtU.VqAr0x4hqF.RgO.00lTgi6ikQxjY9Eq', '2025-11-05 23:48:40', 'avancado', 1),
(2, 'Teste User', 'teste@teste.com', '$2y$10$r9kFJhhXR2Lj/T7mJJJcGOBcZmTkrwnkLjHNAJ3hwu6oVu6oXtZS6', '2025-11-10 19:18:27', 'iniciante', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `drive_file_id` varchar(100) NOT NULL,
  `drive_embed_link` varchar(500) NOT NULL,
  `ordem` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `videos`
--

INSERT INTO `videos` (`id`, `modulo_id`, `titulo`, `descricao`, `drive_file_id`, `drive_embed_link`, `ordem`, `ativo`) VALUES
(1, 3, 'Introdução ao Git', 'Aprenda os conceitos fundamentais do Git', '1gvD7FhlMWdjIXz3OWJaqQFbwNXXA_jnp', 'https://drive.google.com/file/d/1gvD7FhlMWdjIXz3OWJaqQFbwNXXA_jnp/preview', 1, 1),
(2, 1, 'Instalação do Git', 'Como instalar e configurar o Git no seu sistema', '1cJNVlHvf4_T4CURpJlkpQFsZVFeweL5Q', 'https://drive.google.com/file/d/1cJNVlHvf4_T4CURpJlkpQFsZVFeweL5Q/preview', 2, 1),
(3, 4, 'Introdução ao Padlet', 'Conheça a ferramenta Padlet e suas funcionalidades', '10CtBeYycyjPizfDUufpkIaQNN7HafrLd', 'https://drive.google.com/file/d/10CtBeYycyjPizfDUufpkIaQNN7HafrLd/preview', 1, 1),
(12, 24, 'Introdução ao Padrão MVC', 'Conceitos fundamentais do padrão Model-View-Controller', '14_AP8xPsjEWjorMDyE8kNTwpvjde6U5_', 'https://drive.google.com/file/d/14_AP8xPsjEWjorMDyE8kNTwpvjde6U5_/preview', 1, 1),
(13, 25, 'Introdução ao PHP', 'Primeiros passos com a linguagem PHP', '11yxLMar_TRocqiZyTyRHs7EHoWvKhQP4', 'https://drive.google.com/file/d/11yxLMar_TRocqiZyTyRHs7EHoWvKhQP4/preview', 1, 1),
(14, 25, 'Variáveis e Funções em PHP', 'Aprendendo sobre variáveis, funções e estruturas básicas', '1jP7Es-qkEz3GDdgdOzmzfOr-EOaemnMz', 'https://drive.google.com/file/d/1jP7Es-qkEz3GDdgdOzmzfOr-EOaemnMz/preview', 2, 1),
(15, 26, 'Formulários HTML com PHP', 'Criando e processando formulários com PHP', '1VP2bHn8nm-CONaRgEkqtlS_WGDt_c_YV', 'https://drive.google.com/file/d/1VP2bHn8nm-CONaRgEkqtlS_WGDt_c_YV/preview', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `videos_assistidos`
--

CREATE TABLE `videos_assistidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `data_assistido` datetime DEFAULT current_timestamp(),
  `progresso` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `videos_assistidos`
--

INSERT INTO `videos_assistidos` (`id`, `usuario_id`, `video_id`, `data_assistido`, `progresso`) VALUES
(17, 1, 3, '2025-11-10 20:59:11', 100.00),
(18, 1, 2, '2025-11-10 21:48:22', 100.00),
(19, 1, 1, '2025-11-10 21:48:25', 100.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atividades`
--
ALTER TABLE `atividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_modulo_id` (`modulo_id`),
  ADD KEY `idx_ordem` (`ordem`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `compras_cursos`
--
ALTER TABLE `compras_cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_compra_usuario_curso` (`usuario_id`,`curso_id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_curso_id` (`curso_id`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nivel_requerido` (`nivel_requerido`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_curso_id` (`curso_id`),
  ADD KEY `idx_ordem` (`ordem`);

--
-- Índices de tabela `niveis_usuario`
--
ALTER TABLE `niveis_usuario`
  ADD PRIMARY KEY (`nivel`);

--
-- Índices de tabela `respostas_usuarios`
--
ALTER TABLE `respostas_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_atividade_id` (`atividade_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_nivel` (`nivel_conta`);

--
-- Índices de tabela `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_modulo_id` (`modulo_id`),
  ADD KEY `idx_ordem` (`ordem`),
  ADD KEY `idx_ativo` (`ativo`);

--
-- Índices de tabela `videos_assistidos`
--
ALTER TABLE `videos_assistidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_video_usuario` (`usuario_id`,`video_id`),
  ADD KEY `idx_usuario_id` (`usuario_id`),
  ADD KEY `idx_video_id` (`video_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atividades`
--
ALTER TABLE `atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `compras_cursos`
--
ALTER TABLE `compras_cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `respostas_usuarios`
--
ALTER TABLE `respostas_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `videos_assistidos`
--
ALTER TABLE `videos_assistidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atividades`
--
ALTER TABLE `atividades`
  ADD CONSTRAINT `atividades_ibfk_1` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `compras_cursos`
--
ALTER TABLE `compras_cursos`
  ADD CONSTRAINT `compras_cursos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compras_cursos_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `modulos_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `respostas_usuarios`
--
ALTER TABLE `respostas_usuarios`
  ADD CONSTRAINT `respostas_usuarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `respostas_usuarios_ibfk_2` FOREIGN KEY (`atividade_id`) REFERENCES `atividades` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`modulo_id`) REFERENCES `modulos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `videos_assistidos`
--
ALTER TABLE `videos_assistidos`
  ADD CONSTRAINT `videos_assistidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `videos_assistidos_ibfk_2` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
