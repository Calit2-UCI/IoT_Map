<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:35:10 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_countries_menu'), $this); echo tpl_function_js(array('module' => 'countries','file' => 'admin-location-sorter.js'), $this);?>
<script><?php echo '
	var sorter;
	$(function(){
		sorter = new sortLocations({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\', 
			urlSaveSort: \'admin/countries/ajax_save_city_sorter/';  echo $this->_vars['country']['code'];  echo '/';  echo $this->_vars['region']['id'];  echo '\'
		});
	});
'; ?>
</script>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/city_edit/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['region']['id']; ?>
"><?php echo l('link_add_city', 'countries', '', 'text', array()); ?></a></div></li>
		<?php if ($this->_vars['sort_mode']): ?>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/region/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['region']['id']; ?>
/1/0"><?php echo l('link_view_mode', 'countries', '', 'text', array()); ?></a></div></li>
		<?php else: ?>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/region/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['region']['id']; ?>
/1/1"><?php echo l('link_sorting_mode', 'countries', '', 'text', array()); ?></a></div></li>
		<?php endif; ?>
	</ul>
	&nbsp;
</div>

<?php if ($this->_vars['sort_mode']): ?>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w50"><?php echo l('field_city_name', 'countries', '', 'text', array()); ?></th>
	<th></th>
	<th><?php echo l('link_sorting_mode', 'countries', '', 'text', array()); ?></th>
	<th></th>
</tr>
<tr>
<td width="40%">
<div id="menu_items">
	<select multiple id="clsr0ul" style="width:100%; height: 415px">
	<?php if (is_array($this->_vars['installed']) and count((array)$this->_vars['installed'])): foreach ((array)$this->_vars['installed'] as $this->_vars['item']): ?>
	<option id="item_<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</option>
	<?php endforeach; endif; ?>
	</select>
</div>
</td>
<td width="5%" class="middle">
	<div style="margin: auto; width: 18px;">
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
arrow-left-gray.gif" height="12" style="cursor: pointer;" id="moveToSortList">
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
arrow-right-gray.gif" height="12" style="cursor: pointer;" id="moveToDefault">
	</div>
</td>
<td width="40%">
<div id="menu_items">
	<select multiple name="parent_0" id="clsr0ul_sort" style="width:100%; height: 415px">
	<?php if (is_array($this->_vars['sorted']) and count((array)$this->_vars['sorted'])): foreach ((array)$this->_vars['sorted'] as $this->_vars['item']): ?>
	<option id="item_<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</option>
	<?php endforeach; endif; ?>
	</select>
</div>
</td>
<td width="5%" class="middle">
	<div style="margin: auto; width: 12px;">
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
arrow-up-gray.gif" alt="" width="12" style="cursor: pointer;" id="moveUp">
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
arrow-down-gray.gif" alt="" width="12" style="cursor: pointer;" id="moveDown">
	</div>
</td>
</tr>
</table>

<?php else: ?>

<div class="filter-form">
<form method="post">
	<h3><?php echo $this->_vars['country']['name']; ?>
: <?php echo $this->_vars['region']['name']; ?>
</h3>
	<?php echo l("search_city", 'countries', '', 'text', array()); ?>: <input type="text" name="search" value="<?php echo $this->_vars['search']; ?>
">
	<input type="submit" name="btn_save" value="<?php echo l('btn_send', 'start', '', 'button', array()); ?>">
</form>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_city_name', 'countries', '', 'text', array()); ?></th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['installed']) and count((array)$this->_vars['installed'])): foreach ((array)$this->_vars['installed'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/city_edit/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['region']['id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_city', 'countries', '', 'text', array()); ?>" title="<?php echo l('link_edit_city', 'countries', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/countries/city_delete/<?php echo $this->_vars['country']['code']; ?>
/<?php echo $this->_vars['region']['id']; ?>
/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_city', 'countries', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_city', 'countries', '', 'text', array()); ?>" title="<?php echo l('link_delete_city', 'countries', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3" class="center"><?php echo l('no_cities', 'countries', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php endif; ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
