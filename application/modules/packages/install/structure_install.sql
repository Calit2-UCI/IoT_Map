DROP TABLE IF EXISTS `[prefix]packages`;
CREATE TABLE IF NOT EXISTS `[prefix]packages` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `price` float NOT NULL,
  `pay_type` tinyint(3) NOT NULL,
  `services_list` text NOT NULL,
  `date_add` datetime NOT NULL,
  `available_days` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]packages_users`;
CREATE TABLE IF NOT EXISTS `[prefix]packages_users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `id_package` int(3) NOT NULL,
  `price` float NOT NULL,
  `till_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_2` (`id`, `id_user`, `id_package`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

