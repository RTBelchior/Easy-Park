--
-- Base de dados: `easypark`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cartoes`
--

CREATE TABLE `cartoes` (
  `id_cartao` int(11) NOT NULL,
  `numero_cartao` varchar(11) NOT NULL,
  `ativo_cartao` tinyint(1) NOT NULL,
  `data_registo_cartao` date NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `id_tipo_cartao` int(11) NOT NULL
);

--
-- Extraindo dados da tabela `cartoes`
--

INSERT INTO `cartoes` (`id_cartao`, `numero_cartao`, `ativo_cartao`, `data_registo_cartao`, `id_utilizador`, `id_tipo_cartao`) VALUES
(1, 'A9DB5E83', 1, '2025-11-12', 1, 1),
(2, 'EACE2F80', 1, '2025-11-13', 2, 1),
(3, 'CARD003', 0, '2025-11-14', 3, 2),
(4, 'CARD004', 1, '2025-11-15', 4, 2),
(5, 'CARD005', 1, '2025-11-15', 5, 2),
(6, 'CARD006', 1, '2025-11-15', 6, 1),
(7, 'CARD007', 1, '2025-11-15', 7, 2),
(8, 'CARD008', 1, '2025-11-15', 8, 2),
(9, 'CARD009', 1, '2025-11-15', 9, 2),
(10, 'CARD0010', 1, '2025-11-15', 10, 2),
(11, 'CARD0011', 1, '2025-11-15', 11, 2),
(12, 'CARD0012', 1, '2025-11-15', 12, 2),
(13, 'CARD0013', 1, '2025-11-15', 13, 1),
(14, 'CARD0014', 1, '2025-11-15', 14, 2),
(15, 'CARD0015', 1, '2025-11-15', 15, 2),
(16, 'CARD0016', 1, '2025-11-15', 16, 1),
(17, 'CARD0017', 1, '2025-11-15', 17, 2),
(18, 'CARD0018', 1, '2025-11-15', 18, 2),
(19, 'CARD0019', 1, '2025-11-15', 19, 2),
(20, 'CARD0020', 1, '2025-11-15', 20, 1),
(21, 'CARD0021', 1, '2025-11-15', 21, 2),
(22, 'CARD0022', 1, '2025-11-15', 22, 2),
(23, 'CARD0023', 1, '2025-11-15', 23, 1),
(24, 'CARD0024', 1, '2025-11-15', 24, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `formulario`
--

CREATE TABLE `formulario` (
  `id_form` int(11) NOT NULL,
  `avaliacao_form` enum('1','2','3','4','5') NOT NULL,
  `mensagem_form` longtext NOT NULL,
  `data_hora_form` datetime NOT NULL,
  `id_utilizador` int(11) NOT NULL
);

--
-- Extraindo dados da tabela `formulario`
--

INSERT INTO `formulario` (`id_form`, `avaliacao_form`, `mensagem_form`, `data_hora_form`, `id_utilizador`) VALUES
(1, '5', 'Gostei muito', '2025-11-12 19:03:25', 1),
(2, '4', 'Muito Bom', '2025-11-13 23:14:10', 2),
(3, '4', 'Gostei', '2025-11-14 10:05:30', 3),
(4, '5', 'Excelente funcionamento', '2025-11-15 13:15:14', 4),
(5, '3', 'Bom Funcionamento', '2025-09-10 13:15:14', 7),
(6, '2', 'Não Gostei', '2025-09-14 13:15:14', 10),
(7, '5', 'Funciona tudo corretamente', '2025-09-20 13:15:14', 18),
(8, '4', 'So falta ampliar para mais escolas', '2025-09-22 13:15:14', 15),
(9, '5', 'Excelente', '2025-09-22 13:15:14', 14),
(10, '1', 'Deixou a desejar', '2025-09-22 13:15:14', 22);

-- --------------------------------------------------------

--
-- Estrutura da tabela `acesso`
--

CREATE TABLE `acesso` (
  `id_acesso` int(11) NOT NULL,
  `tipo_acesso` enum('entrada','saida') NOT NULL,
  `data_hora_acesso` datetime NOT NULL,
  `id_cartao` int(11) NOT NULL,
  `id_parque` int(11) NOT NULL
);

--
-- Extraindo dados da tabela `acesso`
--

INSERT INTO `acesso` (`id_acesso`, `tipo_acesso`, `data_hora_acesso`, `id_cartao`, `id_parque`) VALUES
(1, 'entrada', '2025-10-02 08:05:00', 1, 1),
(2, 'saida',   '2025-10-02 17:10:00', 1, 1),
(3, 'entrada', '2025-10-04 08:20:00', 1, 2),
(4, 'saida',   '2025-10-04 17:30:00', 1, 2),
(5, 'entrada', '2025-10-06 08:10:00', 1, 3),
(6, 'saida',   '2025-10-06 17:05:00', 1, 3),
(7, 'entrada', '2025-10-08 08:45:00', 1, 1),
(8, 'saida',   '2025-10-08 18:00:00', 1, 1),
(9, 'entrada', '2025-10-10 09:00:00', 1, 2),
(10, 'saida',  '2025-10-10 17:45:00', 1, 2),
(11, 'entrada', '2025-10-13 08:15:00', 1, 3),
(12, 'saida',  '2025-10-13 17:20:00', 1, 3),
(13, 'entrada', '2025-10-15 08:30:00', 1, 1),
(14, 'saida',  '2025-10-15 17:55:00', 1, 1),
(15, 'entrada', '2025-10-17 08:10:00', 1, 2),
(16, 'saida',  '2025-10-17 18:10:00', 1, 2),
(17, 'entrada', '2025-10-20 08:25:00', 1, 3),
(18, 'saida',  '2025-10-20 17:35:00', 1, 3),
(19, 'entrada', '2025-10-22 08:40:00', 1, 1),
(20, 'saida',  '2025-10-22 17:50:00', 1, 1),
(21, 'entrada', '2025-10-25 08:50:00', 1, 2),
(22, 'saida',  '2025-10-25 18:00:00', 1, 2),
(23, 'entrada', '2025-10-28 09:10:00', 1, 3),
(24, 'saida',  '2025-10-28 18:15:00', 1, 3),

(25, 'entrada', '2025-10-01 08:00:00', 2, 1),
(26, 'saida',  '2025-10-01 17:20:00', 2, 1),
(27, 'entrada', '2025-10-03 08:15:00', 2, 2),
(28, 'saida',  '2025-10-03 17:35:00', 2, 2),
(29, 'entrada', '2025-10-06 08:05:00', 2, 3),
(30, 'saida',  '2025-10-06 17:10:00', 2, 3),
(31, 'entrada', '2025-10-08 08:40:00', 2, 1),
(32, 'saida',  '2025-10-08 17:50:00', 2, 1),
(33, 'entrada', '2025-10-11 08:25:00', 2, 2),
(34, 'saida',  '2025-10-11 17:45:00', 2, 2),
(35, 'entrada', '2025-10-14 08:35:00', 2, 3),
(36, 'saida',  '2025-10-14 18:05:00', 2, 3),
(37, 'entrada', '2025-10-17 08:10:00', 2, 1),
(38, 'saida',  '2025-10-17 17:30:00', 2, 1),
(39, 'entrada', '2025-10-20 08:20:00', 2, 2),
(40, 'saida',  '2025-10-20 17:40:00', 2, 2),
(41, 'entrada', '2025-10-23 08:05:00', 2, 3),
(42, 'saida',  '2025-10-23 17:25:00', 2, 3),
(43, 'entrada', '2025-10-27 08:30:00', 2, 1),
(44, 'saida',  '2025-10-27 17:50:00', 2, 1),
(45, 'entrada', '2025-10-30 08:45:00', 2, 2),
(46, 'saida',  '2025-10-30 18:00:00', 2, 2),
(47, 'entrada', '2025-11-03 08:15:00', 2, 3),
(48, 'saida',  '2025-11-03 17:35:00', 2, 3),
(49, 'entrada', '2025-11-10 08:10:00', 2, 1),
(50, 'saida',  '2025-11-10 17:30:00', 2, 1),

(51, 'entrada', '2025-10-04 08:05:00', 3, 1),
(52, 'saida',  '2025-10-04 17:10:00', 3, 1),
(53, 'entrada', '2025-10-07 08:20:00', 3, 2),
(54, 'saida',  '2025-10-07 17:25:00', 3, 2),
(55, 'entrada', '2025-10-09 08:15:00', 3, 3),
(56, 'saida',  '2025-10-09 17:40:00', 3, 3),
(57, 'entrada', '2025-10-12 08:30:00', 3, 1),
(58, 'saida',  '2025-10-12 17:55:00', 3, 1),
(59, 'entrada', '2025-10-16 08:10:00', 3, 2),
(60, 'saida',  '2025-10-16 17:20:00', 3, 2),
(61, 'entrada', '2025-10-19 08:45:00', 3, 3),
(62, 'saida',  '2025-10-19 18:05:00', 3, 3),
(63, 'entrada', '2025-10-24 08:25:00', 3, 1),
(64, 'saida',  '2025-10-24 17:35:00', 3, 1),
(65, 'entrada', '2025-10-29 08:40:00', 3, 2),
(66, 'saida',  '2025-10-29 17:50:00', 3, 2),
(67, 'entrada', '2025-11-05 08:50:00', 3, 3),
(68, 'saida',  '2025-11-05 18:10:00', 3, 3),
(69, 'entrada', '2025-11-12 08:35:00', 3, 1),
(70, 'saida',  '2025-11-12 17:55:00', 3, 1),

(71, 'entrada', '2025-10-02 08:10:00', 4, 1),
(72, 'saida',  '2025-10-02 17:25:00', 4, 1),
(73, 'entrada', '2025-10-05 08:20:00', 4, 2),
(74, 'saida',  '2025-10-05 17:40:00', 4, 2),
(75, 'entrada', '2025-10-09 08:05:00', 4, 3),
(76, 'saida',  '2025-10-09 17:15:00', 4, 3),
(77, 'entrada', '2025-10-11 08:30:00', 4, 1),
(78, 'saida',  '2025-10-11 17:50:00', 4, 1),
(79, 'entrada', '2025-10-14 08:15:00', 4, 2),
(80, 'saida',  '2025-10-14 17:35:00', 4, 2),
(81, 'entrada', '2025-10-18 08:40:00', 4, 3),
(82, 'saida',  '2025-10-18 18:00:00', 4, 3),
(83, 'entrada', '2025-10-21 08:25:00', 4, 1),
(84, 'saida',  '2025-10-21 17:45:00', 4, 1),
(85, 'entrada', '2025-10-23 08:35:00', 4, 2),
(86, 'saida',  '2025-10-23 17:55:00', 4, 2),
(87, 'entrada', '2025-10-26 08:05:00', 4, 3),
(88, 'saida',  '2025-10-26 17:30:00', 4, 3),
(89, 'entrada', '2025-10-31 08:50:00', 4, 1),
(90, 'saida',  '2025-10-31 18:05:00', 4, 1),
(91, 'entrada', '2025-11-07 08:20:00', 4, 2),
(92, 'saida',  '2025-11-07 17:40:00', 4, 2),
(93, 'entrada', '2025-11-14 08:15:00', 4, 3),
(94, 'saida',  '2025-11-14 17:35:00', 4, 3),

(95, 'entrada', '2025-10-01 08:10:00', 5, 2),
(96, 'saida',  '2025-10-01 17:20:00', 5, 2),
(97, 'entrada', '2025-10-10 08:25:00', 5, 3),
(98, 'saida',  '2025-10-10 17:45:00', 5, 3),
(99, 'entrada', '2025-11-20 08:15:00', 5, 1),

(100, 'entrada', '2025-10-02 08:05:00', 6, 1),
(101, 'saida',  '2025-10-02 17:05:00', 6, 1),
(102, 'entrada', '2025-10-12 08:20:00', 6, 2),
(103, 'saida',  '2025-10-12 17:40:00', 6, 2),
(104, 'entrada', '2025-11-18 08:10:00', 6, 1),

(105, 'entrada', '2025-10-03 08:30:00', 7, 2),
(106, 'saida',  '2025-10-03 17:50:00', 7, 2),
(107, 'entrada', '2025-10-14 08:15:00', 7, 3),
(108, 'saida',  '2025-10-14 17:30:00', 7, 3),
(109, 'entrada', '2025-11-22 08:25:00', 7, 1),

(110, 'entrada', '2025-10-04 08:10:00', 8, 3),
(111, 'saida',  '2025-10-04 17:20:00', 8, 3),
(112, 'entrada', '2025-10-15 08:40:00', 8, 1),
(113, 'saida',  '2025-10-15 17:55:00', 8, 1),
(114, 'entrada', '2025-11-09 08:05:00', 8, 1),

(115, 'entrada', '2025-10-05 08:20:00', 9, 1),
(116, 'saida',  '2025-10-05 17:45:00', 9, 1),
(117, 'entrada', '2025-10-16 08:25:00', 9, 2),
(118, 'saida',  '2025-10-16 17:35:00', 9, 2),
(119, 'entrada', '2025-11-11 08:30:00', 9, 1),

(120, 'entrada', '2025-10-06 08:05:00', 10, 2),
(121, 'saida',  '2025-10-06 17:10:00', 10, 2),
(122, 'entrada', '2025-10-18 08:15:00', 10, 3),
(123, 'saida',  '2025-10-18 17:30:00', 10, 3),
(124, 'entrada', '2025-11-05 08:20:00', 10, 1),

(125, 'entrada', '2025-10-07 08:25:00', 11, 3),
(126, 'saida',  '2025-10-07 17:40:00', 11, 3),
(127, 'entrada', '2025-10-20 08:35:00', 11, 1),
(128, 'saida',  '2025-10-20 17:55:00', 11, 1),
(129, 'entrada', '2025-11-07 08:10:00', 11, 1),

(130, 'entrada', '2025-10-08 08:10:00', 12, 1),
(131, 'saida',  '2025-10-08 17:15:00', 12, 1),
(132, 'entrada', '2025-10-21 08:20:00', 12, 2),
(133, 'saida',  '2025-10-21 17:35:00', 12, 2),
(134, 'entrada', '2025-11-12 08:30:00', 12, 1),

(135, 'entrada', '2025-10-09 08:05:00', 13, 2),
(136, 'saida',  '2025-10-09 17:25:00', 13, 2),
(137, 'entrada', '2025-10-22 08:30:00', 13, 3),
(138, 'saida',  '2025-10-22 17:50:00', 13, 3),
(139, 'entrada', '2025-11-19 08:15:00', 13, 1),

(140, 'entrada', '2025-10-10 08:15:00', 14, 3),
(141, 'saida',  '2025-10-10 17:35:00', 14, 3),
(142, 'entrada', '2025-10-23 08:05:00', 14, 1),
(143, 'saida',  '2025-10-23 17:20:00', 14, 1),
(144, 'entrada', '2025-11-10 08:25:00', 14, 1),

(145, 'entrada', '2025-10-11 08:20:00', 15, 1),
(146, 'saida',  '2025-10-11 17:40:00', 15, 1),
(147, 'entrada', '2025-10-24 08:30:00', 15, 2),
(148, 'saida',  '2025-10-24 17:55:00', 15, 2),
(149, 'entrada', '2025-11-03 08:10:00', 15, 1),

(150, 'entrada', '2025-10-12 08:30:00', 16, 2),
(151, 'saida',  '2025-10-12 17:50:00', 16, 2),
(152, 'entrada', '2025-10-25 08:15:00', 16, 3),
(153, 'saida',  '2025-10-25 17:35:00', 16, 3),
(154, 'entrada', '2025-11-08 08:05:00', 16, 1),

(155, 'entrada', '2025-10-13 08:10:00', 17, 3),
(156, 'saida',  '2025-10-13 17:30:00', 17, 3),
(157, 'entrada', '2025-10-26 08:20:00', 17, 1),
(158, 'saida',  '2025-10-26 17:45:00', 17, 1),
(159, 'entrada', '2025-11-06 08:15:00', 17, 1),

(160, 'entrada', '2025-10-14 08:25:00', 18, 1),
(161, 'saida',  '2025-10-14 17:40:00', 18, 1),
(162, 'entrada', '2025-10-27 08:35:00', 18, 2),
(163, 'saida',  '2025-10-27 17:55:00', 18, 2),
(164, 'entrada', '2025-11-13 08:20:00', 18, 1),

(165, 'entrada', '2025-10-15 08:05:00', 19, 2),
(166, 'saida',  '2025-10-15 17:25:00', 19, 2),
(167, 'entrada', '2025-10-28 08:30:00', 19, 3),
(168, 'saida',  '2025-10-28 17:50:00', 19, 3),
(169, 'entrada', '2025-11-02 08:10:00', 19, 1),

(170, 'entrada', '2025-10-16 08:15:00', 20, 3),
(171, 'saida',  '2025-10-16 17:35:00', 20, 3),
(172, 'entrada', '2025-10-29 08:20:00', 20, 1),
(173, 'saida',  '2025-10-29 17:40:00', 20, 1),
(174, 'entrada', '2025-11-16 08:25:00', 20, 1),

(175, 'entrada', '2025-10-17 08:10:00', 21, 1),
(176, 'saida',  '2025-10-17 17:30:00', 21, 1),
(177, 'entrada', '2025-10-30 08:35:00', 21, 2),
(178, 'saida',  '2025-10-30 17:55:00', 21, 2),
(179, 'entrada', '2025-11-04 08:15:00', 21, 1),

(180, 'entrada', '2025-10-18 08:20:00', 22, 2),
(181, 'saida',  '2025-10-18 17:40:00', 22, 2),
(182, 'entrada', '2025-11-01 08:30:00', 22, 3),
(183, 'saida',  '2025-11-01 17:50:00', 22, 3),
(184, 'entrada', '2025-11-09 08:10:00', 22, 1),

(185, 'entrada', '2025-10-19 08:05:00', 23, 3),
(186, 'saida',  '2025-10-19 17:20:00', 23, 3),
(187, 'entrada', '2025-11-02 08:25:00', 23, 2),
(188, 'saida',  '2025-11-02 17:45:00', 23, 2),
(189, 'entrada', '2025-11-21 08:30:00', 23, 1),

(190, 'entrada', '2025-10-20 08:15:00', 24, 1),
(191, 'saida',  '2025-10-20 17:35:00', 24, 1),
(192, 'entrada', '2025-11-11 08:05:00', 24, 2),
(193, 'saida',  '2025-11-11 17:25:00', 24, 2),
(194, 'entrada', '2025-11-23 08:20:00', 24, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `parque`
--

CREATE TABLE `parque` (
  `id_parque` int(11) NOT NULL,
  `lotacao_maxima` int(11) NOT NULL,
  `id_universidade` int(11) NOT NULL
);

--
-- Extraindo dados da tabela `parque`
--

INSERT INTO `parque` (`id_parque`, `lotacao_maxima`, `id_universidade`) VALUES
(1, 100, 1),
(2, 100, 1),
(3, 60, 1),
(4, 1000, 2),
(5, 2000, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL,
  `nome_utilizador` varchar(100) NOT NULL,
  `numero_utilizador` int(11) NOT NULL,
  `email_utilizador` varchar(100) NOT NULL,
  `password_utilizador` varchar(255) NOT NULL,
  `ativo_utilizador` tinyint(1) DEFAULT 1,
  `id_tipo_utilizador` int(11) NOT NULL
);

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome_utilizador`, `numero_utilizador`, `email_utilizador`, `password_utilizador`, `ativo_utilizador`, `id_tipo_utilizador`) VALUES
(1, 'Rodrigo Belchior', 2025159599, '2025159599@estudantes.ips.pt', '1234', 1, 1),
(2, 'Ricardo Almeida', 2025151617, '2025131415@estudantes.ips.pt', '1234', 1, 2),
(3, 'Matheus Santana', 2025130281, '2025130281@estudantes.ips.pt', '1234', 1, 4),
(4, 'Rodrigo Firinca', 2025131577, '2025131577@estudantes.ips.pt', '1234', 1, 3),
(5, 'Fábio Feiteira', 2025125847, '2025125847@estudantes.ips.pt', '1234', 1, 2),
(6, 'Rodrigo Fonseca', 2025168899, '2025168899@estudantes.ips.pt', '1234', 1, 2),
(7, 'João Alves', 2025141152, '2025141152@estudantes.ips.pt', '1234', 1, 2),
(8, 'Rogério Pereira', 2025138195, '2025138195@estudantes.ips.pt', '1234', 1, 2),
(9, 'Riley Chilembo', 2025141253, '2025141253@estudantes.ips.pt', '1234', 1, 2),
(10, 'Tiago Duarte', 2025129178, '2025129178@estudantes.ips.pt', '1234', 1, 2),
(11, 'Fernando Ferreira', 2025123789, '2025123789@estudantes.ips.pt', '1234', 1, 2),
(12, 'Iúri Novas', 2025121875, '2025121875@estudantes.ips.pt', '1234', 1, 2),
(13, 'Luis Nunes', 2025123048, '2025123048@estudantes.ips.pt', '1234', 1, 2),
(14, 'Tiago Pardal', 202301160, '202301160@estudantes.ips.pt', '1234', 1, 2),
(15, 'Jaime Quaresma', 2025144047, '2025144047@estudantes.ips.pt', '1234', 1, 2),
(16, 'Tomás Ramos', 2025160385, '2025160385@estudantes.ips.pt', '1234', 1, 2),
(17, 'Diogo Rocha', 2025141335, '2025141335@estudantes.ips.pt', '1234', 1, 2),
(18, 'Pedro Carreiro', 2025723123, 'pedro.carreiro@estsetubal.ips.pt', '1234', 1, 4),
(19, 'Miguel Boavida', 2023839212, 'miguel.boavida@estsetubal.ips.pt', '1234', 1, 4),
(20, 'Elisabete Lopes', 2018231358, 'elisabete.lopes@estsetubal.ips.pt', '1234', 1, 4),
(21, 'Martinha Piteira', 2017123223, 'martinha.piteira@estsetubal.ips.pt', '1234', 1, 4),
(22, 'Alexandra Alenquer', 2000845938, 'alexandra.alenquer@estsetubal.ips.pt', '1234', 1, 3),
(23, 'Pedro Almeida', 2015938438, 'pedro.almeida@estsetubal.ips.pt', '1234', 1, 3),
(24, 'Cristina Alexandre', 2015264819, 'cristina.alexandre@estsetubal.ips.pt', '1234', 1, 3);


