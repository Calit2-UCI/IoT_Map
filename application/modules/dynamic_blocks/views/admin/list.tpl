{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_dynblocks_menu'}
<div class="actions">
	<ul>
		{if $allow_config_add}<li><div class="l"><a href="{$site_url}admin/dynamic_blocks/edit">{l i='link_add_dynamic_block_area' gid='dynamic_blocks'}</a></div></li>{/if}
	</ul>
	&nbsp;
</div>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">{l i='field_gid' gid='dynamic_blocks'}</th>
	<th class="">{l i='field_name' gid='dynamic_blocks'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$areas}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.gid}</td>
	<td>{$item.name}</td>
	<td class="icons">
		<a href="{$site_url}admin/dynamic_blocks/area_blocks/{$item.id}"><img src="{$site_root}{$img_folder}icon-settings.png" width="16" height="16" border="0" alt="{l i='link_edit_area_blocks' gid='dynamic_blocks'}" title="{l i='link_edit_area_blocks' gid='dynamic_blocks'}"></a>
		<a href="{$site_url}admin/dynamic_blocks/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_area' gid='dynamic_blocks'}" title="{l i='link_edit_area' gid='dynamic_blocks'}"></a>
		<a href="{$site_url}admin/dynamic_blocks/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_area' gid='dynamic_blocks' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_area' gid='dynamic_blocks'}" title="{l i='link_delete_area' gid='dynamic_blocks'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center">{l i='no_areas' gid='dynamic_blocks'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
