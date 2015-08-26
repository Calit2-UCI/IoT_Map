<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:12:56 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_dynblocks_menu'), $this);?>
<div class="actions">
	<ul>
		<?php if ($this->_vars['allow_config_add']): ?><li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/edit"><?php echo l('link_add_dynamic_block_area', 'dynamic_blocks', '', 'text', array()); ?></a></div></li><?php endif; ?>
	</ul>
	&nbsp;
</div>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150"><?php echo l('field_gid', 'dynamic_blocks', '', 'text', array()); ?></th>
	<th class=""><?php echo l('field_name', 'dynamic_blocks', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['areas']) and count((array)$this->_vars['areas'])): foreach ((array)$this->_vars['areas'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['gid']; ?>
</td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/area_blocks/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-settings.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_area_blocks', 'dynamic_blocks', '', 'text', array()); ?>" title="<?php echo l('link_edit_area_blocks', 'dynamic_blocks', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_area', 'dynamic_blocks', '', 'text', array()); ?>" title="<?php echo l('link_edit_area', 'dynamic_blocks', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/dynamic_blocks/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_area', 'dynamic_blocks', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_area', 'dynamic_blocks', '', 'text', array()); ?>" title="<?php echo l('link_delete_area', 'dynamic_blocks', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="center"><?php echo l('no_areas', 'dynamic_blocks', '', 'text', array()); ?></td></tr>
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
