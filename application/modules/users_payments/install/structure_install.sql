DROP TABLE IF EXISTS `[prefix]user_account_list`;
CREATE TABLE IF NOT EXISTS `[prefix]user_account_list` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `id_user` int(3) NOT NULL,
  `date_add` datetime NOT NULL,
  `message` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `price_type` enum('add','spend') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user_2` (`id_user`,`date_add`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
