{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_kisses_menu'}

<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/kisses/add/" id="btn_add">{l i='btn_add' gid='kisses'}</a></div></li>
	</ul>
	&nbsp;
</div>
<div id="new_kiss"></div>
<form id="kisses_form" method="post" enctype="multipart/form-data" name="kisses_form" action="{$site_url}kisses/post_form">
	<div id="kisses_post_upload_form" class="">
		<div class="v">
			<div class="drag ptb10">
				<div id="dndfiles" class="drag-area"><ins>{l i='drag_files' gid='kisses'}</ins></div>
			</div>
			<div>
				<div class="upload-btn">
					<span data-role="filebutton">
						<s>{l i='btn_choose_file' gid='start'}</s>
						<input type="file" name="multiupload" id="multiupload" multiple />
					</span>
					{if $kisses_params.image_upload_config.max_size}
						&nbsp;({l i='max' gid='kisses'}. {if $kisses_params.image_upload_config.max_size}{$kisses_params.image_upload_config.max_size|bytes_format} {l i='images' gid='kisses'}. {/if})
					{/if}
				</div>
				&nbsp;<span id="attach-input-error"></span>
				<div id="attach-input-warning"></div>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="button" value="{l i='btn_send' gid='start'}" name="btn_send" id="btn_send" /></div></div>
	<a class="cancel" href="{$site_url}admin/start/menu/add_ons_items">{l i='btn_cancel' gid='start'}</a>
	</div>
</form>

<script type="text/javascript">{literal}
	var kisses;
	var param='sorter/ASC/';
	var reload_link = "{/literal}{$site_url}admin/kisses/{literal}";
	
	$(function(){
		var kisses_params = {/literal}{json_encode data=$kisses_params}{literal} || {};
		kisses_params.uploaded = true;
			
			loadScripts(
				"{/literal}{js file='uploader.js' return='path'}{literal}", 
				function(){
					mu = new uploader({
						siteUrl: site_url,
						uploadUrl: kisses_params.url_upload,
						zoneId: 'dndfiles',
						fileId: 'multiupload',
						formId: 'kisses_form',
						sendType: 'file',
						sendId: 'btn_send',
						messageId: 'attach-input-error',
						warningId: 'attach-input-warning',
						maxFileSize: kisses_params.max_upload_size,
						mimeType: kisses_params.allowed_mimes,
						cbOnComplete: function(data){
							
							if(data.errors && data.errors.length){
								error_object.show_error_block(data.errors, 'error');
								kisses_params.uploaded = false;
							}
						},
						cbOnQueueComplete: function(){
							if(kisses_params.uploaded){
								reload_this_page('index/'+param);
							}
							/*kisses_params.uploaded = true;*/
						},
						createThumb: true,
						thumbCrop: false,
						thumbJpeg: false,
						fileListInZone: true,
						jqueryFormPluginUrl: "{/literal}{js file='jquery.form.min.js' return='path'}{literal}"
					});
				},
				['mu'],
				{async: false}
			);
		
	});
	
	function reload_this_page(value){
		var link = reload_link + value;
		location.href=link;
	}
{/literal}</script>

{include file="footer.tpl"}
