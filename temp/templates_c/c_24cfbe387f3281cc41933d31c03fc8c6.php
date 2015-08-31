<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:54:40 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_fields_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="#" id="add_field_form" onclick="javascript: return false;"><?php echo l('link_add_form_field', 'field_editor', '', 'text', array()); ?></a></div></li>
		<li><div class="l"><a href="#" id="add_section_form" onclick="javascript: return false;"><?php echo l('link_add_form_section', 'field_editor', '', 'text', array()); ?></a></div></li>
		<?php if ($this->_vars['data']['fields_data']): ?><li><div class="l"><a href="#" id="sorting_form" onclick="javascript: return false;"><?php echo l('link_save_sorting', 'field_editor', '', 'text', array()); ?></a></div></li><?php endif; ?>
	</ul>
	&nbsp;
</div>

<div class="edit-form">
	<div class="row header"><?php echo l('form_name', 'field_editor', '', 'text', array()); ?>: <?php echo $this->_vars['data']['name']; ?>
</div>
</div>

<div id="menu_items">
	<ul name="form_root" id="form_root" class="sortable"></ul>
</div>

<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/forms/<?php echo $this->_vars['data']['editor_type_gid']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
<?php echo tpl_function_js(array('module' => 'field_editor','file' => 'admin-form-fields.js'), $this);?>
<script type='text/javascript'>
var field_data = <?php if ($this->_vars['data']['field_data_json']):  echo $this->_vars['data']['field_data_json'];  else: ?>[]<?php endif; ?>;
var field_names = <?php if ($this->_vars['data']['field_names_json']):  echo $this->_vars['data']['field_names_json'];  else: ?>[]<?php endif; ?>;
<?php echo '

	$(function(){
		new formFields({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
			field_data: field_data,
			field_names: field_names,
			formId: \'';  echo $this->_vars['data']['id'];  echo '\',
			empty_fields: \'';  echo l("error_form_name_incorrect", "field_editor", '', 'text', array());  echo '\'
//			urlSaveSort: \'admin/field_editor/ajax_section_sort\'
		});
	});
'; ?>

</script>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>