<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:41:05 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<h1><?php echo l('header_service_settings', 'services', '', 'text', array()); ?>: <?php echo l($this->_vars['data']['name_lang_gid'], 'services', '', 'text', array()); ?></h1>

	<div class="content-value">
		<form method="post" action="<?php echo $this->_vars['site_url']; ?>
services/form/<?php echo $this->_vars['data']['gid']; ?>
">
			<div class="edit_block">
				<div class="payment_table">
					<h3><?php echo l($this->_vars['data']['description_lang_gid'], 'services', '', 'text', array()); ?></h3>
					<table>
						<?php if (is_array($this->_vars['data']['template']['data_admin_array']) and count((array)$this->_vars['data']['template']['data_admin_array'])): foreach ((array)$this->_vars['data']['template']['data_admin_array'] as $this->_vars['item']): ?>
							<tr>
								<td><?php echo $this->_vars['item']['name']; ?>
:</td>
								<td class="value">
								<?php if ($this->_vars['item']['type'] == 'string' || $this->_vars['item']['type'] == 'int' || $this->_vars['item']['type'] == 'text'): ?>
									<?php echo $this->_vars['item']['value']; ?>

								<?php elseif ($this->_vars['item']['type'] == 'price'): ?>
									<?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['value']), $this);?>
								<?php elseif ($this->_vars['item']['type'] == 'checkbox'): ?>
									<?php if ($this->_vars['item']['value'] == '1'):  echo l('yes_checkbox_value', 'services', '', 'text', array());  else:  echo l('no_checkbox_value', 'services', '', 'text', array());  endif; ?>
								<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; endif; ?>

						<?php if (is_array($this->_vars['data']['template']['data_user_array']) and count((array)$this->_vars['data']['template']['data_user_array'])): foreach ((array)$this->_vars['data']['template']['data_user_array'] as $this->_vars['item']): ?>
							<?php if ($this->_vars['item']['type'] == 'hidden'): ?>
								<input type="hidden" value="<?php echo $this->_vars['item']['value']; ?>
" name="data_user[<?php echo $this->_vars['item']['gid']; ?>
]">
							<?php else: ?>
								<tr>
									<td><?php echo $this->_vars['item']['name']; ?>
:</td>
									<?php if ($this->_vars['item']['type'] == 'string'): ?>
										<td class="value"><input type="text" value="<?php echo $this->_vars['item']['value']; ?>
" name="data_user[<?php echo $this->_vars['item']['gid']; ?>
]"></td>
									<?php elseif ($this->_vars['item']['type'] == 'int'): ?>
										<td class="value"><input type="text" value="<?php echo $this->_vars['item']['value']; ?>
" name="data_user[<?php echo $this->_vars['item']['gid']; ?>
]" class="short"></td>
									<?php elseif ($this->_vars['item']['type'] == 'price'): ?>
										<td class="value"><input type="text" value="<?php echo $this->_vars['item']['value']; ?>
" name="data_user[<?php echo $this->_vars['item']['gid']; ?>
]" class="short"> <?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start'), $this);?></td>
									<?php elseif ($this->_vars['item']['type'] == 'text'): ?>
										<td class="value"class="value"><textarea name="data_user[<?php echo $this->_vars['item']['gid']; ?>
]"><?php echo $this->_vars['item']['value']; ?>
</textarea></td>
									<?php elseif ($this->_vars['item']['type'] == 'checkbox'): ?>
										<td class="value"><input type="checkbox" value="1" name="data_user[<?php echo $this->_vars['item']['gid']; ?>
]" <?php if ($this->_vars['item']['value'] == '1'): ?>checked<?php endif; ?>></td>
									<?php endif; ?>
								</tr>
							<?php endif; ?>
						<?php endforeach; endif; ?>
						<?php if ($this->_vars['data']['template']['price_type'] == '2'): ?>
							<tr>
								<td><?php echo l('field_your_price', 'services', '', 'text', array()); ?>:</td>
								<td class="value"><input type="text" value="<?php echo $this->_vars['data']['price']; ?>
" name="price" class="short"> <b><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start'), $this);?></b></td>
							</tr>
						<?php elseif ($this->_vars['data']['template']['price_type'] == '3'): ?>
							<tr>
								<td><?php echo l('field_price', 'services', '', 'text', array()); ?>:</td>
								<td class="value"><input type="hidden" value="<?php echo $this->_vars['data']['price']; ?>
" name="price" class="short"><b><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['data']['price']), $this);?></b></td>
							</tr>
						<?php else: ?>
							<tr>
								<td><?php echo l('field_price', 'services', '', 'text', array()); ?>:</td>
								<td class="value"><b><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['data']['price']), $this);?></b></td>
							</tr>
						<?php endif; ?>
					</table>
				</div>
			</div>
			<?php if ($this->_vars['data']['free_activate']): ?>
				<input type="submit" class="btn mtb10" value="<?php echo l('btn_activate_free', 'services', '', 'button', array()); ?>" name="btn_account">
			<?php else: ?>
				<div class="pt10">
					<label class="labeled"><?php echo l('field_activate_immediately', 'services', '', 'text', array()); ?> <input type="checkbox" value="1" name="activate_immediately" checked /></label>
				</div>
				<?php if (( $this->_vars['data']['pay_type'] == 1 || $this->_vars['data']['pay_type'] == 2 ) && $this->_vars['is_module_installed']): ?>
					<h2 class="line top bottom"><?php echo l('account_payment', 'services', '', 'text', array()); ?></h2>
					<?php if ($this->_vars['data']['disable_account_pay']):  echo l('error_account_less_then_service_price', 'services', '', 'text', array()); ?> <a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'update'), $this);?>"><?php echo l("link_add_founds", 'services', '', 'text', array()); ?></a>
					<?php else: ?>
						<?php echo l('on_your_account_now', 'services', '', 'text', array()); ?>: <b><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['data']['user_account']), $this);?></b>
						<div class="b outside">
							<input type="submit" value="<?php echo l('btn_pay_account', 'services', '', 'button', array()); ?>" name="btn_account">
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($this->_vars['data']['pay_type'] == 2 || $this->_vars['data']['pay_type'] == 3): ?>
					<h2 class="line top bottom"><?php echo l('payment_systems', 'services', '', 'text', array()); ?></h2>
					<?php if ($this->_vars['billing_systems']): ?>
						<input type="hidden" id="system_gid" name="system_gid" value="">
						<?php if (is_array($this->_vars['billing_systems']) and count((array)$this->_vars['billing_systems'])): foreach ((array)$this->_vars['billing_systems'] as $this->_vars['item']): ?>
							<button type="submit" name="btn_system" value="1" class="mrb20" onclick="$('#system_gid').val('<?php echo $this->_vars['item']['gid']; ?>
');"><?php echo $this->_vars['item']['name']; ?>
</button>
						<?php endforeach; endif; ?>
					<?php elseif ($this->_vars['data']['pay_type'] == 3): ?>
						<?php echo l('error_empty_billing_system_list', 'service', '', 'text', array()); ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</form>
		<div class="pt10">
			<a class="btn-link" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => account,'action' => 'services'), $this);?>">
				<i class="icon-arrow-left icon-big edge hover"></i><i><?php echo l('back_to_payment_services', 'services', '', 'text', array()); ?></i>
			</a>
		</div>
	</div>
</div>
<div class="clr"></div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
