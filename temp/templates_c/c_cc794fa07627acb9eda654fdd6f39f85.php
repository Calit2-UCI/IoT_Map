<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.bytes_format.php');
$this->register_modifier("bytes_format", "tpl_modifier_bytes_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:35 Pacific Daylight Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<?php if ($this->_vars['wall_params']['place'] == 'homepage'): ?>
	<h1 class="mt10">
		<?php echo l('recent_activity', 'wall_events', '', 'text', array()); ?>
		<span id="wall_permissions_link" class="fright" title="<?php echo l('header_wall_settings', 'wall_events', '', 'text', array()); ?>" onclick="ajax_permissions_form(site_url+'wall_events/ajax_user_permissions/');"><i class="fa-cog fa-big edge hover zoom30"><i class="fa-mini-stack icon-lock"></i></i></span>
	</h1>
<?php endif; ?>
<?php if ($this->_vars['wall_params']['show_post_form']): ?>
	<div id="wall_post" class="hide post-form wide">
		<form id="wall_upload_form" method="post" enctype="multipart/form-data" name="wall_upload_form" action="<?php echo $this->_vars['site_url']; ?>
wall_events/post_form/<?php echo $this->_vars['wall_params']['id_wall']; ?>
/<?php echo $this->_vars['wall_params']['place']; ?>
">
			<input type="hidden" name="id" value="0" />
			<input type="hidden" name="id_wall" value="<?php echo $this->_vars['wall_params']['id_wall']; ?>
" />
			<div class="form-input">
				<div class="table-div">
					<div class="text"><textarea id="wall_post_text" placeholder="<?php echo l('post_placeholder', 'wall_events', '', 'text', array()); ?>" name="text"></textarea></div>
					<div><input type="button" value="<?php echo l('btn_send', 'start', '', 'text', array()); ?>" name="btn_send" id="btn_send"  /></div>
				</div>
				<div class="ptb5">
					<span class="a" onclick="$('#wall_post_upload_form').stop().slideToggle();"><?php echo l('add_uploads', 'wall_events', '', 'text', array()); ?></span>
					<span class="ml20 a" onclick="$('#wall_post_embed_form').stop().slideToggle();"><?php echo l('add_embed', 'wall_events', '', 'text', array()); ?></span>
				</div>
			</div>
			<div id="wall_post_embed_form" class="hide ptb5">
				<div><?php echo l('embed_code', 'wall_events', '', 'text', array()); ?>:</div>
				<div>
					<textarea id="wall_embed_code" class="middle-text" name="embed_code"></textarea>
				</div>
			</div>
			<div id="wall_post_upload_form" class="hide">
				<div class="v">
					<div class="drag ptb10">
						<div id="dndfiles" class="drag-area"><ins><?php echo l('drag_files', 'wall_events', '', 'text', array()); ?></ins></div>
					</div>
					<div>
						<div class="upload-btn">
							<span data-role="filebutton">
								<s><?php echo l('btn_choose_file', 'start', '', 'text', array()); ?></s>
								<input type="file" name="multiupload" id="multiupload" multiple />
							</span>
							<?php if ($this->_vars['wall_params']['image_upload_config']['max_size'] || $this->_vars['wall_params']['video_upload_config']['max_size']): ?>
								&nbsp;(<?php echo l('max', 'wall_events', '', 'text', array()); ?>. <?php if ($this->_vars['wall_params']['image_upload_config']['max_size']):  echo $this->_run_modifier($this->_vars['wall_params']['image_upload_config']['max_size'], 'bytes_format', 'plugin', 1); ?>
 <?php echo l('images', 'wall_events', '', 'text', array()); ?>. <?php endif;  if ($this->_vars['wall_params']['video_upload_config']['max_size']):  echo $this->_run_modifier($this->_vars['wall_params']['video_upload_config']['max_size'], 'bytes_format', 'plugin', 1); ?>
 <?php echo l('videos', 'wall_events', '', 'text', array()); ?>.<?php endif; ?>)
							<?php endif; ?>
						</div>
						&nbsp;<span id="attach-input-error"></span>
						<div id="attach-input-warning"></div>
					</div>
				</div>
			</div>			
		</form>
	</div>
<?php endif; ?>

<div id="wall" class="wall">
	
</div>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<script><?php echo '
	var wall;
	$(function(){
		var wall_params = ';  echo tpl_function_json_encode(array('data' => $this->_vars['wall_params']), $this); echo ' || {};
		wall_params.id = \'wall\';
		wall_params.onInit = function(){$(\'#wall_post\').fadeIn(600);};
		loadScripts(
			"';  echo tpl_function_js(array('module' => 'wall_events','file' => 'wall.js','return' => 'path'), $this); echo '",
			function(){
				wall = new Wall().init(wall_params).loadEvents();
			},
			\'wall\',
			{async: false}
		);

		$(\'#wall_permissions_link\').click(function(){
			ajax_permissions_form(site_url+\'wall_events/ajax_user_permissions/\');
			return false;
		});
		
		user_ajax_permissions = new loadingContent({
			loadBlockWidth: \'620px\',
			/*linkerObjID: \'wall_permissions_link\',*/
			loadBlockLeftType: \'center\',
			loadBlockTopType: \'center\',
			loadBlockTopPoint: 100,
			closeBtnClass: \'w\'
		});
		
		if(wall_params.show_post_form){
			loadScripts(
				"';  echo tpl_function_js(array('file' => 'uploader.js','return' => 'path'), $this); echo '", 
				function(){
					mu = new uploader({
						siteUrl: site_url,
						uploadUrl: wall_params.url_upload,
						zoneId: \'dndfiles\',
						fileId: \'multiupload\',
						formId: \'wall_upload_form\',
						sendType: \'file\',
						sendId: \'btn_send\',
						messageId: \'attach-input-error\',
						warningId: \'attach-input-warning\',
						maxFileSize: wall_params.max_upload_size,
						mimeType: wall_params.allowed_mimes,
						cbOnComplete: function(data){
							wall.properties.uploaded = true;
							if(data.id){
								$(\'#wall_upload_form\').find(\'input[name="id"]\').val(data.id);
							}
							if(data.joined_id){
								$(\'#wall_event_\'+data.joined_id).remove();
							}
						},
						cbOnQueueComplete: function(){
							$(\'#wall_upload_form\').find(\'input[name="id"]\').val(\'0\');
							if(!wall.properties.uploaded){
								wall.newPost(function(){wall.loadEvents(\'new\');});
							}else{
								$(\'#wall_post_text\').val(\'\');
								$(\'#wall_embed_code\').val(\'\');
								wall.loadEvents(\'new\');
							}
							wall.properties.uploaded = false;
						},
						createThumb: true,
						thumbWidth: 100,
						thumbHeight: 100,
						thumbCrop: true,
						thumbJpeg: false,
						thumbBg: \'transparent\',
						fileListInZone: true,
						jqueryFormPluginUrl: "';  echo tpl_function_js(array('file' => 'jquery.form.min.js','return' => 'path'), $this); echo '"
					});
				},
				[\'mu\'],
				{async: false}
			);
		}
	});
	
	$(document)
		.on(\'dragenter\', \'#wall_post\', function(){$(\'#wall_post_upload_form\').slideDown();})
		.on(\'pjax:start\', function(e){$(document).off(\'dragenter\', \'#wall_post\');});
	
	function ajax_permissions_form(url){
		$.ajax({
			url: url,
			cache: false,
			data: {redirect_url: location.href},
			success: function(data){
				user_ajax_permissions.show_load_block(data);
			},
			type: \'POST\'
		});
	}
</script>'; ?>

