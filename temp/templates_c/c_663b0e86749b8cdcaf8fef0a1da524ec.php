<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:18:11 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_languages_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/lang_edit"><?php echo l('link_add_lang', 'languages', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_language_name', 'languages', '', 'text', array()); ?></th>
	<th class="w50"><?php echo l('field_default', 'languages', '', 'text', array()); ?></th>
	<th class="w50"><?php echo l('field_active', 'languages', '', 'text', array()); ?></th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center">
		<?php if ($this->_vars['item']['is_default']): ?>
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" >
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/lang_default/<?php echo $this->_vars['item']['id']; ?>
" title="<?php echo l('note_set_default', 'languages', '', 'text', array()); ?>"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" ></a>
		<?php endif; ?>
	</td>
	<td class="center">
		<?php if ($this->_vars['item']['status']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/lang_active/<?php echo $this->_vars['item']['id']; ?>
/0" title="<?php echo l('note_deactivate', 'languages', '', 'text', array()); ?>"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" ></a>
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/lang_active/<?php echo $this->_vars['item']['id']; ?>
/1" title="<?php echo l('note_activate', 'languages', '', 'text', array()); ?>"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" ></a>
		<?php endif; ?>
	</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/lang_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_lang', 'languages', '', 'text', array()); ?>" title="<?php echo l('link_edit_lang', 'languages', '', 'text', array()); ?>"></a>
		<?php if ($this->_vars['user_session_data']['lang_id'] != $this->_vars['item']['id'] && $this->_vars['default_lang_id'] != $this->_vars['item']['id']): ?>
			<a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/lang_delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_lang', 'languages', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_lang', 'languages', '', 'text', array()); ?>" title="<?php echo l('link_delete_lang', 'languages', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; endif; ?>
</table>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
