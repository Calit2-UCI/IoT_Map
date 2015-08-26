{if $mode ne 'sort'}{include file="header.tpl"}{else}{include file="header.tpl" load_type='ui'}{/if}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_fields_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/field_editor/section_edit/{$type}/">{l i='link_add_section' gid='field_editor'}</a></div></li>
{if $mode ne 'sort'}
		<li><div class="l"><a href="{$site_url}admin/field_editor/sections/{$type}/sort">{l i='link_sorting_mode' gid='field_editor'}</a></div></li>
{else}
		<li><div class="l"><a href="{$site_url}admin/field_editor/sections/{$type}/">{l i='link_view_mode' gid='field_editor'}</a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false">{l i='link_save_sorting' gid='field_editor'}</a></div></li>
{/if}
	</ul>
	&nbsp;
</div>

{if $mode ne 'sort'}

<div class="menu-level3">
	<ul>
	{foreach item=item from=$types}
		<li class="{if $type eq $item.gid}active{/if}"><a href="{$site_url}admin/field_editor/sections/{$item.gid}">{$item.name}</a></li>
	{/foreach}
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_section_name' gid='field_editor'}</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item from=$sections}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.name}</td>
	<td class="icons">
		<a href="{$site_url}admin/field_editor/section_edit/{$type}/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_section' gid='field_editor'}" title="{l i='link_edit_section' gid='field_editor'}"></a>
		<a href="{$site_url}admin/field_editor/section_delete/{$type}/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_section' gid='field_editor' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_section' gid='field_editor'}" title="{l i='link_delete_section' gid='field_editor'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_sections' gid='field_editor'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}

{else}
<div id="menu_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
	{foreach item=item from=$sections}
	<li id="item_{$item.id}">{$item.name}</li>
	{/foreach}
	</ul>
</div>
{js file='admin-multilevel-sorter.js'}
<script type='text/javascript'>{literal}
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: '{/literal}{$site_url}{literal}',
			onActionUpdate: false,
			urlSaveSort: 'admin/field_editor/ajax_section_sort'
		});
	});
{/literal}</script>

{/if}


{include file="footer.tpl"}
