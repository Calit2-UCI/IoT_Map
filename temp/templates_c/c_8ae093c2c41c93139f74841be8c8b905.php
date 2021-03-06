<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:19:17 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_banners_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/edit_group"><?php echo l('link_add_group', 'banners', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w50"><?php echo l('field_group_id', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_group_name', 'banners', '', 'text', array()); ?></th>
	<th class="w70"><?php echo l('field_group_price', 'banners', '', 'text', array()); ?></th>
	<th class="w70">&nbsp;</th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['groups']) and count((array)$this->_vars['groups'])): foreach ((array)$this->_vars['groups'] as $this->_vars['item']): ?>
<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><?php echo $this->_vars['item']['id']; ?>
</td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center"><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['price']), $this);?></td>
	<td class="center"><a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/group_pages/<?php echo $this->_vars['item']['id']; ?>
"><?php echo l('pages_list', 'banners', '', 'text', array()); ?></a></td>
	<td class="icons">
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/edit_group/<?php echo $this->_vars['item']['id']; ?>
'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" alt="<?php echo l('link_edit_group', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_edit_group', 'banners', '', 'text', array()); ?>"></a>
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/delete_group/<?php echo $this->_vars['item']['id']; ?>
' onclick="return confirm('<?php echo l('note_delete_group', 'banners', '', 'js', array()); ?>');"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" alt="<?php echo l('link_delete_group', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_delete_group', 'banners', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="center"><?php echo l('no_groups', 'banners', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
