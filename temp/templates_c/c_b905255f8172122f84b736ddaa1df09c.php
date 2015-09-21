<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 02:28:40 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block load_content">
	<h1><?php echo l('header_wall_settings', 'wall_events', '', 'text', array()); ?>&nbsp;</h1>

	<?php if ($this->_vars['user_perm']): ?>
		<div class="inside wall-perm-form">
			<form action="<?php echo $this->_vars['site_url']; ?>
wall_events/save_user_permissions" method="post">
				<input type="hidden" value="<?php echo $this->_vars['redirect_url']; ?>
" name="redirect_url" />
				<div class="r">
					<?php if ($this->_vars['user_perm']['wall_post']): ?>
						<div class="r">
							<input type="hidden" name="perm[wall_post][post_allow]" value="0" />
							<label>
								<input type="checkbox" name="perm[wall_post][post_allow]" value="1"<?php if ($this->_vars['user_perm']['wall_post']['post_allow']): ?>checked<?php endif; ?> />
								&nbsp;<?php echo l('post_allow', 'wall_events', '', 'text', array()); ?>
							</label>
						</div>
					<?php endif; ?>
					<table class="list">
						<tr>
							<th><?php echo l('events', 'wall_events', '', 'text', array()); ?></th>
							<th><?php echo l('my_events_show', 'wall_events', '', 'text', array()); ?></th>
							<th><?php echo l('friends_events_show', 'wall_events', '', 'text', array()); ?></th>
						</tr>
						<?php if (is_array($this->_vars['user_perm']) and count((array)$this->_vars['user_perm'])): foreach ((array)$this->_vars['user_perm'] as $this->_vars['gid'] => $this->_vars['perm']): ?>
							<tr>
								<td><?php echo l('wetype_'.$this->_vars['gid'], 'wall_events', '', 'text', array()); ?></td>
								<td>
									<select name="perm[<?php echo $this->_vars['gid']; ?>
][permissions]">
										<option value="0"<?php if ($this->_vars['perm']['permissions'] == 0): ?> selected<?php endif; ?>><?php echo l('value_for_me', 'wall_events', '', 'text', array()); ?></option>
										<option value="1"<?php if ($this->_vars['perm']['permissions'] == 1): ?> selected<?php endif; ?>><?php echo l('value_for_friends', 'wall_events', '', 'text', array()); ?></option>
										<option value="2"<?php if ($this->_vars['perm']['permissions'] == 2): ?> selected<?php endif; ?>><?php echo l('value_for_registered', 'wall_events', '', 'text', array()); ?></option>
										<option value="3"<?php if ($this->_vars['perm']['permissions'] == 3): ?> selected<?php endif; ?>><?php echo l('value_for_all', 'wall_events', '', 'text', array()); ?></option>
									</select>
								</td>
								<td class="center"><input type="hidden" name="perm[<?php echo $this->_vars['gid']; ?>
][feed]" value="0" /><input type="checkbox" name="perm[<?php echo $this->_vars['gid']; ?>
][feed]"<?php if ($this->_vars['perm']['feed']): ?> checked<?php endif; ?> value="1" /></td>
							</tr>
						<?php endforeach; endif; ?>
					</table>
				</div>
				<div>
					<input type="submit" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_save">
				</div>
			</form>
		</div>
	<?php else: ?>
		<div class="p20"><?php echo l('no_wall_events_types', 'wall_events', '', 'text', array()); ?></div>
	<?php endif; ?>
	<div class="clr"></div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>