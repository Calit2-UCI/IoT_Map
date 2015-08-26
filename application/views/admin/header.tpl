<!DOCTYPE html>
<html DIR="{$_LANG.rtl}">
<head>
	<meta http-equiv="X-UA-Compatible">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<meta name="robot" content="All">
	{seotag tag='title|description|keyword|canonical|og_title|og_type|og_url|og_image|og_site_name|og_description'}
	{helper func_name='js' helper_name='theme' func_param=$load_type}
	{helper func_name='css' helper_name='theme' func_param=$load_type}
	<script type="text/javascript">
		var site_url = '{$site_url}';
		var site_rtl_settings = '{$_LANG.rtl}';
		var site_error_position = 'left';
		{*literal}	
		var alerts;
		(function() {
			$(function(){
				alerts = new Alerts();
			});
		})();
		{/literal*}
	</script>

	<link rel="shortcut icon" href="{$site_root}favicon.ico">
	
	<link rel="stylesheet" type="text/css" href="{$site_url}application/views/admin/css/font-awesome.css">
</head>

<body>
{*pg_include_file=demo_help_admin_menu.html*}
{helper func_name='demo_panel' helper_name='start' func_param='admin'}
	<div id="error_block">{foreach item=item from=$_PREDEFINED.error}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>
	<div id="info_block">{foreach item=item from=$_PREDEFINED.info}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>
	<div id="success_block">{foreach item=item from=$_PREDEFINED.success}{if $item.text}{$item.text}<br>{/if}{/foreach}</div>

	<div class="bg">
		<div class="main">
			<!-- Left column -->
			<div class="lc">
				<!-- Logo -->
				<div class="logo">
					<div class="b">
						<a href="{$site_url}admin/"><img src="{$site_root}{$logo_settings.path}" border="0" alt="logo" width="{$logo_settings.width}" height="{$logo_settings.height}"></a>
					</div>
				</div>
				<!-- Menu -->
				{if !empty($initial_setup)}
					{helper func_name='get_initial_setup_menu' helper_name='install' func_param=$step}
				{elseif !empty($modules_setup)}
					{helper func_name='get_modules_setup_menu' helper_name='install' func_param=$step}
				{elseif !empty($product_setup)}
					{helper func_name='get_product_setup_menu' helper_name='install' func_param=$step}
				{else}
					{helper func_name='get_admin_main_menu' helper_name='menu'}
				{/if}
			</div>

			<!-- Right column -->
			<div class="rc">
				{if $auth_type eq 'admin'}
					<div class="w-login-str">
						<div class="version">{helper func_name='product_version' helper_name='start'}</div>
						{if empty($initial_setup) && empty($modules_setup) && empty($product_setup)}
							<span class="ib">
								{helper func_name='users_lang_select' module='users'}
							</span>
						{/if} |
						<a href="http://www.pilotgroup.net/support/" target="_blank">{l i='btn_support' gid='start'}</a>
						{*<select onchange="redirectDocumentation($('#redirect_drop').val());" id="redirect_drop">
							<option value="" selected>Get help</option>
							<option value="http://docs.pilotgroup.net/display/JobSitePro/Home">Documentation</option>
							<option value="http://server.iad.liveperson.net/hc/12270993/?cmd=file&file=visitorWantsToChat&site=12270993&imageUrl=http://server.iad.liveperson.net">Ask a question</option>
							<option value="http://community.pilotgroup.net/pilotgroup/products/pilotgroup_pg_job_site_pro">Share an idea</option>
							<option value="http://community.pilotgroup.net/pilotgroup/products/pilotgroup_pg_job_site_pro">Report a problem</option>
							<option value="http://community.pilotgroup.net/pilotgroup/products/pilotgroup_pg_job_site_pro">Give praise</option>
						</select>
						<script>{literal}
							function redirectDocumentation(value){
								if(value) document.location.href = value;
								return false;
							}
						</script>{/literal*}

						{if !empty($modules_setup)} | <a href="{$site_url}admin/install/logoff" class="logoff">{l i='btn_logoff' gid='start'}</a>
						{elseif !empty($initial_setup) || !empty($product_setup)}
						{elseif $auth_type eq 'admin'} | <a href="{$site_url}admin/ausers/logoff" class="logoff">{l i='btn_logoff' gid='start'}</a>
						{/if}
					</div>
				{/if}
				<div class="w-area">
					<div class="b">
						{if $_PREDEFINED.back_link}
							<div class="quest-block">
								 <a href="{$_PREDEFINED.back_link}" class="back">{l i='btn_back' gid='start'}</a>&nbsp;
							</div>
						{/if}
							<h1>{$_PREDEFINED.header}</h1>
