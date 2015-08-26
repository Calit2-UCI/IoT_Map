{include file="header.tpl"}
<div class="actions">
	{if $allow_config_add}
	<ul>
		<li><div class="l"><a href="{$site_url}admin/file_uploads/config_edit">{l i='link_add_config' gid='file_uploads'}</a></div></li>
	</ul>
	{/if}
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">{l i='field_gid' gid='file_uploads'}</th>
	<th class="">{l i='field_name' gid='file_uploads'}</th>
	<th class="w100">{l i='actions' gid='file_uploads'}</th>
</tr>
{foreach item=item from=$configs}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.gid}</b></td>
	<td>{$item.name}</td>
	<td class="icons">
		<a href="{$site_url}admin/file_uploads/config_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_config' gid='file_uploads'}" title="{l i='link_edit_config' gid='file_uploads'}"></a>
		{if $allow_config_add}
		<a href="{$site_url}admin/file_uploads/config_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_config' gid='file_uploads' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_config' gid='file_uploads'}" title="{l i='link_delete_config' gid='file_uploads'}"></a>
		{/if}
	</td>
</tr>
{foreachelse}
<tr><td class="center zebra" colspan=3>{l i='no_configs' gid='file_uploads'}</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}
