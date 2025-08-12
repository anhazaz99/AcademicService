-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for academic_service
CREATE DATABASE IF NOT EXISTS `academic_service` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `academic_service`;

-- Dumping structure for table academic_service.diem_sinh_vien
CREATE TABLE IF NOT EXISTS `diem_sinh_vien` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sinh_vien_id` bigint unsigned NOT NULL,
  `mon_hoc_id` bigint unsigned NOT NULL,
  `diem_TX` double NOT NULL,
  `lan_thi` int NOT NULL DEFAULT '1',
  `ngay_thi` date DEFAULT NULL,
  `diem_DK` double DEFAULT NULL,
  `diem_thi` double DEFAULT NULL,
  `diemTB` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diem_sinh_vien_sinh_vien_id_foreign` (`sinh_vien_id`),
  KEY `diem_sinh_vien_mon_hoc_id_foreign` (`mon_hoc_id`),
  CONSTRAINT `diem_sinh_vien_mon_hoc_id_foreign` FOREIGN KEY (`mon_hoc_id`) REFERENCES `mon_hocs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `diem_sinh_vien_sinh_vien_id_foreign` FOREIGN KEY (`sinh_vien_id`) REFERENCES `sinh_viens` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.giao_viens
CREATE TABLE IF NOT EXISTS `giao_viens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ho_ten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gioi_tinh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `khoa_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `giao_viens_user_id_foreign` (`user_id`),
  KEY `giao_viens_khoa_id_foreign` (`khoa_id`),
  CONSTRAINT `giao_viens_khoa_id_foreign` FOREIGN KEY (`khoa_id`) REFERENCES `khoas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `giao_viens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.khoas
CREATE TABLE IF NOT EXISTS `khoas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ten_khoa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.lich_hocs
CREATE TABLE IF NOT EXISTS `lich_hocs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mon_hoc_id` bigint unsigned NOT NULL,
  `lop_id` bigint unsigned NOT NULL,
  `phong_id` bigint unsigned NOT NULL,
  `ngay_hoc` date NOT NULL,
  `tiet_bat_dau` int NOT NULL,
  `so_tiet` int NOT NULL,
  `giao_vien_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lich_hocs_mon_hoc_id_foreign` (`mon_hoc_id`),
  KEY `lich_hocs_lop_id_foreign` (`lop_id`),
  KEY `lich_hocs_phong_id_foreign` (`phong_id`),
  KEY `lich_hocs_giao_vien_id_foreign` (`giao_vien_id`),
  CONSTRAINT `lich_hocs_giao_vien_id_foreign` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_viens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lich_hocs_lop_id_foreign` FOREIGN KEY (`lop_id`) REFERENCES `lops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lich_hocs_mon_hoc_id_foreign` FOREIGN KEY (`mon_hoc_id`) REFERENCES `mon_hocs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lich_hocs_phong_id_foreign` FOREIGN KEY (`phong_id`) REFERENCES `phongs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.lops
CREATE TABLE IF NOT EXISTS `lops` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ten_lop` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `khoa_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lops_khoa_id_foreign` (`khoa_id`),
  CONSTRAINT `lops_khoa_id_foreign` FOREIGN KEY (`khoa_id`) REFERENCES `khoas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.mon_hocs
CREATE TABLE IF NOT EXISTS `mon_hocs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ten_mon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_tin_chi` int NOT NULL,
  `khoa_id` bigint unsigned NOT NULL,
  `giao_vien_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mon_hocs_khoa_id_foreign` (`khoa_id`),
  KEY `mon_hocs_giao_vien_id_foreign` (`giao_vien_id`),
  CONSTRAINT `mon_hocs_giao_vien_id_foreign` FOREIGN KEY (`giao_vien_id`) REFERENCES `giao_viens` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mon_hocs_khoa_id_foreign` FOREIGN KEY (`khoa_id`) REFERENCES `khoas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.phongs
CREATE TABLE IF NOT EXISTS `phongs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ma_phong` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_cho_ngoi` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.sinh_viens
CREATE TABLE IF NOT EXISTS `sinh_viens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ho_ten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `gioi_tinh` enum('Nam','Nu') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lop_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ma_sv` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sdt` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sinh_viens_user_id_foreign` (`user_id`),
  KEY `sinh_viens_lop_id_foreign` (`lop_id`),
  CONSTRAINT `sinh_viens_lop_id_foreign` FOREIGN KEY (`lop_id`) REFERENCES `lops` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sinh_viens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table academic_service.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','sinhvien','giaovien') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sinhvien',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
