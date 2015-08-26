DROP TABLE IF EXISTS `[prefix]tokens`;
CREATE TABLE IF NOT EXISTS `[prefix]tokens` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `token` varchar(40) NOT NULL DEFAULT '0',
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;