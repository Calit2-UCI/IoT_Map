<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.slider.php');
$this->register_function("slider", "tpl_function_slider"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.checkbox.php');
$this->register_function("checkbox", "tpl_function_checkbox"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.selectbox.php');
$this->register_function("selectbox", "tpl_function_selectbox"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 00:42:07 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<?php $this->assign('field_gid', $this->_vars['field']['field']['gid']); ?>
	<?php if (! $this->_vars['field_name']):  $this->assign('field_name', $this->_vars['field']['field']['gid']);  endif; ?>
	<?php if ($this->_vars['field']['field']['type'] == 'select'): ?>
		<?php 
$this->assign('default_select_lang', l('select_default', 'start', '', 'text', array()));
 ?>
		<?php if ($this->_vars['field']['settings']['search_type'] == 'one'): ?>
			<?php echo tpl_function_selectbox(array('input' => $this->_vars['field_name'],'id' => $this->_vars['field_name'].'_select','value' => $this->_vars['field']['field_content']['options']['option'],'selected' => $this->_vars['data'][$this->_vars['field_name']],'default' => $this->_vars['default_select_lang']), $this);?>
		<?php else: ?>
			<?php echo tpl_function_checkbox(array('input' => $this->_vars['field_name'],'id' => $this->_vars['field_name'].'_select','value' => $this->_vars['field']['field_content']['options']['option'],'selected' => $this->_vars['data'][$this->_vars['field_name']]), $this);?>
		<?php endif; ?>
	<?php elseif ($this->_vars['field']['field']['type'] == 'multiselect'): ?>
		<?php if ($this->_vars['field']['field_content']['settings_data_array']['view_type'] == 'mselect'): ?>
			<?php echo tpl_function_selectbox(array('input' => $this->_vars['field_name'],'id' => $this->_vars['field_name'].'_select','value' => $this->_vars['field']['field_content']['options']['option'],'selected' => $this->_vars['data'][$this->_vars['field_name']],'default' => $this->_vars['default_select_lang']), $this);?>
		<?php else: ?>
			<?php echo tpl_function_checkbox(array('input' => $this->_vars['field_name'],'id' => $this->_vars['field_name'].'_select','value' => $this->_vars['field']['field_content']['options']['option'],'selected' => $this->_vars['field']['field_content']['value'],'group_methods' => 1), $this);?>
		<?php endif; ?>
	<?php elseif ($this->_vars['field']['field']['type'] == 'text'): ?>
		<?php if ($this->_vars['field']['settings']['search_type'] == 'number' && $this->_vars['field']['settings']['view_type'] == 'range'): ?>
			<?php $this->assign('field_gid_min', $this->_vars['field_name'].'_min'); ?>
			<?php $this->assign('field_gid_max', $this->_vars['field_name'].'_max'); ?>
			<input type="text" name="<?php echo $this->_vars['field_name']; ?>
_min" class="short" value="<?php echo $this->_vars['data'][$this->_vars['field_gid_min']]; ?>
">&nbsp;-&nbsp;
			<input type="text" name="<?php echo $this->_vars['field_name']; ?>
_max" class="short" value="<?php echo $this->_vars['data'][$this->_vars['field_gid_max']]; ?>
">
		<?php elseif ($this->_vars['field']['settings']['search_type'] == 'number'): ?>
			<input type="text" name="<?php echo $this->_vars['field_name']; ?>
" class="short" value="<?php echo $this->_vars['data'][$this->_vars['field_name']]; ?>
">
		<?php else: ?>
			<input type="text" name="<?php echo $this->_vars['field_name']; ?>
" value="<?php echo $this->_vars['data'][$this->_vars['field_name']]; ?>
">
		<?php endif; ?>
	<?php elseif ($this->_vars['field']['field']['type'] == 'range'): ?>
		<div class="w200">
			<?php if ($this->_vars['field']['settings']['search_type'] == 'range'): ?>
				<?php $this->assign('field_gid_min', $this->_vars['field_name'].'_min'); ?>
				<?php $this->assign('field_gid_max', $this->_vars['field_name'].'_max'); ?>
				<?php echo tpl_function_slider(array('id' => $this->_vars['field_name'].'_slider','min' => $this->_vars['field']['field_content']['settings_data_array']['min_val'],'max' => $this->_vars['field']['field_content']['settings_data_array']['max_val'],'value_min' => $this->_vars['data'][$this->_vars['field_gid_min']],'value_max' => $this->_vars['data'][$this->_vars['field_gid_max']],'field_name_min' => $this->_vars['field_name'].'_min','field_name_max' => $this->_vars['field_name'].'_max'), $this);?>
			<?php else: ?>
				<input type="text" name="<?php echo $this->_vars['field_name']; ?>
" class="short" value="<?php echo $this->_vars['data'][$this->_vars['field_name']]; ?>
">
			<?php endif; ?>
		</div>
	<?php elseif ($this->_vars['field']['field']['type'] == 'textarea'): ?>
		<input type="text" name="<?php echo $this->_vars['field_name']; ?>
" value="<?php echo $this->_vars['data'][$this->_vars['field_name']]; ?>
">
	<?php elseif ($this->_vars['field']['field']['type'] == 'checkbox'): ?>
		<?php if ($this->_vars['field']['field_content']['value']):  $this->assign('chbx_field_value', 1);  else:  $this->assign('chbx_field_value', 0);  endif; ?>
		<?php echo tpl_function_checkbox(array('input' => $this->_vars['field_name'],'id' => $this->_vars['field_name'].'_select','value' => $this->_vars['chbx_field_value'],'selected' => $this->_vars['data'][$this->_vars['field_name']]), $this);?>
	<?php endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>