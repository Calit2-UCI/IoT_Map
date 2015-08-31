<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:10:09 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_ausers_change', 'ausers', '', 'text', array());  else:  echo l('admin_header_ausers_add', 'ausers', '', 'text', array());  endif; ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_nickname', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['nickname']; ?>
" name="nickname" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_email', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['email']; ?>
" name="email" class="middle"></div>
		</div>
		<?php if ($this->_vars['data']['id']): ?>
		<div class="row">
			<div class="h"><?php echo l('field_change_password', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" name="update_password" id="pass_change_field"></div>
		</div>
		<?php endif; ?>
		<div class="row">
			<div class="h"><?php echo l('field_password', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="password" value="" name="password" id="pass_field" class="middle"<?php if ($this->_vars['data']['id']): ?>disabled<?php endif; ?>></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_repassword', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="password" value="" name="repassword" class="middle" id="repass_field"<?php if ($this->_vars['data']['id']): ?>disabled<?php endif; ?>></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_name', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name"  class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_description', 'ausers', '', 'text', array()); ?>: </div>
			<div class="v"><textarea name="description" class="long pb2"><?php echo $this->_vars['data']['description']; ?>
</textarea></div>
		</div>	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/ausers"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
$(function(){
	$("div.row:odd").addClass("zebra");
	$("#pass_change_field").click(function(){
		if(this.checked){
			$("#pass_field").removeAttr("disabled"); $("#repass_field").removeAttr("disabled");
		}else{
			$("#pass_field").attr(\'disabled\', \'disabled\'); $("#repass_field").attr(\'disabled\', \'disabled\');
		}
	});
});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>