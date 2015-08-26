{include file="header.tpl" load_type='ui'}

{if $max_results}
	<div class="filter-form">{l i='error_no_editing' gid='polls'}</div>
{/if}
<form method="post" action="" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $poll_data.id}{l i='admin_header_polls_change' gid='polls'}{else}{l i='admin_header_polls_add' gid='polls'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_language' gid='polls'}: </div>
			<div class="v">
				<select name="language" id="poll_language" class="middle_short">
					<option value="0" {if $poll_data.language == 0}selected="selected"{/if}>{l i='all_languages' gid='polls'}</option>
					{if $languages_count > 1}
						{foreach item=item key=lang_id from=$languages}
							<option value="{$lang_id}" {if $poll_data.language == $lang_id}selected="selected"{/if}>{$item.name}</option>
						{/foreach}
					{/if}
				</select>
			</div>
		</div>
		<div class="row row-zebra">
			<div class="h">{l i='field_question' gid='polls'}: </div>
			<div class="v">
				{if $languages_count > 0}
					<div id="languages_container">
						{foreach item=item key=lang_id from=$languages}
							<div id="question_{$lang_id}" class="question p-top2 {if $poll_data.language > 0 && $poll_data.language != $lang_id}hide{/if}">
                                <input type="text" {if $max_results}disabled="disabled"{/if} class="question_input long" id="question_input_{$lang_id}" name="question[{$lang_id}]"
									   value="{if $poll_data.question[$lang_id]}{$poll_data.question[$lang_id]}{else}{$poll_data.question.default}{/if}">
								&nbsp;|&nbsp;{$item.name}
							</div>
						{/foreach}
					</div>
				{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_poll_type' gid='polls'}: </div>
			<div class="v">
				<select name="poll_type" class="middle_short">
					<option value="0" {if $poll_data.poll_type == 0}selected="selected"{/if}>{l i='poll_type_0' gid='polls'}</option>
					{foreach from=$user_types.option item=item key=key}
						<option value="{$key}"{if $poll_data.poll_type=={$key}} selected{/if}>{$item}</option>
					{/foreach}
					<option value="-1" {if $poll_data.poll_type == -1}selected="selected"{/if}>{l i='poll_type_1' gid='polls'}</option>
					<option value="-2" {if $poll_data.poll_type == -2}selected="selected"{/if}>{l i='poll_type_2' gid='polls'}</option>
				</select>
			</div>
		</div>
		<div class="row row-zebra">
			<div class="h">{l i='field_answer_type' gid='polls'}: </div>
			<div class="v">
				<select name="answer_type" class="long" {if $max_results}disabled="disabled"{/if}>
					<option value="0" {if $poll_data.answer_type == 0}selected="selected"{/if}>{l i='answer_type_0' gid='polls'}</option>
					<option value="1" {if $poll_data.answer_type == 1}selected="selected"{/if}>{l i='answer_type_1' gid='polls'}</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_sorter' gid='polls'}: </div>
			<div class="v">
				<select name="sorter" class="long">
					<option value="0" {if $poll_data.sorter == 0}selected="selected"{/if}>{l i='sorter_0' gid='polls'}</option>
					<option value="1" {if $poll_data.sorter == 1}selected="selected"{/if}>{l i='sorter_1' gid='polls'}</option>
					<option value="2" {if $poll_data.sorter == 2}selected="selected"{/if}>{l i='sorter_2' gid='polls'}</option>
				</select>
			</div>
		</div>
		<div class="row row-zebra">
			<div class="h">{l i='field_show_results' gid='polls'}: </div>
			<div class="v">
				<input type="checkbox" name="show_results" {if $poll_data.show_results}checked{/if} />
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='use_comments' gid='polls'}: </div>
			<div class="v">
				<input type="checkbox" name="use_comments" {if $poll_data.use_comments}checked{/if} />
			</div>
		</div>
		<div class="row row-zebra">
			<div class="h">{l i='field_date_start' gid='polls'}: </div>
			<div class="v">
				<input type="text" value="{$poll_data.date_start}" name="date_start" class="datepicker short_long" id="date_start">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_date_end' gid='polls'}: </div>
			<div class="v">
				<input id="use_expiration" type="checkbox" name="use_expiration" value="1" {if $poll_data.use_expiration}checked{/if}>
				<input {if !$poll_data.use_expiration}disabled{/if} type="text" value="{$poll_data.date_end}" name="date_end" class="datepicker short_long" id="date_end">
			</div>
		</div>

	</div>

	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/polls">{l i='btn_cancel' gid='start'}</a>
</form>

{literal}
	<script type="text/javascript">
		var polls;
		$(function(){
			polls = new adminPolls({
				siteUrl: '{/literal}{$site_url}{literal}'
			});
			polls.bind_events();
		});
	</script>
{/literal}

{include file="footer.tpl"}
