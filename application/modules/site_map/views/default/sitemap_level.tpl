<ul>
{foreach item=li from=$list}
<li>
	{if $li.clickable}<a href="{$li.link}">{$li.name}</a>{else}{$li.name}{/if}
	{if $li.items}{include file="sitemap_level.tpl"  module="site_map" list=$li.items}{/if}
</li>
{/foreach}
</ul>