<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 06:13:27 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="tab-submenu bg-highlight_bg" id="mailbox_top_menu">
	<div class="fleft delim-space">
		<ul>
			<li class="delim-stroke"><label><input type="checkbox" id="select_all_checkbox">&nbsp;<?php echo l('select_all', 'start', '', 'text', array()); ?></label></li>
			<?php if ($this->_vars['folder'] == 'spam'): ?><li><s id="unmark_spam" class="fa-exclamation icon-big edge hover zoom30" title="<?php echo l('link_message_unmark_spam', 'mailbox', '', 'button', array()); ?>"><del></del></s></li><?php endif; ?>
			<?php if ($this->_vars['folder'] == 'trash'): ?><li><s id="mailbox_untrash" class="fa-trash icon-big edge hover zoom30" title="<?php echo l('link_message_untrash', 'mailbox', '', 'button', array()); ?>"><del></del></s></li><?php endif; ?>
			<?php if ($this->_vars['folder'] == 'inbox'): ?><li><s id="mark_spam" class="fa-exclamation icon-big edge hover zoom30" title="<?php echo l('link_message_mark_spam', 'mailbox', '', 'button', array()); ?>"></s></li><?php endif; ?>
			<?php if ($this->_vars['folder'] != 'trash'): ?><li><s id="mailbox_delete" class="fa-trash icon-big edge hover zoom30" title="<?php echo l('link_message_delete', 'mailbox', '', 'button', array()); ?>"></s></li><?php endif; ?>
			<?php if ($this->_vars['folder'] == 'trash'): ?><li><s id="mailbox_delete_forever" class="fa-remove icon-big edge hover" title="<?php echo l('link_message_forever', 'mailbox', '', 'button', array()); ?>"></s></li><?php endif; ?>
		</ul>
	</div>
	<div class="fright">
		<ul>
			<li><input type="text" name="mail_keywords" id="mail_keywords" value="<?php echo $this->_run_modifier($this->_vars['keywords'], 'escape', 'plugin', 1); ?>
" autocomplete="off" class="small_input_text"></li>&nbsp;
			<li><input type="button" value="<?php echo l('btn_search', 'start', '', 'text', array()); ?>" name="btn_search" id="btn_search_messages" class="btn_search_messages small_button"></li>
		</ul>
	</div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
