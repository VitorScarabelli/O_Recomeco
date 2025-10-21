-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/10/2025 às 20:30
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
-- Banco de dados: `bdrecomeco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbconfiguracaopartida`
--

CREATE TABLE `tbconfiguracaopartida` (
  `idConfiguracao` int(11) NOT NULL,
  `nomeConfiguracao` varchar(100) NOT NULL,
  `personagens` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`personagens`)),
  `eventosPersonagem` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`eventosPersonagem`)),
  `temasSelecionados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`temasSelecionados`)),
  `eventosSelecionados` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`eventosSelecionados`)),
  `eventosCasas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`eventosCasas`)),
  `dataCriacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `dataModificacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` tinyint(1) DEFAULT 1,
  `codigoPartida` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbconfiguracaopartida`
--

INSERT INTO `tbconfiguracaopartida` (`idConfiguracao`, `nomeConfiguracao`, `personagens`, `eventosPersonagem`, `temasSelecionados`, `eventosSelecionados`, `eventosCasas`, `dataCriacao`, `dataModificacao`, `ativo`, `codigoPartida`) VALUES
(5, 'Partida Oficial com 20 Eventos e 4 personagens', '[{\"id\":\"1\",\"nome\":\"IDOSO\",\"emoji\":\"\\ud83d\\udc74\"},{\"id\":\"3\",\"nome\":\"MULHER NEGRA\",\"emoji\":\"\\ud83d\\udc69\\ud83c\\udffd\\u200d\\ud83e\\uddb1\"},{\"id\":\"4\",\"nome\":\"RETIRANTE\",\"emoji\":\"\\ud83e\\uddf3\"},{\"id\":\"5\",\"nome\":\"MULHER TRANS\",\"emoji\":\"\\ud83c\\udf08\"}]', '[{\"id\":1,\"personagem\":\"1\"},{\"id\":2,\"personagem\":\"1\"},{\"id\":3,\"personagem\":\"1\"},{\"id\":4,\"personagem\":\"1\"},{\"id\":9,\"personagem\":\"3\"},{\"id\":10,\"personagem\":\"3\"},{\"id\":11,\"personagem\":\"3\"},{\"id\":12,\"personagem\":\"3\"},{\"id\":13,\"personagem\":\"4\"},{\"id\":14,\"personagem\":\"4\"},{\"id\":15,\"personagem\":\"4\"},{\"id\":16,\"personagem\":\"4\"},{\"id\":17,\"personagem\":\"5\"},{\"id\":18,\"personagem\":\"5\"},{\"id\":19,\"personagem\":\"5\"},{\"id\":20,\"personagem\":\"5\"}]', '[]', '[\"1\",\"2\",\"40\",\"3\",\"30\",\"35\",\"6\",\"5\",\"10\",\"17\",\"8\",\"37\",\"4\",\"23\",\"21\",\"15\",\"33\",\"18\",\"11\",\"43\"]', '[{\"id\":\"1\",\"casa\":1,\"tipo\":\"geral\"},{\"id\":\"2\",\"casa\":18,\"tipo\":\"geral\"},{\"id\":\"40\",\"casa\":15,\"tipo\":\"geral\"},{\"id\":\"3\",\"casa\":40,\"tipo\":\"geral\"},{\"id\":\"30\",\"casa\":17,\"tipo\":\"geral\"},{\"id\":\"35\",\"casa\":13,\"tipo\":\"geral\"},{\"id\":\"6\",\"casa\":23,\"tipo\":\"geral\"},{\"id\":\"5\",\"casa\":3,\"tipo\":\"geral\"},{\"id\":\"10\",\"casa\":11,\"tipo\":\"geral\"},{\"id\":\"17\",\"casa\":24,\"tipo\":\"geral\"},{\"id\":\"8\",\"casa\":35,\"tipo\":\"geral\"},{\"id\":\"37\",\"casa\":2,\"tipo\":\"geral\"},{\"id\":\"4\",\"casa\":8,\"tipo\":\"geral\"},{\"id\":\"23\",\"casa\":26,\"tipo\":\"geral\"},{\"id\":\"21\",\"casa\":12,\"tipo\":\"geral\"},{\"id\":\"15\",\"casa\":34,\"tipo\":\"geral\"},{\"id\":\"33\",\"casa\":14,\"tipo\":\"geral\"},{\"id\":\"18\",\"casa\":10,\"tipo\":\"geral\"},{\"id\":\"11\",\"casa\":21,\"tipo\":\"geral\"},{\"id\":\"43\",\"casa\":5,\"tipo\":\"geral\"},{\"id\":1,\"casa\":20,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":2,\"casa\":16,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":3,\"casa\":36,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":4,\"casa\":6,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":9,\"casa\":28,\"tipo\":\"personagem\",\"personagem\":\"3\"},{\"id\":10,\"casa\":9,\"tipo\":\"personagem\",\"personagem\":\"3\"},{\"id\":11,\"casa\":38,\"tipo\":\"personagem\",\"personagem\":\"3\"},{\"id\":12,\"casa\":33,\"tipo\":\"personagem\",\"personagem\":\"3\"},{\"id\":13,\"casa\":32,\"tipo\":\"personagem\",\"personagem\":\"4\"},{\"id\":14,\"casa\":37,\"tipo\":\"personagem\",\"personagem\":\"4\"},{\"id\":15,\"casa\":25,\"tipo\":\"personagem\",\"personagem\":\"4\"},{\"id\":16,\"casa\":27,\"tipo\":\"personagem\",\"personagem\":\"4\"},{\"id\":17,\"casa\":19,\"tipo\":\"personagem\",\"personagem\":\"5\"},{\"id\":18,\"casa\":22,\"tipo\":\"personagem\",\"personagem\":\"5\"},{\"id\":19,\"casa\":30,\"tipo\":\"personagem\",\"personagem\":\"5\"},{\"id\":20,\"casa\":31,\"tipo\":\"personagem\",\"personagem\":\"5\"}]', '2025-10-13 15:39:58', '2025-10-13 18:10:04', 1, 'K3F497'),
(19, 'Partida com eventos bons', '[{\"id\":\"1\",\"nome\":\"IDOSO\",\"emoji\":\"\\ud83d\\udc74\"},{\"id\":\"2\",\"nome\":\"DEFICIENTE VISUAL\",\"emoji\":\"\\ud83d\\udc68\\u200d\\ud83e\\uddaf\"}]', '[{\"id\":1,\"personagem\":\"1\"},{\"id\":2,\"personagem\":\"1\"},{\"id\":3,\"personagem\":\"1\"},{\"id\":4,\"personagem\":\"1\"},{\"id\":5,\"personagem\":\"2\"},{\"id\":6,\"personagem\":\"2\"},{\"id\":7,\"personagem\":\"2\"},{\"id\":8,\"personagem\":\"2\"}]', '[]', '[\"3\",\"6\",\"8\",\"10\",\"4\",\"17\",\"5\",\"15\",\"21\",\"12\",\"18\",\"19\",\"22\",\"13\",\"7\",\"2\",\"16\",\"14\",\"1\",\"9\"]', '[{\"id\":\"3\",\"casa\":19,\"tipo\":\"geral\"},{\"id\":\"6\",\"casa\":28,\"tipo\":\"geral\"},{\"id\":\"8\",\"casa\":13,\"tipo\":\"geral\"},{\"id\":\"10\",\"casa\":37,\"tipo\":\"geral\"},{\"id\":\"4\",\"casa\":35,\"tipo\":\"geral\"},{\"id\":\"17\",\"casa\":11,\"tipo\":\"geral\"},{\"id\":\"5\",\"casa\":18,\"tipo\":\"geral\"},{\"id\":\"15\",\"casa\":6,\"tipo\":\"geral\"},{\"id\":\"21\",\"casa\":21,\"tipo\":\"geral\"},{\"id\":\"12\",\"casa\":26,\"tipo\":\"geral\"},{\"id\":\"18\",\"casa\":34,\"tipo\":\"geral\"},{\"id\":\"19\",\"casa\":12,\"tipo\":\"geral\"},{\"id\":\"22\",\"casa\":24,\"tipo\":\"geral\"},{\"id\":\"13\",\"casa\":38,\"tipo\":\"geral\"},{\"id\":\"7\",\"casa\":30,\"tipo\":\"geral\"},{\"id\":\"2\",\"casa\":29,\"tipo\":\"geral\"},{\"id\":\"16\",\"casa\":17,\"tipo\":\"geral\"},{\"id\":\"14\",\"casa\":1,\"tipo\":\"geral\"},{\"id\":\"1\",\"casa\":23,\"tipo\":\"geral\"},{\"id\":\"9\",\"casa\":10,\"tipo\":\"geral\"},{\"id\":1,\"casa\":2,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":2,\"casa\":7,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":3,\"casa\":20,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":4,\"casa\":33,\"tipo\":\"personagem\",\"personagem\":\"1\"},{\"id\":5,\"casa\":4,\"tipo\":\"personagem\",\"personagem\":\"2\"},{\"id\":6,\"casa\":32,\"tipo\":\"personagem\",\"personagem\":\"2\"},{\"id\":7,\"casa\":15,\"tipo\":\"personagem\",\"personagem\":\"2\"},{\"id\":8,\"casa\":9,\"tipo\":\"personagem\",\"personagem\":\"2\"}]', '2025-10-13 18:22:08', '2025-10-13 18:22:08', 1, '5BQ97G');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbevento`
--

CREATE TABLE `tbevento` (
  `idEvento` int(11) NOT NULL,
  `nomeEvento` varchar(50) NOT NULL,
  `descricaoEvento` varchar(255) NOT NULL,
  `modificadorEvento` varchar(120) NOT NULL,
  `dificuldadeEvento` varchar(10) NOT NULL,
  `temaAula` varchar(50) DEFAULT NULL,
  `impactoEvento` varchar(50) NOT NULL,
  `casaEvento` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbevento`
