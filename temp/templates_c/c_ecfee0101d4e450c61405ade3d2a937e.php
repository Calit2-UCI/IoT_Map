<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.breadcrumbs.php');
$this->register_function("breadcrumbs", "tpl_function_breadcrumbs"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.start_search_form.php');
$this->register_function("start_search_form", "tpl_function_start_search_form"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.menu.php');
$this->register_function("menu", "tpl_function_menu"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.css.php');
$this->register_function("css", "tpl_function_css"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.json_encode.php');
$this->register_function("json_encode", "tpl_function_json_encode"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:52 Pacific Daylight Time */ ?>

<?php if (! $this->_vars['is_pjax']): ?>
<!DOCTYPE html>
<html DIR="<?php echo $this->_vars['_LANG']['rtl']; ?>
">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="revisit-after" content="3 days">
	<?php echo tpl_function_seotag(array('tag' => 'robots'), $this);?>
	<link rel="shortcut icon" href="<?php echo $this->_vars['site_root']; ?>
favicon.ico">
	<link href="<?php echo $this->_vars['site_root']; ?>
application/views/default/css/fa_icon.css" rel="stylesheet" type="text/css">
	<!-- For iPhone 4 Retina display: -->
	 <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
favicon/apple-touch-icon-114x114-precomposed.png">
	<!-- For iPad: -->
	 <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
favicon/apple-touch-icon-72x72-precomposed.png">
	<!-- For iPhone: -->
	 <link rel="apple-touch-icon-precomposed" href="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
favicon/apple-touch-icon-57x57-precomposed.png">

<?php endif; ?>
	<?php echo tpl_function_seotag(array('tag' => 'description|keyword|canonical|og_title|og_type|og_url|og_image|og_site_name|og_description'), $this);?>
	<?php echo tpl_function_seotag(array('tag' => 'title'), $this);?>
	<script>
		var site_rtl_settings = '<?php echo $this->_vars['_LANG']['rtl']; ?>
';
		var is_pjax = parseInt('<?php echo $this->_vars['is_pjax']; ?>
');
		var js_events = <?php echo tpl_function_json_encode(array('data' => $this->_vars['js_events']), $this);?>;
		var id_user = <?php if (! empty ( $this->_vars['user_session_data']['user_id'] )):  echo $this->_vars['user_session_data']['user_id'];  else: ?>0<?php endif; ?>;
	</script>
    
<?php if (! $this->_vars['is_pjax']): ?>
	<script>
		var site_url = '<?php echo $this->_vars['site_url']; ?>
';
		var img_folder = '<?php echo $this->_vars['img_folder']; ?>
';
		var site_error_position = 'center';
		var use_pjax = parseInt('<?php echo $this->_vars['use_pjax']; ?>
');
		var pjax_container = '#pjaxcontainer';
	</script>
	<?php echo tpl_function_helper(array('func_name' => 'js','helper_name' => 'theme','func_param' => $this->_vars['load_type']), $this); endif; ?>

	<script>
		var messages = <?php echo tpl_function_json_encode(array('data' => $this->_vars['_PREDEFINED']), $this);?>;
		var alerts;
		var notifications;
		<?php echo '
			new pginfo({messages: messages});
			$(function(){
				alerts = new Alerts();
				notifications = new Notifications();
			});
		'; ?>

	</script>
	
	<link href="<?php echo $this->_vars['site_root']; ?>
application/views/default/css/font-awesome.css" rel="stylesheet" type="text/css">
	<?php echo tpl_function_css(array('file' => 'general','media' => 'screen,print'), $this);?>
	<?php echo tpl_function_css(array('file' => 'print','media' => 'print'), $this);?>
	<?php echo tpl_function_css(array('file' => 'style','media' => 'screen'), $this);?>
	
	<?php echo tpl_function_helper(array('func_name' => 'css','helper_name' => 'theme','func_param' => $this->_vars['load_type']), $this); if (! $this->_vars['is_pjax']): ?>

	<?php echo '<!--[if gt IE 9]><style type="text/css">.gradient,.gradient:before,.gradient:after,[class*="icon-"] [class*="icon-"], [class*="icon-"] [class*="icon-"]:before, [class*="icon-"] [class*="icon-"]:after{filter: none;}</style><![endif]-->'; ?>

</head>

<body>
	<?php echo tpl_function_block(array('name' => 'chats_block','module' => 'chats'), $this);?>
	<?php echo tpl_function_helper(array('func_name' => 'im_chat_button','module' => 'im'), $this);?>
	<?php echo tpl_function_helper(array('func_name' => 'likes','module' => 'likes'), $this);?>
	<?php echo tpl_function_helper(array('func_name' => 'demo_panel','helper_name' => 'start','func_param' => 'user'), $this);?>
	<div id="pjaxcontainer" class="hp100">
<?php endif; ?>
		<script>$('body').removeClass('index-page site-page').addClass('<?php if (! empty ( $this->_vars['header_type'] ) && $this->_vars['header_type'] == 'index'): ?>index-page<?php else: ?>site-page<?php endif; ?>');</script>

		<?php echo tpl_function_helper(array('func_name' => 'banner_initialize','module' => 'banners'), $this);?>
		<?php echo tpl_function_block(array('name' => 'show_social_networks_head','module' => 'social_networking'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'seo_advanced_traker','helper_name' => 'seo_advanced_helper','module' => 'seo_advanced','func_param' => 'top'), $this);?>
		<?php if (! empty ( $this->_vars['display_browser_error'] )): ?>
			<?php echo tpl_function_helper(array('func_name' => 'available_browsers','helper_name' => 'start'), $this);?>
		<?php endif; ?>

		<div id="error_block"><?php if (is_array($this->_vars['_PREDEFINED']['error']) and count((array)$this->_vars['_PREDEFINED']['error'])): foreach ((array)$this->_vars['_PREDEFINED']['error'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>
		<div id="info_block"><?php if (is_array($this->_vars['_PREDEFINED']['info']) and count((array)$this->_vars['_PREDEFINED']['info'])): foreach ((array)$this->_vars['_PREDEFINED']['info'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>
		<div id="success_block"><?php if (is_array($this->_vars['_PREDEFINED']['success']) and count((array)$this->_vars['_PREDEFINED']['success'])): foreach ((array)$this->_vars['_PREDEFINED']['success'] as $this->_vars['item']):  if ($this->_vars['item']['text']):  echo $this->_vars['item']['text']; ?>
<br><?php endif;  endforeach; endif; ?></div>

		<?php if (empty ( $this->_vars['header_type'] ) || $this->_vars['header_type'] != 'index'): ?>
			<div class="header">
				<div class="content">
					<div class="header-logo">
						<a href="<?php echo $this->_vars['site_url']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['mini_logo_settings']['path']; ?>
" border="0" 
								 alt="<?php echo tpl_function_helper(array('func_name' => 'seo_tags_default','func_param' => 'header_text'), $this);?>" 
								 width="<?php echo $this->_vars['mini_logo_settings']['width']; ?>
" 
								 height="<?php echo $this->_vars['mini_logo_settings']['height']; ?>
"></a>
					</div>
					<menu id="header-menu">
						<?php if ($this->_vars['auth_type'] == 'user'): ?>
							
							<li>
								<?php echo tpl_function_block(array('name' => 'user_account','module' => 'users_payments'), $this);?>
							</li>
							<li>
								<?php echo tpl_function_block(array('name' => 'top_menu','module' => 'users'), $this);?>
							</li>
							<li>
								<?php echo tpl_function_block(array('name' => 'users_lang_select','module' => 'users','type' => 'menu'), $this);?>
							</li>
							<li>
								<?php echo tpl_function_menu(array('gid' => 'settings_menu','template' => 'settings_menu'), $this);?>
							</li>
						<?php else: ?>
							<li>
								<?php echo tpl_function_block(array('name' => 'users_lang_select','module' => 'users','type' => 'menu'), $this);?>
							</li>
							<li>
								<?php echo tpl_function_block(array('name' => 'auth_links','module' => 'users'), $this);?>
							</li>
						<?php endif; ?>
					</menu>
				</div>
			</div>
			<div id="top_bar_fixed">
				<div class="menu-search-bar">
					<div class="content table-div">
						<div class="w30">
							<a href="javascript: history.back();"><i class="fa-arrow-left icon-big w edge hover"></i></a>
						</div>
						<div class="top_menu">
							<?php if ($this->_vars['auth_type'] == 'user'):  echo tpl_function_menu(array('gid' => 'user_top_menu','template' => 'user_top_menu'), $this); else:  echo tpl_function_menu(array('gid' => 'guest_main_menu','template' => 'user_main_menu'), $this); endif; ?>
						</div>
						<div class="righted">
							<?php echo tpl_function_start_search_form(array('type' => 'line'), $this);?>
						</div>
					</div>
				</div>
			</div>
			<div class="clr"></div>
		<?php endif; ?>
		<div class="main">
			<div class="content">
				<?php echo tpl_function_breadcrumbs(array(), $this);?>
				
                <?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'top-banner'), $this);?>
				<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'left-top-banner'), $this);?>
				<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'right-top-banner'), $this);?>
				<div class="clr"></div>
				
                <?php if (empty ( $this->_vars['header_type'] ) || ( $this->_vars['header_type'] != 'index' && $this->_vars['header_type'] != 'network' )): ?><div class="mb20 mt20"><?php echo tpl_function_helper(array('func_name' => 'featured_users','module' => 'users'), $this);?></div><?php endif; ?>
