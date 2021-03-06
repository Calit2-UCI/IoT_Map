<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 04:21:32 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header"><?php echo l('admin_header_settings_edit', 'aviary', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_used', 'aviary', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="hidden" name="data[used]" value="0">
				<input type="checkbox" name="data[used]" value="1" <?php if ($this->_vars['data']['used']): ?>checked<?php endif; ?> id="aviary_used">
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_api_key', 'aviary', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" name="data[api_key]" value="<?php echo $this->_run_modifier($this->_vars['data']['api_key'], 'escape', 'plugin', 1); ?>
" id="aviary_api_key" class="middle" <?php if (! $this->_vars['data']['used']): ?>disabled<?php endif; ?>>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/add_ons_items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<script><?php echo '
	$(function(){
		$(\'#aviary_used\').bind(\'click\', function(){
			if(this.checked){
				$(\'#aviary_api_key\').prop(\'disabled\', false);
			}else{
				$(\'#aviary_api_key\').prop(\'disabled\', true);
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
