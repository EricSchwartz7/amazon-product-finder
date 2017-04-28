-- MySQL dump 10.13  Distrib 5.7.18, for osx10.11 (x86_64)
--
-- Host: localhost    Database: products
-- ------------------------------------------------------
-- Server version	5.7.18

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
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asin` varchar(150) NOT NULL,
  `title` varchar(65000) DEFAULT NULL,
  `mpn` varchar(150) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (85,'B0066BE3TG','Polo Ralph Lauren Men\'s Faxon Low Sneaker, Grey, 11 D US','816155651029',4130),(86,'B01EIMSOW6','GW M1618-1 Fashion Sneaker 12 M','',3999),(87,'B01J2QN7RU','Leader Show (Tm) Men\'s Autumn ','',2799),(88,'WEOIFJOWIEF','Thing','EOIRJEIOF394',100099),(89,'B0066BE3TG','Polo Ralph Lauren Men\'s Faxon Low Sneaker, Grey, 11 D US','816155651029',4130),(90,'B00NUZIFOK','ASICS Men\'s Gel Venture 5 Running Shoe, Black/Onyx/Charcoal, 10 4E US','T5N3N.9099/T5P0N.9099',4895),(91,'B01EIUOSRS','Apple MMGF2LL/A MacBook Air 13.3-Inch Laptop (8GB RAM 128 GB SSD) MMGF2','MMGF2LL/A',97900),(92,'B00159LRXY','Apple A1181 Macbook MB403LL 13.3 Inch Laptop (2.1 GHz Intel Core 2 Duo Mobile, 2 GB SDRAM, 120GB HDD, Mac OS x 10.7 Lion), White','MB403B/A',0),(93,'B002C744K6','Apple MacBook Pro MC118LL/A 15.4-Inch Laptop','MC118LL/A',0),(94,'B01EXFDFMM','Apple MMGF2LL/A MacBook Air 13.3-Inch Laptop (5th Gen Intel Core i5 1.6 GHz, 8 GB LPDDR3, 128 GB)','MMGF2LLAEARBUDBDL',83700),(95,'B01EXFDFMM','Apple MMGF2LL/A MacBook Air 13.3-Inch Laptop (5th Gen Intel Core i5 1.6 GHz, 8 GB LPDDR3, 128 GB)','MMGF2LLAEARBUDBDL',83700),(96,'B06XP4FP8N','Spigen Tough Armor Galaxy S8 Plus Case with Kickstand and Extreme Heavy Duty Protection and Air Cushion Technology for Galaxy S8 Plus (2017) - Black','571CS21695',3499),(97,'B06XP7S564','Spigen Ultra Hybrid Galaxy S8 Plus Case with Air Cushion Technology and Hybrid Drop Protection for Galaxy S8 Plus (2017) - Crystal Clear','571CS21683',2499),(98,'B01EIUJ1AW','Apple MacBook MMGL2LL/A 12-Inch Laptop with Retina Display (1.1GHz Dual Core Intel m3, 8GB RAM, 256GB HD, OS X) Rose Gold','MMGL2LL/A',129900),(99,'B01EIUJ1AW','Apple MacBook MMGL2LL/A 12-Inch Laptop with Retina Display (1.1GHz Dual Core Intel m3, 8GB RAM, 256GB HD, OS X) Rose Gold','MMGL2LL/A',129900),(100,'B01J6B22MC','Apple Macbook Retina Display 12 Inch (Full-HD LED Backlit IPS Display, Intel Core M-5Y31 1.1GHz up to 2.4GHz, 8GB RAM, 256GB SSD, Wi-Fi, Bluetooth 4.0, Gray) (Certified Refurbished)','A1534',89999),(101,'B01EIUEZ1W','Apple MacBook MLHE2LL/A 12-Inch Laptop with Retina Display (1.1GHz Dual Core Intel m3, 8GB RAM, 256GB HD, OS X), Gold','MLHE2LL/A',126800);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-28 15:20:23
