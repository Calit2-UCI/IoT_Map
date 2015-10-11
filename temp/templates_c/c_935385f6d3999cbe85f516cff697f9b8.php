<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-06 02:38:28 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<div class="content-block">

<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>

<div class="content-value">
<p><?php echo l('text_restore', 'users', '', 'text', array()); ?></p>

<div class="edit_block">
<form action="" method="post" >
<div class="r">
	<div class="f"><?php echo l('field_email', 'users', '', 'text', array()); ?>: </div>
	<div class="v"><input type="text" name="email" value="<?php echo $this->_run_modifier($this->_vars['data']['email'], 'escape', 'plugin', 1); ?>
"></div>
</div>
<div class="r">
	<div class="f">&nbsp;</div>
	<div class="v"><input type="submit" class='btn' value="<?php echo l('btn_send', 'start', '', 'button', array()); ?>" name="btn_save"></div>
</div>
</form>

</div>
</div>
</div>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
