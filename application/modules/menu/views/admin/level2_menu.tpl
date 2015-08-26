<div class="menu-level3">
	<ul>
	{foreach item=item from=$menu}
		<li{if $item.active eq 1} class="active"{/if}><a id="{$item.gid}" href="{$item.link}">{$item.value}</a></li>
	{/foreach}
	</ul>
	&nbsp;
</div>