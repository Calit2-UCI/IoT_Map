{if $breadcrumbs}
	<div class="breadcrumb">
		<a href="{$site_url}">{l i='header_start_page' gid='start'}</a>
		{foreach item=item from=$breadcrumbs}
			&nbsp;>&nbsp;{if $item.url}<a id="breadcrumb_{$item.gid}" href="{$item.url}">{$item.text}</a>{else}{$item.text}{/if}
		{/foreach}
	</div>
{/if}