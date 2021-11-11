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
INSERT INTO `age_group` VALUES (0,NULL,NULL,NULL),(1,'2020-12-22',80,NULL),(2,'2021-01-01',70,79),(3,'2021-02-01',60,69),(4,'2021-02-15',50,59),(5,'2021-03-01',40,49),(6,'2021-04-01',30,39),(7,'2021-05-01',18,29),(8,'2021-06-01',12,17),(9,'2021-01-01',5,11),(10,NULL,0,4);
/*!40000 ALTER TABLE `age_group` ENABLE KEYS */;

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking` (
  `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `person_id` int DEFAULT NULL,
  `facility_name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `status` enum('active','cancel','finish') DEFAULT 'active',
  PRIMARY KEY (`booking_id`),
  KEY `person_id` (`person_id`),
  KEY `facility_name` (`facility_name`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`facility_name`) REFERENCES `facility` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking`
--

/*!40000 ALTER TABLE `booking` DISABLE KEYS */;
INSERT INTO `booking` VALUES (1,1,'hospital_notre_dame','2021-11-09','11:00:00',NULL);
/*!40000 ALTER TABLE `booking` ENABLE KEYS */;

--
-- Table structure for table `covid`
--

DROP TABLE IF EXISTS `covid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `covid` (
  `covid_id` int NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `variant` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`covid_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `covid`
--

/*!40000 ALTER TABLE `covid` DISABLE KEYS */;
INSERT INTO `covid` VALUES (1,'Alpha'),(2,'Beta'),(3,'Gamma'),(4,'Delta'),(5,'MU');
/*!40000 ALTER TABLE `covid` ENABLE KEYS */;

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
  `category` enum('by_appointment','walk_in') NOT NULL,
  PRIMARY KEY (`name`),
  KEY `manager` (`manager`),
  CONSTRAINT `facility_ibfk_1` FOREIGN KEY (`manager`) REFERENCES `person` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table maintains information about Public Health facilities where the vaccination is performed';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facility`
--

/*!40000 ALTER TABLE `facility` DISABLE KEYS */;
INSERT INTO `facility` VALUES ('centre_hospitalier_de_lUniversite_de_montreal','1051 Rue Sanguinet, Montréal, QC H2X 3E4','5148908000','https://www.chumontreal.qc.ca/joindre-le-chum','hospital',1000,7,'Quebec','by_appointment'),('douglas_mental_health_university_institute',' 6875 Bd LaSalle, Verdun, QC H4H 1R3','5147616131','http://www.douglas.qc.ca/?locale=en','hospital',900,3,'Quebec','by_appointment'),('hospital_notre_dame','1560 Sherbrooke St E, Montreal, Quebec H2L 4M1','5144138777','https://ciusss-centresudmtl.gouv.qc.ca/etablissement/hopital-notre-dame','hospital',400,9,'Quebec','by_appointment'),('jewish_general_hospital',' 3755 Chem. de la Côte-Sainte-Catherine, Montréal, QC H3T 1E2','5143408222','https://www.jgh.ca','hospital',800,2,'Quebec','by_appointment'),('lakeshore_general_hospital','160 Av Stillview Suite 1297, Pointe-Claire, QC H9R 2Y2','5146302225','https://fondationlakeshore.ca','hospital',1000,1,'Quebec','by_appointment'),('montreal_childrens_hospital','1001 Decarie Blvd, Montreal, Quebec H4A 3J1','5144124400','https://www.thechildren.com','hospital',450,7,'Quebec','by_appointment'),('montreal_general_hospital','1650 Cedar Ave, Montreal','5149341934','https://muhc.ca/','hospital',1000,NULL,'quebec','by_appointment'),('montreal_neurological_hospital','3801 Rue University, Montréal, QC H3A 2B4','5143986644','https://www.mcgill.ca/neuro/fr','hospital',760,8,'Quebec','by_appointment'),('royal_victoria_hospital','1001 Decarie Blvd, Montreal, Quebec H4A 3J1','5149341934','https://muhc.ca/glen','hospital',650,6,'Quebec','by_appointment'),('stMarys_hospital','3830 Av. Lacombe, Montréal, QC H3T 1M5','5142453511','https://www.smhc.qc.ca/en/','hospital',500,4,'Quebec','by_appointment');
/*!40000 ALTER TABLE `facility` ENABLE KEYS */;

--
-- Table structure for table `facility_operating_hour`
--

