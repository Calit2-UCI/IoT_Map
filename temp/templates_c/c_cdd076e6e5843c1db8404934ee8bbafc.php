<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:09:08 Pacific Daylight Time */ ?>

<menu class="header-item" label="<?php echo l('on_account_header', 'users_payments', '', 'text', array()); ?>">
	<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'update'), $this);?>">
		<?php if ($this->_vars['user_account']): ?>
			<?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['user_account'],'cur_gid' => $this->_vars['base_currency']['gid']), $this);?>&nbsp;
		<?php endif; ?>
		<i class="fa-credit-card"></i>&nbsp;
		<i class="fa-caret-down"></i>
	</a>
	<div class="drop w300">
					<span><?php echo l('services', 'users_payments', '', 'text', array()); ?></span> 
			(<a class="extra" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account'), $this);?>"><?php echo l('find_out_more', 'users_payments', '', 'text', array()); ?></a>)
			<menu>
				<?php if ($this->_vars['user_services']): ?>
					<?php if (is_array($this->_vars['user_services']) and count((array)$this->_vars['user_services'])): foreach ((array)$this->_vars['user_services'] as $this->_vars['user_service']): ?>
						<?php if (! $this->_vars['user_service']['is_expired']): ?>
							<li>
								<a id="users_payments_link_service_<?php echo $this->_vars['user_service']['gid']; ?>
" href="<?php echo $this->_vars['site_url']; ?>
services/form/<?php echo $this->_vars['user_service']['gid']; ?>
"><?php echo $this->_vars['user_service']['name']; ?>

								<?php if ($this->_vars['user_service']['days_left']): ?>
									: <span class="fright"><?php echo $this->_vars['user_service']['days_left']; ?>
 <?php echo l('days_left', 'users_payments', '', 'text', array()); ?></span>
								<?php endif; ?>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; endif; ?>
				<?php endif; ?>
			</menu>
							<span><?php echo l('memberships', 'memberships', '', 'text', array()); ?></span> 
			(<a class="extra" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'memberships'), $this);?>"><?php echo l('find_out_more', 'users_payments', '', 'text', array()); ?></a>)
			<menu>
				<?php if ($this->_vars['user_memberships']): ?>
					<?php if (is_array($this->_vars['user_memberships']) and count((array)$this->_vars['user_memberships'])): foreach ((array)$this->_vars['user_memberships'] as $this->_vars['user_membership']): ?>
						<li>
							<?php echo $this->_vars['user_membership']['membership_info']['name']; ?>

							<span class="fright"><?php echo $this->_vars['user_membership']['left_str']; ?>
</span>
						</li>
					<?php endforeach; endif; ?>
				<?php endif; ?>
			</menu>
				<menu>
			<li><a id="users_payments_link_update_account" class="extra" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'update'), $this);?>"><?php echo l('add_funds', 'users_payments', '', 'text', array()); ?></a></li>
		</menu>
	</div>
</menu>
