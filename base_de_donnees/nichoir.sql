-- MySQL dump 10.13  Distrib 5.7.11, for Win32 (AMD64)
--
-- Host: localhost    Database: nichoir
-- ------------------------------------------------------
-- Server version	5.7.11

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
-- Table structure for table `dht22_ext`
--

DROP TABLE IF EXISTS `dht22_ext`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dht22_ext` (
  `de_pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `de_date_heure` datetime DEFAULT NULL,
  `de_temperature` float DEFAULT NULL,
  `de_humidite` float DEFAULT NULL,
  PRIMARY KEY (`de_pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dht22_ext`
--

LOCK TABLES `dht22_ext` WRITE;
/*!40000 ALTER TABLE `dht22_ext` DISABLE KEYS */;
/*!40000 ALTER TABLE `dht22_ext` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dht22_int`
--

DROP TABLE IF EXISTS `dht22_int`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dht22_int` (
  `di_pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `di_date_heure` datetime DEFAULT NULL,
  `di_temperature` float DEFAULT NULL,
  `di_humidite` float DEFAULT NULL,
  PRIMARY KEY (`di_pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dht22_int`
--

LOCK TABLES `dht22_int` WRITE;
/*!40000 ALTER TABLE `dht22_int` DISABLE KEYS */;
/*!40000 ALTER TABLE `dht22_int` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hx711`
--

DROP TABLE IF EXISTS `hx711`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hx711` (
  `h_pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `h_date_heure` datetime DEFAULT NULL,
  `h_poids` float DEFAULT NULL,
  PRIMARY KEY (`h_pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hx711`
--

LOCK TABLES `hx711` WRITE;
/*!40000 ALTER TABLE `hx711` DISABLE KEYS */;
/*!40000 ALTER TABLE `hx711` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images_infra`
--

DROP TABLE IF EXISTS `images_infra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_infra` (
  `ii_pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `ii_date_heure` datetime DEFAULT NULL,
  `ii_img` longblob,
  PRIMARY KEY (`ii_pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images_infra`
--

LOCK TABLES `images_infra` WRITE;
/*!40000 ALTER TABLE `images_infra` DISABLE KEYS */;
/*!40000 ALTER TABLE `images_infra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images_stand`
--

DROP TABLE IF EXISTS `images_stand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_stand` (
  `is_pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_date_heure` datetime DEFAULT NULL,
  `is_img` longblob,
  PRIMARY KEY (`is_pk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images_stand`
--

LOCK TABLES `images_stand` WRITE;
/*!40000 ALTER TABLE `images_stand` DISABLE KEYS */;
/*!40000 ALTER TABLE `images_stand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `u_pk_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_username` varchar(255) DEFAULT NULL,
  `u_password` varchar(255) DEFAULT NULL,
  `u_sched` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`u_pk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-27 17:58:14
