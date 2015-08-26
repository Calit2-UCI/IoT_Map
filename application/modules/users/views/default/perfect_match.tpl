{include file="header.tpl"}
<h1>{seotag tag='header_text'}</h1>

<div class="pos-rel">
	{start_search_form type='full' show_data='1' object='perfect_match'}
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
						viewUrl: '{/literal}{seolink module='users' method='perfect_match'}{literal}',
						viewAjaxUrl: 'ajax_perfect_match',
						listBlockId: 'main_users_results',
						tIds: ['pages_block_1', 'pages_block_2', 'sorter_block']
					});
				},
				'users_list'
			);
		});
	{/literal}</script>		
</div>

{include file="footer.tpl"}
