<div class="content-block load_content">
	<h1>{l i='field_user_logo' gid='users'}</h1>
	<div class="m20 oh">
		<div class="edit-media-content" id="image_content_avatar">
			<div class="pos-rel">
				{if $avatar_data.is_owner}
					<link rel="stylesheet" type="text/css" href="{$site_url}{$js_folder}jquery.imgareaselect/css/imgareaselect-default.css"></link>
					<div class="photo-edit" id="photo-edit">
						<div id="photo_source_recrop_box" class="photo-source-box lh0">
							{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$avatar_data.user}
							{if $avatar_data.user.user_logo_moderation}
							<img id="photo_source_recrop" src="{$avatar_data.user.media.user_logo_moderation.file_url}?{''|time}" alt="{$text_user_logo}" title="{$text_user_logo}">
							{else}
							<img id="photo_source_recrop" src="{$avatar_data.user.media.user_logo.file_url}?{''|time}" alt="{$text_user_logo}" title="{$text_user_logo}">
							{/if}
						</div>
						<div class="ptb5 oh tab-submenu" id="media_menu">
							<ul class="fleft" id="photo_sizes" data-area="recrop"></ul>
							<ul class="fright">
								<li class="active"><span data-section="view">{l i="view" gid="media"}</span></li>
								<li><span data-section="recrop">{l i="recrop" gid="media"}</span></li>
							</ul>
						</div>
					</div>
					
					<form id="upload_avatar" action="" method="post" enctype="multipart/form-data" name="upload_video">
						
						<div id="load_avatar" class="hide">
							
							<div class="pb5">
								<div id="dnd_upload_avatar" class="drag">
									<div id="dndfiles_avatar" class="drag-area">
										<div class="drag">{l i='drag_photos' gid='media'}</div>
									</div>
								</div>
								<div>
									<div class="upload-btn">
										<span data-role="filebutton">
											<s>{l i='btn_choose_file' gid='start'}</s>
											<input type="file" name="avatar" id="file_avatar" />
										</span>
										&nbsp;({l i='max' gid='start'} {$avatar_data.upload_config.max_size|bytes_format})
									</div>
									
									&nbsp;<span id="attach_error_avatar"></span>
									<div id="attach_warning_avatar"></div>
								</div>
							</div>
							
							<div>
								<input type="button" value="{l i='btn_upload' gid='start' type='button'}" name="btn_upload" id="btn_upload_avatar">
								<div class="fright"><input class="hide" type="button" value="{l i='wc_btn_use_webcamera' gid='users' type='button'}" name="btn_use_webcamera" id="btn_use_webcamera"></div>
							</div>
							
						</div>
						
						<br>
						
						<div><input type="button" value="{l i='wc_btn_change_photo' gid='users' type='button'}" name="btn_change_photo" id="btn_change_photo"></div>
						
					</form>
					
					<form name="avatar" class="hide" action="" method="post" enctype="multipart/form-data" id="stuff">
						<div class="video_capture fleft">
							<div id="allow">{l i='wc_get_user_camera' gid='users'}</div>
							<div class="">
								<video id="video" width="320" height="240" autoplay="autoplay" ></video>
								<canvas id="canvas" class="" width="0" height="0"></canvas>
							</div>
						</div>
						
						<input type="file" name="avatar" id="web_avatar" class="hide" />
						
						<div class="clr"></div>
						
						<input id="take_picture" type="button" value="{l i='wc_take_picture' gid='users' type='button'}" />
						<input id="repicture" type="button" value="{l i='wc_repicture' gid='users' type='button'}" class="hide" />
						<input id="save_picture" type="button" value="{l i='wc_save_picture' gid='users' type='button'}" class="hide" />
						<div class="fright"><a href="#" class="fright hide" id="btn_cancel_webcamera">{l i='btn_close' gid='start'}</a></div>
					</form>
					
					<script>{literal}
						$(function(){
							loadScripts(
								["{/literal}{js file='jquery.imgareaselect/jquery.imgareaselect.js' return='path'}{literal}", "{/literal}{js file='uploader.js' return='path'}{literal}", "{/literal}{js file='canvas-to-blob.min.js' return='path'}{literal}", "{/literal}{js file='webcamera.js' return='path'}{literal}"],
								function(){
									var upload_config = {/literal}{json_encode data=$avatar_data.upload_config}{literal};
									json_encode_data = {/literal}{json_encode data=$avatar_data.selections}{literal};
									user_avatar_selections = json_encode_data;
									avatar_width = json_encode_data.grand.width;
									avatar_height = json_encode_data.grand.height;
									
									user_avatar.uninit_imageareaselect();
									for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
										user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
									}
									{/literal}{if $avatar_data.have_avatar}
									user_avatar.init_imageareaselect();
									{/if}{literal}

									avatar_uploader = new uploader({
										siteUrl: site_url,
										uploadUrl: 'users/upload_avatar',
										zoneId: 'dndfiles_avatar',
										fileId: 'file_avatar',
										formId: 'upload_avatar',
										filebarId: 'filebar_avatar',
										sendType: 'file',
										sendId: 'btn_upload_avatar',
	
										multiFile: false,
										messageId: 'attach_error_avatar',
										warningId: 'attach_warning_avatar',
										maxFileSize: upload_config.max_size,
										mimeType: upload_config.allowed_mimes,
										createThumb: true,
										thumbWidth: 200,
										thumbHeight: 200,
										thumbCrop: true,
										thumbJpeg: false,
										thumbBg: 'transparent',
										fileListInZone: true,
										cbOnUpload: function(name, data){
											if(data.logo && !$.isEmptyObject(data.logo)){
												$('#image_content_avatar').find('.photo-edit').show();
												$('#photo_source_recrop').attr('src', '');
												user_avatar.uninit_imageareaselect();
												for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
													user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
												}
												$('#photo_source_recrop').attr('src', data.logo.file_url+'?'+new Date().getTime());
												$('#user_photo > img').attr('src', data.logo.thumbs.middle+'?'+new Date().getTime());
												$('img[id^=avatar_'+id_user+']').attr('src', data.logo.thumbs.small+'?'+new Date().getTime());
												user_avatar.init_imageareaselect();
												var images = $('img');
												if(data.old_logo && !$.isEmptyObject(data.old_logo)){
													for(var i in data.old_logo.thumbs) if(data.old_logo.thumbs.hasOwnProperty(i)){
														images.filter('[src^="'+data.old_logo.thumbs[i]+'"]').attr('src', data.logo.thumbs[i]+'?'+new Date().getTime());
													}
												}
												if(data.old_logo_moderation && !$.isEmptyObject(data.old_logo_moderation)){
													for(var i in data.old_logo_moderation.thumbs) if(data.old_logo_moderation.thumbs.hasOwnProperty(i)){
														images.filter('[src^="'+data.old_logo_moderation.thumbs[i]+'"]').attr('src', data.logo.thumbs[i]+'?'+new Date().getTime());
													}
												}
											}
										},
										cbOnComplete: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, 'error');
											}
										},
										ailedjqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
									});
									
									avatar_web_uploader = new uploader({
										siteUrl: site_url,
										uploadUrl: 'users/upload_avatar',					
										fileId: 'web_avatar',
										formId: 'upload_avatar',
										sendType: 'file',
										sendId: 'save_picture',
										multiFile: false,
										messageId: 'attach_error_avatar',
										warningId: 'attach_warning_avatar',
										maxFileSize: upload_config.max_size,
										mimeType: upload_config.allowed_mimes,
										createThumb: true,
										thumbWidth: 200,
										thumbHeight: 200,
										thumbCrop: true,
										thumbJpeg: false,
										thumbBg: 'transparent',
										fileListInZone: false,
										cbOnUpload: function(name, data){
											if(data.logo && !$.isEmptyObject(data.logo)){
												$('#stuff, #btn_cancel_webcamera').hide(300);
												$('#btn_change_photo').show();
												$('#image_content_avatar').find('.photo-edit').show();
												$('#photo_source_recrop').attr('src', '');
												user_avatar.uninit_imageareaselect();
												for(var i in user_avatar_selections) if(user_avatar_selections.hasOwnProperty(i)){
													user_avatar.add_selection(i, 0, 0, parseInt(user_avatar_selections[i].width), parseInt(user_avatar_selections[i].height));
												}
												$('#photo_source_recrop').attr('src', data.logo.file_url+'?'+new Date().getTime());
												$('#user_photo > img').attr('src', data.logo.thumbs.middle+'?'+new Date().getTime());
												$('img[id^=avatar_'+id_user+']').attr('src', data.logo.thumbs.small+'?'+new Date().getTime());
												user_avatar.init_imageareaselect();
												var images = $('img');
												if(data.old_logo && !$.isEmptyObject(data.old_logo)){
													for(var i in data.old_logo.thumbs) if(data.old_logo.thumbs.hasOwnProperty(i)){
														images.filter('[src^="'+data.old_logo.thumbs[i]+'"]').attr('src', data.logo.thumbs[i]+'?'+new Date().getTime());
													}
												}
												if(data.old_logo_moderation && !$.isEmptyObject(data.old_logo_moderation)){
													for(var i in data.old_logo_moderation.thumbs) if(data.old_logo_moderation.thumbs.hasOwnProperty(i)){
														images.filter('[src^="'+data.old_logo_moderation.thumbs[i]+'"]').attr('src', data.logo.thumbs[i]+'?'+new Date().getTime());
													}
												}
											}
										},
										cbOnComplete: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, 'error');
											}
										},
										jqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
									});
						
									avatar_web_camera = new webcamera({
										wc_width: avatar_width,
										wc_height: avatar_height,
										wc_alert: "{/literal}{l i='wc_alert' gid='users'}{literal}",
										wc_load_avatar: 'load_avatar'
									});
									
								},
								['user_avatar', 'avatar_uploader', 'avatar_web_uploader', 'avatar_web_camera'],
								{async: false}
							);
						});
					</script>{/literal}
				{else}
					<div class="center lh0">
						{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$avatar_data.user}
						<img src="{$avatar_data.user.media.user_logo.thumbs.grand}" alt="{$text_user_logo}" title="{$text_user_logo}">
					</div>
				{/if}
				<div class="mt20">
					{block name='comments_form' module='comments' gid=user_avatar id_obj=$avatar_data.user.id hidden=0 count=$avatar_data.user.logo_comments_count}
				</div>
			</div>
		</div>
	</div>
</div>

