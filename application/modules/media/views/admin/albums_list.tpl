{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='media_menu_item'}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w110">{l i='field_name' gid='media'}</th>
	<th>{l i='album_info' gid='media'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$albums}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">
		{$item.name}
		
	</td>
	<td>
		<b>{l i='media_user' gid='media'}</b>: {$item.user_info.output_name}<br>
		<b>{l i='field_permitted_for' gid='media'}</b>: {ld_option i='permissions' gid='media' option=$item.permissions}<br>
		<b>{l i='album_items' gid='media'}</b>: {$item.media_count} 
		
	</td>
	<td class="icons">
		<a href="{$site_url}admin/media/delete_album/{$item.id}"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='packages'}" title="{l i='link_delete_service' gid='packages'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_albums' gid='media'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
