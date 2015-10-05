<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 04:20:50 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_shoutbox_menu'), $this);?>
<div class="actions">&nbsp;</div>


<form method="post" action="" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header"><?php echo $this->_vars['settings_data']['name']; ?>
</div>
		<?php if (is_array($this->_vars['settings_data']['vars']) and count((array)$this->_vars['settings_data']['vars'])): foreach ((array)$this->_vars['settings_data']['vars'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<div class="row<?php if (!($this->_vars['key'] % 2)): ?> zebra<?php endif; ?>">
				<div class="h"><?php echo $this->_vars['item']['field_name']; ?>
:</div>
				<div class="v">
					<?php if ($this->_vars['item']['field_type'] == 'text' || ! $this->_vars['item']['field_type']): ?>
						<input type="text" name="settings[<?php echo $this->_vars['item']['field']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['item']['value'], 'escape', 'plugin', 1); ?>
" class="short">
					<?php elseif ($this->_vars['item']['field_type'] == 'checkbox'): ?>
						<input type="checkbox" name="settings[<?php echo $this->_vars['item']['field']; ?>
]" value="1" <?php if ($this->_vars['item']['value']): ?>checked<?php endif; ?> class="short">
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; endif; ?>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
</form>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
