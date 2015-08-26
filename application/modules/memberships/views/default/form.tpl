{include file="header.tpl"}
	<div class="content-block">
		<h1>{l i='header_membership_settings' gid='memberships'}: {$membership.name}</h1>
		{if $show_ms_change_warning}
			<p>
				{l i='membership_change_warning' gid='memberships'}
			</p>
		{/if}
		<p>
			{$membership.description}
		</p>
		<div class="content-value mt20">
			{block name='memberships_list' module='memberships' memberships=$membership hide_buy_btn=true}
			<form method="post" action="">
				{if $membership.free_activate}
					<input type="submit" class='btn' value="{l i='btn_activate_free' gid='memberships' type='button'}" name="btn_account">
				{else}
					{if ($membership.pay_type eq 'account' || $membership.pay_type eq 'account_and_direct')}
						<h2 class="line top bottom">{l i='account_payment' gid='services'}</h2>
						{if $membership.disable_account_pay}
							{l i='error_account_less_then_service_price' gid='services'} <a href="{seolink module='users' method='account' action='update'}">{l i="link_add_founds" gid='services'}</a>
						{else}
							{l i='on_your_account_now' gid='services'}: <b>{block name='currency_format_output' module='start' value=$membership.user_account}</b>
							<div class="b outside">
								<input type="submit" data-pjax-submit="0" value="{l i='btn_pay_account' gid='services' type='button'}" name="btn_account">
							</div>
						{/if}
					{/if}
					{if $membership.pay_type eq 'account_and_direct' || $membership.pay_type eq 'direct'}
						<h2 class="line top bottom">{l i='payment_systems' gid='services'}</h2>
						{if $billing_systems}
							{l i='error_select_payment_system' gid='services'}<br>
							<select name="system_gid"><option value="">...</option>{foreach item=item from=$billing_systems}<option value="{$item.gid}">{$item.name}</option>{/foreach}</select>
							<div class="b outside">
								<input type="submit" data-pjax-submit="0" value="{l i='btn_pay_systems' gid='services' type='button'}" name="btn_system" class="btn">
							</div>
						{elseif $membership.pay_type eq 'direct'}
							{l i='error_empty_billing_system_list' gid='service'}
						{/if}
					{/if}
				{/if}
			</form>
		</div>
		<div class="mt20">
			<a class="btn-link" href="{$site_url}memberships/index"><i class="icon-arrow-left icon-big edge hover"></i><i>{l i='back_to_memberships' gid='memberships'}</i></a>
		</div>
	</div>
{include file="footer.tpl"}
