<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:08 Pacific Daylight Time */ ?>

<li<?php if (! $this->_vars['messages_count']): ?> class="hide-always"<?php endif; ?>>
	<a href="<?php echo tpl_function_seolink(array('module' => 'mailbox','method' => 'inbox'), $this);?>">
		<?php echo l('header_new_messages', 'mailbox', '', 'text', array()); ?>: <b class="summand fright inbox_new_message"><?php echo $this->_vars['messages_count']; ?>
</b>
	</a>
</li>
