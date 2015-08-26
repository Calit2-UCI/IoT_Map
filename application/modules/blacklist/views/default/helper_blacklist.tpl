<span id="block_user_{$id_dest_user}">
	<a class="link-r-margin add_to_blacklist{if $action eq 'remove'} hide{/if}" data-userId="{$id_dest_user}" 
	   href="{seolink module='blacklist' method='add' destination_user_id=$id_dest_user}" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="{l i='action_add' gid='blacklist'}">
		<i class="fa-lock icon-big edge hover zoom30"></i>
	</a>
	<a class="link-r-margin remove_from_blacklist{if $action eq 'add'} hide{/if}" data-userId="{$id_dest_user}" 
	   href="{seolink module='blacklist' method='remove' destination_user_id=$id_dest_user}" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="{l i='action_remove' gid='blacklist'}">
		<i class="fa-lock icon-big edge hover zoom30">
			<i class="fa-mini-stack icon-remove"></i>
		</i>
	</a>
</span>
<script>{literal}
	$(function() {
		loadScripts(
				["{/literal}{js file='blacklist.js' module='blacklist' return='path'}{literal}"],
				function() {
					blacklistObj = new blacklist({
						siteUrl: site_url
					});
				},
				'blacklistObj'
				);
	});
</script>{/literal}
