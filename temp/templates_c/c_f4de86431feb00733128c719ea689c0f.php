<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:25:53 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<div>
		<?php if ($this->_vars['event']['id_wall'] == $this->_vars['user_id'] || $this->_vars['event']['id_poster'] == $this->_vars['user_id']): ?>
			<a class="fright delete_wall_event" data-id="<?php echo $this->_vars['event']['id']; ?>
" data-message="<?php echo l('confirm_delete', 'wall_events', '', 'text', array()); ?>" href="javascript:;"><?php echo l('btn_delete', 'start', '', 'text', array()); ?></a>
		<?php endif; ?>
	</div>
	<?php echo tpl_function_counter(array('start' => 0,'print' => false,'assign' => 'i'), $this);?>
	<?php if (is_array($this->_vars['event']['data']) and count((array)$this->_vars['event']['data'])): foreach ((array)$this->_vars['event']['data'] as $this->_vars['key'] => $this->_vars['edata']): ?>
		<div><?php echo $this->_run_modifier($this->_vars['edata']['event_date'], 'date_format', 'plugin', 1, $this->_vars['date_format']);  if ($this->_vars['event']['id_poster'] != $this->_vars['user_id']): ?><span class="ml10"><?php echo tpl_function_block(array('name' => 'mark_as_spam_block','module' => 'spam','object_id' => $this->_vars['event']['id'],'type_gid' => 'wall_events_object','template' => 'minibutton'), $this);?></span><?php endif; ?></div>

		<?php if ($this->_vars['edata']['text']): ?><div><?php echo $this->_run_modifier($this->_vars['edata']['text'], 'nl2br', 'PHP', 1); ?>
</div><?php endif; ?>

		<?php if ($this->_vars['event']['media'][$this->_vars['key']]['img']): ?>
			<div class="wall-gallery" gallery="wall_<?php echo $this->_vars['event']['id']; ?>
">
				<?php if (is_array($this->_vars['event']['media'][$this->_vars['key']]['img']) and count((array)$this->_vars['event']['media'][$this->_vars['key']]['img'])): foreach ((array)$this->_vars['event']['media'][$this->_vars['key']]['img'] as $this->_vars['item']): ?>
					<?php echo tpl_function_counter(array('print' => false), $this);?>
					<div class="ib p5"><img src="<?php if ($this->_vars['i'] > 8):  echo $this->_vars['item']['thumbs']['middle'];  else:  echo $this->_vars['item']['thumbs']['big'];  endif; ?>" gallery-src="<?php echo $this->_vars['item']['thumbs']['grand']; ?>
" 
						alt="<?php echo $this->_run_modifier($this->_vars['item']['photo_alt'], 'escape', 'plugin', 1); ?>
" title="<?php echo $this->_run_modifier($this->_vars['item']['photo_title'], 'escape', 'plugin', 1); ?>
" /></div>
				<?php endforeach; endif; ?>
			</div>
		<?php endif; ?>

		<?php if (! empty ( $this->_vars['event']['media'][$this->_vars['key']]['video'] )): ?>
			<?php if (is_array($this->_vars['event']['media'][$this->_vars['key']]['video']) and count((array)$this->_vars['event']['media'][$this->_vars['key']]['video'])): foreach ((array)$this->_vars['event']['media'][$this->_vars['key']]['video'] as $this->_vars['item']): ?>
				<div class="ptb5">
					<?php if ($this->_vars['item']['status'] == 'start'): ?>
						<div><?php echo $this->_vars['item']['file_name']; ?>
</div>
						<div class="error-text"><?php echo l('video_converting', 'wall_events', '', 'text', array()); ?></div>
					<?php elseif ($this->_vars['item']['errors']): ?>
						<div><?php echo $this->_vars['item']['file_name']; ?>
</div>
						<?php if (is_array($this->_vars['item']['errors']) and count((array)$this->_vars['item']['errors'])): foreach ((array)$this->_vars['item']['errors'] as $this->_vars['err']): ?>
							<div class="error-text"><?php echo $this->_vars['err']; ?>
</div>
						<?php endforeach; endif; ?>
					<?php elseif ($this->_vars['item']['embed']): ?>
						<div><?php echo $this->_vars['item']['embed']; ?>
</div>
					<?php endif; ?>
				</div>
			<?php endforeach; endif; ?>
		<?php endif; ?>
	<?php endforeach; endif; ?>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
