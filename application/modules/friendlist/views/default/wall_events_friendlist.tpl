{strip}
{foreach from=$event.data item='edata' key='key'}
	{assign var='id_dest_user' value=$edata.id_dest_user}
	{if !$users[$id_dest_user]}{assign var='id_dest_user' value='0'}{/if}
	<div>{$edata.event_date|date_format:$date_format}</div>
	<div>
		<div class="user-content">
			<div class="image">
				<a href="{if !empty($id_dest_user)}{seolink module='users' method='view' data=$users[$id_dest_user]}{else}{seolink module='users' method='untitled'}{/if}">
					<img src="{$users[$id_dest_user].media.user_logo.thumbs.small}" />
				</a>
			</div>
			<div class="content">
				{l i='wall_events_and' gid='friendlist'}&nbsp;
				<a href="{if !empty($id_dest_user)}{seolink module='users' method='view' data=$users[$id_dest_user]}{else}{seolink module='users' method='untitled'}{/if}">
					{$users[$id_dest_user].output_name}
				</a>&nbsp;
				{if $event.event_type_gid == 'friend_add'}
					{l i='wall_now_friends' gid='friendlist'}
				{elseif $event.event_type_gid == 'friend_del'}
					{l i='wall_not_friends' gid='friendlist'}
				{/if}
			</div>
		</div>
	</div>
{/foreach}
{/strip}