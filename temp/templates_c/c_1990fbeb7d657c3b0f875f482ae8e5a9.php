<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.pagination.php');
$this->register_function("pagination", "tpl_function_pagination"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:45:59 Pacific Daylight Time */ ?>

<div class="content-block">
	<table class="list">
		<tr>
			<th class="w30">ID</th>
			<th class="w150"><?php echo l('field_date_add', 'payments', '', 'text', array()); ?></th>
			<th><?php echo l('field_amount', 'payments', '', 'text', array()); ?></th>
			<th><?php echo l('field_payment_type', 'payments', '', 'text', array()); ?></th>
			<th><?php echo l('field_billing_type', 'payments', '', 'text', array()); ?></th>
			<th><?php echo l('field_status', 'payments', '', 'text', array()); ?></th>
		</tr>
		<?php if (is_array($this->_vars['payments_helper_payments']) and count((array)$this->_vars['payments_helper_payments'])): foreach ((array)$this->_vars['payments_helper_payments'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<tr>
			<td><?php echo $this->_vars['item']['id']; ?>
</td>
			<td><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['payments_helper_page_data']['date_format']); ?>
</td>
			<td><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['amount']), $this);?></td>
			<td><?php echo $this->_vars['item']['payment_type_gid']; ?>
 (<?php echo $this->_vars['item']['payment_data']['name']; ?>
)</td>
			<td><?php echo $this->_vars['item']['system_gid']; ?>
</td>
			<td>
				<?php if ($this->_vars['item']['status'] == '1'): ?>
					<font class="success"><?php echo l('payment_status_approved', 'payments', '', 'text', array()); ?></font>
				<?php elseif ($this->_vars['item']['status'] == '-1'): ?>
					<font class="error"><?php echo l('payment_status_declined', 'payments', '', 'text', array()); ?></font>
				<?php else: ?>
					<?php echo l('payment_status_wait', 'payments', '', 'text', array()); ?>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; else: ?>
		<tr>
			<td class="center" colspan="6"><?php echo l('payment_history_empty_results', 'payments', '', 'text', array()); ?></td>
		</tr>
		<?php endif; ?>
	</table>
	<div><?php echo tpl_function_pagination(array('data' => $this->_vars['payments_helper_page_data'],'type' => 'full'), $this);?></div>
</div>
