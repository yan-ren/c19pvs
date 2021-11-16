/*
 Navicat Premium Data Transfer

 Source Server         : concordia
 Source Server Type    : MySQL
 Source Server Version : 80022
 Source Host           : knc353.encs.concordia.ca:3306
 Source Schema         : knc353_2

 Target Server Type    : MySQL
 Target Server Version : 80022
 File Encoding         : 65001

 Date: 15/11/2021 22:57:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for age_group
-- ----------------------------
DROP TABLE IF EXISTS `age_group`;
CREATE TABLE `age_group` (
  `age_group_id` int NOT NULL COMMENT 'primary key',
  `vaccination_date` date DEFAULT NULL COMMENT 'date available for vaccination',
  `min_age` mediumint DEFAULT NULL COMMENT 'minimum age in the group (inclusive)',
  `max_age` mediumint DEFAULT NULL COMMENT 'maximum age in the group (inclusive)',
  `status` enum('A','D') NOT NULL DEFAULT 'A' COMMENT 'A - Active, D - Deleted',
  PRIMARY KEY (`age_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of age_group
-- ----------------------------
BEGIN;
INSERT INTO `age_group` VALUES (0, NULL, NULL, NULL, 'A');
INSERT INTO `age_group` VALUES (1, '2020-12-22', 80, NULL, 'A');
INSERT INTO `age_group` VALUES (2, '2021-01-01', 70, 79, 'A');
INSERT INTO `age_group` VALUES (3, '2021-02-01', 60, 69, 'A');
INSERT INTO `age_group` VALUES (4, '2021-02-15', 50, 59, 'A');
INSERT INTO `age_group` VALUES (5, '2021-03-01', 40, 49, 'A');
INSERT INTO `age_group` VALUES (6, '2021-04-01', 30, 39, 'A');
INSERT INTO `age_group` VALUES (7, '2021-05-01', 18, 29, 'A');
INSERT INTO `age_group` VALUES (8, '2021-06-01', 12, 17, 'A');
INSERT INTO `age_group` VALUES (9, '2021-01-01', 5, 11, 'A');
INSERT INTO `age_group` VALUES (10, NULL, 0, 4, 'A');
COMMIT;

-- ----------------------------
-- Table structure for booking
-- ----------------------------
DROP TABLE IF EXISTS `booking`;
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

-- ----------------------------
-- Records of booking
-- ----------------------------
BEGIN;
INSERT INTO `booking` VALUES (1, 1, 'hospital_notre_dame', '2021-11-09', '11:00:00', NULL);
COMMIT;

-- ----------------------------
-- Table structure for covid
-- ----------------------------
DROP TABLE IF EXISTS `covid`;
CREATE TABLE `covid` (
  `covid_id` int NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `variant` varchar(255) DEFAULT NULL,
  `status` enum('A','D') NOT NULL DEFAULT 'A' COMMENT 'A - Active, D - Deleted',
  PRIMARY KEY (`covid_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of covid
-- ----------------------------
BEGIN;
INSERT INTO `covid` VALUES (1, 'unknown', 'D');
INSERT INTO `covid` VALUES (2, 'Beta', 'A');
INSERT INTO `covid` VALUES (3, 'Gamma', 'A');
INSERT INTO `covid` VALUES (4, 'Delta', 'A');
INSERT INTO `covid` VALUES (5, 'MU', 'A');
INSERT INTO `covid` VALUES (6, 'Alpha', 'A');
INSERT INTO `covid` VALUES (7, 'venom', 'A');
INSERT INTO `covid` VALUES (8, 'unknown2', 'D');
INSERT INTO `covid` VALUES (9, 'invulnerable', 'A');
COMMIT;

-- ----------------------------
-- Table structure for facility
-- ----------------------------
DROP TABLE IF EXISTS `facility`;
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

-- ----------------------------
-- Records of facility
-- ----------------------------
BEGIN;
INSERT INTO `facility` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', '1051 Rue Sanguinet, Montréal, QC H2X 3E4', '5148908000', 'https://www.chumontreal.qc.ca/joindre-le-chum', 'hospital', 1000, 7, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('douglas_mental_health_university_institute', ' 6875 Bd LaSalle, Verdun, QC H4H 1R3', '5147616131', 'http://www.douglas.qc.ca/?locale=en', 'hospital', 900, 3, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('halifax_general', '100 king st. Halifax, NS H3G5T5', '9023914388', 'https://www.halifaxhospital.ca', 'hospital', 600, 7, 'NS', 'walk_in');
INSERT INTO `facility` VALUES ('hospital_notre_dame', '1560 Sherbrooke St E, Montreal, Quebec H2L 4M1', '5144138777', 'https://ciusss-centresudmtl.gouv.qc.ca/etablissement/hopital-notre-dame', 'hospital', 400, 9, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('jewish_general_hospital', ' 3755 Chem. de la Côte-Sainte-Catherine, Montréal, QC H3T 1E2', '5143408222', 'https://www.jgh.ca', 'hospital', 800, 2, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('lakeshore_general_hospital', '160 Av Stillview Suite 1297, Pointe-Claire, QC H9R 2Y2', '5146302225', 'https://fondationlakeshore.ca', 'hospital', 1000, 1, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('montreal_childrens_hospital', '1001 Decarie Blvd, Montreal, Quebec H4A 3J1', '5144124400', 'https://www.thechildren.com', 'hospital', 450, 7, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('montreal_general_hospital', '1650 Cedar Ave, Montreal', '5149341934', 'https://muhc.ca/', 'hospital', 1000, NULL, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('montreal_neurological_hospital', '3801 Rue University, Montréal, QC H3A 2B4', '5143986644', 'https://www.mcgill.ca/neuro/fr', 'hospital', 760, 8, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('royal_victoria_hospital', '1001 Decarie Blvd, Montreal, Quebec H4A 3J1', '5149341934', 'https://muhc.ca/glen', 'hospital', 650, 6, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('st_marys_hospital', '3830 Av. Lacombe, Montréal, QC H3T 1M5', '5142453511', 'https://www.smhc.qc.ca/en/', 'hospital', 500, 4, 'QC', 'by_appointment');
INSERT INTO `facility` VALUES ('toronto_general_hospital', '200 Elizabeth St, Toronto, ON M5G 2C4', '4163403131', 'https//www.uhn.ca', 'hospital', 1000, 3, 'ON', 'walk_in');
INSERT INTO `facility` VALUES ('uhn', '201 Elizabeth St, Toronto, ON M5G 2C4', '6478009000', 'https//:www.uhn.ca', 'hospital', 1000, 2, 'ON', 'by_appointment');
COMMIT;

-- ----------------------------
-- Table structure for facility_operating_hour
-- ----------------------------
DROP TABLE IF EXISTS `facility_operating_hour`;
CREATE TABLE `facility_operating_hour` (
  `facility_name` varchar(255) NOT NULL,
  `day_of_week` int NOT NULL,
  `open` time NOT NULL,
  `close` time NOT NULL,
  PRIMARY KEY (`facility_name`,`day_of_week`),
  CONSTRAINT `facility_operating_hour_ibfk_1` FOREIGN KEY (`facility_name`) REFERENCES `facility` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of facility_operating_hour
-- ----------------------------
BEGIN;
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', 2, '08:00:08', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('centre_hospitalier_de_lUniversite_de_montreal', 6, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('douglas_mental_health_university_institute', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('douglas_mental_health_university_institute', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('douglas_mental_health_university_institute', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('douglas_mental_health_university_institute', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('douglas_mental_health_university_institute', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('douglas_mental_health_university_institute', 5, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('douglas_mental_health_university_institute', 6, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('hospital_notre_dame', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('hospital_notre_dame', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('hospital_notre_dame', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('hospital_notre_dame', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('hospital_notre_dame', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('hospital_notre_dame', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('hospital_notre_dame', 6, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('jewish_general_hospital', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('jewish_general_hospital', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('jewish_general_hospital', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('jewish_general_hospital', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('jewish_general_hospital', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('jewish_general_hospital', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('jewish_general_hospital', 6, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('lakeshore_general_hospital', 0, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('lakeshore_general_hospital', 1, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('lakeshore_general_hospital', 2, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('lakeshore_general_hospital', 3, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('lakeshore_general_hospital', 4, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_childrens_hospital', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_childrens_hospital', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_childrens_hospital', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_childrens_hospital', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_childrens_hospital', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_childrens_hospital', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_childrens_hospital', 6, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_general_hospital', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_general_hospital', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_general_hospital', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_general_hospital', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_general_hospital', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_general_hospital', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_general_hospital', 6, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_neurological_hospital', 0, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_neurological_hospital', 1, '08:00:00', '17:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_neurological_hospital', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_neurological_hospital', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_neurological_hospital', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_neurological_hospital', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('montreal_neurological_hospital', 6, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('royal_victoria_hospital', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('royal_victoria_hospital', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('royal_victoria_hospital', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('royal_victoria_hospital', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('royal_victoria_hospital', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('royal_victoria_hospital', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('royal_victoria_hospital', 6, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('st_marys_hospital', 0, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('st_marys_hospital', 1, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('st_marys_hospital', 2, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('st_marys_hospital', 3, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('st_marys_hospital', 4, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('st_marys_hospital', 5, '08:00:00', '20:00:00');
INSERT INTO `facility_operating_hour` VALUES ('st_marys_hospital', 6, '08:00:00', '20:00:00');
COMMIT;

-- ----------------------------
-- Table structure for healthcare_worker
-- ----------------------------
DROP TABLE IF EXISTS `healthcare_worker`;
CREATE TABLE `healthcare_worker` (
  `person_id` int NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `employee_id` int DEFAULT NULL,
  `hourly_rate` int NOT NULL,
  `status` enum('A','D') NOT NULL DEFAULT 'A' COMMENT 'A - Active, D - Deleted',
  PRIMARY KEY (`person_id`,`facility_name`),
  KEY `facility_name` (`facility_name`),
  CONSTRAINT `healthcare_worker_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`),
  CONSTRAINT `healthcare_worker_ibfk_2` FOREIGN KEY (`facility_name`) REFERENCES `facility` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of healthcare_worker
-- ----------------------------
BEGIN;
INSERT INTO `healthcare_worker` VALUES (2, 'toronto_general_hospital', 2, 23, 'A');
INSERT INTO `healthcare_worker` VALUES (3, 'lakeshore_general_hospital', 3, 32, 'A');
INSERT INTO `healthcare_worker` VALUES (18, 'hospital_notre_dame', 555, 25, 'A');
INSERT INTO `healthcare_worker` VALUES (18, 'jewish_general_hospital', 666, 25, 'A');
INSERT INTO `healthcare_worker` VALUES (19, 'hospital_notre_dame', 777, 20, 'A');
INSERT INTO `healthcare_worker` VALUES (66, 'jewish_general_hospital', 66, 29, 'D');
INSERT INTO `healthcare_worker` VALUES (77, 'montreal_childrens_hospital', 77, 30, 'D');
INSERT INTO `healthcare_worker` VALUES (88, 'st_marys_hospital', 88, 27, 'A');
INSERT INTO `healthcare_worker` VALUES (99, 'royal_victoria_hospital', 99, 25, 'A');
COMMIT;

-- ----------------------------
-- Table structure for healthcare_worker_assignment
-- ----------------------------
DROP TABLE IF EXISTS `healthcare_worker_assignment`;
CREATE TABLE `healthcare_worker_assignment` (
  `person_id` int NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `role` enum('nurse','manager','security','secretary','regular') NOT NULL,
  `vaccine_name` varchar(255) DEFAULT NULL,
  `dose_given` mediumint DEFAULT NULL,
  `lot` varchar(255) DEFAULT NULL,
  KEY `person_id` (`person_id`,`facility_name`),
  CONSTRAINT `healthcare_worker_assignment_ibfk_1` FOREIGN KEY (`person_id`, `facility_name`) REFERENCES `healthcare_worker` (`person_id`, `facility_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='This table maintains the registration of public health care workers with public health care system';

-- ----------------------------
-- Records of healthcare_worker_assignment
-- ----------------------------
BEGIN;
INSERT INTO `healthcare_worker_assignment` VALUES (18, 'hospital_notre_dame', '2021-11-01', '2022-01-01', 'nurse', NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for infection
-- ----------------------------
DROP TABLE IF EXISTS `infection`;
CREATE TABLE `infection` (
  `person_id` int NOT NULL,
  `date` date NOT NULL COMMENT 'date of infection',
  `covid_id` int NOT NULL DEFAULT '1',
  KEY `person_id` (`person_id`),
  KEY `covid_id` (`covid_id`),
  CONSTRAINT `infection_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`person_id`) ON DELETE CASCADE,
  CONSTRAINT `infection_ibfk_2` FOREIGN KEY (`covid_id`) REFERENCES `covid` (`covid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of infection
-- ----------------------------
BEGIN;
INSERT INTO `infection` VALUES (1, '2020-03-06', 1);
INSERT INTO `infection` VALUES (2, '2020-12-15', 1);
INSERT INTO `infection` VALUES (3, '2021-10-01', 1);
INSERT INTO `infection` VALUES (4, '2020-07-16', 1);
INSERT INTO `infection` VALUES (5, '2021-09-28', 1);
INSERT INTO `infection` VALUES (6, '2020-01-15', 1);
INSERT INTO `infection` VALUES (7, '2020-02-27', 1);
INSERT INTO `infection` VALUES (8, '2020-07-15', 1);
INSERT INTO `infection` VALUES (9, '2020-04-28', 1);
INSERT INTO `infection` VALUES (10, '2020-09-08', 1);
COMMIT;

-- ----------------------------
-- Table structure for person
-- ----------------------------
DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `person_id` int NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
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
  `status` enum('A','D') NOT NULL DEFAULT 'A' COMMENT 'A - Active, D - Deleted',
  PRIMARY KEY (`person_id`),
  KEY `age_group_id` (`age_group_id`),
  CONSTRAINT `person_ibfk_1` FOREIGN KEY (`age_group_id`) REFERENCES `age_group` (`age_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of person
-- ----------------------------
BEGIN;
INSERT INTO `person` VALUES (1, 'james', 'mike', 'doe', '1991-11-11', '1', '2020-11-11', '2023-11-11', '5140000000', '1120 Rue Concordia', 'Montreal', 'QC', 'H4H3E3', 'Canada', 'casdi@gmail.com', 'sdaf-213', 2, 1, 'A');
INSERT INTO `person` VALUES (2, 'jane', 'jenny', 'doe', '1997-11-11', '2', '2020-11-11', '2023-11-11', '5140000001', '1120 Rue Montreal', 'lasalle', 'QC', 'H4H3G3', 'Canada', 'cadddi@gmail.com', 'sdaf-211', 3, 1, 'A');
INSERT INTO `person` VALUES (3, 'john', 'rose', 'doe', '2001-11-11', '3', '2019-10-12', '2023-10-12', '5140000002', '1120 Rue Guy', 'Montreal', 'QC', 'H4G3D3', 'Canada', 'cadsdi@gmail.com', 'sdaf-212', 7, 1, 'A');
INSERT INTO `person` VALUES (4, 'john', 'cai', 'doe', '1991-01-11', '4', '2021-10-12', '2024-10-12', '5140000003', '1120 Rue Catherine', 'Laval', 'QC', 'H4D3D3', 'Canada', 'casdi@gmail.com', 'sdaf-214', 6, 1, 'A');
INSERT INTO `person` VALUES (5, 'sam', 'sam', 'doe', '1988-01-11', '5', '2021-01-13', '2024-01-12', '5140000004', '1120 Rue MaxKay', 'Brossard', 'QC', 'H5D3D3', 'Canada', 'ggsdfas@gmail.com', 'sdaf-215', 10, 1, 'A');
INSERT INTO `person` VALUES (6, 'john', 'jim', 'smith', '1979-01-01', '6', '2021-10-12', '2024-10-12', '5140000005', '1120 Rue Max', 'Montreal', 'QC', 'H4D3H3', 'Canada', 'sdfas@gmail.com', 'sdaf-216', 5, 1, 'A');
INSERT INTO `person` VALUES (7, 'sam', 'dick', 'smith', '1970-11-11', '7', '2021-10-12', '2024-10-12', '5140000006', '1120 Rue Marie', 'Toronto', 'ON', 'H6D3A4', 'Canada', 'wedfa@gmail.com', 'sdaf-217', 4, 1, 'A');
INSERT INTO `person` VALUES (8, 'kim', 'derek', 'smith', '1990-09-11', '8', '2021-10-12', '2024-10-12', '5140000007', '1120 Rue Ontario', 'Toronto', 'ON', 'H6D3B4', 'Canada', 'asddfa@gmail.com', 'sdaf-218', 1, 1, 'A');
INSERT INTO `person` VALUES (9, 'jim', 'duston', 'carry', '2000-12-01', '9', '2021-10-12', '2024-10-12', '5140000008', '1120 Rue Quebec', 'Montreal', 'QC', 'H8D3A8', 'Canada', 'dfa@gmail.com', 'sdaf-219', 8, 1, 'A');
INSERT INTO `person` VALUES (10, 'ali', 'sam', 'smith', '1999-09-01', '10', '2021-10-12', '2024-10-12', '5140000009', '1120 Rue Quebec', 'Montreal', 'QC', 'H8D3A8', 'Canada', 'dfa@gmail.com', 'sdaf-220', 9, 1, 'A');
INSERT INTO `person` VALUES (11, 'zhangbin ', '', 'cai', '1993-07-09', '', '2020-09-08', '2023-10-05', '6476666666', '1122 Ruy Guy', 'Royal-Mount', 'QC', 'H4G5T5', 'China', 'caizhan@concordia.ca', 'ch-00769', 7, 0, 'A');
INSERT INTO `person` VALUES (12, 'ryan', NULL, 'yan', '1990-01-30', '12', '2019-10-10', '2022-10-06', '5140006666', '2344 Rue Paquet', 'Quebec City', 'QC', 'H4D5N4', 'Canada', 'yan@concordia.ca', 'dca-6666', 7, 1, 'A');
INSERT INTO `person` VALUES (13, 'rongxi', NULL, 'meng', '1989-06-06', '13', '2019-10-14', '2023-10-18', '5140004666', '1235 Rue Maria', 'Montreal', 'QC', 'D4D5G5', 'China', 'xi@concordia.ca', 'ch-00699', 6, 1, 'A');
INSERT INTO `person` VALUES (14, 'jason', NULL, 'qian', '1991-06-04', '14', '2021-09-27', '2024-10-18', '5146669466', '1234 Rue Google', 'Montreal', 'QC', 'G4G5L6', 'China', 'qian@concordia.ca', 'ch-07666', 7, 1, 'A');
INSERT INTO `person` VALUES (15, 'walter', NULL, 'white', '1970-01-27', '', '2017-10-03', '2020-10-07', '6479965444', '1000 Rue Texa', 'Laval', 'QC', 'L4L6Y7', 'US', 'walter@gmail.com', 'usd-0094', 4, 0, 'A');
INSERT INTO `person` VALUES (16, 'marie', 'rose', 'hunt', '1999-02-02', '', '2018-10-16', '2021-09-27', '5147774444', '1234 Rue Hunter', 'Montreal', 'QC', 'D2D4G4', 'US', 'marie@gmail.com', 'adfa-999', 7, 0, 'A');
INSERT INTO `person` VALUES (17, 'don', 'jr', 'jump', '1961-01-31', '15', '2020-10-07', '2023-10-04', '4150000000', '2222 Rue Pres', 'Laval', 'QC', 'D4D5T5', 'Canada', 'ttDrum@gmail.com', 'dfa-334', 3, 1, 'A');
INSERT INTO `person` VALUES (18, 'james', NULL, 'bond', '1999-09-09', '66', '2019-05-16', '2022-10-22', '7778889999', '6666 Rue Bond', 'Toronto', 'ON', 'M6X5N1', 'Canada', 'jamsebond@outlook.com', 'cad-666', 3, 1, 'A');
INSERT INTO `person` VALUES (19, 'jason', NULL, 'borne', '2000-09-10', '77', '2018-06-06', '2023-10-22', '1112223334', '7777 Rue bond', 'Toronto', 'ON', 'M8X7N1', 'Canada', 'jasonborne@outlook.com', 'cad-777', 4, 2, 'A');
INSERT INTO `person` VALUES (20, 'apoline', 'jenny', 'kai', '1998-09-09', '16', '2018-09-09', '2023-10-22', '1112228844', '2343 King St', 'Halifax', 'NS', 'H3T5U8', 'Canada', 'apoline@gmail.com', 'cad-555', 7, 1, 'A');
INSERT INTO `person` VALUES (21, 'josee', NULL, 'cook', '1999-10-11', '17', '2019-08-19', '2024-11-22', '123422324', '333 Queen St', 'Calgary', 'AB', 'A2A7Y8', 'Canada', 'josee@hotmail.com', 'cad-999', 7, 1, 'A');
INSERT INTO `person` VALUES (66, 'uzi', NULL, 'jian', '1997-10-20', '66', '2020-08-23', '2022-08-22', '6479990987', '66 Ave God', 'Toronto', 'ON', 'M9X1N2', 'Canada', 'uzij@outllook.com', 'can-66', 2, 1, 'A');
INSERT INTO `person` VALUES (77, 'clearlove', NULL, 'seven', '1993-07-20', '77', '2020-08-20', '2022-08-22', '5146667777', '77 Ave God', 'Montreal', 'QC', 'H8N1A4', 'Canada', 'clearlove7@outlook.com', 'can-77', 3, 1, 'A');
INSERT INTO `person` VALUES (88, 'rookie', NULL, 'song', '1997-09-20', '88', '2020-09-20', '2024-08-20', '6470001111', '88 Ave God', 'Toronto', 'ON', 'M8X1A2', 'Canada', 'rookiesong@outlook.com', 'can-88', 4, 1, 'A');
INSERT INTO `person` VALUES (99, 'jackson', NULL, 'wang', '1993-10-20', '99', '2018-08-20', '2022-08-22', '4167778889', '99 Rue pigeon', 'Toronto', 'ON', 'M3X1N2', 'Canada', 'jacksonw@outlook.com', 'can-99', 3, 1, 'A');
COMMIT;

-- ----------------------------
-- Table structure for province
-- ----------------------------
DROP TABLE IF EXISTS `province`;
CREATE TABLE `province` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `age_group` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`),
  KEY `age_group` (`age_group`),
  CONSTRAINT `province_ibfk_1` FOREIGN KEY (`age_group`) REFERENCES `age_group` (`age_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of province
-- ----------------------------
BEGIN;
INSERT INTO `province` VALUES ('PEI', 5);
INSERT INTO `province` VALUES ('MB', 6);
INSERT INTO `province` VALUES ('NL', 6);
INSERT INTO `province` VALUES ('NS', 6);
INSERT INTO `province` VALUES ('AB', 7);
INSERT INTO `province` VALUES ('BC', 7);
INSERT INTO `province` VALUES ('NB', 7);
INSERT INTO `province` VALUES ('NVT', 7);
INSERT INTO `province` VALUES ('NWT', 7);
INSERT INTO `province` VALUES ('ON', 7);
INSERT INTO `province` VALUES ('QC', 7);
INSERT INTO `province` VALUES ('SK', 7);
INSERT INTO `province` VALUES ('YT', 7);
COMMIT;

-- ----------------------------
-- Table structure for vaccination
-- ----------------------------
DROP TABLE IF EXISTS `vaccination`;
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

-- ----------------------------
-- Records of vaccination
-- ----------------------------
BEGIN;
INSERT INTO `vaccination` VALUES (1, 'Pfizer', 2, '2020-10-14', 'z0001', 'St. Mary\'s Hospital', 'Montreal', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (2, 'Pfizer', 2, '2020-08-12', 'z0002', 'Jewish General Hospital', 'Montreal', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (3, 'AstraZeneca', 2, '2020-06-09', 'z0003', 'Centre hospitalier de l\'Université de Montréal', 'lasalle', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (4, 'Pfizer', 2, '2020-08-11', 'z0004', 'Hopital Notre-Dame', 'Brossard', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (5, 'Moderna', 2, '2020-10-13', 'z0005', 'Douglas Mental Health University Institute', 'Laval', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (6, 'Pfizer', 2, '2020-07-07', 'z0006', 'montreal general hospital', 'lasalle', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (7, 'Moderna', 2, '2020-07-06', 'z0007', 'St. Mary\'s Hospital', 'Montreal', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (8, 'Moderna', 2, '2020-09-30', 'z0008', 'Jewish General Hospital', 'Laval', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (10, 'Moderna', 2, '2020-06-30', 'z0010', 'Lakeshore General Hospital', 'Montreal', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (11, 'Covax', 2, '2021-07-07', 'cn0001', 'Fuzhou Hospital', 'Fuzhou', 'Fujian', 'China');
INSERT INTO `vaccination` VALUES (12, 'Pfizer', 2, '2021-08-18', 'z0011', 'Douglas Mental Health University Institute', 'Montreal', 'Quebec', 'Canada');
INSERT INTO `vaccination` VALUES (13, 'Covax', 2, '2021-08-10', 'z0012', 'Nanjing Hospital', 'Nanjing', 'Jiangsu', 'China');
INSERT INTO `vaccination` VALUES (14, 'Pfizer', 2, '2021-06-08', 'cn-0002', 'Nanjing Hospital', 'Nanjing', 'Jiangsu', 'China');
COMMIT;

-- ----------------------------
-- Table structure for vaccine
-- ----------------------------
DROP TABLE IF EXISTS `vaccine`;
CREATE TABLE `vaccine` (
  `vaccine_name` varchar(255) NOT NULL COMMENT 'primary key',
  `status` enum('suspend','safe') NOT NULL,
  `dose` mediumint DEFAULT NULL,
  `approval` date DEFAULT NULL,
  `suspension` date DEFAULT NULL,
  PRIMARY KEY (`vaccine_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vaccine
-- ----------------------------
BEGIN;
INSERT INTO `vaccine` VALUES ('AstraZeneca', 'suspend', NULL, NULL, '2020-09-25');
INSERT INTO `vaccine` VALUES ('CanaVax', 'safe', 1, '2021-10-08', NULL);
INSERT INTO `vaccine` VALUES ('Covax', 'safe', 2, '2020-10-15', NULL);
INSERT INTO `vaccine` VALUES ('Diaozha', 'safe', 2, '2020-11-12', NULL);
INSERT INTO `vaccine` VALUES ('Gam-COVID-Vac', 'suspend', 2, NULL, '2020-09-25');
INSERT INTO `vaccine` VALUES ('Johnson&Johnson', 'suspend', 1, NULL, '2021-01-20');
INSERT INTO `vaccine` VALUES ('Moderna', 'safe', 2, '2020-11-10', NULL);
INSERT INTO `vaccine` VALUES ('Niubi', 'safe', 1, '2021-11-10', NULL);
INSERT INTO `vaccine` VALUES ('NovaVac', 'suspend', 2, NULL, '2020-10-15');
INSERT INTO `vaccine` VALUES ('Pfizer', 'safe', 2, '2020-10-23', NULL);
INSERT INTO `vaccine` VALUES ('QCVax', 'suspend', 1, NULL, '2021-09-09');
INSERT INTO `vaccine` VALUES ('SputnikV', 'suspend', 2, NULL, '2020-10-14');
INSERT INTO `vaccine` VALUES ('SuperVenom', 'safe', 2, NULL, NULL);
INSERT INTO `vaccine` VALUES ('Venom', 'suspend', 2, '2019-10-22', '2020-10-20');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
