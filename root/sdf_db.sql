-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 20, 2019 at 09:59 PM
-- Server version: 5.6.34
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdf_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_name` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_name`) VALUES
('Fashion'),
('Food & Drink'),
('Health & Fitness'),
('Technology');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `discountid` int(11) NOT NULL,
  `percent` decimal(10,0) NOT NULL,
  `name` varchar(80) NOT NULL,
  `code` varchar(120) DEFAULT NULL,
  `expire_date` date NOT NULL,
  `start_date` date NOT NULL,
  `subcategory` varchar(80) NOT NULL,
  `storeid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discountid`, `percent`, `name`, `code`, `expire_date`, `start_date`, `subcategory`, `storeid`) VALUES
(1, '15', 'sport clothes', 'DKTPRNRUXHRLHYA', '2019-10-27', '2019-02-27', 'Clothing', 1),
(2, '25', 'women shoes', 'LNGQMNEFPKGWRVD', '2019-03-07', '2019-03-01', 'Shoes', 1),
(4, '40', 'laptops', 'XXWSYOXAHHQIRMI', '2019-03-13', '2019-03-01', 'Laptops & Tablets', 2),
(5, '80', 'mouse and keyboards', 'RXQKDLWBMJLYPCQ', '2019-03-01', '2019-03-01', 'Technology Accessories', 2),
(6, '30', 'football shirts', 'UZOWRSQXUGTENCW', '2019-03-25', '2019-03-01', 'Clothing', 1),
(7, '30', 'Samsung Wireless Earbuds', 'IFALELUSDKRDQVF', '2019-03-14', '2019-03-02', 'Technology Accessories', 3);

-- --------------------------------------------------------

--
-- Table structure for table `registereduser`
--

