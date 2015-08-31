<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 10:20:17 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="menu-level2">
	<ul>
		<li class="active"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/installed_themes"><?php echo l('header_installed_themes', 'themes', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/enable_themes"><?php echo l('header_enable_themes', 'themes', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['type'] == 'user'): ?>active<?php endif;  if (! $this->_vars['filter']['user']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/installed_themes/user"><?php echo l('filter_user_themes', 'themes', '', 'text', array()); ?> (<?php echo $this->_vars['filter']['user']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['type'] == 'admin'): ?>active<?php endif;  if (! $this->_vars['filter']['admin']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/installed_themes/admin"><?php echo l('filter_admin_themes', 'themes', '', 'text', array()); ?> (<?php echo $this->_vars['filter']['admin']; ?>
)</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">&nbsp;</th>
	<th class="w100"><?php echo l('field_theme', 'themes', '', 'text', array()); ?></th>
	<th><?php echo l('field_description', 'themes', '', 'text', array()); ?></th>
	<th><?php echo l('field_default', 'themes', '', 'text', array()); ?></th>
	<th><?php echo l('field_active', 'themes', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['themes']) and count((array)$this->_vars['themes'])): foreach ((array)$this->_vars['themes'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php if ($this->_vars['item']['img']): ?><img src="<?php echo $this->_vars['item']['img']; ?>
" class="img"><?php endif; ?></td>
	<td class="center"><?php echo $this->_vars['item']['theme']; ?>
</td>
	<td><b><?php echo $this->_vars['item']['theme_name']; ?>
</b><br><?php echo $this->_vars['item']['theme_description']; ?>
</td>
	<td class="center">
		<?php if ($this->_vars['item']['default']): ?>
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" >
		<?php else: ?>		
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" >
		<?php endif; ?>
	</td>
	<td class="center">
		<?php if ($this->_vars['item']['active']): ?>
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('text_activate_theme', 'themes', '', 'text', array()); ?>" title="<?php echo l('text_activate_theme', 'themes', '', 'text', array()); ?>">
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_theme', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_activate_theme', 'themes', '', 'text', array()); ?>"></a>
		<?php endif; ?>	
	</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/preview/<?php echo $this->_vars['item']['theme']; ?>
" target="_blank"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-view.png" width="16" height="16" border="0" alt="<?php echo l('link_preview_theme', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_preview_theme', 'themes', '', 'text', array()); ?>"></a>
		<?php if ($this->_vars['item']['setable']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/sets/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-list.png" width="16" height="16" border="0" alt="<?php echo l('link_sets_theme', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_sets_theme', 'themes', '', 'text', array()); ?>"></a>
		<?php endif; ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/view_installed/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_view_theme', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_view_theme', 'themes', '', 'text', array()); ?>"></a>
		<?php if (! $this->_vars['item']['active'] && ! $this->_vars['item']['default']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/uninstall/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_uninstall_theme', 'themes', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_uninstall_theme', 'themes', '', 'text', array()); ?>" title="<?php echo l('link_uninstall_theme', 'themes', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="6" class="center"><?php echo l('no_themes', 'themes', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
