<div class="user-box">
	<input type="text" name="user_name" id="user_text_{$user_helper_data.rand}" autocomplete="off" value="{$user_helper_data.user.output_name}" placeholder="{$user_helper_data.placeholder}">
	<button class="search-btn" id="user_open_{$user_helper_data.rand}" name="submit"><i class="fa-search w"></i></button>
	<input type="hidden" name="{$user_helper_data.var_user_name}" id="user_hidden_{$user_helper_data.rand}" value="{$user_helper_data.user.id}">
</div>

<script>{literal}
{/literal}{if $user_helper_data.var_js_name}var {$user_helper_data.var_js_name};{/if}{literal}
$(function(){
	loadScripts(
		"{/literal}{js module='users' file='users-input.js' return='path'}{literal}",
		function(){
			user_{/literal}{$user_helper_data.rand}{literal} = new usersInput({
				siteUrl: '{/literal}{$site_url}{literal}',
				rand: '{/literal}{$user_helper_data.rand}{literal}',
				id_user: '{/literal}{$user_helper_data.user.id}{literal}',
			});
		},
		'user_{/literal}{$user_helper_data.rand}{literal}'
	);
});
{/literal}</script>
