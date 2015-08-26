<form class="poll_form" name="poll_form" action="" method="POST">
	<input name="poll_id" type="hidden" value="{$poll_data.id}" />
	<div class="question_{$poll_data.id}">
		{foreach item=item key=key from=$poll_data.answers_colors}
			<div>
				{if $poll_data.answer_type}
					<input id="a_{$poll_data.id}_{$key}" class="answer answer_{$poll_data.id}_{$key}" type="checkbox" value="{$key}" name="answer[{$key}]">
				{else}
					<input id="a_{$poll_data.id}_{$key}" class="answer answer_{$poll_data.id}_{$key}" type="radio" value="{$key}" name="answer">
				{/if}
				{if $language}
					{assign var="language_item" value=$key.'_'.$language}
				{else}
					{assign var="language_item" value=$key.'_'.$cur_lang}
				{/if}
				{if !$poll_data.answers_languages[$language_item]} 
					{assign var="language_item" value=$key.'_default'}
				{/if}
				<label for="a_{$poll_data.id}_{$key}">{$poll_data.answers_languages[$language_item]}</label>
			</div>
		{/foreach}
		{if $poll_data.use_comments}
			<br />
			<div class="r">
				<div class="f">{l i='add_comment' gid='polls'}</div>
				<div class="v"><input type="text" name="poll_comment" value=""></div>
			</div>
		{/if}
	</div>
	<div class="poll_inputs">
		<input class="respond" type="button" value="{l i='respond' gid='polls'}" name="respond">
		{if !$one_poll_place && 1 < $polls_count}
			<div class="poll_action">
				<a class="poll_link next_poll" href="javascript:void(0);">{l i='next_poll' gid='polls'}</a>
			</div>
		{/if}
	</div>
</form>