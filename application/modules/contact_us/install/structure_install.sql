DROP TABLE IF EXISTS `[prefix]contact_us`;
CREATE TABLE IF NOT EXISTS `[prefix]contact_us` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `mails` text NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `[prefix]contact_us` VALUES(1, 'a:2:{i:0;s:13:"test@test.com";i:1;s:13:"mail@test.com";}', '2011-05-10 08:47:01');
INSERT INTO `[prefix]contact_us` VALUES(2, 'a:1:{i:0;s:13:"mail@test.com";}', '2011-05-10 09:12:57');
INSERT INTO `[prefix]contact_us` VALUES(3, 'a:1:{i:0;s:13:"test@test.com";}', '2011-05-10 09:23:06');
