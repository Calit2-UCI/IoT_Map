<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:47:52 Pacific Daylight Time */ ?>

<div>
<?php if (is_array($this->_vars['settings_errors']) and count((array)$this->_vars['settings_errors'])): foreach ((array)$this->_vars['settings_errors'] as $this->_vars['item']): ?>
<font class="req"><?php echo $this->_vars['item']; ?>
</font><br>
<?php endforeach; endif; ?>
</div>
		<div class="form">
			<div class="row">
				<div class="h"><?php echo l('field_order_key', 'start', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['product_order_key']; ?>
" name="product_order_key"></div>
			</div>
		</div>
