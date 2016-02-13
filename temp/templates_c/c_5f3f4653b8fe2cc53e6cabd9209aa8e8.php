<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:35:56 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="content">
<div class="lc">
	<div class="inside account_menu">
		<?php echo tpl_function_helper(array('func_name' => 'get_content_tree','helper_name' => 'content','func_param' => $this->_vars['page']['id']), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'big-left-banner'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'left-banner'), $this);?>
	</div>
</div>
<div class="rc">
	<div class="content-block wysiwyg">
		<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
		<?php echo $this->_vars['page']['content']; ?>

	</div>
	<?php echo tpl_function_block(array('name' => 'show_social_networks_like','module' => 'social_networking'), $this);?>
	<?php echo tpl_function_block(array('name' => 'show_social_networks_share','module' => 'social_networking'), $this);?>
	<?php echo tpl_function_block(array('name' => 'show_social_networks_comments','module' => 'social_networking'), $this);?>
</div>
<div class="clr"></div>
</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>