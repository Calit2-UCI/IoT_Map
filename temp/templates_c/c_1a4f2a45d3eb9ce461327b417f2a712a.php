<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:35 Pacific Daylight Time */ ?>

<a id="btn-kisses-<?php echo $this->_vars['kisses_button_rand']; ?>
" class="btn-kisses<?php if ($this->_vars['kisses_back']): ?>-back<?php endif; ?> link-r-margin"
   title="<?php echo l('kiss', 'kisses', '', 'button', array()); ?>"
   href="javascript:void(0);">
	<i class='icon-smile icon-big edge hover'></i>
</a>

<script><?php echo '
	$(function(){
		
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'kisses.js','module' => 'kisses','return' => 'path'), $this); echo '",
			function(){
				kisses = new Kisses({
					siteUrl: site_url,
					use_form: true,
					btnForm: \''; ?>
btn-kisses-<?php echo $this->_vars['kisses_button_rand'];  echo '\',
					urlGetForm: \''; ?>
kisses/ajax_get_kisses/<?php echo $this->_vars['user_id'];  echo '\',
					urlSendForm: \''; ?>
kisses/ajax_set_kisses/<?php echo $this->_vars['user_id'];  echo '\',
					dataType: \'';  if ($this->_vars['is_user']): ?>html<?php else: ?>json<?php endif;  echo '\',
				});
			},
			[\'kisses\'],
			{async: false}
		);
		
	});
'; ?>
</script>
