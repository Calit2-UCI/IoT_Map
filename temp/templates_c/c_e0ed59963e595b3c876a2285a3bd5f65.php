<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:51:15 Pacific Daylight Time */ ?>

<div>
	<?php if (is_array($this->_vars['settings_errors']) and count((array)$this->_vars['settings_errors'])): foreach ((array)$this->_vars['settings_errors'] as $this->_vars['item']): ?>
		<font class="req"><?php echo $this->_vars['item']; ?>
</font><br>
	<?php endforeach; endif; ?>
</div>
<div class="form">
	<div class="row">
		<div class="h"><?php echo l('field_mail_charset', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_charset']; ?>
" name="mail_charset"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_protocol', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v">
			<select name="mail_protocol"><?php if (is_array($this->_vars['protocol_lang']['option']) and count((array)$this->_vars['protocol_lang']['option'])): foreach ((array)$this->_vars['protocol_lang']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select>
		</div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_mailpath', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_mailpath']; ?>
" name="mail_mailpath"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_smtp_host', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_host']; ?>
" name="mail_smtp_host"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_smtp_user', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_user']; ?>
" name="mail_smtp_user"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_smtp_pass', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_pass']; ?>
" name="mail_smtp_pass"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_smtp_port', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_smtp_port']; ?>
" name="mail_smtp_port"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_useragent', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_useragent']; ?>
" name="mail_useragent"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_from_email', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_from_email']; ?>
" name="mail_from_email"></div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('field_mail_from_name', 'notifications', '', 'text', array()); ?>: </div>
		<div class="v"><input type="text" value="<?php echo $this->_vars['settings_data']['mail_from_name']; ?>
" name="mail_from_name"></div>
	</div>
	<?php if ($this->_vars['openssl_loaded']): ?>
	<br>
	<div class="row header"><b><?php echo l('admin_header_settings_dkim', 'notifications', '', 'text', array()); ?></b></div>
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
	<?php endif; ?>
</div>
