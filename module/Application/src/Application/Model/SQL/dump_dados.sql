SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

INSERT INTO `evento_tipo` (`id`, `nome`, `data_criacao`, `hora_criacao`, `data_inativacao`, `hora_inativacao`) VALUES
(1, 'REUNIAO ABERTA', '2018-01-17', '00:00:01', NULL, NULL);

INSERT INTO `grupo_pessoa_tipo` (`id`, `data_criacao`, `hora_criacao`, `data_inativacao`, `hora_inativacao`, `nome`) VALUES
(1, '2018-01-17', '00:00:00', NULL, NULL, 'PONTE'),
(2, '2018-01-17', '00:00:00', NULL, NULL, 'PROSPECTO');

INSERT INTO `hierarquia` (`id`, `data_criacao`, `hora_criacao`, `data_inativacao`, `hora_inativacao`, `nome`) VALUES
(1, '2018-01-17', '00:00:01', NULL, NULL, 'ATIVO SEM REUNIAO'),
(2, '2018-01-17', '00:00:01', NULL, NULL, 'ATIVO COM REUNIAO');

INSERT INTO `tarefa_tipo` (`id`, `data_criacao`, `hora_criacao`, `data_inativacao`, `hora_inativacao`, `nome`) VALUES
(1, '2018-01-17', '00:00:01', NULL, NULL, 'LIGAR'),
(2, '2018-01-17', '00:00:01', NULL, NULL, 'ENVIAR MENSAGEM');