<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-11 01:48:20 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>

	<div class="edit_block">
		<?php if ($this->_vars['user']['user_open_id'] && ! $this->_vars['user']['password']): ?>
			<p><?php echo l('text_open_id_use', 'users', '', 'text', array()); ?> <b><?php echo $this->_vars['user']['user_open_id']; ?>
</b></p>
			<p><?php echo l('text_open_id_create_password', 'users', '', 'text', array()); ?></p>
			<form action="" method="post" >
				<div class="r">
					<div class="f"><?php echo l('field_email', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="email" value="<?php echo $this->_vars['user']['email']; ?>
"></div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_password', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="password" name="password"></div>
				</div>
				<?php if (! empty ( $this->_vars['use_repassword'] )): ?>
					<div class="r">
						<div class="f"><?php echo l('field_repassword', 'users', '', 'text', array()); ?>: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				<?php endif; ?>
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_account_create"></div>
				</div>
			</form>
		<?php elseif ($this->_vars['user']['user_open_id'] && $this->_vars['user']['password']): ?>
			<p><?php echo l('text_open_id_use', 'users', '', 'text', array()); ?> <b><?php echo $this->_vars['user']['user_open_id']; ?>
</b></p>
			<p><?php echo l('text_open_id_and_password_used', 'users', '', 'text', array()); ?></p>
			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f"><?php echo l('field_email', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="email" value="<?php echo $this->_vars['user']['email']; ?>
"></div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_phone', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="phone" value="<?php echo $this->_vars['user']['phone']; ?>
"></div>
				</div>
				<div class="r hide">
					<div class="v"><input type="checkbox" name="show_adult" id="show_adult" value="1"<?php if ($this->_vars['user']['show_adult']): ?> checked<?php endif; ?> /> <label for="show_adult"><?php echo l('field_show_adult', 'users', '', 'text', array()); ?></label></div>
				</div>
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_contact_save"></div>
				</div>
			</form>

			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f"><?php echo l('field_password', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="password" name="password"></div>
				</div>
				<?php if (! empty ( $this->_vars['use_repassword'] )): ?>
					<div class="r">
						<div class="f"><?php echo l('field_repassword', 'users', '', 'text', array()); ?>: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				<?php endif; ?>
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_password_save"></div>
				</div>
			</form>
		<?php else: ?>
			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f"><?php echo l('field_email', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="email" value="<?php echo $this->_vars['user']['email']; ?>
"></div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_phone', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="phone" value="<?php echo $this->_vars['user']['phone']; ?>
"></div>
				</div>
				<div class="r hide">
					<div class="v"><input type="checkbox" name="show_adult" id="show_adult" value="1"<?php if ($this->_vars['user']['show_adult']): ?> checked<?php endif; ?> /> <label for="show_adult"><?php echo l('field_show_adult', 'users', '', 'text', array()); ?></label></div>
				</div>
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_contact_save"></div>
				</div>
			</form>

			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f"><?php echo l('field_password', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="password" name="password"></div>
				</div>
				<?php if (! empty ( $this->_vars['use_repassword'] )): ?>
					<div class="r">
						<div class="f"><?php echo l('field_repassword', 'users', '', 'text', array()); ?>: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				<?php endif; ?>
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_password_save"></div>
				</div>
			</form>
		<?php endif; ?>
		<?php echo tpl_function_helper(array('func_name' => 'show_social_networking_link','module' => 'users_connections'), $this);?>
	</div>
	<div class="ptb10 hide">
		<?php echo tpl_function_helper(array('func_name' => 'get_user_subscriptions_form','module' => 'subscriptions','func_param' => 'account'), $this);?>
	</div>
	<div class="ptb10 line top">
		<span class="a" onclick="ability_delete_available_view.check_available();"><?php echo l('delete_account', 'users', '', 'text', array()); ?></span>
	</div>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
			function(){
				ability_delete_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: \'users/ajax_available_ability_delete/\',
					buyAbilityAjaxUrl: \'users/ajax_activate_ability_delete/\',
					buyAbilityFormId: \'ability_form\',
					buyAbilitySubmitId: \'ability_form_submit\',
					formType: \'list\',
					alert_type: \'delete_profile\',
					alert_ok_button: "';  echo l('btn_confirm_delete_profile', 'users', '', 'text', array());  echo '",
					alert_cancel_button: "';  echo l('btn_not_confirm_delete_profile', 'users', '', 'text', array());  echo '",
					lang_delete_confirm: "';  echo l('text_delete_confirm', 'users', '', 'text', array());  echo '",
					success_request: function(message) {
						error_object.show_error_block(message, \'success\');
						locationHref(site_url);
					},
					fail_request: function(message) {error_object.show_error_block(message, \'error\');},
				});
			},
			[\'ability_delete_available_view\'],
			{async: false}
		);
	});
</script>'; ?>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>