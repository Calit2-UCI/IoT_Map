DROP TABLE IF EXISTS `[prefix]chats`;
CREATE TABLE `[prefix]chats` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`gid` VARCHAR(255) NOT NULL,
	`active` TINYINT(1) NOT NULL DEFAULT '0',
	`installed` TINYINT(1) NOT NULL DEFAULT '0',
	`activities` SET('own_page','include') NOT NULL,
	`settings` TEXT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `name` (`gid`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8;