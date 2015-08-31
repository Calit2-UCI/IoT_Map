<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:58:57 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<h1><?php echo l('header_inline_lang_edit', 'start', '', 'text', array()); ?></h1>
	<div class="inside">
		<form name="lang_edit" class="edit-form n100">
			<div class="popup">
			
			<?php if (is_array($this->_vars['langs']) and count((array)$this->_vars['langs'])): foreach ((array)$this->_vars['langs'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
				<div class="row<?php if (!($this->_vars['counter'] % 2)): ?> zebra<?php endif; ?>">
					<div class="h"><label><?php echo $this->_vars['item']['name']; ?>
</label>: </div>
					<div class="v">
						<?php if ($this->_vars['is_textarea']): ?>
						<textarea name="field<?php echo $this->_vars['key']; ?>
" rows="10" cols="80" lang-editor="redactor" lang-id="<?php echo $this->_vars['key']; ?>
"></textarea>
						<?php else: ?>
						<input type="text" name="field<?php echo $this->_vars['key']; ?>
" value="" lang-editor="redactor" lang-id="<?php echo $this->_vars['key']; ?>
" />
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; endif; ?>
			
			</div>
			<div class="btn"><div class="l"><input type="button" id="lie_save" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
			<a class="cancel" href="#" id="lie_close"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
		</form>
	</div>
</div>
