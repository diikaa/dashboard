-- MySQL dump 10.13  Distrib 5.5.49, for FreeBSD10.3 (i386)
--
-- Host: localhost    Database: dashboard
-- ------------------------------------------------------
-- Server version	5.5.49

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
-- Table structure for table `laporan`
--

DROP TABLE IF EXISTS `laporan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `laporan` (
  `id_laporan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_laporan` varchar(100) NOT NULL,
  `modif` date NOT NULL,
  PRIMARY KEY (`id_laporan`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laporan`
--

LOCK TABLES `laporan` WRITE;
/*!40000 ALTER TABLE `laporan` DISABLE KEYS */;
INSERT INTO `laporan` VALUES (1,'student distribution','2016-07-28'),(2,'student sex','2016-07-30'),(3,'student origin','2016-07-30');
/*!40000 ALTER TABLE `laporan` ENABLE KEYS */;

CREATE TRIGGER `trig_laporan` AFTER INSERT ON `laporan`
FOR EACH ROW
BEGIN
  -- Tulis pesan notifikasi ke tabel notifikasi
  INSERT INTO `notifikasi` (`pesan`) VALUES ('Laporan baru ditambahkan: ' + NEW.`nama_laporan`);
END;

UNLOCK TABLES;

--
-- Table structure for table `otoritas`
--

DROP TABLE IF EXISTS `otoritas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otoritas` (
  `id_otoritas` int(11) NOT NULL AUTO_INCREMENT,
  `id_previlage` int(11) NOT NULL,
  `id_laporan` int(11) NOT NULL,
  PRIMARY KEY (`id_otoritas`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otoritas`
--

LOCK TABLES `otoritas` WRITE;
/*!40000 ALTER TABLE `otoritas` DISABLE KEYS */;
INSERT INTO `otoritas` VALUES (5,1,3),(4,1,1),(6,1,2);
/*!40000 ALTER TABLE `otoritas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `previlage`
--

DROP TABLE IF EXISTS `previlage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `previlage` (
  `id_previlage` int(11) NOT NULL AUTO_INCREMENT,
  `nama_previlage` varchar(50) NOT NULL,
  PRIMARY KEY (`id_previlage`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `previlage`
--

LOCK TABLES `previlage` WRITE;
/*!40000 ALTER TABLE `previlage` DISABLE KEYS */;
INSERT INTO `previlage` VALUES (1,'administrator'),(2,'default'),(14,'manajer');
/*!40000 ALTER TABLE `previlage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tab_data`
--

DROP TABLE IF EXISTS `tab_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tab_data` (
  `id_data` int(5) NOT NULL AUTO_INCREMENT,
  `nama_data` varchar(40) NOT NULL,
  `server` varchar(100) NOT NULL,
  `user` varchar(30) NOT NULL,
  `pass` varchar(30) NOT NULL,
  `data_base` varchar(30) NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `query` text NOT NULL,
  PRIMARY KEY (`id_data`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tab_data`
--

LOCK TABLES `tab_data` WRITE;
/*!40000 ALTER TABLE `tab_data` DISABLE KEYS */;
INSERT INTO `tab_data` VALUES (1,'ds_student_distribution','localhost','report','report','masterdata','MySQL','select c.nama_fakultas_english as FACULTY,b.nama_prodi_english as PROGRAM,count(*) as TOTAL from t_mst_student as a, t_mst_program as b, t_mst_fakultas as c where a.c_program=b.c_kode_prodi and b.c_kode_fakultas=c.c_kode_fakultas and C_KODE_STATUS_AKTIF_MHS in (\'A\',\'C\') group by a.c_program order by FACULTY,PROGRAM'),(2,'ds_sex_composition','localhost','report','report','masterdata','MySQL','select c.nama_fakultas_english as FACULTY,a.jenis_kelamin as SEX,count(*) as TOTAL from t_mst_student as a, t_mst_fakultas as c where a.c_faculty=c.c_kode_fakultas and C_KODE_STATUS_AKTIF_MHS in (\'A\',\'C\') group by c.nama_fakultas_english,a.jenis_kelamin order by FACULTY,SEX'),(3,'ds_student_origin','localhost','report','report','masterdata','MySQL','select nama_propinsi as PROVINCE, count(*) as TOTAL from t_mst_student as a, t_par_propinsi as b where a.c_kode_propinsi=b.c_kode_propinsi group by a.c_kode_propinsi'),(4,'ds_komposisi_dosen','127.0.0.1','report','report','masterdata','MySQL','select c_kode_prodi,count(*) from t_mst_dosen group by c_kode_prodi');
/*!40000 ALTER TABLE `tab_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tab_filter`
--

DROP TABLE IF EXISTS `tab_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tab_filter` (
  `id_filter` int(11) NOT NULL AUTO_INCREMENT,
  `nama_filter` varchar(40) NOT NULL,
  `jenis_filter` varchar(20) NOT NULL,
  `intv` varchar(20) NOT NULL,
  `id_laporan` int(11) NOT NULL,
  PRIMARY KEY (`id_filter`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tab_filter`
--

LOCK TABLES `tab_filter` WRITE;
/*!40000 ALTER TABLE `tab_filter` DISABLE KEYS */;
INSERT INTO `tab_filter` VALUES (1,'hide/show faculty','baris','range',1),(2,'hide/show faculty','baris','interval',2),(3,'hide/show province','baris','range',3),(4,'hide/show program','baris','interval',1);
/*!40000 ALTER TABLE `tab_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tab_grouping`
--

DROP TABLE IF EXISTS `tab_grouping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tab_grouping` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `nama_group` varchar(40) NOT NULL,
  `jenis_group` varchar(20) NOT NULL,
  `intv` varchar(20) NOT NULL,
  `h_kolom` varchar(40) NOT NULL,
  `f_kolom` int(4) NOT NULL,
  `id_laporan` int(11) NOT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tab_grouping`
--

LOCK TABLES `tab_grouping` WRITE;
/*!40000 ALTER TABLE `tab_grouping` DISABLE KEYS */;
INSERT INTO `tab_grouping` VALUES (1,'group on faculty','baris','range','',3,1),(2,'group on sex','baris','interval','',3,2);
/*!40000 ALTER TABLE `tab_grouping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `nik` varchar(20) NOT NULL,
  `nama_user` varchar(75) NOT NULL,
  `pass_user` varchar(50) NOT NULL,
  `prev_user` int(11) NOT NULL,
  PRIMARY KEY (`nik`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('admin','administrator','21232f297a57a5a743894a0e4a801fc3',1);
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

-- Dump completed on 2018-07-27 22:42:04
