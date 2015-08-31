<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:12:10 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_uploads_menu'), $this);?>
<div class="actions">
	<?php if ($this->_vars['allow_config_add']): ?>
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/config_edit"><?php echo l('link_add_config', 'uploads', '', 'text', array()); ?></a></div></li>
	</ul>
	<?php endif; ?>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150"><?php echo l('field_gid', 'uploads', '', 'text', array()); ?></th>
	<th class=""><?php echo l('field_name', 'uploads', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['configs']) and count((array)$this->_vars['configs'])): foreach ((array)$this->_vars['configs'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><b><?php echo $this->_vars['item']['gid']; ?>
</b></td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/config_thumbs/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-list.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_thumb', 'uploads', '', 'text', array()); ?>" title="<?php echo l('link_edit_thumb', 'uploads', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/config_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_config', 'uploads', '', 'text', array()); ?>" title="<?php echo l('link_edit_config', 'uploads', '', 'text', array()); ?>"></a>
		<?php if ($this->_vars['allow_config_add']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/config_delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_config', 'uploads', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_config', 'uploads', '', 'text', array()); ?>" title="<?php echo l('link_delete_config', 'uploads', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td class="center zebra" colspan=3><?php echo l('no_configs', 'uploads', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
