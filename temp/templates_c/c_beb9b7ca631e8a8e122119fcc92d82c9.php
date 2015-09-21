<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:39:51 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<h1><?php echo l('header_add_funds_selected', 'users_payments', '', 'text', array()); ?></h1>
	<div class="inside">
		<div class="edit-form n100">
			<div class="row">
				<div class="h"><?php echo l('field_amount', 'users_payments', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" value="" name="amount" class="short" id="add_fund_amount" autofocus="autofocus" /> <?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start'), $this);?></div>
				<script>setTimeout("$('#add_fund_amount').focus()", 100);</script>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="button" name="btn_save" value="<?php echo l('btn_add', 'start', '', 'button', array()); ?>" onclick="javascript: send_add_funds();"></div></div>
		<div class="clr"></div>
	</div>
</div>