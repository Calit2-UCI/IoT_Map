<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:44:01 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block load_content">
	<h1><?php echo l('header_use_services', 'services', '', 'text', array()); ?></h1>

	<div class="inside">
		<?php 
$this->assign(data_alert_lng, l('service_activate_confirm', 'services', '', 'text', array()));
 ?>

		<?php if ($this->_vars['service']['is_free_status'] || $this->_vars['block_data']['user_services']): ?>
			<?php if ($this->_vars['service']['is_free_status']): ?>
				<div class="h3"><?php echo l('service_activate_free_text', 'services', '', 'text', array()); ?></div>
				<div class="service<?php if ($this->_vars['block_data']['user_services']): ?> mb10<?php endif; ?>">
					<div class="table">
						<dl>
							<dt class="view">
								<div class="h2"><?php echo $this->_vars['service']['name']; ?>
</div>
								<div class="t-2">
									<?php if (is_array($this->_vars['service']['template']['data_admin_array']) and count((array)$this->_vars['service']['template']['data_admin_array'])): foreach ((array)$this->_vars['service']['template']['data_admin_array'] as $this->_vars['setting_gid'] => $this->_vars['setting_options']): ?>
										<div><span><?php echo $this->_vars['setting_options']['name']; ?>
: <?php echo $this->_vars['service']['data_admin'][$this->_vars['setting_gid']]; ?>
</span></div>
									<?php endforeach; endif; ?>
								</div>
							</dt>
							<dt class="righted">
								<input type="button" onclick="
									var href='<?php echo $this->_vars['site_url']; ?>
services/user_service_activate/<?php echo $this->_vars['user_session_data']['user_id']; ?>
/0/<?php echo $this->_vars['service']['gid']; ?>
';
									var alert='<?php echo $this->_run_modifier($this->_vars['data_alert_lng'], 'escape', 'plugin', 1); ?>
<br><?php echo $this->_run_modifier($this->_vars['service']['name'], 'escape', 'plugin', 1); ?>
<br>(<?php echo $this->_run_modifier($this->_vars['service']['description'], 'escape', 'plugin', 1); ?>
)';
									<?php echo '
									if(!parseInt(\'';  echo $this->_vars['service']['template']['alert_activate'];  echo '\')) {
										locationHref(href);
									} else {
										alerts.show({
											text: alert.replace(/<br>/g, \'\\n\'),
											type: \'confirm\',
											ok_callback: function(){
												locationHref(href);
											}
										});
									}'; ?>
" value="<?php echo l('btn_activate', 'services', '', 'text', array()); ?>" />
							</dt>
						</dl>
					</div>
				</div>
				<?php if ($this->_vars['block_data']['user_services']): ?>
					<div class="centered h3"><?php echo l('or', 'start', '', 'text', array()); ?></div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ($this->_vars['block_data']['user_services']): ?>
				<div class="h3"><?php echo l('service_spend_text', 'services', '', 'text', array()); ?></div>
				<form method="POST" action="" id="ability_form" class="oya <?php if ($this->_vars['service']['is_free_status']): ?>maxh150<?php else: ?>maxh250<?php endif; ?>">
					<?php if (is_array($this->_vars['block_data']['user_services']) and count((array)$this->_vars['block_data']['user_services'])): foreach ((array)$this->_vars['block_data']['user_services'] as $this->_vars['item']): ?>
						<div class="service">
							<div class="table">
								<dl>
									<dt class="view">
										<div class="h2"><?php if ($this->_vars['item']['package_name']):  echo $this->_vars['item']['package_name']; ?>
 : <?php endif;  if ($this->_vars['item']['service']['name']):  echo $this->_vars['item']['service']['name'];  else:  echo $this->_vars['item']['name'];  endif;  if ($this->_vars['item']['count']): ?>&nbsp;(<?php echo $this->_vars['item']['count']; ?>
)<?php endif; ?></div>
										<?php if ($this->_vars['item']['package_till_date']): ?><div><?php echo l('package_active_till', 'packages', '', 'text', array()); ?>:&nbsp;<?php echo $this->_run_modifier($this->_vars['item']['package_till_date'], 'date_format', 'plugin', 1, $this->_vars['block_data']['date_time_format']); ?>
</div><?php endif; ?>
										<div class="t-2">
											<?php if (is_array($this->_vars['item']['service']['template']['data_admin_array']) and count((array)$this->_vars['item']['service']['template']['data_admin_array'])): foreach ((array)$this->_vars['item']['service']['template']['data_admin_array'] as $this->_vars['setting_gid'] => $this->_vars['setting_options']): ?>
												<div><span><?php echo $this->_vars['setting_options']['name']; ?>
: <?php echo $this->_vars['item']['service']['data_admin'][$this->_vars['setting_gid']]; ?>
</span></div>
											<?php endforeach; endif; ?>
										</div>
									</dt>
									<dt class="righted">
										<input type="button" data-value="<?php echo $this->_vars['item']['id']; ?>
" data-alert="<?php if ($this->_vars['item']['template']['alert_activate']):  echo $this->_run_modifier($this->_vars['data_alert_lng'], 'escape', 'plugin', 1, html); ?>
<br><?php echo $this->_run_modifier($this->_vars['item']['name'], 'escape', 'plugin', 1, html); ?>
<br><?php echo $this->_run_modifier($this->_vars['item']['description'], 'escape', 'plugin', 1, html); ?>
<br><?php echo $this->_run_modifier($this->_vars['item']['alert'], 'escape', 'plugin', 1, html);  endif; ?>" value="<?php echo l('btn_activate', 'services', '', 'text', array()); ?>" />
									</dt>
								</dl>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</form>
			<?php endif; ?>
		<?php else: ?>
			<p><?php echo l('service_buy_text', 'services', '', 'text', array()); ?></p>
			<a href="<?php echo tpl_function_seolink(array('module' => 'services','method' => 'index','gid' => $this->_vars['template']['gid']), $this);?>" target="blank"><?php echo l('service_link_buy', 'services', '', 'text', array()); ?></a>
		<?php endif; ?>
	</div>
	<div class="clr"></div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
