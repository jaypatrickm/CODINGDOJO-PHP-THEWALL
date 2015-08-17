-- MySQL dump 10.13  Distrib 5.6.19, for osx10.7 (i386)
--
-- Host: 127.0.0.1    Database: codingdojo
-- ------------------------------------------------------
-- Server version	5.5.38

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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `message_id_idx` (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (6,3,20,'Hello Harry!','2015-04-20 00:05:49','2015-04-20 00:05:49'),(7,3,17,'Harry, i love the coding dojo too!','2015-04-20 01:48:17','2015-04-20 01:48:17'),(8,3,13,'Hey Jaypatrick and Hermione! nice to see you on here!','2015-04-20 01:49:12','2015-04-20 01:49:12'),(9,3,21,'Hey don\'t forget about me!','2015-04-20 01:50:07','2015-04-20 01:50:07'),(10,2,21,'Nice Jay!','2015-04-20 01:50:14','2015-04-20 01:50:14'),(11,5,13,'I love you Ron!','2015-04-20 01:50:40','2015-04-20 01:50:40');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (2,19,'Let\'s test with using \"quotes.\" Because now I\'ve added a way to fix this... hopefully.. \" \" \" \" EAT THAT QUOTES!','2015-04-19 23:17:07','2015-04-19 23:17:07'),(3,13,'I\'m harry potter! I love the coding dojo!','2015-04-19 23:21:12','2015-04-19 23:21:12'),(4,20,'It\'s Levi-OOOOH-sa not LevioSAH','2015-04-19 23:23:25','2015-04-19 23:23:25'),(5,21,'Hello everyone, I\'m Ron Weasley!','2015-04-20 01:49:53','2015-04-20 01:49:53'),(7,19,'I make lame jokes!','2015-04-20 08:07:03','2015-04-20 08:07:03');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `salt` varchar(44) NOT NULL,
  `password` varchar(32) NOT NULL,
  `date_of_birth` varchar(45) DEFAULT NULL,
  `profile_picture` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (13,'harry','potter','hpotter@gmail.com','e936dde122d2c95fff439f4720152f7194e761523133','353b071c268105cbd716d85448b7f278','','','2015-04-19 21:04:43','2015-04-19 21:04:43'),(17,'jaypatrick','manalansan','jaypatrickm@gmail.com','0200bc3a271e843af20403fd087b5448b25815ca88c1','a8e0b74b6ff47b1093bf92a69bc7fc9c','11/17/1988','squirtle.jpg','2015-04-19 21:50:02','2015-04-19 21:50:02'),(19,'jay','manalansan','jay@gmail.com','031a27035ad24f06418a8debfc9a00e357a16a6c5258','71d0ee2706f68c85ab50cb3e07b79202','','','2015-04-19 21:57:42','2015-04-19 21:57:42'),(20,'hermione','granger','hgranger@gmail.com','bbeb74533b2a4e263001f703e35bd2a612c7743ccd35','9b7e61d369a521822eaf68b98699f40c','','','2015-04-19 23:21:47','2015-04-19 23:21:47'),(21,'Ron','Weasley','rweasley@yahoo.com','8e042612ddba8f68533fbad426216ced3db3fe9afe76','12d9795e6ecaf44b7cc470d4736e6a49','','','2015-04-20 01:49:36','2015-04-20 01:49:36');
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

-- Dump completed on 2015-04-20  8:56:10
