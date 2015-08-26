{include file="header.tpl"}
<div class="content-block">
	<h1>{l i='header_services_list' gid='services'}</h1>
    {block name='services_buy_list' module='services' template_gid=$template_gid}
    {block name='memberships_list' module='memberships' template_gid=$template_gid headline=1}
    {block name='packages_list' module='packages' template_gid=$template_gid headline=1}
</div>
<div class="clr"></div>
{include file="footer.tpl"}

