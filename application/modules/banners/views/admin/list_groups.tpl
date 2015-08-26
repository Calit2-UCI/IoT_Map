{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_banners_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/banners/edit_group">{l i='link_add_group' gid='banners'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w50">{l i='field_group_id' gid='banners'}</th>
	<th>{l i='field_group_name' gid='banners'}</th>
	<th class="w70">{l i='field_group_price' gid='banners'}</th>
	<th class="w70">&nbsp;</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach from=$groups item=item}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.id}</td>
	<td>{$item.name}</td>
	<td class="center">{block name='currency_format_output' module='start' value=$item.price}</td>
	<td class="center"><a href="{$site_url}admin/banners/group_pages/{$item.id}">{l i='pages_list' gid='banners'}</a></td>
	<td class="icons">
		<a href='{$site_url}admin/banners/edit_group/{$item.id}'><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='link_edit_group' gid='banners'}" title="{l i='link_edit_group' gid='banners'}"></a>
		<a href='{$site_url}admin/banners/delete_group/{$item.id}' onclick="return confirm('{l i='note_delete_group' gid='banners' type='js'}');"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='link_delete_group' gid='banners'}" title="{l i='link_delete_group' gid='banners'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center">{l i='no_groups' gid='banners'}</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}
