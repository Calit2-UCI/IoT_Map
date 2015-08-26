{foreach item=item from=$thread}
<li>
	<a id="btn_toggle_message_{$item.id}" class="btn-toggle-message" data-id="{$item.id}"><i class="icon-caret-right g"></i></a>
	<div class="table-div">
		<dl>
			<dt class="text-overflow user">{$item.sender.output_name}</dt>
			<dt class="righted">{$item.date_add|date_format:$page_data.date_time_format}</dt>
		</dl>
	</div>
	<div class="teaser">{if $item.is_new && $read_disabled}{l i='error_not_access' gid='mailbox'}{else}{$item.message}{/if}</div>
</li>
{/foreach}
{if $thread_count}
	{l i='link_more_messages' gid='mailbox' assign='link_more_messages'}
	<li class="center"><a href="javascipt:void(0);" class="btn_thread" data-id="{$message.id}" data-direction="{$direction}" data-page="{$page}">{$link_more_messages|replace:'[messages]':$thread_count}</a></li>
{/if}
