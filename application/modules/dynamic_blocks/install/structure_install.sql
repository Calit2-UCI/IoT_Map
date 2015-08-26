DROP TABLE IF EXISTS `[prefix]dynblocks_areas`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_areas` (
 `id` int(3) NOT NULL AUTO_INCREMENT,
 `gid` varchar(30) NOT NULL,
 `name` varchar(250) NOT NULL,
 `date_add` datetime NOT NULL,
 PRIMARY KEY (`id`),
 KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'index-page', 'Default theme index page', '2011-09-08 10:26:06');
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'mediumturquoise', 'MediumTurquoise theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'lavender', 'Lavender theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'adult', 'Couple theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'matrimonial', 'Matrimonial theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'boyfriends', 'Boyfriend theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'girlfriends', 'Girlfriend theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'niche', 'Niche theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'guys', 'Guys theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'christian', 'Christian theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'community', 'Community theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'companions', 'Companions theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'girls', 'Girls theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'jewish', 'Jewish singles theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'lovers', 'Hobby theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'neighbourhood', 'Neighbors theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'blackonwhite', 'Black on white theme index page', '2014-06-30 15:18:49'); 
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'deepblue', 'Deep blue theme index page', '2014-06-30 15:18:49');
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'autumn_walk', 'Autumn walk theme index page', '2014-08-26 00:00:00');
INSERT INTO `[prefix]dynblocks_areas` VALUES (NULL, 'persimmon_red', 'Persimmon red', '2015-01-23 00:00:00');

DROP TABLE IF EXISTS `[prefix]dynblocks_area_block`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_area_block` (
 `id` int(3) NOT NULL AUTO_INCREMENT,
 `id_area` int(3) NOT NULL,
 `id_block` int(3) NOT NULL,
 `params` text NOT NULL,
 `view_str` varchar(100) NOT NULL,
 `width` tinyint(4) NOT NULL,
 `sorter` int(3) NOT NULL,
 `cache_time` int(3) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `id_area` (`id_area`,`id_block`,`sorter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]dynblocks_blocks`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_blocks` (
 `id` int(3) NOT NULL AUTO_INCREMENT,
 `gid` varchar(50) NOT NULL,
 `name` VARCHAR( 255 ) NOT NULL,
 `module` varchar(50) NOT NULL,
 `model` varchar(50) NOT NULL,
 `method` varchar(100) NOT NULL,
 `min_width` tinyint(4) NOT NULL,
 `params` text NOT NULL,
 `views` text NOT NULL,
 `date_add` datetime NOT NULL,
 `date_modified` datetime NOT NULL,
 PRIMARY KEY (`id`),
 KEY `gid` (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]dynblocks_presets`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_presets` (
 `id` int(3) NOT NULL AUTO_INCREMENT,
 `gid` varchar(30) NOT NULL,
 `gid_area` varchar(30) NOT NULL,
 `name` varchar(30) NOT NULL,
 `logo` varchar(255) NOT NULL,
 `comment` varchar(255) NOT NULL,
 `editable` tinyint(1) NOT NULL,
 `date_add` datetime NOT NULL,
 PRIMARY KEY (`id`),
 KEY `gid` (`gid`),
 KEY `gid_area` (`gid_area`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'index-page', 'index-page', 'Index page', 'index-page.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'mediumturquoise', 'mediumturquoise', 'Mediumturquoise', 'mediumturquoise.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'lavender', 'lavender', 'Lavender', 'lavender.png', '', 1, '2015-01-19 18:59:06');
-- INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'adult', 'adult', 'Adult', 'adult.png', '', 1, '2015-01-19 18:59:06');
-- INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'matrimonial', 'matrimonial', 'Matrimonial', 'matrimonial.png', '', 1, '2015-01-19 18:59:06');
-- INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'boyfriends', 'boyfriends', 'Boyfriends', 'boyfriends.png', '', 1, '2015-01-19 18:59:06');
-- INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'girlfriends', 'girlfriends', 'Girlfriends', 'girlfriends.png', '', 1, '2015-01-19 18:59:06');
-- INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'niche', 'niche', 'Niche', 'niche.png', '', 1, '2015-01-19 18:59:06');
-- INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'guys', 'guys', 'Guys', 'guys.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'christian', 'christian', 'Christian', 'christian.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'community', 'community', 'Community', 'community.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'companions', 'companions', 'Companions', 'companions.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'girls', 'girls', 'Girls', 'girls.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'jewish', 'jewish', 'Jewish', 'jewish.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'lovers', 'lovers', 'Lovers', 'lovers.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'neighbourhood', 'neighbourhood', 'Neighbourhood', 'neighbourhood.png', '', 1, '2015-01-19 18:59:06');
INSERT INTO `[prefix]dynblocks_presets` VALUES (NULL, 'persimmon_red', 'persimmon_red', 'Persimmon red', 'persimmon_red.png', '', 1, '2015-01-19 18:59:06');

DROP TABLE IF EXISTS `[prefix]dynblocks_presets_block`;
CREATE TABLE IF NOT EXISTS `[prefix]dynblocks_presets_block` (
 `id` int(3) NOT NULL AUTO_INCREMENT,
 `id_preset` int(3) NOT NULL,
 `gid_area` varchar(30) NOT NULL,
 `id_block` int(3) NOT NULL,
 `params` text NOT NULL,
 `view_str` varchar(100) NOT NULL,
 `width` tinyint(4) NOT NULL,
 `sorter` int(3) NOT NULL,
 `cache_time` int(3) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `gid_area` (`gid_area`,`id_block`,`sorter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;