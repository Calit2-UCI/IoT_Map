<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:02:15 Pacific Daylight Time */ ?>

<div class="lc">
	<div class="inside account_menu">
		<?php echo tpl_function_block(array('name' => 'show_poll_place_block','module' => 'polls','one_poll_place' => 0), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'big-left-banner'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'left-banner'), $this);?>
	</div>
</div>