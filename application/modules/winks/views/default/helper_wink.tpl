<a id="btn-wink-{$wink_button_rand}" class="btn-wink{if $wink_back}-back{/if} link-r-margin"
   data-user-id="{$partner_id}" data-is-pending="{$is_pending}" data-is-new="{$is_new}"
   title="{if $wink_back}{l i='wink_back' gid='winks' type='button'}{else}{l i='wink' gid='winks' type='button'}{/if}"
   href="javascript:void(0);">
	<i class='fa-eye-open icon-big edge hover{if $is_pending} g{/if}'></i>
</a>
<script>{literal}
	$(function(){
		loadScripts(
			["{/literal}{js file='winks.js' module='winks' return='path'}{literal}"],
			function(){
				winksObj = new winks({
					siteUrl: site_url,
					titleWink: '{/literal}{l i='wink' gid='winks'}{literal}',
					titleWinkBack: '{/literal}{l i='wink_back' gid='winks'}{literal}',
					errIsPending: '{/literal}{l i='error_is_pending' gid='winks'}{literal}',
					succIgnored: '{/literal}{l i='msg_ignored' gid='winks'}{literal}',
					succWinked: '{/literal}{l i='msg_winked' gid='winks'}{literal}',
					succResponded: '{/literal}{l i='msg_responded' gid='winks'}{literal}',
					wink_button_rand: '{/literal}{$wink_button_rand}{literal}'
				});
			},
			'winksObj'
		);
	});
</script>{/literal}
