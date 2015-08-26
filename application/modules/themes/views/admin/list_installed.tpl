{include file="header.tpl"}
<div class="menu-level2">
	<ul>
		<li class="active"><div class="l"><a href="{$site_url}admin/themes/installed_themes">{l i='header_installed_themes' gid='themes'}</a></div></li>
		<li><div class="l"><a href="{$site_url}admin/themes/enable_themes">{l i='header_enable_themes' gid='themes'}</a></div></li>
	</ul>
	&nbsp;
</div>
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li class="{if $type eq 'user'}active{/if}{if !$filter.user} hide{/if}"><a href="{$site_url}admin/themes/installed_themes/user">{l i='filter_user_themes' gid='themes'} ({$filter.user})</a></li>
		<li class="{if $type eq 'admin'}active{/if}{if !$filter.admin} hide{/if}"><a href="{$site_url}admin/themes/installed_themes/admin">{l i='filter_admin_themes' gid='themes'} ({$filter.admin})</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">&nbsp;</th>
	<th class="w100">{l i='field_theme' gid='themes'}</th>
	<th>{l i='field_description' gid='themes'}</th>
	<th>{l i='field_default' gid='themes'}</th>
	<th>{l i='field_active' gid='themes'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$themes}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{if $item.img}<img src="{$item.img}" class="img">{/if}</td>
	<td class="center">{$item.theme}</td>
	<td><b>{$item.theme_name}</b><br>{$item.theme_description}</td>
	<td class="center">
		{if $item.default}
		<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" >
		{else}		
		<img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" >
		{/if}
	</td>
	<td class="center">
		{if $item.active}
		<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='text_activate_theme' gid='themes'}" title="{l i='text_activate_theme' gid='themes'}">
		{else}
		<a href="{$site_url}admin/themes/activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_theme' gid='themes'}" title="{l i='link_activate_theme' gid='themes'}"></a>
		{/if}	
	</td>
	<td class="icons">
		<a href="{$site_url}admin/themes/preview/{$item.theme}" target="_blank"><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" border="0" alt="{l i='link_preview_theme' gid='themes'}" title="{l i='link_preview_theme' gid='themes'}"></a>
		{if $item.setable}
		<a href="{$site_url}admin/themes/sets/{$item.id}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" border="0" alt="{l i='link_sets_theme' gid='themes'}" title="{l i='link_sets_theme' gid='themes'}"></a>
		{/if}
		<a href="{$site_url}admin/themes/view_installed/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_view_theme' gid='themes'}" title="{l i='link_view_theme' gid='themes'}"></a>
		{if !$item.active && !$item.default}
		<a href="{$site_url}admin/themes/uninstall/{$item.id}" onclick="javascript: if(!confirm('{l i='note_uninstall_theme' gid='themes' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_uninstall_theme' gid='themes'}" title="{l i='link_uninstall_theme' gid='themes'}"></a>
		{/if}
	</td>
</tr>
{foreachelse}
<tr><td colspan="6" class="center">{l i='no_themes' gid='themes'}</td></tr>
{/foreach}
</table>
{include file="footer.tpl"}
