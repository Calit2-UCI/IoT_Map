<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:37:22 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if ($this->_vars['page_type'] == 'cute'): ?>
	<div class="pages">
		<div class="inside">
			<ins class="current"><?php echo l('text_pages', 'start', '', 'text', array()); ?> <?php echo $this->_vars['page_data']['cur_page']; ?>
 <?php echo l('text_of', 'start', '', 'text', array()); ?> <?php echo $this->_vars['page_data']['total_pages']; ?>
</ins>
			<ins class="prev<?php if ($this->_vars['page_data']['prev_page'] == $this->_vars['page_data']['cur_page']): ?> gray<?php endif; ?>"><a href="<?php echo $this->_vars['page_data']['base_url'];  echo $this->_vars['page_data']['prev_page']; ?>
">&nbsp;</a></ins>
			<ins class="next<?php if ($this->_vars['page_data']['next_page'] == $this->_vars['page_data']['cur_page']): ?> gray<?php endif; ?>"><a href="<?php echo $this->_vars['page_data']['base_url'];  echo $this->_vars['page_data']['next_page']; ?>
">&nbsp;</a></ins>
		</div>
	</div>
<?php elseif ($this->_vars['page_type'] == 'full' && $this->_vars['page_data']['nav']): ?>
	<div class="line pages">
		<div class="inside"><?php echo $this->_vars['page_data']['nav']; ?>
</div>
	</div>
<?php endif;  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
