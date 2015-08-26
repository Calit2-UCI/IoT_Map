DROP TABLE IF EXISTS `[prefix]widgets`;
CREATE TABLE IF NOT EXISTS `[prefix]widgets` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL,
  `size` varchar(20) NOT NULL,
  `colors` text NULL,
  `settings` text NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `url` (`gid`, `url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