CREATE TABLE `registereduser` (
  `userid` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `stylePref` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registereduser`
--

INSERT INTO `registereduser` (`userid`, `username`, `firstname`, `lastname`, `email`, `password`, `stylePref`) VALUES
(1, 'student1', 'studentfn', 'studentln', 'student1@gmail.com', '123456', 1),
(2, 'storememb1', '', '', '', '123456', 0),
(3, 'storememb2', 'storemembfn', 'storemembln', 'storememb2@gmail.com', '123456', 0);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `storeid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(3000) DEFAULT NULL,
  `category` varchar(40) NOT NULL,
  `website` varchar(80) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  `location` blob,
  `photos` blob,
  `storememberID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`storeid`, `name`, `description`, `category`, `website`, `phone`, `likes`, `dislikes`, `location`, `photos`, `storememberID`) VALUES
(1, 'JD', 'A really long description to test if the description overlays the store link', 'Fashion', 'jd.co.uk', '075456412315', 0, 0, NULL, NULL, 2),
(2, 'Currys', 'currys description here...', 'Technology', 'Currys.com', '075456412315', 0, 0, NULL, NULL, 2),
(3, 'Carphone Warehouse', '', 'Technology', 'carphonewarehouse.com', '07454542125431', 1, 0, NULL, NULL, 2),
(4, 'EE', '', 'Technology', 'ee.com', '075456412315', 0, 0, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `storemember`
--

CREATE TABLE `storemember` (
  `userid` int(11) NOT NULL,
  `stores` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `storemember`
--

INSERT INTO `storemember` (`userid`, `stores`) VALUES
(2, 0x613a343a7b693a303b693a313b693a313b693a323b693a323b693a333b693a333b693a343b7d),
(3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `userid` int(11) NOT NULL,
  `dob` date NOT NULL,
  `graduation_date` date NOT NULL,
  `university` varchar(80) NOT NULL,
  `store_view_history` blob,
  `fav_stores` blob,
  `disliked_stores` blob,
  `claimedlist` blob,
  `prefCategories` blob,
  `prefSubCategories` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`userid`, `dob`, `graduation_date`, `university`, `store_view_history`, `fav_stores`, `disliked_stores`, `claimedlist`, `prefCategories`, `prefSubCategories`) VALUES
(1, '1997-08-28', '2021-06-01', 'Queen Mary University of London', 0x613a343a7b693a3135313b733a313a2232223b693a3135323b733a313a2233223b693a3135333b733a313a2234223b693a3137333b733a313a2231223b7d, 0x613a313a7b693a303b733a313a2233223b7d, 0x613a303a7b7d, 0x613a323a7b693a303b733a313a2231223b693a313b733a313a2232223b7d, 0x613a323a7b733a363a22636174656731223b733a373a2246617368696f6e223b733a363a22636174656734223b733a31303a22546563686e6f6c6f6779223b7d, 0x613a313a7b733a31323a2273756243617465675f335f31223b733a31363a224669746e65737320436c6f7468696e67223b7d);

-- --------------------------------------------------------

--
-- Table structure for table `studentclaim`
--

CREATE TABLE `studentclaim` (
  `studentclaim_id` int(11) NOT NULL,
  `discount_rating` tinyint(1) DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `discount` blob,
  `discountid` int(11) NOT NULL,
  `discount_available` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `studentclaim`
--

INSERT INTO `studentclaim` (`studentclaim_id`, `discount_rating`, `used`, `discount`, `discountid`, `discount_available`) VALUES
(1, NULL, 0, 0x613a323a7b693a303b4f3a383a22646973636f756e74223a313a7b733a31323a2200646973636f756e7400645f223b613a313a7b693a303b613a383a7b733a31303a22646973636f756e746964223b733a313a2231223b733a373a2270657263656e74223b733a323a223135223b733a343a226e616d65223b733a31333a2273706f727420636c6f74686573223b733a343a22636f6465223b733a31353a22444b5450524e52555848524c485941223b733a31313a226578706972655f64617465223b733a31303a22323031392d31302d3237223b733a31303a2273746172745f64617465223b733a31303a22323031392d30322d3237223b733a31313a2273756263617465676f7279223b733a383a22436c6f7468696e67223b733a373a2273746f72656964223b733a313a2231223b7d7d7d693a313b733a323a224a44223b7d, 1, 1),
(2, NULL, 0, 0x613a323a7b693a303b4f3a383a22646973636f756e74223a313a7b733a31323a2200646973636f756e7400645f223b613a313a7b693a303b613a383a7b733a31303a22646973636f756e746964223b733a313a2236223b733a373a2270657263656e74223b733a323a223330223b733a343a226e616d65223b733a31353a22666f6f7462616c6c20736869727473223b733a343a22636f6465223b733a31353a22555a4f5752535158554754454e4357223b733a31313a226578706972655f64617465223b733a31303a22323031392d30332d3235223b733a31303a2273746172745f64617465223b733a31303a22323031392d30332d3031223b733a31313a2273756263617465676f7279223b733a383a22436c6f7468696e67223b733a373a2273746f72656964223b733a313a2231223b7d7d7d693a313b733a323a224a44223b7d, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `subcategory_name` varchar(30) NOT NULL,
  `category_name` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`subcategory_name`, `category_name`) VALUES
('Clothing', 'Fashion'),
('Shoes', 'Fashion'),
('Accessories', 'Fashion'),
('Jewellery & Watches', 'Fashion'),
('Menswear', 'Fashion'),
('Lingerie & Underwear', 'Fashion'),
('Italian', 'Food & Drink'),
('Thai', 'Food & Drink'),
('Mexican', 'Food & Drink'),
('Fast Food', 'Food & Drink'),
('Chinese', 'Food & Drink'),
('Korean', 'Food & Drink'),
('Japanese', 'Food & Drink'),
('Turkish', 'Food & Drink'),
('Fitness Clothing', 'Health & Fitness'),
('Gym', 'Health & Fitness'),
('Equipment', 'Health & Fitness'),
('Supplements', 'Health & Fitness'),
('Fitness Trackers', 'Health & Fitness'),
('Laptops & Tablets', 'Technology'),
('Technology Accessories', 'Technology'),
('Mobile', 'Technology'),
('Electricals', 'Technology'),
('Gaming', 'Technology');

-- --------------------------------------------------------

--
-- Table structure for table `university`
--

CREATE TABLE `university` (
  `university_name` varchar(80) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `university`
--

INSERT INTO `university` (`university_name`) VALUES
('Barnet and Southgate College - Colindale Campus'),
('Bloomsbury Institute'),
('BPP University'),
('Brunel University London'),
('Cambridge University'),
('Cardiff University'),
('Central Saint Martins'),
('City And Islington College - Sixth Form College'),
('City Lit'),
('City of Westminster College, Paddington Green Campus'),
('City, University of London'),
('Croydon College'),
('Durham University'),
('Guildhall School of Music & Drama'),
('Harrow College'),
('Imperial College London'),
('King\'s College London'),
('Lambeth College Clapham Centre'),
('Lewisham Southwark College'),
('London College of Communication'),
('London College of Fashion'),
('London Metropolitan University'),
('London School of Business and Finance'),
('London School of Economics'),
('London School of Economics and Political Science'),
('London South Bank University'),
('Middlesex University'),
('Newcastle University'),
('Newham College - East Ham Campus'),
('Queen Mary University of London'),
('Queen\'s University Belfast'),
('Ravensbourne University London'),
('Regent\'s University London'),
('Royal College of Art'),
('Royal College of Music'),
('Royal College of Obstetricians and Gynaecologists'),
('Royal College of Physicians'),
('The College of Haringey, Enfield and North East London, Tottenham Centre'),
('Trinity Laban'),
('University College London'),
('University of Birmingham'),
('University of Bristol'),
('University of Cambridge'),
('University of East London'),
('University of Edinburgh'),
('University of Exeter'),
('University of Glasgow'),
('University of Greenwich'),
('University of Leeds'),
('University of Liverpool'),
('University of London'),
('University of Manchester'),
('University of Nottingham'),
('University of Oxford'),
('University of Roehampton London'),
('University of Sheffield'),
('University of Southampton'),
('University of Warwick'),
('University of West London'),
('University of Westminster'),
('University of York');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_name`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discountid`);

--
-- Indexes for table `registereduser`
--
ALTER TABLE `registereduser`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`storeid`);

--
-- Indexes for table `storemember`
--
ALTER TABLE `storemember`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `studentclaim`
--
ALTER TABLE `studentclaim`
  ADD PRIMARY KEY (`studentclaim_id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`subcategory_name`);

--
-- Indexes for table `university`
--
ALTER TABLE `university`
  ADD PRIMARY KEY (`university_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discountid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registereduser`
--
ALTER TABLE `registereduser`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `storeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `studentclaim`
--
ALTER TABLE `studentclaim`
  MODIFY `studentclaim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
