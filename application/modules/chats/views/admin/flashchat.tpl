<div id="flashchat">
	<form method="post" action="" name="save_form" enctype="multipart/form-data">
		<div class="edit-form n150">
			<div class="row header">{l i='settings' gid='chats'}</div>
			<div class="row">
				<div class="h">
					{l i='flashchat_chat_server_mode' gid='chats'}:
				</div>
				<div class="v">
					{foreach from=$chat_server_modes item=chat_server_mode}
						<label>
							<input class="server_type" name="settings[chat_server_mode]" 
								   id="server_{$chat_server_mode}" type="radio" value="{$chat_server_mode}"
								   {if $chat.settings.chat_server_mode eq $chat_server_mode}checked="checked"{/if}>
							{l i='flashchat_chat_server_mode_'+$chat_server_mode gid='chats'}
							<p class="note">
								{l i='flashchat_chat_server_mode_'+$chat_server_mode+'_note' gid='chats'}
							</p>
						</label>
					{/foreach}
				</div>
			</div>
			{foreach from=$chat_server_modes item=chat_server_mode}
				<div class="{if $chat.settings.chat_server_mode ne $chat_server_mode}hide{/if} server_settings" 
					 id="settings_{$chat_server_mode}">
					{include file="flashchat_settings_"+$chat_server_mode+".tpl" module="chats" theme="admin"}
				</div>
			{/foreach}
		</div>
		<div class="btn">
			<div class="l">
				<input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}">
			</div>
		</div>
		<a class="cancel" href="{$site_url}admin/chats">{l i='btn_cancel' gid='start'}</a>
	</form>
	<br>
	<div id="admin_panel">
		<div class="edit-form mt5">
			<div class="row header">{l i='admin_panel' gid='chats'}</div>
		</div>
		<!--<span id="admin_panel_not_available">{l i='flashchat_admin_panel_not_available' gid='chats'}</span>-->
		<object id="admin-panel" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
				codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,19,0" 
				width="100%" height="700" id="topcmm_123webmessenger">
            <param name="movie" value="{$url}">
			<param name="quality" value="high">
			<param name="menu" value="false">
			<param name="allowScriptAccess" value="always">
			<embed id="embed" src="{$url}" quality="high" menu="false" width="100%" height="700"
				   type="application/x-shockwave-flash" allowScriptAccess="always"
				   pluginspage="http://www.macromedia.com/go/getflashplayer"
				   scale="noscale" name="topcmm_123webmessenger" swLiveConnect="true"></embed>
		</object>
	</div>
	{js module="chats" file="flashchat-admin.js"}
	{literal}
		<script type="text/javascript">
			var flashchat;
			$(function() {
				flashchat = new flashchatAdmin({
					adminFile: '{/literal}{$admin_file}{literal}'
				});
				flashchat.chatSettings = {/literal}{json_encode data = $chat.settings}{literal};
			});
		</script>
	{/literal}
</div>