<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:45 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/menu/edit"><?php echo l('link_add_menu', 'menu', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="<?php echo $this->_vars['sort_links']['name']; ?>
"<?php if ($this->_vars['order'] == 'name'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_menu_name', 'menu', '', 'text', array()); ?></a></th>
	<th><a href="<?php echo $this->_vars['sort_links']['gid']; ?>
"<?php if ($this->_vars['order'] == 'gid'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_menu_gid', 'menu', '', 'text', array()); ?></a></th>
	<th class="w150"><a href="<?php echo $this->_vars['sort_links']['date_created']; ?>
"<?php if ($this->_vars['order'] == 'date_created'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_created', 'menu', '', 'text', array()); ?></a></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['menus']) and count((array)$this->_vars['menus'])): foreach ((array)$this->_vars['menus'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td><?php echo $this->_vars['item']['gid']; ?>
</td>
	<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_created'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/menu/items/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-list.png" width="16" height="16" border="0" alt="<?php echo l('link_items', 'menu', '', 'text', array()); ?>" title="<?php echo l('link_items', 'menu', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/menu/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_menu', 'menu', '', 'text', array()); ?>" title="<?php echo l('link_edit_menu', 'menu', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/menu/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_menu', 'menu', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_menu', 'menu', '', 'text', array()); ?>" title="<?php echo l('link_delete_menu', 'menu', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; endif; ?>
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
