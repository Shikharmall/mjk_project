-- MySQL dump 10.13  Distrib 5.6.24, for Win64 (x86_64)
--
-- Host: 198.71.227.95    Database: mjk
-- ------------------------------------------------------
-- Server version	5.7.26-29-log

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
-- Table structure for table `add_collect_submitted_report`
--

DROP TABLE IF EXISTS `add_collect_submitted_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_collect_submitted_report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_id` varchar(45) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `lt_name` varchar(105) DEFAULT NULL,
  `designation` varchar(205) DEFAULT NULL,
  `digital_signature` varchar(205) DEFAULT NULL,
  `sample_selected_id` varchar(105) DEFAULT NULL,
  `lab_id` bigint(20) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_collect_submitted_report`
--

LOCK TABLES `add_collect_submitted_report` WRITE;
/*!40000 ALTER TABLE `add_collect_submitted_report` DISABLE KEYS */;
INSERT INTO `add_collect_submitted_report` VALUES (1,'G6HVXu',3,'Rahul','Lab Technician','2023-01-20-63ca98d95db6a.png','[1,2]',2,'2023-01-20'),(2,'xV83Ap',3,'Rahul','Lab Technician','2023-01-20-63ca9ba21cc2f.png','[3]',2,'2023-01-20'),(3,'Rs8w72',1,'Akash','Lab Technician','2023-01-20-63caa6f50a261.png','[4,5]',1,'2023-01-20'),(5,'sq6o97',25,'Rihan Shah','Lab Technician','2023-01-21-63cb9740cbb61.png','[7, 8]',3,'2023-01-21'),(6,'RCrcvp',30,'kd lt name','jk designation','2023-01-26-63d2251e8f4ea.png','[12]',5,'2023-01-26'),(7,'pruzZ3',16,'ghj','try','2023-02-03-63dcfc587d20d.png','[22, 23]',4,'2023-02-03'),(8,'4Knb0j',25,'rahul patel','lab Technician','2023-02-08-63e3888756db7.png','[25, 26]',5,'2023-02-08'),(9,'rXkR6z',5,'ggcyc','h hvuv','2023-02-10-63e5ed229dcf5.png','[29]',2,'2023-02-10'),(10,'J0a6WI',25,'rohan patel','lab technician','2023-02-13-63e9de7e605b6.png','[31, 32]',2,'2023-02-13'),(11,'xAHuJE',25,'akash singh','lab Technician','2023-02-13-63e9fa7755e2e.png','[33, 34]',1,'2023-02-13'),(12,'4qxbt6',14,'pooja','lt','2023-02-13-63ea65164d82c.png','[35, 36]',7,'2023-02-13'),(13,'oIzXjS',25,'rahul patel','lab technician','2023-02-14-63eb1048b08e2.png','[37]',1,'2023-02-14'),(14,'cz0IAw',25,'rahul patel','lab technician','2023-02-14-63eb1336e175c.png','[38]',2,'2023-02-14'),(15,'YsRLAb',3,'akash singh','lab Technician','2023-02-14-63eb159ecb0b5.png','[39, 40]',2,'2023-02-14'),(16,'M36y1s',25,'Rahul Patel','Lab Technician','2023-02-16-63ee122364454.png','[41, 42, 43]',8,'2023-02-16'),(17,'ui2UrU',16,'bhavin','bhavin','2023-02-18-63f0742b8a2c0.png','[44]',7,'2023-02-18'),(18,'EwOowk',25,'rahul patel','lab technician','2023-02-21-63f462164670c.png','[45]',8,'2023-02-21'),(19,'SnVY1D',25,'akash Singh','lab technician','2023-02-21-63f462cd41e1f.png','[46]',9,'2023-02-21'),(20,'JjDbLt',25,'rahul patel','lab technician','2023-02-21-63f4723e095e8.png','[47, 48]',9,'2023-02-21'),(21,'1k8yV4',25,'rahul patel','lab technician','2023-02-21-63f472b20adab.png','[49]',8,'2023-02-21'),(22,'XTv8lS',25,'lokesh shah','lab technician','2023-02-21-63f47bd7ea19b.png','[50, 51]',8,'2023-02-21'),(23,'TWhVxV',25,'rahul patel','lab technician','2023-02-21-63f47c41d3946.png','[52]',9,'2023-02-21'),(24,'b6V4Ta',25,'rahul patel','lab technician','2023-02-21-63f48138bb5ad.png','[53]',8,'2023-02-21');
/*!40000 ALTER TABLE `add_collect_submitted_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_collect_submitted_sample`
--

DROP TABLE IF EXISTS `add_collect_submitted_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_collect_submitted_sample` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_id` varchar(45) DEFAULT NULL,
  `collected_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `sample_selected_id` varchar(105) DEFAULT NULL,
  `lab_id` bigint(20) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_collect_submitted_sample`
--

