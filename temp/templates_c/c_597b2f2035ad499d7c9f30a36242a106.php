<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:01:46 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_video_menu'), $this);?>
<div class="actions">&nbsp;</div>

<?php if (! $this->_vars['settings']['use_youtube_converting']): ?>
<div class="filter-form"><?php echo l('error_unable_youtube_converting_video', 'video_uploads', '', 'text', array()); ?></div>
<?php endif; ?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_youtube_settings', 'video_uploads', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_youtube_login', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings']['youtube_converting_login']; ?>
" name="youtube_converting_login" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_youtube_password', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="password" value="<?php echo $this->_vars['settings']['youtube_converting_password']; ?>
" name="youtube_converting_password" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_youtube_developer_key', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings']['youtube_converting_developer_key']; ?>
" name="youtube_converting_developer_key" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_youtube_source', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings']['youtube_converting_source']; ?>
" name="youtube_converting_source" class="middle"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<div class="btn gray fright"><div class="l"><input type="button" name="btn_reset" value="<?php echo l('check_youtube_settings', 'video_uploads', '', 'button', array()); ?>" onclick="javascript: location.href='<?php echo $this->_vars['site_url']; ?>
admin/video_uploads/youtube_settings_check';"></div></div>

</form>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>