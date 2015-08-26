{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_spam_menu'}
<div class="actions">
	&nbsp;
</div>
<form method="post" action="{$site_url}admin/spam/settings" name="save_form">
	<div class="edit-form n150">		
		<div class="row header">{l i='admin_header_settings' gid='spam'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_send_mail' gid='spam'}: </div>
			<div class="v"><input type="text" name="send_alert_to_email" class="middle" value="{$data.send_alert_to_email|escape}"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
</form>
<div class="clr"></div>
{include file="footer.tpl"}
