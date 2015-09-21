<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-03 01:12:47 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/edit_set/<?php echo $this->_vars['id_theme']; ?>
"><?php echo l('link_add_set', 'themes', '', 'text', array()); ?></a></div></li>
	</ul>
&nbsp;
</div>


<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w50">&nbsp;</th>
	<th><?php echo l('field_set_name', 'themes', '', 'text', array()); ?></th>
	<th class="w50"><?php echo l('field_active', 'themes', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['sets']) and count((array)$this->_vars['sets'])): foreach ((array)$this->_vars['sets'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><div style="margin: 2px; background-color: #<?php echo $this->_vars['item']['color_settings']['main_bg']; ?>
">&nbsp;</div></td>
	<td><?php echo $this->_vars['item']['set_name']; ?>
</td>
	<td class="center">
		<?php if ($this->_vars['item']['active']): ?>
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('text_activate_set', 'themes', '', 'text', array()); ?>" title="<?php echo l('text_activate_set', 'themes', '', 'text', array()); ?>">
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/activate_set/<?php echo $this->_vars['id_theme']; ?>
/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_set', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_activate_set', 'themes', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/preview/<?php echo $this->_vars['theme']['gid']; ?>
/<?php echo $this->_vars['item']['set_gid']; ?>
" target="_blank"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-view.png" width="16" height="16" border="0" alt="<?php echo l('link_preview_theme', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_preview_theme', 'themes', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/edit_set/<?php echo $this->_vars['id_theme']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_set', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_edit_set', 'themes', '', 'text', array()); ?>"></a>
		<?php if (! $this->_vars['item']['active']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/delete_set/<?php echo $this->_vars['id_theme']; ?>
/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_set', 'themes', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_set', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_delete_set', 'themes', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_sets', 'themes', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
