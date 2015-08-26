{include file="header.tpl"}

{assign var="language" value=$poll_data.language}

{helper func_name='show_poll_results_block' helper_name='polls' func_param=$poll.id}

<br />

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/polls/results/{$poll.id}/all">{l i='poll_type_0' gid='polls'} ({$filter_data.all})</a></li>
		{foreach from=$user_types item=item key=key}
			<li class="{if $filter eq $item}active{/if}{if !$filter_data[$item]} hide{/if}"><a href="{$site_url}admin/polls/results/{$poll.id}/{$item}">{$item} ({$filter_data[$item]})</a></li>
		{/foreach}
		<li class="{if $filter eq 'authorized'}active{/if}{if !$filter_data.authorized} hide{/if}"><a href="{$site_url}admin/polls/results/{$poll.id}/authorized">{l i='poll_type_1' gid='polls'} ({$filter_data.authorized})</a></li>
		<li class="{if $filter eq 'not_authorized'}active{/if}{if !$filter_data.not_authorized} hide{/if}"><a href="{$site_url}admin/polls/results/{$poll.id}/not_authorized">{l i='poll_type_2' gid='polls'} ({$filter_data.not_authorized})</a></li>
	</ul>
	&nbsp;
</div>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first"><a href="{$sort_links.user_id}"{if $order eq 'user_id'} class="{$order_direction|lower}"{/if}>{l i='user' gid='polls'}</a></th>
		<th><a href="{$sort_links.date_add}"{if $order eq 'date_add'} class="{$order_direction|lower}"{/if}>{l i='respond_date' gid='polls'}</a></th>
		<th><a href="{$sort_links.ip}"{if $order eq 'ip'} class="{$order_direction|lower}"{/if}>{l i='respond_ip' gid='polls'}</a></th>

		{foreach item=item key=key from=$answers_links}
			<th><a href="{$item}"><div style="display:inline-table;width:20px;height:10px;background-color:#{$poll.answers_colors[$key]};"></div><a style="display:inline;line-height:10px;" {if $order eq $key} class="{$order_direction|lower}"{/if}></a></a></th>
		{/foreach}
		<th>{l i='comment' gid='polls'}</th>
	</tr>
	{foreach item=item key=key from=$results_data}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first center tooltip" id="hide_{$key}" title="">
				{if !$item.user_id}
					{l i='guest' gid='polls'}
				{else}
					{$item.user.nickname}
					{if $item.user_type}
						({$item.user.user_type_str})
					{/if}
				{/if}
				<span id="span_hide_{$key}" class="hide">
					<div class="tooltip-info">
						<b>{l i='browser' gid='polls'}: {$item.agent}</b>
					</div>
				</span>
			</td>
			<td>{$item.response_date}</td>
			<td>{$item.ip}</td>

			{foreach item=a_item key=a_key from=$answers_links}
				{assign var="answer_item" value='answer_'.$a_key}
				<td class="center">
				{if $item[$answer_item]}
					<div style="display:inline-table;width:20px;height:10px;background-color:#{$poll.answers_colors[$a_key]};"></div>
				{/if}
				</td>
			{/foreach}
			<td>{$item.comment}</td>
		</tr>
	{foreachelse}
		<tr><td colspan="7" class="center">{l i='no_results' gid='polls'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}
{js file='easyTooltip.min.js'}
{literal}
	<script type="text/javascript">
		$(function(){
			$('input#grouping_all').bind('click', function(){
				var checked = $(this).is(':checked');
				if(checked){
					$('input[type=checkbox]').prop('checked', true);
				}else{
					$('input[type=checkbox]').prop('checked', false);
				}
			});
			$(function(){
				$(".tooltip").each(function(){
					$(this).easyTooltip({
						useElement: 'span_'+$(this).attr('id')
					});
				});
			});
		});
	</script>
{/literal}

{include file="footer.tpl"}
