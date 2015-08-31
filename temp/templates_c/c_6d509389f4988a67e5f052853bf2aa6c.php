<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 20:06:00 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if (is_array($this->_vars['fields_data']) and count((array)$this->_vars['fields_data'])): foreach ((array)$this->_vars['fields_data'] as $this->_vars['gid'] => $this->_vars['field']): ?>
	<div class="r">
		<div class="f"><?php echo $this->_vars['field']['name']; ?>
:</div>
		<div class="v">
		<?php if ($this->_vars['field']['field_type'] == 'select'): ?>
			<?php if (! $this->_vars['field']['value']):  else:  echo $this->_run_modifier($this->_vars['field']['value'], 'nl2br', 'PHP', 1);  endif; ?>
		<?php elseif ($this->_vars['field']['field_type'] == 'textarea'): ?>
			<?php if (! $this->_vars['field']['value']):  else:  echo $this->_run_modifier($this->_vars['field']['value'], 'nl2br', 'PHP', 1);  endif; ?>
		<?php elseif ($this->_vars['field']['field_type'] == 'text'): ?>
			<?php if (! $this->_vars['field']['value']):  else:  echo $this->_run_modifier($this->_vars['field']['value'], 'nl2br', 'PHP', 1);  endif; ?>
		<?php elseif ($this->_vars['field']['field_type'] == 'range'): ?>
			<?php if (! $this->_vars['field']['value']):  else:  echo $this->_vars['field']['value'];  endif; ?>
		<?php elseif ($this->_vars['field']['field_type'] == 'multiselect'): ?>
			<?php if (! $this->_vars['field']['value']):  else:  echo $this->_vars['field']['value_str'];  endif; ?>
		<?php elseif ($this->_vars['field']['field_type'] == 'checkbox'): ?>
			<?php if ($this->_vars['field']['value']):  echo l('option_checkbox_yes', 'start', '', 'text', array());  else:  echo l('option_checkbox_no', 'start', '', 'text', array());  endif; ?>
		<?php endif; ?>
		</div>
	</div>
<?php endforeach; endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
