<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 00:17:39 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<h1><?php echo l('header_add_form_field', 'field_editor', '', 'text', array()); ?></h1>
	<div class="inside">
		<?php if ($this->_vars['form_type'] == 'sections'): ?>
			<b><?php echo l('text_add_form_select_section', 'field_editor', '', 'text', array()); ?>:</b>
			<ul id="section_select" class="form-fields">
			<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['item']): ?>
				<li gid="<?php echo $this->_vars['item']['gid']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		<?php else: ?>
			<b><?php echo l('text_add_form_select_field', 'field_editor', '', 'text', array()); ?></b> <?php echo l('text_add_form_or', 'field_editor', '', 'text', array()); ?> <a href="#" id="fields_back" onclick="javascript: return false;"><?php echo l('text_add_form_select_another_s', 'field_editor', '', 'text', array()); ?></a><br>
			<ul id="field_select" class="form-fields">
			<?php if (is_array($this->_vars['fields']) and count((array)$this->_vars['fields'])): foreach ((array)$this->_vars['fields'] as $this->_vars['item']): ?>
				<li gid="<?php echo $this->_vars['item']['gid']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</li>
			<?php endforeach; endif; ?>
			</ul>
		<?php endif; ?>
		<a class="cancel" href="#" id="fields_close" onclick="javascript: return false;"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</div>
</div>