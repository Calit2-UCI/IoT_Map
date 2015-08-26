CREATE TABLE IF NOT EXISTS `[prefix]user_connections` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `service_id` int(3) NOT NULL,
  `access_token` TEXT NOT NULL,
  `access_token_secret` TEXT NOT NULL,
  `data` TEXT NOT NULL,
  `date_end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;