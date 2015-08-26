{include file="header.tpl"}

<form method="post" action="{$data.action}">
	<div class="filter-form">
		<h3>Errors:</h3>
		<textarea readonly cols="90" rows="40">{foreach item=item from=$errors}{$item}
______________________________________{/foreach}</textarea>
	</div>
	<div class="btn"><div class="l"><a href="{$site_url}admin/install/sql">Refresh</a></div></div>
</form>
<div class="clr"></div>
{include file="footer.tpl"}