<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.ld.php');
$this->register_function("ld", "tpl_function_ld"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.bytes_format.php');
$this->register_modifier("bytes_format", "tpl_modifier_bytes_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:48:35 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<div class="content-block load_content">
	<h1><?php echo l('add_photos', 'media', '', 'text', array()); ?></h1>
	<div class="m10 oh popup-form">
		<form id="item_form" onsubmit="return" action="<?php echo $this->_vars['site_root']; ?>
/<?php echo $this->_vars['seo']['myprofile']; ?>
" method="post" enctype="multipart/form-data" name="item_form">
			<div class="r">
				<div class="f"><?php echo l('field_files', 'media', '', 'text', array()); ?>:</div>
				<div class="v">
					<div id="dnd_upload" class="drag">
						<div id="dndfiles" class="drag-area">
							<div class="drag">
								<p><?php echo l('drag_photos', 'media', '', 'text', array()); ?></p>
							</div>
						</div>
					</div>
					<div>
						<div class="upload-btn">
							<span data-role="filebutton">
								<s><?php echo l('btn_choose_file', 'start', '', 'text', array()); ?></s>
								<input type="file" name="multiUpload" id="multiUpload" accept="image/*;capture=camera" multiple />
							</span>
							<?php if ($this->_vars['media_config']['max_size']): ?>&nbsp;(<?php echo l('max', 'start', '', 'text', array()); ?> <?php echo $this->_run_modifier($this->_vars['media_config']['max_size'], 'bytes_format', 'plugin', 1); ?>
)<?php endif; ?>
						</div>
						&nbsp;<span id="attach-input-error"></span>
						<div id="attach-input-warning"></div>
					</div>
				</div>
			</div>
			<div id="album_content">
				<div class="r <?php if (! $this->_vars['user_albums']): ?>hide<?php endif; ?>" id="albums_select_block">
					<div class="f"><?php echo l('albums', 'media', '', 'text', array()); ?>:</div>
					<div class="v" id="albums_select">
						<select class="wp100 box-sizing" name="album_id" >
							<option value="0"><?php echo l('please_select', 'media', '', 'text', array()); ?></option>
							<?php if (is_array($this->_vars['user_albums']) and count((array)$this->_vars['user_albums'])): foreach ((array)$this->_vars['user_albums'] as $this->_vars['key'] => $this->_vars['item']): ?>
								<option value="<?php echo $this->_vars['item']['id']; ?>
"<?php if ($this->_vars['item']['id'] == $this->_vars['id_album']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
</option>
							<?php endforeach; endif; ?>
						</select>
					</div>
				</div>
				<div class="r">
					<span class="pointer link-r-margin" id="create_album_button_aform"><span class="a"><?php echo l('create_album', 'media', '', 'text', array()); ?></span></span>
					<span class="hide" id="create_album_container_aform">
						<span class="input-w-btn">
							<input type='text' name='album_name' id='album_name_aform'>
							<span class="a-button" id="save_album_aform"><i class="fa-ok w"></i></span>
						</span>
					</span>
				</div>
			</div>
			<div class="r">
				<div class="f"><?php echo l('field_permitted_for', 'media', '', 'text', array()); ?>:</div>
				<div class="v">
					<?php echo tpl_function_ld(array('gid' => 'media','i' => 'permissions'), $this);?>
					<select class="wp100 box-sizing" name="permissions">
						<?php if (is_array($this->_vars['ld_permissions']['option']) and count((array)$this->_vars['ld_permissions']['option'])): foreach ((array)$this->_vars['ld_permissions']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
							<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == 4): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
						<?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<div class="r">
				<div class="f"><?php echo l('field_description', 'media', '', 'text', array()); ?>:</div>
				<div class="v text"><textarea class="box-sizing" name="description"><?php echo $this->_vars['data']['description']; ?>
</textarea></div>
			</div>
			<div class="v"><input type="button" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>" name="btn_upload" id="btn_upload"></div>
		</form>
	</div>
	<div class="clr"></div>
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script type='text/javascript'><?php echo '
	$(function(){
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'uploader.js','return' => 'path'), $this); echo '", 
			function(){
				var allowed_mimes = ';  echo tpl_function_json_encode(array('data' => $this->_vars['media_config']['allowed_mimes']), $this); echo ';
				mu = new uploader({
					siteUrl: site_url,
					uploadUrl: \'media/save_image\',
					zoneId: \'dndfiles\',
					fileId: \'multiUpload\',
					formId: \'item_form\',
					sendType: \'file\',
					sendId: \'btn_upload\',
					messageId: \'attach-input-error\',
					warningId: \'attach-input-warning\',
					maxFileSize: \'';  echo $this->_vars['media_config']['max_size'];  echo '\',
					mimeType:  allowed_mimes,
					cbOnQueueComplete: function(data){
						if(window.sitegallery){
							sitegallery.reload();
						}else if(window.mediagallery){
							mediagallery.reload();
						}
					},
					createThumb: true,
					thumbWidth: 60,
					thumbHeight: 60,
					thumbCrop: true,
					thumbJpeg: false,
					thumbBg: \'transparent\',
					fileListInZone: true,
					filebarHeight: 200,
					jqueryFormPluginUrl: "';  echo tpl_function_js(array('file' => 'jquery.form.min.js','return' => 'path'), $this); echo '"
			   });
			},
			[\'mu\'],
			{async: false}
		);
		
		loadScripts(
			"';  echo tpl_function_js(array('file' => 'albums.js','module' => 'media','return' => 'path'), $this); echo '",
			function(){
				albums_obj = new albums({
					siteUrl: site_url, 
					contentDiv: \'#album_content\',
					createAlbumButton: \'#create_album_button_aform\', 
					createAlbumContainer: \'#create_album_container_aform\', 
					saveAlbumButton: \'#save_album_aform\', 
					albumNameInput: \'#album_name_aform\', 
					create_album_success_request: function(resp){
						if (resp.status){
							$(\'#album_content #albums_select\').html(resp.data.albums_select);
							$(\'#album_content #albums_select select\').val(resp.data.album_id).prop(\'selected\',\'selected\')
							$(\'#album_content #albums_select select\').addClass(\'wp100\').addClass(\'box-sizing\');
							$(\'#item_form #albums_select_block\').removeClass(\'hide\');
							if(mediagallery){
								mediagallery.properties.galleryContentPage = 1,
								mediagallery.properties.all_loaded = 0;
								mediagallery.load_content(1);
								this.windowObj.hide_load_block();
								if(resp.data.albums_select && mediagallery.properties.idUser == resp.data.id_user){
									var selected_album = $(mediagallery.properties.albumSelector).val();
									$(mediagallery.properties.albumSelectorContainer).html(resp.data.albums_select).val(selected_album).prop(\'selected\',\'selected\');
								}
							}
						}else{
							error_object.show_error_block(resp.errors, \'error\');
						}
					}
				});
			},
			[\'albums_obj\'],
			{async: false}
		);
	});
'; ?>
</script>
