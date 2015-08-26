<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:12:46 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/menu/items_edit/<?php echo $this->_vars['menu_data']['id']; ?>
"><?php echo l('link_add_menu_item', 'menu', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false"><?php echo l('link_save_sorter', 'menu', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div id="menu_items">
<ul name="parent_0" class="sort connected" id="clsr0ul">
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "menu". $this->module_templates.  $this->get_current_theme_gid('', '"menu"'). "tree_level.tpl", array('list' => $this->_vars['menu'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</ul>
</div>

<script ><?php echo '
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\', 
			urlSaveSort: \'admin/menu/ajax_save_item_sorter\',
			urlDeleteItem: \'admin/menu/ajax_item_delete/\',
			urlActivateItem: \'admin/menu/ajax_item_activate/1/\',
			urlDeactivateItem: \'admin/menu/ajax_item_activate/0/\'
		});
	});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>