ALTER TABLE `[prefix]users`
	ADD COLUMN `net_id` INT(11) NOT NULL DEFAULT '0' AFTER `id`,
	ADD COLUMN `net_status` ENUM('not_agree','ready','processed','error','ok','need_ask') NOT NULL DEFAULT 'ready',
	ADD COLUMN `net_info` VARCHAR(255) NOT NULL DEFAULT '',
	ADD COLUMN `net_is_incomer` TINYINT(1) NOT NULL DEFAULT '0',
	ADD INDEX `net_id_status` (`net_id`,`net_status`);
    
UPDATE `[prefix]users` SET `net_status`='need_ask';

ALTER TABLE `[prefix]users_deleted`
	ADD COLUMN `net_id` INT(11) NOT NULL DEFAULT '0' AFTER `id`,
	ADD COLUMN `net_status` ENUM('not_agree','ready','processed','error','ok','need_ask') NOT NULL DEFAULT 'not_agree',
	ADD COLUMN `net_info` VARCHAR(255) NOT NULL DEFAULT '',
	ADD COLUMN `net_is_incomer` TINYINT(1) NOT NULL DEFAULT '0';

DROP TABLE IF EXISTS `[prefix]net_temp`;
CREATE TABLE `[prefix]net_temp` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`net_id` INT(10) UNSIGNED NOT NULL,
	`local_id` INT(10) UNSIGNED NOT NULL,
	`action` ENUM('add','update','remove') NOT NULL,
	`type` ENUM('in','out') NOT NULL,
	`data` TEXT NOT NULL,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]net_events_handlers`;
CREATE TABLE `[prefix]net_events_handlers` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`event` VARCHAR(50) NOT NULL,
	`module` VARCHAR(50) NOT NULL,
	`model` VARCHAR(50) NOT NULL,
	`method` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
