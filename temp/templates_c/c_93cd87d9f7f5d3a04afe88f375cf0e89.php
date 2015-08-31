<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 00:17:44 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<h1><?php if ($this->_vars['section_gid']):  echo l('header_edit_form_section', 'field_editor', '', 'text', array());  else:  echo l('header_add_form_section', 'field_editor', '', 'text', array());  endif; ?></h1>
	<div class="inside">
	<form id="save_section_name">
	<div class="edit-form n150">
		<div class="row">
			<div class="h"><?php echo l('field_name', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data'][$this->_vars['cur_lang']]; ?>
" name="langs[<?php echo $this->_vars['cur_lang']; ?>
]"></div>
		</div>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<?php if ($this->_vars['lang_id'] != $this->_vars['cur_lang']): ?>
		<div class="row<?php if (!($this->_vars['counter'] % 2)): ?> zebra<?php endif; ?>">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data'][$this->_vars['lang_id']]; ?>
" name="langs[<?php echo $this->_vars['lang_id']; ?>
]"></div>
		</div>
		<?php endif; ?>
		<?php endforeach; endif; ?>
	</div>
	<input type="hidden" name="section_gid" value="<?php echo $this->_vars['section_gid']; ?>
" id="ajax_section_gid">
	<div class="btn"><div class="l"><input type="button" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" id="save_section_btn"></div></div>
	<a class="cancel" href="#" id="cancel_save_section" onclick="return false;"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</form>
	</div>
</div>