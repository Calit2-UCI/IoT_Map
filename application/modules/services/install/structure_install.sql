DROP TABLE IF EXISTS `[prefix]services`;
CREATE TABLE IF NOT EXISTS `[prefix]services` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(100) NOT NULL,
  `template_gid` varchar(100) NOT NULL,
  `type` enum('internal', 'tariff', 'package', 'membership'),
  `user_type_disabled` text NULL, 
  `user_type_disabled_code` bigint(20) NOT NULL, 
  `pay_type` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `price` float NOT NULL,
  `data_admin` text NOT NULL,
  `lds` text NOT NULL,
  `id_membership` int(11) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]services_log`;
CREATE TABLE IF NOT EXISTS `[prefix]services_log` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `id_service` int(3) NOT NULL,
  `user_data` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_service` (`id_service`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]services_templates`;
CREATE TABLE IF NOT EXISTS `[prefix]services_templates` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(100) NOT NULL,
  `callback_module` varchar(50) NOT NULL,
  `callback_model` varchar(50) NOT NULL,
  `callback_buy_method` varchar(150) NOT NULL,
  `callback_activate_method` varchar(150) NOT NULL,
  `callback_validate_method` varchar(150) NOT NULL,
  `price_type` tinyint(3) NOT NULL,
  `data_admin` text NOT NULL,
  `data_user` text NOT NULL,
  `lds` text NOT NULL,
  `date_add` datetime NOT NULL,
  `moveable` tinyint(3) NOT NULL,
  `is_membership` tinyint(1) NOT NULL,
  `data_membership` text NOT NULL,
  `alert_activate` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]services_users`;
CREATE TABLE IF NOT EXISTS `[prefix]services_users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `service_gid` varchar(100) NOT NULL,
  `template_gid` varchar(100) NOT NULL,
  `service` text NOT NULL,
  `template` text NOT NULL,
  `payment_data` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `date_expired` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `count` int(3) NOT NULL DEFAULT '1',
  `id_users_package` int(3) NOT NULL,
  `id_users_membership` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_3` (`id_user`,`date_created`,`date_modified`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
