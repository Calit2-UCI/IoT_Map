<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:11:52 Pacific Daylight Time */ ?>

<div class="menu-level2">
	<ul>
		<li<?php if ($this->_vars['section'] == 'overview'): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/start/settings/overview"><?php echo l('sett_overview_item', 'start', '', 'text', array()); ?></a></div></li>
		<li<?php if ($this->_vars['section'] == 'numerics'): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/start/settings/numerics"><?php echo l('sett_numerics_item', 'start', '', 'text', array()); ?></a></div></li>
		<?php if (is_array($this->_vars['other_settings']) and count((array)$this->_vars['other_settings'])): foreach ((array)$this->_vars['other_settings'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<li<?php if ($this->_vars['key'] == $this->_vars['section']): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/start/settings/<?php echo $this->_vars['key']; ?>
"><?php echo l('sett_'.$this->_vars['key'].'_item', 'start', '', 'text', array()); ?></a></div></li>
		<?php endforeach; endif; ?>
		<li<?php if ($this->_vars['section'] == 'date_formats'): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/start/settings/date_formats"><?php echo l('sett_date_formats_item', 'start', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>