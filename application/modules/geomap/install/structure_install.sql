DROP TABLE IF EXISTS `[prefix]geomap_drivers`;
CREATE TABLE IF NOT EXISTS `[prefix]geomap_drivers` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` varchar(30) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `regkey` varchar(100) NOT NULL,
  `need_regkey` tinyint(1) NOT NULL,
  `link` varchar(100) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]geomap_drivers` VALUES (1, 'googlemapsv3', 1, '', 1, 'https://code.google.com/apis/console', '2011-03-21 15:26:54', '2011-03-21 15:26:54');
INSERT INTO `[prefix]geomap_drivers` VALUES (2, 'yandexmapsv2', 0, '', 0, 'http://api.yandex.ru/maps/doc/intro/concepts/intro.xml', '2011-03-21 15:26:54', '2011-03-21 15:26:54');
INSERT INTO `[prefix]geomap_drivers` VALUES (3, 'bingmapsv7', 0, '', 1, 'https://www.bingmapsportal.com/', '2011-10-23 15:26:54', '2011-10-23 15:26:54');

DROP TABLE IF EXISTS `[prefix]geomap_settings`;
CREATE TABLE IF NOT EXISTS `[prefix]geomap_settings` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `map_gid` varchar(50) NOT NULL,
  `id_user` int(3) NOT NULL,
  `id_object` int(3) NOT NULL,
  `gid` varchar(50) NOT NULL,
  `lat` decimal(10,7) NOT NULL,
  `lon` decimal(10,7) NOT NULL,
  `zoom` tinyint(3) NOT NULL,
  `view_type` tinyint(3) NOT NULL,
  `view_settings` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `map_gid` (`map_gid`,`id_user`,`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]geomap_settings` VALUES (1, 'googlemapsv3', 0, 0, '', 55.7497723, 37.6242685, 10, 4, '');
INSERT INTO `[prefix]geomap_settings` VALUES (2, 'yandexmapsv2', 0, 0, '', 55.7497723, 37.6242685, 5, 1, '');
INSERT INTO `[prefix]geomap_settings` VALUES (3, 'bingmapsv7', 0, 0, '', 55.7497723, 37.6242685, 5, 1, '');
-- INSERT INTO `[prefix]geomap_settings` VALUES (4, 'googlemapsv3', 0, 0, 'example', 0.0000000, 0.0000000, 4, 1, '');
-- INSERT INTO `[prefix]geomap_settings` VALUES (5, 'bingmapsv7', 0, 0, 'example', 0.0000000, 0.0000000, 4, 1, '');
-- INSERT INTO `[prefix]geomap_settings` VALUES (6, 'yandexmapsv2', 0, 0, 'example', 0.0000000, 0.0000000, 4, 1, '');
