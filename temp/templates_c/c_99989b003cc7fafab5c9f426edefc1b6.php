<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 23:20:43 Pacific Daylight Time */ ?>

<div class="content-block">
	<h1><?php echo l('reg_subscriptions', 'subscriptions', '', 'text', array()); ?></h1>

	<form action="" method="post" >
		<?php if (is_array($this->_vars['subscriptions_list']) and count((array)$this->_vars['subscriptions_list'])): foreach ((array)$this->_vars['subscriptions_list'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<div class="r">
				<div class="v">
					<input <?php if ($this->_vars['item']['subscribed']): ?>checked<?php endif; ?> type="checkbox" name="user_subscriptions_list[]" value="<?php echo $this->_vars['item']['id']; ?>
" id="sub<?php echo $this->_vars['item']['id']; ?>
">
					<label for="sub<?php echo $this->_vars['item']['id']; ?>
"><?php echo l($this->_vars['item']['name_i'], 'subscriptions', '', 'text', array()); ?></label>
				</div>
			</div>
		<?php endforeach; endif; ?>
		<div class="r">
			<div class="b"><input type="submit" class='btn' value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_subscriptions_save"></div>
		</div>
	</form>
</div>