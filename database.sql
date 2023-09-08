/*
SQLyog Professional v12.4.3 (64 bit)
MySQL - 10.4.28-MariaDB : Database - apotekv2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`apotekv2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

/*Table structure for table `basket` */

DROP TABLE IF EXISTS `basket`;

CREATE TABLE `basket` (
  `id_basket` int(11) NOT NULL AUTO_INCREMENT,
  `id_item` int(11) DEFAULT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_basket`),
  KEY `ibdfk_1` (`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `basket` */

/*Table structure for table `category` */

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `category` */

insert  into `category`(`id_category`,`name`,`cdt`,`udt`) values 
(1,'Obat Bebas Terbatas','2023-02-02 14:45:45',NULL),
(2,'Obat Bebas','2023-02-02 14:45:45',NULL),
(3,'Obat Keras','2023-02-02 14:45:45',NULL),
(4,'Obat Narkotika','2023-02-02 14:45:45',NULL),
(5,'Obat Psikotropika','2023-02-02 14:45:45',NULL),
(6,'Obat Herbal','2023-02-02 14:45:45',NULL),
(7,'Lainnya','2023-02-02 14:45:45',NULL),
(8,'Jamu','2023-02-06 20:45:18',NULL),
(9,'Minuman','2023-05-13 10:27:28',NULL),
(10,'Makanan','2023-05-13 10:27:28',NULL),
(11,'Obat Kantuk','2023-05-13 10:27:28',NULL),
(12,'Bahan Kimia','2023-05-13 10:27:28',NULL);

/*Table structure for table `customer` */

DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) DEFAULT NULL,
  `gender` enum('l','p') DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `customer` */

