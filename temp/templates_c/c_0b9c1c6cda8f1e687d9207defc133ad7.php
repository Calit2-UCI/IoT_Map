<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:19:22 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_banners_menu'), $this);?>
<div class="actions">
	<ul>
		<?php if ($this->_vars['allow_config_add']): ?><li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/banners/edit_place"><?php echo l('link_add_place', 'banners', '', 'text', array()); ?></a></div></li><?php endif; ?>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_place_id', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_place_name', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_place_keyword', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_place_sizes', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_place_in_rotation', 'banners', '', 'text', array()); ?></th>
	<th><?php echo l('field_place_rotate_time', 'banners', '', 'text', array()); ?></th>
	<th>&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['places']) and count((array)$this->_vars['places'])): foreach ((array)$this->_vars['places'] as $this->_vars['place']): ?>
<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><?php echo $this->_vars['place']['id']; ?>
</td>
	<td><?php echo $this->_vars['place']['name']; ?>
</td>
	<td class="w150"><?php echo $this->_vars['place']['keyword']; ?>
</td>
	<td class="w70 center"><?php echo $this->_vars['place']['width']; ?>
 x <?php echo $this->_vars['place']['height']; ?>
</td>
	<td class="w70 center"><?php echo $this->_vars['place']['places_in_rotation']; ?>
</td>
	<td class="w70 center"><?php if ($this->_vars['place']['rotate_time']):  echo $this->_vars['place']['rotate_time']; ?>
 sec.<?php else:  echo l('no_rotation', 'banners', '', 'text', array());  endif; ?></td>
	<td class="icons">
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/edit_place/<?php echo $this->_vars['place']['id']; ?>
'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" alt="<?php echo l('link_edit_place', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_edit_place', 'banners', '', 'text', array()); ?>"></a>
		<a href='<?php echo $this->_vars['site_url']; ?>
admin/banners/delete_place/<?php echo $this->_vars['place']['id']; ?>
' onclick="return confirm('<?php echo l('note_delete_place', 'banners', '', 'js', array()); ?>');"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" alt="<?php echo l('link_delete_place', 'banners', '', 'text', array()); ?>" title="<?php echo l('link_delete_place', 'banners', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="7" class="center"><?php echo l('no_place', 'banners', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