LOCK TABLES `add_collect_submitted_sample` WRITE;
/*!40000 ALTER TABLE `add_collect_submitted_sample` DISABLE KEYS */;
INSERT INTO `add_collect_submitted_sample` VALUES (1,'G6HVXu',1,3,'1',2,'2023-01-20'),(2,'G6HVXu',1,3,'2',2,'2023-01-20'),(3,'xV83Ap',2,3,'3',2,'2023-01-20'),(4,'Rs8w72',3,1,'4',1,'2023-01-20'),(5,'Rs8w72',3,1,'5',1,'2023-01-20'),(7,'sq6o97',5,25,'7',3,'2023-01-21'),(8,'sq6o97',5,25,'8',3,'2023-01-21'),(9,'RCrcvp',6,30,'12',5,'2023-01-26'),(10,'pruzZ3',7,16,'22',4,'2023-02-03'),(11,'pruzZ3',7,16,'23',4,'2023-02-03'),(12,'4Knb0j',8,25,'25',5,'2023-02-08'),(13,'4Knb0j',8,25,'26',5,'2023-02-08'),(14,'rXkR6z',9,5,'29',2,'2023-02-10'),(15,'J0a6WI',10,25,'31',2,'2023-02-13'),(16,'J0a6WI',10,25,'32',2,'2023-02-13'),(17,'xAHuJE',11,25,'33',1,'2023-02-13'),(18,'xAHuJE',11,25,'34',1,'2023-02-13'),(19,'4qxbt6',12,14,'35',7,'2023-02-13'),(20,'4qxbt6',12,14,'36',7,'2023-02-13'),(21,'oIzXjS',13,25,'37',1,'2023-02-14'),(22,'cz0IAw',14,25,'38',2,'2023-02-14'),(23,'YsRLAb',15,3,'39',2,'2023-02-14'),(24,'YsRLAb',15,3,'40',2,'2023-02-14'),(25,'M36y1s',16,25,'41',8,'2023-02-16'),(26,'M36y1s',16,25,'42',8,'2023-02-16'),(27,'M36y1s',16,25,'43',8,'2023-02-16'),(28,'ui2UrU',17,16,'44',7,'2023-02-18'),(29,'EwOowk',18,25,'45',8,'2023-02-21'),(30,'SnVY1D',19,25,'46',9,'2023-02-21'),(31,'JjDbLt',20,25,'47',9,'2023-02-21'),(32,'JjDbLt',20,25,'48',9,'2023-02-21'),(33,'1k8yV4',21,25,'49',8,'2023-02-21'),(34,'XTv8lS',22,25,'50',8,'2023-02-21'),(35,'XTv8lS',22,25,'51',8,'2023-02-21'),(36,'TWhVxV',23,25,'52',9,'2023-02-21'),(37,'b6V4Ta',24,25,'53',8,'2023-02-21');
/*!40000 ALTER TABLE `add_collect_submitted_sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_collected_report`
--

DROP TABLE IF EXISTS `add_collected_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_collected_report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_id` varchar(45) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `lt_name` varchar(205) DEFAULT NULL,
  `designation` varchar(205) DEFAULT NULL,
  `digital_signature` varchar(205) DEFAULT NULL,
  `sample_selected_id` varchar(205) DEFAULT NULL,
  `lab_id` bigint(20) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_collected_report`
--

LOCK TABLES `add_collected_report` WRITE;
/*!40000 ALTER TABLE `add_collected_report` DISABLE KEYS */;
INSERT INTO `add_collected_report` VALUES (1,'lH5Koq',3,'Rahul','Lab Technician','2023-01-20-63ca963478d96.png','[1,2]',2,'2023-01-20'),(2,'IFdTBz',3,'Rahul','Lab Technician','2023-01-20-63ca9b92d75d3.png','[3]',2,'2023-01-20'),(3,'K1hcYw',3,'Akash Singh','Lab Technician','2023-01-20-63caa599b2379.png','[4,5]',1,'2023-01-20'),(5,'ltNRTB',25,'Ritesh Patel','Lab Technician','2023-01-21-63cb82022d8a2.png','[8, 7]',3,'2023-01-21'),(9,'J9K92X',30,'kd lt name','jk designation','2023-01-26-63d21df0e80c0.png','[12]',5,'2023-01-26'),(10,'16oShi',5,'vk','HIV','2023-01-31-63d890e57e6bd.png','[14, 11]',6,'2023-01-31'),(11,'7020Qz',5,'testing','HIV','2023-01-31-63d894d687da9.png','[15]',5,'2023-01-31'),(12,'c6osJo',30,'gcrxfcxr','chgcgccg','2023-01-31-63d89e82b4ff1.png','[17]',2,'2023-01-31'),(13,'8gJsqm',30,'gcgx','vvv g','2023-01-31-63d8a2755a27e.png','[18]',3,'2023-01-31'),(14,'qs0QMt',16,'xyz','xyz','2023-02-03-63dcd69024a74.png','[23, 22]',4,'2023-02-03'),(15,'IL8fYK',25,'rahul patel','lab Technician','2023-02-08-63e3879aacbc6.png','[26, 25]',5,'2023-02-08'),(16,'oas9kW',5,'fgff','bhbh','2023-02-10-63e5de8a078e7.png','[29]',2,'2023-02-10'),(17,'u1alVT',5,'ggggh','hhhj','2023-02-10-63e5df99c526c.png','[28]',3,'2023-02-10'),(18,'4HcaZU',5,'hh g','uvyv','2023-02-10-63e5e0e04746d.png','[16]',4,'2023-02-10'),(19,'gKBPn4',5,'hvvyvyyc','hvhvvh','2023-02-10-63e5e30c90212.png','[16]',4,'2023-02-10'),(20,'jn42sC',5,'g cgv','vhvvhv','2023-02-10-63e5e775305e4.png','[16]',4,'2023-02-10'),(21,'SAbqRv',5,'g gvyv','h hvv','2023-02-10-63e5e82f68a47.png','[16]',4,'2023-02-10'),(22,'tv478f',5,'gtc','ibbu','2023-02-10-63e5e8e05916a.png','[16]',4,'2023-02-10'),(23,'OptJT7',5,'txt','h ub','2023-02-10-63e5eb1253aef.png','[30]',4,'2023-02-10'),(24,'GWglKN',25,'rohan patel','lab technician','2023-02-13-63e9dd6f41131.png','[32, 31]',2,'2023-02-13'),(25,'fqKxTY',25,'akash singh','lab Technician','2023-02-13-63e9f9537111f.png','[34, 33]',1,'2023-02-13'),(26,'NKY45L',14,'N/A','N/A','2023-02-13-63ea62d06df2b.png','[35]',7,'2023-02-13'),(27,'2QlQvr',14,'harsh','1262817','2023-02-13-63ea64909ce21.png','[36]',7,'2023-02-13'),(28,'l8k47a',25,'rahul patel','lab technician','2023-02-14-63eb0faf4c8ba.png','[37]',1,'2023-02-14'),(29,'zg8JNd',25,'rahul patel','lab technician','2023-02-14-63eb127a0d003.png','[38]',2,'2023-02-14'),(30,'twoZAw',3,'akash singh','lab Technician','2023-02-14-63eb141d29046.png','[39]',2,'2023-02-14'),(31,'T53XND',3,'akash singh','lab Technician','2023-02-14-63eb14eadd300.png','[40]',2,'2023-02-14'),(32,'1jm4gx',25,'Rahul Patel','Lab Technician','2023-02-16-63ee102e0dc8d.png','[43, 42, 41]',8,'2023-02-16'),(33,'NB0ryX',16,'Shhsjw','sjsj','2023-02-18-63f07355dba80.png','[44]',7,'2023-02-18'),(34,'OceNTv',25,'rahul patel','lab technician','2023-02-21-63f4605309023.png','[45]',8,'2023-02-21'),(35,'pCaEx2',25,'akash singh','lab technician','2023-02-21-63f460d5e94db.png','[46]',9,'2023-02-21'),(36,'o75XJB',25,'rahul patel','lab technician','2023-02-21-63f46f366a846.png','[48, 47]',9,'2023-02-21'),(37,'SjRMz9',25,'rahul Patel','lab Technician','2023-02-21-63f470c660d61.png','[49]',8,'2023-02-21'),(38,'AWUCVB',25,'priyank khanna','lab technician','2023-02-21-63f47b23a73b5.png','[51, 50]',8,'2023-02-21'),(39,'SproSe',25,'lokesh shah','lab technician','2023-02-21-63f47b77c9326.png','[52]',9,'2023-02-21'),(40,'zySh8j',25,'rahul patel','lab technician','2023-02-21-63f47fdd88819.png','[53]',8,'2023-02-21'),(41,'w91feh',25,'rahul patel','lab technician','2023-02-21-63f482089987b.png','[54]',8,'2023-02-21');
/*!40000 ALTER TABLE `add_collected_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_collected_sample`
--

DROP TABLE IF EXISTS `add_collected_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_collected_sample` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_id` varchar(45) DEFAULT NULL,
  `collected_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `sample_selected_id` varchar(105) DEFAULT NULL,
  `lab_id` bigint(20) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_collected_sample`
--

LOCK TABLES `add_collected_sample` WRITE;
/*!40000 ALTER TABLE `add_collected_sample` DISABLE KEYS */;
INSERT INTO `add_collected_sample` VALUES (1,'lH5Koq',1,3,'1',2,'2023-01-20'),(2,'lH5Koq',1,3,'2',2,'2023-01-20'),(3,'IFdTBz',2,3,'3',2,'2023-01-20'),(4,'K1hcYw',3,3,'4',1,'2023-01-20'),(5,'K1hcYw',3,3,'5',1,'2023-01-20'),(7,'ltNRTB',5,25,'8',3,'2023-01-21'),(8,'ltNRTB',5,25,'7',3,'2023-01-21'),(12,'J9K92X',9,30,'12',5,'2023-01-26'),(13,'16oShi',10,5,'14',6,'2023-01-31'),(14,'16oShi',10,5,'11',6,'2023-01-31'),(15,'7020Qz',11,5,'15',5,'2023-01-31'),(16,'c6osJo',12,30,'17',2,'2023-01-31'),(17,'8gJsqm',13,30,'18',3,'2023-01-31'),(18,'qs0QMt',14,16,'23',4,'2023-02-03'),(19,'qs0QMt',14,16,'22',4,'2023-02-03'),(20,'IL8fYK',15,25,'26',5,'2023-02-08'),(21,'IL8fYK',15,25,'25',5,'2023-02-08'),(22,'oas9kW',16,5,'29',2,'2023-02-10'),(23,'u1alVT',17,5,'28',3,'2023-02-10'),(24,'4HcaZU',18,5,'16',4,'2023-02-10'),(25,'gKBPn4',19,5,'16',4,'2023-02-10'),(26,'jn42sC',20,5,'16',4,'2023-02-10'),(27,'SAbqRv',21,5,'16',4,'2023-02-10'),(28,'tv478f',22,5,'16',4,'2023-02-10'),(29,'OptJT7',23,5,'30',4,'2023-02-10'),(30,'GWglKN',24,25,'32',2,'2023-02-13'),(31,'GWglKN',24,25,'31',2,'2023-02-13'),(32,'fqKxTY',25,25,'34',1,'2023-02-13'),(33,'fqKxTY',25,25,'33',1,'2023-02-13'),(34,'NKY45L',26,14,'35',7,'2023-02-13'),(35,'2QlQvr',27,14,'36',7,'2023-02-13'),(36,'l8k47a',28,25,'37',1,'2023-02-14'),(37,'zg8JNd',29,25,'38',2,'2023-02-14'),(38,'twoZAw',30,3,'39',2,'2023-02-14'),(39,'T53XND',31,3,'40',2,'2023-02-14'),(40,'1jm4gx',32,25,'43',8,'2023-02-16'),(41,'1jm4gx',32,25,'42',8,'2023-02-16'),(42,'1jm4gx',32,25,'41',8,'2023-02-16'),(43,'NB0ryX',33,16,'44',7,'2023-02-18'),(44,'OceNTv',34,25,'45',8,'2023-02-21'),(45,'pCaEx2',35,25,'46',9,'2023-02-21'),(46,'o75XJB',36,25,'48',9,'2023-02-21'),(47,'o75XJB',36,25,'47',9,'2023-02-21'),(48,'SjRMz9',37,25,'49',8,'2023-02-21'),(49,'AWUCVB',38,25,'51',8,'2023-02-21'),(50,'AWUCVB',38,25,'50',8,'2023-02-21'),(51,'SproSe',39,25,'52',9,'2023-02-21'),(52,'zySh8j',40,25,'53',8,'2023-02-21'),(53,'w91feh',41,25,'54',8,'2023-02-21');
/*!40000 ALTER TABLE `add_collected_sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_collected_sample_multiple_images`
--

DROP TABLE IF EXISTS `add_collected_sample_multiple_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_collected_sample_multiple_images` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) DEFAULT NULL,
  `sample_id` bigint(20) DEFAULT NULL,
  `image` varchar(205) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_collected_sample_multiple_images`
--

LOCK TABLES `add_collected_sample_multiple_images` WRITE;
/*!40000 ALTER TABLE `add_collected_sample_multiple_images` DISABLE KEYS */;
INSERT INTO `add_collected_sample_multiple_images` VALUES (1,3,1,'2023-01-20-63ca9829ba369.png','2023-01-20'),(2,3,1,'2023-01-04-63b4fc4689b2c.png','2023-01-20'),(3,3,2,'2023-01-20-63ca98460c9db.png','2023-01-20'),(4,3,2,'2023-01-20-63ca9858a458c.png','2023-01-20'),(5,3,3,'2023-01-20-63ca98460c9db.png','2023-01-20'),(6,3,3,'2023-01-20-63ca9858a458c.png','2023-01-20'),(7,1,4,'2023-01-20-63ca98460c9db.png','2023-01-20'),(8,1,4,'2023-01-20-63ca9858a458c.png','2023-01-20'),(9,1,5,'2023-01-20-63ca98460c9db.png','2023-01-20'),(10,1,5,'2023-01-20-63ca9858a458c.png','2023-01-20'),(11,7,6,'2023-01-21-63cb7a48acc62.png','2023-01-21'),(12,25,7,'2023-01-21-63cb9602b4f4c.png','2023-01-21'),(13,25,7,'2023-01-21-63cb96298535d.png','2023-01-21'),(14,25,7,'2023-01-21-63cb96426cd59.png','2023-01-21'),(15,25,7,'2023-01-21-63cb96594a489.png','2023-01-21'),(16,25,8,'2023-01-21-63cb96859cad5.png','2023-01-21'),(17,25,8,'2023-01-21-63cb96e50774b.png','2023-01-21'),(18,25,8,'2023-01-21-63cb9711c89aa.png','2023-01-21'),(19,30,12,'2023-01-26-63d2250360432.png','2023-01-26'),(20,16,22,'2023-02-03-63dcfbdb0ebea.png','2023-02-03'),(21,16,22,'2023-02-03-63dcfbea67675.png','2023-02-03'),(22,25,25,'2023-02-08-63e3880b2ac93.png','2023-02-08'),(23,25,25,' 2023-02-08-63e38821c190c.png','2023-02-08'),(24,25,26,'2023-02-08-63e3883dafe71.png','2023-02-08'),(25,25,26,' 2023-02-08-63e38851b6b60.png','2023-02-08'),(26,25,26,' 2023-02-08-63e38865f18d7.png','2023-02-08'),(27,5,29,'2023-02-10-63e5ed09d555d.png','2023-02-10'),(28,25,31,'2023-02-13-63e9de1518944.png','2023-02-13'),(29,25,31,'2023-02-13-63e9de288f383.png','2023-02-13'),(30,25,32,'2023-02-13-63e9de446387d.png','2023-02-13'),(31,25,32,'2023-02-13-63e9de5c74e20.png','2023-02-13'),(32,25,33,'2023-02-13-63e9f9ae3082a.png','2023-02-13'),(33,25,33,'2023-02-13-63e9f9c4062a6.png','2023-02-13'),(34,25,33,'2023-02-13-63e9f9de03cd2.png','2023-02-13'),(35,25,33,'2023-02-13-63e9f9f905dc9.png','2023-02-13'),(36,25,34,'2023-02-13-63e9fa1c1be6f.png','2023-02-13'),(37,25,34,'2023-02-13-63e9fa3be9924.png','2023-02-13'),(38,25,34,'2023-02-13-63e9fa58c54c7.png','2023-02-13'),(39,14,36,'2023-02-13-63ea650306207.png','2023-02-13'),(40,25,37,'2023-02-14-63eb101ce32fd.png','2023-02-14'),(41,25,37,'2023-02-14-63eb102fb8a8d.png','2023-02-14'),(42,25,38,'2023-02-14-63eb12d7039e4.png','2023-02-14'),(43,25,38,'2023-02-14-63eb131b24d15.png','2023-02-14'),(44,3,39,'2023-02-14-63eb1529c6f11.png','2023-02-14'),(45,3,39,'2023-02-14-63eb153d01adf.png','2023-02-14'),(46,3,40,'2023-02-14-63eb1558ee461.png','2023-02-14'),(47,3,40,'2023-02-14-63eb1584efa8b.png','2023-02-14'),(48,25,41,'2023-02-16-63ee115e37b44.png','2023-02-16'),(49,25,41,'2023-02-16-63ee1180a8e78.png','2023-02-16'),(50,25,42,'2023-02-16-63ee11abe305e.png','2023-02-16'),(51,25,42,'2023-02-16-63ee11c4d3b4a.png','2023-02-16'),(52,25,42,'2023-02-16-63ee11e6061db.png','2023-02-16'),(53,25,43,'2023-02-16-63ee120253256.png','2023-02-16'),(54,16,44,'2023-02-18-63f07412bdc71.png','2023-02-18'),(55,25,45,'2023-02-21-63f461eb20812.png','2023-02-21'),(56,25,46,'2023-02-21-63f462b1aa14f.png','2023-02-21'),(57,25,47,'2023-02-21-63f4712a52222.png','2023-02-21'),(58,25,48,'2023-02-21-63f471dcdd55e.png','2023-02-21'),(59,25,47,'2023-02-21-63f47206a4a8d.png','2023-02-21'),(60,25,48,'2023-02-21-63f47223526fd.png','2023-02-21'),(61,25,49,'2023-02-21-63f4729937deb.png','2023-02-21'),(62,25,50,'2023-02-21-63f47baa9072e.png','2023-02-21'),(63,25,51,'2023-02-21-63f47bbdd776a.png','2023-02-21'),(64,25,52,'2023-02-21-63f47c26c1005.png','2023-02-21'),(65,25,53,'2023-02-21-63f481254104b.png','2023-02-21');
/*!40000 ALTER TABLE `add_collected_sample_multiple_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_district`
--

DROP TABLE IF EXISTS `add_district`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_district` (
  `district_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `gps_address` text,
  `latitude` varchar(105) DEFAULT NULL,
  `longitude` varchar(105) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`district_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_district`
--

LOCK TABLES `add_district` WRITE;
/*!40000 ALTER TABLE `add_district` DISABLE KEYS */;
INSERT INTO `add_district` VALUES (1,'Adajan','Adajan, Surat, Gujarat, India','21.1959098','72.79330209999999','2022-12-20 17:57:49'),(2,'Katargam','Katargam, Surat, Gujarat, India','21.2266205','72.8312383','2022-12-20 17:57:56'),(3,'Varachha','Varachha, Surat, Gujarat, India','21.2021189','72.8672703','2022-12-20 19:20:38'),(4,'Kapodara','Kapodra, Surat, Gujarat, India','21.2187655','72.8745776','2022-12-20 19:21:08'),(5,'Malad','Malad West, Mumbai, Maharashtra, India','19.1889541','72.835543','2023-01-18 15:56:59'),(6,'Goregoan','Goregaon, Mumbai, Maharashtra, India','19.1662566','72.8525696','2023-01-18 16:05:15'),(7,'Mumbai City','Mumbai, Maharashtra, India','19.0759837','72.8776559','2023-01-18 16:15:55'),(8,'Hirabaug','Hirabaugh, Surat, Gujarat, India','21.211125','72.8630434','2023-01-24 11:08:50'),(9,'Mota Varachha','Mota Varachha, Surat, Gujarat, India','21.2408267','72.8806069','2023-01-26 10:46:27'),(10,'Ahmedabad','Ahmedabad, Gujarat, India','23.022505','72.5713621','2023-01-26 10:46:36'),(12,'Test12','Surat, Gujarat, India','21.1702401','72.83106070000001','2023-01-27 20:56:25'),(14,'Chhipwad','Chhipwad, Valsad, Gujarat, India','20.6207084','72.9327686','2023-02-14 12:10:26'),(15,'Valsad','Valsad, Gujarat, India','20.5992349','72.9342451','2023-02-16 11:06:46');
/*!40000 ALTER TABLE `add_district` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_hospital`
--

DROP TABLE IF EXISTS `add_hospital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_hospital` (
  `hospital_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(105) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT 'N/A',
  `email` varchar(105) DEFAULT 'N/A',
  `gps_address` text,
  `latitude` varchar(105) DEFAULT NULL,
  `longitude` varchar(105) DEFAULT NULL,
  `image` varchar(105) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`hospital_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_hospital`
--

LOCK TABLES `add_hospital` WRITE;
/*!40000 ALTER TABLE `add_hospital` DISABLE KEYS */;
INSERT INTO `add_hospital` VALUES (1,'P P Savani','N/A','N/A','P.P.Savani Hospital, Varachha Main Rd, opposite Kapodra Police Station, Vishnu Nagar Society, Vishnu Nager, Singanpor, Surat, Gujarat, India','21.2270149','72.8213741','2022-12-20-63a191e0ea289.png',1,'2022-12-20 16:13:45'),(2,'P P Maniya Hospital','N/A','N/A','P P Maniya Children & Woman Hospital, Varachha Main Road, near Baroda pristage, Vallabhnagar Society, Varachha, Surat, Gujarat, India','21.2116582','72.8547892','2022-12-20-63a1956fa168a.png',1,'2022-12-20 16:28:55'),(3,'Kiran Hospital','N/A','N/A','Kiran Hospital, near Sumul Dairy Road, Tunki, Katargam, Surat, Gujarat, India','21.2184767','72.8368004','2022-12-20-63a195f714b38.png',1,'2022-12-20 16:31:11'),(5,'Topiwala Hospital','N/A','N/A','Bandu Gore Marg, Kakaji Nagar, Jawahar Nagar, Goregaon West, Mumbai, Maharashtra, India','19.1650206','72.8474308','2023-01-18-63c7cbe69af25.png',1,'2023-01-18 16:07:26'),(6,'MNHP( Motilal Nagar Health Post)','N/A','N/A','Motilal Nagar I, Goregaon West, Mumbai, Maharashtra, India','19.1574784','72.83825470000001','2023-01-18-63c7cc29b5d46.png',0,'2023-01-18 16:08:33'),(7,'Ayush Hospital','N/A','N/A','Muslim Nagar, RP Nagar, Dharavi, Mumbai, Maharashtra, India','19.0394447','72.8548711','2023-01-18-63c7cd6d573db.png',1,'2023-01-18 16:13:57'),(8,'Bal Gulabi Hospital','N/A','N/A','Cluster_mumbai_96 Sanket Building, Cluster_mumbai_96, Anmol Co-Operative Housing Society, Babasaheb Ambedkar Nagar, Dadar, Mumbai, Maharashtra 400028, India','19.0163414','72.8373546','2023-01-18-63c7d0035eebf.png',0,'2023-01-18 16:24:59'),(9,'New Civil Hospital Surat','N/A','N/A','DUMAS BEACH, Dumas Road, Sultanabad, Piplod, Surat, Gujarat, India','21.0840327','72.7093454','2023-01-23-63ce77683b661.png',1,'2023-01-23 17:32:48'),(10,'test hospital','N/A','N/A','P P Maniya Children & Woman Hospital, Varachha Main Road, near Baroda pristage, Vallabhnagar Society, Varachha, Surat, Gujarat, India','21.2116582','72.8547892','2023-01-25-63d13c3a8cdc7.png',1,'2023-01-25 19:57:06'),(13,'Cooper Hospital','N/A','N/A','Cooper Hospital OPD, Indravadan Oza Road, JVPD Scheme, Juhu, Mumbai, Maharashtra, India','19.1077239','72.8354382','2023-02-13-63ea5f7ec6f4a.png',1,'2023-02-13 21:34:14'),(14,'Amit Hospital','N/A','N/A','Amit Hospital, Halar Road, beside SBI Bnak, opp. Avabai High School, Dharampur, Valsad, Gujarat, India','20.6098396','72.927289','2023-02-16-63edc213b1df0.png',1,'2023-02-16 11:11:39'),(15,'Lotus Hospital','N/A','N/A','Lotus Hospital Road, Ramwadi, Ramji Tekra, Valsad, Gujarat, India','20.6147567','72.9264922','2023-02-16-63edc25187577.png',1,'2023-02-16 11:12:41');
/*!40000 ALTER TABLE `add_hospital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_invoice_kilometer_sample`
--

DROP TABLE IF EXISTS `add_invoice_kilometer_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_invoice_kilometer_sample` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) DEFAULT NULL,
  `sample_id` bigint(20) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_invoice_kilometer_sample`
--

LOCK TABLES `add_invoice_kilometer_sample` WRITE;
/*!40000 ALTER TABLE `add_invoice_kilometer_sample` DISABLE KEYS */;
INSERT INTO `add_invoice_kilometer_sample` VALUES (1,1,9,'2023-02-11'),(2,1,2,'2023-02-11'),(3,1,1,'2023-02-11');
/*!40000 ALTER TABLE `add_invoice_kilometer_sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_invoice_sample`
--

DROP TABLE IF EXISTS `add_invoice_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_invoice_sample` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) DEFAULT NULL,
  `sample_id` bigint(20) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_invoice_sample`
--

LOCK TABLES `add_invoice_sample` WRITE;
/*!40000 ALTER TABLE `add_invoice_sample` DISABLE KEYS */;
INSERT INTO `add_invoice_sample` VALUES (1,1,12,'2023-02-11'),(2,1,3,'2023-02-11'),(3,1,2,'2023-02-11'),(4,1,1,'2023-02-11'),(5,2,43,'2023-02-20'),(6,2,42,'2023-02-20'),(7,2,41,'2023-02-20');
/*!40000 ALTER TABLE `add_invoice_sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_kilometer_invoice`
--

DROP TABLE IF EXISTS `add_kilometer_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_kilometer_invoice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `district_id` bigint(20) DEFAULT NULL,
  `rate` varchar(105) DEFAULT NULL,
  `total_kilometer` varchar(105) DEFAULT NULL,
  `amount` varchar(105) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `created_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_kilometer_invoice`
--

LOCK TABLES `add_kilometer_invoice` WRITE;
/*!40000 ALTER TABLE `add_kilometer_invoice` DISABLE KEYS */;
INSERT INTO `add_kilometer_invoice` VALUES (1,'2023-01-01','2023-01-31',3,'10','1670','16700','2023-02-11','12:07:20');
/*!40000 ALTER TABLE `add_kilometer_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_lab`
--

DROP TABLE IF EXISTS `add_lab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_lab` (
  `lab_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `district_id` bigint(20) DEFAULT NULL,
  `gps_address` text,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`lab_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_lab`
--

LOCK TABLES `add_lab` WRITE;
/*!40000 ALTER TABLE `add_lab` DISABLE KEYS */;
INSERT INTO `add_lab` VALUES (1,'J K V Laboratory',2,'Vasta Devdi Road, Tunki, Katargam, Surat, Gujarat, India','21.2159764','72.8365748','2022-12-20-63a1abba5f97d.png',1,'2022-12-20 18:04:02'),(2,'Prabhat Laboratory',3,'PRABHAT PATHOLOGY LABORATORY, Varachha Main Road, above MADHI NI KHAMANI, Narmad Nagar, Tapsil Society, Hirabaugh, Surat, Gujarat, India','21.2146167','72.8608192','2022-12-20-63a1bea1d71d0.png',1,'2022-12-20 19:24:41'),(3,'Appa Pada',5,'Appa Pada Road, Malad, Datta Wadi, Ramgad Nagar, Malad East, Mumbai, Maharashtra, India','19.1906638','72.8710409','2023-01-18-63c7ca5a148e0.png',1,'2023-01-18 16:00:50'),(4,'UHC DHARAVI LAB',7,'60 Feet Road, Muslim Nagar, Matunga Labour Camp, Dharavi, Mumbai, Maharashtra, India','19.0384667','72.85305869999999','2023-01-18-63c7cf85b1ceb.png',1,'2023-01-18 16:22:53'),(5,'Shadhna Laboratory',3,'Hirabag Circle, Adarsh Society, Ram Nagar, Hirabaugh, Surat, Gujarat','21.2159001','72.86315069999999','2023-01-24-63cf6ec2b5460.png',1,'2023-01-24 11:07:31'),(6,'test',6,'Surat, Gujarat, India','21.1702401','72.83106070000001','2023-01-26-63d206f7d2ff9.png',1,'2023-01-26 10:22:07'),(7,'Sunflower Lab',5,'Sunflower Laboratory And Diagnostic Center, Dasha Shrimali Nagar, Malad West, Mumbai, Maharashtra, India','19.1928953','72.8417094','2023-02-13-63ea60068a390.png',1,'2023-02-13 21:36:30'),(8,'Aarsh Lab',15,'AARSH LAB, Kapadia Chal, Valsad, Gujarat, India','20.6114102','72.9323088','2023-02-16-63edc28d21071.png',1,'2023-02-16 11:13:41'),(9,'Shreenath Clinical Laboratory',15,'Shreenath Clinical Laboratory, Station Road, opp. कल्यान बॉग, Kapadia Chal, Valsad, Gujarat, India','20.6100756','72.92909949999999','2023-02-16-63edc2c1a476b.png',1,'2023-02-16 11:14:33');
/*!40000 ALTER TABLE `add_lab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_sample_box_detail`
--

DROP TABLE IF EXISTS `add_sample_box_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_sample_box_detail` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `collected_from` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `district_id` bigint(20) DEFAULT NULL,
  `lab_id` bigint(20) DEFAULT NULL,
  `sample_auto_id` bigint(20) DEFAULT NULL,
  `sample_id` varchar(45) DEFAULT NULL,
  `scan_code` varchar(105) DEFAULT NULL,
  `nikshay_id` varchar(105) DEFAULT NULL,
  `patient` varchar(105) NOT NULL,
  `invoice_photo` varchar(205) DEFAULT NULL,
  `type_test_for` varchar(105) DEFAULT NULL,
  `type_patient` varchar(105) DEFAULT NULL,
  `no_of_sample` varchar(45) DEFAULT NULL,
  `specimen_id` bigint(20) DEFAULT NULL,
  `test_id` bigint(20) DEFAULT NULL,
  `sample_meter_name` varchar(205) DEFAULT NULL,
  `sample_meter_photo` varchar(205) DEFAULT NULL,
  `sample_date_time` varchar(105) DEFAULT NULL,
  `kilometer` varchar(105) DEFAULT NULL,
  `degree` varchar(105) DEFAULT NULL,
  `sample_box_photo` varchar(205) DEFAULT NULL,
  `sample_box_name` varchar(205) DEFAULT NULL,
  `map_area_name` text,
  `from_latitude` varchar(105) DEFAULT NULL,
  `from_longitude` varchar(105) DEFAULT NULL,
  `to_sample_date_time` varchar(105) DEFAULT NULL,
  `to_sample_meter_photo` varchar(105) DEFAULT NULL,
  `to_kilometer` varchar(105) DEFAULT NULL,
  `to_actual_kilometer` varchar(105) DEFAULT NULL,
  `to_degree` varchar(105) DEFAULT NULL,
  `to_sample_box_photo` varchar(105) DEFAULT NULL,
  `to_map_area_name` text,
  `to_latitude` varchar(105) DEFAULT NULL,
  `to_longitude` varchar(105) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `collect_lab_sample_meter_name` varchar(205) DEFAULT 'N/A',
  `collect_lab_sample_meter_photo` varchar(205) DEFAULT 'N/A',
  `collect_lab_sample_date_time` varchar(105) DEFAULT 'N/A',
  `collect_lab_kilometer` varchar(105) DEFAULT 'N/A',
  `collect_actual_kilometer` varchar(105) DEFAULT NULL,
  `collect_map_area_name` text,
  `collect_latitude` varchar(105) DEFAULT NULL,
  `collect_longitude` varchar(105) DEFAULT NULL,
  `submit_hospital_sample_meter_name` varchar(105) DEFAULT 'N/A',
  `submit_hospital_sample_meter_photo` varchar(105) DEFAULT 'N/A',
  `submit_hospital_sample_date_time` varchar(105) DEFAULT 'N/A',
  `submit_hospital_kilometer` varchar(105) DEFAULT 'N/A',
  `submit_actual_kilometer` varchar(105) DEFAULT NULL,
  `submit_map_area_name` text,
  `submit_latitude` varchar(105) DEFAULT NULL,
  `submit_longitude` varchar(105) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `sample_invoice_status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_sample_box_detail`
--

LOCK TABLES `add_sample_box_detail` WRITE;
/*!40000 ALTER TABLE `add_sample_box_detail` DISABLE KEYS */;
INSERT INTO `add_sample_box_detail` VALUES (1,1,3,3,2,1,'GJ24D9','6898765','4132442','Rahul Patel','2023-01-20-63ca942cde120.png','Treatment','Public','2',4,1,'Spedometer','2023-01-20-63ca950218015.png','02/01/2023, 01:00 PM','80','10','2023-01-20-63ca9523a0488.png','Sample Box',NULL,NULL,NULL,'20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','150',NULL,'15','2023-01-20-63ca95f52ec4c.png',NULL,NULL,NULL,'2023-01-20 18:50:49',3,'N/A','2023-01-20-63ca9858a458c.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,'N/A','2023-01-20-63ca9a01cf075.png','03/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20',1),(2,1,3,3,2,1,'GJ24D9','896789','5634566','Isha Shah','2023-01-20-63ca9463e8e82.png','Diagnosis','Public','2',4,1,'Spedometer','2023-01-20-63ca950218015.png','02/01/2023, 01:00 PM','80','10','2023-01-20-63ca9523a0488.png','Sample Box',NULL,NULL,NULL,'20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','150',NULL,'15','2023-01-20-63ca95f52ec4c.png',NULL,NULL,NULL,'2023-01-20 18:50:49',3,'N/A','2023-01-20-63ca9858a458c.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,'N/A','2023-01-20-63ca9a01cf075.png','03/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20',1),(3,1,3,3,2,2,'8eLyGY','90788','136577','Reena Patel','2023-01-20-63ca9a01cf075.png','Diagnosis','Public','2',4,1,'Spedometer','2023-01-20-63ca9b3c1402d.png','02/01/2023, 01:00 PM','80','10','2023-01-20-63ca9b1c12037.png','Sample Box',NULL,NULL,NULL,'20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','150',NULL,'15','2023-01-20-63ca9ba21cc2f.png',NULL,NULL,NULL,'2023-01-20 19:16:55',3,'N/A','2023-01-20-63caa16c494a6.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,'N/A','2023-01-20-63caa21f9fa36.png','20/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20',1),(4,3,1,2,1,3,'ZC0AQd','675788','453554','Roshni Patel','2023-01-20-63ca9a01cf075.png','Treatment','Public','2',4,1,'Spedometer','2023-01-20-63ca9b3c1402d.png','20/01/2023, 01:00 PM','80','10','2023-01-20-63caa495095ac.png','Sample Box',NULL,NULL,NULL,'20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','150',NULL,'15','2023-01-20-63caa5c9442d7.png',NULL,NULL,NULL,'2023-01-20 19:58:29',3,'N/A','2023-01-20-63caa6ca98477.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,'N/A','2023-01-20-63caa770b03a7.png','20/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20',0),(5,3,1,2,1,3,'ZC0AQd','05788','246477','Rohan Patel','2023-01-20-63caa495095ac.png','Treatment','Private','2',4,1,'Spedometer','2023-01-20-63ca9b3c1402d.png','20/01/2023, 01:00 PM','80','10','2023-01-20-63caa495095ac.png','Sample Box',NULL,NULL,NULL,'20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','150',NULL,'15','2023-01-20-63caa5c9442d7.png',NULL,NULL,NULL,'2023-01-20 19:58:29',3,'N/A','2023-01-20-63caa6ca98477.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,'N/A','2023-01-20-63caa770b03a7.png','20/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20',0),(7,6,25,5,3,5,'dEcBAL','12345678','Zoo5744','Anjali Prajapati','2023-01-21-63cb80716e304.png','Diagnosis','Private','3',1,3,'MNHP( Motilal Nagar Health Post)','2023-01-21-63cb801271274.png','21-01-2023  11:31 AM','50','5','2023-01-21-63cb8042049e3.png','MNHP( Motilal Nagar Health Post)','Motilal Nagar I, Goregaon West, Mumbai, Maharashtra, India','19.1574784','72.83825470000001','21-01-2023  11:40 AM','2023-01-21-63cb816a421a3.png','120',NULL,'8','2023-01-21-63cb81b9c8e69.png','Appa Pada Road, Malad, Datta Wadi, Ramgad Nagar, Malad East, Mumbai, Maharashtra, India','19.1906638','72.8710409','2023-01-21 11:37:40',3,'N/A','2023-01-21-63cb95b8ca970.png','21-01-2023 01:11 PM','180',NULL,NULL,NULL,NULL,'N/A','2023-01-21-63cb97bc46b5f.png','21-01-2023 01:14 PM','200',NULL,NULL,NULL,NULL,'2023-01-21',0),(8,6,25,5,3,5,'dEcBAL','https://luatbaoloi.com','SC47478','Raina Singh','2023-01-21-63cb80e3bb749.png','Treatment','Public','1',5,2,'MNHP( Motilal Nagar Health Post)','2023-01-21-63cb801271274.png','21-01-2023  11:31 AM','50','5','2023-01-21-63cb8042049e3.png','MNHP( Motilal Nagar Health Post)',NULL,NULL,NULL,'21-01-2023  11:40 AM','2023-01-21-63cb816a421a3.png','120',NULL,'8','2023-01-21-63cb81b9c8e69.png',NULL,NULL,NULL,'2023-01-21 11:37:40',3,'N/A','2023-01-21-63cb95b8ca970.png','21-01-2023 01:11 PM','180',NULL,NULL,NULL,NULL,'N/A','2023-01-21-63cb97bc46b5f.png','21-01-2023 01:14 PM','200',NULL,NULL,NULL,NULL,'2023-01-21',0),(11,10,5,6,6,8,'hMLYe9','123456','@123','Test','2023-01-26-63d21b9182c6e.png','Diagnosis','Public','3',11,9,'test','2023-01-26-63d21b3ff1324.png','26-01-2023  11:48 AM','123456','1','2023-01-26-63d21b6a63314.png','test',NULL,NULL,NULL,'31-01-2023  09:23 AM','2023-01-31-63d890803342d.png','123456',NULL,'1','2023-01-31-63d890c0bb1da.png',NULL,NULL,NULL,'2023-01-26 11:51:04',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-26',0),(12,10,30,3,5,9,'iap3e0','KD98253','kd nikahay id','jk patient','2023-01-26-63d2183e239b6.png','Diagnosis','Private','1',11,10,'test','2023-01-26-63d2174910ac8.png','26-01-2023  11:31 AM','123','1','2023-01-26-63d2175d68fe8.png','test',NULL,NULL,NULL,'26-01-2023  11:59 AM','2023-01-26-63d21db4a195a.png','12345',NULL,'15','2023-01-26-63d21dd816d96.png',NULL,NULL,NULL,'2023-01-26 11:56:56',3,'N/A','2023-01-26-63d224e2d5f8b.png','26-01-2023 12:30 PM','12345',NULL,NULL,NULL,NULL,'N/A','2023-01-26-63d2255906b96.png','26-01-2023 12:31 PM','12345',NULL,NULL,NULL,NULL,'2023-01-26',1),(13,10,14,5,3,10,'MlooZ7','12345678','12345678','Harsh','2023-01-30-63d75b309bbb7.png','Treatment','Public','2',10,3,'test','2023-01-30-63d75b08e3441.png','30-01-2023  11:21 AM','15','12','2023-01-30-63d75b1a2e1ca.png','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-30 11:23:54',0,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-30',0),(14,10,5,6,6,11,'RnKQ6p','upi://pay?pa=kevaldanani44@okaxis','133','jk','2023-01-30-63d7b83563a8d.png','Treatment','Private','1',10,7,'test','2023-01-30-63d7b76da62ae.png','30-01-2023  05:56 PM','123456789','121','2023-01-30-63d7b808b74b0.png','test',NULL,NULL,NULL,'31-01-2023  09:23 AM','2023-01-31-63d890803342d.png','123456',NULL,'1','2023-01-31-63d890c0bb1da.png',NULL,NULL,NULL,'2023-01-30 18:01:39',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-30',0),(15,9,5,3,5,12,'a4uP8e','098765','qwe@','VS','2023-01-31-63d891ef92a60.png','Diagnosis','Public','3',2,1,'New Civil Hospital Surat','2023-01-31-63d891883c286.png','31-01-2023  09:25 AM','123456','1','2023-01-31-63d891a4313fb.png','New Civil Hospital Surat',NULL,NULL,NULL,'31-01-2023  09:40 AM','2023-01-31-63d8946ec92ab.png','123456',NULL,'123','2023-01-31-63d8949c2c676.png',NULL,NULL,NULL,'2023-01-31 09:30:13',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-31',0),(16,7,5,7,4,13,'A47keF','567890','mv','vv','2023-01-31-63d8954e3ea66.png','Diagnosis','Public','1',13,4,'Ayush Hospital','2023-01-31-63d8950530f80.png','31-01-2023  09:41 AM','123156','12','2023-01-31-63d895293477d.png','Ayush Hospital',NULL,NULL,NULL,'10-02-2023  12:18 PM','2023-02-10-63e5e8aa4f599.png','128282',NULL,'288','2023-02-10-63e5e8c5bbf4e.png','N/A','21.2064108','72.851777','2023-01-31 09:44:57',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-31',0),(17,5,30,3,2,14,'YJJZyt','567891','whsh','susjs','2023-01-31-63d89ac842311.png','Diagnosis','Private','2',4,2,'Topiwala Hospital','2023-01-31-63d89a309ca19.png','31-01-2023  10:03 AM','123456','1','2023-01-31-63d89a99c3ba7.png','Topiwala Hospital',NULL,NULL,NULL,'31-01-2023  10:22 AM','2023-01-31-63d89e2e55d1b.png','123456',NULL,'1','2023-01-31-63d89e556d153.png',NULL,NULL,NULL,'2023-01-31 10:07:22',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-31',0),(18,2,30,5,3,15,'IhLS9d','086434','sheh','shehe','2023-01-31-63d89f4e2af41.png','Treatment','Public','2',3,3,'P P Maniya Hospital','2023-01-31-63d89ecd6375c.png','31-01-2023  10:23 AM','123654','123','2023-01-31-63d89ef3c9383.png','P P Maniya Hospital',NULL,NULL,NULL,'31-01-2023  10:38 AM','2023-01-31-63d8a1fc65d56.png','4889',NULL,'2','2023-01-31-63d8a248eb761.png',NULL,NULL,NULL,'2023-01-31 10:26:25',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-31',0),(19,3,30,2,1,16,'PhkpKn','747474','ycycvty','vjvubv','2023-01-31-63d8a3dd89b32.png','Diagnosis','Public','3',4,3,'Kiran Hospital','2023-01-31-63d8a364a86ec.png','31-01-2023  10:42 AM','1329','566','2023-01-31-63d8a3aa1ea8e.png','Kiran Hospital',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31 10:46:08',0,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-31',0),(20,10,30,3,5,17,'IDmFVg','12346','Nik81400','Nikunj','2023-01-31-63d8b4ae6a804.png','Treatment','Private','2',11,5,'test','2023-01-31-63d8b4755efb4.png','31-01-2023  11:55 AM','12345','12','2023-01-31-63d8b48e013f5.png','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31 11:58:37',0,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-31',0),(21,10,30,5,3,17,'IDmFVg','12346','Nik81400','Nikunj','2023-01-31-63d8b4ae6a804.png','Treatment','Private','2',11,5,'test','2023-01-31-63d8b4755efb4.png','31-01-2023  11:55 AM','12345','12','2023-01-31-63d8b48e013f5.png','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31 11:58:37',0,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-01-31',0),(22,7,16,7,4,18,'ifeIvH','https://qrstud.io/qrmnky','17839293','dusjs','2023-02-02-63dba7c50fa1d.png','Diagnosis','Private','2',13,5,'Ayush Hospital','2023-02-03-63dcd5841aa0d.png','03-02-2023  03:05 PM','5','3','2023-02-03-63dcd594b1557.png','Ayush Hospital',NULL,NULL,NULL,'03-02-2023  03:10 PM','2023-02-03-63dcd663d2d86.png','10',NULL,'N/A','2023-02-03-63dcd67660de1.png',NULL,NULL,NULL,'2023-02-03 15:07:13',2,'N/A','2023-02-03-63dcfbc5174bc.png','03-02-2023 05:51 PM','12',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-02-03',0),(23,7,16,7,4,18,'ifeIvH','https://www.investopedia.com/terms/q/quick-response-qr-code.asp','12345678','Patient','2023-02-03-63dcd5a9acf6f.png','Diagnosis','Private','2',12,5,'Ayush Hospital','2023-02-03-63dcd5841aa0d.png','03-02-2023  03:05 PM','5','3','2023-02-03-63dcd594b1557.png','Ayush Hospital',NULL,NULL,NULL,'03-02-2023  03:10 PM','2023-02-03-63dcd663d2d86.png','10',NULL,'N/A','2023-02-03-63dcd67660de1.png',NULL,NULL,NULL,'2023-02-03 15:07:13',2,'N/A','2023-02-03-63dcfbc5174bc.png','03-02-2023 05:51 PM','12',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-02-03',0),(24,10,14,5,3,19,'giJ8Tb','1234','12345678','test','2023-02-07-63e1cd98b55f2.png','Treatment','Public','2',3,4,'test','2023-02-07-63e1cd4bda729.png','07-02-2023  09:31 AM','2','6','2023-02-07-63e1cd59cfc68.png','test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-07 09:34:13',0,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-02-07',0),(25,9,25,3,5,20,'jDg0zT','453488','568599','hiral patel','2023-02-08-63e38606b47e4.png','Diagnosis','Private','2',4,5,'New Civil Hospital Surat','2023-02-08-63e385980eee4.png','08-02-2023  04:50 PM','100','20','2023-02-08-63e385b6b0d0e.png','New Civil Hospital Surat',NULL,NULL,NULL,'08-02-2023  04:59 PM','2023-02-08-63e3873375ffd.png','18',NULL,'25','2023-02-08-63e3877d17c10.png',NULL,NULL,NULL,'2023-02-08 16:56:53',3,'N/A','2023-02-08-63e387da7dba7.png','08-02-2023 05:03 PM','150',NULL,NULL,NULL,NULL,'N/A','2023-02-08-63e388eae7ff0.png','08-02-2023 05:05 PM','280',NULL,NULL,NULL,NULL,'2023-02-08',0),(26,9,25,3,5,20,'jDg0zT','http://bn.m.wikipedia.org','247648','harsh patel','2023-02-08-63e386caf3c84.png','Diagnosis','Public','1',3,2,'New Civil Hospital Surat','2023-02-08-63e385980eee4.png','08-02-2023  04:50 PM','100','20','2023-02-08-63e385b6b0d0e.png','New Civil Hospital Surat',NULL,NULL,NULL,'08-02-2023  04:59 PM','2023-02-08-63e3873375ffd.png','18',NULL,'25','2023-02-08-63e3877d17c10.png',NULL,NULL,NULL,'2023-02-08 16:56:53',3,'N/A','2023-02-08-63e387da7dba7.png','08-02-2023 05:03 PM','150',NULL,NULL,NULL,NULL,'N/A','2023-02-08-63e388eae7ff0.png','08-02-2023 05:05 PM','280',NULL,NULL,NULL,NULL,'2023-02-08',0),(27,5,30,3,2,21,'h0Htr6','111111','gaga','bahahs','2023-02-09-63e4ba2c0fb1e.png','Treatment','Public','1',4,2,'Topiwala Hospital','2023-02-09-63e4b9f38a8fc.png','09-02-2023  02:46 PM','123','18','2023-02-09-63e4ba0d07eaa.png','Topiwala Hospital',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-09 14:48:36',0,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-02-09',0),(28,5,5,5,3,22,'VT8SDt','09865','hhxd','djdhd','2023-02-10-63e5db2b584e3.png','Diagnosis','Public','1',10,2,'Topiwala Hospital','2023-02-10-63e5dae2ce030.png','10-02-2023  11:18 AM','123456','123','2023-02-10-63e5dafb2b054.png','Topiwala Hospital','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064145','72.8517779','10-02-2023  11:39 AM','2023-02-10-63e5df5e55b3b.png','123456',NULL,'12','2023-02-10-63e5df7e9a683.png','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064145','72.8517779','2023-02-10 11:21:59',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-02-10',0),(29,5,5,3,2,23,'gU26NN','09865','gcgccgc','uvhv','2023-02-10-63e5dc253cc01.png','Treatment','Public','3',3,3,'Topiwala Hospital','2023-02-10-63e5dbe603e97.png','10-02-2023  11:23 AM','123','12','2023-02-10-63e5dbfd387f9.png','Topiwala Hospital','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064145','72.8517779','10-02-2023  11:34 AM','2023-02-10-63e5de5627930.png','123',NULL,'12','2023-02-10-63e5de6d43e76.png','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064145','72.8517779','2023-02-10 11:25:15',3,'N/A','2023-02-10-63e5ece8d612b.png','10-02-2023 12:37 PM','18008',NULL,'248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064314','72.8517444','N/A','2023-02-10-63e5eece3da2e.png','10-02-2023 12:44 PM','425888',NULL,'248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064249','72.8517636','2023-02-10',0),(30,7,5,7,4,24,'S3Khpq','09865','cycfy','hvhvv','2023-02-10-63e5ea648130b.png','Diagnosis','Public','1',10,2,'Ayush Hospital','2023-02-10-63e5e9d9e866d.png','10-02-2023  12:23 PM','7557','388','2023-02-10-63e5ea0777cf8.png','Ayush Hospital','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064145','72.8517779','10-02-2023  12:27 PM','2023-02-10-63e5eaabcb2e3.png','123456',NULL,'12','2023-02-10-63e5eae5e6c76.png','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064145','72.8517779','2023-02-10 12:26:12',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-02-10',0),(31,9,25,3,2,25,'MvpjlL','2957556','573578','ritika shah','2023-02-13-63e9dbb53d6ac.png','Diagnosis','Private','2',4,4,'New Civil Hospital Surat','2023-02-13-63e9db6abf045.png','13-02-2023  12:10 PM','50','20','2023-02-13-63e9db9730171.png','New Civil Hospital Surat','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','13-02-2023  12:18 PM','2023-02-13-63e9dd2fe3ce1.png','25',NULL,'25','2023-02-13-63e9dd502047e.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13 12:16:59',3,'N/A','2023-02-13-63e9dde4328ea.png','13-02-2023 12:23 PM','150',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-13-63e9dec7bd433.png','13-02-2023 12:25 PM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13',0),(32,9,25,3,2,25,'MvpjlL','AA9999','2474478','ronak shah','2023-02-13-63e9dc5d7e1bd.png','Treatment','Public','2',12,1,'New Civil Hospital Surat','2023-02-13-63e9db6abf045.png','13-02-2023  12:10 PM','50','20','2023-02-13-63e9db9730171.png','New Civil Hospital Surat','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','13-02-2023  12:18 PM','2023-02-13-63e9dd2fe3ce1.png','25',NULL,'25','2023-02-13-63e9dd502047e.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13 12:16:59',3,'N/A','2023-02-13-63e9dde4328ea.png','13-02-2023 12:23 PM','150',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-13-63e9dec7bd433.png','13-02-2023 12:25 PM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13',0),(33,3,25,2,1,26,'y8yHcb','1234444','967864','rahul solanki','2023-02-13-63e9f82f88103.png','Treatment','Private','1',3,2,'Kiran Hospital','2023-02-13-63e9f7dc6ac04.png','13-02-2023  02:11 PM','80','18','2023-02-13-63e9f808c8010.png','Kiran Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','13-02-2023  02:17 PM','2023-02-13-63e9f9030f13e.png','150',NULL,'23','2023-02-13-63e9f937bf005.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13 14:15:19',3,'N/A','2023-02-13-63e9f982a8b75.png','13-02-2023 02:22 PM','180',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-13-63e9faac71530.png','13-02-2023 02:24 PM','210',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13',0),(34,3,25,2,1,26,'y8yHcb','5685445','2464477','reena patel','2023-02-13-63e9f8786e9f4.png','Treatment','Public','1',4,5,'Kiran Hospital','2023-02-13-63e9f7dc6ac04.png','13-02-2023  02:11 PM','80','18','2023-02-13-63e9f808c8010.png','Kiran Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','13-02-2023  02:17 PM','2023-02-13-63e9f9030f13e.png','150',NULL,'23','2023-02-13-63e9f937bf005.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13 14:15:19',3,'N/A','2023-02-13-63e9f982a8b75.png','13-02-2023 02:22 PM','180',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-13-63e9faac71530.png','13-02-2023 02:24 PM','210',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13',0),(35,13,14,5,7,27,'QNk836','12345678','12569372','Rajesh Khanna','2023-02-13-63ea61f03bdaf.png','Treatment','Private','1',13,5,'Cooper Hospital','2023-02-13-63ea61c593c8b.png','13-02-2023  09:43 PM','200','250','2023-02-13-63ea61d995828.png','Cooper Hospital','\"\"','\"\"','\"\"','13-02-2023  09:48 PM','2023-02-13-63ea62b0be41d.png','240',NULL,'30','2023-02-13-63ea62c276e41.png','N/A','N/A','N/A','2023-02-13 21:45:45',3,'N/A','2023-02-13-63ea64e657f6a.png','13-02-2023 09:57 PM','230',NULL,'N/A','N/A','N/A','N/A','2023-02-13-63ea652db41cd.png','13-02-2023 09:58 PM','250',NULL,'N/A','N/A','N/A','2023-02-13',0),(36,13,14,5,7,28,'tSQeXN','12345678','55577772','asad','2023-02-13-63ea63d495f9d.png','Diagnosis','Public','2',10,3,'Cooper Hospital','2023-02-13-63ea63ae0006d.png','13-02-2023  09:51 PM','23','8','2023-02-13-63ea63bd77564.png','Cooper Hospital',NULL,NULL,NULL,'13-02-2023  09:55 PM','2023-02-13-63ea6472719d0.png','45',NULL,'12','2023-02-13-63ea648263312.png','N/A','N/A','N/A','2023-02-13 21:53:20',3,'N/A','2023-02-13-63ea64e657f6a.png','13-02-2023 09:57 PM','230',NULL,'N/A','N/A','N/A','N/A','2023-02-13-63ea652db41cd.png','13-02-2023 09:58 PM','250',NULL,'N/A','N/A','N/A','2023-02-13',0),(37,2,25,2,1,29,'bvNIEg','1863455','2464477','reena patel','2023-02-14-63eb0eca4137d.png','Treatment','Public','2',4,5,'P P Maniya Hospital','2023-02-14-63eb0e707c322.png','14-02-2023  10:00 AM','50','15','2023-02-14-63eb0e8c410d4.png','P P Maniya Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','14-02-2023  10:05 AM','2023-02-14-63eb0f7ca96da.png','85',NULL,'20','2023-02-14-63eb0f9553e8a.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14 10:03:45',3,'N/A','2023-02-14-63eb1003bd9fe.png','14-02-2023 10:08 AM','120',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-14-63eb10eb8f24f.png','14-02-2023 10:11 AM','210',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14',0),(38,1,25,3,2,30,'b2fzZa','4096565','573578','Ritika Shah','2023-02-14-63eb1193b54ee.png','Diagnosis','Private','1',4,4,'P P Savani','2023-02-14-63eb1154582e6.png','14-02-2023  10:12 AM','40','10','2023-02-14-63eb11730edeb.png','P P Savani','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','14-02-2023  10:17 AM','2023-02-14-63eb1214a26d8.png','80',NULL,'21','2023-02-14-63eb123326f74.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14 10:15:37',3,'N/A','2023-02-14-63eb12a9dc871.png','14-02-2023 10:20 AM','110',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-14-63eb15e592006.png','14-02-2023 10:32 AM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14',0),(39,3,3,3,2,31,'G0nLQl','1987556','573898','rohan patel','2023-02-14-63eb13afd324c.png','Diagnosis','Private','1',12,2,'Kiran Hospital','2023-02-14-63eb137a96491.png','14-02-2023  10:22 AM','50','20','2023-02-14-63eb1390ae113.png','Kiran Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','14-02-2023  10:24 AM','2023-02-14-63eb13ef5af91.png','99',NULL,'20','2023-02-14-63eb1408cbfdb.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14 10:23:47',3,'N/A','2023-02-14-63eb150a908b4.png','14-02-2023 10:31 AM','120',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-14-63eb16199032d.png','14-02-2023 10:33 AM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14',0),(40,3,3,3,2,32,'5pmRhw','1454545','584599','ruhani shAh','2023-02-14-63eb14750fe8c.png','Diagnosis','Private','2',1,1,'Kiran Hospital','2023-02-14-63eb1439e92d0.png','14-02-2023  10:25 AM','60','20','2023-02-14-63eb145c3a4bf.png','Kiran Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','14-02-2023  10:27 AM','2023-02-14-63eb14b7bddee.png','99',NULL,'25','2023-02-14-63eb14cf75d27.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14 10:27:02',3,'N/A','2023-02-14-63eb150a908b4.png','14-02-2023 10:31 AM','120',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-14-63eb16199032d.png','14-02-2023 10:33 AM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14',0),(41,15,25,15,8,33,'iU9JFt','ABAGS4562231','573688','Reshma Merchant','2023-02-16-63ee0ca06b6de.png','Diagnosis','Private','1',13,1,'Lotus Hospital','2023-02-16-63ee0c059661b.png','16-02-2023  04:26 PM','80','20','2023-02-16-63ee0c52941f0.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','16-02-2023  04:44 PM','2023-02-16-63ee0fd85961f.png','150',NULL,'25','2023-02-16-63ee100982c25.png','AARSH LAB, Kapadia Chal, Valsad, Gujarat, India','20.618535','72.9339856','2023-02-16 16:33:54',3,'N/A','2023-02-16-63ee113780b3d.png','16-02-2023 04:52 PM','200',NULL,'Abrama-Dharampur Road, Abrama Village, Valsad, Gujarat','20.618535','72.9422753','N/A','2023-02-16-63ee143430cdd.png','16-02-2023 05:02 PM','280',NULL,'Lotus Hospital Road, Ramwadi, Ramji Tekra, Valsad, Gujarat, India','20.618535','72.9339856','2023-02-16',1),(42,15,25,15,8,33,'iU9JFt','ABAGS4562234','955897','Radhika Iyer','2023-02-16-63ee0d21bbdfe.png','Diagnosis','Public','2',12,4,'Lotus Hospital','2023-02-16-63ee0c059661b.png','16-02-2023  04:26 PM','80','20','2023-02-16-63ee0c52941f0.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','16-02-2023  04:44 PM','2023-02-16-63ee0fd85961f.png','150',NULL,'25','2023-02-16-63ee100982c25.png','AARSH LAB, Kapadia Chal, Valsad, Gujarat, India','20.618535','72.9339856','2023-02-16 16:33:54',3,'N/A','2023-02-16-63ee113780b3d.png','16-02-2023 04:52 PM','200',NULL,'Abrama-Dharampur Road, Abrama Village, Valsad, Gujarat','20.618535','72.9422753','N/A','2023-02-16-63ee143430cdd.png','16-02-2023 05:02 PM','280',NULL,'Lotus Hospital Road, Ramwadi, Ramji Tekra, Valsad, Gujarat, India','20.6147567','72.9264922','2023-02-16',1),(43,14,25,15,8,34,'5kuOhi','AA9999','4744899','Rishabh Shah','2023-02-16-63ee0e7ca8266.png','Treatment','Private','2',4,3,'Amit Hospital','2023-02-16-63ee0de34faf3.png','16-02-2023  04:34 PM','110','20','2023-02-16-63ee0e1190527.png','Amit Hospital','Amit Hospital, Halar Road, beside SBI Bnak, opp. Avabai High School, Dharampur, Valsad, Gujarat, India','20.6098396','72.927289','16-02-2023  04:44 PM','2023-02-16-63ee0fd85961f.png','150',NULL,'25','2023-02-16-63ee100982c25.png','AARSH LAB, Kapadia Chal, Valsad, Gujarat, India','20.6114102','72.9323088','2023-02-16 16:38:46',3,'N/A','2023-02-16-63ee113780b3d.png','16-02-2023 04:52 PM','200',NULL,'Abrama-Dharampur Road, Abrama Village, Valsad, Gujarat','20.5923493','72.9422753','N/A','2023-02-16-63ee135766e4c.png','16-02-2023 04:58 PM','250',NULL,'Amit Hospital, Halar Road, beside SBI Bnak, opp. Avabai High School, Dharampur, Valsad, Gujarat, India','20.6098396','72.927289','2023-02-16',1),(44,13,16,5,7,35,'7HWY7Y','http://en.m.wikipedia.org','987654321','Harshada','2023-02-18-63f072a89c81f.png','Diagnosis','Private','1',13,9,'Cooper Hospital','2023-02-18-63f0723fe7bb2.png','18-02-2023  12:07 PM','1','1','2023-02-18-63f07265b5003.png','Cooper Hospital','501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','18-02-2023  12:12 PM','2023-02-18-63f07319b3d80.png','5',NULL,'1','2023-02-18-63f0734020c23.png','501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','2023-02-18 12:10:48',3,'N/A','2023-02-18-63f073f74d8e6.png','18-02-2023 12:15 PM','1',NULL,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','N/A','2023-02-18-63f074cd7e61a.png','18-02-2023 12:18 PM','1',NULL,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','2023-02-18',0),(45,15,25,15,8,36,'RKXGXN','7210804002411','5674367','rohani','2023-02-21-63f45e0cdaf9a.png','Diagnosis','Private','2',4,3,'Lotus Hospital','2023-02-21-63f45dd406d4d.png','21-02-2023  11:29 AM','28','5','2023-02-21-63f45de8bbeb3.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  11:40 AM','2023-02-21-63f4600c444e3.png','20',NULL,'6','2023-02-21-63f4603736b51.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 11:33:34',3,'N/A','2023-02-21-63f461a3b645b.png','21-02-2023 11:47 AM','30',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f462f573d34.png','21-02-2023 11:51 AM','50',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(46,15,25,15,9,36,'RKXGXN','ABAGS4562232','35899457','rohan patel','2023-02-21-63f45e678cd5e.png','Diagnosis','Private','1',4,3,'Lotus Hospital','2023-02-21-63f45dd406d4d.png','21-02-2023  11:29 AM','28','5','2023-02-21-63f45de8bbeb3.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  11:42 AM','2023-02-21-63f460aa159c1.png','50',NULL,'8','2023-02-21-63f460bf275ef.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 11:33:34',3,'N/A','2023-02-21-63f4625b311df.png','21-02-2023 11:50 AM','20',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f462f573d34.png','21-02-2023 11:51 AM','50',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(47,15,25,15,9,37,'kd4UBX','ABAGS4562234','48990','rahul shah','2023-02-21-63f46a9e8cb2e.png','Treatment','Private','2',4,2,'Lotus Hospital','2023-02-21-63f46a7a54a78.png','21-02-2023  12:23 PM','20','5','2023-02-21-63f46a895230e.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  12:43 PM','2023-02-21-63f46edc9416a.png','10',NULL,'5','2023-02-21-63f46f13383a5.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 12:28:00',3,'N/A','2023-02-21-63f470fdcd3fe.png','21-02-2023 12:56 PM','20',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f472ee23f3b.png','21-02-2023 12:59 PM','30',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(48,15,25,15,9,37,'kd4UBX','ABAGS4562232','379000','reena shah','2023-02-21-63f46ad515dd2.png','Treatment','Public','1',12,2,'Lotus Hospital','2023-02-21-63f46a7a54a78.png','21-02-2023  12:23 PM','20','5','2023-02-21-63f46a895230e.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  12:43 PM','2023-02-21-63f46edc9416a.png','10',NULL,'5','2023-02-21-63f46f13383a5.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 12:28:00',3,'N/A','2023-02-21-63f470fdcd3fe.png','21-02-2023 12:56 PM','20',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f472ee23f3b.png','21-02-2023 12:59 PM','30',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(49,15,25,15,8,37,'kd4UBX','ABAGS4562232','33689995','ronak patel','2023-02-21-63f46b2e6f651.png','Diagnosis','Private','2',3,3,'Lotus Hospital','2023-02-21-63f46a7a54a78.png','21-02-2023  12:23 PM','20','5','2023-02-21-63f46a895230e.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  12:50 PM','2023-02-21-63f47082adb05.png','10',NULL,'6','2023-02-21-63f470aacb6ef.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 12:28:00',3,'N/A','2023-02-21-63f4725c6b64b.png','21-02-2023 12:58 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f472ee23f3b.png','21-02-2023 12:59 PM','30',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(50,14,25,15,8,38,'BOCvJE','ABAGS4562232','246789','radhika patel','2023-02-21-63f4739eee486.png','Diagnosis','Private','2',12,4,'Amit Hospital','2023-02-21-63f4736744b0d.png','21-02-2023  01:01 PM','10','1','2023-02-21-63f4737b7e2f6.png','Amit Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  01:34 PM','2023-02-21-63f47ae19b440.png','20',NULL,'5','2023-02-21-63f47af5e718e.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 13:04:29',3,'N/A','2023-02-21-63f47b9631d5f.png','21-02-2023 01:37 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f47c5b3afb5.png','21-02-2023 01:40 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(51,14,25,15,8,38,'BOCvJE','ABAGS4562234','2479990','rishabh patel','2023-02-21-63f473ce2180d.png','Diagnosis','Private','2',4,3,'Amit Hospital','2023-02-21-63f4736744b0d.png','21-02-2023  01:01 PM','10','1','2023-02-21-63f4737b7e2f6.png','Amit Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  01:34 PM','2023-02-21-63f47ae19b440.png','20',NULL,'5','2023-02-21-63f47af5e718e.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 13:04:29',3,'N/A','2023-02-21-63f47b9631d5f.png','21-02-2023 01:37 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f47c5b3afb5.png','21-02-2023 01:40 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(52,15,25,15,9,39,'2CkbT7','ABAGS4562234','479000','rusha','2023-02-21-63f4748370acb.png','Diagnosis','Private','2',12,4,'Lotus Hospital','2023-02-21-63f47454113c7.png','21-02-2023  01:05 PM','10','2','2023-02-21-63f47469c4949.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  01:35 PM','2023-02-21-63f47b37b07b7.png','10',NULL,'10','2023-02-21-63f47b5b35db2.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 13:07:36',3,'N/A','2023-02-21-63f47c0329dfa.png','21-02-2023 01:39 PM','20',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f47c8ca57f3.png','21-02-2023 01:41 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(53,14,25,15,8,40,'FeemjK','ABAGS4562239','4756','vidisha','2023-02-21-63f47f81a0a0b.png','Diagnosis','Private','1',12,5,'Amit Hospital','2023-02-21-63f47f473aea7.png','21-02-2023  01:52 PM','2','6','2023-02-21-63f47f5a10101.png','Amit Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  01:54 PM','2023-02-21-63f47fb552fb0.png','5',NULL,'7','2023-02-21-63f47fc9960db.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 13:54:04',3,'N/A','2023-02-21-63f481085b6b6.png','21-02-2023 02:00 PM','5',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','N/A','2023-02-21-63f48149a649e.png','21-02-2023 02:01 PM','5',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21',0),(54,15,25,15,8,41,'FW19Fd','ABAGS4562234','4799','dfy','2023-02-21-63f481aa2ede1.png','Diagnosis','Private','2',10,3,'Lotus Hospital','2023-02-21-63f48187a34d9.png','21-02-2023  02:01 PM','5','5','2023-02-21-63f481966c7e2.png','Lotus Hospital','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','21-02-2023  02:03 PM','2023-02-21-63f481d8cd64f.png','8','0','5','2023-02-21-63f481f1eeee0.png','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21 14:03:11',1,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'N/A','N/A','N/A','N/A',NULL,NULL,NULL,NULL,'2023-02-21',0);
/*!40000 ALTER TABLE `add_sample_box_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_sample_box_item`
--

DROP TABLE IF EXISTS `add_sample_box_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_sample_box_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) DEFAULT NULL,
  `invoice_photo` varchar(205) DEFAULT NULL,
  `scan_code` varchar(105) DEFAULT NULL,
  `nikshay_id` varchar(105) DEFAULT NULL,
  `patient` varchar(205) DEFAULT NULL,
  `type_test_for` varchar(105) DEFAULT NULL,
  `type_patient` varchar(105) DEFAULT NULL,
  `no_of_sample` varchar(105) DEFAULT NULL,
  `specimen_id` bigint(20) DEFAULT NULL,
  `test_id` bigint(20) DEFAULT NULL,
  `lab_id` bigint(20) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_sample_box_item`
--

LOCK TABLES `add_sample_box_item` WRITE;
/*!40000 ALTER TABLE `add_sample_box_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `add_sample_box_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_sample_box_sample`
--

DROP TABLE IF EXISTS `add_sample_box_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_sample_box_sample` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` varchar(45) DEFAULT NULL,
  `sample_selected_id` bigint(20) DEFAULT NULL,
  `hospital_id` bigint(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_sample_box_sample`
--

LOCK TABLES `add_sample_box_sample` WRITE;
/*!40000 ALTER TABLE `add_sample_box_sample` DISABLE KEYS */;
INSERT INTO `add_sample_box_sample` VALUES (1,'3',1,1,'2023-01-20 18:49:50','2023-01-20'),(2,'3',2,1,'2023-01-20 18:49:50','2023-01-20'),(3,'3',3,1,'2023-01-20 19:55:16','2023-01-20'),(4,'1',4,3,'2023-01-20 19:29:58','2023-01-20'),(5,'1',5,3,'2023-01-20 19:29:58','2023-01-20'),(7,'25',7,6,'2023-01-21 11:40:37','2023-01-21'),(8,'25',8,6,'2023-01-21 11:40:37','2023-01-21'),(11,'5',11,10,'2023-01-26 11:04:51','2023-01-26'),(12,'30',12,10,'2023-01-26 11:56:56','2023-01-26'),(13,'14',13,10,'2023-01-30 11:54:23','2023-01-30'),(14,'5',14,10,'2023-01-30 18:39:01','2023-01-30'),(15,'5',15,9,'2023-01-31 09:13:30','2023-01-31'),(16,'5',16,7,'2023-01-31 09:57:44','2023-01-31'),(17,'30',17,5,'2023-01-31 10:22:07','2023-01-31'),(18,'30',18,2,'2023-01-31 10:25:26','2023-01-31'),(19,'30',19,3,'2023-01-31 10:08:46','2023-01-31'),(20,'30',20,10,'2023-01-31 11:37:58','2023-01-31'),(21,'30',21,10,'2023-01-31 11:37:58','2023-01-31'),(22,'16',22,7,'2023-02-03 15:13:07','2023-02-03'),(23,'16',23,7,'2023-02-03 15:13:07','2023-02-03'),(24,'14',24,10,'2023-02-07 09:13:34','2023-02-07'),(25,'25',25,9,'2023-02-08 16:53:56','2023-02-08'),(26,'25',26,9,'2023-02-08 16:53:56','2023-02-08'),(27,'30',27,5,'2023-02-09 14:36:48','2023-02-09'),(28,'5',28,5,'2023-02-10 11:59:21','2023-02-10'),(29,'5',29,5,'2023-02-10 11:15:25','2023-02-10'),(30,'5',30,7,'2023-02-10 12:12:26','2023-02-10'),(31,'25',31,9,'2023-02-13 12:59:16','2023-02-13'),(32,'25',32,9,'2023-02-13 12:59:16','2023-02-13'),(33,'25',33,3,'2023-02-13 14:19:15','2023-02-13'),(34,'25',34,3,'2023-02-13 14:19:15','2023-02-13'),(35,'14',35,13,'2023-02-13 21:45:45','2023-02-13'),(36,'14',36,13,'2023-02-13 21:20:53','2023-02-13'),(37,'25',37,2,'2023-02-14 10:45:03','2023-02-14'),(38,'25',38,1,'2023-02-14 10:37:15','2023-02-14'),(39,'3',39,3,'2023-02-14 10:47:23','2023-02-14'),(40,'3',40,3,'2023-02-14 10:02:27','2023-02-14'),(41,'25',41,15,'2023-02-16 16:54:33','2023-02-16'),(42,'25',42,15,'2023-02-16 16:54:33','2023-02-16'),(43,'25',43,14,'2023-02-16 16:46:38','2023-02-16'),(44,'16',44,13,'2023-02-18 12:48:10','2023-02-18'),(45,'25',45,15,'2023-02-21 11:34:33','2023-02-21'),(46,'25',46,15,'2023-02-21 11:34:33','2023-02-21'),(47,'25',47,15,'2023-02-21 12:00:28','2023-02-21'),(48,'25',48,15,'2023-02-21 12:00:28','2023-02-21'),(49,'25',49,15,'2023-02-21 12:00:28','2023-02-21'),(50,'25',50,14,'2023-02-21 13:29:04','2023-02-21'),(51,'25',51,14,'2023-02-21 13:29:04','2023-02-21'),(52,'25',52,15,'2023-02-21 13:36:07','2023-02-21'),(53,'25',53,14,'2023-02-21 13:04:54','2023-02-21'),(54,'25',54,15,'2023-02-21 14:11:03','2023-02-21');
/*!40000 ALTER TABLE `add_sample_box_sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_sample_collected_details`
--

DROP TABLE IF EXISTS `add_sample_collected_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_sample_collected_details` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sample_id` varchar(45) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `sample_meter_name` varchar(205) DEFAULT NULL,
  `sample_meter_photo` varchar(205) DEFAULT NULL,
  `sample_date_time` varchar(105) DEFAULT NULL,
  `collected_from` varchar(45) DEFAULT NULL,
  `map_area_name` text,
  `kilometer` varchar(105) DEFAULT NULL,
  `degree` varchar(105) DEFAULT NULL,
  `sample_box_photo` varchar(205) DEFAULT NULL,
  `sample_box_name` varchar(205) DEFAULT NULL,
  `from_latitude` varchar(105) DEFAULT NULL,
  `from_longitude` varchar(105) DEFAULT NULL,
  `to_sample_date_time` varchar(105) DEFAULT NULL,
  `to_sample_meter_photo` varchar(105) DEFAULT NULL,
  `to_sample_box_photo` varchar(105) DEFAULT NULL,
  `to_kilometer` varchar(105) DEFAULT NULL,
  `to_actual_kilometer` varchar(105) DEFAULT NULL,
  `to_degree` varchar(105) DEFAULT NULL,
  `to_latitude` varchar(105) DEFAULT NULL,
  `to_longitude` varchar(105) DEFAULT NULL,
  `to_map_area_name` text,
  `updated` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `collect_lab_sample_meter_name` varchar(105) DEFAULT NULL,
  `collect_lab_sample_meter_photo` varchar(105) DEFAULT NULL,
  `collect_lab_sample_date_time` varchar(105) DEFAULT NULL,
  `collect_lab_kilometer` varchar(105) DEFAULT NULL,
  `collect_actual_kilometer` varchar(105) DEFAULT NULL,
  `collect_map_area_name` text,
  `collect_latitude` varchar(105) DEFAULT NULL,
  `collect_longitude` varchar(105) DEFAULT NULL,
  `submit_hospital_sample_meter_name` varchar(105) DEFAULT NULL,
  `submit_hospital_sample_meter_photo` varchar(105) DEFAULT NULL,
  `submit_hospital_sample_date_time` varchar(105) DEFAULT NULL,
  `submit_hospital_kilometer` varchar(105) DEFAULT NULL,
  `submit_actual_kilometer` varchar(105) DEFAULT NULL,
  `submit_map_area_name` text,
  `submit_latitude` varchar(105) DEFAULT NULL,
  `submit_longitude` varchar(105) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `submitted_date` date DEFAULT NULL,
  `kilometer_invoice_status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_sample_collected_details`
--

LOCK TABLES `add_sample_collected_details` WRITE;
/*!40000 ALTER TABLE `add_sample_collected_details` DISABLE KEYS */;
INSERT INTO `add_sample_collected_details` VALUES (1,'GJ24D9',3,'Spedometer','2023-01-20-63ca950218015.png','02/01/2023, 01:00 PM','1','P.P.Savani Hospital, Varachha Main Rd, opposite Kapodra Police Station, Vishnu Nagar Society, Vishnu Nager, Singanpor, Surat, Gujarat, India','80','10','2023-01-20-63ca9523a0488.png','Sample Box','21.2270149','72.8213741','20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','2023-01-20-63ca95f52ec4c.png','150',NULL,'15',NULL,NULL,NULL,NULL,3,NULL,'2023-01-20-63ca9858a458c.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,NULL,'2023-01-20-63ca9a01cf075.png','03/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20','2023-01-20',1),(2,'8eLyGY',3,'Spedometer','2023-01-20-63ca9b3c1402d.png','02/01/2023, 01:00 PM','1','P.P.Savani Hospital, Varachha Main Rd, opposite Kapodra Police Station, Vishnu Nagar Society, Vishnu Nager, Singanpor, Surat, Gujarat, India','80','10','2023-01-20-63ca9b1c12037.png','Sample Box','21.2270149','72.8213741','20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','2023-01-20-63ca9ba21cc2f.png','150',NULL,'15',NULL,NULL,NULL,NULL,3,NULL,'2023-01-20-63caa16c494a6.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,NULL,'2023-01-20-63caa21f9fa36.png','20/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20','2023-01-20',1),(3,'ZC0AQd',1,'Spedometer','2023-01-20-63ca9b3c1402d.png','20/01/2023, 01:00 PM','3','Kiran Hospital, near Sumul Dairy Road, Tunki, Katargam, Surat, Gujarat, India','80','10','2023-01-20-63caa495095ac.png','Sample Box','21.2184767','72.8368004','20-01-2023 16:30:35','2023-01-20-63ca95de1e030.png','2023-01-20-63caa5c9442d7.png','150',NULL,'15',NULL,NULL,NULL,NULL,3,NULL,'2023-01-20-63caa6ca98477.png','20/01/2023, 17:00 PM','180',NULL,NULL,NULL,NULL,NULL,'2023-01-20-63caa770b03a7.png','20/01/2023, 17:00 PM','200',NULL,NULL,NULL,NULL,'2023-01-20','2023-01-20',0),(5,'dEcBAL',25,'MNHP( Motilal Nagar Health Post)','2023-01-21-63cb801271274.png','21-01-2023  11:31 AM','6','Motilal Nagar I, Goregaon West, Mumbai, Maharashtra, India','50','5','2023-01-21-63cb8042049e3.png','MNHP( Motilal Nagar Health Post)','19.1574784','72.83825470000001','21-01-2023  11:40 AM','2023-01-21-63cb816a421a3.png','2023-01-21-63cb81b9c8e69.png','120',NULL,'8','19.1906638','72.8710409','Appa Pada Road, Malad, Datta Wadi, Ramgad Nagar, Malad East, Mumbai, Maharashtra, India',NULL,3,NULL,'2023-01-21-63cb95b8ca970.png','21-01-2023 01:11 PM','180',NULL,NULL,NULL,NULL,NULL,'2023-01-21-63cb97bc46b5f.png','21-01-2023 01:14 PM','200',NULL,NULL,NULL,NULL,'2023-01-21','2023-01-21',0),(8,'hMLYe9',5,'test','2023-01-26-63d21b3ff1324.png','26-01-2023  11:48 AM','10','\"\"','80','1','2023-01-26-63d21b6a63314.png','test','\"\"','\"\"','31-01-2023  09:23 AM','2023-01-31-63d890803342d.png','2023-01-31-63d890c0bb1da.png','120',NULL,'1',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-26',NULL,0),(9,'iap3e0',30,'test','2023-01-26-63d2174910ac8.png','26-01-2023  11:31 AM','10','\"\"','20','1','2023-01-26-63d2175d68fe8.png','test','\"\"','\"\"','26-01-2023  11:59 AM','2023-01-26-63d21db4a195a.png','2023-01-26-63d21dd816d96.png','90',NULL,'15',NULL,NULL,NULL,NULL,3,NULL,'2023-01-26-63d224e2d5f8b.png','26-01-2023 12:30 PM','140',NULL,NULL,NULL,NULL,NULL,'2023-01-26-63d2255906b96.png','26-01-2023 12:31 PM','200',NULL,NULL,NULL,NULL,'2023-01-26','2023-01-26',1),(10,'MlooZ7',14,'test','2023-01-30-63d75b08e3441.png','30-01-2023  11:21 AM','10','\"\"','15','12','2023-01-30-63d75b1a2e1ca.png','test','\"\"','\"\"',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-30',NULL,0),(11,'RnKQ6p',5,'test','2023-01-30-63d7b76da62ae.png','30-01-2023  05:56 PM','10','\"\"','50','121','2023-01-30-63d7b808b74b0.png','test','\"\"','\"\"','31-01-2023  09:23 AM','2023-01-31-63d890803342d.png','2023-01-31-63d890c0bb1da.png','80',NULL,'1',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-30',NULL,0),(12,'a4uP8e',5,'New Civil Hospital Surat','2023-01-31-63d891883c286.png','31-01-2023  09:25 AM','9','\"\"','60','1','2023-01-31-63d891a4313fb.png','New Civil Hospital Surat','\"\"','\"\"','31-01-2023  09:40 AM','2023-01-31-63d8946ec92ab.png','2023-01-31-63d8949c2c676.png','90',NULL,'123',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31',NULL,0),(13,'A47keF',5,'Ayush Hospital','2023-01-31-63d8950530f80.png','31-01-2023  09:41 AM','7','\"\"','60','12','2023-01-31-63d895293477d.png','Ayush Hospital','\"\"','\"\"','10-02-2023  12:18 PM','2023-02-10-63e5e8aa4f599.png','2023-02-10-63e5e8c5bbf4e.png','100',NULL,'288','21.2064108','72.851777','N/A',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31',NULL,0),(14,'YJJZyt',30,'Topiwala Hospital','2023-01-31-63d89a309ca19.png','31-01-2023  10:03 AM','5','\"\"','70','1','2023-01-31-63d89a99c3ba7.png','Topiwala Hospital','\"\"','\"\"','31-01-2023  10:22 AM','2023-01-31-63d89e2e55d1b.png','2023-01-31-63d89e556d153.png','100',NULL,'1',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31',NULL,0),(15,'IhLS9d',30,'P P Maniya Hospital','2023-01-31-63d89ecd6375c.png','31-01-2023  10:23 AM','2','\"\"','50','123','2023-01-31-63d89ef3c9383.png','P P Maniya Hospital','\"\"','\"\"','31-01-2023  10:38 AM','2023-01-31-63d8a1fc65d56.png','2023-01-31-63d8a248eb761.png','100',NULL,'2',NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31',NULL,0),(16,'PhkpKn',30,'Kiran Hospital','2023-01-31-63d8a364a86ec.png','31-01-2023  10:42 AM','3','\"\"','50','566','2023-01-31-63d8a3aa1ea8e.png','Kiran Hospital','\"\"','\"\"',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31',NULL,0),(17,'IDmFVg',30,'test','2023-01-31-63d8b4755efb4.png','31-01-2023  11:55 AM','10','\"\"','50','12','2023-01-31-63d8b48e013f5.png','test','\"\"','\"\"',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-31',NULL,0),(18,'ifeIvH',16,'Ayush Hospital','2023-02-03-63dcd5841aa0d.png','03-02-2023  03:05 PM','7','\"\"','5','3','2023-02-03-63dcd594b1557.png','Ayush Hospital','\"\"','\"\"','03-02-2023  03:10 PM','2023-02-03-63dcd663d2d86.png','2023-02-03-63dcd67660de1.png','10',NULL,'N/A',NULL,NULL,NULL,NULL,2,NULL,'2023-02-03-63dcfbc5174bc.png','03-02-2023 05:51 PM','12',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-03',NULL,0),(19,'giJ8Tb',14,'test','2023-02-07-63e1cd4bda729.png','07-02-2023  09:31 AM','10','\"\"','2','6','2023-02-07-63e1cd59cfc68.png','test','\"\"','\"\"',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-07',NULL,0),(20,'jDg0zT',25,'New Civil Hospital Surat','2023-02-08-63e385980eee4.png','08-02-2023  04:50 PM','9','\"\"','100','20','2023-02-08-63e385b6b0d0e.png','New Civil Hospital Surat','\"\"','\"\"','08-02-2023  04:59 PM','2023-02-08-63e3873375ffd.png','2023-02-08-63e3877d17c10.png','18',NULL,'25',NULL,NULL,NULL,NULL,3,NULL,'2023-02-08-63e387da7dba7.png','08-02-2023 05:03 PM','150',NULL,NULL,NULL,NULL,NULL,'2023-02-08-63e388eae7ff0.png','08-02-2023 05:05 PM','280',NULL,NULL,NULL,NULL,'2023-02-08','2023-02-08',0),(21,'h0Htr6',30,'Topiwala Hospital','2023-02-09-63e4b9f38a8fc.png','09-02-2023  02:46 PM','5','Bandu Gore Marg, Kakaji Nagar, Jawahar Nagar, Goregaon West, Mumbai, Maharashtra, India','40','18','2023-02-09-63e4ba0d07eaa.png','Topiwala Hospital','19.1650206','72.8474308',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-09',NULL,0),(22,'VT8SDt',5,'Topiwala Hospital','2023-02-10-63e5dae2ce030.png','10-02-2023  11:18 AM','5','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','50','123','2023-02-10-63e5dafb2b054.png','Topiwala Hospital','21.2064156','72.8517746','10-02-2023  11:39 AM','2023-02-10-63e5df5e55b3b.png','2023-02-10-63e5df7e9a683.png','100',NULL,'12','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-10',NULL,0),(23,'gU26NN',5,'Topiwala Hospital','2023-02-10-63e5dbe603e97.png','10-02-2023  11:23 AM','5','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','50','12','2023-02-10-63e5dbfd387f9.png','Topiwala Hospital','21.2064156','72.8517746','10-02-2023  11:34 AM','2023-02-10-63e5de5627930.png','2023-02-10-63e5de6d43e76.png','80',NULL,'12','N/A','N/A','N/A',NULL,3,NULL,'2023-02-10-63e5ece8d612b.png','10-02-2023 12:37 PM','150',NULL,'248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064314','72.8517444',NULL,'2023-02-10-63e5eece3da2e.png','10-02-2023 12:44 PM','200',NULL,'248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','21.2064249','72.8517636','2023-02-10','2023-02-10',0),(24,'S3Khpq',5,'Ayush Hospital','2023-02-10-63e5e9d9e866d.png','10-02-2023  12:23 PM','7','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India','50','388','2023-02-10-63e5ea0777cf8.png','Ayush Hospital','21.2064145','72.8517779','10-02-2023  12:27 PM','2023-02-10-63e5eaabcb2e3.png','2023-02-10-63e5eae5e6c76.png','80',NULL,'12','21.2064145','72.8517779','248, Kamal Baug Society, Varachha, Surat, Gujarat 395006, India',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-10',NULL,0),(25,'MvpjlL',25,'New Civil Hospital Surat','2023-02-13-63e9db6abf045.png','13-02-2023  12:10 PM','9','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','50','20','2023-02-13-63e9db9730171.png','New Civil Hospital Surat','20.618535','72.9339856','13-02-2023  12:18 PM','2023-02-13-63e9dd2fe3ce1.png','2023-02-13-63e9dd502047e.png','25',NULL,'25','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-13-63e9dde4328ea.png','13-02-2023 12:23 PM','150',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-13-63e9dec7bd433.png','13-02-2023 12:25 PM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13','2023-02-13',0),(26,'y8yHcb',25,'Kiran Hospital','2023-02-13-63e9f7dc6ac04.png','13-02-2023  02:11 PM','3','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','80','18','2023-02-13-63e9f808c8010.png','Kiran Hospital','20.618535','72.9339856','13-02-2023  02:17 PM','2023-02-13-63e9f9030f13e.png','2023-02-13-63e9f937bf005.png','150',NULL,'23','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-13-63e9f982a8b75.png','13-02-2023 02:22 PM','180',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-13-63e9faac71530.png','13-02-2023 02:24 PM','210',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-13','2023-02-13',0),(27,'QNk836',14,'Cooper Hospital','2023-02-13-63ea61c593c8b.png','13-02-2023  09:43 PM','13','\"\"','200','250','2023-02-13-63ea61d995828.png','Cooper Hospital','\"\"','\"\"','13-02-2023  09:48 PM','2023-02-13-63ea62b0be41d.png','2023-02-13-63ea62c276e41.png','240',NULL,'30','N/A','N/A','N/A',NULL,3,NULL,'2023-02-13-63ea64e657f6a.png','13-02-2023 09:57 PM','230',NULL,'N/A','N/A','N/A',NULL,'2023-02-13-63ea652db41cd.png','13-02-2023 09:58 PM','250',NULL,'N/A','N/A','N/A','2023-02-13','2023-02-13',0),(28,'tSQeXN',14,'Cooper Hospital','2023-02-13-63ea63ae0006d.png','13-02-2023  09:51 PM','13','N/A','23','8','2023-02-13-63ea63bd77564.png','Cooper Hospital','N/A','N/A','13-02-2023  09:55 PM','2023-02-13-63ea6472719d0.png','2023-02-13-63ea648263312.png','45',NULL,'12','N/A','N/A','N/A',NULL,3,NULL,'2023-02-13-63ea64e657f6a.png','13-02-2023 09:57 PM','230',NULL,'N/A','N/A','N/A',NULL,'2023-02-13-63ea652db41cd.png','13-02-2023 09:58 PM','250',NULL,'N/A','N/A','N/A','2023-02-13','2023-02-13',0),(29,'bvNIEg',25,'P P Maniya Hospital','2023-02-14-63eb0e707c322.png','14-02-2023  10:00 AM','2','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','50','15','2023-02-14-63eb0e8c410d4.png','P P Maniya Hospital','20.618535','72.9339856','14-02-2023  10:05 AM','2023-02-14-63eb0f7ca96da.png','2023-02-14-63eb0f9553e8a.png','85',NULL,'20','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-14-63eb1003bd9fe.png','14-02-2023 10:08 AM','120',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-14-63eb10eb8f24f.png','14-02-2023 10:11 AM','210',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','2023-02-14',0),(30,'b2fzZa',25,'P P Savani','2023-02-14-63eb1154582e6.png','14-02-2023  10:12 AM','1','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','40','10','2023-02-14-63eb11730edeb.png','P P Savani','20.618535','72.9339856','14-02-2023  10:17 AM','2023-02-14-63eb1214a26d8.png','2023-02-14-63eb123326f74.png','80',NULL,'21','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-14-63eb12a9dc871.png','14-02-2023 10:20 AM','110',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-14-63eb15e592006.png','14-02-2023 10:32 AM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','2023-02-14',0),(31,'G0nLQl',3,'Kiran Hospital','2023-02-14-63eb137a96491.png','14-02-2023  10:22 AM','3','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','50','20','2023-02-14-63eb1390ae113.png','Kiran Hospital','20.618535','72.9339856','14-02-2023  10:24 AM','2023-02-14-63eb13ef5af91.png','2023-02-14-63eb1408cbfdb.png','99',NULL,'20','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-14-63eb150a908b4.png','14-02-2023 10:31 AM','120',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-14-63eb16199032d.png','14-02-2023 10:33 AM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','2023-02-14',0),(32,'5pmRhw',3,'Kiran Hospital','2023-02-14-63eb1439e92d0.png','14-02-2023  10:25 AM','3','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','60','20','2023-02-14-63eb145c3a4bf.png','Kiran Hospital','20.618535','72.9339856','14-02-2023  10:27 AM','2023-02-14-63eb14b7bddee.png','2023-02-14-63eb14cf75d27.png','99',NULL,'25','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-14-63eb150a908b4.png','14-02-2023 10:31 AM','120',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-14-63eb16199032d.png','14-02-2023 10:33 AM','200',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','2023-02-14',0),(33,'iU9JFt',25,'Lotus Hospital','2023-02-16-63ee0c059661b.png','16-02-2023  04:26 PM','15','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','80','20','2023-02-16-63ee0c52941f0.png','Lotus Hospital','20.618535','72.9339856','16-02-2023  04:44 PM','2023-02-16-63ee0fd85961f.png','2023-02-16-63ee100982c25.png','150',NULL,'25','20.618535','72.9339856','AARSH LAB, Kapadia Chal, Valsad, Gujarat, India',NULL,3,NULL,'2023-02-16-63ee113780b3d.png','16-02-2023 04:52 PM','200',NULL,'Abrama-Dharampur Road, Abrama Village, Valsad, Gujarat','20.5923493','72.9422753',NULL,'2023-02-16-63ee143430cdd.png','16-02-2023 05:02 PM','280',NULL,'Lotus Hospital Road, Ramwadi, Ramji Tekra, Valsad, Gujarat, India','20.6147567','72.9264922','2023-02-16','2023-02-16',0),(34,'5kuOhi',25,'Amit Hospital','2023-02-16-63ee0de34faf3.png','16-02-2023  04:34 PM','14','Amit Hospital, Halar Road, beside SBI Bnak, opp. Avabai High School, Dharampur, Valsad, Gujarat, India','110','20','2023-02-16-63ee0e1190527.png','Amit Hospital','20.6098396','72.927289','16-02-2023  04:44 PM','2023-02-16-63ee0fd85961f.png','2023-02-16-63ee100982c25.png','150',NULL,'25','20.6114102','72.9323088','AARSH LAB, Kapadia Chal, Valsad, Gujarat, India',NULL,3,NULL,'2023-02-16-63ee113780b3d.png','16-02-2023 04:52 PM','200',NULL,'Abrama-Dharampur Road, Abrama Village, Valsad, Gujarat','20.5923493','72.9422753',NULL,'2023-02-16-63ee135766e4c.png','16-02-2023 04:58 PM','250',NULL,'Amit Hospital, Halar Road, beside SBI Bnak, opp. Avabai High School, Dharampur, Valsad, Gujarat, India','20.6098396','72.927289','2023-02-16','2023-02-16',0),(35,'7HWY7Y',16,'Cooper Hospital','2023-02-18-63f0723fe7bb2.png','18-02-2023  12:07 PM','13','501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','1','1','2023-02-18-63f07265b5003.png','Cooper Hospital','19.10784737','72.83608713','18-02-2023  12:12 PM','2023-02-18-63f07319b3d80.png','2023-02-18-63f0734020c23.png','5',NULL,'1','19.10784737','72.83608713','501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India',NULL,3,NULL,'2023-02-18-63f073f74d8e6.png','18-02-2023 12:15 PM','1',NULL,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713',NULL,'2023-02-18-63f074cd7e61a.png','18-02-2023 12:18 PM','1',NULL,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','2023-02-18','2023-02-18',0),(36,'RKXGXN',25,'Lotus Hospital','2023-02-21-63f45dd406d4d.png','21-02-2023  11:29 AM','15','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','28','5','2023-02-21-63f45de8bbeb3.png','Lotus Hospital','20.618535','72.9339856','21-02-2023  11:42 AM','2023-02-21-63f460aa159c1.png','2023-02-21-63f460bf275ef.png','50',NULL,'8','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-21-63f4625b311df.png','21-02-2023 11:50 AM','20',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-21-63f462f573d34.png','21-02-2023 11:51 AM','50',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','2023-02-21',0),(37,'kd4UBX',25,'Lotus Hospital','2023-02-21-63f46a7a54a78.png','21-02-2023  12:23 PM','15','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20','5','2023-02-21-63f46a895230e.png','Lotus Hospital','20.618535','72.9339856','21-02-2023  12:50 PM','2023-02-21-63f47082adb05.png','2023-02-21-63f470aacb6ef.png','30',NULL,'6','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-21-63f4725c6b64b.png','21-02-2023 12:58 PM','30',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-21-63f472ee23f3b.png','21-02-2023 12:59 PM','30',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','2023-02-21',0),(38,'BOCvJE',25,'Amit Hospital','2023-02-21-63f4736744b0d.png','21-02-2023  01:01 PM','14','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','10','1','2023-02-21-63f4737b7e2f6.png','Amit Hospital','20.618535','72.9339856','21-02-2023  01:34 PM','2023-02-21-63f47ae19b440.png','2023-02-21-63f47af5e718e.png','20',NULL,'5','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-21-63f47b9631d5f.png','21-02-2023 01:37 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-21-63f47c5b3afb5.png','21-02-2023 01:40 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','2023-02-21',0),(39,'2CkbT7',25,'Lotus Hospital','2023-02-21-63f47454113c7.png','21-02-2023  01:05 PM','15','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','10','2','2023-02-21-63f47469c4949.png','Lotus Hospital','20.618535','72.9339856','21-02-2023  01:35 PM','2023-02-21-63f47b37b07b7.png','2023-02-21-63f47b5b35db2.png','10',NULL,'10','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,3,NULL,'2023-02-21-63f47c0329dfa.png','21-02-2023 01:39 PM','20',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-21-63f47c8ca57f3.png','21-02-2023 01:41 PM','10',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','2023-02-21',0),(40,'FeemjK',25,'Amit Hospital','2023-02-21-63f47f473aea7.png','21-02-2023  01:52 PM','14','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','2','6','2023-02-21-63f47f5a10101.png','Amit Hospital','20.618535','72.9339856',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,NULL,'2023-02-21-63f481085b6b6.png','21-02-2023 02:00 PM','5',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856',NULL,'2023-02-21-63f48149a649e.png','21-02-2023 02:01 PM','5',NULL,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','2023-02-21',0),(41,'FW19Fd',25,'Lotus Hospital','2023-02-21-63f48187a34d9.png','21-02-2023  02:01 PM','15','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','5','5','2023-02-21-63f481966c7e2.png','Lotus Hospital','20.618535','72.9339856','21-02-2023  02:03 PM','2023-02-21-63f481d8cd64f.png','2023-02-21-63f481f1eeee0.png','8',NULL,'5','20.618535','72.9339856','JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-02-21',NULL,0);
/*!40000 ALTER TABLE `add_sample_collected_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_sample_invoice`
--

DROP TABLE IF EXISTS `add_sample_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_sample_invoice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `district_id` bigint(20) DEFAULT NULL,
  `rate` varchar(105) DEFAULT NULL,
  `total_sample` varchar(105) DEFAULT NULL,
  `amount` varchar(105) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `created_time` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_sample_invoice`
--

LOCK TABLES `add_sample_invoice` WRITE;
/*!40000 ALTER TABLE `add_sample_invoice` DISABLE KEYS */;
INSERT INTO `add_sample_invoice` VALUES (1,'2023-01-01','2023-01-31',3,'10','4','40','2023-02-11','11:47:55'),(2,'2023-02-15','2023-02-17',15,'10','3','30','2023-02-20','10:47:47');
/*!40000 ALTER TABLE `add_sample_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_sample_report`
--

DROP TABLE IF EXISTS `add_sample_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_sample_report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `total_km` varchar(45) DEFAULT NULL,
  `sample_id` varchar(45) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `collected_from` bigint(20) DEFAULT NULL,
  `from_date_time` datetime DEFAULT NULL,
  `to_date_time` datetime DEFAULT NULL,
  `lab_id` varchar(105) DEFAULT NULL,
  `district` varchar(105) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_sample_report`
--

LOCK TABLES `add_sample_report` WRITE;
/*!40000 ALTER TABLE `add_sample_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `add_sample_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_selected_sample`
--

DROP TABLE IF EXISTS `add_selected_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_selected_sample` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sample_id` varchar(45) DEFAULT NULL,
  `collection_id` varchar(45) DEFAULT NULL,
  `staff_id` varchar(45) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_selected_sample`
--

LOCK TABLES `add_selected_sample` WRITE;
/*!40000 ALTER TABLE `add_selected_sample` DISABLE KEYS */;
/*!40000 ALTER TABLE `add_selected_sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_specimen`
--

DROP TABLE IF EXISTS `add_specimen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_specimen` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(205) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_specimen`
--

LOCK TABLES `add_specimen` WRITE;
/*!40000 ALTER TABLE `add_specimen` DISABLE KEYS */;
INSERT INTO `add_specimen` VALUES (1,'serum samples','2023-01-18 16:08:33'),(2,'virology swab samples','2023-01-18 16:13:57'),(3,'biopsy and necropsy tissue','2023-01-18 16:24:59'),(4,'cerebrospinal fluid','2023-01-23 17:32:48'),(10,'Test','2023-01-26 10:47:21'),(11,'Example','2023-01-26 10:48:44'),(12,'Hematology','2023-01-26 10:49:59'),(13,'Sputum AFB','2023-01-26 11:03:58');
/*!40000 ALTER TABLE `add_specimen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_staff_activity`
--

DROP TABLE IF EXISTS `add_staff_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_staff_activity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) DEFAULT NULL,
  `address` text,
  `latitude` varchar(105) DEFAULT NULL,
  `longitude` varchar(105) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `created_time` time DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `kilometer` varchar(105) DEFAULT NULL,
  `actual_kilometer` varchar(105) DEFAULT NULL,
  `sample_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_staff_activity`
--

LOCK TABLES `add_staff_activity` WRITE;
/*!40000 ALTER TABLE `add_staff_activity` DISABLE KEYS */;
INSERT INTO `add_staff_activity` VALUES (1,14,'\"\"','\"\"','\"\"','2023-02-13','21:45:45',1,13,NULL,NULL,NULL),(2,14,NULL,NULL,NULL,'2023-02-13','21:25:48',2,7,NULL,NULL,NULL),(3,14,NULL,NULL,NULL,'2023-02-13','21:20:53',1,13,NULL,NULL,NULL),(4,14,NULL,NULL,NULL,'2023-02-13','21:53:55',2,7,NULL,NULL,NULL),(5,14,NULL,NULL,NULL,'2023-02-13','21:07:58',3,7,NULL,NULL,NULL),(6,14,NULL,NULL,NULL,'2023-02-13','21:49:58',4,13,NULL,NULL,NULL),(7,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','10:45:03',1,2,NULL,NULL,NULL),(8,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','10:00:06',2,1,NULL,NULL,NULL),(9,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-14','10:34:08',3,1,NULL,NULL,NULL),(10,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','10:39:11',4,2,NULL,NULL,NULL),(11,3,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','10:15:38',1,1,NULL,NULL,NULL),(12,3,'UCO Bank - Halar Road Branch, avabhai high school, Halar Road, Kapadia Chal, Valsad, Gujarat','20.6098459','72.9259709','2023-02-14','10:55:17',2,2,NULL,NULL,NULL),(13,3,'Tithal Road, Zinnat Nagar, Valsad, Gujarat','20.6046258','72.912201','2023-02-14','10:21:03',3,2,NULL,NULL,NULL),(14,3,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','10:23:47',1,3,NULL,NULL,NULL),(15,3,'UCO Bank - Halar Road Branch, avabhai high school, Halar Road, Kapadia Chal, Valsad, Gujarat','20.6098459','72.9259709','2023-02-14','10:24:53',2,2,NULL,NULL,NULL),(16,3,'Abrama-Dharampur Road, Abrama Village, Valsad, Gujarat','20.5923493','72.9422753','2023-02-14','10:27:02',1,3,NULL,NULL,NULL),(17,3,'Tithal Road, Zinnat Nagar, Valsad, Gujarat','20.6046258','72.912201','2023-02-14','10:28:20',2,2,NULL,NULL,NULL),(18,3,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-14','10:31:19',3,2,NULL,NULL,NULL),(19,3,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','10:32:55',4,1,NULL,NULL,NULL),(20,3,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-14','10:33:46',4,3,NULL,NULL,NULL),(21,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-16','16:33:54',1,15,NULL,NULL,NULL),(22,25,'Amit Hospital, Halar Road, beside SBI Bnak, opp. Avabai High School, Dharampur, Valsad, Gujarat, India','20.6098396','72.927289','2023-02-16','16:38:46',1,14,NULL,NULL,NULL),(23,25,'AARSH LAB, Kapadia Chal, Valsad, Gujarat, India','20.6114102','72.9323088','2023-02-16','16:44:55',2,8,NULL,NULL,NULL),(24,25,'Abrama-Dharampur Road, Abrama Village, Valsad, Gujarat','20.5923493','72.9422753','2023-02-16','16:53:20',3,8,NULL,NULL,NULL),(25,25,'Amit Hospital, Halar Road, beside SBI Bnak, opp. Avabai High School, Dharampur, Valsad, Gujarat, India','20.6098396','72.927289','2023-02-16','16:59:01',4,14,NULL,NULL,NULL),(26,25,'Lotus Hospital Road, Ramwadi, Ramji Tekra, Valsad, Gujarat, India','20.6147567','72.9264922','2023-02-16','17:02:40',4,15,NULL,NULL,NULL),(27,16,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','2023-02-18','12:10:48',1,13,NULL,NULL,NULL),(28,16,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','2023-02-18','12:12:31',2,7,NULL,NULL,NULL),(29,16,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','19.10784737','2023-02-18','12:16:05',3,7,NULL,NULL,NULL),(30,16,'501, Gulmohar Rd, JVPD Scheme, Vile Parle West, Mumbai, Maharashtra 400056, India','19.10784737','72.83608713','2023-02-18','12:19:08',4,13,NULL,NULL,NULL),(31,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','11:33:34',1,15,NULL,NULL,NULL),(32,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','11:40:27',2,8,NULL,NULL,NULL),(33,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','11:42:38',2,9,NULL,NULL,NULL),(34,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-21','11:47:59',3,8,NULL,NULL,NULL),(35,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-21','11:51:02',3,9,NULL,NULL,NULL),(36,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','11:52:08',4,15,NULL,NULL,NULL),(37,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','12:28:01',1,15,NULL,NULL,NULL),(38,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','12:44:08',2,9,NULL,NULL,NULL),(39,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','12:50:43',2,8,NULL,NULL,NULL),(40,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-21','12:56:55',3,9,NULL,NULL,NULL),(41,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-21','12:58:51',3,8,NULL,NULL,NULL),(42,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:00:15',4,15,NULL,NULL,NULL),(43,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:04:29',1,14,NULL,NULL,NULL),(44,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:07:36',1,15,NULL,NULL,NULL),(45,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:34:52',2,8,NULL,NULL,NULL),(46,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:36:16',2,9,NULL,NULL,NULL),(47,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-21','13:37:52',3,8,NULL,NULL,NULL),(48,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-21','13:39:38',3,9,NULL,NULL,NULL),(49,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:40:30',4,14,NULL,NULL,NULL),(50,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:41:16',4,15,NULL,NULL,NULL),(51,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','13:54:04',1,14,NULL,NULL,NULL),(52,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','20.618535','2023-02-21','14:00:49',3,8,NULL,NULL,NULL),(53,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','14:01:28',4,14,NULL,NULL,NULL),(54,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','14:03:11',1,15,NULL,NULL,NULL),(55,25,'JW9M 9HG, Chhipwad, Valsad, Gujarat 396001, India','20.618535','72.9339856','2023-02-21','14:04:17',2,8,NULL,NULL,NULL);
/*!40000 ALTER TABLE `add_staff_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_submitted_hospital_report`
--

DROP TABLE IF EXISTS `add_submitted_hospital_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_submitted_hospital_report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_id` varchar(45) DEFAULT NULL,
  `hospital_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `lt_name` varchar(205) DEFAULT NULL,
  `designation` varchar(205) DEFAULT NULL,
  `digital_signature` varchar(205) DEFAULT NULL,
  `sample_selected_id` varchar(205) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_submitted_hospital_report`
--

LOCK TABLES `add_submitted_hospital_report` WRITE;
/*!40000 ALTER TABLE `add_submitted_hospital_report` DISABLE KEYS */;
INSERT INTO `add_submitted_hospital_report` VALUES (1,'Zz5HIj',1,3,'Rahul','Lab Technician','2023-01-20-63ca99b22ce7f.png','[1,2]','2023-01-20'),(2,'zvZFdy',1,3,'Rahul','Lab Technician','2023-01-20-63caa240f21f7.png','[3]','2023-01-20'),(3,'0zXAzM',3,1,'Akash','Lab Technician','2023-01-20-63caa78522c9b.png','[4,5]','2023-01-20'),(5,'l65qIr',6,25,'Rahul Patel','Lab Technician','2023-01-21-63cb97ee1cfca.png','[7, 8]','2023-01-21'),(6,'HIJbxK',10,30,'jk lt name','kd designation','2023-01-26-63d22576e2a13.png','[12]','2023-01-26'),(7,'qN2l0U',9,25,'akash singh','lab Technician','2023-02-08-63e3890dbf976.png','[25, 26]','2023-02-08'),(8,'u1BoSo',5,5,'gcgcgcg','gccy','2023-02-10-63e5eefe348ef.png','[29]','2023-02-10'),(9,'RBpZOA',9,25,'rohan patel','lab technician','2023-02-13-63e9dee4587df.png','[31, 32]','2023-02-13'),(10,'BCuA23',3,25,'akash singh','lab technician','2023-02-13-63e9faf620dad.png','[33, 34]','2023-02-13'),(11,'fy5o06',13,14,'pooja','ahb','2023-02-13-63ea6540b396d.png','[35, 36]','2023-02-13'),(12,'EQhM6y',2,25,'rahul patel','lab technician','2023-02-14-63eb1102d6205.png','[37]','2023-02-14'),(13,'tYAy41',1,25,'akash singh','lab technician','2023-02-14-63eb15fe2261c.png','[38]','2023-02-14'),(14,'lZS5F2',3,3,'akash Singh','lab Technician','2023-02-14-63eb163177f70.png','[39, 40]','2023-02-14'),(15,'ifohun',14,25,'Rahul Patel','Lab Technician','2023-02-16-63ee137985b0d.png','[43]','2023-02-16'),(16,'CKguu6',15,25,'Rahul Patel','Lab Technician','2023-02-16-63ee145558243.png','[41, 42]','2023-02-16'),(17,'J2YtOj',13,16,'bhavi','bhavi','2023-02-18-63f074e2857be.png','[44]','2023-02-18'),(18,'pwkvYX',15,25,'rahul patel','lab technician','2023-02-21-63f4630f7504f.png','[45, 46]','2023-02-21'),(19,'Udz82h',15,25,'rahul patel','lab technician','2023-02-21-63f47306654c0.png','[47, 48, 49]','2023-02-21'),(20,'8uuvlh',14,25,'rahul patel','lab technician','2023-02-21-63f47c75a3c6c.png','[50, 51]','2023-02-21'),(21,'aHxpdL',15,25,'rahul Patel','lab technician','2023-02-21-63f47ca3ef0e0.png','[52]','2023-02-21'),(22,'UCwguc',14,25,'rahul patel','lab technician','2023-02-21-63f4815fc6b36.png','[53]','2023-02-21');
/*!40000 ALTER TABLE `add_submitted_hospital_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_submitted_hospital_sample`
--

DROP TABLE IF EXISTS `add_submitted_hospital_sample`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_submitted_hospital_sample` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `report_id` varchar(45) DEFAULT NULL,
  `collected_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `sample_selected_id` bigint(20) DEFAULT NULL,
  `hospital_id` bigint(20) DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_submitted_hospital_sample`
--

LOCK TABLES `add_submitted_hospital_sample` WRITE;
/*!40000 ALTER TABLE `add_submitted_hospital_sample` DISABLE KEYS */;
INSERT INTO `add_submitted_hospital_sample` VALUES (1,'Zz5HIj',1,3,1,1,'2023-01-20'),(2,'Zz5HIj',1,3,2,1,'2023-01-20'),(3,'zvZFdy',2,3,3,1,'2023-01-20'),(4,'0zXAzM',3,1,4,3,'2023-01-20'),(5,'0zXAzM',3,1,5,3,'2023-01-20'),(7,'l65qIr',5,25,7,6,'2023-01-21'),(8,'l65qIr',5,25,8,6,'2023-01-21'),(9,'HIJbxK',6,30,12,10,'2023-01-26'),(10,'qN2l0U',7,25,25,9,'2023-02-08'),(11,'qN2l0U',7,25,26,9,'2023-02-08'),(12,'u1BoSo',8,5,29,5,'2023-02-10'),(13,'RBpZOA',9,25,31,9,'2023-02-13'),(14,'RBpZOA',9,25,32,9,'2023-02-13'),(15,'BCuA23',10,25,33,3,'2023-02-13'),(16,'BCuA23',10,25,34,3,'2023-02-13'),(17,'fy5o06',11,14,35,13,'2023-02-13'),(18,'fy5o06',11,14,36,13,'2023-02-13'),(19,'EQhM6y',12,25,37,2,'2023-02-14'),(20,'tYAy41',13,25,38,1,'2023-02-14'),(21,'lZS5F2',14,3,39,3,'2023-02-14'),(22,'lZS5F2',14,3,40,3,'2023-02-14'),(23,'ifohun',15,25,43,14,'2023-02-16'),(24,'CKguu6',16,25,41,15,'2023-02-16'),(25,'CKguu6',16,25,42,15,'2023-02-16'),(26,'J2YtOj',17,16,44,13,'2023-02-18'),(27,'pwkvYX',18,25,45,15,'2023-02-21'),(28,'pwkvYX',18,25,46,15,'2023-02-21'),(29,'Udz82h',19,25,47,15,'2023-02-21'),(30,'Udz82h',19,25,48,15,'2023-02-21'),(31,'Udz82h',19,25,49,15,'2023-02-21'),(32,'8uuvlh',20,25,50,14,'2023-02-21'),(33,'8uuvlh',20,25,51,14,'2023-02-21'),(34,'aHxpdL',21,25,52,15,'2023-02-21'),(35,'UCwguc',22,25,53,14,'2023-02-21');
/*!40000 ALTER TABLE `add_submitted_hospital_sample` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_test`
--

DROP TABLE IF EXISTS `add_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_test` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(205) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_test`
--

LOCK TABLES `add_test` WRITE;
/*!40000 ALTER TABLE `add_test` DISABLE KEYS */;
INSERT INTO `add_test` VALUES (1,'blood analysis','2023-01-18 16:08:33'),(2,'gastric fluid analysis','2023-01-18 16:13:57'),(3,'kidney function test.','2023-01-18 16:24:59'),(4,'liver function test','2023-01-23 17:32:48'),(5,'lumbar puncture.','2023-01-25 19:57:06'),(7,'Test','2023-01-26 10:51:21'),(8,'abc','2023-01-26 10:51:27'),(9,'xyz','2023-01-26 10:51:33'),(10,'KD TEST','2023-01-26 10:54:52');
/*!40000 ALTER TABLE `add_test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `add_url`
--

DROP TABLE IF EXISTS `add_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `add_url` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `url` varchar(205) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `add_url`
--

LOCK TABLES `add_url` WRITE;
/*!40000 ALTER TABLE `add_url` DISABLE KEYS */;
INSERT INTO `add_url` VALUES (1,'http://mjk.workfordemo.in/');
/*!40000 ALTER TABLE `add_url` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `mo_no` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `a_password` varchar(255) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'MJK','9999999999','$2a$10$Njph8UW4mf0todPimyfMPOUNG1fWkmj/iDG61gg5p/cVBu8d3vwI.','$2a$10$Njph8UW4mf0todPimyfMPOUNG1fWkmj/iDG61gg5p/cVBu8d3vwI.','1',NULL,'admin@gmail.com','Z5hgwBCo5Y7IyO07Ymq58FMZn2D9L2vJ1rnGa97wB7uQhRp8lifp2HCsGUO6');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assign_district`
--

DROP TABLE IF EXISTS `assign_district`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assign_district` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) DEFAULT NULL,
  `district_id` bigint(20) DEFAULT NULL,
  `assign_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assign_district`
--

LOCK TABLES `assign_district` WRITE;
/*!40000 ALTER TABLE `assign_district` DISABLE KEYS */;
INSERT INTO `assign_district` VALUES (1,32,3,'2023-02-09','2023-02-09 14:40:10'),(2,25,2,'2023-02-09','2023-02-09 14:47:39'),(3,25,15,'2023-02-16','2023-02-16 11:15:23'),(4,30,3,'2023-02-16','2023-02-16 11:28:23');
/*!40000 ALTER TABLE `assign_district` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assign_hospital`
--

DROP TABLE IF EXISTS `assign_hospital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assign_hospital` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hospital_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `assign_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assign_hospital`
--

LOCK TABLES `assign_hospital` WRITE;
/*!40000 ALTER TABLE `assign_hospital` DISABLE KEYS */;
INSERT INTO `assign_hospital` VALUES (1,2,32,'2023-02-09','2023-02-09 14:41:21'),(2,13,14,'2023-02-13','2023-02-13 21:43:27'),(3,15,25,'2023-02-16','2023-02-16 11:18:02'),(4,14,25,'2023-02-16','2023-02-16 11:18:13');
/*!40000 ALTER TABLE `assign_hospital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assign_lab`
--

DROP TABLE IF EXISTS `assign_lab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assign_lab` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lab_id` bigint(20) DEFAULT NULL,
  `staff_id` bigint(20) DEFAULT NULL,
  `assign_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assign_lab`
--

LOCK TABLES `assign_lab` WRITE;
/*!40000 ALTER TABLE `assign_lab` DISABLE KEYS */;
INSERT INTO `assign_lab` VALUES (1,2,32,'2023-02-09','2023-02-09 14:41:29'),(2,9,25,'2023-02-16','2023-02-16 11:16:26'),(3,8,25,'2023-02-16','2023-02-16 11:16:34');
/*!40000 ALTER TABLE `assign_lab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distance_status`
--

DROP TABLE IF EXISTS `distance_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distance_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distance_status`
--

LOCK TABLES `distance_status` WRITE;
/*!40000 ALTER TABLE `distance_status` DISABLE KEYS */;
INSERT INTO `distance_status` VALUES (1,1,'2023-02-21 13:51:53');
/*!40000 ALTER TABLE `distance_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) DEFAULT NULL,
  `tokenable_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `token` varchar(65) DEFAULT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=269 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (3,'App\\Models\\User',2,'MyAuthApp','21438a64f2aaa78ab7b215a901a79f23be1adaa1f8402528196f9470f5c24ec2','[\"*\"]',NULL,'2022-12-20 19:30:47','2022-12-20 19:30:47'),(11,'App\\Models\\User',2,'MyAuthApp','117c15dffbf1d933647b3163dfb9fea0961ce0ff0a27ffbde62dd004480edf3a','[\"*\"]',NULL,'2022-12-21 00:07:58','2022-12-21 00:07:58'),(16,'App\\Models\\User',4,'MyAuthApp','d2ec91efe39da31a38fb6d06a4fd0005b184d77967a2370152a48bdea37a4da2','[\"*\"]',NULL,'2022-12-21 00:34:13','2022-12-21 00:34:13'),(73,'App\\Models\\User',8,'MyAuthApp','3c4f0b1511e5394b3a1c170c9d068c171ceb1db3b9e02104a7578b2bfbb80da4','[\"*\"]','2022-12-23 22:41:28','2022-12-23 22:41:28','2022-12-23 22:28:28'),(94,'App\\Models\\User',9,'MyAuthApp','282304dc86c9398668b9e18cf38ce02b128611486d22234346665da0153582f6','[\"*\"]',NULL,'2022-12-24 19:52:38','2022-12-24 19:52:38'),(102,'App\\Models\\User',10,'MyAuthApp','6d78bbfd49f8ccadff1178d2a1b38dfffd88f9768ecda24c18a9579ae64936e9','[\"*\"]','2022-12-24 22:52:08','2022-12-24 22:52:08','2022-12-24 22:35:06'),(105,'App\\Models\\User',11,'MyAuthApp','bd6fb9e4326d5ad12737c9038ac1eae565d161739072ef0e192b9d8b60b13d86','[\"*\"]',NULL,'2022-12-24 23:04:37','2022-12-24 23:04:37'),(184,'App\\Models\\User',14,'MyAuthApp','e5f80f917ba79a0efc2691739b66330f4213f0f5b6880fb6df9f759dc2108ce8','[\"*\"]','2023-02-21 20:57:49','2023-02-21 20:57:49','2023-01-05 01:42:17'),(186,'App\\Models\\User',15,'MyAuthApp','f8b4baf6e0c7633e21ffa03be822ce52875fd1066521d31853e93b6a6c94ebd1','[\"*\"]','2023-01-05 17:39:17','2023-01-05 17:39:17','2023-01-05 16:26:23'),(187,'App\\Models\\User',13,'MyAuthApp','7b5f7d8b4c00b80a953df7cbe7c168bcf4e03fcd207fff804bdb157fb5810771','[\"*\"]','2023-01-07 00:43:29','2023-01-07 00:43:29','2023-01-05 18:10:55'),(194,'App\\Models\\User',17,'MyAuthApp','7270d49e3382b95555e62e8ee89d4097bee0342b6a623ab5bd335cfb8d9b2c27','[\"*\"]','2023-01-06 22:17:12','2023-01-06 22:17:12','2023-01-06 21:52:01'),(195,'App\\Models\\User',12,'MyAuthApp','21b5b32594651a489ab8efe0ea1f9b979298e792318c30e1cc71085b79ca0f4b','[\"*\"]','2023-01-25 16:28:53','2023-01-25 16:28:53','2023-01-07 00:43:54'),(205,'App\\Models\\User',18,'MyAuthApp','fe955e37c1dd626dcccddd0a7baf1d7cb3e5bf5b61a6ea5145c4aa0a00c23d1b','[\"*\"]',NULL,'2023-01-13 23:42:11','2023-01-13 23:42:11'),(206,'App\\Models\\User',18,'MyAuthApp','57cf97c9191e4678e4f023506f40e776c01399299e2d2b6a07287f33d3f0b680','[\"*\"]','2023-01-14 00:07:45','2023-01-14 00:07:45','2023-01-13 23:42:11'),(210,'App\\Models\\User',20,'MyAuthApp','5feb656805704f89c7d6336453559debf165b07e5c304fd89e387aa835ea6e63','[\"*\"]','2023-01-27 01:29:16','2023-01-27 01:29:16','2023-01-14 04:11:53'),(211,'App\\Models\\User',21,'MyAuthApp','852413727b50e64d50fd1d6dde1b2fb2bde85baffcdf48477d2fae2e1b62b13c','[\"*\"]','2023-01-29 22:09:14','2023-01-29 22:09:14','2023-01-14 04:15:47'),(212,'App\\Models\\User',22,'MyAuthApp','a125aa9d5b365da3cb507baf39a09d97098e642b0f3b2c312a84237425550f68','[\"*\"]','2023-02-11 19:41:42','2023-02-11 19:41:42','2023-01-14 04:22:25'),(213,'App\\Models\\User',23,'MyAuthApp','d28a565c1321ca4b43c114d0e49a27c666c4927cab6008535480bcd9787a6fa0','[\"*\"]','2023-02-05 18:10:21','2023-02-05 18:10:21','2023-01-14 04:40:35'),(214,'App\\Models\\User',24,'MyAuthApp','081c9b852f1194470c71a27d486f729a13b280d14289ffceed3ea092c1d978f8','[\"*\"]','2023-02-16 07:25:15','2023-02-16 07:25:15','2023-01-14 04:44:39'),(226,'App\\Models\\User',26,'MyAuthApp','3d78efb03615d3e8bb1524f950fdf507dea7ef0bca1333b4d2b1b2e205a71ab2','[\"*\"]','2023-01-21 01:30:00','2023-01-21 01:30:00','2023-01-21 01:24:12'),(227,'App\\Models\\User',27,'MyAuthApp','9ef75372c1428611cba7322bf85c39ac2ba8dd34d54657ce51156e122770380b','[\"*\"]','2023-01-21 01:40:11','2023-01-21 01:40:11','2023-01-21 01:37:49'),(228,'App\\Models\\User',3,'MyAuthApp','d87500bec3f79009f2b199eb79c26bcb6f5bf6c22f7235b53a4c686b7a67ea4a','[\"*\"]','2023-01-21 03:05:13','2023-01-21 03:05:13','2023-01-21 01:39:38'),(230,'App\\Models\\User',28,'MyAuthApp','3701adbcc4c68b6cccbc9b3968c1b11c57ec9a27f06dc7fc29d89da14e66fc8a','[\"*\"]','2023-01-21 04:44:26','2023-01-21 04:44:26','2023-01-21 04:44:22'),(235,'App\\Models\\User',19,'MyAuthApp','29022442389fd4a54651341cbf83ddd197ec3797f3ad18dd2c32b287f337ab61','[\"*\"]','2023-01-24 00:45:21','2023-01-24 00:45:21','2023-01-24 00:45:15'),(237,'App\\Models\\User',7,'MyAuthApp','305dfd5a65d5c7fd9b2057284828069a233340d2382a1c3dc9def91e37bfdeb3','[\"*\"]','2023-01-26 17:54:52','2023-01-26 17:54:52','2023-01-25 16:54:23'),(238,'App\\Models\\User',1,'MyAuthApp','b310a8399343a623f13d0f848122ce163eb552bad0101535eb8034aa0da440a9','[\"*\"]','2023-01-25 20:04:47','2023-01-25 20:04:47','2023-01-25 18:47:16'),(239,'App\\Models\\User',29,'MyAuthApp','cd650ceebffe49fe376090329850544607d63b413ae316328bd7ba1c17c24f13','[\"*\"]','2023-01-26 17:28:28','2023-01-26 17:28:28','2023-01-26 16:50:33'),(243,'App\\Models\\User',6,'MyAuthApp','8c109ad88c66ddaf43f975060ebc044b5fd155e8e00f6e3cdd133da371bec9c5','[\"*\"]','2023-01-26 18:13:00','2023-01-26 18:13:00','2023-01-26 18:11:39'),(244,'App\\Models\\User',31,'MyAuthApp','49c8d483ee71a06f4167b783e1ceeb988602210a06c3dbee0d5838405f2097e5','[\"*\"]','2023-01-31 22:30:11','2023-01-31 22:30:11','2023-01-26 18:19:26'),(248,'App\\Models\\User',32,'MyAuthApp','3b84104516550e0aab642438e1a5ce3e64a302dc0e6f9936932880ce3055aaa2','[\"*\"]','2023-01-26 19:55:42','2023-01-26 19:55:42','2023-01-26 19:55:39'),(249,'App\\Models\\User',30,'MyAuthApp','8a7a9f342950e87db363c92921f170277df33cb063003b838fa56207e464845f','[\"*\"]','2023-02-09 23:12:08','2023-02-09 23:12:08','2023-01-31 17:02:28'),(260,'App\\Models\\User',5,'MyAuthApp','8ec18786f6f5d864e6a062d1384380bae9f9966c668ce7611b749ca1265b5dd2','[\"*\"]','2023-02-21 18:46:04','2023-02-21 18:46:04','2023-02-10 19:43:19'),(263,'App\\Models\\User',25,'MyAuthApp','e57649279cc30b4b209243c9a8b19f6bff16216b59705daa8e5b3a1bfab8fca5','[\"*\"]','2023-02-21 21:04:26','2023-02-21 21:04:26','2023-02-13 19:09:09'),(264,'App\\Models\\User',33,'MyAuthApp','756ef2722fdf201959e7262beb457c8b2b9ae94d9cf1972a14b6c2293dab7954','[\"*\"]','2023-02-16 22:11:46','2023-02-16 22:11:46','2023-02-16 22:11:43'),(265,'App\\Models\\User',16,'MyAuthApp','175e7511c9b84c432a0d1fdfcd33b31fb73d5fa303e9c6fda5666588dcaa1c42','[\"*\"]','2023-02-18 19:19:23','2023-02-18 19:19:23','2023-02-18 19:06:56'),(266,'App\\Models\\User',34,'MyAuthApp','bc4d5ecbe9841b4465f5056206f2a6e08c1c9bafead7824810e867aefda3391a','[\"*\"]','2023-02-21 18:33:31','2023-02-21 18:33:31','2023-02-21 17:41:19'),(268,'App\\Models\\User',35,'MyAuthApp','665560bdb03bee6adc3b610d90ec7393c9018ba4f19ae7672b3e590a87fcc7b2','[\"*\"]','2023-02-21 21:21:06','2023-02-21 21:21:06','2023-02-21 20:57:56');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(105) DEFAULT 'N/A',
  `email` varchar(105) DEFAULT 'N/A',
  `password` varchar(105) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT 'N/A',
  `image` varchar(105) DEFAULT 'N/A',
  `device_id` varchar(305) DEFAULT 'N/A',
  `status` int(11) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Zarna Patel','unitech.zarna7@gmail.com','$2y$10$rf2PnRWpiDY7S6CteXcl3uXa8LJWoX7Y2dZ.lpz78zgd1jPM3mGi.','1212121212','2023-01-26-63d209a9081d7.png','epNbZXUATIqD9lCMMUwhVm:APA91bHPD24vftcw1JuXRnNF3zdnMhUMuMyuk81im5J9I-q2ov5BhyLOJ5ywNeNDRUx3EbUKULloqOPCAEqebGN7UHlZEZXEhoMGpNSqMmuxuvcB63_rMtqef_DoNNtDkT4HASYqllge',1,'2022-12-20 12:47:36'),(2,'Zarna Test','zarna02@gmail.com','$2y$10$r10cz.FhnydVTeXErUBYF.eKTYcofMi4Z0jXHbtZZbqLkDfs4v.Cy','4545454545','N/A','73234567',1,'2022-12-20 17:58:07'),(3,'Isha Shah','isha02@gmail.com','$2y$10$W/dKjQAIyE6ltEu/ZhK3L.X6LEe0pTScqzX4EWajKO5DCFKXwtyE.','7878787878','N/A','epNbZXUATIqD9lCMMUwhVm:APA91bHPD24vftcw1JuXRnNF3zdnMhUMuMyuk81im5J9I-q2ov5BhyLOJ5ywNeNDRUx3EbUKULloqOPCAEqebGN7UHlZEZXEhoMGpNSqMmuxuvcB63_rMtqef_DoNNtDkT4HASYqllge',1,'2022-12-20 17:14:13'),(4,'isha','isha03@gmail.com','$2y$10$XHleQwoEQEr8mmPO713vMO7xg6MWUt6krbKNTZYdh95gYWk3cxFBC','N/A','N/A','abc',1,'2022-12-20 17:44:32'),(5,'Sujal Varsadiya','sujal.unitech@gmail.com','$2y$10$4CzGt5nW6mgt2/YqrZ0puu9tdyTkFX6jBR1gpZNxtcIG51fJfYj3C','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','cUyWl7KcSQmSCmmCIczYcO:APA91bHk0norC0LVIAEVyVvu_fmxv-i7sqBSC_Jsu2yxIXNSIdMyicypZUeIv_khkvUbr8wgeitI104hinY3f0YMsgKK3qHXsSQ2Zto0RPs-xaG4kpJmv3qkXhxc38Fx6SQAjU4IrOKg',1,'2022-12-20 17:01:41'),(6,'Rahul Singh','phoneunitech@gmail.com','$2y$10$r8xeQ7Mqz2yCVpmQL2rEaOWaCO2AKiBt3yOF8ahiUMoau1Qa3FIre','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','c4vQGf4VSAGQ503cSirtXf:APA91bGKD97L6c5b7ny97Z84nJftDzLVViXoPIMANlfBAh_j1D0VYvn5btcHZL2SWajGWKnkJN5bHBbr9IytiMxIwpSRmwQvaZz0j44ASNjT7ZKEC6mkFx6YXqB-_qpiRlDxhWUKfOun',1,'2022-12-21 12:32:24'),(8,'Darshan Dobariya','darshandobariya127@gmail.com','$2y$10$yzusHYedJPuxqo.2Dg92Jux123fQ78IeafRr8oo1FHhy5sFHnpzWm','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','drOaJoBTRqaJAuAJLIrNLk:APA91bFEQ-ibNAddmcjDEcwbRzER0MPmTJFAI3RQDnuxOGoTz23j6guRFdZm-p_OFz_Ow3xMgggUIRk4d1WbicvEcm3mIl3OV483Ho1ypD1VymHbeeXIgtdgnFftkKkBiLPRSpygGotw',1,'2022-12-23 09:38:20'),(9,'Parth Patel','123parthtadhani@gmail.com','$2y$10$JWvk/h7NeIFrBrmO8sXsxunkYgXYK3a2XTAk7kcRr4uj0vXuaUz.i','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','cTFbQBbuRJi4djzxPebINy:APA91bFmPBmXU3M40cmEpLAHkYN-rlscn7sEZgM1RmoSdPgYaOW-ddP9VNHjx3XP_8lZQ3RfcRV_b3vCTBG1hI3TyK2IdTO0ArID8Vnk5qEZhxd5m6I92hqosJb8CdJGjQ3OXx2IajT0',1,'2022-12-23 11:37:39'),(10,'Unitech IT Solution','itsolution377@gmail.com','$2y$10$1s5Y7A7zO3leFOT3Md3Oxeszo.MSsOodYw3KFqzTTZfD9hy9tSSbS','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','cVE6uwIiQIqfWvLBCKVMLZ:APA91bF9Ottk7LAEqDef8JOKvWtwByh9BFM28dD9Gwr60O4ow72Y1OEMUlH3MokwimUE1mHFWrC4d5enr9RQf7htN4iN_Jkx0YsRl_aBEFMlQgrLf3evlnDlEkB8pmIHhYweiX7ewrkl',1,'2022-12-24 15:05:35'),(11,'hari unitech','unitech.harikrushna@gmail.com','$2y$10$IWHv1dNDCgg0F4z0eFp97u3KiW8hTTaZztB.UkJzyEEWKfFF1gkYm','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','fJEQ8S6oSNS2YtcaDj6OvM:APA91bF4EbjJU_3YBXDjeECDM24qR7IdSguHt-amtItd1iUbSXhhCjYAWOsKiPDs2oNAMrEp36JXFN6gW88lcd4CbkyO9K14b5wUebuviwhZL-WUcyQtB406bPlhqNy97h0r6wT9aMSt',1,'2022-12-24 15:01:36'),(12,'Demo','demotrial0912@gmail.com','$2y$10$51WbsT4nJzI90kliyC9v7.WgCz644X64ee5U0WQnwUPR6ZVOcsuLi','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','fGWyrZM7TuqG7rKX51rnzw:APA91bGFIRZkis4TCCZJwqraprp48MR1jJ62Smvp7gkHPf51A0tC9x9NcjatWpd-NBINbcAyuDGjMf_yzPD2HRC3SCgd5wzJmPY0SRIvUvXhEKbwVXjJcENDaJi2ZOE6SlvXeXbwmgA6',1,'2022-12-29 10:11:00'),(14,'harsh shah','harshsshah999@gmail.com','$2y$10$6Z52VjLlnqrqBgNu.S.PFO6HrCzjyEi78gzIWzVTV.2MophIaPm6O','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','fB9R81rsQ4G9t20Jevt1aE:APA91bFNS2i1kaIv-5lAYFMKM8905Fo3kvxl-0cGRyhwViTnAGwQDPjsg_0Y6U_fKc_Oph1DOn8qV1f5aNcJAHZ88yUtZtOdOsU4zASVNdpCHgcwEPEaZ-_qLycTxjVBHhxyTI6jOuj6',1,'2023-01-04 18:16:42'),(15,'Mitali Prajapati','mitaliprajapati327@gmail.com','$2y$10$tP53j4t13XTP/5sJEB6Bve7P4vqd5VPD50EmOT7W6bLmhRgwzwRIm','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','c4cwaWDNSIi9j8a0ynIhON:APA91bFL9o6pnzkG6FAUZlTJbRVkRChcW9whkTrWNRJjcV53czkiuIFt5f6rA48lq--uEzWGAB7W_qceRUe3baTFMen8Y3XudvawkrLm_qr1Mi6zmwY_VCzDyAcZVplajNf8YS7k0PNM',1,'2023-01-05 09:22:26'),(16,'Dishant Chandura','dishantchandura@gmail.com','$2y$10$oVlvLMevoreZqMf4V3ifmOg/gbz5OQPUIKF9Pqu010yuYQNgD6j9C','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','cR9Pab20Rt263fc9IlEtqG:APA91bH9dGFyzCLWop5wHi76dNjNL-jUY5VbA5jJnJTZzZXvuX_2v6H53LB-9oaoyERKr6ziSAr2eMWuqhmlONKIfElc4bFxMgYzPg3J7ht7_PThPiwfsnjFtYJDq9IUcoiPJ1x9dS2f',1,'2023-01-05 19:33:28'),(17,'Parth Mithapara','parthunitech123@gmail.com','$2y$10$Ppv127mhFsKeV0cTo30xm.KGCkpRqFwacIwgUJ3d46c9lUBqW8yri','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','d3-HJyXBQgqoQpDbUeND-7:APA91bERZSjuZpL11r9d8FGKdIGCJ5HVXNKpY_MD3uWpnzwqjgqFPC-GdOMerJCn4CtmiwUkDIFV4vyRdQY6-_2schEfesVBfkZwJTnBZcJa6uCocyKNTSaEzWiqRD3H6dWhrnqNXnBd',1,'2023-01-06 14:01:52'),(18,'Project Manager','pmunitech2022@gmail.com','$2y$10$eZqTYsJdXuAMkFvwxCSDBe0FZiEFIajAT.9kuCZcv96wZnATvgyGO','N/A','https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg','ewKQ6E-hSGCp-maddqvxL9:APA91bFwDtK6Ir6kgQH-WS6cIXgjHUaBf_LUHA7hh1qfRAgvuaJMZT_DUBh83aQC5dTh0uSCz4QD25Odo-Ctnx-vmK6Lo3I8W_p_pWt8gaS22avqYWKem015x7rp_MlmlVSz4xn_1sG7',1,'2023-01-07 11:23:56'),(20,'Akbar Ali Sayyed','akbarsayyed12@gmail.com','$2y$10$vulm9MO5QCXmeauSbOYE8uQwISjCPXwRf5RJuF4.SBkBYV9TxPhiS','8989898989','2023-01-21-63cbada72e984.png','fD-jI8ldSbCZan8RqVt-ys:APA91bHiY_ZPcqTas4wS5OK7tLpzPuWXEIpuuE7vNru0VwlGIwgtLho6lkfto5cI5dZ41-O9pbvbMnzjGAvgoAhULGkwHg9GIlLlvJ9KR1LHtKpJ3juZPE5QVLmhd8UIfWqA92-3uoS4',1,'2023-01-13 21:53:11'),(21,'Pramod Palkar','ppalkar@ngomjk.org','$2y$10$Vj5lpfaFBFLDZ.ZEEZ0IgOCQ4pUXLtH4JrkEMYnKeIhtQVrEHjwCS','N/A','https://lh3.googleusercontent.com/a/AEdFTp7D6PlkRIN6wMD42G0Teb0iy3Vq6gVyQhvb2xsK=s96-c','dg4pF8jJS6uHerW28OpxhL:APA91bH7LkTu_73A8AJLP3j4rd3ij2M69y4sdLep8UH5zDYIBfZfAi7s9X3bKhG0mw2JAUgobtlsxA2kudVryJGHurSY7TeR2TezOH0GoeSmy7z-M4m1IXgzC5dfxu1s260vjUCyA5lf',1,'2023-01-13 21:47:15'),(22,'Amit Mahadik','mamit262@gmail.com','$2y$10$zJy2.82YQ0.1ziu8RNac0O4NJbEFNsx/BDoQwzSghF3mEpdDKBGyq','N/A','https://lh3.googleusercontent.com/a/AEdFTp7AbR00H6Y7epQOlqPZVRJzl_BQrV0ZmGN6U-wKcQ=s96-c','d_VKqeIsT6qOZV-q8yh_LX:APA91bHDt9mW6NlZT4_fXr2GlkJAGhdEhGzeiTX_Dcv-wNZwjqhcnK83VMRyYEWxLmyWszWF67nW1huRehD4HE8gnhK3ffSoKVEnldtVGcdRhx2I74_GCw8PO3gFGX_Uf0jPsSdEp0hx',1,'2023-01-13 21:25:22'),(23,'SADA shiv','schudnaik@ngomjk.org','$2y$10$dHobbrAGnVW8zBxe8bEc1eBl0.KdYo2mK2v..pJGmufTiOBDSLe/2','N/A','https://lh3.googleusercontent.com/a/AEdFTp5mmiIqLCQ4O9H8BZRXq_VRLaQcI0KFClNd-RjG=s96-c','e_caL1P9RvS5au849Pw_N5:APA91bF5pReJT89D48tGV6V3d-r5kfA_uesQZfoebIZxaUBNXmTykK8IbYE9kDc-zTeTIZ8G0Xc7IrSg8cOqA5eNRWRuMDc2PkV5yeEnEoO6VZfSrzDODescuuin8Cgt9bOauD5DOLf9',1,'2023-01-13 21:35:40'),(24,'Nitin Kamble','i.nitinkamble1310@gmail.com','$2y$10$bysEazv5xlFZPN6ZxVcW2erEhignf9kGUR3x03UGrbXm1fzWmBA6q','N/A','https://lh3.googleusercontent.com/a/AEdFTp7efx5y3ADeG7hh8vRKGJrx_V0cXhS_98niOZGmzQ=s96-c','eBWazk88Q_WF0Tt3yrDKhf:APA91bHGpH0t0QI0TB1LWYfRPD3hNGEA8_SXPRSNgT2H2hIWupY9dtMGcEtXEwGpfY-SZJfho3gBee332Ts9FBuoCgkmm5L4iY65FoqNeRPgnr17NREUDgcDdpbo5xJseoc-yeQjIU_Q',1,'2023-01-13 21:39:44'),(25,'Hansa Patel','hansapatel21120924@gmail.com','$2y$10$Q6n3p5M74LZUmnLT.6hZCOAYIZeNJSy0mfm706bI7NbTT378rhulC','N/A','https://lh3.googleusercontent.com/a/AEdFTp7amhhdmaEADEaADvGZOp7oUZ7F-C-1Lb6iP--W=s96-c','c260C0gxT8K0w8snDPZqnq:APA91bH5F_imgMIX3z66NLZniNb_4ypos9z5Dckx-EyLdYCzvnXHDsuokXntPJBPlSNidrriTyp_PsgJ3_szvZosXRKiIfULcWbfEx1C13e4F2PuqaD0XwyTiqWbZV8I796HYZOj81OP',1,'2023-01-16 17:30:34'),(30,'Keval Unitech','kd.unitech@gmail.com','$2y$10$0Z0ICQX6ycL3LTzygCzoJ.1dgxuEJXZGsSbBcywgPtFWuLZ08Jmbe','N/A','https://lh3.googleusercontent.com/a/AEdFTp5uUoUBC1ju9fSv3pPpRMofrSkQEUutccHcCcNC=s96-c','eEchiZZOS4uGaPf9krtg-g:APA91bG9kuzlMiYfLAdgG3AxCMu4r4PYJH2Vbi_AWkPq0S05XNqHxfEtpexBJu8AHOu1GYv_UG6NPHDt0oGXbTe12SugI_Lcnp7UU2NGt5qV64YoREcH0precTtE081Ve91VSh6N5jmo',1,'2023-01-26 10:47:58'),(31,'anish unitech','anishunitech@gmail.com','$2y$10$dkWdmhrYij.vvaF1VeKyVudh0U4yL9T3Zm/8jnGHbom72UN1nMrpm','N/A','https://lh3.googleusercontent.com/a/AEdFTp5N9cVW8kn-vl-SuFgYTn-8FKvCYGllAAKYpv3o=s96-c','cL6XG5ZbQl6_ZqJa5RFf4F:APA91bHMiA2H3g53vps9vpfGH2aOEh_VIJkKaD0GA4lxN5f03T2CqJcnwogMJmgVe7XzWv5wNQfGpQIGMTJiIazJEApY_SwTMZziXu5HIZX7ZfcdmUX-aIKJJAS2TdwK8MRoMxd0ZdRC',0,'2023-01-26 11:25:19'),(32,'Keval Danani','kevaldanani44@gmail.com','$2y$10$ExB4yoanKtZuBCzqIFmu6uT/MtuKf.xAlsVZdr9DiPhz.J/p0SO8S','N/A','https://lh3.googleusercontent.com/a/AEdFTp7n_KYsUqOx9cAvAp24jEepCD8oXKdZX7M4CCsTUg=s96-c','eWVIvN2BRLCVXA30RxQpl8:APA91bGGbzoPZuGuhsN21jp4tWkvqoOZpCQwvg9amBKWdE-maijP8GytGIADC25A16pUXpR_elURitTeH1vVtY1w04oj2zH77Gvzm5du8JnOcHq6D1lLXB42qk95ShHyKL6U3wOdupYm',0,'2023-01-26 12:39:55'),(33,'MJK PPSA','ppsamjk@gmail.com','$2y$10$mbTUpyX89TVZ8vK73SYBNe55IQrHAsM5ttu4zKRibl1NHnfiweWkK','N/A','https://lh3.googleusercontent.com/a/AEdFTp74sjd-YovMZQD0gNRKD13-ZSttwalVt2BUd2M=s96-c','eVmjkHq-Sh-yq55m6B28Pd:APA91bFkWKAn3DakEFgUXFLTQeLqmxMtUZbBBwOUd01Kz87GhOUFgKKjfic0JVkQwYO1yz2FI8YspF6CHCEKB3gIvZnCd1SJoQt0OAPojlXnGphict4gWFaO5Ggp4CJX-ziqWJWPAKal',1,'2023-02-16 15:43:11'),(34,'brijesh unitech','brijesh.unitechitsolution@gmail.com','$2y$10$lvZFeVtHctv9OYYH3NVtseeGP1kRSiy8liXYpwK99vTv1ZZBzZv2G','N/A','https://lh3.googleusercontent.com/a/AEdFTp4BRS05k4Y55dDmjeVXsaBnvuYZfTZp49CgpzO8=s96-c','e-VKzNzzTKqSHiHuRvOEzB:APA91bEDzb1aDbhVbFN-3KukX7ohMkwDLYspKZHlXYht2CR4Y8rIIfFyHRfjBTUZ3poUu4ugVALceIN8t2w5NIPzZtKsQ4xgPQJf9t47X8Wnxd6qz--YGrtx_WCk39L6jfSSFcOhFCJF',1,'2023-02-21 10:19:41'),(35,'Komal Khadake','komalkhadake1809@gmail.com','$2y$10$QdfcH7bCRa105cvtFMRElefgaYC8KQL.i.iL0ibbQlcWB.v5ebJiS','N/A','https://lh3.googleusercontent.com/a/AEdFTp4sfhGQKt0ZazjQlESfcJqFVMDcgodGSvCi9a_9=s96-c','esS0ukBtTGiVrO1zQASu90:APA91bH042A2p_9ZWRt82wspbkbv9WuWrXhavmAUJZgLDSkZOfFvd5ock5TY7KXw_KFMmS8SwAZ-61VHMx93osBd3EsM_by7hb5yxgeuMTv5wpZ49Ta_XghbyxgdmKG2Ue7vsTh-twm4',1,'2023-02-21 13:56:57');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-21 14:29:09
