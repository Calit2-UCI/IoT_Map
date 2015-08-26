<?php

$fe_sections = array(
	array("data" => array( "gid" => "about-me", "editor_type_gid" => "users")),
	array("data" => array( "gid" => "my-interests", "editor_type_gid" => "users")),
);

$fe_fields = array(
	array("data" => array( "gid" => "weight", "section_gid" => "about-me", "editor_type_gid" => "users", "field_type" => "select", "fts" => "0", "settings_data" => 'a:3:{s:13:"default_value";i:0;s:9:"view_type";s:6:"select";s:12:"empty_option";b:1;}', "sorter" => "1", "options" => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11'))),
	array("data" => array( "gid" => "height", "section_gid" => "about-me", "editor_type_gid" => "users", "field_type" => "select", "fts" => "0", "settings_data" => 'a:3:{s:13:"default_value";i:0;s:9:"view_type";s:6:"select";s:12:"empty_option";b:1;}', "sorter" => "2", "options" => array('1', '2', '3', '4', '5', '6', '7', '8'))),
	array("data" => array( "gid" => "body", "section_gid" => "about-me", "editor_type_gid" => "users", "field_type" => "select", "fts" => "1", "settings_data" => 'a:3:{s:13:"default_value";i:0;s:9:"view_type";s:6:"select";s:12:"empty_option";b:1;}', "sorter" => "3", "options" => array('1', '2', '3', '4', '5', '6'))),
	array("data" => array( "gid" => "hair", "section_gid" => "about-me", "editor_type_gid" => "users", "field_type" => "select", "fts" => "1", "settings_data" => 'a:3:{s:13:"default_value";i:0;s:9:"view_type";s:6:"select";s:12:"empty_option";b:1;}', "sorter" => "4", "options" => array('1', '2', '3', '4', '5', '6', '7', '8'))),
	array("data" => array( "gid" => "style", "section_gid" => "about-me", "editor_type_gid" => "users", "field_type" => "multiselect", "fts" => "1", "settings_data" => 'a:2:{s:13:"default_value";s:0:"";s:9:"view_type";s:8:"checkbox";}', "sorter" => "6", "options" => array('1', '2', '3', '4', '5', '6'))),
	array("data" => array( "gid" => "ideal_date", "section_gid" => "my-interests", "editor_type_gid" => "users", "field_type" => "textarea", "fts" => "1", "settings_data" => 'a:3:{s:13:"default_value";s:0:"";s:8:"min_char";i:0;s:8:"max_char";i:0;}', "sorter" => "1", "options" => array())),
	array("data" => array( "gid" => "best_bd", "section_gid" => "my-interests", "editor_type_gid" => "users", "field_type" => "textarea", "fts" => "1", "settings_data" => 'a:3:{s:13:"default_value";s:0:"";s:8:"min_char";i:0;s:8:"max_char";i:0;}', "sorter" => "2", "options" => array())),
	array("data" => array( "gid" => "interests", "section_gid" => "my-interests", "editor_type_gid" => "users", "field_type" => "multiselect", "fts" => "1", "settings_data" => 'a:2:{s:13:"default_value";s:0:"";s:9:"view_type";s:8:"checkbox";}', "sorter" => "3", "options" => array('1', '2', '3', '4', '5', '6', '7'))),
);

$fe_forms = array(
	array('data' => array(
		'gid' => 'advanced_search',
		'editor_type_gid' => 'users',
		'name' => 'Search form',
		'field_data' => '',
		'is_system' => 1,
	)),
	array('data' => array(
		'gid' => 'perfect_match',
		'editor_type_gid' => 'users',
		'name' => 'Perfect match form',
		'field_data' => '',
		'is_system' => 1,
	)),
);

