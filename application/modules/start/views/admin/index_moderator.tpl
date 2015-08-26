{include file="header.tpl"}
<p>{l i='moderator_comment' gid='admin_home_page'}</p>
<h2>{l i='header_quick_start' gid='admin_home_page'}</h2>
<div class="right-side">
	{helper func_name='admin_home_payments_block' module='payments'}
	{helper func_name='admin_home_polls_block' module='polls'}
</div>

<div class="left-side">
	{helper func_name='admin_home_users_block' module='users'}
	{helper func_name='admin_home_banners_block' module='banners'}
	{helper func_name='admin_home_spam_block' module='spam'}
</div>

{include file="footer.tpl"}