DROP TABLE IF EXISTS `facility_operating_hour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facility_operating_hour` (
  `facility_name` varchar(255) NOT NULL,
  `day_of_week` int NOT NULL,
  `open` time NOT NULL,
  `close` time NOT NULL,
  PRIMARY KEY (`facility_name`,`day_of_week`),
  CONSTRAINT `facility_operating_hour_ibfk_1` FOREIGN KEY (`facility_name`) REFERENCES `facility` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facility_operating_hour`
--

/*!40000 ALTER TABLE `facility_operating_hour` DISABLE KEYS */;
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal',0,'08:00:00','20:00:00'),('centre_hospitalier_de_lUniversite_de_montreal',1,'08:00:00','20:00:00'),('centre_hospitalier_de_lUniversite_de_montreal',2,'08:00:08','20:00:00'),('centre_hospitalier_de_lUniversite_de_montreal',3,'08:00:00','20:00:00'),('centre_hospitalier_de_lUniversite_de_montreal',4,'08:00:00','20:00:00'),('centre_hospitalier_de_lUniversite_de_montreal',5,'08:00:00','20:00:00'),('centre_hospitalier_de_lUniversite_de_montreal',6,'08:00:00','17:00:00'),('douglas_mental_health_university_institute',0,'08:00:00','20:00:00'),('douglas_mental_health_university_institute',1,'08:00:00','20:00:00'),('douglas_mental_health_university_institute',2,'08:00:00','20:00:00'),('douglas_mental_health_university_institute',3,'08:00:00','20:00:00'),('douglas_mental_health_university_institute',4,'08:00:00','20:00:00'),('douglas_mental_health_university_institute',5,'08:00:00','17:00:00'),('douglas_mental_health_university_institute',6,'08:00:00','17:00:00'),('hospital_notre_dame',0,'08:00:00','20:00:00'),('hospital_notre_dame',1,'08:00:00','20:00:00'),('hospital_notre_dame',2,'08:00:00','20:00:00'),('hospital_notre_dame',3,'08:00:00','20:00:00'),('hospital_notre_dame',4,'08:00:00','20:00:00'),('hospital_notre_dame',5,'08:00:00','20:00:00'),('hospital_notre_dame',6,'08:00:00','20:00:00'),('jewish_general_hospital',0,'08:00:00','20:00:00'),('jewish_general_hospital',1,'08:00:00','20:00:00'),('jewish_general_hospital',2,'08:00:00','20:00:00'),('jewish_general_hospital',3,'08:00:00','20:00:00'),('jewish_general_hospital',4,'08:00:00','20:00:00'),('jewish_general_hospital',5,'08:00:00','20:00:00'),('jewish_general_hospital',6,'08:00:00','20:00:00'),('lakeshore_general_hospital',0,'08:00:00','17:00:00'),('lakeshore_general_hospital',1,'08:00:00','17:00:00'),('lakeshore_general_hospital',2,'08:00:00','17:00:00'),('lakeshore_general_hospital',3,'08:00:00','17:00:00'),('lakeshore_general_hospital',4,'08:00:00','17:00:00'),('montreal_childrens_hospital',0,'08:00:00','20:00:00'),('montreal_childrens_hospital',1,'08:00:00','20:00:00'),('montreal_childrens_hospital',2,'08:00:00','20:00:00'),('montreal_childrens_hospital',3,'08:00:00','20:00:00'),('montreal_childrens_hospital',4,'08:00:00','20:00:00'),('montreal_childrens_hospital',5,'08:00:00','20:00:00'),('montreal_childrens_hospital',6,'08:00:00','20:00:00'),('montreal_general_hospital',0,'08:00:00','20:00:00'),('montreal_general_hospital',1,'08:00:00','20:00:00'),('montreal_general_hospital',2,'08:00:00','20:00:00'),('montreal_general_hospital',3,'08:00:00','20:00:00'),('montreal_general_hospital',4,'08:00:00','20:00:00'),('montreal_general_hospital',5,'08:00:00','20:00:00'),('montreal_general_hospital',6,'08:00:00','20:00:00'),('montreal_neurological_hospital',0,'08:00:00','17:00:00'),('montreal_neurological_hospital',1,'08:00:00','17:00:00'),('montreal_neurological_hospital',2,'08:00:00','20:00:00'),('montreal_neurological_hospital',3,'08:00:00','20:00:00'),('montreal_neurological_hospital',4,'08:00:00','20:00:00'),('montreal_neurological_hospital',5,'08:00:00','20:00:00'),('montreal_neurological_hospital',6,'08:00:00','20:00:00'),('royal_victoria_hospital',0,'08:00:00','20:00:00'),('royal_victoria_hospital',1,'08:00:00','20:00:00'),('royal_victoria_hospital',2,'08:00:00','20:00:00'),('royal_victoria_hospital',3,'08:00:00','20:00:00'),('royal_victoria_hospital',4,'08:00:00','20:00:00'),('royal_victoria_hospital',5,'08:00:00','20:00:00'),('royal_victoria_hospital',6,'08:00:00','20:00:00'),('stMarys_hospital',0,'08:00:00','20:00:00'),('stMarys_hospital',1,'08:00:00','20:00:00'),('stMarys_hospital',2,'08:00:00','20:00:00'),('stMarys_hospital',3,'08:00:00','20:00:00'),('stMarys_hospital',4,'08:00:00','20:00:00'),('stMarys_hospital',5,'08:00:00','20:00:00'),('stMarys_hospital',6,'08:00:00','20:00:00');
/*!40000 ALTER TABLE `facility_operating_hour` ENABLE KEYS */;

--
-- Table structure for table `healthcare_worker`
--

DROP TABLE IF EXISTS `healthcare_worker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `healthcare_worker` (
  `person_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `hourly_rate` int NOT NULL,
  PRIMARY KEY (`person_id`,`employee_id`,`facility_name`),
  KEY `facility_name` (`facility_name`),
  CONSTRAINT `healthcare_worker_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),
  CONSTRAINT `healthcare_worker_ibfk_2` FOREIGN KEY (`facility_name`) REFERENCES `facility` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `healthcare_worker`
--

/*!40000 ALTER TABLE `healthcare_worker` DISABLE KEYS */;
/*!40000 ALTER TABLE `healthcare_worker` ENABLE KEYS */;

--
-- Table structure for table `healthcare_worker_assignment`
--

DROP TABLE IF EXISTS `healthcare_worker_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `healthcare_worker_assignment` (
  `person_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `role` enum('nurse','manager','security','secretary','regular') NOT NULL,
  `vaccine_name` varchar(255) DEFAULT NULL,
  `dose_given` mediumint DEFAULT NULL,
  `lot` varchar(255) DEFAULT NULL,
  KEY `person_id` (`person_id`,`employee_id`,`facility_name`),
  CONSTRAINT `healthcare_worker_assignment_ibfk_1` FOREIGN KEY (`person_id`, `employee_id`, `facility_name`) REFERENCES `healthcare_worker` (`person_id`, `employee_id`, `facility_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table maintains the registration of public health care workers with public health care system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `healthcare_worker_assignment`
--

/*!40000 ALTER TABLE `healthcare_worker_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `healthcare_worker_assignment` ENABLE KEYS */;

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
  `middle_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`person_id`),
  KEY `age_group_id` (`age_group_id`),
  CONSTRAINT `person_ibfk_1` FOREIGN KEY (`age_group_id`) REFERENCES `age_group` (`age_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,'james','doe','1991-11-11','1','2020-11-11','2023-11-11','5140000000','1120 Rue Concordia','Montreal','Quebec','H4H3E3','Canada','casdi@gmail.com','sdaf-213',2,1,NULL),(2,'jane','doe','1997-11-11','2','2020-11-11','2023-11-11','5140000001','1120 Rue Montreal','lasalle','Quebec','H4H3G3','Canada','cadddi@gmail.com','sdaf-211',3,1,NULL),(3,'john','doe','2001-11-11','3','2019-10-12','2023-10-12','5140000002','1120 Rue Guy','Montreal','Quebec','H4G3D3','Canada','cadsdi@gmail.com','sdaf-212',7,1,NULL),(4,'john','doe','1991-01-11','4','2021-10-12','2024-10-12','5140000003','1120 Rue Catherine','Laval','Quebec','H4D3D3','Canada','casdi@gmail.com','sdaf-214',6,1,NULL),(5,'sam','doe','1988-01-11','5','2021-01-13','2024-01-12','5140000004','1120 Rue MaxKay','Brossard','Quebec','H5D3D3','Canada','ggsdfas@gmail.com','sdaf-215',10,1,NULL),(6,'john','smith','1979-01-01','6','2021-10-12','2024-10-12','5140000005','1120 Rue Max','Montreal','Quebec','H4D3H3','Canada','sdfas@gmail.com','sdaf-216',5,1,NULL),(7,'sam','smith','1970-11-11','7','2021-10-12','2024-10-12','5140000006','1120 Rue Marie','Toronto','Ontario','H6D3A4','Canada','wedfa@gmail.com','sdaf-217',4,1,NULL),(8,'kim','smith','1990-09-11','8','2021-10-12','2024-10-12','5140000007','1120 Rue Ontario','Toronto','Ontario','H6D3B4','Canada','asddfa@gmail.com','sdaf-218',1,1,NULL),(9,'jim','carry','2000-12-01','9','2021-10-12','2024-10-12','5140000008','1120 Rue Quebec','Montreal','Quebec','H8D3A8','Canada','dfa@gmail.com','sdaf-219',8,1,NULL),(10,'ali','smith','1999-09-01','10','2021-10-12','2024-10-12','5140000009','1120 Rue Quebec','Montreal','Quebec','H8D3A8','Canada','dfa@gmail.com','sdaf-220',9,1,NULL),(11,'zhangbin ','cai','1993-07-09','','2020-09-08','2023-10-05','6476666666','1122 Ruy Guy','Royal-Mount','Quebec','H4G5T5','China','caizhan@concordia.ca','ch-00769',7,0,NULL),(12,'ryan','yan','1990-01-30','12','2019-10-10','2022-10-06','5140006666','2344 Rue Paquet','Quebec City','Quebec','H4D5N4','Canada','yan@concordia.ca','dca-6666',7,1,NULL),(13,'rongxi','meng','1989-06-06','13','2019-10-14','2023-10-18','5140004666','1235 Rue Maria','Montreal','Quebec','D4D5G5','China','xi@concordia.ca','ch-00699',6,1,NULL),(14,'jason','qian','1991-06-04','14','2021-09-27','2024-10-18','5146669466','1234 Rue Google','Montreal','Quebec','G4G5L6','China','qian@concordia.ca','ch-07666',7,1,NULL),(15,'walter','white','1970-01-27','','2017-10-03','2020-10-07','6479965444','1000 Rue Texa','Laval','Quebec','L4L6Y7','US','walter@gmail.com','usd-0094',4,0,NULL),(16,'marie','hunt','1999-02-02','','2018-10-16','2021-09-27','5147774444','1234 Rue Hunter','Montreal','Quebec','D2D4G4','US','marie@gmail.com','adfa-999',7,0,NULL),(17,'don','jump','1961-01-31','15','2020-10-07','2023-10-04','4150000000','2222 Rue Pres','Laval','Quebec','D4D5T5','Canada','ttDrum@gmail.com','dfa-334',3,1,NULL),(18,'james','bond','1999-09-09','66','2019-05-16','2022-10-22','7778889999','6666 Rue Bond','Toronto','Ontario','M6X5N1','Canada','jamsebond@outlook.com','cad-666',3,1,NULL),(19,'jason','borne','2000-09-10','77','2018-06-06','2023-10-22','1112223334','7777 Rue bond','Toronto','Ontario','M8X7N1','Canada','jasonborne@outlook.com','cad-777',4,2,NULL);
/*!40000 ALTER TABLE `person` ENABLE KEYS */;

--
-- Table structure for table `province`
--

DROP TABLE IF EXISTS `province`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `province` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `age_group` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`),
  KEY `age_group` (`age_group`),
  CONSTRAINT `province_ibfk_1` FOREIGN KEY (`age_group`) REFERENCES `age_group` (`age_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `province`
--

/*!40000 ALTER TABLE `province` DISABLE KEYS */;
INSERT INTO `province` VALUES ('PEI',5),('MB',6),('NL',6),('NS',6),('AB',7),('BC',7),('NB',7),('NVT',7),('NWT',7),('ON',7),('QC',7),('SK',7),('YT',7);
/*!40000 ALTER TABLE `province` ENABLE KEYS */;

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
  `approval` date DEFAULT NULL,
  `suspension` date DEFAULT NULL,
  PRIMARY KEY (`vaccine_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vaccine`
--

/*!40000 ALTER TABLE `vaccine` DISABLE KEYS */;
INSERT INTO `vaccine` VALUES ('AstraZeneca','suspend',NULL,NULL,'2020-09-25'),('CanaVax','safe',1,'2021-10-08',NULL),('Covax','safe',2,'2020-10-15',NULL),('Gam-COVID-Vac','suspend',2,NULL,'2020-09-25'),('Johnson&Johnson','suspend',1,NULL,'2021-01-20'),('Moderna','safe',2,'2020-11-10',NULL),('NovaVac','suspend',2,NULL,'2020-10-15'),('Pfizer','safe',2,'2020-10-23',NULL),('QCVax','suspend',1,NULL,'2021-09-09'),('SputnikV','suspend',2,NULL,'2020-10-14');
/*!40000 ALTER TABLE `vaccine` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-11 13:21:49
