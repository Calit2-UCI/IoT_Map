{include file="header.tpl"}

{include file="left_panel.tpl" module="start"}

<div class="rc">
	<div class="content-block">
		<h1>{l i='header_my_memberships' gid='users_memberships'}</h1>
		<p class="header-comment">{l i='text_my_memberships' gid='users_memberships'}</p>
	</div>
	<div class="content-block">
		{foreach item=item from=$user_memberships}
		<h2 class="line top bottom">{$item.membership_info.name}</h2>
		<p class="header-comment">
			{foreach item=item_s from=$item.membership_info.services_list}
				-{$item_s.name}<br>
			{/foreach}
		</p>
		<p class="header-comment">
			{foreach item=item_s from=$item.user_services}
				-{$item_s.service_name}({$item_s.count})<br>
			{/foreach}
		</p>
		<br>
		{foreachelse}
				{l i='no_users_memberships' gid='users_memberships'}
		{/foreach}
		{pagination data=$page_data type='full'}
	</div>
</div>
<div class="clr"></div>
{include file="footer.tpl"}
