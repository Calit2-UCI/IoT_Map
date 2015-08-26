DROP TABLE IF EXISTS `[prefix]menu`;
CREATE TABLE IF NOT EXISTS `[prefix]menu` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `check_permissions` tinyint(3) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]menu_items`;
CREATE TABLE IF NOT EXISTS `[prefix]menu_items` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `menu_id` int(3) NOT NULL,
  `parent_id` int(3) NOT NULL,
  `gid` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `sorter` tinyint(3) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `indicator_gid`  varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `sorter` (`sorter`),
  KEY `parent_id` (`parent_id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]menu_indicators`;
CREATE TABLE IF NOT EXISTS `[prefix]menu_indicators` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(255) NOT NULL,
  `user_id` int(3) NOT NULL DEFAULT 0,
  `value` varchar(255) DEFAULT '' NOT NULL,
  `uid` varchar(255) DEFAULT '' NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]menu_indicators_types`;
CREATE TABLE IF NOT EXISTS `[prefix]menu_indicators_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(255) NOT NULL,
  `auth_type` enum("user", "admin", "mudule") NOT NULL DEFAULT 'user',
  `delete_by_cron` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
