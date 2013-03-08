-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2013 at 02:58 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `helpcat`
--

-- --------------------------------------------------------

--
-- Table structure for table `ws_accounts`
--

CREATE TABLE IF NOT EXISTS `ws_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `password_hash` varchar(128) NOT NULL,
  `password_salt` text NOT NULL,
  `privileges` int(11) NOT NULL DEFAULT '0',
  `last_heartbeat` datetime NOT NULL,
  `email` text NOT NULL,
  `display_name` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ws_accounts`
--

INSERT INTO `ws_accounts` (`id`, `name`, `password_hash`, `password_salt`, `privileges`, `last_heartbeat`, `email`, `display_name`) VALUES
(1, 'admin', 'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec', '', 0, '2013-03-06 20:46:10', 'nikolai@helpcat.net', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `ws_chats`
--

CREATE TABLE IF NOT EXISTS `ws_chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ws_customers`
--

CREATE TABLE IF NOT EXISTS `ws_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `chat` int(11) NOT NULL,
  `addr` text NOT NULL,
  `user_agent` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `last_heartbeat` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `chat` (`chat`),
  KEY `last_heartbeat` (`last_heartbeat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ws_messages`
--

CREATE TABLE IF NOT EXISTS `ws_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `chat` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `sender_type` int(11) NOT NULL,
  `name` text NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chat` (`chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ws_notifications`
--

CREATE TABLE IF NOT EXISTS `ws_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `global` tinyint(1) NOT NULL DEFAULT '1',
  `recipient` int(11) NOT NULL DEFAULT '-1',
  `type` text NOT NULL,
  `target` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `global` (`global`),
  KEY `recipient` (`recipient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ws_sessions`
--

CREATE TABLE IF NOT EXISTS `ws_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `agent` int(11) NOT NULL,
  `chat` int(11) NOT NULL,
  `last_heartbeat` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agent` (`agent`),
  KEY `chat` (`chat`),
  KEY `last_heartbeat` (`last_heartbeat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
