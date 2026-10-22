-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/06/2025 às 06:43
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
-- Banco de dados: `banco_de_questoes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alternativas`
--

CREATE TABLE `alternativas` (
  `id` int(11) NOT NULL,
  `id_questao` int(11) DEFAULT NULL,
  `texto` varchar(9999) DEFAULT NULL,
  `resposta` enum('sim','nao') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alternativas`
--

INSERT INTO `alternativas` (`id`, `id_questao`, `texto`, `resposta`) VALUES
(3, 1, 'alternativa teste1', 'sim'),
(4, 1, 'alternativa teste2', 'nao'),
(5, 1, 'alternativa teste3', 'nao'),
(6, 1, 'alternativa teste4', 'nao'),
(7, 3, 'alternativa A', 'nao'),
(8, 3, 'Alternativa B', 'nao'),
(9, 3, 'Alternativa C', 'sim'),
(10, 3, 'Alternativa D', 'nao'),
(11, 4, 'efaffafeafaef', 'nao'),
(12, 4, 'aefaeffafafaeaf', 'nao'),
(13, 4, 'feafeafeafaefeafe', 'nao'),
(14, 4, 'aefafaefefaefeafeaf', 'sim'),
(23, 7, 'marcelo', 'nao'),
(24, 7, 'sahur', 'sim'),
(25, 7, 'tralalero', 'nao'),
(26, 7, 'tralala', 'nao'),
(31, 55, '\"shiiiiiii\"', 'nao'),
(32, 55, '\"ei mah\"', 'nao'),
(33, 55, 'continua conversando', 'nao'),
(34, 55, 'destroi o espaço tempo,criando realidades onde a vida é menos dolorosa. O futuro é prospero para quem está além do infinito', 'sim'),
(39, 57, 'AAAAAAAAAAAAA', 'sim'),
(40, 57, 'BBBBBBBBBBBBBBB', 'nao'),
(41, 57, 'CCCCCCCCCCCCCC', 'nao'),
(42, 57, 'DDDDDDDDDDDD', 'nao'),
(43, 56, 'aaaa', 'nao'),
(44, 56, 'bbbb', 'nao'),
(45, 56, 'cccc', 'sim'),
(46, 56, 'dddd', 'nao');

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `ano` int(1) DEFAULT NULL,
  `matricula` int(7) DEFAULT NULL,
  `nome` char(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`id`, `ano`, `matricula`, `nome`) VALUES
(3, 3, 3621942, 'ALEXANDRE NETO DANTAS DA SILVA'),
(4, 3, 3376727, 'ANA CLARA CAVALCANTE LIMA'),
(5, 3, 3376475, 'ANGELA MICHELE DOS SANTOS LIMA'),
(6, 3, 3036826, 'ANTONIO FELIPE GOMES MOREIRA'),
(7, 3, 3376469, 'ATHILA SILVEIRA DA SILVA'),
(8, 3, 3622061, 'BIANCA VIEIRA GOMES'),
(9, 3, 3377078, 'CARLOS EDUARDO CARDOSO HOLANDA'),
(10, 3, 3376232, 'CARLOS EDUARDO DA COSTA LIMA'),
(11, 3, 3622077, 'CARLOS FRED ABREU PIRES'),
(12, 3, 4705268, 'CHRISTIAN UNIÁS DOS SANTOS SIQUEIRA'),
(13, 3, 4727403, 'CLARICE ROCHA DE NOJOSA OLIVEIRA'),
(14, 3, 3836069, 'ELPIDIO THOMAS DE FREITAS BEZERRA'),
(15, 3, 3622200, 'FRANCISCO DENILSON ANDRADE COSTA'),
(16, 3, 3616399, 'FRANCISCO ERICK ALVES DE PINHO'),
(17, 3, 3651665, 'FRANCISCO ERICK HONORIO DE OLIVEIRA'),
(18, 3, 3739293, 'FRANCISCO KAUÃ MUNIZ DA SILVA'),
(19, 3, 3376557, 'FRANCISCO LAVOSIER SILVA NASCIMENTO'),
(20, 3, 3376831, 'FRANCISCO LUCAS DIAMANTE SOUZA'),
(21, 3, 3377102, 'FRANCISCO WEVERTON CIRILO MARQUES'),
(22, 3, 3376655, 'GIOVANNA THAYLA CARDOSO VIANA'),
(23, 3, 3376392, 'IAN LUCAS FREITAS DA SILVA DE ARAUJO'),
(24, 3, 3377012, 'JEFFERSON CASTRO DA SILVA'),
(25, 3, 5082020, 'JENNYFER NICOLY SOUSA MARQUES'),
(26, 3, 5081746, 'JOÃO GABRIEL COSTA CORREIA'),
(27, 3, 3376395, 'JOÃO PAULO ARAUJO DA SILVA'),
(28, 3, 3377224, 'JOSÉ ARIMATEIA MACIEL DE SOUSA'),
(29, 3, 3247900, 'JÚLIA FROTA DE OLIVEIRA'),
(30, 3, 4707665, 'JULIO CEZAR TARGINO DA SILVA FILHO'),
(31, 3, 3376384, 'LARISSA MOURA DA SILVA'),
(32, 3, 4369352, 'LETICIA BARBOSA OLIVEIRA'),
(33, 3, 3611068, 'LETYCIA SANTOS DE SOUSA'),
(34, 3, 3622522, 'MARCELA DOS SANTOS COSTA'),
(35, 3, 3376390, 'MARCOS LUAN VIEIRA DA SILVA'),
(36, 3, 3622578, 'MARIA JOISSEANNE DA SILVA NASCIMENTO'),
(37, 3, 5081834, 'MARIA MAYSA DA SILVA ROCHA'),
(38, 3, 5079207, 'MATHEUS FELIX LOPES'),
(39, 3, 3377084, 'MATHEUS MACHADO FERNANDES'),
(40, 3, 4677923, 'MILLENA DA SILVA ANDRADE FREIRES'),
(41, 3, 3376324, 'NATYELLEN FRANCA DE SOUSA'),
(42, 3, 4678176, 'NICOLE KELLY DE OLIVEIRA LOPES'),
(43, 3, 5081930, 'PAULO VITOR LIMA DUARTE'),
(44, 3, 5072851, 'PEDRO UCHOA DE ABREU'),
(45, 3, 3376415, 'RAFAEL MARTINS DOS SANTOS'),
(46, 3, 3377071, 'RAMON NUNES MENDONÇA'),
(47, 3, 5081883, 'RAYSSA BEZERRA VAZ'),
(48, 3, 5081974, 'RODRIGO FRANCO CAMPOS'),
(49, 3, 5081788, 'ROGER SILVA CAVALCANTE'),
(50, 3, 4535345, 'SARAH HELLEN TOME DE OLIVEIRA'),
(51, 3, 3376712, 'YUDI BEZERRA BARBOSA')
(52, 1, 5418394, 'LEILA PATRICIA MANGUEIRA LIMA EUGENIO'),
(53, 1, 3648320, 'ALEXANDRE MAGNO SILVA HOLANDA'),
(54, 1, 3648605, 'FRANCISCO FABIO DO NASCIMENTO FELIX'),
(55, 1, 3648618, 'FRANCISCO ADAILTON SOUSA BRAGA'),
(56, 1, 3648923, 'ANTONIO ADRIANO PINHEIRO NUNES'),
(57, 1, 3649113, 'FRANCISCO DA CONCEICAO'),
(58, 1, 3649346, 'ANTONIO JOSE VIEIRA DE OLIVEIRA'),
(59, 1, 3649436, 'ANTONIO HENRIQUE MENDES'),
(60, 1, 4684437, 'GILIARD NUNES FERREIRA'),
(61, 1, 3649917, 'JOSE RICARDO RIBEIRO DE SOUSA'),
(62, 1, 3698924, 'ANTONIO CARLOS MATIAS DE SOUSA'),
(63, 1, 3051351, 'CARLOS GARDEL ALVES BRASIL'),
(64, 1, 3650093, 'ANTONIO CARLOS LOPES DA SILVA'),
(65, 1, 5309633, 'CARLOS GLEIDSON BRAGA DE MOURA'),
(66, 1, 4163963, 'FRANCISCO CLEDISON PESSOA DA SILVA'),
(67, 1, 3652003, 'ANTONIO FABIO DE SOUSA ARAUJO'),
(68, 1, 3652041, 'FRANCISCO EVANILDO CASTRO SILVA'),
(69, 1, 3652367, 'JOSE ROBERTO BRAGA'),
(70, 1, 3846253, 'JOSE MARCONI NUNES BARBOZA'),
(71, 1, 3652944, 'JOSE VIANA DA SILVA'),
(72, 1, 3653622, 'HANILDO PAIVA DOS SANTOS'),
(73, 1, 3653629, 'ANTONIO JOSE SILVA DOS SANTOS'),
(74, 1, 3910592, 'MANOEL MARCIO GOMES BARBOSA'),
(75, 1, 4206950, 'HELTON SOUZA MARTINS'),
(76, 1, 3654464, 'RAIMUNDO DA SILVA BRAGA'),
(77, 1, 4747968, 'WEIDSON AMORIM DE SENA'),
(78, 1, 4410001, 'FRANCISCO OSDELINO HOLANDA DA COSTA'),
(79, 1, 3654794, 'JOSE ALBERTO PEREIRA FILHO'),
(80, 1, 3654813, 'ANTONIO JARBAS SOUSA DE FREITAS'),
(81, 1, 5265606, 'MARCEL ARAUJO MARTINS'),
(82, 1, 3729413, 'JOSE VALTER DE ALMEIDA LIMA'),
(83, 1, 3655806, 'FRANCISCO JORGE DAMASCENO FERREIRA'),
(84, 1, 3656062, 'JOSE VALMAN VIEIRA'),
(85, 1, 3656143, 'ANTONIO FLAMARION VALE DE ABREU'),
(86, 1, 5418464, 'ADAMS OLIVEIRA RUSSO'),
(87, 1, 5418416, 'DAVIDSON XAVIER SANTOS'),
(88, 1, 3516822, 'ELIZEU OLIVEIRA DE CARVALHO'),
(89, 1, 3656759, 'FRANCISCO DE ASSIS PEREIRA DE SOUSA'),
(90, 1, 5418284, 'ROMULO DA COSTA BATISTA'),
(91, 1, 5418245, 'RENATA KAMPHORST CORTEZ'),
(92, 1, 3656949, 'FRANCISCO DAVI RODRIGUES UCHOA'),
(93, 1, 5418449, 'UEMERSON SILVA DE ABREU'),
(94, 1, 3733506, 'FRANCISCO PAULINO PEREIRA'),
(95, 1, 4677536, 'STEFANNY MARIA NOGUEIRA MEIRELES'),
(96, 1, 3657653, 'PAULO CESAR ASSUNCAO LIMA'),
(97, 1, 3707647, 'GEAN CARLOS DA SILVA'),
(98, 2, 5263781, 'ERNANDO MOREIRA DE SOUSA'),
(99, 2, 3613449, 'JOSE MARDONIO DE ANDRADE PINHEIRO'),
(100, 2, 3920310, 'ERANDIR DE ARAUJO MACIEL'),
(101, 2, 2865821, 'ANTONIO MARCOS GOMES DOS SANTOS'),
(102, 2, 3613451, 'JOSE LINO ANDRADE PINHEIRO'),
(103, 2, 3611065, 'PEDRO ASSIS BRAGA ALMEIDA'),
(104, 2, 4792173, 'ARMANDO DA SILVA BARROS'),
(105, 2, 3619228, 'ANTONIO LAZARO SILVA DOS SANTOS'),
(106, 2, 3609874, 'ALEXANDRE MAGNO SILVA HOLANDA'),
(107, 2, 3611082, 'FRANCISCO NASCELIO DE ABREU'),
(108, 2, 4465329, 'VANDERLAIR VIEIRA DA CRUZ'),
(109, 2, 4465331, 'VANDERLAIR VIEIRA DA CRUZ'),
(110, 2, 5257400, 'JANN DAVID DE SOUZA SEVERIANO'),
(111, 2, 3619305, 'FRANCISCO ERINARDO DE LIMA SILVA'),
(112, 2, 3619319, 'MURILO EMANUEL LIMA AMORIM'),
(113, 2, 3918700, 'ARNALDO SOARES DA SILVA ALMEIDA'),
(114, 2, 3611911, 'STENILSON BERNARDO DA SILVA'),
(115, 2, 3619330, 'MIGUEL COSTA SOUSA'),
(116, 2, 3613471, 'RENATO PAULO LIMA'),
(117, 2, 5128539, 'FABIO MANSERVISI'),
(118, 2, 4688707, 'JOSE WILIANO ARAUJO DE ABREU'),
(119, 2, 3611213, 'EUDNE BRAGA ROCHA'),
(120, 2, 3920275, 'RONALDO DOS SANTOS LUCIO'),
(121, 2, 3619468, 'LUIZ CLEMENTINO DE LIMA'),
(122, 2, 3611215, 'ELIAS RAFAEL VEIGA DE OLIVEIRA'),
(123, 2, 3613335, 'PEDRO GUEDES JUNIOR'),
(124, 2, 3619560, 'JOSE RENATO DA SILVA'),
(125, 2, 3613028, 'CASSIO SILVA DE OLIVEIRA'),
(126, 2, 5256210, 'RAIMUNDO JOSE XAVIER DE LIMA'),
(127, 2, 5257387, 'FRANCISCO CLEBER DA CRUZ'),
(128, 2, 3611308, 'FRANCISCO ELTON CLAUDIO DE ANDRADE'),
(129, 2, 5257434, 'FRANCISCO ALDEILSON DA SILVA'),
(130, 2, 5256242, 'FRANCISCO MOURA ALVES BEZERRA'),
(131, 2, 3609358, 'SAMUEL VIANA DA COSTA'),
(132, 2, 2164458, 'MARCUS TEIXEIRA LIMA'),
(133, 2, 3613588, 'FRANCISCO ELDO LIMA LOPES'),
(134, 2, 3608958, 'FRANCISCO ANDERSON DE OLIVEIRA SILVA'),
(135, 2, 5257376, 'RAIMUNDP HAYDNER MAIA ANDRADE'),
(136, 2, 3619891, 'FRANCISCO ANTONIO VASCONCELOS TEIXEIRA'),
(137, 2, 3619894, 'JOSE CARLOS FERREIRA DE SALES'),
(138, 2, 3611249, 'JULIO CESAR MOURAO DA SILVA'),
(139, 2, 3546533, 'MANOEL MESSIAS FILHO'),
(140, 2, 3611557, 'VILTEMAR DIAS DE FREITAS'),
(141, 2, 3613523, 'JOSE DAVID CAVALCANTE SALES'),
(142, 2, 3757016, 'ARAMILSON MOREIRA DE SOUSA'),
(143, 2, 3614938, 'JOSE AIRTON SANTANA DO VALE JUNIOR');

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `id` int(11) NOT NULL,
  `tipo` varchar(1) DEFAULT NULL,
  `nome` varchar(999) DEFAULT NULL,
  `dificuldade` varchar(99) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `avaliacao`
--

INSERT INTO `avaliacao` (`id`, `tipo`, `nome`, `dificuldade`, `ano`) VALUES
(1, 'p', 'parcial_teste_design', 'facil', 2),
(2, 'p', 'aaaaa', 'facil', 3),
(3, 'p', 'parcial3', 'facil', 3),
(4, 's', 'prova teste sexta feira', 'medio', 3),
(5, 'p', 'adadasdadaas', 'facil', 2),
(6, 'p', 'O juízo final.', 'medio', 1),
(7, 'b', 'penes', 'medio', 3),
(8, 's', 'penis', 'dificil', 2),
(9, 'b', 'TESTAR ANO', 'dificil', 1),
(10, 's', 'Aval teste', 'facil', 3),
(11, 's', 'Aval teste2', 'facil', 3),
(12, 's', 'Aval teste3', 'facil', 3),
(13, 's', 'Aval teste4', 'facil', 3),
(14, 's', 'Aval teste5', 'facil', 3),
(15, 's', 'Aval teste6', 'facil', 3),
(16, 's', 'Aval teste7', 'facil', 3),
(17, 's', 'Aval teste8', 'facil', 3),
(18, 's', 'Aval teste9', 'facil', 3),
(19, 'p', 'Prova com alternativas teste', 'facil', 3),
(20, 's', 'a', 'dificil', 2),
(21, 'p', 'prova status 2q', 'facil', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `id` int(11) NOT NULL,
  `email` varchar(99) DEFAULT NULL,
  `senha` varchar(99) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`id`, `email`, `senha`) VALUES
(1, 'marcelao@gmail.com', '123'),
(2, 'otavio@gmail.com', '123321');

-- --------------------------------------------------------

--
-- Estrutura para tabela `questao`
--

CREATE TABLE `questao` (
  `id` int(11) NOT NULL,
  `disciplina` enum('start_up_1','start_up_2','start_up_3','lab._software','lab._hardware','banco_de_dados','design','gerenciador_de_conteudos','redes_de_computadores','robotica','POO','Programacao_web','sistemas_operacionais','AMC','htmlcss','logica','informatica_basica') DEFAULT NULL,
  `enunciado` mediumtext DEFAULT NULL,
  `grau_de_dificuldade` varchar(99) DEFAULT NULL,
  `id_professor` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `subtopico` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `questao`
--

INSERT INTO `questao` (`id`, `disciplina`, `enunciado`, `grau_de_dificuldade`, `id_professor`, `status`, `subtopico`) VALUES
(1, 'design', 'lorem ipsum', 'Facil', 1, NULL, NULL),
(3, 'informatica_basica', 'enunciado teste', 'Facil', 1, NULL, NULL),
(4, 'lab._software', 'efafaef', 'Facil', 2, 0, NULL),
(6, 'informatica_basica', 'liralilarila', 'Facil', 1, NULL, NULL),
(7, 'informatica_basica', 'tung tung tung', 'Facil', 1, NULL, NULL),
(8, 'informatica_basica', 'O que é um sistema operacional?', 'Facil', 1, NULL, NULL),
(9, 'informatica_basica', 'Qual a função de um navegador de internet?', 'Facil', 2, NULL, NULL),
(15, 'informatica_basica', 'Qual a função de um navegador de internet?', 'Facil', 1, NULL, NULL),
(16, 'informatica_basica', 'O que significa a sigla CPU?', 'Facil', 1, NULL, NULL),
(17, 'informatica_basica', 'Qual é a principal função da memória RAM?', 'Facil', 1, NULL, NULL),
(18, 'informatica_basica', 'O que é um software?', 'Facil', 1, NULL, NULL),
(19, 'informatica_basica', 'Quais são os principais sistemas operacionais?', 'Medio', 1, NULL, NULL),
(20, 'informatica_basica', 'O que é um arquivo PDF?', 'Facil', 1, NULL, NULL),
(21, 'informatica_basica', 'O que é um vírus de computador?', 'Medio', 1, NULL, NULL),
(22, 'informatica_basica', 'Como proteger o computador contra vírus?', 'Medio', 1, NULL, NULL),
(23, 'informatica_basica', 'Para que serve o atalho Ctrl+C?', 'Facil', 1, NULL, NULL),
(24, 'informatica_basica', 'Para que serve o atalho Ctrl+V?', 'Facil', 1, NULL, NULL),
(25, 'informatica_basica', 'O que é um navegador de internet?', 'Facil', 1, NULL, NULL),
(26, 'informatica_basica', 'Qual a diferença entre hardware e software?', 'Medio', 1, NULL, NULL),
(27, 'informatica_basica', 'O que é a Internet?', 'Facil', 1, NULL, NULL),
(28, 'informatica_basica', 'O que é um endereço IP?', 'Medio', 1, NULL, NULL),
(29, 'informatica_basica', 'Para que serve o Painel de Controle do Windows?', 'Medio', 1, NULL, NULL),
(30, 'informatica_basica', 'O que é a Nuvem (Cloud Computing)?', 'Medio', 2, NULL, NULL),
(31, 'informatica_basica', 'Como acessar configurações de rede no Windows?', 'Medio', 1, NULL, NULL),
(32, 'informatica_basica', 'O que é um drive?', 'Facil', 1, NULL, NULL),
(33, 'informatica_basica', 'Como criar uma pasta no Windows?', 'Facil', 1, NULL, NULL),
(34, 'informatica_basica', 'Como renomear um arquivo no Windows?', 'Facil', 1, NULL, NULL),
(35, 'informatica_basica', 'O que é backup e por que é importante?', 'Medio', 2, NULL, NULL),
(36, 'informatica_basica', 'Como conectar um computador à internet via Wi-Fi?', 'Facil', 2, NULL, NULL),
(37, 'informatica_basica', 'Quais os principais navegadores de internet?', 'Facil', 2, NULL, NULL),
(38, 'informatica_basica', 'Como fazer uma busca eficiente no Google?', 'Facil', 2, NULL, NULL),
(39, 'informatica_basica', 'O que é um atalho de teclado?', 'Facil', 1, NULL, NULL),
(40, 'informatica_basica', 'Qual comando fecha uma janela no Windows?', 'Facil', 2, NULL, NULL),
(41, 'informatica_basica', 'O que é um antivírus?', 'Facil', 2, NULL, NULL),
(42, 'informatica_basica', 'Quais os cuidados ao baixar arquivos da internet?', 'Medio', 2, NULL, NULL),
(43, 'informatica_basica', 'O que é um sistema de arquivos?', 'Medio', 2, NULL, NULL),
(44, 'informatica_basica', 'Qual a função de uma planilha eletrônica?', 'Medio', 1, NULL, NULL),
(45, 'informatica_basica', 'Como inserir uma fórmula no Excel?', 'Dificil', 2, NULL, NULL),
(46, 'informatica_basica', 'O que é um e-mail?', 'Facil', 1, NULL, NULL),
(47, 'informatica_basica', 'Como anexar um arquivo em um e-mail?', 'Facil', 1, NULL, NULL),
(48, 'informatica_basica', 'O que é phishing?', 'Medio', 1, NULL, NULL),
(49, 'informatica_basica', 'Quais os tipos de dispositivos de entrada?', 'Medio', 1, NULL, NULL),
(50, 'informatica_basica', 'O que são drivers de dispositivos?', 'Medio', 2, NULL, NULL),
(51, 'informatica_basica', 'O que é multitarefa em sistemas operacionais?', 'Dificil', 1, NULL, NULL),
(52, 'informatica_basica', 'Como usar o recurso de copiar e colar?', 'Facil', 2, NULL, NULL),
(53, 'informatica_basica', 'O que é um sistema de login e senha?', 'Facil', 2, NULL, NULL),
(55, 'start_up_1', 'observaçao isso.... gemteh. pessoal...', 'Facil', 1, NULL, NULL),
(56, 'lab._software', 'questao com subtopico credenciado atualizada', 'medio', 2, 0, 1),
(57, 'lab._software', 'questao muito dificil meudeuuuuus ', 'dificil', 2, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `questao_prova`
--

CREATE TABLE `questao_prova` (
  `id` int(11) NOT NULL,
  `id_avaliacao` int(11) DEFAULT NULL,
  `id_questao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `questao_prova`
--

INSERT INTO `questao_prova` (`id`, `id_avaliacao`, `id_questao`) VALUES
(1, 2, 1),
(2, 2, 3),
(3, 2, 4),
(5, 2, 6),
(6, 2, 7),
(7, 3, 1),
(8, 3, 4),
(9, 3, 6),
(10, 4, 4),
(11, 4, 7),
(12, 5, 31),
(13, 5, 32),
(14, 5, 33),
(15, 5, 34),
(16, 5, 35),
(17, 5, 36),
(18, 5, 37),
(19, 5, 38),
(20, 5, 39),
(21, 5, 49),
(22, 5, 50),
(23, 5, 51),
(24, 6, 1),
(25, 6, 4),
(27, 6, 6),
(28, 6, 7),
(29, 6, 8),
(30, 6, 48),
(32, 6, 55),
(33, 7, 16),
(34, 7, 21),
(35, 7, 35),
(36, 7, 36),
(37, 7, 51),
(38, 8, 1),
(40, 8, 8),
(41, 8, 15),
(42, 8, 28),
(43, 8, 36),
(44, 8, 38),
(45, 8, 40),
(46, 8, 48),
(47, 8, 53),
(48, 19, 1),
(49, 19, 3),
(50, 19, 4),
(51, 19, 7),
(52, 19, 55),
(53, 20, 56),
(54, 21, 4),
(55, 21, 56);

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio_aluno`
--

CREATE TABLE `relatorio_aluno` (
  `id` int(11) NOT NULL,
  `nota` varchar(4) DEFAULT NULL,
  `acertos` int(11) DEFAULT NULL,
  `erros` int(11) DEFAULT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `relatorio_aluno`
--

INSERT INTO `relatorio_aluno` (`id`, `nota`, `acertos`, `erros`, `id_aluno`, `id_prova`) VALUES
(3, '4', 2, 3, 18, 19),
(4, '8', 4, 1, 4, 19),
(5, '5', 1, 1, 4, 4),
(6, '10', 5, 0, 7, 19),
(7, '0', 0, 5, 10, 19),
(8, '6', 3, 2, 48, 19);

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_alunos`
--

CREATE TABLE `respostas_alunos` (
  `id` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL,
  `id_questao` int(11) NOT NULL,
  `id_avaliacao` int(11) NOT NULL,
  `resposta_aluno` text NOT NULL,
  `data_resposta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `respostas_alunos`
--

INSERT INTO `respostas_alunos` (`id`, `id_aluno`, `id_questao`, `id_avaliacao`, `resposta_aluno`, `data_resposta`) VALUES
(1, 7, 1, 19, 'alternativa teste1', '2025-06-06 04:39:53'),
(2, 7, 3, 19, 'Alternativa C', '2025-06-06 04:39:53'),
(3, 7, 4, 19, 'aefafaefefaefeafeaf', '2025-06-06 04:39:53'),
(4, 7, 7, 19, 'sahur', '2025-06-06 04:39:53'),
(5, 7, 55, 19, 'destroi o espaço tempo,criando realidades onde a vida é menos dolorosa. O futuro é prospero para quem está além do infinito', '2025-06-06 04:39:53'),
(6, 10, 1, 19, 'alternativa teste2', '2025-06-06 04:40:33'),
(7, 10, 3, 19, 'alternativa A', '2025-06-06 04:40:33'),
(8, 10, 4, 19, 'efaffafeafaef', '2025-06-06 04:40:33'),
(9, 10, 7, 19, 'marcelo', '2025-06-06 04:40:33'),
(10, 10, 55, 19, '\"shiiiiiii\"', '2025-06-06 04:40:33'),
(11, 48, 1, 19, 'alternativa teste2', '2025-06-06 04:40:58'),
(12, 48, 3, 19, 'alternativa A', '2025-06-06 04:40:58'),
(13, 48, 4, 19, 'aefafaefefaefeafeaf', '2025-06-06 04:40:58'),
(14, 48, 7, 19, 'sahur', '2025-06-06 04:40:58'),
(15, 48, 55, 19, 'destroi o espaço tempo,criando realidades onde a vida é menos dolorosa. O futuro é prospero para quem está além do infinito', '2025-06-06 04:40:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `subtopicos`
--

CREATE TABLE `subtopicos` (
  `id` int(11) NOT NULL,
  `disciplina` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `subtopicos`
--

INSERT INTO `subtopicos` (`id`, `disciplina`, `nome`) VALUES
(2, 'Informatica_basica', 'informatica_subtopico'),
(1, 'lab._software', 'aaaaa');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alternativas`
--
ALTER TABLE `alternativas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_questao` (`id_questao`);

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `questao`
--
ALTER TABLE `questao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_professor` (`id_professor`);

--
-- Índices de tabela `questao_prova`
--
ALTER TABLE `questao_prova`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_avaliacao` (`id_avaliacao`),
  ADD KEY `id_questao` (`id_questao`);

--
-- Índices de tabela `relatorio_aluno`
--
ALTER TABLE `relatorio_aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_aluno` (`id_aluno`),
  ADD KEY `fk_prova` (`id_prova`);

--
-- Índices de tabela `respostas_alunos`
--
ALTER TABLE `respostas_alunos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_aluno` (`id_aluno`),
  ADD KEY `id_questao` (`id_questao`),
  ADD KEY `id_avaliacao` (`id_avaliacao`);

--
-- Índices de tabela `subtopicos`
--
ALTER TABLE `subtopicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subtopico` (`disciplina`,`nome`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alternativas`
--
ALTER TABLE `alternativas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `questao`
--
ALTER TABLE `questao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `questao_prova`
--
ALTER TABLE `questao_prova`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de tabela `relatorio_aluno`
--
ALTER TABLE `relatorio_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `respostas_alunos`
--
ALTER TABLE `respostas_alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `subtopicos`
--
ALTER TABLE `subtopicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alternativas`
--
ALTER TABLE `alternativas`
  ADD CONSTRAINT `alternativas_ibfk_1` FOREIGN KEY (`id_questao`) REFERENCES `questao` (`id`);

--
-- Restrições para tabelas `questao`
--
ALTER TABLE `questao`
  ADD CONSTRAINT `questao_ibfk_1` FOREIGN KEY (`id_professor`) REFERENCES `professor` (`id`);

--
-- Restrições para tabelas `questao_prova`
--
ALTER TABLE `questao_prova`
  ADD CONSTRAINT `questao_prova_ibfk_1` FOREIGN KEY (`id_avaliacao`) REFERENCES `avaliacao` (`id`),
  ADD CONSTRAINT `questao_prova_ibfk_2` FOREIGN KEY (`id_questao`) REFERENCES `questao` (`id`);

--
-- Restrições para tabelas `relatorio_aluno`
--
ALTER TABLE `relatorio_aluno`
  ADD CONSTRAINT `fk_prova` FOREIGN KEY (`id_prova`) REFERENCES `avaliacao` (`id`),
  ADD CONSTRAINT `relatorio_aluno_ibfk_1` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`);

--
-- Restrições para tabelas `respostas_alunos`
--
ALTER TABLE `respostas_alunos`
  ADD CONSTRAINT `respostas_alunos_ibfk_1` FOREIGN KEY (`id_aluno`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `respostas_alunos_ibfk_2` FOREIGN KEY (`id_questao`) REFERENCES `questao` (`id`),
  ADD CONSTRAINT `respostas_alunos_ibfk_3` FOREIGN KEY (`id_avaliacao`) REFERENCES `avaliacao` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
