-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE  TABLE IF NOT EXISTS `user` (
  `userId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(20) NOT NULL ,
  `userhash` VARCHAR(32) NOT NULL ,
  PRIMARY KEY (`userId`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `uniUsername` ON `user` (`username` ASC) ;


-- -----------------------------------------------------
-- Table `location`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `location` ;

CREATE  TABLE IF NOT EXISTS `location` (
  `locationId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `locationCity` VARCHAR(100) NOT NULL ,
  `locationState` ENUM('AL','AK','AS','AZ','AR','CA','CO','CT','DE','DC','FM','FL','GA','GU','HI','ID','IL','IN','IA','KS','KY','LA','ME','MH','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','MP','OH','OK','OR','PW','PA','PR','RI','SC','SD','TN','TX','UT','VT','VI','VA','WA','WV','WI','WY','AE','AA','AP') NOT NULL ,
  PRIMARY KEY (`locationId`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `uniLocation` ON `location` (`locationState` ASC, `locationCity` ASC) ;


-- -----------------------------------------------------
-- Table `contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contact` ;

CREATE  TABLE IF NOT EXISTS `contact` (
  `contactId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `userId` INT UNSIGNED NOT NULL ,
  `contactFirstName` VARCHAR(60) NOT NULL ,
  `contactLastName` VARCHAR(60) NOT NULL ,
  `contactZipCode` VARCHAR(10) NULL ,
  `locationId` INT UNSIGNED NULL ,
  PRIMARY KEY (`contactId`) ,
  CONSTRAINT `fk_contact_location`
    FOREIGN KEY (`locationId` )
    REFERENCES `location` (`locationId` )
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_contact_user1`
    FOREIGN KEY (`userId` )
    REFERENCES `user` (`userId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_contact_location` ON `contact` (`locationId` ASC) ;

CREATE INDEX `fk_contact_user1` ON `contact` (`userId` ASC) ;


-- -----------------------------------------------------
-- Table `interest`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `interest` ;

CREATE  TABLE IF NOT EXISTS `interest` (
  `interestId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `interestName` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`interestId`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `uniInterestName` ON `interest` (`interestName` ASC) ;


-- -----------------------------------------------------
-- Table `contact_interest`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contact_interest` ;

CREATE  TABLE IF NOT EXISTS `contact_interest` (
  `contactId` INT UNSIGNED NOT NULL ,
  `interestId` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`contactId`, `interestId`) ,
  CONSTRAINT `fk_contact_interest_contact1`
    FOREIGN KEY (`contactId` )
    REFERENCES `contact` (`contactId` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_contact_interest_interest1`
    FOREIGN KEY (`interestId` )
    REFERENCES `interest` (`interestId` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE INDEX `fk_contact_interest_contact1` ON `contact_interest` (`contactId` ASC) ;

CREATE INDEX `fk_contact_interest_interest1` ON `contact_interest` (`interestId` ASC) ;


-- -----------------------------------------------------
-- View `view_contact`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `view_contact` ;
CREATE  OR REPLACE VIEW `view_contact` AS
SELECT
c.*,
l.locationCity,
l.locationState,
GROUP_CONCAT(interestName) as interest
FROM contact c
LEFT JOIN location l
USING (locationId)
LEFT JOIN contact_interest ci
USING (contactId)
LEFT JOIN interest i
USING (interestId)
GROUP BY contactId
;