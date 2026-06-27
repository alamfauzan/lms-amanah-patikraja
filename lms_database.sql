-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: lms_amanah_patikraja
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
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
-- Table structure for table `hasil_kuis`
--

DROP TABLE IF EXISTS `hasil_kuis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hasil_kuis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kuis_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `attempt` int NOT NULL DEFAULT '1',
  `nilai_raw` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nilai_akhir` decimal(5,2) NOT NULL DEFAULT '0.00',
  `benar` int NOT NULL DEFAULT '0',
  `salah` int NOT NULL DEFAULT '0',
  `mulai_at` datetime DEFAULT NULL,
  `selesai_at` datetime DEFAULT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_hasil_kuis` (`kuis_id`,`siswa_id`,`attempt`),
  KEY `hasil_kuis_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `hasil_kuis_kuis_id_foreign` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hasil_kuis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hasil_kuis`
--

LOCK TABLES `hasil_kuis` WRITE;
/*!40000 ALTER TABLE `hasil_kuis` DISABLE KEYS */;
INSERT INTO `hasil_kuis` VALUES (2,3,34,1,10.00,9.09,1,1,'2026-06-10 16:06:10','2026-06-10 16:06:40',1,'2026-06-10 09:06:10','2026-06-10 09:06:40'),(3,5,34,1,0.00,0.00,0,1,'2026-06-10 23:12:33','2026-06-10 23:12:48',1,'2026-06-10 16:12:33','2026-06-10 16:12:48'),(4,4,34,1,10.00,50.00,1,1,'2026-06-10 23:42:27','2026-06-10 23:45:05',1,'2026-06-10 16:42:27','2026-06-10 16:45:05');
/*!40000 ALTER TABLE `hasil_kuis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `hari` tinyint NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jadwal_kelas_id_foreign` (`kelas_id`),
  KEY `jadwal_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `jadwal_guru_id_foreign` (`guru_id`),
  CONSTRAINT `jadwal_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jadwal_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal`
--

