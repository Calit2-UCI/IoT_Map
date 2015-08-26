{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_uploads_menu'}
<div class="actions">
	{if $allow_config_add}
	<ul>
		<li><div class="l"><a href="{$site_url}admin/uploads/config_edit">{l i='link_add_config' gid='uploads'}</a></div></li>
	</ul>
	{/if}
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">{l i='field_gid' gid='uploads'}</th>
	<th class="">{l i='field_name' gid='uploads'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$configs}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.gid}</b></td>
	<td>{$item.name}</td>
	<td class="icons">
		<a href="{$site_url}admin/uploads/config_thumbs/{$item.id}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" border="0" alt="{l i='link_edit_thumb' gid='uploads'}" title="{l i='link_edit_thumb' gid='uploads'}"></a>
		<a href="{$site_url}admin/uploads/config_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_config' gid='uploads'}" title="{l i='link_edit_config' gid='uploads'}"></a>
		{if $allow_config_add}
		<a href="{$site_url}admin/uploads/config_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_config' gid='uploads' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_config' gid='uploads'}" title="{l i='link_delete_config' gid='uploads'}"></a>
		{/if}
	</td>
</tr>
{foreachelse}
<tr><td class="center zebra" colspan=3>{l i='no_configs' gid='uploads'}</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}
