{include file="header.tpl"}
<div class="content">
{strip}
<div class="content-block">
	<h1>{seotag tag='header_text'}: {l i='header_'$action gid='users'}</h1>
	
	{include file="account_menu.tpl" module="users" theme="default"}
	
	{if $action eq 'services'}
		{capture assign='services_block'}
			{block name='services_buy_list' module='services'}
		{/capture}
		{capture assign='packages_block'}
			{block name='packages_list' module='packages'}
		{/capture}
		{capture assign='user_services_block'}
			{block name='user_services_list' module='services' id_user=$user_id}
		{/capture}
		{capture assign='user_packages_block'}
			{block name='user_packages_list' module='packages'}
		{/capture}
		<div class="expandable">
			<div class="h2 expander">
				{l i='header_services' gid='users'}
				<div class="fright">&nbsp;<a class="icon fa-chevron down icon-big edge hover zoom20"></a></div>
			</div>
			<div class="toggle hide">
				{$services_block}
				{$packages_block}
			</div>
		</div>
		<div class="expandable">
			<div class="h2 expander">
				{l i='header_my_services' gid='users'}
				<div class="fright">&nbsp;<a class="icon fa-chevron down icon-big edge hover zoom20"></a></div>
			</div>
			<div class="toggle hide">
				{$user_services_block}
				{$user_packages_block}
			</div>
		</div>
	{elseif $action eq 'memberships'}
		<div class="memberships ptb20">
			{block name='memberships_list' module='memberships'}
		</div>
		
	{elseif $action eq 'update'}
		<!--{block name='user_account' module='users_payments'}-->
		{helper func_name='update_account_block' module='users_payments'}
		<!--</br><p style="font-size: 12px">No update account yet</p>-->
	{elseif $action eq 'payments_history'}
		<div>{block name='user_payments_history' module='payments' id_user=$user_id page=$page base_url=$base_url}</div>
	{elseif $action eq 'banners'}
		<div>{block name='my_banners' module='banners' page=$page base_url=$base_url}</div>
        {elseif $action eq 'send_money'}
		{helper func_name='send_money_block' module='send_money'}
	{/if}
</div>
<div class="clr"></div>
{literal}
<script>
	$('.expander').bind('click', function(){
		var target = $(this).parents('.expandable').find('.toggle');
		var icon = $(this).find('.icon');
		if (target.is(':hidden')){
			icon.removeClass('down');
			icon.addClass('up');
			target.slideDown();
		} else {
			icon.removeClass('up');
			icon.addClass('down');
			target.slideUp();
		}
	});
</script>
{/literal}
{/strip}
</div>
{include file="footer.tpl"}