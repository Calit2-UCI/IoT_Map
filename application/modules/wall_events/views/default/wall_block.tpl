{strip}
{if $wall_params.place == 'homepage'}
	<h1 class="mt10">
		{l i='recent_activity' gid='wall_events'}
		<span id="wall_permissions_link" class="fright" title="{l i='header_wall_settings' gid='wall_events'}" onclick="ajax_permissions_form(site_url+'wall_events/ajax_user_permissions/');"><i class="fa-cog fa-big edge hover zoom30"><i class="fa-mini-stack icon-lock"></i></i></span>
	</h1>
{/if}
{if $wall_params.show_post_form}
	<div id="wall_post" class="hide post-form wide">
		<form id="wall_upload_form" method="post" enctype="multipart/form-data" name="wall_upload_form" action="{$site_url}wall_events/post_form/{$wall_params.id_wall}/{$wall_params.place}">
			<input type="hidden" name="id" value="0" />
			<input type="hidden" name="id_wall" value="{$wall_params.id_wall}" />
			<div class="form-input">
				<div class="table-div">
					<div class="text"><textarea id="wall_post_text" placeholder="{l i='post_placeholder' gid='wall_events'}" name="text"></textarea></div>
					<div><input type="button" value="{l i='btn_send' gid='start'}" name="btn_send" id="btn_send" {*onclick="wall.newPost();"*} /></div>
				</div>
				<div class="ptb5">
					<span class="a" onclick="$('#wall_post_upload_form').stop().slideToggle();">{l i='add_uploads' gid='wall_events'}</span>
					<span class="ml20 a" onclick="$('#wall_post_embed_form').stop().slideToggle();">{l i='add_embed' gid='wall_events'}</span>
				</div>
			</div>
			<div id="wall_post_embed_form" class="hide ptb5">
				<div>{l i='embed_code' gid='wall_events'}:</div>
				<div>
					<textarea id="wall_embed_code" class="middle-text" name="embed_code"></textarea>
				</div>
			</div>
			<div id="wall_post_upload_form" class="hide">
				<div class="v">
					<div class="drag ptb10">
						<div id="dndfiles" class="drag-area"><ins>{l i='drag_files' gid='wall_events'}</ins></div>
					</div>
					<div>
						<div class="upload-btn">
							<span data-role="filebutton">
								<s>{l i='btn_choose_file' gid='start'}</s>
								<input type="file" name="multiupload" id="multiupload" multiple />
							</span>
							{if $wall_params.image_upload_config.max_size || $wall_params.video_upload_config.max_size}
								&nbsp;({l i='max' gid='wall_events'}. {if $wall_params.image_upload_config.max_size}{$wall_params.image_upload_config.max_size|bytes_format} {l i='images' gid='wall_events'}. {/if}{if $wall_params.video_upload_config.max_size}{$wall_params.video_upload_config.max_size|bytes_format} {l i='videos' gid='wall_events'}.{/if})
							{/if}
						</div>
						&nbsp;<span id="attach-input-error"></span>
						<div id="attach-input-warning"></div>
					</div>
				</div>
			</div>			
		</form>
	</div>
{/if}

<div id="wall" class="wall">
	
</div>
{/strip}

<script>{literal}
	var wall;
	$(function(){
		var wall_params = {/literal}{json_encode data=$wall_params}{literal} || {};
		wall_params.id = 'wall';
		wall_params.onInit = function(){$('#wall_post').fadeIn(600);};
		loadScripts(
			"{/literal}{js module='wall_events' file='wall.js' return='path'}{literal}",
			function(){
				wall = new Wall().init(wall_params).loadEvents();
			},
			'wall',
			{async: false}
		);

		$('#wall_permissions_link').click(function(){
			ajax_permissions_form(site_url+'wall_events/ajax_user_permissions/');
			return false;
		});
		
		user_ajax_permissions = new loadingContent({
			loadBlockWidth: '620px',
			/*linkerObjID: 'wall_permissions_link',*/
			loadBlockLeftType: 'center',
			loadBlockTopType: 'center',
			loadBlockTopPoint: 100,
			closeBtnClass: 'w'
		});
		
		if(wall_params.show_post_form){
			loadScripts(
				"{/literal}{js file='uploader.js' return='path'}{literal}", 
				function(){
					mu = new uploader({
						siteUrl: site_url,
						uploadUrl: wall_params.url_upload,
						zoneId: 'dndfiles',
						fileId: 'multiupload',
						formId: 'wall_upload_form',
						sendType: 'file',
						sendId: 'btn_send',
						messageId: 'attach-input-error',
						warningId: 'attach-input-warning',
						maxFileSize: wall_params.max_upload_size,
						mimeType: wall_params.allowed_mimes,
						cbOnComplete: function(data){
							wall.properties.uploaded = true;
							if(data.id){
								$('#wall_upload_form').find('input[name="id"]').val(data.id);
							}
							if(data.joined_id){
								$('#wall_event_'+data.joined_id).remove();
							}
						},
						cbOnQueueComplete: function(){
							$('#wall_upload_form').find('input[name="id"]').val('0');
							if(!wall.properties.uploaded){
								wall.newPost(function(){wall.loadEvents('new');});
							}else{
								$('#wall_post_text').val('');
								$('#wall_embed_code').val('');
								wall.loadEvents('new');
							}
							wall.properties.uploaded = false;
						},
						createThumb: true,
						thumbWidth: 100,
						thumbHeight: 100,
						thumbCrop: true,
						thumbJpeg: false,
						thumbBg: 'transparent',
						fileListInZone: true,
						jqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
					});
				},
				['mu'],
				{async: false}
			);
		}
	});
	
	$(document)
		.on('dragenter', '#wall_post', function(){$('#wall_post_upload_form').slideDown();})
		.on('pjax:start', function(e){$(document).off('dragenter', '#wall_post');});
	
	function ajax_permissions_form(url){
		$.ajax({
			url: url,
			cache: false,
			data: {redirect_url: location.href},
			success: function(data){
				user_ajax_permissions.show_load_block(data);
			},
			type: 'POST'
		});
	}
</script>{/literal}
