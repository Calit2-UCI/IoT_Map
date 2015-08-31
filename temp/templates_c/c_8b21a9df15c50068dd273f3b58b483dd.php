<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:19:37 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'editable|ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php echo tpl_function_js(array('file' => 'admin-multilevel-sorter.js'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/properties/property_items/<?php echo $this->_vars['current_lang_id']; ?>
/<?php echo $this->_vars['current_gid']; ?>
"><?php echo l('link_add_ds_item', 'properties', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false;"><?php echo l('link_resort_items', 'properties', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
		<li class="<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/properties/property/<?php echo $this->_vars['ds_gid']; ?>
/<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></li>
		<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>
<div class="filter-form" id="ds_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		<?php if (is_array($this->_vars['reference']['option']) and count((array)$this->_vars['reference']['option'])): foreach ((array)$this->_vars['reference']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<li id="item_<?php echo $this->_vars['key']; ?>
">
			<div class="icons">
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/properties/property_items/<?php echo $this->_vars['current_lang_id']; ?>
/<?php echo $this->_vars['current_gid']; ?>
/<?php echo $this->_vars['key']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" alt="<?php echo l('btn_edit', 'start', '', 'text', array()); ?>" title="<?php echo l('btn_edit', 'start', '', 'text', array()); ?>"></a>
				<a href='#' onclick="if (confirm('<?php echo l('note_delete_ds_item', 'properties', '', 'js', array()); ?>')) mlSorter.deleteItem('<?php echo $this->_vars['key']; ?>
');return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" alt="<?php echo l('btn_delete', 'start', '', 'text', array()); ?>" title="<?php echo l('btn_delete', 'start', '', 'text', array()); ?>"></a>
			</div>
			<div class="editable" id="<?php echo $this->_vars['key']; ?>
"><?php echo $this->_vars['item']; ?>
</div>
		</li>
		<?php endforeach; endif; ?>
	</ul>
</div>
<script>
<?php echo '
var mlSorter;

$(function(){
	mlSorter = new multilevelSorter({
		siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
		itemsBlockID: \'pages\',
		urlSaveSort: \'admin/properties/ajax_ds_item_save_sorter/';  echo $this->_vars['current_gid'];  echo '/\',
		urlDeleteItem: \'admin/properties/ajax_ds_item_delete/';  echo $this->_vars['current_gid'];  echo '/\',
	});
});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
