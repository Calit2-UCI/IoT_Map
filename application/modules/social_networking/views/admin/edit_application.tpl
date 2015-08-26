{include file="header.tpl" load_type='ui'}
{js file='easyTooltip.min.js'}

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_application_change' gid='social_networking'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_app_key' gid='social_networking'}: </div>
			<div class="v">
				<input type="text" value="{$data.app_key}" name="app_key" class="middle">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_app_secret' gid='social_networking'}: </div>
			<div class="v"><input type="text" value="{$data.app_secret}" name="app_secret" class="middle"></div>
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

{include file="footer.tpl"}