<span id="fav_{$id_dest_user}">
	<a class="add_to_fav{if $action eq 'remove'} hide{/if} link-r-margin" data-userId="{$id_dest_user}" 
	   href="{seolink module='favourites' method='add' destination_user_id=$id_dest_user}" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="{l i='action_add' gid='favourites'}">
		<i class="fa-heart icon-big edge hover"></i>
	</a>
	<a class="remove_from_fav{if $action eq 'add'} hide{/if} link-r-margin" data-userId="{$id_dest_user}" 
	   href="{seolink module='favourites' method='remove' destination_user_id=$id_dest_user}" 
	   data-pjax="0" onclick="event.preventDefault();" class="link-r-margin" 
	   title="{l i='action_remove' gid='favourites'}">
		<i class="fa-heart icon-big edge hover">
			<i class="fa-mini-stack icon-remove"></i>
		</i>
	</a>
</span>
<script>{literal}
	$(function() {
		loadScripts(
				["{/literal}{js file='favourites.js' module='favourites' return='path'}{literal}"],
				function() {
					favouritesObj = new favourites({
						siteUrl: site_url
					});
				},
				'favouritesObj'
				);
	});
</script>{/literal}
