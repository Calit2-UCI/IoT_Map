{include file="header.tpl"}
<div class="homepage_block">
	<table cellspacing="0" cellpadding="0" class="data get-started" width="100%">
	<tr>
		<th class="first" colspan=3>{l i='header_get_started' gid='admin_home_page'}</th>
	</tr>
	<tr>
		<td class="first">
			{module_tpl module='ausers' tpl='link_edit' theme_type='admin'}<br/>
			{module_tpl module='notifications' tpl='link_settings' theme_type='admin'}<br/>
			{module_tpl module='seo' tpl='link_default_listing' theme_type='admin'}<br/>
			{module_tpl module='cronjob' tpl='link_index' theme_type='admin'}<br/>
		</td>
		<td>
			{module_tpl module='countries' tpl='link_index' theme_type='admin'}<br/>
			{module_tpl module='video_uploads' tpl='link_system_settings' theme_type='admin'}<br/>
			{module_tpl module='uploads' tpl='link_watermarks' theme_type='admin'}<br/>
			{module_tpl module='file_uploads' tpl='link_index' theme_type='admin'}<br/>
		</td>
		<td>
			{module_tpl module='moderation' tpl='link_settings' theme_type='admin'}<br/>
			{module_tpl module='dynamic_blocks' tpl='link_index' theme_type='admin'}<br/>
			{module_tpl module='languages' tpl='link_pages' theme_type='admin'}<br/>
			{module_tpl module='content' tpl='link_index' theme_type='admin'}<br/>
		</td>
	</tr>
	</table>

	<h2>{l i='header_quick_start' gid='admin_home_page'}</h2>
	{helper func_name='admin_home_stat' module='users' cache='true'}

	<div class="right-side">
		{helper func_name='admin_home_payments_block' module='payments'}
		{helper func_name='admin_home_polls_block' module='polls'}
	</div>

	<div class="left-side">
		{helper func_name='admin_home_users_block' module='users'}
		{helper func_name='admin_home_stat' module='users' cache='true'}
		{helper func_name='admin_home_banners_block' module='banners'}
		{helper func_name='admin_home_spam_block' module='spam'}
	</div>
</div>
<div class="clr"></div>
{block name='ad' module='start'}
{include file="footer.tpl"}
