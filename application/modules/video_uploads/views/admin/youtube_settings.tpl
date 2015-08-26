{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_video_menu'}
<div class="actions">&nbsp;</div>

{if !$settings.use_youtube_converting}
<div class="filter-form">{l i='error_unable_youtube_converting_video' gid='video_uploads'}</div>
{/if}

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_youtube_settings' gid='video_uploads'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_youtube_login' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$settings.youtube_converting_login}" name="youtube_converting_login" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_youtube_password' gid='video_uploads'}: </div>
			<div class="v"><input type="password" value="{$settings.youtube_converting_password}" name="youtube_converting_password" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_youtube_developer_key' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$settings.youtube_converting_developer_key}" name="youtube_converting_developer_key" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_youtube_source' gid='video_uploads'}: </div>
			<div class="v"><input type="text" value="{$settings.youtube_converting_source}" name="youtube_converting_source" class="middle"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<div class="btn gray fright"><div class="l"><input type="button" name="btn_reset" value="{l i='check_youtube_settings' gid='video_uploads' type='button'}" onclick="javascript: location.href='{$site_url}admin/video_uploads/youtube_settings_check';"></div></div>

</form>


{include file="footer.tpl"}