--

INSERT INTO `tbevento` (`idEvento`, `nomeEvento`, `descricaoEvento`, `modificadorEvento`, `dificuldadeEvento`, `temaAula`, `impactoEvento`, `casaEvento`) VALUES
(1, 'VISITA TÉCNICA', 'SUA TURMA PARTICIPOU DE UMA VISITA TÉCNICA PARA APRENDER COISAS DIFERENTES DO HABITUAL.', 'AVANCE 2 CASAS.', 'FÁCIL', 'EDUCAÇÃO', 'BOM', 2),
(2, 'PROMOÇÃO DE MATERIAIS ESCOLARES', 'VOCÊ ENCONTROU UMA PROMOÇÃO EM UMA PAPELARIA E CONSEGUIU COMPRAR NOVOS MATERIAIS PARA ESTUDAR.', 'AVANCE 2 CASAS.', 'FÁCIL', 'EDUCAÇÃO', 'BOM', 2),
(3, 'AJUDA DO CHEFE', 'SEU CHEFE PAGOU SEU ALMOÇO HOJE, E VOCÊ NÃO PRECISOU GASTAR O SEU DINHEIRO.', 'AVANCE 2 CASAS.', 'FÁCIL', 'EMPREGO E TRABALHO', 'BOM', 2),
(4, 'CARONA', 'SEU COLEGA TE DEU UMA CARONA, ECONOMIZANDO GASTOS COM CONDUÇÃO.', 'AVANCE 2 CASAS.', 'FÁCIL', 'EDUCAÇÃO', 'BOM', 2),
(5, 'ANIVERSÁRIO', 'HOJE É SEU ANIVERSÁRIO, E VOCÊ GANHOU UMA FESTINHA SURPRESA DE AMIGOS.', 'AVANCE 2 CASAS.', 'FÁCIL', 'SOCIAL', 'BOM', 2),
(6, 'ATENDIMENTO RÁPIDO', 'VOCÊ FOI AO MÉDICO E FOI ATENDIDO RAPIDAMENTE.', 'AVANCE 2 CASAS.', 'FÁCIL', 'SAÚDE', 'BOM', 2),
(7, 'MATERIAIS', 'SEU VIZINHO IA JOGAR FORA MATERIAIS ESCOLARES, E VOCÊ CONSEGUIU PEGAR PARA USAR.', 'AVANCE 2 CASAS.', 'FÁCIL', 'EDUCAÇÃO', 'BOM', 2),
(8, 'BOLSA FAMÍLIA / PÉ-DE-MEIA', 'VOCÊ SACOU SEU BENEFÍCIO DO BOLSA FAMÍLIA OU PÉ-DE-MEIA.', 'AVANCE 4 CASAS.', 'MÉDIO', 'BENEFÍCIO', 'BOM', 4),
(9, 'CESTA BÁSICA', 'VOCÊ RECEBEU UMA CESTA BÁSICA DE UM FAMILIAR.', 'AVANCE 4 CASAS.', 'MÉDIO', 'BENEFÍCIO', 'BOM', 4),
(10, 'AUMENTO', 'SEU CHEFE TE DEU UM AUMENTO DE 20% NO SALÁRIO.', 'AVANCE 4 CASAS.', 'MÉDIO', 'EMPREGO E TRABALHO', 'BOM', 4),
(11, 'DINHEIRO NA RUA', 'VOCÊ ENCONTROU UMA NOTA DE 100 REAIS NA RUA.', 'AVANCE 4 CASAS.', 'MÉDIO', 'SORTE/AZAR', 'BOM', 4),
(12, 'COMOÇÃO', 'UM AMIGO SE COMOVEU COM SUA SITUAÇÃO E DECIDIU TE AJUDAR.', 'AVANCE 4 CASAS.', 'MÉDIO', 'SOCIAL', 'BOM', 4),
(13, 'PROMOÇÃO', 'VOCÊ FOI PROMOVIDO NO TRABALHO E GANHOU UM BOM AUMENTO.', 'AVANCE 6 CASAS.', 'DIFÍCIL', 'EMPREGO E TRABALHO', 'BOM', 6),
(14, 'ROUPAS NOVAS', 'VOCÊ GANHOU ROUPAS NOVAS EM UM BAZAR SOCIAL E VAI ECONOMIZAR POR UM TEMPO.', 'AVANCE 6 CASAS.', 'DIFÍCIL', 'SORTE/AZAR', 'BOM', 6),
(15, 'CELULAR NOVO', 'SEU MELHOR AMIGO TE DEU O CELULAR ANTIGO DELE.', 'AVANCE 6 CASAS.', 'DIFÍCIL', 'SOCIAL', 'BOM', 6),
(16, 'QUITAÇÃO DE DÍVIDAS', 'UM FAMILIAR TE AJUDOU A QUITAR GRANDE PARTE DAS SUAS DÍVIDAS.', 'AVANCE 6 CASAS.', 'DIFÍCIL', 'SOCIAL', 'BOM', 6),
(17, 'BICICLETA', 'VOCÊ GANHOU UMA BICICLETA E AGORA PODE ECONOMIZAR NO TRANSPORTE.', 'AVANCE 6 CASAS.', 'DIFÍCIL', 'SOCIAL', 'BOM', 6),
(18, 'EMPREGO', 'VOCÊ CONSEGUIU UM EMPREGO EM OUTRA CIDADE COM MORADIA E ALIMENTAÇÃO INCLUÍDAS.', 'AVANCE 8 CASAS.', 'EXTREMO', 'EMPREGO E TRABALHO', 'BOM', 8),
(19, 'GANHOU UM PROCESSO', 'VOCÊ GANHOU UM PROCESSO POR DANOS MORAIS E RECEBEU UMA GRANDE INDENIZAÇÃO.', 'AVANCE 8 CASAS.', 'EXTREMO', 'JURÍDICO', 'BOM', 8),
(20, 'ESTUDO COMPLETO', 'NO SEU ANIVERSÁRIO, UM FAMILIAR DECIDIU PAGAR TODOS OS SEUS ESTUDOS ATÉ A FACULDADE.', 'AVANCE 8 CASAS.', 'EXTREMO', 'SOCIAL', 'BOM', 8),
(21, 'CASA POPULAR', 'VOCÊ FOI SORTEADO PARA RECEBER UMA CASA POPULAR MOBILIADA.', 'AVANCE 8 CASAS.', 'EXTREMO', 'MORADIA', 'BOM', 8),
(22, 'GANHOU NA LOTERIA', 'VOCÊ GANHOU NA LOTERIA!!!', 'AVANCE 8 CASAS.', 'EXTREMO', 'SORTE/AZAR', 'BOM', 8),
(23, 'CACHORRO FUGIU', 'SEU CACHORRO FUGIU E VOCÊ PRECISOU PROCURÁ-LO.', 'VOLTE 2 CASAS.', 'FÁCIL', 'SORTE/AZAR', 'RUIM', -2),
(24, 'PERDEU O ÔNIBUS', 'VOCÊ FICOU SEM DINHEIRO PARA A PASSAGEM E PERDEU O ÔNIBUS.', 'VOLTE 2 CASAS.', 'FÁCIL', 'TRANSPORTE PÚBLICO', 'RUIM', -2),
(25, 'PERDEU AS CHAVES', 'VOCÊ SAIU COM PRESSA E ACABOU PERDENDO AS CHAVES.', 'VOLTE 2 CASAS.', 'FÁCIL', 'SORTE/AZAR', 'RUIM', -2),
(26, 'FICOU DOENTE', 'VOCÊ FICOU DOENTE POR NÃO SEGUIR AS REGRAS DE HIGIENE BÁSICA.', 'VOLTE 2 CASAS.', 'FÁCIL', 'SAÚDE', 'RUIM', -2),
(27, 'FALTA DE ENERGIA', 'A ENERGIA ACABOU E SEU DESPERTADOR NÃO TOCOU.', 'VOLTE 2 CASAS.', 'FÁCIL', 'MORADIA', 'RUIM', -2),
(28, 'TRABALHO DA ESCOLA', 'VOCÊ TEVE QUE FALTAR AO TRABALHO PARA TERMINAR UM TRABALHO ESCOLAR.', 'VOLTE 4 CASAS.', 'MÉDIO', 'EDUCAÇÃO', 'RUIM', -4),
(29, 'RECUSADO NO EMPREGO', 'VOCÊ FOI RECUSADO EM UMA ENTREVISTA DE EMPREGO.', 'VOLTE 4 CASAS.', 'MÉDIO', 'EMPREGO E TRABALHO', 'RUIM', -4),
(30, 'AMEAÇAS NA ESCOLA', 'VOCÊ RECEBEU AMEAÇAS NA ESCOLA E PRECISOU FALTAR ALGUNS DIAS.', 'VOLTE 4 CASAS.', 'MÉDIO', 'EDUCAÇÃO', 'RUIM', -4),
(31, 'ENDIVIDAMENTO', 'VOCÊ FICOU ENDIVIDADO E PRECISA GUARDAR DINHEIRO PARA PAGAR AS CONTAS.', 'VOLTE 4 CASAS.', 'MÉDIO', 'JURÍDICO', 'RUIM', -4),
(32, 'EMPRÉSTIMO NÃO DEVOLVIDO', 'VOCÊ EMPRESTOU DINHEIRO E NÃO RECEBEU DE VOLTA.', 'VOLTE 4 CASAS.', 'MÉDIO', 'SOCIAL', 'RUIM', -4),
(33, 'CELULAR QUEBROU', 'VOCÊ DEIXOU O CELULAR CAIR E ELE QUEBROU.', 'VOLTE 6 CASAS.', 'DIFÍCIL', 'SORTE/AZAR', 'RUIM', -6),
(34, 'RECUPERAÇÃO', 'VOCÊ REPROVOU EM UMA MATÉRIA E FICOU DE RECUPERAÇÃO.', 'VOLTE 6 CASAS.', 'DIFÍCIL', 'EDUCAÇÃO', 'RUIM', -6),
(35, 'ATRASO DE DÍVIDA', 'VOCÊ ATRASOU A CONTA DE LUZ E FICOU ALGUNS DIAS SEM ENERGIA.', 'VOLTE 6 CASAS.', 'DIFÍCIL', 'JURÍDICO', 'RUIM', -6),
(36, 'HORA EXTRA', 'VOCÊ TEVE QUE FAZER HORA EXTRA E PERDEU UMA PROVA IMPORTANTE.', 'VOLTE 6 CASAS.', 'DIFÍCIL', 'EDUCAÇÃO', 'RUIM', -6),
(37, 'CARTEIRA ROUBADA', 'SUA CARTEIRA FOI ROUBADA NO ÔNIBUS COM DINHEIRO E DOCUMENTOS.', 'VOLTE 6 CASAS.', 'DIFÍCIL', 'TRANSPORTE PÚBLICO', 'RUIM', -6),
(38, 'DEMISSÃO', 'VOCÊ FOI DEMITIDO INJUSTAMENTE POR UM ERRO DE OUTRO FUNCIONÁRIO.', 'VOLTE 8 CASAS.', 'EXTREMO', 'EMPREGO E TRABALHO', 'RUIM', -8),
(39, 'INCÊNDIO', 'UM INCÊNDIO DEVIDO À FIAÇÃO ANTIGA DESTRUIU PARTE DA SUA CASA.', 'VOLTE 8 CASAS.', 'EXTREMO', 'MORADIA', 'RUIM', -8),
(40, 'ACIDENTE', 'VOCÊ SOFREU UM GRAVE ACIDENTE NO TRABALHO E FOI HOSPITALIZADO.', 'VOLTE 8 CASAS.', 'EXTREMO', 'EMPREGO E TRABALHO', 'RUIM', -8),
(41, 'PRECONCEITO FORTE', 'VOCÊ SOFREU UM CASO GRAVE DE PRECONCEITO FÍSICO E MENTAL.', 'VOLTE 8 CASAS.', 'EXTREMO', 'SOCIAL', 'RUIM', -8),
(42, 'ENCHENTE', 'SUA CASA FOI ALAGADA E VOCÊ PERDEU GRANDE PARTE DOS SEUS PERTENCES.', 'VOLTE 8 CASAS.', 'EXTREMO', 'MORADIA', 'RUIM', -8),
(43, 'DESPERTADOR NÃO TOCOU', 'SEU DESPERTADOR NÃO TOCOU E VOCÊ SE ATRASOU PARA A AULA.', 'VOLTE 2 CASAS.', 'FÁCIL', 'SORTE/AZAR', 'RUIM', -2),
(44, 'FALTA DE LUZ', 'A LUZ ACABOU NO MEIO DOS SEUS ESTUDOS E VOCÊ NÃO CONSEGUIU TERMINAR.', 'VOLTE 2 CASAS.', 'FÁCIL', 'SORTE/AZAR', 'RUIM', -2);


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbeventopersonagem`
--

CREATE TABLE `tbeventopersonagem` (
  `idEvento` int(11) NOT NULL,
  `idPersonagem` int(11) NOT NULL,
  `nomeEvento` varchar(100) NOT NULL,
  `descricaoEvento` varchar(120) NOT NULL,
  `casas` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbeventopersonagem`
