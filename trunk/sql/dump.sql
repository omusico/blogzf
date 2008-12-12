-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generaci�n: 11-12-2008 a las 22:19:56
-- Versi�n del servidor: 5.0.51
-- Versi�n de PHP: 5.2.6-5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `zfblog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` bigint(20) NOT NULL auto_increment,
  `category_left` int(11) NOT NULL,
  `category_right` int(11) NOT NULL,
  `category_title` varchar(255) NOT NULL,
  `category_url` varchar(255) NOT NULL,
  `category_position` int(11) default '0',
  PRIMARY KEY  (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `category`
--

INSERT INTO `category` (`category_id`, `category_left`, `category_right`, `category_title`, `category_url`, `category_position`) VALUES
(1, 0, 7, 'default', 'default', 0),
(2, 1, 2, 'zend framework', 'zend-framework', 0),
(3, 3, 6, 'php', 'php', 0),
(4, 4, 5, 'php 5', 'php5', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` bigint(20) NOT NULL auto_increment,
  `post_id` bigint(20) NOT NULL,
  `comment_created_on` timestamp NULL default NULL,
  `comment_updated_on` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `comment_author` varchar(255) default NULL,
  `comment_email` varchar(255) default NULL,
  `comment_site` varchar(255) default NULL,
  `comment_content` longtext,
  `comment_words` longtext,
  `comment_ip` varchar(39) default NULL,
  `comment_status` smallint(6) default '0',
  `comment_spam_status` varchar(128) default '0',
  `comment_spam_filter` varchar(32) default NULL,
  `comment_trackback` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `idx_comment_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `comment`
--

INSERT INTO `comment` (`comment_id`, `post_id`, `comment_created_on`, `comment_updated_on`, `comment_author`, `comment_email`, `comment_site`, `comment_content`, `comment_words`, `comment_ip`, `comment_status`, `comment_spam_status`, `comment_spam_filter`, `comment_trackback`) VALUES
(1, 1, '2008-12-11 12:20:13', '2008-12-11 12:29:04', 'Sebastien Cramatte', '', '', 'hola mundo. Lorem ipsum dolorm consectetur ...', '', '', 0, '0', '', 0),
(2, 1, '2008-12-11 13:13:14', '2008-12-11 13:13:42', 'Pablo', NULL, NULL, 'Lorem ipsum dolorm consectetur ...', NULL, NULL, 0, '0', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `log_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `log_table` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  `log_created_on` timestamp NULL default NULL,
  `log_ip` varchar(39) character set utf8 collate utf8_bin NOT NULL,
  `log_msg` varchar(255) character set utf8 collate utf8_bin NOT NULL,
  PRIMARY KEY  (`log_id`),
  KEY `idx_log_user_id` USING BTREE (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `log`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `media_id` bigint(20) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `media_title` varchar(255) NOT NULL,
  `media_file` varchar(255) NOT NULL,
  `media_dir` varchar(255) NOT NULL default '.',
  `media_meta` longtext,
  `media_dt` timestamp NULL default NULL,
  `media_created_on` timestamp NULL default NULL,
  `media_updated_on` timestamp NULL default NULL,
  `media_private` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`media_id`),
  KEY `idx_media_user_id` USING BTREE (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `media`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `page_id` bigint(20) NOT NULL auto_increment,
  `page_url` varchar(255) default NULL,
  `page_title` varchar(255) default NULL,
  `page_excerpt` varchar(255) NOT NULL,
  `page_content` text,
  `page_lang` varchar(5) default NULL,
  `page_status` smallint(6) default NULL,
  `page_keywords` varchar(255) default NULL,
  `page_description` varchar(255) default NULL,
  PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `page`
--

INSERT INTO `page` (`page_id`, `page_url`, `page_title`, `page_excerpt`, `page_content`, `page_lang`, `page_status`, `page_keywords`, `page_description`) VALUES
(1, 'about', 'About', 'My name is S�bastien, I''m helping Pablo building this nice Blog !', 'Hola mundo.', 'es', 1, NULL, NULL),
(2, 'contact', 'Contact', '', 'Hola mundo', 'es', 1, NULL, NULL),
(3, 'extras', 'Extras', '', 'Hola mundo ...', 'es', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `post_id` bigint(20) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `post_created_on` timestamp NULL default NULL,
  `post_updated_on` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `post_password` varchar(32) default NULL,
  `post_url` varchar(255) NOT NULL,
  `post_lang` varchar(5) default NULL,
  `post_title` varchar(255) default NULL,
  `post_excerpt` text,
  `post_content` text,
  `post_notes` text,
  `post_status` smallint(6) NOT NULL default '0',
  `post_selected` smallint(6) NOT NULL default '0',
  `post_open_comment` smallint(6) NOT NULL default '0',
  `post_open_tb` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  UNIQUE KEY `uk_post_url` (`post_url`),
  KEY `idx_post_user_id` USING BTREE (`user_id`),
  KEY `idx_post_post_dt_post_id` USING BTREE (`post_id`),
  KEY `idx_blog_post_post_dt_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `post`
--

INSERT INTO `post` (`post_id`, `user_id`, `post_created_on`, `post_updated_on`, `post_password`, `post_url`, `post_lang`, `post_title`, `post_excerpt`, `post_content`, `post_notes`, `post_status`, `post_selected`, `post_open_comment`, `post_open_tb`) VALUES
(1, 1, '2008-09-08 17:12:48', '2008-12-11 15:01:00', '', 'lorem-ipsum-dolor', 'es', 'Lorem Ipsum Dolor', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. \r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. ', NULL, 1, 0, 0, 0),
(2, 1, '2008-10-10 16:49:51', '0000-00-00 00:00:00', NULL, 'lorem-ipsum-dolor-2', 'es', 'Lorem Ipsum Dolor 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. \r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. ', NULL, 1, 1, 0, 0),
(3, 1, '2008-10-10 16:50:03', '2008-12-11 17:14:03', NULL, 'lorem-ipsum-dolor-3', 'es', 'Lorem Ipsum Dolor 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. ', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. \r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec pretium. Nam lorem. Nam magna erat, tincidunt at, feugiat euismod, convallis et, lectus. Praesent faucibus suscipit dui. Praesent suscipit massa et ante fringilla tincidunt. Nulla purus. Morbi malesuada sodales dui. Phasellus urna purus, pulvinar non, rutrum sit amet, fringilla vel, velit. Ut tortor. Pellentesque molestie volutpat leo. In vitae nisi. Integer ac arcu a leo imperdiet consectetur. Nam vitae erat in nulla scelerisque adipiscing. Nam mauris magna, sagittis euismod, vulputate in, elementum eu, justo. Mauris molestie, mi in molestie pharetra, neque lorem accumsan purus, sed feugiat quam pede vitae diam. Sed dolor eros, aliquam vitae, accumsan in, tristique id, ante. Integer neque pede, commodo quis, bibendum venenatis, porta ut, urna. Ut ac justo cursus lorem tincidunt dictum. Proin sed est. Sed adipiscing, diam ut consectetur aliquam, ante lacus sagittis erat, at tempor sapien magna in eros. ', NULL, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_category`
--

CREATE TABLE IF NOT EXISTS `post_category` (
  `category_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`category_id`,`post_id`),
  KEY `idx_post_media_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `post_category`
--

INSERT INTO `post_category` (`category_id`, `post_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_media`
--

CREATE TABLE IF NOT EXISTS `post_media` (
  `media_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`media_id`,`post_id`),
  KEY `idx_post_media_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `post_media`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_tag`
--

CREATE TABLE IF NOT EXISTS `post_tag` (
  `tag_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`tag_id`,`post_id`),
  KEY `idx_post_media_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `post_tag`
--

INSERT INTO `post_tag` (`tag_id`, `post_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(3, 2),
(4, 2),
(2, 3),
(4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(40) NOT NULL,
  `session_time` int(11) NOT NULL default '0',
  `sesssion_start` int(11) NOT NULL default '0',
  `sesssion_value` longtext NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `session`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` bigint(20) NOT NULL auto_increment,
  `tag_word` varchar(255) NOT NULL,
  `tag_url` varchar(255) NOT NULL,
  PRIMARY KEY  (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `tag`
--

INSERT INTO `tag` (`tag_id`, `tag_word`, `tag_url`) VALUES
(1, 'php', ''),
(2, 'zend', ''),
(3, 'zend framework', ''),
(4, 'css', ''),
(5, 'xhtml', ''),
(6, 'oop', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(20) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `status` smallint(1) NOT NULL,
  `created_on` timestamp NULL default NULL,
  `updated_on` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `display_name`, `status`, `created_on`, `updated_on`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 0, '2008-12-09 18:03:49', '2008-12-09 18:03:49');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `post_category`
--
ALTER TABLE `post_category`
  ADD CONSTRAINT `fk_post_category_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`),
  ADD CONSTRAINT `fk_post_category_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `post_media`
--
ALTER TABLE `post_media`
  ADD CONSTRAINT `fk_post_media_media` FOREIGN KEY (`media_id`) REFERENCES `media` (`media_id`),
  ADD CONSTRAINT `post_media_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `fk_post_tag_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tag_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`tag_id`);



--
-- Table structure for table `lov`
--

CREATE TABLE IF NOT EXISTS `lov` (
  `lov_id` mediumint(10) NOT NULL auto_increment,
  `type` char(20) NOT NULL,
  `value` char(40) NOT NULL,
  PRIMARY KEY  (`lov_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `lov`
--

INSERT INTO `lov` (`lov_id`, `type`, `value`) VALUES
(1, 'status', 'publicado'),
(2, 'status', 'pendiente'),
(3, 'status', 'borrador'),
(4, 'status', 'agendado');