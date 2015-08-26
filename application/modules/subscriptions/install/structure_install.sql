DROP TABLE IF EXISTS `[prefix]subscriptions`;
CREATE TABLE IF NOT EXISTS `[prefix]subscriptions` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `id_template` int(3) NOT NULL,
  `subscribe_type` varchar(10) NOT NULL,
  `id_content_type` int(3) NOT NULL,
  `scheduler` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]subscriptions_types`;
CREATE TABLE IF NOT EXISTS `[prefix]subscriptions_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `module` varchar(25) NOT NULL,
  `model` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]subscriptions_users`;
CREATE TABLE IF NOT EXISTS `[prefix]subscriptions_users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `id_subscription` int(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`id_user`, `id_subscription`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;