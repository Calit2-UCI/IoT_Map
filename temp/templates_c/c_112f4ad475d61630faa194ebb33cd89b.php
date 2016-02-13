<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-11 01:15:35 Pacific Standard Time */ ?>

<div class="load_content_controller">
	<h1><?php echo l('header_amenity_select', 'geomap', '', 'text', array()); ?></h1>
	<div class="inside">
		<ul class="controller-items" id="amenity_select_items"></ul>
	
		<div class="controller-actions">
			<input type="button" id="amenity_close_link" name="close_btn" value="<?php echo l('btn_close', 'start', '', 'button', array()); ?>" class="fleft">
			<?php if ($this->_vars['data']['max'] > 1): ?>
			<a href="#" id="amenity_select_back" class="btn-link link-margin"><ins class="with-icon i-delete no-hover"></ins><?php echo l('link_reset_all', 'geomap', '', 'text', array()); ?></a>
			<?php endif; ?>
		</div>
		<?php if ($this->_vars['data']['max'] > 1): ?>
		<div class=" line top"><?php echo l('text_availbale_select_amenities', 'geomap', '', 'text', array()); ?> <span id="amenity_max_left_block"></span></div>
		<?php endif; ?>
	</div>
</div>
