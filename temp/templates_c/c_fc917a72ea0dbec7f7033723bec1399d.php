<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-13 00:51:10 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block load_content">
	<h1><?php echo $this->_vars['header']; ?>
</h1>
	<div class="inside">
		<?php if ($this->_vars['load_map_scripts']): ?>
			<?php echo tpl_function_block(array('name' => show_default_map,'gid' => 'profile_view','module' => geomap,'markers' => $this->_vars['markers'],'settings' => $this->_vars['map_settings'],'width' => '600','height' => '400','only_load_scripts' => 1,'map_id' => 'users_map_container'), $this);?>			
		<?php endif; ?>
	
		<?php echo tpl_function_block(array('name' => show_default_map,'gid' => 'profile_view','module' => geomap,'markers' => $this->_vars['markers'],'settings' => $this->_vars['map_settings'],'width' => '600','height' => '400','only_load_content' => 1,'map_id' => 'users_map_container'), $this);?>
	</div>
	<div class="clr"></div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
