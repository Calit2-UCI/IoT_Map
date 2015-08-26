{include file="header.tpl"}
	<div class="content-block">
		<h1>{l i='header_package_settings' gid='packages'}: {$data.name}</h1>

		<div class="content-value">
			{block name='packages_list' module='packages' packages=$packages hide_btn=1 stretch=1}
			<form method="post" action="">
				{if $data.free_activate}
					<input type="submit" class='btn' value="{l i='btn_activate_free' gid='services' type='button'}" name="btn_account">
				{else}
					{if ($data.pay_type eq 1 || $data.pay_type eq 2) && $is_module_installed}
						<h2 class="line top bottom">{l i='account_payment' gid='services'}</h2>
						{if $data.disable_account_pay}
							{l i='error_account_less_then_service_price' gid='services'} <a href="{seolink module='users' method='account' action='update'}">{l i="link_add_founds" gid='services'}</a>
						{else}
							{l i='on_your_account_now' gid='services'}: <b>{block name='currency_format_output' module='start' value=$data.user_account}</b>
							<div class="b outside">
								<input type="submit" data-pjax-submit="0" value="{l i='btn_pay_account' gid='services' type='button'}" name="btn_account">
							</div>
						{/if}
					{/if}

					{if $data.pay_type eq 2 || $data.pay_type eq 3}
						<h2 class="line top bottom">{l i='payment_systems' gid='services'}</h2>
						{if $billing_systems}
							{l i='error_select_payment_system' gid='services'}<br>
							<select name="system_gid"><option value="">...</option>{foreach item=item from=$billing_systems}<option value="{$item.gid}">{$item.name}</option>{/foreach}</select>
							<div class="b outside">
								<input type="submit" data-pjax-submit="0" value="{l i='btn_pay_systems' gid='services' type='button'}" name="btn_system" class="btn">
							</div>
						{elseif $data.pay_type eq 3}
							{l i='error_empty_billing_system_list' gid='service'}
						{/if}
					{/if}

				{/if}
			</form>
		</div>
		<div class="mt20">
			<a class="btn-link" href="{$site_url}packages/index"><i class="icon-arrow-left icon-big edge hover"></i><i>{l i='back_to_packages' gid='packages'}</i></a>
		</div>
	</div>
{include file="footer.tpl"}
