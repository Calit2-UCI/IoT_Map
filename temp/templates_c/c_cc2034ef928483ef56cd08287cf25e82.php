<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 23:20:43 Pacific Daylight Time */ ?>

<?php if (count ( $this->_vars['applications'] ) > 0): ?>
	<h3><?php echo l('you_can_link', 'users_connections', '', 'text', array()); ?></h3>
	<?php if (is_array($this->_vars['applications']) and count((array)$this->_vars['applications'])): foreach ((array)$this->_vars['applications'] as $this->_vars['item']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
users_connections/oauth_link/<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a>
	<?php endforeach; endif; ?>
<?php endif; ?>
<?php if (count ( $this->_vars['un_applications'] ) > 0): ?>
	<h3><?php echo l('you_can_unlink', 'users_connections', '', 'text', array()); ?></h3>
	<?php if (is_array($this->_vars['un_applications']) and count((array)$this->_vars['un_applications'])): foreach ((array)$this->_vars['un_applications'] as $this->_vars['item']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
users_connections/oauth_unlink/<?php echo $this->_vars['item']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a>
	<?php endforeach; endif; ?>
<?php endif; ?>
