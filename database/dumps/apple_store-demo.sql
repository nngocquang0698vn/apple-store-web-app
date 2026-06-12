-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: apple_store
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'iPhone','iphone','Danh mß╗Ñc iPhone d├ánh cho ─æß╗ô ├ín iStore.',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(2,'iPad','ipad','Danh mß╗Ñc iPad d├ánh cho ─æß╗ô ├ín iStore.',1,2,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(3,'Phß╗Ñ kiß╗çn','phu-kien','Danh mß╗Ñc Phß╗Ñ kiß╗çn d├ánh cho ─æß╗ô ├ín iStore.',1,3,'2026-06-12 16:42:08','2026-06-12 16:42:08');
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
INSERT INTO `colors` VALUES (1,'─Éen','black','#1F2937',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(2,'Trß║»ng','white','#F9FAFB',1,2,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(3,'Xanh d╞░╞íng','blue','#2563EB',1,3,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(4,'Hß╗ông','pink','#EC4899',1,4,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(5,'T├¡m','purple','#7C3AED',1,5,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(6,'Xanh l├í','green','#16A34A',1,6,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(7,'Titanium tß╗▒ nhi├¬n','natural-titanium','#9CA3AF',1,7,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(8,'Titanium ─æen','black-titanium','#374151',1,8,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(9,'Titanium trß║»ng','white-titanium','#E5E7EB',1,9,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(10,'Titanium xanh sa mß║íc','desert-titanium','#D6BFA3',1,10,'2026-06-12 16:42:08','2026-06-12 16:42:08');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,7,'iPhone 15','IP15-PNK-128','Hß╗ông','128 GB',18990000,1,18990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(2,1,3,19,'iPhone 16','IP16-BLK-128','─Éen','128 GB',19990000,1,19990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(3,2,11,69,'C├íp USB-C sang Lightning 1m','CBL-CL-1M','Trß║»ng','Kh├┤ng c├│',450000,1,450000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(4,2,13,71,'AirPods (thß║┐ hß╗ç 3)','APOD3-WHT','Trß║»ng','Kh├┤ng c├│',4290000,1,4290000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(5,3,1,6,'iPhone 15','IP15-BLU-512','Xanh d╞░╞íng','512 GB',26990000,1,26990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(6,3,4,36,'iPhone 16 Pro','IP16P-WTI-512','Titanium trß║»ng','512 GB',33990000,1,33990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(7,4,4,34,'iPhone 16 Pro','IP16P-WTI-128','Titanium trß║»ng','128 GB',25990000,2,51980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(8,4,5,37,'iPhone 16 Pro Max','IP16PM-BTI-128','Titanium ─æen','128 GB',29990000,2,59980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(9,5,2,18,'iPhone 15 Pro','IP15P-WTI-512','Titanium trß║»ng','512 GB',32990000,2,65980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(10,5,3,20,'iPhone 16','IP16-BLK-256','─Éen','256 GB',23990000,1,23990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(11,6,2,12,'iPhone 15 Pro','IP15P-NTI-512','Titanium tß╗▒ nhi├¬n','512 GB',32990000,2,65980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(12,6,6,47,'iPad 10.9 inch','IPAD10-BLU-256','Xanh d╞░╞íng','256 GB',12990000,1,12990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(13,7,1,7,'iPhone 15','IP15-PNK-128','Hß╗ông','128 GB',18990000,1,18990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(14,7,3,21,'iPhone 16','IP16-BLK-512','─Éen','512 GB',27990000,2,55980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(15,8,7,57,'iPad Air M2','IPADAIR-PUR-512','T├¡m','512 GB',20990000,1,20990000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(16,8,9,67,'Apple 20W USB-C Power Adapter','CHG20W-WHT','Trß║»ng','Kh├┤ng c├│',490000,2,980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(17,9,8,66,'iPad Pro 11 inch M4','IPADP11-WHT-1024','Trß║»ng','1 TB',28990000,2,57980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(18,9,14,72,'AirPods Pro (thß║┐ hß╗ç 2)','APODP2-WHT','Trß║»ng','Kh├┤ng c├│',5990000,2,11980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(19,10,2,16,'iPhone 15 Pro','IP15P-WTI-128','Titanium trß║»ng','128 GB',24990000,2,49980000,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(20,10,8,65,'iPad Pro 11 inch M4','IPADP11-WHT-512','Trß║»ng','512 GB',25990000,1,25990000,'2026-06-12 16:42:09','2026-06-12 16:42:09');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-260612-0001',2,'Kh├ích h├áng 1','0910000001','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 1','Ph╞░ß╗¥ng 16','4083 Phß╗æ Mß╗Öc Nhi├¬n ─É─âng','Eius dolore blanditiis ullam quia.','cod',38980000,0,38980000,'pending',NULL,NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(2,'ORD-260612-0002',3,'Kh├ích h├áng 2','0910000002','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 2','Ph╞░ß╗¥ng 11','60 Phß╗æ Hß╗ông','Eum non id quis ipsum.','cod',4740000,30000,4770000,'pending',NULL,NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(3,'ORD-260612-0003',4,'Kh├ích h├áng 3','0910000003','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 3','Ph╞░ß╗¥ng 12','39 Phß╗æ ─Éß║╖ng','Qui libero sunt voluptatem sequi.','cod',60980000,0,60980000,'confirmed',NULL,NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(4,'ORD-260612-0004',5,'Kh├ích h├áng 4','0910000004','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 4','Ph╞░ß╗¥ng 4','228 Phß╗æ S╞ín',NULL,'cod',111960000,0,111960000,'confirmed',NULL,NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(5,'ORD-260612-0005',6,'Kh├ích h├áng 5','0910000005','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 5','Ph╞░ß╗¥ng 1','54 Phß╗æ D╞░','Perferendis dolor et ut dolor nihil atque.','cod',89970000,0,89970000,'shipping',NULL,NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(6,'ORD-260612-0006',2,'Kh├ích h├áng 1','0910000001','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 6','Ph╞░ß╗¥ng 17','12 Phß╗æ Ng├┤ Ph╞░ß╗¢c D├ón','Et tempora enim excepturi et dolorem.','cod',78970000,0,78970000,'shipping',NULL,NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(7,'ORD-260612-0007',3,'Kh├ích h├áng 2','0910000002','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 7','Ph╞░ß╗¥ng 18','46 Phß╗æ Thi',NULL,'cod',74970000,0,74970000,'completed',NULL,'2026-06-10 16:42:09','2026-06-12 16:42:09','2026-06-12 16:42:09'),(8,'ORD-260612-0008',4,'Kh├ích h├áng 3','0910000003','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 8','Ph╞░ß╗¥ng 9','99 Phß╗æ M├ú ─É├┤n Hß║ính',NULL,'cod',21970000,0,21970000,'completed',NULL,'2026-06-10 16:42:09','2026-06-12 16:42:09','2026-06-12 16:42:09'),(9,'ORD-260612-0009',5,'Kh├ích h├áng 4','0910000004','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 9','Ph╞░ß╗¥ng 11','557 Phß╗æ V─ân','Facere eaque omnis placeat quaerat eos et numquam.','cod',69960000,0,69960000,'cancelled','2026-06-11 16:42:09',NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(10,'ORD-260612-0010',6,'Kh├ích h├áng 5','0910000005','TP. Hß╗ô Ch├¡ Minh','Quß║¡n 10','Ph╞░ß╗¥ng 16','631 Phß╗æ B├í',NULL,'cod',75970000,0,75970000,'cancelled','2026-06-11 16:42:09',NULL,'2026-06-12 16:42:09','2026-06-12 16:42:09');
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
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'products/demo/iphone-15-black.webp','iPhone 15 m├áu ─æen',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(2,1,'products/demo/iphone-15-pink.webp','iPhone 15 m├áu hß╗ông',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(3,1,'products/demo/iphone-15-blue.webp','iPhone 15 m├áu xanh d╞░╞íng',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(4,1,'products/demo/iphone-15-view-1.webp','iPhone 15 g├│c chß╗Ñp 1',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(5,1,'products/demo/iphone-15-view-2.webp','iPhone 15 g├│c chß╗Ñp 2',5,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(6,1,'products/demo/iphone-15-view-3.webp','iPhone 15 g├│c chß╗Ñp 3',6,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(7,2,'products/demo/iphone-15-pro-natural-titanium.webp','iPhone 15 Pro m├áu Titanium tß╗▒ nhi├¬n',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(8,2,'products/demo/iphone-15-pro-black-titanium.webp','iPhone 15 Pro m├áu Titanium ─æen',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(9,2,'products/demo/iphone-15-pro-white-titanium.webp','iPhone 15 Pro m├áu Titanium trß║»ng',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(10,2,'products/demo/iphone-15-pro-view-1.webp','iPhone 15 Pro g├│c chß╗Ñp 1',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(11,2,'products/demo/iphone-15-pro-view-2.webp','iPhone 15 Pro g├│c chß╗Ñp 2',5,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(12,2,'products/demo/iphone-15-pro-view-3.webp','iPhone 15 Pro g├│c chß╗Ñp 3',6,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(13,3,'products/demo/iphone-16-black.webp','iPhone 16 m├áu ─æen',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(14,3,'products/demo/iphone-16-pink.webp','iPhone 16 m├áu hß╗ông',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(15,3,'products/demo/iphone-16-blue.webp','iPhone 16 m├áu xanh d╞░╞íng',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(16,3,'products/demo/iphone-16-view-1.webp','iPhone 16 g├│c chß╗Ñp 1',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(17,3,'products/demo/iphone-16-view-2.webp','iPhone 16 g├│c chß╗Ñp 2',5,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(18,3,'products/demo/iphone-16-view-3.webp','iPhone 16 g├│c chß╗Ñp 3',6,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(19,4,'products/demo/iphone-16-pro-black-titanium.webp','iPhone 16 Pro m├áu Titanium ─æen',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(20,4,'products/demo/iphone-16-pro-natural-titanium.webp','iPhone 16 Pro m├áu Titanium tß╗▒ nhi├¬n',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(21,4,'products/demo/iphone-16-pro-white-titanium.webp','iPhone 16 Pro m├áu Titanium trß║»ng',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(22,4,'products/demo/iphone-16-pro-view-1.webp','iPhone 16 Pro g├│c chß╗Ñp 1',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(23,4,'products/demo/iphone-16-pro-view-2.webp','iPhone 16 Pro g├│c chß╗Ñp 2',5,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(24,4,'products/demo/iphone-16-pro-view-3.webp','iPhone 16 Pro g├│c chß╗Ñp 3',6,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(25,5,'products/demo/iphone-16-pro-max-black-titanium.webp','iPhone 16 Pro Max m├áu Titanium ─æen',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(26,5,'products/demo/iphone-16-pro-max-natural-titanium.webp','iPhone 16 Pro Max m├áu Titanium tß╗▒ nhi├¬n',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(27,5,'products/demo/iphone-16-pro-max-desert-titanium.webp','iPhone 16 Pro Max m├áu Titanium sa mß║íc',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(28,5,'products/demo/iphone-16-pro-max-view-1.webp','iPhone 16 Pro Max g├│c chß╗Ñp 1',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(29,5,'products/demo/iphone-16-pro-max-view-2.webp','iPhone 16 Pro Max g├│c chß╗Ñp 2',5,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(30,5,'products/demo/iphone-16-pro-max-view-3.webp','iPhone 16 Pro Max g├│c chß╗Ñp 3',6,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(31,6,'products/demo/ipad-10-9-blue.webp','iPad 10.9 inch m├áu xanh d╞░╞íng',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(32,6,'products/demo/ipad-10-9-pink.webp','iPad 10.9 inch m├áu hß╗ông',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(33,6,'products/demo/ipad-10-9-white.webp','iPad 10.9 inch m├áu trß║»ng',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(34,6,'products/demo/ipad-10-9-view-1.webp','iPad 10.9 inch g├│c chß╗Ñp 1',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(35,7,'products/demo/ipad-air-m2-blue.webp','iPad Air M2 m├áu xanh d╞░╞íng',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(36,7,'products/demo/ipad-air-m2-purple.webp','iPad Air M2 m├áu t├¡m',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(37,7,'products/demo/ipad-air-m2-white.webp','iPad Air M2 m├áu trß║»ng',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(38,7,'products/demo/ipad-air-m2-view-1.webp','iPad Air M2 g├│c chß╗Ñp 1',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(39,7,'products/demo/ipad-air-m2-view-2.webp','iPad Air M2 g├│c chß╗Ñp 2',5,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(40,7,'products/demo/ipad-air-m2-view-3.webp','iPad Air M2 g├│c chß╗Ñp 3',6,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(41,8,'products/demo/ipad-pro-11-m4-black.webp','iPad Pro 11 inch M4 m├áu ─æen',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(42,8,'products/demo/ipad-pro-11-m4-white.webp','iPad Pro 11 inch M4 m├áu trß║»ng',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(43,8,'products/demo/ipad-pro-11-m4-view-1.webp','iPad Pro 11 inch M4 g├│c chß╗Ñp 1',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(44,8,'products/demo/ipad-pro-11-m4-view-2.webp','iPad Pro 11 inch M4 g├│c chß╗Ñp 2',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(45,8,'products/demo/ipad-pro-11-m4-view-3.webp','iPad Pro 11 inch M4 g├│c chß╗Ñp 3',5,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(46,9,'products/demo/apple-20w-usb-c-adapter.webp','Apple 20W USB-C Power Adapter',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(47,9,'products/demo/apple-20w-usb-c-adapter-view-1.webp','Apple 20W USB-C Power Adapter g├│c chß╗Ñp 1',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(48,10,'products/demo/apple-30w-usb-c-adapter.webp','Apple 30W USB-C Power Adapter',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(49,10,'products/demo/apple-30w-usb-c-adapter-view-1.webp','Apple 30W USB-C Power Adapter g├│c chß╗Ñp 1',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(50,10,'products/demo/apple-30w-usb-c-adapter-view-2.webp','Apple 30W USB-C Power Adapter g├│c chß╗Ñp 2',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(51,11,'products/demo/usb-c-to-lightning-1m.webp','C├íp USB-C sang Lightning 1m',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(52,11,'products/demo/usb-c-to-lightning-1m-view-1.webp','C├íp USB-C sang Lightning 1m g├│c chß╗Ñp 1',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(53,11,'products/demo/usb-c-to-lightning-1m-view-2.webp','C├íp USB-C sang Lightning 1m g├│c chß╗Ñp 2',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(54,12,'products/demo/usb-c-cable-1m.webp','C├íp USB-C 1m',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(55,13,'products/demo/airpods-3.webp','AirPods (thß║┐ hß╗ç 3)',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(56,13,'products/demo/airpods-3-view-1.webp','AirPods (thß║┐ hß╗ç 3) g├│c chß╗Ñp 1',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(57,14,'products/demo/airpods-pro-2.webp','AirPods Pro (thß║┐ hß╗ç 2)',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(58,14,'products/demo/airpods-pro-2-view-1.webp','AirPods Pro (thß║┐ hß╗ç 2) g├│c chß╗Ñp 1',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(59,14,'products/demo/airpods-pro-2-view-2.webp','AirPods Pro (thß║┐ hß╗ç 2) g├│c chß╗Ñp 2',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(60,14,'products/demo/airpods-pro-2-view-3.webp','AirPods Pro (thß║┐ hß╗ç 2) g├│c chß╗Ñp 3',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(61,15,'products/demo/airpods-max-black.webp','AirPods Max m├áu ─æen',1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(62,15,'products/demo/airpods-max-blue.webp','AirPods Max m├áu xanh d╞░╞íng',2,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(63,15,'products/demo/airpods-max-view-1.webp','AirPods Max g├│c chß╗Ñp 1',3,0,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(64,15,'products/demo/airpods-max-view-2.webp','AirPods Max g├│c chß╗Ñp 2',4,0,'2026-06-12 16:42:08','2026-06-12 16:42:08');
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
INSERT INTO `product_series` VALUES (1,1,'iPhone 15 Series','iphone-15',2023,1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(2,1,'iPhone 16 Series','iphone-16',2024,1,2,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(3,2,'iPad','ipad',2022,1,3,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(4,2,'iPad Air','ipad-air',2024,1,4,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(5,2,'iPad Pro','ipad-pro',2024,1,5,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(6,3,'USB-C Chargers','usb-c-chargers',2023,1,6,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(7,3,'Charging Cables','charging-cables',2023,1,7,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(8,3,'AirPods','airpods',2024,1,8,'2026-06-12 16:42:08','2026-06-12 16:42:08');
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
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,1,2,'IP15-BLK-128',19990000,18990000,16,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(2,1,1,3,'IP15-BLK-256',23990000,22990000,12,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(3,1,1,4,'IP15-BLK-512',27990000,26990000,8,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(4,1,3,2,'IP15-BLU-128',19990000,18990000,19,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(5,1,3,3,'IP15-BLU-256',23990000,22990000,20,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(6,1,3,4,'IP15-BLU-512',27990000,26990000,13,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(7,1,4,2,'IP15-PNK-128',19990000,18990000,7,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(8,1,4,3,'IP15-PNK-256',23990000,22990000,14,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(9,1,4,4,'IP15-PNK-512',27990000,26990000,8,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(10,2,7,2,'IP15P-NTI-128',25990000,24990000,11,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(11,2,7,3,'IP15P-NTI-256',29990000,28990000,14,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(12,2,7,4,'IP15P-NTI-512',33990000,32990000,25,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(13,2,8,2,'IP15P-BTI-128',25990000,24990000,12,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(14,2,8,3,'IP15P-BTI-256',29990000,28990000,19,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(15,2,8,4,'IP15P-BTI-512',33990000,32990000,22,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(16,2,9,2,'IP15P-WTI-128',25990000,24990000,27,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(17,2,9,3,'IP15P-WTI-256',29990000,28990000,10,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(18,2,9,4,'IP15P-WTI-512',33990000,32990000,11,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(19,3,1,2,'IP16-BLK-128',20990000,19990000,7,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(20,3,1,3,'IP16-BLK-256',24990000,23990000,20,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(21,3,1,4,'IP16-BLK-512',28990000,27990000,19,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(22,3,3,2,'IP16-BLU-128',20990000,19990000,15,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(23,3,3,3,'IP16-BLU-256',24990000,23990000,25,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(24,3,3,4,'IP16-BLU-512',28990000,27990000,12,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(25,3,4,2,'IP16-PNK-128',20990000,19990000,11,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(26,3,4,3,'IP16-PNK-256',24990000,23990000,5,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(27,3,4,4,'IP16-PNK-512',28990000,27990000,17,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(28,4,8,2,'IP16P-BTI-128',26990000,25990000,24,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(29,4,8,3,'IP16P-BTI-256',30990000,29990000,8,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(30,4,8,4,'IP16P-BTI-512',34990000,33990000,22,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(31,4,7,2,'IP16P-NTI-128',26990000,25990000,17,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(32,4,7,3,'IP16P-NTI-256',30990000,29990000,21,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(33,4,7,4,'IP16P-NTI-512',34990000,33990000,9,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(34,4,9,2,'IP16P-WTI-128',26990000,25990000,7,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(35,4,9,3,'IP16P-WTI-256',30990000,29990000,19,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(36,4,9,4,'IP16P-WTI-512',34990000,33990000,21,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(37,5,8,2,'IP16PM-BTI-128',30990000,29990000,10,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(38,5,8,3,'IP16PM-BTI-256',34990000,33990000,28,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(39,5,8,4,'IP16PM-BTI-512',38990000,37990000,13,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(40,5,7,2,'IP16PM-NTI-128',30990000,29990000,18,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(41,5,7,3,'IP16PM-NTI-256',34990000,33990000,25,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(42,5,7,4,'IP16PM-NTI-512',38990000,37990000,25,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(43,5,10,2,'IP16PM-DTI-128',30990000,29990000,13,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(44,5,10,3,'IP16PM-DTI-256',34990000,33990000,26,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(45,5,10,4,'IP16PM-DTI-512',38990000,37990000,7,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(46,6,3,1,'IPAD10-BLU-64',NULL,9990000,6,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(47,6,3,3,'IPAD10-BLU-256',NULL,12990000,17,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(48,6,4,1,'IPAD10-PNK-64',NULL,9990000,4,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(49,6,4,3,'IPAD10-PNK-256',NULL,12990000,15,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(50,6,2,1,'IPAD10-WHT-64',NULL,9990000,18,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(51,6,2,3,'IPAD10-WHT-256',NULL,12990000,7,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(52,7,3,2,'IPADAIR-BLU-128',NULL,14990000,12,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(53,7,3,3,'IPADAIR-BLU-256',NULL,17990000,19,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(54,7,3,4,'IPADAIR-BLU-512',NULL,20990000,7,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(55,7,5,2,'IPADAIR-PUR-128',NULL,14990000,17,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(56,7,5,3,'IPADAIR-PUR-256',NULL,17990000,16,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(57,7,5,4,'IPADAIR-PUR-512',NULL,20990000,20,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(58,7,2,2,'IPADAIR-WHT-128',NULL,14990000,16,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(59,7,2,3,'IPADAIR-WHT-256',NULL,17990000,4,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(60,7,2,4,'IPADAIR-WHT-512',NULL,20990000,14,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(61,8,1,3,'IPADP11-BLK-256',NULL,22990000,3,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(62,8,1,4,'IPADP11-BLK-512',NULL,25990000,20,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(63,8,1,5,'IPADP11-BLK-1024',NULL,28990000,7,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(64,8,2,3,'IPADP11-WHT-256',NULL,22990000,14,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(65,8,2,4,'IPADP11-WHT-512',NULL,25990000,4,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(66,8,2,5,'IPADP11-WHT-1024',NULL,28990000,5,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(67,9,2,NULL,'CHG20W-WHT',NULL,490000,51,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(68,10,2,NULL,'CHG30W-WHT',NULL,790000,75,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(69,11,2,NULL,'CBL-CL-1M',NULL,450000,31,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(70,12,2,NULL,'CBL-CC-1M',NULL,390000,27,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(71,13,2,NULL,'APOD3-WHT',NULL,4290000,26,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(72,14,2,NULL,'APODP2-WHT',NULL,5990000,16,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(73,15,1,NULL,'APODMAX-BLK',13990000,12990000,8,1,'2026-06-12 16:42:09','2026-06-12 16:42:09'),(74,15,3,NULL,'APODMAX-BLU',13990000,12990000,15,1,'2026-06-12 16:42:09','2026-06-12 16:42:09');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,'iPhone 15','iphone-15','iPhone 15 ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPhone 15 l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Hiß╗çu n─âng mß║ính mß║╜</li><li>Camera n├óng cß║Ñp</li><li>Pin bß╗ün</li><li>iOS mß╗¢i nhß║Ñt</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPhone 15</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/iphone-15-black.webp\" alt=\"iPhone 15\" loading=\"lazy\"></p>','Chipset: Apple A16 Bionic\nM├án h├¼nh: 6.1 inch Super Retina XDR OLED\nCamera sau: 48MP + 12MP\nCamera tr╞░ß╗¢c: 12MP TrueDepth\nPin: L├¬n ─æß║┐n 20 giß╗¥ xem video\nCß╗òng sß║íc: USB-C\nHß╗ç ─æiß╗üu h├ánh: iOS',2023,1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08',NULL),(2,1,1,'iPhone 15 Pro','iphone-15-pro','iPhone 15 Pro ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPhone 15 Pro l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Hiß╗çu n─âng mß║ính mß║╜</li><li>Camera n├óng cß║Ñp</li><li>Pin bß╗ün</li><li>iOS mß╗¢i nhß║Ñt</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPhone 15 Pro</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/iphone-15-pro-natural-titanium.webp\" alt=\"iPhone 15 Pro\" loading=\"lazy\"></p>','Chipset: Apple A17 Pro\nM├án h├¼nh: 6.1 inch ProMotion OLED 120Hz\nCamera sau: 48MP ch├¡nh + 12MP tele + 12MP g├│c si├¬u rß╗Öng\nKhung: Titanium\nCß╗òng sß║íc: USB-C 3.0\nPin: L├¬n ─æß║┐n 23 giß╗¥ xem video',2023,1,1,'2026-06-12 16:41:08','2026-06-12 16:41:08',NULL),(3,1,2,'iPhone 16','iphone-16','iPhone 16 ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPhone 16 l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Hiß╗çu n─âng mß║ính mß║╜</li><li>Camera n├óng cß║Ñp</li><li>Pin bß╗ün</li><li>iOS mß╗¢i nhß║Ñt</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPhone 16</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/iphone-16-black.webp\" alt=\"iPhone 16\" loading=\"lazy\"></p>','Chipset: Apple A18\nM├án h├¼nh: 6.1 inch Super Retina XDR OLED\nCamera sau: 48MP Fusion + 12MP g├│c si├¬u rß╗Öng\nN├║t Camera Control\nCß╗òng sß║íc: USB-C\nPin: L├¬n ─æß║┐n 22 giß╗¥ xem video',2024,1,1,'2026-06-12 16:40:08','2026-06-12 16:40:08',NULL),(4,1,2,'iPhone 16 Pro','iphone-16-pro','iPhone 16 Pro ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPhone 16 Pro l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Chip A18 Pro</li><li>Camera Fusion 48MP</li><li>Khung titanium</li><li>USB-C</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPhone 16 Pro</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/iphone-16-pro-black-titanium.webp\" alt=\"iPhone 16 Pro\" loading=\"lazy\"></p><div class=\"video-embed\"><iframe src=\"https://www.youtube.com/embed/aqz-KE-bpKQ\" title=\"Video giß╗¢i thiß╗çu iPhone 16 Pro\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>','Chipset: Apple A18 Pro\nM├án h├¼nh: 6.3 inch ProMotion OLED 120Hz\nCamera sau: 48MP Fusion + 48MP g├│c si├¬u rß╗Öng + 12MP tele 5x\nKhung: Titanium\nCß╗òng sß║íc: USB-C 3.0\nPin: L├¬n ─æß║┐n 27 giß╗¥ xem video',2024,1,1,'2026-06-12 16:39:08','2026-06-12 16:39:08',NULL),(5,1,2,'iPhone 16 Pro Max','iphone-16-pro-max','iPhone 16 Pro Max ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPhone 16 Pro Max l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Chip A18 Pro</li><li>Camera Fusion 48MP</li><li>Khung titanium</li><li>USB-C</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPhone 16 Pro Max</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/iphone-16-pro-max-black-titanium.webp\" alt=\"iPhone 16 Pro Max\" loading=\"lazy\"></p>','Chipset: Apple A18 Pro\nM├án h├¼nh: 6.9 inch ProMotion OLED 120Hz\nCamera sau: 48MP Fusion + 48MP g├│c si├¬u rß╗Öng + 12MP tele 5x\nKhung: Titanium\nCß╗òng sß║íc: USB-C 3.0\nPin: L├¬n ─æß║┐n 33 giß╗¥ xem video',2024,0,1,'2026-06-12 16:38:08','2026-06-12 16:38:08',NULL),(6,2,3,'iPad 10.9 inch','ipad-10-9','iPad 10.9 inch ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPad 10.9 inch l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>M├án h├¼nh sß║»c n├⌐t</li><li>Hß╗ù trß╗ú Apple Pencil</li><li>Pin cß║ú ng├áy</li><li>iPadOS</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPad 10.9 inch</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/ipad-10-9-blue.webp\" alt=\"iPad 10.9 inch\" loading=\"lazy\"></p>','Chipset: Apple A14 Bionic\nM├án h├¼nh: 10.9 inch Liquid Retina\nB├║t hß╗ù trß╗ú: Apple Pencil (thß║┐ hß╗ç 1)\nCamera tr╞░ß╗¢c: 12MP Center Stage\nCß╗òng sß║íc: USB-C\nHß╗ç ─æiß╗üu h├ánh: iPadOS',2022,0,1,'2026-06-12 16:37:08','2026-06-12 16:37:08',NULL),(7,2,4,'iPad Air M2','ipad-air-m2','iPad Air M2 ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPad Air M2 l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>M├án h├¼nh sß║»c n├⌐t</li><li>Hß╗ù trß╗ú Apple Pencil</li><li>Pin cß║ú ng├áy</li><li>iPadOS</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPad Air M2</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/ipad-air-m2-blue.webp\" alt=\"iPad Air M2\" loading=\"lazy\"></p><div class=\"video-embed\"><iframe src=\"https://www.youtube.com/embed/aqz-KE-bpKQ\" title=\"Video giß╗¢i thiß╗çu iPad Air M2\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>','Chipset: Apple M2\nM├án h├¼nh: 11 inch Liquid Retina\nB├║t hß╗ù trß╗ú: Apple Pencil Pro / USB-C\nCamera tr╞░ß╗¢c: 12MP Center Stage\nCß╗òng sß║íc: USB-C\nHß╗ç ─æiß╗üu h├ánh: iPadOS',2024,1,1,'2026-06-12 16:36:08','2026-06-12 16:36:08',NULL),(8,2,5,'iPad Pro 11 inch M4','ipad-pro-11-m4','iPad Pro 11 inch M4 ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>iPad Pro 11 inch M4 l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>M├án h├¼nh sß║»c n├⌐t</li><li>Hß╗ù trß╗ú Apple Pencil</li><li>Pin cß║ú ng├áy</li><li>iPadOS</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>iPad Pro 11 inch M4</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/ipad-pro-11-m4-black.webp\" alt=\"iPad Pro 11 inch M4\" loading=\"lazy\"></p>','Chipset: Apple M4\nM├án h├¼nh: 11 inch Ultra Retina XDR OLED\nB├║t hß╗ù trß╗ú: Apple Pencil Pro\nCamera tr╞░ß╗¢c: 12MP Center Stage\nCß╗òng sß║íc: USB-C (Thunderbolt)\nHß╗ç ─æiß╗üu h├ánh: iPadOS',2024,1,1,'2026-06-12 16:35:08','2026-06-12 16:35:08',NULL),(9,3,6,'Apple 20W USB-C Power Adapter','apple-20w-usb-c-adapter','Apple 20W USB-C Power Adapter ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>Apple 20W USB-C Power Adapter l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Ch├¡nh h├úng Apple</li><li>Bß║úo h├ánh cß╗¡a h├áng</li><li>Ph├╣ hß╗úp ─æß╗ô ├ín</li><li>Giao h├áng to├án quß╗æc</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>Apple 20W USB-C Power Adapter</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/apple-20w-usb-c-adapter.webp\" alt=\"Apple 20W USB-C Power Adapter\" loading=\"lazy\"></p>','C├┤ng suß║Ñt: 20W\nCß╗òng ra: USB-C\nT╞░╞íng th├¡ch: iPhone, iPad, AirPods\nChuß║⌐n sß║íc nhanh: USB Power Delivery\nPhß║ím vi ─æiß╗çn ├íp: 100ΓÇô240V',2023,1,1,'2026-06-12 16:34:08','2026-06-12 16:34:08',NULL),(10,3,6,'Apple 30W USB-C Power Adapter','apple-30w-usb-c-adapter','Apple 30W USB-C Power Adapter ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>Apple 30W USB-C Power Adapter l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Ch├¡nh h├úng Apple</li><li>Bß║úo h├ánh cß╗¡a h├áng</li><li>Ph├╣ hß╗úp ─æß╗ô ├ín</li><li>Giao h├áng to├án quß╗æc</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>Apple 30W USB-C Power Adapter</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/apple-30w-usb-c-adapter.webp\" alt=\"Apple 30W USB-C Power Adapter\" loading=\"lazy\"></p>','C├┤ng suß║Ñt: 30W\nCß╗òng ra: USB-C\nT╞░╞íng th├¡ch: iPad, MacBook Air, iPhone\nChuß║⌐n sß║íc nhanh: USB Power Delivery\nPhß║ím vi ─æiß╗çn ├íp: 100ΓÇô240V',2023,0,1,'2026-06-12 16:33:08','2026-06-12 16:33:08',NULL),(11,3,7,'C├íp USB-C sang Lightning 1m','usb-c-to-lightning-1m','C├íp USB-C sang Lightning 1m ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>C├íp USB-C sang Lightning 1m l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Ch├¡nh h├úng Apple</li><li>Bß║úo h├ánh cß╗¡a h├áng</li><li>Ph├╣ hß╗úp ─æß╗ô ├ín</li><li>Giao h├áng to├án quß╗æc</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>C├íp USB-C sang Lightning 1m</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/usb-c-to-lightning-1m.webp\" alt=\"C├íp USB-C sang Lightning 1m\" loading=\"lazy\"></p>','Chiß╗üu d├ái: 1 m├⌐t\n─Éß║ºu v├áo: USB-C\n─Éß║ºu ra: Lightning\nT╞░╞íng th├¡ch: iPhone, AirPods hß╗Öp Lightning\nChß╗⌐c n─âng: Sß║íc & ─æß╗ông bß╗Ö dß╗» liß╗çu',2023,0,1,'2026-06-12 16:32:08','2026-06-12 16:32:08',NULL),(12,3,7,'C├íp USB-C 1m','usb-c-cable-1m','C├íp USB-C 1m ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>C├íp USB-C 1m l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Ch├¡nh h├úng Apple</li><li>Bß║úo h├ánh cß╗¡a h├áng</li><li>Ph├╣ hß╗úp ─æß╗ô ├ín</li><li>Giao h├áng to├án quß╗æc</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>C├íp USB-C 1m</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/usb-c-cable-1m.webp\" alt=\"C├íp USB-C 1m\" loading=\"lazy\"></p>','Chiß╗üu d├ái: 1 m├⌐t\n─Éß║ºu nß╗æi: USB-C to USB-C\nT╞░╞íng th├¡ch: iPhone 15 trß╗ƒ l├¬n, iPad, Mac\nChß╗⌐c n─âng: Sß║íc & truyß╗ün dß╗» liß╗çu\nChuß║⌐n: USB 2.0',2023,0,1,'2026-06-12 16:31:08','2026-06-12 16:31:08',NULL),(13,3,8,'AirPods (thß║┐ hß╗ç 3)','airpods-3','AirPods (thß║┐ hß╗ç 3) ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>AirPods (thß║┐ hß╗ç 3) l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Spatial Audio</li><li>Chip H1</li><li>Chß╗æng n╞░ß╗¢c IPX4</li><li>Hß╗Öp sß║íc MagSafe</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>AirPods (thß║┐ hß╗ç 3)</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/airpods-3.webp\" alt=\"AirPods (thß║┐ hß╗ç 3)\" loading=\"lazy\"></p>','Chipset: Apple H1\nKiß╗âu tai nghe: Earbuds (kh├┤ng n├║t silicon)\n├ém thanh: Spatial Audio, Adaptive EQ\nChß╗æng n╞░ß╗¢c: IPX4\nPin tai nghe: ~6 giß╗¥\nPin k├¿m hß╗Öp: ~30 giß╗¥\nSß║íc hß╗Öp: MagSafe, Lightning hoß║╖c kh├┤ng d├óy Qi',2021,0,1,'2026-06-12 16:30:08','2026-06-12 16:30:08',NULL),(14,3,8,'AirPods Pro (thß║┐ hß╗ç 2)','airpods-pro-2','AirPods Pro (thß║┐ hß╗ç 2) ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>AirPods Pro (thß║┐ hß╗ç 2) l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Chß╗æng ß╗ôn ANC</li><li>Chip H2</li><li>Spatial Audio</li><li>Hß╗Öp sß║íc USB-C</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>AirPods Pro (thß║┐ hß╗ç 2)</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/airpods-pro-2.webp\" alt=\"AirPods Pro (thß║┐ hß╗ç 2)\" loading=\"lazy\"></p>','Chipset: Apple H2\nChß╗æng ß╗ôn: ANC chß╗º ─æß╗Öng\n├ém thanh: Spatial Audio, Adaptive EQ\nChß╗æng n╞░ß╗¢c: IP54\nPin tai nghe: ~6 giß╗¥ (ANC bß║¡t)\nPin k├¿m hß╗Öp: ~30 giß╗¥\nSß║íc hß╗Öp: USB-C MagSafe',2022,1,1,'2026-06-12 16:29:08','2026-06-12 16:29:08',NULL),(15,3,8,'AirPods Max','airpods-max','AirPods Max ch├¡nh h├úng, bß║úo h├ánh theo ch├¡nh s├ích cß╗¡a h├áng.','<h2>Giß╗¢i thiß╗çu</h2><p>AirPods Max l├á sß║ún phß║⌐m demo trong dß╗▒ ├ín iStore, tr├¼nh b├áy m├┤ tß║ú rich-text t╞░╞íng th├¡ch Quill.</p><h3>─Éiß╗âm nß╗òi bß║¡t</h3><ul><li>Tai nghe over-ear</li><li>Chß╗æng ß╗ôn cao cß║Ñp</li><li>├ém thanh kh├┤ng gian</li><li>Pin 20 giß╗¥</li></ul><h3>Th├┤ng sß╗æ nhanh</h3><table><thead><tr><th>Hß║íng mß╗Ñc</th><th>Chi tiß║┐t</th></tr></thead><tbody><tr><td>Th╞░╞íng hiß╗çu</td><td>Apple</td></tr><tr><td>Ph├ón loß║íi</td><td>AirPods Max</td></tr><tr><td>Mß╗Ñc ─æ├¡ch</td><td>Hß╗ìc tß║¡p / demo</td></tr></tbody></table><h3>H├¼nh ß║únh minh hß╗ìa</h3><p><img src=\"/storage/products/demo/airpods-max.webp\" alt=\"AirPods Max\" loading=\"lazy\"></p>','Chipset: Apple H1\nKiß╗âu tai nghe: Over-ear\nChß╗æng ß╗ôn: ANC chß╗º ─æß╗Öng\n├ém thanh: Spatial Audio c├í nh├ón h├│a\nPin: ~20 giß╗¥ (ANC bß║¡t)\nKß║┐t nß╗æi: Bluetooth 5.0\nSß║íc: USB-C',2020,0,1,'2026-06-12 16:28:08','2026-06-12 16:28:08',NULL);
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
INSERT INTO `storage_options` VALUES (1,'64 GB',64,1,1,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(2,'128 GB',128,1,2,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(3,'256 GB',256,1,3,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(4,'512 GB',512,1,4,'2026-06-12 16:42:08','2026-06-12 16:42:08'),(5,'1 TB',1024,1,5,'2026-06-12 16:42:08','2026-06-12 16:42:08');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Quß║ún trß╗ï vi├¬n iStore','admin@istore.test','0900000001',NULL,'$2y$12$3h/.lidwy7KhBFkUuPmrQeFClEo/2AlYr8QuIdHXKXs/CrYa4.I0S',NULL,'2026-06-12 16:42:07','2026-06-12 16:42:07','admin','active',NULL),(2,'Kh├ích h├áng 1','customer1@istore.test','0910000001','2026-06-12 16:42:07','$2y$12$h55WxOz5kHoZ6cjUIlmAm.Ng1ssO3H/qoFxtrIYtyBU4u8kpbzpZm','JAuXQ7vGTS','2026-06-12 16:42:08','2026-06-12 16:42:08','customer','active',NULL),(3,'Kh├ích h├áng 2','customer2@istore.test','0910000002','2026-06-12 16:42:08','$2y$12$h55WxOz5kHoZ6cjUIlmAm.Ng1ssO3H/qoFxtrIYtyBU4u8kpbzpZm','M4OiZ571Qe','2026-06-12 16:42:08','2026-06-12 16:42:08','customer','active',NULL),(4,'Kh├ích h├áng 3','customer3@istore.test','0910000003','2026-06-12 16:42:08','$2y$12$h55WxOz5kHoZ6cjUIlmAm.Ng1ssO3H/qoFxtrIYtyBU4u8kpbzpZm','7t7jWj8oeG','2026-06-12 16:42:08','2026-06-12 16:42:08','customer','active',NULL),(5,'Kh├ích h├áng 4','customer4@istore.test','0910000004','2026-06-12 16:42:08','$2y$12$h55WxOz5kHoZ6cjUIlmAm.Ng1ssO3H/qoFxtrIYtyBU4u8kpbzpZm','dbeK4EQ6jS','2026-06-12 16:42:08','2026-06-12 16:42:08','customer','active',NULL),(6,'Kh├ích h├áng 5','customer5@istore.test','0910000005','2026-06-12 16:42:08','$2y$12$h55WxOz5kHoZ6cjUIlmAm.Ng1ssO3H/qoFxtrIYtyBU4u8kpbzpZm','m94LFfKNfl','2026-06-12 16:42:08','2026-06-12 16:42:08','customer','active',NULL);
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

-- Dump completed on 2026-06-12 23:42:10
