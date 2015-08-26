<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<?php if ($this->_vars['like']): ?>
<p><?php echo l('do_you_like', 'social_networking', '', 'text', array()); ?></p>
<?php echo $this->_vars['like']; ?>

<?php endif; ?>
