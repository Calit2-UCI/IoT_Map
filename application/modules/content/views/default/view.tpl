{include file="header.tpl"}
<div class="lc">
	<div class="inside account_menu">
		{helper func_name='get_content_tree' helper_name='content' func_param=$page.id}
		{helper func_name='show_banner_place' module='banners' func_param='big-left-banner'}
		{helper func_name='show_banner_place' module='banners' func_param='left-banner'}
	</div>
</div>
<div class="rc">
	<div class="content-block wysiwyg">
		<h1>{seotag tag='header_text'}</h1>
		{$page.content}
	</div>
	{block name='show_social_networks_like' module='social_networking'}
	{block name='show_social_networks_share' module='social_networking'}
	{block name='show_social_networks_comments' module='social_networking'}
</div>
<div class="clr"></div>
{include file="footer.tpl"}