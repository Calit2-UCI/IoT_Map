DROP TABLE IF EXISTS `[prefix]banners`;
CREATE TABLE IF NOT EXISTS `[prefix]banners` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `alt_text` varchar(255) NOT NULL,
  `approve` tinyint(3) NOT NULL,
  `banner_image` varchar(100) NOT NULL,
  `banner_place_id` tinyint(3) NOT NULL,
  `banner_type` tinyint(3) NOT NULL,
  `decline_reason` text NOT NULL,
  `expiration_date` datetime NOT NULL,
  `html` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `new_window` tinyint(3) NOT NULL,
  `is_admin` tinyint(3) NOT NULL,
  `number_of_clicks` int(3) NOT NULL,
  `number_of_views` int(3) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `user_id` int(3) NOT NULL,
  `stat_clicks` int(11) NOT NULL,
  `stat_views` int(11) NOT NULL,
  `user_activate_info` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_banner_group`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_banner_group` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `banner_id` int(3) NOT NULL,
  `group_id` int(3) NOT NULL,
  `place_id` int(3) NOT NULL,
  `is_admin` tinyint(3) NOT NULL,
  `positions` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`,`place_id`,`is_admin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_groups`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL DEFAULT '0',
  `gid` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_modules`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_modules` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) NOT NULL,
  `model_name` varchar(255) NOT NULL,
  `method_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_pages`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_pages` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `link` (`link`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_places`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_places` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `places_in_rotation` tinyint(3) NOT NULL,
  `rotate_time` tinyint(3) NOT NULL,
  `width` int(3) NOT NULL,
  `height` int(3) NOT NULL,
  `access` TINYINT( 3 ) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]banners_places` VALUES(1, '2011-06-09 11:45:10', '2011-09-09 08:57:24', 'bottom-banner', 'Bottom banner', 10, 0, 980, 90, 1);
INSERT INTO `[prefix]banners_places` VALUES(2, '2011-06-09 11:48:22', '2011-09-09 10:20:29', 'big-left-banner', 'Big left banner', 10, 0, 185, 155, 1);
INSERT INTO `[prefix]banners_places` VALUES(3, '2011-06-09 11:56:56', '2011-09-09 08:57:38', 'left-banner', 'Left banner', 10, 0, 185, 75, 1);
INSERT INTO `[prefix]banners_places` VALUES(4, '2011-06-09 11:56:56', '2011-09-09 08:57:38', 'top-banner', 'Top banner', 10, 0, 980, 90, 1);
INSERT INTO `[prefix]banners_places` VALUES(5, '2011-06-09 11:56:56', '2011-09-09 08:57:38', 'big-right-banner', 'Big right banner', 10, 0, 320, 250, 1);
INSERT INTO `[prefix]banners_places` VALUES(6, '2011-06-09 11:56:56', '2011-09-09 08:57:38', 'right-banner', 'Right banner', 10, 0, 320, 75, 1);

DROP TABLE IF EXISTS `[prefix]banners_place_group`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_place_group` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `place_id` int(3) NOT NULL,
  `group_id` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `place_id` (`place_id`,`group_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_statistics`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL DEFAULT '0',
  `report_type` set('year','month','week','day') NOT NULL,
  `year` year(4) NOT NULL,
  `month` tinyint(3) NOT NULL,
  `week` tinyint(3) NOT NULL,
  `day` tinyint(3) NOT NULL,
  `action` set('view','click') NOT NULL,
  `stat` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `banner_id` (`banner_id`),
  KEY `action` (`report_type`),
  KEY `report_type` (`report_type`),
  KEY `year` (`year`),
  KEY `month` (`month`),
  KEY `week` (`week`),
  KEY `day` (`day`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_statistics_hourly`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_statistics_hourly` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `banner_id` int(3) NOT NULL,
  `date` date NOT NULL,
  `hour` tinyint(3) NOT NULL,
  `action` set('view','click') NOT NULL,
  `stat` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `banner_id` (`banner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]banners_statistics_temp`;
CREATE TABLE IF NOT EXISTS `[prefix]banners_statistics_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `action` set('click','view') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
