<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:49:54 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="hlBox" id="<?php echo $this->_vars['hlb_id']; ?>
_box" data-multiselect="<?php echo $this->_vars['hlb_multiselect']; ?>
" data-defaults=<?php echo tpl_function_json_encode(array('data' => $this->_vars['hlb_selected']), $this);?> data-input="<?php echo $this->_vars['hlb_input']; ?>
">
	<div class="data">
		<div id="<?php echo $this->_vars['hlb_id']; ?>
_inputs"></div>
		<ul>
			<?php if (is_array($this->_vars['hlb_value']) and count((array)$this->_vars['hlb_value'])): foreach ((array)$this->_vars['hlb_value'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<li data-value="<?php echo $this->_vars['key']; ?>
"><?php echo $this->_vars['item']; ?>
</li>
			<?php endforeach; endif; ?>
		</ul>
	</div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
<script>hlboxes.push('<?php echo $this->_vars['hlb_id']; ?>
');</script>