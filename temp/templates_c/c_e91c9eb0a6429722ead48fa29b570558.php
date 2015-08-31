<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:14:33 Pacific Daylight Time */ ?>

<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/moderators/edit"><?php echo l('link_add_user', 'moderators', '', 'text', array()); ?></a></div></li>