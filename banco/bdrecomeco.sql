-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/09/2025 às 12:36
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
-- Estrutura para tabela `tbevento`
--

CREATE TABLE `tbevento` (
  `idEvento` int(11) NOT NULL,
  `nomeEvento` varchar(50) NOT NULL,
  `descricaoEvento` varchar(255) NOT NULL,
  `modificadorEvento` varchar(120) NOT NULL,
  `dificuldadeEvento` varchar(10) NOT NULL,
  `impactoEvento` varchar(50) NOT NULL,
  `casaEvento` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbevento`
--

INSERT INTO `tbevento` (`idEvento`, `nomeEvento`, `descricaoEvento`, `modificadorEvento`, `dificuldadeEvento`, `impactoEvento`, `casaEvento`) VALUES
(1, 'Visita técnica', 'Sua sala saiu para um evento onde vocês vão aprender sobre coias diferentes do habitual', 'anda mais 2 casas', 'facil', 'bom', 2),
(2, 'Promoção de materiais escolares', 'Você acabou se deparando com uma promoção de materiais escolares em um apapelaria e conseguiu comprar coisas novas para te ajudar a estudar!', 'avance 2 casas', 'facil', 'bom', 2),
(3, 'Ajuda do chefe', 'Seu chefe pagou seu almoço hoje e você não terá que gastar o pouco que você tem.', 'Avance 2 casas.', 'fácil', 'bom', 2),
(4, 'Carona', 'Seu colega de trabalho/escola te deu uma carona hoje, e você não terá que gastar dinheiro com condução.', 'Avance 2 casas.', 'fácil', 'bom', 2),
(5, 'Aniversário', 'Hoje é seu aniversário e você recebeu uma mini festa surpresa (bolo e salgados) de alguns amigos seus.', 'Avance 2 casas.', 'fácil', 'bom', 2),
(6, 'Atendimento rápido', 'Você estava no médico e por sorte seu atendimento foi rápido.', 'Avance 2 casas.', 'fácil', 'bom', 2),
(7, 'Materiais', 'Seu vizinho ia jogar fora os materiais dele do ano passado, e você como estava precisando, pediu a ele, e ele te deu.', 'Avance 2 casas.', 'fácil', 'bom', 2),
(8, 'Bolsa Família/Pé de Meia', 'Você sacou o seu Bolsa Família/Pé de Meia.', 'Avance 4 casas.', 'médio', 'bom', 4),
(9, 'Cesta básica', 'Você recebeu uma cesta básica de um familiar seu.', 'Avance 4 casas.', 'médio', 'bom', 4),
(10, 'Aumento', 'Seu chefe te deu um aumento de 20% no salário.', 'Avance 4 casas.', 'médio', 'bom', 4),
(11, 'Dinheiro na rua', 'Você achou uma nota de 100 reais na rua do centro.', 'Avance 4 casas.', 'médio', 'bom', 4),
(12, 'Comoção', 'Um amigo seu ficou comovido ao ver você sofrendo preconceitos e resolveu te ajudar a passar por isso.', 'Avance 4 casas.', 'médio', 'bom', 4),
(13, 'Promoção', 'O seu chefe te deu uma boa promoção, com um bom aumento incluído.', 'Avance 6 casas.', 'difícil', 'bom', 6),
(14, 'Roupas novas', 'Ganhou roupas novas em um bazar social e não precisará gastar com isso por um bom tempo.', 'Avance 6 casas.', 'difícil', 'bom', 6),
(15, 'Celular novo', 'Seu melhor amigo estava pra trocar de celular e te deu o antigo dele.', 'Avance 6 casas.', 'difícil', 'bom', 6),
(16, 'Quitação de dívidas', 'Um familiar seu quitou boa parte de suas dívidas como forma de apoio e agradecimento.', 'Avance 6 casas.', 'difícil', 'bom', 6),
(17, 'Bicicleta', 'Você ganhou uma bicicleta de presente de um amigo seu, e agora pode se locomover sem gastar com condução.', 'Avance 6 casas.', 'difícil', 'bom', 6),
(18, 'Emprego', 'Conseguiu um emprego em uma cidade de classe alta em outra cidade com moradia e alimentação incluídos.', 'Avance 8 casas.', 'extremo', 'bom', 8),
(19, 'Ganhou um processo', 'Ganhou um processo por danos morais e recebeu uma indenização muito alta.', 'Avance 8 casas.', 'extremo', 'bom', 8),
(20, 'Estudo Completo', 'No dia do seu aniversário, um familiar decidiu pagar todos os seus estudos até o fim da faculdade.', 'Avance 8 casas.', 'extremo', 'bom', 8),
(21, 'Casa popular', 'Foi sorteado para receber uma casa popular mobiliada, pronta para morar.', 'Avance 8 casas.', 'extremo', 'bom', 8),
(22, 'Ganhou na loteria', 'Você ganhou na loteria!!!', 'Avance 8 casas.', 'extremo', 'bom', 8),
(23, 'Cachorro fugiu', 'Seu cachorro fugiu e você precisou procurar.', 'Volte 2 casas.', 'fácil', 'ruim', -2),
(24, 'Perdeu o ônibus', 'Não pegou o ônibus por que ficou sem dinheiro pra passagem.', 'Volte 2 casas.', 'fácil', 'ruim', -2),
(25, 'Perdeu as chaves', 'Perdeu as chaves pois saiu com pressa.', 'Volte 2 casas.', 'fácil', 'ruim', -2),
(26, 'Ficou doente', 'Ficou doente pois não seguiu as normas de higiene básica.', 'Volte 2 casas.', 'fácil', 'ruim', -2),
(27, 'Falta de energia', 'O seu despertador não tocou pois a energia da sua casa acabou e o celular descarregou.', 'Volte 2 casas.', 'fácil', 'ruim', -2),
(28, 'Trabalho da escola', 'Você teve que voltar pra casa e terminar um trabalho da escola, faltando ao trabalho.', 'Volte 4 casas.', 'médio', 'ruim', -4),
(29, 'Recusado no emprego', 'Você foi recusado em uma entrevista de emprego.', 'Volte 4 casas.', 'médio', 'ruim', -4),
(30, 'Ameaças na escola', 'Recebeu ameaças na escola e precisou faltar alguns dias.', 'Volte 4 casas.', 'médio', 'ruim', -4),
(31, 'Ficou endividado', 'Você ficou endividado e agora precisa juntar dinheiro.', 'Volte 4 casas.', 'médio', 'ruim', -4),
(32, 'Empréstimo não devolvido', 'Você emprestou dinheiro e não recebeu de volta.', 'Volte 4 casas.', 'médio', 'ruim', -4),
(33, 'Celular quebrou', 'Você esbarrou em alguém, o celular caiu no chão e quebrou.', 'Volte 6 casas.', 'difícil', 'ruim', -6),
(34, 'Recuperação', 'Você reprovou em uma matéria e ficou de recuperação.', 'Volte 6 casas.', 'difícil', 'ruim', -6),
(35, 'Atraso de dívida', 'Você atrasou a conta de luz e ficou alguns dias sem energia.', 'Volte 6 casas.', 'difícil', 'ruim', -6),
(36, 'Hora extra', 'Teve que fazer hora extra no trabalho e perdeu a prova na escola.', 'Volte 6 casas.', 'difícil', 'ruim', -6),
(37, 'Carteira roubada', 'Sua carteira foi roubada no ônibus com documentos e dinheiro.', 'Volte 6 casas.', 'difícil', 'ruim', -6),
(38, 'Demissão', 'Seu chefe te demitiu injustamente por causa de um problema causado por outro funcionário.', 'Volte 8 casas.', 'extremo', 'ruim', -8),
(39, 'Incêndio', 'Houve um incêndio na sua casa por causa da fiação elétrica antiga.', 'Volte 8 casas.', 'extremo', 'ruim', -8),
(40, 'Acidente', 'Você sofreu um acidente gravíssimo no trabalho e foi hospitalizado.', 'Volte 8 casas.', 'extremo', 'ruim', -8),
(41, 'Preconceito Forte', 'Enquanto andava na rua, você sofreu preconceito pesado, com danos físicos e mentais.', 'Volte 8 casas.', 'extremo', 'ruim', -8),
(42, 'Enchente', 'Sua casa em área de risco foi alagada e você perdeu a maioria dos seus pertences.', 'Volte 8 casas.', 'extremo', 'ruim', -8),
(43, 'Despertador não tocou', 'O seu despertador acabou não tocando e você se atrasou para a aula', 'Voltar 2 casas', 'facil', 'ruim', -2),
(44, 'Falta de luz', 'Você esava no meio de uma sessão de estudos e a luz caiu completamente!', 'volte 2 casas', 'fácil', 'ruim', -2);

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
(1, 1, 'Lembra de um atalho', 'Por viver muito no mesmo lugar você sabe de alguns atalhos.', 2, 'bom'),                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      