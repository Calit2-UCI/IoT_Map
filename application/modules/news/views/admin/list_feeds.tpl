{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_news_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/news/feed_edit">{l i='link_add_feeds' gid='news'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if $id_lang eq 0}active{/if}{if !$filter_data.0} hide{/if}"><a href="{$site_url}admin/news/feeds/0">{l i='filter_all_feeds' gid='news'} ({$filter_data.0})</a></li>
		{foreach item=item key=lid from=$languages}
		<li class="{if $lid eq $id_lang}active{/if}{if !$filter_data[$lid]} hide{/if}"><a href="{$site_url}admin/news/feeds/{$lid}">{$item.name} ({$filter_data[$lid]})</a></li>
		{/foreach}
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="{$sort_links.date_add'}"{if $order eq 'date_add'} class="{$order_direction|lower}"{/if}>{l i='field_date_add' gid='news'}</a></th>
	<th>{l i='field_feed_title' gid='news'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$feeds}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center w150">{$item.date_add|date_format:$page_data.date_format}</td>
	<td><b>{$item.title}</b>{if $item.description}<br><i>{$item.description}</i>{/if}</td>
	<td class="icons">
		{if $item.status}
		<a href="{$site_url}admin/news/feed_activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_feed' gid='news'}" title="{l i='link_deactivate_feed' gid='news'}"></a>
		{else}
		<a href="{$site_url}admin/news/feed_activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_feed' gid='news'}" title="{l i='link_activate_feed' gid='news'}"></a>
		{/if}
		<a href="{$site_url}admin/news/feed_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_feed' gid='news'}" title="{l i='link_edit_feed' gid='news'}"></a>
		<a href="{$site_url}admin/news/feed_parse/{$item.id}"><img src="{$site_root}{$img_folder}icon-play.png" width="16" height="16" border="0" alt="{l i='link_parse_feed' gid='news'}" title="{l i='link_parse_feed' gid='news'}"></a>
		<a href="{$site_url}admin/news/feed_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_feed' gid='news' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_feed' gid='news'}" title="{l i='link_delete_feed' gid='news'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="3" class="center">{l i='no_feeds' gid='news'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
