DROP TABLE IF EXISTS `[prefix]winks`;
CREATE TABLE IF NOT EXISTS `[prefix]winks` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_from` INT(10) UNSIGNED NOT NULL COMMENT '@[prefix]users:id',
	`id_to` INT(10) UNSIGNED NOT NULL COMMENT '@[prefix]users:id',
	`type` ENUM('new','replied','ignored') NOT NULL DEFAULT 'new',
	`date` DATETIME NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `users` (`id_from`, `id_to`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8;