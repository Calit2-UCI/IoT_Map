{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_notifications_menu'}
<div class="actions">&nbsp;</div>
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_settings_editing' gid='notifications'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_mail_charset' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_charset}" name="mail_charset"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_mail_protocol' gid='notifications'}: </div>
			<div class="v">
				<select name="mail_protocol">{foreach item=item key=key from=$protocol_lang.option}<option value="{$key}" {if $key eq $settings_data.mail_protocol}selected{/if}>{$item}</option>{/foreach}</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_mail_mailpath' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_mailpath}" name="mail_mailpath"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_mail_smtp_host' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_smtp_host}" name="mail_smtp_host"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_mail_smtp_user' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_smtp_user}" name="mail_smtp_user"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_mail_smtp_pass' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_smtp_pass}" name="mail_smtp_pass"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_mail_smtp_port' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_smtp_port}" name="mail_smtp_port"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_mail_useragent' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_useragent}" name="mail_useragent"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_mail_from_email' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_from_email}" name="mail_from_email"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_mail_from_name' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_from_name}" name="mail_from_name"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<div class="clr"></div>
</form>
<div class="clr"></div>

<form method="post" action="{$data.action}" name="dkim_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_settings_dkim' gid='notifications'}</div>
		{if $openssl_loaded}
		<div class="row">
			<div class="h">{l i='field_dkim_domain_selector' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.dkim_domain_selector}" name="dkim_domain_selector"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_dkim_secret_key' gid='notifications'}: </div>
			<div class="v"><textarea name="dkim_private_key">{$settings_data.dkim_private_key}</textarea></div>
		</div>
		{else}
		<div class="h150">
			{l i='dkim_no_openssl' gid='notifications'}
		</div>
		{/if}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_dkim" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<div class="clr"></div>
</form>
<div class="clr"></div>

<form method="post" action="{$data.action}" name="test_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_settings_testing' gid='notifications'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_mail_to_email' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$settings_data.mail_to_email}" name="mail_to_email"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_test" value="{l i='btn_send' gid='start' type='button'}"></div></div>
	<div class="clr"></div>
</form>
<div class="clr"></div>
{include file="footer.tpl"}
