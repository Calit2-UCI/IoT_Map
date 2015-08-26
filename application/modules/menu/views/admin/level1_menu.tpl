<div class="menu-level2">
	<ul>
	{foreach item=item from=$menu}
		<li{if $item.active eq 1} class="active"{/if}>
			<div class="l">
				<a id="{$item.gid}" href="{$item.link}">{$item.value}
					{if $item.indicator}<span class="num">{$item.indicator}</span>{/if}
				</a>
			</div>
		</li>
	{/foreach}
	</ul>
	&nbsp;
</div>