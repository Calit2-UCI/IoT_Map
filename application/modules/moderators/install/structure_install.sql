DROP TABLE IF EXISTS `[prefix]ausers_moderate_methods`;
CREATE TABLE `[prefix]ausers_moderate_methods` (
`id` INT( 3 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`module` VARCHAR( 25 ) NOT NULL ,
`method` VARCHAR( 100 ) NOT NULL ,
`is_default` TINYINT( 3 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8;