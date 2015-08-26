<h2 class="line top bottom linked">
	{l i='reg_subscriptions' gid='subscriptions'}
	<a class="fright icon-pencil icon-big edge hover" href="{$site_url}users/profile/subscriptions/"></a>
</h2>
<div class="view-section">
	<div class="view-section">
	{foreach item=item from=$subscriptions_list }
		<div class="r">
			<div class="f">{$item.name}:</div>
			<div class="v">{if $item.subscribed}{l i='user_subscribed' gid='subscriptions'}{else}{l i='user_not_subscribed' gid='subscriptions'}{/if}</div>
		</div>
	{/foreach}
	</div>
</div>