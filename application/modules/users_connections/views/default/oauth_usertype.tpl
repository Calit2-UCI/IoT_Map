{include file="header.tpl"}
{if $service_name}
	<p>{l i='first_connect_via' gid='users_connections'} {$service_name}!</p>
{/if}
<p>{l i='select_usertype' gid='users_connections'}</p>
<form action="{$site_url}users_connections/oauth_register/" method="POST">
	<input type="hidden" name="service_id" value="{$service_id}" />
	<input type="hidden" name="application_id" value="{$application_id}" />
	<input type="hidden" name="access_token" value="{$access_token}" />
	<input type="hidden" name="access_token_secret" value="{$access_token_secret}" />
	<input type="hidden" name="date_end" value="{$date_end}" />
	<input type="hidden" name="service_user_id" value="{$service_user_id}" />
	<input type="hidden" name="service_user_fname" value="{$service_user_fname}" />
	<input type="hidden" name="service_user_sname" value="{$service_user_sname}" />
	<input type="hidden" name="service_user_email" value="{$service_user_email}" />
	<select name="user_type">
		{foreach from=$user_type.option item=item key=key}
			<option value="{$key}">{$item}</option>
		{/foreach}
	</select>
	<input type="submit" name="btn_register" value="{l i='btn_ok' gid='start'}" />
</form>
{include file="footer.tpl"}