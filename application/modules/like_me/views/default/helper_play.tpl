{if !empty($user_data)}
	<div class="media-gallery mt20 center" data-profile_id="{$user_data.id}">
		<div class="user">
			<div class="photo pos-rel">
				<div id="congratulations" class="center"></div>
				<span>
					<img src="{$user_data.media.user_logo.thumbs.grand}" alt="{$user_data.output_name}" title="{$user_data.output_name}" />
				</span>
			</div>
		</div>
		<div class="mt10 mb20">
			<a href="{seolink module='users' method='view' data=$user_data}">{$user_data.output_name}</a>,&nbsp;{$user_data.age}
		</div>
		<div id="action-button" class="text-overflow">
			<span class="mr20"><input id="skip_button" type="button" value="{l i='button_skip' gid='like_me'}"></span>
			<span class="ml20"><input id="like_button" type="button" value="{l i='button_like' gid='like_me'}"></span>		
		</div>
	</div>
{else}
	<div>
		<div class="mt20"><h2>{l i='empty_users_list' gid='like_me'}</h2></div>
		<div class="mt20">
			{foreach item='item' key='key' from=$play_more}
				{assign var='field' value='field_play_more_'+$key}
				<span class="mr20"><input type="button" value="{l i=$field gid='like_me'}" id="go-{$key}"></span>
			{/foreach}
		</div>
	</div>
{/if}