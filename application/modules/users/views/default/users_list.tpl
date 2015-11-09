{include file="header.tpl"}
<div class="content">
	<h1>{if $search_text}{l i='search_results' gid='users'}: '{$search_text}'{else}Find Company{/if}</h1>

	<div class="pos-rel">
		{start_search_form type='advanced' show_data='1' object='user'}
	</div>
	<div class="content-block">
		<div id="main_users_results">
			{$block}
		</div>

		<script type="text/javascript">{literal}
			$(function(){
				loadScripts("{/literal}{js module='users' file='users-list.js' return='path'}{literal}",
					function(){
						users_list = new usersList({
							siteUrl: '{/literal}{$site_url}{literal}',
							viewUrl: '{/literal}{seolink module='users' method='search'}{literal}',
							viewAjaxUrl: 'ajax_search',
							listBlockId: 'main_users_results',
							tIds: ['pages_block_1', 'pages_block_2', 'sorter_block']
						});
					},
					'users_list'
				);
			});
		{/literal}</script>		
	</div>
</div>
{include file="footer.tpl"}
