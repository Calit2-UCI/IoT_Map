<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:40:47 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?>: <?php echo l('header_'.$this->_vars['action'], 'users', '', 'text', array()); ?></h1>
	
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('"default"', '"users"'). "account_menu.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	
	<?php if ($this->_vars['action'] == 'services'): ?>
		<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'services_block')); tpl_block_capture(array('assign' => 'services_block'), null, $this); ob_start(); ?>
			<?php echo tpl_function_block(array('name' => 'services_buy_list','module' => 'services'), $this);?>
		<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'packages_block')); tpl_block_capture(array('assign' => 'packages_block'), null, $this); ob_start(); ?>
			<?php echo tpl_function_block(array('name' => 'packages_list','module' => 'packages'), $this);?>
		<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'user_services_block')); tpl_block_capture(array('assign' => 'user_services_block'), null, $this); ob_start(); ?>
			<?php echo tpl_function_block(array('name' => 'user_services_list','module' => 'services','id_user' => $this->_vars['user_id']), $this);?>
		<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
		<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'user_packages_block')); tpl_block_capture(array('assign' => 'user_packages_block'), null, $this); ob_start(); ?>
			<?php echo tpl_function_block(array('name' => 'user_packages_list','module' => 'packages'), $this);?>
		<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
		<div class="expandable">
			<div class="h2 expander">
				<?php echo l('header_services', 'users', '', 'text', array()); ?>
				<div class="fright">&nbsp;<a class="icon fa-chevron down icon-big edge hover zoom20"></a></div>
			</div>
			<div class="toggle hide">
				<?php echo $this->_vars['services_block']; ?>

				<?php echo $this->_vars['packages_block']; ?>

			</div>
		</div>
		<div class="expandable">
			<div class="h2 expander">
				<?php echo l('header_my_services', 'users', '', 'text', array()); ?>
				<div class="fright">&nbsp;<a class="icon fa-chevron down icon-big edge hover zoom20"></a></div>
			</div>
			<div class="toggle hide">
				<?php echo $this->_vars['user_services_block']; ?>

				<?php echo $this->_vars['user_packages_block']; ?>

			</div>
		</div>
	<?php elseif ($this->_vars['action'] == 'memberships'): ?>
		<div class="memberships ptb20">
			<?php echo tpl_function_block(array('name' => 'memberships_list','module' => 'memberships'), $this);?>
		</div>
	<?php elseif ($this->_vars['action'] == 'update'): ?>
		<?php echo tpl_function_helper(array('func_name' => 'update_account_block','module' => 'users_payments'), $this);?>
	<?php elseif ($this->_vars['action'] == 'payments_history'): ?>
		<div><?php echo tpl_function_block(array('name' => 'user_payments_history','module' => 'payments','id_user' => $this->_vars['user_id'],'page' => $this->_vars['page'],'base_url' => $this->_vars['base_url']), $this);?></div>
	<?php elseif ($this->_vars['action'] == 'banners'): ?>
		<div><?php echo tpl_function_block(array('name' => 'my_banners','module' => 'banners','page' => $this->_vars['page'],'base_url' => $this->_vars['base_url']), $this);?></div>
        <?php elseif ($this->_vars['action'] == 'send_money'): ?>
		<?php echo tpl_function_helper(array('func_name' => 'send_money_block','module' => 'send_money'), $this);?>
	<?php endif; ?>
</div>
<div class="clr"></div>
<?php echo '
<script>
	$(\'.expander\').bind(\'click\', function(){
		var target = $(this).parents(\'.expandable\').find(\'.toggle\');
		var icon = $(this).find(\'.icon\');
		if (target.is(\':hidden\')){
			icon.removeClass(\'down\');
			icon.addClass(\'up\');
			target.slideDown();
		} else {
			icon.removeClass(\'up\');
			icon.addClass(\'down\');
			target.slideUp();
		}
	});
</script>
'; ?>

<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>