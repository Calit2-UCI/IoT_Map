{include file="header.tpl"}

<form method="post" action="{$data.action}">
	<div class="filter-form">
		<h3>Welcome | Admin panel</h3>
		<div class="form">
			<br>
			<div class="row">
				<div class="h">Login: </div>
				<div class="v"><input type="text" value="{$data.login}" name="login"></div>
			</div>
			<div class="row">
				<div class="h">Password: </div>
				<div class="v"><input type="password" value="" name="password"></div>
			</div>
		
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_login" value="Sign in"></div></div>
</form>
<div class="clr"></div>
{include file="footer.tpl"}