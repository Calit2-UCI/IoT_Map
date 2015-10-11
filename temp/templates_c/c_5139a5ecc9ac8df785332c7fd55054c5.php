<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-11 00:30:43 Pacific Daylight Time */ ?>

<menu id="users-top-menu" class="header-item" label="<?php echo l('on_account_header', 'users_payments', '', 'text', array()); ?>">
	<i class="fa-comments"></i>&nbsp;<b class="sum"></b>&nbsp;
	<i class="fa-caret-down"></i>
	<div class="drop w300">
		<span><?php echo l('notifications', 'users', '', 'text', array()); ?></span>
		<menu>
			<?php echo tpl_function_block(array('name' => 'new_messages','module' => 'mailbox','template' => 'header'), $this);?>
			<?php echo tpl_function_block(array('name' => 'admin_new_messages','module' => 'tickets','template' => 'header','is_admin' => '1'), $this);?>
			
			<?php echo tpl_function_block(array('name' => 'friend_requests','module' => 'friendlist','template' => 'header'), $this);?>
			<?php echo tpl_function_block(array('name' => 'winks_count','module' => 'winks','template' => 'header'), $this);?>
			<?php echo tpl_function_block(array('name' => 'visitors','module' => 'users','template' => 'header'), $this);?>
			<?php echo tpl_function_block(array('name' => 'new_kisses','module' => 'kisses','template' => 'header'), $this);?>
			<li class="no-notifications hide-always"><?php echo l('no_notifications', 'users', '', 'text', array()); ?></li>
		</menu>
	</div>
</menu>
<script type='text/javascript'><?php echo '
	$(function(){
		loadScripts(
			["';  echo tpl_function_js(array('file' => 'top-menu.js','module' => 'users','return' => 'path'), $this); echo '"],
			function(){
				new topMenu({
					siteUrl: site_url
				});
			}
		);
	});
</script>'; ?>

