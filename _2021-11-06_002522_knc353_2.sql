-- MySQL dump 10.13  Distrib 8.0.26, for macos11.3 (x86_64)
--
-- Host: 127.0.0.1    Database: knc353_2
-- ------------------------------------------------------
-- Server version	8.0.22

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
-- Table structure for table `age_group`
--

DROP TABLE IF EXISTS `age_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `age_group` (
  `age_group_id` int NOT NULL COMMENT 'primary key',
  `vaccination_date` date DEFAULT NULL COMMENT 'date available for vaccination',
  `min_age` mediumint DEFAULT NULL COMMENT 'minimum age in the group (inclusive)',
  `max_age` mediumint DEFAULT NULL COMMENT 'maximum age in the group (inclusive)',
  PRIMARY KEY (`age_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `age_group`
--

/*!40000 ALTER TABLE `age_group` DISABLE KEYS */;
INSERT INTO `age_group` VALUES (1,'2020-12-01',80,NULL),(2,'2021-01-01',70,79),(3,'2021-02-01',60,69),(4,'2021-02-15',50,59),(5,'2021-03-01',40,49),(6,'2021-04-01',30,39),(7,'2021-05-01',18,29),(8,'2021-06-01',12,17),(9,NULL,5,11),(10,NULL,0,4);
/*!40000 ALTER TABLE `age_group` ENABLE KEYS */;

--
-- Table structure for table `facility`
--

