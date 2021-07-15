SET NAMES UTF8;
SET time_zone = "+00:00";

-- -----------------------------------------------------
-- Schema ultraviolet
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ultraviolet` DEFAULT CHARACTER SET utf8 ;
USE `ultraviolet` ;

-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(60) NOT NULL,
  `position` INT NOT NULL,
  `descriptionSeo` VARCHAR(160) NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_template_variable`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_template_variable` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `selector` VARCHAR(20) NOT NULL,
  `value` VARCHAR(40) NOT NULL,
  `defaultValue` VARCHAR(40) NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_media`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_media` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NULL DEFAULT NULL,
  `path` VARCHAR(255) NOT NULL,
  `video` TINYINT NOT NULL DEFAULT '0',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fullName` VARCHAR(50) NULL DEFAULT NULL,
  `tmdbId` INT NULL DEFAULT NULL,
  `pseudo` VARCHAR(25) NULL DEFAULT NULL,
  `email` VARCHAR(130) NULL DEFAULT NULL,
  `emailConfirmed` TINYINT NULL DEFAULT '0',
  `emailKey` VARCHAR(255) NULL DEFAULT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `role` ENUM('user', 'moderator', 'admin', 'editor', 'vip') NULL DEFAULT 'user',
  `optin` TINYINT NULL DEFAULT '1',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  `mediaId` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_person_uv_media1_idx` (`mediaId` ASC),
  CONSTRAINT `fk_uv_person_uv_media1`
    FOREIGN KEY (`mediaId`)
    REFERENCES `ultraviolet`.`uv_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `publicationDate` DATETIME NULL DEFAULT NULL,
  `contentUpdatedAt` DATETIME NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  `mediaId` INT NOT NULL,
  `personId` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_article_uv_media1_idx` (`mediaId` ASC),
  INDEX `fk_uv_article_uv_person1_idx` (`personId` ASC),
  CONSTRAINT `fk_uv_article_uv_media1`
    FOREIGN KEY (`mediaId`)
    REFERENCES `ultraviolet`.`uv_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_article_uv_person1`
    FOREIGN KEY (`personId`)
    REFERENCES `ultraviolet`.`uv_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_article_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_article_history` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `views` INT NULL DEFAULT '0',
    `date` DATE NOT NULL,
    `articleId` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_uv_article_history_uv_article_idx` (`articleId` ASC),
    CONSTRAINT `fk_uv_article_history_uv_article`
    FOREIGN KEY (`articleId`)
    REFERENCES `ultraviolet`.`uv_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_category_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_category_article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `articleId` INT NOT NULL,
  `categoryId` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_category_article_uv_article_idx` (`articleId` ASC),
  INDEX `fk_uv_category_article_uv_category1_idx` (`categoryId` ASC),
  CONSTRAINT `fk_uv_category_article_uv_article`
    FOREIGN KEY (`articleId`)
    REFERENCES `ultraviolet`.`uv_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_category_article_uv_category1`
    FOREIGN KEY (`categoryId`)
    REFERENCES `ultraviolet`.`uv_category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_production`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_production` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tmdbId` INT NULL DEFAULT NULL,
  `title` VARCHAR(100) NOT NULL,
  `originalTitle` VARCHAR(100) NULL DEFAULT NULL,
  `releaseDate` VARCHAR(10) NULL DEFAULT NULL,
  `type` ENUM('movie', 'series', 'season', 'episode') NOT NULL,
  `totalSeasons` INT NULL DEFAULT NULL,
  `totalEpisodes` INT NULL DEFAULT NULL,
  `overview` TEXT NULL DEFAULT NULL,
  `runtime` INT NULL DEFAULT NULL,
  `number` TINYINT NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  `parentProductionId` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_production_uv_production1_idx` (`parentProductionId` ASC),
  CONSTRAINT `fk_uv_production_uv_production1`
    FOREIGN KEY (`parentProductionId`)
    REFERENCES `ultraviolet`.`uv_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_page`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_page` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `position` TINYINT NOT NULL,
  `state` ENUM('draft', 'scheduled', 'published', 'hidden', 'deleted') NOT NULL DEFAULT 'draft',
  `descriptionSeo` VARCHAR(160) NULL DEFAULT NULL,
  `publicationDate` DATETIME NULL DEFAULT NULL,
  `content` TEXT NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` TEXT NULL DEFAULT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deletedAt` DATETIME NULL DEFAULT NULL,
  `articleId` INT NOT NULL,
  `personId` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_comment_uv_article1_idx` (`articleId` ASC),
  INDEX `fk_uv_comment_uv_person1_idx` (`personId` ASC),
  CONSTRAINT `fk_uv_comment_uv_article1`
    FOREIGN KEY (`articleId`)
    REFERENCES `ultraviolet`.`uv_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_comment_uv_person1`
    FOREIGN KEY (`personId`)
    REFERENCES `ultraviolet`.`uv_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_production_media`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_production_media` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `mediaId` INT NOT NULL,
  `productionId` INT NOT NULL,
  `keyArt` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_production_media_uv_media1_idx` (`mediaId` ASC),
  INDEX `fk_uv_production_media_uv_production1_idx` (`productionId` ASC),
  CONSTRAINT `fk_uv_production_media_uv_media1`
    FOREIGN KEY (`mediaId`)
    REFERENCES `ultraviolet`.`uv_media` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_production_media_uv_production1`
    FOREIGN KEY (`productionId`)
    REFERENCES `ultraviolet`.`uv_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_production_person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_production_person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `personId` INT NOT NULL,
  `productionId` INT NOT NULL,
  `department` VARCHAR(15) NOT NULL,
  `character` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_production_person_uv_person1_idx` (`personId` ASC),
  INDEX `fk_uv_production_person_uv_production1_idx` (`productionId` ASC),
  CONSTRAINT `fk_uv_production_person_uv_person1`
    FOREIGN KEY (`personId`)
    REFERENCES `ultraviolet`.`uv_person` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_production_person_uv_production1`
    FOREIGN KEY (`productionId`)
    REFERENCES `ultraviolet`.`uv_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ultraviolet`.`uv_production_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ultraviolet`.`uv_production_article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `productionId` INT NOT NULL,
  `articleId` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_uv_production_article_uv_production1_idx` (`productionId` ASC),
  INDEX `fk_uv_production_article_uv_article1_idx` (`articleId` ASC),
  CONSTRAINT `fk_uv_production_article_uv_production1`
    FOREIGN KEY (`productionId`)
    REFERENCES `ultraviolet`.`uv_production` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_uv_production_article_uv_article1`
    FOREIGN KEY (`articleId`)
    REFERENCES `ultraviolet`.`uv_article` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Insert default images in database
