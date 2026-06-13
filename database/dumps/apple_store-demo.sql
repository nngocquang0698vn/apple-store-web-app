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
INSERT INTO `categories` VALUES (1,'iPhone','iphone','Danh mục iPhone dành cho đồ án iStore.',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,'iPad','ipad','Danh mục iPad dành cho đồ án iStore.',1,2,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,'Phụ kiện','phu-kien','Danh mục Phụ kiện dành cho đồ án iStore.',1,3,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `colors` VALUES (1,'Đen','black','#1F2937',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,'Trắng','white','#F9FAFB',1,2,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,'Xanh dương','blue','#2563EB',1,3,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(4,'Hồng','pink','#EC4899',1,4,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(5,'Tím','purple','#7C3AED',1,5,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(6,'Xanh lá','green','#16A34A',1,6,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(7,'Titanium tự nhiên','natural-titanium','#9CA3AF',1,7,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(8,'Titanium đen','black-titanium','#374151',1,8,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(9,'Titanium trắng','white-titanium','#E5E7EB',1,9,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(10,'Titanium xanh sa mạc','desert-titanium','#D6BFA3',1,10,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `order_items` VALUES (1,1,3,19,'iPhone 16','IP16-BLK-128','Đen','128 GB',19990000,1,19990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,1,3,26,'iPhone 16','IP16-PNK-256','Hồng','256 GB',23990000,2,47980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,2,5,42,'iPhone 16 Pro Max','IP16PM-NTI-512','Titanium tự nhiên','512 GB',37990000,1,37990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(4,2,7,55,'iPad Air M2','IPADAIR-PUR-128','Tím','128 GB',14990000,1,14990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(5,3,1,3,'iPhone 15','IP15-BLK-512','Đen','512 GB',26990000,2,53980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(6,3,2,14,'iPhone 15 Pro','IP15P-BTI-256','Titanium đen','256 GB',28990000,1,28990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(7,4,2,11,'iPhone 15 Pro','IP15P-NTI-256','Titanium tự nhiên','256 GB',28990000,2,57980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(8,4,4,33,'iPhone 16 Pro','IP16P-NTI-512','Titanium tự nhiên','512 GB',33990000,2,67980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(9,5,3,19,'iPhone 16','IP16-BLK-128','Đen','128 GB',19990000,1,19990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(10,5,4,35,'iPhone 16 Pro','IP16P-WTI-256','Titanium trắng','256 GB',29990000,2,59980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(11,6,3,26,'iPhone 16','IP16-PNK-256','Hồng','256 GB',23990000,1,23990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(12,6,8,63,'iPad Pro 11 inch M4','IPADP11-BLK-1024','Đen','1 TB',28990000,2,57980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(13,7,6,50,'iPad 10.9 inch','IPAD10-WHT-64','Trắng','64 GB',9990000,2,19980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(14,7,8,66,'iPad Pro 11 inch M4','IPADP11-WHT-1024','Trắng','1 TB',28990000,2,57980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(15,8,5,45,'iPhone 16 Pro Max','IP16PM-DTI-512','Titanium xanh sa mạc','512 GB',37990000,1,37990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(16,8,8,62,'iPad Pro 11 inch M4','IPADP11-BLK-512','Đen','512 GB',25990000,2,51980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(17,9,1,8,'iPhone 15','IP15-PNK-256','Hồng','256 GB',22990000,2,45980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(18,9,3,26,'iPhone 16','IP16-PNK-256','Hồng','256 GB',23990000,2,47980000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(19,10,7,59,'iPad Air M2','IPADAIR-WHT-256','Trắng','256 GB',17990000,1,17990000,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(20,10,9,67,'Apple 20W USB-C Power Adapter','CHG20W-WHT','Trắng','Không có',490000,2,980000,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `orders` VALUES (1,'ORD-260613-0001',2,'Khách hàng 1','0910000001','TP. Hồ Chí Minh','Quận 1','Phường 3','1626 Phố Hy Phúc Sinh',NULL,'cod',67970000,0,67970000,'pending',NULL,NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,'ORD-260613-0002',3,'Khách hàng 2','0910000002','TP. Hồ Chí Minh','Quận 2','Phường 15','339 Phố Đường','Excepturi ut dolor sint quia iusto.','cod',52980000,0,52980000,'pending',NULL,NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,'ORD-260613-0003',4,'Khách hàng 3','0910000003','TP. Hồ Chí Minh','Quận 3','Phường 10','3 Phố Chu',NULL,'cod',82970000,0,82970000,'confirmed',NULL,NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(4,'ORD-260613-0004',5,'Khách hàng 4','0910000004','TP. Hồ Chí Minh','Quận 4','Phường 5','822 Phố Hán Nhu Lĩnh',NULL,'cod',125960000,0,125960000,'confirmed',NULL,NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(5,'ORD-260613-0005',6,'Khách hàng 5','0910000005','TP. Hồ Chí Minh','Quận 5','Phường 17','923 Phố Mạch Võ Trung','Debitis sed velit id tempora impedit.','cod',79970000,0,79970000,'shipping',NULL,NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(6,'ORD-260613-0006',2,'Khách hàng 1','0910000001','TP. Hồ Chí Minh','Quận 6','Phường 7','72 Phố Lương Lai Linh',NULL,'cod',81970000,0,81970000,'shipping',NULL,NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(7,'ORD-260613-0007',3,'Khách hàng 2','0910000002','TP. Hồ Chí Minh','Quận 7','Phường 5','8632 Phố Chiêm Cần Châu',NULL,'cod',77960000,0,77960000,'completed',NULL,'2026-06-11 03:57:44','2026-06-13 03:57:44','2026-06-13 03:57:44'),(8,'ORD-260613-0008',4,'Khách hàng 3','0910000003','TP. Hồ Chí Minh','Quận 8','Phường 20','434 Phố Châu Phúc Hạnh','Aspernatur voluptatem sed ab dolorem omnis et.','cod',89970000,0,89970000,'completed',NULL,'2026-06-11 03:57:44','2026-06-13 03:57:44','2026-06-13 03:57:44'),(9,'ORD-260613-0009',5,'Khách hàng 4','0910000004','TP. Hồ Chí Minh','Quận 9','Phường 5','7 Phố Hạ','Accusamus temporibus at quae eos et harum quas.','cod',93960000,0,93960000,'cancelled','2026-06-12 03:57:44',NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(10,'ORD-260613-0010',6,'Khách hàng 5','0910000005','TP. Hồ Chí Minh','Quận 10','Phường 17','72 Phố An Diệu Sinh','Excepturi aut odio iure blanditiis velit labore.','cod',18970000,0,18970000,'cancelled','2026-06-12 03:57:44',NULL,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `product_images` VALUES (1,1,'products/demo/iphone-15-black.webp','iPhone 15 màu đen',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,1,'products/demo/iphone-15-pink.webp','iPhone 15 màu hồng',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,1,'products/demo/iphone-15-blue.webp','iPhone 15 màu xanh dương',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(4,1,'products/demo/iphone-15-view-1.webp','iPhone 15 góc chụp 1',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(5,1,'products/demo/iphone-15-view-2.webp','iPhone 15 góc chụp 2',5,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(6,1,'products/demo/iphone-15-view-3.webp','iPhone 15 góc chụp 3',6,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(7,2,'products/demo/iphone-15-pro-natural-titanium.webp','iPhone 15 Pro màu Titanium tự nhiên',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(8,2,'products/demo/iphone-15-pro-black-titanium.webp','iPhone 15 Pro màu Titanium đen',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(9,2,'products/demo/iphone-15-pro-white-titanium.webp','iPhone 15 Pro màu Titanium trắng',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(10,2,'products/demo/iphone-15-pro-view-1.webp','iPhone 15 Pro góc chụp 1',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(11,2,'products/demo/iphone-15-pro-view-2.webp','iPhone 15 Pro góc chụp 2',5,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(12,2,'products/demo/iphone-15-pro-view-3.webp','iPhone 15 Pro góc chụp 3',6,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(13,3,'products/demo/iphone-16-black.webp','iPhone 16 màu đen',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(14,3,'products/demo/iphone-16-pink.webp','iPhone 16 màu hồng',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(15,3,'products/demo/iphone-16-blue.webp','iPhone 16 màu xanh dương',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(16,3,'products/demo/iphone-16-view-1.webp','iPhone 16 góc chụp 1',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(17,3,'products/demo/iphone-16-view-2.webp','iPhone 16 góc chụp 2',5,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(18,3,'products/demo/iphone-16-view-3.webp','iPhone 16 góc chụp 3',6,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(19,4,'products/demo/iphone-16-pro-black-titanium.webp','iPhone 16 Pro màu Titanium đen',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(20,4,'products/demo/iphone-16-pro-natural-titanium.webp','iPhone 16 Pro màu Titanium tự nhiên',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(21,4,'products/demo/iphone-16-pro-white-titanium.webp','iPhone 16 Pro màu Titanium trắng',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(22,4,'products/demo/iphone-16-pro-view-1.webp','iPhone 16 Pro góc chụp 1',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(23,4,'products/demo/iphone-16-pro-view-2.webp','iPhone 16 Pro góc chụp 2',5,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(24,4,'products/demo/iphone-16-pro-view-3.webp','iPhone 16 Pro góc chụp 3',6,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(25,5,'products/demo/iphone-16-pro-max-black-titanium.webp','iPhone 16 Pro Max màu Titanium đen',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(26,5,'products/demo/iphone-16-pro-max-natural-titanium.webp','iPhone 16 Pro Max màu Titanium tự nhiên',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(27,5,'products/demo/iphone-16-pro-max-desert-titanium.webp','iPhone 16 Pro Max màu Titanium sa mạc',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(28,5,'products/demo/iphone-16-pro-max-view-1.webp','iPhone 16 Pro Max góc chụp 1',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(29,5,'products/demo/iphone-16-pro-max-view-2.webp','iPhone 16 Pro Max góc chụp 2',5,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(30,5,'products/demo/iphone-16-pro-max-view-3.webp','iPhone 16 Pro Max góc chụp 3',6,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(31,6,'products/demo/ipad-10-9-blue.webp','iPad 10.9 inch màu xanh dương',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(32,6,'products/demo/ipad-10-9-pink.webp','iPad 10.9 inch màu hồng',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(33,6,'products/demo/ipad-10-9-white.webp','iPad 10.9 inch màu trắng',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(34,6,'products/demo/ipad-10-9-view-1.webp','iPad 10.9 inch góc chụp 1',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(35,7,'products/demo/ipad-air-m2-blue.webp','iPad Air M2 màu xanh dương',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(36,7,'products/demo/ipad-air-m2-purple.webp','iPad Air M2 màu tím',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(37,7,'products/demo/ipad-air-m2-white.webp','iPad Air M2 màu trắng',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(38,7,'products/demo/ipad-air-m2-view-1.webp','iPad Air M2 góc chụp 1',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(39,7,'products/demo/ipad-air-m2-view-2.webp','iPad Air M2 góc chụp 2',5,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(40,7,'products/demo/ipad-air-m2-view-3.webp','iPad Air M2 góc chụp 3',6,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(41,8,'products/demo/ipad-pro-11-m4-black.webp','iPad Pro 11 inch M4 màu đen',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(42,8,'products/demo/ipad-pro-11-m4-white.webp','iPad Pro 11 inch M4 màu trắng',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(43,8,'products/demo/ipad-pro-11-m4-view-1.webp','iPad Pro 11 inch M4 góc chụp 1',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(44,8,'products/demo/ipad-pro-11-m4-view-2.webp','iPad Pro 11 inch M4 góc chụp 2',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(45,8,'products/demo/ipad-pro-11-m4-view-3.webp','iPad Pro 11 inch M4 góc chụp 3',5,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(46,9,'products/demo/apple-20w-usb-c-adapter.webp','Apple 20W USB-C Power Adapter',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(47,9,'products/demo/apple-20w-usb-c-adapter-view-1.webp','Apple 20W USB-C Power Adapter góc chụp 1',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(48,10,'products/demo/apple-30w-usb-c-adapter.webp','Apple 30W USB-C Power Adapter',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(49,10,'products/demo/apple-30w-usb-c-adapter-view-1.webp','Apple 30W USB-C Power Adapter góc chụp 1',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(50,10,'products/demo/apple-30w-usb-c-adapter-view-2.webp','Apple 30W USB-C Power Adapter góc chụp 2',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(51,11,'products/demo/usb-c-to-lightning-1m.webp','Cáp USB-C sang Lightning 1m',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(52,11,'products/demo/usb-c-to-lightning-1m-view-1.webp','Cáp USB-C sang Lightning 1m góc chụp 1',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(53,11,'products/demo/usb-c-to-lightning-1m-view-2.webp','Cáp USB-C sang Lightning 1m góc chụp 2',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(54,12,'products/demo/usb-c-cable-1m.webp','Cáp USB-C 1m',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(55,13,'products/demo/airpods-3.webp','AirPods (thế hệ 3)',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(56,13,'products/demo/airpods-3-view-1.webp','AirPods (thế hệ 3) góc chụp 1',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(57,14,'products/demo/airpods-pro-2.webp','AirPods Pro (thế hệ 2)',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(58,14,'products/demo/airpods-pro-2-view-1.webp','AirPods Pro (thế hệ 2) góc chụp 1',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(59,14,'products/demo/airpods-pro-2-view-2.webp','AirPods Pro (thế hệ 2) góc chụp 2',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(60,14,'products/demo/airpods-pro-2-view-3.webp','AirPods Pro (thế hệ 2) góc chụp 3',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(61,15,'products/demo/airpods-max-black.webp','AirPods Max màu đen',1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(62,15,'products/demo/airpods-max-blue.webp','AirPods Max màu xanh dương',2,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(63,15,'products/demo/airpods-max-view-1.webp','AirPods Max góc chụp 1',3,0,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(64,15,'products/demo/airpods-max-view-2.webp','AirPods Max góc chụp 2',4,0,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `product_series` VALUES (1,1,'iPhone 15 Series','iphone-15',2023,1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,1,'iPhone 16 Series','iphone-16',2024,1,2,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,2,'iPad','ipad',2022,1,3,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(4,2,'iPad Air','ipad-air',2024,1,4,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(5,2,'iPad Pro','ipad-pro',2024,1,5,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(6,3,'USB-C Chargers','usb-c-chargers',2023,1,6,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(7,3,'Charging Cables','charging-cables',2023,1,7,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(8,3,'AirPods','airpods',2024,1,8,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `product_variants` VALUES (1,1,1,2,'IP15-BLK-128',19990000,18990000,21,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,1,1,3,'IP15-BLK-256',23990000,22990000,17,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,1,1,4,'IP15-BLK-512',27990000,26990000,17,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(4,1,3,2,'IP15-BLU-128',19990000,18990000,29,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(5,1,3,3,'IP15-BLU-256',23990000,22990000,27,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(6,1,3,4,'IP15-BLU-512',27990000,26990000,25,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(7,1,4,2,'IP15-PNK-128',19990000,18990000,19,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(8,1,4,3,'IP15-PNK-256',23990000,22990000,22,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(9,1,4,4,'IP15-PNK-512',27990000,26990000,8,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(10,2,7,2,'IP15P-NTI-128',25990000,24990000,20,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(11,2,7,3,'IP15P-NTI-256',29990000,28990000,22,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(12,2,7,4,'IP15P-NTI-512',33990000,32990000,15,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(13,2,8,2,'IP15P-BTI-128',25990000,24990000,11,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(14,2,8,3,'IP15P-BTI-256',29990000,28990000,18,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(15,2,8,4,'IP15P-BTI-512',33990000,32990000,10,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(16,2,9,2,'IP15P-WTI-128',25990000,24990000,30,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(17,2,9,3,'IP15P-WTI-256',29990000,28990000,11,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(18,2,9,4,'IP15P-WTI-512',33990000,32990000,12,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(19,3,1,2,'IP16-BLK-128',20990000,19990000,8,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(20,3,1,3,'IP16-BLK-256',24990000,23990000,10,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(21,3,1,4,'IP16-BLK-512',28990000,27990000,17,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(22,3,3,2,'IP16-BLU-128',20990000,19990000,29,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(23,3,3,3,'IP16-BLU-256',24990000,23990000,9,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(24,3,3,4,'IP16-BLU-512',28990000,27990000,27,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(25,3,4,2,'IP16-PNK-128',20990000,19990000,22,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(26,3,4,3,'IP16-PNK-256',24990000,23990000,26,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(27,3,4,4,'IP16-PNK-512',28990000,27990000,10,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(28,4,8,2,'IP16P-BTI-128',26990000,25990000,11,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(29,4,8,3,'IP16P-BTI-256',30990000,29990000,12,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(30,4,8,4,'IP16P-BTI-512',34990000,33990000,9,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(31,4,7,2,'IP16P-NTI-128',26990000,25990000,8,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(32,4,7,3,'IP16P-NTI-256',30990000,29990000,7,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(33,4,7,4,'IP16P-NTI-512',34990000,33990000,22,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(34,4,9,2,'IP16P-WTI-128',26990000,25990000,28,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(35,4,9,3,'IP16P-WTI-256',30990000,29990000,25,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(36,4,9,4,'IP16P-WTI-512',34990000,33990000,21,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(37,5,8,2,'IP16PM-BTI-128',30990000,29990000,14,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(38,5,8,3,'IP16PM-BTI-256',34990000,33990000,17,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(39,5,8,4,'IP16PM-BTI-512',38990000,37990000,9,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(40,5,7,2,'IP16PM-NTI-128',30990000,29990000,23,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(41,5,7,3,'IP16PM-NTI-256',34990000,33990000,22,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(42,5,7,4,'IP16PM-NTI-512',38990000,37990000,12,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(43,5,10,2,'IP16PM-DTI-128',30990000,29990000,10,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(44,5,10,3,'IP16PM-DTI-256',34990000,33990000,19,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(45,5,10,4,'IP16PM-DTI-512',38990000,37990000,12,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(46,6,3,1,'IPAD10-BLU-64',NULL,9990000,18,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(47,6,3,3,'IPAD10-BLU-256',NULL,12990000,6,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(48,6,4,1,'IPAD10-PNK-64',NULL,9990000,3,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(49,6,4,3,'IPAD10-PNK-256',NULL,12990000,4,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(50,6,2,1,'IPAD10-WHT-64',NULL,9990000,4,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(51,6,2,3,'IPAD10-WHT-256',NULL,12990000,13,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(52,7,3,2,'IPADAIR-BLU-128',NULL,14990000,17,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(53,7,3,3,'IPADAIR-BLU-256',NULL,17990000,12,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(54,7,3,4,'IPADAIR-BLU-512',NULL,20990000,9,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(55,7,5,2,'IPADAIR-PUR-128',NULL,14990000,5,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(56,7,5,3,'IPADAIR-PUR-256',NULL,17990000,20,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(57,7,5,4,'IPADAIR-PUR-512',NULL,20990000,18,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(58,7,2,2,'IPADAIR-WHT-128',NULL,14990000,7,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(59,7,2,3,'IPADAIR-WHT-256',NULL,17990000,13,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(60,7,2,4,'IPADAIR-WHT-512',NULL,20990000,20,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(61,8,1,3,'IPADP11-BLK-256',NULL,22990000,8,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(62,8,1,4,'IPADP11-BLK-512',NULL,25990000,16,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(63,8,1,5,'IPADP11-BLK-1024',NULL,28990000,17,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(64,8,2,3,'IPADP11-WHT-256',NULL,22990000,5,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(65,8,2,4,'IPADP11-WHT-512',NULL,25990000,16,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(66,8,2,5,'IPADP11-WHT-1024',NULL,28990000,12,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(67,9,2,NULL,'CHG20W-WHT',NULL,490000,69,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(68,10,2,NULL,'CHG30W-WHT',NULL,790000,87,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(69,11,2,NULL,'CBL-CL-1M',NULL,450000,26,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(70,12,2,NULL,'CBL-CC-1M',NULL,390000,33,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(71,13,2,NULL,'APOD3-WHT',NULL,4290000,30,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(72,14,2,NULL,'APODP2-WHT',NULL,5990000,23,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(73,15,1,NULL,'APODMAX-BLK',13990000,12990000,17,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(74,15,3,NULL,'APODMAX-BLU',13990000,12990000,11,1,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `products` VALUES (1,1,1,'iPhone 15','iphone-15','iPhone 15 chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPhone 15 là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Hiệu năng mạnh mẽ</li><li>Camera nâng cấp</li><li>Pin bền</li><li>iOS mới nhất</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPhone 15</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/iphone-15-black.webp\" alt=\"iPhone 15\" loading=\"lazy\"></p>','Chipset: Apple A16 Bionic\nMàn hình: 6.1 inch Super Retina XDR OLED\nCamera sau: 48MP + 12MP\nCamera trước: 12MP TrueDepth\nPin: Lên đến 20 giờ xem video\nCổng sạc: USB-C\nHệ điều hành: iOS',2023,1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44',NULL),(2,1,1,'iPhone 15 Pro','iphone-15-pro','iPhone 15 Pro chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPhone 15 Pro là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Hiệu năng mạnh mẽ</li><li>Camera nâng cấp</li><li>Pin bền</li><li>iOS mới nhất</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPhone 15 Pro</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/iphone-15-pro-natural-titanium.webp\" alt=\"iPhone 15 Pro\" loading=\"lazy\"></p>','Chipset: Apple A17 Pro\nMàn hình: 6.1 inch ProMotion OLED 120Hz\nCamera sau: 48MP chính + 12MP tele + 12MP góc siêu rộng\nKhung: Titanium\nCổng sạc: USB-C 3.0\nPin: Lên đến 23 giờ xem video',2023,1,1,'2026-06-13 03:56:44','2026-06-13 03:56:44',NULL),(3,1,2,'iPhone 16','iphone-16','iPhone 16 chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPhone 16 là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Hiệu năng mạnh mẽ</li><li>Camera nâng cấp</li><li>Pin bền</li><li>iOS mới nhất</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPhone 16</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/iphone-16-black.webp\" alt=\"iPhone 16\" loading=\"lazy\"></p>','Chipset: Apple A18\nMàn hình: 6.1 inch Super Retina XDR OLED\nCamera sau: 48MP Fusion + 12MP góc siêu rộng\nNút Camera Control\nCổng sạc: USB-C\nPin: Lên đến 22 giờ xem video',2024,1,1,'2026-06-13 03:55:44','2026-06-13 03:55:44',NULL),(4,1,2,'iPhone 16 Pro','iphone-16-pro','iPhone 16 Pro chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPhone 16 Pro là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Chip A18 Pro</li><li>Camera Fusion 48MP</li><li>Khung titanium</li><li>USB-C</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPhone 16 Pro</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/iphone-16-pro-black-titanium.webp\" alt=\"iPhone 16 Pro\" loading=\"lazy\"></p><div class=\"video-embed\"><iframe src=\"https://www.youtube.com/embed/aqz-KE-bpKQ\" title=\"Video giới thiệu iPhone 16 Pro\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>','Chipset: Apple A18 Pro\nMàn hình: 6.3 inch ProMotion OLED 120Hz\nCamera sau: 48MP Fusion + 48MP góc siêu rộng + 12MP tele 5x\nKhung: Titanium\nCổng sạc: USB-C 3.0\nPin: Lên đến 27 giờ xem video',2024,1,1,'2026-06-13 03:54:44','2026-06-13 03:54:44',NULL),(5,1,2,'iPhone 16 Pro Max','iphone-16-pro-max','iPhone 16 Pro Max chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPhone 16 Pro Max là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Chip A18 Pro</li><li>Camera Fusion 48MP</li><li>Khung titanium</li><li>USB-C</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPhone 16 Pro Max</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/iphone-16-pro-max-black-titanium.webp\" alt=\"iPhone 16 Pro Max\" loading=\"lazy\"></p>','Chipset: Apple A18 Pro\nMàn hình: 6.9 inch ProMotion OLED 120Hz\nCamera sau: 48MP Fusion + 48MP góc siêu rộng + 12MP tele 5x\nKhung: Titanium\nCổng sạc: USB-C 3.0\nPin: Lên đến 33 giờ xem video',2024,0,1,'2026-06-13 03:53:44','2026-06-13 03:53:44',NULL),(6,2,3,'iPad 10.9 inch','ipad-10-9','iPad 10.9 inch chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPad 10.9 inch là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Màn hình sắc nét</li><li>Hỗ trợ Apple Pencil</li><li>Pin cả ngày</li><li>iPadOS</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPad 10.9 inch</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/ipad-10-9-blue.webp\" alt=\"iPad 10.9 inch\" loading=\"lazy\"></p>','Chipset: Apple A14 Bionic\nMàn hình: 10.9 inch Liquid Retina\nBút hỗ trợ: Apple Pencil (thế hệ 1)\nCamera trước: 12MP Center Stage\nCổng sạc: USB-C\nHệ điều hành: iPadOS',2022,0,1,'2026-06-13 03:52:44','2026-06-13 03:52:44',NULL),(7,2,4,'iPad Air M2','ipad-air-m2','iPad Air M2 chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPad Air M2 là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Màn hình sắc nét</li><li>Hỗ trợ Apple Pencil</li><li>Pin cả ngày</li><li>iPadOS</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPad Air M2</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/ipad-air-m2-blue.webp\" alt=\"iPad Air M2\" loading=\"lazy\"></p><div class=\"video-embed\"><iframe src=\"https://www.youtube.com/embed/aqz-KE-bpKQ\" title=\"Video giới thiệu iPad Air M2\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>','Chipset: Apple M2\nMàn hình: 11 inch Liquid Retina\nBút hỗ trợ: Apple Pencil Pro / USB-C\nCamera trước: 12MP Center Stage\nCổng sạc: USB-C\nHệ điều hành: iPadOS',2024,1,1,'2026-06-13 03:51:44','2026-06-13 03:51:44',NULL),(8,2,5,'iPad Pro 11 inch M4','ipad-pro-11-m4','iPad Pro 11 inch M4 chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>iPad Pro 11 inch M4 là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Màn hình sắc nét</li><li>Hỗ trợ Apple Pencil</li><li>Pin cả ngày</li><li>iPadOS</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>iPad Pro 11 inch M4</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/ipad-pro-11-m4-black.webp\" alt=\"iPad Pro 11 inch M4\" loading=\"lazy\"></p>','Chipset: Apple M4\nMàn hình: 11 inch Ultra Retina XDR OLED\nBút hỗ trợ: Apple Pencil Pro\nCamera trước: 12MP Center Stage\nCổng sạc: USB-C (Thunderbolt)\nHệ điều hành: iPadOS',2024,1,1,'2026-06-13 03:50:44','2026-06-13 03:50:44',NULL),(9,3,6,'Apple 20W USB-C Power Adapter','apple-20w-usb-c-adapter','Apple 20W USB-C Power Adapter chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>Apple 20W USB-C Power Adapter là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Chính hãng Apple</li><li>Bảo hành cửa hàng</li><li>Phù hợp đồ án</li><li>Giao hàng toàn quốc</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>Apple 20W USB-C Power Adapter</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/apple-20w-usb-c-adapter.webp\" alt=\"Apple 20W USB-C Power Adapter\" loading=\"lazy\"></p>','Công suất: 20W\nCổng ra: USB-C\nTương thích: iPhone, iPad, AirPods\nChuẩn sạc nhanh: USB Power Delivery\nPhạm vi điện áp: 100–240V',2023,1,1,'2026-06-13 03:49:44','2026-06-13 03:49:44',NULL),(10,3,6,'Apple 30W USB-C Power Adapter','apple-30w-usb-c-adapter','Apple 30W USB-C Power Adapter chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>Apple 30W USB-C Power Adapter là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Chính hãng Apple</li><li>Bảo hành cửa hàng</li><li>Phù hợp đồ án</li><li>Giao hàng toàn quốc</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>Apple 30W USB-C Power Adapter</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/apple-30w-usb-c-adapter.webp\" alt=\"Apple 30W USB-C Power Adapter\" loading=\"lazy\"></p>','Công suất: 30W\nCổng ra: USB-C\nTương thích: iPad, MacBook Air, iPhone\nChuẩn sạc nhanh: USB Power Delivery\nPhạm vi điện áp: 100–240V',2023,0,1,'2026-06-13 03:48:44','2026-06-13 03:48:44',NULL),(11,3,7,'Cáp USB-C sang Lightning 1m','usb-c-to-lightning-1m','Cáp USB-C sang Lightning 1m chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>Cáp USB-C sang Lightning 1m là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Chính hãng Apple</li><li>Bảo hành cửa hàng</li><li>Phù hợp đồ án</li><li>Giao hàng toàn quốc</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>Cáp USB-C sang Lightning 1m</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/usb-c-to-lightning-1m.webp\" alt=\"Cáp USB-C sang Lightning 1m\" loading=\"lazy\"></p>','Chiều dài: 1 mét\nĐầu vào: USB-C\nĐầu ra: Lightning\nTương thích: iPhone, AirPods hộp Lightning\nChức năng: Sạc & đồng bộ dữ liệu',2023,0,1,'2026-06-13 03:47:44','2026-06-13 03:47:44',NULL),(12,3,7,'Cáp USB-C 1m','usb-c-cable-1m','Cáp USB-C 1m chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>Cáp USB-C 1m là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Chính hãng Apple</li><li>Bảo hành cửa hàng</li><li>Phù hợp đồ án</li><li>Giao hàng toàn quốc</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>Cáp USB-C 1m</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/usb-c-cable-1m.webp\" alt=\"Cáp USB-C 1m\" loading=\"lazy\"></p>','Chiều dài: 1 mét\nĐầu nối: USB-C to USB-C\nTương thích: iPhone 15 trở lên, iPad, Mac\nChức năng: Sạc & truyền dữ liệu\nChuẩn: USB 2.0',2023,0,1,'2026-06-13 03:46:44','2026-06-13 03:46:44',NULL),(13,3,8,'AirPods (thế hệ 3)','airpods-3','AirPods (thế hệ 3) chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>AirPods (thế hệ 3) là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Spatial Audio</li><li>Chip H1</li><li>Chống nước IPX4</li><li>Hộp sạc MagSafe</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>AirPods (thế hệ 3)</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/airpods-3.webp\" alt=\"AirPods (thế hệ 3)\" loading=\"lazy\"></p>','Chipset: Apple H1\nKiểu tai nghe: Earbuds (không nút silicon)\nÂm thanh: Spatial Audio, Adaptive EQ\nChống nước: IPX4\nPin tai nghe: ~6 giờ\nPin kèm hộp: ~30 giờ\nSạc hộp: MagSafe, Lightning hoặc không dây Qi',2021,0,1,'2026-06-13 03:45:44','2026-06-13 03:45:44',NULL),(14,3,8,'AirPods Pro (thế hệ 2)','airpods-pro-2','AirPods Pro (thế hệ 2) chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>AirPods Pro (thế hệ 2) là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Chống ồn ANC</li><li>Chip H2</li><li>Spatial Audio</li><li>Hộp sạc USB-C</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>AirPods Pro (thế hệ 2)</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/airpods-pro-2.webp\" alt=\"AirPods Pro (thế hệ 2)\" loading=\"lazy\"></p>','Chipset: Apple H2\nChống ồn: ANC chủ động\nÂm thanh: Spatial Audio, Adaptive EQ\nChống nước: IP54\nPin tai nghe: ~6 giờ (ANC bật)\nPin kèm hộp: ~30 giờ\nSạc hộp: USB-C MagSafe',2022,1,1,'2026-06-13 03:44:44','2026-06-13 03:44:44',NULL),(15,3,8,'AirPods Max','airpods-max','AirPods Max chính hãng, bảo hành theo chính sách cửa hàng.','<h2>Giới thiệu</h2><p>AirPods Max là sản phẩm demo trong dự án iStore, trình bày mô tả rich-text tương thích Quill.</p><h3>Điểm nổi bật</h3><ul><li>Tai nghe over-ear</li><li>Chống ồn cao cấp</li><li>Âm thanh không gian</li><li>Pin 20 giờ</li></ul><h3>Thông số nhanh</h3><table><thead><tr><th>Hạng mục</th><th>Chi tiết</th></tr></thead><tbody><tr><td>Thương hiệu</td><td>Apple</td></tr><tr><td>Phân loại</td><td>AirPods Max</td></tr><tr><td>Mục đích</td><td>Học tập / demo</td></tr></tbody></table><h3>Hình ảnh minh họa</h3><p><img src=\"/storage/products/demo/airpods-max.webp\" alt=\"AirPods Max\" loading=\"lazy\"></p>','Chipset: Apple H1\nKiểu tai nghe: Over-ear\nChống ồn: ANC chủ động\nÂm thanh: Spatial Audio cá nhân hóa\nPin: ~20 giờ (ANC bật)\nKết nối: Bluetooth 5.0\nSạc: USB-C',2020,0,1,'2026-06-13 03:43:44','2026-06-13 03:43:44',NULL);
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
INSERT INTO `sessions` VALUES ('cUwIdFjPzDawcr1aNE6hnqQ3oRGAzE3P0UlU4RsH',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0','eyJfdG9rZW4iOiI0Y29Za1FFQTlmTmRQMk1GMzZDY2F2SUJoV0phZFdDeGxBazlTenpsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2FwcGxlLXN0b3JlLXdlYi1hcHAudGVzdFwvcHJvZHVjdHNcL2lwaG9uZS0xNj9jb2xvcj1ibGFjayZzdG9yYWdlPTEyOCIsInJvdXRlIjoicHJvZHVjdHMuc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1781323214);
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
INSERT INTO `storage_options` VALUES (1,'64 GB',64,1,1,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(2,'128 GB',128,1,2,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(3,'256 GB',256,1,3,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(4,'512 GB',512,1,4,'2026-06-13 03:57:44','2026-06-13 03:57:44'),(5,'1 TB',1024,1,5,'2026-06-13 03:57:44','2026-06-13 03:57:44');
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
INSERT INTO `users` VALUES (1,'Quản trị viên iStore','admin@istore.test','0900000001',NULL,'$2y$12$R/cvKvdoE8dRTz1JJOQkIe1hRtu7pdalvuua4svswk3AI792955Z6',NULL,'2026-06-13 03:57:43','2026-06-13 03:57:43','admin','active',NULL),(2,'Khách hàng 1','customer1@istore.test','0910000001','2026-06-13 03:57:43','$2y$12$9NtpsS8lU/zso7Lw.VchDuF28SPmGEwFBRlzCfqv8YuGaxOS8LNGi','74soB0o0aF','2026-06-13 03:57:44','2026-06-13 03:57:44','customer','active',NULL),(3,'Khách hàng 2','customer2@istore.test','0910000002','2026-06-13 03:57:44','$2y$12$9NtpsS8lU/zso7Lw.VchDuF28SPmGEwFBRlzCfqv8YuGaxOS8LNGi','TVUZ6Q8AmA','2026-06-13 03:57:44','2026-06-13 03:57:44','customer','active',NULL),(4,'Khách hàng 3','customer3@istore.test','0910000003','2026-06-13 03:57:44','$2y$12$9NtpsS8lU/zso7Lw.VchDuF28SPmGEwFBRlzCfqv8YuGaxOS8LNGi','wz0j2KSaYC','2026-06-13 03:57:44','2026-06-13 03:57:44','customer','active',NULL),(5,'Khách hàng 4','customer4@istore.test','0910000004','2026-06-13 03:57:44','$2y$12$9NtpsS8lU/zso7Lw.VchDuF28SPmGEwFBRlzCfqv8YuGaxOS8LNGi','bYyDw4EAQg','2026-06-13 03:57:44','2026-06-13 03:57:44','customer','active',NULL),(6,'Khách hàng 5','customer5@istore.test','0910000005','2026-06-13 03:57:44','$2y$12$9NtpsS8lU/zso7Lw.VchDuF28SPmGEwFBRlzCfqv8YuGaxOS8LNGi','OD4zsPzpuy','2026-06-13 03:57:44','2026-06-13 03:57:44','customer','active',NULL);
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

-- Dump completed on 2026-06-13 11:00:21
