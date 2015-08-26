{include file="header.tpl"}
<div class="content-block">
	<h1>{l i='polls_results' gid='polls'}</h1>
	{foreach item=item key=key from=$polls}
		<div id="poll_{$key}" class="poll_question_link">
			<div class="h2">
				{if $language}
					{if $item.question[$language]}
						{$item.question[$language]}
					{else}
						{$item.question.default}
					{/if}
				{else}
					{if $item.question[$cur_lang]}
						{$item.question[$cur_lang]}
					{else}
						{$item.question.default}
					{/if}
				{/if}
				<div class="fright">
					<a data-role="expander" class="fa-chevron down icon-big edge hover zoom20"></a>
				</div>
			</div>
		</div>
		<div class="poll_results_content"></div>
	{/foreach}
	{literal}
		<script type="text/javascript">
			$(function() {
				loadScripts(
					"{/literal}{js module='polls' file='polls.js' return='path'}{literal}", 
					function(){
						if ('undefined' != typeof(PollsList)) {
							new PollsList({
								siteUrl: '{/literal}{$site_url}{literal}'
							});
						}
					}
				);
			});
		</script>
	{/literal}
</div>
<div class="clr"></div>
{include file="footer.tpl"}
