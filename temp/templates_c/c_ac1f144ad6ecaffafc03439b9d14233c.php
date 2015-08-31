<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:08 Pacific Daylight Time */ ?>

<li<?php if (! $this->_vars['visitors_count']): ?> class="hide-always"<?php endif; ?>>
	<a id="users_link_my_guests" href="<?php echo $this->_vars['site_url']; ?>
users/my_guests">
		<?php echo l('header_my_guests', 'users', '', 'text', array()); ?>: <b class="summand visitors_count fright"><?php echo $this->_vars['visitors_count']; ?>
</b>
	</a>
</li>