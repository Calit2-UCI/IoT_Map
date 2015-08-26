<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:13:10 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_menu_change', 'menu', '', 'text', array());  else:  echo l('admin_header_menu_add', 'menu', '', 'text', array());  endif; ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_menu_name', 'menu', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_menu_gid', 'menu', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_menu_check_permissions', 'menu', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" name="check_permissions" value="1" <?php if ($this->_vars['data']['check_permissions']): ?>checked<?php endif; ?>></div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/menu"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>