{strip}
<ul id="ajax_login_link_menu_{$dynamic_block_auth_links_data.rand}" class="index-menu{if $dynamic_block_auth_links_data.params.right_align} righted{/if}">
	<li><a style="font-size:16.5px; font-family:Arial; font-weight: normal;" id="dynblocks_link_register" href="{$site_url}users/registration">{l i='link_register' gid='users'}</a></li>
	<li><a style="font-size:16.5px; font-family:Arial; font-weight: normal;" href="{$site_url}users/login_form" onclick="return false;" id="ajax_login_link">{l i='header_login' gid='users'}</a></li>
</ul>

<script>{literal}
	$(function(){
		var rand = '{/literal}{$dynamic_block_auth_links_data.rand}{literal}';
		user_ajax_login{/literal}{$dynamic_block_auth_links_data.rand}{literal} = new loadingContent({
			loadBlockWidth: '520px',
			linkerObjID: 'ajax_login_link_menu_'+rand,
			loadBlockLeftType: 'right',
			loadBlockTopType: 'bottom',
			closeBtnClass: 'w'
		}).update_css_styles({'z-index': 2000}).update_css_styles({'z-index': 2000}, 'bg');
		$('#ajax_login_link').unbind('click').click(function(){
			$.ajax({
				url: site_url + 'users/ajax_login_form',
				cache: false,
				success: function(data){
					user_ajax_login{/literal}{$dynamic_block_auth_links_data.rand}{literal}.show_load_block(data);
				}
			});
			return false;
		});
	});
</script>{/literal}
{/strip}
