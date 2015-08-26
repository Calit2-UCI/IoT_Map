<div id="user_select_{$select_data.rand}" class="user-select">
	<span id="user_text_{$select_data.rand}">
		{foreach item=item from=$select_data.selected}
			{$item.output_name} 
			{if $item.output_name ne $item.nickname}({$item.nickname}){/if}
			{if $select_data.max_select ne 1}<br>{/if}
			<input type="hidden" name="{$select_data.var_name}{if $select_data.max_select ne 1}[]{/if}" value="{$item.id}">
		{/foreach}
	</span>
	<a href="#" id="user_link_{$select_data.rand}">{l i='link_manage_users' gid='users'}</a>
	{if $select_data.max_select > 1 } <i>({l i='max_user_select' gid='users'}: {$select_data.max_select})</i>{/if}<br>
	<div class="clr"></div>
</div>

<script>{literal}
	$(function() {
		loadScripts(
			"{/literal}{js module='users' file='friends-select.js' return='path'}{literal}",
			function() {
				new friendsSelect({
					siteUrl: '{/literal}{$site_url}{literal}',
					user_type: '{/literal}{$select_data.user_type}{literal}',
					selected_items: [{/literal}{$select_data.selected_str}{literal}],
					var_name: '{/literal}{$select_data.var_name}{literal}',
					max: '{/literal}{$select_data.max_select}{literal}',
					rand: '{/literal}{$select_data.rand}{literal}'
				});
			},
			''
		);
	});
{/literal}</script>