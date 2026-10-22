-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/05/2025 às 23:50
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
-- Banco de dados: `sis_pdt2`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `matricula` int(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `turma` varchar(20) NOT NULL,
  `numero_chamada` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`matricula`, `nome`, `turma`, `numero_chamada`) VALUES
(1578696, 'Lara Raiza Martins Almeida', '3D', '29'),
(1606035, 'Rayele Gama Veras', '3C', '44'),
(2164458, 'Metusael Sousa Lima', '2B', '35'),
(2198185, 'Jayllanna Guimaraes Gomes', '3A', '19'),
(2719237, 'Jamysson Araujo Bezerra', '3D', '23'),
(2839239, 'Davylla Faustino de Souza', '1C', '14'),
(2865821, 'Antonia Marta Nascimento dos Santos', '2B', '4'),
(3036826, 'Antonio Felipe Gomes Moreira', '3B', '4'),
(3051351, 'Carlos Henrique Costa Alves', '1B', '12'),
(3243053, 'Ana Lara Alves da Silva', '3D', '3'),
(3243434, 'Maria Alessandra de Souza Morais', '3D', '36'),
(3244753, 'Pedro Ivo Fernandes Viudez', '3C', '42'),
(3246878, 'Larissa de Oliveira Mendes Sousa', '3C', '29'),
(3247900, 'Julia Frota de Oliveira', '3B', '27'),
(3367170, 'Adryelli Silva Guedes', '3C', '1'),
(3376232, 'Carlos Eduardo da Costa Lima', '3B', '8'),
(3376252, 'Ana Kessia Cardoso Coelho', '3C', '5'),
(3376272, 'Giselly Sousa Sampaio', '3D', '21'),
(3376287, 'Rian Jhones Brito da Silva Barros', '3C', '45'),
(3376303, 'Lanna Regia de Lima Monteiro', '3A', '23'),
(3376324, 'Natyellen Franca de Sousa', '3B', '39'),
(3376345, 'Lara Gabriela Oliveira Mota', '3C', '28'),
(3376352, 'Leia Horrara da Silva Costa', '3A', '26'),
(3376360, 'Ana Livia Nascimento de Lima', '3C', '7'),
(3376383, 'Nycole Rodrigues Gomes', '3A', '40'),
(3376384, 'Larissa Moura da Silva', '3B', '29'),
(3376386, 'Maria Carynne Monteiro Lopes', '3A', '32'),
(3376390, 'Marcos Luan Vieira da Silva', '3B', '33'),
(3376392, 'Ian Lucas Freitas da Silva de Araujo', '3B', '21'),
(3376395, 'Joao Paulo Araujo da Silva', '3B', '25'),
(3376396, 'Jose Maycon da Silva Abreu', '3D', '28'),
(3376405, 'Julia Evelyn Augusto de Sousa', '3C', '24'),
(3376412, 'Maria Eduarda Sousa Lima', '3D', '38'),
(3376415, 'Rafael Martins dos Santos', '3B', '43'),
(3376466, 'Luiz Fernando Abreu de Mesquita', '3D', '33'),
(3376475, 'Angela Michele dos Santos Lima', '3B', '3'),
(3376481, 'Antonia Regiane Rodrigues da Silva', '3A', '9'),
(3376489, 'Mayara Vitoria de Moura Capistrano', '3C', '38'),
(3376491, 'Ana Luiza Gama Rocha', '3C', '8'),
(3376499, 'Athila Silveira da Silva', '3B', '5'),
(3376504, 'Rita Silva dos Santos', '3D', '47'),
(3376557, 'Francisco Lavosier Silva Nascimento', '3B', '17'),
(3376579, 'Mariane Barbosa de Sousa', '3C', '35'),
(3376655, 'Giovanna Thayla Cardoso Viana', '3B', '20'),
(3376712, 'Yudi Bezerra Barbosa', '3B', '49'),
(3376724, 'Antonia Gleyciane Abreu de Sousa', '3C', '11'),
(3376727, 'Ana Clara Cavalcante Lima', '3B', '2'),
(3376808, 'Joao Batista Capistrano Filho', '3D', '24'),
(3376810, 'Francisco Edson Sousa Lima Filho', '3D', '19'),
(3376830, 'Fatima Caylanny Dias de Souza', '3A', '13'),
(3376831, 'Francisco Lucas Diamante Souza', '3B', '18'),
(3376956, 'Ingrid Mikaelly Cavalcante Silva', '3C', '22'),
(3376961, 'Emanoela Militao Azevedo', '3A', '12'),
(3376964, 'Maria Clara Farias Gomes da Cunha', '3A', '33'),
(3376985, 'Kariny Maria do Nascimento Mourao', '3A', '21'),
(3377012, 'Jefferson Castro da Silva', '3B', '22'),
(3377026, 'Marilia Dayenne Silva de Oliveira Morais', '3D', '42'),
(3377035, 'Ana Luiza Barbosa dos Santos', '3A', '6'),
(3377037, 'Carolina Keteley dos Santos Henrique', '3C', '14'),
(3377056, 'Alice da Silva Sousa', '3D', '1'),
(3377066, 'Lydson de Sousa Martins', '3D', '34'),
(3377071, 'Ramon Nunes Mendonca', '3B', '44'),
(3377078, 'Carlos Eduardo Cardoso Holanda', '3B', '7'),
(3377084, 'Matheus Machado Fernandes', '3B', '37'),
(3377102, 'Francisco Weverton Cirilo Marques', '3B', '19'),
(3377106, 'Lohany Barbosa Rodrigues', '3C', '32'),
(3377137, 'Hiago Sousa da Silva', '3C', '21'),
(3377148, 'Joao Vitor Ciriaco de Sousa', '3D', '27'),
(3377162, 'Rosana Paz da Costa', '3C', '46'),
(3377176, 'Francisco Christyan Teofilo da Silva', '3D', '18'),
(3377185, 'Isadora Costa Martins', '2A', '19'),
(3377215, 'Joao Pedro Soares Sousa', '3D', '26'),
(3377224, 'Jose Arimateia Maciel de Sousa', '3B', '26'),
(3377229, 'Sthefanny Alves dos Santos', '3C', '48'),
(3377253, 'Thyago Lenilson Mariano dos Santos', '3A', '46'),
(3377254, 'Luciany Silva de Sousa', '3C', '33'),
(3377274, 'Maria Evyla Ribeiro de Oliveira', '3D', '39'),
(3377322, 'Ana Vitoria Santos da Silva', '3C', '10'),
(3377328, 'Raniely Monteiro da Silva', '3C', '43'),
(3422347, 'Francisca Larisse Santos Pereira', '3C', '18'),
(3509863, 'Maria Lais Moura de Oliveira', '2A', '30'),
(3516822, 'Paulo Jose da Silva de Carvalho', '1B', '37'),
(3546533, 'Victor Emanuel Moreira Silveira', '2B', '42'),
(3568500, 'Caio da Silva Vieira', '3A', '11'),
(3596721, 'Maria Fernanda Castro de Sousa', '1D', '33'),
(3599739, 'Yasmin Abreu Macedo', '2A', '48'),
(3606559, 'Amanda Sara Inacio Pereira', '2A', '1'),
(3607756, 'Alice Paula Santos de Sousa', '2D', '2'),
(3607796, 'Antonio Guilherme Oliveira Santos', '2D', '8'),
(3607832, 'Maria Eduarda de Sousa Araujo', '2C', '28'),
(3607835, 'Maria Vitoria Braga Barbosa', '2D', '38'),
(3607872, 'Christian Carvalho Freitas', '2C', '6'),
(3608046, 'Allana Livia Pereira de Almeida', '2D', '3'),
(3608064, 'Evelyn Maria Lima Castro', '2A', '13'),
(3608122, 'Vanessa Andrade de Lima', '2A', '44'),
(3608610, 'Ana Beatriz Guedes Cruz', '2A', '2'),
(3608613, 'Adrielly Bonifacio da Silva', '2D', '1'),
(3608767, 'Emilly Ieda Lima Cavalcante', '2A', '12'),
(3608780, 'Luiz Pedro Laureano de Sousa', '2D', '32'),
(3608809, 'Italo Sousa da Silva', '3A', '18'),
(3608838, 'Francisco Marley Ferreira Teofilo', '2C', '9'),
(3608876, 'Maria Beatriz Silva Sousa', '2D', '35'),
(3608958, 'Paulo Henrique Bonifacio Silva', '2B', '37'),
(3608967, 'Samyra Sousa de Abreu', '2D', '43'),
(3609131, 'Joao Angelo Viana Maia', '2D', '22'),
(3609256, 'Felipe Lima Gomes', '3D', '15'),
(3609301, 'Elany Vitoria Rodrigues de Araujo', '2C', '7'),
(3609358, 'Marian Lilian da Silva Costa', '2B', '34'),
(3609408, 'Rita de Cassia Ferreira Gonzaga', '2C', '41'),
(3609435, 'Carlos Samuel de Sousa Silva', '2D', '10'),
(3609453, 'Tadeu Lucio Viana Maia', '2D', '44'),
(3609550, 'Thalyta Gabriela Bordin Machado', '2A', '43'),
(3609634, 'Lara Margarida Cavalcante Gois', '2D', '26'),
(3609660, 'Ana Carolina Silva Andrade', '2C', '2'),
(3609874, 'Ayde Maria Araujo Holanda', '2B', '9'),
(3609894, 'Bruna Brasileiro Ferreira', '2C', '4'),
(3610074, 'Izadora Thauany Freitas de Araujo', '2C', '14'),
(3610323, 'Emily Beatriz Lessa da Silva', '2D', '12'),
(3610473, 'Jose Vitor Alves Pereira Almeida', '2A', '21'),
(3610512, 'Maria de Paula Cavalcante Colares', '2A', '28'),
(3610602, 'Maria Michelly Sampaio Vieira', '2A', '32'),
(3610683, 'Evellyn Sousa Rodrigues de Vasconcelos', '2C', '8'),
(3610726, 'Lucio Guilherme Vieira da Silva', '2C', '23'),
(3610727, 'Maria Ariane Silva de Oliveira', '2D', '33'),
(3610733, 'Viviane Hellen Alves Fernandes', '1A', '45'),
(3611065, 'Antonio Pedro Lucas Pereira de Almeida', '2B', '6'),
(3611068, 'Letycia Santos de Sousa', '3B', '31'),
(3611082, 'Bianca Cleysla Gaspar de Abreu', '2B', '10'),
(3611086, 'Davi Rufino de Sousa', '2A', '11'),
(3611213, 'Guilherme da Silva Rocha', '2B', '22'),
(3611215, 'Jairla Lemos de Oliveira', '2B', '25'),
(3611249, 'Thalyta Helen da Silva Costa', '2B', '41'),
(3611308, 'Lucas Oliveira de Andrade', '2B', '31'),
(3611557, 'Viltemar Dias de Freitas Filho', '2B', '43'),
(3611565, 'Ramonna Stefany Martins de Oliveira', '2A', '39'),
(3611911, 'Felipe Bernardo da Silva', '2B', '17'),
(3612170, 'Maria Clara Ferreira de Araujo', '2C', '27'),
(3612176, 'Maria Clara Pereira dos Santos', '2A', '27'),
(3612272, 'Ludmylla da Costa Silva', '2D', '31'),
(3612961, 'Ingrid Oliveira de Souza', '3A', '17'),
(3613028, 'Kaio Monteiro de Oliveira', '2B', '28'),
(3613287, 'Francisco Kaio Pereira da Silva', '2D', '15'),
(3613313, 'Naci Oliveira de Sousa', '2C', '11'),
(3613335, 'Jose Pedro Guedes Neto', '2B', '26'),
(3613337, 'Karolyne Peixoto da Silva', '2A', '23'),
(3613366, 'Maria Vitoria Pereira dos Santos', '2C', '30'),
(3613384, 'Mickael Lucas dos Reis Pociano', '2C', '33'),
(3613387, 'Mirella Oliveira de Paiva', '2C', '35'),
(3613449, 'Abraao Lyncoln Sampaio de Andrade', '2B', '2'),
(3613451, 'Antonio Jonathan Sampaio Pinheiro', '2B', '5'),
(3613471, 'Francisca Raiane Brazil Lima', '2B', '19'),
(3613477, 'Hermes de Sousa Almeida Filho', '2D', '19'),
(3613523, 'William Vasconcelos Sales', '2B', '44'),
(3613554, 'Sara Melyssa Viana Cavalcante', '2C', '45'),
(3613588, 'Paula Ketlen de Lima Lopes', '2B', '36'),
(3613936, 'Ketley Kaianny Sousa de Oliveira', '3C', '27'),
(3613992, 'Myrela Pires Soares', '3A', '37'),
(3614703, 'Wheverton Cristian da Silva Rocha', '2D', '46'),
(3614845, 'Ismael de Sousa Alves Junior', '2D', '21'),
(3614888, 'Livia da Silva Morais', '2D', '30'),
(3614938, 'Yasmin Ketelyn da Silva Santana', '2B', '46'),
(3615537, 'Derick da Silva Pereira', '3D', '12'),
(3615571, 'Francisco Adrian Lima da Costa', '3C', '19'),
(3616399, 'Francisco Erick Alves de Pinho', '3B', '14'),
(3619124, 'Ana Beatriz de Abreu Lima', '2D', '5'),
(3619156, 'Ana Luysa Soares Facanha', '2A', '6'),
(3619228, 'Arthur Freitas Lima dos Santos', '2B', '8'),
(3619236, 'Bruna Rodrigues Duarte', '2A', '8'),
(3619266, 'Clarysse Marianne Lima Dias', '2A', '9'),
(3619270, 'Crislane Estevam da Silva', '2A', '10'),
(3619305, 'Ellen Christine Oliveira Silva', '2B', '14'),
(3619319, 'Enzo Miguel Cardoso Lima', '2B', '15'),
(3619330, 'Felipe Correia Sousa', '2B', '18'),
(3619332, 'Fernanda Kevilly da Silva Soares', '2A', '15'),
(3619381, 'Francisco Kauan Sousa Lira', '2A', '17'),
(3619468, 'Isabelly Facanha de Lima', '2B', '24'),
(3619546, 'Jose Akil de Souza Pereira Moreira', '2C', '16'),
(3619560, 'Jose Renato da Silva', '2B', '27'),
(3619577, 'Kailane Nogueira Bessa', '2C', '18'),
(3619597, 'Kayllane Moura de Sousa', '2A', '24'),
(3619606, 'Lara Fabiana dos Santos Sousa', '2D', '25'),
(3619626, 'Leticia Barros da Silva', '2D', '28'),
(3619629, 'Leticia Machado de Oliveira', '2D', '29'),
(3619662, 'Luiz Gabriel dos Santos Leite', '2C', '24'),
(3619667, 'Luiza Mariane da Silva Nascimento', '2A', '26'),
(3619717, 'Maria Fernanda Moura da Silva', '2A', '29'),
(3619729, 'Maria Lara Souza dos Santos', '2A', '31'),
(3619750, 'Maria Vitoria Silva Gomes', '2C', '31'),
(3619766, 'Matheus Freitas Marques', '2C', '32'),
(3619773, 'Mayra Kessy de Sousa Batista', '2D', '39'),
(3619776, 'Messias Wesley Macedo de Abreu', '2A', '35'),
(3619791, 'Milena Francalino de Sousa', '2A', '36'),
(3619795, 'Myckaelly Marques Alves', '2D', '40'),
(3619845, 'Rebeca Rodrigues Martins', '2A', '41'),
(3619881, 'Samuel Rodrigues de Sousa', '2C', '43'),
(3619891, 'Sophia Loren do Nascimento Teixeira', '2B', '39'),
(3619894, 'Suyanne Cardoso de Sales', '2B', '40'),
(3621942, 'Alexandre Neto Dantas da Silva', '3B', '1'),
(3621973, 'Ana Leticia Silva Barbosa', '3C', '6'),
(3621976, 'Ana Livia Sousa da Silva', '3D', '5'),
(3621999, 'Anna Gabrielly Rodrigues Souza', '3A', '7'),
(3622051, 'Arnatyellen Riane Pereira Alves', '3A', '10'),
(3622061, 'Bianca Vieira Gomes', '3B', '6'),
(3622077, 'Carlos Fred Abreu Pires', '3B', '9'),
(3622099, 'Davi Silva de Lima', '3D', '9'),
(3622151, 'Evilin Vitoria Macedo Oliveira', '3D', '14'),
(3622190, 'Francisco Abner de Sousa Chagas', '3D', '16'),
(3622195, 'Francisco Ariel Andrade de Souza', '3D', '17'),
(3622196, 'Francisco Arthur Santos da Silva', '3A', '14'),
(3622200, 'Francisco Denilson Andrade Costa', '3B', '13'),
(3622340, 'Jamilly Germano Santos', '3D', '22'),
(3622381, 'Joao Victor Costa Sousa', '3C', '23'),
(3622430, 'Juliana Gomes de Sousa', '3A', '20'),
(3622478, 'Levita da Silva Macieira', '3D', '30'),
(3622488, 'Lucas dos Reis Feitosa', '3A', '29'),
(3622494, 'Luis Guilherme da Silva Lima', '3D', '31'),
(3622498, 'Luiz Henrique Martins Lima', '3A', '30'),
(3622499, 'Luis Otavio do Nascimento Oliveira', '3D', '32'),
(3622513, 'Luma Thayse Castro Cavalcante', '3A', '31'),
(3622522, 'Marcela dos Santos Costa', '3B', '32'),
(3622556, 'Maria Denise Ferreira de Sousa', '3D', '37'),
(3622559, 'Maria Eduarda da Silva Sousa', '3A', '34'),
(3622578, 'Maria Joisseanne da Silva Nascimento', '3B', '34'),
(3622648, 'Nara Livia Ferreira Nunes', '3A', '38'),
(3622659, 'Nicole Correia da Silva', '3A', '39'),
(3622684, 'Pedro Igson Alves Silva', '3C', '41'),
(3622685, 'Pedro Kaue Cavalcante Lima', '3D', '44'),
(3622690, 'Pedro Lucas Rodrigues Franca', '3D', '45'),
(3622752, 'Sophia Amelia Lopes Dias Tomaz', '3A', '44'),
(3622789, 'Vyrna Glicya de Abreu Rocha', '3A', '47'),
(3647777, 'Ana Geysa da Silva Oliveira', '3C', '3'),
(3648176, 'Adrielly Alves Rodrigues', '1A', '1'),
(3648320, 'Alicia Araujo Holanda', '1B', '2'),
(3648523, 'Ana Clara Pereira da Costa', '1A', '2'),
(3648554, 'Ana Cyntia Lima da Silva', '1A', '3'),
(3648605, 'Ana Julia da Silva Felix', '1B', '3'),
(3648618, 'Ana Julia Rodrigues Braga', '1B', '4'),
(3648722, 'Ana Lidia Oliveira Pontes', '1A', '6'),
(3648723, 'Ana Ligia Nascimento de Lima', '1C', '5'),
(3648746, 'Ana Luiza de Abreu Lima', '1C', '6'),
(3648768, 'Ana Lyvia da Silva Costa', '1C', '7'),
(3648833, 'Ana Sarah Abreu Holanda', '1A', '8'),
(3648923, 'Andre Luiz Damiao Pinheiro', '1B', '5'),
(3648995, 'Anna Sophia Alves Andre', '1C', '8'),
(3648997, 'Anne Isabelly do Nascimento Vieira', '1C', '9'),
(3649093, 'Antonia Iasmim Silva de Andrade', '2D', '7'),
(3649113, 'Antonia Juliana Azevedo da Conceicao', '1B', '6'),
(3649346, 'Antonio Eduardo da Silva Oliveira', '1B', '7'),
(3649436, 'Antonio Henrique Mendes', '1B', '8'),
(3649473, 'Antonio Jhonata de Abreu dos Santos', '1D', '3'),
(3649808, 'Bianca Cardoso Castro', '1C', '10'),
(3649900, 'Caio Cesar Araujo Silva', '1A', '11'),
(3649914, 'Caio Sousa Vasconcelos', '1C', '11'),
(3649917, 'Caleb da Silva Lira Ribeiro', '1B', '10'),
(3650093, 'Carlos Henrique Souza da Silva', '1B', '13'),
(3650470, 'Dayana Ferreira Gomes', '3D', '11'),
(3650972, 'Fatima Rayssa Almeida Lopes', '2A', '14'),
(3651195, 'Francisca Juliana Aquino Soares', '1A', '13'),
(3651304, 'Francisca Sabrina Sousa da Silva', '1C', '15'),
(3651376, 'Francisca Yasmin Felix Sousa', '1C', '16'),
(3651568, 'Francisco Davi Sampaio Carneiro', '1D', '8'),
(3651640, 'Francisco Eldo Lima Matos', '1C', '18'),
(3651665, 'Francisco Erick Honorio de Oliveira', '3B', '15'),
(3652003, 'Francisco Luan Santos Araujo', '1B', '16'),
(3652041, 'Francisco Marcus Davi Castro dos Santos', '1B', '17'),
(3652042, 'Francisco Marley Martins Conserva', '3A', '15'),
(3652123, 'Francisco Pedro Lima dos Santos', '2D', '16'),
(3652210, 'Francisco Samuel Silva de Oliveira', '1D', '9'),
(3652367, 'Gabriel Damiao Braga', '1B', '18'),
(3652642, 'Gustavo Rodrigo Araujo Lima', '1D', '11'),
(3652651, 'Hadassa do Nascimento Silva', '1A', '17'),
(3652944, 'Izabelly Felix da Silva', '1B', '20'),
(3653241, 'Joao Lucas da Costa Sampaio', '1D', '16'),
(3653248, 'Joao Lucas Nascimento de Oliveira', '1D', '17'),
(3653319, 'Joao Pedro Morais Nobre', '2D', '23'),
(3653425, 'Joel Levi Silva da Conceicao', '1D', '18'),
(3653622, 'Jose Hanilton Araujo Paiva', '1B', '21'),
(3653629, 'Jose Hiarley da Costa dos Santos', '1B', '22'),
(3653840, 'Julia Araujo Albuquerque', '2C', '17'),
(3654198, 'Lara Freire Luz', '1A', '21'),
(3654267, 'Laura Licy Goncalves Quintino', '2D', '27'),
(3654271, 'Laure Nycole da Silva Torres', '1A', '23'),
(3654460, 'Lorena Carvalho Silva', '1D', '24'),
(3654464, 'Lorena Evillyn Santos Braga', '1B', '25'),
(3654466, 'Lorena Santos Almeida', '1A', '25'),
(3654724, 'Luiza Vitoria Xavier da Silva', '1C', '28'),
(3654762, 'Luiz Fernando Vasconcelos da Silva', '1D', '26'),
(3654794, 'Luiz Miguel Sousa Pereira', '1B', '28'),
(3654813, 'Luyd Anthony Oliveira de Freitas', '1B', '29'),
(3654965, 'Marcos Venancio Pereira Viana', '1D', '27'),
(3654984, 'Marcos Vinicius Moreira da Silva', '1D', '28'),
(3655047, 'Maria Ana Flavia Valentim Gadelha', '1C', '29'),
(3655093, 'Maria Byanca de Oliveira Lemos', '1C', '30'),
(3655150, 'Maria Clara Oliveira de Sousa', '1D', '29'),
(3655165, 'Maria Clarisse Santiago de Macedo', '1D', '30'),
(3655497, 'Maria Isabelle Ferreira da Costa', '1A', '28'),
(3655522, 'Maria Izabel Silva de Abreu', '1C', '32'),
(3655599, 'Maria Laura Silva de Oliveira', '1A', '31'),
(3655752, 'Maria Odalice da Costa Freitas', '1D', '36'),
(3655806, 'Maria Rita da Silva Ferreira', '1B', '32'),
(3655959, 'Marina Machado Fernandes', '1A', '35'),
(3656029, 'Mateus Henrique da Silva Souza', '1C', '35'),
(3656062, 'Matheus de Sousa Vieira', '1B', '33'),
(3656143, 'Mayron Martins de Abreu', '1B', '34'),
(3656244, 'Mikael Pietro Sousa Albuquerque', '1D', '39'),
(3656452, 'Nicolle Lopes de Oliveira', '1A', '37'),
(3656528, 'Paloma da Silva Capistrano', '1D', '40'),
(3656539, 'Pamela Morais Firmino', '1C', '38'),
(3656543, 'Pamylla Moura de Sousa', '1D', '41'),
(3656700, 'Pedro Henry Nunes Mendonca', '1C', '39'),
(3656759, 'Pedro Lucio Silva de Sousa', '1B', '38'),
(3656853, 'Rafaely Vitoria Viana Cavalcante', '3D', '46'),
(3656906, 'Raissa da Silva', '1A', '39'),
(3656949, 'Ravi de Sousa Uchoa', '1B', '41'),
(3656979, 'Rayllane Iolanda Brito Benjamim', '2C', '40'),
(3657171, 'Roberta Kelly dos Santos Araujo', '1D', '43'),
(3657260, 'Rosielly Braga Andrade', '1A', '41'),
(3657372, 'Samile Elayde de Moraes Marques', '1A', '42'),
(3657497, 'Shara Connan Costa Silva', '1C', '41'),
(3657653, 'Taviny Vitoria Alves Lima', '1B', '45'),
(3657731, 'Thaynara Sampaio Rodrigues', '1A', '44'),
(3657732, 'Thays Assuncao Santos', '1D', '45'),
(3657743, 'Thiago Eric Mesquita Ribeiro', '1D', '46'),
(3657850, 'Venancio Ventura da Silva', '1C', '43'),
(3658257, 'Yassmim Estter Castro Maia', '1A', '46'),
(3674565, 'Maria Paula Silva Souza', '1D', '37'),
(3685100, 'Nicolly da Silva Pimentel', '1C', '37'),
(3689612, 'Luigi Rodrigues de Vasconcelos', '1A', '26'),
(3693572, 'Gleique Ferreira da Silva', '2D', '18'),
(3698924, 'Carla Priscila Castro de Sousa', '1B', '11'),
(3699009, 'Maria Jamily de Araujo Chaves', '1D', '34'),
(3701399, 'Samuel Costa Dias', '2C', '42'),
(3701683, 'Klarrion Dias do Nascimento', '3A', '22'),
(3702841, 'Laryssa Vitoria Uchoa de Lima', '1D', '22'),
(3702952, 'Graziela Santos Vieira', '1A', '16'),
(3704326, 'Rafaelly Ribeiro Maciel', '2C', '39'),
(3705783, 'Yahsmim Maria Rocha do Nascimento', '3D', '49'),
(3707647, 'Veridiane Alexandre da Silva', '1B', '46'),
(3707982, 'Isabely Santos Uchoa', '2C', '13'),
(3711266, 'Maria Samile Quintela de Lima', '1A', '34'),
(3721653, 'Rayssa do Nascimento Alves', '1A', '40'),
(3729413, 'Marcos Paulo do Rosario Lima', '1B', '31'),
(3729869, 'Giselle Moreira da Silva', '1A', '15'),
(3730839, 'Willniely de Souza Andrade', '1C', '45'),
(3733506, 'Samuel Caleb Almeida Paulino', '1B', '43'),
(3733895, 'Yanna Geracina do Nascimento Souza', '1C', '46'),
(3739293, 'Francisco Kaua Muniz da Silva', '3B', '16'),
(3739327, 'Francisco Kassio Muniz da Silva', '3C', '20'),
(3740889, 'Caroline Evellyn de Souza Silva', '2C', '5'),
(3757016, 'Yasmim Christine Barbosa Moreira', '2B', '45'),
(3798144, 'Carla Gabrielly dos Santos Felix', '1C', '12'),
(3818077, 'Francisco Daniel Silva Torres', '1C', '17'),
(3836069, 'Elpidio Thomas de Freitas Bezerra', '3B', '12'),
(3842049, 'Milena Andrade do Vale', '3D', '43'),
(3846253, 'Gabriel Garcia Nunes', '1B', '19'),
(3846509, 'Davi Vitor Bezerra', '1C', '13'),
(3849648, 'Antonia Cibelle da Paixao Ferreira', '3A', '8'),
(3853037, 'Ana Davylla Marques Sousa', '2A', '4'),
(3861372, 'Layra Raissa dos Santos Maciel', '1D', '23'),
(3910567, 'Carlos Adonias Vitoriano Ferreira', '1D', '4'),
(3910592, 'Kayllane Sousa Barbosa', '1B', '23'),
(3910594, 'Samara Vitoria da Silva Lima', '1D', '44'),
(3910719, 'Davi Santos de Oliveira', '1D', '5'),
(3911540, 'Gabriela Moreira Silva', '1A', '14'),
(3916934, 'Pedro Lucas Costa Lima', '2A', '38'),
(3918700, 'Ester Andrade Almeida', '2B', '16'),
(3919436, 'Marcus Lucas de Araujo', '3D', '35'),
(3920200, 'Luana Vasconcelos Batista', '3A', '28'),
(3920275, 'Isaac Ikeda dos Santos', '2B', '23'),
(3920310, 'Ana Clara Silva de Araujo Maciel', '2B', '3'),
(3920329, 'Ana Sarah Lima Pereira', '2C', '3'),
(3923169, 'Jullya dos Santos Leonardo Cavalcante', '3C', '25'),
(3942849, 'Ana Beatriz Ferreira Nunes da Veiga', '3A', '3'),
(3956230, 'Monique Oliveira Gomes Gurgel', '2C', '36'),
(3969939, 'Maria Eduarda do Nascimento Miranda', '2D', '36'),
(3970859, 'Antonia Hanna Rabelo Costa', '1A', '9'),
(4065695, 'Maria Lara Nascimento Sousa', '1A', '30'),
(4093652, 'Alice Sousa Costa', '3A', '1'),
(4141404, 'Ana Layna de Souza Costa', '2A', '5'),
(4163963, 'Francisco Gledyson Rodrigues Pessoa', '1B', '15'),
(4173261, 'Mikaelly Castro Teixeira', '1A', '36'),
(4185904, 'Maria Barbara Henrique Pontes', '2D', '34'),
(4186283, 'Joao Vitor dos Santos Silva', '2D', '24'),
(4187669, 'Joao Guilherme Silva Conde', '1D', '15'),
(4188328, 'Kayo Klysman da Silva Aguiar', '1D', '19'),
(4189825, 'Ana Flavia Pereira Santiago', '3A', '4'),
(4191846, 'Nicolas Nascimento da Silva', '2D', '41'),
(4202244, 'Matheus Lima de Araujo', '3C', '37'),
(4206950, 'Larah Isabelle Tabosa da Silva Souza', '1B', '24'),
(4209261, 'Larissa Vieira Brito', '3C', '30'),
(4255654, 'Vitoria Evelyn Lima de Oliveira', '1C', '44'),
(4292167, 'Maria Vitoria Ribeiro de Sousa', '3C', '36'),
(4342876, 'Maria Vivian Lemos Damasceno', '2A', '33'),
(4355898, 'Yan Martins Barros', '2D', '47'),
(4355947, 'Yasmim Frota da Rocha', '2A', '47'),
(4358506, 'Aleff Brayan Braga Vasconcelos', '1D', '1'),
(4358538, 'Maria de Fatima Gomes Estevam', '1C', '31'),
(4362355, 'Ana Sophia da Silva Sousa', '2D', '6'),
(4362465, 'Maria Bianca Rodrigues de Lima', '2C', '26'),
(4363853, 'Matheus Bryan Dias Almeida', '3A', '36'),
(4367712, 'Elloa Oliveira dos Santos', '3C', '17'),
(4367877, 'Marcia Vieira Moura Bezerra dos Santos', '2C', '25'),
(4369352, 'Leticia Barbosa Oliveira', '3B', '30'),
(4410001, 'Luiz Miguel da Silva Holanda da Costa', '1B', '27'),
(4411455, 'Lara Victoria Paiva Torres', '2C', '21'),
(4424615, 'Ana Leticia Paixao de Lima', '1A', '5'),
(4427238, 'Lara Evelyn Freitas Rodrigues', '1D', '21'),
(4465329, 'Camila Souza Cruz', '2B', '11'),
(4465331, 'Carolina Souza Cruz', '2B', '12'),
(4524236, 'Francisca Leonara Fernandes de Menezes', '2A', '16'),
(4524265, 'Joao Ariel Moura Rodrigues', '1D', '14'),
(4524280, 'Ana Munique Marques da Silva', '3C', '9'),
(4535345, 'Sarah Hellen Tome de Oliveira', '3B', '48'),
(4537241, 'Klara Freitas Damasceno', '2C', '20'),
(4539184, 'Lais Alves Costa', '1D', '20'),
(4542129, 'Ana Beatriz da Silva Fermon', '2D', '4'),
(4551350, 'Samira Rodrigues Barbosa', '1C', '40'),
(4553697, 'Thiago Nogueira Barbosa', '2D', '45'),
(4555246, 'Isabelle Leite Maia', '2C', '12'),
(4555530, 'Vanessa Sabrina Costa da Silva', '2C', '46'),
(4556116, 'Saori Branco Oliveira de Castro', '2C', '44'),
(4559160, 'Joao Pedro Abreu da Silva', '3D', '25'),
(4562012, 'Jose Henrique Sampaio Lemos', '2A', '20'),
(4645847, 'Antonelly Almeida de Abreu', '3D', '7'),
(4672510, 'Lara Evenlly Sena Moura', '2A', '25'),
(4675269, 'Iara Cecilia de Sousa Franca', '1D', '12'),
(4677165, 'Rizle Valentina Nogueira Rodrigues', '3D', '48'),
(4677536, 'Stefanny Maria Nogueira Meireles', '1B', '44'),
(4677923, 'Millena da Silva Andrade Freires', '3B', '38'),
(4678176, 'Nicole Kelly de Oliveira Lopes', '3B', '40'),
(4678299, 'Emanuela Coelho do Nascimento', '1D', '7'),
(4678365, 'Yarley Deryk da Costa Holanda', '3D', '50'),
(4679246, 'Byanca da Silva Sousa', '3C', '13'),
(4684437, 'Arthur Soares Nunes', '1B', '9'),
(4684479, 'Maria Sophia da Silva de Sousa', '1D', '38'),
(4688082, 'Cecilia Maysa Aquino Lima', '1A', '12'),
(4688707, 'Gisele Oliveira de Abreu', '2B', '21'),
(4697343, 'Ana Leticia Cardoso Martins', '3D', '4'),
(4705268, 'Christian Unias dos Santos Siqueira', '3B', '10'),
(4707665, 'Julio Cezar Targino da Silva Filho', '3B', '28'),
(4709713, 'Ana Clothilde Pereira de Sousa', '1C', '2'),
(4713947, 'Elias Gabriel Brito das Chagas', '3D', '13'),
(4727222, 'Lia Rocha de Nojosa Oliveira', '3A', '27'),
(4727403, 'Clarice Rocha de Nojosa Oliveira', '3B', '11'),
(4741521, 'Raianny Sampaio de Oliveira', '3A', '42'),
(4741564, 'Ana Beatriz Braz da Silva', '3A', '2'),
(4747204, 'Kaique Araujo de Andrade Sales', '3C', '26'),
(4747968, 'Lucas Antonio Pessoa de Sena', '1B', '26'),
(4749204, 'Leanderson Costa Sousa', '3A', '25'),
(4749220, 'Miguel Angelo Ximenes Nogueira', '3C', '39'),
(4792130, 'Liviane Maria Pontes Barros', '1A', '24'),
(4792173, 'Armando da Silva Barros Filho', '2B', '7'),
(4796920, 'Hillary Moreno da Silva', '1A', '18'),
(4829675, 'Ana Leticia Dantas de Castro', '1A', '4'),
(4835889, 'Francisco Vinicio Cavalcante Brasileiro', '3D', '20'),
(4878950, 'Ana Sofia da Silva Ramos', '2A', '7'),
(4879035, 'Nicolas Batista Braga', '2C', '37'),
(4879810, 'Davydson Soares de Araujo', '3D', '10'),
(4879850, 'Cinthya Menezes Pereira', '3C', '15'),
(4879854, 'Mikael Sousa da Silva', '3C', '40'),
(4884140, 'Anna Julia Lopes Lemos', '3D', '6'),
(4936367, 'Yasmin Luisy Falcao de Morais', '2A', '49'),
(4950825, 'Antonio Samuel Barros Bezerra', '1A', '10'),
(4968220, 'Daniel de Oliveira Rodrigues', '3C', '16'),
(4983438, 'Ana Gloria Santos Pereira', '1C', '3'),
(5022677, 'Maria Eduarda Nunes Maia', '1D', '32'),
(5041875, 'Guilherme Linhares de Sousa', '2C', '10'),
(5072791, 'Mariah Gabryele de Abreu Mendonca', '3D', '41'),
(5072851, 'Pedro Uchoa de Abreu', '3B', '42'),
(5072959, 'Beatriz Vieira Sena', '3D', '8'),
(5073067, 'Maria Gabriele de Oliveira Lima', '3D', '40'),
(5073140, 'Ana Hivyna da Silva Ramos', '3D', '2'),
(5075312, 'Marjorie Evelyn Mendes Cruz', '3A', '35'),
(5075384, 'Sarah Dias Oliveira', '3A', '43'),
(5075741, 'Lara Kathellyn Felipe Costa', '3A', '24'),
(5075869, 'Ana Heloisa Honorio de Abreu', '3A', '5'),
(5076018, 'Francisco Otavio Alves Oliveira', '3A', '16'),
(5077858, 'Nycolle Stefanelle Meneses Costa', '3A', '41'),
(5077925, 'Thiffany Christine Leite de Paiva', '3A', '45'),
(5078090, 'Leticia Carvalho Cordeiro', '3C', '31'),
(5078170, 'Sofia Moura Alves', '3C', '47'),
(5079098, 'Alessandro de Almeida Nunes', '3C', '2'),
(5079207, 'Matheus Felix Lopes', '3B', '36'),
(5081254, 'Maria Eduarda Carvalho Cunha', '3C', '34'),
(5081443, 'Arthur Andrade Araujo', '3C', '12'),
(5081746, 'Joao Gabriel Costa Correia', '3B', '24'),
(5081788, 'Roger Silva Cavalcante', '3B', '47'),
(5081834, 'Maria Maysa da Silva Rocha', '3B', '35'),
(5081883, 'Rayssa Bezerra Vaz ', '3B', '45'),
(5081930, 'Paulo Vitor Lima Duarte', '3B', '41'),
(5081974, 'Rodrigo Franco Campos', '3B', '46'),
(5082020, 'Jennyfer Nicoly Sousa Marques', '3B', '23'),
(5088875, 'Ana Jaisla Souza Vaz', '3C', '4'),
(5101142, 'Ramon Yuri Barbosa Cordeiro', '1D', '42'),
(5101991, 'Julia Ketlen de Sousa Borges', '2A', '22'),
(5102034, 'Emanuelly Souza Feitosa', '2D', '11'),
(5116272, 'Isabelly Braga Costa de Freitas', '1A', '19'),
(5128539, 'Giorgio Manservisi Neto', '2B', '20'),
(5208604, 'Maria Manoela Araujo Rocha', '1A', '33'),
(5239337, 'Raylana Cordeiro Santos', '2A', '40'),
(5245382, 'Allicia Kessia Lemos de Meneses', '2C', '1'),
(5255610, 'Pedro Lucas Guimaraes Sousa', '2D', '42'),
(5255811, 'Augusto Rafael dos Reis Mendes', '2D', '9'),
(5255841, 'Maria Isabela da Silva Dias', '2D', '37'),
(5255891, 'Francisca Giselle Silva Castelo', '2D', '13'),
(5256037, 'Gabriel Rodrigues dos Santos', '2D', '17'),
(5256210, 'Kauan Cristian Santos de Lima', '2B', '29'),
(5256242, 'Maria Eduarda Lima Alves', '2B', '33'),
(5257376, 'Rhayanna da Silva Andrade', '2B', '38'),
(5257387, 'Kauanna Tiburcio da Cruz', '2B', '30'),
(5257400, 'Cassio Holanda Gomes Severiano', '2B', '13'),
(5257434, 'Luis Felipe Oliveira da Silva', '2B', '32'),
(5257876, 'Sabrina Silva Chaves', '2A', '42'),
(5257902, 'Monique Cavalcante Girao Monteiro', '2A', '37'),
(5257932, 'Yasmim Almeida Santana', '2A', '46'),
(5257967, 'Ana Clara Oliveira Cruz', '2A', '3'),
(5257997, 'Viviane Macario Costa', '2A', '45'),
(5258083, 'Marilia de Freitas Alves', '2A', '34'),
(5258135, 'Giselly da Silva Guedes', '2A', '18'),
(5259831, 'Paloma Vitoria Lima do Nascimento', '2C', '38'),
(5259887, 'Mirella Christine de Melo Freitas', '2C', '34'),
(5260192, 'Karen Lima Salgueiro', '2C', '19'),
(5260250, 'Lucas Lima do Nascimento', '2C', '22'),
(5260279, 'Izaias Franco Silva', '2C', '15'),
(5260309, 'Maria Paula Sousa de Miranda Jacinto', '2C', '29'),
(5263781, 'Abner Stefan Fernandes Moreira', '2B', '1'),
(5265329, 'Gabriel Lui Sousa Bezerra', '1D', '10'),
(5265606, 'Marcel Araujo Martins Filho', '1B', '30'),
(5268947, 'Francisco Arllen Almeida de Souza', '2D', '14'),
(5270172, 'Ingrid Felipe Alves', '2D', '20'),
(5309633, 'Carlos Rubens Costa Braga', '1B', '14'),
(5356901, 'Delta Mirella dos Santos Pereira', '1D', '6'),
(5414018, 'Nycole Araujo Pinheiro', '1A', '38'),
(5414459, 'Maria Letycia Nicolly Barros da Silva', '1A', '32'),
(5414677, 'Laura Kilvia da Silva Sousa', '1A', '22'),
(5414730, 'Ana Livia Costa Andrade', '1A', '7'),
(5414762, 'Maria Lanay Vitor de Oliveira', '1A', '29'),
(5414793, 'Maria Eduarda Medeiros Santos', '1A', '27'),
(5414818, 'Samilla Alves Russo', '1A', '43'),
(5414849, 'Julia Gomes Rodrigues', '1A', '20'),
(5414971, 'Ana Clara Mendes Abreu', '1C', '1'),
(5414999, 'Marina Feitosa Galvao Brito', '1C', '34'),
(5415067, 'Maria Julia Lima de Castro', '1C', '33'),
(5415106, 'Stefhany Pires Ferreira', '1C', '42'),
(5415161, 'Ana Julia Diniz Carneiro', '1C', '4'),
(5415182, 'Matheus Amorim dos Santos', '1C', '36'),
(5418003, 'Maria Eduarda de Sousa Silva', '1D', '31'),
(5418026, 'Ana Leticia dos Santos Silva', '1D', '2'),
(5418144, 'Isabelly Ferreira de Sousa', '1D', '13'),
(5418165, 'Lucas Amorim dos Santos', '1D', '25'),
(5418179, 'Maria Laura Bezerra Pontes', '1D', '35'),
(5418245, 'Raul Cortez de Lima', '1B', '40'),
(5418284, 'Phabulo Henry de Lima Batista', '1B', '39'),
(5418394, 'Ainoa Luara Mangueira Eugenio', '1B', '1'),
(5418416, 'Nicolly de Souza Santos', '1B', '36'),
(5418449, 'Samira Moura de Abreu', '1B', '42'),
(5418464, 'Mikael de Jesus Mendes Russo', '1B', '35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `avisos`
--

CREATE TABLE `avisos` (
  `id_aviso` int(11) NOT NULL,
  `aviso` text NOT NULL,
  `data_aviso` date NOT NULL,
  `matricula_aluno` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `avisos`
--

INSERT INTO `avisos` (`id_aviso`, `aviso`, `data_aviso`, `matricula_aluno`) VALUES
(0, 'deixar de ser besta e parar de gostar de algume que nn gosta de ti, e voltar a ser uma pessoa melhor', '2025-05-28', 5081746);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lider`
--

CREATE TABLE `lider` (
  `nome` varchar(120) NOT NULL,
  `matricula_lider` int(10) NOT NULL,
  `bimestre` enum('1°','2°','3°','4°') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lider`
--

INSERT INTO `lider` (`nome`, `matricula_lider`, `bimestre`) VALUES
('Rafael Martins dos Santos', 3376415, '2°');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mapeamento`
--

CREATE TABLE `mapeamento` (
  `id_mapeamento` int(11) NOT NULL,
  `numero_carteira` int(2) NOT NULL,
  `matricula_aluno` int(11) NOT NULL,
  `data_mapeamento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ocorrencias`
--

CREATE TABLE `ocorrencias` (
  `id_ocorrencias` int(11) NOT NULL,
  `ocorrencia` text NOT NULL,
  `data_ocorrencia` date NOT NULL,
  `matricula_aluno` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ocorrencias`
--

INSERT INTO `ocorrencias` (`id_ocorrencias`, `ocorrencia`, `data_ocorrencia`, `matricula_aluno`) VALUES
(0, 'quebrou o coração do gabriel', '2025-05-28', 4677923);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pdts`
--

CREATE TABLE `pdts` (
  `matricula_prof` int(10) NOT NULL,
  `nome_professor` varchar(50) NOT NULL,
  `turma_responsavel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pdts`
--

INSERT INTO `pdts` (`matricula_prof`, `nome_professor`, `turma_responsavel`) VALUES
(30315618, 'Dhenis Silva Maciel', '3D'),
(40179208, 'Davi de Assis Melo', '2C'),
(40261508, 'Romário Souza Magalhães', '1D'),
(40261516, 'Karolina Martins de Medeiros', '2B'),
(40270981, 'Antônio Marcelo Vieira Batista', '1C'),
(40336184, 'Maria Clarice Lima Lucas', '1A'),
(40336222, 'Karla Cristianne Nunes Ferreira e Silva', '2A'),
(40336230, 'José Willamy dos Santos Costa', '3A'),
(40336249, 'Greycianne Felix Cavalcante Luz', '1B'),
(40336257, 'Maria Aparecida Moura de Lima', '3C'),
(40339830, 'Maria das Graças Nunes Rocha', '2D'),
(48262023, 'Thayná Clotilde Alves Pompeu', '3B');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores_comuns`
--

CREATE TABLE `professores_comuns` (
  `matricula` int(10) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `componente_curricular` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professores_comuns`
--

INSERT INTO `professores_comuns` (`matricula`, `nome`, `componente_curricular`) VALUES
(12205317, 'Jamylis Gosson Viana Colombo ', ' '),
(12330316, 'Roberto Barbosa da Silva ', ' '),
(16005517, 'Carlos Henrique Roseo de Paula Pessoa ', ' '),
(30026063, 'Mykerson Sousa Costa', ' '),
(30229010, 'Reginaldo Romulo Coelho Pontes ', ' '),
(30315618, 'Dhenis Silva Maciel ', ' '),
(40179208, 'Davi de Assis Melo ', ' '),
(40261508, 'Romário Souza Magalhães ', ' '),
(40261516, 'Karolina Martins de Medeiros PROF', ' '),
(40270973, 'Carlos Abner Nunes Ferreira ', ' '),
(40270981, 'Antônio Marcelo Vieira Batista', ' '),
(40336184, 'Maria Clarice Lima Lucas Pinheiro', ' '),
(40336192, 'Sigliany Freires Lemos ', ' '),
(40336214, 'Matheus da Silva Falcão ', ' '),
(40336222, 'Karla Cristianne Nunes Ferreira e Silva ', ' '),
(40336230, 'José Willamy dos Santos Costa', ' '),
(40336249, 'Greycianne Felix Cavalcante Luz', ' '),
(40336257, 'Maria Aparecida Moura de Lima ', ' '),
(40350977, 'Brenda Kathellen Melo de Almeida', ' '),
(40389830, 'Maria das Graças Nunes Rocha ', ' '),
(47869315, 'Ana Fabiane Carvalho ', ' '),
(47988713, 'Gislania de Freitas Silva ', ' '),
(48262023, 'Thayná Clotilde Alves Pompeu ', ' '),
(50420019, 'Francisco Antônio Teóteo Monte ', ' '),
(81290628, 'Erica de Medeiros Leite', ' ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `secretario`
--

CREATE TABLE `secretario` (
  `nome` varchar(120) NOT NULL,
  `matricula_secretario` int(10) NOT NULL,
  `bimestre` enum('1°','2°','3°','4°') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `secretario`
--

INSERT INTO `secretario` (`nome`, `matricula_secretario`, `bimestre`) VALUES
('Pedro Uchoa de Abreu', 5072851, '2°');

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_avisos`
--

CREATE TABLE `solicitacao_avisos` (
  `id` int(11) NOT NULL,
  `aviso` text NOT NULL,
  `matricula_professor` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_ocorrencias`
--

CREATE TABLE `solicitacao_ocorrencias` (
  `id` int(11) NOT NULL,
  `ocorrencia` text NOT NULL,
  `matricula_professor` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vice_lider`
--

CREATE TABLE `vice_lider` (
  `nome` varchar(120) NOT NULL,
  `matricula_vice_lider` int(10) NOT NULL,
  `bimestre` enum('1°','2°','3°','4°') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vice_lider`
--

INSERT INTO `vice_lider` (`nome`, `matricula_vice_lider`, `bimestre`) VALUES
('Rayssa Bezerra Vaz ', 5081883, '2°');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
