<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-22 02:32:40 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => get_admin_level1_menu,'helper_name' => menu,'func_param' => 'admin_kisses_menu'), $this);?>

<div class="actions">&nbsp;</div>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header"><?php echo l('admin_header_kisses_settings', 'kisses', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('settings_admin_items_per_page', 'kisses', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" name="admin_items_per_page" value="<?php echo $this->_vars['settings_data']['admin_items_per_page']; ?>
" class="short" id="admin_items_per_page">
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('settings_users_items_per_page', 'kisses', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" name="items_per_page" value="<?php echo $this->_vars['settings_data']['items_per_page']; ?>
" class="short" id="users_items_per_page">
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('number_max_symbols', 'kisses', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="text" name="number_max_symbols" value="<?php echo $this->_vars['settings_data']['number_max_symbols']; ?>
" class="short" id="number_max_symbols">
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('admin_system_settings_page', 'kisses', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="checkbox" name="system_settings_page" value="1" <?php if ($this->_vars['settings_data']['system_settings_page']): ?>checked<?php endif; ?> class="short" id="system_settings_page">
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/add_ons_items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<script><?php echo '
	$("div.row:odd").addClass("zebra");
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
