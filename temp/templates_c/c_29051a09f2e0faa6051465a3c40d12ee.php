<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:40 Pacific Daylight Time */ ?>

	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2><?php echo l('stat_header_payments', 'payments', '', 'text', array()); ?></th>
	</tr>
	<tr>
		<td class="first"><a id="payments_link_all" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/"><?php echo l('stat_header_all', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="payments_link_all_num" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/"><?php echo $this->_vars['stat_payments']['all']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a id="payments_link_approve" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/approve"><?php echo l('stat_header_approved', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="payments_link_approve_num" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/approve"><?php echo $this->_vars['stat_payments']['approve']; ?>
</a></td>
	</tr>
	<tr>
		<td class="first"><a id="payments_link_decline" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/decline"><?php echo l('stat_header_declined', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="payments_link_decline_num" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/decline"><?php echo $this->_vars['stat_payments']['decline']; ?>
</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a id="payments_link_wait" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/wait"><?php echo l('stat_header_awaiting', 'payments', '', 'text', array()); ?></a></td>
		<td class="w30"><a id="payments_link_wait_num" href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/wait"><?php echo $this->_vars['stat_payments']['wait']; ?>
</a></td>
	</tr>
	</table>

