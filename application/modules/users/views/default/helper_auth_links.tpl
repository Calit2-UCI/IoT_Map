{strip}
{if $auth_type eq 'user'}
	<ul><li>{l i='welcome' gid='users'}&nbsp;<a id="users_link_profile" href="{seolink module='users' method='profile'}">{$user_session_data.output_name}</a>!</li></ul>
{/if}
		
<ul id="ajax_login_link_menu">
	<li{if $auth_type eq 'user'} class="hide-always"{/if}><a id="ajax_login_link" href="{seolink module='users' method='login_form'}" onclick="return false;" id="ajax_login_link">{l i='header_login' gid='users'}</a></li>
</ul>

<script>{literal}
	$(function(){
		user_ajax_login = new loadingContent({
			loadBlockWidth: '520px',
			linkerObjID: 'ajax_login_link_menu',
			loadBlockLeftType: 'right',
			loadBlockTopType: 'bottom',
			closeBtnClass: 'w'
		}).update_css_styles({'z-index': 2000}).update_css_styles({'z-index': 2000}, 'bg');
		$('#ajax_login_link').unbind('click').click(function(){
			$.ajax({
				url: site_url + 'users/ajax_login_form',
				cache: false,
				success: function(data){
					user_ajax_login.show_load_block(data);
				}
			});
			return false;
		});
	});
</script>{/literal}

{/strip}
