<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-25 23:16:32 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div id="index_registration_login_forms_<?php echo $this->_vars['dynamic_block_registration_login_form_data']['rand']; ?>
" class="index-login-form bg-html_bg p20">
	<?php echo tpl_function_helper(array('func_name' => 'show_social_networking_login','module' => 'users_connections'), $this);?>
	<div id="index_registration_form_<?php echo $this->_vars['dynamic_block_registration_login_form_data']['rand']; ?>
"<?php if ($this->_vars['dynamic_block_registration_login_form_data']['view'] == 'login_form'): ?> class="hide"<?php endif; ?>>
		<form action="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'registration'), $this);?>" method="post">
			<div class="r">
				<input type="text" name="email" class="big-input wp100 box-sizing" placeholder="<?php echo l('field_email', 'users', '', 'button', array()); ?>" <?php if ($this->_vars['DEMO_MODE']): ?>value="<?php echo $this->_vars['demo_user_type_login_settings']['login']; ?>
"<?php endif; ?>>
			</div>
			<div class="r">
				<input type="password" name="password" class="big-input wp100 box-sizing" placeholder="<?php echo l('field_password', 'users', '', 'button', array()); ?>" <?php if ($this->_vars['DEMO_MODE']): ?>value="<?php echo $this->_vars['demo_user_type_login_settings']['password']; ?>
"<?php endif; ?>>
			</div>
			<div class="r">
				<input type="submit" class="wp100 box-sizing" value="<?php echo l('btn_register', 'start', '', 'button', array()); ?>" name="logbtn">
			</div>
			<div class="centered">
				<span class="a" data-toggle><?php echo l('btn_login', 'start', '', 'text', array()); ?></span>
			</div>
		</form>
	</div>
	<div id="index_login_form_<?php echo $this->_vars['dynamic_block_registration_login_form_data']['rand']; ?>
"<?php if ($this->_vars['dynamic_block_registration_login_form_data']['view'] == 'registration_form'): ?> class="hide"<?php endif; ?>>
		<form action="<?php echo $this->_vars['site_url']; ?>
users/login" method="post">
			<div class="r">
				<input type="text" name="email" class="big-input wp100 box-sizing" placeholder="<?php echo l('field_email', 'users', '', 'button', array()); ?>" <?php if ($this->_vars['DEMO_MODE']): ?>value="<?php echo $this->_vars['demo_user_type_login_settings']['login']; ?>
"<?php endif; ?>>
			</div>
			<div class="r">
				<input type="password" name="password" class="big-input wp100 box-sizing" placeholder="<?php echo l('field_password', 'users', '', 'button', array()); ?>" <?php if ($this->_vars['DEMO_MODE']): ?>value="<?php echo $this->_vars['demo_user_type_login_settings']['password']; ?>
"<?php endif; ?>>
			</div>
			<div class="r">
				<input type="submit" class="wp100 box-sizing" value="<?php echo l('btn_login', 'start', '', 'button', array()); ?>" name="logbtn">
			</div>
			<div class="centered">
				<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'restore'), $this);?>"><?php echo l('link_restore', 'users', '', 'text', array()); ?></a><span class="a ml10" data-toggle><?php echo l('btn_register', 'start', '', 'text', array()); ?></span>
			</div>
		</form>
	</div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script><?php echo '
	$(\'#index_registration_login_forms_';  echo $this->_vars['dynamic_block_registration_login_form_data']['rand'];  echo '\').off(\'click\', \'[data-toggle]\').on(\'click\', \'[data-toggle]\', function(){
		var rand = \'';  echo $this->_vars['dynamic_block_registration_login_form_data']['rand'];  echo '\';
		if($(\'#index_registration_form_\'+rand).is(\':visible\')){
			if($(\'#index_login_form_\'+rand).find(\'input[name="email"]\').val() == \'\'){
				$(\'#index_login_form_\'+rand).find(\'input[name="email"]\').val($(\'#index_registration_form_\'+rand).find(\'input[name="email"]\').val());
			}
			if($(\'#index_login_form_\'+rand).find(\'input[name="password"]\').val() == \'\'){
				$(\'#index_login_form_\'+rand).find(\'input[name="password"]\').val($(\'#index_registration_form_\'+rand).find(\'input[name="password"]\').val());
			}
			$(\'#index_registration_form_\'+rand).stop(true).fadeOut(300, function(){$(\'#index_login_form_\'+rand).stop(true).fadeIn(300);});
		}else{
			if($(\'#index_registration_form_\'+rand).find(\'input[name="email"]\').val() == \'\'){
				$(\'#index_registration_form_\'+rand).find(\'input[name="email"]\').val($(\'#index_login_form_\'+rand).find(\'input[name="email"]\').val());
			}
			if($(\'#index_registration_form_\'+rand).find(\'input[name="password"]\').val() == \'\'){
				$(\'#index_registration_form_\'+rand).find(\'input[name="password"]\').val($(\'#index_login_form_\'+rand).find(\'input[name="password"]\').val());
			}
			$(\'#index_login_form_\'+rand).stop(true).fadeOut(300, function(){$(\'#index_registration_form_\'+rand).stop(true).fadeIn(300);});
		}
	});

	$(document).one(\'pjax:start\', function(){
		$(\'#index_registration_login_forms_';  echo $this->_vars['dynamic_block_registration_login_form_data']['rand'];  echo '\').off(\'click\', \'[data-toggle]\');
	});
</script>'; ?>

