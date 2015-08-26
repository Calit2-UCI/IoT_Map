{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_notifications_menu'}
<div class="actions">
	{if $allow_edit}
		<ul>
			<li><div class="l"><a href="{$site_url}admin/notifications/template_edit">{l i='link_add_template' gid='notifications'}</a></div></li>
		</ul>
	{/if}
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/notifications/templates/all">{l i='filter_all_templates' gid='notifications'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'text'}active{/if}{if !$filter_data.text} hide{/if}"><a href="{$site_url}admin/notifications/templates/text">{l i='filter_text_templates' gid='notifications'} ({$filter_data.text})</a></li>
		<li class="{if $filter eq 'html'}active{/if}{if !$filter_data.html} hide{/if}"><a href="{$site_url}admin/notifications/templates/html">{l i='filter_html_templates' gid='notifications'} ({$filter_data.html})</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		{*<th class="first"><a href="{$sort_links.gid'}"{if $order eq 'gid'} class="{$order_direction|lower}"{/if}>{l i='field_template_gid' gid='notifications'}</a></th>*}
		<th class="first w100"><a href="{$sort_links.name}"{if $order eq 'name'} class="{$order_direction|lower}"{/if}>{l i='field_template_name' gid='notifications'}</a></th>
		<th class="w100">{l i='field_content_type' gid='notifications'}</th>
		<th class="w100"><a href="{$sort_links.date_add}"{if $order eq 'date_add'} class="{$order_direction|lower}"{/if}>{l i='field_date_add' gid='notifications'}</a></th>
		<th class="w50">&nbsp;</th>
	</tr>
	{foreach item=item from=$templates}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			{*<td class="first center">{$item.gid}</td>*}
			<td>{$item.name}</td>
			<td class="center">{l i='field_content_type_'+$item.content_type gid='notifications'}</td>
			<td class="center">{$item.date_add|date_format:$page_data.date_format}</td>
			<td class="icons">
				<a href="{$site_url}admin/notifications/template_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_template' gid='notifications'}" title="{l i='link_edit_template' gid='notifications'}"></a>
					{if $allow_edit}
					<a href="{$site_url}admin/notifications/template_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_template' gid='notifications' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_template' gid='notifications'}" title="{l i='link_delete_template' gid='notifications'}"></a>
					{/if}
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="5" class="center">{l i='no_templates' gid='notifications'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
