{strip}
<div id="index_registration_login_forms_{$dynamic_block_registration_login_form_data.rand}" class="index-login-form bg-html_bg p20">
	{helper func_name='show_social_networking_login' module='users_connections'}
	<div id="index_registration_form_{$dynamic_block_registration_login_form_data.rand}"{if $dynamic_block_registration_login_form_data.view == 'login_form'} class="hide"{/if}>
		<form action="{seolink module='users' method='registration'}" method="post">
			<div class="r">
				<input type="text" name="email" class="big-input wp100 box-sizing" placeholder="{l i='field_email' gid='users' type='button'}" {if $DEMO_MODE}value="{$demo_user_type_login_settings.login}"{/if}>
			</div>
			<div class="r">
				<input type="password" name="password" class="big-input wp100 box-sizing" placeholder="{l i='field_password' gid='users' type='button'}" {if $DEMO_MODE}value="{$demo_user_type_login_settings.password}"{/if}>
			</div>
			<div class="r">
				<input type="submit" class="wp100 box-sizing" value="{l i='btn_register' gid='start' type='button'}" name="logbtn">
			</div>
			<div class="centered">
				<span class="a" data-toggle>{l i='btn_login' gid='start'}</span>
			</div>
		</form>
	</div>
	<div id="index_login_form_{$dynamic_block_registration_login_form_data.rand}"{if $dynamic_block_registration_login_form_data.view == 'registration_form'} class="hide"{/if}>
		<form action="{$site_url}users/login" method="post">
			<div class="r">
				<input type="text" name="email" class="big-input wp100 box-sizing" placeholder="{l i='field_email' gid='users' type='button'}" {if $DEMO_MODE}value="{$demo_user_type_login_settings.login}"{/if}>
			</div>
			<div class="r">
				<input type="password" name="password" class="big-input wp100 box-sizing" placeholder="{l i='field_password' gid='users' type='button'}" {if $DEMO_MODE}value="{$demo_user_type_login_settings.password}"{/if}>
			</div>
			<div class="r">
				<input type="submit" class="wp100 box-sizing" value="{l i='btn_login' gid='start' type='button'}" name="logbtn">
			</div>
			<div class="centered">
				<a href="{seolink module='users' method='restore'}">{l i='link_restore' gid='users'}</a><span class="a ml10" data-toggle>{l i='btn_register' gid='start'}</span>
			</div>
		</form>
	</div>
</div>
{/strip}

<script>{literal}
	$('#index_registration_login_forms_{/literal}{$dynamic_block_registration_login_form_data.rand}{literal}').off('click', '[data-toggle]').on('click', '[data-toggle]', function(){
		var rand = '{/literal}{$dynamic_block_registration_login_form_data.rand}{literal}';
		if($('#index_registration_form_'+rand).is(':visible')){
			if($('#index_login_form_'+rand).find('input[name="email"]').val() == ''){
				$('#index_login_form_'+rand).find('input[name="email"]').val($('#index_registration_form_'+rand).find('input[name="email"]').val());
			}
			if($('#index_login_form_'+rand).find('input[name="password"]').val() == ''){
				$('#index_login_form_'+rand).find('input[name="password"]').val($('#index_registration_form_'+rand).find('input[name="password"]').val());
			}
			$('#index_registration_form_'+rand).stop(true).fadeOut(300, function(){$('#index_login_form_'+rand).stop(true).fadeIn(300);});
		}else{
			if($('#index_registration_form_'+rand).find('input[name="email"]').val() == ''){
				$('#index_registration_form_'+rand).find('input[name="email"]').val($('#index_login_form_'+rand).find('input[name="email"]').val());
			}
			if($('#index_registration_form_'+rand).find('input[name="password"]').val() == ''){
				$('#index_registration_form_'+rand).find('input[name="password"]').val($('#index_login_form_'+rand).find('input[name="password"]').val());
			}
			$('#index_login_form_'+rand).stop(true).fadeOut(300, function(){$('#index_registration_form_'+rand).stop(true).fadeIn(300);});
		}
	});

	$(document).one('pjax:start', function(){
		$('#index_registration_login_forms_{/literal}{$dynamic_block_registration_login_form_data.rand}{literal}').off('click', '[data-toggle]');
	});
</script>{/literal}
