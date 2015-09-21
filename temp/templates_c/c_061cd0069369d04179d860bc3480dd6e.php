<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 02:00:16 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<div class="content-block">
		<h1><?php echo l('header_membership_settings', 'memberships', '', 'text', array()); ?>: <?php echo $this->_vars['membership']['name']; ?>
</h1>
		<?php if ($this->_vars['show_ms_change_warning']): ?>
			<p>
				<?php echo l('membership_change_warning', 'memberships', '', 'text', array()); ?>
			</p>
		<?php endif; ?>
		<p>
			<?php echo $this->_vars['membership']['description']; ?>

		</p>
		<div class="content-value mt20">
			<?php echo tpl_function_block(array('name' => 'memberships_list','module' => 'memberships','memberships' => $this->_vars['membership'],'hide_buy_btn' => true), $this);?>
			<form method="post" action="">
				<?php if ($this->_vars['membership']['free_activate']): ?>
					<input type="submit" class='btn' value="<?php echo l('btn_activate_free', 'memberships', '', 'button', array()); ?>" name="btn_account">
				<?php else: ?>
					<?php if (( $this->_vars['membership']['pay_type'] == 'account' || $this->_vars['membership']['pay_type'] == 'account_and_direct' )): ?>
						<h2 class="line top bottom"><?php echo l('account_payment', 'services', '', 'text', array()); ?></h2>
						<?php if ($this->_vars['membership']['disable_account_pay']): ?>
							<?php echo l('error_account_less_then_service_price', 'services', '', 'text', array()); ?> <a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'update'), $this);?>"><?php echo l("link_add_founds", 'services', '', 'text', array()); ?></a>
						<?php else: ?>
							<?php echo l('on_your_account_now', 'services', '', 'text', array()); ?>: <b><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['membership']['user_account']), $this);?></b>
							<div class="b outside">
								<input type="submit" data-pjax-submit="0" value="<?php echo l('btn_pay_account', 'services', '', 'button', array()); ?>" name="btn_account">
							</div>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ($this->_vars['membership']['pay_type'] == 'account_and_direct' || $this->_vars['membership']['pay_type'] == 'direct'): ?>
						<h2 class="line top bottom"><?php echo l('payment_systems', 'services', '', 'text', array()); ?></h2>
						<?php if ($this->_vars['billing_systems']): ?>
							<?php echo l('error_select_payment_system', 'services', '', 'text', array()); ?><br>
							<select name="system_gid"><option value="">...</option><?php if (is_array($this->_vars['billing_systems']) and count((array)$this->_vars['billing_systems'])): foreach ((array)$this->_vars['billing_systems'] as $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['gid']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</option><?php endforeach; endif; ?></select>
							<div class="b outside">
								<input type="submit" data-pjax-submit="0" value="<?php echo l('btn_pay_systems', 'services', '', 'button', array()); ?>" name="btn_system" class="btn">
							</div>
						<?php elseif ($this->_vars['membership']['pay_type'] == 'direct'): ?>
							<?php echo l('error_empty_billing_system_list', 'service', '', 'text', array()); ?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
			</form>
		</div>
		<div class="mt20">
			<a class="btn-link" href="<?php echo $this->_vars['site_url']; ?>
memberships/index"><i class="icon-arrow-left icon-big edge hover"></i><i><?php echo l('back_to_memberships', 'memberships', '', 'text', array()); ?></i></a>
		</div>
	</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
