{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_video_menu'}
<div class="actions">&nbsp;</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">{l i='field_gid' gid='video_uploads'}</th>
	<th class="">{l i='field_name' gid='video_uploads'}</th>
	<th class="w100">{l i='field_upload_type' gid='video_uploads'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$configs}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center"><b>{$item.gid}</b></td>
	<td>{$item.name}</td>
	<td class="center">
		{if $item.upload_type eq 'local'}{l i='field_upload_type_local' gid='video_uploads'}
		{elseif $item.upload_type eq 'youtube'}{l i='field_upload_type_youtube' gid='video_uploads'}
		{/if}
	</td>
	<td class="icons">
		<a href="{$site_url}admin/video_uploads/config_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_config' gid='video_uploads'}" title="{l i='link_edit_config' gid='video_uploads'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td class="center zebra" colspan=3>{l i='no_configs' gid='video_uploads'}</td></tr>
{/foreach}
</table>

{include file="footer.tpl"}
