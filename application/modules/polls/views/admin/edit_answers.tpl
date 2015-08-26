{include file="header.tpl" load_type='ui'}
{if $poll_data.language}
    {assign var="cur_lang" value=$poll_data.language}
{/if}
{js file='colorsets/jscolor/jscolor.js'}
{literal}
    <script type="text/javascript">
		var polls;
        $(function(){
			polls = new adminPollsAnswers({
				siteUrl: '{/literal}{$site_url}{literal}',
				counter: '{/literal}{$answers_count}{literal}',
				show_results: '{/literal}{$poll_data.show_results}{literal}'
			});
		});
	</script>
{/literal}
{if $responds_count}
    <div class="filter-form">{l i='error_no_editing' gid='polls'}</div>
{/if}
<div class="lef">
	<form method="post" action="" name="save_form" enctype="multipart/form-data">
        {assign var="language" value=$poll_data.language}
        <div class="edit-form n100">
			<div class="row header">{l i='admin_header_answers_change' gid='polls'}</div>
			<div class="row">
				<div class="h">{l i='field_question' gid='polls'}: </div>
				<div class="v">
					{if $language && $poll_data.question[$language]}
						{$poll_data.question[$language]}
					{elseif $poll_data.question[$cur_lang]}
						{$poll_data.question[$cur_lang]}
					{else}
						{$poll_data.question.default}
					{/if}
				</div>
			</div>
			{foreach item=answer key=i from=$poll_data.answers_colors}
				<div id="row_answer_{$i}" class="row row-zebra">
					<div class="h">{l i='field_answer' gid='polls'}: </div>
					<div class="v">
						{if !$responds_count}
							<a id="delete_{$i}" class="delete_answer fright" href="javascript:void(0);">
								<img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_answer' gid='polls'}" title="{l i='link_delete_answer' gid='polls'}" />
							</a>
						{/if}
						<div id="languages_container">
							{foreach item=item key=lang_id from=$languages}
								<div id="answer_{$i}_{$lang_id}" class="question p-top2 {if $poll_data.language > 0 && $poll_data.language != $lang_id}hide{/if}">
									{assign var="language_item" value=$i.'_'.$lang_id}
									{assign var="value" value=$poll_data.answers_languages[$language_item]}
									{if !$value}
										{assign var="language_item" value=$i.'_default'}
										{assign var="value" value=$poll_data.answers_languages[$language_item]}
									{/if}
									<input {if $responds_count}disabled="disabled"{/if}
											{if $lang_id == $cur_lang}id="answer_{$i}"{else}id="answer_{$i}_input_{$lang_id}"{/if}
											class="answer_{$i}_input middle_short {if $lang_id == $cur_lang}default_answer{/if}"
											type="text" value="{$value}" name="answer[{$i}_{$lang_id}]" />
									&nbsp;|&nbsp;{$item.name}
								</div>
							{/foreach}
						</div>
						<div class="answer_side">
							# <input id="color_answer_{$i}" class="color_input color-pick" type="text" value="{$poll_data.answers_colors[$i]}" name="answers_colors[{$i}]">
						</div>
					</div>
				</div>
			{/foreach}
			{if !$responds_count}
				<a href="javascript:void(0);" id="add_answer">{l i='add_answer' gid='polls'}</a>
			{/if}
		</div>

		<div class="btn">
			<div class="l">
				<input class="answer_input" type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}">
			</div>
		</div>
		<a class="cancel" href="{$site_url}admin/polls/">{l i='btn_cancel' gid='start'}</a>
	</form>
	{*<Template for new answer>*}
	<div id="answer_tpl" class="hide">
		<div id="row_answer_-id-" class="row row-zebra">
			<div class="h">{l i='field_answer' gid='polls'}: </div>
			<div class="v">
				{if !$responds_count}
					<a id="delete_-id-" class="delete_answer fright" href="javascript:void(0);">
						<img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_answer' gid='polls'}" title="{l i='link_delete_answer' gid='polls'}" />
					</a>
				{/if}
				<div id="languages_container">
					{foreach item=item key=lang_id from=$languages}
						{*counter print=false assign=counter*}
						<div id="answer_-id-_{$lang_id}" class="question p-top2 {if $poll_data.language > 0 && $poll_data.language != $lang_id}hide{/if}">
							{assign var="language_item" value='-id-_'.$lang_id}
							<input {if $responds_count}disabled="disabled"{/if}
									{if $lang_id == $cur_lang}id="answer_-id-"{else}id="answer_-id-_input_{$lang_id}"{/if}
									class="answer_-id-_input answer_input {if $lang_id == $cur_lang}default_answer{/if}"
									type="text" value="{$poll_data.answers_languages[$language_item]}" name="answer[-id-_{$lang_id}]" />
							&nbsp;|&nbsp;{$item.name}
						</div>
					{/foreach}
				</div>
				<div class="answer_side">
					# <input id="color_answer_-id-" class="color_input color-pick" type="text" value="{$poll_data.answers_colors[-id-]}" name="answers_colors[-id-]">
				</div>
			</div>
		</div>
	</div>
	{*</Template for new answer>*}
</div>

<div class="ref">
	<div class="preview edit-form n150">
		<div class="poll">
			<div class="row header">
				{l i='admin_header_preview_poll' gid='polls'}</div>
			<div id="question" >
				<div class="row">
					<div class="h">
						{if $language}{$poll_data.question[$language]}{else}{$poll_data.question[$cur_lang]}{/if}
					</div>
				</div>
				{foreach item=answer key=i from=$poll_data.answers_colors}
					{assign var="language_item" value=$i.'_'.$lang_id}
					<div class="row" id="preview_answer_{$i}">
						<div class="h">
							<input id="r_{$i}" class="answer_{$i}" type="{if $poll_data.answer_type == 1}checkbox{else}radio{/if}" value="{$i}" name="answer">
							<label for="r_{$i}">{if $answers_languages[$language_item]}{$answers_languages[$language_item]}{/if}</label>
						</div>
					</div>
				{/foreach}
				{*<Template for new preview>*}
				<div id="preview_tpl" class="hide">
					<div class="row" id="preview_answer_-id-">
						<div class="h">
							<input id="r_-id-" class="answer_-id-" type="{if $poll_data.answer_type == 1}checkbox{else}radio{/if}" value="-id-" name="answer">
							<label for="r_-id-">{if $answers_languages[$language_item]}{$answers_languages[$language_item]}{/if}</label>
						</div>
					</div>
				</div>
				{*</Template for new preview>*}
			</div>
		</div>
		{if $poll_data.show_results}
			<div class="results">
				<div class="row header">{l i='admin_header_preview_poll_results' gid='polls'}</div>
				<div id="results">
					<p>{if $language}{$poll_data.question[$language]}{else}{$poll_data.question[$cur_lang]}{/if}</p>
					<div id="results_answers"></div>
				</div>
			</div>
		{/if}
	</div>
</div>

{literal}
	<script type="text/javascript">
		$(function(){
			polls.properties.counter = '{/literal}{$answers_count}{literal}';
			polls.bind_events();
		});
	</script>
{/literal}

{include file="footer.tpl"}
