{include file="header.tpl"}
<script  type='text/javascript' src="{$site_root}{$js_folder}easyTooltip.min.js"></script>
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_config_change' gid='video_uploads'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_name' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='video_uploads'}: </div>
			<div class="v"><b>{$data.gid}</b></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_max_size' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$data.max_size}" name="max_size" class="short"> b <i>{l i='int_unlimit_condition' gid='video_uploads'}</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_file_formats' gid='video_uploads'}: </div>
			<div class="v">
				{foreach item=item from=$formats}
					<div class="column-block"><input type="checkbox" name="file_formats[]" value="{$item}" {if $data.enable_formats[$item]}checked{/if} id="frm_{$item}"> <label for="frm_{$item}">{$item}</label></div>
				{/foreach}
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_upload_type' gid='video_uploads'}: </div>
			<div class="v">

				<input type="radio" value="local" name="upload_type" id="upload_type_local" {if $data.upload_type eq 'local'}checked{/if}>
				<label for="upload_type_local">{l i='field_upload_type_local' gid='video_uploads'}</label> &nbsp;&nbsp;&nbsp;
				<input type="radio" value="youtube" name="upload_type" id="upload_type_youtube" {if $data.upload_type eq 'youtube'}checked{/if} {if !$settings.use_youtube_converting}disabled{/if}>
				<label for="upload_type_youtube">{l i='field_upload_type_youtube' gid='video_uploads'}</label>
				{if !$settings.use_youtube_converting}<i>({l i='field_upload_type_youtube_note' gid='video_uploads'})</i>{/if}
			</div>
		</div>

		<div id="local_settings" {if $data.upload_type ne 'local'}class="hide"{/if}>
			<div class="row header">{l i='admin_header_config_local_settings' gid='video_uploads'}</div>
			<div class="row">
				<div class="h">{l i='field_local_enable_encoding' gid='video_uploads'}: </div>
				<div class="v">
					<input type="checkbox" value="1" name="use_convert" id="use_convert" {if $data.use_convert eq 1}checked{/if} {if !$settings.use_local_converting_video}disabled{/if}>
				{if !$settings.use_local_converting_video}<i>({l i='field_upload_type_local_note' gid='video_uploads'})</i>{/if}
				</div>
			</div>

			<div id="local_settings_params" {if $data.use_convert ne 1 || !$settings.use_local_converting_video}class="hide"{/if}>
				<div class="row zebra">
					<div class="h">{l i='field_local_width' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.local_settings.width}" name="local_settings[width]" class="short"></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_local_height' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.local_settings.height}" name="local_settings[height]" class="short"></div>
				</div>
				<div class="row zebra">
					<div class="h">{l i='field_local_audio_freq' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.local_settings.audio_freq}" name="local_settings[audio_freq]" class="short"></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_local_audio_brate' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.local_settings.audio_brate}" name="local_settings[audio_brate]" class="short"></div>
				</div>
				<div class="row zebra">
					<div class="h">{l i='field_local_video_brate' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.local_settings.video_brate}" name="local_settings[video_brate]" class="short"></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_local_video_rate' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.local_settings.video_rate}" name="local_settings[video_rate]" class="short"></div>
				</div>
			</div>
		</div>

		<div id="youtube_settings" {if $data.upload_type ne 'youtube'}class="hide"{/if}>
			<div class="row header">{l i='admin_header_config_youtube_settings' gid='video_uploads'}</div>
				<div class="row zebra">
					<div class="h">{l i='field_youtube_width' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.youtube_settings.width}" name="youtube_settings[width]" class="short"></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_youtube_height' gid='video_uploads'}: </div>
					<div class="v"><input type="text" value="{$data.youtube_settings.height}" name="youtube_settings[height]" class="short"></div>
				</div>
		</div>


		<div class="row header">{l i='admin_header_config_thumbs_settings' gid='video_uploads'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_enable_thumbs' gid='video_uploads'}: </div>
			<div class="v">
				<input type="checkbox" id="use_thumbs" name="use_thumbs" value="1" {if $data.use_thumbs eq '1'}checked{/if} {if !$settings.use_local_converting_thumbs && $data.upload_type eq 'local'}disabled{/if}>
				<span id="use_thumbs_info" {if !(!$settings.use_local_converting_thumbs && $data.upload_type eq 'local')}class="hide"{/if}><i>({l i='field_use_thumbs_note' gid='video_uploads'})</i></span>
			</div>
		</div>
		<div id="images_settings" {if (!$settings.use_local_converting_thumbs && $data.upload_type eq 'local') || $data.use_thumbs ne '1'}class="hide"{/if}>
			<div class="row">
				<div class="h">{l i='field_thumbs' gid='video_uploads'}: </div>
				<div class="v">
					<a href="#" id="add_thumb_link">{l i='link_add_thumb' gid='video_uploads'}</a><br>

					<div class="column-block header">{l i='field_thumb_gid' gid='video_uploads'}</div>
					<div class="column-block header">{l i='field_thumb_width' gid='video_uploads'}</div>
					<div class="column-block header">{l i='field_thumb_height' gid='video_uploads'}</div>
{*					<div class="column-block header">{l i='field_animated_gif' gid='video_uploads'}</div> *}
					<div class="clr"></div>
					{assign var="thumbs_count" value=0}
					<ul id="thumbs_list" class="thumbs-list">
					{foreach item=item key=key from=$data.thumbs_settings}
					<li>
						<div class="column-block"><input type="text" name="thumbs_settings[{$key}][gid]" value="{$item.gid}" class="short"></div>
						<div class="column-block"><input type="text" name="thumbs_settings[{$key}][width]" value="{$item.width}" class="short"> px</div>
						<div class="column-block"><input type="text" name="thumbs_settings[{$key}][height]" value="{$item.height}" class="short"> px</div>
{*						<div class="column-block"><input type="checkbox" name="thumbs_settings[{$key}][animated]" value="1" {if $item.animated}checked{/if}></div>*}
						<div class="column-block"><a href="#" class="delete_thumb">{l i='link_delete_thumb' gid='video_uploads'}</a></div>
					</li>
					{assign var="thumbs_count" value=$key}
					{/foreach}
					<li id="thumb_example" class="hide">
						<div class="column-block"><input type="text" name="thumbs_settings[_new_key_][gid]" value="" class="short"></div>
						<div class="column-block"><input type="text" name="thumbs_settings[_new_key_][width]" value="" class="short"> px</div>
						<div class="column-block"><input type="text" name="thumbs_settings[_new_key_][height]" value="" class="short"> px</div>
{*						<div class="column-block"><input type="checkbox" name="thumbs_settings[_new_key_][animated]" value="1"></div>*}
						<div class="column-block"><a href="#" class="delete_thumb">{l i='link_delete_thumb' gid='video_uploads'}</a></div>
					</li>
					</ul>
				</div>
			</div>
			<div class="row zebra">
				<div class="h">{l i='field_default_img' gid='video_uploads'}: </div>
				<div class="v"><input type="file" value="" name="default_img" class="file">
				{if $data.default_img_data}<br><a href="{$data.default_img_data.file_url}" target="blank">{l i='link_view_default_image' gid='video_uploads'}</a>{/if}
				</div>
			</div>
			{if $data.default_img}
			<div class="row">
				<div class="h">{l i='field_default_img_delete' gid='video_uploads'}: </div>
				<div class="v"><input type="checkbox" value="1" name="default_img_delete"></div>
			</div>
			{/if}
		</div>

		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/video_uploads/index">{l i='btn_cancel' gid='start'}</a>
	</div>
</form>
<script type='text/javascript'>
	var is_local_thumbs_allowed = parseInt('{$settings.use_local_converting_thumbs}');
	var thumbs_count = parseInt('{$thumbs_count}');
{literal}
	$(function(){
		$('input[name=upload_type]').bind('change', function(){ change_type($(this).val());});
		$('#use_convert').bind('change', function(){
			if($(this).is(':checked')){
				$('#local_settings_params').show();
			}else{
				$('#local_settings_params').hide();
			}
		});
		$('#use_thumbs').bind('change', function(){
			if($(this).is(':checked')){
				$('#images_settings').show();
			}else{
				$('#images_settings').hide();
			}
		});

		$('#add_thumb_link').bind('click', function(){
			var content = $('#thumb_example').html();
			thumbs_count++;
			content = content.replace(/_new_key_/g, thumbs_count);
			$('#thumbs_list').append('<li>'+content+'</li>');
			return false;
		});

		$('#thumbs_list').delegate('a.delete_thumb', 'click', function(){
			$(this).parent().parent().remove();
			return false;
		});
	});

	function change_type(upload_type){
		if($('#use_thumbs').is(':checked')){
			$('#images_settings').show();
		}
		$('#use_thumbs').removeAttr('disabled');
		$('#use_thumbs_info').hide();

		if(upload_type == 'local'){
			$('#local_settings').show();
			$('#youtube_settings').hide();

			if(!is_local_thumbs_allowed){
				$('#use_thumbs').attr('disabled', 'disabled');
				$('#use_thumbs').prop('checked', false);
				$('#use_thumbs_info').show();
				$('#images_settings').hide();
			}
		}

		if(upload_type == 'youtube'){
			$('#local_settings').hide();
			$('#youtube_settings').show();
		}

	}
{/literal}
</script>
{include file="footer.tpl"}