<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:08 Pacific Daylight Time */ ?>

<li<?php if (! $this->_vars['friend_requests_count']): ?> class="hide-always"<?php endif; ?>>
	<a id="friendlist_link_friends_requests" href="<?php echo $this->_vars['site_url']; ?>
friendlist/friends_requests">
		<?php echo l('friends_requests', 'friendlist', '', 'text', array()); ?>: <b class="summand friend_requests_count fright"><?php echo $this->_vars['friend_requests_count']; ?>
</b>
	</a>
</li>