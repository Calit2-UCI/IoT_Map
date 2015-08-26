<div class="p20 border congratulations">
	<h2>{l i='field_header_congratulations' gid='like_me'}</h2>
	<h3>{l i='field_congratulations' gid='like_me'}</h3>
	<div class="pos-rel m10 ptb20">
		{foreach item='item' from=$user_data.users}
			<div class="p5 {if $item.id eq $user_data.profile_id}second-liked{else}first-liked{/if}">
				<img src="{$item.media.user_logo.thumbs.big}" alt="{$item.nickname}" title="{$item.nickname}">
				<div>{$item.output_name}</div>
			</div>
		{/foreach}
	</div>
	<div class="clr"></div>
	<div class="mt20 pt10">{$user_data.settings.chat_message[$lang_id]}</div>
	<div class="mt20">
		<div class="pb5 mt20">
			{foreach item='set' key='key' from=$user_data.settings.chat_more}
				{if !empty($set.helper)}
					<div class="like_me-btn" data-name="{$set.name}">
						{block name=$set.helper module=$key id_user=$user_data.profile_id user_id=$user_data.profile_id id_contact=$user_data.profile_id}
					</div>
				{/if}
			{/foreach}
		</div>
		<div>
			<input id="keep_playing" type="button" name="keep_playing" value="{l i='button_keep_playing' gid='like_me'}">
		</div>
	</div>
</div>