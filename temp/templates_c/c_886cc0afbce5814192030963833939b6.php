<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 04:21:22 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_widgets_menu'), $this);?>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_name', 'widgets', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_module', 'widgets', '', 'text', array()); ?></th>
	<th class="w100 ">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['widgets']) and count((array)$this->_vars['widgets'])): foreach ((array)$this->_vars['widgets'] as $this->_vars['item']): ?>
<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<?php $this->assign('name', $this->_vars['item']['gid'].'_name'); ?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td><?php echo $this->_vars['item']['langs'][$this->_vars['name']]; ?>
</td>
	<td><?php echo $this->_vars['item']['module']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/widgets/install_widget/<?php echo $this->_vars['item']['module']; ?>
/<?php echo $this->_vars['item']['gid']; ?>
"><?php echo l('link_install_widget', 'widgets', '', 'text', array()); ?></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3" class="center"><?php echo l('no_widgets_install', 'widgets', '', 'text', array()); ?></td></tr>
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
