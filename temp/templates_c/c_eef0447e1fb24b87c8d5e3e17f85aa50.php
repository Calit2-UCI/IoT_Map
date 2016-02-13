<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-15 07:04:06 Pacific Standard Time */ ?>

<div class="content-block load_content">
	<h1><?php echo l('header_login', 'users', '', 'text', array()); ?></h1>

	<div class="inside logform">
		<form action="<?php echo $this->_vars['site_url']; ?>
users/login" method="post">
			<div class="r">
				<div class="f"><?php echo l('field_email', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" name="email" <?php if ($this->_vars['DEMO_MODE']): ?>value="<?php echo $this->_vars['demo_user_type_login_settings']['login']; ?>
"<?php endif; ?>></div>
			</div>
			<div class="r">
				<div class="f"><?php echo l('field_password', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="password" name="password" <?php if ($this->_vars['DEMO_MODE']): ?>value="<?php echo $this->_vars['demo_user_type_login_settings']['password']; ?>
"<?php endif; ?>>
					<span class="v-link"><a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'restore'), $this);?>"><?php echo l('link_restore', 'users', '', 'text', array()); ?></a></span>
				</div>
			</div>
			
			
			<div class="r">
				<input type="submit" value="<?php echo l('btn_login', 'start', '', 'button', array()); ?>" name="logbtn">
			</div>
		</form>
		<?php echo tpl_function_helper(array('func_name' => 'show_social_networking_login','module' => 'users_connections'), $this);?>


	</div>
	<div class="clr"></div>
</div>
