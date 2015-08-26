{include file="header.tpl"}

<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/themes/edit_set/{$id_theme}">{l i='link_add_set' gid='themes'}</a></div></li>
	</ul>
&nbsp;
</div>


<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w50">&nbsp;</th>
	<th>{l i='field_set_name' gid='themes'}</th>
	<th class="w50">{l i='field_active' gid='themes'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$sets}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><div style="margin: 2px; background-color: #{$item.color_settings.main_bg}">&nbsp;</div></td>
	<td>{$item.set_name}</td>
	<td class="center">
		{if $item.active}
		<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='text_activate_set' gid='themes'}" title="{l i='text_activate_set' gid='themes'}">
		{else}
		<a href="{$site_url}admin/themes/activate_set/{$id_theme}/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_set' gid='themes'}" title="{l i='link_activate_set' gid='themes'}"></a>
		{/if}
	</td>
	<td class="icons">
		<a href="{$site_url}admin/themes/preview/{$theme.gid}/{$item.set_gid}" target="_blank"><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" border="0" alt="{l i='link_preview_theme' gid='themes'}" title="{l i='link_preview_theme' gid='themes'}"></a>
		<a href="{$site_url}admin/themes/edit_set/{$id_theme}/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_set' gid='themes'}" title="{l i='link_edit_set' gid='themes'}"></a>
		{if !$item.active}
		<a href="{$site_url}admin/themes/delete_set/{$id_theme}/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_set' gid='themes' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_set' gid='themes'}" title="{l i='link_delete_set' gid='themes'}"></a>
		{/if}
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_sets' gid='themes'}</td></tr>
{/foreach}
</table>
{include file="footer.tpl"}
