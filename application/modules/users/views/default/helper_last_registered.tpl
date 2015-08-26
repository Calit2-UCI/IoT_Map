<h1>{l i='header_last_registered' gid='users'}</h1>
<div class="last-reg-block">
	{foreach item=item from=$users}
	<div class="small-user-block">
		{if $item.media.user_logo.thumbs.small}
		<div class="logo-img">
			{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$item}
			<a href="{seolink module='users' method='view' data=$item}">
				<img src="{$item.media.user_logo.thumbs.small}" alt="{$text_user_logo}" title="{$text_user_logo}" />
			</a>
		</div>
		<div class="clr"></div>
		{/if}
	</div>
	{/foreach}
	<div class="clr"></div>
	<div><a href="{seolink module='users' method='listing' data='all'}">{l i='view_all' gid='start'}</a></div>
</div>
