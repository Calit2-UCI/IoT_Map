<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 20:30:13 Pacific Daylight Time */ ?>

<div class="available-browsers">
	<a class="pos-abs" href='#' onclick='javascript:this.parentNode.style.display="none"; return false;'><img src='<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
/available_browsers/cornerx.jpg' alt='Close this notice'/></a>
	<div>
		<img class="fleft" src='<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
/available_browsers/warning.jpg' alt='Warning!'/>
		<div class="fleft pt10">
			<h2><?php echo l('available_browsers_text1', 'start', '', 'text', array()); ?></h2>
			<div><?php echo l('available_browsers_text2', 'start', '', 'text', array()); ?></div>
		</div>
		<a href='http://www.firefox.com' target='_blank'><img src='<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
/available_browsers/firefox.jpg' alt='Get Firefox'/></a>
		<a href='http://www.google.com/chrome' target='_blank'><img src='<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
/available_browsers/chrome.jpg' alt='Get Google Chrome'/></a>
		<a href='http://www.apple.com/safari/download/' target='_blank'><img src='<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
/available_browsers/safari.jpg' alt='Get Safari'/></a>
		<a href='http://www.browserforthebetter.com/download.html' target='_blank'><img src='<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
/available_browsers/ie8.jpg' alt='Get Internet Explorer'/></a>
	</div>
</div>