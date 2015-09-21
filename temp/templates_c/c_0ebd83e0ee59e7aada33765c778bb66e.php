<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:43:25 Pacific Daylight Time */ ?>

<?php if ($this->_vars['block_memberships']): ?>
    <?php if ($this->_vars['headline']): ?>
        <div class="expandable">
            <h2 class="h2"><?php echo l('header_memberships_index', 'memberships', '', 'text', array()); ?></h2>
        </div>
    <?php endif; ?>
	<div class="table-scroll memberships">
		<div>
			<table>
				
				<colgroup>
					<col>
					<?php if (is_array($this->_vars['block_memberships']) and count((array)$this->_vars['block_memberships'])): foreach ((array)$this->_vars['block_memberships'] as $this->_vars['mkey'] => $this->_vars['membership']): ?>
						<col<?php if (! empty ( $this->_vars['membership']['is_mine'] )): ?> class="my"<?php endif; ?>>
					<?php endforeach; endif; ?>
				</colgroup>
				<caption></caption>
				<thead>
					<tr>
						
						<th></th>
						<?php if (is_array($this->_vars['block_memberships']) and count((array)$this->_vars['block_memberships'])): foreach ((array)$this->_vars['block_memberships'] as $this->_vars['mkey'] => $this->_vars['membership']): ?>
							<th>
								<h3><?php echo $this->_vars['membership']['name']; ?>
</h3>
								<div><b class="price"><?php echo tpl_function_block(array('name' => 'currency_format_output','module' => 'start','value' => $this->_vars['membership']['price']), $this);?></b></div>
								<div class="info">
									<?php echo $this->_vars['membership']['period_count']; ?>
 <?php echo $this->_vars['membership']['period_type_output']; ?>

								</div>
								<?php if (! empty ( $this->_vars['membership']['is_mine'] )): ?>
										<?php echo l('text_expires', 'memberships', '', 'text', array()); ?> <?php echo $this->_run_modifier($this->_vars['membership']['expired'], 'date_format', 'plugin', 1, $this->_vars['block_memberships_date_format']); ?>

								<?php elseif (empty ( $this->_vars['hide_buy_btn'] )): ?>
									<div><a href="<?php echo tpl_function_seolink(array('module' => 'memberships','method' => 'form','gid' => $this->_vars['membership']['gid']), $this);?>" 
									   class="button"><?php echo l('btn_buy_now', 'memberships', '', 'text', array()); ?></a></div>
								<?php endif; ?>
							</th>
						<?php endforeach; endif; ?>
					</tr>
				</thead>
				<tbody>
					
					<?php if (is_array($this->_vars['all_services']) and count((array)$this->_vars['all_services'])): foreach ((array)$this->_vars['all_services'] as $this->_vars['tpl_gid'] => $this->_vars['service']): ?>
						<tr>
							<th><?php echo $this->_vars['service']['name']; ?>
</th>
							<?php if (is_array($this->_vars['block_memberships']) and count((array)$this->_vars['block_memberships'])): foreach ((array)$this->_vars['block_memberships'] as $this->_vars['membership']): ?>
								<?php $this->assign('mId', $this->_vars['membership']['id']); ?>
								<td>
									<?php if ($this->_vars['service']['membership_templates'][$this->_vars['mId']]): ?>
										+
									<?php else: ?>
										-
									<?php endif; ?>
								</td>
							<?php endforeach; endif; ?>
						</tr>
					<?php endforeach; endif; ?>
					<?php if ($this->_vars['duplicate_buttons'] && empty ( $this->_vars['hide_buy_btn'] )): ?>
						<tr>
							<th></th>
							<?php if (isset($this->_sections['m'])) unset($this->_sections['m']);
$this->_sections['m']['loop'] = is_array($this->_vars['block_memberships']) ? count($this->_vars['block_memberships']) : max(0, (int)$this->_vars['block_memberships']);
$this->_sections['m']['name'] = 'm';
$this->_sections['m']['show'] = true;
$this->_sections['m']['max'] = $this->_sections['m']['loop'];
$this->_sections['m']['step'] = 1;
$this->_sections['m']['start'] = $this->_sections['m']['step'] > 0 ? 0 : $this->_sections['m']['loop']-1;
if ($this->_sections['m']['show']) {
	$this->_sections['m']['total'] = $this->_sections['m']['loop'];
	if ($this->_sections['m']['total'] == 0)
		$this->_sections['m']['show'] = false;
} else
	$this->_sections['m']['total'] = 0;
if ($this->_sections['m']['show']):

		for ($this->_sections['m']['index'] = $this->_sections['m']['start'], $this->_sections['m']['iteration'] = 1;
			 $this->_sections['m']['iteration'] <= $this->_sections['m']['total'];
			 $this->_sections['m']['index'] += $this->_sections['m']['step'], $this->_sections['m']['iteration']++):
$this->_sections['m']['rownum'] = $this->_sections['m']['iteration'];
$this->_sections['m']['index_prev'] = $this->_sections['m']['index'] - $this->_sections['m']['step'];
$this->_sections['m']['index_next'] = $this->_sections['m']['index'] + $this->_sections['m']['step'];
$this->_sections['m']['first']	  = ($this->_sections['m']['iteration'] == 1);
$this->_sections['m']['last']	   = ($this->_sections['m']['iteration'] == $this->_sections['m']['total']);
?>
								<td>
									<a href="<?php echo tpl_function_seolink(array('module' => 'memberships','method' => 'form','gid' => $this->_vars['block_memberships'][$this->_sections['m']['index']]['gid']), $this);?>" 
									   class="button"><?php echo l('btn_buy_now', 'memberships', '', 'text', array()); ?></a>
								</td>
							<?php endfor; endif; ?>
						</tr>
					<?php endif; ?>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
	</div>
<?php else: ?>
	<?php echo l('no_memberships', 'memberships', '', 'text', array());  endif; ?>
