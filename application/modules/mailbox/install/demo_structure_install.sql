INSERT INTO `[prefix]mailbox` (`id`, `id_pair`, `id_reply`, `id_user`, `id_from_user`, `id_to_user`, `subject`, `message`, `is_new`, `date_add`, `date_read`, `date_trash`, `id_thread`, `folder`, `from_folder`, `from_spam`, `attaches_count`, `notified`, `search_field`) VALUES
(1,	2,	0,	15,	15,	16,	'Hello William',	'How are you? )',	0,	'2013-10-29 16:33:58',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'outbox',	NULL,	NULL,	0,	0,	'William William Elk Hello William'),
(2,	1,	0,	16,	15,	16,	'Hello William',	'How are you? )',	1,	'2013-10-29 16:33:57',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'inbox',	NULL,	NULL,	0,	0,	'Annie Anna Crawford Hello William'),
(3,	0,	0,	15,	15,	0,	'hey',	'Hello Ivan,\nI think we met at Ben\'s last Friday?',	0,	'2013-10-29 16:42:08',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'drafts',	NULL,	NULL,	0,	0,	'hey'),
(4,	0,	0,	2,	2,	1,	'',	'',	0,	'2013-10-30 12:47:30',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'drafts',	NULL,	NULL,	0,	0,	'ivan22 Ivan Fateev'),
(5,	6,	0,	1,	1,	4,	'hey',	'Hello Maria,\nHow are you? A nice picture, by the way :)',	0,	'2013-10-30 18:37:07',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'outbox',	NULL,	NULL,	0,	0,	'Maria Maria Santos hey'),
(6,	5,	0,	4,	1,	4,	'hey',	'Hello Maria,\nHow are you? A nice picture, by the way :)',	1,	'2013-10-30 18:37:06',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'inbox',	NULL,	NULL,	0,	0,	'ivan22 Ivan Fateev hey');

INSERT INTO `[prefix]mailbox_services` (`id`, `id_user`, `gid_service`, `service_data`, `date_add`, `date_modified`) VALUES
(1,	15,	'send_message_service',	'a:1:{s:13:\"message_count\";i:9;}',	'2013-10-29 16:33:45',	'2013-10-29 16:33:57'),
(2,	2,	'send_message_service',	'a:1:{s:13:\"message_count\";i:10;}',	'2013-10-30 12:47:27',	'2013-10-30 12:47:27'),
(3,	1,	'send_message_service',	'a:1:{s:13:\"message_count\";i:9;}',	'2013-10-30 18:36:40',	'2013-10-30 18:37:06');