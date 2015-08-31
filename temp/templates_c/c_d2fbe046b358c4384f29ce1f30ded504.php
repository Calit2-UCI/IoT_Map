<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:15:45 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_payments_menu'), $this);?>
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['filter'] == 'all'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/all/<?php echo $this->_vars['payment_type_gid']; ?>
/<?php echo $this->_vars['system_gid']; ?>
"><?php echo l('filter_payments_all', 'payments', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['all']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'wait'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/wait/<?php echo $this->_vars['payment_type_gid']; ?>
/<?php echo $this->_vars['system_gid']; ?>
"><?php echo l('filter_payments_wait', 'payments', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['wait']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'approve'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/approve/<?php echo $this->_vars['payment_type_gid']; ?>
/<?php echo $this->_vars['system_gid']; ?>
"><?php echo l('filter_payments_approve', 'payments', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['approve']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'decline'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/index/decline/<?php echo $this->_vars['payment_type_gid']; ?>
/<?php echo $this->_vars['system_gid']; ?>
"><?php echo l('filter_payments_decline', 'payments', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['decline']; ?>
)</a></li>
	</ul>
	&nbsp;
</div>

<div class="filter-form">
	<div class="row">
		<div class="h"><?php echo l('filter_payment_type', 'payments', '', 'text', array()); ?>:</div>
		<div class="v">
			<select name="payment_type_gid" class="middle_short" onchange="javascript: reload_this_page(this.value, system_gid);">
			<option value="all">...</option><?php if (is_array($this->_vars['payment_types']) and count((array)$this->_vars['payment_types'])): foreach ((array)$this->_vars['payment_types'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['gid']; ?>
" <?php if ($this->_vars['payment_type_gid'] == $this->_vars['item']['gid']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['gid']; ?>
</option><?php endforeach; endif; ?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="h"><?php echo l('filter_billing_type', 'payments', '', 'text', array()); ?>:</div>
		<div class="v">
			<select name="system_gid" class="middle_short" onchange="javascript: reload_this_page(payment_type_gid, this.value);">
			<option value="all">...</option><?php if (is_array($this->_vars['systems']) and count((array)$this->_vars['systems'])): foreach ((array)$this->_vars['systems'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['gid']; ?>
" <?php if ($this->_vars['system_gid'] == $this->_vars['item']['gid']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
</option><?php endforeach; endif; ?>
			</select>
		</div>
	</div>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_payment_user', 'payments', '', 'text', array()); ?></th>
	<th><a href="<?php echo $this->_vars['sort_links']['amount']; ?>
"<?php if ($this->_vars['order'] == 'amount'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_payment_amount', 'payments', '', 'text', array()); ?></a></th>
	<th><?php echo l('field_payment_type', 'payments', '', 'text', array()); ?></th>
	<th><?php echo l('field_payment_billing_system', 'payments', '', 'text', array()); ?></th>
	<th><a href="<?php echo $this->_vars['sort_links']['date_add']; ?>
"<?php if ($this->_vars['order'] == 'date_add'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_payment_date', 'payments', '', 'text', array()); ?></a></th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['payments']) and count((array)$this->_vars['payments'])): foreach ((array)$this->_vars['payments'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center tooltip" id="hide_<?php echo $this->_vars['item']['id']; ?>
">
		<?php echo $this->_vars['item']['user']['output_name']; ?>

		<span id="span_hide_<?php echo $this->_vars['item']['id']; ?>
" class="hide"><div class="tooltip-info">
			<?php if (is_array($this->_vars['item']['payment_data_formatted']) and count((array)$this->_vars['item']['payment_data_formatted'])): foreach ((array)$this->_vars['item']['payment_data_formatted'] as $this->_vars['param_id'] => $this->_vars['param']): ?>
			<b><?php echo $this->_vars['param']['name']; ?>
:</b> <?php echo $this->_vars['param']['value']; ?>
<br>
			<?php endforeach; endif; ?>
		</div></span>
	</td>
	<td class="center"><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['amount']), $this);?></td>
	<td class="center"><?php echo $this->_vars['item']['payment_type_gid']; ?>
</td>
	<td class="center"><?php echo $this->_vars['item']['system_gid']; ?>
</td>
	<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td class="icons">
	<?php if ($this->_vars['item']['status'] == '1'): ?>
		<font class="success"><?php echo l('payment_status_approved', 'payments', '', 'text', array()); ?></font>
	<?php elseif ($this->_vars['item']['status'] == '-1'): ?>
		<font class="error"><?php echo l('payment_status_declined', 'payments', '', 'text', array()); ?></font>
	<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/payment_status/approve/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-approve.png" width="16" height="16" border="0" alt="<?php echo l('link_payment_approve', 'payments', '', 'text', array()); ?>" title="<?php echo l('link_payment_approve', 'payments', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/payment_status/decline/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-decline.png" width="16" height="16" border="0" alt="<?php echo l('link_payment_decline', 'payments', '', 'text', array()); ?>" title="<?php echo l('link_payment_decline', 'payments', '', 'text', array()); ?>"></a>
	</td>
	<?php endif; ?>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="6" class="center"><?php echo l('no_payments', 'payments', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'easyTooltip.min.js'), $this);?>
<script type="text/javascript">
var filter = '<?php echo $this->_vars['filter']; ?>
';
var payment_type_gid = '<?php if ($this->_vars['payment_type_gid']):  echo $this->_vars['payment_type_gid'];  else: ?>all<?php endif; ?>';
var system_gid = '<?php if ($this->_vars['system_gid']):  echo $this->_vars['system_gid'];  else: ?>all<?php endif; ?>';
var order = '<?php echo $this->_vars['order']; ?>
';
var order_direction = '<?php echo $this->_vars['order_direction']; ?>
';
var reload_link = "<?php echo $this->_vars['site_url']; ?>
admin/payments/index/";
<?php echo '
function reload_this_page(payment_type_gid, system_gid){
	var link = reload_link + filter + \'/\' + payment_type_gid + \'/\' + system_gid + \'/\' + order + \'/\' + order_direction;
	location.href=link;
}

$(function(){
	$(".tooltip").each(function(){
		$(this).easyTooltip({
			useElement: \'span_\'+$(this).attr(\'id\')
		});
	});
});
'; ?>

</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
