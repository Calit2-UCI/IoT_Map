{include file="header.tpl"}
<div class="content">
{strip}
<div class="content-block">
	<h1>{seotag tag='header_text'}</h1>
	<div>
		<div class="h3 line bottom">{l i='delete_account_alert' gid='users'}</div>
		<form method="POST" action="">
			<input type="submit" value="{l i='delete_account' gid='users'}" name="btn_delete" />
		</form>
	</div>
</div>
<div class="clr"></div>
{/strip}
</div>
{include file="footer.tpl"}