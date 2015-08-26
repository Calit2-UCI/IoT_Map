{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_uploads_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/uploads/thumb_edit/{$config_id}">{l i='link_add_thumb' gid='uploads'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_prefix' gid='uploads'}</th>
	<th class="w150">{l i='field_sizes' gid='uploads'}</th>
	<th class="w100">{l i='field_thumb_watermark' gid='uploads'}</th>
	<th class="w70">{l i='field_resize_type' gid='uploads'}</th>
	<th class="w100">{l i='field_date_add' gid='uploads'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$thumbs}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.prefix}</b></td>
	<td class="center">{$item.width}x{$item.height}</td>
	<td class="center">{if $item.watermark_id}<img src="{$site_root}{$img_folder}icon-full.png">{else}&nbsp;{/if}</td>
	<td class="center">{$item.crop_param}</td>
	<td class="center">{$item.date_add}</td>
	<td class="icons">
		<a href="{$site_url}admin/uploads/thumb_edit/{$config_id}/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_thumb' gid='uploads'}" title="{l i='link_edit_thumb' gid='uploads'}"></a>
		<a href="{$site_url}admin/uploads/thumb_delete/{$config_id}/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_thumb' gid='uploads' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_thumb' gid='uploads'}" title="{l i='link_delete_thumb' gid='uploads'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td class="center zebra" colspan=6>{l i='no_thumbs' gid='uploads'}</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}
