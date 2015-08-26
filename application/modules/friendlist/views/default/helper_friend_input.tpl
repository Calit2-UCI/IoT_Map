<a href="#" id="user_open_{$friend_helper_data.rand}">{l i='link_select_friend' gid='friendlist'}</a>
<script>{literal}
{/literal}{if $country_helper_data.var_js_name}var {$country_helper_data.var_js_name};{/if}{literal}
$(function(){
	loadScripts(
		"{/literal}{js module='friendlist' file='friends-input.js' return='path'}{literal}",
		function(){
			friend_{/literal}{$friend_helper_data.rand}{literal} = new friendsInput({
				siteUrl: '{/literal}{$site_url}{literal}',
				rand: '{/literal}{$friend_helper_data.rand}{literal}',
				id_user: '{/literal}{$friend_helper_data.user.id}{literal}',
				{/literal}{if $friend_helper_data.values_callback}values_callback: {$friend_helper_data.values_callback}{/if}{literal}
			});
		},
		'friend_{/literal}{$friend_helper_data.rand}{literal}'
	);
});
{/literal}</script>
