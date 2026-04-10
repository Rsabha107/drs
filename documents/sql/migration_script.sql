-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: mds
-- Source Schemata: mds
-- Created: Tue Mar 18 10:25:52 2025
-- Workbench Version: 8.0.26
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema mds
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `mds` ;
CREATE SCHEMA IF NOT EXISTS `mds` ;

-- ----------------------------------------------------------------------------
-- Table mds.bookapp_booking_slots
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`bookapp_booking_slots` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` INT NOT NULL,
  `booking_date` DATE NOT NULL,
  `booking_slot` VARCHAR(45) CHARACTER SET 'utf8mb4' NOT NULL,
  `maximum_slots` INT NOT NULL,
  `available_slots` INT NOT NULL,
  `used_slots` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 13
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.bookapp_bookings
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`bookapp_bookings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL DEFAULT NULL,
  `event_id` INT NOT NULL,
  `booking_ref_number` VARCHAR(50) NOT NULL,
  `booking_date` DATE NOT NULL,
  `team_id` INT NOT NULL,
  `destination_id` INT NOT NULL,
  `schedule_period_id` INT NOT NULL,
  `delivery_status_id` INT NULL DEFAULT '12',
  `arrival_date_time` DATETIME NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 34
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.bookapp_destinations
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`bookapp_destinations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) CHARACTER SET 'utf8mb4' NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.bookapp_events
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`bookapp_events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `active_flag` INT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.bookapp_teams
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`bookapp_teams` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) CHARACTER SET 'utf8mb4' NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.cal
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`cal` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `month` VARCHAR(20) NULL DEFAULT NULL,
  `month_num` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 25
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.colors
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`colors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.event_location
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`event_location` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `active_flag` INT NOT NULL,
  `creator_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 17
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.event_venue
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`event_venue` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `location_id` INT NULL DEFAULT NULL,
  `active_flag` INT NOT NULL,
  `creator_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.failed_jobs
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `connection` TEXT CHARACTER SET 'utf8mb4' NOT NULL,
  `queue` TEXT CHARACTER SET 'utf8mb4' NOT NULL,
  `payload` LONGTEXT CHARACTER SET 'utf8mb4' NOT NULL,
  `exception` LONGTEXT CHARACTER SET 'utf8mb4' NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.genders
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`genders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.global_attachments
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`global_attachments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `archived` VARCHAR(5) NOT NULL DEFAULT 'N',
  `model_id` INT NOT NULL,
  `model_name` VARCHAR(25) NOT NULL,
  `employee_id` INT NULL DEFAULT NULL,
  `file_name` VARCHAR(250) NOT NULL,
  `original_file_name` VARCHAR(150) NOT NULL,
  `file_extension` VARCHAR(10) NOT NULL,
  `file_size` INT NOT NULL,
  `file_path` VARCHAR(150) NULL DEFAULT NULL,
  `description` VARCHAR(4000) NULL DEFAULT NULL,
  `user_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 68
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.global_status
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`global_status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `active_flag` INT NOT NULL,
  `color` VARCHAR(45) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.jobs
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `payload` LONGTEXT CHARACTER SET 'utf8mb4' NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `jobs_queue_index` (`queue` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 202
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.languages
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`languages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `code` VARCHAR(10) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 136
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_booking
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_booking` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL DEFAULT NULL,
  `event_id` INT NOT NULL,
  `rsp_id` INT NULL DEFAULT NULL,
  `booking_ref_number` VARCHAR(50) NOT NULL,
  `booking_date` DATE NOT NULL,
  `venue_id` INT NOT NULL,
  `client_id` INT NOT NULL,
  `schedule_period_id` INT NOT NULL,
  `schedule_id` INT NULL DEFAULT NULL,
  `driver_id` INT NOT NULL,
  `vehicle_id` INT NOT NULL,
  `vehicle_type_id` INT NOT NULL,
  `booking_party_company_name` VARCHAR(100) NULL DEFAULT NULL,
  `booking_party_contact_name` VARCHAR(200) NULL DEFAULT NULL,
  `booking_party_contact_email` VARCHAR(150) NULL DEFAULT NULL,
  `booking_party_contact_number` VARCHAR(50) NULL DEFAULT NULL,
  `delivering_party_company_name` VARCHAR(200) NULL DEFAULT NULL,
  `delivering_party_contact_number` VARCHAR(50) NULL DEFAULT NULL,
  `delivering_party_contact_email` VARCHAR(150) NULL DEFAULT NULL,
  `receiver_name` VARCHAR(250) NOT NULL,
  `receiver_contact_number` VARCHAR(50) NOT NULL,
  `dispatch_id` INT NOT NULL,
  `cargo_id` INT NOT NULL,
  `loading_zone_id` INT NOT NULL,
  `active_flag` INT NOT NULL,
  `delivery_status_id` INT NOT NULL DEFAULT '12',
  `arrival_date_time` DATETIME NULL DEFAULT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 27
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_booking_files
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_booking_files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `task_id` INT NOT NULL,
  `file_name` VARCHAR(250) NOT NULL,
  `original_file_name` VARCHAR(150) NOT NULL,
  `file_extension` VARCHAR(10) NOT NULL,
  `file_size` INT NOT NULL,
  `file_path` VARCHAR(150) NULL DEFAULT NULL,
  `user_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_booking_notes
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_booking_notes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `booking_id` INT NOT NULL,
  `note_text` TEXT NULL DEFAULT NULL,
  `user_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_booking_slots
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_booking_slots` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NULL DEFAULT NULL,
  `event_name` VARCHAR(55) NULL DEFAULT NULL,
  `venue_id` INT NULL DEFAULT NULL,
  `venue_name` VARCHAR(55) NULL DEFAULT NULL,
  `booking_date` DATE NULL DEFAULT NULL,
  `rsp_booking_slot` VARCHAR(45) NULL DEFAULT NULL,
  `venue_arrival_time` VARCHAR(45) NULL DEFAULT NULL,
  `bookings_slots_all` INT NULL DEFAULT NULL,
  `available_slots` INT NULL DEFAULT NULL,
  `used_slots` INT NULL DEFAULT NULL,
  `bookings_slots_cat` INT NULL DEFAULT NULL,
  `slot_visibility` DATE NULL DEFAULT NULL,
  `rsp_id` INT NULL DEFAULT NULL,
  `remote_search_park` VARCHAR(45) NULL DEFAULT NULL,
  `match_day` VARCHAR(5) NULL DEFAULT NULL,
  `comments` VARCHAR(150) NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `created_by` INT NULL DEFAULT NULL,
  `updated_by` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6866
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_booking_status
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_booking_status` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `color` VARCHAR(25) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_cargo_types
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_cargo_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) CHARACTER SET 'utf8mb4' NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_configuration
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_configuration` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `config_key` VARCHAR(255) NOT NULL,
  `config_value` TEXT NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `config_key` (`config_key` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb3;

-- ----------------------------------------------------------------------------
-- Table mds.mds_delivery_rsp
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_delivery_rsp` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_delivery_types
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_delivery_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) CHARACTER SET 'utf8mb4' NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_delivery_venue
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_delivery_venue` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) CHARACTER SET 'utf8mb4' NOT NULL,
  `short_name` VARCHAR(50) CHARACTER SET 'utf8mb4' NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_delivery_zone
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_delivery_zone` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) NOT NULL,
  `venue_id` INT NULL DEFAULT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_driver_status
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_driver_status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `color` VARCHAR(25) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_drivers
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_drivers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(150) NOT NULL,
  `last_name` VARCHAR(150) NOT NULL,
  `mobile_number` VARCHAR(50) NOT NULL,
  `national_identifier_number` VARCHAR(50) NOT NULL,
  `active_flag` INT NOT NULL,
  `status_id` INT NULL DEFAULT '12',
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_events
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT NOT NULL DEFAULT '1',
  `updated_by` INT NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_functional_areas
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_functional_areas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) NOT NULL,
  `venue_id` INT NULL DEFAULT NULL,
  `creator_id` INT NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_number_gen
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_number_gen` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `last_number` BIGINT UNSIGNED NOT NULL,
  `person_type` INT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_vehicle_types
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_vehicle_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) CHARACTER SET 'utf8mb4' NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.mds_vehicles
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`mds_vehicles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_type_id` INT NOT NULL,
  `make` VARCHAR(50) NOT NULL,
  `license_plate` VARCHAR(50) NOT NULL,
  `status_id` INT NOT NULL,
  `active_flag` INT NOT NULL,
  `created_by` INT NOT NULL,
  `updated_by` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.migrations
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`migrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `batch` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 50
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.model_has_permissions
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`model_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `model_type` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `model_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  INDEX `model_has_permissions_model_id_model_type_index` (`model_id` ASC, `model_type` ASC) ,
  CONSTRAINT `model_has_permissions_permission_id_foreign`
    FOREIGN KEY (`permission_id`)
    REFERENCES `mds`.`permissions` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.notifications
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`notifications` (
  `id` CHAR(36) CHARACTER SET 'utf8mb4' NOT NULL,
  `type` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `notifiable_type` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `notifiable_id` BIGINT UNSIGNED NOT NULL,
  `data` TEXT CHARACTER SET 'utf8mb4' NOT NULL,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `notifications_notifiable_type_notifiable_id_index` (`notifiable_type` ASC, `notifiable_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.password_reset_tokens
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`password_reset_tokens` (
  `email` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `token` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`email`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.permission_group
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`permission_group` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.permissions
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`permissions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `guard_name` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `group_name` VARCHAR(255) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `active_flag` INT NOT NULL DEFAULT '1',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `permissions_name_guard_name_unique` (`name` ASC, `guard_name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 57
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.personal_access_tokens
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`personal_access_tokens` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `tokenable_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `token` VARCHAR(64) CHARACTER SET 'utf8mb4' NOT NULL,
  `abilities` TEXT CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `last_used_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `personal_access_tokens_token_unique` (`token` ASC) ,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type` ASC, `tokenable_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.role_has_permissions
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`role_has_permissions` (
  `permission_id` BIGINT UNSIGNED NOT NULL,
  `role_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  INDEX `role_has_permissions_role_id_foreign` (`role_id` ASC) ,
  CONSTRAINT `role_has_permissions_permission_id_foreign`
    FOREIGN KEY (`permission_id`)
    REFERENCES `mds`.`permissions` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign`
    FOREIGN KEY (`role_id`)
    REFERENCES `mds`.`roles` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.roles
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`roles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `guard_name` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `roles_name_guard_name_unique` (`name` ASC, `guard_name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.statuses
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`statuses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `color` VARCHAR(25) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.user_event
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`user_event` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `event_id` INT NULL DEFAULT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 34
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.user_fa
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`user_fa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fa_id` INT NULL DEFAULT NULL,
  `user_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 31
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.user_types
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`user_types` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `active_flag` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table mds.users
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mds`.`users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` INT NOT NULL,
  `name` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `username` VARCHAR(255) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) CHARACTER SET 'utf8mb4' NOT NULL,
  `photo` VARCHAR(255) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `phone` VARCHAR(255) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `address` TEXT CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `role` ENUM('admin', 'agent', 'user') CHARACTER SET 'utf8mb4' NOT NULL DEFAULT 'user',
  `status` ENUM('1', '0') CHARACTER SET 'utf8mb4' NOT NULL DEFAULT '0',
  `remember_token` VARCHAR(100) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `usertype` ENUM('admin', 'user') CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `is_admin` TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `users_email_unique` (`email` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 46
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 1;
