{strip}
<div class="content-block load_content">
	<h1>{l i='add_photos' gid='media'}</h1>
	<div class="m10 oh popup-form">
		<form id="item_form" onsubmit="return" action="{$site_root}/{$seo.myprofile}" method="post" enctype="multipart/form-data" name="item_form">
			<div class="r">
				<div class="f">{l i='field_files' gid='media'}:</div>
				<div class="v">
					<div id="dnd_upload" class="drag">
						<div id="dndfiles" class="drag-area">
							<div class="drag">
								<p>{l i='drag_photos' gid='media'}</p>
							</div>
						</div>
					</div>
					<div>
						<div class="upload-btn">
							<span data-role="filebutton">
								<s>{l i='btn_choose_file' gid='start'}</s>
								<input type="file" name="multiUpload" id="multiUpload" accept="image/*;capture=camera" multiple />
							</span>
							{if $media_config.max_size}&nbsp;({l i='max' gid='start'} {$media_config.max_size|bytes_format}){/if}
						</div>
						&nbsp;<span id="attach-input-error"></span>
						<div id="attach-input-warning"></div>
					</div>
				</div>
			</div>
			<div id="album_content">
				<div class="r {if !$user_albums}hide{/if}" id="albums_select_block">
					<div class="f">{l i='albums' gid='media'}:</div>
					<div class="v" id="albums_select">
						<select class="wp100 box-sizing" name="album_id" >
							<option value="0">{l i='please_select' gid='media'}</option>
							{foreach item=item key=key name=f from=$user_albums}
								<option value="{$item.id}"{if $item.id == $id_album} selected{/if}>{$item.name}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="r">
					<span class="pointer link-r-margin" id="create_album_button_aform"><span class="a">{l i='create_album' gid='media'}</span></span>
					<span class="hide" id="create_album_container_aform">
						<span class="input-w-btn">
							<input type='text' name='album_name' id='album_name_aform'>
							<span class="a-button" id="save_album_aform"><i class="fa-ok w"></i></span>
						</span>
					</span>
				</div>
			</div>
			<div class="r">
				<div class="f">{l i='field_permitted_for' gid='media'}:</div>
				<div class="v">
					{ld gid='media' i='permissions'}
					<select class="wp100 box-sizing" name="permissions">
						{foreach item=item key=key name=f from=$ld_permissions.option}
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
		loadScripts(
			"{/literal}{js file='uploader.js' return='path'}{literal}", 
			function(){
				var allowed_mimes = {/literal}{json_encode data=$media_config.allowed_mimes}{literal};
				mu = new uploader({
					siteUrl: site_url,
					uploadUrl: 'media/save_image',
					zoneId: 'dndfiles',
					fileId: 'multiUpload',
					formId: 'item_form',
					sendType: 'file',
					sendId: 'btn_upload',
					messageId: 'attach-input-error',
					warningId: 'attach-input-warning',
					maxFileSize: '{/literal}{$media_config.max_size}{literal}',
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
					thumbBg: 'transparent',
					fileListInZone: true,
					filebarHeight: 200,
					jqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
			   });
			},
			['mu'],
			{async: false}
		);
		
		loadScripts(
			"{/literal}{js file='albums.js' module='media' return='path'}{literal}",
			function(){
				albums_obj = new albums({
					siteUrl: site_url, 
					contentDiv: '#album_content',
					createAlbumButton: '#create_album_button_aform', 
					createAlbumContainer: '#create_album_container_aform', 
					saveAlbumButton: '#save_album_aform', 
					albumNameInput: '#album_name_aform', 
					create_album_success_request: function(resp){
						if (resp.status){
							$('#album_content #albums_select').html(resp.data.albums_select);
							$('#album_content #albums_select select').val(resp.data.album_id).prop('selected','selected')
							$('#album_content #albums_select select').addClass('wp100').addClass('box-sizing');
							$('#item_form #albums_select_block').removeClass('hide');
							if(mediagallery){
								mediagallery.properties.galleryContentPage = 1,
								mediagallery.properties.all_loaded = 0;
								mediagallery.load_content(1);
								this.windowObj.hide_load_block();
								if(resp.data.albums_select && mediagallery.properties.idUser == resp.data.id_user){
									var selected_album = $(mediagallery.properties.albumSelector).val();
									$(mediagallery.properties.albumSelectorContainer).html(resp.data.albums_select).val(selected_album).prop('selected','selected');
								}
							}
						}else{
							error_object.show_error_block(resp.errors, 'error');
						}
					}
				});
			},
			['albums_obj'],
			{async: false}
		);
	});
{/literal}</script>
