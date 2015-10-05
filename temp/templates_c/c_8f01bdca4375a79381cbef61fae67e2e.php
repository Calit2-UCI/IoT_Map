<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 03:55:48 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_notifications_menu'), $this);?>
<div class="actions">&nbsp;</div>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_settings_editing', 'notifications', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_mail_charset', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_charset']; ?>
" name="mail_charset"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_mail_protocol', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="mail_protocol"><?php if (is_array($this->_vars['protocol_lang']['option']) and count((array)$this->_vars['protocol_lang']['option'])): foreach ((array)$this->_vars['protocol_lang']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['key'] == $this->_vars['settings_data']['mail_protocol']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_mail_mailpath', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_mailpath']; ?>
" name="mail_mailpath"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_mail_smtp_host', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_host']; ?>
" name="mail_smtp_host"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_mail_smtp_user', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_user']; ?>
" name="mail_smtp_user"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_mail_smtp_pass', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_pass']; ?>
" name="mail_smtp_pass"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_mail_smtp_port', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_port']; ?>
" name="mail_smtp_port"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_mail_useragent', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_useragent']; ?>
" name="mail_useragent"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_mail_from_email', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_from_email']; ?>
" name="mail_from_email"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_mail_from_name', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_from_name']; ?>
" name="mail_from_name"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<div class="clr"></div>
</form>
<div class="clr"></div>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="dkim_form">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_settings_dkim', 'notifications', '', 'text', array()); ?></div>
		<?php if ($this->_vars['openssl_loaded']): ?>
		<div class="row">
			<div class="h"><?php echo l('field_dkim_domain_selector', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['dkim_domain_selector']; ?>
" name="dkim_domain_selector"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_dkim_secret_key', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><textarea name="dkim_private_key"><?php echo $this->_vars['settings_data']['dkim_private_key']; ?>
</textarea></div>
		</div>
		<?php else: ?>
		<div class="h150">
			<?php echo l('dkim_no_openssl', 'notifications', '', 'text', array()); ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_dkim" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<div class="clr"></div>
</form>
<div class="clr"></div>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="test_form">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_settings_testing', 'notifications', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_mail_to_email', 'notifications', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_to_email']; ?>
" name="mail_to_email"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_test" value="<?php echo l('btn_send', 'start', '', 'button', array()); ?>"></div></div>
	<div class="clr"></div>
</form>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
