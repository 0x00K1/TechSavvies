-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: techsavvies
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `category_name` varchar(255) NOT NULL COMMENT 'E.g., T-Shirts, Backpacks, etc.',
  `description` text DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'T-shirts','Tech-inspired and programming-themed t-shirts for developers and tech enthusiasts'),(2,'Backpacks','Functional and stylish backpacks designed for carrying tech gear and laptops'),(3,'Books','Technical books and programming guides for learning and reference'),(4,'Laptops','High-performance laptops and notebooks for development and professional use'),(5,'Stickers','Programming and tech-themed stickers for personalizing your gear'),(6,'Hardware Tools','Essential tools for computer maintenance and hardware work'),(7,'Software Tools','Digital tools and software licenses for development'),(8,'Mugs','Tech-themed coffee mugs and drinkware'),(9,'Phone Cases','Protective cases with tech-inspired designs'),(10,'Games','Programming and tech-related games and simulators');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1 COMMENT 'Number of units purchased',
  `price_per_unit` decimal(10,2) NOT NULL COMMENT 'Price at time of purchase',
  PRIMARY KEY (`order_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,65,1,129.99),(2,78,3,49.99),(2,81,2,149.99),(2,85,3,29.99),(2,90,1,34.99),(3,61,3,24.99),(3,68,1,39.99),(3,86,3,27.99),(4,68,2,39.99),(4,70,1,1299.99),(4,75,2,8.99),(4,80,3,79.99),(5,61,2,24.99),(5,64,2,89.99),(5,68,1,39.99),(5,77,1,79.99),(6,76,1,34.99),(7,71,3,1799.99),(7,74,3,7.99),(7,78,2,49.99),(7,82,3,19.99),(7,85,3,29.99),(8,77,3,79.99),(8,78,2,49.99),(8,87,1,24.99),(8,89,1,49.99),(8,90,1,34.99),(9,71,1,1799.99),(9,72,1,899.99),(9,83,1,17.99),(9,88,3,39.99),(9,89,1,49.99),(10,75,1,8.99),(10,76,1,34.99),(10,90,3,34.99),(11,63,2,27.99),(11,73,1,9.99),(11,83,2,17.99),(11,88,2,39.99),(12,61,2,24.99),(12,77,1,79.99),(12,80,3,79.99),(13,76,3,34.99),(14,63,3,27.99),(14,67,3,49.99),(14,77,3,79.99),(14,80,3,79.99),(15,89,2,49.99),(16,67,1,49.99),(16,72,1,899.99),(16,80,2,79.99),(17,67,1,49.99),(17,70,2,1299.99),(17,73,2,9.99),(18,77,2,79.99),(18,87,1,24.99),(19,62,2,29.99),(19,66,1,69.99),(19,76,1,34.99),(19,83,2,17.99),(20,69,1,54.99),(20,76,3,34.99),(21,89,1,49.99),(21,90,1,34.99),(22,71,3,1799.99),(22,77,2,79.99),(22,79,2,99.99),(23,61,1,24.99),(23,63,3,27.99),(23,65,2,129.99),(23,87,2,24.99),(24,87,3,24.99),(25,63,1,27.99),(25,70,3,1299.99),(25,76,2,34.99),(25,79,1,99.99),(25,83,1,17.99),(26,61,2,24.99),(26,66,3,69.99),(26,87,3,24.99),(26,90,3,34.99),(27,69,3,54.99),(27,81,3,149.99),(27,84,2,18.99),(28,80,1,79.99),(29,72,1,899.99),(29,73,1,9.99),(29,83,3,17.99),(29,86,2,27.99),(29,88,2,39.99),(30,68,3,39.99),(30,80,1,79.99),(30,85,1,29.99),(31,61,2,24.99),(31,79,1,99.99),(31,85,1,29.99),(31,89,1,49.99),(32,61,1,24.99),(32,79,3,99.99),(32,83,3,17.99),(33,86,3,27.99),(34,66,3,69.99),(34,77,2,79.99),(34,79,3,99.99),(35,74,2,7.99),(35,90,3,34.99),(36,77,3,79.99),(36,83,1,17.99),(37,74,1,7.99),(37,75,1,8.99),(37,83,1,17.99),(38,90,2,34.99),(39,66,2,69.99),(39,76,3,34.99),(39,80,1,79.99),(39,85,2,29.99),(39,88,1,39.99),(40,66,1,69.99),(40,88,3,39.99),(41,61,2,24.99),(41,78,1,49.99),(41,79,1,99.99),(41,83,2,17.99),(42,68,1,39.99),(42,72,3,899.99),(42,77,2,79.99),(42,80,1,79.99),(43,72,2,899.99),(43,90,2,34.99),(44,68,1,39.99),(44,70,2,1299.99),(44,86,1,27.99),(45,65,3,129.99),(45,68,3,39.99),(45,69,1,54.99),(46,67,2,49.99),(46,78,3,49.99),(46,83,2,17.99),(47,63,2,27.99),(47,69,1,54.99),(47,77,3,79.99),(48,76,1,34.99),(48,80,3,79.99),(48,86,1,27.99),(48,88,3,39.99),(49,63,3,27.99),(50,64,2,89.99),(50,67,3,49.99),(50,74,2,7.99),(50,78,3,49.99);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','paid','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Total price of the order',
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,'2025-02-21 04:00:21','completed',129.99),(2,3,'2025-02-21 04:00:21','completed',574.91),(3,2,'2025-02-21 04:00:21','completed',198.93),(4,3,'2025-02-21 04:00:21','completed',1637.92),(5,1,'2025-02-21 04:00:21','completed',349.94),(6,3,'2025-02-21 04:00:21','completed',34.99),(7,2,'2025-02-21 04:00:21','completed',5673.86),(8,2,'2025-02-21 04:00:21','completed',449.92),(9,1,'2025-02-21 04:00:21','completed',2887.93),(10,3,'2025-02-21 04:00:21','completed',148.95),(11,3,'2025-02-21 04:00:21','completed',181.93),(12,3,'2025-02-21 04:00:21','completed',369.94),(13,2,'2025-02-21 04:00:21','completed',104.97),(14,2,'2025-02-21 04:00:21','completed',713.88),(15,3,'2025-02-21 04:00:21','completed',99.98),(16,1,'2025-02-21 04:00:21','completed',1109.96),(17,1,'2025-02-21 04:00:21','completed',2669.95),(18,1,'2025-02-21 04:00:21','completed',184.97),(19,2,'2025-02-21 04:00:21','completed',200.94),(20,3,'2025-02-21 04:00:21','completed',159.96),(21,2,'2025-02-21 04:00:21','completed',84.98),(22,1,'2025-02-21 04:00:21','completed',5759.93),(23,2,'2025-02-21 04:00:21','completed',418.92),(24,1,'2025-02-21 04:00:21','completed',74.97),(25,3,'2025-02-21 04:00:21','completed',4115.92),(26,2,'2025-02-21 04:00:21','completed',439.89),(27,2,'2025-02-21 04:00:21','completed',652.92),(28,2,'2025-02-21 04:00:21','completed',79.99),(29,2,'2025-02-21 04:00:21','completed',1099.91),(30,3,'2025-02-21 04:00:21','completed',229.95),(31,3,'2025-02-21 04:00:21','completed',229.95),(32,3,'2025-02-21 04:00:21','completed',378.93),(33,3,'2025-02-21 04:00:21','completed',83.97),(34,3,'2025-02-21 04:00:21','completed',669.92),(35,3,'2025-02-21 04:00:21','completed',120.95),(36,2,'2025-02-21 04:00:21','completed',257.96),(37,3,'2025-02-21 04:00:21','completed',34.97),(38,1,'2025-02-21 04:00:21','completed',69.98),(39,1,'2025-02-21 04:00:21','completed',424.91),(40,3,'2025-02-21 04:00:21','completed',189.96),(41,2,'2025-02-21 04:00:21','completed',235.94),(42,1,'2025-02-21 04:00:21','completed',2979.93),(43,3,'2025-02-21 04:00:21','completed',1869.96),(44,1,'2025-02-21 04:00:21','completed',2667.96),(45,2,'2025-02-21 04:00:21','completed',564.93),(46,1,'2025-02-21 04:00:21','completed',285.93),(47,3,'2025-02-21 04:00:21','completed',350.94),(48,3,'2025-02-21 04:00:21','completed',422.92),(49,3,'2025-02-21 04:00:21','completed',83.97),(50,1,'2025-02-21 04:00:21','completed',495.90);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL COMMENT 'File name for product image, stored in /images',
  `description` text DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0 COMMENT 'Available inventory count',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'On update, CURRENT_TIMESTAMP',
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (61,1,'Tech Debugger T-Shirt','T-Shirt.png','Comfortable cotton t-shirt with a quirky debugging print','Gray','S,M,L,XL',24.99,100,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(62,1,'Python Lover Tee','T-Shirt.png','Black t-shirt featuring Python logo and code snippets','Black','M,L,XL',29.99,75,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(63,1,'JavaScript Ninja','T-Shirt.png','Premium cotton blend with JavaScript design','Navy','S,M,L',27.99,85,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(64,2,'TechPro Backpack','T-Shirt.png','Water-resistant laptop backpack with USB charging port','Black','15\"',89.99,50,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(65,2,'CyberSafe Anti-theft','T-Shirt.png','Anti-theft backpack with hidden compartments','Gray','17\"',129.99,30,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(66,2,'DevPack Light','T-Shirt.png','Lightweight backpack perfect for developers on the go','Blue','13\"',69.99,45,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(67,3,'Full Stack Development Guide','T-Shirt.png','Comprehensive guide to modern web development',NULL,'Paperback',49.99,200,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(68,3,'Python Mastery','T-Shirt.png','Advanced Python programming techniques and best practices',NULL,'Hardcover',39.99,150,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(69,3,'AI & Machine Learning Basics','T-Shirt.png','Introduction to AI and ML concepts',NULL,'Digital',54.99,100,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(70,4,'TechBook Pro','T-Shirt.png','15.6\" Laptop, 16GB RAM, 512GB SSD, Intel i7','Silver','15.6\"',1299.99,20,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(71,4,'DevStation X1','T-Shirt.png','14\" Developer Laptop, 32GB RAM, 1TB SSD','Space Gray','14\"',1799.99,15,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(72,4,'CodeMaster Light','T-Shirt.png','13\" Ultrabook, 8GB RAM, 256GB SSD','White','13\"',899.99,25,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(73,5,'Developer Pack Stickers','T-Shirt.png','Set of 10 programming language stickers','Multi','3\"x3\"',9.99,500,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(74,5,'Linux Penguin Pack','T-Shirt.png','Collection of Linux distribution logos','Multi','2\"x2\"',7.99,300,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(75,5,'Git Commands Set','T-Shirt.png','Essential Git command reference stickers','Multi','4\"x4\"',8.99,400,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(76,6,'Pro Screwdriver Set','T-Shirt.png','Precision screwdriver set for electronics','Black','12-piece',34.99,150,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(77,6,'PC Repair Kit','T-Shirt.png','Complete computer repair toolkit','Blue','24-piece',79.99,100,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(78,6,'Network Cable Tester','T-Shirt.png','Professional network cable testing tool','Yellow','Standard',49.99,75,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(79,7,'Code Editor Pro License','T-Shirt.png','Professional code editor annual license',NULL,'Annual',99.99,1000,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(80,7,'Cloud Backup Plus','T-Shirt.png','Annual cloud backup service subscription',NULL,'1TB',79.99,500,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(81,7,'Security Suite Pro','T-Shirt.png','Complete development security toolkit',NULL,'Enterprise',149.99,300,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(82,8,'Code Debug Mug','T-Shirt.png','Color-changing debug message mug','Black','11oz',19.99,200,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(83,8,'Programmer Fuel Mug','T-Shirt.png','Large capacity coffee mug with funny quote','White','15oz',17.99,250,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(84,8,'Binary Code Mug','T-Shirt.png','Mug with binary code pattern design','Navy','11oz',18.99,180,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(85,9,'Developer iPhone Case','T-Shirt.png','Shock-proof case with code pattern','Clear','iPhone 13',29.99,150,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(86,9,'Android Debug Case','T-Shirt.png','Protective case with ADB command design','Black','Pixel 6',27.99,140,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(87,9,'Tech Pattern Case','T-Shirt.png','Slim case with circuit board design','Green','Galaxy S21',24.99,160,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(88,10,'Code Quest','T-Shirt.png','Programming puzzle adventure game',NULL,'Digital',39.99,100,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(89,10,'Hack Simulator','T-Shirt.png','Ethical hacking simulation game',NULL,'Digital',49.99,80,'2025-02-21 01:52:13','2025-02-21 03:39:00'),(90,10,'Dev Tycoon','T-Shirt.png','Software company management simulator',NULL,'Digital',34.99,120,'2025-02-21 01:52:13','2025-02-21 03:39:00');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `email` varchar(191) NOT NULL COMMENT 'User email, used for login and verification',
  `password` varchar(255) NOT NULL COMMENT 'Hashed password',
  `username` varchar(191) DEFAULT NULL COMMENT 'Optional user nickname/username | Default savvyclient[-----]',
  `role` enum('user','root') NOT NULL DEFAULT 'user' COMMENT 'Roles: user or root',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'test1@example.com','password123','testuser1','user','2025-02-21 03:54:05'),(2,'test2@example.com','password123','testuser2','user','2025-02-21 03:54:05'),(3,'test3@example.com','password123','testuser3','user','2025-02-21 03:54:05');
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

-- Dump completed on 2025-02-21  7:33:51
