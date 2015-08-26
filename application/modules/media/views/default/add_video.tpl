{strip}
<div class="content-block load_content">
	<h1>{l i='add_video' gid='media'}</h1>
	<div class="m10 oh popup-form">
		<form id="upload_video" action="{$site_url}media/save_video" method="post" enctype="multipart/form-data" name="upload_video">
			<div class="r">
				<div class="f">{l i='field_files' gid='media'}:</div>
				<div class="v">
					<input type="file" name="videofile" id="videofile" />({if $media_config.max_size eq '0'}{l i='int_unlimited' gid='uploads'}{else}{l i='max' gid='start'} {$media_config.max_size|bytes_format}{/if})&nbsp;<span id="attach-input-error"></span>
					<div id="attach-input-warning"></div>
				</div>
				<div>
					<div class="pt10">{l i='field_or_embed_code' gid='media'}</div>
					<div class="text"><textarea class="box-sizing" name="embed_code"></textarea></div>
				</div>
			</div>
			{if $user_albums}
				<div class="r">
					<div class="f">{l i='albums' gid='media'}:</div>
					<div class="v">
						<select class="wp100 box-sizing" name="id_album">
						<option value="0">{l i='please_select' gid='media'}</option>
						{foreach item=item key=key name=f from=$user_albums}
							<option value="{$item.id}"{if $item.id == $id_album} selected{/if}>{$item.name}</option>
						{/foreach}
						</select>
					</div>
				</div>
			{/if}
			<div class="r">
				<div class="f">{l i='field_permitted_for' gid='media'}:</div>
				<div class="v">
					{ld gid='media' i='permissions'}
					<select class="wp100 box-sizing" name="permissions">
					{foreach item=item key=key from=$ld_permissions.option}
						<option value="{$key}"{if $key == 4} selected{/if}>{$item}</option>
					{/foreach}
					</select>
				</div>
			</div>
			<div class="r">
				<div class="f">{l i='field_description' gid='media'}:</div>
				<div class="v text"><textarea class="box-sizing" name="description">{$data.description}</textarea></div>
			</div>
			<div class="v"><input type="button" value="{l i='btn_save' gid='start' type='button'}" name="btn_upload" id="btn_upload"></div>
		</form>
	</div>
	<div class="clr"></div>
</div>
{/strip}


<script type='text/javascript'>{literal}
	$(function(){
		var allowed_mimes = {/literal}{json_encode data=$media_config.allowed_mimes}{literal};
		loadScripts(
			"{/literal}{js file='uploader.js' return='path'}{literal}", 
			function(){
				vu = new uploader({
					siteUrl: site_url,
					uploadUrl: 'media/save_video',
					//zoneId: 'dragAndDropFiles',
					fileId: 'videofile',
					formId: 'upload_video',
					sendType: 'file',
					sendId: 'btn_upload',
					//multiFile: false,
					messageId: 'attach-input-error',
					warningId: 'attach-input-warning',
					maxFileSize: '{/literal}{$media_config.max_size}{literal}',
					mimeType:  allowed_mimes,
					allowEmptyFile: true,
					cbOnUpload: function(name, data){
						if(window.sitegallery){
							sitegallery.reload();
						}else if(window.mediagallery){
							mediagallery.reload();
						}
						if(window.mediagallery){
							if(data.warnings && data.warnings.length){
								error_object.show_error_block(data.warnings, 'warning');
							}
							mediagallery.properties.windowObj.hide_load_block();
						}
					},
					cbOnComplete: function(data){
						if(data.errors.length){
							error_object.show_error_block(data.errors, 'error');
						}
						$('#videofile').val('');
					},
					jqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
			   });
			},
			['vu'],
			{async: false}
		);
	});
{/literal}</script>
