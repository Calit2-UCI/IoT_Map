{include file="header.tpl"}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/menu/edit">{l i='link_add_menu' gid='menu'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="{$sort_links.name'}"{if $order eq 'name'} class="{$order_direction|lower}"{/if}>{l i='field_menu_name' gid='menu'}</a></th>
	<th><a href="{$sort_links.gid}"{if $order eq 'gid'} class="{$order_direction|lower}"{/if}>{l i='field_menu_gid' gid='menu'}</a></th>
	<th class="w150"><a href="{$sort_links.date_created}"{if $order eq 'date_created'} class="{$order_direction|lower}"{/if}>{l i='field_date_created' gid='menu'}</a></th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$menus}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.name}</td>
	<td>{$item.gid}</td>
	<td class="center">{$item.date_created|date_format:$page_data.date_format}</td>
	<td class="icons">
		<a href="{$site_url}admin/menu/items/{$item.id}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" border="0" alt="{l i='link_items' gid='menu'}" title="{l i='link_items' gid='menu'}"></a>
		<a href="{$site_url}admin/menu/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_menu' gid='menu'}" title="{l i='link_edit_menu' gid='menu'}"></a>
		<a href="{$site_url}admin/menu/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_menu' gid='menu' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_menu' gid='menu'}" title="{l i='link_delete_menu' gid='menu'}"></a>
	</td>
</tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
