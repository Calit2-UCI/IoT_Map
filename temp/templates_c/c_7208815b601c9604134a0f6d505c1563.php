<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 00:54:49 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="services">
	<?php 
$this->assign(data_alert_lng, l('service_activate_confirm', 'services', '', 'text', array()));
 ?>
	<?php if (is_array($this->_vars['services_block_services']) and count((array)$this->_vars['services_block_services'])): foreach ((array)$this->_vars['services_block_services'] as $this->_vars['item']): ?>
		<div class="service">
			<div class="table">
				<dl>
					<dt class="view">
						<div class="h2"><?php echo $this->_vars['item']['name'];  if ($this->_vars['item']['price']): ?> - <?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['item']['price']), $this); endif; ?></div>
						<div class="t-2">
							<?php if ($this->_vars['item']['description']): ?><span><?php echo $this->_vars['item']['description']; ?>
</span><?php endif; ?>
							<?php if (is_array($this->_vars['item']['template']['data_admin_array']) and count((array)$this->_vars['item']['template']['data_admin_array'])): foreach ((array)$this->_vars['item']['template']['data_admin_array'] as $this->_vars['setting_gid'] => $this->_vars['setting_options']): ?>
								<div><span><?php echo $this->_vars['setting_options']['name']; ?>
: <?php echo $this->_vars['item']['data_admin'][$this->_vars['setting_gid']]; ?>
</span></div>
							<?php endforeach; endif; ?>
						</div>
					</dt>
					<dt class="righted">
						<?php if ($this->_vars['item']['price'] || $this->_vars['item']['template']['price_type'] != 1): ?>
							<input type="button" onclick="locationHref('<?php echo tpl_function_seolink(array('module' => 'services','method' => 'form','gid' => $this->_vars['item']['gid']), $this);?>');" value="<?php echo l('btn_buy_now', 'services', '', 'text', array()); ?>" />
						<?php else: ?>
							<input type="button" onclick="
								var href='<?php echo $this->_vars['site_url']; ?>
services/user_service_activate/<?php echo $this->_vars['user_id']; ?>
/0/<?php echo $this->_vars['item']['gid']; ?>
';
								var alert='<?php echo $this->_run_modifier($this->_vars['data_alert_lng'], 'escape', 'plugin', 1); ?>
<br><?php echo $this->_run_modifier($this->_vars['item']['name'], 'escape', 'plugin', 1); ?>
<br>(<?php echo $this->_run_modifier($this->_vars['item']['description'], 'escape', 'plugin', 1); ?>
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
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
