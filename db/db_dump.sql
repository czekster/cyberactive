-- MySQL dump 10.13  Distrib 8.0.26, for Win64 (x86_64)
--
-- Host: localhost    Database: database
-- ------------------------------------------------------
-- Server version	8.0.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `models_objects`
--

DROP TABLE IF EXISTS `models_objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `models_objects` (
  `id_stix_model` int NOT NULL,
  `id_stix_object` int NOT NULL,
  PRIMARY KEY (`id_stix_model`,`id_stix_object`),
  KEY `fk_models_objects_id_model_idx` (`id_stix_model`),
  KEY `fk_models_objects_id_object_idx` (`id_stix_object`),
  CONSTRAINT `fk_models_objects_id_model` FOREIGN KEY (`id_stix_model`) REFERENCES `stix_models` (`id_stix_model`),
  CONSTRAINT `fk_models_objects_id_object` FOREIGN KEY (`id_stix_object`) REFERENCES `stix_objects` (`id_stix_object`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `id_profile` int NOT NULL,
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (1,'Cyber-security managers'),(2,'Network administrators'),(3,'Management'),(4,'Analyst'),(5,'External users');
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stix_models`
--

DROP TABLE IF EXISTS `stix_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stix_models` (
  `id_stix_model` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_date` timestamp NOT NULL,
  `modified_date` timestamp NOT NULL,
  `id_user` int NOT NULL,
  `stix_json_model` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_stix_model`),
  KEY `fk_id_user_stix_models_idx` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stix_objects`
--

DROP TABLE IF EXISTS `stix_objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stix_objects` (
  `id_stix_object` int NOT NULL AUTO_INCREMENT,
  `id_stix_type` int NOT NULL,
  `uuid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_profile` int DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `retracted` tinyint NOT NULL DEFAULT '0',
  `modified_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `json` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_stix_object`),
  KEY `fk_stix_elements_id_type_idx` (`id_stix_type`),
  CONSTRAINT `fk_stix_elements_id_type` FOREIGN KEY (`id_stix_type`) REFERENCES `stix_types` (`id_stix_type`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stix_types`
--

DROP TABLE IF EXISTS `stix_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stix_types` (
  `id_stix_type` int NOT NULL,
  `acronym` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_stix_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stix_types`
--

LOCK TABLES `stix_types` WRITE;
/*!40000 ALTER TABLE `stix_types` DISABLE KEYS */;
INSERT INTO `stix_types` VALUES (1,'SDO','STIX Domain Objects'),(2,'SRO','STIX Relationship Objects'),(3,'SCO','STIX Cyber-observable Objects'),(4,'SBO','STIX Bundle Object');
/*!40000 ALTER TABLE `stix_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_profiles` (
  `id_user_profile` int NOT NULL,
  `description` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_user_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profiles`
--

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;
INSERT INTO `user_profiles` VALUES (1,'Administrators'),(2,'Users');
/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `id_profile` int DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `passwd` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_login` timestamp NOT NULL,
  `id_user_profile` int NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `id_users_UNIQUE` (`id_user`),
  KEY `fk_users_id_group_idx` (`id_profile`),
  KEY `fk_users_id_user_profile_idx` (`id_user_profile`),
  CONSTRAINT `fk_users_id_profile` FOREIGN KEY (`id_profile`) REFERENCES `profiles` (`id_profile`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_users_id_user_profile` FOREIGN KEY (`id_user_profile`) REFERENCES `user_profiles` (`id_user_profile`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_stix_models`
--

DROP TABLE IF EXISTS `users_stix_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_stix_models` (
  `id_user` int NOT NULL,
  `id_stix_model` int NOT NULL,
  PRIMARY KEY (`id_user`,`id_stix_model`),
  KEY `fk_id_user_users_stix_models_idx` (`id_user`),
  KEY `fk_id_stix_model_users_stix_models_idx` (`id_stix_model`),
  CONSTRAINT `fk_id_stix_model_users_stix_models` FOREIGN KEY (`id_stix_model`) REFERENCES `stix_models` (`id_stix_model`),
  CONSTRAINT `fk_id_user_users_stix_models` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-10-26 10:00:15
