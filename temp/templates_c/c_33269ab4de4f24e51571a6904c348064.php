<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-01 08:10:07 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script  type='text/javascript' src="<?php echo $this->_vars['site_root'];  echo $this->_vars['js_folder']; ?>
easyTooltip.min.js"></script>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_config_change', 'video_uploads', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_name', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_gid', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><b><?php echo $this->_vars['data']['gid']; ?>
</b></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_max_size', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['max_size']; ?>
" name="max_size" class="short"> b <i><?php echo l('int_unlimit_condition', 'video_uploads', '', 'text', array()); ?></i></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_file_formats', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<?php if (is_array($this->_vars['formats']) and count((array)$this->_vars['formats'])): foreach ((array)$this->_vars['formats'] as $this->_vars['item']): ?>
					<div class="column-block"><input type="checkbox" name="file_formats[]" value="<?php echo $this->_vars['item']; ?>
" <?php if ($this->_vars['data']['enable_formats'][$this->_vars['item']]): ?>checked<?php endif; ?> id="frm_<?php echo $this->_vars['item']; ?>
"> <label for="frm_<?php echo $this->_vars['item']; ?>
"><?php echo $this->_vars['item']; ?>
</label></div>
				<?php endforeach; endif; ?>
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_upload_type', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v">

				<input type="radio" value="local" name="upload_type" id="upload_type_local" <?php if ($this->_vars['data']['upload_type'] == 'local'): ?>checked<?php endif; ?>>
				<label for="upload_type_local"><?php echo l('field_upload_type_local', 'video_uploads', '', 'text', array()); ?></label> &nbsp;&nbsp;&nbsp;
				<input type="radio" value="youtube" name="upload_type" id="upload_type_youtube" <?php if ($this->_vars['data']['upload_type'] == 'youtube'): ?>checked<?php endif; ?> <?php if (! $this->_vars['settings']['use_youtube_converting']): ?>disabled<?php endif; ?>>
				<label for="upload_type_youtube"><?php echo l('field_upload_type_youtube', 'video_uploads', '', 'text', array()); ?></label>
				<?php if (! $this->_vars['settings']['use_youtube_converting']): ?><i>(<?php echo l('field_upload_type_youtube_note', 'video_uploads', '', 'text', array()); ?>)</i><?php endif; ?>
			</div>
		</div>

		<div id="local_settings" <?php if ($this->_vars['data']['upload_type'] != 'local'): ?>class="hide"<?php endif; ?>>
			<div class="row header"><?php echo l('admin_header_config_local_settings', 'video_uploads', '', 'text', array()); ?></div>
			<div class="row">
				<div class="h"><?php echo l('field_local_enable_encoding', 'video_uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="checkbox" value="1" name="use_convert" id="use_convert" <?php if ($this->_vars['data']['use_convert'] == 1): ?>checked<?php endif; ?> <?php if (! $this->_vars['settings']['use_local_converting_video']): ?>disabled<?php endif; ?>>
				<?php if (! $this->_vars['settings']['use_local_converting_video']): ?><i>(<?php echo l('field_upload_type_local_note', 'video_uploads', '', 'text', array()); ?>)</i><?php endif; ?>
				</div>
			</div>

			<div id="local_settings_params" <?php if ($this->_vars['data']['use_convert'] != 1 || ! $this->_vars['settings']['use_local_converting_video']): ?>class="hide"<?php endif; ?>>
				<div class="row zebra">
					<div class="h"><?php echo l('field_local_width', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['local_settings']['width']; ?>
" name="local_settings[width]" class="short"></div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_local_height', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['local_settings']['height']; ?>
" name="local_settings[height]" class="short"></div>
				</div>
				<div class="row zebra">
					<div class="h"><?php echo l('field_local_audio_freq', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['local_settings']['audio_freq']; ?>
" name="local_settings[audio_freq]" class="short"></div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_local_audio_brate', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['local_settings']['audio_brate']; ?>
" name="local_settings[audio_brate]" class="short"></div>
				</div>
				<div class="row zebra">
					<div class="h"><?php echo l('field_local_video_brate', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['local_settings']['video_brate']; ?>
" name="local_settings[video_brate]" class="short"></div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_local_video_rate', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['local_settings']['video_rate']; ?>
" name="local_settings[video_rate]" class="short"></div>
				</div>
			</div>
		</div>

		<div id="youtube_settings" <?php if ($this->_vars['data']['upload_type'] != 'youtube'): ?>class="hide"<?php endif; ?>>
			<div class="row header"><?php echo l('admin_header_config_youtube_settings', 'video_uploads', '', 'text', array()); ?></div>
				<div class="row zebra">
					<div class="h"><?php echo l('field_youtube_width', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['youtube_settings']['width']; ?>
" name="youtube_settings[width]" class="short"></div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_youtube_height', 'video_uploads', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" value="<?php echo $this->_vars['data']['youtube_settings']['height']; ?>
" name="youtube_settings[height]" class="short"></div>
				</div>
		</div>


		<div class="row header"><?php echo l('admin_header_config_thumbs_settings', 'video_uploads', '', 'text', array()); ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_enable_thumbs', 'video_uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="checkbox" id="use_thumbs" name="use_thumbs" value="1" <?php if ($this->_vars['data']['use_thumbs'] == '1'): ?>checked<?php endif; ?> <?php if (! $this->_vars['settings']['use_local_converting_thumbs'] && $this->_vars['data']['upload_type'] == 'local'): ?>disabled<?php endif; ?>>
				<span id="use_thumbs_info" <?php if (! ( ! $this->_vars['settings']['use_local_converting_thumbs'] && $this->_vars['data']['upload_type'] == 'local' )): ?>class="hide"<?php endif; ?>><i>(<?php echo l('field_use_thumbs_note', 'video_uploads', '', 'text', array()); ?>)</i></span>
			</div>
		</div>
		<div id="images_settings" <?php if (( ! $this->_vars['settings']['use_local_converting_thumbs'] && $this->_vars['data']['upload_type'] == 'local' ) || $this->_vars['data']['use_thumbs'] != '1'): ?>class="hide"<?php endif; ?>>
			<div class="row">
				<div class="h"><?php echo l('field_thumbs', 'video_uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<a href="#" id="add_thumb_link"><?php echo l('link_add_thumb', 'video_uploads', '', 'text', array()); ?></a><br>

					<div class="column-block header"><?php echo l('field_thumb_gid', 'video_uploads', '', 'text', array()); ?></div>
					<div class="column-block header"><?php echo l('field_thumb_width', 'video_uploads', '', 'text', array()); ?></div>
					<div class="column-block header"><?php echo l('field_thumb_height', 'video_uploads', '', 'text', array()); ?></div>

					<div class="clr"></div>
					<?php $this->assign('thumbs_count', 0); ?>
					<ul id="thumbs_list" class="thumbs-list">
					<?php if (is_array($this->_vars['data']['thumbs_settings']) and count((array)$this->_vars['data']['thumbs_settings'])): foreach ((array)$this->_vars['data']['thumbs_settings'] as $this->_vars['key'] => $this->_vars['item']): ?>
					<li>
						<div class="column-block"><input type="text" name="thumbs_settings[<?php echo $this->_vars['key']; ?>
][gid]" value="<?php echo $this->_vars['item']['gid']; ?>
" class="short"></div>
						<div class="column-block"><input type="text" name="thumbs_settings[<?php echo $this->_vars['key']; ?>
][width]" value="<?php echo $this->_vars['item']['width']; ?>
" class="short"> px</div>
						<div class="column-block"><input type="text" name="thumbs_settings[<?php echo $this->_vars['key']; ?>
][height]" value="<?php echo $this->_vars['item']['height']; ?>
" class="short"> px</div>

						<div class="column-block"><a href="#" class="delete_thumb"><?php echo l('link_delete_thumb', 'video_uploads', '', 'text', array()); ?></a></div>
					</li>
					<?php $this->assign('thumbs_count', $this->_vars['key']); ?>
					<?php endforeach; endif; ?>
					<li id="thumb_example" class="hide">
						<div class="column-block"><input type="text" name="thumbs_settings[_new_key_][gid]" value="" class="short"></div>
						<div class="column-block"><input type="text" name="thumbs_settings[_new_key_][width]" value="" class="short"> px</div>
						<div class="column-block"><input type="text" name="thumbs_settings[_new_key_][height]" value="" class="short"> px</div>

						<div class="column-block"><a href="#" class="delete_thumb"><?php echo l('link_delete_thumb', 'video_uploads', '', 'text', array()); ?></a></div>
					</li>
					</ul>
				</div>
			</div>
			<div class="row zebra">
				<div class="h"><?php echo l('field_default_img', 'video_uploads', '', 'text', array()); ?>: </div>
				<div class="v"><input type="file" value="" name="default_img" class="file">
				<?php if ($this->_vars['data']['default_img_data']): ?><br><a href="<?php echo $this->_vars['data']['default_img_data']['file_url']; ?>
" target="blank"><?php echo l('link_view_default_image', 'video_uploads', '', 'text', array()); ?></a><?php endif; ?>
				</div>
			</div>
			<?php if ($this->_vars['data']['default_img']): ?>
			<div class="row">
				<div class="h"><?php echo l('field_default_img_delete', 'video_uploads', '', 'text', array()); ?>: </div>
				<div class="v"><input type="checkbox" value="1" name="default_img_delete"></div>
			</div>
			<?php endif; ?>
		</div>

		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/video_uploads/index"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</div>
</form>
<script type='text/javascript'>
	var is_local_thumbs_allowed = parseInt('<?php echo $this->_vars['settings']['use_local_converting_thumbs']; ?>
');
	var thumbs_count = parseInt('<?php echo $this->_vars['thumbs_count']; ?>
');
<?php echo '
	$(function(){
		$(\'input[name=upload_type]\').bind(\'change\', function(){ change_type($(this).val());});
		$(\'#use_convert\').bind(\'change\', function(){
			if($(this).is(\':checked\')){
				$(\'#local_settings_params\').show();
			}else{
				$(\'#local_settings_params\').hide();
			}
		});
		$(\'#use_thumbs\').bind(\'change\', function(){
			if($(this).is(\':checked\')){
				$(\'#images_settings\').show();
			}else{
				$(\'#images_settings\').hide();
			}
		});

		$(\'#add_thumb_link\').bind(\'click\', function(){
			var content = $(\'#thumb_example\').html();
			thumbs_count++;
			content = content.replace(/_new_key_/g, thumbs_count);
			$(\'#thumbs_list\').append(\'<li>\'+content+\'</li>\');
			return false;
		});

		$(\'#thumbs_list\').delegate(\'a.delete_thumb\', \'click\', function(){
			$(this).parent().parent().remove();
			return false;
		});
	});

	function change_type(upload_type){
		if($(\'#use_thumbs\').is(\':checked\')){
			$(\'#images_settings\').show();
		}
		$(\'#use_thumbs\').removeAttr(\'disabled\');
		$(\'#use_thumbs_info\').hide();

		if(upload_type == \'local\'){
			$(\'#local_settings\').show();
			$(\'#youtube_settings\').hide();

			if(!is_local_thumbs_allowed){
				$(\'#use_thumbs\').attr(\'disabled\', \'disabled\');
				$(\'#use_thumbs\').prop(\'checked\', false);
				$(\'#use_thumbs_info\').show();
				$(\'#images_settings\').hide();
			}
		}

		if(upload_type == \'youtube\'){
			$(\'#local_settings\').hide();
			$(\'#youtube_settings\').show();
		}

	}
'; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>