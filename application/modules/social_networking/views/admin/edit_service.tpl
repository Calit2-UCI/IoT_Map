{include file="header.tpl" load_type='ui'}
{js file='easyTooltip.min.js'}

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_service_change' gid='social_networking'}{else}{l i='admin_header_service_add' gid='social_networking'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_name' gid='social_networking'}: </div>
			<div class="v">
				<input type="text" value="{$data.name}" name="name">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='social_networking'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_authorize' gid='social_networking'}: </div>
			<div class="v"><input type="text" value="{$data.authorize_url}" name="authorize_url"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_access_key' gid='social_networking'}: </div>
			<div class="v"><input type="text" value="{$data.access_key_url}" name="access_key_url"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_app_enabled' gid='social_networking'}: </div>
			<div class="v"><input type="checkbox" value="1" {if $data.app_enabled}checked{/if} name="app_enabled"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_oauth_enabled' gid='social_networking'}: </div>
			<div class="v"><input type="checkbox" value="1" {if $data.oauth_enabled}checked{/if} name="oauth_enabled"></div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/social_networking/services/">{l i='btn_cancel' gid='start'}</a>
	</div>
</form>
<script type='text/javascript'>
	{literal}
	$(function(){
		$(".tooltip").each(function(){
			$(this).easyTooltip({
				useElement: 'tt_'+$(this).attr('id')
			});
		});
	});
	{/literal}
</script>

<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
	{/literal}</script>

	{include file="footer.tpl"}