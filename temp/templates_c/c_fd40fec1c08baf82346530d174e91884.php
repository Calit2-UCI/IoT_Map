<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-19 10:17:05 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php 
$this->assign('default_select_lang', l('select_default', 'start', '', 'text', array()));
 ?>
<?php 
$this->assign('all_select_lang', l('filter_all', 'users', '', 'text', array()));
 ?>
<?php 
$this->assign('location_lang', l('field_search_country', 'users', '', 'text', array()));
 ?>


<form action="<?php echo $this->_vars['form_settings']['action']; ?>
" method="POST" id="main_search_form_<?php echo $this->_vars['form_settings']['form_id']; ?>
">
	<div style="
	height: 105px;
	background-color: #668284;
	
	border-bottom-left-radius: 5px;
	border-bottom-right-radius: 5px;
    ">
			<!----->
			<div style="height: 20px; margin: auto">
			</div>
			
			<h1 class="logo_header" style="display: inline-block; width: 350px;">
				<a href="<?php echo $this->_vars['site_url']; ?>
" class="logo-block">
				<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['logo_settings']['path']; ?>
" alt="<?php echo tpl_function_helper(array('func_name' => 'seo_tags_default','func_param' => 'header_text'), $this);?>" width="<?php echo $this->_vars['logo_settings']['width']; ?>
" height="<?php echo $this->_vars['logo_settings']['height']; ?>
">
				</a>
			</h1>
			<!------>
		
			<div class="fields-block aligned-fields" style="display: inline-block; width: 508px; position: relative; top: -30px; border:1px;">
				<input type="text" name="search" placeholder="<?php echo l('search_people', 'start', '', 'text', array()); ?>" style="width: 340px; height: 30px; "/>
				<button type="submit" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" name="search_button" >
					<?php echo l('btn_search', 'start', '', 'button', array()); ?>
				</button>
			</div>
			<!------->
			<ul style="display: inline-block; position: relative; top: -15px;"  align="center">
				<div>
				<li><button style="font-size:16.5px; font-family:Arial; font-weight: normal; text-decoration: none; border-radius: 5px; height: 29px; margin: 0px 1px 1px 1px; background-color: green; border-color: green; position: relative; top: -1px;" href="<?php echo $this->_vars['site_url']; ?>
users/registration" style="">REGISTOR</button></li>
				</div>
				<div>
				<li><button style="font-size:16.5px; font-family:Arial; font-weight: normal; text-decoration: none; border-radius: 5px; height: 30px; margin: 2px 1px 0px 1px; background-color: green; border-color: green; position: relative; top: -2px; font-size: 15px;" href="<?php echo $this->_vars['site_url']; ?>
users/login_form">LOGIN</button></li>
				</div>
			</ul>
			<!-------->
			
	</div>
</form>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

