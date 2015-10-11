<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-06 00:02:13 Pacific Daylight Time */ ?>

<div id="<?php echo $this->_vars['data']['type']; ?>
"><?php echo tpl_function_block(array('name' => 'play','module' => 'like_me','value' => $this->_vars['data']), $this);?></div>