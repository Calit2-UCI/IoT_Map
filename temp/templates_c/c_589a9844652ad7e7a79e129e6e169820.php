<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:07:06 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_im_menu'), $this); $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="actions">&nbsp;</div>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header"><?php echo l('im_settings_module', 'im', '', 'text', array()); ?></div>
		
		<div class="row">
			<div class="h"><?php echo l('im_status_field', 'im', '', 'text', array()); ?>:</div>
			<div class="v">

				<input type="checkbox" name="status" value="1" <?php if ($this->_vars['settings_data']['status']): ?>checked<?php endif; ?> class="short" id="im_status">
			
			</div>
		</div>
		
		<div class="row">
			<div class="h"><?php echo l('im_message_max_chars_field', 'im', '', 'text', array()); ?>:</div>
			<div class="v">

				<input type="text" name="message_max_chars" value="<?php echo $this->_vars['settings_data']['message_max_chars']; ?>
" class="short" id="message_max_chars">
				
			</div>
		</div>
		
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/add_ons_items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
</div>
<script><?php echo '
	$("div.row:odd").addClass("zebra");
'; ?>
</script>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
