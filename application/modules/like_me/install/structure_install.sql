DROP TABLE IF EXISTS `[prefix]like_me`;
CREATE TABLE IF NOT EXISTS `[prefix]like_me` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `id_profile` int(10) NOT NULL,
  `status_match` tinyint(2) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
