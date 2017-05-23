-- MySQL dump 10.13  Distrib 5.7.14, for Win64 (x86_64)
--
-- Host: localhost    Database: scheduler
-- ------------------------------------------------------
-- Server version	5.7.14

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `date_time` datetime DEFAULT NULL,
  `place` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `addr_street` varchar(50) DEFAULT NULL,
  `addr_street2` varchar(50) DEFAULT NULL,
  `addr_city` varchar(20) DEFAULT NULL,
  `addr_zipcode` varchar(10) DEFAULT NULL,
  `addr_province` varchar(20) DEFAULT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES ('2016-12-02 01:07:00','Everyone future','643534','','','Kelowna','V1Y 9K9','BC',17),('2016-12-01 01:08:00','Doctor','','','','','','',19),('2016-12-01 13:08:00','School','','','','','','',20);
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logins`
--

DROP TABLE IF EXISTS `logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logins` (
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logins`
--

LOCK TABLES `logins` WRITE;
/*!40000 ALTER TABLE `logins` DISABLE KEYS */;
INSERT INTO `logins` VALUES ('JDoee','jdoe@gmail.com','*D37C49F9CBEFBF8B6F4B165AC703AA271E079004');
/*!40000 ALTER TABLE `logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_appts`
--

DROP TABLE IF EXISTS `person_appts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person_appts` (
  `name` varchar(50) NOT NULL,
  `login_email` varchar(100) NOT NULL,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`name`,`login_email`,`app_id`),
  KEY `appt_fk` (`app_id`),
  CONSTRAINT `appt_fk` FOREIGN KEY (`app_id`) REFERENCES `appointments` (`ID`),
  CONSTRAINT `person_fk` FOREIGN KEY (`name`, `login_email`) REFERENCES `persons` (`name`, `login_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person_appts`
--

LOCK TABLES `person_appts` WRITE;
/*!40000 ALTER TABLE `person_appts` DISABLE KEYS */;
INSERT INTO `person_appts` VALUES ('Darryl','jdoe@gmail.com',17),('Jordan','jdoe@gmail.com',17),('Josh','jdoe@gmail.com',17),('Myself','jdoe@gmail.com',17),('Darryl','jdoe@gmail.com',19),('Myself','jdoe@gmail.com',19),('Jordan','jdoe@gmail.com',20);
/*!40000 ALTER TABLE `person_appts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persons`
--

DROP TABLE IF EXISTS `persons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persons` (
  `name` varchar(50) NOT NULL,
  `relation` varchar(20) DEFAULT NULL,
  `login_email` varchar(100) NOT NULL,
  PRIMARY KEY (`name`,`login_email`),
  KEY `login_email` (`login_email`),
  CONSTRAINT `persons_logins_fk` FOREIGN KEY (`login_email`) REFERENCES `logins` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persons`
--

LOCK TABLES `persons` WRITE;
/*!40000 ALTER TABLE `persons` DISABLE KEYS */;
INSERT INTO `persons` VALUES ('Darryl','Dad','jdoe@gmail.com'),('Jordan','son','jdoe@gmail.com'),('Josh','Boy','jdoe@gmail.com'),('Myself',NULL,'jdoe@gmail.com');
/*!40000 ALTER TABLE `persons` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-01  2:06:26
