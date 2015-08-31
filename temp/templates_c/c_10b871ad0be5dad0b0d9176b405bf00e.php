<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:36:20 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_seo_menu'), $this);?>
<div class="actions">
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('settings_name_field', 'seo', '', 'text', array()); ?></th>
	<th class="w30">&nbsp;</th>
</tr>
<tr class="zebra">
	<td class="first"><?php echo l('default_seo_admin_field', 'seo', '', 'text', array()); ?></td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/seo/default_edit/admin"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_settings', 'seo', '', 'text', array()); ?>" title="<?php echo l('link_edit_settings', 'seo', '', 'text', array()); ?>"></a>
	</td>
</tr>
<tr>
	<td class="first"><?php echo l('default_seo_user_field', 'seo', '', 'text', array()); ?></td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/seo/default_edit/user"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_settings', 'seo', '', 'text', array()); ?>" title="<?php echo l('link_edit_settings', 'seo', '', 'text', array()); ?>"></a>
	</td>
</tr>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
