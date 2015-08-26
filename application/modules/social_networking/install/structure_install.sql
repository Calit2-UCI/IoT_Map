DROP TABLE IF EXISTS `[prefix]social_networking_services`;
CREATE TABLE IF NOT EXISTS `[prefix]social_networking_services` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `service_logo` varchar(100) NOT NULL,
  `authorize_url` TEXT NOT NULL,
  `access_key_url` TEXT NOT NULL,
  `app_enabled` tinyint(3) NOT NULL,
  `app_key` TEXT NOT NULL,
  `app_secret` TEXT NOT NULL,
  `oauth_enabled` tinyint(3) NOT NULL,
  `oauth_version` tinyint(3) NOT NULL,
  `oauth_status` tinyint(3) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]social_networking_pages`;
CREATE TABLE IF NOT EXISTS `[prefix]social_networking_pages` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `controller` varchar(35) NOT NULL,
  `method` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `data` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controller` (`controller`)
) ENGINE=MyISAM AUTO_INCREMENT=386 DEFAULT CHARSET=utf8;
