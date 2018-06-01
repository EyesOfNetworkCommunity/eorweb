CREATE TABLE `remediation_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE remediation_action ADD COLUMN id_group int(11) DEFAULT NULL;
