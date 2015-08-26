{strip}
<div class="tabs tab-size-15 noPrint">
	<ul>
		<li{if $tab eq 'my_favs'} class="active"{/if}>
			<a data-pjax-no-scroll="1" href="{seolink module='favourites' method='my_favs'}">{l i='my_fav' gid='favourites'}</a>
		</li>
		<li{if $tab ne 'my_favs'} class="active"{/if} id="inbox">
			<a data-pjax-no-scroll="1" href="{seolink module='favourites' method='i_am_their_fav'}">{l i='fav_me' gid='favourites'}</a>
		</li>
	</ul>
</div>
{/strip}
