<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:40 Pacific Daylight Time */ ?>

	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2><?php echo l('stat_header_banners', 'banners', '', 'text', array()); ?></th>
	</tr>
	<tr>
		<td class="first"><a id="banners_link_user" href="<?php echo $this->_vars['site_url']; ?>
admin/banners/index/user"><?php echo l('stat_header_moderation_banners', 'banners', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="banners_link_user_num" href="<?php echo $this->_vars['site_url']; ?>
admin/banners/index/user"><?php echo $this->_vars['stat_banners']['users']; ?>
</a></td>
	</tr>
	</table>

