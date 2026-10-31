-- Records department data entered in the ACP on a department's behalf (for the Reports page)
CREATE TABLE IF NOT EXISTS `department_data_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` BIGINT UNSIGNED NOT NULL,
  `data_type` VARCHAR(30) NOT NULL,
  `ref_key` VARCHAR(191) NULL,
  `source` VARCHAR(20) NOT NULL DEFAULT 'acp',
  `user_id` BIGINT UNSIGNED NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_dept_created` (`department_id`, `created_at`),
  KEY `idx_type_created` (`data_type`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
