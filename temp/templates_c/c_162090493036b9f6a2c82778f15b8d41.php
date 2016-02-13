<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 02:50:00 Pacific Standard Time */ ?>

<div class="fright">
	<ul>
		<?php if ($this->_vars['action'] == 'banners'): ?>
				<li>
			<s id="add_banner" class="a btn-link">
				<a href="<?php echo $this->_vars['site_url']; ?>
banners/edit">
					<i class="icon-file-text-alt icon-big edge hover">
						<i class="icon-mini-stack icon-plus bottomright"></i>
					</i>
				</a>
				<i><a href="<?php echo $this->_vars['site_url']; ?>
banners/edit"><?php echo l('link_add_banner', 'banners', '', 'text', array()); ?></a></i>
			</s>
		</li>
				<?php endif; ?>
	</ul>
</div>

<div class="tabs tab-size-15 noPrint">
	<ul>
					<li<?php if ($this->_vars['action'] == 'memberships'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'memberships'), $this);?>"><?php echo l('header_memberships', 'users', '', 'text', array()); ?></a></li>
				
			<li<?php if ($this->_vars['action'] == 'services'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'services'), $this);?>"><?php echo l('header_services', 'users', '', 'text', array()); ?></a></li>
			
		
					<li<?php if ($this->_vars['action'] == 'update'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'update'), $this);?>"><?php echo l('header_account_update', 'users', '', 'text', array()); ?></a></li>
							<li<?php if ($this->_vars['action'] == 'payments_history'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'payments_history'), $this);?>">History</a></li>
							<li<?php if ($this->_vars['action'] == 'banners'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'banners'), $this);?>"><?php echo l('header_my_banners', 'banners', '', 'text', array()); ?></a></li>
				<?php /*
			<li<?php if ($this->_vars['action'] == 'send_money'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'account','action' => 'send_money'), $this);?>"><?php echo l('send_money', 'send_money', '', 'text', array()); ?></a></li>
		*/ ?>
	</ul>
</div>
