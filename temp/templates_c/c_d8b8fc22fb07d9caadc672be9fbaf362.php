<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:01:51 Pacific Daylight Time */ ?>

<?php if (count ( $this->_vars['services'] ) > 0): ?>
	<div class="oauth-links">
		<span class="mr5"><?php echo l('sign_in_with', 'users_connections', '', 'text', array()); ?></span>
		<ins>
			<?php if (is_array($this->_vars['services']) and count((array)$this->_vars['services'])): foreach ((array)$this->_vars['services'] as $this->_vars['item']): ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
users_connections/oauth_login/<?php echo $this->_vars['item']['id']; ?>
" class="icon-<?php echo $this->_vars['item']['gid']; ?>
 icon-big edge square hover" data-pjax="0" title="<?php echo $this->_vars['item']['name']; ?>
"></a>
			<?php endforeach; endif; ?>
		</ins>
	</div>
<?php endif; ?>