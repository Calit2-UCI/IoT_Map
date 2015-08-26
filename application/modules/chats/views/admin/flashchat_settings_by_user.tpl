<div class="row">
	<div class="h">
		{l i='flashchat_server_type' gid='chats'}:
	</div>
	<div class="v">
		<label>
			<input name="settings[server_settings][by_user][server_type]" type="radio" value="flashchat"
				   {if $chat.settings.server_settings.by_user.server_type eq 'flashchat'}
				   checked="checked"{/if} onchange="chooseServerType('flashchat')" />
			{l i='flashchat_server_type_flashchat' gid='chats'}
		</label>
		<label>
			<input name="settings[server_settings][by_user][server_type]" type="radio" value="ppvsoftware"
				   {if $chat.settings.server_settings.by_user.server_type eq 'ppvsoftware'} 
				   checked="checked"{/if} onchange="chooseServerType('ppvsoftware')" />
			{l i='flashchat_server_type_ppvsoftware' gid='chats'}
		</label>
	</div>
</div>
<div class="row zebra">
	<div class="h">
		{l i='flashchat_init_host' gid='chats'}:
	</div>
	<div class="v">
		<input name="settings[server_settings][by_user][init_host]" type="text"
			   value="{$chat.settings.server_settings.by_user.init_host}" class="long">
	</div>
</div>
<div class="row">
	<div class="h">
		{l i='flashchat_init_port' gid='chats'}:
	</div>
	<div class="v">
		<input name="settings[server_settings][by_user][init_port]" type="text" id="init_port" 
			   value="{$chat.settings.server_settings.by_user.init_port}" class="short">
	</div>
</div>
<div class="row zebra">
	<div class="h">
		{l i='flashchat_init_port_h' gid='chats'}:
	</div>
	<div class="v">
		<input name="settings[server_settings][by_user][init_port_h]" type="text" id="init_port_h"
			   value="{$chat.settings.server_settings.by_user.init_port_h}" class="short">
	</div>
</div>
<div class="row">
	<div class="h">
		{l i='flashchat_client_location' gid='chats'}:
	</div>
	<div class="v">
		<input name="settings[server_settings][by_user][client_location]" type="text" id="client_location"
			   value="{$chat.settings.server_settings.by_user.client_location}" class="long">
	</div>
</div>
<div class="row zebra">
	<div class="h">
		{l i='flashchat_integration_url' gid='chats'}:
	</div>
	<div class="v">
		{l i='flashchat_integration_url_note' gid='chats'}:
		<p><mark>{$login_url}</mark></p>
	</div>
</div>
{if $chat.settings.server_settings.by_user.server_type eq 'flashchat'}
<div class="row" id="client_type">
	<div class="h">
		{l i='flashchat_client_type' gid='chats'}:
	</div>
	<div class="v">
		<label>
			<input name="settings[server_settings][by_user][client_type]" type="radio" value="htmlchat"
				   {if $chat.settings.server_settings.by_user.client_type eq 'htmlchat'}
				   checked="checked"{/if}>
			{l i='flashchat_client_type_htmlchat' gid='chats'}
		</label>
		<label>
			<input name="settings[server_settings][by_user][client_type]" type="radio" value="flashchat"
				   {if $chat.settings.server_settings.by_user.client_type eq 'flashchat'} 
				   checked="checked"{/if}>
			{l i='flashchat_client_type_flashchat' gid='chats'}
		</label>
	</div>
</div>
{/if}