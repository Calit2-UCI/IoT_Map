{if $currencies|count > 1}
<li>
	<select onchange="javascript: load_currency(this.value);">
		{foreach item=item from=$currencies}<option value="{$item.id}" {if $item.is_default} selected{/if}>{$item.gid}</option>{/foreach}
	</select>
	<script>{literal}
		var currency_url = '{/literal}{$site_url}users/change_currency/{literal}';
		function load_currency(value){
			location.href = currency_url + value;
		}
	{/literal}</script>
</li>
{/if}
