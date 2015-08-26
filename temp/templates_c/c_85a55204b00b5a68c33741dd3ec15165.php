<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<h1 class="logo_header">
	<a href="<?php echo $this->_vars['site_url']; ?>
" class="logo-block">
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['logo_settings']['path']; ?>
" alt="<?php echo tpl_function_helper(array('func_name' => 'seo_tags_default','func_param' => 'header_text'), $this);?>" width="<?php echo $this->_vars['logo_settings']['width']; ?>
" height="<?php echo $this->_vars['logo_settings']['height']; ?>
">
	</a>
</h1>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
