DROP TABLE IF EXISTS `[prefix]payments`;
CREATE TABLE IF NOT EXISTS `[prefix]payments` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `payment_type_gid` varchar(20) NOT NULL,
  `id_user` int(3) NOT NULL,
  `amount` float NOT NULL,
  `currency_gid` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `system_gid` varchar(20) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `payment_data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_type_gid` (`payment_type_gid`,`id_user`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]payments_currency`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_currency` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(10) NOT NULL,
  `abbr` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `per_base` decimal(7,4) NOT NULL,
  `template` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]payments_currency` VALUES(1, 'USD', '$', 'American Dollar', '1', '[abbr][value|dec_part:2|dec_sep:.|gr_sep: ]', 1);
INSERT INTO `[prefix]payments_currency` VALUES(2, 'RUB', 'руб.', 'Российский рубль', '0.0304', '[value|dec_part:2|dec_sep:.|gr_sep: ] [abbr]', 0);
INSERT INTO `[prefix]payments_currency` VALUES(3, 'EUR', '€', 'Euro', '1.3411', '[abbr][value|dec_part:2|dec_sep:.|gr_sep: ]', 0);

DROP TABLE IF EXISTS `[prefix]payments_log`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_log` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `system_gid` varchar(20) NOT NULL,
  `log_type` varchar(10) NOT NULL,
  `payment_data` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]payments_systems`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_systems` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `in_use` tinyint(3) NOT NULL,
  `date_add` datetime NOT NULL,
  `settings_data` text NOT NULL,
  `tarifs_type` tinyint(4) NOT NULL,
  `tarifs_editable` tinyint(1) NOT NULL,
  `tarifs_status` text NOT NULL,
  `tarifs_data` text NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `in_use` (`in_use`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]payments_systems` VALUES(NULL, 'offline', 'Offline payment', 1, '2011-05-18 11:36:00', '', 0, 0, '', '', 'logo_offline.png');

DROP TABLE IF EXISTS `[prefix]payments_type`;
CREATE TABLE IF NOT EXISTS `[prefix]payments_type` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `gid` varchar(30) NOT NULL,
  `callback_module` varchar(50) NOT NULL,
  `callback_model` varchar(50) NOT NULL,
  `callback_method` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
