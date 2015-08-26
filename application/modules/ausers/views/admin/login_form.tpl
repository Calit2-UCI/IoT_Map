{include file="header.tpl"}
<form method="post" action="{$data.action}">
	<div class="filter-form">
		<div class="form">
			<br>
			<div class="row">
				<div class="h">{l i='field_nickname' gid='ausers'}: </div>
				<div class="v"><input type="text" value="{if empty($data.nickname)}{if $DEMO_MODE}{$demo_login_settings.admin.login}{/if}{else}{$data.nickname|escape}{/if}" name="nickname"></div>
			</div>
			<div class="row">
				<div class="h">{l i='field_password' gid='ausers'}: </div>
				<div class="v"><input type="password" value="{if $DEMO_MODE}{$demo_login_settings.admin.password}{/if}" name="password"></div>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_login" value="{l i='btn_login' gid='start' type='button'}"></div></div>
</form>
<div class="clr"></div>
{include file="footer.tpl"}