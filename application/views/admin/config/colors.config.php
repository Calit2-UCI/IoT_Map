<?php

$scheme = array(
	'index_bg_image' => array('type'=>'file', 'autochange'=>'no', 'light_default'=> '', 'dark_default'=>''),
	'index_bg_image_bg' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'FFFFFF', 'dark_default'=>'FFFFFF'),
	'index_bg_image_scroll' => array('type'=>'checkbox', 'autochange'=>'no', 'light_default'=> '1', 'dark_default'=>'1'),
	'index_bg_image_adjust_width' => array('type'=>'checkbox', 'autochange'=>'no', 'light_default'=> '0', 'dark_default'=>'0'),
	'index_bg_image_adjust_height' => array('type'=>'checkbox', 'autochange'=>'no', 'light_default'=> '0', 'dark_default'=>'0'),
	'index_bg_image_repeat_x' => array('type'=>'checkbox', 'autochange'=>'no', 'light_default'=> '', 'dark_default'=>''),
	'index_bg_image_repeat_y' => array('type'=>'checkbox', 'autochange'=>'no', 'light_default'=> '', 'dark_default'=>''),
	'index_bg_image_ver' => array('type'=>'text', 'autochange'=>'no', 'light_default'=>1, 'dark_default'=>1),
	
	'html_bg' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'F2F2F2', 'dark_default'=>'343434'),
	'main_bg' => array('type'=>'color', 'autochange'=>'yes', 'light_default'=> '29b43d', 'dark_default'=>'568D2B'),
	'header_bg' => array('type'=>'color', 'autochange'=>'yes', 'light_default'=> '4C4C4C', 'dark_default'=>'272727'),
	'footer_bg' => array('type'=>'color', 'autochange'=>'yes', 'light_default'=> '208B2F', 'dark_default'=>'252525'),
	'menu_hover_bg' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'F6E4A0', 'dark_default'=>'323232'),
	//'hover_bg' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'E2EFE4', 'dark_default'=>'272727'),
	'popup_bg' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'FFFFFF', 'dark_default'=>'4C4C4C'),
	'highlight_bg' => array('type'=>'color', 'autochange'=>'footer_bg', 'light_default'=> 'f7fff8', 'dark_default'=>'4C4C4C'),
	'input_color' => array('type'=>'color', 'autochange'=>'yes', 'light_default'=> '208d30', 'dark_default'=>'396615'),
	'input_bg_color' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'FFFFFF', 'dark_default'=>'343434'),
	//'status_color' => array('type'=>'color', 'autochange'=>'yes', 'light_default'=> 'e5863a', 'dark_default'=>'DA7D38'),
	'link_color' => array('type'=>'color', 'autochange'=>'yes', 'light_default'=> '208b2f', 'dark_default'=>'7FC24A'),

	'font_color' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> '4c4c4c', 'dark_default'=>'B3B3B3'),
	'header_color' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'f27000', 'dark_default'=>'FFFFFF'),
	'descr_color' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> '4c4c4c', 'dark_default'=>'FFFFFF'),
	'contrast_color' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'FFFFFF', 'dark_default'=>'FFFFFF'),
	'delimiter_color' => array('type'=>'color', 'autochange'=>'no', 'light_default'=> 'e5e5e5', 'dark_default'=>'666666'),

	'font_family' => array('type'=>'text', 'autochange'=>'no', 'light_default'=> "'SegoeUINormal', Arial, 'Lucida Grande','Lucida Sans Unicode', Verdana, sans-serif", 'dark_default'=>"'SegoeUINormal', Arial, 'Lucida Grande','Lucida Sans Unicode', Verdana, sans-serif"),
	'main_font_size' => array('type'=>'font', 'autochange'=>'no', 'light_default'=> '13', 'dark_default'=>'13'),
	'input_font_size' => array('type'=>'font', 'autochange'=>'no', 'light_default'=> '15', 'dark_default'=>'15'),
	'h1_font_size' => array('type'=>'font', 'autochange'=>'no', 'light_default'=> '20', 'dark_default'=>'20'),
	'h2_font_size' => array('type'=>'font', 'autochange'=>'no', 'light_default'=> '17', 'dark_default'=>'17'),
	'small_font_size' => array('type'=>'font', 'autochange'=>'no', 'light_default'=> '12', 'dark_default'=>'12'),
	'search_btn_font_size' => array('type'=>'font', 'autochange'=>'no', 'light_default'=> '22', 'dark_default'=>'22'),
);

$sprite = array(
	"icons" => array("width" => 138, "height" => 2500),
);
$sprite_mobile = array(
	"icons" => array("width" => 102, "height" => 531),
);


?>