-- -----------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `tipo_utilizador`
--

CREATE TABLE `tipo_utilizador` (
  `id_tipo_utilizador` int(11) NOT NULL,
  `tipo_utilizador` varchar(100) NOT NULL
);

-- 
-- Extraindo dados da tabela `tipo_utilizador`
--

INSERT INTO `tipo_utilizador` (`id_tipo_utilizador`, `tipo_utilizador`) VALUES
(1, 'Administrador'),
(2, 'Aluno'),
(3, 'Funcionário'),
(4, 'Professor');

-- -------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `tipo_cartao`
--

CREATE TABLE `tipo_cartao` (
  `id_tipo_cartao` int(11) NOT NULL,
  `tipo_cartao` varchar(100) NOT NULL
);

-- 
-- Extraindo dados da tabela `tipo_cartao`
--

INSERT INTO `tipo_cartao` (`id_tipo_cartao`, `tipo_cartao`) VALUES
(1, 'tag'),
(2, 'cartão');


-- -------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `cartao_parque`
--

CREATE TABLE `cartao_parque` (
  `id_cartao_parque` int(11) NOT NULL,
  `id_cartao` int(11) NOT NULL,
  `id_parque` int(11) NOT NULL
);

-- 
-- Extraindo dados da tabela `cartao_parque`
--

