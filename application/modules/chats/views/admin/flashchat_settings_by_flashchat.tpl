<div class="row">
	<div class="h">
		{l i='flashchat_client_type' gid='chats'}:
	</div>
	<div class="v">
		<label>
			<input name="settings[server_settings][by_flashchat][client_type]" type="radio" value="htmlchat" 
				   {if $chat.settings.server_settings.by_flashchat.client_type eq 'htmlchat'}checked="checked"{/if}>
			{l i='flashchat_client_type_htmlchat' gid='chats'}
		</label>
		<label>
			<input name="settings[server_settings][by_flashchat][client_type]" type="radio" value="flashchat"
				   {if $chat.settings.server_settings.by_flashchat.client_type eq 'flashchat'} checked="checked"{/if}>
			{l i='flashchat_client_type_flashchat' gid='chats'}
		</label>
	</div>
</div>
<div class="row zebra">
	<div class="h">
		{l i='flashchat_client_location' gid='chats'}:
	</div>
	<div class="v">
		<input name="settings[server_settings][by_flashchat][client_location]" type="text" 
			   value="{$chat.settings.server_settings.by_flashchat.client_location}" class="long">
        <p>{l i='flashchat_by_flashchat_client_example' gid='chats'}:</p>
        <p>{l i='flashchat_by_flashchat_client_note' gid='chats'}</p>
	</div>
</div>
<div class="row">
	<div class="h">
		{l i='flashchat_integration_url' gid='chats'}:
	</div>
	<div class="v">
		{l i='flashchat_integration_url_note' gid='chats'}:
		<p><mark>{$login_url}</mark></p>
	</div>
</div>
