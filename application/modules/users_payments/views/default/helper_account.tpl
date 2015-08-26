<menu class="header-item" label="{l i='on_account_header' gid='users_payments'}">
	<a href="{seolink module='users' method='account' action='update'}">
		{if $user_account}
			{block name='currency_format_output' module='start' value=$user_account cur_gid=$base_currency.gid}&nbsp;
		{/if}
		<i class="fa-credit-card"></i>&nbsp;
		<i class="fa-caret-down"></i>
	</a>
	<div class="drop w300">
		{depends module=services}
			<span>{l i='services' gid='users_payments'}</span> 
			(<a class="extra" href="{seolink module='users' method='account'}">{l i='find_out_more' gid='users_payments'}</a>)
			<menu>
				{if $user_services}
					{foreach item='user_service' from=$user_services}
						{if !$user_service.is_expired}
							<li>
								<a id="users_payments_link_service_{$user_service.gid}" href="{$site_url}services/form/{$user_service.gid}">{$user_service.name}
								{if $user_service.days_left}
									: <span class="fright">{$user_service.days_left} {l i='days_left' gid='users_payments'}</span>
								{/if}
								</a>
							</li>
						{/if}
					{/foreach}
				{/if}
			</menu>
		{/depends}
		{depends module=memberships}
			<span>{l i='memberships' gid='memberships'}</span> 
			(<a class="extra" href="{seolink module='users' method='account' action='memberships'}">{l i='find_out_more' gid='users_payments'}</a>)
			<menu>
				{if $user_memberships}
					{foreach item='user_membership' from=$user_memberships}
						<li>
							{$user_membership.membership_info.name}
							<span class="fright">{$user_membership.left_str}</span>
						</li>
					{/foreach}
				{/if}
			</menu>
		{/depends}
		<menu>
			<li><a id="users_payments_link_update_account" class="extra" href="{seolink module='users' method='account' action='update'}">{l i='add_funds' gid='users_payments'}</a></li>
		</menu>
	</div>
</menu>
