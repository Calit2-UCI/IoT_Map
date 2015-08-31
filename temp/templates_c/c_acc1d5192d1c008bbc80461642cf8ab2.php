<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:20:02 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="menu-level2">
	<ul>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
		<li<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang']): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/index/<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></div></li>
		<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>

<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/edit/<?php echo $this->_vars['current_lang']; ?>
/0"><?php echo l('link_add_page', 'content', '', 'text', array()); ?></a></div></li>
		<?php if ($this->_vars['pages']): ?>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false"><?php echo l('link_save_sorter', 'content', '', 'text', array()); ?></a></div></li>
		<?php endif; ?>
	</ul>
	&nbsp;
</div>

<div id="pages">
<ul name="parent_0" class="sort connected" id="clsr0ul">
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "content". $this->module_templates.  $this->get_current_theme_gid('', '"content"'). "tree_level.tpl", array('list' => $this->_vars['pages'],'load_type' => false));
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
			itemsBlockID: \'pages\',
			urlSaveSort: \'admin/content/ajax_save_sorter\',
			urlDeleteItem: \'admin/content/ajax_delete/\',
			urlActivateItem: \'admin/content/ajax_activate/1/\',
			urlDeactivateItem: \'admin/content/ajax_activate/0/\'
		});
	});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>