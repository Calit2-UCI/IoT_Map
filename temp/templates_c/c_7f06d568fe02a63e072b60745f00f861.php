<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.default.php');
$this->register_modifier("default", "tpl_modifier_default"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:50:14 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<input type="hidden" name="<?php echo $this->_vars['sb_input']; ?>
" id="<?php echo $this->_vars['sb_id']; ?>
" value="<?php echo $this->_vars['sb_selected']; ?>
">
<div class="selectBox<?php if ($this->_vars['sb_class']): ?> <?php echo $this->_vars['sb_class'];  endif; ?>" id="<?php echo $this->_vars['sb_id']; ?>
_box">
	<div class="label"><?php echo $this->_run_modifier($this->_vars['sb_default'], 'default', 'plugin', 1, '&nbsp;'); ?>
</div><div class="arrow"></div>
	<div class="data">
		<ul>
			<?php if ($this->_vars['sb_default']): ?><li gid="0"><span><?php echo $this->_vars['sb_default']; ?>
</span></li><?php endif; ?>
			<?php if (is_array($this->_vars['sb_value']) and count((array)$this->_vars['sb_value'])): foreach ((array)$this->_vars['sb_value'] as $this->_vars['key'] => $this->_vars['item']): ?><li gid="<?php echo $this->_vars['key']; ?>
"><span><?php echo $this->_vars['item']; ?>
</span></li><?php endforeach; endif; ?>
		</ul>
	</div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
<script>if(typeof selects === 'undefined') selects = []; selects.push('<?php echo $this->_vars['sb_id']; ?>
');</script>