SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
/*CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;*/

-- -----------------------------------------------------
-- Table `uv_media`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_media` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NULL,
  `path` VARCHAR(255) NOT NULL,
  `video` TINYINT NOT NULL DEFAULT '0',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_person` (
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
  `uv_media_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_person_uv_media1_idx` (`uv_media_id` ASC),
  CONSTRAINT `fk_uv_person_uv_media1`
    FOREIGN KEY (`uv_media_id`)
    REFERENCES `uv_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_article` (
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
  `uv_media_id` INT NOT NULL,
  `uv_person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_article_uv_media1_idx` (`uv_media_id` ASC),
  INDEX `fk_uv_article_uv_person1_idx` (`uv_person_id` ASC),
  CONSTRAINT `fk_uv_article_uv_media1`
    FOREIGN KEY (`uv_media_id`)
    REFERENCES `uv_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_article_uv_person1`
    FOREIGN KEY (`uv_person_id`)
    REFERENCES `uv_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_mailing`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_mailing` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(130) NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_production`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_production` (
  `id` INT NOT NULL AUTO_INCREMENT,
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
  `uv_production_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_production_uv_production1_idx` (`uv_production_id` ASC),
  CONSTRAINT `fk_uv_production_uv_production1`
    FOREIGN KEY (`uv_production_id`)
    REFERENCES `uv_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_genre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_genre` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_page`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_page` (
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
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL,
  `visible` TINYINT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `uv_article_id` INT NOT NULL,
  `uv_person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_comment_uv_article1_idx` (`uv_article_id` ASC),
  INDEX `fk_uv_comment_uv_person1_idx` (`uv_person_id` ASC),
  CONSTRAINT `fk_uv_comment_uv_article1`
    FOREIGN KEY (`uv_article_id`)
    REFERENCES `uv_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_comment_uv_person1`
    FOREIGN KEY (`uv_person_id`)
    REFERENCES `uv_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_page_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_page_article` (
  `uv_article_id` INT NOT NULL,
  `uv_page_id` INT NOT NULL,
  INDEX `fk_uv_truc_uv_article1_idx` (`uv_article_id` ASC),
  INDEX `fk_uv_truc_uv_page1_idx` (`uv_page_id` ASC),
  CONSTRAINT `fk_uv_truc_uv_article1`
    FOREIGN KEY (`uv_article_id`)
    REFERENCES `uv_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_truc_uv_page1`
    FOREIGN KEY (`uv_page_id`)
    REFERENCES `uv_page` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET = latin1;


-- -----------------------------------------------------
-- Table `uv_article_production`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_article_production` (
  `uv_article_id` INT NOT NULL,
  `uv_production_id` INT NOT NULL,
  INDEX `fk_test_copy1_uv_article1_idx` (`uv_article_id` ASC),
  INDEX `fk_test_copy1_uv_production1_idx` (`uv_production_id` ASC),
  CONSTRAINT `fk_test_copy1_uv_article1`
    FOREIGN KEY (`uv_article_id`)
    REFERENCES `uv_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_test_copy1_uv_production1`
    FOREIGN KEY (`uv_production_id`)
    REFERENCES `uv_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uv_production_genre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `uv_production_genre` (
  `uv_production_id` INT NOT NULL,
  `uv_genre_id` INT NOT NULL,
  INDEX `fk_uv_bidule_uv_production1_idx` (`uv_production_id` ASC),
  INDEX `fk_uv_bidule_uv_genre1_idx` (`uv_genre_id` ASC),
  CONSTRAINT `fk_uv_bidule_uv_production1`
    FOREIGN KEY (`uv_production_id`)
    REFERENCES `uv_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_bidule_uv_genre1`
    FOREIGN KEY (`uv_genre_id`)
    REFERENCES `uv_genre` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
