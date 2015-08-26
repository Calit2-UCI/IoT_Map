{strip}
{foreach from=$event.data item='edata' key='key'}
	{assign var='id_dest_user' value=$edata.id_dest_user}
	{if !$users[$id_dest_user]}{assign var='id_dest_user' value='0'}{/if}
	<div>{$edata.event_date|date_format:$date_format}</div>
	<div>
		<div class="user-content">
			<div class="image">
				<a href="{seolink module='users' method='view' data=$users[$id_dest_user]}">
					<img src="{$users[$id_dest_user].media.user_logo.thumbs.small}" />
				</a>
			</div>
			<div class="content">
				{l i='wall_events_and' gid='favourites'}&nbsp;
				<a href="{seolink module='users' method='view' data=$users[$id_dest_user]}">
					{$users[$id_dest_user].output_name}
				</a>&nbsp;
				{if $event.event_type_gid == 'favourite_add'}
					{l i='wall_now_favourites' gid='favourites'}
				{elseif $event.event_type_gid == 'favourite_remove'}
					{l i='wall_not_favourite' gid='favourites'}
				{/if}
			</div>
		</div>
	</div>
{/foreach}
{/strip}