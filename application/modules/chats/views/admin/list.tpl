{include file="header.tpl"}
<table id="chatsList" cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first">{l i='name' gid='chats'}</th>
		<th class="w100">{l i='status' gid='chats'}</th>
		<th class="w100">{l i='activity' gid='chats'}</th>
	</tr>
	{foreach item=item from=$chats}
		<tr>
			<td class="first">
				{if $item.installed}
					<a href="{$site_url}admin/chats/settings/{$item.gid}">
						{$item.name}
					</a>
				{else}
					{$item.name}
				{/if}
			</td>
			<td>
				{if $item.installed}
					<a href="{$site_url}admin/chats/installation/{$item.gid}">
						{l i='installed' gid='chats'}
					</a>
				{elseif $item.has_files}
					<a href="{$site_url}admin/chats/installation/{$item.gid}">
						{l i='install' gid='chats'}
					</a>
				{elseif $item.vendor_url}
					<a href="{$item.vendor_url}">
						{l i='get_files' gid='chats'}
					</a>
				{else}
					{l i='no_files' gid='chats'}
				{/if}
			</td>
			<td class="center">
				{if $item.installed}
					{if $item.active}
						<a data-id="{$item.id}" href="{$site_url}admin/chats/deactivate/{$item.gid}"
						   class="deactivate" href="javascript:void(0);">
							<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0">
						</a>
					{else}
						<a data-id="{$item.id}" href="{$site_url}admin/chats/activate/{$item.gid}"
						   class="activate" href="javascript:void(0);">
							<img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0">
						</a>
					{/if}
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="7" class="center">{l i='no_chats' gid='chats'}</td></tr>
		{/foreach}
</table>
{js file='admin-chats.js' module='chats'}
{literal}
	<script type="text/javascript">
		var chats;
		$(function() {
			chats = new adminChats({
				siteUrl: '{/literal}{$site_url}{literal}'
			});
		});
	</script>
{/literal}
{include file="footer.tpl"}
