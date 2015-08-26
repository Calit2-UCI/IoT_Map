{strip}
<div class="like_users">
	<div class="lh0{if $like_users|@count > 14} w-scroll{/if}">
		{foreach item=user from=$like_users}
			{l i='text_user_logo' gid='likes' type='button' assign='text_user_logo' replace_array=$user}
			<a id="like_user_{$user.id}" href="{$user.link}" title="{$user.output_name|escape}"><img src="{$user.media.user_logo.thumbs.small}" alt="{$text_user_logo}" title="{$text_user_logo}"></a>
		{/foreach}
	</div>
	{if $has_more}
		<ul class="centered">
			<li class="a like_more_btn">{l i='btn_view_more' gid='start'}</li>
		</ul>
	{/if}
</div>
{/strip}
