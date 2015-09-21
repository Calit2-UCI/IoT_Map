<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-03 01:40:41 Pacific Daylight Time */ ?>

<a id="btn-wink-<?php echo $this->_vars['wink_button_rand']; ?>
" class="btn-wink<?php if ($this->_vars['wink_back']): ?>-back<?php endif; ?> link-r-margin"
   data-user-id="<?php echo $this->_vars['partner_id']; ?>
" data-is-pending="<?php echo $this->_vars['is_pending']; ?>
" data-is-new="<?php echo $this->_vars['is_new']; ?>
"
   title="<?php if ($this->_vars['wink_back']):  echo l('wink_back', 'winks', '', 'button', array());  else:  echo l('wink', 'winks', '', 'button', array());  endif; ?>"
   href="javascript:void(0);">
	<i class='fa-eye-open icon-big edge hover<?php if ($this->_vars['is_pending']): ?> g<?php endif; ?>'></i>
</a>
<script><?php echo '
	$(function(){
		loadScripts(
			["';  echo tpl_function_js(array('file' => 'winks.js','module' => 'winks','return' => 'path'), $this); echo '"],
			function(){
				winksObj = new winks({
					siteUrl: site_url,
					titleWink: \'';  echo l('wink', 'winks', '', 'text', array());  echo '\',
					titleWinkBack: \'';  echo l('wink_back', 'winks', '', 'text', array());  echo '\',
					errIsPending: \'';  echo l('error_is_pending', 'winks', '', 'text', array());  echo '\',
					succIgnored: \'';  echo l('msg_ignored', 'winks', '', 'text', array());  echo '\',
					succWinked: \'';  echo l('msg_winked', 'winks', '', 'text', array());  echo '\',
					succResponded: \'';  echo l('msg_responded', 'winks', '', 'text', array());  echo '\',
					wink_button_rand: \'';  echo $this->_vars['wink_button_rand'];  echo '\'
				});
			},
			\'winksObj\'
		);
	});
</script>'; ?>