INSERT INTO `cartao_parque` (`id_cartao_parque`, `id_cartao`, `id_parque`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 1),
(5, 3, 2),
(6, 4, 1),
(7, 5, 2),
(8, 6, 1),
(9, 7, 2),
(10, 8, 3),
(11, 9, 1),
(12, 10, 2),
(13, 11, 1),
(14, 12, 2),
(15, 13, 1),
(16, 14, 2),
(17, 15, 3),
(18, 16, 1),
(19, 17, 2),
(20, 18, 1),
(21, 19, 2),
(22, 20, 2),
(23, 21, 2),
(24, 22, 2),
(25, 23, 2),
(26, 24, 3);

-- -------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `universidade`
--

CREATE TABLE `universidade` (
  `id_universidade` int(11) NOT NULL,
  `nome_universidade` varchar(100) NOT NULL,
  `telefone_universidade` varchar(100) NOT NULL,
  `endereco_universidade` varchar(100) NOT NULL,
  `id_local` int(11) NOT NULL
);

-- 
-- Extraindo dados da tabela `universidade`
--

INSERT INTO `universidade` (`id_universidade`, `nome_universidade`, `telefone_universidade`, `endereco_universidade`,`id_local`) VALUES
(1, 'Instituto Politécnico de Setúbal', '265 548 820', 'Campus do IPS - Estefanilha, 2910-761', 1),
(2, 'Instituto Politécnico de Lisboa',  '21 710 1200', 'Estr. de Benfica 529, 1549-020', 2),
(3, 'Faculdade de Ciências e Tecnologia da Universidade Nova de Lisboa', '21 294 8300', 'Largo da Torre, 2829-516 Caparica', 1);

