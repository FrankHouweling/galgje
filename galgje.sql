-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 31 Jul 2012 om 11:38
-- Serverversie: 5.1.44
-- PHP-Versie: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `galgje`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `max_players` int(2) NOT NULL,
  `current_mode` int(1) NOT NULL COMMENT '0->waiting,1->playing',
  `current_word` varchar(120) NOT NULL,
  `guessed_characters` varchar(120) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `last_action` int(11) NOT NULL,
  `guest_guessing` int(11) NOT NULL,
  `last_winner` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Gegevens worden uitgevoerd voor tabel `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `max_players`, `current_mode`, `current_word`, `guessed_characters`, `admin_id`, `last_action`, `guest_guessing`, `last_winner`) VALUES
(5, 'Frenkie''s Room', 20, 1, 'Brown', '', 21, 1343731780, 21, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `current_room` int(11) NOT NULL,
  `last_action` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`id`, `current_room`, `last_action`) VALUES
(1, 0, 1342709449),
(2, 0, 1342694292),
(3, 0, 1342705466),
(4, 0, 1342709445),
(5, 0, 1342721408),
(6, 0, 1342728609),
(7, 0, 1343208598),
(8, 0, 1343417482),
(9, 0, 1343414495),
(10, 0, 1343414676),
(11, 0, 1343416549),
(12, 0, 1343562914),
(13, 0, 1343559335),
(14, 0, 1343561861),
(15, 0, 1343562921),
(16, 0, 1343590373),
(17, 0, 1343647122),
(18, 0, 1343647149),
(19, 0, 1343725551),
(20, 0, 1343725540),
(21, 0, 1343731788);
