<span id="friendlist_{$friendlist_button_rand}">
	<span id="friendlist_links_{$friendlist_button_rand}">
		{foreach from=$buttons item='btn' key='btn_name'}
			<a href="{seolink module='friendlist' method=$btn.method destination_user_id=$id_dest_user}" 
			   class="link-r-margin"
			   data-pjax="0" method="{$btn.method}" onclick="event.preventDefault();" 
			   title="{l i='action_'+$btn_name gid='friendlist'}" data-user_id="{$id_dest_user}"><i class="icon-{$btn.icon} icon-big edge hover zoom30">
					{if $btn.icon_stack}<i class="fa-mini-stack icon-{$btn.icon_stack}"></i>{/if}</i></a>
		{/foreach}
	</span>
	<script>{literal}
		$(function() {
			loadScripts(
				"{/literal}{js module='friendlist' file='lists_links.js' return='path'}{literal}",
				function() {
					var id_dest_user = parseInt('{/literal}{$id_dest_user}{literal}');
					var button_rand = parseInt('{/literal}{$friendlist_button_rand}{literal}');
					lists_links = new ListsLinks({
						siteUrl: site_url,
						id_dest_user: id_dest_user,
						button_rand: button_rand,
						url: 'friendlist/'
					});
				},
				'lists_links',
				{async: false}
			);
		});
	</script>{/literal}
</span>
