{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_banners_menu'}
<div class="actions">
	<ul>
		{if $allow_config_add}<li><div class="l"><a href="{$site_url}admin/banners/edit_place">{l i='link_add_place' gid='banners'}</a></div></li>{/if}
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_place_id' gid='banners'}</th>
	<th>{l i='field_place_name' gid='banners'}</th>
	<th>{l i='field_place_keyword' gid='banners'}</th>
	<th>{l i='field_place_sizes' gid='banners'}</th>
	<th>{l i='field_place_in_rotation' gid='banners'}</th>
	<th>{l i='field_place_rotate_time' gid='banners'}</th>
	<th>&nbsp;</th>
</tr>
{foreach from=$places item='place'}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$place.id}</td>
	<td>{$place.name}</td>
	<td class="w150">{$place.keyword}</td>
	<td class="w70 center">{$place.width} x {$place.height}</td>
	<td class="w70 center">{$place.places_in_rotation}</td>
	<td class="w70 center">{if $place.rotate_time}{$place.rotate_time} sec.{else}{l i='no_rotation' gid='banners'}{/if}</td>
	<td class="icons">
		<a href='{$site_url}admin/banners/edit_place/{$place.id}'><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='link_edit_place' gid='banners'}" title="{l i='link_edit_place' gid='banners'}"></a>
		<a href='{$site_url}admin/banners/delete_place/{$place.id}' onclick="return confirm('{l i='note_delete_place' gid='banners' type='js'}');"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='link_delete_place' gid='banners'}" title="{l i='link_delete_place' gid='banners'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="7" class="center">{l i='no_place' gid='banners'}</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}
