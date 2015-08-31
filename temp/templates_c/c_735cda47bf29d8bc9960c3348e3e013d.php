<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.default.php');
$this->register_modifier("default", "tpl_modifier_default");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 17:51:57 Pacific Daylight Time */ ?>

					<ul id="select_options">
					<?php if (is_array($this->_vars['reference_data']['option']) and count((array)$this->_vars['reference_data']['option'])): foreach ((array)$this->_vars['reference_data']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
					<li id="option_<?php echo $this->_vars['key']; ?>
"><?php echo $this->_run_modifier($this->_vars['item'], 'default', 'plugin', 1, "&nbsp;"); ?>

						<div class="icons">
							<a href="#" class="active_link hide"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_default_option', 'field_editor', '', 'text', array()); ?>" title="<?php echo l('link_default_option', 'field_editor', '', 'text', array()); ?>"></a>
							<a href="#" class="deactive_link hide"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_default_option', 'field_editor', '', 'text', array()); ?>" title="<?php echo l('link_default_option', 'field_editor', '', 'text', array()); ?>"></a>
							<a href="#" class="edit_link"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_option', 'field_editor', '', 'text', array()); ?>" title="<?php echo l('link_edit_option', 'field_editor', '', 'text', array()); ?>"></a>
							<a href="#" class="delete_link"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_option', 'field_editor', '', 'text', array()); ?>" title="<?php echo l('link_delete_option', 'field_editor', '', 'text', array()); ?>"></a>
						</div>
					</li>
					<?php endforeach; endif; ?>
					</ul>
