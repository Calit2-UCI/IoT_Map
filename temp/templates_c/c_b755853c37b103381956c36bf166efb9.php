<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:37 Pacific Daylight Time */ ?>


<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<span class="color-link_color like_block<?php echo $this->_vars['likes_helper_block_class']; ?>
" title="[<?php echo $this->_vars['likes_helper_gid']; ?>
_title]" data-gid="<?php echo $this->_vars['likes_helper_gid']; ?>
" data-action="[<?php echo $this->_vars['likes_helper_gid']; ?>
_action]">
	<span id="like_btn_<?php echo $this->_vars['likes_helper_gid']; ?>
" class="like_btn [<?php echo $this->_vars['likes_helper_gid']; ?>
_class]<?php echo $this->_vars['likes_helper_btn_class']; ?>
" href="javascript:void(0)"></span>
	<span class="like_num<?php echo $this->_vars['likes_helper_num_class']; ?>
">[<?php echo $this->_vars['likes_helper_gid']; ?>
]</span>
</span>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>