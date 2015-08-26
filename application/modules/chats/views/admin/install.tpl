{include file="header.tpl"}
<a href="javascript:void(0);" onclick="document.getElementById('adminPage').contentWindow.location.reload(true);">{l i='btn_refresh' gid='start'}</a>
{$chat_block}
<div class="btn">
	<div class="l">
		<a href="{$site_url}admin/chats/set_install/{$chat.gid}" type="submit" name="btn_save">
			{l i='installed' gid='chats' type='button'}
		</a>
	</div>
</div>
<a class="cancel" href="{$site_url}admin/chats">{l i='btn_cancel' gid='start'}</a>
{include file="footer.tpl"}