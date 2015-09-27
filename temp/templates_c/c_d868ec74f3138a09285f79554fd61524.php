<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-22 02:00:52 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_video_menu'), $this);?>
<div class="actions">&nbsp;</div>

<?php if ($this->_vars['settings']['use_shell_exec']): ?>
<div class="filter-form">
	<b><?php echo l('settings_os', 'video_uploads', '', 'text', array()); ?>:</b> <?php echo $this->_vars['settings']['used_system']; ?>
<br>
	<b><?php echo l('settings_ffmpeg_version', 'video_uploads', '', 'text', array()); ?>:</b> <?php if ($this->_vars['versions']['ffmpeg']):  echo $this->_vars['versions']['ffmpeg'];  else: ?><font class="error"><?php echo l('settings_not_found', 'video_uploads', '', 'text', array()); ?></font><?php endif; ?><br>
	<b><?php echo l('settings_mencoder_version', 'video_uploads', '', 'text', array()); ?>:</b> <?php if ($this->_vars['versions']['mencoder']):  echo $this->_vars['versions']['mencoder'];  else: ?><font class="error"><?php echo l('settings_not_found', 'video_uploads', '', 'text', array()); ?></font><?php endif; ?><br>
	<b><?php echo l('settings_mplayer_version', 'video_uploads', '', 'text', array()); ?>:</b> <?php if ($this->_vars['versions']['mplayer']):  echo $this->_vars['versions']['mplayer'];  else: ?><font class="error"><?php echo l('settings_not_found', 'video_uploads', '', 'text', array()); ?></font><?php endif; ?><br>
	<b><?php echo l('settings_flvtool2_version', 'video_uploads', '', 'text', array()); ?>:</b> <?php if ($this->_vars['versions']['flvtool2']):  echo $this->_vars['versions']['flvtool2'];  else: ?><font class="error"><?php echo l('settings_not_found', 'video_uploads', '', 'text', array()); ?></font><?php endif; ?><br>
	<?php if ($this->_vars['versions']['ffmpeg'] || $this->_vars['versions']['mencoder']): ?>
	<b><?php echo l('settings_convert_video_type', 'video_uploads', '', 'text', array()); ?>:</b> <?php echo $this->_vars['settings']['local_converting_video_type']; ?>
<br>
	<?php endif; ?>
	<?php if (! $this->_vars['settings']['use_local_converting_video']): ?>
	<br><?php echo l('error_unable_local_converting_video', 'video_uploads', '', 'text', array()); ?><br>
	<?php endif; ?>
	<?php if (! $this->_vars['settings']['use_local_converting_meta_data']): ?>
	<br><?php echo l('error_unable_local_converting_meta_data', 'video_uploads', '', 'text', array()); ?><br>
	<?php endif; ?>
	<?php if (! $this->_vars['settings']['use_local_converting_thumbs']): ?>
	<br><?php echo l('error_unable_local_converting_thumbs', 'video_uploads', '', 'text', array()); ?><br>
	<?php endif; ?>
	
	<?php if ($this->_vars['codecs']): ?>
	<br>
		<b><?php echo l('required_video_codecs', 'video_uploads', '', 'text', array()); ?>:</b><br>
		<?php if (is_array($this->_vars['codecs']['video_required']) and count((array)$this->_vars['codecs']['video_required'])): foreach ((array)$this->_vars['codecs']['video_required'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php echo $this->_vars['key']; ?>
 (<?php echo $this->_vars['item']['codec_description']; ?>
) - <?php if ($this->_vars['item']['installed']): ?><font class="success"><?php echo l('codec_installed', 'video_uploads', '', 'text', array()); ?></font><?php else: ?><font class="error"><?php echo l('codec_not_installed', 'video_uploads', '', 'text', array()); ?></font><?php endif; ?><br>
		<?php endforeach; endif; ?><br>

		<b><?php echo l('required_audio_codecs', 'video_uploads', '', 'text', array()); ?>:</b><br>
		<?php if (is_array($this->_vars['codecs']['audio_required']) and count((array)$this->_vars['codecs']['audio_required'])): foreach ((array)$this->_vars['codecs']['audio_required'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<?php echo $this->_vars['key']; ?>
 (<?php echo $this->_vars['item']['codec_description']; ?>
) - <?php if ($this->_vars['item']['installed']): ?><font class="success"><?php echo l('codec_installed', 'video_uploads', '', 'text', array()); ?></font><?php else: ?><font class="error"><?php echo l('codec_not_installed', 'video_uploads', '', 'text', array()); ?></font><?php endif; ?><br>
		<?php endforeach; endif; ?><br>
	<?php endif; ?>
	
	<?php if ($this->_vars['php_ini']): ?>
	<br>
	<b><?php echo l('php_ini_settings', 'video_uploads', '', 'text', array()); ?>:</b><br>
	<b>post_max_size:</b> <?php echo $this->_vars['php_ini']['post_max_size']; ?>
<br>
	<b>upload_max_filesize:</b> <?php echo $this->_vars['php_ini']['upload_max_filesize']; ?>
<br>
	<?php echo $this->_vars['php_ini']['max_size_notice']; ?>

	<?php endif; ?>
</div>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_system_settings', 'video_uploads', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_ffmpeg_path', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings']['ffmpeg_path']; ?>
" name="ffmpeg_path" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_mencoder_path', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings']['mencoder_path']; ?>
" name="mencoder_path" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_mplayer_path', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings']['mplayer_path']; ?>
" name="mplayer_path" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_flvtool2_path', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['settings']['flvtool2_path']; ?>
" name="flvtool2_path" class="middle"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<div class="btn gray fright"><div class="l"><input type="button" name="btn_reset" value="<?php echo l('reset_system_settings', 'video_uploads', '', 'button', array()); ?>" onclick="javascript: location.href='<?php echo $this->_vars['site_url']; ?>
admin/video_uploads/system_settings_reset';"></div></div>
	<div class="clr"></div>
</form>

<?php else: ?>

<div class="filter-form"><?php echo l("error_unable_shell_exec", 'video_uploads', '', 'text', array()); ?></div>

<?php endif; ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>