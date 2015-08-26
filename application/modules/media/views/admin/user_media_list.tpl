{include file="header.tpl"}
<div class="menu-level2">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/users/edit/personal/{$user_id}">{l i='table_header_personal' gid='users'}</a></div></li>
		{if $sections}
			{foreach item=item key=key from=$sections}
			<li><div class="l"><a href="{$site_url}admin/users/edit/{$item.gid}/{$user_id}">{$item.name}</a></div></li>
			{/foreach}
		{/if}
		{depends module=seo_advanced}
			<li><a href="{$site_url}admin/users/edit/seo/{$user_id}">{l i='filter_section_seo' gid='seo'}</a></li>
		{/depends}
		<li class="active"><a href="{$site_url}admin/media/user_media/{$user_id}/{$param}">{l i='filter_section_uploads' gid='media'}</a></li>
	</ul>
	&nbsp;
</div>
<div class="actions">&nbsp;</div>
<div class="menu-level3">
	<ul>
		<li {if $param eq 'photo'}class="active"{/if}><a href="{$site_url}admin/media/user_media/{$user_id}/photo">{l i='filter_section_photos' gid='media'}</a></li>
		<li {if $param eq 'video'}class="active"{/if}><a href="{$site_url}admin/media/user_media/{$user_id}/video">{l i='filter_section_videos' gid='media'}</a></li>
	</ul>
	&nbsp;
</div>
{strip}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w110">{l i='field_files' gid='media'}</th>
		<th>{l i='media_info' gid='media'}</th>
		<th class="w100">&nbsp;</th>
	</tr>
	{foreach item=item from=$media}
	{counter print=false assign=counter}
	<tr{if $counter is div by 2} class="zebra"{/if}>
		<td class="first center">
			{if $item.media}
				<a href="{$item.media.mediafile.file_url}" target="_blank"><img src="{$item.media.mediafile.thumbs.small}"/></a>
			{/if}
			{if $item.video_content}
				<span onclick="vpreview = new loadingContent({literal}{'closeBtnClass': 'w'}{/literal}); vpreview.show_load_block('{$item.video_content.embed|escape}');"><img class="pointer" src="{$item.video_content.thumbs.small}"/></span>
				{*<img src="{$item.video_content.thumbs.small}"/>*}
			{/if}
		</td>
		<td>
			<b>{l i='media_owner' gid='media'}</b>: {$item.owner_info.output_name}<br>			
			<b>{l i='media_user' gid='media'}</b>: {$item.user_info.output_name}<br>			
			<b>{l i='field_permitted_for' gid='media'}</b>: {ld_option i='permissions' gid='media' option=$item.permissions}
		</td>
		<td class="icons">
			<a href="{$site_url}admin/media/delete_media/{$item.id}"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='packages'}" title="{l i='link_delete_service' gid='packages'}"></a>
		</td>
	</tr>
	{foreachelse}
	<tr><td colspan="4" class="center">{l i='no_media' gid='media'}</td></tr>
	{/foreach}
</table>
{/strip}
{include file="pagination.tpl"}

<div class="clr"></div>
<a class="cancel" href="{$back_url}">{l i='btn_cancel' gid='start'}</a>

{include file="footer.tpl"}
