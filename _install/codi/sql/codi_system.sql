SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE `acl_permission` (
  `name` varchar(45) NOT NULL,
  `module` varchar(25) NOT NULL,
  `comment` varchar(255) NOT NULL,
  UNIQUE KEY `name_module` (`name`,`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `acl_role` (
  `name` varchar(25) NOT NULL,
  `comment` varchar(255) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `acl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `mod_date` datetime NOT NULL,
  `create_user` int(10) unsigned NOT NULL,
  `mod_user` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_logged` datetime DEFAULT NULL,
  `last_failed_login` datetime DEFAULT NULL,
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `activated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `act_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `acl_user` (`id`, `create_date`, `mod_date`, `create_user`, `mod_user`, `name`, `password`, `last_logged`, `last_failed_login`, `banned`, `activated`, `act_code`) VALUES
(1, '2013-08-02 00:00:00', '2013-08-02 00:00:00', 1, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, NULL, 0, 1, NULL);

CREATE TABLE `codi_controller` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `mod_date` datetime NOT NULL,
  `create_user` int(11) unsigned NOT NULL,
  `mod_user` int(11) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `module` varchar(45) NOT NULL,
  `id_codi_datacase` int(10) unsigned NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Controller_Module` (`name`,`module`),
  KEY `id_codi_datacase_foreign_contraint` (`id_codi_datacase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `codi_controller` (`id`, `create_date`, `mod_date`, `create_user`, `mod_user`, `name`, `module`, `id_codi_datacase`, `comment`) VALUES
(1, '2013-09-06 12:39:22', '2013-09-06 12:39:22', 1, 1, 'Home', 'codi', 1, NULL);

CREATE TABLE `codi_controller_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `id_codi_controller` int(10) unsigned NOT NULL,
  `index_action` tinyint(1) NOT NULL DEFAULT '0',
  `default_layout_id_rendus_layout` int(10) unsigned NOT NULL,
  `default_content_id_rendus_layout` int(10) unsigned NOT NULL,
  `id_codi_datacase` int(10) unsigned NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_id_codi_controller` (`name`,`id_codi_controller`),
  KEY `Codi_Controller_Foreign_Constrain` (`id_codi_controller`),
  KEY `id_codi_datacase_foreign_constraint` (`id_codi_datacase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `codi_controller_action` (`id`, `name`, `id_codi_controller`, `index_action`, `default_layout_id_rendus_layout`, `default_content_id_rendus_layout`, `id_codi_datacase`, `comment`) VALUES
(1, 'Index', 1, 1, 1, 2, 1, NULL);

CREATE TABLE `codi_datacase` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `mod_date` datetime NOT NULL,
  `create_user` int(10) unsigned NOT NULL,
  `mod_user` int(10) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `module` varchar(25) NOT NULL,
  `classname` varchar(90) DEFAULT NULL,
  `comment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `codi_datacase` (`id`, `create_date`, `mod_date`, `create_user`, `mod_user`, `name`, `module`, `classname`, `comment`) VALUES
(1, '2013-09-10 18:54:18', '2013-09-10 18:54:18', 1, 1, 'main', 'codi', NULL, 'Main DataCase of Controller'),
(2, '2013-09-10 18:54:18', '2013-09-10 18:54:18', 1, 1, 'login_panel', 'codi', NULL, 'DataCase for LoginPanel');

CREATE TABLE `codi_datacase_param` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `id_codi_datacase` int(10) unsigned NOT NULL,
  `required` tinyint(1) NOT NULL,
  `type` varchar(10) NOT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `lenght` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_data_case_foreign_constraint` (`id_codi_datacase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `codi_datacase_param` (`id`, `name`, `id_codi_datacase`, `required`, `type`, `default_value`, `lenght`) VALUES
(1, 'isLogged', 1, 0, 'bool', '0', NULL),
(2, 'isLogged', 2, 0, 'bool', '0', NULL);

CREATE TABLE `codi_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `mod_date` datetime NOT NULL,
  `create_user` int(11) NOT NULL,
  `mod_user` int(11) NOT NULL,
  `classname` varchar(90) NOT NULL,
  `id_codi_datacase` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `classname` (`classname`),
  KEY `id_codi_datacase_foreign_constraint` (`id_codi_datacase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `codi_form_param` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `id_codi_form` int(10) unsigned NOT NULL,
  `id_rendus_element_form` int(10) unsigned NOT NULL,
  `default_value` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `row` smallint(1) unsigned DEFAULT NULL,
  `col` smallint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_codi_form_name` (`name`,`id_codi_form`),
  KEY `id_codi_form_foreign_constraint` (`id_codi_form`),
  KEY `id_rendus_element_form_foreign_constraint` (`id_rendus_element_form`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `lang_transation` (
  `module` varchar(30) NOT NULL,
  `language` varchar(2) NOT NULL,
  `context` varchar(255) NOT NULL,
  `value` text,
  UNIQUE KEY `main_index` (`module`,`language`,`context`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `rendus_element` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `mod_date` datetime NOT NULL,
  `create_user` int(10) unsigned NOT NULL,
  `mod_user` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `module` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `rendus_element_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `classname` varchar(90) NOT NULL,
  `id_rendus_element` int(10) unsigned NOT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `classname` (`classname`),
  KEY `id_rendus_element_foreign_constraint` (`id_rendus_element`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `rendus_layout` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `mod_date` datetime NOT NULL,
  `create_user` int(10) unsigned NOT NULL,
  `mod_user` int(10) unsigned NOT NULL,
  `name` varchar(90) NOT NULL,
  `module` varchar(25) NOT NULL,
  `css_files` varchar(255) DEFAULT NULL,
  `js_files` varchar(255) DEFAULT NULL,
  `type` varchar(25) NOT NULL,
  `subtype` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `rendus_layout` (`id`, `create_date`, `mod_date`, `create_user`, `mod_user`, `name`, `module`, `css_files`, `js_files`, `type`, `subtype`) VALUES
(1, '2013-09-08 23:02:42', '2013-09-08 23:02:42', 1, 1, 'Rendus_Layout_Default', 'rendus', 'main.css;component.css', '', 'layout', NULL),
(2, '2013-09-07 23:15:31', '2013-09-07 23:15:31', 1, 1, 'Codi_Content_StartPage', 'codi', '', '', 'content', NULL),
(3, '2013-09-09 21:27:39', '2013-09-09 21:27:39', 1, 1, 'Rendus_Layout_Component_Footer', 'codi', '', '', 'component', NULL),
(4, '2013-09-09 21:27:39', '2013-09-09 21:27:39', 1, 1, 'Rendus_Layout_Component_LoginPanel', 'codi', '', '', 'component', NULL),
(5, '2013-09-09 21:28:16', '2013-09-09 21:28:16', 1, 1, 'Rendus_Layout_Component_Menu', 'codi', '', '', 'component', NULL);

CREATE TABLE `rendus_socket` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_date` datetime NOT NULL,
  `mod_date` datetime NOT NULL,
  `create_user` int(10) unsigned NOT NULL,
  `mod_user` int(10) unsigned NOT NULL,
  `name` varchar(90) NOT NULL,
  `source_id_rendus_layout` int(10) unsigned NOT NULL,
  `id_rendus_layout` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_id_rendus_layout` (`name`,`id_rendus_layout`),
  KEY `Rendus_Layout_Foreign_Constraint` (`id_rendus_layout`),
  KEY `Rendus_Component_Foreign_Constraint` (`source_id_rendus_layout`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `rendus_socket` (`id`, `create_date`, `mod_date`, `create_user`, `mod_user`, `name`, `source_id_rendus_layout`, `id_rendus_layout`) VALUES
(1, '2013-09-09 21:11:39', '2013-09-09 21:11:39', 1, 1, 'menu', 5, 1),
(2, '2013-09-09 21:11:39', '2013-09-09 21:11:39', 1, 1, 'login_panel', 4, 1),
(3, '2013-09-09 21:12:03', '2013-09-09 21:12:03', 1, 1, 'footer', 3, 1);


ALTER TABLE `codi_controller`
  ADD CONSTRAINT `codi_controller_ibfk_1` FOREIGN KEY (`id_codi_datacase`) REFERENCES `codi_datacase` (`id`);

ALTER TABLE `codi_controller_action`
  ADD CONSTRAINT `codi_controller_action_ibfk_2` FOREIGN KEY (`id_codi_datacase`) REFERENCES `codi_datacase` (`id`),
  ADD CONSTRAINT `codi_controller_action_ibfk_3` FOREIGN KEY (`id_codi_controller`) REFERENCES `codi_controller` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `codi_datacase_param`
  ADD CONSTRAINT `codi_datacase_param_ibfk_2` FOREIGN KEY (`id_codi_datacase`) REFERENCES `codi_datacase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `codi_form`
  ADD CONSTRAINT `codi_form_ibfk_1` FOREIGN KEY (`id_codi_datacase`) REFERENCES `codi_datacase` (`id`);

ALTER TABLE `codi_form_param`
  ADD CONSTRAINT `codi_form_param_ibfk_1` FOREIGN KEY (`id_codi_form`) REFERENCES `codi_form` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `codi_form_param_ibfk_2` FOREIGN KEY (`id_rendus_element_form`) REFERENCES `rendus_element` (`id`);

ALTER TABLE `rendus_element_form`
  ADD CONSTRAINT `rendus_element_form_ibfk_3` FOREIGN KEY (`id_rendus_element`) REFERENCES `rendus_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rendus_socket`
  ADD CONSTRAINT `rendus_socket_ibfk_1` FOREIGN KEY (`id_rendus_layout`) REFERENCES `rendus_layout` (`id`),
  ADD CONSTRAINT `rendus_socket_ibfk_2` FOREIGN KEY (`source_id_rendus_layout`) REFERENCES `rendus_layout` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
