-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2020 at 01:10 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homeautomation`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appliances`
--

CREATE TABLE `tbl_appliances` (
  `applianceID` tinyint(1) NOT NULL,
  `applianceName` varchar(20) DEFAULT NULL,
  `applianceRating` decimal(6,2) DEFAULT NULL,
  `applianceStatus` tinyint(1) NOT NULL,
  `applianceOutputPin` smallint(2) NOT NULL,
  `applianceInputPin` tinyint(4) NOT NULL,
  `applianceUCL` smallint(5) DEFAULT NULL,
  `applianceLCL` smallint(5) DEFAULT NULL,
  `applianceReadingStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_appliances`
--

INSERT INTO `tbl_appliances` (`applianceID`, `applianceName`, `applianceRating`, `applianceStatus`, `applianceOutputPin`, `applianceInputPin`, `applianceUCL`, `applianceLCL`, `applianceReadingStatus`) VALUES
(1, 'gg', NULL, 0, 40, 37, NULL, NULL, 0),
(2, NULL, NULL, 0, 38, 35, NULL, NULL, 0),
(3, NULL, NULL, 0, 36, 33, NULL, NULL, 0),
(4, NULL, NULL, 0, 32, 31, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

CREATE TABLE `tbl_logs` (
  `logID` int(100) NOT NULL,
  `logDateTime` datetime NOT NULL,
  `logAppliance` varchar(20) NOT NULL,
  `logAction` tinyint(1) NOT NULL,
  `logVia` tinyint(1) NOT NULL,
  `logUser` smallint(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notifID` int(11) NOT NULL,
  `notifMessage` varchar(50) NOT NULL,
  `notifText` text NOT NULL,
  `notifDateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification_status`
--

CREATE TABLE `tbl_notification_status` (
  `notifUserID` tinyint(1) NOT NULL,
  `notifID` int(11) NOT NULL,
  `notifStatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_readings`
--

CREATE TABLE `tbl_readings` (
  `applianceID` tinyint(1) NOT NULL,
  `rCurrent` decimal(4,2) NOT NULL,
  `rVoltage` smallint(3) NOT NULL,
  `rDateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_schedules`
--

CREATE TABLE `tbl_schedules` (
  `scheduleID` int(11) NOT NULL,
  `scheduleDate` date DEFAULT NULL,
  `scheduleTime` time NOT NULL,
  `scheduleApplianceID` tinyint(1) NOT NULL,
  `scheduleAction` tinyint(1) NOT NULL,
  `scheduleRepeat` varchar(7) DEFAULT NULL,
  `isExecuted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_totalconsumption`
--

CREATE TABLE `tbl_totalconsumption` (
  `totalConsID` int(11) NOT NULL,
  `totalConsPort` tinyint(1) NOT NULL,
  `totalConsWatt` smallint(6) NOT NULL,
  `totalConsStart` datetime NOT NULL,
  `totalConsEnd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unsentnotif`
--

CREATE TABLE `tbl_unsentnotif` (
  `notifID` int(11) NOT NULL,
  `notifMessage` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userID` int(4) NOT NULL,
  `userName` varchar(20) NOT NULL,
  `userPass` varchar(20) NOT NULL,
  `userProf` varchar(20) NOT NULL,
  `userType` tinyint(4) NOT NULL,
  `userFirstName` varchar(50) NOT NULL,
  `userLastName` varchar(50) NOT NULL,
  `userPhoneNumber` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userID`, `userName`, `userPass`, `userProf`, `userType`, `userFirstName`, `userLastName`, `userPhoneNumber`) VALUES
(0, 'abay', 'abay', '', 0, 'Ian Joseph', 'Solon', '9956139395'),
(1, 'znarf', 'znarf', '', 1, 'Franz Dink', 'Catalan', '9999999999');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_appliances`
--
ALTER TABLE `tbl_appliances`
  ADD PRIMARY KEY (`applianceID`),
  ADD UNIQUE KEY `UK_applianceName` (`applianceName`);

--
-- Indexes for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  ADD PRIMARY KEY (`logID`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notifID`);

--
-- Indexes for table `tbl_notification_status`
--
ALTER TABLE `tbl_notification_status`
  ADD KEY `FK_notifID` (`notifID`);

--
-- Indexes for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  ADD PRIMARY KEY (`scheduleID`),
  ADD KEY `FK_scheduleApplianceID` (`scheduleApplianceID`);

--
-- Indexes for table `tbl_totalconsumption`
--
ALTER TABLE `tbl_totalconsumption`
  ADD PRIMARY KEY (`totalConsID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_appliances`
--
ALTER TABLE `tbl_appliances`
  MODIFY `applianceID` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  MODIFY `logID` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notifID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  MODIFY `scheduleID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_totalconsumption`
--
ALTER TABLE `tbl_totalconsumption`
  MODIFY `totalConsID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_notification_status`
--
ALTER TABLE `tbl_notification_status`
  ADD CONSTRAINT `FK_notifID` FOREIGN KEY (`notifID`) REFERENCES `tbl_notifications` (`notifID`);

--
-- Constraints for table `tbl_schedules`
--
ALTER TABLE `tbl_schedules`
  ADD CONSTRAINT `FK_scheduleApplianceID` FOREIGN KEY (`scheduleApplianceID`) REFERENCES `tbl_appliances` (`applianceID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
