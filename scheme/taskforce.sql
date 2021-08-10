-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Table `cities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` TEXT(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `locations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `city_id` INT NOT NULL,
  `coordinates` POINT NOT NULL,
  `title` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `city_id_idx` (`city_id` ASC) VISIBLE,
  CONSTRAINT `city_id`
    FOREIGN KEY (`city_id`)
    REFERENCES `cities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  `name` TEXT(100) NOT NULL,
  `email` TEXT(100) NOT NULL,
  `password` TEXT(100) NOT NULL,
  `address` TEXT(100) NULL,
  `location_id` INT NOT NULL,
  `birthday` DATE NULL,
  `information` TEXT(500) NULL,
  `phone` TEXT(20) NULL,
  `skype` TEXT(100) NULL,
  `messenger` TEXT(100) NULL,
  `notify_new_message` TINYINT NULL DEFAULT 1,
  `notify_task_actions` TINYINT NULL DEFAULT 1,
  `notify_new_review` TINYINT NULL DEFAULT 1,
  `show_contacts` TINYINT NULL DEFAULT 0,
  `show_profile` TINYINT NULL DEFAULT 1,
  `complite_tasks` INT NULL,
  `failed_tasks` INT NULL,
  `status` TINYINT NULL DEFAULT 0,
  `role` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `city_id_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_spec_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_spec_categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `implementor_specialization`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `implementor_specialization` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `specialization_id` INT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `specialization_id_idx` (`specialization_id` ASC) VISIBLE,
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `imp_specialization_id`
    FOREIGN KEY (`specialization_id`)
    REFERENCES `user_spec_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_specialization_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  `status_id` TINYINT NULL DEFAULT 0,
  `customer_id` INT NULL,
  `implementor_id` INT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(300) NOT NULL,
  `category_id` INT NOT NULL,
  `location_id` INT NULL,
  `budget` INT NULL,
  `completed_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `category_id_idx` (`category_id` ASC) VISIBLE,
  INDEX `customer_id_idx` (`customer_id` ASC) VISIBLE,
  INDEX `implementor_id_idx` (`implementor_id` ASC) VISIBLE,
  INDEX `location_id_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `category_id`
    FOREIGN KEY (`category_id`)
    REFERENCES `user_spec_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `customer_id`
    FOREIGN KEY (`customer_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `implementor_id`
    FOREIGN KEY (`implementor_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `feedback_list`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `feedback_list` (
  `id` INT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  `text` TEXT(200) NULL,
  `score` FLOAT NULL,
  `task_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `task_id_idx` (`task_id` ASC) INVISIBLE,
  CONSTRAINT `task_feedback_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_feedback`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_feedback` (
  `id` INT NOT NULL,
  `user_id` INT NULL,
  `feedback_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  INDEX `feedback_id_idx` (`feedback_id` ASC) VISIBLE,
  CONSTRAINT `user_feedback_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `feedback_id`
    FOREIGN KEY (`feedback_id`)
    REFERENCES `feedback_list` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `files` (
  `id` INT NOT NULL,
  `file_url` TEXT(2048) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `portfolio_has_files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `portfolio_has_files` (
  `id` INT NOT NULL,
  `file_id` INT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  INDEX `portfolio_file_id_idx` (`file_id` ASC) VISIBLE,
  CONSTRAINT `portfolio_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `portfolio_file_id`
    FOREIGN KEY (`file_id`)
    REFERENCES `files` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `task_has_files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `task_has_files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `file_id` INT NULL,
  `task_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `file_id_idx` (`file_id` ASC) VISIBLE,
  INDEX `task_id_idx` (`task_id` ASC) VISIBLE,
  CONSTRAINT `task_file_id`
    FOREIGN KEY (`file_id`)
    REFERENCES `files` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `current_task_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `responses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `responses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL,
  `implementor_id` INT NULL,
  `task_id` INT NULL,
  `cost` INT NULL,
  `description` VARCHAR(200) NULL,
  PRIMARY KEY (`id`),
  INDEX `implementor_id_idx` (`implementor_id` ASC) VISIBLE,
  INDEX `task_id_idx` (`task_id` ASC) VISIBLE,
  CONSTRAINT `implementor_responce_id`
    FOREIGN KEY (`implementor_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `task_responce_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sender_id` INT NOT NULL,
  `recipient_id` INT NOT NULL,
  `message` TEXT(300) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `task_id` INT NOT NULL,
  `is_read` TINYINT NOT NULL DEFAULT 0,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `sender_id_idx` (`sender_id` ASC) VISIBLE,
  INDEX `recipient_id_idx` (`recipient_id` ASC) VISIBLE,
  INDEX `message_task_id_idx` (`task_id` ASC) VISIBLE,
  CONSTRAINT `sender_id`
    FOREIGN KEY (`sender_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `recipient_id`
    FOREIGN KEY (`recipient_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `message_task_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `avatar_has_files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `avatar_has_files` (
  `id` INT NOT NULL,
  `file_id` INT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  INDEX `portfolio_file_id_idx` (`file_id` ASC) VISIBLE,
  CONSTRAINT `avatar_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_file_id`
    FOREIGN KEY (`file_id`)
    REFERENCES `files` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
