<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.slider.php');
$this->register_function("slider", "tpl_function_slider"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.in_array.php');
$this->register_function("in_array", "tpl_function_in_array"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 22:30:25 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if (is_array($this->_vars['fields_data']) and count((array)$this->_vars['fields_data'])): foreach ((array)$this->_vars['fields_data'] as $this->_vars['item']): ?>
	<?php $this->assign('field_gid', $this->_vars['item']['gid']); ?>
	<?php if (! $this->_vars['not_editable_fields'][$this->_vars['field_gid']]): ?>
		<div class="r">
			<div class="f"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v" id="field-<?php echo $this->_vars['item']['gid']; ?>
">
			<?php if ($this->_vars['item']['field_type'] == 'select'): ?>
				<?php if ($this->_vars['item']['settings_data_array']['view_type'] == 'select'): ?>
					<select name="<?php echo $this->_vars['item']['field_name']; ?>
">
						<?php if ($this->_vars['item']['settings_data_array']['empty_option']): ?><option value="0"<?php if ($this->_vars['value'] == 0): ?> selected<?php endif; ?>>...</option><?php endif; ?>
						<?php if (is_array($this->_vars['item']['options']['option']) and count((array)$this->_vars['item']['options']['option'])): foreach ((array)$this->_vars['item']['options']['option'] as $this->_vars['value'] => $this->_vars['option']): ?><option value="<?php echo $this->_vars['value']; ?>
"<?php if ($this->_vars['value'] == $this->_vars['item']['value']): ?> selected<?php endif; ?>><?php echo $this->_vars['option']; ?>
</option><?php endforeach; endif; ?>
					</select>
				<?php else: ?>
					<?php if ($this->_vars['item']['settings_data_array']['empty_option']): ?><input type="radio" name="<?php echo $this->_vars['item']['field_name']; ?>
" value="0"<?php if ($this->_vars['value'] == 0): ?> checked<?php endif; ?> id="<?php echo $this->_vars['item']['field_name']; ?>
_0"><label for="<?php echo $this->_vars['item']['field_name']; ?>
_0">No select</label><br><?php endif; ?>
					<?php if (is_array($this->_vars['item']['options']['option']) and count((array)$this->_vars['item']['options']['option'])): foreach ((array)$this->_vars['item']['options']['option'] as $this->_vars['value'] => $this->_vars['option']): ?><input type="radio" name="<?php echo $this->_vars['item']['field_name']; ?>
" value="<?php echo $this->_vars['value']; ?>
" <?php if ($this->_vars['value'] == $this->_vars['item']['value']): ?> checked<?php endif; ?> id="<?php echo $this->_vars['item']['field_name']; ?>
_<?php echo $this->_vars['value']; ?>
"><label for="<?php echo $this->_vars['item']['field_name']; ?>
_<?php echo $this->_vars['value']; ?>
"><?php echo $this->_vars['option']; ?>
</label><br><?php endforeach; endif; ?>
				<?php endif; ?>
			<?php elseif ($this->_vars['item']['field_type'] == 'multiselect'): ?>
				<?php if ($this->_vars['item']['settings_data_array']['view_type'] == 'mselect'): ?>
					<select name="<?php echo $this->_vars['item']['field_name']; ?>
[]" multiple>
						<?php if (is_array($this->_vars['item']['options']['option']) and count((array)$this->_vars['item']['options']['option'])): foreach ((array)$this->_vars['item']['options']['option'] as $this->_vars['value'] => $this->_vars['option']): ?><option value="<?php echo $this->_vars['value']; ?>
" <?php echo tpl_function_in_array(array('match' => $this->_vars['value'],'array' => $this->_vars['item']['value'],'returnvalue' => "selected"), $this);?>><?php echo $this->_vars['option']; ?>
</option><?php endforeach; endif; ?>
					</select>
				<?php else: ?>
					<?php if (is_array($this->_vars['item']['options']['option']) and count((array)$this->_vars['item']['options']['option'])): foreach ((array)$this->_vars['item']['options']['option'] as $this->_vars['value'] => $this->_vars['option']): ?>
						<div class="chbx">
							<input type="checkbox" name="<?php echo $this->_vars['item']['field_name']; ?>
[]" value="<?php echo $this->_vars['value']; ?>
" <?php echo tpl_function_in_array(array('match' => $this->_vars['value'],'array' => $this->_vars['item']['value'],'returnvalue' => "checked"), $this);?> id="<?php echo $this->_vars['item']['field_name']; ?>
_<?php echo $this->_vars['value']; ?>
"><label for="<?php echo $this->_vars['item']['field_name']; ?>
_<?php echo $this->_vars['value']; ?>
"><?php echo $this->_vars['option']; ?>
</label>
						</div>
					<?php endforeach; endif; ?>
					<div class="clr"></div>
					<a href="#" class="select-link"><?php echo l('select_all', 'start', '', 'text', array()); ?></a> &nbsp;|&nbsp;<a href="#" class="unselect-link"><?php echo l('unselect_all', 'start', '', 'text', array()); ?></a> 
				<?php endif; ?>
			<?php elseif ($this->_vars['item']['field_type'] == 'text'): ?>
				<input type="text" name="<?php echo $this->_vars['item']['field_name']; ?>
" value="<?php echo $this->_run_modifier($this->_vars['item']['value'], 'escape', 'plugin', 1); ?>
" maxlength="<?php echo $this->_vars['item']['settings_data_array']['max_char']; ?>
" <?php if ($this->_vars['item']['settings_data_array']['max_char'] < 11): ?>class="short"<?php elseif ($this->_vars['item']['settings_data_array']['max_char'] > 1100): ?>class="long"<?php endif; ?>>
			<?php elseif ($this->_vars['item']['field_type'] == 'range'): ?>
				<div class="w500">
					<?php echo tpl_function_slider(array('id' => $this->_vars['item']['field_name'].'_slider','single' => 1,'active_always' => 1,'min' => $this->_vars['item']['settings_data_array']['min_val'],'max' => $this->_vars['item']['settings_data_array']['max_val'],'value' => $this->_vars['item']['value'],'field_name' => $this->_vars['item']['field_name']), $this);?>
				</div>
			<?php elseif ($this->_vars['item']['field_type'] == 'textarea'): ?>
				<textarea name="<?php echo $this->_vars['item']['field_name']; ?>
"><?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  echo $this->_run_modifier($this->_vars['item']['value'], 'escape', 'plugin', 1);  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?></textarea>
			<?php elseif ($this->_vars['item']['field_type'] == 'checkbox'): ?>
				<input type="checkbox" name="<?php echo $this->_vars['item']['field_name']; ?>
" value="1"<?php if ($this->_vars['item']['value'] == '1'): ?> checked<?php endif; ?>>
			<?php endif; ?>
			&nbsp;
			</div>
		</div>
	<?php endif;  endforeach; endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
	
<script type="text/javascript"><?php echo '
	function setchbx(fid, status){
		if(status){
			$(\'#\'+fid).find(\'input[type=checkbox]\').prop(\'checked\', true);
		}else{
			$(\'#\'+fid).find(\'input[type=checkbox]\').prop(\'checked\', false);
		}
	}
	$(function(){
		$(\'.select-link\').unbind(\'click\').bind(\'click\', function(){
			setchbx($(this).parent().attr(\'id\'), 1); return false;
		});
		$(\'.unselect-link\').unbind(\'click\').bind(\'click\', function(){
			setchbx($(this).parent().attr(\'id\'), 0); return false;
		});
	});
'; ?>
</script>