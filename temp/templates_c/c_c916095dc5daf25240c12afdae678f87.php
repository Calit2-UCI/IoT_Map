<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-13 09:31:28 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_language_change', 'languages', '', 'text', array());  else:  echo l('admin_header_language_add', 'languages', '', 'text', array());  endif; ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_name', 'languages', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_code', 'languages', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['code']; ?>
" name="code" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_rtl', 'languages', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="radio" value="ltr" name="rtl" <?php if ($this->_vars['data']['rtl'] == 'ltr' || ! $this->_vars['data']['rtl']): ?>checked<?php endif; ?> id="ltr_val"><label for="ltr_val"><?php echo l('field_rtl_value_ltr', 'languages', '', 'text', array()); ?></label><br>
				<input type="radio" value="rtl" name="rtl" <?php if ($this->_vars['data']['rtl'] == 'rtl'): ?>checked<?php endif; ?> id="rtl_val"><label for="rtl_val"><?php echo l('field_rtl_value_rtl', 'languages', '', 'text', array()); ?></label><br>
			</div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/languages"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>