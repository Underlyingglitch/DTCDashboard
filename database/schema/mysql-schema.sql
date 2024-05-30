/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(1023) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `synced` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calendar_item_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_item_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `calendar_item_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_item_user_calendar_item_id_foreign` (`calendar_item_id`),
  KEY `calendar_item_user_user_id_foreign` (`user_id`),
  CONSTRAINT `calendar_item_user_calendar_item_id_foreign` FOREIGN KEY (`calendar_item_id`) REFERENCES `calendar_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `calendar_item_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calendar_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `discipline` varchar(255) DEFAULT NULL,
  `district` varchar(255) NOT NULL,
  `place` varchar(255) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_address` varchar(255) DEFAULT NULL,
  `date_from` date NOT NULL,
  `date_to` date DEFAULT NULL,
  `results` text DEFAULT NULL,
  `results_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`results_files`)),
  `program` text DEFAULT NULL,
  `program_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`program_files`)),
  `description` text DEFAULT NULL,
  `description_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '[]' CHECK (json_valid(`description_files`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calendar_updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `calendar_item_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_updates_calendar_item_id_foreign` (`calendar_item_id`),
  CONSTRAINT `calendar_updates_calendar_item_id_foreign` FOREIGN KEY (`calendar_item_id`) REFERENCES `calendar_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `clubs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clubs` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `place` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `competition_trainer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competition_trainer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `competition_id` bigint(20) unsigned NOT NULL,
  `trainer_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `competition_trainer_competition_id_foreign` (`competition_id`),
  KEY `competition_trainer_trainer_id_foreign` (`trainer_id`),
  CONSTRAINT `competition_trainer_competition_id_foreign` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `competition_trainer_trainer_id_foreign` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `competitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `competitions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `declarations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `declarations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `match_day_id` bigint(20) unsigned NOT NULL,
  `jury_id` bigint(20) unsigned NOT NULL,
  `km` int(11) NOT NULL,
  `day_amount` int(11) NOT NULL,
  `iban` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `declarations_match_day_id_foreign` (`match_day_id`),
  KEY `declarations_jury_id_foreign` (`jury_id`),
  CONSTRAINT `declarations_jury_id_foreign` FOREIGN KEY (`jury_id`) REFERENCES `juries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `declarations_match_day_id_foreign` FOREIGN KEY (`match_day_id`) REFERENCES `match_days` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `loaded_page` varchar(255) DEFAULT NULL,
  `authenticated_user_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `last_seen` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dg_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dg_resources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `old_hash` varchar(255) DEFAULT NULL,
  `status` enum('idle','new','hasupdate','deleted') NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feedback_user_id_foreign` (`user_id`),
  CONSTRAINT `feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nr` int(11) NOT NULL,
  `baan` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gymnasts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gymnasts` (
  `id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthdate` varchar(255) NOT NULL,
  `photo` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `juries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `juries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `function` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `postal` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `club_id` bigint(20) unsigned DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `juries_club_id_foreign` (`club_id`),
  CONSTRAINT `juries_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `match_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `match_days` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `competition_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `location_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `match_days_competition_id_foreign` (`competition_id`),
  KEY `match_days_location_id_foreign` (`location_id`),
  CONSTRAINT `match_days_competition_id_foreign` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `match_days_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `niveau_wedstrijd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `niveau_wedstrijd` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wedstrijd_id` bigint(20) unsigned NOT NULL,
  `niveau_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `niveau_wedstrijd_wedstrijd_id_foreign` (`wedstrijd_id`),
  KEY `niveau_wedstrijd_niveau_id_foreign` (`niveau_id`),
  CONSTRAINT `niveau_wedstrijd_niveau_id_foreign` FOREIGN KEY (`niveau_id`) REFERENCES `niveaus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `niveau_wedstrijd_wedstrijd_id_foreign` FOREIGN KEY (`wedstrijd_id`) REFERENCES `wedstrijds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `niveaus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `niveaus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `supplement` varchar(255) NOT NULL,
  `niveau_number` int(11) DEFAULT NULL,
  `age_category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pending_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pending_changes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `operation` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `processed_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `processed_scores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wedstrijd_id` bigint(20) unsigned NOT NULL,
  `group_id` int(11) NOT NULL,
  `toestel` int(11) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `processed_scores_wedstrijd_id_foreign` (`wedstrijd_id`),
  CONSTRAINT `processed_scores_wedstrijd_id_foreign` FOREIGN KEY (`wedstrijd_id`) REFERENCES `wedstrijds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `match_day_id` bigint(20) unsigned NOT NULL,
  `gymnast_id` bigint(20) unsigned NOT NULL,
  `club_id` bigint(20) unsigned NOT NULL,
  `niveau_id` bigint(20) unsigned NOT NULL,
  `startnumber` int(11) NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned DEFAULT NULL,
  `signed_off` tinyint(1) NOT NULL DEFAULT 0,
  `place` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registrations_match_day_id_foreign` (`match_day_id`),
  KEY `registrations_gymnast_id_foreign` (`gymnast_id`),
  KEY `registrations_club_id_foreign` (`club_id`),
  KEY `registrations_niveau_id_foreign` (`niveau_id`),
  KEY `registrations_group_id_foreign` (`group_id`),
  KEY `registrations_team_id_foreign` (`team_id`),
  CONSTRAINT `registrations_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `registrations_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `registrations_gymnast_id_foreign` FOREIGN KEY (`gymnast_id`) REFERENCES `gymnasts` (`id`),
  CONSTRAINT `registrations_match_day_id_foreign` FOREIGN KEY (`match_day_id`) REFERENCES `match_days` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registrations_niveau_id_foreign` FOREIGN KEY (`niveau_id`) REFERENCES `niveaus` (`id`),
  CONSTRAINT `registrations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `score_corrections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `score_corrections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `startnumber` int(11) NOT NULL,
  `score_id` bigint(20) unsigned NOT NULL,
  `d` double(5,3) NOT NULL,
  `e1` double(5,3) NOT NULL,
  `e2` double(5,3) DEFAULT NULL,
  `e3` double(5,3) DEFAULT NULL,
  `n` double(5,3) NOT NULL DEFAULT 0.000,
  `total` double(5,3) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `score_corrections_score_id_foreign` (`score_id`),
  CONSTRAINT `score_corrections_score_id_foreign` FOREIGN KEY (`score_id`) REFERENCES `scores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `match_day_id` bigint(20) unsigned NOT NULL,
  `startnumber` int(11) NOT NULL,
  `toestel` int(11) NOT NULL,
  `d` double(5,3) NOT NULL,
  `e1` double(5,3) NOT NULL,
  `e2` double(5,3) DEFAULT NULL,
  `e3` double(5,3) DEFAULT NULL,
  `e` double(5,3) DEFAULT NULL,
  `n` double(5,3) NOT NULL,
  `total` double(5,3) NOT NULL,
  `place` int(11) DEFAULT NULL,
  `counted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scores_match_day_id_foreign` (`match_day_id`),
  CONSTRAINT `scores_match_day_id_foreign` FOREIGN KEY (`match_day_id`) REFERENCES `match_days` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sync_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sync_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `operation` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `synced` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `team_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_scores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `match_day_id` bigint(20) unsigned NOT NULL,
  `toestel_scores` varchar(255) NOT NULL DEFAULT '0,0,0,0,0,0',
  `total_score` double(6,3) NOT NULL DEFAULT 0.000,
  `place` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_scores_team_id_foreign` (`team_id`),
  KEY `team_scores_match_day_id_foreign` (`match_day_id`),
  CONSTRAINT `team_scores_match_day_id_foreign` FOREIGN KEY (`match_day_id`) REFERENCES `match_days` (`id`) ON DELETE CASCADE,
  CONSTRAINT `team_scores_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `competition_id` bigint(20) unsigned NOT NULL,
  `niveau_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `performing` int(11) NOT NULL DEFAULT 5,
  `counting` int(11) NOT NULL DEFAULT 3,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_competition_id_foreign` (`competition_id`),
  KEY `teams_niveau_id_foreign` (`niveau_id`),
  CONSTRAINT `teams_competition_id_foreign` FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teams_niveau_id_foreign` FOREIGN KEY (`niveau_id`) REFERENCES `niveaus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trainers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `club_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trainers_club_id_foreign` (`club_id`),
  CONSTRAINT `trainers_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `key` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_settings_user_id_foreign` (`user_id`),
  CONSTRAINT `user_settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `wedstrijds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wedstrijds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `match_day_id` bigint(20) unsigned NOT NULL,
  `index` int(11) NOT NULL,
  `group_settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`group_settings`)),
  `round_settings` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wedstrijds_match_day_id_foreign` (`match_day_id`),
  CONSTRAINT `wedstrijds_match_day_id_foreign` FOREIGN KEY (`match_day_id`) REFERENCES `match_days` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2023_11_19_095422_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2023_11_19_095537_add_roles_to_system',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2023_11_27_191850_create_locations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2023_11_27_191874_create_competitions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2023_11_28_155300_remove_location_from_competition',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2023_11_28_160902_create_match_days_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2023_12_01_120404_create_wedstrijds_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2023_12_01_132340_create_niveaux_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2023_12_01_132418_create_groups_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2023_12_01_132828_create_gymnasts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2023_12_01_133115_create_clubs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2023_12_01_133132_create_teams_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2023_12_01_133221_create_registrations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2023_12_01_140007_create_niveau_wedstrijd_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2023_12_09_221659_create_scores_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2023_12_09_221706_create_processed_scores_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2023_12_13_105507_add_columns_to_clubs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2023_12_13_105626_create_trainers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2023_12_17_103042_create_user_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2023_12_17_103328_add_imported_to_match_days_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2023_12_18_113101_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2023_12_18_113211_create_sessions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2023_12_18_132230_create_juries_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2023_12_18_132507_create_declarations_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2023_12_18_134304_add_soft_delete_columns',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2023_12_18_135816_create_audits_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2023_12_18_155627_add_active_column_to_users_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2023_12_20_184725_add_team_total_score_to_teams_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2023_12_20_184841_add_score_counted_to_scores_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2023_12_20_194557_add_toestel_scores_to_teams_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2023_12_21_130902_make_user_id_column_on_user_settings_table_nullable',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2023_12_21_191846_create_team_scores_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2023_12_21_220849_remove_imported_column_from_match_days_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2024_01_02_144957_add_synced_column_to_audits_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2024_01_17_110124_add_last_seen_column_to_users_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2024_01_17_204116_create_dg_resources_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2024_01_17_212923_create_job_batches_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2024_01_18_100122_add_age_category_to_niveaus_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2024_01_18_114258_add_oefenstof_last_updated_to_settings',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2024_01_20_100606_create_feedback_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2024_01_22_133642_create_competition_trainer_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2024_01_23_093127_add_team_setting_to_teams_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2024_01_25_182446_create_pending_changes_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2024_01_25_233020_create_sync_tasks_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2024_01_29_115445_add_niveau_number_to_niveaus_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2024_02_05_191009_add_name_to_match_days_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2018_08_08_100000_create_telescope_entries_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2024_02_12_230008_add_multiple_e_scores_to_scores_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2024_02_16_104326_create_score_corrections_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2024_02_16_141340_add_approved_status_to_score_corrections_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2024_02_16_142102_add_soft_deletes_to_score_corrections_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2024_02_16_153255_add_score_correction_enabled_setting',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2024_02_16_213113_add_group_settings_to_wedstrijds_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2024_02_17_212614_add_round_settings_to_wedstrijds_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2024_02_19_111716_create_devices_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2024_02_19_113606_add_loaded_page_to_devices_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2024_02_26_130738_add_user_id_to_score_corrections_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2024_02_26_133505_add_authenticated_user_to_devices_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2024_02_26_140253_add_locked_to_users_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2024_03_15_085003_add_startnumber_to_score_corrections_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2024_03_15_205151_add_place_to_scores_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2024_03_15_205711_add_place_to_team_scores_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2024_03_18_120210_add_e_column_to_scores_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2024_03_18_132614_add_place_to_registrations_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2024_05_05_132637_add_type_to_user_settings_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2024_05_03_141506_create_calendar_items_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2024_05_05_095621_create_calendar_updates_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2024_05_05_102116_add_details_to_calendar_items_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2024_05_06_143512_create_calendar_item_user_table',25);
