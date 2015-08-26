{strip}
{foreach from=$events item=e}
	{if $e.html}
		{assign var=e_user_id value=$e.id_poster}
		{if $users[$e_user_id]}
			{assign var=e_user value=$users[$e_user_id]}
		{else}
			{assign var=e_user value=$users[0]}
		{/if}
		<div id="wall_event_{$e.id}" class="user-content" gid="{$e.event_type_gid}">
			<div class="image small">
				{l i='text_wall_photo' gid='wall' type='button' assign='text_wall_photo' replace_array=$e}
				<a href="{if !empty($e_user.id)}{seolink module='users' method='view' data=$e_user}{else}{seolink module='users' method='untitled'}{/if}"><img id="avatar_{$e_user.id}_e_{$e.id}"  src="{$e_user.media.user_logo.thumbs.small}" alt="{$text_wall_photo}" title="{$text_wall_photo}" /></a>
			</div>
			<div class="content">
				<div class="fleft"><a href="{if !empty($e_user.id)}{seolink module='users' method='view' data=$e_user}{else}{seolink module='users' method='untitled'}{/if}">{$e_user.output_name}</a>&nbsp;&nbsp;</div>
				{$e.html}
				<span class="fright">{block name='like_block' module='likes' gid='wevt'.$e.id type='button'}</span>
				<div>{block name='comments_form' module='comments' gid='wall_events' id_obj=$e.id hidden=1 count=$e.comments_count}</div>
			</div>
		</div>
	{/if}
{/foreach}
{/strip}
