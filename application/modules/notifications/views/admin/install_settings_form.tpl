<div>
	{foreach item=item from=$settings_errors}
		<font class="req">{$item}</font><br>
	{/foreach}
</div>
<div class="form">
	<div class="row">
		<div class="h">{l i='field_mail_charset' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_charset}" name="mail_charset"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_protocol' gid='notifications'}: </div>
		<div class="v">
			<select name="mail_protocol">{foreach item=item key=key from=$protocol_lang.option}<option value="{$key}">{$item}</option>{/foreach}</select>
		</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_mailpath' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_mailpath}" name="mail_mailpath"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_smtp_host' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_smtp_host}" name="mail_smtp_host"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_smtp_user' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_smtp_user}" name="mail_smtp_user"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_smtp_pass' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_smtp_pass}" name="mail_smtp_pass"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_smtp_port' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_smtp_port}" name="mail_smtp_port"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_useragent' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_useragent}" name="mail_useragent"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_from_email' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_from_email}" name="mail_from_email"></div>
	</div>
	<div class="row">
		<div class="h">{l i='field_mail_from_name' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.mail_from_name}" name="mail_from_name"></div>
	</div>
	{if $openssl_loaded}
	<br>
	<div class="row header"><b>{l i='admin_header_settings_dkim' gid='notifications'}</b></div>
	<div class="row">
		<div class="h">{l i='field_dkim_domain_selector' gid='notifications'}: </div>
		<div class="v"><input type="text" value="{$settings_data.dkim_domain_selector}" name="dkim_domain_selector"></div>
	</div>
	<div class="row zebra">
		<div class="h">{l i='field_dkim_secret_key' gid='notifications'}: </div>
		<div class="v"><textarea name="dkim_private_key">{$settings_data.dkim_private_key}</textarea></div>
	</div>
	{/if}
</div>
