-- phpMyAdmin SQL Dump
-- version 2.11.8.1deb4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-12-2008 a las 17:20:05
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6-5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `zfblog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` bigint(20) NOT NULL,
  `category_left` int(11) NOT NULL,
  `category_right` int(11) NOT NULL,
  `category_title` varchar(255) collate utf8_bin NOT NULL,
  `category_url` varchar(255) collate utf8_bin NOT NULL,
  `category_position` int(11) default '0',
  PRIMARY KEY  (`category_id`),
  UNIQUE KEY `uk_category_title` (`category_title`),
  UNIQUE KEY `uk_category_url` (`category_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `comment_created_on` timestamp NULL default NULL,
  `comment_updated_on` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `comment_author` varchar(255) collate utf8_bin default NULL,
  `comment_email` varchar(255) collate utf8_bin default NULL,
  `comment_site` varchar(255) collate utf8_bin default NULL,
  `comment_content` longtext collate utf8_bin,
  `comment_words` longtext collate utf8_bin,
  `comment_ip` varchar(39) collate utf8_bin default NULL,
  `comment_status` smallint(6) default '0',
  `comment_spam_status` varchar(128) collate utf8_bin default '0',
  `comment_spam_filter` varchar(32) collate utf8_bin default NULL,
  `comment_trackback` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `idx_comment_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `log_id` bigint(20) NOT NULL,
  `user_id` int(11) default NULL,
  `log_table` varchar(255) collate utf8_bin NOT NULL,
  `log_created` datetime NOT NULL default '1970-01-01 00:00:00',
  `log_ip` varchar(39) collate utf8_bin NOT NULL,
  `log_msg` varchar(255) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`log_id`),
  KEY `idx_log_user_id` USING BTREE (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `media_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `media_path` varchar(255) collate utf8_bin NOT NULL,
  `media_title` varchar(255) collate utf8_bin NOT NULL,
  `media_file` varchar(255) collate utf8_bin NOT NULL,
  `media_dir` varchar(255) collate utf8_bin NOT NULL default '.',
  `media_meta` longtext collate utf8_bin,
  `media_dt` timestamp NULL default NULL,
  `media_created_on` timestamp NULL default NULL,
  `media_updated_on` timestamp NULL default NULL,
  `media_private` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`media_id`),
  KEY `idx_media_user_id` USING BTREE (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `post_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_created_on` timestamp NULL default NULL,
  `post_updated_on` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `post_password` varchar(32) collate utf8_bin default NULL,
  `post_url` varchar(255) collate utf8_bin NOT NULL,
  `post_lang` varchar(5) collate utf8_bin default NULL,
  `post_title` varchar(255) collate utf8_bin default NULL,
  `post_excerpt` text character set utf8,
  `post_content` text character set utf8,
  `post_notes` text character set utf8,
  `post_status` smallint(6) NOT NULL default '0',
  `post_selected` smallint(6) NOT NULL default '0',
  `post_open_comment` smallint(6) NOT NULL default '0',
  `post_open_tb` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  UNIQUE KEY `uk_post_url` (`post_url`),
  KEY `idx_post_user_id` USING BTREE (`user_id`),
  KEY `idx_post_post_dt_post_id` USING BTREE (`post_id`),
  KEY `idx_blog_post_post_dt_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_category`
--

CREATE TABLE IF NOT EXISTS `post_category` (
  `category_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`category_id`,`post_id`),
  KEY `idx_post_media_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_media`
--

CREATE TABLE IF NOT EXISTS `post_media` (
  `media_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`media_id`,`post_id`),
  KEY `idx_post_media_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `post_tag`
--

CREATE TABLE IF NOT EXISTS `post_tag` (
  `tag_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  PRIMARY KEY  (`tag_id`,`post_id`),
  KEY `idx_post_media_post_id` USING BTREE (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(40) collate utf8_bin NOT NULL,
  `session_time` int(11) NOT NULL default '0',
  `sesssion_start` int(11) NOT NULL default '0',
  `sesssion_value` longtext collate utf8_bin NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `tag_id` int(11) NOT NULL auto_increment,
  `tag_word` varchar(255) NOT NULL,
  PRIMARY KEY  (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` mediumint(10) NOT NULL auto_increment,
  `username` char(50) NOT NULL,
  `password` char(50) NOT NULL,
  `display_name` char(100) NOT NULL,
  `status` char(10) NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