DROP TABLE IF EXISTS `facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facility` (
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `website` varchar(255) NOT NULL,
  `type` enum('hospital','clinic','special_installment') NOT NULL,
  `capacity` int NOT NULL,
  `manager` int DEFAULT NULL,
  `province` varchar(255) NOT NULL,
  PRIMARY KEY (`name`),
  KEY `manager` (`manager`),
  CONSTRAINT `facility_ibfk_1` FOREIGN KEY (`manager`) REFERENCES `person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table maintains information about Public Health facilities where the vaccination is performed';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facility`
--

/*!40000 ALTER TABLE `facility` DISABLE KEYS */;
INSERT INTO `facility` VALUES ('Centre hospitalier de l\'Université de Montréal','1051 Rue Sanguinet, Montréal, QC H2X 3E4','5148908000','https://www.chumontreal.qc.ca/joindre-le-chum','hospital',1000,7,'Quebec'),('Douglas Mental Health University Institute',' 6875 Bd LaSalle, Verdun, QC H4H 1R3','5147616131','http://www.douglas.qc.ca/?locale=en','hospital',900,3,'Quebec'),('Hopital Notre-Dame','1560 Sherbrooke St E, Montreal, Quebec H2L 4M1','5144138777','https://ciusss-centresudmtl.gouv.qc.ca/etablissement/hopital-notre-dame','hospital',400,9,'Quebec'),('Jewish General Hospital',' 3755 Chem. de la Côte-Sainte-Catherine, Montréal, QC H3T 1E2','5143408222','https://www.jgh.ca','hospital',800,2,'Quebec'),('Lakeshore General Hospital','160 Av Stillview Suite 1297, Pointe-Claire, QC H9R 2Y2','5146302225','https://fondationlakeshore.ca','hospital',1000,1,'Quebec'),('Montreal Children\'s Hospital','1001 Decarie Blvd, Montreal, Quebec H4A 3J1','5144124400','https://www.thechildren.com','hospital',450,7,'Quebec'),('montreal general hospital','1650 Cedar Ave, Montreal','5149341934','https://muhc.ca/','hospital',1000,NULL,'quebec'),('Montreal Neurological Hospital','3801 Rue University, Montréal, QC H3A 2B4','5143986644','https://www.mcgill.ca/neuro/fr','hospital',760,8,'Quebec'),('Royal Victoria Hospital','1001 Decarie Blvd, Montreal, Quebec H4A 3J1','5149341934','https://muhc.ca/glen','hospital',650,6,'Quebec'),('St. Mary\'s Hospital','3830 Av. Lacombe, Montréal, QC H3T 1M5','5142453511','https://www.smhc.qc.ca/en/','hospital',500,4,'Quebec');
/*!40000 ALTER TABLE `facility` ENABLE KEYS */;

--
-- Table structure for table `healthcare_worker`
--

DROP TABLE IF EXISTS `healthcare_worker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `healthcare_worker` (
  `person_id` int NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `role` enum('nurse','manager','security','secretary','regular') NOT NULL,
  `vaccine_name` varchar(255) DEFAULT NULL,
  `dose_given` mediumint DEFAULT NULL,
  `lot` varchar(255) DEFAULT NULL,
  KEY `vaccine_name` (`vaccine_name`),
  KEY `person_id` (`person_id`),
  KEY `facility_name` (`facility_name`),
  CONSTRAINT `healthcare_worker_ibfk_1` FOREIGN KEY (`vaccine_name`) REFERENCES `vaccine` (`vaccine_name`) ON DELETE CASCADE,
  CONSTRAINT `healthcare_worker_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`) ON DELETE CASCADE,
  CONSTRAINT `healthcare_worker_ibfk_3` FOREIGN KEY (`facility_name`) REFERENCES `facility` (`name`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table maintains the registration of public health care workers with public health care system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `healthcare_worker`
--

/*!40000 ALTER TABLE `healthcare_worker` DISABLE KEYS */;
INSERT INTO `healthcare_worker` VALUES (1,'St. Mary\'s Hospital','2018-10-10','2059-10-24','nurse','Pfizer',2,'z0001'),(2,'Jewish General Hospital','2021-10-07','2022-10-07','security','Pfizer',1,'z0002'),(3,'Centre hospitalier de l\'Université de Montréal','1959-12-28','2022-10-01','manager','AstraZeneca',2,'z0003'),(4,'Hopital Notre-Dame','2019-10-09','2024-10-01','secretary','Pfizer',2,'z0004'),(5,'Douglas Mental Health University Institute','2017-09-26','2041-01-29','nurse','Moderna',2,'Z0004'),(6,'montreal general hospital','2019-10-08','2023-09-27','regular','Pfizer',1,'z0006'),(7,'St. Mary\'s Hospital','2015-09-29','2059-12-28','manager','Moderna',2,'z0007'),(8,'Jewish General Hospital','2016-09-27','2050-02-01','security','Moderna',2,'z0008'),(9,'Lakeshore General Hospital','2021-09-28','2022-09-30','security','Moderna',1,'z0009'),(10,'Lakeshore General Hospital','2020-09-28','2022-09-30','secretary','Moderna',2,'z0010'),(2,'Montreal Children\'s Hospital','2021-09-26','2022-10-01','regular','Pfizer',2,'z0002'),(8,'Lakeshore General Hospital','2021-09-26','2022-09-25','security','Moderna',2,'z0008'),(15,'Douglas Mental Health University Institute','2020-10-21','2021-10-14','security',NULL,NULL,NULL),(16,'St. Mary\'s Hospital','2021-09-26','2022-09-25','nurse',NULL,NULL,NULL);
/*!40000 ALTER TABLE `healthcare_worker` ENABLE KEYS */;

--
-- Table structure for table `infection`
--

DROP TABLE IF EXISTS `infection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `infection` (
  `person_id` int NOT NULL,
  `date` date NOT NULL COMMENT 'date of infection',
  KEY `person_id` (`person_id`),
  CONSTRAINT `infection_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infection`
--

/*!40000 ALTER TABLE `infection` DISABLE KEYS */;
INSERT INTO `infection` VALUES (1,'2020-03-06'),(2,'2020-12-15'),(3,'2021-10-01'),(4,'2020-07-16'),(5,'2021-09-28'),(6,'2020-01-15'),(7,'2020-02-27'),(8,'2020-07-15'),(9,'2020-04-28'),(10,'2020-09-08');
/*!40000 ALTER TABLE `infection` ENABLE KEYS */;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `person` (
  `person_id` int NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `medicare_card_number` varchar(255) DEFAULT NULL,
  `date_of_issue_of_medicare_card` date DEFAULT NULL,
  `date_of_expiry_of_the_medicare_card` date DEFAULT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `citizenship` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passport_number` varchar(255) NOT NULL,
  `age_group_id` int DEFAULT NULL,
  `registered` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`person_id`),
  KEY `age_group_id` (`age_group_id`),
  CONSTRAINT `person_ibfk_1` FOREIGN KEY (`age_group_id`) REFERENCES `age_group` (`age_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,'James','Doe','1991-11-11','1','2020-11-11','2023-11-11','5140000000','1120 Rue Concordia','Montreal','Quebec','H4H3E3','Canada','casdi@gmail.com','sdaf-213',2,1),(2,'Jane','Doe','1997-11-11','2','2020-11-11','2023-11-11','5140000001','1120 Rue Montreal','lasalle','Quebec','H4H3G3','Canada','cadddi@gmail.com','sdaf-211',3,1),(3,'John','Doe','2001-11-11','3','2019-10-12','2023-10-12','5140000002','1120 Rue Guy','Montreal','Quebec','H4G3D3','Canada','cadsdi@gmail.com','sdaf-212',7,1),(4,'John','Doe','1991-01-11','4','2021-10-12','2024-10-12','5140000003','1120 Rue Catherine','Laval','Quebec','H4D3D3','Canada','casdi@gmail.com','sdaf-214',6,1),(5,'Sam','Doe','1988-01-11','5','2021-01-13','2024-01-12','5140000004','1120 Rue MaxKay','Brossard','Quebec','H5D3D3','Canada','ggsdfas@gmail.com','sdaf-215',10,1),(6,'John','Smith','1979-01-01','6','2021-10-12','2024-10-12','5140000005','1120 Rue Max','Montreal','Quebec','H4D3H3','Canada','sdfas@gmail.com','sdaf-216',5,1),(7,'Sam','Smith','1970-11-11','7','2021-10-12','2024-10-12','5140000006','1120 Rue Marie','Toronto','Ontario','H6D3A4','Canada','wedfa@gmail.com','sdaf-217',4,1),(8,'Kim','Smith','1990-09-11','8','2021-10-12','2024-10-12','5140000007','1120 Rue Ontario','Toronto','Ontario','H6D3B4','Canada','asddfa@gmail.com','sdaf-218',1,1),(9,'Jim','Carry','2000-12-01','9','2021-10-12','2024-10-12','5140000008','1120 Rue Quebec','Montreal','Quebec','H8D3A8','Canada','dfa@gmail.com','sdaf-219',8,1),(10,'Ali','Smith','1999-09-01','10','2021-10-12','2024-10-12','5140000009','1120 Rue Quebec','Montreal','Quebec','H8D3A8','Canada','dfa@gmail.com','sdaf-220',9,1),(11,'Zhangbin ','Cai','1993-07-09','','2020-09-08','2023-10-05','6476666666','1122 Ruy Guy','Royal-Mount','Quebec','H4G5T5','China','caizhan@concordia.ca','ch-00769',7,0),(12,'Ryan','Yan','1990-01-30','12','2019-10-10','2022-10-06','5140006666','2344 Rue Paquet','Quebec City','Quebec','H4D5N4','Canada','yan@concordia.ca','dca-6666',7,1),(13,'Rongxi','Meng','1989-06-06','13','2019-10-14','2023-10-18','5140004666','1235 Rue Maria','Montreal','Quebec','D4D5G5','China','xi@concordia.ca','ch-00699',6,1),(14,'Jason','Qian','1991-06-04','14','2021-09-27','2024-10-18','5146669466','1234 Rue Google','Montreal','Quebec','G4G5L6','China','qian@concordia.ca','ch-07666',7,1),(15,'Walter','White','1970-01-27','','2017-10-03','2020-10-07','6479965444','1000 Rue Texa','Laval','Quebec','L4L6Y7','US','walter@gmail.com','usd-0094',4,0),(16,'Marie','Hunt','1999-02-02','','2018-10-16','2021-09-27','5147774444','1234 Rue Hunter','Montreal','Quebec','D2D4G4','US','marie@gmail.com','adfa-999',7,0),(17,'Don','Jump','1961-01-31','15','2020-10-07','2023-10-04','4150000000','2222 Rue Pres','Laval','Quebec','D4D5T5','Canada','ttDrum@gmail.com','dfa-334',3,1);
/*!40000 ALTER TABLE `person` ENABLE KEYS */;

--
-- Table structure for table `vaccination`
--

DROP TABLE IF EXISTS `vaccination`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaccination` (
  `person_id` int DEFAULT NULL,
  `vaccine_name` varchar(255) DEFAULT NULL,
  `dose` mediumint DEFAULT NULL,
  `date` date DEFAULT NULL,
  `lot` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  KEY `person_id` (`person_id`),
  KEY `vaccine_name` (`vaccine_name`),
  CONSTRAINT `vaccination_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`) ON DELETE CASCADE,
  CONSTRAINT `vaccination_ibfk_2` FOREIGN KEY (`vaccine_name`) REFERENCES `vaccine` (`vaccine_name`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaccination`
--

/*!40000 ALTER TABLE `vaccination` DISABLE KEYS */;
INSERT INTO `vaccination` VALUES (1,'Pfizer',2,'2020-10-14','z0001','St. Mary\'s Hospital','Montreal','Quebec','Canada'),(2,'Pfizer',2,'2020-08-12','z0002','Jewish General Hospital','Montreal','Quebec','Canada'),(3,'AstraZeneca',2,'2020-06-09','z0003','Centre hospitalier de l\'Université de Montréal','lasalle','Quebec','Canada'),(4,'Pfizer',2,'2020-08-11','z0004','Hopital Notre-Dame','Brossard','Quebec','Canada'),(5,'Moderna',2,'2020-10-13','z0005','Douglas Mental Health University Institute','Laval','Quebec','Canada'),(6,'Pfizer',2,'2020-07-07','z0006','montreal general hospital','lasalle','Quebec','Canada'),(7,'Moderna',2,'2020-07-06','z0007','St. Mary\'s Hospital','Montreal','Quebec','Canada'),(8,'Moderna',2,'2020-09-30','z0008','Jewish General Hospital','Laval','Quebec','Canada'),(10,'Moderna',2,'2020-06-30','z0010','Lakeshore General Hospital','Montreal','Quebec','Canada'),(11,'Covax',2,'2021-07-07','cn0001','Fuzhou Hospital','Fuzhou','Fujian','China'),(12,'Pfizer',2,'2021-08-18','z0011','Douglas Mental Health University Institute','Montreal','Quebec','Canada'),(13,'Covax',2,'2021-08-10','z0012','Nanjing Hospital','Nanjing','Jiangsu','China'),(14,'Pfizer',2,'2021-06-08','cn-0002','Nanjing Hospital','Nanjing','Jiangsu','China');
/*!40000 ALTER TABLE `vaccination` ENABLE KEYS */;

--
-- Table structure for table `vaccine`
--

DROP TABLE IF EXISTS `vaccine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vaccine` (
  `vaccine_name` varchar(255) NOT NULL COMMENT 'primary key',
  `status` enum('suspend','safe') NOT NULL,
  `dose` mediumint DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  PRIMARY KEY (`vaccine_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaccine`
--

/*!40000 ALTER TABLE `vaccine` DISABLE KEYS */;
INSERT INTO `vaccine` VALUES ('AstraZeneca','suspend',NULL,NULL),('CanaVax','safe',1,'2021-10-08'),('Covax','safe',2,'2020-10-15'),('Gam-COVID-Vac','suspend',2,'2020-09-25'),('Johnson&Johnson','suspend',1,'2021-01-20'),('Moderna','safe',2,'2020-11-10'),('NovaVac','suspend',2,'2020-10-15'),('Pfizer','safe',2,'2020-10-23'),('QCVax','suspend',1,'2021-09-09'),('SputnikV','suspend',2,'2020-10-14');
/*!40000 ALTER TABLE `vaccine` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-06  0:25:37
