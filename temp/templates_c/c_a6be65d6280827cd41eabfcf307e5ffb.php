<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:52:00 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<h1><?php if ($this->_vars['option_gid']):  echo l('header_change_select_option', 'field_editor', '', 'text', array());  else:  echo l('header_add_select_option', 'field_editor', '', 'text', array());  endif; ?></h1>
	<div class="inside">
		<form method="post" action="" name="save_form">
			<div class="edit-form n150" id='change_option_block'>
				<?php if (is_array($this->_vars['lang_data']) and count((array)$this->_vars['lang_data'])): foreach ((array)$this->_vars['lang_data'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
				<div class="row<?php if (!($this->_vars['counter'] % 2)): ?> zebra<?php endif; ?>">
					<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
					<div class="v"><input type="text" name="<?php echo $this->_vars['key']; ?>
" value="<?php echo $this->_vars['item']['value']; ?>
">&nbsp;<span>(<?php echo $this->_vars['item']['field_name']; ?>
)</span></div>
				</div>
				<?php endforeach; endif; ?>
			</div>
			<div class="btn"><div class="l"><input type="button" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" id="btn_save"></div></div>
			<a class="cancel" href="#" id="btn_cancel"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
		</form>
	</div>
</div>