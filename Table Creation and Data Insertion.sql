# ************************************************************
# Sequel Pro SQL dump
# Version 4499
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 173.194.81.32 (MySQL 5.6.26)
# Database: StaffData
# Generation Time: 2016-01-12 22:32:44 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table staff
# ------------------------------------------------------------

DROP TABLE IF EXISTS `staff`;

CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(200) NOT NULL,
  `forename` varchar(200) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `phoneNumber` varchar(15) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;

INSERT INTO `staff` (`id`, `surname`, `forename`, `location`, `phoneNumber`, `email`)
VALUES
	(30,’Smith’,’Oliver','London','21345678','olly@mmu.ac.uk'),
	(31,’Collins’,’Michael','Liverpool','0123456789','michael.stdfv@stu.ac.uk'),
	(32,'Palmer’,’Leonardo’,’Manchester','12345676543','mewregbf@wertegf.co.uk'),
	(43,'Smith','Steven','Miami','555123425332342','steven.smith@gmail.com'),
	(44,'Swarth','Jack','LA','43546758664','jack@gmail.com'),
	(55,'House','Tree','Forest','447654321123','tree_house@green.com'),
	(62,'Piccoli','Michael','Manchester’,’65276846787’,’michael.google@gmail.com’),
	(64,’Terrior’,’William','Wales’,’72346189274’,’will.terr@yahoo.com');

/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
