-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: master_db
-- ------------------------------------------------------
-- Server version	8.0.42

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Super Admin','admin@taxbridge.pk','$2y$12$PfHLRFbUZPo/CO01xWnfCeY9H20mDBTlpvQiX83JNXEdah7Ls45ay',NULL,'2025-10-24 06:14:28','2025-10-24 06:50:33');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_configurations`
--

DROP TABLE IF EXISTS `business_configurations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_configurations` (
  `bus_config_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bus_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bus_ntn_cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_account_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_reg_num` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_contact_num` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_IBAN` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_swift_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bus_acc_branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_acc_branch_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `db_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fbr_env` enum('sandbox','production') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sandbox',
  `fbr_api_token_sandbox` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fbr_api_token_prod` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`bus_config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_configurations`
--

LOCK TABLES `business_configurations` WRITE;
/*!40000 ALTER TABLE `business_configurations` DISABLE KEYS */;
INSERT INTO `business_configurations` VALUES (1,'Secureism Pvt Ltd','127.0.0.1','8923980','F3 Center of Technology, Zaraj Society, Islamabad Pakistan','PUNJAB','company/1760937917.svg','SECUREISM (PRIVATE) LIMITED','0010109016750017','0119999','03001234567','ZEESHAN QAMAR','PK44ABPA0010109016750017','ABPAPKKA','ABL CHAKLALA SCHEME 3 RAWALPINDI','0757','25a88e58a92bf1aa5e3261df0c7fcee4','2025-07-04 12:02:41','2025-10-20 05:26:02','tax_bridge','root','Admin','sandbox','2ebe4443-4c22-341f-8f4e-aa4002fcffcb','4443-4c22-341f-8f4e-aa4002fcffcb'),(10,'Telenor','127.0.0.1','7845487','Telenor Business Address','AZAD JAMMU AND KASHMIR','company/1761547341.png','N/A','N/A','N/A','03001234568','Telenor Person','N/A','N/A','N/A','N/A','79ed61574cd2c00ef90188eb4f3a68dd','2025-10-27 06:42:21','2025-10-27 06:42:21','fbr_telenor_29daba_db','root','Admin','sandbox','dfsa',NULL),(11,'PTA','127.0.0.1','9789797','Business Address','BALOCHISTAN','company/1761652595.jpg','N/A','N/A','N/A','6579879779','dsfdsafdsa','N/A','N/A','N/A','N/A','f5f0524f1a86184ac466f0fcb27b85da','2025-10-28 11:56:35','2025-10-28 11:56:35','fbr_pta_8eafa2_db','root','Admin','sandbox','fdsafsad',NULL),(12,'Nayatell','127.0.0.1','3432432','Balochistan','AZAD JAMMU AND KASHMIR','company/1762149840.jpg','N/A','N/A','N/A','243234324234','Muhammad Naseem','N/A','N/A','N/A','N/A','6966d02bb4f127b1c3f870dd363ac214','2025-11-03 06:04:00','2025-11-03 06:04:00','fbr_nayatell_54a9a4_db','dummy','dummy','sandbox','fdsa',NULL),(13,'PTA','127.0.0.1','3242343','Business Addres','CAPITAL TERRITORY','company/1762150064.jpg','N/A','N/A','N/A','342324234','Muhammad Shawaiz','N/A','N/A','N/A','N/A','e7c157608a7c034de7383e22d06f573e','2025-11-03 06:07:44','2025-11-03 06:07:44','fbr_pta_756a46_db','root','Admin','sandbox','fdsaf',NULL);
/*!40000 ALTER TABLE `business_configurations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_scenarios`
--

DROP TABLE IF EXISTS `business_scenarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_scenarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bus_config_id` bigint unsigned NOT NULL,
  `scenario_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `bus_config_id` (`bus_config_id`),
  KEY `scenario_id` (`scenario_id`),
  CONSTRAINT `business_scenarios_ibfk_1` FOREIGN KEY (`bus_config_id`) REFERENCES `business_configurations` (`bus_config_id`),
  CONSTRAINT `business_scenarios_ibfk_2` FOREIGN KEY (`scenario_id`) REFERENCES `sandbox_scenarios` (`scenario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_scenarios`
--

LOCK TABLES `business_scenarios` WRITE;
/*!40000 ALTER TABLE `business_scenarios` DISABLE KEYS */;
INSERT INTO `business_scenarios` VALUES (11,1,19,'2025-08-25 07:44:26','2025-08-25 07:44:26'),(16,1,18,'2025-10-07 11:04:00','2025-10-07 11:04:00'),(25,10,1,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(26,10,2,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(27,10,3,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(28,11,2,'2025-10-28 11:56:35','2025-10-28 11:56:35'),(29,11,4,'2025-10-28 11:56:35','2025-10-28 11:56:35'),(30,12,1,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(31,12,2,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(32,12,3,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(33,12,4,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(34,13,1,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(35,13,2,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(36,13,3,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(37,13,4,'2025-11-03 06:07:44','2025-11-03 06:07:44');
/*!40000 ALTER TABLE `business_scenarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('taxbridge_invoicing_management_system_cache_5c785c036466adea360111aa28563bfd556b5fba','i:1;',1762150124),('taxbridge_invoicing_management_system_cache_5c785c036466adea360111aa28563bfd556b5fba:timer','i:1762150124;',1762150124);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('hammad.ali@f3technologies.eu','$2y$12$qtS4mZlbaNz1AdQEdifwYeZmScvEBmLVqyRQ.6x6SY4i5EtZMlCsa','2025-10-24 11:10:16');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (2,'admin'),(3,'manager'),(1,'owner'),(4,'staff'),(5,'viewer');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sandbox_scenarios`
--

DROP TABLE IF EXISTS `sandbox_scenarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sandbox_scenarios` (
  `scenario_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `scenario_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scenario_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`scenario_id`),
  UNIQUE KEY `sandbox_scenarios_scenario_code_unique` (`scenario_code`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sandbox_scenarios`
--

LOCK TABLES `sandbox_scenarios` WRITE;
/*!40000 ALTER TABLE `sandbox_scenarios` DISABLE KEYS */;
INSERT INTO `sandbox_scenarios` VALUES (1,'SN001','Goods at standard rate to registered buyers','Goods at standard rate (default)',NULL,NULL),(2,'SN002','Goods at standard rate to unregistered buyers','Goods at standard rate (default)',NULL,NULL),(3,'SN003','Sale of Steel (Melted and Re-Rolled)','Steel Melting and re-rolling',NULL,NULL),(4,'SN004','Sale by Ship Breakers','Ship breaking',NULL,NULL),(5,'SN005','Reduced rate sale','Goods at Reduced Rate',NULL,NULL),(6,'SN006','Exempt goods sale','Exempt Goods',NULL,NULL),(7,'SN007','Zero rated sale','Goods at zero-rate',NULL,NULL),(8,'SN008','Sale of 3rd schedule goods','3rd Schedule Goods',NULL,NULL),(9,'SN009','Cotton Spinners purchase from Cotton Ginners (Textile Sector)','Cotton Ginners',NULL,NULL),(10,'SN010','Mobile Operators adds Sale (Telecom Sector)','Telecommunication services',NULL,NULL),(11,'SN011','Toll Manufacturing sale by Steel sector','Toll Manufacturing',NULL,NULL),(12,'SN012','Sale of Petroleum products','Petroleum Products',NULL,NULL),(13,'SN013','Electricity Supply to Retailers','Electricity Supply to Retailers',NULL,NULL),(14,'SN014','Sale of Gas to CNG stations','Gas to CNG stations',NULL,NULL),(15,'SN015','Sale of mobile phones','Mobile Phones',NULL,NULL),(16,'SN016','Processing / Conversion of Goods','Processing/ Conversion of Goods',NULL,NULL),(17,'SN017','Sale of Goods where FED is charged in ST mode','Goods (FED in ST Mode)',NULL,NULL),(18,'SN018','Sale of Services where FED is charged in ST mode','Services (FED in ST Mode)',NULL,NULL),(19,'SN019','Sale of Services','Services',NULL,NULL),(20,'SN020','Sale of Electric Vehicles','Electric Vehicle',NULL,NULL),(21,'SN021','Sale of Cement /Concrete Block','Cement /Concrete Block',NULL,NULL),(22,'SN022','Sale of Potassium Chlorate','Potassium Chlorate',NULL,NULL),(23,'SN023','Sale of CNG','CNG Sales',NULL,NULL),(24,'SN024','Goods sold that are listed in SRO 297(1)/2023','Goods as per SRO.297(1)/2023',NULL,NULL),(25,'SN025','Drugs sold at fixed ST rate under serial 81 of Eighth Schedule Table 1','Non-Adjustable Supplies',NULL,NULL),(26,'SN026','Sale to End Consumer by retailers','Goods at Standard Rate (default)',NULL,NULL),(27,'SN027','Sale to End Consumer by retailers','3rd Schedule Goods',NULL,NULL),(28,'SN028','Sale to End Consumer by retailers','Goods at Reduced Rate',NULL,NULL);
/*!40000 ALTER TABLE `sandbox_scenarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('9POnVBbiXnlvwug0jVAz61qEBMLKHHbsnmDc5t7S',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDNVbDRtbldiWDRCa0tHWjBPdHlCZFZLZ0RDVmk1N3pHYWcweTNhUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762151072),('iMceCqcKb2nRpfytCFGtQ9HiMrI1Q345VfH6OYaV',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaWRRajI4NUl6YkJOSjY4Slc0ZmE5bW9Mb2NNMXRtM0Z0WWdNRU1zUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1762162160),('u9pcu0qbeFEu8Gm6BL81JGTcx1FF7InmCKnHifo5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSmVQOHhDbXJRT0dsRGVVeUxkcGJLNURJankyeFQ2Rkc1VVE4bW56SiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fX0=',1762150422),('znHBeEmQqHckIhr9bwKf28Ob64l9pusfOHfGKGz3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHVSeUlIc2plRXpxUUZsTnJOOXZ3Z3FlaXpMeWVPbTIySGtzcFk5VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9idXNpbmVzc2VzIjt9czo1MjoibG9naW5fYWRtaW5fNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1762150750);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_business`
--

DROP TABLE IF EXISTS `user_business`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_business` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `bus_config_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_business` (`user_id`,`bus_config_id`),
  KEY `bus_config_id` (`bus_config_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_business_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_business_ibfk_2` FOREIGN KEY (`bus_config_id`) REFERENCES `business_configurations` (`bus_config_id`) ON DELETE CASCADE,
  CONSTRAINT `user_business_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_business`
--

LOCK TABLES `user_business` WRITE;
/*!40000 ALTER TABLE `user_business` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_business` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `twofa_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `twofa_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,1,'Hammad Ali','hammad.ali@f3technologies.eu',NULL,'$2y$12$m.9/VLtJaFqvrBpUzbW9ou89KZf/mP.Y2xj5MqmGoFV6McQDUj.K.',0,'4XYJ3EBWU5RMAH72',NULL,'2025-07-02 01:29:39','2025-10-28 06:59:19'),(12,10,'Telenor Name','abdul.wadood@f3technologies.eu',NULL,'$2y$12$AFNxUcTLV09pXQfYeDcEW.DluLc8gpfMfeCrHECDruWKHjCebPpXu',0,NULL,NULL,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(13,11,'Zeeshan Arain','zeeshanarian@f3technologies.eu',NULL,'$2y$12$6YGrURYLgqRHhUDhslMeDO/yp8JSbi5pEGjfR6MTtXix.qCjSBbM6',0,NULL,NULL,'2025-10-28 11:56:35','2025-10-28 11:56:35'),(14,12,'Muhammad Naseem','muhammad.naseem@f3technologies.eu',NULL,'$2y$12$z3vntbsWgytrNr4yW.tRBeJcgC4L0M.wJ4FVmyv.qLcS2eR/pb5ge',0,NULL,NULL,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(15,13,'Muhammad Shawaiz','muhammad.shawaiz@f3technologies.eu',NULL,'$2y$12$WNhiDSf50RY.N7gd1S3/te0M8Oqv/ePuji4ZyWD9iVjTY3Qiu8u.G',0,NULL,NULL,'2025-11-03 06:07:44','2025-11-03 06:07:44');
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

-- Dump completed on 2025-11-04 15:35:19
