<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.module_tpl.php');
$this->register_function("module_tpl", "tpl_function_module_tpl"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:39 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="homepage_block">
	<table cellspacing="0" cellpadding="0" class="data get-started" width="100%">
	<tr>
		<th class="first" colspan=3><?php echo l('header_get_started', 'admin_home_page', '', 'text', array()); ?></th>
	</tr>
	<tr>
		<td class="first">
			<?php echo tpl_function_module_tpl(array('module' => 'ausers','tpl' => 'link_edit','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'notifications','tpl' => 'link_settings','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'seo','tpl' => 'link_default_listing','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'cronjob','tpl' => 'link_index','theme_type' => 'admin'), $this);?><br/>
		</td>
		<td>
			<?php echo tpl_function_module_tpl(array('module' => 'countries','tpl' => 'link_index','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'video_uploads','tpl' => 'link_system_settings','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'uploads','tpl' => 'link_watermarks','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'file_uploads','tpl' => 'link_index','theme_type' => 'admin'), $this);?><br/>
		</td>
		<td>
			<?php echo tpl_function_module_tpl(array('module' => 'moderation','tpl' => 'link_settings','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'dynamic_blocks','tpl' => 'link_index','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'languages','tpl' => 'link_pages','theme_type' => 'admin'), $this);?><br/>
			<?php echo tpl_function_module_tpl(array('module' => 'content','tpl' => 'link_index','theme_type' => 'admin'), $this);?><br/>
		</td>
	</tr>
	</table>

	<h2><?php echo l('header_quick_start', 'admin_home_page', '', 'text', array()); ?></h2>
	<?php echo tpl_function_helper(array('func_name' => 'admin_home_stat','module' => 'users','cache' => 'true'), $this);?>

	<div class="right-side">
		<?php echo tpl_function_helper(array('func_name' => 'admin_home_payments_block','module' => 'payments'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'admin_home_polls_block','module' => 'polls'), $this);?>
	</div>

	<div class="left-side">
		<?php echo tpl_function_helper(array('func_name' => 'admin_home_users_block','module' => 'users'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'admin_home_stat','module' => 'users','cache' => 'true'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'admin_home_banners_block','module' => 'banners'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'admin_home_spam_block','module' => 'spam'), $this);?>
	</div>
</div>
<div class="clr"></div>
<?php echo tpl_function_block(array('name' => 'ad','module' => 'start'), $this); $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
