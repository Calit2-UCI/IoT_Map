DROP TABLE IF EXISTS `[prefix]languages`;
CREATE TABLE IF NOT EXISTS `[prefix]languages` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `rtl` enum('rtl','ltr') NOT NULL,
  `is_default` tinyint(3) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]lang_dedicate_modules`;
CREATE TABLE IF NOT EXISTS `[prefix]lang_dedicate_modules` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module` varchar(25) NOT NULL,
  `model` varchar(100) NOT NULL,
  `method_add` varchar(100) NOT NULL,
  `method_delete` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]lang_dedicate_modules` VALUES(NULL, '', 'pg_theme', 'lang_dedicate_module_callback_add', 'lang_dedicate_module_callback_delete', '2010-11-19 13:14:00');
INSERT INTO `[prefix]lang_dedicate_modules` VALUES(NULL, '', 'pg_seo', 'lang_dedicate_module_callback_add', 'lang_dedicate_module_callback_delete', '2010-11-19 13:14:00');

DROP TABLE IF EXISTS `[prefix]lang_ds`;
CREATE TABLE IF NOT EXISTS `[prefix]lang_ds` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(100) NOT NULL,
  `gid` varchar(100) NOT NULL,
  `option_gid` varchar(50) NOT NULL default '',
  `type` enum('header','option') NOT NULL,
  `sorter` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_gid` (`module_gid`),
  KEY `sorter` (`sorter`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]lang_pages`;
CREATE TABLE IF NOT EXISTS `[prefix]lang_pages` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(100) NOT NULL,
  `gid` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_gid` (`module_gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]libraries`;
CREATE TABLE `[prefix]libraries` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gid` VARCHAR( 25 ) NOT NULL ,
  `version` FLOAT NOT NULL,
  `name` VARCHAR( 100 ) NOT NULL ,
  `date_add` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]modules`;
CREATE TABLE IF NOT EXISTS `[prefix]modules` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(25) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `module_description` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `version` FLOAT NOT NULL,
  `date_add` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_gid` (`module_gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]modules_config`;
CREATE TABLE IF NOT EXISTS `[prefix]modules_config` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(25) NOT NULL,
  `config_gid` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_gid` (`module_gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]modules_methods`;
CREATE TABLE IF NOT EXISTS `[prefix]modules_methods` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(25) NOT NULL,
  `controller` varchar(35) NOT NULL,
  `method` varchar(100) NOT NULL,
  `access` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_gid` (`module_gid`),
  KEY `module_gid_2` (`module_gid`,`controller`,`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]sessions`;
CREATE TABLE IF NOT EXISTS `[prefix]sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_address` bigint(19) DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `user_data` text,
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]themes`;
CREATE TABLE IF NOT EXISTS `[prefix]themes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) NOT NULL,
  `theme_type` enum('admin','user') NOT NULL DEFAULT 'user',
  `scheme` varchar(100) NOT NULL,
  `active` tinyint(3) NOT NULL DEFAULT '0',
  `theme_name` varchar(255) NOT NULL,
  `theme_description` varchar(255) NOT NULL,
  `setable` tinyint(3) NOT NULL,
  `logo_width` int(3) NOT NULL,
  `logo_height` int(3) NOT NULL,
  `logo_default` varchar(255) NOT NULL,
  `mini_logo_width` int(3) NOT NULL,
  `mini_logo_height` int(3) NOT NULL,
  `mini_logo_default` varchar(255) NOT NULL,
  `mobile_logo_width` int(3) NOT NULL,
  `mobile_logo_height` int(3) NOT NULL,
  `mobile_logo_default` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `default` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]themes` VALUES(1, 'admin', 'admin', 'default', 1, 'Admin area theme', 'Default admin template; PilotGroup', '1', '180', '150', 'logo.png', '160', '160', 'logo.png', '160', '32', 'mobile_logo.png');
INSERT INTO `[prefix]themes` VALUES(2, 'default', 'user', 'default', 1, 'User area theme', 'Default user side template; PilotGroup', '1', '111', '21', 'logo.png', '30', '30', 'mini_logo.png', '160', '32', 'mobile_logo.png');

DROP TABLE IF EXISTS `[prefix]themes_colorsets`;
CREATE TABLE IF NOT EXISTS `[prefix]themes_colorsets` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `set_name` varchar(255) NOT NULL,
  `set_gid` varchar(100) NOT NULL,
  `id_theme` int(3) NOT NULL,
  `color_settings` text NOT NULL,
  `active` tinyint(3) NOT NULL,
  `scheme_type` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_theme` (`id_theme`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[prefix]themes_colorsets` (`id`, `set_name`, `set_gid`, `id_theme`, `color_settings`, `active`, `scheme_type`) VALUES
