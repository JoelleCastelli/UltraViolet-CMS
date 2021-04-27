-- MySQL Script generated by MySQL Workbench
-- Tue Apr  6 16:51:08 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema ultraviolet
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ultraviolet
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ultraviolet` DEFAULT CHARACTER SET utf8 ;
USE `ultraviolet` ;

-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_media`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_media` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NULL DEFAULT NULL,
  `path` VARCHAR(255) NOT NULL,
  `video` TINYINT NOT NULL DEFAULT '0',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fullName` VARCHAR(50) NULL DEFAULT NULL,
  `pseudo` VARCHAR(25) NULL DEFAULT NULL,
  `email` VARCHAR(130) NULL DEFAULT NULL,
  `emailConfirmed` TINYINT NULL DEFAULT '0',
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `role` ENUM('user', 'admin', 'editor', 'vip') NULL DEFAULT 'user',
  `optin` TINYINT NULL DEFAULT '1',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  `uvtr_media_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_person_uvtr_media1_idx` (`uvtr_media_id` ASC),
  CONSTRAINT `fk_uvtr_person_uvtr_media1`
    FOREIGN KEY (`uvtr_media_id`)
    REFERENCES `ultraviolet`.`uvtr_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `rating` INT NULL DEFAULT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `state` ENUM('draft', 'scheduled', 'published', 'hidden', 'deleted') NOT NULL DEFAULT 'draft',
  `totalViews` INT NULL DEFAULT '0',
  `titleSeo` VARCHAR(60) NULL DEFAULT NULL,
  `descriptionSeo` VARCHAR(160) NULL DEFAULT NULL,
  `publicationDate` DATETIME NULL DEFAULT NULL,
  `contentUpdatedAt` DATETIME NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  `uvtr_media_id` INT NOT NULL,
  `uvtr_person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_article_uvtr_media1_idx` (`uvtr_media_id` ASC),
  INDEX `fk_uvtr_article_uvtr_person1_idx` (`uvtr_person_id` ASC),
  CONSTRAINT `fk_uvtr_article_uvtr_media1`
    FOREIGN KEY (`uvtr_media_id`)
    REFERENCES `ultraviolet`.`uvtr_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_article_uvtr_person1`
    FOREIGN KEY (`uvtr_person_id`)
    REFERENCES `ultraviolet`.`uvtr_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_mailing`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_mailing` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(130) NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_production`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_production` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tmdbId` INT NULL DEFAULT NULL,
  `title` VARCHAR(100) NOT NULL,
  `originalTitle` VARCHAR(100) NULL DEFAULT NULL,
  `releaseDate` VARCHAR(10) NULL DEFAULT NULL,
  `type` ENUM('movie', 'series', 'season', 'episode') NOT NULL,
  `overview` TEXT NULL DEFAULT NULL,
  `runtime` INT NULL DEFAULT NULL,
  `number` TINYINT NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  `uvtr_production_id` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_production_uvtr_production1_idx` (`uvtr_production_id` ASC),
  CONSTRAINT `fk_uvtr_production_uvtr_production1`
    FOREIGN KEY (`uvtr_production_id`)
    REFERENCES `ultraviolet`.`uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_genre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_genre` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_page`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `position` TINYINT NOT NULL,
  `state` ENUM('draft', 'scheduled', 'published', 'hidden', 'deleted') NOT NULL DEFAULT 'draft',
  `titleSeo` VARCHAR(60) NULL DEFAULT NULL,
  `descriptionSeo` VARCHAR(160) NULL DEFAULT NULL,
  `publicationDate` DATETIME NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL DEFAULT NULL,
  `visible` TINYINT NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `uvtr_article_id` INT NOT NULL,
  `uvtr_person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_comment_uvtr_article1_idx` (`uvtr_article_id` ASC),
  INDEX `fk_uvtr_comment_uvtr_person1_idx` (`uvtr_person_id` ASC),
  CONSTRAINT `fk_uvtr_comment_uvtr_article1`
    FOREIGN KEY (`uvtr_article_id`)
    REFERENCES `ultraviolet`.`uvtr_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_comment_uvtr_person1`
    FOREIGN KEY (`uvtr_person_id`)
    REFERENCES `ultraviolet`.`uvtr_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_page_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_page_article` (
  `uvtr_article_id` INT NOT NULL,
  `uvtr_page_id` INT NOT NULL,
  INDEX `fk_uvtr_page_article_uvtr_article1_idx` (`uvtr_article_id` ASC),
  INDEX `fk_uvtr_page_article_uvtr_page1_idx` (`uvtr_page_id` ASC),
  CONSTRAINT `fk_uvtr_page_article_uvtr_article1`
    FOREIGN KEY (`uvtr_article_id`)
    REFERENCES `ultraviolet`.`uvtr_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_page_article_uvtr_page1`
    FOREIGN KEY (`uvtr_page_id`)
    REFERENCES `ultraviolet`.`uvtr_page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_production_genre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_production_genre` (
  `uvtr_production_id` INT NOT NULL,
  `uvtr_genre_id` INT NOT NULL,
  INDEX `fk_uvtr_production_genre_uvtr_production1_idx` (`uvtr_production_id` ASC),
  INDEX `fk_uvtr_production_genre_uvtr_genre1_idx` (`uvtr_genre_id` ASC),
  CONSTRAINT `fk_uvtr_production_genre_uvtr_production1`
    FOREIGN KEY (`uvtr_production_id`)
    REFERENCES `ultraviolet`.`uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_production_genre_uvtr_genre1`
    FOREIGN KEY (`uvtr_genre_id`)
    REFERENCES `ultraviolet`.`uvtr_genre` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_production_media`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_production_media` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uvtr_media_id` INT NOT NULL,
  `uvtr_production_id` INT NOT NULL,
  `key_art` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_production_media_uvtr_media1_idx` (`uvtr_media_id` ASC),
  INDEX `fk_uvtr_production_media_uvtr_production1_idx` (`uvtr_production_id` ASC),
  CONSTRAINT `fk_uvtr_production_media_uvtr_media1`
    FOREIGN KEY (`uvtr_media_id`)
    REFERENCES `ultraviolet`.`uvtr_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_production_media_uvtr_production1`
    FOREIGN KEY (`uvtr_production_id`)
    REFERENCES `ultraviolet`.`uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_production_person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_production_person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uvtr_person_id` INT NOT NULL,
  `uvtr_production_id` INT NOT NULL,
  `department` VARCHAR(15) NOT NULL,
  `character` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_production_person_uvtr_person1_idx` (`uvtr_person_id` ASC),
  INDEX `fk_uvtr_production_person_uvtr_production1_idx` (`uvtr_production_id` ASC),
  CONSTRAINT `fk_uvtr_production_person_uvtr_person1`
    FOREIGN KEY (`uvtr_person_id`)
    REFERENCES `ultraviolet`.`uvtr_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_production_person_uvtr_production1`
    FOREIGN KEY (`uvtr_production_id`)
    REFERENCES `ultraviolet`.`uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_production_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_production_article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `uvtr_production_id` INT NOT NULL,
  `uvtr_article_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_production_article_uvtr_production1_idx` (`uvtr_production_id` ASC),
  INDEX `fk_uvtr_production_article_uvtr_article1_idx` (`uvtr_article_id` ASC),
  CONSTRAINT `fk_uvtr_production_article_uvtr_production1`
    FOREIGN KEY (`uvtr_production_id`)
    REFERENCES `ultraviolet`.`uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_production_article_uvtr_article1`
    FOREIGN KEY (`uvtr_article_id`)
    REFERENCES `ultraviolet`.`uvtr_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
