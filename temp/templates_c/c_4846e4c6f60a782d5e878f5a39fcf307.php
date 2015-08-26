<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<?php if ($this->_vars['policy_page_gid']): ?>
	<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'cookie_policy_link')); tpl_block_capture(array('assign' => 'cookie_policy_link'), null, $this); ob_start(); ?>
		<?php echo tpl_function_block(array('name' => 'get_page_link','module' => 'content','page_gid' => $this->_vars['policy_page_gid']), $this);?>
	<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  endif; ?>
<div class="mod-cookie-policy noPrint" id="cookie_policy_block">
	<div class="content">
		<ins id="cookie_policy_close" class="fright icon-remove icon-2x"></ins>
		<?php echo l('text_cookie_policy', 'cookie_policy', '', 'text', array('link'=>$this->_vars['cookie_policy_link'])); ?>
	</div>
</div>
<?php echo tpl_function_js(array('module' => cookie_policy,'file' => 'cookie_policy.js'), $this); echo '<script>
	$(function(){
		new cookiePolicy({
			siteUrl: \'';  echo $this->_vars['site_root'];  echo '\',
			domain: \'';  echo $this->_vars['cookie_site_server'];  echo '\',
			path: \'';  echo $this->_vars['cookie_site_server'];  echo '\',
		});
	});
</script>'; ?>

