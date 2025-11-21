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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_configurations`
--

LOCK TABLES `business_configurations` WRITE;
/*!40000 ALTER TABLE `business_configurations` DISABLE KEYS */;
INSERT INTO `business_configurations` VALUES (1,'Secureism Pvt Ltd','127.0.0.1','8923980','F3 Center of Technology, Zaraj Society, Islamabad Pakistan','PUNJAB','company/1760937917.svg','SECUREISM (PRIVATE) LIMITED','0010109016750017','0119999','03001234567','ZEESHAN QAMAR','PK44ABPA0010109016750017','ABPAPKKA','ABL CHAKLALA SCHEME 3 RAWALPINDI','0757','25a88e58a92bf1aa5e3261df0c7fcee4','2025-07-04 12:02:41','2025-11-21 07:06:29','tax_bridge','root','Admin','sandbox','2ebe4443-4c22-341f-8f4e-aa4002fcffcb','4443-4c22-341f-8f4e-aa4002fcffcb'),(10,'Telenor','127.0.0.1','7845487','Telenor Business Address','AZAD JAMMU AND KASHMIR','company/1761547341.png','N/A','N/A','N/A','03001234568','Telenor Person','N/A','N/A','N/A','N/A','79ed61574cd2c00ef90188eb4f3a68dd','2025-10-27 06:42:21','2025-10-27 06:42:21','fbr_telenor_29daba_db','root','Admin','sandbox','dfsa',NULL),(11,'PTA','127.0.0.1','9789797','Business Address','BALOCHISTAN','company/1761652595.jpg','N/A','N/A','N/A','6579879779','dsfdsafdsa','N/A','N/A','N/A','N/A','f5f0524f1a86184ac466f0fcb27b85da','2025-10-28 11:56:35','2025-10-28 11:56:35','fbr_pta_8eafa2_db','root','Admin','sandbox','fdsafsad',NULL),(12,'Nayatell','127.0.0.1','3432432','Balochistan','AZAD JAMMU AND KASHMIR','company/1762149840.jpg','N/A','N/A','N/A','243234324234','Muhammad Naseem','N/A','N/A','N/A','N/A','6966d02bb4f127b1c3f870dd363ac214','2025-11-03 06:04:00','2025-11-03 06:04:00','fbr_nayatell_54a9a4_db','root','Admin','sandbox','fdsa',NULL),(13,'PTA','127.0.0.1','3242343','Business Addres','CAPITAL TERRITORY','company/1762150064.jpg','N/A','N/A','N/A','342324234','Muhammad Shawaiz','N/A','N/A','N/A','N/A','e7c157608a7c034de7383e22d06f573e','2025-11-03 06:07:44','2025-11-03 06:07:44','fbr_pta_756a46_db','root','Admin','sandbox','fdsaf',NULL),(14,'ABC Pvt Ltd1',NULL,'8923980','F3 Center of Technology, Zaraj Society, Islamabad Pakistan','PUNJAB','company/1762409205.svg','ABC Pvt Ltd','987654321','REG-2025','03001234567','ZEESHAN QAMAR','PK00HBL0000000000012345',NULL,'ABL CHAKLALA SCHEME 3 RAWALPINDI','0757','d041920c6ac012c89eee3dd64f2267e1','2025-11-06 06:00:26','2025-11-06 06:06:45',NULL,NULL,NULL,'sandbox','2ebe4443-4c22-341f-8f4e-aa4002fcffcb','4443-4c22-341f-8f4e-aa4002fcffcb'),(15,'New',NULL,'1234567','F3 Center of Technology, Zaraj Society, Islamabad Pakistan','PUNJAB','company/1762489333.svg','ABC Pvt','987654321','REG-2025','03001234567','ZEESHAN QAMAR','PK00HBL0000000000012345',NULL,'ABL CHAKLALA SCHEME 3 RAWALPINDI','0757','7952128b5fefe773886de70ab66befd1','2025-11-06 06:01:32','2025-11-07 04:22:13',NULL,NULL,NULL,'sandbox','2ebe4443-4c22-341f-8f4e-aa4002fcffcb','4443-4c22-341f-8f4e-aa4002fcffcb');
/*!40000 ALTER TABLE `business_configurations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_feature_usage`
--

DROP TABLE IF EXISTS `business_feature_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_feature_usage` (
  `business_feature_usage_id` int unsigned NOT NULL AUTO_INCREMENT,
  `business_id` int unsigned NOT NULL,
  `business_package_id` bigint unsigned DEFAULT NULL,
  `feature_key` varchar(100) NOT NULL,
  `period_start_date` date NOT NULL,
  `period_end_date` date NOT NULL,
  `used_count` int unsigned DEFAULT '0',
  PRIMARY KEY (`business_feature_usage_id`),
  KEY `business_id` (`business_id`),
  KEY `feature_key` (`feature_key`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_feature_usage`
