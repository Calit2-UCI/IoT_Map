<div  class="r">
	<div class="f">{l i='reg_subscriptions' gid='subscriptions'}: </div>
</div>
{foreach item=item from=$subscriptions_list key=key}
	<div class="r">
		<div class="v">
			<input {if $item.subscribed}checked{/if} type="checkbox" name="user_subscriptions_list[]" value="{$item.id}" id="sub{$item.id}">
			<label for="sub{$item.id}">{l i=$item.name_i gid='subscriptions'}</label>
		</div>
	</div>
{/foreach}
