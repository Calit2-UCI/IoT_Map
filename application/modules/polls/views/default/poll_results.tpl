{if $poll_data.show_results}
	<div class="results_{$poll_data.id}">
		{foreach item=item key=key from=$poll_data.results}
			{assign var="item" value=$poll_data.answers_colors[$key]}
			{assign var="respond" value=$poll_data.results[$key]}
			{if !$respond}{assign var="respond" value=0}{/if}
			{if $max_results}
				<div class="poll_result" id="result_answer_{$item}">
					{if $language}{assign var="language_item" value=$key.'_'.$language}{else}{assign var="language_item" value=$key.'_'.$cur_lang}{/if}
					{$poll_data.answers_languages[$language_item]}
					<br />
					<div class="poll_progress" style="background-color: #{$item}; width: {math equation="floor((x / y) * z * 0.7)+1" x=$respond y=$max_results z=100}%;"></div>
					<div class="percent">
						{math equation="floor((x / y) * z)" x=$respond y=$max_results z=100}%
					</div>
				</div>
				<br />
			{else}
				<div class="poll_result" id="result_answer_{$item}">
					{if $language}{assign var="language_item" value=$key.'_'.$language}{else}{assign var="language_item" value=$key.'_'.$cur_lang}{/if}
					{$poll_data.answers_languages[$language_item]}
					<br />
					<div class="poll_progress" style="float: left; background-color: #{$item}; width: 1%;"></div>
					<div style="float: left; margin-left: 10px;">0%</div>
				</div>
				<br />
			{/if}
		{/foreach}
	</div>
{else}
	<p>{l i='dont_show_results_message' gid='polls'}</p>
{/if}
{if !$one_poll_place && 1 < $polls_count}
	<div class="poll_action">
		<a class="poll_link next_poll" href="javascript:void(0);">{l i='next_poll' gid='polls'}</a>
	</div>
{/if}