--

INSERT INTO `tbeventopersonagem` (`idEvento`, `idPersonagem`, `nomeEvento`, `descricaoEvento`, `casas`, `tipo`) VALUES
(1, 1, 'LEMBRA DE UM ATALHO', 'POR VIVER HÁ MUITO TEMPO NO MESMO LUGAR, VOCÊ CONHECE ALGUNS ATALHOS ÚTEIS.', 2, 'BOM'),
(2, 1, 'DEUS AJUDA QUEM CEDO MADRUGA', 'VOCÊ DORME CEDO E ACORDA CEDO, SEMPRE PRONTO PARA ENFRENTAR O DIA.', 2, 'BOM'),
(3, 1, 'DOR NAS COSTAS', 'VOCÊ JÁ NÃO TEM A MESMA RESISTÊNCIA FÍSICA DE ANTES, E SUAS COSTAS DOEM COM FREQUÊNCIA.', -2, 'RUIM'),
(4, 1, 'VOCÊ PERDEU SEUS ÓCULOS', 'SUA VISÃO PIOROU COM O TEMPO, E VOCÊ COSTUMA PERDER SEUS ÓCULOS COM FACILIDADE.', -2, 'RUIM'),
(5, 2, 'LOCAL COM ACESSIBILIDADE', 'SEU TATO É MUITO AGUÇADO, E VOCÊ PERCEBE DETALHES QUE OUTROS NÃO PERCEBEM.', 2, 'BOM'),
(6, 2, 'MEMÓRIA MUSCULAR', 'MESMO SEM ENXERGAR, VOCÊ TEM EXCELENTE NOÇÃO DE ESPAÇO NOS LUGARES QUE JÁ CONHECE.', 2, 'BOM'),
(7, 2, 'NA MALDADE', 'UMA PESSOA MÁ TE ENGANOU E TE LEVOU PARA UM CAMINHO ERRADO.', -2, 'RUIM'),
(8, 2, 'BENGALA QUEBRADA', 'DURANTE UMA CAMINHADA, VOCÊ ESBARROU EM ALGUÉM E SUA BENGALA QUEBROU.', -2, 'RUIM'),
(9, 3, 'EM PÉ DE IGUALDADE', 'LUTANDO PELOS SEUS DIREITOS, VOCÊ CONSEGUE UM SALÁRIO JUSTO, SEM REDUÇÕES “POR SER MULHER”.', 2, 'BOM'),
(10, 3, 'CRIA DO GUETO', 'VOCÊ CRESCEU EM UM AMBIENTE DIFÍCIL, MAS ISSO TE TORNOU MAIS FORTE.', 2, 'BOM'),
(11, 3, 'RACISMO', 'MESMO SENDO UMA PESSOA GENTIL, AINDA É JULGADA PELA SUA COR.', -2, 'RUIM'),
(12, 3, 'MACHISMO ESTRUTURAL', 'SÓ POR SER MULHER, VOCÊ NÃO É LEVADA TÃO A SÉRIO QUANTO OS HOMENS.', -2, 'RUIM'),
(13, 4, 'BOM DAS PERNAS', 'ACOSTUMADO A LONGAS CAMINHADAS, VOCÊ SUPORTA JORNADAS MAIORES SEM CANSAÇO.', 2, 'BOM'),
(14, 4, 'CARISMÁTICO', 'VOCÊ TEM FACILIDADE EM PEDIR AJUDA OU ALIMENTO AOS OUTROS QUANDO PRECISA.', 2, 'BOM'),
(15, 4, 'NOVO NA CIDADE', 'POR AINDA NÃO CONHECER MUITO BEM O LOCAL, VOCÊ SE PERDE COM FACILIDADE.', -2, 'RUIM'),
(16, 4, 'SAUDADE DE CASA', 'A SAUDADE DE CASA TE DEIXA TRISTE E AFETA SEU DESEMPENHO.', -2, 'RUIM'),
(17, 5, 'SEM MEDO', 'A CORAGEM QUE TE TROUXE ATÉ AQUI TE FAZ SUPERAR QUALQUER DESAFIO.', 2, 'BOM'),
(18, 5, 'AUTOCONFIANÇA', 'VOCÊ SABE QUEM É E TEM UMA CONFIANÇA QUE POUCOS POSSUEM.', 2, 'BOM'),
(19, 5, 'TRANSFOBIA', 'VOCÊ SOFRE PRECONCEITO AO ENTRAR EM ESPAÇOS QUE NÃO CONSIDERAM “SEU LUGAR”.', -2, 'RUIM'),
(20, 5, 'DISCRIMINAÇÃO DE GÊNERO', 'AS PESSOAS NÃO CONFIAM EM VOCÊ, O QUE DIFICULTA SUAS OPORTUNIDADES DE EMPREGO.', -2, 'RUIM'),
(21, 6, 'SABEDORIA EMPÁTICA', 'VOCÊ TEM CONHECIMENTO ESPIRITUAL E HABILIDADES SOCIAIS PARA LIDAR COM DIFERENTES PESSOAS.', 1, 'BOM'),
(22, 6, 'PROTEÇÃO DOS ORIXÁS', 'VOCÊ MANTÉM CALMA E EQUILÍBRIO MESMO EM SITUAÇÕES DE TENSÃO.', 2, 'BOM'),
(23, 6, 'INTOLERÂNCIA RELIGIOSA', 'VOCÊ ENFRENTA DISCRIMINAÇÃO RELIGIOSA EM ALGUNS AMBIENTES.', -2, 'RUIM'),
(24, 6, 'ESTEREÓTIPOS', 'ALGUMAS PESSOAS SE AFASTAM DE VOCÊ POR MEDO OU PRECONCEITO RELIGIOSO.', -2, 'RUIM');


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbpersonagem`
--

CREATE TABLE `tbpersonagem` (
  `idPersonagem` int(11) NOT NULL,
  `nomePersonagem` varchar(50) NOT NULL,
  `descricaoPersonagem` text DEFAULT NULL,
  `emojiPersonagem` varchar(10) DEFAULT NULL,
  `iconePersonagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbpersonagem`
--

INSERT INTO `tbpersonagem` (`idPersonagem`, `nomePersonagem`, `descricaoPersonagem`, `emojiPersonagem`, `iconePersonagem`) VALUES 
(1, 'IDOSO', 'UMA PESSOA SÁBIA, REPLETA DE HISTÓRIAS E APRENDIZADOS, QUE ENCARA OS DESAFIOS DO TEMPO COM SERENIDADE E FORÇA INTERIOR.', '👴', 'idosoicone.jpg'),
(2, 'DEFICIENTE VISUAL', 'VOCÊ ENXERGA O MUNDO DE UMA FORMA ÚNICA. SUA PERCEPÇÃO E SENSIBILIDADE TE GUIAM POR CAMINHOS QUE MUITOS NÃO CONSEGUEM VER.', '👨‍🦯', 'cegoicone.jpg'),
(3, 'MULHER NEGRA', 'UMA MULHER QUE TEM ORGULHO DE SUA COR E LUTA TODOS OS DIAS CONTRA O PRECONCEITO.', '👩🏽‍🦱', 'negraicone.png'),
(4, 'RETIRANTE', 'UM VIAJANTE DETERMINADO, QUE DEIXOU O LAR EM BUSCA DE NOVAS OPORTUNIDADES.', '🧑‍🌾', 'retiranteicone.png'),
(5, 'MULHER TRANS', 'UMA MULHER QUE TEVE A CORAGEM DE SER QUEM REALMENTE É E HOJE VIVE SUA VERDADE COM ORGULHO.', '👩', 'transicone.png'),
(6, 'UMBANDISTA', 'SEGUIDOR DA UMBANDA, CAMINHA COM FÉ, GUIADO PELOS ORIXÁS, ESPALHANDO EQUILÍBRIO, RESPEITO E LUZ POR ONDE PASSA.', '👳🏽‍♂️', 'umbandaicone.png');


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbusuario`
--

