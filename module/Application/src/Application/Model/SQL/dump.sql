SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `evento`;
CREATE TABLE `evento` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `nome` varchar(30) NOT NULL,
  `dia` int(1) NOT NULL,
  `hora` time NOT NULL,
  `evento_tipo_id` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `evento_tipo_id` (`evento_tipo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `evento_frequencia`;
CREATE TABLE `evento_frequencia` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `evento_id` int(6) unsigned NOT NULL,
  `pessoa_id` bigint(11) unsigned NOT NULL,
  `frequencia` enum('S','N') NOT NULL DEFAULT 'N',
  `dia` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_evento_frequencia_evento_id` (`evento_id`),
  KEY `index_evento_frequencia_pessoa_id` (`pessoa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `evento_tipo`;
CREATE TABLE `evento_tipo` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `fato_ciclo`;
CREATE TABLE `fato_ciclo` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `ligacao` int(1) NULL,
  `mensagem` int(1) NULL,
  `ponte` int(1) NULL,
  `prospecto` int(1) NULL,
  `frequencia` int(1) NULL,
  `clique_ligacao` int(1) NULL,
  `clique_mensagem` int(1) NULL,
  `numero_identificador` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE `grupo` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `grupo_evento`;
CREATE TABLE `grupo_evento` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `grupo_id` int(6) unsigned NOT NULL,
  `evento_id` int(6) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_grupo_evento_grupo_id` (`grupo_id`),
  KEY `index_grupo_evento_evento_id` (`evento_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `grupo_pai_filho`;
CREATE TABLE `grupo_pai_filho` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `pai_id` int(6) unsigned NOT NULL,
  `filho_id` int(6) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_grupo_pai_filho_pai_id` (`pai_id`),
  KEY `index_grupo_pai_filho_filho_id` (`filho_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `grupo_pessoa`;
CREATE TABLE `grupo_pessoa` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` int(11) DEFAULT NULL,
  `grupo_id` int(6) unsigned NOT NULL,
  `pessoa_id` bigint(11) unsigned NOT NULL,
  `grupo_pessoa_tipo_id` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_grupo_pessoa_grupo_id` (`grupo_id`),
  KEY `index_grupo_pessoa_pessoa_id` (`pessoa_id`),
  KEY `index_grupo_pessoa_grupo_pessoa_tipo_id` (`grupo_pessoa_tipo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `grupo_pessoa_tipo`;
CREATE TABLE `grupo_pessoa_tipo` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `grupo_responsavel`;
CREATE TABLE `grupo_responsavel` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `grupo_id` int(6) unsigned NOT NULL,
  `pessoa_id` bigint(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_grupo_responsavel_pessoa_id` (`pessoa_id`),
  KEY `index_grupo_responsavel_grupo_id` (`grupo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `hierarquia`;
CREATE TABLE `hierarquia` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `pessoa`;
CREATE TABLE `pessoa` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `telefone` bigint(11) unsigned NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `senha` varchar(40) DEFAULT NULL,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `pessoa_hierarquia`;
CREATE TABLE `pessoa_hierarquia` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `pessoa_id` bigint(11) unsigned NOT NULL,
  `hierarquia_id` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_pessoa_hierarquia_pessoa_id` (`pessoa_id`),
  KEY `index_pessoa_hierarquia_hierarquia_id` (`hierarquia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `ponte_prospecto`;
CREATE TABLE `ponte_prospecto` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `ponte_id` bigint(11) unsigned NOT NULL,
  `prospecto_id` bigint(11) unsigned NOT NULL,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_pronte_prospecto_ponte_id` (`ponte_id`),
  KEY `index_ponte_prospecto_prospecto_id` (`prospecto_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `tarefa`;
CREATE TABLE `tarefa` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `tarefa_tipo_id` int(1) unsigned NOT NULL,
  `pessoa_id` bigint(11) unsigned NOT NULL,
  `realizada` enum('S','N') NOT NULL DEFAULT 'N',
  `data_alteracao` date DEFAULT NULL,
  `hora_alteracao` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_tarefa_tarefa_tipo_id` (`tarefa_tipo_id`),
  KEY `index_tarefa_pessoa_id` (`pessoa_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `tarefa_tipo`;
CREATE TABLE `tarefa_tipo` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `data_criacao` date NOT NULL,
  `hora_criacao` time NOT NULL,
  `data_inativacao` date DEFAULT NULL,
  `hora_inativacao` time DEFAULT NULL,
  `nome` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `evento`
  ADD CONSTRAINT `fk_evento_evento_tipo_id` FOREIGN KEY (`evento_tipo_id`) REFERENCES `evento_tipo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `evento_frequencia`
  ADD CONSTRAINT `fk_evento_frequencia_evento_id` FOREIGN KEY (`evento_id`) REFERENCES `evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_evento_frequencia_pessoa_id` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `grupo_evento`
  ADD CONSTRAINT `fk_grupo_evento_evento_id` FOREIGN KEY (`evento_id`) REFERENCES `evento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_grupo_evento_grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `grupo_pai_filho`
  ADD CONSTRAINT `fk_grupo_pai_filho_filho_id` FOREIGN KEY (`filho_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_grupo_pai_filho_pai_id` FOREIGN KEY (`pai_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `grupo_pessoa`
  ADD CONSTRAINT `fk_grupo_pessoa_grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_grupo_pessoa_grupo_pessoa_tipo_id` FOREIGN KEY (`grupo_pessoa_tipo_id`) REFERENCES `grupo_pessoa_tipo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_grupo_pessoa_pessoa_id` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `grupo_responsavel`
  ADD CONSTRAINT `fk_grupo_responsavel_grupo_id` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_grupo_responsavel_pessoa_id` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `pessoa_hierarquia`
  ADD CONSTRAINT `fk_pessoa_hierarquia_hierarquia_id` FOREIGN KEY (`hierarquia_id`) REFERENCES `hierarquia` (`id`),
  ADD CONSTRAINT `fk_pessoa_hierarquia_pessoa_id` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `ponte_prospecto`
  ADD CONSTRAINT `fk_ponte_prospecto_ponte_id` FOREIGN KEY (`ponte_id`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ponte_prospecto_prospecto_id` FOREIGN KEY (`prospecto_id`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `tarefa`
  ADD CONSTRAINT `fk_tarefa_pessoa_id` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tarefa_tarefa_tipo_id` FOREIGN KEY (`tarefa_tipo_id`) REFERENCES `tarefa_tipo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
