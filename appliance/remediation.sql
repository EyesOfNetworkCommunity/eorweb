CREATE TABLE `remediation` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(25) NOT NULL,
	`user_id` INT(11) NOT NULL,
	`date_demand` DATETIME NOT NULL,
	`date_validation` DATETIME NULL DEFAULT NULL,
	`state` VARCHAR(25) NOT NULL DEFAULT 'inactive',
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=42
;

CREATE TABLE `remediation_action` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`remediationID` INT(11) NOT NULL,
	`description` VARCHAR(25) NOT NULL,
	`type` VARCHAR(25) NOT NULL,
	`DateDebut` DATETIME NOT NULL,
	`DateFin` DATETIME NOT NULL,
	`source` VARCHAR(50) NOT NULL,
	`host` VARCHAR(50) NOT NULL,
	`service` VARCHAR(50) NOT NULL,
	`Action` VARCHAR(15) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `description` (`description`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=38
;
