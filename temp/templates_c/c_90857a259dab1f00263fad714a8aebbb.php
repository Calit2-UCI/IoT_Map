<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-09 00:46:41 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header"><?php echo l('admin_header_settings_url', 'mobile', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('admin_settings_android_url', 'mobile', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="url" name="android_url" value="<?php echo $this->_vars['mobile_settings']['android_url']; ?>
" class="long" id="android_url">
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('admin_settings_ios_url', 'mobile', '', 'text', array()); ?>:</div>
			<div class="v">
				<input type="url" name="ios_url" value="<?php echo $this->_vars['mobile_settings']['ios_url']; ?>
" class="long" id="ios_url">
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/add_ons_items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<script><?php echo '
	$("div.row:odd").addClass("zebra");
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>