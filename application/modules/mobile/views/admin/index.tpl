{include file="header.tpl" load_type='ui'}
<form method="post" action="" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header">{l i='admin_header_settings_url' gid='mobile'}</div>
		<div class="row">
			<div class="h">{l i='admin_settings_android_url' gid='mobile'}:</div>
			<div class="v">
				<input type="url" name="android_url" value="{$mobile_settings.android_url}" class="long" id="android_url">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='admin_settings_ios_url' gid='mobile'}:</div>
			<div class="v">
				<input type="url" name="ios_url" value="{$mobile_settings.ios_url}" class="long" id="ios_url">
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/start/menu/add_ons_items">{l i='btn_cancel' gid='start'}</a>
</form>
<script>{literal}
	$("div.row:odd").addClass("zebra");
{/literal}</script>
{include file="footer.tpl"}