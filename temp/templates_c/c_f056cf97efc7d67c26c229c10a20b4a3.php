<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:07:34 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_notifications_menu'), $this);?>
<div class="actions">
	<?php if ($this->_vars['allow_edit']): ?>
		<ul>
			<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/notifications/edit"><?php echo l('link_add_notification', 'notifications', '', 'text', array()); ?></a></div></li>
		</ul>
	<?php endif; ?>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		
		<th class="first w100"><?php echo l('field_notification_name', 'notifications', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('field_send_type', 'notifications', '', 'text', array()); ?></th>
		<th class="w100"><a href="<?php echo $this->_vars['sort_links']['date_add']; ?>
"<?php if ($this->_vars['order'] == 'date_add'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_add', 'notifications', '', 'text', array()); ?></a></th>
		<th class="w50">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['notifications']) and count((array)$this->_vars['notifications'])): foreach ((array)$this->_vars['notifications'] as $this->_vars['item']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
			
			<td><?php echo l($this->_vars['item']['name_i'], 'notifications', '', 'text', array()); ?></td>
			<td class="center"><?php echo l('field_send_type_'.$this->_vars['item']['send_type'], 'notifications', '', 'text', array()); ?></td>
			<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
			<td class="icons">
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/notifications/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_notification', 'notifications', '', 'text', array()); ?>" title="<?php echo l('link_edit_notification', 'notifications', '', 'text', array()); ?>"></a>
					<?php if ($this->_vars['allow_edit']): ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/notifications/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_notification', 'notifications', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_notification', 'notifications', '', 'text', array()); ?>" title="<?php echo l('link_delete_notification', 'notifications', '', 'text', array()); ?>"></a>
					<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="5" class="center"><?php echo l('no_notifications', 'notifications', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
