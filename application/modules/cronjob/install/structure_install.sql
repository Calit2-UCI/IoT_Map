DROP TABLE IF EXISTS `[prefix]cronjob`;
CREATE TABLE IF NOT EXISTS `[prefix]cronjob` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_execute` datetime NOT NULL,
  `module` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `cron_tab` varchar(50) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `in_process` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]cronjob_log`;
CREATE TABLE IF NOT EXISTS `[prefix]cronjob_log` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `date_add` datetime NOT NULL,
  `cron_id` int(3) NOT NULL,
  `function_result` varchar(50) NOT NULL,
  `output` text NOT NULL,
  `errors` text NOT NULL,
  `execution_time` varchar(50) NOT NULL,
  `memory_usage` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_id` (`cron_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;