-- -----------------------------------------------------
INSERT INTO `ultraviolet`.`uv_media` (`title`, `path`) VALUES ("Utilisateur - Image par défaut", "/src/img/default_user.jpg");
INSERT INTO `ultraviolet`.`uv_media` (`title`, `path`) VALUES ("Article - Image par défaut", "/src/img/default_article.png");

-- -----------------------------------------------------
-- Insert example categories
-- -----------------------------------------------------
INSERT INTO `ultraviolet`.`uv_category` (`name`, `position`, `descriptionSeo`) VALUES ("Films", 1, "Découvrez nos dernières news et critiques sur les meilleures films du moment !");
INSERT INTO `ultraviolet`.`uv_category` (`name`, `position`, `descriptionSeo`) VALUES ("Séries", 2, "Découvrez nos dernières news et critiques sur les meilleures séries du moment !");
INSERT INTO `ultraviolet`.`uv_category` (`name`, `position`, `descriptionSeo`) VALUES ("Actualités", 3, "Retrouvez nos dernières actualités sur les meilleurs films et séries");
INSERT INTO `ultraviolet`.`uv_category` (`name`, `position`, `descriptionSeo`) VALUES ("Critiques", 4, "Retrouvez nos dernières critiques des meilleurs films et séries");

-- -----------------------------------------------------
-- Insert example page
-- -----------------------------------------------------
INSERT INTO `ultraviolet`.`uv_page` (`title`, `slug`, `position`, `state`, `descriptionSeo`, `content`)
VALUES ("Ma première page", "ma-premiere-page", 1, "published", "Ceci est la description de votre page telle qu'elle sera vue par les moteurs de recherche", "<p>Voici la toute première page de votre site !</p>");

-- -----------------------------------------------------
-- Insert default templates variables
-- -----------------------------------------------------
INSERT INTO `ultraviolet`.`uv_template_variable` (`selector`, `value`, `defaultValue`) VALUES ("#navbar-front", "#000d28", "#000d28");
INSERT INTO `ultraviolet`.`uv_template_variable` (`selector`, `value`, `defaultValue`) VALUES ("#navbar", "#000d28", "#000d28");