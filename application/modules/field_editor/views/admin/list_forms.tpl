{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_fields_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/field_editor/form_edit/{$type}/">{l i='link_add_form' gid='field_editor'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
	{foreach item=item from=$types}
		<li class="{if $type eq $item.gid}active{/if}"><a href="{$site_url}admin/field_editor/forms/{$item.gid}">{$item.name}</a></li>
	{/foreach}
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='form_name' gid='field_editor'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$forms}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.name}</td>
	<td class="icons">
		<a href="{$site_url}admin/field_editor/form_fields/{$item.id}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" border="0" alt="{l i='link_edit_form_fields' gid='field_editor'}" title="{l i='link_edit_form_fields' gid='field_editor'}"></a>
		<a href="{$site_url}admin/field_editor/form_edit/{$type}/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_form' gid='field_editor'}" title="{l i='link_edit_form' gid='field_editor'}"></a>
		{if !$item.is_system}<a href="{$site_url}admin/field_editor/form_delete/{$type}/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_form' gid='field_editor' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_form' gid='field_editor'}" title="{l i='link_delete_form' gid='field_editor'}"></a>{/if}
	</td>
</tr>
{foreachelse}
<tr><td colspan="2" class="center">{l i='no_forms' gid='field_editor'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}

{include file="footer.tpl"}
