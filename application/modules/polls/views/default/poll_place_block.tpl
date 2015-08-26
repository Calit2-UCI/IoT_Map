{if !$hide_poll}
<div id="poll_block_{$poll_data.id}" class="poll_block" style="border-top: 0px;">
	<link rel="stylesheet" type="text/css" href="{$site_root}application/modules/polls/views/default/css/style.css" />

	<p>{if $language}{$poll_data.question[$language]}{else}{$poll_data.question[$cur_lang]}{/if}</p>
	<div class="poll">
		{$poll_block}
	</div>
	<script type="text/javascript">
		{literal}
			$(function() {
				loadScripts(
					"{/literal}{js module='polls' file='polls.js' return='path'}{literal}", 
					function(){
						polls = new Polls({
							siteUrl: '{/literal}{$site_url}{literal}',
							poll_id: '{/literal}{$poll_data.id}{literal}'
						});
					},
					'polls'
				);
			});
		{/literal}
	</script>
</div>
{/if}