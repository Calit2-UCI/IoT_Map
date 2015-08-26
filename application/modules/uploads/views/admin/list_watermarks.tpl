{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_uploads_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/uploads/watermark_edit">{l i='link_add_watermark' gid='uploads'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">{l i='field_gid' gid='uploads'}</th>
	<th class="">{l i='field_name' gid='uploads'}</th>
	<th class="w70">{l i='field_wm_type' gid='uploads'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$watermarks}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.gid}</b></td>
	<td>{$item.name}</td>
	<td class="center">{$item.wm_type}</td>
	<td class="icons">
		<a href="{$site_url}admin/uploads/watermark_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_watermark' gid='uploads'}" title="{l i='link_edit_watermark' gid='uploads'}"></a>
		<a href="{$site_url}admin/uploads/watermark_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_watermark' gid='uploads' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_watermark' gid='uploads'}" title="{l i='link_delete_watermark' gid='uploads'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td class="center zebra" colspan=3>{l i='no_watermarks' gid='uploads'}</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}
