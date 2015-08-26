{strip}
<ul id="user_top_menu">
	{foreach key=key item=item from=$menu}
		<li {if $item.active || !empty($item.in_chain)}class="active"{/if}><span class="a">{$item.value}{if $item.indicator}<span class="num">{$item.indicator}</span>{/if}</span>
			{if $item.sub}
				<ul class="submenu noPrint">
					{foreach key=key item=s from=$item.sub}
						<li {if $s.active}class="active"{/if}><a id="user_top_{$item.gid}_{$s.gid}" href="{$s.link}">{$s.value}{if $s.indicator}<span class="num">{$s.indicator}</span>{/if}</a></li>
					{/foreach}
				</ul>
			{/if}
		</li>
	{/foreach}
</ul>
{/strip}
