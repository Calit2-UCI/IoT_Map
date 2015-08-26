DROP TABLE IF EXISTS `[prefix]aviary_modules`;
CREATE TABLE IF NOT EXISTS `[prefix]aviary_modules` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(25) NOT NULL,
  `model_name` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
