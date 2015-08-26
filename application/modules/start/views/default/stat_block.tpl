<div class="stat-block">
	<div class="inside">
		{l i='today_on_site' gid='start'}: {foreach item=item from=$stat key=key}{$item.object_name} {$item.count}{if $key+1 !== count($stat)},{/if} {/foreach}
	</div>
</div>	