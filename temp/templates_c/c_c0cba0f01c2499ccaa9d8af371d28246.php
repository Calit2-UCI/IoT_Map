<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 00:16:56 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="checkBox" id="<?php echo $this->_vars['cb_id']; ?>
_cbox" iname="<?php echo $this->_vars['cb_input']; ?>
">
	<?php if (is_array($this->_vars['cb_value']) and count((array)$this->_vars['cb_value'])): foreach ((array)$this->_vars['cb_value'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<div class="input">
			<div class="box<?php if ($this->_vars['item']['checked']): ?> checked<?php endif; ?>" gid="<?php echo $this->_vars['key']; ?>
"></div><?php if (1): ?><div class="label" gid="<?php echo $this->_vars['key']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</div><?php endif; ?>
			<?php if ($this->_vars['item']['checked']): ?><input type="hidden" name="<?php echo $this->_vars['cb_input'];  if ($this->_vars['cb_count'] > 1): ?>[]<?php endif; ?>" value="<?php echo $this->_vars['key']; ?>
" /><?php endif; ?>
		</div>
	<?php endforeach; endif; ?>
	<?php if ($this->_vars['cb_display_group_methods']): ?>
		<div class="clr"></div>
		<span id="<?php echo $this->_vars['cb_id']; ?>
_cbox_check_all" class="a" onclick="$(this).parent().find('input[type=checkbox]').prop('checked', true);"><?php echo l('select_all', 'start', '', 'text', array()); ?></span>&nbsp;|&nbsp;
		<span id="<?php echo $this->_vars['cb_id']; ?>
_cbox_uncheck_all" class="a" onclick="$(this).parent().find('input[type=checkbox]').prop('checked', false);"><?php echo l('unselect_all', 'start', '', 'text', array()); ?></span> 
	<?php endif; ?>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
<script>checkboxes.push('<?php echo $this->_vars['cb_id']; ?>
');</script>