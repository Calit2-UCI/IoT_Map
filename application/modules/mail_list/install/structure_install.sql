DROP TABLE IF EXISTS `[prefix]mail_list_filters`;
CREATE TABLE IF NOT EXISTS `[prefix]mail_list_filters` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `search_data` text NOT NULL,
  `date_search` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;