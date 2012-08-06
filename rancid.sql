-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 06, 2012 at 10:23 AM
-- Server version: 5.2.12-MariaDB-mariadb115-log
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rancid`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_sessions`
--

CREATE TABLE IF NOT EXISTS `active_sessions` (
  `sid` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `val` text,
  `changed` varchar(14) NOT NULL DEFAULT '',
  `username` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`,`sid`),
  KEY `changed` (`changed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_user`
--

CREATE TABLE IF NOT EXISTS `auth_user` (
  `user_id` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `perms` varchar(255) DEFAULT '',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `k_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `db_sequence`
--

CREATE TABLE IF NOT EXISTS `db_sequence` (
  `seq_name` varchar(127) NOT NULL DEFAULT '',
  `nextid` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`seq_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostname` varchar(255) NOT NULL,
  `section` varchar(60) DEFAULT NULL,
  `type` varchar(60) DEFAULT NULL,
  `login_type` varchar(60) DEFAULT NULL,
  `username` varchar(60) DEFAULT NULL,
  `passwd` text,
  `en_passwd` text,
  `no_enable` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `ssh_cmd` varchar(255) DEFAULT NULL,
  `timeout` int(11) NOT NULL DEFAULT '0',
  `state` enum('Up','Down') NOT NULL DEFAULT 'Up',
  `comments` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hostname` (`hostname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `device_types`
--

CREATE TABLE IF NOT EXISTS `device_types` (
  `device_type` varchar(60) NOT NULL,
  PRIMARY KEY (`device_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `EventLog`
--

CREATE TABLE IF NOT EXISTS `EventLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `EventTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Program` varchar(64) NOT NULL DEFAULT '',
  `IPAddress` varchar(20) DEFAULT '',
  `UserName` varchar(100) NOT NULL DEFAULT '',
  `Description` varchar(255) NOT NULL,
  `ExtraInfo` text,
  `Level` enum('Info','Warning','Error','Debug') NOT NULL DEFAULT 'Info',
  PRIMARY KEY (`id`),
  KEY `Program` (`Program`),
  KEY `IPAddress` (`IPAddress`),
  KEY `UserName` (`UserName`),
  KEY `Level` (`Level`),
  KEY `EventTime` (`EventTime`),
  FULLTEXT KEY `Description` (`Description`,`ExtraInfo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `LinkedTables`
--

CREATE TABLE IF NOT EXISTS `LinkedTables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `FormName` varchar(64) NOT NULL DEFAULT '',
  `FieldName` varchar(64) NOT NULL DEFAULT '',
  `LinkTable` varchar(64) NOT NULL DEFAULT '',
  `LinkField` varchar(64) NOT NULL DEFAULT '',
  `LinkDesc` varchar(40) NOT NULL DEFAULT '',
  `LinkInfo` varchar(128) DEFAULT '',
  `NullValue` varchar(255) DEFAULT '',
  `NullDesc` varchar(255) DEFAULT '',
  `LinkCondition` varchar(255) DEFAULT '',
  `LinkErrorMsg` varchar(255) DEFAULT '',
  `DefaultValue` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FormName` (`FormName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_types`
--

CREATE TABLE IF NOT EXISTS `login_types` (
  `login_type` varchar(60) NOT NULL,
  PRIMARY KEY (`login_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `position` int(11) NOT NULL,
  `title` varchar(40) NOT NULL DEFAULT '',
  `target` varchar(255) NOT NULL DEFAULT '',
  `header` varchar(80) DEFAULT '',
  `subnavhdr` varchar(20) DEFAULT '',
  `HtmlTitle` varchar(255) DEFAULT '',
  `MetaData` text,
  `view_requires` varchar(255) DEFAULT '',
  `edit_requires` varchar(255) DEFAULT '',
  `LongDescription` varchar(80) DEFAULT '',
  `HelpText` mediumtext,
  `width` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`,`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=340 ;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE IF NOT EXISTS `sections` (
  `section` varchar(60) NOT NULL,
  PRIMARY KEY (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `session_stats`
--

CREATE TABLE IF NOT EXISTS `session_stats` (
  `sid` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `start_time` varchar(14) NOT NULL DEFAULT '',
  `referer` varchar(250) NOT NULL DEFAULT '',
  `addr` varchar(15) NOT NULL DEFAULT '',
  `user_agent` varchar(250) NOT NULL DEFAULT '',
  KEY `session_identifier` (`name`,`sid`),
  KEY `start_time` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