-- -------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `local`
--

CREATE TABLE `local` (
  `id_local` int(11) NOT NULL,
  `distrito_local` varchar(100) NOT NULL
);    

-- 
-- Extraindo dados da tabela `local`
--

INSERT INTO `local` (`id_local`, `distrito_local`) VALUES
(1, 'Setubal'),
(2, 'Lisboa'),
(3, 'Porto');

-- -------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `veiculos_utilizador`
--

CREATE TABLE `veiculos_utilizador` (
  `id_veiculos_utilizador` int(11) NOT NULL,
  `id_veiculos` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL
);

-- 
-- Extraindo dados da tabela `veiculos_utilizador`
--

INSERT INTO `veiculos_utilizador` (`id_veiculos_utilizador`, `id_veiculos`, `id_utilizador`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 2),
(4, 4, 3),
(5, 5, 4),
(6, 6, 1),
(7, 7, 5),
(8, 8, 6),
(9, 9, 7),
(10, 10, 8),
(11, 11, 9),
(12, 12, 10),
(13, 13, 11),
(14, 14, 12),
(15, 15, 13),
(16, 16, 14),
(17, 17, 15),
(18, 18, 16),
(19, 19, 17),
(20, 20, 18),
(21, 21, 19),
(22, 22, 20),
(23, 24, 21);

