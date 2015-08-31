<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:15:09 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_spam_menu'), $this);?>
<form method="post" action="" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_types_edit', 'spam', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_type_form_type', 'spam', '', 'text', array()); ?>:&nbsp;* </div>
			<div class="v">
				<select name="data[form_type]" class="long">
					<?php if (is_array($this->_vars['form_type_lang']['option']) and count((array)$this->_vars['form_type_lang']['option'])): foreach ((array)$this->_vars['form_type_lang']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_run_modifier($this->_vars['key'], 'escape', 'plugin', 1); ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['form_type']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_type_send_mail', 'spam', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="hidden" name="data[send_mail]" value="0" />
				<input type="checkbox" name="data[send_mail]" value="1" <?php if ($this->_vars['data']['send_mail']): ?>checked="checked"<?php endif; ?> />
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_type_status', 'spam', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="hidden" name="data[status]" value="0" />
				<input type="checkbox" name="data[status]" value="1" <?php if ($this->_vars['data']['status']): ?>checked="checked"<?php endif; ?> />
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/spam/types"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>

<script><?php echo '
$(function(){
	$("div.row:odd").addClass("zebra");
});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
