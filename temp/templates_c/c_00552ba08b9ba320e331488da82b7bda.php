<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:49:35 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content-block">
	<h1><?php echo l('header_services_list', 'services', '', 'text', array()); ?></h1>
    <?php echo tpl_function_block(array('name' => 'services_buy_list','module' => 'services','template_gid' => $this->_vars['template_gid']), $this);?>
    <?php echo tpl_function_block(array('name' => 'memberships_list','module' => 'memberships','template_gid' => $this->_vars['template_gid'],'headline' => 1), $this);?>
    <?php echo tpl_function_block(array('name' => 'packages_list','module' => 'packages','template_gid' => $this->_vars['template_gid'],'headline' => 1), $this);?>
</div>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

