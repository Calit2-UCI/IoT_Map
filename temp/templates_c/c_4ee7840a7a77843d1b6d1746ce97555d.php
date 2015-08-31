<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:14:33 Pacific Daylight Time */ ?>

<li class="<?php if ($this->_vars['filter'] == 'all'): ?>active<?php endif;  if (! $this->_vars['filter_data']['all']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/ausers/index/all"><?php echo l('filter_all_users', 'ausers', '', 'text', array()); ?> (<?php echo $this->_vars['count_data']['all']; ?>
)</a></li>
<li class="<?php if ($this->_vars['filter'] == 'moderator'): ?>active<?php endif;  if (! $this->_vars['filter_data']['moderator']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/moderators/"><?php echo l('filter_moderator_users', 'ausers', '', 'text', array()); ?> (<?php echo $this->_vars['count_data']['moderators']; ?>
)</a></li>