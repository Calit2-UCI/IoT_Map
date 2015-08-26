{if $policy_page_gid}
	{capture assign='cookie_policy_link'}
		{block name='get_page_link' module='content' page_gid=$policy_page_gid}
	{/capture}
{/if}
<div class="mod-cookie-policy noPrint" id="cookie_policy_block">
	<div class="content">
		<ins id="cookie_policy_close" class="fright icon-remove icon-2x"></ins>
		{l i='text_cookie_policy' gid='cookie_policy' link=$cookie_policy_link}
	</div>
</div>
{js module=cookie_policy file='cookie_policy.js'}
{literal}<script>
	$(function(){
		new cookiePolicy({
			siteUrl: '{/literal}{$site_root}{literal}',
			domain: '{/literal}{$cookie_site_server}{literal}',
			path: '{/literal}{$cookie_site_server}{literal}',
		});
	});
</script>{/literal}
