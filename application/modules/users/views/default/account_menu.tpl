<div class="fright">
	<ul>
		{if $action eq 'banners'}
		{depends module=banners}
		<li>
			<s id="add_banner" class="a btn-link">
				<a href="{$site_url}banners/edit">
					<i class="icon-file-text-alt icon-big edge hover">
						<i class="icon-mini-stack icon-plus bottomright"></i>
					</i>
				</a>
				<i><a href="{$site_url}banners/edit">{l i='link_add_banner' gid='banners'}</a></i>
			</s>
		</li>
		{/depends}
		{/if}
	</ul>
</div>

<div class="tabs tab-size-15 noPrint">
	<ul>
		{depends module=memberships}
			<li{if $action eq 'memberships'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='account' action='memberships'}">{l i='header_memberships' gid='users'}</a></li>
		{/depends}
		{*depends module=services*}
			<li{if $action eq 'services'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='account' action='services'}">{l i='header_services' gid='users'}</a></li>
			{*<li{if $action eq 'my_services'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='account' action='my_services'}">{l i='header_my_services' gid='users'}</a></li>*}
		{*/depends*}
		{depends module=users_payments}
			<li{if $action eq 'update'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='account' action='update'}">{l i='header_account_update' gid='users'}</a></li>
		{/depends}
		{depends module=payments}
			<li{if $action eq 'payments_history'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='account' action='payments_history'}">{l i='header_my_payments_statistic' gid='payments'}</a></li>
		{/depends}
		{depends module=banners}
			<li{if $action eq 'banners'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='account' action='banners'}">{l i='header_my_banners' gid='banners'}</a></li>
		{/depends}
		{depends module=send_money}
			<li{if $action eq 'send_money'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='account' action='send_money'}">{l i='send_money' gid='send_money'}</a></li>
		{/depends}
	</ul>
</div>
