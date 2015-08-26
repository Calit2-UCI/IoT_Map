<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:37 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
">
	<div class="filter-form">
		<div class="form">
			<br>
			<div class="row">
				<div class="h"><?php echo l('field_nickname', 'ausers', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" value="<?php if (empty ( $this->_vars['data']['nickname'] )):  if ($this->_vars['DEMO_MODE']):  echo $this->_vars['demo_login_settings']['admin']['login'];  endif;  else:  echo $this->_run_modifier($this->_vars['data']['nickname'], 'escape', 'plugin', 1);  endif; ?>" name="nickname"></div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_password', 'ausers', '', 'text', array()); ?>: </div>
				<div class="v"><input type="password" value="<?php if ($this->_vars['DEMO_MODE']):  echo $this->_vars['demo_login_settings']['admin']['password'];  endif; ?>" name="password"></div>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_login" value="<?php echo l('btn_login', 'start', '', 'button', array()); ?>"></div></div>
</form>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>