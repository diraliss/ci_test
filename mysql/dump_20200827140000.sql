-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: localhost    Database: ci_test_database
-- ------------------------------------------------------
-- Server version	5.7.31-0ubuntu0.18.04.1

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
-- Table structure for table `boosterpack`
--

DROP TABLE IF EXISTS `boosterpack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boosterpack` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boosterpack`
--

LOCK TABLES `boosterpack` WRITE;
/*!40000 ALTER TABLE `boosterpack` DISABLE KEYS */;
INSERT INTO `boosterpack` VALUES (1,5.00,0.00,'2020-03-30 00:17:28','2020-08-25 18:08:48'),(2,20.00,8.00,'2020-03-30 00:17:28','2020-08-26 18:32:03'),(3,50.00,15.00,'2020-03-30 00:17:28','2020-08-26 19:00:54');
/*!40000 ALTER TABLE `boosterpack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `assign_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,1,1,'Ну чо ассигн проверим','2020-03-27 21:39:44','2020-08-25 18:08:48',NULL),(2,1,1,'Второй коммент','2020-03-27 21:39:55','2020-08-25 18:08:48',NULL),(3,2,1,'Второй коммент от второго человека','2020-03-27 21:40:22','2020-08-25 18:08:48',NULL),(4,1,1,'Ответ на второй комментарий','2020-08-26 10:14:41','2020-08-26 19:32:07',2),(5,1,1,'Ответ на ответ','2020-08-26 10:18:33','2020-08-26 19:32:27',4),(6,1,1,'qweqwe','2020-08-27 09:52:17','2020-08-27 09:52:17',NULL),(7,1,1,'Ответ на ответ на ответ','2020-08-27 10:05:18','2020-08-27 10:05:18',5),(8,1,1,'Ляляляля-lalalalala','2020-08-27 10:06:41','2020-08-27 10:06:41',NULL),(9,1,1,'Ляляляля-lalalalala @ivz','2020-08-27 10:07:11','2020-08-27 10:07:11',NULL);
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entity_like`
--

DROP TABLE IF EXISTS `entity_like`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entity_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) unsigned NOT NULL,
  `entity_type` varchar(32) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entity_like`
--

LOCK TABLES `entity_like` WRITE;
/*!40000 ALTER TABLE `entity_like` DISABLE KEYS */;
INSERT INTO `entity_like` VALUES (1,1,'post',1,'2020-08-26 19:14:43'),(2,1,'post',1,'2020-08-26 19:14:47'),(3,2,'comment',1,'2020-08-26 19:38:11');
/*!40000 ALTER TABLE `entity_like` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (20200827114800);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `img` varchar(1024) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,1,'Тестовый постик 1','/images/posts/1.png','2018-08-30 13:31:14','2020-08-25 18:08:49'),(2,1,'Печальный пост','/images/posts/2.png','2018-10-11 01:33:27','2020-08-25 18:08:49');
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(7) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `extra` text,
  `amount` varchar(255) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction`
--

LOCK TABLES `transaction` WRITE;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
INSERT INTO `transaction` VALUES (1,'in',1,1,'[]','200','2020-08-26 18:55:45'),(2,'out',1,0,'{\"boosterpack_id\":\"3\",\"db_errors\":{\"code\":0,\"message\":\"\"}}','50','2020-08-26 18:56:53'),(3,'out',1,0,'{\"boosterpack_id\":\"3\",\"db_errors\":{\"code\":0,\"message\":\"\"}}','50','2020-08-26 18:58:27'),(4,'out',1,1,'[]','50','2020-08-26 19:00:15'),(5,'out',1,1,'[]','50','2020-08-26 19:00:54'),(6,'in',1,1,'[]','100','2020-08-26 19:21:12'),(7,'in',1,0,'{\"input\":{\"amount\":\"100\",\"user_id\":1},\"db_errors\":{\"code\":1054,\"message\":\"Unknown column \'walle_balance\' in \'field list\'\"}}','100','2020-08-26 19:21:45'),(8,'in',1,0,'{\"input\":{\"amount\":\"rtkrth\",\"user_id\":1},\"form_errors\":{\"amount\":\"Amount must contain only numbers.\"}}','rtkrth','2020-08-26 20:16:47'),(9,'in',1,0,'{\"input\":{\"amount\":\"rtkrth\",\"user_id\":1},\"form_errors\":{\"amount\":\"Amount must contain only numbers.\"}}','rtkrth','2020-08-26 20:16:53');
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `personaname` varchar(50) NOT NULL DEFAULT '',
  `avatarfull` varchar(150) NOT NULL DEFAULT '',
  `rights` tinyint(4) NOT NULL DEFAULT '0',
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_refilled` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` datetime NOT NULL,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `available_likes` int(10) unsigned DEFAULT '20',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `time_created` (`time_created`),
  KEY `time_updated` (`time_updated`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin@niceadminmail.pl','eyuhHv','AdminProGod','https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/96/967871835afdb29f131325125d4395d55386c07a_full.jpg',0,192.45,402.45,210.00,'2019-07-26 01:53:54','2020-08-26 19:38:11',56),(2,'simpleuser@niceadminmail.pl','q8RYAP','simpleuser','https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/86/86a0c845038332896455a566a1f805660a13609b_full.jpg',0,0.00,0.00,0.00,'2019-07-26 01:53:54','2020-08-25 18:08:49',20);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_auth`
--

DROP TABLE IF EXISTS `users_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(63) DEFAULT NULL,
  `user_agent` text,
  `user_id` int(11) unsigned DEFAULT NULL,
  `state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `extra` text,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_auth`
--

LOCK TABLES `users_auth` WRITE;
/*!40000 ALTER TABLE `users_auth` DISABLE KEYS */;
INSERT INTO `users_auth` VALUES (1,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',1,1,'{\"attempt\":null}','2020-08-27 08:56:42'),(2,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,NULL,'2020-08-27 08:58:21'),(3,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,NULL,'2020-08-27 09:00:38'),(4,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":1,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:01:24'),(5,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":1,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:01:50'),(6,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":2,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:03:55'),(7,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":3,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:04:00'),(8,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":4,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:04:20'),(9,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":5,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:04:22'),(10,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":6,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:04:31'),(11,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":7,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:05:01'),(12,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":8,\"errors\":[\"Authorization attempts ended, try later\"],\"blocked_until\":1598520189}','2020-08-27 09:14:46'),(13,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":9,\"errors\":[\"Authorization attempts ended, try later\"],\"blocked_until\":1598520286}','2020-08-27 09:15:11'),(14,'37.1.82.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.86 YaBrowser/20.8.0.894 Yowser/2.5 Safari/537.36',NULL,0,'{\"attempt\":1,\"errors\":[\"Invalid credentials\"]}','2020-08-27 09:29:00');
/*!40000 ALTER TABLE `users_auth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_boosterpack`
--

DROP TABLE IF EXISTS `users_boosterpack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_boosterpack` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `boosterpack_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `added_likes` int(11) unsigned NOT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_boosterpack`
--

LOCK TABLES `users_boosterpack` WRITE;
/*!40000 ALTER TABLE `users_boosterpack` DISABLE KEYS */;
INSERT INTO `users_boosterpack` VALUES (4,3,1,40,'2020-08-26 19:00:54');
/*!40000 ALTER TABLE `users_boosterpack` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-27 13:59:18
