<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.bytes_format.php');
$this->register_modifier("bytes_format", "tpl_modifier_bytes_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:32:02 Pacific Daylight Time */ ?>

<div class="content-block load_content">
	<h1><?php echo l('field_user_logo', 'users', '', 'text', array()); ?></h1>
	<div class="m20 oh">
		<div class="edit-media-content" id="image_content_avatar">
			<div class="pos-rel">
				<?php if ($this->_vars['avatar_data']['is_owner']): ?>
					<link rel="stylesheet" type="text/css" href="<?php echo $this->_vars['site_url'];  echo $this->_vars['js_folder']; ?>
jquery.imgareaselect/css/imgareaselect-default.css"></link>
					<div class="photo-edit" id="photo-edit">
						<div id="photo_source_recrop_box" class="photo-source-box lh0">
							<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['avatar_data']['user'])));
 ?>
							<?php if ($this->_vars['avatar_data']['user']['user_logo_moderation']): ?>
							<img id="photo_source_recrop" src="<?php echo $this->_vars['avatar_data']['user']['media']['user_logo_moderation']['file_url']; ?>
?<?php echo $this->_run_modifier('', 'time', 'PHP', 1); ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
">
							<?php else: ?>
							<img id="photo_source_recrop" src="<?php echo $this->_vars['avatar_data']['user']['media']['user_logo']['file_url']; ?>
