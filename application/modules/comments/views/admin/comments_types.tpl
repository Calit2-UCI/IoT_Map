{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first">{l i='field_number' gid='comments'}</th>
		<th>{l i='field_name' gid='comments'}</th>
		<th>{l i='field_char_count' gid='comments'}</th>
		<th>{l i='field_guest_access' gid='comments'}</th>
		<th>{l i='field_use_likes' gid='comments'}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$comments_types item=comments_type}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first w20 center">{$counter}</td>
			<td>{l i='ctype_'$comments_type.gid gid='comments'}</td>
			<td class="center">{$comments_type.settings.char_count}</td>
			<td class="center">
				{if $comments_type.gid!='user_avatar'}
					{if $comments_type.settings.guest_access}
						<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" alt="" title="">
					{else}
						<img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" alt="" title="">
					{/if}
				{/if}
			</td>
			<td class="center">
				{if $comments_type.settings.use_likes}
					<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" alt="" title="">
				{else}
					<img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" alt="" title="">
				{/if}
			</td>
			<td class="w150 icons">
				<span>
					<a href="javascript:void(0);" onclick="activateCommentsType({$comments_type.id}, 0, this);" {if !$comments_type.status}style="display:none;"{/if}><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" alt="{l i='link_deactivate_comments_type' gid='comments'}" title="{l i='link_deactivate_comments_type' gid='comments'}"></a>
					<a href="javascript:void(0);" onclick="activateCommentsType({$comments_type.id}, 1, this);" {if $comments_type.status}style="display:none;"{/if}><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" alt="{l i='link_activate_comments_type' gid='comments'}" title="{l i='link_activate_comments_type' gid='comments'}"></a>
				</span>
				<a href='{$site_url}admin/comments/edit_type/{$comments_type.id}'><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='link_edit_comments_type' gid='comments'}" title="{l i='link_edit_comments_type' gid='comments'}"></a>
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="8" class="center">{l i='no_comments_types' gid='comments'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}

<script type='text/javascript'>
{literal}
	function activateCommentsType(id, status, a_obj){
		$.post(
			site_url+'admin/comments/ajax_activate_type/',
			{id: id, status: status},
			function(resp){
				if(resp.status){
					$(a_obj).parent().find('a:hidden').show();
					$(a_obj).hide();
				}
			},
			'json'
		);
	}
{/literal}
</script>

{include file="footer.tpl"}
