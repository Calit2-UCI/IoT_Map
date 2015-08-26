<?php

if (!defined('BASEPATH')) 	exit('No direct script access allowed');

$mimes_categories = array(
	'documents' => array('doc', 'docx', 'pdf', 'ppt', 'rtf', 'text', 'txt', 'word', 'xls', 'xlsx', 'csv', 'xml'),
	'images' => array('bmp', 'gif', 'jpeg', 'jpg', 'png'),
	'graphics' => array('ai', 'eps', 'ps', 'psd'),
	'archives' => array('zip', 'rar', '7z', 'tar', 'gz', 'tgz'),
	'audio' => array('mp3', 'wav'),
	'video' => array('mpeg', 'mpg', 'mov', 'avi', 'wmv', 'flv', 'mkv'),
	'others' => array('swf'),
);
