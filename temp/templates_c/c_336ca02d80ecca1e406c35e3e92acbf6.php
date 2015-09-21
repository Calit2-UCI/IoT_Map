<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:40:47 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="services">
	<?php $this->assign('is_inactive_services', 0); ?>
	<?php 
$this->assign(data_alert_lng, l('service_activate_confirm', 'services', '', 'text', array()));
 ?>
	<?php if (is_array($this->_vars['services_block_services']) and count((array)$this->_vars['services_block_services'])): foreach ((array)$this->_vars['services_block_services'] as $this->_vars['item']): ?>
		<?php if (! $this->_vars['item']['is_active']):  $this->assign('is_inactive_services', 1);  endif; ?>
		<div class="service<?php if (! $this->_vars['item']['is_active']): ?> inactive hide-always<?php endif; ?>">
			<div class="table">
				<dl>
					<dt class="view">
						<div class="h2"><?php if ($this->_vars['item']['service']['name_lang_gid']):  echo l($this->_vars['item']['service']['name_lang_gid'], 'services', '', 'text', array());  else:  echo $this->_vars['item']['name'];  endif;  if ($this->_vars['item']['count']): ?>&nbsp;(<?php echo $this->_vars['item']['count']; ?>
)<?php endif; ?></div>
						<div class="t-2">
							<?php if (! $this->_vars['item']['is_active']): ?>
								<div class="pb5">
									<?php echo l('activated', 'services', '', 'text', array()); ?>:&nbsp;<?php echo $this->_run_modifier($this->_vars['item']['date_modified'], 'date_format', 'plugin', 1, $this->_vars['services_block_date_formats']['date_format']); ?>

									<?php if ($this->_vars['item']['date_expires']): ?><br>
										<?php if ($this->_vars['item']['is_expired']): ?>
											<?php echo l('expires', 'services', '', 'text', array()); ?>:
											<?php echo l('expired', 'services', '', 'text', array()); ?>:
										<?php else: ?>
											<?php echo l(expires, services, '', 'text', array()); ?>:
										<?php endif; ?>
										&nbsp;<?php echo $this->_run_modifier($this->_vars['item']['date_expires'], 'date_format', 'plugin', 1, $this->_vars['services_block_date_formats']['date_format']); ?>

									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php if ($this->_vars['item']['service']['description_lang_gid']): ?>
								<div>
									<span><?php echo l($this->_vars['item']['service']['description_lang_gid'], 'services', '', 'text', array()); ?></span>
								</div>
							<?php elseif ($this->_vars['item']['description']): ?>
								<div>
									<span><?php echo $this->_vars['item']['description']; ?>
</span>
								</div>
							<?php endif; ?>
							<?php if (is_array($this->_vars['item']['service']['template']['data_admin_array']) and count((array)$this->_vars['item']['service']['template']['data_admin_array'])): foreach ((array)$this->_vars['item']['service']['template']['data_admin_array'] as $this->_vars['setting_gid'] => $this->_vars['setting_options']): ?>
								<div>
									<span><?php echo l($this->_vars['setting_options']['name_lang_gid'], 'services', '', 'text', array()); ?>: <?php echo $this->_vars['item']['service']['data_admin_array'][$this->_vars['setting_gid']]; ?>
</span>
								</div>
							<?php endforeach; endif; ?>
						</div>
					</dt>
					<dt class="righted">
						<?php if ($this->_vars['item']['is_active']): ?>
							<input type="button" onclick="
								var href='<?php echo $this->_vars['site_url']; ?>
services/user_service_activate/<?php echo $this->_vars['item']['id_user']; ?>
/<?php echo $this->_vars['item']['id']; ?>
/<?php echo $this->_vars['item']['service_gid']; ?>
';
								var alert='<?php echo $this->_run_modifier($this->_vars['data_alert_lng'], 'escape', 'plugin', 1); ?>
<br><?php echo $this->_run_modifier($this->_vars['item']['name'], 'escape', 'plugin', 1); ?>
<br>(<?php echo $this->_run_modifier($this->_vars['item']['description'], 'escape', 'plugin', 1, javascript); ?>
)';
								<?php echo '
								if(!parseInt(\'';  echo $this->_vars['item']['template']['alert_activate'];  echo '\')) {
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
						<?php endif; ?>
					</dt>
				</dl>
			</div>
		</div>
	<?php endforeach; else: ?>
		<?php echo l('no_services', 'services', '', 'text', array()); ?>
	<?php endif; ?>

	<?php if ($this->_vars['is_inactive_services']): ?>
		<div><span class="a" onclick="$(this).parents('.services').find('.service.inactive').toggleClass('hide-always');"><?php echo l('show_hide_inactive_services', 'services', '', 'text', array()); ?></span></div>
	<?php endif; ?>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
