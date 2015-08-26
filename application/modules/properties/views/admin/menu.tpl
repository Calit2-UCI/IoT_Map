{include file="header.tpl"}
{foreach item=item key=gid from=$properties}
<div class="settings-block with-{$gid}" onclick="javascript: location.href='{$site_url}admin/properties/property/{$gid}';">
	<a href="{$site_url}admin/properties/property/{$gid}"><h6>{$item.header}</h6></a>
</div>
{/foreach}
{include file="footer.tpl"}
