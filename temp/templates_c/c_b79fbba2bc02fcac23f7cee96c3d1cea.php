<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:12:46 Pacific Daylight Time */ ?>

<?php if (is_array($this->_vars['list']) and count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['item']):  $this->assign('id', $this->_vars['item']['id']); ?>
<li id="item_<?php echo $this->_vars['id']; ?>
">

	<div id="clsr<?php echo $this->_vars['id']; ?>
" class="closer expand<?php if (count ( $this->_vars['item']['sub'] )): ?> visible<?php endif; ?>"></div>
	<div class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/menu/items_edit/<?php echo $this->_vars['item']['menu_id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-copy.png" width="16" height="16" alt="<?php echo l('create_subitem', 'menu', '', 'text', array()); ?>" title="<?php echo l('create_subitem', 'menu', '', 'text', array()); ?>"></a>
		<a href="#" onclick="javascript: mlSorter.deactivateItem(<?php echo $this->_vars['item']['id']; ?>
);return false;" id="active_<?php echo $this->_vars['id']; ?>
" <?php if ($this->_vars['item']['status'] != 1): ?>class="hide"<?php endif; ?>><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" alt="<?php echo l('make_inactive', 'start', '', 'text', array()); ?>" title="<?php echo l('make_inactive', 'start', '', 'text', array()); ?>"></a>
		<a href="#" onclick="javascript: mlSorter.activateItem(<?php echo $this->_vars['item']['id']; ?>
);return false;" id="deactive_<?php echo $this->_vars['id']; ?>
" <?php if ($this->_vars['item']['status'] == 1): ?>class="hide"<?php endif; ?>><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" alt="<?php echo l('make_active', 'start', '', 'text', array()); ?>" title="<?php echo l('make_active', 'start', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/menu/items_edit/<?php echo $this->_vars['item']['menu_id']; ?>
/<?php echo $this->_vars['item']['parent_id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" alt="<?php echo l('edit', 'start', '', 'text', array()); ?>" title="<?php echo l('edit', 'start', '', 'text', array()); ?>"></a>
		<a href='#' onclick="if (confirm('<?php echo l('note_delete_menu_item', 'menu', '', 'js', array()); ?>')) mlSorter.deleteItem(<?php echo $this->_vars['item']['id']; ?>
);return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" alt="<?php echo l('delete', 'start', '', 'text', array()); ?>" title="<?php echo l('delete', 'start', '', 'text', array()); ?>"></a>
	</div>
	<?php echo $this->_vars['item']['value']; ?>

	<ul id="clsr<?php echo $this->_vars['id']; ?>
ul" class="sort connected<?php if (count ( $this->_vars['item']['sub'] )): ?> hide<?php endif; ?>" name="parent_<?php echo $this->_vars['id']; ?>
"><?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "menu". $this->module_templates.  $this->get_current_theme_gid('', '"menu"'). "tree_level.tpl", array('list' => $this->_vars['item']['sub'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?></ul>
</li>
<?php endforeach; endif; ?>
