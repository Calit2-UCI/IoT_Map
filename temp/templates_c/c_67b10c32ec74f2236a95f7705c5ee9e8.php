<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:45 Pacific Daylight Time */ ?>

<div class="pages">
	<?php if ($this->_vars['page_data']['total_rows']): ?>
		<span class="total"><?php echo l('showing', 'start', '', 'text', array()); ?> <?php echo $this->_vars['page_data']['start_num']; ?>
 - <?php echo $this->_vars['page_data']['end_num']; ?>
 / <?php echo $this->_vars['page_data']['total_rows']; ?>
</span>
	<?php endif; ?>
	&nbsp;<?php if (isset ( $this->_vars['page_data']['nav'] )):  echo $this->_vars['page_data']['nav'];  endif; ?>
</div>