DROP TABLE IF EXISTS `[prefix]kisses`;
CREATE TABLE IF NOT EXISTS `[prefix]kisses` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`image` varchar(150) NOT NULL COMMENT 'name image',
	`sorter` INT(4) UNSIGNED NOT NULL COMMENT 'sorter',
	`date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]kisses_users`;
CREATE TABLE IF NOT EXISTS `[prefix]kisses_users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`image` varchar(150) NOT NULL COMMENT 'name image',
	`user_to` INT(10) UNSIGNED NOT NULL COMMENT 'user to',
	`user_from` INT(10) UNSIGNED NOT NULL COMMENT 'user from',
	`message` text NOT NULL COMMENT 'kiss message',
	`date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`mark` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'read on not to read',
	PRIMARY KEY (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8;
