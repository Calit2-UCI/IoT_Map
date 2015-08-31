<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 00:32:36 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<h1><?php echo l('header_change_field_settings', 'field_editor', '', 'text', array()); ?></h1>
	<div class="inside">
		<form id="save_section_name">
			<div class="edit-form n150">
				<?php if ($this->_vars['field_type'] == 'select'): ?>
					<div class="row">
						<div class="h"><?php echo l('field_select_search_type', 'field_editor', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="search_type" id="field_search_type">
								<option value="one" <?php if ($this->_vars['field']['settings']['search_type'] == 'one'): ?>selected<?php endif; ?>><?php echo l('field_select_search_type_one', 'field_editor', '', 'text', array()); ?></option>			
								<option value="many" <?php if ($this->_vars['field']['settings']['search_type'] == 'many'): ?>selected<?php endif; ?>><?php echo l('field_select_search_type_many', 'field_editor', '', 'text', array()); ?></option>			
							</select>
						</div>
					</div>
					<div class="row zebra<?php if ($this->_vars['field']['settings']['search_type'] == 'many'): ?> hide<?php endif; ?>" id="field_view_type_one">
						<div class="h"><?php echo l('field_select_view_type_header', 'field_editor', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="view_type[one]">
								<option value="select" <?php if ($this->_vars['field']['settings']['view_type'] == 'select'): ?>selected<?php endif; ?>><?php echo l('field_select_view_type_select', 'field_editor', '', 'text', array()); ?></option>			
								<option value="radio" <?php if ($this->_vars['field']['settings']['view_type'] == 'radio'): ?>selected<?php endif; ?>><?php echo l('field_select_view_type_radio', 'field_editor', '', 'text', array()); ?></option>			
							</select>
						</div>
					</div>
					<div class="row zebra<?php if ($this->_vars['field']['settings']['search_type'] == 'one' || ! $this->_vars['field']['settings']['search_type']): ?> hide<?php endif; ?>" id="field_view_type_many">
						<div class="h"><?php echo l('field_select_view_type_header', 'field_editor', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="view_type[many]">
								<option value="select" <?php if ($this->_vars['field']['settings']['view_type'] == 'select'): ?>selected<?php endif; ?>><?php echo l('field_select_view_type_multi', 'field_editor', '', 'text', array()); ?></option>			
								<option value="radio" <?php if ($this->_vars['field']['settings']['view_type'] == 'radio'): ?>selected<?php endif; ?>><?php echo l('field_select_view_type_checkbox', 'field_editor', '', 'text', array()); ?></option>			
								<option value="slider" <?php if ($this->_vars['field']['settings']['view_type'] == 'slider'): ?>selected<?php endif; ?>><?php echo l('field_select_view_type_slider', 'field_editor', '', 'text', array()); ?></option>
							</select>
						</div>
					</div>
				<?php elseif ($this->_vars['field_type'] == 'text'): ?>
					<div class="row">
						<div class="h"><?php echo l('field_text_search_type', 'field_editor', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="search_type" id="field_search_type">
								<option value="text" <?php if ($this->_vars['field']['settings']['search_type'] == 'text'): ?>selected<?php endif; ?>><?php echo l('field_search_type_text', 'field_editor', '', 'text', array()); ?></option>			
								<option value="number" <?php if ($this->_vars['field']['settings']['search_type'] == 'number'): ?>selected<?php endif; ?>><?php echo l('field_search_type_number', 'field_editor', '', 'text', array()); ?></option>			
							</select>
						</div>
					</div>
					<div class="row zebra<?php if ($this->_vars['field']['settings']['search_type'] == 'number'): ?> hide<?php endif; ?>" id="field_view_type_text">
						<div class="h"><?php echo l('field_text_view_type_header', 'field_editor', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="view_type[text]">
								<option value="equal" <?php if ($this->_vars['field']['settings']['view_type'] == 'equal'): ?>selected<?php endif; ?>><?php echo l('field_text_view_type_exact', 'field_editor', '', 'text', array()); ?></option>			
								<option value="range" <?php if ($this->_vars['field']['settings']['view_type'] == 'range'): ?>selected<?php endif; ?>><?php echo l('field_text_view_type_like', 'field_editor', '', 'text', array()); ?></option>			
							</select>
						</div>
					</div>
					<div class="row zebra<?php if ($this->_vars['field']['settings']['search_type'] == 'text' || ! $this->_vars['field']['settings']['search_type']): ?> hide<?php endif; ?>" id="field_view_type_number">
						<div class="h"><?php echo l('field_text_view_type_header', 'field_editor', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="view_type[number]">
								<option value="equal" <?php if ($this->_vars['field']['settings']['view_type'] == 'equal'): ?>selected<?php endif; ?>><?php echo l('field_text_view_type_exact', 'field_editor', '', 'text', array()); ?></option>			
								<option value="range" <?php if ($this->_vars['field']['settings']['view_type'] == 'range'): ?>selected<?php endif; ?>><?php echo l('field_text_view_type_range', 'field_editor', '', 'text', array()); ?></option>			
							</select>
						</div>
					</div>
				<?php elseif ($this->_vars['field_type'] == 'range'): ?>
					<div class="row">
						<div class="h"><?php echo l('field_text_search_type', 'field_editor', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="search_type" id="field_search_type">
								<option value="range" <?php if ($this->_vars['field']['settings']['search_type'] == 'range'): ?>selected<?php endif; ?>><?php echo l('field_search_type_range', 'field_editor', '', 'text', array()); ?></option>			
								<option value="number" <?php if ($this->_vars['field']['settings']['search_type'] == 'number'): ?>selected<?php endif; ?>><?php echo l('field_search_type_number', 'field_editor', '', 'text', array()); ?></option>			
							</select>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<script><?php echo '
				$(function(){
					$(\'#field_search_type\').bind(\'change\', function(){
						$(this).find(\'option\').each(function(){
							$(\'#field_view_type_\'+$(this).val()).hide();			
						});
						$(\'#field_view_type_\'+$(this).val()).show();
					});
				});
			</script>'; ?>

			<input type="hidden" name="section_gid" value="<?php echo $this->_vars['section_gid']; ?>
" id="ajax_section_gid">
			<div class="btn"><div class="l"><input type="button" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" id="save_settings_btn"></div></div>
			<a class="cancel" href="#" id="cancel_save_settings" onclick="return false;"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
		</form>
	</div>
</div>