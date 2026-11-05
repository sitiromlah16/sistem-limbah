/*
 Navicat Premium Dump SQL

 Source Server         : DBkiran
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : db_limbah

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 30/10/2026 09:00:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for detail_waste_shifts
-- ----------------------------
DROP TABLE IF EXISTS `detail_waste_shifts`;
CREATE TABLE `detail_waste_shifts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `laporan_produksi_id` bigint UNSIGNED NOT NULL,
  `kategori_waste_id` bigint UNSIGNED NOT NULL,
  `lane_id` bigint UNSIGNED NULL DEFAULT NULL,
  `volume_kg` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `detail_waste_shifts_laporan_produksi_id_foreign`(`laporan_produksi_id` ASC) USING BTREE,
  INDEX `detail_waste_shifts_kategori_waste_id_foreign`(`kategori_waste_id` ASC) USING BTREE,
  INDEX `detail_waste_shifts_lane_id_foreign`(`lane_id` ASC) USING BTREE,
  CONSTRAINT `detail_waste_shifts_kategori_waste_id_foreign` FOREIGN KEY (`kategori_waste_id`) REFERENCES `master_kategori_wastes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `detail_waste_shifts_lane_id_foreign` FOREIGN KEY (`lane_id`) REFERENCES `master_trading_lanes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `detail_waste_shifts_laporan_produksi_id_foreign` FOREIGN KEY (`laporan_produksi_id`) REFERENCES `laporan_produksi_shifts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of detail_waste_shifts
-- ----------------------------
INSERT INTO `detail_waste_shifts` VALUES (1, 1, 1, NULL, 120.50, '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `detail_waste_shifts` VALUES (2, 1, 2, NULL, 95.00, '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `detail_waste_shifts` VALUES (3, 1, 3, NULL, 80.20, '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `detail_waste_shifts` VALUES (4, 2, 1, NULL, 140.00, '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `detail_waste_shifts` VALUES (5, 2, 2, NULL, 110.50, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (6, 2, 3, NULL, 95.00, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (7, 3, 1, NULL, 115.00, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (8, 3, 2, NULL, 130.00, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (9, 3, 3, NULL, 125.40, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (10, 4, 1, NULL, 110.00, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (11, 4, 2, NULL, 88.50, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (12, 4, 3, NULL, 75.00, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `detail_waste_shifts` VALUES (13, 5, 1, NULL, 130.00, '2026-10-12 02:10:14', '2026-10-12 03:14:20');
INSERT INTO `detail_waste_shifts` VALUES (14, 5, 2, NULL, 450.00, '2026-10-12 02:10:14', '2026-10-12 03:14:20');
INSERT INTO `detail_waste_shifts` VALUES (15, 5, 3, NULL, 90.00, '2026-10-12 02:10:14', '2026-10-12 03:14:20');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for laporan_produksi_shifts
-- ----------------------------
DROP TABLE IF EXISTS `laporan_produksi_shifts`;
CREATE TABLE `laporan_produksi_shifts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `shift` int NOT NULL,
  `total_output_kg` decimal(12, 2) NOT NULL,
  `status_validasi` enum('Pending','Validated','Revisi') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Pending',
  `catatan_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `laporan_produksi_shifts_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `laporan_produksi_shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of laporan_produksi_shifts
-- ----------------------------
INSERT INTO `laporan_produksi_shifts` VALUES (1, 1, '2026-10-10', 1, 15000.00, 'Validated', 'Data sesuai standar toleransi limbah shift pagi.', '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `laporan_produksi_shifts` VALUES (2, 1, '2026-10-10', 2, 14200.00, 'Validated', 'Tervalidasi.', '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `laporan_produksi_shifts` VALUES (3, 1, '2026-10-11', 3, 13500.00, 'Validated', 'Tervalidasi, waste stripping sedikit naik di malam hari.', '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `laporan_produksi_shifts` VALUES (4, 1, '2026-10-12', 1, 16000.00, 'Pending', NULL, '2026-10-12 02:10:14', '2026-10-12 02:10:14');
INSERT INTO `laporan_produksi_shifts` VALUES (5, 1, '2026-10-12', 2, 14000.00, 'Revisi', 'sesuaikan kembali dengan tradingnya', '2026-10-12 02:10:14', '2026-10-27 04:22:33');

-- ----------------------------
-- Table structure for master_kategori_wastes
-- ----------------------------
DROP TABLE IF EXISTS `master_kategori_wastes`;
CREATE TABLE `master_kategori_wastes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `rincian_per_lane` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_kategori_wastes
-- ----------------------------
INSERT INTO `master_kategori_wastes` VALUES (1, 'Trading', 'Limbah jenis Trading', 1, 1, '2026-10-12 02:10:13', '2026-10-12 03:33:03');
INSERT INTO `master_kategori_wastes` VALUES (2, 'Cutting', 'Limbah jenis Cutting', 1, 0, '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `master_kategori_wastes` VALUES (3, 'Stripping', 'Limbah jenis Stripping', 1, 0, '2026-10-12 02:10:13', '2026-10-12 02:10:13');

-- ----------------------------
-- Table structure for master_trading_lanes
-- ----------------------------
DROP TABLE IF EXISTS `master_trading_lanes`;
CREATE TABLE `master_trading_lanes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_lane` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `master_trading_lanes_nama_lane_unique`(`nama_lane` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_trading_lanes
-- ----------------------------
INSERT INTO `master_trading_lanes` VALUES (1, 'Lane A', 1, '2026-10-12 03:33:04', '2026-10-12 03:33:04');
INSERT INTO `master_trading_lanes` VALUES (2, 'Lane B', 1, '2026-10-12 03:33:04', '2026-10-12 03:33:04');
INSERT INTO `master_trading_lanes` VALUES (3, 'Lane C', 1, '2026-10-12 03:33:04', '2026-10-12 03:33:04');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2026_10_11_000001_create_master_kategori_wastes_table', 1);
INSERT INTO `migrations` VALUES (5, '2026_10_11_000002_create_laporan_produksi_shifts_table', 1);
INSERT INTO `migrations` VALUES (6, '2026_10_11_000003_create_detail_waste_shifts_table', 1);
INSERT INTO `migrations` VALUES (7, '2026_10_11_000004_create_sessions_table', 2);
INSERT INTO `migrations` VALUES (8, '2026_10_12_000005_add_trading_lanes_to_waste_details', 3);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'operator',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Budi Operator', 'operator@pabrik.com', NULL, '$2y$12$UQEjAp43WioK0oQDANStV.q93xniAky1WLjksmW1iiJ4jFFJY.hK2', 'operator', NULL, '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `users` VALUES (2, 'Siti Admin', 'admin@pabrik.com', NULL, '$2y$12$FlVpjnIDcOU34hdfVVUCW.McCXCiPQ0rPX7o0gn1Q2ixXE.R5iCkq', 'admin', NULL, '2026-10-12 02:10:13', '2026-10-12 02:10:13');
INSERT INTO `users` VALUES (3, 'Andi Manajer', 'manajer@pabrik.com', NULL, '$2y$12$rqAs0W5AYrcMdNTf5dhE6ujg59m8YLIMKu82qP8RFZaErerIO6/fy', 'manajer', NULL, '2026-10-12 02:10:13', '2026-10-12 02:10:13');

SET FOREIGN_KEY_CHECKS = 1;
