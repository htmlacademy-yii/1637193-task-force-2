-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema taskforce
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema taskforce
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `taskforce` DEFAULT CHARACTER SET utf8 ;
USE `taskforce` ;

-- -----------------------------------------------------
-- Table `taskforce`.`cities`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`cities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `city` TEXT(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`locations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `city_id` INT NOT NULL,
  `coordinates` POINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `city_id_idx` (`city_id` ASC) VISIBLE,
  CONSTRAINT `city_id`
    FOREIGN KEY (`city_id`)
    REFERENCES `taskforce`.`cities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `registered_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` TEXT(100) NOT NULL,
  `email` TEXT(100) NOT NULL,
  `avatar_url` TEXT(200) NULL,
  `password` TEXT(100) NOT NULL,
  `adress` TEXT(100) NULL,
  `location_id` INT NOT NULL,
  `birthday` DATE NULL,
  `information` TEXT(500) NULL,
  `specialization` TEXT(100) NULL,
  `phone` TEXT(20) NULL,
  `skype` TEXT(100) NULL,
  `messenger` TEXT(100) NULL,
  `notify_new_message` TINYINT NULL DEFAULT 1,
  `notify_task_actions` TINYINT NULL DEFAULT 1,
  `notify_new_review` TINYINT NULL DEFAULT 1,
  `show_contacts` TINYINT NULL DEFAULT 0,
  `show_profile` TINYINT NULL DEFAULT 1,
  `feedback_list_id` INT NULL,
  `portpolio_id` INT NULL,
  `complite_tasks` INT NULL,
  `failed_tasks` INT NULL,
  `role` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `city_id_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `taskforce`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `major` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`implementor_major`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`implementor_major` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `major_id` INT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `specialization_id_idx` (`major_id` ASC) VISIBLE,
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `specialization_id`
    FOREIGN KEY (`major_id`)
    REFERENCES `taskforce`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_major_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`task_files_all`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`task_files_all` (
  `id` INT NOT NULL,
  `file_url` TEXT(200) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`task_files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`task_files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `file_id` INT NULL,
  `task_id` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `file_id_idx` (`file_id` ASC) VISIBLE,
  INDEX `task_id_idx` (`task_id` ASC) VISIBLE,
  CONSTRAINT `task_file_id`
    FOREIGN KEY (`file_id`)
    REFERENCES `taskforce`.`task_files_all` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `task_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`tasks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_ad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_id` TINYINT NULL DEFAULT 0,
  `customer_id` INT NULL,
  `implementor_id` INT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(300) NOT NULL,
  `category_id` INT NOT NULL,
  `files_id` INT NULL,
  `location_id` INT NULL,
  `budget` INT NULL,
  `completed_ad` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `category_id_idx` (`category_id` ASC) VISIBLE,
  INDEX `customer_id_idx` (`customer_id` ASC) VISIBLE,
  INDEX `implementor_id_idx` (`implementor_id` ASC) VISIBLE,
  INDEX `files_id_idx` (`files_id` ASC) VISIBLE,
  INDEX `location_id_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `category_id`
    FOREIGN KEY (`category_id`)
    REFERENCES `taskforce`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `customer_id`
    FOREIGN KEY (`customer_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `implementor_id`
    FOREIGN KEY (`implementor_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `files_id`
    FOREIGN KEY (`files_id`)
    REFERENCES `taskforce`.`task_files` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `location_id`
    FOREIGN KEY (`location_id`)
    REFERENCES `taskforce`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`feedback_list`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`feedback_list` (
  `id` INT NOT NULL,
  `added_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` TEXT(200) NULL,
  `score` FLOAT NULL,
  `task_id` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `task_id_idx` (`task_id` ASC) INVISIBLE,
  CONSTRAINT `task_feedback_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`user_feedback`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`user_feedback` (
  `id` INT NOT NULL,
  `user_id` INT NULL,
  `feedback_id` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  INDEX `feedback_id_idx` (`feedback_id` ASC) VISIBLE,
  CONSTRAINT `user_feedback_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `feedback_id`
    FOREIGN KEY (`feedback_id`)
    REFERENCES `taskforce`.`feedback_list` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`portfolio_files`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`portfolio_files` (
  `id` INT NOT NULL,
  `file_url` TEXT(200) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`portfolio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`portfolio` (
  `id` INT NOT NULL,
  `file_id` INT NULL,
  `user_id` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `file_id_idx` (`file_id` ASC) VISIBLE,
  INDEX `user_id_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `file_portpolio_id`
    FOREIGN KEY (`file_id`)
    REFERENCES `taskforce`.`portfolio_files` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `user_portfolio_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`responses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`responses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `created_ad` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `implementor_id` INT NULL,
  `task_id` INT NULL,
  `cost` INT NULL,
  `description` VARCHAR(200) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `implementor_id_idx` (`implementor_id` ASC) VISIBLE,
  INDEX `task_id_idx` (`task_id` ASC) VISIBLE,
  CONSTRAINT `implementor_responce_id`
    FOREIGN KEY (`implementor_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `task_responce_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`messages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `taskforce`.`messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sender_id` INT NOT NULL,
  `recipient_id` INT NOT NULL,
  `message` TEXT(300) NOT NULL,
  `sended_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `task_id` INT NOT NULL,
  `is_read` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  INDEX `sender_id_idx` (`sender_id` ASC) VISIBLE,
  INDEX `recipient_id_idx` (`recipient_id` ASC) VISIBLE,
  INDEX `message_task_id_idx` (`task_id` ASC) VISIBLE,
  CONSTRAINT `sender_id`
    FOREIGN KEY (`sender_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `recipient_id`
    FOREIGN KEY (`recipient_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `message_task_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
