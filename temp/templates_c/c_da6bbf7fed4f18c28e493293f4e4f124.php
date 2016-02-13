<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 00:47:19 Pacific Standard Time */ ?>

<?php if ($this->_vars['template_data']['price_type'] == 1): ?>
		<div class="row">
			<div class="h"><?php echo l('field_price', 'services', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['price']; ?>
" name="price" class="short"> <?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start'), $this);?></div>
		</div>
<?php endif;  if (is_array($this->_vars['template_data']['data_admin_array']) and count((array)$this->_vars['template_data']['data_admin_array'])): foreach ((array)$this->_vars['template_data']['data_admin_array'] as $this->_vars['item']): ?>
		<div class="row">
			<div class="h"><?php echo $this->_vars['item']['name']; ?>
: </div>
			<?php if ($this->_vars['item']['type'] == 'string'): ?>
			<div class="v"><input type="text" value="<?php echo $this->_vars['item']['value']; ?>
" name="data_admin[<?php echo $this->_vars['item']['gid']; ?>
]"></div>
			<?php elseif ($this->_vars['item']['type'] == 'int'): ?>
			<div class="v"><input type="text" value="<?php echo $this->_vars['item']['value']; ?>
" name="data_admin[<?php echo $this->_vars['item']['gid']; ?>
]" class="short"></div>
			<?php elseif ($this->_vars['item']['type'] == 'price'): ?>
			<div class="v"><input type="text" value="<?php echo $this->_vars['item']['value']; ?>
" name="data_admin[<?php echo $this->_vars['item']['gid']; ?>
]" class="short"> <?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start'), $this);?></div>
			<?php elseif ($this->_vars['item']['type'] == 'text'): ?>
			<div class="v"><textarea name="data_admin[<?php echo $this->_vars['item']['gid']; ?>
]"><?php echo $this->_vars['item']['value']; ?>
</textarea></div>
			<?php elseif ($this->_vars['item']['type'] == 'checkbox'): ?>
			<div class="v"><input type="checkbox" value="1" name="data_admin[<?php echo $this->_vars['item']['gid']; ?>
]" <?php if ($this->_vars['item']['value'] == '1'): ?>checked<?php endif; ?>></div>
			<?php endif; ?>
		</div>
<?php endforeach; endif; ?>