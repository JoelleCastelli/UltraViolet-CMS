SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

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
  `title` VARCHAR(50) NULL,
  `path` VARCHAR(255) NOT NULL,
  `video` TINYINT NOT NULL DEFAULT '0',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fullName` VARCHAR(50) NULL,
  `pseudo` VARCHAR(25) NULL,
  `email` VARCHAR(130) NULL,
  `emailConfirmed` TINYINT NULL DEFAULT '0',
  `password` VARCHAR(255) NULL,
  `role` ENUM('user', 'admin', 'editor', 'vip') NULL DEFAULT 'user',
  `optin` TINYINT NULL DEFAULT '1',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL,
  `uvtr_media_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_person_uvtr_media1_idx` (`uvtr_media_id` ASC),
  CONSTRAINT `fk_uvtr_person_uvtr_media1`
    FOREIGN KEY (`uvtr_media_id`)
    REFERENCES `uvtr_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NULL,
  `description` VARCHAR(255) NULL,
  `content` LONGTEXT NULL,
  `rating` INT NULL,
  `slug` VARCHAR(100) NULL,
  `state` ENUM('draft', 'scheduled', 'published', 'hidden', 'deleted') NULL,
  `totalViews` INT NULL DEFAULT '0',
  `titleSeo` VARCHAR(60) NULL,
  `descriptionSeo` VARCHAR(160) NULL,
  `publicationDate` DATETIME NULL,
  `contentUpdatedAt` DATETIME NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL,
  `uvtr_media_id` INT NOT NULL,
  `uvtr_person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_article_uvtr_media1_idx` (`uvtr_media_id` ASC),
  INDEX `fk_uvtr_article_uvtr_person1_idx` (`uvtr_person_id` ASC),
  CONSTRAINT `fk_uvtr_article_uvtr_media1`
    FOREIGN KEY (`uvtr_media_id`)
    REFERENCES `uvtr_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_article_uvtr_person1`
    FOREIGN KEY (`uvtr_person_id`)
    REFERENCES `uvtr_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_mailing`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_mailing` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(130) NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_production`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_production` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tmdbId` INT NULL,
  `title` VARCHAR(100) NOT NULL,
  `originalTitle` VARCHAR(100) NULL,
  `releaseDate` DATETIME NULL,
  `type` ENUM('movie', 'series', 'season', 'episode') NOT NULL,
  `overview` TEXT NULL,
  `runtime` TINYINT NULL,
  `number` TINYINT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL,
  `uvtr_production_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_production_uvtr_production1_idx` (`uvtr_production_id` ASC),
  CONSTRAINT `fk_uvtr_production_uvtr_production1`
    FOREIGN KEY (`uvtr_production_id`)
    REFERENCES `uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_genre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_genre` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_page`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NULL,
  `slug` VARCHAR(100) NULL,
  `position` TINYINT NULL,
  `state` ENUM('draft', 'scheduled', 'published', 'hidden', 'deleted') NOT NULL DEFAULT 'draft',
  `titleSeo` VARCHAR(60) NULL,
  `descriptionSeo` VARCHAR(160) NULL,
  `publicationDate` DATETIME NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL,
  `visible` TINYINT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `uvtr_article_id` INT NOT NULL,
  `uvtr_person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uvtr_comment_uvtr_article1_idx` (`uvtr_article_id` ASC),
  INDEX `fk_uvtr_comment_uvtr_person1_idx` (`uvtr_person_id` ASC),
  CONSTRAINT `fk_uvtr_comment_uvtr_article1`
    FOREIGN KEY (`uvtr_article_id`)
    REFERENCES `uvtr_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_comment_uvtr_person1`
    FOREIGN KEY (`uvtr_person_id`)
    REFERENCES `uvtr_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = utf8;


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
    REFERENCES `uvtr_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_page_article_uvtr_page1`
    FOREIGN KEY (`uvtr_page_id`)
    REFERENCES `uvtr_page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uvtr_article_production`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uvtr_article_production` (
  `uvtr_article_id` INT NOT NULL,
  `uvtr_production_id` INT NOT NULL,
  INDEX `fk_uvtr_article_production_uvtr_article1_idx` (`uvtr_article_id` ASC),
  INDEX `fk_uvtr_article_production_uvtr_production1_idx` (`uvtr_production_id` ASC),
  CONSTRAINT `fk_uvtr_article_production_uvtr_article1`
    FOREIGN KEY (`uvtr_article_id`)
    REFERENCES `uvtr_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_article_production_uvtr_production1`
    FOREIGN KEY (`uvtr_production_id`)
    REFERENCES `uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


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
    REFERENCES `uvtr_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uvtr_production_genre_uvtr_genre1`
    FOREIGN KEY (`uvtr_genre_id`)
    REFERENCES `uvtr_genre` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;