(1, 'Default color scheme', 'default', 1, 'a:24:{s:7:"html_bg";s:6:"F2F2F2";s:7:"main_bg";s:6:"29B43D";s:9:"header_bg";s:6:"4C4C4C";s:9:"footer_bg";s:6:"208B2F";s:13:"menu_hover_bg";s:6:"F6E4A0";s:8:"hover_bg";s:6:"E2EFE4";s:8:"popup_bg";s:6:"FFFFFF";s:12:"highlight_bg";s:6:"F7FFF8";s:11:"input_color";s:6:"208D30";s:14:"input_bg_color";s:6:"FFFFFF";s:12:"status_color";s:6:"E5863A";s:10:"link_color";s:6:"208B2F";s:10:"font_color";s:6:"4C4C4C";s:12:"header_color";s:6:"F27000";s:11:"descr_color";s:6:"808080";s:14:"contrast_color";s:6:"FFFFFF";s:15:"delimiter_color";s:6:"E5E5E5";s:11:"font_family";s:82:"''SegoeUINormal'', Arial, ''Lucida Grande'',''Lucida Sans Unicode'', Verdana, sans-serif";s:14:"main_font_size";s:2:"13";s:15:"input_font_size";s:2:"15";s:12:"h1_font_size";s:2:"20";s:12:"h2_font_size";s:2:"17";s:15:"small_font_size";s:2:"12";s:20:"search_btn_font_size";s:2:"22";}', 1, 'light'),
(2, 'Default color scheme', 'default', 2, 'a:32:{s:14:"index_bg_image";s:18:"index_bg_image.jpg";s:17:"index_bg_image_bg";s:6:"FFFFFF";s:21:"index_bg_image_scroll";s:1:"1";s:27:"index_bg_image_adjust_width";b:0;s:28:"index_bg_image_adjust_height";b:0;s:23:"index_bg_image_repeat_x";b:0;s:23:"index_bg_image_repeat_y";b:0;s:18:"index_bg_image_ver";s:1:"4";s:7:"html_bg";s:6:"FFFFFF";s:7:"main_bg";s:6:"FE3D0C";s:9:"header_bg";s:6:"F2F2F2";s:9:"footer_bg";s:6:"F2F2F2";s:13:"menu_hover_bg";s:6:"F2F2F2";s:8:"hover_bg";s:6:"F2F2F2";s:8:"popup_bg";s:6:"F2F2F2";s:12:"highlight_bg";s:6:"F2F2F2";s:11:"input_color";s:6:"111111";s:14:"input_bg_color";s:6:"FFFFFF";s:12:"status_color";s:6:"FE3D0C";s:10:"link_color";s:6:"FE3D0C";s:10:"font_color";s:6:"111111";s:12:"header_color";s:6:"777777";s:11:"descr_color";s:6:"777777";s:14:"contrast_color";s:6:"FFFFFF";s:15:"delimiter_color";s:6:"C5C5C5";s:11:"font_family";s:84:"''Segoe UI Normal'', Arial, ''Lucida Grande'',''Lucida Sans Unicode'', Verdana, sans-serif";s:14:"main_font_size";s:2:"13";s:15:"input_font_size";s:2:"15";s:12:"h1_font_size";s:2:"22";s:12:"h2_font_size";s:2:"17";s:15:"small_font_size";s:2:"12";s:20:"search_btn_font_size";s:2:"22";}', 1, 'light');

DROP TABLE IF EXISTS `[prefix]seo_modules`;
CREATE TABLE IF NOT EXISTS `[prefix]seo_modules` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `module_gid` varchar(25) NOT NULL,
  `model_name` varchar(50) NOT NULL,
  `get_settings_method` varchar(100) NOT NULL,
  `get_rewrite_vars_method` varchar(100) NOT NULL,
  `get_sitemap_urls_method` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `module_gid` (`module_gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]seo_settings`;
CREATE TABLE IF NOT EXISTS `[prefix]seo_settings` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `controller` enum('user','admin','custom') NOT NULL,
  `module_gid` varchar(25) NOT NULL,
  `method` varchar(50) NOT NULL,
  `noindex` tinyint(3) NOT NULL,
  `url_template` varchar(255) NOT NULL,
  `lang_in_url` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `model` (`module_gid`),
  KEY `controller` (`controller`),
  KEY `method` (`method`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
