{if !$is_pjax}
<!DOCTYPE html>
<html DIR="{$_LANG.rtl}">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	{seotag tag='robots'}
	<link rel="shortcut icon" href="{$site_root}favicon.ico">
	<link href="{$site_root}application/views/default/css/fa_icon.css" rel="stylesheet" type="text/css">
	<!-- For iPhone 4 Retina display: -->
	 <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$site_root}{$img_folder}favicon/apple-touch-icon-114x114-precomposed.png">
	<!-- For iPad: -->
	 <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$site_root}{$img_folder}favicon/apple-touch-icon-72x72-precomposed.png">
	<!-- For iPhone: -->
	 <link rel="apple-touch-icon-precomposed" href="{$site_root}{$img_folder}favicon/apple-touch-icon-57x57-precomposed.png">

{/if}
	{seotag tag='description|keyword|canonical|og_title|og_type|og_url|og_image|og_site_name|og_description'}
	{seotag tag='title'}
	<script>
		var site_rtl_settings = '{$_LANG.rtl}';
		var is_pjax = parseInt('{$is_pjax}');
		var js_events = {json_encode data=$js_events};
		var id_user = {if !empty($user_session_data.user_id)}{$user_session_data.user_id}{else}0{/if};
	</script>
    
{if !$is_pjax}
	<script>
		var site_url = '{$site_url}';
		var img_folder = '{$img_folder}';
		var site_error_position = 'center';
		var use_pjax = parseInt('{$use_pjax}');
		var pjax_container = '#pjaxcontainer';
	</script>
	{helper func_name='js' helper_name='theme' func_param=$load_type}
{/if}

	<script>
		var messages = {json_encode data=$_PREDEFINED};
		var alerts;
		var notifications;
		{literal}
			new pginfo({messages: messages});
			$(function(){
				alerts = new Alerts();
				notifications = new Notifications();
			});
		{/literal}
	</script>
	
	<link href="{$site_root}application/views/default/css/font-awesome.css" rel="stylesheet" type="text/css">
	{css file='general' media='screen,print'}
	{css file='print' media='print'}
	{css file='style' media='screen'}
	
	{helper func_name='css' helper_name='theme' func_param=$load_type}
{if !$is_pjax}

	{literal}<!--[if gt IE 9]><style type="text/css">.gradient,.gradient:before,.gradient:after,[class*="icon-"] [class*="icon-"], [class*="icon-"] [class*="icon-"]:before, [class*="icon-"] [class*="icon-"]:after{filter: none;}</style><![endif]-->{/literal}
</head>

<body>
	{block name='chats_block' module='chats'}
	{helper func_name='im_chat_button' module='im'}
	{helper func_name='likes' module='likes'}
	{helper func_name='demo_panel' helper_name='start' func_param='user'}
	<div id="pjaxcontainer" class="hp100"{* style="margin-top: 55px;"*}>
{/if}
		<script>$('body').removeClass('index-page site-page').addClass('{if !empty($header_type) && $header_type == 'index'}index-page{else}site-page{/if}');</script>

		{helper func_name='banner_initialize' module='banners'}
		{block name='show_social_networks_head' module='social_networking'}
		{helper func_name='seo_advanced_traker' helper_name='seo_advanced_helper' module='seo_advanced' func_param='top'}
		{if !empty($display_browser_error)}
			{helper func_name='available_browsers' helper_name='start'}
		{/if}

		<div id="error_block">{foreach item=item from=$_PREDEFINED.error}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>
		<div id="info_block">{foreach item=item from=$_PREDEFINED.info}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>
		<div id="success_block">{foreach item=item from=$_PREDEFINED.success}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>

		{if empty($header_type) || $header_type ne 'index'}
			<div class="header">
				<div class="content">
					<div class="header-logo">
						<a href="{$site_url}"><img src="{$site_root}{$mini_logo_settings.path}" border="0" 
								 alt="{helper func_name='seo_tags_default' func_param='header_text'}" 
								 width="{$mini_logo_settings.width}" 
								 height="{$mini_logo_settings.height}"></a>
					</div>
					<menu id="header-menu">
						{if $auth_type eq 'user'}
							{*block name='auth_links' module='users'*}
							
							<!---change it to a hyper link to service-->
							<!--{block name='user_account' module='users_payments'}-->
							
							<li>
								{block name='top_menu' module='users'}
							</li>
							<li>
								{block name='users_lang_select' module='users' type='menu'}
							</li>
							<li>  <!-----user menu----->
								{menu gid='settings_menu' template='settings_menu'}
							</li>
						{else}
							<li>
								{block name='users_lang_select' module='users' type='menu'}
							</li>
							<li>
								<ul>
									<li class="register"  {if $auth_type eq 'user'} class="hide-always"{/if}><a href="{$site_url}users/registration">Register</a></li>
								</ul>
							</li>
							<li class="login">  <!--no more popout windown login, link to login page-->
								<!--{block name='auth_links' module='users'}-->					
								<a href="{$site_url}users/login_form">Login</a>
							</li>
						{/if}
					</menu>
				</div>
			</div>
			<div id="top_bar_fixed">
				
				<div class="menu-search-bar">
				<div class="content">
					<div class="content table-div">
						<!--div class="w30">
							<a href="javascript: history.back();"><i class="fa-arrow-left icon-big w edge hover"></i></a>
						</div-->
						<div class="top_menu">
							{if $auth_type eq 'user'}{menu gid='user_top_menu' template='user_top_menu'}{else}{menu gid='guest_main_menu' template='user_main_menu'}{/if}
						</div>
						<div class="righted">
							{start_search_form type='line'}
						</div>
					</div>
				</div>
				</div>
			</div>
			<div class="clr"></div>
		{/if}
		<div class="main">
			<div> <!--class="content"-->     <!--JL commented it-->
				<!--{breadcrumbs}-->    <!--JL commented it-->
								
                {helper func_name='show_banner_place' module='banners' func_param='top-banner'}
				{helper func_name='show_banner_place' module='banners' func_param='left-top-banner'}
				{helper func_name='show_banner_place' module='banners' func_param='right-top-banner'}
				<div class="clr"></div>
				
                {if empty($header_type) || ($header_type ne 'index' && $header_type ne 'network')}<div class="mb20 mt20">{helper func_name='featured_users' module='users'}</div>{/if}