insert  into `customer`(`id_customer`,`name`,`gender`,`phone`,`address`,`cdt`,`udt`) values 
(1,'Adan Nandiswara','l','089334718291','Berbah, Sleman','2023-02-02 14:43:31',NULL),
(3,'Nur Lailatus','p','089567877577','Tegal','2023-03-25 16:48:58',NULL),
(5,'Yusuf','l','08971826382','Bantul, Yogyakarta','2023-05-13 10:26:48',NULL);

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(30) DEFAULT NULL,
  `item_type` int(11) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `id_location` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `id_category` int(11) DEFAULT NULL,
  `purchase_price` int(11) DEFAULT NULL,
  `selling_price` int(11) DEFAULT NULL,
  `min_stok` int(11) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `id_satuan` (`id_satuan`),
  KEY `id_location` (`id_location`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `item` */

insert  into `item`(`id_item`,`item_code`,`item_type`,`barcode`,`name`,`id_location`,`id_satuan`,`id_category`,`purchase_price`,`selling_price`,`min_stok`,`cdt`,`udt`) values 
(12,'OB-SUXR5',1,'81643','Amoxcilin',1,8,2,5000,6000,5,'2023-03-19 11:02:32',NULL),
(13,'OB-H0TMV',1,'71439','Albendazol Tablet 125 Mg',1,8,2,9900,11900,10,'2023-03-19 14:36:58',NULL),
(14,'OB-311G6',1,'35354','Ambroxol Table 30 Mg',1,8,6,16750,18500,10,'2023-03-19 14:38:28',NULL),
(15,'AK-BYCUC',2,'89109','Hansaplast',2,10,7,3400,5300,5,'2023-03-19 14:41:54',NULL),
(16,'AK-CJC3S',2,'23446','Perban Medis',2,3,7,14350,16700,15,'2023-03-19 14:42:38',NULL),
(17,'OB-FNGST',1,'13232','Diazepam Kapsul 125 Mg',5,13,3,2350,4500,4,'2023-03-19 14:44:14',NULL),
(18,'OB-U4ME3',1,'80651','Eritromisin Kapsul 50 Mg',5,13,1,27800,31450,15,'2023-03-19 14:45:33',NULL),
(19,'OB-9DDBF',1,'68293','Klorokuin tablet 120 Mg',5,8,5,14445,16500,5,'2023-03-19 14:48:22',NULL),
(30,'OB-O45QX',1,'71827','Panadol Extra',2,6,1,7000,10000,5,'2023-04-15 13:09:51',NULL),
(31,'OB-M0YAM',1,'44476','Diapet',5,10,2,7500,10000,10,'2023-05-01 11:46:03',NULL),
(32,'AK-T96CL',1,'67585','Bodrexin',5,10,2,250,500,10,'2023-05-08 10:26:23',NULL),
(36,'OB-FNAP1',1,'23892','Efedin tablet 25 mg (HCl)',3,8,2,17000,20000,250,'2023-08-03 23:40:26',NULL),
(37,'OB-JWZV2',1,'71723','Hidroklortiazida tablet 25 mg',5,8,2,48000,50000,1000,'2023-08-03 23:41:50',NULL),
(38,'OB-N3GGL',1,'79558','Hyosine N butilbromide tablet 10 mg',6,8,3,231000,250000,100,'2023-08-03 23:45:14',NULL),
(39,'AK-ATXE2',2,'23073','Alat suntik sekali pakai 1 ml (stera)',7,11,7,71500,75000,10,'2023-08-03 23:47:07',NULL),
(40,'AK-8M9EJ',2,'40696','Kasa hidrofil',7,11,7,8250,10000,10,'2023-08-03 23:48:53',NULL),
(41,'AK-VMLTB',2,'18574','Infusion set dewasa',7,6,7,3500,5000,10,'2023-08-03 23:49:57',NULL),
(42,'OB-JMPS2',1,'83312','Etil klorida spray',8,1,3,132000,140000,10,'2023-08-03 23:51:40',NULL),
(43,'OB-45DHG',1,'46260','Enervon-C',5,8,2,1300,2000,20,'2023-08-03 23:53:22',NULL),
(44,'OB-VXA1X',1,'41205','Betadin 60 mL',1,1,2,30100,33000,20,'2023-08-03 23:55:06',NULL),
(45,'OB-UAM6Z',1,'72322','Betadin 15 mL',1,1,2,9800,11000,20,'2023-08-03 23:55:58','2023-08-06 09:47:27'),
(46,'OB-9ZFB9',1,'32274','Cefixim',2,8,2,3300,4500,50,'2023-08-03 23:56:56',NULL),
(47,'OB-GBH2F',1,'55347','Dulcolac',1,8,2,1500,2500,50,'2023-08-03 23:57:51',NULL),
(48,'OB-1EG49',1,'61481','Minyak kayu putih spray ',3,1,6,18100,20000,20,'2023-08-03 23:59:19',NULL),
(49,'OB-B410O',1,'94532','Minyak telon spray ',3,1,6,18100,20000,20,'2023-08-04 00:00:15',NULL),
(50,'UM-OANQW',3,'92617','Permen Lollipop',5,10,7,500,1000,10,'2023-08-08 08:35:38',NULL),
(51,'OB-O8CAM',1,'434322','Bodrex',5,1,5,1500,2000,30,'2023-08-08 14:16:22',NULL);

/*Table structure for table `location` */

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
  `id_location` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_location`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `location` */

insert  into `location`(`id_location`,`name`,`cdt`,`udt`) values 
(1,'Etalase Selatan','2023-04-15 12:57:19',NULL),
(2,'Etalase Barat','2023-04-15 12:57:19',NULL),
(3,'Etalase Timur','2023-04-15 12:57:19',NULL),
(5,'Etalase Utara','2023-04-15 12:57:37',NULL),
(6,'Rak I','2023-05-13 10:28:48',NULL),
(7,'Rak II','2023-05-13 10:28:48',NULL),
(8,'Rak III','2023-05-13 10:28:48',NULL),
(9,'Rak IV','2023-05-13 10:28:48',NULL);

/*Table structure for table `satuan` */

DROP TABLE IF EXISTS `satuan`;

CREATE TABLE `satuan` (
  `id_satuan` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_satuan`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `satuan` */

insert  into `satuan`(`id_satuan`,`name`,`cdt`,`udt`) values 
(1,'Buah','2023-02-02 14:47:43',NULL),
(2,'Unit','2023-02-02 14:47:43',NULL),
(3,'Lembar','2023-02-02 14:47:43',NULL),
(4,'Pasang','2023-02-02 14:47:43',NULL),
(5,'Keping','2023-02-02 14:47:43',NULL),
(6,'Bungkus','2023-02-02 14:47:43',NULL),
(7,'Potong','2023-02-02 14:47:43',NULL),
(8,'Tablet','2023-02-02 14:47:43',NULL),
(9,'Lusin','2023-02-02 14:47:43',NULL),
(10,'Pcs','2023-02-02 14:47:43',NULL),
(11,'Box','2023-02-02 14:47:43',NULL),
(12,'Dus','2023-02-06 20:45:32',NULL),
(13,'Kapsul','2023-03-19 14:44:22',NULL),
(14,'Butir','2023-05-13 10:28:05',NULL),
(15,'Selop','2023-05-13 10:28:05',NULL),
(16,'Karung','2023-05-13 10:28:05',NULL),
(17,'Galon ','2023-05-13 10:28:05',NULL);

/*Table structure for table `supplier` */

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `supplier` */

insert  into `supplier`(`id_supplier`,`name`,`phone`,`address`,`description`,`cdt`,`udt`) values 
(1,'CV. Asia Nanggroe Abadi','0213298292','SMART MARKET DAAN MOGOT BLOK B NO 8 Jl. Daan Mogot KM 19 Kebun Besar Batu Ceper Tangerang 15122, Kot','loremipsum','2023-02-02 14:39:42',NULL),
(2,'PT. Inovasi Sukses Sentosa','021398223729','Rukan New Castle Blok B-56 Jln. Greenlake City, Petir, Cipondoh Tangerang Kota, 15147, Kota Tangeran','loremipsum','2023-02-02 14:40:15',NULL),
(3,'PT Jaya Utama Santikah','021339238829','Jl. Hayam Wuruk No.127 Lantai GF2 Blok A26 No.6-7, Jakarta Barat, DKI Jakarta, Indonesia','','2023-02-02 14:41:23',NULL),
(4,'PT Multiverse Anugerah Chemindo','043939217','Jl. Hasyim Ashari No.118,Golden City Business Park Blok A9 Cipondoh-Tangerang, Kota Tangerang, Bante','Obat Obatan Bahan Kimia Kosmetik, Bahan Kimia Makanan, Kimia Industri','2023-02-02 14:43:05',NULL);

/*Table structure for table `transaksi` */

DROP TABLE IF EXISTS `transaksi`;

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('in','out') DEFAULT NULL,
  `no_faktur` varchar(30) DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `cash` int(11) DEFAULT NULL,
  `change` int(11) DEFAULT NULL,
  `is_draft` enum('1','0') DEFAULT '0',
  `type` enum('transaksi','opname','retur') DEFAULT 'transaksi',
  `cid` int(11) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `uid` int(11) DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `user_id` (`user_id`),
  KEY `id_supplier` (`id_supplier`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `transaksi` */

insert  into `transaksi`(`id_transaksi`,`status`,`no_faktur`,`tgl`,`user_id`,`id_supplier`,`id_customer`,`description`,`cash`,`change`,`is_draft`,`type`,`cid`,`cdt`,`uid`,`udt`) values 
(121,'in','APT-WNKRM/3276/MH172','2023-08-01',NULL,1,NULL,'-',NULL,NULL,'0','transaksi',NULL,'2023-06-22 10:42:03',NULL,NULL),
(122,'out','APT2306220001','2023-08-02',2,NULL,0,'',20000,8000,'0','transaksi',NULL,'2023-06-22 10:48:32',NULL,NULL),
(128,'in','APT-WNKRM/3276/MH173','2023-08-08',NULL,2,NULL,'',NULL,NULL,'0','transaksi',NULL,'2023-08-08 08:55:36',NULL,NULL),
(129,'in','APT-WNKRM/3296/MH132','2023-08-08',NULL,4,NULL,'',NULL,NULL,'0','transaksi',NULL,'2023-08-08 08:57:49',NULL,NULL),
(130,'out','APT2308080001','2023-08-08',2,NULL,1,'',100000,15500,'0','transaksi',NULL,'2023-08-08 09:00:20',NULL,NULL),
(131,'out','APT2308080002','2023-08-08',2,NULL,0,'',20000,500,'0','transaksi',NULL,'2023-08-08 09:01:59',NULL,NULL),
(132,'out','APT2308080003','2023-08-08',2,NULL,5,'',60000,500,'0','transaksi',NULL,'2023-08-08 09:19:30',NULL,NULL),
(133,'out','APT2308080004','2023-08-08',2,NULL,0,'',15000,3100,'0','transaksi',NULL,'2023-08-08 09:25:31',NULL,NULL),
(135,'in',NULL,'2023-08-08',2,NULL,NULL,NULL,NULL,NULL,'0','opname',NULL,'2023-08-08 14:20:38',NULL,NULL),
(136,'in','APT-WNKRM/3246/MH171','2023-08-08',NULL,3,NULL,'',NULL,NULL,'0','transaksi',NULL,'2023-08-08 14:23:12',NULL,NULL),
(137,'out','APT2308080005','2023-08-08',2,NULL,0,'',20000,8900,'0','transaksi',NULL,'2023-08-08 14:25:53',NULL,NULL),
(138,'out','APT2308090001','2023-08-09',8,NULL,0,'',200000,200,'0','transaksi',NULL,'2023-08-09 07:56:11',NULL,NULL),
(139,'in','APT-WNKRM/4376/MH132','2023-08-09',NULL,1,NULL,'',NULL,NULL,'0','transaksi',NULL,'2023-08-09 07:57:36',NULL,NULL),
(140,'out','APT2308090002','2023-08-09',8,NULL,0,'',200000,27000,'0','transaksi',NULL,'2023-08-09 08:00:04',NULL,NULL),
(141,'out','APT2308090003','2023-08-09',2,NULL,0,'',10000,4000,'0','transaksi',NULL,'2023-08-09 10:27:41',NULL,NULL),
(142,'in','APT-WNKRM/3276/MH170','2023-08-09',NULL,1,NULL,'',NULL,NULL,'0','transaksi',NULL,'2023-08-09 11:16:51',NULL,NULL),
(143,'in','APT-WNKRM/3276/MH179','2023-08-09',NULL,1,NULL,'',NULL,NULL,'0','transaksi',NULL,'2023-08-09 11:18:31',NULL,NULL),
(144,'out','APT2308100001','2023-08-10',2,NULL,0,'',250000,20000,'0','transaksi',NULL,'2023-08-10 10:30:06',NULL,NULL),
(145,'in','APT-WNKRM/1927/MH278','2023-08-10',NULL,1,NULL,'',NULL,NULL,'0','transaksi',NULL,'2023-08-10 10:32:06',NULL,NULL),
(146,'out','APT2308120001','2023-08-12',2,NULL,0,'',20000,1500,'0','transaksi',NULL,'2023-08-12 15:12:35',NULL,NULL);

/*Table structure for table `transaksi_d` */

DROP TABLE IF EXISTS `transaksi_d`;

CREATE TABLE `transaksi_d` (
  `id_transaksi_d` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaksi` int(11) DEFAULT NULL,
  `status` enum('in','out') DEFAULT NULL,
  `reff` int(11) DEFAULT NULL,
  `id_item` int(11) DEFAULT NULL,
  `expired` date DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `note` varchar(100) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` date DEFAULT NULL,
  PRIMARY KEY (`id_transaksi_d`),
  KEY `transaksi_d_ibfk_1` (`id_transaksi`),
  KEY `id_item` (`id_item`),
  CONSTRAINT `transaksi_d_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `transaksi_d` */

insert  into `transaksi_d`(`id_transaksi_d`,`id_transaksi`,`status`,`reff`,`id_item`,`expired`,`batch`,`qty`,`price`,`total_price`,`note`,`cdt`,`udt`) values 
(1,121,'in',1,32,'2029-02-10',NULL,15,250,3750,NULL,'2023-06-22 10:42:03',NULL),
(2,121,'in',2,19,'2026-11-25',NULL,35,14445,505575,NULL,'2023-06-22 10:42:20',NULL),
(3,121,'in',3,12,'2027-12-15',NULL,25,5000,125000,NULL,'2023-06-22 10:42:37',NULL),
(4,122,'out',3,12,'2027-12-15',NULL,2,6000,12000,NULL,'2023-06-22 10:48:32',NULL),
(17,128,'in',17,13,'2027-08-31','635ggx2',10,9900,99000,NULL,'2023-08-08 08:55:36',NULL),
(18,128,'in',18,50,'2026-09-26',NULL,55,500,27500,NULL,'2023-08-08 08:55:58',NULL),
(19,129,'in',19,14,'2026-12-31',NULL,35,16750,586250,NULL,'2023-08-08 08:57:49',NULL),
(20,129,'in',20,15,'2030-01-31',NULL,75,3400,255000,NULL,'2023-08-08 08:58:11',NULL),
(21,129,'in',21,16,'2030-08-01',NULL,100,14350,1435000,NULL,'2023-08-08 08:58:34',NULL),
(22,129,'in',22,17,'2027-09-25',NULL,55,2350,129250,NULL,'2023-08-08 08:58:56',NULL),
(23,130,'out',21,16,'2030-08-01',NULL,5,16700,83500,NULL,'2023-08-08 09:00:20',NULL),
(24,130,'out',18,50,'2026-09-26',NULL,1,1000,1000,NULL,'2023-08-08 09:00:20',NULL),
(25,131,'out',22,17,'2027-09-25',NULL,4,4500,18000,NULL,'2023-08-08 09:01:59',NULL),
(26,131,'out',1,32,'2029-02-10',NULL,3,500,1500,NULL,'2023-08-08 09:01:59',NULL),
(27,132,'out',17,13,'2027-08-31','635ggx2',5,11900,59500,NULL,'2023-08-08 09:19:30',NULL),
(28,133,'out',17,13,'2027-08-31','635ggx2',1,11900,11900,NULL,'2023-08-08 09:25:31',NULL),
(30,135,'in',3,12,'2027-12-15',NULL,2,5000,10000,'','2023-08-08 14:20:38',NULL),
(31,136,'in',31,17,'2027-09-01',NULL,10,2350,23500,NULL,'2023-08-08 14:23:12',NULL),
(32,136,'in',32,49,'2026-12-01',NULL,15,18100,271500,NULL,'2023-08-08 14:23:52',NULL),
(33,137,'out',20,15,'2030-01-31',NULL,2,5300,10600,NULL,'2023-08-08 14:25:53',NULL),
(34,137,'out',1,32,'2029-02-10',NULL,1,500,500,NULL,'2023-08-08 14:25:53',NULL),
(35,138,'out',17,13,'2027-08-31','635ggx2',2,11900,23800,NULL,'2023-08-09 07:56:11',NULL),
(36,138,'out',19,14,'2026-12-31',NULL,5,18500,92500,NULL,'2023-08-09 07:56:11',NULL),
(37,138,'out',21,16,'2030-08-01',NULL,5,16700,83500,NULL,'2023-08-09 07:56:11',NULL),
(38,139,'in',38,18,'2030-01-31','4b24b',50,27800,1390000,NULL,'2023-08-09 07:57:36',NULL),
(39,139,'in',39,30,'2026-12-01','324knk',75,7000,525000,NULL,'2023-08-09 07:58:04',NULL),
(40,139,'in',40,31,'2028-07-20',NULL,35,7000,245000,NULL,'2023-08-09 07:58:25',NULL),
(41,140,'out',3,12,'2027-12-15',NULL,20,6000,120000,NULL,'2023-08-09 08:00:04',NULL),
(42,140,'out',20,15,'2030-01-31',NULL,10,5300,53000,NULL,'2023-08-09 08:00:04',NULL),
(43,141,'out',3,12,'2027-12-15',NULL,1,6000,6000,NULL,'2023-08-09 10:27:41',NULL),
(44,142,'in',44,31,'0000-00-00',NULL,35,7500,262500,NULL,'2023-08-09 11:16:51',NULL),
(45,143,'in',45,31,'0000-00-00',NULL,35,8000,280000,NULL,'2023-08-09 11:18:31',NULL),
(46,144,'out',45,31,'0000-00-00',NULL,10,10000,100000,NULL,'2023-08-10 10:30:06',NULL),
(47,144,'out',32,49,'2026-12-01',NULL,5,20000,100000,NULL,'2023-08-10 10:30:06',NULL),
(48,144,'out',39,30,'2026-12-01','324knk',3,10000,30000,NULL,'2023-08-10 10:30:06',NULL),
(49,145,'in',49,36,'2028-06-30','3n28947',35,17000,595000,NULL,'2023-08-10 10:32:06',NULL),
(50,145,'in',50,37,'2027-04-30','3248nx9',65,48000,3120000,NULL,'2023-08-10 10:33:16',NULL),
(51,146,'out',19,14,'2026-12-31',NULL,1,18500,18500,NULL,'2023-08-12 15:12:35',NULL);

/*Table structure for table `transaksi_d_temp` */

DROP TABLE IF EXISTS `transaksi_d_temp`;

CREATE TABLE `transaksi_d_temp` (
  `id_transaksi_d` int(11) NOT NULL AUTO_INCREMENT,
  `id_basket` int(11) DEFAULT NULL,
  `reff` int(11) DEFAULT NULL,
  `id_item` int(11) DEFAULT NULL,
  `expired` date DEFAULT NULL,
  `batch` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_transaksi_d`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `transaksi_d_temp` */

/*Table structure for table `type` */

DROP TABLE IF EXISTS `type`;

CREATE TABLE `type` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `cdt` datetime DEFAULT current_timestamp(),
  `udt` datetime DEFAULT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `type` */

insert  into `type`(`id_type`,`name`,`cdt`,`udt`) values 
(1,'Obat','2023-02-02 14:45:45',NULL),
(2,'Alat Kesehatan','2023-02-02 14:45:45',NULL),
(3,'Umum ','2023-02-02 14:45:45',NULL);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(70) DEFAULT NULL,
  `password` varchar(70) DEFAULT NULL,
  `avatar` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `level` int(1) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `user` */

insert  into `user`(`user_id`,`username`,`password`,`avatar`,`name`,`address`,`level`) values 
(2,'admin','d033e22ae348aeb5660fc2140aec35850c4da997','560199.png','Admin Apotek','Yogyakarta',1),
(8,'karyawan','87c78b8da768468c4f3826791496385536c11dad',NULL,'Karyawan','Sleman, Yogyakarta',2);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
