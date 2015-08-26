<div class="row zebra">
	<label>
		<div class="h">
			{ld_header i='flashchat_skin' gid='chats'}
		</div>
		<div class="v">
			{ld gid='chats' i='flashchat_skin'}
			<select name="settings[server_settings][by_flashchat_free][skin]">
				{foreach item=item key=key name=f from=$ld_flashchat_skin.option}
					<option {if $chat.settings.server_settings.by_flashchat_free.skin == $key} 
							selected="selected"{/if} value="{$key}">
						{$item}
					</option>
				{/foreach}
			</select>
		</div>
	</label>
</div>
<div class="row">
	<label>
		<div class="h">
			{ld_header i='flashchat_lang' gid='chats'}
		</div>
		<div class="v">
			{ld gid='chats' i='flashchat_lang'}
			<select name="settings[server_settings][by_flashchat_free][lang]">
				{foreach item=item key=key name=f from=$ld_flashchat_lang.option}
					<option {if $chat.settings.server_settings.by_flashchat_free.lang == $key} 
							selected="selected"{/if}value="{$key}">
						{$item}
					</option>
				{/foreach}
			</select>
		</div>
	</label>
</div>
<div class="row zebra">
	<div class="h">
		{l i='flashchat_chatroom' gid='chats'}:
	</div>
	<div class="v">
		<input name="settings[server_settings][by_flashchat_free][chat_room]" type="text" 
			   value="{$chat.settings.server_settings.by_flashchat_free.chat_room}" >
	</div>
</div>