--

LOCK TABLES `business_feature_usage` WRITE;
/*!40000 ALTER TABLE `business_feature_usage` DISABLE KEYS */;
INSERT INTO `business_feature_usage` VALUES (1,1,1,'invoices','2025-11-18','2025-12-18',0),(2,1,1,'users','2025-11-18','2025-12-18',0),(3,1,2,'invoices','2025-11-18','2025-12-18',0),(4,1,2,'users','2025-11-18','2025-12-18',0),(5,1,3,'invoices','2025-11-18','2025-12-18',1),(6,1,3,'users','2025-11-18','2025-12-18',0),(7,1,4,'invoices','2025-11-18','2025-12-18',2),(8,1,4,'users','2025-11-18','2025-12-18',0),(9,1,5,'invoices','2025-11-18','2025-12-18',0),(10,1,5,'users','2025-11-18','2025-12-18',0),(11,1,6,'invoices','2025-11-18','2025-12-18',0),(12,1,6,'users','2025-11-18','2025-12-18',0);
/*!40000 ALTER TABLE `business_feature_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_package_features`
--

DROP TABLE IF EXISTS `business_package_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_package_features` (
  `business_package_features_id` int unsigned NOT NULL AUTO_INCREMENT,
  `business_package_id` int unsigned NOT NULL,
  `feature_key` varchar(100) NOT NULL,
  `limit_type` enum('monthly','quarterly','yearly','total') NOT NULL DEFAULT 'monthly',
  `limit_value` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`business_package_features_id`),
  KEY `business_package_id` (`business_package_id`),
  CONSTRAINT `business_package_features_ibfk_1` FOREIGN KEY (`business_package_id`) REFERENCES `business_packages` (`business_packages_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_package_features`
--

LOCK TABLES `business_package_features` WRITE;
/*!40000 ALTER TABLE `business_package_features` DISABLE KEYS */;
INSERT INTO `business_package_features` VALUES (1,1,'invoices','monthly',10),(2,1,'users','total',1),(3,2,'invoices','monthly',10),(4,2,'users','total',1),(5,3,'invoices','monthly',10),(6,3,'users','total',1),(7,4,'invoices','monthly',50),(8,4,'users','total',5),(9,5,'invoices','monthly',50),(10,5,'users','total',5),(11,6,'invoices','monthly',10),(12,6,'users','total',1);
/*!40000 ALTER TABLE `business_package_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `business_packages`
--

DROP TABLE IF EXISTS `business_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `business_packages` (
  `business_packages_id` int unsigned NOT NULL AUTO_INCREMENT,
  `business_id` int unsigned NOT NULL,
  `package_id` int unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `price_after_discout` decimal(15,2) DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `is_trial` tinyint(1) NOT NULL DEFAULT '0',
  `trial_end_date` date DEFAULT NULL,
  PRIMARY KEY (`business_packages_id`),
  KEY `business_id` (`business_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `business_packages_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_packages`
--

LOCK TABLES `business_packages` WRITE;
/*!40000 ALTER TABLE `business_packages` DISABLE KEYS */;
INSERT INTO `business_packages` VALUES (1,1,3,'2025-11-18','2025-12-18',0.00,0.00,0,0,NULL),(2,1,3,'2025-11-18','2025-12-18',0.00,0.00,0,0,NULL),(3,1,3,'2025-11-18','2025-12-18',0.00,0.00,0,0,NULL),(4,1,4,'2025-11-18','2025-12-18',0.00,0.00,1,0,NULL),(5,1,4,'2025-11-18','2025-12-18',0.00,0.00,0,0,NULL),(6,1,3,'2025-11-18','2025-12-18',0.00,0.00,0,0,NULL);
/*!40000 ALTER TABLE `business_packages` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `business_scenarios`
--

LOCK TABLES `business_scenarios` WRITE;
/*!40000 ALTER TABLE `business_scenarios` DISABLE KEYS */;
INSERT INTO `business_scenarios` VALUES (11,1,19,'2025-08-25 07:44:26','2025-08-25 07:44:26'),(16,1,18,'2025-10-07 11:04:00','2025-10-07 11:04:00'),(25,10,1,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(26,10,2,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(27,10,3,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(28,11,2,'2025-10-28 11:56:35','2025-10-28 11:56:35'),(29,11,4,'2025-10-28 11:56:35','2025-10-28 11:56:35'),(30,12,1,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(31,12,2,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(32,12,3,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(33,12,4,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(34,13,1,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(35,13,2,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(36,13,3,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(37,13,4,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(38,14,1,'2025-11-06 06:00:26','2025-11-06 06:00:26'),(39,14,3,'2025-11-06 06:00:26','2025-11-06 06:00:26'),(40,14,7,'2025-11-06 06:00:26','2025-11-06 06:00:26'),(44,15,18,'2025-11-06 06:08:10','2025-11-06 06:08:10'),(45,15,19,'2025-11-06 06:08:16','2025-11-06 06:08:16');
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
INSERT INTO `cache` VALUES ('taxbridge_invoicing_management_system_cache_2fa:pending:9CKXUWeMOLiBhaalWWRjMQLcJ5Gz4joFRzQrQkj58Qt1LjU7qfgBN6lU3tnxUck7','i:2;',1763549812),('taxbridge_invoicing_management_system_cache_2fa:pending:cotXjVSZcwBKOtCkXOI08U0Qiiq7AXlsd8oa8jS69WZx1oCU7ZWlJOGd2AMJRJTY','i:2;',1763550593);
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
-- Table structure for table `package_features`
--

DROP TABLE IF EXISTS `package_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `package_features` (
  `package_features_id` int unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int unsigned NOT NULL,
  `feature_key` varchar(100) NOT NULL,
  `limit_type` enum('monthly','quarterly','yearly','total') NOT NULL DEFAULT 'monthly',
  `limit_value` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`package_features_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `package_features_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`package_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_features`
--

LOCK TABLES `package_features` WRITE;
/*!40000 ALTER TABLE `package_features` DISABLE KEYS */;
INSERT INTO `package_features` VALUES (7,3,'invoices','monthly',10),(8,3,'users','total',1),(9,4,'invoices','monthly',50),(10,4,'users','total',5);
/*!40000 ALTER TABLE `package_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `package_id` int unsigned NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) NOT NULL,
  `package_description` text,
  `package_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `package_billing_cycle` enum('monthly','quarterly','yearly','custom') NOT NULL DEFAULT 'monthly',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (3,'Starter','Starter package for small businesses',1000.00,'monthly','2025-11-17 11:57:58','2025-11-17 11:57:58'),(4,'Standard','Standard Package',2500.00,'monthly','2025-11-18 09:46:57','2025-11-18 09:46:57');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
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
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`(64)),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (10,'App\\Models\\User',2,'api-token','937d120e182f263841cbb4bd2c031f4b51975bfc63804f131de1fb9b76a34274','[\"*\"]','2025-11-07 05:33:35',NULL,'2025-11-07 05:20:10','2025-11-07 05:33:35'),(12,'App\\Models\\User',2,'refresh','ea5fefb2f19b721d2a81688a908967397e12ae6eb1a6d574fd97dab5c1258a87','[\"*\"]',NULL,'2025-12-07 05:46:30','2025-11-07 05:46:30','2025-11-07 05:46:30'),(14,'App\\Models\\User',2,'refresh','b03635de5b419673353110e7212be80a4119709b551f4b58b96717fb277b26af','[\"*\"]',NULL,'2025-12-07 05:49:26','2025-11-07 05:49:26','2025-11-07 05:49:26'),(16,'App\\Models\\User',2,'refresh','17c6ec03c8d81d140163fc570b38403d1a0ff2c815537b9b70b349646998fe71','[\"*\"]',NULL,'2025-12-07 05:51:54','2025-11-07 05:51:54','2025-11-07 05:51:54'),(18,'App\\Models\\User',2,'refresh','adbd8ad10efe99d8811bfed477c803845507dff40d58ffb7a09808f6af7b8c8c','[\"*\"]',NULL,'2025-12-07 06:10:38','2025-11-07 06:10:38','2025-11-07 06:10:38'),(20,'App\\Models\\User',2,'refresh','8ed09abfed17334874bcc06c82f5a788c1d63db72ceaaaee6d27191ba67bb58e','[\"*\"]',NULL,'2025-12-10 04:05:09','2025-11-10 04:05:09','2025-11-10 04:05:09'),(22,'App\\Models\\User',2,'refresh','4aba022db112ac81a5683156e51b3d507fe1f7c7362aa4b67a7b01f053ad1ec0','[\"*\"]',NULL,'2025-12-10 05:41:08','2025-11-10 05:41:08','2025-11-10 05:41:08'),(24,'App\\Models\\User',2,'refresh','c04414c8b0435cae5ac74655be03de3416bf477e8e1aa48a713c8c7abeadc533','[\"*\"]',NULL,'2025-12-10 06:05:39','2025-11-10 06:05:39','2025-11-10 06:05:39'),(26,'App\\Models\\User',2,'refresh','eeb8c321c54f5b5014efcd0eba761813cbc0a03f674b341b57b8cb0ddd322318','[\"*\"]',NULL,'2025-12-10 06:10:36','2025-11-10 06:10:36','2025-11-10 06:10:36'),(28,'App\\Models\\User',2,'refresh','4b046014046fd416e9250b080f3bf3262487f0fdb0d4142183da6e53f5f88d5e','[\"*\"]','2025-11-10 06:19:31','2025-12-10 06:14:59','2025-11-10 06:14:59','2025-11-10 06:19:31'),(31,'App\\Models\\User',2,'refresh','04eed224e6200defa9086ec8cb7c670dd474e144579499179f7bc16ff5a97098','[\"*\"]',NULL,'2025-12-12 11:58:10','2025-11-12 11:58:10','2025-11-12 11:58:10'),(33,'App\\Models\\User',2,'refresh','43666c0a39a1cd0d4540e48520049c9c708d68a11b6b1f3ebab8581c28948d6b','[\"*\"]',NULL,'2025-12-13 10:07:54','2025-11-13 10:07:54','2025-11-13 10:07:54'),(35,'App\\Models\\User',2,'refresh','f19aea4294ba41c2143745ed0a8b13b523d56dff8c83ff73b0904c14ab57599e','[\"*\"]',NULL,'2025-12-13 11:12:06','2025-11-13 11:12:06','2025-11-13 11:12:06'),(37,'App\\Models\\User',2,'refresh','a93c081fc4d23b0b7d5332f8ac6b39fbe5db089b34d1072c3ef3f3f7cac713b6','[\"*\"]',NULL,'2025-12-14 11:17:39','2025-11-14 11:17:39','2025-11-14 11:17:39'),(39,'App\\Models\\User',2,'refresh','f6ae7900d3fd23207f4bcc3026701bd62af29e45fbb4e0d8701ee47e58e36d1b','[\"*\"]',NULL,'2025-12-17 05:14:18','2025-11-17 05:14:18','2025-11-17 05:14:18'),(41,'App\\Models\\User',2,'refresh','0ca050a287ec21ad89473a9e13118ba2a547b5d88cf4f32fcf91d83f1c0e5fef','[\"*\"]',NULL,'2025-12-17 06:40:36','2025-11-17 06:40:36','2025-11-17 06:40:36'),(43,'App\\Models\\User',2,'refresh','dac7756ff85a779d2a296440cdbf3c52fb287dacc6dadc565463221ad35312a0','[\"*\"]',NULL,'2025-12-17 11:49:54','2025-11-17 11:49:54','2025-11-17 11:49:54'),(45,'App\\Models\\User',2,'refresh','e5d0c5667366a50a95d7ceab1d3e7b747a3fc076c8af62983249261b88a7a6bd','[\"*\"]',NULL,'2025-12-19 05:56:25','2025-11-19 05:56:25','2025-11-19 05:56:25'),(47,'App\\Models\\User',2,'refresh','f0e98ab3bd5f96edc9c7d49884ea1b92ffaef7aba047b9788810dd4d701703eb','[\"*\"]',NULL,'2025-12-19 07:48:00','2025-11-19 07:48:00','2025-11-19 07:48:00'),(49,'App\\Models\\User',2,'refresh','72b2ac6d676a447816244ed5e4a4974e2a6b3784eaf84646f1cd62be7368a43e','[\"*\"]',NULL,'2025-12-19 11:05:15','2025-11-19 11:05:15','2025-11-19 11:05:15'),(51,'App\\Models\\User',2,'refresh','9d69f0a0a8997ba07e1e7613eaf9a8c58df9942189069d7c899ac2fde3655745','[\"*\"]',NULL,'2025-12-21 04:14:37','2025-11-21 04:14:37','2025-11-21 04:14:37'),(52,'App\\Models\\User',2,'access','edc443a4ff2355791761a979b6445c89bd633810c2900b931d2681e63ff7a6f0','[\"*\"]','2025-11-21 04:15:35','2025-11-21 05:14:40','2025-11-21 04:14:40','2025-11-21 04:15:35'),(53,'App\\Models\\User',2,'refresh','5053d2ab96a5d4ad2176acb0c1d97ce03cbe5d1428fe5a41af7c4af4c3af531a','[\"*\"]',NULL,'2025-12-21 04:14:40','2025-11-21 04:14:40','2025-11-21 04:14:40');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('57SBcdk5ymof7GhrLbTWKyQKouujXXSuRSQfB4Pw',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFMzaGFrcDdyV3VNWjVYY0NzZHRjMm1obXM4SVBqR3Vkd1ZHZUJ1ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1763716898);
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,1,'Hammad Ali','hammad.ali@f3technologies.eu',NULL,'$2y$12$Gc1yaXcCcNBkB5IUDUelaOcX0/tke.WRq/e5vkEHgzBq1Wn1NPkNC',0,'4XYJ3EBWU5RMAH72','nPY4dHGSb6z2uUKfJPWNBmGOsi9Zrjq34DVqAdokwFttbMmt2U7o79pePgYM','2025-07-02 01:29:39','2025-11-19 11:05:06'),(12,10,'Telenor Name','abdul.wadood@f3technologies.eu',NULL,'$2y$12$AFNxUcTLV09pXQfYeDcEW.DluLc8gpfMfeCrHECDruWKHjCebPpXu',0,NULL,NULL,'2025-10-27 06:42:21','2025-10-27 06:42:21'),(13,11,'Zeeshan Arain','zeeshanarian@f3technologies.eu',NULL,'$2y$12$6YGrURYLgqRHhUDhslMeDO/yp8JSbi5pEGjfR6MTtXix.qCjSBbM6',0,NULL,NULL,'2025-10-28 11:56:35','2025-10-28 11:56:35'),(14,12,'Muhammad Naseem','muhammad.naseem@f3technologies.eu',NULL,'$2y$12$z3vntbsWgytrNr4yW.tRBeJcgC4L0M.wJ4FVmyv.qLcS2eR/pb5ge',0,NULL,NULL,'2025-11-03 06:04:00','2025-11-03 06:04:00'),(15,13,'Muhammad Shawaiz','muhammad.shawaiz@f3technologies.eu',NULL,'$2y$12$WNhiDSf50RY.N7gd1S3/te0M8Oqv/ePuji4ZyWD9iVjTY3Qiu8u.G',0,NULL,NULL,'2025-11-03 06:07:44','2025-11-03 06:07:44'),(18,14,'Abdul Wadood','abdul.wadood1@f3technologies.eu',NULL,'$2y$12$4mqImTDJgr9fZBJ8VsckvuE2Ni/dNJAf9o8fyBjyQLYwxs/TAIL/a',0,NULL,NULL,'2025-11-18 12:21:09','2025-11-18 12:21:09'),(19,14,'Abdul Wadood','abdul.wadood2@f3technologies.eu',NULL,'$2y$12$P3aKyHs3FYB2UEyEagU0Oe4ful4wc8jHvAaVQsEG6mDzvy6NZUZC6',0,NULL,NULL,'2025-11-18 12:24:04','2025-11-18 12:24:04'),(22,14,'Abdul Wadood','abdul.wadood3@f3technologies.eu',NULL,'$2y$12$jZ9oZ2ecOfcvQf587k65Q.9kYb8c1OXzRan7tGSI23V06mChu8HBS',0,NULL,NULL,'2025-11-18 12:24:50','2025-11-18 12:24:50');
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

-- Dump completed on 2025-11-21 14:59:39
