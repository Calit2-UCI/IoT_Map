<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 00:48:05 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_payments_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/packages/package_edit"><?php echo l('link_add_package', 'packages', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first"><?php echo l('field_package_name', 'packages', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('field_status', 'packages', '', 'text', array()); ?></th>
		<th class="w100">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['packages']) and count((array)$this->_vars['packages'])): foreach ((array)$this->_vars['packages'] as $this->_vars['item']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
			<td class="first center"><?php echo $this->_vars['item']['name']; ?>
</td>
			<td class="center">
				<?php if ($this->_vars['item']['status']): ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/packages/activate_package/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_service', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_service', 'packages', '', 'text', array()); ?>"></a>
				<?php else: ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/packages/activate_package/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_service', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_activate_service', 'packages', '', 'text', array()); ?>"></a>
				<?php endif; ?>
			</td>
			<td class="icons">
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/packages/package_services/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-list.png" width="16" height="16" border="0" alt="<?php echo l('link_package_services', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_edit_service', 'packages', '', 'text', array()); ?>"></a>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/packages/package_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_service', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_edit_service', 'packages', '', 'text', array()); ?>"></a>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/packages/package_delete/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>"></a>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="4" class="center"><?php echo l('no_packages', 'packages', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
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
