<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:19:26 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_banners_menu'), $this);?>
<div class="actions">&nbsp;</div>

<form method="post" action="" name="moder_sattings_save">
	<div class="edit-form n150">
		<div class="row">
			<div class="h"><label for="period"><?php echo l('field_period', 'banners', '', 'text', array()); ?></label>: </div>
			<div class="v"><input type="text" name="period" class="short" value="<?php echo $this->_run_modifier($this->_vars['data']['period'], 'escape', 'plugin', 1); ?>
"></div>
		</div>
		<div class="row">
			<div class="h"><label for="moderation_send_mail"><?php echo l('field_moderation_send_mail', 'banners', '', 'text', array()); ?></label>: </div>
			<div class="v">
				<input type="hidden" name="moderation_send_mail" value="0"> 
				<input type="checkbox" name="moderation_send_mail" value="1" id="moderation_send_mail" <?php if ($this->_vars['data']['moderation_send_mail']): ?>checked="checked"<?php endif; ?>> 
				&nbsp;&nbsp;
				<?php echo l('field_admin_moderation_emails', 'banners', '', 'text', array()); ?>
				<input type="text" name="admin_moderation_emails" value="<?php echo $this->_run_modifier($this->_vars['data']['admin_moderation_emails'], 'escape', 'plugin', 1); ?>
" id="admin_moderation_emails" class="middle" <?php if (! $this->_vars['data']['moderation_send_mail']): ?>disabled<?php endif; ?>> 
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
</form>
<script><?php echo '
$(function(){
	$("div.row:not(.hide):even").addClass("zebra");
	$(\'#moderation_send_mail\').bind(\'change\', function(){
		if(this.checked){
			$(\'#admin_moderation_emails\').removeAttr(\'disabled\');
		}else{
			$(\'#admin_moderation_emails\').attr(\'disabled\', \'disabled\');
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
