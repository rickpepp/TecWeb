SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema tinkleart
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `tinkleart` DEFAULT CHARACTER SET utf8 ;
USE `tinkleart` ;

-- Dopo la prima volta, non ripetere questa operaizione /*
-- -----------------------------------------------------
-- secure_user
-- -----------------------------------------------------
CREATE USER 'secur_user'@'localhost' IDENTIFIED BY '8lT]4gtavyMPWF)s' 
REQUIRE NONE WITH MAX_USER_CONNECTIONS 3;
-- */

-- -----------------------------------------------------
-- Table `tinkleart`.`persona`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`persona` (
    `idpersona` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(30) NOT NULL,
    `cognome` VARCHAR(30) NOT NULL,
    `password` CHAR(128) NOT NULL, 
    `salt` CHAR(128) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `descrizione` TEXT,
    `imgpersona` VARCHAR(100) DEFAULT 'Foto.png',
    PRIMARY KEY (`idpersona`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`post` (
    `idpost` INT NOT NULL AUTO_INCREMENT,
    `imgpost` VARCHAR(100) NOT NULL,
    `testopost` TEXT NOT NULL,
    `datapost` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `nlike` INT NOT NULL DEFAULT 0,
    `persona` INT NOT NULL,
    PRIMARY KEY (`idpost`),
    INDEX `fk_post_persona_idx` (`persona` ASC),
    CONSTRAINT `fk_post_persona`
    FOREIGN KEY (`persona`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tinkleart`.`categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`categoria` (
    `idcategoria` INT NOT NULL AUTO_INCREMENT,
    `nomecategoria` VARCHAR(50) NOT NULL,
    `imgcategoria` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`idcategoria`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tinkleart`.`post_ha_categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`post_ha_categoria` (
    `post` INT NOT NULL,
    `categoria` INT NOT NULL,
    PRIMARY KEY (`post`, `categoria`),
    INDEX `fk_post_ha_categoria_categoria_idx` (`categoria` ASC),
    INDEX `fk_post_ha_categoria_post_idx` (`post` ASC),
    CONSTRAINT `fk_post_has_categoria_post1`
    FOREIGN KEY (`post`)
    REFERENCES `tinkleart`.`post` (`idpost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_post_has_categoria_categoria1`
    FOREIGN KEY (`categoria`)
    REFERENCES `tinkleart`.`categoria` (`idcategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`commento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`commento` (
    `idcommento` INT NOT NULL AUTO_INCREMENT,
    `persona` INT NOT NULL,
    `post` INT NOT NULL,
    `datacommento` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `testocommento` TEXT NOT NULL,
    `visualizzato` TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (`idcommento`),
    INDEX `fk_commento_persona_idx` (`persona` ASC),
    INDEX `fk_commento_post_idx` (`persona` ASC),
    CONSTRAINT `fk_commento_persona`
    FOREIGN KEY (`persona`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_commento_post`
    FOREIGN KEY (`post`)
    REFERENCES `tinkleart`.`post` (`idpost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`like`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`like` (
    `post` INT NOT NULL,
    `persona` INT NOT NULL,
    `visualizzato` TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (`post`,`persona`),
    INDEX `fk_like_persona_idx` (`persona` ASC),
    INDEX `fk_like_post_idx` (`persona` ASC),
    CONSTRAINT `fk_like_persona`
    FOREIGN KEY (`persona`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_like_post`
    FOREIGN KEY (`post`)
    REFERENCES `tinkleart`.`post` (`idpost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`hashtag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`hashtag` (
    `idhashtag` INT NOT NULL AUTO_INCREMENT,
    `nomehashtag` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`idhashtag`))
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`hashtag_ha_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`hashtag_ha_post` (
    `hashtag` INT NOT NULL,
    `post` INT NOT NULL,
    PRIMARY KEY (`hashtag`,`post`),
    INDEX `fk_hashtag_ha_post_hashtag_idx` (`hashtag` ASC),
    INDEX `fk_hashtag_ha_post_post_idx` (`post` ASC),
    CONSTRAINT `fk_hashtag_ha_post_hashtag`
    FOREIGN KEY (`hashtag`)
    REFERENCES `tinkleart`.`hashtag` (`idhashtag`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_hashtag_ha_post_post`
    FOREIGN KEY (`post`)
    REFERENCES `tinkleart`.`post` (`idpost`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`segui_persona`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`segui_persona` (
    `personaseguita` INT NOT NULL,
    `personasegue` INT NOT NULL,
    `visualizzato` TINYINT NOT NULL DEFAULT 0,
    PRIMARY KEY (`personaseguita`,`personasegue`),
    INDEX `fk_segui_persona_personaseguita_idx` (`personaseguita` ASC),
    INDEX `fk_segui_persona_post_idx` (`personasegue` ASC),
    CONSTRAINT `fk_segui_persona_personaseguita`
    FOREIGN KEY (`personaseguita`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_segui_persona_personasegue`
    FOREIGN KEY (`personasegue`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`segui_categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`segui_categoria` (
    `persona` INT NOT NULL,
    `categoria` INT NOT NULL,
    PRIMARY KEY (`persona`,`categoria`),
    INDEX `fk_segui_categoria_persona_idx` (`persona` ASC),
    INDEX `fk_segui_categoria_categoria_idx` (`categoria` ASC),
    CONSTRAINT `fk_segui_categoria_categoria1`
    FOREIGN KEY (`categoria`)
    REFERENCES `tinkleart`.`categoria` (`idcategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_segui_categoria_persona`
    FOREIGN KEY (`persona`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`tentativi_accesso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`tentativi_accesso` (
  `persona` INT NOT NULL,
  `ora` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`persona`,`ora`),
  INDEX `fk_stentativi_accesso_persona_idx` (`persona` ASC),
  CONSTRAINT `fk_tentativi_accesso_persona`
    FOREIGN KEY (`persona`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- Table `tinkleart`.`tentativi_recupero`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tinkleart`.`tentativi_recupero` (
  `idrecupero` INT NOT NULL AUTO_INCREMENT,
  `tentativo` CHAR(128) NOT NULL, 
  `ora` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `persona` INT NOT NULL,
  PRIMARY KEY (`idrecupero`),
  INDEX `fk_stentativi_recupero_persona_idx` (`persona` ASC),
  CONSTRAINT `fk_tentativi_recupero_persona`
    FOREIGN KEY (`persona`)
    REFERENCES `tinkleart`.`persona` (`idpersona`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;