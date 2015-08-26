{strip}
	<div>
		{if $event.id_wall == $user_id || $event.id_poster == $user_id}
			<a class="fright delete_wall_event" data-id="{$event.id}" data-message="{l i='confirm_delete' gid='wall_events'}" href="javascript:;">{l i='btn_delete' gid='start'}</a>
		{/if}
	</div>
	{counter start=0 print=false assign='i'}
	{foreach from=$event.data item=edata key=key}
		<div>{$edata.event_date|date_format:$date_format}{if $event.id_poster != $user_id}<span class="ml10">{block name='mark_as_spam_block' module='spam' object_id=$event.id type_gid='wall_events_object' template='minibutton'}</span>{/if}</div>

		{if $edata.text}<div>{$edata.text|nl2br}</div>{/if}

		{if $event.media[$key].img}
			<div class="wall-gallery" gallery="wall_{$event.id}">
				{foreach from=$event.media[$key].img item=item name=f}
					{counter print=false}
					<div class="ib p5"><img src="{if $i > 8}{$item.thumbs.middle}{else}{$item.thumbs.big}{/if}" gallery-src="{$item.thumbs.grand}" 
						alt="{$item.photo_alt|escape}" title="{$item.photo_title|escape}" /></div>
				{/foreach}
			</div>
		{/if}

		{if !empty($event.media[$key].video)}
			{foreach from=$event.media[$key].video item=item}
				<div class="ptb5">
					{if $item.status == 'start'}
						<div>{$item.file_name}</div>
						<div class="error-text">{l i='video_converting' gid='wall_events'}</div>
					{elseif $item.errors}
						<div>{$item.file_name}</div>
						{foreach from=$item.errors item=err}
							<div class="error-text">{$err}</div>
						{/foreach}
					{elseif $item.embed}
						<div>{$item.embed}</div>
					{/if}
				</div>
			{/foreach}
		{/if}
	{/foreach}
{/strip}