-- -------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id_veiculos` int(11) NOT NULL,
  `marca_veiculos` varchar(100) NOT NULL,  
  `modelo_veiculos` varchar(100) NOT NULL,  
  `matricula_veiculos` varchar(8) NOT NULL,
  `id_tipo_veiculo` int(11) NOT NULL
);

-- 
-- Extraindo dados da tabela `veiculos`
--

INSERT INTO `veiculos` (`id_veiculos`, `marca_veiculos`, `modelo_veiculos`, `matricula_veiculos`,`id_tipo_veiculo`) VALUES
(1, 'BMW', 'M3 Competition','RB-40-IM', 1),
(2, 'Mercedes', 'AMG G63','BR-25-AE', 1),
(3, 'Honda', 'Civic','PT-67-EA', 1),
(4, 'Opel', 'Corsa','BR-64-RD', 1),
(5, 'Mercedes', 'Classe A','RA-57-TV', 1),
(6, 'Yamaha', 'MT-07','TE-57-JG', 2),
(7, 'Honda', 'CBR','GD-24-SF', 2),
(8, 'Peugeot', '5008','BR-64-RD', 1),
(9, 'Peugeot', '208','BR-64-RD', 1),
(10, 'Peugeot', '508','BR-64-RD', 1),
(11, 'Peugeot', '3008','BR-64-RD', 1),
(12, 'Opel', 'Astra','BR-64-RD', 1),
(13, 'Peugeot', '2008','BR-64-RD', 1),
(14, 'Opel', 'Mokka','BR-64-RD', 1),
(15, 'Peugeot', '208','BR-64-RD', 1),
(16, 'Opel', 'Corsa','BR-64-RD', 1),
(17, 'Opel', 'mokka','BR-64-RD', 1),
(18, 'Mercedes', 'Classe s','BR-64-RD', 1),
(19, 'BMW', 'Serie 3','BR-64-RD', 1),
(20, 'BMW', 'Serie 1','BR-64-RD', 1),
(21, 'Mini', 'Couper','BR-64-RD', 1),
(22, 'Renaut', 'Megane','BR-64-RD', 1),
(23, 'Audi', 'RS6','BR-64-RD', 1),
(24, 'Audi', 'Q8','BR-64-RD', 1);

-- -------------------------------------------------------------------------------------------------------------------

--
-- Estrutura da tabela `tipo_veiculo`
--

CREATE TABLE `tipo_veiculo` (
  `id_tipo_veiculo` int(11) NOT NULL,
  `nome_tipo_veiculo` varchar(100) NOT NULL  
);

-- 
-- Extraindo dados da tabela `tipo_veiculo`
--

INSERT INTO `tipo_veiculo` (`id_tipo_veiculo`, `nome_tipo_veiculo`) VALUES
(1, 'Carro'),
(2, 'Mota');


-- -------------------------------------------------------------------------------------------------------------------

-- 
--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cartoes`
--
ALTER TABLE `cartoes`
  ADD PRIMARY KEY (`id_cartao`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `id_tipo_cartao` (`id_tipo_cartao`);

--
-- Índices para tabela `formulario`
--
ALTER TABLE `formulario`
  ADD PRIMARY KEY (`id_form`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `acesso`
--
ALTER TABLE `acesso`
  ADD PRIMARY KEY (`id_acesso`),
  ADD KEY `id_cartao` (`id_cartao`),
  ADD KEY `id_parque` (`id_parque`);

--
-- Índices para tabela `parque`
--
ALTER TABLE `parque`
  ADD PRIMARY KEY (`id_parque`),
  ADD KEY `id_universidade` (`id_universidade`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD KEY `id_tipo_utilizador` (`id_tipo_utilizador`);


--
-- Índices para tabela `tipo_utilizador`
--
ALTER TABLE `tipo_utilizador`
  ADD PRIMARY KEY (`id_tipo_utilizador`);

--
-- Índices para tabela `tipo_cartao`
--
ALTER TABLE `tipo_cartao`
  ADD PRIMARY KEY (`id_tipo_cartao`);  

--
-- Índices para tabela `cartao_parque`
--
ALTER TABLE `cartao_parque`
  ADD PRIMARY KEY (`id_cartao_parque`);  

--
-- Índices para tabela `universidade`
--
ALTER TABLE `universidade`
  ADD PRIMARY KEY (`id_universidade`),
  ADD KEY `id_local` (`id_local`);

--
-- Índices para tabela `local`
--
ALTER TABLE `local`
  ADD PRIMARY KEY (`id_local`);

--
-- Índices para tabela `veiculos_utilizador`
--
ALTER TABLE `veiculos_utilizador`
  ADD PRIMARY KEY (`id_veiculos_utilizador`),
  ADD KEY `id_veiculos` (`id_veiculos`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices para tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id_veiculos`),
  ADD KEY `id_tipo_veiculo` (`id_tipo_veiculo`);

--
-- Índices para tabela `tipo_veiculo`
--
ALTER TABLE `tipo_veiculo`
  ADD PRIMARY KEY (`id_tipo_veiculo`);


--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cartoes`
--
ALTER TABLE `cartoes`
  MODIFY `id_cartao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `formulario`
--
ALTER TABLE `formulario`
  MODIFY `id_form` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `acesso`
--
ALTER TABLE `acesso`
  MODIFY `id_acesso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT de tabela `parque`
--
ALTER TABLE `parque`
  MODIFY `id_parque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `tipo_utilizador`
--
ALTER TABLE `tipo_utilizador`
  MODIFY `id_tipo_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tipo_cartao`
--
ALTER TABLE `tipo_cartao`
  MODIFY `id_tipo_cartao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cartao_parque`
--
ALTER TABLE `cartao_parque`
  MODIFY `id_cartao_parque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `universidade`
--
ALTER TABLE `universidade`
  MODIFY `id_universidade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `local`
--
ALTER TABLE `local`
  MODIFY `id_local` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `veiculos_utilizador`
--
ALTER TABLE `veiculos_utilizador`
  MODIFY `id_veiculos_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
  
--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id_veiculos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `tipo_veiculo`
--
ALTER TABLE `tipo_veiculo`
  MODIFY `id_tipo_veiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cartoes`
--
ALTER TABLE `cartoes`
  ADD CONSTRAINT `cartoes_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`),
  ADD CONSTRAINT `cartoes_ibfk_2` FOREIGN KEY (`id_tipo_cartao`) REFERENCES `tipo_cartao` (`id_tipo_cartao`);

--
-- Limitadores para a tabela `formulario`
--
ALTER TABLE `formulario`
  ADD CONSTRAINT `formulario_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`);

--
-- Limitadores para a tabela `acesso`
--
ALTER TABLE `acesso`
  ADD CONSTRAINT `acesso_ibfk_1` FOREIGN KEY (`id_cartao`) REFERENCES `cartoes` (`id_cartao`),
  ADD CONSTRAINT `acesso_ibfk_2` FOREIGN KEY (`id_parque`) REFERENCES `parque` (`id_parque`);

--
-- Limitadores para a tabela `parque`
--
ALTER TABLE `parque`
  ADD CONSTRAINT `parque_ibfk_1` FOREIGN KEY (`id_universidade`) REFERENCES `universidade` (`id_universidade`);

--
-- Limitadores para a tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD CONSTRAINT `utilizadores_ibfk_1` FOREIGN KEY (`id_tipo_utilizador`) REFERENCES `tipo_utilizador` (`id_tipo_utilizador`);

--
-- Limitadores para a tabela `cartao_parque`
--
ALTER TABLE `cartao_parque`
  ADD CONSTRAINT `cartao_parque_ibfk_1` FOREIGN KEY (`id_cartao`) REFERENCES `cartoes` (`id_cartao`),
  ADD CONSTRAINT `cartao_parque_ibfk_2` FOREIGN KEY (`id_parque`) REFERENCES `parque` (`id_parque`);

--
-- Limitadores para a tabela `universidade`
--
ALTER TABLE `universidade`
  ADD CONSTRAINT `universidade_ibfk_1` FOREIGN KEY (`id_local`) REFERENCES `local` (`id_local`);

--
-- Limitadores para a tabela `veiculos_utilizador`
--
ALTER TABLE `veiculos_utilizador`
  ADD CONSTRAINT `veiculos_utilizador_ibfk_1` FOREIGN KEY (`id_veiculos`) REFERENCES `veiculos` (`id_veiculos`),
  ADD CONSTRAINT `veiculos_utilizador_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`);  

--
-- Limitadores para a tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`id_tipo_veiculo`) REFERENCES `tipo_veiculo` (`id_tipo_veiculo`);
COMMIT;