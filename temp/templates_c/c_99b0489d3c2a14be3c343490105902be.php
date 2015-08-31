<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:14:33 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/ausers/edit"><?php echo l('link_add_user', 'ausers', '', 'text', array()); ?></a></div></li>
		<?php echo tpl_function_block(array('name' => 'add_moderator','module' => 'moderators'), $this);?>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<?php echo tpl_function_block(array('name' => 'count_ausers','module' => 'moderators'), $this);?>
		<li class="<?php if ($this->_vars['filter'] == 'admin'): ?>active<?php endif;  if (! $this->_vars['filter_data']['admin']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/ausers/index/admin"><?php echo l('filter_admin_users', 'ausers', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['admin']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'not_active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['not_active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/ausers/index/not_active"><?php echo l('filter_not_active_users', 'ausers', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['not_active']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/ausers/index/active"><?php echo l('filter_active_users', 'ausers', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['active']; ?>
)</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="<?php echo $this->_vars['sort_links']['nickname']; ?>
"<?php if ($this->_vars['order'] == 'nickname'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_nickname', 'ausers', '', 'text', array()); ?></a></th>
	<th><a href="<?php echo $this->_vars['sort_links']['name']; ?>
"<?php if ($this->_vars['order'] == 'name'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_name', 'ausers', '', 'text', array()); ?></a></th>
	<th><a href="<?php echo $this->_vars['sort_links']['email']; ?>
"<?php if ($this->_vars['order'] == 'email'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_email', 'ausers', '', 'text', array()); ?></a></th>
	<th class="w150"><a href="<?php echo $this->_vars['sort_links']['date_created']; ?>
"<?php if ($this->_vars['order'] == 'date_created'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_created', 'ausers', '', 'text', array()); ?></a></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['users']) and count((array)$this->_vars['users'])): foreach ((array)$this->_vars['users'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['nickname']; ?>
</td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td><?php echo $this->_vars['item']['email']; ?>
</td>
	<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_created'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td class="icons">
		<?php if ($this->_vars['item']['status']): ?>
		<a href="<?php echo $this->_vars['site_root']; ?>
admin/<?php echo $this->_vars['item']['module']; ?>
/activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_user', 'ausers', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_user', 'ausers', '', 'text', array()); ?>"></a>
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_root']; ?>
admin/<?php echo $this->_vars['item']['module']; ?>
/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_user', 'ausers', '', 'text', array()); ?>" title="<?php echo l('link_activate_user', 'ausers', '', 'text', array()); ?>"></a>
		<?php endif; ?>
		<a href="<?php echo $this->_vars['site_root']; ?>
admin/<?php echo $this->_vars['item']['module']; ?>
/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_user', 'ausers', '', 'text', array()); ?>" title="<?php echo l('link_edit_user', 'ausers', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_root']; ?>
admin/<?php echo $this->_vars['item']['module']; ?>
/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_user', 'ausers', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_user', 'ausers', '', 'text', array()); ?>" title="<?php echo l('link_delete_user', 'ausers', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr>
	<td class="center" colspan="5"><?php echo l('ausers_list_empty', 'ausers', '', 'text', array()); ?></td>
</tr>
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
