<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<script type='text/javascript'><?php echo '
	var banners;
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('module' => 'banners','file' => 'banners.js','return' => 'path'), $this); echo '",
			function(){
				banners = new Banners;
			},
			\'banners\'
		);
	});
'; ?>
</script>