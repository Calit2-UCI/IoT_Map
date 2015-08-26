{foreach item=item key=key from=$pages}
<li id="module_pages_{$module_id}_{$key}" {if $item.group_id}class="inactive"{else}class="sortable"{/if}>
	<b class="name">{$item.name}</b><br><i>{$site_url}<span class="link">{$item.link}</span></i>
	{if $item.group_id}<br><font class="used_page">{l i='page_used_in_group' gid='banners'}: {$item.group_name}</font>{/if}
</li>
{/foreach}