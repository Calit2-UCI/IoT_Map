{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_news_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/news/edit">{l i='link_add_news' gid='news'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150"><a href="{$sort_links.date_add'}"{if $order eq 'date_add'} class="{$order_direction|lower}"{/if}>{l i='field_date_add' gid='news'}</a></th>
	<th><a href="{$sort_links.name'}"{if $order eq 'name'} class="{$order_direction|lower}"{/if}>{l i='field_name' gid='news'}</a></th>
	<th class="w100">{l i='field_news_type' gid='news'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$news}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.date_add|date_format:$page_data.date_format}</td>
	<td>{$item.name}</td>
	<td class="center">{$item.news_type}</td>
	<td class="icons">
		{if $item.status}
		<a href="{$site_url}admin/news/activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_news' gid='news'}" title="{l i='link_deactivate_news' gid='news'}"></a>
		{else}
		<a href="{$site_url}admin/news/activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_news' gid='news'}" title="{l i='link_activate_news' gid='news'}"></a>
		{/if}
		<a href="{$site_url}admin/news/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_news' gid='news'}" title="{l i='link_edit_news' gid='news'}"></a>
		<a href="{$site_url}admin/news/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_news' gid='news' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_news' gid='news'}" title="{l i='link_delete_news' gid='news'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_news' gid='news'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
