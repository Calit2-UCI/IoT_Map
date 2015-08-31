<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:17:43 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'editable'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_languages_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/pages_edit/<?php echo $this->_vars['current_lang_id']; ?>
/<?php echo $this->_vars['current_module_id']; ?>
"><?php echo l('link_add_page', 'languages', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/ajax_pages_delete/<?php echo $this->_vars['current_lang_id']; ?>
/<?php echo $this->_vars['current_module_id']; ?>
" onclick="javascript:  if(!confirm('<?php echo l('note_delete_pages', 'languages', '', 'text', array()); ?>')) return false; delete_strings(this.href); return false;"><?php echo l('link_delete_selected', 'languages', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
		<li class="<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/pages/<?php echo $this->_vars['lang_id']; ?>
/<?php echo $this->_vars['current_module_id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></li>
		<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>
<div class="filter-form">
	<div class="form">
	<select name="module_id" onchange="javascript: reload_page(this.value);">
	<?php if (is_array($this->_vars['modules']) and count((array)$this->_vars['modules'])): foreach ((array)$this->_vars['modules'] as $this->_vars['module_id'] => $this->_vars['item']): ?>
	<option value="<?php echo $this->_vars['module_id']; ?>
"<?php if ($this->_vars['module_id'] == $this->_vars['current_module_id']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']['module_gid']; ?>
 (<?php echo $this->_vars['item']['module_name']; ?>
)</option>
	<?php endforeach; endif; ?>
	</select>
	</div>
</div>
<?php if ($this->_vars['pages']): ?>
<table cellspacing="0" cellpadding="0" class="data" width="100%" id="pages_table">
<tr>
	<th class="first w30">&nbsp;</th>
	<th class="w150"><?php echo l('field_gid', 'languages', '', 'text', array()); ?></th>
	<th><?php echo l('field_text', 'languages', '', 'text', array()); ?></th>
	<th class="w50">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['pages']) and count((array)$this->_vars['pages'])): foreach ((array)$this->_vars['pages'] as $this->_vars['key'] => $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?> id="<?php echo $this->_vars['key']; ?>
_tr">
	<td class="first center"><input type="checkbox" value="<?php echo $this->_vars['key']; ?>
" class="del"></td>
	<td><?php echo $this->_vars['key']; ?>
</td>
	<td class="editable" id="<?php echo $this->_vars['key']; ?>
"><?php echo $this->_vars['item']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/pages_edit/<?php echo $this->_vars['current_lang_id']; ?>
/<?php echo $this->_vars['current_module_id']; ?>
/<?php echo $this->_vars['key']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_page', 'languages', '', 'text', array()); ?>" title="<?php echo l('link_edit_page', 'languages', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/languages/pages_delete/<?php echo $this->_vars['current_lang_id']; ?>
/<?php echo $this->_vars['current_module_id']; ?>
/<?php echo $this->_vars['key']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_page', 'languages', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_page', 'languages', '', 'text', array()); ?>" title="<?php echo l('link_delete_page', 'languages', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  endif; ?>
<script>
var reload_url = "<?php echo $this->_vars['site_url']; ?>
admin/languages/pages/<?php echo $this->_vars['current_lang_id']; ?>
/";
var change_url = '<?php echo $this->_vars['site_url']; ?>
admin/languages/ajax_pages_save/<?php echo $this->_vars['current_lang_id']; ?>
/<?php echo $this->_vars['current_module_id']; ?>
';
<?php echo '

function reload_page(module_id){
	location.href=reload_url+module_id;
}

function delete_strings(url){
	var gidArr = new Object;
	$(".del:checked").each(function(i){
		gidArr[i] = $(this).val();
	});
	$.ajax({
		url: url, 
		type: \'POST\',
		data: ({gids: gidArr}), 
		cache: false,
		success: function(data){
			for(i in gidArr){ $(\'#\'+gidArr[i]+\'_tr\').remove(); }
			$(\'#pages_table tr\').removeClass(\'zebra\');
			$("#pages_table tr:odd").addClass("zebra");
		}
	});
}

$(function(){
	$(\'.editable\').editable(change_url, {
		type: \'textarea\',
		tooltip: \'';  echo l("default_editable_text", "languages", '', "js", array());  echo '\',
		placeholder: \'<font class="hide_text">';  echo l("default_editable_text", "languages", '', "js", array());  echo '</font>\',
		name : \'text\',
		submit : \'Save\',
		height: \'auto\',
		width: 300,
		callback: function(value, settings){
			$(this).html(settings.current);
		},
		onEdit: function(content){
//			alert(content);
		}
	});
});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
