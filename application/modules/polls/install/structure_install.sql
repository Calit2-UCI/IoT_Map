
DROP TABLE IF EXISTS `[prefix]polls`;
CREATE TABLE IF NOT EXISTS `[prefix]polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_type` varchar(50) NOT NULL,
  `answer_type` int(1) NOT NULL,
  `question` text NOT NULL,
  `language` int(1) NOT NULL,
  `use_comments` int(1) NOT NULL,
  `sorter` int(1) NOT NULL,
  `show_results` int(1) NOT NULL,
  `use_expiration` int(1) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `status` int(1) NOT NULL,
  `answers_languages` text NOT NULL,
  `answers_colors` text NOT NULL,
  `results` text NOT NULL,
  `responds_count` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]polls_responses`;
CREATE TABLE IF NOT EXISTS `[prefix]polls_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agent` VARCHAR(255) NOT NULL,
  `ip` VARCHAR(15) NOT NULL,
  `date_add` datetime NOT NULL,
  `answer_1` int(11) NOT NULL,
  `answer_2` int(11) NOT NULL,
  `answer_3` int(11) NOT NULL,
  `answer_4` int(11) NOT NULL,
  `answer_5` int(11) NOT NULL,
  `answer_6` int(11) NOT NULL,
  `answer_7` int(11) NOT NULL,
  `answer_8` int(11) NOT NULL,
  `answer_9` int(11) NOT NULL,
  `answer_10` int(11) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
