DROP TABLE IF EXISTS `[prefix]sitemap_modules`;
CREATE TABLE IF NOT EXISTS `[prefix]sitemap_modules`(
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(25) NOT NULL,
  `model_name` varchar(50) NOT NULL,
  `get_urls_method` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_gid` (`module_gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;