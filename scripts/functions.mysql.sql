DELIMITER $$

DROP FUNCTION IF EXISTS `passwordHash`$$

CREATE FUNCTION `passwordHash`(pass VARCHAR(64)) RETURNS char(32) CHARSET latin1
BEGIN
	RETURN md5(concat(pass,'Uhs*5t8H'));
    END$$
DELIMITER ;