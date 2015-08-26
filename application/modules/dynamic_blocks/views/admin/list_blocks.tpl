{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_dynblocks_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/dynamic_blocks/edit_block">{l i='link_add_dynamic_block' gid='dynamic_blocks'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">{l i='field_gid' gid='dynamic_blocks'}</th>
	<th class="w250">{l i='field_name' gid='dynamic_blocks'}</th>
	<th class="">{l i='field_method' gid='dynamic_blocks'}</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item from=$blocks}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.gid}</td>
	<td>{l i=$item.name_i gid=$item.lang_gid}</td>
	<td class="center">{$item.model}::{$item.method}</td>
	<td class="icons">
		<a href="{$site_url}admin/dynamic_blocks/edit_block/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_block' gid='dynamic_blocks'}" title="{l i='link_edit_block' gid='dynamic_blocks'}"></a>
		<a href="{$site_url}admin/dynamic_blocks/delete_block/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_block' gid='dynamic_blocks' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_block' gid='dynamic_blocks'}" title="{l i='link_delete_block' gid='dynamic_blocks'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_blocks' gid='dynamic_blocks'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