CREATE TABLE `tbusuario` (
  `idUsuario` int(11) NOT NULL,
  `nomeUsuario` varchar(100) NOT NULL,
  `emailUsuario` varchar(255) NOT NULL,
  `senhaUsuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbusuario`
--

INSERT INTO `tbusuario` (`idUsuario`, `nomeUsuario`, `emailUsuario`, `senhaUsuario`) VALUES
(1, 'adm', 'adm@gmail.com', '12345');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbconfiguracaopartida`
--
ALTER TABLE `tbconfiguracaopartida`
  ADD PRIMARY KEY (`idConfiguracao`),
  ADD UNIQUE KEY `codigoPartida` (`codigoPartida`);

--
-- Índices de tabela `tbevento`
--
ALTER TABLE `tbevento`
  ADD PRIMARY KEY (`idEvento`);

--
-- Índices de tabela `tbeventopersonagem`
--
ALTER TABLE `tbeventopersonagem`
  ADD PRIMARY KEY (`idEvento`);

--
-- Índices de tabela `tbpersonagem`
--
ALTER TABLE `tbpersonagem`
  ADD PRIMARY KEY (`idPersonagem`);

--
-- Índices de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbconfiguracaopartida`
--
ALTER TABLE `tbconfiguracaopartida`
  MODIFY `idConfiguracao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `tbevento`
--
ALTER TABLE `tbevento`
  MODIFY `idEvento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `tbeventopersonagem`
--
ALTER TABLE `tbeventopersonagem`
  MODIFY `idEvento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `tbpersonagem`
--
ALTER TABLE `tbpersonagem`
  MODIFY `idPersonagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
