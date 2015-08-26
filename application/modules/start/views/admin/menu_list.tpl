{include file="header.tpl"}
{foreach item=item from=$options}
<div id="{$item.gid}_block" class="settings-block{if $item.gid} with-{$item.gid}{/if}" onclick="javascript: location.href='{$item.link}';">
	<a id="{$item.gid}" href="{$item.link}"><h6>{$item.value}</h6></a>
	<div>{$item.tooltip}</div>
</div>
{/foreach}
{include file="footer.tpl"}