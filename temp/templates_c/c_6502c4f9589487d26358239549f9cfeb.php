<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:01:51 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<script type="text/javascript">
	selects = [];
	checkboxes = [];
	hlboxes = [];
</script>
<div class="search-box <?php echo $this->_vars['form_settings']['type']; ?>
">
	<div id="search-form-block_<?php echo $this->_vars['form_settings']['form_id']; ?>
"><?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  echo $this->_vars['form_block'];  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?></div>
</div>
<script type="text/javascript"><?php echo '
	$(function(){
		loadScripts(
			[
				"';  echo tpl_function_js(array('module' => 'start','file' => 'search.js','return' => 'path'), $this); echo '",
				"';  echo tpl_function_js(array('module' => 'start','file' => 'selectbox.js','return' => 'path'), $this); echo '",
				"';  echo tpl_function_js(array('module' => 'start','file' => 'checkbox.js','return' => 'path'), $this); echo '",
				"';  echo tpl_function_js(array('module' => 'start','file' => 'hlbox.js','return' => 'path'), $this); echo '"
			],
			function(){
				';  echo $this->_vars['form_settings']['object'];  echo $this->_vars['form_settings']['type'];  echo ' = new search({
					siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
					currentForm: \'';  echo $this->_vars['form_settings']['object'];  echo '\',
					currentFormType: \'';  echo $this->_vars['form_settings']['type'];  echo '\',
					hide_popup: ';  if ($this->_vars['form_settings']['hide_popup']): ?>true<?php else: ?>false<?php endif;  echo ',
					popup_autoposition: ';  if ($this->_vars['form_settings']['popup_autoposition']): ?>true<?php else: ?>false<?php endif;  echo '
				});
			},
			\'';  echo $this->_vars['form_settings']['object'];  echo $this->_vars['form_settings']['type'];  echo '\',
			{async: false}
		);
	});
'; ?>
</script>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>