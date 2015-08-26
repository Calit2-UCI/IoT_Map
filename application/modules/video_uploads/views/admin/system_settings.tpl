{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_video_menu'}
<div class="actions">&nbsp;</div>

{if $settings.use_shell_exec}
<div class="filter-form">
	<b>{l i='settings_os' gid='video_uploads'}:</b> {$settings.used_system}<br>
	<b>{l i='settings_ffmpeg_version' gid='video_uploads'}:</b> {if $versions.ffmpeg}{$versions.ffmpeg}{else}<font class="error">{l i='settings_not_found' gid='video_uploads'}</font>{/if}<br>
	<b>{l i='settings_mencoder_version' gid='video_uploads'}:</b> {if $versions.mencoder}{$versions.mencoder}{else}<font class="error">{l i='settings_not_found' gid='video_uploads'}</font>{/if}<br>
	<b>{l i='settings_mplayer_version' gid='video_uploads'}:</b> {if $versions.mplayer}{$versions.mplayer}{else}<font class="error">{l i='settings_not_found' gid='video_uploads'}</font>{/if}<br>
	<b>{l i='settings_flvtool2_version' gid='video_uploads'}:</b> {if $versions.flvtool2}{$versions.flvtool2}{else}<font class="error">{l i='settings_not_found' gid='video_uploads'}</font>{/if}<br>
	{if $versions.ffmpeg || $versions.mencoder}
	<b>{l i='settings_convert_video_type' gid='video_uploads'}:</b> {$settings.local_converting_video_type}<br>
	{/if}
	{if !$settings.use_local_converting_video}
	<br>{l i='error_unable_local_converting_video' gid='video_uploads'}<br>
	{/if}
	{if !$settings.use_local_converting_meta_data}
	<br>{l i='error_unable_local_converting_meta_data' gid='video_uploads'}<br>
	{/if}
	{if !$settings.use_local_converting_thumbs}
	<br>{l i='error_unable_local_converting_thumbs' gid='video_uploads'}<br>
	{/if}
	
	{if $codecs}
	<br>
		<b>{l i='required_video_codecs' gid='video_uploads'}:</b><br>
		{foreach item=item key=key from=$codecs.video_required}
		{$key} ({$item.codec_description}) - {if $item.installed}<font class="success">{l i='codec_installed' gid='video_uploads'}</font>{else}<font class="error">{l i='codec_not_installed' gid='video_uploads'}</font>{/if}<br>
		{/foreach}<br>

		<b>{l i='required_audio_codecs' gid='video_uploads'}:</b><br>
		{foreach item=item key=key from=$codecs.audio_required}
		{$key} ({$item.codec_description}) - {if $item.installed}<font class="success">{l i='codec_installed' gid='video_uploads'}</font>{else}<font class="error">{l i='codec_not_installed' gid='video_uploads'}</font>{/if}<br>
		{/foreach}<br>
	{/if}
	
	{if $php_ini}
	<br>
	<b>{l i='php_ini_settings' gid='video_uploads'}:</b><br>
	<b>post_max_size:</b> {$php_ini.post_max_size}<br>
	<b>upload_max_filesize:</b> {$php_ini.upload_max_filesize}<br>
	{$php_ini.max_size_notice}
	{/if}
</div>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_system_settings' gid='video_uploads'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_ffmpeg_path' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$settings.ffmpeg_path}" name="ffmpeg_path" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_mencoder_path' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$settings.mencoder_path}" name="mencoder_path" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_mplayer_path' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$settings.mplayer_path}" name="mplayer_path" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_flvtool2_path' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$settings.flvtool2_path}" name="flvtool2_path" class="middle"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<div class="btn gray fright"><div class="l"><input type="button" name="btn_reset" value="{l i='reset_system_settings' gid='video_uploads' type='button'}" onclick="javascript: location.href='{$site_url}admin/video_uploads/system_settings_reset';"></div></div>
	<div class="clr"></div>
</form>

{else}

<div class="filter-form">{l i="error_unable_shell_exec" gid='video_uploads'}</div>

{/if}

{include file="footer.tpl"}