?<?php echo $this->_run_modifier('', 'time', 'PHP', 1); ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
">
							<?php endif; ?>
						</div>
						<div class="ptb5 oh tab-submenu" id="media_menu">
							<ul class="fleft" id="photo_sizes" data-area="recrop"></ul>
							<ul class="fright">
								<li class="active"><span data-section="view"><?php echo l("view", "media", '', 'text', array()); ?></span></li>
								<li><span data-section="recrop"><?php echo l("recrop", "media", '', 'text', array()); ?></span></li>
							</ul>
						</div>
					</div>
					
					<form id="upload_avatar" action="" method="post" enctype="multipart/form-data" name="upload_video">
						
						<div id="load_avatar" class="hide">
							
							<div class="pb5">
								<div id="dnd_upload_avatar" class="drag">
									<div id="dndfiles_avatar" class="drag-area">
										<div class="drag"><?php echo l('drag_photos', 'media', '', 'text', array()); ?></div>
									</div>
								</div>
								<div>
									<div class="upload-btn">
										<span data-role="filebutton">
											<s><?php echo l('btn_choose_file', 'start', '', 'text', array()); ?></s>
											<input type="file" name="avatar" id="file_avatar" />
										</span>
										&nbsp;(<?php echo l('max', 'start', '', 'text', array()); ?> <?php echo $this->_run_modifier($this->_vars['avatar_data']['upload_config']['max_size'], 'bytes_format', 'plugin', 1); ?>
)
									</div>
									
									&nbsp;<span id="attach_error_avatar"></span>
									<div id="attach_warning_avatar"></div>
								</div>
							</div>
							
							<div>
								<input type="button" value="<?php echo l('btn_upload', 'start', '', 'button', array()); ?>" name="btn_upload" id="btn_upload_avatar">
								<div class="fright"><input class="hide" type="button" value="<?php echo l('wc_btn_use_webcamera', 'users', '', 'button', array()); ?>" name="btn_use_webcamera" id="btn_use_webcamera"></div>
							</div>
							
						</div>
						
						<br>
						
						<div><input type="button" value="<?php echo l('wc_btn_change_photo', 'users', '', 'button', array()); ?>" name="btn_change_photo" id="btn_change_photo"></div>
						
					</form>
					
					<form name="avatar" class="hide" action="" method="post" enctype="multipart/form-data" id="stuff">
						<div class="video_capture fleft">
							<div id="allow"><?php echo l('wc_get_user_camera', 'users', '', 'text', array()); ?></div>
							<div class="">
								<video id="video" width="320" height="240" autoplay="autoplay" ></video>
								<canvas id="canvas" class="" width="0" height="0"></canvas>
							</div>
						</div>
						
						<input type="file" name="avatar" id="web_avatar" class="hide" />
						
						<div class="clr"></div>
						
						<input id="take_picture" type="button" value="<?php echo l('wc_take_picture', 'users', '', 'button', array()); ?>" />
						<input id="repicture" type="button" value="<?php echo l('wc_repicture', 'users', '', 'button', array()); ?>" class="hide" />
						<input id="save_picture" type="button" value="<?php echo l('wc_save_picture', 'users', '', 'button', array()); ?>" class="hide" />
						<div class="fright"><a href="#" class="fright hide" id="btn_cancel_webcamera"><?php echo l('btn_close', 'start', '', 'text', array()); ?></a></div>
					</form>
					
					<script><?php echo '
						$(function(){
							loadScripts(
								["';  echo tpl_function_js(array('file' => 'jquery.imgareaselect/jquery.imgareaselect.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('file' => 'uploader.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('file' => 'canvas-to-blob.min.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('file' => 'webcamera.js','return' => 'path'), $this); echo '"],
								function(){
									var upload_config = ';  echo tpl_function_json_encode(array('data' => $this->_vars['avatar_data']['upload_config']), $this); echo ';
									json_encode_data = ';  echo tpl_function_json_encode(array('data' => $this->_vars['avatar_data']['selections']), $this); echo ';
									user_avatar_selections = json_encode_data;
									avatar_width = json_encode_data.grand.width;
									avatar_height = json_encode_data.grand.height;
									
									user_avatar.uninit_imageareaselect();
									for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
										user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
									}
									';  if ($this->_vars['avatar_data']['have_avatar']): ?>
									user_avatar.init_imageareaselect();
									<?php endif;  echo '

									avatar_uploader = new uploader({
										siteUrl: site_url,
										uploadUrl: \'users/upload_avatar\',
										zoneId: \'dndfiles_avatar\',
										fileId: \'file_avatar\',
										formId: \'upload_avatar\',
										filebarId: \'filebar_avatar\',
										sendType: \'file\',
										sendId: \'btn_upload_avatar\',
	
										multiFile: false,
										messageId: \'attach_error_avatar\',
										warningId: \'attach_warning_avatar\',
										maxFileSize: upload_config.max_size,
										mimeType: upload_config.allowed_mimes,
										createThumb: true,
										thumbWidth: 200,
										thumbHeight: 200,
										thumbCrop: true,
										thumbJpeg: false,
										thumbBg: \'transparent\',
										fileListInZone: true,
										cbOnUpload: function(name, data){
											if(data.logo && !$.isEmptyObject(data.logo)){
												$(\'#image_content_avatar\').find(\'.photo-edit\').show();
												$(\'#photo_source_recrop\').attr(\'src\', \'\');
												user_avatar.uninit_imageareaselect();
												for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
													user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
												}
												$(\'#photo_source_recrop\').attr(\'src\', data.logo.file_url+\'?\'+new Date().getTime());
												$(\'#user_photo > img\').attr(\'src\', data.logo.thumbs.middle+\'?\'+new Date().getTime());
												$(\'img[id^=avatar_\'+id_user+\']\').attr(\'src\', data.logo.thumbs.small+\'?\'+new Date().getTime());
												user_avatar.init_imageareaselect();
												var images = $(\'img\');
												if(data.old_logo && !$.isEmptyObject(data.old_logo)){
													for(var i in data.old_logo.thumbs) if(data.old_logo.thumbs.hasOwnProperty(i)){
														images.filter(\'[src^="\'+data.old_logo.thumbs[i]+\'"]\').attr(\'src\', data.logo.thumbs[i]+\'?\'+new Date().getTime());
													}
												}
												if(data.old_logo_moderation && !$.isEmptyObject(data.old_logo_moderation)){
													for(var i in data.old_logo_moderation.thumbs) if(data.old_logo_moderation.thumbs.hasOwnProperty(i)){
														images.filter(\'[src^="\'+data.old_logo_moderation.thumbs[i]+\'"]\').attr(\'src\', data.logo.thumbs[i]+\'?\'+new Date().getTime());
													}
												}
											}
										},
										cbOnComplete: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, \'error\');
											}
										},
										ailedjqueryFormPluginUrl: "';  echo tpl_function_js(array('file' => 'jquery.form.min.js','return' => 'path'), $this); echo '"
									});
									
									avatar_web_uploader = new uploader({
										siteUrl: site_url,
										uploadUrl: \'users/upload_avatar\',					
										fileId: \'web_avatar\',
										formId: \'upload_avatar\',
										sendType: \'file\',
										sendId: \'save_picture\',
										multiFile: false,
										messageId: \'attach_error_avatar\',
										warningId: \'attach_warning_avatar\',
										maxFileSize: upload_config.max_size,
										mimeType: upload_config.allowed_mimes,
										createThumb: true,
										thumbWidth: 200,
										thumbHeight: 200,
										thumbCrop: true,
										thumbJpeg: false,
										thumbBg: \'transparent\',
										fileListInZone: false,
										cbOnUpload: function(name, data){
											if(data.logo && !$.isEmptyObject(data.logo)){
												$(\'#stuff, #btn_cancel_webcamera\').hide(300);
												$(\'#btn_change_photo\').show();
												$(\'#image_content_avatar\').find(\'.photo-edit\').show();
												$(\'#photo_source_recrop\').attr(\'src\', \'\');
												user_avatar.uninit_imageareaselect();
												for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
													user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
												}
												$(\'#photo_source_recrop\').attr(\'src\', data.logo.file_url+\'?\'+new Date().getTime());
												$(\'#user_photo > img\').attr(\'src\', data.logo.thumbs.middle+\'?\'+new Date().getTime());
												$(\'img[id^=avatar_\'+id_user+\']\').attr(\'src\', data.logo.thumbs.small+\'?\'+new Date().getTime());
												user_avatar.init_imageareaselect();
												var images = $(\'img\');
												if(data.old_logo && !$.isEmptyObject(data.old_logo)){
													for(var i in data.old_logo.thumbs) if(data.old_logo.thumbs.hasOwnProperty(i)){
														images.filter(\'[src^="\'+data.old_logo.thumbs[i]+\'"]\').attr(\'src\', data.logo.thumbs[i]+\'?\'+new Date().getTime());
													}
												}
												if(data.old_logo_moderation && !$.isEmptyObject(data.old_logo_moderation)){
													for(var i in data.old_logo_moderation.thumbs) if(data.old_logo_moderation.thumbs.hasOwnProperty(i)){
														images.filter(\'[src^="\'+data.old_logo_moderation.thumbs[i]+\'"]\').attr(\'src\', data.logo.thumbs[i]+\'?\'+new Date().getTime());
													}
												}
											}
										},
										cbOnComplete: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, \'error\');
											}
										},
										jqueryFormPluginUrl: "';  echo tpl_function_js(array('file' => 'jquery.form.min.js','return' => 'path'), $this); echo '"
									});
						
									avatar_web_camera = new webcamera({
										wc_width: avatar_width,
										wc_height: avatar_height,
										wc_alert: "';  echo l('wc_alert', 'users', '', 'text', array());  echo '",
										wc_load_avatar: \'load_avatar\'
									});
									
								},
								[\'user_avatar\', \'avatar_uploader\', \'avatar_web_uploader\', \'avatar_web_camera\'],
								{async: false}
							);
						});
					</script>'; ?>

				<?php else: ?>
					<div class="center lh0">
						<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['avatar_data']['user'])));
 ?>
						<img src="<?php echo $this->_vars['avatar_data']['user']['media']['user_logo']['thumbs']['grand']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
">
					</div>
				<?php endif; ?>
				<div class="mt20">
					<?php echo tpl_function_block(array('name' => 'comments_form','module' => 'comments','gid' => user_avatar,'id_obj' => $this->_vars['avatar_data']['user']['id'],'hidden' => 0,'count' => $this->_vars['avatar_data']['user']['logo_comments_count']), $this);?>
				</div>
			</div>
		</div>
	</div>
</div>

