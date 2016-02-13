<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-01-07 00:09:49 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_js(array('file' => 'easyTooltip.min.js'), $this);?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_application_change', 'social_networking', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_app_key', 'social_networking', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="text" value="<?php echo $this->_vars['data']['app_key']; ?>
" name="app_key" class="middle">
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_app_secret', 'social_networking', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['app_secret']; ?>
" name="app_secret" class="middle"></div>
		</div>

		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/social_networking/services/"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</div>
</form>
<script type='text/javascript'>
	<?php echo '
	$(function(){
		$(".tooltip").each(function(){
			$(this).easyTooltip({
				useElement: \'tt_\'+$(this).attr(\'id\')
			});
		});
	});
	'; ?>

</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>