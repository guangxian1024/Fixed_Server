-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 22, 2015 at 09:30 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `redbasket`
--

-- --------------------------------------------------------

--
-- Table structure for table `android_device_list`
--

CREATE TABLE IF NOT EXISTS `android_device_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `devicetoken` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `device_list`
--

CREATE TABLE IF NOT EXISTS `device_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `devicetoken` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `device_list`
--

INSERT INTO `device_list` (`id`, `fb_id`, `devicetoken`, `user_flag`) VALUES
(1, '1443656369265544', 'd5901880595c6b2a7cf0f0cbda04e5a35429e884d975912bc8d4c0ea57af9492', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_list`
--

CREATE TABLE IF NOT EXISTS `order_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_fb_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_fb_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `merchant_paypal` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `user_paypal` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `special_title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `count` double NOT NULL,
  `unit_price` double NOT NULL,
  `tax` double NOT NULL,
  `total_price` double NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed_flag` tinyint(1) NOT NULL DEFAULT '0',
  `transactionId` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `orderNumber` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `barcode` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `order_list`
--

INSERT INTO `order_list` (`id`, `merchant_fb_id`, `user_fb_id`, `merchant_paypal`, `user_paypal`, `special_title`, `count`, `unit_price`, `tax`, `total_price`, `order_date`, `completed_flag`, `transactionId`, `orderNumber`, `barcode`, `user_name`) VALUES
(9, '1443656369265544', '1431670627147629', 'glenn_test1@paypal.com', 'user1', 'Fast Food', 2, 18, 1.12, 37.12, '2015-04-22 06:54:55', 1, 'AP-24C4530338385093F', '113579', '1504220654559', 'Glenn Chirino'),
(10, '1443656369265544', '1431670627147629', 'glenn_test1@paypal.com', 'user1', 'Fast Food', 2, 18, 1.12, 37.12, '2015-04-22 07:56:39', 0, 'AP-83P222867C5690221', '113580', '1504220756390', 'Glenn Chirino');

-- --------------------------------------------------------

--
-- Table structure for table `order_number`
--

CREATE TABLE IF NOT EXISTS `order_number` (
  `last_order_number` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_number`
--

INSERT INTO `order_number` (`last_order_number`) VALUES
(113582);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE IF NOT EXISTS `store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `contact_name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `special_title` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `fb_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image_path` text COLLATE utf8_unicode_ci NOT NULL,
  `special_description` text COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Street',
  `city` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `special_price` double NOT NULL,
  `tax_rate` double NOT NULL,
  `phonenumber` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `paypalemail` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `publish_flag` tinyint(1) NOT NULL DEFAULT '0',
  `published_time` datetime NOT NULL,
  `expire_time` int(11) NOT NULL,
  `pagelink` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`id`, `name`, `contact_name`, `special_title`, `fb_id`, `image_path`, `special_description`, `street`, `city`, `latitude`, `longitude`, `special_price`, `tax_rate`, `phonenumber`, `paypalemail`, `publish_flag`, `published_time`, `expire_time`, `pagelink`) VALUES
(1, 'Glenn Test Restaurant', 'Glenn Chirino', 'Fast Food', '1443656369265544', 'specialPictures/14436563692655442015_04_21_08_17_29.jpg', 'This is my Fast Food', '13 St ', 'Amsterdam', 52.3731, 4.8922, 18, 3.1, '', 'glenn_test1@paypal.com', 1, '2015-04-22 11:54:06', 160, 'https://www.facebook.com/pages/Glenn-Test-Restaurant/1443656369265544'),
(2, 'Glenn Mobile Team', 'Glenn C', '', '625942670839493', '', '', '14 St', 'Amsterdam', 52.3731, 4.8922, 0, 2.3, ' 31203201824', 'glenn_test1@paypal.com', 1, '2015-04-22 00:00:00', 0, 'https://www.facebook.com/pages/Glenn-Mobile-Team/625942670839493');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `contact_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `push_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fb_id`, `name`, `latitude`, `longitude`, `contact_email`, `push_flag`) VALUES
(1, '1431670627147629', 'Glenn Chirino', 0, 0, '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
