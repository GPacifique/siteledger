-- Construction Project Management Schema (MySQL)
-- Generated: 2026-01-31

CREATE TABLE `projects` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `project_code` VARCHAR(100) NULL,
  `location` VARCHAR(255) NULL,
  `contract_value` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `budget` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `status` ENUM('planning','active','completed','on_hold') NOT NULL DEFAULT 'planning',
  `project_type` ENUM('DESIGN','EXECUTION','DESIGN_EXECUTION') NOT NULL DEFAULT 'EXECUTION',
  `current_phase` ENUM('design','execution') NULL,
  `tenant_id` BIGINT UNSIGNED NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `updated_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`client_id`),
  INDEX (`tenant_id`),
  UNIQUE KEY `projects_project_code_unique` (`project_code`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `phases` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` BIGINT UNSIGNED NOT NULL,
  `position` INT NOT NULL DEFAULT 0,
  `name` VARCHAR(150) NOT NULL,
  `planned_start` DATE NULL,
  `planned_end` DATE NULL,
  `actual_start` DATE NULL,
  `actual_end` DATE NULL,
  `status` ENUM('pending','in_progress','completed','on_hold') NOT NULL DEFAULT 'pending',
  `budget` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`project_id`),
  CONSTRAINT `phases_project_fk` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tasks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `phase_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `assigned_worker_id` BIGINT UNSIGNED NULL,
  `status` ENUM('todo','in_progress','done') NOT NULL DEFAULT 'todo',
  `planned_start` DATE NULL,
  `planned_end` DATE NULL,
  `actual_start` DATE NULL,
  `actual_end` DATE NULL,
  `estimated_hours` DECIMAL(8,2) NULL,
  `actual_hours` DECIMAL(8,2) NULL,
  `hourly_rate` DECIMAL(12,2) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`phase_id`),
  INDEX (`assigned_worker_id`),
  CONSTRAINT `tasks_phase_fk` FOREIGN KEY (`phase_id`) REFERENCES `phases`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `workers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(80) NULL,
  `hourly_rate` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `contact` VARCHAR(255) NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `tenant_id` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`tenant_id`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `worker_assignments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `worker_id` BIGINT UNSIGNED NOT NULL,
  `phase_id` BIGINT UNSIGNED NULL,
  `task_id` BIGINT UNSIGNED NULL,
  `role` VARCHAR(100) NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `hourly_rate` DECIMAL(12,2) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`worker_id`),
  INDEX (`phase_id`),
  INDEX (`task_id`),
  CONSTRAINT `wa_worker_fk` FOREIGN KEY (`worker_id`) REFERENCES `workers`(`id`) ON DELETE CASCADE,
  CONSTRAINT `wa_phase_fk` FOREIGN KEY (`phase_id`) REFERENCES `phases`(`id`) ON DELETE CASCADE,
  CONSTRAINT `wa_task_fk` FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `materials` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `phase_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `unit` VARCHAR(50) NULL,
  `unit_cost` DECIMAL(12,4) NOT NULL DEFAULT 0.0000,
  `quantity` DECIMAL(12,4) NOT NULL DEFAULT 0.0000,
  `total_cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `supplier` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`phase_id`),
  CONSTRAINT `materials_phase_fk` FOREIGN KEY (`phase_id`) REFERENCES `phases`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `expenses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` BIGINT UNSIGNED NOT NULL,
  `phase_id` BIGINT UNSIGNED NULL,
  `category` ENUM('labor','material','transport','equipment','other') NOT NULL,
  `description` TEXT NULL,
  `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `incurred_at` DATE NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`project_id`),
  INDEX (`phase_id`),
  CONSTRAINT `expenses_project_fk` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_phase_fk` FOREIGN KEY (`phase_id`) REFERENCES `phases`(`id`) ON DELETE SET NULL
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` BIGINT UNSIGNED NOT NULL,
  `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `payment_date` DATE NULL,
  `source` ENUM('client','other') NOT NULL DEFAULT 'client',
  `status` ENUM('Pending','Paid','Failed') NOT NULL DEFAULT 'Paid',
  `reference` VARCHAR(255) NULL,
  `created_by` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`project_id`),
  CONSTRAINT `payments_project_fk` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `phase_costs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `phase_id` BIGINT UNSIGNED NOT NULL,
  `total_material` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_labor` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_other` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_cost` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `computed_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX (`phase_id`),
  CONSTRAINT `pc_phase_fk` FOREIGN KEY (`phase_id`) REFERENCES `phases`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- End of schema
