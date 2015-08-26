{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_contacts_menu'}
<div class="actions">&nbsp;</div>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_settings_change' gid='contact_us'}</div>
		<div class="row">
			<div class="h">{l i='field_settings_default_alert_email' gid='contact_us'}: </div>
			<div class="v"><input type="text" value="{$data.default_alert_email}" name="default_alert_email" class="long"></div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}