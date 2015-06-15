-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2015 at 06:29 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fixed_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `fb_id`, `token`, `flag`) VALUES
(1, '105620423110610', '(null)', 1),
(2, '1426142184376392', '(null)', 1),
(3, '114452895556078', '(null)', 1);

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE IF NOT EXISTS `matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user2` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `provider` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `fix_date` datetime NOT NULL,
  `comment` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `liked_user` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `is_accept` tinyint(1) NOT NULL DEFAULT '0',
  `is_dislike` tinyint(1) NOT NULL DEFAULT '0',
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `religion`
--

CREATE TABLE IF NOT EXISTS `religion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `fb_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `single` tinyint(1) NOT NULL,
  `workplace` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `schools` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `interest` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0',
  `is_man` tinyint(1) NOT NULL DEFAULT '0',
  `is_interested_man` tinyint(1) NOT NULL DEFAULT '0',
  `is_single` tinyint(1) NOT NULL DEFAULT '0',
  `religion_priority` int(11) NOT NULL DEFAULT '0',
  `match_zipcode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `diance_range` double NOT NULL DEFAULT '9999999999',
  `min_age` int(11) NOT NULL DEFAULT '0',
  `max_age` int(11) NOT NULL DEFAULT '100',
  `min_height` int(11) NOT NULL DEFAULT '0',
  `max_height` int(11) NOT NULL DEFAULT '9999999',
  `tagline` text COLLATE utf8_unicode_ci NOT NULL,
  `height` int(11) NOT NULL,
  `religion` int(11) NOT NULL,
  `photo_path` text COLLATE utf8_unicode_ci NOT NULL,
  `fix_reminder` tinyint(1) NOT NULL,
  `cash_notification` tinyint(1) NOT NULL,
  `match_notification` tinyint(1) NOT NULL DEFAULT '0',
  `chat_notification` tinyint(1) NOT NULL DEFAULT '0',
  `alert_setting` tinyint(1) NOT NULL DEFAULT '0',
  `update_setting` tinyint(1) NOT NULL DEFAULT '0',
  `fb_friend_list` text COLLATE utf8_unicode_ci NOT NULL,
  `coins` int(11) NOT NULL DEFAULT '0',
  `match_revenue` double NOT NULL DEFAULT '0',
  `referral_revenue` double NOT NULL DEFAULT '0',
  `fixes` int(11) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fb_id`, `name`, `email`, `sex`, `birthday`, `single`, `workplace`, `schools`, `interest`, `state`, `city`, `street`, `zipcode`, `latitude`, `longitude`, `is_man`, `is_interested_man`, `is_single`, `religion_priority`, `match_zipcode`, `diance_range`, `min_age`, `max_age`, `min_height`, `max_height`, `tagline`, `height`, `religion`, `photo_path`, `fix_reminder`, `cash_notification`, `match_notification`, `chat_notification`, `alert_setting`, `update_setting`, `fb_friend_list`, `coins`, `match_revenue`, `referral_revenue`, `fixes`) VALUES
(1, '213245', '', '', 0, '0000-00-00', 0, '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, '', 9999999999, 0, 100, 0, 9999999, '', 0, 0, '', 0, 0, 0, 0, 0, 0, '', 0, 0, 0, 5),
(0, '114452895556078', 'mei li', 'mei_gijuuat_li@tfbnw.net', 1, '08/08/1980', 0, '(null)', '', '', '', '', '', '', 37.963281, -121.304, 0, 0, 0, 0, '', 9999999999, 0, 100, 0, 9999999, '', 0, 0, '', 0, 0, 0, 0, 0, 0, '[]', 0, 0, 0, 5),
(0, '105620423110610', 'jin an', 'jin_lcsdbll_an@tfbnw.net', 1, '08/08/1980', 1, 'Ten bank', 'Beijing No. 2 High School 北京二中,北京语言大学 (Beijing Language and Culture University)', '', '', '', '', '', 0, 0, 0, 0, 0, 0, '', 9999999999, 0, 100, 0, 9999999, 'I like this App very', 50, 0, '["105620423110610.jpg","105620423110610_1.jpg"]', 1, 1, 1, 1, 1, 1, '[]', 0, 0, 0, 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
