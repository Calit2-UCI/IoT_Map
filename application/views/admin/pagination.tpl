<div class="pages">
	{if $page_data.total_rows}
		<span class="total">{l i='showing' gid='start'} {$page_data.start_num} - {$page_data.end_num} / {$page_data.total_rows}</span>
	{/if}
	&nbsp;{if isset($page_data.nav)}{$page_data.nav}{/if}
</div>