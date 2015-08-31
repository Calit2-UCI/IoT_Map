<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:50:14 Pacific Daylight Time */ ?>

<?php if ($this->_vars['mode'] != 'sort'):  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  else:  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  endif;  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_fields_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/field_edit/<?php echo $this->_vars['type']; ?>
/<?php echo $this->_vars['section']; ?>
/"><?php echo l('link_add_field', 'field_editor', '', 'text', array()); ?></a></div></li>
<?php if ($this->_vars['mode'] != 'sort'): ?>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/fields/<?php echo $this->_vars['type']; ?>
/<?php echo $this->_vars['section']; ?>
/sort"><?php echo l('link_sorting_mode', 'field_editor', '', 'text', array()); ?></a></div></li>
<?php else: ?>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/fields/<?php echo $this->_vars['type']; ?>
/<?php echo $this->_vars['section']; ?>
/"><?php echo l('link_view_mode', 'field_editor', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false"><?php echo l('link_save_sorting', 'field_editor', '', 'text', array()); ?></a></div></li>
<?php endif; ?>
	</ul>
	&nbsp;
</div>

<?php if ($this->_vars['mode'] != 'sort'): ?>

<div class="menu-level3">
	<ul>
	<?php if (is_array($this->_vars['types']) and count((array)$this->_vars['types'])): foreach ((array)$this->_vars['types'] as $this->_vars['item']): ?>
		<li class="<?php if ($this->_vars['type'] == $this->_vars['item']['gid']): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/fields/<?php echo $this->_vars['item']['gid']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></li>
	<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>

<div class="filter-form">
	<select name="section" onchange="javascript: reload_this_page(this.value);">
	<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['gid']; ?>
" <?php if ($this->_vars['section'] == $this->_vars['item']['gid']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
</option><?php endforeach; endif; ?>
	</select>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_field_name', 'field_editor', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_field_type', 'field_editor', '', 'text', array()); ?></th>
	<th class="w50">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['fields']) and count((array)$this->_vars['fields'])): foreach ((array)$this->_vars['fields'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center"><?php echo $this->_vars['item']['field_type']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/field_edit/<?php echo $this->_vars['type']; ?>
/<?php echo $this->_vars['section']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_field', 'field_editor', '', 'text', array()); ?>" title="<?php echo l('link_edit_field', 'field_editor', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/field_delete/<?php echo $this->_vars['type']; ?>
/<?php echo $this->_vars['section']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_field', 'field_editor', '', 'text', array()); ?>" title="<?php echo l('link_delete_field', 'field_editor', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_fields', 'field_editor', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<script type="text/javascript">
var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/field_editor/fields/<?php echo $this->_vars['type']; ?>
/";
<?php echo '
function reload_this_page(value){
	var link = reload_link + value;
	location.href=link;
}
'; ?>
</script>

<?php else: ?>
<div id="menu_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
	<?php if (is_array($this->_vars['fields']) and count((array)$this->_vars['fields'])): foreach ((array)$this->_vars['fields'] as $this->_vars['item']): ?>
	<li id="item_<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</li>
	<?php endforeach; endif; ?>
	</ul>
</div>
<?php echo tpl_function_js(array('file' => 'admin-multilevel-sorter.js'), $this);?>
<script type='text/javascript'><?php echo '
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
			onActionUpdate: false,
			urlSaveSort: \'admin/field_editor/ajax_field_sort\'
		});
	});
'; ?>
</script>

<?php endif; ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
