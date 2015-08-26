<a id="btn-kisses-{$kisses_button_rand}" class="btn-kisses{if $kisses_back}-back{/if} link-r-margin"
   title="{l i='kiss' gid='kisses' type='button'}"
   href="javascript:void(0);">
	<i class='icon-smile icon-big edge hover'></i>
</a>

<script>{literal}
	$(function(){
		
		loadScripts(
			"{/literal}{js file='kisses.js' module='kisses' return='path'}{literal}",
			function(){
				kisses = new Kisses({
					siteUrl: site_url,
					use_form: true,
					btnForm: '{/literal}btn-kisses-{$kisses_button_rand}{literal}',
					urlGetForm: '{/literal}kisses/ajax_get_kisses/{$user_id}{literal}',
					urlSendForm: '{/literal}kisses/ajax_set_kisses/{$user_id}{literal}',
					dataType: '{/literal}{if $is_user}html{else}json{/if}{literal}',
				});
			},
			['kisses'],
			{async: false}
		);
		
	});
{/literal}</script>
