<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:51:57 Pacific Daylight Time */ ?>

<?php if ($this->_vars['field_type'] == 'checkbox'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_checkbox_by_default', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="hidden" name="settings_data[default_value]" value="0">
				<input type="checkbox" name="settings_data[default_value]" value="1"<?php if ($this->_vars['data']['settings_data_array']['default_value']): ?> checked<?php endif; ?>>
			</div>
		</div>
<?php elseif ($this->_vars['field_type'] == 'text'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_text_by_default', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[default_value]" value="<?php echo $this->_vars['data']['settings_data_array']['default_value']; ?>
"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_text_min_char', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[min_char]" value="<?php echo $this->_vars['data']['settings_data_array']['min_char']; ?>
" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_text_max_char', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[max_char]" value="<?php echo $this->_vars['data']['settings_data_array']['max_char']; ?>
" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_text_template', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
			<select name="settings_data[template]">
			<?php if (is_array($this->_vars['initial']['template']['options']) and count((array)$this->_vars['initial']['template']['options'])): foreach ((array)$this->_vars['initial']['template']['options'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
"<?php if ($this->_vars['data']['settings_data_array']['template'] == $this->_vars['item']): ?> selected<?php endif; ?>><?php echo l("text_template_" . $this->_vars['item'] . "", 'field_editor', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_text_format', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
			<select name="settings_data[format]">
			<?php if (is_array($this->_vars['initial']['format']['options']) and count((array)$this->_vars['initial']['format']['options'])): foreach ((array)$this->_vars['initial']['format']['options'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
"<?php if ($this->_vars['data']['settings_data_array']['format'] == $this->_vars['item']): ?> selected<?php endif; ?>><?php echo l("text_format_" . $this->_vars['item'] . "", 'field_editor', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
			</select>
			</div>
		</div>
<?php elseif ($this->_vars['field_type'] == 'textarea'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_textarea_by_default', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[default_value]" value="<?php echo $this->_vars['data']['settings_data_array']['default_value']; ?>
"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_textarea_min_char', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[min_char]" value="<?php echo $this->_vars['data']['settings_data_array']['min_char']; ?>
" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_textarea_max_char', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[max_char]" value="<?php echo $this->_vars['data']['settings_data_array']['max_char']; ?>
" class="short"></div>
		</div>
<?php elseif ($this->_vars['field_type'] == 'select'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_select_view_type', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
			<select name="settings_data[view_type]">
			<?php if (is_array($this->_vars['initial']['view_type']['options']) and count((array)$this->_vars['initial']['view_type']['options'])): foreach ((array)$this->_vars['initial']['view_type']['options'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
"<?php if ($this->_vars['data']['settings_data_array']['view_type'] == $this->_vars['item']): ?> selected<?php endif; ?>><?php echo l("select_view_type_" . $this->_vars['item'] . "", 'field_editor', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_select_empty_option', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="hidden" name="settings_data[empty_option]" value="0">
				<input type="checkbox" name="settings_data[empty_option]" value="1"<?php if ($this->_vars['data']['settings_data_array']['empty_option']): ?> checked<?php endif; ?>>
			</div>
		</div>

		<div class="row">
			<div class="h"><?php echo l('field_select_options', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
				<div id="hidden_block"></div>
				<a href="#" id="add_option_link"><?php echo l('link_add_new_option', 'field_editor', '', 'text', array()); ?></a>
				<div class="select-options" id="select_options_block">
				<?php echo $this->_vars['options_block']; ?>

				</div>
			</div>
		</div>
		<?php echo tpl_function_js(array('module' => 'field_editor','file' => 'admin-field-editor-select.js'), $this);?>
		<script type='text/javascript'><?php echo '
			var sOptions;
			$(function(){
				sOptions =  new fieldEditorSelect({
					siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
					fieldID: \'';  echo $this->_vars['data']['id'];  echo '\',
					defaultMultiple: false,
					defaultValues: [';  echo $this->_vars['data']['settings_data_array']['default_value'];  echo ']
				});
			});
		'; ?>
</script>
<?php elseif ($this->_vars['field_type'] == 'multiselect'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_select_view_type', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
			<select name="settings_data[view_type]">
			<?php if (is_array($this->_vars['initial']['view_type']['options']) and count((array)$this->_vars['initial']['view_type']['options'])): foreach ((array)$this->_vars['initial']['view_type']['options'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
"<?php if ($this->_vars['data']['settings_data_array']['view_type'] == $this->_vars['item']): ?> selected<?php endif; ?>><?php echo l("select_view_type_" . $this->_vars['item'] . "", 'field_editor', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_select_options', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
				<div id="hidden_block"></div>
				<a href="#" id="add_option_link"><?php echo l('link_add_new_option', 'field_editor', '', 'text', array()); ?></a>
				<div class="select-options" id="select_options_block">
				<?php echo $this->_vars['options_block']; ?>

				</div>
			</div>
		</div>
		<?php echo tpl_function_js(array('module' => 'field_editor','file' => 'admin-field-editor-select.js'), $this);?>
		<script type='text/javascript'><?php echo '
			var sOptions;
			$(function(){
				sOptions =  new fieldEditorSelect({
					siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
					fieldID: \'';  echo $this->_vars['data']['id'];  echo '\',
					defaultMultiple: true,
					defaultValues: ';  echo tpl_function_json_encode(array('data' => $this->_vars['data']['settings_data_array']['default_value']), $this); echo '
				});
			});
		'; ?>
</script>
<?php elseif ($this->_vars['field_type'] == 'range'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_text_min_val', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[min_val]" value="<?php echo $this->_vars['data']['settings_data_array']['min_val']; ?>
" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_text_max_val', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="settings_data[max_val]" value="<?php echo $this->_vars['data']['settings_data_array']['max_val']; ?>
" class="short"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_text_template', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
			<select name="settings_data[template]">
			<?php if (is_array($this->_vars['initial']['template']['options']) and count((array)$this->_vars['initial']['template']['options'])): foreach ((array)$this->_vars['initial']['template']['options'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
"<?php if ($this->_vars['data']['settings_data_array']['template'] == $this->_vars['item']): ?> selected<?php endif; ?>><?php echo l("text_template_" . $this->_vars['item'] . "", 'field_editor', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
			</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_text_format', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
			<select name="settings_data[format]">
			<?php if (is_array($this->_vars['initial']['format']['options']) and count((array)$this->_vars['initial']['format']['options'])): foreach ((array)$this->_vars['initial']['format']['options'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
"<?php if ($this->_vars['data']['settings_data_array']['format'] == $this->_vars['item']): ?> selected<?php endif; ?>><?php echo l("text_format_" . $this->_vars['item'] . "", 'field_editor', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
			</select>
			</div>
		</div>
<?php endif; ?>