LOCK TABLES `jadwal` WRITE;
/*!40000 ALTER TABLE `jadwal` DISABLE KEYS */;
INSERT INTO `jadwal` VALUES (3,4,4,17,1,'01:15:00','03:13:00','kmk','2026-06-10 09:13:47','2026-06-10 09:13:47'),(4,5,4,10,1,'01:18:00','03:18:00','dc 098','2026-06-10 09:19:02','2026-06-10 09:19:02'),(5,4,4,10,3,'03:50:00','04:51:00','asd','2026-06-10 09:46:35','2026-06-10 09:46:35'),(8,7,4,36,4,'09:04:00','10:05:00','dc 301','2026-06-10 17:01:50','2026-06-10 17:01:50'),(9,5,8,35,3,'08:40:00','09:00:00','iot301','2026-06-15 11:41:23','2026-06-15 11:41:23');
/*!40000 ALTER TABLE `jadwal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jawaban_siswa`
--

DROP TABLE IF EXISTS `jawaban_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jawaban_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kuis_id` bigint unsigned NOT NULL,
  `soal_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `attempt` int NOT NULL DEFAULT '1',
  `jawaban` text COLLATE utf8mb4_unicode_ci,
  `is_benar` tinyint(1) DEFAULT NULL,
  `poin_diperoleh` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_jawaban_siswa` (`soal_id`,`siswa_id`,`attempt`),
  KEY `jawaban_siswa_kuis_id_foreign` (`kuis_id`),
  KEY `jawaban_siswa_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `jawaban_siswa_kuis_id_foreign` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jawaban_siswa_soal_id_foreign` FOREIGN KEY (`soal_id`) REFERENCES `soal_kuis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jawaban_siswa`
--

LOCK TABLES `jawaban_siswa` WRITE;
/*!40000 ALTER TABLE `jawaban_siswa` DISABLE KEYS */;
INSERT INTO `jawaban_siswa` VALUES (4,3,8,34,1,'a',0,0.00,'2026-06-10 09:06:22','2026-06-10 09:06:39'),(5,3,9,34,1,'salah',1,10.00,'2026-06-10 09:06:33','2026-06-10 09:06:39'),(6,5,12,34,1,'a',0,0.00,'2026-06-10 16:12:38','2026-06-10 16:12:48'),(7,4,10,34,1,'c',1,10.00,'2026-06-10 16:42:32','2026-06-10 16:45:04'),(8,4,11,34,1,'c',0,0.00,'2026-06-10 16:42:53','2026-06-10 16:45:05');
/*!40000 ALTER TABLE `jawaban_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `wali_kelas_id` bigint unsigned DEFAULT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_wali_kelas_id_foreign` (`wali_kelas_id`),
  CONSTRAINT `kelas_wali_kelas_id_foreign` FOREIGN KEY (`wali_kelas_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (4,'Kelas 7A','Kelas 7A IPA',18,'2016/2017','2026-06-08 10:05:16','2026-06-10 09:52:04'),(5,'9A','mantap',10,'2016/2017','2026-06-10 09:17:59','2026-06-15 11:38:39'),(7,'10 A','halo',36,'2030?/2031','2026-06-10 16:59:49','2026-06-10 17:00:42');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas_mapel_guru`
--

DROP TABLE IF EXISTS `kelas_mapel_guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_mapel_guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_mapel_guru_kelas_id_mata_pelajaran_id_unique` (`kelas_id`,`mata_pelajaran_id`),
  KEY `kelas_mapel_guru_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `kelas_mapel_guru_guru_id_foreign` (`guru_id`),
  CONSTRAINT `kelas_mapel_guru_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_mapel_guru_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_mapel_guru_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas_mapel_guru`
--

LOCK TABLES `kelas_mapel_guru` WRITE;
/*!40000 ALTER TABLE `kelas_mapel_guru` DISABLE KEYS */;
INSERT INTO `kelas_mapel_guru` VALUES (4,4,4,10,'2026-06-08 10:05:16','2026-06-08 12:03:58'),(5,4,5,10,'2026-06-08 12:04:47','2026-06-10 09:54:00'),(7,5,4,17,'2026-06-10 09:19:02','2026-06-10 09:19:34'),(8,5,5,10,'2026-06-10 09:19:54','2026-06-10 09:19:54'),(10,7,4,36,'2026-06-10 17:01:50','2026-06-10 17:01:50'),(11,5,8,35,'2026-06-15 11:41:23','2026-06-15 11:41:23');
/*!40000 ALTER TABLE `kelas_mapel_guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas_siswa`
--

DROP TABLE IF EXISTS `kelas_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kelas_siswa_kelas_id_siswa_id_unique` (`kelas_id`,`siswa_id`),
  KEY `kelas_siswa_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `kelas_siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kelas_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas_siswa`
--

LOCK TABLES `kelas_siswa` WRITE;
/*!40000 ALTER TABLE `kelas_siswa` DISABLE KEYS */;
INSERT INTO `kelas_siswa` VALUES (4,4,11,'2026-06-08 10:23:29','2026-06-08 10:23:29'),(5,4,12,'2026-06-08 10:23:29','2026-06-08 10:23:29'),(6,4,13,'2026-06-08 10:23:29','2026-06-08 10:23:29'),(7,4,14,'2026-06-08 10:23:29','2026-06-08 10:23:29'),(8,4,15,'2026-06-08 10:23:29','2026-06-08 10:23:29'),(9,4,28,'2026-06-08 12:18:34','2026-06-08 12:18:34'),(11,4,34,'2026-06-10 08:59:58','2026-06-10 08:59:58'),(14,5,33,'2026-06-10 09:42:19','2026-06-10 09:42:19'),(15,5,22,'2026-06-10 15:40:38','2026-06-10 15:40:38'),(18,7,24,'2026-06-10 17:02:15','2026-06-10 17:02:15'),(19,7,25,'2026-06-10 17:02:30','2026-06-10 17:02:30'),(20,7,26,'2026-06-10 17:02:36','2026-06-10 17:02:36'),(21,7,37,'2026-06-10 17:03:12','2026-06-10 17:03:12'),(22,5,21,'2026-06-15 11:44:03','2026-06-15 11:44:03');
/*!40000 ALTER TABLE `kelas_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kuis`
--

DROP TABLE IF EXISTS `kuis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kuis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `pertemuan_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `durasi_menit` int NOT NULL DEFAULT '60',
  `jumlah_soal` int NOT NULL DEFAULT '10',
  `batas_pengerjaan` int NOT NULL DEFAULT '1',
  `nilai_diambil_dari` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'terakhir',
  `bobot_nilai` decimal(5,2) NOT NULL DEFAULT '100.00',
  `mulai_at` datetime DEFAULT NULL,
  `selesai_at` datetime DEFAULT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kuis_kelas_id_foreign` (`kelas_id`),
  KEY `kuis_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `kuis_guru_id_foreign` (`guru_id`),
  KEY `kuis_pertemuan_id_foreign` (`pertemuan_id`),
  CONSTRAINT `kuis_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kuis_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kuis_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kuis_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kuis`
--

LOCK TABLES `kuis` WRITE;
/*!40000 ALTER TABLE `kuis` DISABLE KEYS */;
INSERT INTO `kuis` VALUES (3,4,4,10,NULL,'abc','y',15,2,3,'tertinggi',100.00,'2026-06-09 23:00:00','2026-06-11 23:30:00',1,'2026-06-09 09:24:27','2026-06-10 09:05:54'),(4,4,4,10,9,'evaluasi','kerjakan dgn teliti',60,2,1,'tertinggi',100.00,'2026-06-10 05:52:00','2026-06-11 05:52:00',1,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(5,4,4,10,10,'evaluasi aljabar','kerjakan',60,1,1,'terakhir',100.00,'2026-06-02 06:05:00','2026-06-13 06:05:00',1,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(6,4,4,10,11,'evaluasi','kerjakan dengan teliti',60,2,1,'tertinggi',100.00,'2026-06-15 06:32:00','2026-06-15 10:32:00',1,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(7,7,4,36,12,'kuis 1','kerjakan dengan jujur',30,2,1,'tertinggi',100.00,'2026-06-10 07:13:00','2026-06-10 16:00:00',1,'2026-06-10 17:16:39','2026-06-10 17:16:39'),(8,7,4,36,12,'kuis 2','sadjadk',60,1,1,'tertinggi',100.00,'2026-06-11 07:30:00','2026-06-12 07:30:00',1,'2026-06-10 17:31:55','2026-06-10 17:31:55'),(9,7,4,36,12,'fvsdv','sdvz',60,1,1,'tertinggi',100.00,'2026-06-11 07:37:00','2026-06-24 11:37:00',0,'2026-06-10 17:37:55','2026-06-10 17:37:55'),(10,4,5,10,13,'dssf','sdsd',60,1,1,'tertinggi',100.00,'2026-06-11 07:42:00','2026-06-18 07:42:00',1,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(11,5,8,35,14,'seni rupa','kerjakan dengan teliti',60,1,1,'tertinggi',100.00,'2026-06-19 01:48:00','2026-06-17 01:48:00',1,'2026-06-15 11:49:25','2026-06-15 11:49:25');
/*!40000 ALTER TABLE `kuis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_pelajaran`
--

DROP TABLE IF EXISTS `mata_pelajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_pelajaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mata_pelajaran_kode_mapel_unique` (`kode_mapel`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_pelajaran`
--

LOCK TABLES `mata_pelajaran` WRITE;
/*!40000 ALTER TABLE `mata_pelajaran` DISABLE KEYS */;
INSERT INTO `mata_pelajaran` VALUES (4,'Matematika','MAT01','2026-06-08 10:05:16','2026-06-08 10:05:16'),(5,'ipa','ipa-10','2026-06-08 12:04:14','2026-06-08 12:04:14'),(7,'ips','ips-02','2026-06-09 13:25:49','2026-06-09 13:25:49'),(8,'seni budaya','SBY-04','2026-06-10 13:57:48','2026-06-10 13:57:48'),(9,'kalkulus','kls-01','2026-06-10 22:34:56','2026-06-10 22:34:56');
/*!40000 ALTER TABLE `mata_pelajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materi`
--

DROP TABLE IF EXISTS `materi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pertemuan_id` bigint unsigned NOT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` longtext COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'teks',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `materi_pertemuan_id_foreign` (`pertemuan_id`),
  KEY `materi_kelas_id_foreign` (`kelas_id`),
  KEY `materi_guru_id_foreign` (`guru_id`),
  CONSTRAINT `materi_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `materi_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `materi_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materi`
--

LOCK TABLES `materi` WRITE;
/*!40000 ALTER TABLE `materi` DISABLE KEYS */;
INSERT INTO `materi` VALUES (2,4,4,10,'Materi Aljabar Dasar','<p>Ini adalah materi aljabar dasar. Contoh: x + 2 = 5</p>',NULL,'teks','2026-06-08 10:05:16','2026-06-08 11:00:04'),(4,9,4,10,'pengenalan al jabar','apa itu aljabar?','materi/files/NkzDlQBwyy4nSr2sNS5lHUqJWNIRdGWhUt1RBSCf.pdf','file','2026-06-10 15:49:25','2026-06-10 15:49:25'),(5,10,4,10,'Materi Aljabar Dasar','pengertian aljabar','materi/files/cL94am404hvRyrCwcA6hBPwYAZprQabpjmHdajUB.pdf','file','2026-06-10 16:02:05','2026-06-10 16:02:05'),(6,11,4,10,'Materi Aljabar Dasar','pengertian aljabar?','materi/files/pavpUlNeevq8eAq7Uhj8Gq9EIcddObqbLE8daIA8.pdf','file','2026-06-10 16:30:01','2026-06-10 16:30:01'),(7,12,7,36,'pengenalan aljabar','pengertian aljabar','materi/files/cEKalgvUmMzh8EMM7HVYTPmy2CUYnwfOy7aKaOPT.pdf','file','2026-06-10 17:09:44','2026-06-10 17:09:44'),(8,14,5,35,'seni rupa','seni rupa merupkan','materi/files/q2gnRQNCkHQdEerSS4YnQkK4dE0gQXPonYF3cqtK.docx','file','2026-06-15 11:45:31','2026-06-15 11:45:31');
/*!40000 ALTER TABLE `materi` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2026_06_04_182640_create_permission_tables',1),(6,'2026_06_04_184931_create_kelas_table',1),(7,'2026_06_04_184946_create_mata_pelajaran_table',1),(8,'2026_06_04_184958_create_kelas_siswa_table',1),(9,'2026_06_04_185002_create_kelas_mapel_guru_table',1),(10,'2026_06_04_185853_create_pertemuan_table',1),(11,'2026_06_04_185854_create_kuis_table',1),(12,'2026_06_04_185905_create_tugas_table',1),(13,'2026_06_04_185906_create_pengumpulan_tugas_table',1),(14,'2026_06_04_185907_create_soal_kuis_table',1),(15,'2026_06_04_185908_create_jawaban_siswa_table',1),(16,'2026_06_04_185909_create_hasil_kuis_table',1),(17,'2026_06_04_185910_create_materi_table',1),(18,'2026_06_04_185911_create_jadwal_table',1),(19,'2026_06_04_185912_create_notifikasi_table',1),(20,'2026_06_05_120000_create_tahun_ajaran_table',1),(21,'2026_06_05_120001_create_settings_table',1),(22,'2026_06_07_103500_add_nilai_diambil_dari_to_kuis_table',1),(23,'2026_06_08_154325_add_gambar_to_soal_kuis_table',1),(24,'2026_06_09_120000_add_avatar_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (10,'App\\Models\\User',10),(11,'App\\Models\\User',11),(11,'App\\Models\\User',12),(11,'App\\Models\\User',13),(11,'App\\Models\\User',14),(11,'App\\Models\\User',15),(12,'App\\Models\\User',16),(10,'App\\Models\\User',17),(10,'App\\Models\\User',18),(10,'App\\Models\\User',19),(10,'App\\Models\\User',20),(11,'App\\Models\\User',21),(11,'App\\Models\\User',22),(11,'App\\Models\\User',23),(11,'App\\Models\\User',24),(11,'App\\Models\\User',25),(11,'App\\Models\\User',26),(11,'App\\Models\\User',27),(11,'App\\Models\\User',28),(11,'App\\Models\\User',29),(11,'App\\Models\\User',30),(11,'App\\Models\\User',32),(11,'App\\Models\\User',33),(11,'App\\Models\\User',34),(10,'App\\Models\\User',35),(10,'App\\Models\\User',36),(11,'App\\Models\\User',37),(10,'App\\Models\\User',38),(11,'App\\Models\\User',39);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibaca_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_user_id_foreign` (`user_id`),
  CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
INSERT INTO `notifikasi` VALUES (4,10,'Selamat datang','Selamat bergabung, Alam! Akses dashboard guru sekarang.','info',NULL,NULL,'2026-06-08 10:03:42','2026-06-08 10:03:42'),(5,10,'Tugas Baru','Ada tugas baru untuk kelas yang Anda ampu.','info',NULL,NULL,'2026-06-08 10:03:42','2026-06-08 10:03:42'),(6,10,'Pengumuman','Jadwal rapat guru pada hari Rabu, jam 10:00.','info',NULL,NULL,'2026-06-08 10:03:42','2026-06-08 10:03:42'),(7,11,'Tugas Baru: asd','Ada tugas baru di kelas Kelas 7A. Deadline: 10 Jun 2026 00:47','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 10:48:27','2026-06-08 10:48:27'),(8,12,'Tugas Baru: asd','Ada tugas baru di kelas Kelas 7A. Deadline: 10 Jun 2026 00:47','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 10:48:27','2026-06-08 10:48:27'),(9,13,'Tugas Baru: asd','Ada tugas baru di kelas Kelas 7A. Deadline: 10 Jun 2026 00:47','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 10:48:27','2026-06-08 10:48:27'),(10,14,'Tugas Baru: asd','Ada tugas baru di kelas Kelas 7A. Deadline: 10 Jun 2026 00:47','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 10:48:27','2026-06-08 10:48:27'),(11,15,'Tugas Baru: asd','Ada tugas baru di kelas Kelas 7A. Deadline: 10 Jun 2026 00:47','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 10:48:27','2026-06-08 10:48:27'),(12,11,'Nilai Tersedia','Tugas \"asd\" telah dinilai. Nilai: 100','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 11:08:22','2026-06-08 11:08:22'),(13,12,'Nilai Tersedia','Tugas \"asd\" telah dinilai. Nilai: 100','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 11:08:34','2026-06-08 11:08:34'),(14,11,'Nilai Tersedia','Tugas \"asd\" telah dinilai. Nilai: 10','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-08 11:14:48','2026-06-08 11:14:48'),(15,11,'Nilai Tersedia','Tugas \"Tugas 1 - Soal Aljabar\" telah dinilai. Nilai: 70.00','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-08 11:31:33','2026-06-08 11:31:33'),(16,11,'Nilai Tersedia','Tugas \"Tugas 1 - Soal Aljabar\" telah dinilai. Nilai: 99.00','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-08 11:31:41','2026-06-08 11:31:41'),(17,11,'Nilai Tersedia','Tugas \"Tugas 1 - Soal Aljabar\" telah dinilai. Nilai: 99.00','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-08 11:31:49','2026-06-08 11:31:49'),(18,11,'Nilai Tersedia','Tugas \"Tugas 1 - Soal Aljabar\" telah dinilai. Nilai: 99.00','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-08 11:35:09','2026-06-08 11:35:09'),(19,11,'Kuis Baru: abc','Ada kuis baru di kelas Kelas 7A. Durasi: 15 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(20,12,'Kuis Baru: abc','Ada kuis baru di kelas Kelas 7A. Durasi: 15 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(21,13,'Kuis Baru: abc','Ada kuis baru di kelas Kelas 7A. Durasi: 15 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(22,14,'Kuis Baru: abc','Ada kuis baru di kelas Kelas 7A. Durasi: 15 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(23,15,'Kuis Baru: abc','Ada kuis baru di kelas Kelas 7A. Durasi: 15 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(24,28,'Kuis Baru: abc','Ada kuis baru di kelas Kelas 7A. Durasi: 15 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(25,34,'Kuis Baru: abc','Ada kuis baru di kelas Kelas 7A. Durasi: 15 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(26,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"Tugas 1 - Soal Aljabar\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-09 09:27:32','2026-06-09 09:27:32'),(27,34,'Nilai Tersedia','Tugas \"Tugas 1 - Soal Aljabar\" telah dinilai. Nilai: 100','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-09 09:28:29','2026-06-09 09:28:29'),(28,34,'Nilai Tersedia','Tugas \"Tugas 1 - Soal Aljabar\" telah dinilai. Nilai: 100.00','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-09 09:28:36','2026-06-09 09:28:36'),(29,34,'Nilai Tersedia','Tugas \"Tugas 1 - Soal Aljabar\" telah dinilai. Nilai: 100.00','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/1',NULL,'2026-06-09 09:28:41','2026-06-09 09:28:41'),(30,12,'Nilai Tersedia','Tugas \"tugas 2\" telah dinilai. Nilai: 100.00','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-09 14:07:36','2026-06-09 14:07:36'),(31,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"tugas 2\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-10 09:02:34','2026-06-10 09:02:34'),(32,34,'Nilai Tersedia','Tugas \"tugas 2\" telah dinilai. Nilai: 90','nilai_tersedia','http://127.0.0.1:8000/kelas/4/tugas/3',NULL,'2026-06-10 09:03:31','2026-06-10 09:03:31'),(33,10,'Kuis Selesai Dikerjakan','fian menyelesaikan kuis \"abc\" dengan nilai 9.09','pengumpulan','http://127.0.0.1:8000/kelas/4/kuis/3',NULL,'2026-06-10 09:06:40','2026-06-10 09:06:40'),(34,11,'Tugas Baru: persamaan linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 04:42','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 14:43:05','2026-06-10 14:43:05'),(35,12,'Tugas Baru: persamaan linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 04:42','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 14:43:05','2026-06-10 14:43:05'),(36,13,'Tugas Baru: persamaan linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 04:42','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 14:43:05','2026-06-10 14:43:05'),(37,14,'Tugas Baru: persamaan linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 04:42','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 14:43:05','2026-06-10 14:43:05'),(38,15,'Tugas Baru: persamaan linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 04:42','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 14:43:05','2026-06-10 14:43:05'),(39,28,'Tugas Baru: persamaan linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 04:42','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 14:43:05','2026-06-10 14:43:05'),(40,34,'Tugas Baru: persamaan linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 04:42','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 14:43:05','2026-06-10 14:43:05'),(41,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"persamaan linear\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/4',NULL,'2026-06-10 15:16:24','2026-06-10 15:16:24'),(42,11,'Materi Baru: pengenalan al jabar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/4',NULL,'2026-06-10 15:49:25','2026-06-10 15:49:25'),(43,12,'Materi Baru: pengenalan al jabar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/4',NULL,'2026-06-10 15:49:25','2026-06-10 15:49:25'),(44,13,'Materi Baru: pengenalan al jabar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/4',NULL,'2026-06-10 15:49:25','2026-06-10 15:49:25'),(45,14,'Materi Baru: pengenalan al jabar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/4',NULL,'2026-06-10 15:49:25','2026-06-10 15:49:25'),(46,15,'Materi Baru: pengenalan al jabar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/4',NULL,'2026-06-10 15:49:25','2026-06-10 15:49:25'),(47,28,'Materi Baru: pengenalan al jabar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/4',NULL,'2026-06-10 15:49:25','2026-06-10 15:49:25'),(48,34,'Materi Baru: pengenalan al jabar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/4',NULL,'2026-06-10 15:49:25','2026-06-10 15:49:25'),(49,11,'Tugas Baru: linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 08:53','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 15:50:33','2026-06-10 15:50:33'),(50,12,'Tugas Baru: linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 08:53','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 15:50:33','2026-06-10 15:50:33'),(51,13,'Tugas Baru: linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 08:53','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 15:50:33','2026-06-10 15:50:33'),(52,14,'Tugas Baru: linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 08:53','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 15:50:33','2026-06-10 15:50:33'),(53,15,'Tugas Baru: linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 08:53','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 15:50:33','2026-06-10 15:50:33'),(54,28,'Tugas Baru: linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 08:53','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 15:50:33','2026-06-10 15:50:33'),(55,34,'Tugas Baru: linear','Ada tugas baru di kelas Kelas 7A. Deadline: 12 Jun 2026 08:53','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 15:50:33','2026-06-10 15:50:33'),(56,11,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(57,12,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(58,13,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(59,14,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(60,15,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(61,28,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(62,34,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(63,11,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/5',NULL,'2026-06-10 16:02:05','2026-06-10 16:02:05'),(64,12,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/5',NULL,'2026-06-10 16:02:05','2026-06-10 16:02:05'),(65,13,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/5',NULL,'2026-06-10 16:02:05','2026-06-10 16:02:05'),(66,14,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/5',NULL,'2026-06-10 16:02:05','2026-06-10 16:02:05'),(67,15,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/5',NULL,'2026-06-10 16:02:05','2026-06-10 16:02:05'),(68,28,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/5',NULL,'2026-06-10 16:02:05','2026-06-10 16:02:05'),(69,34,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/5',NULL,'2026-06-10 16:02:05','2026-06-10 16:02:05'),(70,11,'Tugas Baru: linear adalah','Ada tugas baru di kelas Kelas 7A. Deadline: 27 Jun 2026 06:03','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:03:32','2026-06-10 16:03:32'),(71,12,'Tugas Baru: linear adalah','Ada tugas baru di kelas Kelas 7A. Deadline: 27 Jun 2026 06:03','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:03:32','2026-06-10 16:03:32'),(72,13,'Tugas Baru: linear adalah','Ada tugas baru di kelas Kelas 7A. Deadline: 27 Jun 2026 06:03','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:03:32','2026-06-10 16:03:32'),(73,14,'Tugas Baru: linear adalah','Ada tugas baru di kelas Kelas 7A. Deadline: 27 Jun 2026 06:03','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:03:32','2026-06-10 16:03:32'),(74,15,'Tugas Baru: linear adalah','Ada tugas baru di kelas Kelas 7A. Deadline: 27 Jun 2026 06:03','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:03:32','2026-06-10 16:03:32'),(75,28,'Tugas Baru: linear adalah','Ada tugas baru di kelas Kelas 7A. Deadline: 27 Jun 2026 06:03','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:03:32','2026-06-10 16:03:32'),(76,34,'Tugas Baru: linear adalah','Ada tugas baru di kelas Kelas 7A. Deadline: 27 Jun 2026 06:03','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:03:32','2026-06-10 16:03:32'),(77,11,'Kuis Baru: evaluasi aljabar','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(78,12,'Kuis Baru: evaluasi aljabar','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(79,13,'Kuis Baru: evaluasi aljabar','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(80,14,'Kuis Baru: evaluasi aljabar','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(81,15,'Kuis Baru: evaluasi aljabar','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(82,28,'Kuis Baru: evaluasi aljabar','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(83,34,'Kuis Baru: evaluasi aljabar','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(84,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"linear adalah\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/7',NULL,'2026-06-10 16:11:59','2026-06-10 16:11:59'),(85,10,'Kuis Selesai Dikerjakan','fian menyelesaikan kuis \"evaluasi aljabar\" dengan nilai 0','pengumpulan','http://127.0.0.1:8000/kelas/4/kuis/5',NULL,'2026-06-10 16:12:48','2026-06-10 16:12:48'),(86,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"linear\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 16:14:12','2026-06-10 16:14:12'),(87,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"linear\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 16:14:15','2026-06-10 16:14:15'),(88,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"linear\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/6',NULL,'2026-06-10 16:14:21','2026-06-10 16:14:21'),(89,11,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/6',NULL,'2026-06-10 16:30:01','2026-06-10 16:30:01'),(90,12,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/6',NULL,'2026-06-10 16:30:01','2026-06-10 16:30:01'),(91,13,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/6',NULL,'2026-06-10 16:30:01','2026-06-10 16:30:01'),(92,14,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/6',NULL,'2026-06-10 16:30:01','2026-06-10 16:30:01'),(93,15,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/6',NULL,'2026-06-10 16:30:01','2026-06-10 16:30:01'),(94,28,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/6',NULL,'2026-06-10 16:30:01','2026-06-10 16:30:01'),(95,34,'Materi Baru: Materi Aljabar Dasar','Materi baru telah ditambahkan di kelas Kelas 7A','materi_baru','http://127.0.0.1:8000/materi/6',NULL,'2026-06-10 16:30:01','2026-06-10 16:30:01'),(96,11,'Tugas Baru: Pengertian linear','Ada tugas baru di kelas Kelas 7A. Deadline: 17 Jun 2026 06:31','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:31:25','2026-06-10 16:31:25'),(97,12,'Tugas Baru: Pengertian linear','Ada tugas baru di kelas Kelas 7A. Deadline: 17 Jun 2026 06:31','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:31:25','2026-06-10 16:31:25'),(98,13,'Tugas Baru: Pengertian linear','Ada tugas baru di kelas Kelas 7A. Deadline: 17 Jun 2026 06:31','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:31:25','2026-06-10 16:31:25'),(99,14,'Tugas Baru: Pengertian linear','Ada tugas baru di kelas Kelas 7A. Deadline: 17 Jun 2026 06:31','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:31:25','2026-06-10 16:31:25'),(100,15,'Tugas Baru: Pengertian linear','Ada tugas baru di kelas Kelas 7A. Deadline: 17 Jun 2026 06:31','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:31:25','2026-06-10 16:31:25'),(101,28,'Tugas Baru: Pengertian linear','Ada tugas baru di kelas Kelas 7A. Deadline: 17 Jun 2026 06:31','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:31:25','2026-06-10 16:31:25'),(102,34,'Tugas Baru: Pengertian linear','Ada tugas baru di kelas Kelas 7A. Deadline: 17 Jun 2026 06:31','tugas_baru','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:31:25','2026-06-10 16:31:25'),(103,11,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/6',NULL,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(104,12,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/6',NULL,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(105,13,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/6',NULL,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(106,14,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/6',NULL,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(107,15,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/6',NULL,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(108,28,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/6',NULL,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(109,34,'Kuis Baru: evaluasi','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/6',NULL,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(110,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"Pengertian linear\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:41:47','2026-06-10 16:41:47'),(111,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"Pengertian linear\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:41:52','2026-06-10 16:41:52'),(112,10,'Tugas Dikumpulkan','fian mengumpulkan tugas \"Pengertian linear\".','pengumpulan','http://127.0.0.1:8000/kelas/4/tugas/8',NULL,'2026-06-10 16:41:57','2026-06-10 16:41:57'),(113,10,'Kuis Selesai Dikerjakan','fian menyelesaikan kuis \"evaluasi\" dengan nilai 50','pengumpulan','http://127.0.0.1:8000/kelas/4/kuis/4',NULL,'2026-06-10 16:45:05','2026-06-10 16:45:05'),(114,24,'Materi Baru: pengenalan aljabar','Materi baru telah ditambahkan di kelas 10 A','materi_baru','http://127.0.0.1:8000/materi/7',NULL,'2026-06-10 17:09:44','2026-06-10 17:09:44'),(115,25,'Materi Baru: pengenalan aljabar','Materi baru telah ditambahkan di kelas 10 A','materi_baru','http://127.0.0.1:8000/materi/7',NULL,'2026-06-10 17:09:44','2026-06-10 17:09:44'),(116,26,'Materi Baru: pengenalan aljabar','Materi baru telah ditambahkan di kelas 10 A','materi_baru','http://127.0.0.1:8000/materi/7',NULL,'2026-06-10 17:09:44','2026-06-10 17:09:44'),(117,37,'Materi Baru: pengenalan aljabar','Materi baru telah ditambahkan di kelas 10 A','materi_baru','http://127.0.0.1:8000/materi/7',NULL,'2026-06-10 17:09:44','2026-06-10 17:09:44'),(118,24,'Tugas Baru: Materi Aljabar Dasar','Ada tugas baru di kelas 10 A. Deadline: 11 Jun 2026 07:10','tugas_baru','http://127.0.0.1:8000/kelas/7/tugas/9',NULL,'2026-06-10 17:11:09','2026-06-10 17:11:09'),(119,25,'Tugas Baru: Materi Aljabar Dasar','Ada tugas baru di kelas 10 A. Deadline: 11 Jun 2026 07:10','tugas_baru','http://127.0.0.1:8000/kelas/7/tugas/9',NULL,'2026-06-10 17:11:09','2026-06-10 17:11:09'),(120,26,'Tugas Baru: Materi Aljabar Dasar','Ada tugas baru di kelas 10 A. Deadline: 11 Jun 2026 07:10','tugas_baru','http://127.0.0.1:8000/kelas/7/tugas/9',NULL,'2026-06-10 17:11:09','2026-06-10 17:11:09'),(121,37,'Tugas Baru: Materi Aljabar Dasar','Ada tugas baru di kelas 10 A. Deadline: 11 Jun 2026 07:10','tugas_baru','http://127.0.0.1:8000/kelas/7/tugas/9',NULL,'2026-06-10 17:11:09','2026-06-10 17:11:09'),(122,24,'Kuis Baru: kuis 1','Ada kuis baru di kelas 10 A. Durasi: 30 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/7',NULL,'2026-06-10 17:16:39','2026-06-10 17:16:39'),(123,25,'Kuis Baru: kuis 1','Ada kuis baru di kelas 10 A. Durasi: 30 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/7',NULL,'2026-06-10 17:16:39','2026-06-10 17:16:39'),(124,26,'Kuis Baru: kuis 1','Ada kuis baru di kelas 10 A. Durasi: 30 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/7',NULL,'2026-06-10 17:16:39','2026-06-10 17:16:39'),(125,37,'Kuis Baru: kuis 1','Ada kuis baru di kelas 10 A. Durasi: 30 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/7',NULL,'2026-06-10 17:16:39','2026-06-10 17:16:39'),(126,36,'Tugas Dikumpulkan','wahyu mengumpulkan tugas \"Materi Aljabar Dasar\".','pengumpulan','http://127.0.0.1:8000/kelas/7/tugas/9',NULL,'2026-06-10 17:20:08','2026-06-10 17:20:08'),(127,24,'Kuis Baru: kuis 2','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/8',NULL,'2026-06-10 17:31:55','2026-06-10 17:31:55'),(128,25,'Kuis Baru: kuis 2','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/8',NULL,'2026-06-10 17:31:55','2026-06-10 17:31:55'),(129,26,'Kuis Baru: kuis 2','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/8',NULL,'2026-06-10 17:31:55','2026-06-10 17:31:55'),(130,37,'Kuis Baru: kuis 2','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/8',NULL,'2026-06-10 17:31:55','2026-06-10 17:31:55'),(131,24,'Kuis Baru: fvsdv','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/9',NULL,'2026-06-10 17:37:55','2026-06-10 17:37:55'),(132,25,'Kuis Baru: fvsdv','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/9',NULL,'2026-06-10 17:37:55','2026-06-10 17:37:55'),(133,26,'Kuis Baru: fvsdv','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/9',NULL,'2026-06-10 17:37:55','2026-06-10 17:37:55'),(134,37,'Kuis Baru: fvsdv','Ada kuis baru di kelas 10 A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/7/kuis/9',NULL,'2026-06-10 17:37:55','2026-06-10 17:37:55'),(135,11,'Kuis Baru: dssf','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/10',NULL,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(136,12,'Kuis Baru: dssf','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/10',NULL,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(137,13,'Kuis Baru: dssf','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/10',NULL,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(138,14,'Kuis Baru: dssf','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/10',NULL,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(139,15,'Kuis Baru: dssf','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/10',NULL,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(140,28,'Kuis Baru: dssf','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/10',NULL,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(141,34,'Kuis Baru: dssf','Ada kuis baru di kelas Kelas 7A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/4/kuis/10',NULL,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(142,21,'Materi Baru: seni rupa','Materi baru telah ditambahkan di kelas 9A','materi_baru','http://127.0.0.1:8000/materi/8',NULL,'2026-06-15 11:45:31','2026-06-15 11:45:31'),(143,22,'Materi Baru: seni rupa','Materi baru telah ditambahkan di kelas 9A','materi_baru','http://127.0.0.1:8000/materi/8',NULL,'2026-06-15 11:45:31','2026-06-15 11:45:31'),(144,33,'Materi Baru: seni rupa','Materi baru telah ditambahkan di kelas 9A','materi_baru','http://127.0.0.1:8000/materi/8',NULL,'2026-06-15 11:45:31','2026-06-15 11:45:31'),(145,21,'Tugas Baru: bab 1 pengenalan seni rupa','Ada tugas baru di kelas 9A. Deadline: 24 Jun 2026 01:46','tugas_baru','http://127.0.0.1:8000/kelas/5/tugas/10',NULL,'2026-06-15 11:46:25','2026-06-15 11:46:25'),(146,22,'Tugas Baru: bab 1 pengenalan seni rupa','Ada tugas baru di kelas 9A. Deadline: 24 Jun 2026 01:46','tugas_baru','http://127.0.0.1:8000/kelas/5/tugas/10',NULL,'2026-06-15 11:46:25','2026-06-15 11:46:25'),(147,33,'Tugas Baru: bab 1 pengenalan seni rupa','Ada tugas baru di kelas 9A. Deadline: 24 Jun 2026 01:46','tugas_baru','http://127.0.0.1:8000/kelas/5/tugas/10',NULL,'2026-06-15 11:46:25','2026-06-15 11:46:25'),(148,21,'Kuis Baru: seni rupa','Ada kuis baru di kelas 9A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/5/kuis/11',NULL,'2026-06-15 11:49:25','2026-06-15 11:49:25'),(149,22,'Kuis Baru: seni rupa','Ada kuis baru di kelas 9A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/5/kuis/11',NULL,'2026-06-15 11:49:25','2026-06-15 11:49:25'),(150,33,'Kuis Baru: seni rupa','Ada kuis baru di kelas 9A. Durasi: 60 menit.','kuis_baru','http://127.0.0.1:8000/kelas/5/kuis/11',NULL,'2026-06-15 11:49:25','2026-06-15 11:49:25'),(151,35,'Tugas Dikumpulkan','upik mengumpulkan tugas \"bab 1 pengenalan seni rupa\".','pengumpulan','http://127.0.0.1:8000/kelas/5/tugas/10',NULL,'2026-06-15 11:52:38','2026-06-15 11:52:38');
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
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
INSERT INTO `password_reset_tokens` VALUES ('fian@lms.com','$2y$12$CyPj2NVuf6gBzcMpwZp66.YP5VovnVFJMoG.WZuTiB7eGO3IlHY22','2026-06-09 14:14:01');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengumpulan_tugas`
--

DROP TABLE IF EXISTS `pengumpulan_tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumpulan_tugas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tugas_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `file_jawaban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `dikumpulkan_at` datetime DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `status` enum('belum','terkumpul','dinilai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengumpulan_tugas_tugas_id_siswa_id_unique` (`tugas_id`,`siswa_id`),
  KEY `pengumpulan_tugas_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `pengumpulan_tugas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengumpulan_tugas_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengumpulan_tugas`
--

LOCK TABLES `pengumpulan_tugas` WRITE;
/*!40000 ALTER TABLE `pengumpulan_tugas` DISABLE KEYS */;
INSERT INTO `pengumpulan_tugas` VALUES (1,1,11,'dummy_jawaban_11.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',99.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(2,1,12,'dummy_jawaban_12.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',70.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(3,1,13,'dummy_jawaban_13.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',60.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(4,1,14,'dummy_jawaban_14.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',NULL,NULL,'terkumpul','2026-06-08 10:51:38','2026-06-08 10:51:38'),(5,1,15,'dummy_jawaban_15.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',NULL,NULL,'terkumpul','2026-06-08 10:51:38','2026-06-08 10:51:38'),(6,2,11,'dummy_jawaban_11.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',90.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(7,2,12,'dummy_jawaban_12.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',10.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(8,2,13,'dummy_jawaban_13.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',80.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(9,2,14,'dummy_jawaban_14.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',NULL,NULL,'terkumpul','2026-06-08 10:51:38','2026-06-08 10:51:38'),(10,2,15,'dummy_jawaban_15.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',NULL,NULL,'terkumpul','2026-06-08 10:51:38','2026-06-08 10:51:38'),(11,3,11,'dummy_jawaban_11.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',100.00,'kocak','dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(12,3,12,'dummy_jawaban_12.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',100.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(13,3,13,'dummy_jawaban_13.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',85.00,NULL,'dinilai','2026-06-08 10:51:38','2026-06-10 16:17:20'),(14,3,14,'dummy_jawaban_14.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',NULL,NULL,'terkumpul','2026-06-08 10:51:38','2026-06-08 10:51:38'),(15,3,15,'dummy_jawaban_15.pdf','Pengumpulan dummy untuk testing','2026-06-08 17:51:38',NULL,NULL,'terkumpul','2026-06-08 10:51:38','2026-06-08 10:51:38'),(16,1,34,'tugas/1/7okba27C3qCxgfVOshWcsaP8n28lzGF5RAMblix2.pdf','4t54g','2026-06-09 16:27:32',100.00,'referg','dinilai','2026-06-09 09:26:56','2026-06-10 16:17:20'),(17,3,34,'tugas/3/W0FgLbNumKuoHS9SM7P0U4alGCIn6ErimPuDHsE7.docx','kljjkj','2026-06-10 16:02:34',90.00,'semangat','dinilai','2026-06-10 09:01:43','2026-06-10 16:17:20'),(18,4,34,'tugas/4/v17QRQvGtDPo2LybykLwZGYyjkzbYRQtQ6ep7Mps.pdf','fdgaseg','2026-06-10 22:16:24',NULL,NULL,'terkumpul','2026-06-10 15:16:24','2026-06-10 15:16:24'),(19,4,11,NULL,NULL,NULL,5.00,NULL,'dinilai','2026-06-10 16:07:45','2026-06-10 16:17:20'),(20,7,34,'tugas/7/uBfH8deY146odAr4s1wj0YsDedBxOOM9TCBLjSxF.pdf','mantap','2026-06-10 23:11:59',NULL,NULL,'terkumpul','2026-06-10 16:11:59','2026-06-10 16:11:59'),(21,6,34,'tugas/6/IKFH0hVL3Iq5L1h5xzPgyRvvqwuhkLZwY1i4CwUf.pdf','fghd','2026-06-10 23:14:21',NULL,NULL,'terkumpul','2026-06-10 16:14:12','2026-06-10 16:14:21'),(22,4,12,NULL,NULL,NULL,20.00,NULL,'dinilai','2026-06-10 16:17:20','2026-06-10 16:17:20'),(23,6,12,NULL,NULL,NULL,60.00,NULL,'dinilai','2026-06-10 16:17:20','2026-06-10 16:17:20'),(24,7,12,NULL,NULL,NULL,70.00,NULL,'dinilai','2026-06-10 16:17:20','2026-06-10 16:17:20'),(25,8,34,'tugas/8/PpN1tFX2LjAVqOqP4M9kTgnDx7VgrIcGXNy3Twtn.pdf','mantap','2026-06-10 23:41:57',NULL,NULL,'terkumpul','2026-06-10 16:41:47','2026-06-10 16:41:57'),(26,9,37,'tugas/9/cI4dRB5dGklWF4q2AM9PGEUSlcZL7qTfWfdXw9PQ.pdf','gampang bu','2026-06-11 00:20:08',80.00,NULL,'dinilai','2026-06-10 17:20:08','2026-06-10 17:36:08'),(27,10,33,'tugas/10/zMhAZEfUgf3begMuQZPNITM4hp1jcGhwJJKX3kap.docx','mantap ibu gampang','2026-06-15 18:52:45',NULL,NULL,'belum','2026-06-15 11:52:38','2026-06-15 11:52:45');
/*!40000 ALTER TABLE `pengumpulan_tugas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pertemuan`
--

DROP TABLE IF EXISTS `pertemuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pertemuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urutan` int NOT NULL DEFAULT '1',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pertemuan_kelas_id_foreign` (`kelas_id`),
  KEY `pertemuan_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  CONSTRAINT `pertemuan_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pertemuan_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pertemuan`
--

LOCK TABLES `pertemuan` WRITE;
/*!40000 ALTER TABLE `pertemuan` DISABLE KEYS */;
INSERT INTO `pertemuan` VALUES (4,4,4,'Pertemuan 1 - Pengenalan Aljabar',1,'Pengenalan konsep aljabar dasar','2026-06-08','2026-06-08 10:05:16','2026-06-08 10:05:16'),(7,4,5,'knm',1,'nmnm','2026-06-10','2026-06-10 09:54:38','2026-06-10 09:54:38'),(9,4,4,'seni rupa',2,'awdsdf','2026-06-23','2026-06-10 14:37:45','2026-06-10 16:24:37'),(10,4,4,'linear',3,'linear bla bla','2026-06-15','2026-06-10 16:00:59','2026-06-10 16:24:47'),(11,4,4,'Materi Aljabar Dasar',4,'sfdgd','2026-06-16','2026-06-10 16:29:06','2026-06-10 16:29:06'),(12,7,4,'tugas 1',1,'safe','2026-06-09','2026-06-10 17:08:33','2026-06-10 17:08:33'),(13,4,5,'linear',2,'scsd','2026-06-12','2026-06-10 17:41:59','2026-06-10 17:42:11'),(14,5,8,'seni rupa',1,'seni rupa merupakan','2026-06-10','2026-06-15 11:42:36','2026-06-15 11:42:36');
/*!40000 ALTER TABLE `pertemuan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (10,'guru','web','2026-06-08 10:01:43','2026-06-08 10:01:43'),(11,'siswa','web','2026-06-08 11:00:37','2026-06-08 11:00:37'),(12,'admin','web','2026-06-08 12:01:32','2026-06-08 12:01:32');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('lms_name','MA PATIKRAJA Learning System','2026-06-08 09:32:12','2026-06-09 13:20:49'),('school_name','MA AMANAH PATIIKRAJA','2026-06-08 09:32:12','2026-06-10 14:06:44');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `soal_kuis`
--

DROP TABLE IF EXISTS `soal_kuis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `soal_kuis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kuis_id` bigint unsigned NOT NULL,
  `pertanyaan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` enum('pilihan_ganda','benar_salah','isian_singkat') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_jawaban` json DEFAULT NULL,
  `kunci_jawaban` text COLLATE utf8mb4_unicode_ci,
  `poin` int NOT NULL DEFAULT '10',
  `urutan` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soal_kuis_kuis_id_foreign` (`kuis_id`),
  CONSTRAINT `soal_kuis_kuis_id_foreign` FOREIGN KEY (`kuis_id`) REFERENCES `kuis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `soal_kuis`
--

LOCK TABLES `soal_kuis` WRITE;
/*!40000 ALTER TABLE `soal_kuis` DISABLE KEYS */;
INSERT INTO `soal_kuis` VALUES (8,3,'apa?',NULL,'pilihan_ganda','{\"a\": \"a\", \"b\": \"c\", \"c\": \"b\", \"d\": \"d\"}','d',100,1,'2026-06-09 09:24:27','2026-06-09 09:24:27'),(9,3,'kljklkjklj',NULL,'benar_salah','{\"a\": null, \"b\": null, \"c\": null, \"d\": null}','salah',10,2,'2026-06-10 09:05:20','2026-06-10 09:05:20'),(10,4,'linear terdiri dari','soal/w9nUoPxRoqcWt76vRvSlh78JU5qWyP7eiI32rKVB.jpg','pilihan_ganda','{\"a\": \"ucang\", \"b\": \"noor\", \"c\": \"alam\", \"d\": \"fauzan\"}','c',10,1,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(11,4,'vdx','soal/euQoASPWrBqdIMFQVLQCGDCjh8sf8M9pzoPufWaY.jpg','pilihan_ganda','{\"a\": \"dsfsf\", \"b\": \"dsdfeg\", \"c\": \"ujg\", \"d\": \"uhjk\"}','b',10,2,'2026-06-10 15:54:31','2026-06-10 15:54:31'),(12,5,'pengertian aljabar?',NULL,'pilihan_ganda','{\"a\": \"ucang\", \"b\": \"noor\", \"c\": \"alam\", \"d\": \"fauzan\"}','c',10,1,'2026-06-10 16:06:14','2026-06-10 16:06:14'),(13,6,'apa ibu kota indonesia?','soal/5oxEkTSQo3wIbz6T20bxkUoHvfm76FJy66bFlosn.jpg','pilihan_ganda','{\"a\": \"ucang\", \"b\": \"noor\", \"c\": \"alam\", \"d\": \"fauzan\"}','d',10,1,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(14,6,'pengertian aljabar',NULL,'pilihan_ganda','{\"a\": \"apa\", \"b\": \"siapa\", \"c\": \"kamu\", \"d\": \"kenapa\"}','c',10,2,'2026-06-10 16:36:33','2026-06-10 16:36:33'),(15,7,'asal usul aljabar?','soal/haoL2kF1Mp1Fl0m0ORbi2g6FLhr7bOMF9uoBnFAo.jpg','pilihan_ganda','{\"a\": \"apa\", \"b\": \"siapa\", \"c\": \"kamu\", \"d\": \"kenapa\"}','b',10,1,'2026-06-10 17:16:39','2026-06-10 17:16:39'),(16,7,'ibu kota jakarata','soal/Yujz8hqDFLBEuePEpZivfuUQpv48DRnRwpCdATQX.jpg','pilihan_ganda','{\"a\": \"jakarta\", \"b\": \"makassar\", \"c\": \"kalimantan\", \"d\": \"bali\"}','a',10,2,'2026-06-10 17:16:39','2026-06-10 17:16:39'),(17,8,'sdcnksc','soal/JV77WopnjROugqECAYFWai8sqALORG6pWzm8KhFn.jpg','pilihan_ganda','{\"a\": \"ucang\", \"b\": \"B\", \"c\": \"cabang seni rupa menghasilkan\", \"d\": \"fauzan\"}','c',10,1,'2026-06-10 17:31:55','2026-06-10 17:31:55'),(18,9,'szdcsdv','soal/G4m0ilbYEDfClnX4wTA8uX13UAnxWbdC5Oq1EdB3.jpg','pilihan_ganda','{\"a\": \"ucang\", \"b\": \"B\", \"c\": \"C\", \"d\": \"d\"}','a',10,1,'2026-06-10 17:37:55','2026-06-10 17:37:55'),(19,10,'sddddvs','soal/dz81NebJ7l9db7jCnM0On5NjjmZSlVRrrinqi2Kx.jpg','pilihan_ganda','{\"a\": \"A\", \"b\": \"B\", \"c\": \"alam\", \"d\": \"a\"}','c',10,1,'2026-06-10 17:42:56','2026-06-10 17:42:56'),(20,11,'seni rupa berasal dari mna?','soal/FXfdWLIpKgCdKVzNVwKIcJv6B7lDDIimtSMFr78l.png','pilihan_ganda','{\"a\": \"jakarta\", \"b\": \"bandung\", \"c\": \"makassar\", \"d\": \"surabaya\"}','c',10,1,'2026-06-15 11:49:25','2026-06-15 11:49:57');
/*!40000 ALTER TABLE `soal_kuis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tahun_ajaran`
--

DROP TABLE IF EXISTS `tahun_ajaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tahun_ajaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tahun_ajaran_tahun_ajaran_unique` (`tahun_ajaran`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tahun_ajaran`
--

LOCK TABLES `tahun_ajaran` WRITE;
/*!40000 ALTER TABLE `tahun_ajaran` DISABLE KEYS */;
INSERT INTO `tahun_ajaran` VALUES (2,'jdsan2026',0,'2026-06-09 13:19:59','2026-06-10 16:58:51'),(3,'2016/2017',0,'2026-06-10 09:17:30','2026-06-10 16:58:51'),(4,'2025/2026',0,'2026-06-10 13:56:21','2026-06-10 16:58:51'),(5,'2030?/2031',1,'2026-06-10 16:58:45','2026-06-10 16:58:51');
/*!40000 ALTER TABLE `tahun_ajaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tugas`
--

DROP TABLE IF EXISTS `tugas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tugas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kelas_id` bigint unsigned NOT NULL,
  `mata_pelajaran_id` bigint unsigned NOT NULL,
  `guru_id` bigint unsigned NOT NULL,
  `pertemuan_id` bigint unsigned DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `deadline` datetime NOT NULL,
  `nilai_maksimum` int NOT NULL DEFAULT '100',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tugas_kelas_id_foreign` (`kelas_id`),
  KEY `tugas_mata_pelajaran_id_foreign` (`mata_pelajaran_id`),
  KEY `tugas_guru_id_foreign` (`guru_id`),
  KEY `tugas_pertemuan_id_foreign` (`pertemuan_id`),
  CONSTRAINT `tugas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugas_mata_pelajaran_id_foreign` FOREIGN KEY (`mata_pelajaran_id`) REFERENCES `mata_pelajaran` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tugas_pertemuan_id_foreign` FOREIGN KEY (`pertemuan_id`) REFERENCES `pertemuan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tugas`
--

LOCK TABLES `tugas` WRITE;
/*!40000 ALTER TABLE `tugas` DISABLE KEYS */;
INSERT INTO `tugas` VALUES (1,4,4,10,4,'Tugas 1 - Soal Aljabar','Kerjakan soal aljabar pada halaman 12-13','2026-06-15 17:05:16',100,NULL,'2026-06-08 10:05:16','2026-06-08 10:05:16'),(2,4,4,10,NULL,'asd','asd','2026-06-09 00:16:00',100,NULL,'2026-06-08 10:16:42','2026-06-08 10:16:42'),(3,4,4,10,4,'tugas 2','asd','2026-06-11 00:47:00',100,'tugas/attachments/IVoD55f9hjkEvpban5dYGDUgEWiTMIHwPsBabq5l.pdf','2026-06-08 10:48:27','2026-06-10 09:01:22'),(4,4,4,10,4,'persamaan linear','dfzdvs','2026-06-12 04:42:00',100,'tugas/attachments/okhTpeyVFvU9iDMDoGShXzHTwycaRYKTexngoVDd.docx','2026-06-10 14:43:05','2026-06-10 14:43:05'),(6,4,4,10,9,'linear','pengertian linear','2026-06-12 08:53:00',100,'tugas/attachments/bBzgNKFtVAd14Yca22yTexDBDq6nYHkVLGNSpjuS.pdf','2026-06-10 15:50:33','2026-06-10 15:50:33'),(7,4,4,10,10,'linear adalah','kerjakan dengan teliti','2026-06-27 06:03:00',100,'tugas/attachments/fTGsjtoYLoe4RgtyGaRefogIZSt1qG3ykK8HeRVr.pdf','2026-06-10 16:03:32','2026-06-10 16:03:32'),(8,4,4,10,11,'Pengertian linear','kerjakan dengan teliti','2026-06-17 06:31:00',100,'tugas/attachments/epMCguBE6GdRXKslxUo7XLRkQJcSzcXJyv11TwD6.pdf','2026-06-10 16:31:25','2026-06-10 16:31:25'),(9,7,4,36,12,'Materi Aljabar Dasar','kerjakan dengan teliti','2026-06-11 07:10:00',100,'tugas/attachments/A0omdVa7SIWrZsXflPBAcQfyw6mb0cc7YdO4Oux0.pdf','2026-06-10 17:11:09','2026-06-10 17:11:09'),(10,5,8,35,14,'bab 1 pengenalan seni rupa','kerjakan','2026-06-24 01:46:00',100,'tugas/attachments/3BIqLX4bDQURaacYaeoGHZGxZCPGiM84eWCxA66I.pdf','2026-06-15 11:46:25','2026-06-15 11:46:25');
/*!40000 ALTER TABLE `tugas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (10,'Alam','alam@lms.com',NULL,'2026-06-08 10:03:42','$2y$12$HkrlJh7MfslR9ioYUUZyhO01ggdtAU4V608Mlgsox/5NLl7r8/AhC','wFnDDLniJ9VupLnVF0VyEIdN1OYLA8NdWoAocUcJJN3LnWaFMpnHGvSWUxfZ','2026-06-08 10:01:44','2026-06-08 10:03:42'),(11,'Siswa 1','siswa1@example.com','avatars/rGCNgf5kDgufgyYfhFGEmZ8hRYN4PFDmELq3FRiD.png',NULL,'$2y$12$/Ds0o4T0DTNuHZFbaqzFVuyggq9KIVE52HiUIkNzw7jhnMgII.EcG',NULL,'2026-06-08 10:23:29','2026-06-08 12:14:39'),(12,'Siswa 2','siswa2@example.com',NULL,NULL,'$2y$12$zwOUopCCASY5dAOMEvd3yOjzNN2I9Au0oLzuk.ymT0nW/d75fYi/m',NULL,'2026-06-08 10:23:29','2026-06-08 10:58:15'),(13,'Siswa 3','siswa3@example.com',NULL,NULL,'$2y$12$CJ5aIgHVbN8W9R4gDedNl.nVCGwq06p5Lw/I7pOi7hZ0wNjAMQl.q',NULL,'2026-06-08 10:23:29','2026-06-08 10:58:15'),(14,'Siswa 4','siswa4@example.com',NULL,NULL,'$2y$12$1oIihFmAAtqYGDtp5JnMHOvnMwKhDl60KmEcsyTV01ifbTyGR.zkm',NULL,'2026-06-08 10:23:29','2026-06-08 10:58:15'),(15,'Siswa 5','siswa5@example.com',NULL,NULL,'$2y$12$RmRYw1ngBHOB3Bm.bRmFn.g1.O2LHqgw.oTiiq/5zv9X55tUUT7Im',NULL,'2026-06-08 10:23:29','2026-06-08 10:58:15'),(16,'Adminh','admin@lms.com','avatars/sbSFjRD67DU6dVwPy08GAkjxkrXh0zw22o8Mg4bN.jpg',NULL,'$2y$12$rz7C2eJuweaAdxlR3uAcIeKqTj89efR5tFWKzh4RJCEXfj6zBBvv6',NULL,'2026-06-08 12:01:32','2026-06-10 09:49:52'),(17,'edgar','edgar@lms.com',NULL,NULL,'$2y$12$Az97.q/UW9S9ffbmiIXuc.HX0FQPWK/d8fQHDVLoqLo1D/ra5aFIO',NULL,'2026-06-08 12:02:55','2026-06-08 12:02:55'),(18,'Guru 1','guru1@lms.com',NULL,NULL,'$2y$12$MI0zVSHddxy9IqPCN/5NBOYKC/3Pl42fjs9OuwXDWX1JL9gnOn9w6','cKAnHRwrs6','2026-06-08 12:11:37','2026-06-08 12:11:37'),(19,'Guru 2','guru2@lms.com',NULL,NULL,'$2y$12$./.eTeuQJwlouBIjn8eRFemltqVaNVQHUn.cuYjQmQrOf7jB9rVwK','z9XgEsanlk','2026-06-08 12:11:38','2026-06-08 12:11:38'),(20,'Guru 3','guru3@lms.com',NULL,NULL,'$2y$12$xGwDb4cShIj9Qb6O1/unGO.JzojzNwD7qHAp/kVgYnlT6ACYwJjYy','wlPTj9cbgV','2026-06-08 12:11:38','2026-06-08 12:11:38'),(21,'Siswa 1','siswa1@lms.com',NULL,NULL,'$2y$12$wLYMyT/wUvE2H1lNmxf6KenWQH2GY/eM4DeadCQMJ.qKSUIQ09zm2','9WrpD2y5HM','2026-06-08 12:11:38','2026-06-08 12:11:38'),(22,'Siswa 2','siswa2@lms.com',NULL,NULL,'$2y$12$OUDVJR2zyE2Rdy7FSYQ32OViW0sMUnxMvdy0krb0miUX2NTIZFpLC','5UDfFgi6ta','2026-06-08 12:11:39','2026-06-08 12:11:39'),(23,'Siswa 3','siswa3@lms.com',NULL,NULL,'$2y$12$1CK8uRcxHH7Af.DsmOEAiuQnX5AFbNlq.XiKZq5vDKYkD6NpCbwom','9KhYgMmOa6','2026-06-08 12:11:39','2026-06-08 12:11:39'),(24,'Siswa 4','siswa4@lms.com',NULL,NULL,'$2y$12$HANrRGX.lm32KylPHxOId.Wr1i83sqOcTbQBRsHNSPn2BTZbF4C4O','ZsWCHieOyP','2026-06-08 12:11:39','2026-06-08 12:11:39'),(25,'Siswa 5','siswa5@lms.com',NULL,NULL,'$2y$12$AAl7.ohOL26idZULF1bSB.G6iuq7cKqqQCt4Zw7n8tDJvFv56grZy','Srg1QO5lzA','2026-06-08 12:11:40','2026-06-08 12:11:40'),(26,'Siswa 6','siswa6@lms.com',NULL,NULL,'$2y$12$e7h5XQ/VRczUKtebRXgYBegmkkJaH5F785rej.d.b3hD3k5/oWTKK','q3FMvKPwKA','2026-06-08 12:11:40','2026-06-08 12:11:40'),(27,'Siswa 7','siswa7@lms.com',NULL,NULL,'$2y$12$qb.O9XjUFH4Ubat6i8cf7ur6JxYehvIM3RCOJTdvWQ8wkhYAmUbf2','RFOAWzEK3p','2026-06-08 12:11:40','2026-06-08 12:11:40'),(28,'Siswa 8','siswa8@lms.com',NULL,NULL,'$2y$12$XYSaaHj2cPkiA22PJvgVMOrLH9ZZwQYoJ1mYBLLDyBM6MvNyJPFp.','DU3vVUZZ7w','2026-06-08 12:11:41','2026-06-08 12:11:41'),(29,'Siswa 9','siswa9@lms.com',NULL,NULL,'$2y$12$DILei0XG13SdOYrLVSbRq.0.mgzPlk.xiZImUTwgSzB8uOLLRYa3G','UZs2uY56pd','2026-06-08 12:11:41','2026-06-08 12:11:41'),(30,'Siswa 10','siswa10@lms.com',NULL,NULL,'$2y$12$bERRLcgSIY/egeT73cpIIeFhe9MyZ9LVZAG9B3I1eiwt7J/o0ejtq','IuWo1bvG08','2026-06-08 12:11:41','2026-06-08 12:11:41'),(32,'Siswa 12','siswa12@lms.com',NULL,NULL,'$2y$12$3Bfv2vxd6Xqj5v.cVrERzup0NPr8ahQIGIA5jQAJRJoWkbkcDS63i','sEeMUWGpTk','2026-06-08 12:11:42','2026-06-08 12:11:42'),(33,'upik','upik@lms.com',NULL,NULL,'$2y$12$7vnKochKPjp.BeVb2jzs6.6bin3Elktk89IqLD6f9MDTV1itGHnZK',NULL,'2026-06-08 12:28:16','2026-06-08 12:28:16'),(34,'fian','fian@lms.com',NULL,NULL,'$2y$12$TTLOwCkUHiy2ziFNXfO3juO04yxMpUb7V7ks/KtGQuIlJxGEkKNX2','p3pMmhVGv2nGf9o8pUFFeGsLeF3LNynLo57jm49lTVmZrSvwsjSPcjpYTqcP','2026-06-09 09:06:20','2026-06-15 10:35:08'),(35,'taufik S.pd','taufik@lms.com',NULL,NULL,'$2y$12$K8B0oxe07IjeoJAE135y5OdXVhPoi9rfV70JfU8OSVqhm9nPYpMuK',NULL,'2026-06-10 13:55:13','2026-06-10 13:55:13'),(36,'septiana S.pd','septiana@lms.com',NULL,NULL,'$2y$12$lVzucdwA0viFtMFtpJ2HWOWqVlicEQvV1hfj5q/qEzqxgMMVykq3O',NULL,'2026-06-10 16:57:27','2026-06-10 16:57:27'),(37,'wahyu','wahyu@lms.com',NULL,NULL,'$2y$12$PUBB2yf6GDrOpV9g1qj/MOj2f/hBr9CWxWeepML3l/mM8U4l2ngbm',NULL,'2026-06-10 16:58:13','2026-06-10 16:58:13'),(38,'maryona','maryona@lms.com',NULL,NULL,'$2y$12$R60QPppQbI06blMtMLkPpeWBUJk2kN2jSOgEa9tm9ZjbM31eohDBC',NULL,'2026-06-10 22:33:20','2026-06-10 22:33:20'),(39,'edgar','edgarndf@lms.com',NULL,NULL,'$2y$12$bKMCW/.u7h1K9qgZJzXHreq.bkuzv03NUTbDuUknefwxxhwV50mr6',NULL,'2026-06-15 10:33:43','2026-06-15 10:33:43');
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

-- Dump completed on 2026-06-28  1:21:14
