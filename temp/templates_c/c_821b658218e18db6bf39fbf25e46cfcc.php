<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:47:42 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
">
	<div class="filter-form">
		<div class="form">
			<font><?php echo l('mod_installer_admin_login_text', 'start', '', 'text', array()); ?></font>
			<br><br>
			<div class="row">
				<div class="h"><?php echo l('field_login', 'start', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" value="<?php echo $this->_vars['data']['login']; ?>
" name="login" class="middle"></div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_password', 'start', '', 'text', array()); ?>: </div>
				<div class="v"><input type="password" value="" name="password" class="middle"></div>
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