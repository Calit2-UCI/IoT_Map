{include file="header.tpl"}
<form method="post" action="{$data.action}">
	<div class="filter-form">
		<div class="form">
			<font>{l i='mod_installer_admin_login_text' gid='start'}</font>
			<br><br>
			<div class="row">
				<div class="h">{l i='field_login' gid='start'}: </div>
				<div class="v"><input type="text" value="{$data.login}" name="login" class="middle"></div>
			</div>
			<div class="row">
				<div class="h">{l i='field_password' gid='start'}: </div>
				<div class="v"><input type="password" value="" name="password" class="middle"></div>
			</div>
		
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_login" value="{l i='btn_login' gid='start' type='button'}"></div></div>
</form>
<div class="clr"></div>
{include file="footer.tpl"}