<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:15:49 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('module' => 'payments','file' => 'admin-payments-settings.js'), $this); echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_payments_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/settings_edit"><?php echo l('link_add_currency', 'payments', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<form method="post" action="<?php echo $this->_vars['site_url']; ?>
admin/payments/update_currency_rates" name="save_form" enctype="multipart/form-data">
<div class="filter-form-new">
	<div class="form">
		<b><?php echo l('header_currency_rates_update_auto', 'payments', '', 'text', array()); ?></b>		
		<p class="rates_update">
			<input type="hidden" name="use_rates_update" value="0" />
			<input type="checkbox" name="use_rates_update" value="1" id="use_rates_update" <?php if ($this->_vars['use_rates_update']): ?>checked<?php endif; ?>> 
			<label><?php echo l('text_currency_rates_update', 'payments', '', 'text', array()); ?></label>
		</p>
		<select name="rates_driver" id="driver_select">
			<?php if (is_array($this->_vars['updaters']) and count((array)$this->_vars['updaters'])): foreach ((array)$this->_vars['updaters'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
" <?php if ($this->_vars['item'] == $this->_vars['rates_update_driver']): ?>selected<?php endif; ?>><?php echo l('currency_updater_'.$this->_vars['item'], 'payments', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
		</select>		
		<input type="image" name="bt_auto" src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-approve.png" alt="<?php echo l('link_currency_rates_update_auto', 'payments', '', 'button', array()); ?>" title="<?php echo l('link_currency_rates_update_auto', 'payments', '', 'button', array()); ?>" id="rates_update_driver"><br><br>
		<b><?php echo l('header_currency_rates_update_manual', 'payments', '', 'text', array()); ?></b><br><br>
		<select name="rates_driver" id="manual_select">
			<?php if (is_array($this->_vars['updaters']) and count((array)$this->_vars['updaters'])): foreach ((array)$this->_vars['updaters'] as $this->_vars['item']): ?>
			<option value="<?php echo $this->_vars['item']; ?>
"><?php echo l('currency_updater_'.$this->_vars['item'], 'payments', '', 'text', array()); ?></option>
			<?php endforeach; endif; ?>
		</select>
		<input type="image" name="bt_manual" src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icn_update.png" alt="<?php echo l('link_currency_rates_update_manual', 'payments', '', 'button', array()); ?>" title="<?php echo l('link_currency_rates_update_manual', 'payments', '', 'button', array()); ?>" id="rates_update_manual"><br><br>
	</div>
</div>
</form>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_currency_gid', 'payments', '', 'text', array()); ?></th>
	<th class=""><?php echo l('field_currency_name', 'payments', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_currency_default', 'payments', '', 'text', array()); ?></th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['currency']) and count((array)$this->_vars['currency'])): foreach ((array)$this->_vars['currency'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><?php echo $this->_vars['item']['gid']; ?>
</td>
	<td class="center"><?php echo $this->_vars['item']['name']; ?>
 (<?php echo $this->_vars['item']['abbr']; ?>
)</td>
	<td class="center">
		<?php if ($this->_vars['item']['is_default']): ?>
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" >
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/settings_use/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_default_currency', 'payments', '', 'text', array()); ?>" title="<?php echo l('link_default_currency', 'payments', '', 'text', array()); ?>"></a>
		<?php endif; ?>
	</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/settings_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_currency', 'payments', '', 'text', array()); ?>" title="<?php echo l('link_edit_currency', 'payments', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/payments/settings_delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_currency', 'payments', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_currency', 'payments', '', 'text', array()); ?>" title="<?php echo l('link_delete_currency', 'payments', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_payment_currencies', 'payments', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script><?php echo '
	$(function(){
		new AdminPaymentsSettings({
			siteUrl: \'';  echo $this->_vars['site_url'];  echo '\', 
		});	
	});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
