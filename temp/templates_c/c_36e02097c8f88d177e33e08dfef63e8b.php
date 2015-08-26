<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:40 Pacific Daylight Time */ ?>

	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2><?php echo l('stat_header_users', 'users', '', 'text', array()); ?></th>
	</tr>
	<?php if ($this->_vars['stat_users']['index_method']): ?>
	<tr>
		<td class="first"><a id="users_link_all" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/"><?php echo l('stat_header_all', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="users_link_all_num" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/"><?php echo $this->_vars['stat_users']['all']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a id="users_link_active" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/active"><?php echo l('stat_header_active', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="users_link_active_num" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/active"><?php echo $this->_vars['stat_users']['active']; ?>
</a></td>
	</tr>
	<tr>
		<td class="first"><a id="users_link_not_active" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_active"><?php echo l('stat_header_blocked', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="users_link_not_active_num" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_active"><?php echo $this->_vars['stat_users']['blocked']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a id="users_link_not_confirm" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_confirm"><?php echo l('stat_header_unconfirmed', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="users_link_not_confirm_num" href="<?php echo $this->_vars['site_url']; ?>
admin/users/index/not_confirm"><?php echo $this->_vars['stat_users']['unconfirm']; ?>
</a></td>
	</tr>
	<?php endif; ?>
	<?php if ($this->_vars['stat_users']['moderation_method']): ?>
	<tr>
		<td class="first"><a id="users_link_user_logo" href="<?php echo $this->_vars['site_url']; ?>
admin/moderation/index/user_logo"><?php echo l('stat_header_moderation_icons', 'users', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="users_link_user_logo_num" href="<?php echo $this->_vars['site_url']; ?>
admin/moderation/index/user_logo"><?php echo $this->_vars['stat_users']['icons']; ?>
</a></td>
	</tr>
	<?php endif; ?>
	</table>
