-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: apple_store
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `apple_store`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `apple_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `apple_store`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'iPhone','iphone','Danh mục iPhone dành cho đồ án iStore.',1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(2,'iPad','ipad','Danh mục iPad dành cho đồ án iStore.',1,2,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(3,'iPod','ipod','Danh mục iPod dành cho đồ án iStore.',1,3,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(4,'Phụ kiện sạc','phu-kien-sac','Danh mục Phụ kiện sạc dành cho đồ án iStore.',1,4,'2026-06-09 13:09:14','2026-06-09 13:09:14');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_code` char(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `colors_slug_unique` (`slug`),
  KEY `colors_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'Đen','black','#1F2937',1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(2,'Trắng','white','#F9FAFB',1,2,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(3,'Xanh dương','blue','#2563EB',1,3,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(4,'Hồng','pink','#EC4899',1,4,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(5,'Tím','purple','#7C3AED',1,5,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(6,'Xanh lá','green','#16A34A',1,6,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(7,'Titanium tự nhiên','natural-titanium','#9CA3AF',1,7,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(8,'Titanium đen','black-titanium','#374151',1,8,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(9,'Titanium trắng','white-titanium','#E5E7EB',1,9,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(10,'Titanium xanh sa mạc','desert-titanium','#D6BFA3',1,10,'2026-06-09 13:09:14','2026-06-09 13:09:14');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_06_09_000001_add_access_fields_to_users_table',1),(5,'2026_06_09_000002_add_phone_to_users_table',1),(6,'2026_06_09_000003_add_default_address_to_users_table',1),(7,'2026_06_09_100001_adjust_users_table_columns',1),(8,'2026_06_09_100002_create_categories_table',1),(9,'2026_06_09_100003_create_product_series_table',1),(10,'2026_06_09_100004_create_products_table',1),(11,'2026_06_09_100005_create_product_images_table',1),(12,'2026_06_09_100006_create_colors_table',1),(13,'2026_06_09_100007_create_storage_options_table',1),(14,'2026_06_09_100008_create_product_variants_table',1),(15,'2026_06_09_100009_create_orders_table',1),(16,'2026_06_09_100010_create_order_items_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_variant_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `storage_label` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `line_total` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`),
  KEY `order_items_product_variant_id_index` (`product_variant_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,3,34,'iPhone 16','IP16-BLU-128','Xanh dương','128 GB',19990000,1,19990000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(2,1,4,53,'iPhone 16 Pro','IP16P-PNK-256','Hồng','256 GB',29990000,2,59980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(3,2,1,6,'iPhone 15','IP15-BLU-512','Xanh dương','512 GB',26990000,2,53980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(4,2,2,23,'iPhone 15 Pro','IP15P-PNK-256','Hồng','256 GB',28990000,2,57980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(5,3,5,65,'iPhone 16 Pro Max','IP16PM-BLU-256','Xanh dương','256 GB',33990000,2,67980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(6,3,5,73,'iPhone 16 Pro Max','IP16PM-BTI-128','Titanium đen','128 GB',29990000,1,29990000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(7,4,5,65,'iPhone 16 Pro Max','IP16PM-BLU-256','Xanh dương','256 GB',33990000,2,67980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(8,4,11,103,'Apple 30W USB-C Power Adapter','CHG30W-WHT','Trắng','Không có',790000,2,1580000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(9,5,3,32,'iPhone 16','IP16-BLK-256','Đen','256 GB',23990000,2,47980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(10,5,9,101,'iPod touch (thế hệ 7)','IPOD-128','Xanh dương','128 GB',7990000,2,15980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(11,6,2,29,'iPhone 15 Pro','IP15P-BTI-256','Titanium đen','256 GB',28990000,1,28990000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(12,6,4,51,'iPhone 16 Pro','IP16P-BLU-512','Xanh dương','512 GB',33990000,2,67980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(13,7,1,3,'iPhone 15','IP15-BLK-512','Đen','512 GB',26990000,1,26990000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(14,7,8,91,'iPad Pro 11 inch M4','IPADP11-BLK-256','Đen','256 GB',22990000,2,45980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(15,8,1,2,'iPhone 15','IP15-BLK-256','Đen','256 GB',22990000,1,22990000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(16,8,3,37,'iPhone 16','IP16-PNK-128','Hồng','128 GB',19990000,2,39980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(17,9,4,59,'iPhone 16 Pro','IP16P-BTI-256','Titanium đen','256 GB',29990000,1,29990000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(18,9,10,102,'Apple 20W USB-C Power Adapter','CHG20W-WHT','Trắng','Không có',490000,2,980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(19,10,3,35,'iPhone 16','IP16-BLU-256','Xanh dương','256 GB',23990000,2,47980000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(20,10,3,41,'iPhone 16','IP16-NTI-256','Titanium tự nhiên','256 GB',23990000,1,23990000,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(21,11,4,58,'iPhone 16 Pro','IP16P-BTI-128','Titanium đen','128 GB',25990000,2,51980000,'2026-06-09 14:29:50','2026-06-09 14:29:50'),(22,12,9,100,'iPod touch (thế hệ 7)','IPOD-64','Xanh dương','64 GB',6990000,1,6990000,'2026-06-09 14:32:40','2026-06-09 14:32:40'),(23,12,7,87,'iPad Air M2','IPADAIR-WHT-512','Trắng','512 GB',20990000,2,41980000,'2026-06-09 14:32:40','2026-06-09 14:32:40');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `receiver_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `subtotal` bigint unsigned NOT NULL,
  `shipping_fee` bigint unsigned NOT NULL DEFAULT '0',
  `total_amount` bigint unsigned NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_user_id_index` (`user_id`),
  KEY `orders_order_code_index` (`order_code`),
  KEY `orders_status_index` (`status`),
  KEY `orders_created_at_index` (`created_at`),
  KEY `orders_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-260609-0001',2,'Khách hàng 1','0910000001','TP. Hồ Chí Minh','Quận 1','Phường 17','4134 Phố Hữu',NULL,'cod',79970000,0,79970000,'pending',NULL,NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(2,'ORD-260609-0002',3,'Khách hàng 2','0910000002','TP. Hồ Chí Minh','Quận 2','Phường 10','797 Phố Bích','Nobis harum ex dolor.','cod',111960000,0,111960000,'pending',NULL,NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(3,'ORD-260609-0003',4,'Khách hàng 3','0910000003','TP. Hồ Chí Minh','Quận 3','Phường 11','5409 Phố Kha Nhàn Duyên','Sapiente qui vitae corporis non.','cod',97970000,0,97970000,'confirmed',NULL,NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(4,'ORD-260609-0004',5,'Khách hàng 4','0910000004','TP. Hồ Chí Minh','Quận 4','Phường 4','140 Phố Đan','Eos totam eius nihil enim modi tempora et a.','cod',69560000,0,69560000,'confirmed',NULL,NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(5,'ORD-260609-0005',6,'Khách hàng 5','0910000005','TP. Hồ Chí Minh','Quận 5','Phường 12','28 Phố Cát Khuyên Ninh',NULL,'cod',63960000,0,63960000,'shipping',NULL,NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(6,'ORD-260609-0006',2,'Khách hàng 1','0910000001','TP. Hồ Chí Minh','Quận 6','Phường 6','53 Phố Hoàng Hữu Diệp',NULL,'cod',96970000,0,96970000,'shipping',NULL,NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(7,'ORD-260609-0007',3,'Khách hàng 2','0910000002','TP. Hồ Chí Minh','Quận 7','Phường 15','469 Phố Lâm Nhân Kỷ',NULL,'cod',72970000,0,72970000,'completed',NULL,'2026-06-07 13:09:15','2026-06-09 13:09:15','2026-06-09 13:09:15'),(8,'ORD-260609-0008',4,'Khách hàng 3','0910000003','TP. Hồ Chí Minh','Quận 8','Phường 6','874 Phố Vũ','Commodi rerum magni sint atque magni voluptatibus illum.','cod',62970000,0,62970000,'completed',NULL,'2026-06-07 13:09:15','2026-06-09 13:09:15','2026-06-09 13:09:15'),(9,'ORD-260609-0009',5,'Khách hàng 4','0910000004','TP. Hồ Chí Minh','Quận 9','Phường 17','35 Phố Tiêu Băng Kỷ','Totam dolores nulla corrupti.','cod',30970000,0,30970000,'cancelled','2026-06-08 13:09:15',NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(10,'ORD-260609-0010',6,'Khách hàng 5','0910000005','TP. Hồ Chí Minh','Quận 10','Phường 14','1 Phố Cam',NULL,'cod',71970000,0,71970000,'cancelled','2026-06-08 13:09:15',NULL,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(11,'ORD-260609-0Y986P',7,'Quang','0375431485','Đồng Nai','Tp. Biên Hoà','Hố Nai','9/2 tổ 6 khu phố 3','Đơn hàng đầu tiên nha shop.','cod',51980000,0,51980000,'pending',NULL,NULL,'2026-06-09 14:29:50','2026-06-09 14:29:50'),(12,'ORD-260609-DGBYNR',7,'Quang','0375431485','Đồng Nai','Biên Hoà','Hố Nai','9/2 kp3','Đơn hàng thứ 2','cod',48970000,0,48970000,'pending',NULL,NULL,'2026-06-09 14:32:40','2026-06-09 14:32:40');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_is_primary_index` (`product_id`,`is_primary`),
  KEY `product_images_product_id_sort_order_index` (`product_id`,`sort_order`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_series`
--

DROP TABLE IF EXISTS `product_series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_series` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_year` smallint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_series_slug_unique` (`slug`),
  KEY `product_series_category_id_is_active_index` (`category_id`,`is_active`),
  KEY `product_series_sort_order_index` (`sort_order`),
  CONSTRAINT `product_series_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_series`
--

LOCK TABLES `product_series` WRITE;
/*!40000 ALTER TABLE `product_series` DISABLE KEYS */;
INSERT INTO `product_series` VALUES (1,1,'iPhone 15 Series','iphone-15',2023,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(2,1,'iPhone 16 Series','iphone-16',2024,1,2,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(3,2,'iPad','ipad',2022,1,3,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(4,2,'iPad Air','ipad-air',2024,1,4,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(5,2,'iPad Pro','ipad-pro',2024,1,5,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(6,3,'iPod touch','ipod-touch',2019,1,6,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(7,4,'USB-C Chargers','usb-c-chargers',2023,1,7,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(8,4,'Charging Cables','charging-cables',2023,1,8,'2026-06-09 13:09:14','2026-06-09 13:09:14');
/*!40000 ALTER TABLE `product_series` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `color_id` bigint unsigned DEFAULT NULL,
  `storage_option_id` bigint unsigned DEFAULT NULL,
  `sku` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_price` bigint unsigned DEFAULT NULL,
  `sale_price` bigint unsigned NOT NULL,
  `stock_quantity` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_index` (`product_id`),
  KEY `product_variants_color_id_index` (`color_id`),
  KEY `product_variants_storage_option_id_index` (`storage_option_id`),
  KEY `product_variants_sale_price_index` (`sale_price`),
  KEY `product_variants_is_active_stock_quantity_index` (`is_active`,`stock_quantity`),
  CONSTRAINT `product_variants_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `product_variants_storage_option_id_foreign` FOREIGN KEY (`storage_option_id`) REFERENCES `storage_options` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,1,2,'IP15-BLK-128',19990000,18990000,27,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(2,1,1,3,'IP15-BLK-256',23990000,22990000,27,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(3,1,1,4,'IP15-BLK-512',27990000,26990000,11,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(4,1,3,2,'IP15-BLU-128',19990000,18990000,7,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(5,1,3,3,'IP15-BLU-256',23990000,22990000,28,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(6,1,3,4,'IP15-BLU-512',27990000,26990000,29,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(7,1,4,2,'IP15-PNK-128',19990000,18990000,17,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(8,1,4,3,'IP15-PNK-256',23990000,22990000,17,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(9,1,4,4,'IP15-PNK-512',27990000,26990000,28,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(10,1,7,2,'IP15-NTI-128',19990000,18990000,30,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(11,1,7,3,'IP15-NTI-256',23990000,22990000,23,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(12,1,7,4,'IP15-NTI-512',27990000,26990000,22,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(13,1,8,2,'IP15-BTI-128',19990000,18990000,24,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(14,1,8,3,'IP15-BTI-256',23990000,22990000,27,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(15,1,8,4,'IP15-BTI-512',27990000,26990000,13,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(16,2,1,2,'IP15P-BLK-128',25990000,24990000,22,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(17,2,1,3,'IP15P-BLK-256',29990000,28990000,14,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(18,2,1,4,'IP15P-BLK-512',33990000,32990000,26,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(19,2,3,2,'IP15P-BLU-128',25990000,24990000,19,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(20,2,3,3,'IP15P-BLU-256',29990000,28990000,26,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(21,2,3,4,'IP15P-BLU-512',33990000,32990000,8,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(22,2,4,2,'IP15P-PNK-128',25990000,24990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(23,2,4,3,'IP15P-PNK-256',29990000,28990000,12,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(24,2,4,4,'IP15P-PNK-512',33990000,32990000,14,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(25,2,7,2,'IP15P-NTI-128',25990000,24990000,25,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(26,2,7,3,'IP15P-NTI-256',29990000,28990000,7,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(27,2,7,4,'IP15P-NTI-512',33990000,32990000,15,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(28,2,8,2,'IP15P-BTI-128',25990000,24990000,28,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(29,2,8,3,'IP15P-BTI-256',29990000,28990000,23,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(30,2,8,4,'IP15P-BTI-512',33990000,32990000,19,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(31,3,1,2,'IP16-BLK-128',20990000,19990000,11,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(32,3,1,3,'IP16-BLK-256',24990000,23990000,9,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(33,3,1,4,'IP16-BLK-512',28990000,27990000,10,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(34,3,3,2,'IP16-BLU-128',20990000,19990000,11,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(35,3,3,3,'IP16-BLU-256',24990000,23990000,12,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(36,3,3,4,'IP16-BLU-512',28990000,27990000,5,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(37,3,4,2,'IP16-PNK-128',20990000,19990000,10,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(38,3,4,3,'IP16-PNK-256',24990000,23990000,21,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(39,3,4,4,'IP16-PNK-512',28990000,27990000,27,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(40,3,7,2,'IP16-NTI-128',20990000,19990000,23,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(41,3,7,3,'IP16-NTI-256',24990000,23990000,5,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(42,3,7,4,'IP16-NTI-512',28990000,27990000,12,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(43,3,8,2,'IP16-BTI-128',20990000,19990000,17,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(44,3,8,3,'IP16-BTI-256',24990000,23990000,6,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(45,3,8,4,'IP16-BTI-512',28990000,27990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(46,4,1,2,'IP16P-BLK-128',26990000,25990000,30,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(47,4,1,3,'IP16P-BLK-256',30990000,29990000,24,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(48,4,1,4,'IP16P-BLK-512',34990000,33990000,24,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(49,4,3,2,'IP16P-BLU-128',26990000,25990000,27,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(50,4,3,3,'IP16P-BLU-256',30990000,29990000,17,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(51,4,3,4,'IP16P-BLU-512',34990000,33990000,8,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(52,4,4,2,'IP16P-PNK-128',26990000,25990000,25,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(53,4,4,3,'IP16P-PNK-256',30990000,29990000,24,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(54,4,4,4,'IP16P-PNK-512',34990000,33990000,21,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(55,4,7,2,'IP16P-NTI-128',26990000,25990000,26,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(56,4,7,3,'IP16P-NTI-256',30990000,29990000,15,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(57,4,7,4,'IP16P-NTI-512',34990000,33990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(58,4,8,2,'IP16P-BTI-128',26990000,25990000,17,1,'2026-06-09 13:09:14','2026-06-09 14:29:50'),(59,4,8,3,'IP16P-BTI-256',30990000,29990000,21,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(60,4,8,4,'IP16P-BTI-512',34990000,33990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(61,5,1,2,'IP16PM-BLK-128',30990000,29990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(62,5,1,3,'IP16PM-BLK-256',34990000,33990000,7,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(63,5,1,4,'IP16PM-BLK-512',38990000,37990000,30,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(64,5,3,2,'IP16PM-BLU-128',30990000,29990000,28,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(65,5,3,3,'IP16PM-BLU-256',34990000,33990000,30,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(66,5,3,4,'IP16PM-BLU-512',38990000,37990000,14,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(67,5,4,2,'IP16PM-PNK-128',30990000,29990000,14,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(68,5,4,3,'IP16PM-PNK-256',34990000,33990000,10,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(69,5,4,4,'IP16PM-PNK-512',38990000,37990000,17,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(70,5,7,2,'IP16PM-NTI-128',30990000,29990000,23,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(71,5,7,3,'IP16PM-NTI-256',34990000,33990000,5,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(72,5,7,4,'IP16PM-NTI-512',38990000,37990000,23,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(73,5,8,2,'IP16PM-BTI-128',30990000,29990000,27,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(74,5,8,3,'IP16PM-BTI-256',34990000,33990000,27,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(75,5,8,4,'IP16PM-BTI-512',38990000,37990000,30,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(76,6,1,1,'IPAD10-BLK-64',NULL,9990000,15,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(77,6,1,3,'IPAD10-BLK-256',NULL,12990000,18,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(78,6,2,1,'IPAD10-WHT-64',NULL,9990000,18,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(79,6,2,3,'IPAD10-WHT-256',NULL,12990000,13,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(80,6,3,1,'IPAD10-BLU-64',NULL,9990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(81,6,3,3,'IPAD10-BLU-256',NULL,12990000,13,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(82,7,1,2,'IPADAIR-BLK-128',NULL,14990000,15,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(83,7,1,3,'IPADAIR-BLK-256',NULL,17990000,12,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(84,7,1,4,'IPADAIR-BLK-512',NULL,20990000,17,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(85,7,2,2,'IPADAIR-WHT-128',NULL,14990000,15,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(86,7,2,3,'IPADAIR-WHT-256',NULL,17990000,10,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(87,7,2,4,'IPADAIR-WHT-512',NULL,20990000,6,1,'2026-06-09 13:09:14','2026-06-09 14:32:40'),(88,7,3,2,'IPADAIR-BLU-128',NULL,14990000,18,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(89,7,3,3,'IPADAIR-BLU-256',NULL,17990000,17,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(90,7,3,4,'IPADAIR-BLU-512',NULL,20990000,10,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(91,8,1,3,'IPADP11-BLK-256',NULL,22990000,10,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(92,8,1,4,'IPADP11-BLK-512',NULL,25990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(93,8,1,5,'IPADP11-BLK-1024',NULL,28990000,12,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(94,8,2,3,'IPADP11-WHT-256',NULL,22990000,15,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(95,8,2,4,'IPADP11-WHT-512',NULL,25990000,3,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(96,8,2,5,'IPADP11-WHT-1024',NULL,28990000,15,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(97,8,3,3,'IPADP11-BLU-256',NULL,22990000,20,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(98,8,3,4,'IPADP11-BLU-512',NULL,25990000,19,1,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(99,8,3,5,'IPADP11-BLU-1024',NULL,28990000,19,1,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(100,9,3,1,'IPOD-64',NULL,6990000,6,1,'2026-06-09 13:09:15','2026-06-09 14:32:40'),(101,9,3,2,'IPOD-128',NULL,7990000,4,1,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(102,10,2,NULL,'CHG20W-WHT',NULL,490000,28,1,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(103,11,2,NULL,'CHG30W-WHT',NULL,790000,61,1,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(104,12,2,NULL,'CBL-CL-1M',NULL,450000,20,1,'2026-06-09 13:09:15','2026-06-09 13:09:15'),(105,13,2,NULL,'CBL-CC-1M',NULL,390000,78,1,'2026-06-09 13:09:15','2026-06-09 13:09:15');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `product_series_id` bigint unsigned DEFAULT NULL,
  `name` varchar(160) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `specifications` longtext COLLATE utf8mb4_unicode_ci,
  `release_year` smallint unsigned DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_index` (`category_id`),
  KEY `products_product_series_id_index` (`product_series_id`),
  KEY `products_release_year_index` (`release_year`),
  KEY `products_is_active_is_featured_index` (`is_active`,`is_featured`),
  KEY `products_created_at_index` (`created_at`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `products_product_series_id_foreign` FOREIGN KEY (`product_series_id`) REFERENCES `product_series` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,'iPhone 15','iphone-15','iPhone 15 chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPhone 15. Sản phẩm dùng cho mục đích học tập.',NULL,2023,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(2,1,1,'iPhone 15 Pro','iphone-15-pro','iPhone 15 Pro chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPhone 15 Pro. Sản phẩm dùng cho mục đích học tập.',NULL,2023,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(3,1,2,'iPhone 16','iphone-16','iPhone 16 chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPhone 16. Sản phẩm dùng cho mục đích học tập.',NULL,2024,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(4,1,2,'iPhone 16 Pro','iphone-16-pro','iPhone 16 Pro chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPhone 16 Pro. Sản phẩm dùng cho mục đích học tập.',NULL,2024,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(5,1,2,'iPhone 16 Pro Max','iphone-16-pro-max','iPhone 16 Pro Max chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPhone 16 Pro Max. Sản phẩm dùng cho mục đích học tập.',NULL,2024,0,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(6,2,3,'iPad 10.9 inch','ipad-10-9','iPad 10.9 inch chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPad 10.9 inch. Sản phẩm dùng cho mục đích học tập.',NULL,2022,0,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(7,2,4,'iPad Air M2','ipad-air-m2','iPad Air M2 chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPad Air M2. Sản phẩm dùng cho mục đích học tập.',NULL,2024,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(8,2,5,'iPad Pro 11 inch M4','ipad-pro-11-m4','iPad Pro 11 inch M4 chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPad Pro 11 inch M4. Sản phẩm dùng cho mục đích học tập.',NULL,2024,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(9,3,6,'iPod touch (thế hệ 7)','ipod-touch-gen-7','iPod touch (thế hệ 7) chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho iPod touch (thế hệ 7). Sản phẩm dùng cho mục đích học tập.',NULL,2019,0,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(10,4,7,'Apple 20W USB-C Power Adapter','apple-20w-usb-c-adapter','Apple 20W USB-C Power Adapter chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho Apple 20W USB-C Power Adapter. Sản phẩm dùng cho mục đích học tập.',NULL,2023,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(11,4,7,'Apple 30W USB-C Power Adapter','apple-30w-usb-c-adapter','Apple 30W USB-C Power Adapter chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho Apple 30W USB-C Power Adapter. Sản phẩm dùng cho mục đích học tập.',NULL,2023,0,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(12,4,8,'Cáp USB-C sang Lightning 1m','usb-c-to-lightning-1m','Cáp USB-C sang Lightning 1m chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho Cáp USB-C sang Lightning 1m. Sản phẩm dùng cho mục đích học tập.',NULL,2023,0,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL),(13,4,8,'Cáp USB-C 1m','usb-c-cable-1m','Cáp USB-C 1m chính hãng, bảo hành theo chính sách cửa hàng.','Mô tả demo cho Cáp USB-C 1m. Sản phẩm dùng cho mục đích học tập.',NULL,2023,0,1,'2026-06-09 13:09:14','2026-06-09 13:09:14',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
INSERT INTO `sessions` VALUES ('BEWo0KeXUANXCPyppyKtzWXFAANdDuLGsQAyO6nL',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','eyJfdG9rZW4iOiJGbzR2cHVvRVlNeUkzY1BLTU5RZGhFa0JQWmhBQWdNQ09uU2xLQng0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2FwcGxlLXN0b3JlLXdlYi1hcHAudGVzdFwvY2FydCIsInJvdXRlIjoiY2FydC5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1781016000),('kuRlGNSZphKqwvQ6ZdgsELhVthl3kJi60vAJ9kLl',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','eyJfdG9rZW4iOiJhSkZCYkRmSnVwVEhFYTEyaW13WG9RMUl1U0l3bEJPMlQzZGZKbklLIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvYXBwbGUtc3RvcmUtd2ViLWFwcC50ZXN0Iiwicm91dGUiOiJob21lIn19',1781016884);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storage_options`
--

DROP TABLE IF EXISTS `storage_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `storage_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity_gb` int unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `storage_options_capacity_gb_unique` (`capacity_gb`),
  KEY `storage_options_is_active_sort_order_index` (`is_active`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storage_options`
--

LOCK TABLES `storage_options` WRITE;
/*!40000 ALTER TABLE `storage_options` DISABLE KEYS */;
INSERT INTO `storage_options` VALUES (1,'64 GB',64,1,1,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(2,'128 GB',128,1,2,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(3,'256 GB',256,1,3,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(4,'512 GB',512,1,4,'2026-06-09 13:09:14','2026-06-09 13:09:14'),(5,'1 TB',1024,1,5,'2026-06-09 13:09:14','2026-06-09 13:09:14');
/*!40000 ALTER TABLE `storage_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `default_address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  KEY `users_role_index` (`role`),
  KEY `users_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Quản trị viên iStore','admin@istore.test','0900000001',NULL,'$2y$12$a5agyBYsiy/VyuvRlQaTReYDPShFRD2AuUeIfh8Vc8enh4mqp3YnC',NULL,'2026-06-09 13:09:14','2026-06-09 13:09:14','admin','active',NULL),(2,'Khách hàng 1','customer1@istore.test','0910000001','2026-06-09 13:09:14','$2y$12$7thYoLRjizg8D.5RQzvDNORYJ7ESBOW9s2X5yM19gqPuBa7qWs.rq','XoAy4aKADeIKzu6611NSEBSAnzIW2rDJzkT7FAdILvd25UeAuAyQqxxzWRbS','2026-06-09 13:09:14','2026-06-09 13:09:14','customer','active',NULL),(3,'Khách hàng 2','customer2@istore.test','0910000002','2026-06-09 13:09:14','$2y$12$7thYoLRjizg8D.5RQzvDNORYJ7ESBOW9s2X5yM19gqPuBa7qWs.rq','9NyINyoqr5','2026-06-09 13:09:14','2026-06-09 13:09:14','customer','active',NULL),(4,'Khách hàng 3','customer3@istore.test','0910000003','2026-06-09 13:09:14','$2y$12$7thYoLRjizg8D.5RQzvDNORYJ7ESBOW9s2X5yM19gqPuBa7qWs.rq','sC2NyktVxP','2026-06-09 13:09:14','2026-06-09 13:09:14','customer','active',NULL),(5,'Khách hàng 4','customer4@istore.test','0910000004','2026-06-09 13:09:14','$2y$12$7thYoLRjizg8D.5RQzvDNORYJ7ESBOW9s2X5yM19gqPuBa7qWs.rq','lQSFhBOBUl','2026-06-09 13:09:14','2026-06-09 13:09:14','customer','active',NULL),(6,'Khách hàng 5','customer5@istore.test','0910000005','2026-06-09 13:09:14','$2y$12$7thYoLRjizg8D.5RQzvDNORYJ7ESBOW9s2X5yM19gqPuBa7qWs.rq','mp9fTyZtc4','2026-06-09 13:09:14','2026-06-09 13:09:14','customer','active',NULL),(7,'Quang','quang@example.com','0375431485',NULL,'$2y$12$btQqeXvXrb/e3oTbe3Mgk.ZqaBpTzHfYCvrHy1/xWEdzUpu38AQUi',NULL,'2026-06-09 14:28:29','2026-06-09 14:28:29','customer','active',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'apple_store'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-09 22:03:09
